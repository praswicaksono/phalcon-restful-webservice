<?php


namespace Jowy\Phrest\Core\Limits;


use Phalcon\Mvc\User\Component;
use Phalcon\Exception as PhalconException;

class Key extends Component
{
    protected $key;

    protected $increment;

    protected $limit;

    public function __construct($key, $increment = "-1 day", $limit = 10000)
    {
        $this->key = $key;
        $this->increment = $increment;
        $this->limit = $limit;
    }

    public function checkLimit()
    {
        $date = date("Y-m-d H:i:s", strtotime($this->increment));

        $logs = $this->key->getApiLogs("created_at > '{$date}'");

        if (count($logs) >= $this->limit) {
            throw new PhalconException("API key has reached limit");
        }

        return true;
    }

    public static function get($key, $increment = "-1 day", $limit = 10000)
    {
        return new self($key, $increment, $limit);
    }
}

// EOF
