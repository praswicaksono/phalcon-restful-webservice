<?php


namespace Jowy\Phrest\Core\Auth;


use Phalcon\Mvc\User\Component;
use Jowy\Phrest\Core\Exception\InvalidAuthException;

class AuthBasic extends Component implements AuthInterface
{

    public function auth()
    {
        $config = $this->di->get("config");
        if (isset($_SERVER["PHP_AUTH_USER"])) {
            if ($_SERVER["PHP_AUTH_USER"] != $config["auth"]["username"] ||
                $_SERVER["PHP_AUTH_PW"] != $config["auth"]["password"]
            ) {
                throw new InvalidAuthException("Unauthorized", 401);
            }
        } else {
            throw new InvalidAuthException("Unauthorized", 401);
        }


        return true;
    }

    public static function get()
    {
        return new self();
    }
}

// EOF
