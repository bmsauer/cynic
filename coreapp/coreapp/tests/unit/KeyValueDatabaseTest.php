<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\ControllerTestTrait;

class KeyValueDatabaseTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;
    
    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    //protected $namespace   = '';
    
    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'KeyValueDatabaseTestsSeeder';

    protected function setUp(): void
    {
        parent::setUp();
        $this->testRequest = new \CodeIgniter\HTTP\IncomingRequest(
            new \Config\App(),
            new \CodeIgniter\HTTP\URI('http://testuri'),
            null, //body
            new \CodeIgniter\HTTP\UserAgent()
        );
        $this->testRequest->setHeader("Content-Type", "application/json");
        // Do something here....
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->testRequest = NULL;
        // Do something here....
    }

    public function test_put_happy(){
        $this->testRequest->setBody(json_encode([
            'key'=>'mykey',
            'value'=>'myvalue',
            'app'=>'testapp',
            'jwt'=>'testjwt',
        ]));
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('put');
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json["success"]);
    }

}