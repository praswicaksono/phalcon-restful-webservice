<?php


namespace Jowy\Phrest\Core;

use Phalcon\Exception as PhalconException;

class Auth
{
    public static function create($strategy)
    {
        $class = "\\Jowy\\Phrest\\Core\\Auth\\Auth" . ucwords($strategy);

        if (class_exists($class)) {
            return new $class();
        }

        throw new PhalconException("Internal error", 500);
    }
}

// EOF
