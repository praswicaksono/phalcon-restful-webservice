<?php

use Jowy\Phrest\Core\Whitelist;

class WhitelistTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    // tests
    public function testWhitelist()
    {
        $whitelist = new Whitelist("192.168.56.1");
        $whitelist->setDI(\Phalcon\DI::getDefault());
        $this->assertTrue($whitelist->check());
    }

    public function testWhitelistFail()
    {
        try {
            $whitelist = new Whitelist("192.168.56.2");
            $whitelist->setDI(\Phalcon\DI::getDefault());
            $this->assertFalse($whitelist->check());
        } catch (\Jowy\Phrest\Core\Exception\InvalidAuthException $e) {
            $this->assertEquals(401, $e->getCode());
        }
    }

}