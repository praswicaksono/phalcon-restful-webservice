<?php


namespace Jowy\Phrest\Core;

use EllipseSynergie\ApiResponse\AbstractResponse;
use Phalcon\Http\ResponseInterface;

class Response extends AbstractResponse
{
    protected $phalconResponse;

    public function withArray(array $array, array $headers = array())
    {
        $this->phalconResponse->setJsonContent($array, JSON_NUMERIC_CHECK);

        if ($this->getStatusCode() != 200) {
            $this->phalconResponse->setStatusCode($this->getStatusCode(), $array["error"]["message"]);
        } else {
            $this->phalconResponse->setStatusCode($this->getStatusCode(), "OK");
        }


        if (isset($headers)) {
            foreach ($headers as $key => $value) {
                $this->phalconResponse->setHeader($key, $value);
            }
        }
        $this->phalconResponse->setContentType('application/json');

        return $this->phalconResponse->send();
    }

    public function setPhalconResponse(ResponseInterface $phalconResponse)
    {
        $this->phalconResponse = $phalconResponse;
    }
}

// EOF
