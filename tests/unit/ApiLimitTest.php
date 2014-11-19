<?php

use Jowy\Phrest\Core\Limits\Key;
use Jowy\Phrest\Core\Limits\Method;
use Mockery as m;

class ApiLimitTest extends \PHPUnit_Framework_TestCase
{

    private $key;

    protected function setUp()
    {
        $this->key = m::mock("ApiKeysModel");

        $this->key->shouldReceive("getApiLogs")->andReturn(
            [
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                20
            ]
        );
    }

    protected function tearDown()
    {

    }

    // tests
    public function testKeyLimit()
    {
        try {
            $this->assertTrue(Key::get($this->key, "-1 day", 11)->checkLimit());
        } catch (\Phalcon\Exception $e) {
            $this->assertNotEquals("API key has reached limit", $e->getMessage());
        }
    }

    public function testKeyLimitReached()
    {
        try {
            $this->assertFalse(Key::get($this->key, "-1 day", 9)->checkLimit());
        } catch (\Phalcon\Exception $e) {
            $this->assertEquals("API key has reached limit", $e->getMessage());
        }
    }

    public function testMethodLimit()
    {
        try {
            $this->assertTrue(Method::get($this->key, "indexAction", "-1 hour", 11)->checkLimit());
        } catch (\Phalcon\Exception $e) {
            $this->assertNotEquals("API key has reached limit", $e->getMessage());
        }
    }

    public function testMethodLimitReached()
    {
        try {
            $this->assertFalse(Method::get($this->key, "/v1/key", "POST", "-1 day", 9)->checkLimit());
        } catch (\Phalcon\Exception $e) {
            $this->assertEquals("API key has reached limit", $e->getMessage());
        }
    }

}