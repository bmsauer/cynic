<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

class CookieAuthDataException extends \Exception{
}

class CookieAuthData {
    public function getCookieData() : array{
        helper('cookie');
        $jwt = get_cookie('jwt');
        $username = get_cookie('username');
        $role = get_cookie('role');
        
        $data = [
            "jwt" => $jwt,
            "username" => $username,
            "role" => $role
        ];
        return $data;
    }
    
    public function setCookieData($jwt, $username, $role, $newexpire) : void {
        helper('cookie');
        set_cookie('jwt', $jwt, $expire=$newexpire);
        set_cookie('username', $username, $expire=$newexpire);
        set_cookie('role', $role, $expire=$newexpire);
    }
}