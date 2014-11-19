<?php

class AuthTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $_SERVER["PHP_AUTH_USER"] = "jowy";
        $_SERVER["PHP_AUTH_PW"] = "Blink182";

    }

    protected function tearDown()
    {
    }

    // tests
    public function testAuth()
    {
        try {
            $auth = \Jowy\Phrest\Core\Auth::create("basic");
            $auth->setDI(\Phalcon\DI::getDefault());
            $this->assertTrue($auth->auth());
        } catch (\Jowy\Phrest\Core\Exception\InvalidAuthException $e) {
            $this->fail("Invalid Auth");
        } catch (\Phalcon\Exception $e) {
            $this->fail("Class not found");
        }

    }

    public function testAuthFail()
    {
        try {
            $auth = \Jowy\Phrest\Core\Auth::create("basic");
            $auth->setDI(\Phalcon\DI::getDefault());
            $_SERVER["PHP_AUTH_PW"] = "Wrong PW";
            $this->assertTrue($auth->auth());
        } catch (\Jowy\Phrest\Core\Exception\InvalidAuthException $e) {
            $this->assertEquals(401, $e->getCode());
        } catch (\Phalcon\Exception $e) {
            $this->fail("Class not found");
        }
    }

    public function testAuthClassNotFound()
    {
        try {
            $auth = \Jowy\Phrest\Core\Auth::create("notExist");
            $auth->setDI(\Phalcon\DI::getDefault());
            $this->assertTrue($auth->auth());
        } catch (\Jowy\Phrest\Core\Exception\InvalidAuthException $e) {
            $this->fail("Class not found");
        } catch (\Phalcon\Exception $e) {
            $this->assertEquals(500, $e->getCode());
        }
    }

}