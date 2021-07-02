<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Repositories\CreditsCardsRepository;

class CreditCardService
{
    protected $repository;
    protected $userService;
    protected $carbon;
    protected $photoRepository;
    protected $companyService;

    public function __construct(
        CreditsCardsRepository $repository,
        Carbon $carbon,
        CompanyService $companyService
    ) {
        $this->repository = $repository;
        $this->carbon = $carbon;
        $this->companyService = $companyService;
    }

    public function search($request)
    {
        $filterColumns = $this->makeParamsSearch($request);

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    private function makeParamsSearch($request)
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        return  $filterColumns;
    }

    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function save($request)
    {
        if ($request->validated()) {
            $card = Arr::get($request, "id");

            if ($card) {
                if (!$this->checkCompany($card)) {
                    return response('Sem permissão para essa empresa', 422);
                }
            }

            $response = $this->repository->updateOrCreate(["id" => $card], $request->all());

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }

            return redirect()->back()->with('message', 'Ocorreu algum erro');
        }
    }

    public function delete($request)
    {
        $card = Arr::get($request, "id");

        if (!$this->checkCompany($card)) {
            return response('Sem permissão para essa empresa', 422);
        }
        $albumFind =  $this->repository->find($card);
        $this->uploadPlugin->remove(Arr::get($albumFind, "background"));

        $response = $this->repository->delete($card);
        if ($response) {
            return response("Removido com sucesso", 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    private function checkCompany($card)
    {
        if ($card) {
            $companyId = Auth::user()->company_id;
            $companySale = $this->repository->find($card);
            if ($companyId != Arr::get($companySale, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
