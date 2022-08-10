<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\CynicJWT;


class CynicJWTTest extends CIUnitTestCase
{
     public function testgenerate_jwt() {       
         $jwt = CynicJWT::generate_jwt("testuser", "user");
         $this->assertIsString($jwt);
    }

}