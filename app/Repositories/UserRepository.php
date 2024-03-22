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

    public function getUserCountByGender()
    {
        return $this->model->select('Gender', DB::raw('count(*) as count'))->groupBy('Gender')->get();
    }

    public function getAverageAgeUsersByGender()
    {
        return $this->model->select('Gender', DB::raw('avg(age) as avg_age'))->groupBy('Gender')->get();
    }

    public function getAllUsers($search)
    {
        $fullNameQuery = "TRIM('\"' FROM CONCAT(\"Name\"->>'first', ' ', \"Name\"->>'last'))";
        $query = $this->model->select(
            DB::raw("$fullNameQuery AS fullName"), 
            'Gender',
            'age',
            'created_at'
        );
        if ($search) {
            $query = $query->where(DB::raw($fullNameQuery), 'ilike', "%$search%");
        }
        return $query->paginate(10);
    }
}