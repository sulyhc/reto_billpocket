<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 7/07/17
 * Time: 08:36 PM
 */
require_once 'classes/SteamUser.php';

$user_obj = new SteamUser();
$action = $_GET['action'];
switch ($action){
    case 'user_history':
        // try to get steamIDD from user nick and with that get user game list
        $nick = $_GET['id'];
        $param = ['vanityurl' => $nick];

        $response = $user_obj->fetchInfo('steamID',$param);
        if(!isset($response['response']['steamid'])){
            #display error message when is a invalid user
            die('nothing to display');
        }

       $steamID = $response['response']['steamid']; #set steamID
        #list user's games
        unset($param);
        $param = ['steamid'=>$steamID, 'include_appinfo'=>true, 'include_played_free_games' => true, 'format'=>'json'];
        $responseGames = $user_obj->fetchInfo('ownedGames', $param, true); #game list
        if(count($responseGames['response']) == 0){
            #default message for empty game list
            die('this player have not games');
        }
        $totalGames = $responseGames['response']['game_count'];
        $listGames = $responseGames['response']['games'];
        $results = file_get_contents('views/components/user_table.html'); #template of table
        #table content (html)
        $rows_content = "";
        $row = "<tr><td>{id}</td><td>{name}</td>
                <td><img src=\"http://media.steampowered.com/steamcommunity/public/images/apps/{id}/{logo}.jpg\" /></td>
                <td><button class='btn btn-success' onclick='displayModalGame(\"{steamID}\",\"{id}\")'>Ver Detalles</button></td></tr>";

        foreach ($listGames as $l) {
            #build table content using fetched list game
            $r = str_replace('{id}',$l['appid'], $row);
            $r = str_replace('{name}',$l['name'], $r);
            $r = str_replace('{logo}',$l['img_logo_url'], $r);
            $r = str_replace('{steamID}',$steamID, $r);
            $rows_content .= $r;
        }


        $results = str_replace('{num}', $totalGames, $results);
        $results = str_replace('{content}', $rows_content, $results);
        echo $results;
        break;

    case 'user_achievements':
    $steamId = $_GET['id'];
    $gameId = $_GET['game_id'];
    break;
}
