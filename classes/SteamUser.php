<?php
require_once 'ApiClient.php';
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 7/07/17
 * Time: 08:48 PM
 */

class SteamUser{

    private $settings;

    function __construct(){
        $this->readSettings();
    }

    public function getSteamID(){
        return $this->makeRequest("");
    }

    public function testFunction(){
        $params = ['appid'=>440, 'count' => 3];
        $url = $this->assembleUrl('news', $params);
        echo $url;
        $response = $this->makeRequest($url);
        echo $response;
    }

    private function assembleUrl($steamMethod, array $params){
        $url = $this->settings['steam']['url'];

        $urlParams = http_build_query($params);
        $url = str_replace('{interface}', $this->settings['steam_methods'][$steamMethod]['interface'], $url);
        $url = str_replace('{method}', $this->settings['steam_methods'][$steamMethod]['method'], $url);
        $url = str_replace('{paramns}', $urlParams, $url);

        return $url;
    }

    private function readSettings(){
        $this->settings = parse_ini_file("settings.ini", true);
    }

    private function makeRequest($finaUrl){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $finaUrl);
        $res = curl_exec($ch);
        return $res;
    }

}