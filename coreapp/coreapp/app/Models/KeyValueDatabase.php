<?php

namespace App\Models;

use CodeIgniter\Model;

class KeyValueDatabaseException extends \Exception{
}

class KeyValueDatabase extends Model
{
    //TODO: codeigniter model validation
    protected $table = 'kv';
    protected $allowedFields = ['k', 'v', 'username', 'app', 'updated_at'];

    public function create($key, $value, $username, $app){
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

    public function read($key, $app){
        $results = $this->getWhere(["k"=>$key, "app"=>$app]);
        if($results->getNumRows() < 1){
            throw new KeyValueDatabaseException("Unable to find item with key $key and app $app");
        } else{
            return $results->getRow();
        }
    }

    public function upsert($key, $app, $username, $value){
        $data = [
            'k' => $key,
            'app' => $app,
            'v'    => $value,
            'username' => $username,
            'updated_at' => time()
        ];
        $results = $this->getWhere(["k"=>$key, "app"=>$app]);
        if($results->getNumRows() < 1){
            try{
                $this->save($data);
            } catch(\Exception $e){
                throw new KeyValueDatabaseException($e->getMessage());
            }
        } else{
            try {
                $this->where('k', $key)
                    ->where('app', $app)
                    ->set($data)
                    ->update();
            } catch(\Exception $e){
                throw new KeyValueDatabaseException($e->getMessage());
            }
        }
    }

    public function remove($key, $app){
        try {
            $this->where('k', $key)
                ->where('app', $app)
                ->delete();
        } catch(\Exception $e){
            throw new KeyValueDatabaseException($e->getMessage());
        }
    }

}