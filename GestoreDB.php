<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gestoredb
 *
 * @author user
 */
class GestoreDB {
	
	private function mostraTesta($titolo){
		$head=file("htmlContent/headTop.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $head as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
		echo($titolo);
		$head=file("htmlContent/headBottom.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $head as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	public function mostraRegistrazione(){
		$body=file("htmlContent/registrazioneTop.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
		
		$this->getSedi();
		
		$body=file("htmlContent/registrazioneBottom.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	public function mostraHomePage(){
		$this->mostraTesta("Home page");
		$this->mostraNavbar();
		
		echo("<img class='.img-fluid' style='max-width: 100%; height: auto;' src='images/homeImage.png'>");
		
		$this->mostraCoda();
	}
	
	private function mostraNavbar(){
		$body=file("htmlContent/navbarTop.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
		echo($_SESSION["email"]);
		$body=file("htmlContent/navbarBottom.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	private function mostraCoda(){
		$tail=file("htmlContent/tail.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $tail as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	private function mostraFormDipendentiTop(){
		$body=file("htmlContent/formDipendentiTop.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	private function mostraFormDipendentiBottom(){
		$body=file("htmlContent/formDipendentiBottom.html"); //leggo il file che contiene la parte di mezzo della mia pagina web
		foreach ( $body as $row ) {
			echo($row); //trasmetto la risposta come documento HTML (corpo)
		}
	}
	
	public function mostraProfilo($cod_fis){
		$this->mostraTesta("Profilo utente");
		$this->mostraNavbar();
		
		$this->getProfilo($cod_fis);
		
		$this->mostraCoda();
	}
	
	public function mostraRicercaClienti(){
		$this->mostraTesta("Ricerca clienti");
		$this->mostraNavbar();
		$this->mostraFormDipendentiTop();
		
		$this->getComuni();
		
		$this->mostraFormDipendentiBottom();
		
		$this->mostraCoda();
	}
	
	public function mostraDipendenti(){
		$this->mostraTesta("Mostra dipendenti");
		$this->mostraNavbar();
		
		//Stampo il ocontenuto HTML per mostrare i pagamenti
		echo("<div class='container-md'>");
		echo("<div class='row'>");
		echo("<div class='col-2'> <label for='sede'> Sede: </label> </div>");
		echo("<div class='col-2'> <select id='sede' onchange='getDipendenti(this)'>");
		echo("<option value='' id='delete'> </option>");
		$this->getSedi();
		echo("</select> </div>");
		echo("</div>");
		echo("</div>");
		echo("<div class='container-md'>");
		echo("<div id='dipendenti' class='row col-12'>");
		echo("</div>");
		echo("</div>");
		
		$this->mostraCoda();
	}
	
	public function mostraAggiungiDipendente(){
		$this->mostraTesta("Aggiungi dipendente");
		$this->mostraNavbar();
		
		$this->mostraRegistrazione();
		
		$this->mostraCoda();
	}
	
	private function clean_input($value){

		$bad_chars = array("{", "}", "(", ")", ";", ":", "<", ">", "/", "$");

		$value = str_ireplace($bad_chars, "", $value);

		$value = htmlentities($value);

		$value = strip_tags($value);

		if(get_magic_quotes_gpc()){
			$value = stripslashes($value);
		}

		return $value;
	}
	
	private function getSedi(){
		$connessione = $this->connetti();
		
		$query = "SELECT id_sede, via, num_civ, comune, provincia FROM sede";
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			echo('<option value="' . $row["id_sede"] . '"> ' . $row["via"] . ' ' . $row["num_civ"] . ', ' . $row["comune"] . ', ' . $row["provincia"] . ' </option>');
		}
	}
	
	private function getComuni(){
		$connessione = $this->connetti();
		
		$query = "SELECT comune FROM cliente GROUP BY comune";
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			echo('<option value="' . $row["comune"] . '"> ' . $row["comune"] . ' </option>');
		}
	}
	
	public function ricercaClienti(){
		
		$connessione = $this->connetti();
		
		if(isset($_GET["nome"])){
			$query = "SELECT nome,cognome,comune,id_cliente FROM cliente WHERE nome='" . $_GET["nome"] . "'";
		}
		
		if(isset($_GET["cognome"])){
			$query = "SELECT nome,cognome,comune,id_cliente FROM cliente WHERE cognome='" . $_GET["cognome"] . "'";
		}
		
		if(isset($_GET["comune"])){
			$query = "SELECT nome,cognome,comune,id_cliente FROM cliente WHERE comune='" . $_GET["comune"] . "'";
		}
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		echo("<table class='table'>");
		echo("<tr>");
		echo("<th> Nome </th>");
		echo("<th> Cognome </th>");
		echo("<th> Comune </th>");
		echo("<th> </th>");
		echo("</tr>");
		foreach($data as $row){
			echo("<tr>");
			echo('<td>' . $row["nome"] . ' </td>');
			echo('<td>' . $row["cognome"] . ' </td>');
			echo('<td>' . $row["comune"] . ' </td>');
			echo('<td> <a href="mostraProfilo.php?id_cliente=' . $row["id_cliente"] . '"> Mostra </a> </td>');
			echo("</tr>");
		}
		
	}
	
	public function getDipendenti(){
		session_start();
		
		$connessione = $this->connetti();
		
		$query = "SELECT nome,cognome,comune,cod_fis FROM dipendente WHERE id_sede='" . $_GET["sede"] . "'";
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		echo("<table class='table'>");
		echo("<tr>");
		echo("<th> Nome </th>");
		echo("<th> Cognome </th>");
		echo("<th> Comune </th>");
		echo("<th> </th>");
		echo("</tr>");
		foreach($data as $row){
			echo("<tr>");
			echo('<td>' . $row["nome"] . ' </td>');
			echo('<td>' . $row["cognome"] . ' </td>');
			echo('<td>' . $row["comune"] . ' </td>');
			echo('<td> <a href="mostraProfilo.php?cod_fis=' . $row["cod_fis"] . '"> Mostra </a> </td>');
			echo("</tr>");
		}
		
		echo("</table>");
	}
	
	private function getProfilo($id){
		
		$connessione = $this->connetti();
		if(isset($_GET["id_cliente"])){
			$query = "SELECT * FROM cliente WHERE cliente.id_cliente = '" . $id ."'";
		}else{
			$query = "SELECT * FROM dipendente WHERE dipendente.cod_fis = '" . $id ."'";
		}
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		echo("<div class='container-md'>");
		foreach($data as $row){
			unset($row["id_cliente"]);
			unset($row["password"]);
			unset($row["id_sede"]);
			for($i = 0;$i<15;$i++){
				unset($row[$i]);
			}
			foreach($row as $key => $value){
				echo("<div class='row'>");
				echo("<label class='col-12'>" . $key . " : " . $value . "</label>");
				echo("</div>");
			}
		}
		echo("</div>");
	}
	
	public function registra(){
		foreach($_POST as $key => $value){
			$value = $this->clean_input($value);
		}
		
		$cod_fis	= $_POST["cod_fis"];
		$nome		= $_POST["nome"];
		$cognome	= $_POST["cognome"];
		$email		= $_POST["email"];
		$via		= $_POST["indirizzo"];
		$num_civ	= $_POST["num_civ"];
		$comune		= $_POST["comune"];
		$provincia	= $_POST["provincia"];
		$data_nasc	= $_POST["data_nasc"];
		$ruolo		= $_POST["ruolo"];
		$stipendio	= $_POST["stipendio"];
		$id_sede	= $_POST["sede"];
		$iban		= $_POST["iban"];
		$pw			= sha1($_POST["password"]);
		
		$connessione = $this->connetti();
		
		$query = 'INSERT INTO dipendente (cod_fis, nome, cognome, email, ruolo, stipendio, via, num_civ, comune, provincia, data_nasc, password, iban, id_sede)';
		$query .='VALUES("'.$cod_fis.'","'.$nome.'","'.$cognome.'","'.$email.'","'.$ruolo.'",'.$stipendio.',"'.$via.'","'.$num_civ.'","'.$comune.'","'.$provincia.'","'.$data_nasc.'","'.$pw.'","'. $iban .'",'.$id_sede.')';
		
		$connessione->exec($query);
		
		echo("Dipendente aggiunto!");
		
		$this->mostraHomePage();
		
		//Chiusura connessione
		$connessione = null;
	}
	
	public function login(){
		session_start();
		
		foreach($_POST as $key => $value){
			$value = $this->clean_input($value);
		}
		
		$time = time();
		
		if(!empty($_SESSION["tempoInizio"])){
			$intervallo = ($time-$_SESSION["tempoInizio"]) % 60;
			
			if( $intervallo < 30){
				echo("Troppi tentativi, riprova tra " . (30 - $intervallo) . " secondi");
				return;
			}else{
				$_SESSION["nTent"] = 0;
				unset($_SESSION["tempoInizio"]);
			}
		}
		$email = $_POST["email"];
		$pw = sha1($_POST["password"]);
		
		$connessione = $this->connetti();
		
		$query = 'SELECT cod_fis,email,password,nome,cognome,ruolo FROM dipendente';
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			if(($row['email'] == $email) && ($row['password'] == $pw)){
				echo("Login effettuato! Benvenuto " . $row["nome"] . " " . $row["cognome"] . "!");
				if(isset($_POST["ricordami"])){
					setcookie("email", $email, time()+60*60*24*30);
				}else{
					setcookie("email", "", time() - 3600);
				}
				$_SESSION["id"] = $row["cod_fis"];
				$_SESSION["email"] = $row["email"];
				$_SESSION["ruolo"] = $row["ruolo"];
				$connessione = null;
				$this->mostraHomePage();
				return;
			}
		}
		
		$connessione = null;
		
		$this->controllaTentativi();
		
		$_SESSION["message"] = "Login fallito, email o password non corretto";
		
		if(isset($_SESSION["id"]) && isset($_SESSION["email"])){
			unset($_SESSION["id"]);
			unset($_SESSION["email"]);
		}
		
		header( 'Location: login.php' );
	}
	
	private function controllaTentativi(){
		if(empty($_SESSION["nTent"])){
			$_SESSION["nTent"] = 0;
		}
		
		$_SESSION["nTent"]++;
		
		if($_SESSION["nTent"] == 3){
			$_SESSION["tempoInizio"] = time();
			echo("\nTroppi tentativi, riprova tra 30 secondi");
		}
	}
	
	
	private function connetti(){
		try{
			$host = 'mysql:dbname=elaborato_esame;host=127.0.0.1;port=3306';
			
			$connection = new PDO($host, "root", "");
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$queryDB = ' CREATE TABLE IF NOT EXISTS societa (
						 id_societa INT(10) NOT NULL AUTO_INCREMENT ,
						 partita_iva VARCHAR(11) NOT NULL ,
						 rag_soc VARCHAR(50) NOT NULL ,
						 email VARCHAR(50) NOT NULL ,
						 cap_soc INT(15) NOT NULL ,
						 via VARCHAR(30) NOT NULL ,
						 num_civ VARCHAR(10) NOT NULL ,
						 citta VARCHAR(30) NOT NULL ,
						 comune VARCHAR(30) NOT NULL ,
						 tel VARCHAR(30) NOT NULL ,
						 PRIMARY KEY (id_societa)); 

						CREATE TABLE IF NOT EXISTS sede ( 
							id_sede INT(10) NOT NULL AUTO_INCREMENT ,
						 via VARCHAR(30) NOT NULL ,
						 num_civ VARCHAR(10) NOT NULL ,
						 comune VARCHAR(30) NOT NULL ,
						 provincia VARCHAR(30) NOT NULL ,
						 id_societa INT(10) NOT NULL ,
						 PRIMARY KEY (id_sede)); 

						CREATE TABLE IF NOT EXISTS dipendente ( 
							cod_fis VARCHAR(16) NOT NULL ,
						 nome VARCHAR(30) NOT NULL ,
						 cognome VARCHAR(30) NOT NULL ,
						 email VARCHAR(50) NOT NULL ,
						 ruolo ENUM("Amministratore","Caporeparto","Lavoratore") CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
						 stipendio INT(10) NOT NULL ,
						 via VARCHAR(30) NOT NULL ,
						 num_civ VARCHAR(10) NOT NULL ,
						 comune VARCHAR(30) NOT NULL ,
						 provincia VARCHAR(30) NOT NULL ,
						 iban VARCHAR(50) NOT NULL ,
						 data_nasc DATE NOT NULL ,
						 password VARCHAR(100) NOT NULL,
						 id_sede INT(10) NOT NULL ,
						 PRIMARY KEY (cod_fis)); 

						CREATE TABLE IF NOT EXISTS servizio ( 
							id_serv INT(10) NOT NULL AUTO_INCREMENT ,
						 tipo VARCHAR(30) NOT NULL ,
						 costo_fisso FLOAT(10) NOT NULL ,
						 costo_unit FLOAT(10) NOT NULL ,
						 PRIMARY KEY (id_serv)); 

						CREATE TABLE IF NOT EXISTS contatore ( 
							id_contatore INT(10) NOT NULL AUTO_INCREMENT ,
						 via VARCHAR(30) NOT NULL ,
						 num_civ VARCHAR(10) NOT NULL ,
						 comune VARCHAR(30) NOT NULL ,
						 provincia VARCHAR(30) NOT NULL ,
						 PRIMARY KEY (id_contatore)); 

						CREATE TABLE IF NOT EXISTS sede_servizio ( 
						 id_sede INT(10) NOT NULL ,
						 id_serv INT(10) NOT NULL ,
						 PRIMARY KEY( id_sede, id_serv)); 

						CREATE TABLE IF NOT EXISTS cliente (
							id_cliente INT(10) NOT NULL AUTO_INCREMENT, 
						 cod_fis VARCHAR(16) NOT NULL,
						 nome VARCHAR(30) NOT NULL ,
						 cognome VARCHAR(30) NOT NULL ,
						 email VARCHAR(50) NOT NULL ,
						 telefono VARCHAR(30) NOT NULL ,
						 via VARCHAR(30) NOT NULL ,
						 num_civ VARCHAR(10) NOT NULL ,
						 comune VARCHAR(30) NOT NULL ,
						 provincia VARCHAR(30) NOT NULL ,
						 data_nasc DATE NOT NULL ,
						 password VARCHAR(100) NOT NULL,
						 PRIMARY KEY (id_cliente)); 

						CREATE TABLE IF NOT EXISTS consumo ( 
							id_consumo INT(10) NOT NULL AUTO_INCREMENT ,
						 cons_totale FLOAT(10) NOT NULL ,
						 data DATE NOT NULL ,
						 id_contatore INT(10) NOT NULL,
						 PRIMARY KEY (id_consumo));

						CREATE TABLE IF NOT EXISTS contratto ( 
							id_cliente INT(10) NOT NULL
							id_contatore INT(10) NULL , 
							id_serv INT(10) NOT NULL ,
							id_cisterna INT(10) NULL ,  
							tipo_pag ENUM("Carta credito","Conto corrente") NOT NULL , 
							IBAN VARCHAR(50) NULL DEFAULT NULL , 
							carta_cred VARCHAR(16) NULL DEFAULT NULL , 
							cod_cred VARCHAR(3) NULL DEFAULT NULL , 
							data_scad DATE NULL DEFAULT NULL ,
							PRIMARY KEY(id_cliente, id_serv));

						CREATE TABLE IF NOT EXISTS cisterna (
							id_cisterna INT(10) NOT NULL,
							livello FLOAT(10) NOT NULL,
							capacita FLOAT(10) NOT NULL,
							via VARCHAR(30) NOT NULL,
							num_civ VARCHAR(10) NOT NULL, 
							comune VARCHAR(30) NOT NULL,
							provincia VARCHAR(30) NOT NULL,
							PRIMARY KEY(id_cisterna)
						)

						ALTER TABLE contatore ADD CONSTRAINT "È posseduto da" FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE; 


						ALTER TABLE dipendente ADD CONSTRAINT "Lavora in" FOREIGN KEY (id_sede) REFERENCES sede(id_sede) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE sede ADD CONSTRAINT "Appartiene a" FOREIGN KEY (id_societa) REFERENCES societa(id_societa) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE sede_servizio ADD CONSTRAINT "Foreign_sede" FOREIGN KEY (id_sede) REFERENCES sede(id_sede) ON DELETE CASCADE ON UPDATE CASCADE; 
						ALTER TABLE sede_servizio ADD CONSTRAINT "Foreign_servizio" FOREIGN KEY (id_serv) REFERENCES servizio(id_serv) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE consumo ADD CONSTRAINT "È misurato da" FOREIGN KEY (id_contatore) REFERENCES contatore(id_contatore) ON DELETE CASCADE ON UPDATE CASCADE;

						ALTER TABLE contratto ADD CONSTRAINT "Stipula" FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE;
						ALTER TABLE contratto ADD CONSTRAINT "Viene collegato" FOREIGN KEY (id_contatore) REFERENCES contatore(id_contatore) ON DELETE CASCADE ON UPDATE CASCADE; 
						ALTER TABLE contratto ADD CONSTRAINT "Fornisce" FOREIGN KEY (id_serv) REFERENCES servizio(id_serv) ON DELETE CASCADE ON UPDATE CASCADE;
						ALTER TABLE contratto ADD CONSTRAINT "Viene collegato 2" FOREIGN KEY (id_cisterna) REFERENCES cisterna(id_cisterna) ON DELETE CASCADE ON UPDATE CASCADE;';
			$connection->exec($queryDB);
		}catch(PDOException $e){
			echo("Connection error: ".$e->getMessage());
		}
		//echo("Connessione effettuata!");

		return $connection;
	}
}
