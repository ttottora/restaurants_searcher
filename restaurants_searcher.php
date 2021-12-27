<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$KEYID = "b457628aaefd4f18";
$HIT_PER_PAGE = 100;
$PREF = "Z011";
$FREEWORD = "渋谷駅";
$FORMAT = "json";

$PARAMS = ["key"=> $KEYID, "count"=>$HIT_PER_PAGE, "pref"=>$PREF, "keyword"=>$FREEWORD, "format"=>$FORMAT];

function write_data_to_csv($params){
    
    $restaurants = [["名称","営業日","住所","アクセス"]];
    $client = new Client();
    try{
        $json_res = $client->request('GET', "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/", ['query' => $params])->getBody();
    }catch(Exception $e){
        return print("エラーが発生しました。APIのURLを確認してください。");
    }
    $response = json_decode($json_res,true);
    
    if(isset($response["results"]["error"])){
        return(print("エラーが発生しました。APIのパラメータを確認してください。"));
    }
    
    foreach($response["results"]["shop"] as $restaurant){
        $rest_info = [$restaurant["name"],$restaurant["open"],$restaurant["address"],$restaurant["access"]];
        $restaurants[] = $rest_info;
    }
    $handle = fopen("restaurants_list2.csv", "wb");
    
    foreach ($restaurants as $values){

        fputcsv($handle, $values);
    }

    fclose($handle);
    return print_r($restaurants);
}

write_data_to_csv($PARAMS);

?>