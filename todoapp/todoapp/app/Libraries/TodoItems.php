<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

class TodoItemsException extends \Exception{
}

class TodoItems {
    /*
        username.all_ids
        username.item.<id>.title
        username.item.<id>.details
        username.item.<id>.date_added
        username.item.<id>.completed
    */
        
    private $username;
    private $jwt;
    
    public function __construct($username, $jwt){
        $this->username = $username;
        $this->jwt = $jwt;
        
        $config = new \Config\App();
        $options = [
                'baseURI' => $config->COREAPP_HOST,
                'timeout' => 3,
        ];
        $this->client = \Config\Services::curlrequest($options);
        
    }
    
    public function add($title, $details){
        //vars
        $uniqid = uniqid();
        $all_ids_array = [];
        $time = Time::now();
        $timestring = $time->toDateTimeString();
        
        //get list of all_ids from database
        $all_ids_array = $this->get_all_ids();
        $all_ids_array[] = $uniqid;
        
        //send fields        
        $this->update_item_field($uniqid, "title", $title);
        $this->update_item_field($uniqid, "details", $details);
        $this->update_item_field($uniqid, "date_added", $timestring);
        $this->update_item_field($uniqid, "completed", "false");
        //update_all_ids
        $this->update_all_ids($all_ids_array);  
    }
    
    public function complete($id){
        $this->update_item_field($id, "completed", "true");
    }
    
    public function get_all_items(){
        $all_ids_array = $this->get_all_ids();
        $all_items = array();
        foreach($all_ids_array as $id){
            $title = $this->get_item_field($id, "title");
            $details = $this->get_item_field($id, "details");
            $date_added = $this->get_item_field($id, "date_added");
            $completed = $this->get_item_field($id, "completed");
            $all_items[$id] = array(
                "title" => $title,
                "details" => $details,
                "date_added" => $date_added,
                "completed" => $completed,
             );
        }
        return $all_items;
    }
    
    private function get_all_ids(){
        $key = $this->username . ".all_ids";
        $response = $this->client->get("api/db/$key/todoapp", [
            'http_errors'=>false,
            'json'=>["jwt" => $this->jwt],
        ]);
        $response_body = $response->getBody();
        $response_code = $response->getStatusCode();
        
        if($response_code == 200){
          $response_body = json_decode($response_body, true);
          $all_ids = $response_body["datum"]["value"];
          if (strlen($all_ids)){
            return explode(" ", $all_ids);
          } else {
            return array();
          }
        } elseif($response_code == 400){
          //doesn't exist yet
          return array();
        } else{
          throw new TodoItemsException("Received status code $response_code from coreapp when retrieving all_ids");
        }
    }
    
    private function get_item_field($uniqid, $field){
        $key = $this->username . ".items.$uniqid.$field";
        $response = $this->client->get("api/db/$key/todoapp", [
            'http_errors'=>false,
            'json'=>[
                "jwt" => $this->jwt,   
            ],
        ]);
        $response_body = $response->getBody();
        $response_code = $response->getStatusCode();
        if($response_code != 200){
            throw new TodoItemsException("Received status code $response_code from coreapp when upserting $field.");
        }  else {
            $response_body_array = json_decode($response_body, true);
            return $response_body_array["datum"]["value"];
        }
    }
    
    private function update_item_field($uniqid, $field, $value) {
        $key = $this->username . ".items.$uniqid.$field";
        $response = $this->client->put("api/db/$key/todoapp", [
            'http_errors'=>false,
            'json'=>[
                "jwt" => $this->jwt,
                "value" => $value,    
            ],
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200){
            throw new TodoItemsException("Received status code $response_code from coreapp when upserting $field.");
        }
    }
    
    private function update_all_ids($all_ids){
        $key = $this->username . ".all_ids";
        $response = $this->client->put("api/db/$key/todoapp", [
            'http_errors'=>false,
            'json'=>[
                "jwt" => $this->jwt,
                "value" => implode(" ", $all_ids),    
            ],
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200){
            throw new TodoItemsException("Received status code $response_code from coreapp when upserting all_ids.");
        }   
    }
    
}