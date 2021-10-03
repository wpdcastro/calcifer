<?php

namespace App\Http\Services;

class NotificationService extends Service
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Fire $fire)
    {
        $this->fire = $fire;
    }

    public function updateDatabase()
    {
        $database = [ ["rapaz"], ["rapaz2"], ["rapaz4"] ];

        $client = new GuzzleHttp\Client();

        $res = $client->request('POST', 'https://api.zenvia.com/v2/channels/sms/messages', [
            'auth' => ['user', 'pass']
        ]);

        $statusCode = $res->getStatusCode();
        // "200"
        $header = $res->getHeader('content-type')[0];
        // 'application/json; charset=utf8'
        $body = $res->getBody();
        // {"type":"User"...'

        // Send an asynchronous request.
        $request = new \GuzzleHttp\Psr7\Request('POST', 'https://api.zenvia.com/v2/channels/sms/messages');
        $promise = $client->sendAsync($request)
        ->then(function ($response) {
            $status = $response->getStatusCode();
            $body   = $response->getBody();
        });
        $promise->wait();

        $json = [
            "from" => "5510999999999",
            "to" => "5514996677641",
            "contents" => [
              [
                "type" => "text",
                "text" => "Hi Zenvia!"
              ]
            ]
        ];

        return ['status' => $statusCode, 'body' => $body, 'header' => $header];
        //return $database;
    }
}
