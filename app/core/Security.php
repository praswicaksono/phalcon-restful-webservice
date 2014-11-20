<?php


namespace Jowy\Phrest\Core;

use Jowy\Phrest\Core\Limits\Method;
use Jowy\Phrest\Models\ApiLogsModel;
use Jowy\Phrest\Models\ApiKeysModel;
use Phalcon\Exception as PhalconException;
use Jowy\Phrest\Core\Limits\Key;
use Jowy\Phrest\Core\Whitelist;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Jowy\Phrest\Core\Exception\InvalidAuthException;

class Security extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        try {
            $apiKey = $this->request->getHeader("HTTP_X_API_KEY");
            $key = ApiKeysModel::findFirst("key = '{$apiKey}'");

            // check if API key is valid
            if (!$key) {
                throw new PhalconException("Invalid API Key");
            }

            // read class annotation
            $classAnnotation = $this->annotations->get($dispatcher->getHandlerClass())->getClassAnnotations();
            $classInfo = $classAnnotation->get("Api");

            // read method annotation
            $methodAnnotation = $this->annotations->getMethod(
                $dispatcher->getHandlerClass(),
                $dispatcher->getActiveMethod()
            );

            // check if method has auth annotation
            if ($methodAnnotation->has("Auth")) {
                $auth = $methodAnnotation->get("Auth")->getArguments();
                $class = "Jowy\\Phrest\\Core\\Auth\\Auth" . ucfirst($auth[0]);
                $class::get()->auth();
            }

            if ($methodAnnotation->has("Whitelist")) {
                Whitelist::get($this->request->getClientAddress())->check();
            }

            // check API key level to access this method
            if ($key->getLevel() < $classInfo->getNamedArgument("level")) {
                throw new PhalconException("Unauthorized");
            }

            // check if API key has ignore limit flag
            if (!$key->getIgnoreLimit()) {
                if ($classInfo->hasNamedArgument("limits")) {
                    $limits = $classInfo->getNamedArgument("limits");

                    // check limit for key to access all method in class
                    if (isset($limits["key"])) {
                        Key::get($key, "-" . $limits["key"]["increment"], $limits["key"]["limit"])->checkLimit();
                    }

                    // check limit for key to access specific method in class
                    if ($methodAnnotation->has("Limit")) {
                        $limit = $methodAnnotation->get("Limit")->getArguments();

                        $increment = (isset($limit[0]["increment"])) ? "-" . $limit[0]["increment"] : "-1 hour";
                        $limit = (isset($limit[0]["limit"])) ? $limit[0]["limit"] : 100;

                        Method::get(
                            $key,
                            $this->request->get("_url"),
                            $this->request->getMethod(),
                            $increment,
                            $limit
                        )->checkLimit();
                    }
                }
            }

            // write logs to db
            $logs = new ApiLogsModel();
            $logs->setApiKeyId($key->getApiKeyId());
            $logs->setIpAddress($this->request->getClientAddress());
            $logs->setMethod($this->request->getMethod());
            $logs->setRoute($this->request->get("_url"));
            $logs->setParam(serialize($dispatcher->getParams()));
            $logs->save();

        } catch (InvalidAuthException $e) {
            $this->apiResponse->errorUnauthorized($e->getMessage());
            return false;
        } catch (PhalconException $e) {
            $this->apiResponse->errorUnauthorized($e->getMessage());
            return false;
        }

        return true;
    }
}

// EOF
