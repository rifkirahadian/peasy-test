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

    public function getUserCountByGender($date)
    {
        return $this->model->select('Gender', DB::raw('count(*) as count'))
                    ->whereDate('created_at', $date)
                    ->groupBy('Gender')->get();
    }

    public function getAverageAgeUsersByGender($date)
    {
        return $this->model->select('Gender', DB::raw('avg(age) as avg_age'))
                    ->whereDate('created_at', $date)
                    ->groupBy('Gender')->get();
    }

    public function getAllUsers($search)
    {
        $fullNameQuery = "TRIM('\"' FROM CONCAT(\"Name\"->>'first', ' ', \"Name\"->>'last'))";
        $query = $this->model->select(
            'id',
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

    public function delete($id)
    {
        $this->model->where('id', $id)->delete();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
}