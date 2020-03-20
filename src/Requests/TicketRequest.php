<?php

namespace MimoGraphix\OSTicket\Requests;

use EpicFailStudio\OSTicket\OSTicketClient;
use EpicFailStudio\OSTicket\Exceptions\OSTicketClientException;

/**
 * Class TicketRequest
 *
 * @author MimoGraphix <mimographix@gmail.com>
 * @copyright EpicFail | Studio
 * @package App\osTicket
 */
class TicketRequest extends Request
{
    /**
     * TicketRequest constructor.
     */
    public function __construct( OSTicketClient $client)
    {
        $this->client = $client;
        $this->setDefaultData( array_merge( $client->default, [
            'name'      =>      '',
            'email'     =>      '',
            'phone' 	=>		'',
            'subject'   =>      '',
            'message'   =>      '',
            'ip'        =>      isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'127.0.0.1',
            'topicId'   =>      '' ] )
        );
    }

    /**
     * @return mixed
     * @throws OSTicketClientException
     */
    public function get(){
        return $this->client->request('api/tickets.json', $this->data);
    }
}
