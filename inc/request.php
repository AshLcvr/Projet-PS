<?php

function recipeAPIbyFirstLetter( $firstletter = 'a'){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.themealdb.com/api/json/v1/1/search.php?f=" .$firstletter,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    return $response;
}

function recipeAPIbyID($id){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.themealdb.com/api/json/v1/1/lookup.php?i=$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    return $response;
}


function getFetchAllof($table, $column, $value){
    global $pdo;
    $sql = "SELECT * FROM $table WHERE  $column = '$value' ";
    $query = $pdo->prepare($sql);
    $query -> execute();
    return $query->fetchAll();
}

function getFetchof($table, $column, $value){
    global $pdo;
    $sql = "SELECT * FROM $table WHERE  $column = '$value' ";
    $query = $pdo->prepare($sql);
    $query -> execute();
    return $query->fetch();
}


function getSimplePDO($table){
    global $pdo;
    $sql = "SELECT * FROM $table ";
    $query = $pdo->prepare($sql);
    $query -> execute();
    return $query->fetch();
}

function getSelectPDO($table, $element){
    global $pdo;
    $sql = "SELECT $element FROM $table ";
    $query = $pdo->prepare($sql);
    $query -> execute();
    return $query->fetchAll();
}

function bind($val){
    echo '$query -> bindValue(\':'.$val.'\', $'.$val.', PDO::PARAM_STR)';
}

