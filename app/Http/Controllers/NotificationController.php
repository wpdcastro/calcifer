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
            "from" => "5510999999999",
            "to" => "5514996677641",
            "contents" => [
              [
                "type" => "text",
                "text" => "Hello World!"
              ]
            ]
        ];

        $json = json_encode($json);

       // X-API-TOKEN: hKp94crjv9OF3UGrCpSXUJw1-UYHhRvLKNLt
//Se_
        $res = $client->request('POST', 'https://api.zenvia.com/v2/channels/sms/messages', [
            'header' => json_encode(['X-API-TOKEN' => 'Se_lzbguBhte25FddpKf1dqNb1Mw536ZYG0A']),
            'body'   => $json
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
