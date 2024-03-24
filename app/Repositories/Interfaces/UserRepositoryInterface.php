<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface {
    public function updateOrCreateUser($value);
    public function getUserCountByGender($date);
    public function getAverageAgeUsersByGender($date);
    public function getAllUsers($search);
    public function delete($id);
    public function find($id);
}