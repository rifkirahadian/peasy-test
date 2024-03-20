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

    public function updateOrCreateUser($uuid, $data)
    {
        return $this->model->updateOrCreate(['uuid' => $uuid], $data);
    }
}