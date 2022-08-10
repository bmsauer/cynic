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
    //protected $namespace   = ''; //use real migrations on test database
    
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

    public function test_authenticate(){
        $body = json_encode(['jwt' => 'bad_jwt']);
        $request = new \CodeIgniter\HTTP\IncomingRequest(
            new \Config\App(),
            new \CodeIgniter\HTTP\URI('http://example.com'),
            null,
            new \CodeIgniter\HTTP\UserAgent()
        );
        $request->setHeader("Content-Type", "application/json");
        $request->setBody($body);
        $result = $this->withRequest($request)->controller(\App\Controllers\Auth::class)->execute('authenticate');
        $result->assertStatus(401);
    }

   
}
