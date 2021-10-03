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


     function grapCsvFiles(){
         $destination = './listFoc/';
         $ctx = stream_context_create();
         $arrContextOptions=array(
               "ssl"=>array(
                     "verify_peer"=>false,
                     "verify_peer_name"=>false,
                 ),
               'http' => array( 
                   'header' => array( 
                       "Authorization: Basic ZGFkb3NfYWJlcnRvczpkYWRvc19hYmVydG9z" 
                       ) 
                   ),
             );  

         $html = file_get_contents("http://queimadas.dgi.inpe.br/queimadas/users/dados_abertos/focos/10min/",false, stream_context_create($arrContextOptions));


         $count = preg_match_all('/<td><a href="([^"]+)">[^<]*<\/a><\/td>/i', $html, $files);
            for ($i = 0; $i < $count; ++$i) {
              $files[1][$i];
            }

            foreach ($files[1] as $key => $value) {

             if($key != 0){
                 // print_r("http://queimadas.dgi.inpe.br/queimadas/users/dados_abertos/focos/10min/".$value);exit;
                 $file = file_get_contents("http://queimadas.dgi.inpe.br/queimadas/users/dados_abertos/focos/10min/".$value,false, stream_context_create($arrContextOptions));
                 
                 $fileSave = file_put_contents( $destination.$value, $file);

             }

            }

         return response()->json(['data' => ['message' => 'Ok']]);

     }

    function readFiles($data){

      //necessario passar somente um arquivo por vez.. pelo $data 
         // $directoryFiles = 'listFoc/';

         // $fileNames = scandir($directoryFiles);
         // foreach ($fileNames as $key => $names) {

         //     if($key != 0 && $key != 1){
         //         $fileDirectory = $directoryFiles.$names;
                 $handle = fopen($fileDirectory, "r");
                 $row = 0;
                 while ($line = fgetcsv($handle, 1000, ",")) {
                     if ($row++ == 0) {
                         continue;
                     }
                     
                     $people[] = [
                         'lat' => $line[0],
                         'lon' => $line[1],
                         'data' => $line[2],
                         'satelite' => $line[3]
                     ];
                 }
                 fclose($handle);
                 $result = $handle;
             // }
         }

         return response()->json(['data' => ['message' => 'ok', 'info' => $result]]);
    }

    function grapLocation($lat, $lon)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          // CURLOPT_URL => 'http://api.positionstack.com/v1/reverse?access_key=0a771a096c4209bd2634d592039a8b86&query='.$lat.','.$lon,
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lon'&key=AIzaSyA07miAu93FuKtizjKJw0pmgphaHk1JBQo',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        $apiResult = json_decode($response, true);

        return response()->json(['data' => ['message' => 'Mensagem enviada succefully updated', 'info' => $apiResult]]);
    }
}
