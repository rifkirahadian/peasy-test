<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function updateOrCreateUser($value)
    {
        return $this->model->updateOrCreate(['uuid' => $value['login']['uuid']], [
            'Gender'    => $value['gender'],
            'Name'      => $value['name'],
            'Location'  => $value['location'],
            'age'       => $value['dob']['age']
        ]);
    }

    public function getUserByGender()
    {
        return $this->model->select('Gender', DB::raw('count(*) as count'))->groupBy('Gender')->get();
    }
}