<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteUser;
use App\Repositories\Interfaces\DailyRecordRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    private $userRepository;
    private $dailyRecordRepository;

    public function __construct(UserRepositoryInterface $userRepository, DailyRecordRepositoryInterface $dailyRecordRepository)
    {
        $this->userRepository = $userRepository;
        $this->dailyRecordRepository = $dailyRecordRepository;
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

        $data = $this->userRepository->getAllUsers($request->search['value']);
        $data->transform(function($item){
            return [
                $item->fullname,
                $item->age,
                $item->Gender,
                $item->created_at,
                "<button type='button' class='btn btn-danger btn-sm' onclick='onDelete($item->id, \"$item->fullname\")'>Delete</button>"
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

    public function destroy($id)
    {
        DeleteUser::dispatch($id, $this->userRepository, $this->dailyRecordRepository);
    }
}
