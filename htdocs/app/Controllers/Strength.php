<?php namespace App\Controllers;

use App\Models\StrengthWorkoutModel;
use App\Models\WorkoutSetModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Strength extends BaseController
{
    // Affiche le détail d'une séance (liste des exos et séries)
    public function workout($id = null)
    {
        if ($id === null || !is_numeric($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $workoutModel = new StrengthWorkoutModel();
        $workout = $workoutModel->find((int)$id);

        if (!$workout) {
            throw PageNotFoundException::forPageNotFound();
        }

        $setModel = new WorkoutSetModel();
        $rawSets = $setModel->getWorkoutDetails((int)$id);

        // Grouper les séries par exercice pour un affichage propre
        $exercises = [];
        foreach ($rawSets as $set) {
            $exId = $set['exercise_id'];
            if (!isset($exercises[$exId])) {
                $exercises[$exId] = [
                    'id'           => $exId,
                    'name'         => $set['exercise_name'],
                    'muscle_group' => $set['muscle_group'],
                    'sets'         => []
                ];
            }
            $exercises[$exId]['sets'][] = $set;
        }

        return view('strength/workout', [
            'title'     => 'Séance du ' . date('d/m/Y', strtotime($workout['date'])),
            'workout'   => $workout,
            'exercises' => $exercises
        ]);
    }

    // Affiche le graphique de progression d'un exercice précis
    public function exercise($id = null)
    {
        if ($id === null || !is_numeric($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $exercise = $db->table('exercises')->where('id', (int)$id)->get()->getRowArray();

        if (!$exercise) {
            throw PageNotFoundException::forPageNotFound();
        }

        $setModel = new WorkoutSetModel();
        $evolution = $setModel->getExerciseEvolution((int)$id);

        return view('strength/exercise', [
            'title'         => 'Progression : ' . $exercise['name'],
            'exercise'      => $exercise,
            'evolutionData' => $evolution
        ]);
    }
}