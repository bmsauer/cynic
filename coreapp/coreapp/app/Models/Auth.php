<?php

namespace App\Models;

use CodeIgniter\Model;

class Auth extends Model
{
    protected $table = 'auth';
    protected $allowedFields = ['username', 'password', 'created_at'];

    public function getAllUsers(){
        return $this->findAll();
    }

    public function getUser($username)
    {
        return $this->where(['username' => $username])->first();
    }

    public function createUser($username, $password){
        $data = [
            'username' => $username,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
        ];
        $this->save($data);
    }

}