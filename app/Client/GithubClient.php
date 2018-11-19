<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 09.11.18
 * Time: 15:18
 */

namespace App\Client;

use GuzzleHttp\Client;

/**
 * Class GithubClient
 * @package App\Client
 */
class GithubClient
{
    private static $URL = 'https://api.github.com';

    private $auth;

    /**
     * GithubClient constructor.
     * @param $auth
     */
    function __construct ($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $endpoint
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requester ($endpoint)
    {
        /*$crl = curl_init();
        $endUrl = self::$URL . $endpoint;
        $userAgent = 'Anonym';
        $userPass = $this->user->getUsername() . ':' . $this->user->getPassword();

        curl_setopt($crl, CURLOPT_URL, $endUrl);
        curl_setopt($crl, CURLOPT_TIMEOUT, 15);             // Die maximale Ausführungszeit in Sekunden für cURL-Funktionen.
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);    // TRUE um den Transfer als String zurückzuliefern, anstatt ihn direkt auszugeben.
        curl_setopt($crl, CURLOPT_USERAGENT, $userAgent);         // Der Wert des "User-Agent: "-Headers für den HTTP-Request
        curl_setopt($crl, CURLOPT_USERPWD, "Base " . $this->auth);
        $response = curl_exec($crl);
        $this->statusCode = curl_getinfo($crl, CURLINFO_HTTP_CODE);
        curl_close($crl);

        return json_decode($response);*/


        $client = new Client();
        $response = $client->get(self::$URL . $endpoint,
            [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->auth,
                ],
            ]);

        return $response;
    }


}
