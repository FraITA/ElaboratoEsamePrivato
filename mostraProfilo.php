<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("GestoreDB.php");

session_start();

$gestore = new GestoreDB();

if(isset($_GET["id_cliente"])){
	$gestore->mostraProfilo($_GET["id_cliente"]);
	return;
}

if(isset($_GET["cod_fis"])){
	$gestore->mostraProfilo($_GET["cod_fis"]);
	return;
}

if(isset($_SESSION["id"])){
	$gestore->mostraProfilo($_SESSION["id"]);
}else{
	die("Accesso negato");
}