<?php

namespace MimoGraphix\OSTicket;

use MimoGraphix\OSTicket\Exceptions\OSTicketClientException;
use MimoGraphix\OSTicket\Requests\TicketRequest;

/**
 * Class OSTicketClient
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @copyright EpicFail | Studio
 * @package it\thecsea\osticket_php_client
 */
class OSTicketClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $url;

    public $default = [];

    /**
     * osticketPhpClient constructor.
     * @param string $url
     * @param string $apiKey
     */
    public function __construct($url = '', $apiKey = '' )
    {
        if($apiKey == '')
           $apiKey = config('osticket.key');

        if($url == '')
            $url = config('osticket.url');

        $this->default = config( 'osticket.default' );
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }


    /**request
     * @param string $path
     * @param array $data
     * @return mixed
     * @throws OSTicketClientException
     */
    public function request($path, $data){
        try {
            $res = $this->client->request('POST', $this->url . '/' . $path, [
                'json' => $data,
                'headers' => [
                    'User-Agent' => 'OSTicketClient/1.0',
                    'Accept' => 'application/json',
                    'Expect' => '',
                    'X-API-Key' => $this->apiKey
                ]
            ]);

        }catch(\Exception $e){
            throw new OSTicketClientException("Request error: ".$e->getMessage(),0,$e);
        }
        if ($res->getStatusCode() != 201)
            throw new OSTicketClientException("Server error: " . $res->getStatusCode());
        try {
            return \GuzzleHttp\json_decode($res->getBody(), true);
        }catch(\Exception $e){
            throw new OSTicketClientException("Error during parsing response",0,$e);
        }
    }

    public function setDefault( $key, $value )
    {
        $this->default[ $key ] = $value;
        return $this;
    }

    /**
     * @return TicketRequest
     */
    public function newTicket(){
        return new TicketRequest($this);
    }
}
