<?php

namespace App\Models;

use CodeIgniter\Model;

class StrengthWorkoutModel extends Model
{
    protected $table            = 'strength_workouts';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['date', 'notes'];

    public function getRecent(int $limit = 5): array
    {
        return $this->orderBy('date', 'DESC')->findAll($limit);
    }
}