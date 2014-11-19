<?php


namespace Jowy\Phrest\Core;


class Auth
{
    public static function create($strategy)
    {
        $class = "Jowy\\Phrest\\Core\\Auth\\Auth" . ucwords($strategy);

        if (class_exists($class)) {
            return new $class();
        }

        throw new \Phalcon\Exception("Internal error", 500);
    }
}

// EOF
