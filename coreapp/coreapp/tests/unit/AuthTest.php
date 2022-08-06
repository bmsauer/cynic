<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\ControllerTestTrait;

class AuthTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;
    
    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    //protected $namespace   = 'Tests\Support';
    
    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'AuthTestsSeeder';

    protected function setUp(): void
    {
        parent::setUp();

        // Do something here....
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Do something here....
    }

    public function testGenerate_jwt() {
        $controller = new \App\Controllers\Auth();
        // Get the invoker for the 'privateMethod' method.
        $method = $this->getPrivateMethodInvoker($controller, 'generate_jwt');
        
        //$jwt = $this->controller(\App\Controllers\Auth::class)->execute("generate_jwt", "testuser", "user");
        $jwt = $method("testuser", "user");
        $this->assertIsString($jwt);
    }
}
