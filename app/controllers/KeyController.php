<?php


namespace Jowy\Phrest\Controllers;

use Phalcon\Mvc\Controller;
use Jowy\Phrest\Models\ApiKeysModel;
use Jowy\Phrest\Transformers\KeyTransformer;

/**
 * @RoutePrefix("/v1/key")
 * @Api(level=1,
    limits={
    "key" : {
    "increment" : "1 day", "limit" : 1000}
    }
  )
 */
class KeyController extends Controller
{
    /**
     * @Get("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     * @Auth("basic")
     */
    public function indexAction()
    {
        $apiKey = $this->request->getHeader("HTTP_X_API_KEY");
        $api = ApiKeysModel::findFirst("key = '{$apiKey}'");
        return $this->apiResponse->withItem($api, new KeyTransformer());
    }

    /**
     * @Put("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     */
    public function editAction()
    {
        $level = $this->request->getPut("level");
        $ignore_limit = $this->request->getPut("ignore_limit");

        $apiKey = $this->request->getHeader("HTTP_X_API_KEY");
        $api = ApiKeysModel::findFirst("key = '{$apiKey}'");

        $api->setLevel(isset($level) ? $level : $api->getLevel());
        $api->setIgnoreLimit(isset($ignore_limit) ? $ignore_limit : $api->getIgnoreLimit());

        $api->save();

        return $this->apiResponse->withItem($api, new KeyTransformer());
    }

    /**
     * @Post("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     */
    public function addAction()
    {
        $level = $this->request->getPost("level");
        $ignore_limit = $this->request->getPost("ignore_limit");

        $api = new ApiKeysModel();
        $api->setKey($this->generateApiKey());
        $api->setIgnoreLimit((isset($ignore_limit) ? $ignore_limit : 0));
        $api->setLevel((isset($level) ? $level : 1));

        if ($api->save()) {
            return $this->apiResponse->withItem($api, new KeyTransformer());
        } else {
            return $this->apiResponse->errorInternalError();
        }

    }

    /**
     * @Delete("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     */
    public function deleteAction()
    {
        $key = $this->request->get("key");
        $api = ApiKeysModel::findFirst("key = '{$key}'");

        if (!$api) {
            return $this->apiResponse->errorInternalError();
        }

        if (!$api->delete()) {
            return $this->apiResponse->errorInternalError();
        }

        return $this->apiResponse->withArray(["deleted" => true]);
    }

    private function generateApiKey()
    {
        $factory = new \RandomLib\Factory;
        $generator = $factory->getGenerator(new \SecurityLib\Strength(\SecurityLib\Strength::MEDIUM));

        $key = $generator->generateString(32, "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
        while (true) {
            $apiKey = ApiKeysModel::findFirst("key = '{$key}'");

            if (!$apiKey) {
                break;
            }

            $key = $generator->generateString(32);
        }

        return $key;
    }

}

// EOF
