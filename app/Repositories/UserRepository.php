<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

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
}