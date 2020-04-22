<?php

namespace Sraban\OnlineVisitor;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Sraban\OnlineVisitor\EmployeeController;
#use Tests\TestCase;

class OnlineVisitorTest extends OrchestraTestCase
{
    public $ov; // online visitor 

    public function setUp() : void
    {
        parent::setUp();

        $this->ov = new EmployeeController();
        $this->ov->refreshEmployee();
    }


    public function test_case1() {

        $expected = ['Saved'];
        $result = $this->ov->statement(["SET empdata 1 ‘Jack Petter’ ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);

        $expected = ['192.168.10.10'];
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ['Deleted'];
        $result = $this->ov->statement(["UNSET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ['Resource not found'];
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = 'The selected ip address is invalid.';
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://google.com’","END"]);
        $this->assertContains($expected, $result[0]->ip_address);
        
        $expected = ['Resource not found'];
        $result = $this->ov->statement(["GET empwebhistory  192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

        $expected = ['NULL'];
        $result = $this->ov->statement(["UNSET empwebhistory  192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

        $expected = ['Resource not found'];
        $result = $this->ov->statement(["GET empwebhistory 192.168.10.10","END"]);
        $this->assertEquals($expected, $result);
    }


    public function test_case2() {

        $expected = ["Saved"];
        $result = $this->ov->statement(["SET empdata 1 ‘Jack Petter’ ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);

        $expected = [2];
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ["Saved"];
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://google.com’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ["Saved"];
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://facebook.com’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = 2;
        $result = $this->ov->statement(["GET empwebhistory  192.168.10.10","END"]);
        $this->assertCount($expected, $result[0]->urls);
        
        $expected = ["Deleted"];
        $result = $this->ov->statement(["UNSET empwebhistory  192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

        $expected = ["Resource not found"];
        $result = $this->ov->statement(["GET empwebhistory 192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

    }


    public function tearDown() : void
    {
       $this->ov->refreshEmployee();
    }
}
