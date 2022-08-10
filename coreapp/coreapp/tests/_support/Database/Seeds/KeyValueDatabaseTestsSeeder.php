<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KeyValueDatabaseTestsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'k' => 'foo',
            'v' => 'bar',
            'app' => 'testapp',
            'username' => 'testuser'
        ];

        // Using Query Builder
        $this->db->table('kv')->insert($data);
    }
}