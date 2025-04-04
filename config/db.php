<?php

date_default_timezone_set("America/Mexico_City");
require_once(__DIR__ . "/config.php");

$MYSQLI = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);

try{
    if ($MYSQLI->connect_error) {
        die("Error al conectar a la base de datos: " . $MYSQLI->connect_error);
    }

}
catch(\Throwable $th){
    throw $th->getMessage();
}
