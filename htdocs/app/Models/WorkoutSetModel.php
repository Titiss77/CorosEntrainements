<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkoutSetModel extends Model
{
    protected $table            = 'workout_sets';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'workout_id', 
        'exercise_id', 
        'set_number', 
        'reps', 
        'weight'
    ];

    public function getTotalVolumePerWorkout(): array
    {
        $sql = "
            SELECT 
                DATE_FORMAT(sw.date, '%Y-%m-%d') as workout_date,
                SUM(ws.reps * ws.weight) as total_volume_kg
            FROM strength_workouts sw
            JOIN workout_sets ws ON sw.id = ws.workout_id
            GROUP BY sw.id
            ORDER BY sw.date ASC
        ";
        
        return $this->db->query($sql)->getResultArray();
    }

    public function getExerciseEvolution(int $exerciseId): array
    {
        $sql = "
            SELECT 
                DATE_FORMAT(sw.date, '%Y-%m-%d') as workout_date,
                MAX(ws.weight) as max_weight_lifted
            FROM strength_workouts sw
            JOIN workout_sets ws ON sw.id = ws.workout_id
            WHERE ws.exercise_id = ?
            GROUP BY sw.id
            ORDER BY sw.date ASC
        ";
        
        return $this->db->query($sql, [$exerciseId])->getResultArray();
    }

    // NOUVELLE MÉTHODE : Récupère tous les détails d'une séance
    public function getWorkoutDetails(int $workoutId): array
    {
        $sql = "
            SELECT 
                ws.set_number, ws.reps, ws.weight,
                e.id as exercise_id, e.name as exercise_name, e.muscle_group
            FROM workout_sets ws
            JOIN exercises e ON ws.exercise_id = e.id
            WHERE ws.workout_id = ?
            ORDER BY ws.exercise_id, ws.set_number ASC
        ";
        return $this->db->query($sql, [$workoutId])->getResultArray();
    }
}