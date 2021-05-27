<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("GestoreDB.php");

session_start();

$gestore = new GestoreDB();

$dati = array('cod_fis', 'nome', 'cognome', 'email', 'indirizzo', 'num_civ', 'comune', 'provincia', 'data_nasc', 'password', 'ruolo', 'stipendio', 'iban', 'sede');

foreach($dati as $value){
	if(!isset($_POST[$value])) die("Alcuni dati non sono stati inseriti!");
}


$gestore->registra();