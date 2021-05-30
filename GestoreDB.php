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
	
	//Tutti i metodi che iniziano con "mostra" servono per stampare a schermo certi tag HTML e valori, o per leggere file HTML
	
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
	
	//funzione per sanificare gli input, evitando l'esecuzione di possibile codice malevolo
	private function clean_input($value){

		$bad_chars = array("{", "}", "(", ")", ";", ":", "<", ">", "/", "$");

		$value = str_ireplace($bad_chars, "", $value);

		$value = htmlentities($value);

		$value = strip_tags($value);

		return $value;
	}
	
	//Valida gli input dato il tipo di input per cui validarlo e il valore dell'input stesso come argomento
	private function val_input($tipo, $value){
		switch($tipo){
			case "email":
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
					die("Formato email non valido!");
				}
				break;
			case "text":
				if(!preg_match("/^[A-Za-zòàèù ]*$/", $value)){
					die("Formato testo non valido!");
				}
				break;
			case "number":
				if(!preg_match("/^[0-9]*$/", $value)){
					die("Formato numero non valido!");
				}
				break;
			case "alphanumeric":
				if(!preg_match("/^[A-Za-zòàèù0-9]*$/", $value)){
					die("Formato alfanumerico non valido!");
				}
				break;
			case "date":
				if(!preg_match("/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $value)){
					die("Formato data non valido");
				}
				break;
			case "num_civ":
				if(!preg_match("/^[0-9A-Z\/]+$/", $value)){
					die("Formato numero civico non valido");
				}
				break;
			case "num_carta":
				if(!preg_match("/^[0-9]{16}$/", $value)){
					die("Formato numero carta di credito non valido");
				}
				break;
			case "cod_carta":
				if(!preg_match("/^[0-9]{3}$/", $value)){
					die("Formato codice carta di credito non valido");
				}
				break;
		}
		
		return $value;
	}
	
	//Metodo per ottenere una lista di tutte le sedi, con id e indirizzo collegato
	private function getSedi(){
		$connessione = $this->connetti();
		
		$query = "SELECT id_sede, via, num_civ, comune, provincia FROM sede";
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			echo('<option value="' . $row["id_sede"] . '"> ' . $row["via"] . ' ' . $row["num_civ"] . ', ' . $row["comune"] . ', ' . $row["provincia"] . ' </option>');
		}
	}
	
	//Metodo per ottenere una lista di tutti i comuni di tutti i clienti non ripetuti
	private function getComuni(){
		$connessione = $this->connetti();
		
		$query = "SELECT comune FROM cliente GROUP BY comune";
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			echo('<option value="' . $row["comune"] . '"> ' . $row["comune"] . ' </option>');
		}
	}
	
	//Metodo per mostrare tutti i clienti in una tabella che corrispondono con il nome, cognome o comune inserito
	public function ricercaClienti(){
		
		$connessione = $this->connetti();
		
		if(isset($_GET["nome"])){
			
			$value = $this->clean_input($_GET["nome"]);
			
			$value = $this->val_input("text", $value);
			
			$query = 'SELECT nome,cognome,comune,id_cliente FROM cliente WHERE nome="' . $value . '"';
		}
		
		if(isset($_GET["cognome"])){
			
			$value = $this->clean_input($_GET["cognome"]);
			
			$value = $this->val_input("text", $value);
			
			$query = 'SELECT nome,cognome,comune,id_cliente FROM cliente WHERE cognome="' . $value . '"';
		}
		
		if(isset($_GET["comune"])){
			
			$value = $this->clean_input($_GET["comune"]);
			
			$value = $this->val_input("text", $value);
			
			$query = 'SELECT nome,cognome,comune,id_cliente FROM cliente WHERE comune="' . $value . '"';
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
		
		$value = $this->clean_input($_GET["sede"]);
		
		$value = $this->val_input("number", $value);
		
		$connessione = $this->connetti();
		
		$query = 'SELECT nome,cognome,comune,cod_fis FROM dipendente WHERE id_sede="' . $value . '"';
		
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
	
	//Metodo per mostrare un profilo dato un codice fiscale (nel caso di dipendenti) o un id cliente
	private function getProfilo($id){
		
		$value = $this->clean_input($id);
		
		$connessione = $this->connetti();
		
		if(isset($_GET["id_cliente"])){
			$value = $this->val_input("number", $value);
			$query = "SELECT * FROM cliente WHERE cliente.id_cliente = '" . $value ."'";
		}else{
			$value = $this->val_input("alphanumeric", $value);
			$query = "SELECT * FROM dipendente WHERE dipendente.cod_fis = '" . $value ."'";
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
	
	//Effettuo la registrazione
	public function registra(){
		
		//Sanificazione input
		foreach($_POST as $key => $value){
			$value = $this->clean_input($value);
		}
		
		//Inserisco tutti i dati in variabili dopo la validazione degli stessi
		$cod_fis	= $this->val_input("alphanumeric", $_POST["cod_fis"]);
		$nome		= $this->val_input("text", $_POST["nome"]);
		$cognome	= $this->val_input("text", $_POST["cognome"]);
		$email		= $this->val_input("email", $_POST["email"]);
		$via		= $this->val_input("text", $_POST["indirizzo"]);
		$num_civ	= $this->val_input("num_civ", $_POST["num_civ"]);
		$comune		= $this->val_input("text", $_POST["comune"]);
		$provincia	= $this->val_input("text", $_POST["provincia"]);
		$data_nasc	= $this->val_input("date", $_POST["data_nasc"]);
		$ruolo		= $this->val_input("text", $_POST["ruolo"]);
		$stipendio	= $this->val_input("number", $_POST["stipendio"]);
		$id_sede	= $this->val_input("number", $_POST["sede"]);
		$iban		= $this->val_input("alphanumeric", $_POST["iban"]);
		$pw			= sha1($_POST["password"]);
		
		//effettuo la connessione al database
		$connessione = $this->connetti();
		
		$query = 'INSERT INTO dipendente (cod_fis, nome, cognome, email, ruolo, stipendio, via, num_civ, comune, provincia, data_nasc, password, iban, id_sede)';
		$query .= 'VALUES(:cod_fis, :nome, :cognome, :email, :ruolo, :stipendio, :via, :num_civ, :comune, :provincia, :data_nasc, :password, :iban, :id_sede)';
		
		//preparo la query
		$sql = $connessione->prepare($query);
		//Per ogni "segnaposto" nella query, faccio riferimento ad una specifica variabile
		$sql->bindParam(":cod_fis", $cod_fis);
		$sql->bindParam(":nome", $nome);
		$sql->bindParam(":cognome", $cognome);
		$sql->bindParam(":email", $email);
		$sql->bindParam(":ruolo", $ruolo);
		$sql->bindParam(":stipendio", $stipendio);
		$sql->bindParam(":via", $via);
		$sql->bindParam(":num_civ", $num_civ);
		$sql->bindParam(":comune", $comune);
		$sql->bindParam(":provincia", $provincia);
		$sql->bindParam(":data_nasc", $data_nasc);
		$sql->bindParam(":password", $pw);
		$sql->bindParam(":iban", $iban);
		$sql->bindParam(":id_sede", $id_sede);
		
		//Eseguo la query
		$sql->execute();
		
		echo("Dipendente aggiunto!");
		
		$this->mostraHomePage();
		
		//Chiusura connessione
		$connessione = null;
	}
	
	//Effettuo il login
	public function login(){
		session_start();
		
		foreach($_POST as $key => $value){
			$value = $this->clean_input($value);	//Sanificazione input
		}
		
		$time = time();
		
		//Dopo 3 tentativi, bisogna aspettare 30 secondi
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
		$email = $this->val_input("email", $_POST["email"]);
		$pw = sha1($_POST["password"]);
		
		$connessione = $this->connetti();
		
		$query = 'SELECT cod_fis,email,password,nome,cognome,ruolo FROM dipendente';
		
		$response = $connessione->query($query);
		
		$data = $response->fetchAll();
		
		foreach($data as $row){
			if(($row['email'] == $email) && ($row['password'] == $pw)){ //Controllo se i dati coincidono
				echo("Login effettuato! Benvenuto " . $row["nome"] . " " . $row["cognome"] . "!");
				if(isset($_POST["ricordami"])){
					setcookie("email", $email, time()+60*60*24*30); //Setto il cookie con l'email se ha spuntato la checkbox "ricorda"
				}else{
					setcookie("email", "", time() - 3600);
				}
				//Salvo dati nelle variabili di sessione per identificarlo
				$_SESSION["id"] = $row["cod_fis"];
				$_SESSION["email"] = $row["email"];
				$_SESSION["ruolo"] = $row["ruolo"];
				$connessione = null;
				$this->mostraHomePage();
				return;
			}
		}
		
		//Se il login è fallito
		$connessione = null;
		
		//Controllo se ha sbagliato 3 volte e aumento il counter
		$this->controllaTentativi();
		
		//mostro messaggio di errore
		$_SESSION["message"] = "Login fallito, email o password non corretto";
		
		if(isset($_SESSION["id"]) && isset($_SESSION["email"])){
			unset($_SESSION["id"]);
			unset($_SESSION["email"]);
			unset($_SESSION["ruolo"]);
		}
		
		header( 'Location: login.php' );
	}
	
	//Aumento il counter dei tentativi fatti
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
	
	//Metodo per connettersi al DBMS
	private function connetti(){
		try{
			$host = 'mysql:dbname=elaborato_esame;host=127.0.0.1;port=3306';
			
			//Effetto la connessione
			$connection = new PDO($host, "root", "");
			
			//Setto la possibilità di vedere gli errori SQL via PHP
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//Se non esistono, creo tutte le tabelle del DB
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
							id_contratto INT(10) NOT NULL AUTO_INCREMENT,
							id_cliente INT(10) NOT NULL
							id_contatore INT(10) NULL , 
							id_serv INT(10) NOT NULL ,
							id_cisterna INT(10) NULL ,  
							tipo_pag ENUM("Carta credito","Conto corrente") NOT NULL , 
							IBAN VARCHAR(50) NULL DEFAULT NULL , 
							carta_cred VARCHAR(16) NULL DEFAULT NULL , 
							cod_cred VARCHAR(3) NULL DEFAULT NULL , 
							data_scad DATE NULL DEFAULT NULL ,
							PRIMARY KEY(id_contratto));

						CREATE TABLE IF NOT EXISTS cisterna (
							id_cisterna INT(10) NOT NULL,
							livello FLOAT(10) NOT NULL,
							capacita FLOAT(10) NOT NULL,
							via VARCHAR(30) NOT NULL,
							num_civ VARCHAR(10) NOT NULL, 
							comune VARCHAR(30) NOT NULL,
							provincia VARCHAR(30) NOT NULL,
							PRIMARY KEY(id_cisterna)
						);
						
						CREATE TABLE IF NOT EXISTS richiesta (
							id_richiesta INT(10) NOT NULL,
							via VARCHAR(30) NOT NULL,
							num_civ VARCHAR(10) NOT NULL,
							comune VARCHAR(30) NOT NULL,
							provincia VARCHAR(30) NOT NULL,
							data DATE NOT NULL,
							effettuato BOOLEAN NOT NULL,
							id_cliente INT(10) NOT NULL,
							id_serv INT(10) NOT NULL,
							PRIMARY KEY(id_richiesta)
						);

						ALTER TABLE contatore ADD CONSTRAINT "È posseduto da" FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE; 


						ALTER TABLE dipendente ADD CONSTRAINT "Lavora in" FOREIGN KEY (id_sede) REFERENCES sede(id_sede) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE sede ADD CONSTRAINT "Appartiene a" FOREIGN KEY (id_societa) REFERENCES societa(id_societa) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE sede_servizio ADD CONSTRAINT "Foreign_sede" FOREIGN KEY (id_sede) REFERENCES sede(id_sede) ON DELETE CASCADE ON UPDATE CASCADE; 
						ALTER TABLE sede_servizio ADD CONSTRAINT "Foreign_servizio" FOREIGN KEY (id_serv) REFERENCES servizio(id_serv) ON DELETE CASCADE ON UPDATE CASCADE; 

						ALTER TABLE consumo ADD CONSTRAINT "È misurato da" FOREIGN KEY (id_contatore) REFERENCES contatore(id_contatore) ON DELETE CASCADE ON UPDATE CASCADE;

						ALTER TABLE contratto ADD CONSTRAINT "Stipula" FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE;
						ALTER TABLE contratto ADD CONSTRAINT "Viene collegato" FOREIGN KEY (id_contatore) REFERENCES contatore(id_contatore) ON DELETE CASCADE ON UPDATE CASCADE; 
						ALTER TABLE contratto ADD CONSTRAINT "Fornisce" FOREIGN KEY (id_serv) REFERENCES servizio(id_serv) ON DELETE CASCADE ON UPDATE CASCADE;
						ALTER TABLE contratto ADD CONSTRAINT "Viene collegato 2" FOREIGN KEY (id_cisterna) REFERENCES cisterna(id_cisterna) ON DELETE CASCADE ON UPDATE CASCADE;
						
						ALTER TABLE richiesta ADD CONSTRAINT "Richiede" FOREIGN KEY (id_serv) REFERENCES servizio(id_serv) ON DELETE CASCADE ON UPDATE CASCADE; 
						ALTER TABLE richiesta ADD CONSTRAINT "È richiesto" da FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE;';
			$connection->exec($queryDB);
		}catch(PDOException $e){
			//Se ci sono eccezioni, mostro il messaggio d'errore
			echo("Connection error: ".$e->getMessage());
		}
		//echo("Connessione effettuata!");

		return $connection;
	}
}
