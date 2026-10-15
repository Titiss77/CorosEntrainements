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

    /**
     * Calcule le tonnage global (Reps x Poids) pour chaque séance
     */
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

    /**
     * Récupère la charge maximale soulevée sur un exercice donné pour chaque séance
     */
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
}