<?php

namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;

class KeyValueDatabase extends BaseController
{
    public function put(){
        $request = service('request');
        $request_data = $request->getJSON(true);
        #TODO: validata input
        $key = $request_data["key"];
        $value = $request_data["value"];
        $app = $request_data["app"];
        $jwt = $request_data["jwt"];

        

        $model = model(App\Models\KeyValueDatabase::class);

        try{
            //TODO: add user from jwt
            $model->put($key, $value, "anonymous", $app);
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