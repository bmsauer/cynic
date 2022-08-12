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

    public function test_create_happy(){
        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue',
            'jwt'=>'testjwt',
        ]));
        
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json["success"]);
    }

    public function test_create_duplicate(){
        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue',
            'jwt'=>'testjwt',
        ]));
        
        $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");
        $result->assertStatus(400);
        $json = json_decode($result->getJSON(), true);
        $this->assertFalse($json["success"]);
    }

    public function test_read_happy(){
        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue',
            'jwt'=>'testjwt',
        ]));
        $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('read', "mykey", "testapp");
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json["success"]);
        $this->assertEquals("myvalue", $json["datum"]["value"]);
        $this->assertEquals("anonymous", $json["datum"]["username"]);
    }

    public function test_upsert_duplicate(){
        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue',
            'jwt'=>'testjwt',
        ]));
        $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");

        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue2',
            'jwt'=>'testjwt',
        ]));
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('upsert', "mykey", "testapp");
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json["success"]);

    }

     public function test_remove_happy(){
        $this->testRequest->setBody(json_encode([
            'value'=>'myvalue',
            'jwt'=>'testjwt',
        ]));
        $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('create', "mykey", "testapp");

        $this->testRequest->setBody(json_encode([
            'jwt'=>'testjwt',
        ]));
        $result = $this->withRequest($this->testRequest)->controller(\App\Controllers\KeyValueDatabase::class)->execute('remove', "mykey", "testapp");
        $result->assertStatus(200);
        $json = json_decode($result->getJSON(), true);
        $this->assertTrue($json["success"]);
    }

}