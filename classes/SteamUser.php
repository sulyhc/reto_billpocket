<?php


class SteamUser
{

    /**
     * @var defined global parameters as default url api and private key api
     */
    private $settings;

    function __construct()
    {
        //start steam settings for work with its API
        $this->readSettings();
    }

    /**
     * Main function for make request and fetch information from Steam Platform
     * @param $method String type of information for fetch
     * @param $params array dictionary with GET url parameters necessary for fetch information
     * @return Array information from Steam platform
     */
   public function fetchInfo($method='', $params=[])
    {

        $url = $this->assembleUrl($method, $params, true);
        $response = $this->makeRequest($url);
        return $response;
    }

    /**
     * Make url from array
     * @param $steamMethod type of action
     * @param array $params dictionary with parameters for send to steam api
     * @param bool $setKey enable private key injection to url parameters
     * @return String url
     */
    private function assembleUrl($steamMethod, array $params, $setKey = False)
    {
        $url = $this->settings['steam']['url'];
        if ($setKey) {
            $params = array_merge($params, array('key' => $this->settings['steam']['key']));
        }

        $urlParams = http_build_query($params);
        $url = str_replace('{interface}', $this->settings['steam_methods'][$steamMethod]['interface'], $url);
        $url = str_replace('{method}', $this->settings['steam_methods'][$steamMethod]['method'], $url);
        $url = str_replace('{version}', $this->settings['steam_methods'][$steamMethod]['version'], $url);
        $url = str_replace('{paramns}', $urlParams, $url);

        return $url;
    }

    /**
     * read settings from .ini file
     */
    private function readSettings()
    {
        $this->settings = parse_ini_file("settings.ini", true);
    }

    /**
     * Make curl request to Steam api
     * @param String $finaUrl API url of Steam
     * @return array response from Steam platform
     */
    private function makeRequest($finaUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $finaUrl);
        $res = curl_exec($ch);
        $json_res = json_decode($res, true);
        return $json_res;
    }

}