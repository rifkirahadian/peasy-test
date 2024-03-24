<?php
namespace App\Repositories\Interfaces;

Interface DailyRecordRepositoryInterface {
    public function create($date, $data);
    public function getAllDailyRecord();
}