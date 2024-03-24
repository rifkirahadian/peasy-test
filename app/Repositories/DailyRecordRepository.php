<?php
namespace App\Repositories;

use App\Models\DailyRecord;
use App\Repositories\Interfaces\DailyRecordRepositoryInterface;

class DailyRecordRepository implements DailyRecordRepositoryInterface
{
    protected $model;

    public function __construct(DailyRecord $model)
    {
        $this->model = $model;
    }

    public function create($date, $data)
    {
        $this->model->updateOrCreate([
            'date' => $date
        ], $data);
    }
}
