function init(backgroundColorAvantgard){
	getDataLineasEstrat();
	setDivHeight();
}

function getDataLineasEstrat(){
	$.ajax({
		url  : 'getGraficosLineasEstrat', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		initGauge(parseInt(result.porcent_general, 10),100,result.porcent_general_amarillo,result.porcent_general_verde,'#F44336','#FFEB3B','#4CAF50',0,100);
		$('#portada').addClass(result.backgroundAvantgard);
		var lista = JSON.parse(result.porcent_lineas_num);
	 	  for(var i=0;i<lista.length;i++){
		        var porcentaje = lista[i].porcentaje;
		        var amarillo   = lista[i].p_amarillo;
		        var verde      = lista[i].p_verde;
		        $('.backCirc'+i).css('background-color',lista[i].color);
		        initGauge(parseInt(porcentaje, 10),i,amarillo,verde,'#F44336','#FFEB3B','#4CAF50',0,100);
		    }
	});
}

function getObjetivosByLinea(data){
	$('#gauges_objetivos').html('');
	abrirCerrarModal('gaugesObjetivos');
	setTimeout(function(){
		$.ajax({
			data : { id_lineaEstrat  : data},  
			url  : 'getOBjetivosByLineaEstrat', 
			async: false,
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
		        var inicioG    = lista[i].inicioG;
		        var finG       = lista[i].finG;
		        $('.backCirc'+(i+10)).css('background-color',lista[i].color);
		        initGauge(parseInt(porcentaje, 10),i+10,amarillo,verde,'#F44336','#FFEB3B','#4CAF50',0,100);
		        
		        $('.highcharts-data-labels g rect').css('display','none');
		        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        $('.highcharts-series-group circle').css('fill','#959595');
			}
		 	
		 	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
			$('.highcharts-tracker').find("tspan").css('font-size','15px');
		});
		}, 150);
}

function setDivHeight() {
    var div = $('.container-gauge');
    div.height(div.width() * 1);
    div = $('.container-rpm');
    div.height(div.width() * 1);
}

$(window).on('load resize', function(){
    setDivHeight();     
    $('.highcharts-yaxis-labels').find("text").css('font-size','7px');
	$('.highcharts-tracker').find("tspan").css('font-size','15px');
});

function goToIndicadores(data){
	$.ajax({
		data : { id_objetivo  : data},  
		url  : 'gotoIndicadores', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.href = data;
	});
}
/*
function cambioRol(data){
	$.ajax({
		data : { id_rol  : data}, 
		url  : 'cambioRol', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		location.href = result.url;
	});
}
*/
function openModalMisionVision(){
	abrirCerrarModal("modalMisionVision");
}

function openEditarValorAmarilloLinea(data,cont,pos){
	$.ajax({
		data : { idLinea : data,
				 idCont  : cont,
				 pos     : pos}, 
		url  : 'getValorAmarilloLinea', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		if(result.error == 1){
			mostrarNotificacion('warning', data.msj, 'Ojo')
		} else if(result.error == 0){
			$('#valorAmarilloLinea').val(result.valorAmarillo);
			abrirCerrarModal('modalEditarFlgAmarilloLinea');
		}
	});
}

//VALIDATE FLG_AMARILLO LINEA ESTRATEGICA
function initEditValorAmarilloLinea(){
	$('#formEditAmarilloLinea').bootstrapValidator({
		framework: 'bootstrap',
//	    excluded: ':disabled',
	    fields: {
	    	valorAmarilloLinea: {
	    		validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el valor amarillo'
	                 },
		             numeric: {
		                 message: 'El valor debe contener solo dígitos'
		             },
		             between: {
                        min: 0,
                        max: 100,
                        message: 'El valor debe estar entre 0 y 100'
                    }
	            }
	        }
	    }
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	    var $form = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        fv       = $form.data('bootstrapValidator');
	    $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });		
	    $.ajax({  
	        data: formData,
	        url: "editValorAmarilloLinea",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
	  	.done(function(data) {
	  		result = JSON.parse(data);
	  		if(result.error == 1){
				mostrarNotificacion('warning',result.msj,"Ojo");
			} else{
				var lista = JSON.parse(result.porcent_lineas_num);
		        var porcentaje = lista.porcentaje;
		        
		        var amarillo   = lista.p_amarillo;
		        var verde      = lista.p_verde;
		        var aux 	   = lista.aux;
		        initGauge(parseInt(porcentaje, 10),result.posicion,amarillo,verde,'#F44336','#FFEB3B','#4CAF50',0,100);
		        $('.backCirc'+result.posicion).css('background-color',lista.color);
		        
		        $('.highcharts-yaxis-labels').find("text").css('font-size','7px');
	        	$('.highcharts-tracker').find("tspan").css('font-size','15px');
	        	
	        	$('.highcharts-data-labels g rect').css('display','none');
	        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
	        	$('.highcharts-series-group circle').css('fill','#959595');
	        	
	        	mostrarNotificacion('success', result.msj , result.cabecera);
	        	abrirCerrarModal('modalEditarFlgAmarilloLinea');
			}
	     });
  });
}

function openEditarValorAmarilloObjetivo(dataL,dataO,cont,pos){
	$.ajax({
		data : { idLinea    : dataL,
			     idObjetivo : dataO,
				 idCont     : cont,
				 pos        : pos}, 
		url  : 'getValorAmarilloObjetivo', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		if(result.error == 1){
			mostrarNotificacion('warning', data.msj, 'Ojo')
		} else if(result.error == 0){
			$('#valorAmarilloObjetivo').val(result.valorAmarillo);
			abrirCerrarModal('modalEditarFlgAmarilloObjetivo');
		}
	});
}

//VALIDATE FLG_AMARILLO OBJETIVO
function initEditValorAmarilloObjetivo(){
	$('#formEditAmarilloObjetivo').bootstrapValidator({
		framework: 'bootstrap',
//	    excluded: ':disabled',
	    fields: {
	    	valorAmarilloObjetivo: {
	    		validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el valor amarillo'
	                 },
		             numeric: {
		                 message: 'El valor debe contener solo dígitos'
		             },
		             between: {
                        min: 0,
                        max: 100,
                        message: 'El valor debe estar entre 0 y 100'
                    }
	            }
	        }
	    }
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	    var $form = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        fv       = $form.data('bootstrapValidator');
	    $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });		
	    $.ajax({  
	        data: formData,
	        url: "editValorAmarilloObjetivo",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
	  	.done(function(data) {
	  		result = JSON.parse(data);
	  		if(result.error == 1){
	  			mostrarNotificacion('warning',result.msj,"Ojo");
	  		} else{
	  			var lista = JSON.parse(result.porcent_objetivos_num);
		 		var porcentaje = lista.porcentaje;
		        var amarillo   = lista.p_amarillo;
		        var verde      = lista.p_verde;
		        $('.backCirc'+(result.posicion)).css('background-color',lista.color);
		        initGauge(parseInt(porcentaje, 10),result.posicion,amarillo,verde,'#F44336','#FFEB3B','#4CAF50',0,100);
		        
		        $('.highcharts-data-labels g rect').css('display','none');
		        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        $('.highcharts-series-group circle').css('fill','#959595');
			 	
			 	$('.highcharts-yaxis-labels').find("text").css('font-size','7px');
				$('.highcharts-tracker').find("tspan").css('font-size','15px');
				
				abrirCerrarModal('modalEditarFlgAmarilloObjetivo');
				mostrarNotificacion('success',result.msj,result.cabecera);
	  		}
	     });
  });
}

function openEditarValorAmarilloGeneral(data){
	$.ajax({ 
		url  : 'getValorAmarilloGeneral', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		result = JSON.parse(data);
		if(result.error == 1){
			mostrarNotificacion('warning', data.msj, 'Ojo')
		} else if(result.error == 0){
			$('#valorAmarilloGeneral').val(result.valorAmarillo);
			abrirCerrarModal('modalEditarFlgAmarilloGeneral');
			
		}
	});
}

//VALIDATE FLG_AMARILLO GENERAL
function initEditValorAmarilloGeneral(){
	$('#formEditAmarilloGeneral').bootstrapValidator({
		framework: 'bootstrap',
//	    excluded: ':disabled',
	    fields: {
	    	valorAmarilloGeneral: {
	    		validators: {
	        		 notEmpty: {
	                     message: 'Ingrese el valor amarillo'
	                 },
		             numeric: {
		                 message: 'El valor debe contener solo dígitos'
		             },
		             between: {
                        min: 0,
                        max: 100,
                        message: 'El valor debe estar entre 0 y 100'
                    }
	            }
	        }
	    }
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	    var $form = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        fv       = $form.data('bootstrapValidator');
	    $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });		
	    $.ajax({  
	        data: formData,
	        url: "editValorAmarilloGeneral",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
	  	.done(function(data) {
	  		result = JSON.parse(data);
	  		var classAux = "img-backdrop";
	  		if(result.error == 1){
	  			mostrarNotificacion('warning',result.msj,"Ojo");
	  		} else{
	  			document.getElementById('portada').className = ''
	  			$('#portada').addClass(classAux);
	  			$('#portada').addClass(result.backgroundAvantgard);
	  			initGauge(parseInt(result.porcent_general, 10),100,result.porcent_general_amarillo,result.porcent_general_verde,'#F44336','#FFEB3B','#4CAF50',0,100);
	  			$('.highcharts-container svg path[fill="transparent"]').css('fill','white');		        
				abrirCerrarModal('modalEditarFlgAmarilloGeneral');
				mostrarNotificacion('success',result.msj,result.cabecera);
	  		}
	     });
  });
}

function init(){
	if($( window ).width() <= 531){
		$('#bscDesc').text("BSC");
		$('#spedDesc').text("SPED");
	}else{
		$('#bscDesc').text("Balanced Scorecard");
		$('#spedDesc').text("Evaluación de docentes");
	}
	 
 $( window ).resize(function() {
     if($( window ).width() <= 531){
		$('#bscDesc').text("BSC");
		$('#spedDesc').text("SPED");
     }else{
    	$('#bscDesc').text("Balanced Scorecard");
    	$('#spedDesc').text("Evaluación de docentes");
     }
	});
}
//codificarcodfam('000271');
function codificarcodfam(codfamilia){
	//codfamilia ES EL CODIGO DE FAMILIA DEL PADRE O MADRE EN SESION
	$.ajax({
		data : { codfam : codfamilia}, 
		url  : 'http://181.224.241.203/senc/serv_senc/codificarcodfam', 
		async: false,
		type : 'POST',
		crossDomain: true,
		dataType: "json"
	})
	.done(function(data){
		//data.url ES LA URL QUE REDIRIJE AL PADRE A LLENAR LAS ENCUESTAS DE SUS HIJOS
	});
}