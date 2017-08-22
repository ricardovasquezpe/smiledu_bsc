function init(){
	initButtonLoad('botonEVZ','botonIn','botonIC','botonEZR');
}

function initMain(){
	getDataLineasEstrat();
	setDivHeight();
	
	if($( window ).width() <= 531){
		$('#descCMando').text("C. Mando");
	}else{
		$('#descCMando').text("Cuadro de mando");
	}
	
	$( window ).resize(function() {
		if($( window ).width() <= 531){
			$('#descCMando').text("C. Mando");
		}else{
			$('#descCMando').text("Cuadro de mando");
		}
	});
}

function getDataLineasEstrat(){
	$.ajax({
		url  : 'c_linea_estrategica/getGraficosLineasEstrat', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		if(result.dorado == 1){
			$('.linEst'+100).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png" style="padding-top:15vh;padding-left:4vh"	>');
		}else{
			initGauge(parseInt(result.porcent_general, 10),100,result.porcent_general_amarillo,result.porcent_general_verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
			$("#linEst100").attr("data-title", "Zona 1: A / Zona 2: B / Zona 3: C");
		}
		var color = null;
		if(result.backgroundAvantgard == 'fondoRojo'){
			color = '#FF3D00';
		} else if(result.backgroundAvantgard == 'fondoAmarillo'){
			color = '#FFCA28';
		} else{
			color = '#4CB5AB';
		}
		$('#colorBarraGeneral').css('background-color',color);
		$("#copasTotal").text(" "+result.dorados);
		$('#portada').addClass(result.backgroundAvantgard);
		var lista = JSON.parse(result.porcent_lineas_num);
	 	  for(var i=0;i<lista.length;i++){
		        var porcentaje = lista[i].porcentaje;
		        var amarillo   = lista[i].p_amarillo;
		        var verde      = lista[i].p_verde;
		        var dorado     = lista[i].dorado;
		        $('#barra'+i).addClass(lista[i].color);

		        if(dorado == 1){
		        	$('.linEst'+i).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect"></i>');
		        	$('#barra'+(i)).css("display", "none");
		        }else{
		        	initGauge(parseInt(porcentaje, 10),i,amarillo,verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
		        }
	 	  }
	 	 $('.highcharts-tracker').find("tspan").append(' %');
	 	 
	 	/*lineas      = JSON.parse(result.arrayNombreLineas);
	 	porcentajes = JSON.parse(result.arrayPorcentajeLineas);
	 	$(function () {
	 	    Highcharts.chart('grafico_barras', {
	 	        chart: {
	 	            type: 'column'
	 	        },
	 	        title: {
	 	            text: ''
	 	        },
	 	        xAxis: {
	 	            categories: lineas,
	 	            crosshair: true
	 	        },
	 	        yAxis: {
	 	            min: 0,
	 	            max : 100,	 	            
	 	        },
	 	        series: [{
	 	        	name : 'Porcentaje (%)',
	 	            data: porcentajes,
	 	            pointWidth: 25

	 	        }]
	 	    });
	 	});*/
	 	 
	});
}

function getObjetivosByLinea(data){
	addLoadingButton('botonIn');
	$('#gauges_objetivos').html('');
	modal('gaugesObjetivos');
	setTimeout(function(){
		var pathArray = window.location.pathname.split( '/' );
		varpath = "getOBjetivosByLineaEstrat";
		if(pathArray.length == 3){
			varpath = "c_linea_estrategica/getOBjetivosByLineaEstrat";
		}
		$.ajax({
			data : { id_lineaEstrat  : data},  
			url  : "c_linea_estrategica/getOBjetivosByLineaEstrat", 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			result = JSON.parse(data);
			$('#gauges_objetivos').html(result.porcent_objetivos);

		 	var lista = JSON.parse(result.porcent_objetivos_num);
		 	for(var i=0;i<lista.length;i++){
		 		var porcentaje = lista[i].porcentaje;
		        var amarillo   = lista[i].p_amarillo;
		        var verde      = lista[i].p_verde;
		        var dorado     = lista[i].dorado;
		        if(dorado == 1){
		        	$('.linEst'+(i+10)).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect" style="height:40%"></i>');
		        	$('#barra'+(i+10)).css("display", "none");
		        }else{
		        	initGauge(parseInt(porcentaje, 10),i+10,amarillo,verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
		        }
		        $('#barra'+(i+10)).addClass(lista[i].color);
		        
		        $('.highcharts-data-labels g rect').css('display','none');
		        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        $('.highcharts-series-group circle').css('fill','#959595');
                $('[data-toggle="tooltip"]').tooltip(); 
		        $('.linEst'+(i+10)).find('.highcharts-tracker').find("tspan").append(' %');
		        $('.linEst'+(i+10)).find(".highcharts-yaxis-labels").find("text").find("tspan").append("%");
			}
		 	stopLoadingButton('botonIn');
		 	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
			$('.highcharts-tracker').find("tspan").css('font-size','15px');
			$('.highcharts-tracker').find("tspan").css('fill','#959595');
			componentHandler.upgradeAllRegistered();
		});
		}, 150);
}

$(window).on('load resize', function(){
    setDivHeight();     
    $('.highcharts-yaxis-labels').find("text").css('font-size','7px');
	$('.highcharts-tracker').find("tspan").css('font-size','15px');
	$('.highcharts-tracker').find("tspan").css('fill','#959595');
});

function goToCategorias(data){
	$.ajax({
		data : { id_objetivo  : data},  
		url  : 'c_linea_estrategica/goToCategorias', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			location.href = data;
		}
	});
}

var cons_id_linea_est   = null;
var cons_cont_linea_est = null;
var cons_pos_linea_est  = null;
function openEditarValorAmarilloLinea(data, cont, pos) {
	cons_id_linea_est   = data;
	cons_cont_linea_est = cont;
	cons_pos_linea_est  = pos;
	$.ajax({
		data : { idLinea : data,
				 idCont  : cont,
				 pos     : pos}, 
		url  : 'c_linea_estrategica/getValorAmarilloLinea', 
		async: false,
		type : 'POST'
	}).done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			result = JSON.parse(data);
			if(result.error == 1) {
				msj('success', data.msj , null);
			} else if(result.error == 0) {
				modal('modalEditarFlgAmarilloLinea');
				setearInput("valorAmarilloLinea", result.valorAmarillo);
				setearInput("valorMeta_LE", result.meta_LE);
			}
		}
	});
}

function editarValorZonaRiesgoLineaEst() {
	addLoadingButton('botonEVZ');
	Pace.restart();
	Pace.track(function() {
		var valorAmarillo = $("#valorAmarilloLinea").val();
		var valorMeta     = $("#valorMeta_LE").val();
		if(!valorAmarillo || !valorMeta ) {
			msj('error', 'Ingrese los valores');
			return;
		}
		if(!isNumerico(valorAmarillo)) {
			msj('error', 'La zona de riesgo debe ser un n&uacute;mero');
			return;
		}
		if(!isNumerico(valorMeta)) {
			msj('error', 'La meta debe ser un n&uacute;mero');
			return;
		}
		if(valorAmarillo <= 0) {
			msj('error', 'La zona de riesgo tiene que ser mayor a cero');
			return;
		}
		if(valorMeta <= 0) {
			msj('error', 'La meta tiene que ser mayor a cero');
			return;
		}
		if(valorAmarillo >= valorMeta) {
			msj('error', 'La zona de riesgo tiene que ser menor a la meta');
			return;
		}
		if(valorMeta > 100) {
			msj('error', 'La meta no puede ser mayor a 100%');
			return;
		}
		$.ajax({
	        data: {valor    : valorAmarillo,
	        	   meta     : valorMeta,
	        	   id_Linea : cons_id_linea_est,
	        	   idCont   : cons_cont_linea_est,
	        	   pos 		: cons_pos_linea_est},
	        url: "c_linea_estrategica/editValorAmarilloLinea",
	        type: 'POST'
	  	}).done(function(data) {
	  		if(data == "") {
				location.reload();
			} else {
		  		result = JSON.parse(data);
		  		if(result.error == 1) {
		  			stopLoadingButton('botonEVZ');
		  			msj('success', result.msj);
				} else {
					var lista = JSON.parse(result.porcent_lineas_num);
			        var porcentaje = lista.porcentaje;
			        var amarillo   = lista.p_amarillo;
			        var verde      = lista.p_verde;
			        var aux 	   = lista.aux;
			        initGauge(parseInt(porcentaje, 10),result.posicion,amarillo,verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
			        $('#barra'+result.posicion).removeAttr('class');
			        $('#barra'+result.posicion).attr('class', 'mdl-bar_state barraEstado '+lista.color);
			        $('.highcharts-yaxis-labels').find("text").css('font-size','7px');
		        	$('.highcharts-tracker').find("tspan").css('font-size','15px');
		        	$('.highcharts-tracker').find("tspan").css('fill','#959595');
		        	$('.linEst'+result.posicion).find('.highcharts-tracker').find("tspan").append(' %');
		        	
		        	$('.highcharts-data-labels g rect').css('display','none');
		        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        	$('.highcharts-series-group circle').css('fill','#959595');
		        	
		        	msj('success', result.msj);
		        	modal('modalEditarFlgAmarilloLinea');
		        	stopLoadingButton('botonEVZ');
		        	$('.linEst'+result.posicion).find(".highcharts-yaxis-labels").find("text").find("tspan").append("%");
				}
		  		stopLoadingButton('botonEVZ');
			}
        	stopLoadingButton('botonEVZ');
	    });
	});
}

var cons_id_objetivo = null;
var cons_cont_objetivo = null;
var cons_pos_objetivo = null;
function openEditarValorAmarilloObjetivo(dataL, dataO, cont, pos){
	cons_id_objetivo   = dataO;
	cons_cont_objetivo = cont;
	cons_pos_objetivo  = pos;
	cons_id_linea_est  = dataL;
	$.ajax({
		data : { idLinea    : dataL,
			     idObjetivo : dataO,
				 idCont     : cont,
				 pos        : pos}, 
		url  : 'c_linea_estrategica/getValorAmarilloObjetivo', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 1){
				mostrarNotificacion('success', data.msj , null);
			} else if(result.error == 0){
				modal('modalEditarFlgAmarilloObjetivo');
				setearInput("valorAmarilloObjetivo", result.valorAmarillo);
				setearInput("valorVerdeObjetivo", result.valorVerde);
			}
		}
	});
}

function editarValorZonRiesgoObjetivo(){
	addLoadingButton('botonEZR');
	Pace.restart();
	Pace.track(function() {
		var valorAmarillo = $("#valorAmarilloObjetivo").val();
		var valorMeta     = $("#valorVerdeObjetivo").val();
		if(!valorAmarillo || !valorMeta ) {
			msj('error', 'Ingrese los valores');
			return;
		}
		if(!isNumerico(valorAmarillo)) {
			msj('error', 'La zona de riesgo debe ser un n&uacute;mero');
			return;
		}
		if(!isNumerico(valorMeta)) {
			msj('error', 'La meta debe ser un n&uacute;mero');
			return;
		}
		if(valorAmarillo <= 0) {
			msj('error', 'La zona de riesgo tiene que ser mayor a cero');
			return;
		}
		if(valorMeta <= 0) {
			msj('error', 'La meta tiene que ser mayor a cero');
			return;
		}
		if(valorAmarillo >= valorMeta) {
			msj('error', 'La zona de riesgo tiene que ser menor a la meta');
			return;
		}
		if(valorMeta > 100) {
			msj('error', 'La meta no puede ser mayor a 100%');
			return;
		}
		addLoadingButton('botonEZR');
		$.ajax({
	        data: {valor            : valorAmarillo,
	        	   meta             : valorMeta,
	        	   id_Linea         : cons_id_linea_est,
	        	   cons_id_objetivo : cons_id_objetivo,
	        	   posicion         : cons_pos_objetivo,
	        	   idCont           : cons_cont_objetivo},
	        url: "c_linea_estrategica/editValorAmarilloObjetivo",
	        type: 'POST'
	  	}).done(function(data) {
	  		if(data == '') {
				location.reload();
			} else {
		  		result = JSON.parse(data);
		  		if(result.error == 1) {
		  			stopLoadingButton('botonEZR');
		  			msj('success', result.msj);
		  		} else {
		  			var lista = JSON.parse(result.porcent_objetivos_num);
			 		var porcentaje = lista.porcentaje;
			        var amarillo   = lista.p_amarillo;
			        var verde      = lista.p_verde;
			        //$('.backCirc'+(result.posicion)).css('background-color',lista.color);
			        initGauge(parseInt(porcentaje, 10),result.posicion,amarillo,verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
			        $('#barra'+result.posicion).removeAttr('class');
			        $('#barra'+result.posicion).attr('class', 'mdl-bar_state barraEstado '+lista.color);
			        
			        $('.highcharts-data-labels g rect').css('display','none');
			        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
			        $('.highcharts-series-group circle').css('fill','#959595');
				 	
				 	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
					$('.highcharts-tracker').find("tspan").css('font-size','15px');
					$('.highcharts-tracker').find("tspan").css('fill','#959595');
					$('.linEst'+result.posicion).find('.highcharts-tracker').find("tspan").append(' %');
					$('.linEst'+result.posicion).find(".highcharts-yaxis-labels").find("text").find("tspan").append("%");
					
					modal('modalEditarFlgAmarilloObjetivo');
			  		stopLoadingButton('botonEZR');
					msj('success', result.msj);
		  		}
		  		stopLoadingButton('botonEZR');
			}
	  		stopLoadingButton('botonEZR');
	     });
	 });
}

function openEditarValorAmarilloGeneral(data){
	$.ajax({ 
		url  : 'c_linea_estrategica/getValorAmarilloGeneral', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			result = JSON.parse(data);
			if(result.error == 1){
				mostrarNotificacion('success', data.msj , null);
			} else if(result.error == 0){
				modal('modalEditarFlgAmarilloGeneral');
				setearInput("valorAmarilloGeneral", result.valorAmarillo);
				setearInput("valorVerdeGeneral", result.valorVerde)
			}
		}
	});
}

function editarValorAmarilloGeneral() {
	Pace.restart();
	Pace.track(function() {
    var valorAmarillo = $("#valorAmarilloGeneral").val();
		$.ajax({
	        data: { valorAmarilloGeneral : valorAmarillo },
	        url: "c_linea_estrategica/editValorAmarilloGeneral",
	        type: 'POST'
	  	}).done(function(data) {
	  		if(data == "") {
				location.reload();
			} else {
		  		result = JSON.parse(data);
		  		var classAux = "img-backdrop";
		  		if(result.error == 1) {
		  			msj('success', result.msj);
		  		} else {
		  			var classPortada = $("#portada").attr('class');
		  			$('#portada').removeClass(classPortada);
		  			$('#portada').addClass(classAux+' '+result.backgroundAvantgard);
		  			var color = null;
		  			if(result.backgroundAvantgard == 'fondoRojo') {
		  				color = '#FF3D00';
		  			} else if(result.backgroundAvantgard == 'fondoAmarillo') {
		  				color = '#FFCA28';
		  			} else {
		  				color = '#4CB5AB';
		  			}
		  			$('#colorBarraGeneral').css('background-color',color);
		  			initGauge(parseInt(result.porcent_general, 10),100,result.porcent_general_amarillo,result.porcent_general_verde,'#FF3D00','#FFCA28','#4CB5AB',0,100);
		  			$('.highcharts-container svg path[fill="transparent"]').css('fill','white');		        
					modal('modalEditarFlgAmarilloGeneral');
					msj('success', result.msj);
					
					$('.highcharts-data-labels g rect').css('display','none');
			        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
			        $('.highcharts-series-group circle').css('fill','#959595');
				 	
				 	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
					$('.highcharts-tracker').find("tspan").css('font-size','15px');
					$('.highcharts-tracker').find("tspan").css('fill','#959595');
					$('.linEst100').find('.highcharts-tracker').find("tspan").append(' %');
			        $('.linEst100').find(".highcharts-yaxis-labels").find("text").find("tspan").append("%");
		  		}
			}
	    });
	});
}

/*function migrar(){
	$.ajax({ 
		url  : 'c_linea_estrategica/updatearIndicador', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		console.log(data);
	});
}*/

function goToAllIndicadores() {
	$('#formGoToIndis').submit();
}