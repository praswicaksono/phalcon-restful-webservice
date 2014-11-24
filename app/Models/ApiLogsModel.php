<?php


namespace Jowy\Phrest\Models;


use Phalcon\Mvc\Model;

class ApiLogsModel extends Model
{
    use CommonBehaviour;

    protected $api_log_id;

    protected $api_key_id;

    protected $route;

    protected $method;

    protected $param;

    protected $ip_address;

    protected $created_at;

    protected $updated_at;

    public function initialize()
    {
        $this->setSource("api_logs");
        $this->belongsTo("api_key_id", "Jowy\\Phrest\\Models\\ApiKeysModel", "api_key_id");
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
     * @param mixed $api_log_id
     */
    public function setApiLogId($api_log_id)
    {
        $this->api_log_id = $api_log_id;
    }

    /**
     * @return mixed
     */
    public function getApiLogId()
    {
        return $this->api_log_id;
    }

    /**
     * @param mixed $ip_address
     */
    public function setIpAddress($ip_address)
    {
        $this->ip_address = $ip_address;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
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
