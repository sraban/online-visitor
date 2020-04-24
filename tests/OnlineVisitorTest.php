<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Sraban\OnlineVisitor\EmployeeController;
use Tests\TestCase;

class OnlineVisitorTest extends TestCase
{
    public $ov; // online visitor 
    public $client;
    public function setUp() : void
    {
        parent::setUp();
        $this->client = new \GuzzleHttp\Client();
        $this->ov = new EmployeeController();
        $this->ov->refreshEmployee();
    }


    public function test_case1() {

        $expected = ['Saved'];
        $result = $this->ov->statement(["SET empdata 1 ‘Jack Petter’ ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);

        $expected = '192.168.10.10';
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result[0]['empIpAddress']);
        
        $expected = ['Deleted'];
        $result = $this->ov->statement(["UNSET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ['Resource not found'];
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = 'The selected ip address is invalid.';
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://google.com’","END"]);
        $this->assertContains($expected, $result[0]->all() );
        
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

        $expected = 1;
        $result = $this->ov->statement(["GET empdata ‘192.168.10.10’","END"]);
        $this->assertEquals($expected, $result[0]['id'] );
        
        $expected = ["Saved"];
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://google.com’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = ["Saved"];
        $result = $this->ov->statement(["SET empwebhistory 192.168.10.10 ‘http://facebook.com’","END"]);
        $this->assertEquals($expected, $result);
        
        $expected = 2;
        $result = $this->ov->statement(["GET empwebhistory  192.168.10.10","END"]);
        $this->assertCount($expected, $result[0]['urls']);
        
        $expected = ["Deleted"];
        $result = $this->ov->statement(["UNSET empwebhistory  192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

        $expected = ["Resource not found"];
        $result = $this->ov->statement(["GET empwebhistory 192.168.10.10","END"]);
        $this->assertEquals($expected, $result);

    }

    public function test_case3() {
        
            $input = <<<EOF
                SET empdata 2 ‘Jack Petter’ ‘192.168.11.11’
                GET empdata ‘192.168.11.11’
                UNSET empdata ‘192.168.11.11’
                GET empdata ‘192.168.11.11’
                SET empwebhistory 192.168.11.11 ‘http://google.com’
                GET empwebhistory  192.168.11.11
                UNSET empwebhistory  192.168.11.11
                GET empwebhistory 192.168.11.11
                END
EOF;

        $promise = $this->client->request('POST',  route('statement'), [
            'body' => $input,
            'debug' => false
        ]);

        $this->assertEquals(200 , $promise->getStatusCode() );
        $promise->getBody()->rewind();
        $this->assertContains('192.168.11.11', $promise->getBody()->getContents() );

    }


    public function test_case4() {
        
        $input = <<<EOF
            SET empdata 2 ‘Jack Petter’ ‘192.168.11.11’
            GET empdata ‘192.168.11.11’
            SET empwebhistory 192.168.11.11 ‘http://google.com’
            SET empwebhistory 192.168.11.11 ‘http://facebook.com’
            GET empwebhistory  192.168.11.11
            UNSET empwebhistory  192.168.11.11
            GET empwebhistory 192.168.11.11
            END
EOF;
                
        $promise = $this->client->request('POST',  route('statement'), [
            'body' => $input,
            'debug' => false
        ]);

        $this->assertEquals(200 , $promise->getStatusCode() );
        $promise->getBody()->rewind();
        $this->assertContains('192.168.11.11', $promise->getBody()->getContents() );
    }

    public function tearDown() : void
    {
       $this->ov->refreshEmployee();
    }
}
