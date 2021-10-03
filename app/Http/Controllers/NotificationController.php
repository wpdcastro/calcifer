<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // $client = new GuzzleHttp();

        $json = [
            "from" => "5510999999999", // 5514996677641
            "to" => "5514996677641",
            "contents" => [ 
                
                [
                    "type" => "text",
                    "text" => "Olá Will, Existem focos de incêndio nas proximidades, mantenha-se seguro!"
                ]
                
            ]
        ];

        // /*
        // body: {
        //     from: 'sender-identifier',
        //     to: 'recipient-identifier',
        //     contents: [{
        //       type: 'text',
        //       text: 'Some text message'
        //     }]
        //   },
        //   */

        // $json = json_encode($json);

        // $res = $client->request('POST', 'https://api.zenvia.com/v2/channels/sms/messages', [
        //     'headers' => [
        //         'X-API-TOKEN' => 'Se_lzbguBhte25FddpKf1dqNb1Mw536ZYG0A'
        //     ],
        //     'body' =>  $json
        // ]);

        // $statusCode = $res->getStatusCode();          // "200"
        // $header = $res->getHeader('content-type')[0]; // 'application/json; charset=utf8'
        // $body = $res->getBody();                      // {"type":"User"...'

        // $promise = $client->sendAsync($res)
        // ->then(function ($response) {
        //     $status = $response->getStatusCode();
        //     $body   = $response->getBody();
        // });
        // $promise->wait();

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.zenvia.com/v2/channels/sms/messages',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($json),
          CURLOPT_HTTPHEADER => array(
            'X-API-TOKEN: Se_lzbguBhte25FddpKf1dqNb1Mw536ZYG0A',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        return response()->json(['data' => ['message' => 'Mensagem enviada succefully updated']]);
    }
    /* Essa função grapCsvFiles() deve ser rodada em rotina a cada 30 min...
       A lista de arquivos é extensa, isso levara em torno de 2 min's para o download completo. 
    */
    public function grapCsvFiles(){
        
        $destination = 'public/listFoc/';

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
                 
                // $fileSave = file_put_contents( $destination.$value, $file);
                $fileSave = Storage::disk('local')->put($value, $file);
            }
        }
 
        if(true){
            //return response()->json(['data' => ['message' => 'Mensagem enviada succefully updated']]);
        }
    }

    public function readFiles($data){

      //necessario passar somente um arquivo por vez.. pelo $data 
         $directoryFiles = 'listFoc/';

         $fileNames = scandir($directoryFiles);
         foreach ($fileNames as $key => $names) {

             if($key != 0 && $key != 1){
                 $fileDirectory = $directoryFiles.$names;

                 /*=========================================================================
                 Somente este trecho deverá se manter nessa função, 
                 o foreach deverá ser passado para o trecho onde a readFiles() será chamada passando por parametro o diretorio completo exemplo "listFoc/noma_file.csv".
                 Isso para que não seja carregado sempre todos os arquivos de uma só vez.
                 */
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
                 /* FIM do trecho 
                 =========================================================================*/

             }
         }

        return response()->json(['data' => ['message' => 'succefully', 'info' => $result]]);
    }

    public function grapLocation()
    {
        $lat = '-22.2180531';
        $lon = '-49.9458418';

        $curl = curl_init();

        curl_setopt_array($curl, array(
          // CURLOPT_URL => 'http://api.positionstack.com/v1/reverse?access_key=0a771a096c4209bd2634d592039a8b86&query='.$lat.','.$lon,
          CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lon.'&key=AIzaSyA07miAu93FuKtizjKJw0pmgphaHk1JBQo',
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

        $cep = str_replace("-","",$apiResult['results'][0]['address_components'][6]['short_name']);

        $DDD = $this->grapDDD($cep);

        $list = ['pais' => $apiResult['results'][0]['address_components'][5]['short_name'],
            'cep' => $DDD['cep'],
                'localidade' => $DDD['localidade'],
                'uf' => $DDD['uf'], 
                'ddd' => $DDD['ddd']];

        return response()->json(['data' => ['message' => 'succefully', 'info' => $list]]);
    }

    public function grapDDD($cep)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://viacep.com.br/ws/'.$cep.'/json/',
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

        return $apiResult;
    }
}

/*====================================================
    
    Pendente:

    - Gravar usuario por meio da tela de cadastro.
    - Incluir gravar dados de focus na função readFiles().
    - Criar rotina de execução de grapCsvFiles().
    - Criar função para chamar readFiles(), para cada localização de focos, buscar usuario cadastrado na região proxima e Enviar notificação sendNotification.
    - Incluir tratar erros em todas as funções.

=====================================================*/