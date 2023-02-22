<?php

namespace App\Controllers;

use App\Libraries\TodoItems;
use App\Libraries\TodoItemsException;
use App\Libraries\CookieAuthData;

class Home extends BaseController
{
    
    
    private function collectTodoItems($username, $jwt){
        $ti = \Config\Services::todoitems($username, $jwt);
        $items = $ti->get_all_items();
        return $items;
    }
    
    public function index()
    {
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        $data["items"] = $this->collectTodoItems($data["username"], $data["jwt"]);
        
        $total = 0;
        foreach($data["items"] as $id => $item){
            $total++;
        }
        
        $data["total_items"] = $total;
        return view('header', $data) . view('home', $data) . view('footer');
    }
    
    public function loginForm() {
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        return view('header',$data) . view('login') . view('footer');
    }
    
    public function login() {
        if($this->validate([
            'username' => 'required',
            'password' => 'required',
        ])){
            $config = new \Config\App();
            $body = [
                "username" => $this->request->getPost('username'),
                "password" => $this->request->getPost('password'),
            ];
            
            $options = [
                'baseURI' => $config->COREAPP_HOST,
                'timeout' => 3,
            ];
            $client = \Config\Services::curlrequest($options);
            try {
                $response = $client->post('api/auth/login', [
                    'debug'=>true,
                    'http_errors'=>false,
                    'json'=>$body,
                ]);
            } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
                return redirect()->to('/login')->with('error', 'Critical: HTTPException raised.');
            }
            
            $response_body = $response->getBody();
            $response_code = $response->getStatusCode();
            if($response_code == 200){     
                if (strpos($response->header('content-type'), 'application/json') !== false) {
                    $response_body = json_decode($response_body, true);
                    $jwt = $response_body["jwt"];
                    $username = $response_body["username"];
                    $role = $response_body["role"];
                    $cad = \Config\Services::cookieauthdata();
                    $cad->setCookieData($jwt, $username, $role, 3600);
                    return redirect()->to('/')->with('message', 'Successful login!')->withCookies();
                }  else {
                    //malformed response
                    return redirect()->to('/login')->with('error', 'Critical: Response from coreapp was not JSON.');
                }
            } elseif ($response_code == 401) {
                    return redirect()->to('/login')->with('error', 'User was not authenticated.  Check your username and password and try again.');  
            } else {
                return redirect()->to('/login')->with('error', 'Unknown error from coreapp, status code: ' + $response_code);
            }
        } else {
            return view("header") . view("login") . view("footer");
        }
    }
    
    public function logoutForm(){
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        return view('header', $data) . view('logout') . view('footer');
    }
    
    
    public function logout(){
        $cad = \Config\Services::cookieauthdata();
        $cad->setCookieData('', '', '', 0);
        return redirect()->to('/')->with('message', 'Successful logout!')->withCookies();
    }
    
    public function addForm(){
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        return view('header', $data) . view('add') . view('footer');
    }
    
    public function add(){
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        if($this->validate([
            'title' => 'required',
        ])){      
            $title = $this->request->getPost('title');
            $details = $this->request->getPost('details');
            $ti = \Config\Services::todoitems($data["username"], $data["jwt"]);
            try{
                $ti->add($title, $details);
                return redirect()->to('/')->with('message', 'Successful adding todo item!')->withCookies();
            } catch (TodoItemsException $e){
                return redirect()->to('/')->with('error', 'Critical: Failed to add item: '. $e->getMessage());   
            }
        } else {
            return view('header', $data) . view('add') . view('footer');
        }
        
    }
    
    public function complete(){
        $cad = \Config\Services::cookieauthdata();
        $data = $cad->getCookieData();
        $id = $this->request->getPost('id');
        $ti = \Config\Services::todoitems($data["username"], $data["jwt"]);
        $ti->complete($id);
        return redirect()->to('/')->with('message', 'Successful completing todo item!')->withCookies();
    }
}
