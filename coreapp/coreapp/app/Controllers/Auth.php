<?php

namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    
    private function generate_jwt($username){
        $secretKey = getenv('SECRET_KEY');
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+1 hour')->getTimestamp();
        $serverName = "cynic";

        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $serverName,                       // Issuer
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expires in 1 hour
            'userName' => $username,                     // User name
        ];
        return JWT::encode($data, $secretKey, 'HS512');

    }
    
    public function index()
    {
        $model = model(App\Models\Auth::class);
        $allUsers = $model->getAllUsers();
        foreach($allUsers as $user){
            echo $user["username"] . "<br/>";
            echo $user["password"] . "<br/>";
            echo $user["created_at"] . "<br/>";
            echo "----<br/>";
        }
        echo "done";
        return view("home");
    }

    public function login()
    {
        $request = service('request');
        $request_data = $request->getJSON(true);
        $submitted_username = $request_data["username"];
        $submitted_password = $request_data["password"];
        #TODO: validate input here

        
        $model = model(App\Models\Auth::class);
        $user = $model->getUser($submitted_username);

        $data = array();
        $status_code = 200;
        if($user === NULL){
             $data = [
                 'success' => false,
                 'jwt' => ''
             ];
             $status_code = 401;
        } else {
            if(password_verify($submitted_password, $user['password'])){
                $data = [
                    'success' => true,
                    'jwt'=> $this->generate_jwt($user['username'])
                ];
                $status_code = 200;
            } else {
                 $data = [
                     'success' => false,
                     'jwt' => ''
                 ];
                 $status_code = 401;
            }
        }
        return $this->response->setStatusCode($status_code)->setJSON($data);
    }

    public function authenticate()
    {
        $request = service('request');
        $request_data = $request->getJSON(true);
        $jwt = $request_data["jwt"];
        #TODO: validate input here

        $secretKey = getenv('SECRET_KEY');
        $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
        $now = new \DateTimeImmutable();
        $serverName = "cynic";

        $status_code = 200;
        $data = array();

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
             $data = [
                 'success' => false,
                 'jwt' => ''
             ];
             $status_code = 401;
        } else {
            $data = [
                'success' => true,
                'jwt'=> $this->generate_jwt($token->userName)
             ];
            $status_code = 200;
        }
        return $this->response->setStatusCode($status_code)->setJSON($data);
    }
        

   
}