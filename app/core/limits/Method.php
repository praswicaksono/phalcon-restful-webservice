<?php


namespace Jowy\Phrest\Core\Limits;


use Phalcon\Mvc\User\Component;
use Jowy\Phrest\Core\Exception\LimitReachedException;

class Method extends Component
{
    protected $key;

    protected $uri;

    protected $http_method;

    protected $increment;

    protected $limit;

    public function __construct($key, $uri, $http_method, $increment = 0, $limit = 0)
    {
        $this->key = $key;
        $this->uri = $uri;
        $this->http_method = $http_method;
        $this->increment = $increment;
        $this->limit = $limit;
    }

    public function checkLimit()
    {
        if (!$this->increment == 0 && !$this->limit == 0) {
            $date = date("Y-m-d H:i:s", strtotime($this->increment));

            $logs = $this->key->getApiLogs(
                "created_at > '{$date}' AND method = '{$this->http_method}' AND route = '{$this->uri}'"
            );

            $count = count($logs);

            if ($count >= $this->limit) {
                throw new LimitReachedException("Limit reached", 503);
            }
            return true;
        }
    }

    public static function get($key, $uri, $http_method, $increment = "-1 hour", $limit = 1000)
    {
        return new self($key, $uri, $http_method, $increment, $limit);
    }
}

// EOF
