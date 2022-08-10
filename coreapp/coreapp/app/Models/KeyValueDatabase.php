<?php

namespace App\Models;

use CodeIgniter\Model;

class KeyValueDatabaseException extends \Exception{
}

class KeyValueDatabase extends Model
{
    protected $table = 'kv';
    protected $allowedFields = ['k', 'v', 'username', 'app', 'updated_at'];

    public function put($key, $value, $username, $app){
        $data = [
            'k' => $key,
            'app' => $app,
            'v'    => $value,
            'username' => $username,
            'updated_at' => time()
        ];
        try{
            $this->save($data);
        } catch (\Exception $e){
            throw new KeyValueDatabaseException($e->getMessage());
        }

    }

}