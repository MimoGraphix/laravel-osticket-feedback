<?php

namespace MimoGraphix\OSTicket\Http\Controllers;

use MimoGraphix\OSTicket\OSTicketClient;
use MimoGraphix\OSTicket\Exceptions\OSTicketClientException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class OSTicketController extends Controller
{
    public function sendTicket( Request $request )
    {
        $v = Validator::make( $request->all(), [
            "name" => 'required',
            "email" => 'required|email',
            "subject" => 'required',
            "message" => 'required',
        ]);

        if ($v->fails())
        {
            return Response::json([
                                      'success' => false,
                                      'errors' => $v->errors(),
                                  ], 400);
        }

        if( config( 'osticket.recaptcha_v3.secret_key' ) != "" )
        {
            $recaptcha = new \ReCaptcha\ReCaptcha( config( 'osticket.recaptcha_v3.secret_key' ) );
            $resp = $recaptcha->setExpectedAction('osticket')
                      ->setScoreThreshold(0.5)
                      ->verify( $request->get( 'recaptcha_response' ), $request->ip() );

            if ( !$resp->isSuccess()) {
                return Response::json([
                                          'success' => false,
                                          'messages' => "We are sorry but it seems you are a robot..",
                                      ], 400);
            }
        }

        $client = new OSTicketClient();
        try
        {
            $response = $client->newTicket()
                ->withName( $request->get( 'name' ) )
                ->withEmail( $request->get( 'email' ) )
                ->withSubject( $request->get( 'subject' ) )
                ->withMessage( $request->get( 'message' ) );

            return Response::json( $response->get(), 200 );
        }
        catch ( OSTicketClientException $e )
        {
            Log::error( $e );
            return Response::json([
                                      'success' => false,
                                      'message' => $e->getMessage(),
                                  ], 400);
        }
    }
}
