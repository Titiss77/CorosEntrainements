<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\WorkoutSetModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $corosModel = new ActivityModel();
        $strengthModel = new WorkoutSetModel();

        // Par défaut, on va tracker l'exercice ID 1 (ex: Monopalme Squat ou Développé couché)
        // A terme, vous pourrez passer cet ID dynamiquement depuis un menu déroulant
        $trackedExerciseId = 1;

        $data = [
            'title'           => 'Tableau de bord COROS & Musculation',
            'stats'           => $corosModel->getStats(),
            'activities'      => $corosModel->getRecent(8),
            'monthlyDistance' => $corosModel->getMonthlyDistance(6),
            // Nouvelles données de musculation
            'tonnageData'     => $strengthModel->getTotalVolumePerWorkout(),
            'evolutionData'   => $strengthModel->getExerciseEvolution($trackedExerciseId),
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