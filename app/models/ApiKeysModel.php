<?php


namespace Jowy\Phrest\Models;


use Phalcon\Mvc\Model;

class ApiKeysModel extends Model
{
    use CommonBehaviour;

    protected $api_key_id;

    protected $key;

    protected $ignore_limit;

    protected $level;

    protected $created_at;

    protected $updated_at;


    public function initialize()
    {
        $this->setSource("api_keys");
        $this->hasMany(
            "api_key_id",
            "Jowy\\Phrest\\Models\\ApiLogsModel",
            "api_key_id",
            [
                "alias" => "ApiLogs"
            ]
        );
    }

    /**
     * @param mixed $api_key_id
     */
    public function setApiKeyId($api_key_id)
    {
        $this->api_key_id = $api_key_id;
    }

    /**
     * @return mixed
     */
    public function getApiKeyId()
    {
        return $this->api_key_id;
    }

    /**
     * @param mixed $ignore_limit
     */
    public function setIgnoreLimit($ignore_limit)
    {
        $this->ignore_limit = $ignore_limit;
    }

    /**
     * @return mixed
     */
    public function getIgnoreLimit()
    {
        return $this->ignore_limit;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}

// EOF
