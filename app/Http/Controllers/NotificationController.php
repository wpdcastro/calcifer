<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleHttp;

class NotificationController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Notification $fire)
    {
        $this->notification = $fire;
    }

    public function index()
    {
        return $this->notification->notification(10);
    }

    public function show($notificationId)
    {
        return $this->notification->find($notificationId);
    }

    public function sendNotification()
    {
        $client = new GuzzleHttp();

        $json = [
            "from" => "5514996677641", // 5514996677641
            "to" => "5514996677641",
            "contents" => [ 
                json_encode(
                [
                    "type" => "text",
                    "text" => "Hello World!"
                ]
                )
            ]
        ];

        /*
        body: {
            from: 'sender-identifier',
            to: 'recipient-identifier',
            contents: [{
              type: 'text',
              text: 'Some text message'
            }]
          },
          */

        $json = json_encode($json);

        $res = $client->request('POST', 'https://api.zenvia.com/v2/channels/sms/messages', [
            'headers' => [
                'X-API-TOKEN' => 'Se_lzbguBhte25FddpKf1dqNb1Mw536ZYG0A'
            ],
            'body' =>  $json
        ]);

        $statusCode = $res->getStatusCode();          // "200"
        $header = $res->getHeader('content-type')[0]; // 'application/json; charset=utf8'
        $body = $res->getBody();                      // {"type":"User"...'

        $promise = $client->sendAsync($res)
        ->then(function ($response) {
            $status = $response->getStatusCode();
            $body   = $response->getBody();
        });
        $promise->wait();

        return response()->json(['data' => ['message' => 'Mensagem enviada succefully updated']]);
    }
}
