<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\WorkoutSetModel;
use App\Models\StrengthWorkoutModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $corosModel = new ActivityModel();
        $strengthModel = new WorkoutSetModel();
        $workoutModel = new StrengthWorkoutModel();

        $data = [
            'title'           => 'Tableau de bord COROS & Musculation',
            'stats'           => $corosModel->getStats(),
            'activities'      => $corosModel->getRecent(8),
            'monthlyDistance' => $corosModel->getMonthlyDistance(6),
            'tonnageData'     => $strengthModel->getTotalVolumePerWorkout(),
            // On récupère les 5 dernières séances de musculation
            'recentWorkouts'  => $workoutModel->getRecent(5), 
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