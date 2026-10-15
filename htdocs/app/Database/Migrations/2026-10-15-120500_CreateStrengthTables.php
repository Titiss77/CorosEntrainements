<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStrengthTables extends Migration
{
    public function up(): void
    {
        // Table: exercises (Catalogue d'exercices)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'muscle_group' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('exercises', true);

        // Table: strength_workouts (Les séances globales)
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'date'  => ['type' => 'DATETIME'],
            'notes' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('strength_workouts', true);

        // Table: workout_sets (Les séries de chaque séance)
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'workout_id'  => ['type' => 'INT', 'unsigned' => true],
            'exercise_id' => ['type' => 'INT', 'unsigned' => true],
            'set_number'  => ['type' => 'INT', 'unsigned' => true],
            'reps'        => ['type' => 'INT', 'unsigned' => true],
            'weight'      => ['type' => 'DECIMAL', 'constraint' => '6,2'],
        ]);
        $this->forge->addKey('id', true);
        // Contraintes de clés étrangères (optionnel mais recommandé)
        $this->forge->addForeignKey('workout_id', 'strength_workouts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('exercise_id', 'exercises', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('workout_sets', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('workout_sets', true);
        $this->forge->dropTable('strength_workouts', true);
        $this->forge->dropTable('exercises', true);
    }
}