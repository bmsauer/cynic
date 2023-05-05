<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FlushKV extends Seeder
{
    public function run()
    {
        // Using Query Builder
        $this->db->table('kv')->emptyTable();
    }
}