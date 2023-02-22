<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class HomeTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    protected function tearDown(): void
    {
        unset($_SESSION['error']);
        unset($_SESSION['message']);
        service('validation')->reset();
        parent::tearDown();
    }
    
    public function test_add(){
        
        $stub = $this->createStub(\App\Libraries\TodoItems::class);
        $stub->method('add')->willreturn(NULL);
        \Config\Services::injectMock("todoitems", $stub);
          
        $result = $this->call('post', 'add', [
                'title'  => 'test title',
                'details' => 'details',
        ]);
        $result->assertStatus(302);
        $result->assertSessionHas("message", "Successful adding todo item!");
    }
    
    public function test_add_bad_title(){
        $stub = $this->createStub(\App\Libraries\TodoItems::class);
        $stub->method('add')->willreturn(NULL);
        \Config\Services::injectMock("todoitems", $stub);

        $result = $this->call('post', 'add', [
                'ttitle'  => 'test title',
                'details' => 'details',
        ]);
        $result->assertStatus(200);
        $result->assertSee('<form action="/add" method="post">');
        //$result->assertSessionHas("error");
    }
    
    public function test_add_form(){
        $result = $this->call("get", "add");
        $result->assertStatus(200);
        $result->assertSee('<form action="/add" method="post">');
    }
    
    public function test_count(){
        $fakeitems = array();
        $fakeitems['1'] = array(
            "title"=> "test",
            "details"=> "test",
            "date_added"=> "test",
            "completed"=> "false"
            );
        $stub = $this->createStub(\App\Libraries\TodoItems::class);
        $stub->method('get_all_items')->willReturn($fakeitems);
        \Config\Services::injectMock("todoitems", $stub);
        
        
        $cookiedata = [
            "jwt" => 'testjwt',
            "username" => 'testusername',
            "role" => 'testrole'
        ];
        $cookiestub = $this->createStub(\App\Libraries\CookieAuthData::class);
        $cookiestub->method('getCookieData')->willreturn($cookiedata);
        \Config\Services::injectMock("cookieauthdata", $cookiestub);
        
        
        $result = $this->call("get", "/");
        //print_r($result->response()->getBody());
        $result->assertStatus(200);
        $result->assertSee("<p>Total Items: 1 </p>");
    }
    
}
