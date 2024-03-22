<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return view('user/index');
    }

    public function datatable(Request $request)
    {
        $currentPage = $request->start == 0 ? 1 : ($request->start / 10) + 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $data = $this->userRepository->getAllUsers();
        $data->transform(function($item){
            return [
                "{$item->Name['first']} {$item->Name['last']}",
                $item->age,
                $item->Gender,
                $item->created_at,
                ""
            ];
        });

        $recordsFiltered = $data->total();
        $recordsTotal = $data->total();

        return [
            'data'  => $data->getCollection(),
            'recordsFiltered'  => $recordsFiltered,
            'recordsTotal'  => $recordsTotal,
        ];
    }
}
