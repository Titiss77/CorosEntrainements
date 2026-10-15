<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FirstSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'debut' => '1ere migration',
        ];
        $this->db->table('first')->insert($data);
    }
}
