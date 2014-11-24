<?php


namespace Jowy\Phrest\Core\Limits;


use Phalcon\Mvc\User\Component;
use Jowy\Phrest\Core\Exception\LimitReachedException;

class Key extends Component
{
    protected $key;

    protected $increment;

    protected $limit;

    public function __construct($key, $increment = 0, $limit = 0)
    {
        $this->key = $key;
        $this->increment = $increment;
        $this->limit = $limit;
    }

    public function checkLimit()
    {
        if (!$this->increment == 0 && !$this->limit == 0) {
            $date = date("Y-m-d H:i:s", strtotime($this->increment));

            $logs = $this->key->getApiLogs("created_at > '{$date}'");

            if (count($logs) >= $this->limit) {
                throw new LimitReachedException("Limit reached", 503);
            }

            return true;
        }
    }

    public static function get($key, $increment = "-1 day", $limit = 10000)
    {
        return new self($key, $increment, $limit);
    }
}

// EOF
