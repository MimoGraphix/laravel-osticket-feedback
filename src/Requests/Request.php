<?php

namespace MimoGraphix\OSTicket\Requests;

use MimoGraphix\OSTicket\OSTicketClient;
use MimoGraphix\OSTicket\OSTicketClientException;

abstract class Request
{

    /*
     * string[]
     */
    protected $data = [];

    /**
     * @var string[]
     */
    private $keys = [];

    /**
     * @var OSTicketClient
     */
    protected $client;

    /**
     * @param $data
     */
    protected function setDefaultData($data){
        $this->data = $data;
        $this->keys = array_keys($this->data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function withData($data)
    {
        $data = array_merge($this->data, $data);
        foreach ($this->keys as $key)
            $this->data[$key] = $data[$key];
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws OSTicketClientException
     */
    function __call($name, $arguments)
    {
        $nameData = lcfirst(substr($name,strlen('with')));
        if(strtolower(substr($name,0,strlen('with'))) == "with" && isset($this->data[$nameData]) && count($arguments) == 1 && is_string($arguments[0])) {
            $this->data[$nameData] = $arguments[0];
            return $this;
        }
        throw new OSTicketClientException("Method invalid: ".$name);
    }

    /**
     * @return mixed
     * @throws OSTicketClientException
     */
    abstract public function get();
}
