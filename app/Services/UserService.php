<?php

namespace App\Services;

use App\Library\Format;
use App\Library\Upload;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ActiveCompany;

class UserService
{
    protected $repository;

    /**
     * Cria instancia de Serviço
     *
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $repository,
        Upload $uploadPlugin

    ) {
        $this->repository = $repository;
        $this->uploadPlugin = $uploadPlugin;
    }

    public function search($request)
    {
        $filterColumns = $this->makeParamsFilter($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    public function get($request)
    {
        $filterColumns = $this->makeParamsFilter($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->get();
    }

    public function list($request)
    {
        $filterColumns = $this->makeParamsFilter($request);
        return $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });
    }

    private function makeParamsFilter($request)
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];

        if (Arr::get($request, 'search_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_name') . '%']);
        }

        array_push($filterColumns, ['id', "!=", Auth::user()->id]);
        return  $filterColumns;
    }

    /**
     * Procura por usuário
     *
     * @param [type] $UserId
     * @return void
     */
    public function find($userId)
    {
        return $this->repository->find($userId)->toArray();
    }

    public function save($request, $register = false, $updateAccount = false)
    {
        $userId = Arr::get($request, "id", false);
        
        if ($updateAccount) {
            $userId = Auth::user()->id;
        }

        if ($userId) {
            if (!$this->checkCompany($userId)) {
                return redirect()->back()->with('message', 'Sem permissão para essa empresa');
            }
            $request = $this->verifyUpdate($request);
        }

        if (!$userId && !$register) {
            $request['password'] = bcrypt(Arr::get($request, "password"));
        }

        $admin =  Arr::get($request, "admin", false);
        $request['admin'] = false;

        if ($admin) {
            $request['admin'] = true;
        }

        if ($updateAccount) {
            unset($request['admin']);
        }

        if ($register) {
            return $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request);
        }

        $old = [];
        if ($userId) {
            $old =  $this->repository->find($userId);
        }

        $request['company_id'] = Auth::user()->company_id;
        $response = $this->repository->updateOrCreate(["id" => Arr::get($request, "id")], $request->all());
        $response = $this->addPhoto($request, $response, 'photo');

        if ($userId && Arr::get($old, "email") == $response->email) {
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }
        
        $response['token_active'] = mt_rand(100000, 999999);
        $response['active'] = 0;
        $response->save();

        //TODO - REMOVER 
        //$user->notify(new ActiveCompany($user->name, $user->token_active));

        if ($response) {
            return redirect()->back()->with('message', 'Registro criado/atualizado! É necessário que o usuário valide o email');
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    private function addPhoto($request, $response, $property)
    {
        $foto = $request->file($property);
        $companyId = Auth::user()->company_id;
        $id = Arr::get($response, "id");
        $path = "photos/company/$companyId/user/$id/$property";

        if ($path && $foto) {
            $pathPhoto = $this->uploadPlugin->upload($foto, $path);
            if (!$pathPhoto) {
                return;
            }

            $response[$property] = $pathPhoto;
            $response->save();
        }

        return $response;
    }

    public function active($request)
    {
        $user = $this->repository->find(Auth::user()->id);
        $userToken = $user['token_active'];

        $tokenRequest = Format::extractNumbers(Arr::get($request, 'token'));

        if ($userToken == $tokenRequest) {
            $user->active = 1;
            return $user->save();
        }

        return response('Token inválido, tente novamente', 422);
    }


    /**
     * Remove password, caso atualização
     *
     * @param [type] $request
     * @return void
     */
    private function verifyUpdate($request)
    {
        unset($request['password']);
        return $request;
    }

    public function checkCompany($userId)
    {
        $companyId = Auth::user()->company_id;

        if ($userId) {
            $client = $this->repository->find($userId);

            if ($companyId != Arr::get($client, "company_id")) {
                return false;
            }
        }

        return true;
    }

    public function delete($request)
    {
        $userId = Arr::get($request, "id");
        $userFind =  $this->repository->find($userId);

        if ($userId == Auth::user()->id) {
            return response('Você não pode deletar o seu usuário', 422);
        }

        if (Arr::get($userFind, "company_id") == Auth::user()->company_id) {
            $foto = Arr::get($userFind, "photo");
            if ($foto) {
                $this->uploadPlugin->remove($foto);
            }

            $response = $this->repository->delete($userId);

            if ($response) {
                return response('Removido com sucesso', 200);
            }
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function deletePhoto($request)
    {
        $userId = Arr::get($request, "id");
        $userFind =  $this->repository->find($userId);

        if (Arr::get($userFind, 'company_id') == Auth::user()->company_id) {
            $response = $this->uploadPlugin->remove(Arr::get($userFind, "photo"));
            if(!$response){
                \Log::info("Tentativa de remover foto bucket deu erro:".Arr::get($userFind, "photo"));
            }
            $userFind['photo'] = null;
            $userFind->save();
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }
}
