<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class KeyValueDatabaseModelTest extends CIUnitTestCase
{
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

        $this->model = model(\App\Models\KeyValueDatabase::class);
        $db = \Config\Database::connect();
        $this->builder = $db->table('kv');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->model = NULL;
        // Do something here....
    }

    public function test_create_happy(){
        $this->model->create("mykey", "myvalue", "testuser", "testapp");

        
        $results = $this->builder->getWhere(["k"=>"mykey"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue",$results->getRow()->v);
    }

    public function test_create_duplicates(){
        $this->expectException(\App\Models\KeyValueDatabaseException::class);
        $this->model->create("mykey", "myvalue", "testuser", "testapp");
        $this->model->create("mykey", "myvalue", "testuser", "testapp");
    }

    public function test_read_happy(){
        $data = [
            'k' => 'mykey',
            'v'  => 'myvalue',
            'app'  => 'myapp',
            'username' => 'testuser',
        ];

        $this->builder->insert($data);
        $result = $this->model->read("mykey", "myapp");
        $this->assertEquals("myvalue", $result->v);
        $this->assertEquals("testuser", $result->username);
    }

    public function test_read_not_found(){
        $this->expectException(\App\Models\KeyValueDatabaseException::class);
        $result = $this->model->read("mykey", "myapp");
    }

    public function test_upsert_not_found(){
        //$this->expectException(\App\Models\KeyValueDatabaseException::class);
        $this->model->upsert("mykey", "myapp", "myuser", "myvalue");

        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue",$results->getRow()->v);
    }

    public function test_upsert_exists(){
        $data = [
            'k' => 'mykey',
            'v'  => 'myvalue',
            'app'  => 'myapp',
            'username' => 'testuser',
        ];
        $this->builder->insert($data);
        
        $this->model->upsert("mykey", "myapp", "myuser", "myvalue2");
        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue2",$results->getRow()->v);
    }

    public function test_upsert_exists_differet_app(){
        $data = [
            'k' => 'mykey',
            'v'  => 'myvalue',
            'app'  => 'myapp',
            'username' => 'testuser',
        ];
        $this->builder->insert($data);
        
        $this->model->upsert("mykey", "myapp2", "myuser", "myvalue2");
        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue",$results->getRow()->v);

        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp2"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue2",$results->getRow()->v);
    }

    public function test_remove_happy(){
        $data = [
            'k' => 'mykey',
            'v'  => 'myvalue',
            'app'  => 'myapp',
            'username' => 'testuser',
        ];
        $this->builder->insert($data);
        
        $this->model->remove("mykey", "myapp");
        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp"]);
        $this->assertEquals(0, $results->getNumRows());
    }

    public function test_remove_not_found(){
        $this->model->remove("mykey", "myapp");
        $results = $this->builder->getWhere(["k"=>"mykey", "app"=>"myapp"]);
        $this->assertEquals(0, $results->getNumRows());
    }

}