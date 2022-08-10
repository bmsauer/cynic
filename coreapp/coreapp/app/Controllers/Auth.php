<?php

namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;
use App\Libraries\CynicJWT;

class Auth extends BaseController
{
    
    public function index()
    {
        return view("header") . view("authHome") . view("footer");
    }

    public function signup_page()
    {
        return view("header") . view("authSignUp") . view("footer");
    }

    public function signup()
    {
        if($this->validate([
            'username' => 'required|alpha_numeric|min_length[3]|max_length[128]|is_unique[auth.username]',
            'password' => 'required|min_length[3]|max_length[128]|matches[confirm_password]',
        ])){
            $model = model(App\Models\Auth::class);
            $model->createUser($this->request->getPost('username'), $this->request->getPost('password'));
            return redirect()->to('/auth/signup')->with('message', 'Successful Signup.');
        } else {
            return view("header") . view("authSignUp") . view("footer");
        }
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
                    'jwt'=> CynicJWT::generate_jwt($user['username'], $user['role'])
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

        try {
            $token = CynicJWT::decode_jwt($jwt);
        } catch (\Exception $e){
            $data = [
                 'success' => false,
                 'jwt' => ''
             ];
             $status_code = 401;
             return $this->response->setStatusCode($status_code)->setJSON($data);
        }

        
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
                'jwt'=> CynicJWT::generate_jwt($token->username, $token->role)
             ];
            $status_code = 200;
        }
        return $this->response->setStatusCode($status_code)->setJSON($data);
    }
        

   
}