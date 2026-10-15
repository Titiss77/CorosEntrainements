<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCorosActivities extends Migration
{
    public function up() : void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'coros_activity_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
                'null'       => true,
            ],
            'sport' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
            ],
            'started_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'duration_seconds' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'distance_meters' => [
                'type'     => 'DECIMAL',
                'constraint' => '10,2',
                'null'     => true,
            ],
            'avg_heart_rate' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'max_heart_rate' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'elevation_gain_m' => [
                'type'     => 'DECIMAL',
                'constraint' => '8,2',
                'null'     => true,
            ],
            'avg_speed_mps' => [
                'type'     => 'DECIMAL',
                'constraint' => '8,3',
                'null'     => true,
            ],
            'avg_pace_seconds_per_km' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'raw_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'synced_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('coros_activity_id');
        $this->forge->addKey('started_at');
        $this->forge->addKey('sport');
        $this->forge->createTable('coros_activities', true);
    }

    public function down() : void
    {
        $this->forge->dropTable('coros_activities', true);
    }
}
