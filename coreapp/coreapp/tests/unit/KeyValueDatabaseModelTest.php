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

        // Do something here....
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Do something here....
    }

    public function test_put_happy(){
        $model = model(\App\Models\KeyValueDatabase::class);
        $model->put("mykey", "myvalue", "testuser", "testapp");

        $db = \Config\Database::connect();
        $builder = $db->table('kv');
        $results = $builder->getWhere(["k"=>"mykey"]);
        $this->assertEquals(1, $results->getNumRows());
        $this->assertEquals("myvalue",$results->getRow()->v);
    }

    public function test_put_duplicates(){
        $this->expectException(\App\Models\KeyValueDatabaseException::class);
        $model = model(\App\Models\KeyValueDatabase::class);
        $model->put("mykey", "myvalue", "testuser", "testapp");
        $model->put("mykey", "myvalue", "testuser", "testapp");
    }

}