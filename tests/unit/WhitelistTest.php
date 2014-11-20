<?php

use Jowy\Phrest\Core\Whitelist;
use Jowy\Phrest\Core\Exception\InvalidAuthException;

class WhitelistTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testWhitelistSuccess()
    {
        try {
            $this->assertTrue(Whitelist::get("127.0.0.1")->check());
            $this->assertTrue(Whitelist::get("127.0.0.2")->check());
        } catch (InvalidAuthException $e) {
            $this->assertNotEquals(401, $e->getCode());
        }
    }

    public function testWhitelistFail()
    {
        try {
            $this->assertFalse(Whitelist::get("127.0.0.3")->check());
        } catch (InvalidAuthException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }

}