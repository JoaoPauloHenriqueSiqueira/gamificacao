<?php

namespace App\Services;

use App\Library\Format;
use App\Library\Upload;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CompanyService
{
    protected $repository;

    /**
     * Cria instancia de Serviço
     *
     * @return void
     */
    public function __construct(
        CompanyRepositoryInterface $repository,
        Upload $uploadPlugin

    ) {
        $this->repository = $repository;
        $this->uploadPlugin = $uploadPlugin;
    }

    /**
     * Pagina Usuários
     *
     * @return void
     */
    public function get()
    {
        return $this->repository->paginate(6);
    }

    public function find($userId)
    {
        return $this->repository->find($userId)->toArray();
    }

    public function searchField($search)
    {
        $list =  $this->repository->scopeQuery(function ($query) use ($search) {
            return $query->where($search);
        });

        $list =  $list->first();

        if ($list) {
            return $list->toArray();
        }

        return $list;
    }

    public function findCompany($id)
    {
        return $this->repository->find($id);
    }

    public function isApi()
    {
        $company = $this->repository->find(Auth::user()->company_id);
        return $company->is_api;
    }


    public function save($request, $new = false)
    {
        if ($new) {
            return $this->repository->updateOrCreate($request);
        }

        if (Arr::exists($request->all(), "name")) {
            $request['name'] = str_replace(' ', '_', trim($request['name']));
        }
        
        $chat = false;
        if (Arr::exists($request->all(), "chat")) {
            $chat = true;
        }
        $request['chat'] = $chat;

        $response = $this->repository->updateOrCreate(["id" => Auth::user()->company_id], $request->all());

        $this->addPhoto($request, $response, 'logo');
        $this->addPhoto($request, $response, 'background_default');

        if ($response) {
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    private function addPhoto($request, $response, $property)
    {
        $foto = $request->file($property);
        $companyId = Auth::user()->company_id;
        $path = "photos/company/$companyId/$property";

        if ($path && $foto) {
            $pathPhoto = $this->uploadPlugin->upload($foto, $path);
            if (!$pathPhoto) {
                return;
            }

            $company = $this->repository->find(Auth::user()->company_id);
            $company[$property] = $pathPhoto;
            $company->save();
        }
    }

    public function deleteLogo()
    {
        $company = $this->repository->find(Auth::user()->company_id);
        $this->uploadPlugin->remove(Arr::get($company, "logo"));

        $company->logo = null;
        $response = $company->save();

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function deleteBackground()
    {
        $company = $this->repository->find(Auth::user()->company_id);
        $this->uploadPlugin->remove(Arr::get($company, "background_default"));

        $company->background_default = null;
        $response = $company->save();

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function checkCompany($productId, $photo = false, $companyId = false)
    {
        if ($productId) {
            if (!$companyId) {
                $companyId = Auth::user()->company_id;
            }

            if ($photo) {
                return $this->photoRepository->find($productId);
            }

            if (!$photo) {
                $product = $this->repository->where("id", $productId)->where("company_id", $companyId);

                if ($product->count() <= 0) {
                    return false;
                }
            }

            if ($companyId != Arr::get($product->first(), "company_id")) {
                return false;
            }
        }

        return true;
    }


    /**
     * Deleta usuário
     *
     * @param [type] $request
     * @return void
     */
    public function delete($request)
    {
        $userId = Arr::get($request, "id");
        $response = $this->repository->delete($userId);

        if ($response) {
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }
}
