/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function openPage(page){
	window.location.assign(page);
}

function toggleRicerca(value){
	var nome = $("#containerNome");
	var cognome = $("#containerCognome");
	var comune = $("#containerComune");
	
	switch(value){
		case "nome":
			nome.show();
			cognome.hide();
			comune.hide();
			break;
			
		case "cognome":
			nome.hide();
			cognome.show();
			comune.hide();
			break;
			
		case "comune":
			nome.hide();
			cognome.hide();
			comune.show();
			break;
	}
}

function getPagamenti(select){
	$("#delete").remove();
	var serv = select.value;
	
	$.ajax({
		url : "getPagamenti.php",
		method : "GET",
		data : { servizio :  serv },
		success : function(data) {
			$("#pagamenti").html(data);
		}
	});
}

function getDipendenti(select){
	$("#delete").remove();
	var valSede = select.value;
	
	$.ajax({
		url : "getDipendenti.php",
		method : "GET",
		data : { sede :  valSede },
		success : function(data) {
			$("#dipendenti").html(data);
		}
	});
}

function ricercaClienti(string){
	switch(string){
		case "nome":
			
			valNome = $("#nome").val();
			
			$.ajax({
				url : "ricercaClienti.php",
				method : "GET",
				data : { nome :  valNome },
				success : function(data) {
					$("#clienti").html(data);
				}
			});
			
			break;
			
		case "cognome":
			
			valCognome = $("#cognome").val();
			
			$.ajax({
				url : "ricercaClienti.php",
				method : "GET",
				data : { cognome :  valCognome },
				success : function(data) {
					$("#clienti").html(data);
				}
			});
			
			break;
			
		case "comune":
			
			$("#delete").remove();
			
			valComune = $("#comune").val();
			
			$.ajax({
				url : "ricercaClienti.php",
				method : "GET",
				data : { comune :  valComune },
				success : function(data) {
					$("#clienti").html(data);
				}
			});
			
			break;
			
	}
}