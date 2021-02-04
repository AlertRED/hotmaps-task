<?
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

$client = new Client(['base_uri' => 'http://testapi.ru/']);

class HotMapsException extends Exception{}

function checkStatus(string $status){
    if ($status==null)
        throw new HotMapsException('Unknown status');
    elseif ($status=='Not found')
        throw new HotMapsException('Not found');
    elseif ($status=='Error')
        throw new HotMapsException('Error');
}

function auth(string $login, string $password){
    global $client;
    $headers = ['login' => $login, 'password'=>  $password];
    try {
        $response = $client->get('auth', $headers);
    } catch (Exception $e) {
        throw new HotMapsException('Error');
    }
    $responseDict = json_decode($response->getBody(), $associative=True);
    checkStatus( $responseDict['status']);
    return $responseDict['token'];
}

function getUser(string $username, string $token){
    global $client;
    try {
        $response = $client->get("get-user/$username", ['query' => ['token' => $token]]);
    } catch (Exception $e) {
        throw new HotMapsException('Error');
    }
    $responseDict = json_decode($response->getBody(), $associative=True);
    checkStatus( $responseDict['status']);
    return $responseDict;
}

function updateUser(string $username, array $new_user, string $token){
    global $client;
    try {
        $response = $client->post("user/$username/update", ['form_params' => json_encode($new_user), 'query' => ['token' => $token]]);
    } catch (Exception $e) {
        throw new HotMapsException('Error');
    }
    $responseDict = json_decode($response->getBody(), $associative=True);
    checkStatus( $responseDict['status']);
    return $responseDict['status']=='OK';
}
?>

