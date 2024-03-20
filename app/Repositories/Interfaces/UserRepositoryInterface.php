<?php
namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface{
    
    public function updateOrCreateUser($value);
}