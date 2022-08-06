<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        /*
        $db = \Config\Database::connect();
        $query   = $db->query('SELECT k,v from kv;');
        $results = $query->getResult();

        foreach ($results as $row) {
            echo $row->title;
            echo $row->name;
            echo $row->email;
        }

        echo 'Total Results: ' . count($results);
        
        $config = new \Config\App();
        $data["bu"] = $config->baseURL;
       
        return view('home', $data);
        */
        return view('header') . view('home') . view('footer');
    }
}
