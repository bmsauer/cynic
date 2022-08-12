<?php

namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;

class KeyValueDatabase extends BaseController
{
    public function create($key, $app){
        $request = service('request');
        $request_data = $request->getJSON(true);
        #TODO: validata input
        $value = $request_data["value"];
        $jwt = $request_data["jwt"];

        $model = model(App\Models\KeyValueDatabase::class);

        try{
            //TODO: set username from jwt, validate permissions
            $model->create($key, $value, "anonymous", $app);
            $data = [
                'success' => true,
            ];
            return $this->response->setStatusCode(200)->setJSON($data);
        } catch (\App\Models\KeyValueDatabaseException $e){
            $data = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $this->response->setStatusCode(400)->setJSON($data);
        }
    }

    public function read($key, $app){
        $request = service('request');
        $request_data = $request->getJSON(true);
        #TODO: validata input
        $jwt = $request_data["jwt"];
        
        //TODO: validate auth for jwt
        $model = model(App\Models\KeyValueDatabase::class);
        try{
            $result = $model->read($key, $app);
            $data = [
                "success" => true,
                "datum" => [
                    "key" => $result->k,
                    "value" => $result->v,
                    "app" => $result->app,
                    "username" => $result->username,
                    "updated_at" => $result->updated_at,
                ],
            ];
            return $this->response->setStatusCode(200)->setJSON($data);
        } catch(\App\Models\KeyValueDatabaseException $e){
            $data = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $this->response->setStatusCode(400)->setJSON($data);
        }
    }

    public function upsert($key, $app){
        $request = service('request');
        $request_data = $request->getJSON(true);
        #TODO: validata input
        $value = $request_data["value"];
        $jwt = $request_data["jwt"];

        $model = model(App\Models\KeyValueDatabase::class);

        try{
            //TODO: set username from jwt, validate permissions
            $model->upsert($key, $app, "anonymous", $value);
            $data = [
                'success' => true,
            ];
            return $this->response->setStatusCode(200)->setJSON($data);
        } catch (\App\Models\KeyValueDatabaseException $e){
            $data = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $this->response->setStatusCode(400)->setJSON($data);
        }
    }

    public function remove($key, $app){
        //TODO: check auth
        $request = service('request');
        $request_data = $request->getJSON(true);
        #TODO: validata input
        $jwt = $request_data["jwt"];

        $model = model(App\Models\KeyValueDatabase::class);

        try{
            //TODO: set username from jwt, validate permissions
            $model->remove($key, $app);
            $data = [
                'success' => true,
            ];
            return $this->response->setStatusCode(200)->setJSON($data);
        } catch (\App\Models\KeyValueDatabaseException $e){
            $data = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $this->response->setStatusCode(400)->setJSON($data);
        }
            
    }
}