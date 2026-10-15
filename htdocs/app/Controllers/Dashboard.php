<?php

namespace App\Controllers;

use App\Models\ActivityModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new ActivityModel();

        $data = [
            'title' => 'Tableau de bord COROS',
            'stats' => $model->getStats(),
            'activities' => $model->getRecent(8),
            'monthlyDistance' => $model->getMonthlyDistance(6),
        ];

        return view('dashboard/index', $data);
    }

    public function activities()
    {
        $model = new ActivityModel();

        return view('dashboard/activities', [
            'title' => 'Activités COROS',
            'activities' => $model->getRecent(100),
        ]);
    }
}
