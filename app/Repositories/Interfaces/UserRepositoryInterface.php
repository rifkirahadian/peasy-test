<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface {
    public function updateOrCreateUser($value);
    public function getUserCountByGender();
    public function getAverageAgeUsersByGender();
}