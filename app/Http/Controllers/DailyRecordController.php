<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DailyRecordRepositoryInterface;
use Illuminate\Http\Request;

class DailyRecordController extends Controller
{
    private $dailyRecordRepository;

    public function __construct(DailyRecordRepositoryInterface $dailyRecordRepository)
    {
        $this->dailyRecordRepository = $dailyRecordRepository;
    }

    public function datatable()
    {
        $data = $this->dailyRecordRepository->getAllDailyRecord();
        $data->transform(function($item) {
            return [
                date('d F Y', strtotime($item->date)),
                $item->male_count,
                $item->female_count,
                number_format($item->male_avg_age, 2),
                number_format($item->female_avg_age, 2)
            ];
        });

        return compact('data');
    }
}
