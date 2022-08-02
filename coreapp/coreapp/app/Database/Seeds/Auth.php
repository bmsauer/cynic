<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Auth extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'brian',
            'password'    => password_hash('brianspassword', PASSWORD_DEFAULT),
        ];

        // Using Query Builder
        $this->db->table('auth')->insert($data);
    }
}