<?php


namespace Jowy\Phrest\Core;


use Phalcon\DI\Injectable;
use Jowy\Phrest\Models\ApiKeysModel;
use Jowy\Phrest\Core\Whitelist as WhitelistSecurity;
use Jowy\Phrest\Core\Limits\Key;
use Jowy\Phrest\Core\Limits\Method;
use Jowy\Phrest\Core\Exception\InvalidApiKey;

class Engine extends Injectable
{
    public function checkKeyLevel($api_key, $api_annotation)
    {
        $key = ApiKeysModel::findFirst("key = '{$api_key}'");

        // check if api key exist and it has sufficent level to access resource
        if (!$key || $key->getLevel() < $api_annotation->getNamedArgument("level")) {
            throw new InvalidApiKey("Invalid API key", 403);
        }

        return $key;
    }

    public function checkAuth($method_annotation)
    {
        // check if method has auth annotation
        if ($method_annotation->has("Auth")) {
            $auth = $method_annotation->get("Auth")->getArguments();
            $class = "Jowy\\Phrest\\Core\\Auth\\Auth" . ucfirst($auth[0]);
            $class::get()->auth();
        }
    }

    public function checkWhitelist($method_annotation)
    {
        // do whitelist check
        ($method_annotation->has("Whitelist"))
            ? WhitelistSecurity::get($this->request->getClientAddress())->check() : true;
    }

    public function checkKeyLimitOnClass($key, $limit_annotation)
    {
        // check limit for key to access all resources
        Key::get($key, $limit_annotation["key"]["increment"], $limit_annotation["key"]["limit"])->checkLimit();
    }

    public function checkMethodLimitByKey($key, $method_annotation)
    {
        // check key has exceed to access resource
        Method::get(
            $key,
            $this->request->get("_url"),
            $this->request->getMethod(),
            $method_annotation[0]["increment"],
            $method_annotation[0]["limit"]
        )->checkLimit();
    }
}

// EOF
