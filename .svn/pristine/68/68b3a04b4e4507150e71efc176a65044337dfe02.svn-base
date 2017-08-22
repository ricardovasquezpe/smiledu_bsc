function initCategorias(cuenta){
	initButtonLoad( 'btnEVZRC');
	for(var i = 0; i<cuenta;i++){
		var dorado     = $('.linEst'+i).attr('data-dorado');
		if(dorado == 1){
        	$('.linEst'+i).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect" style="height:94%"></i>');
        }else{
        	var porcentaje = $('.linEst'+i).attr('data-porcentaje');
    		porcentaje = parseInt(porcentaje, 10);    		
    		var amarillo   = $('.linEst'+i).attr('data-porcent1');
            var verde      = $('.linEst'+i).attr('data-porcent2');
        	initGauge(porcentaje,i,amarillo,verde,'#E2574C','#F4DC51','#43AC6D',0,100);
        }
        $('#barra'+i).addClass($('.linEst'+i).attr('data-cBack')); 
        $(".linEst"+i).find("tspan").append(" %");
    }
	$('.highcharts-tracker').find("tspan").css('fill','#959595');
}

$(window).on('load resize', function(){
    setDivHeight();        
});

function goToIndicadores(data){
	$.ajax({
		data : { id_categoria  : data},  
		url  : 'c_categoria/goToIndicadores', 
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

var cons_id_categoria = null;
var cons_cont_categoria = null;
var cons_pos_categoria = null;
function openEditarValorAmarilloCategoria(data, cont, pos){
	Pace.restart();
	Pace.track(function() {
		cons_id_categoria   = data;
		cons_cont_categoria = cont;
		cons_pos_categoria  = pos;
		$.ajax({
			data : { idCategoria : data,
					 idCont  : cont,
					 pos     : pos}, 
			url  : 'c_categoria/getValorAmarilloCategoria', 
			async: false,
			type : 'POST'
		})
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				result = JSON.parse(data);
				if(result.error == 1) {
					msj('error', data.msj);
				} else if(result.error == 0){
					modal('modalEditarValorAmarillo');
					setearInput("valorAmarillo", result.valorAmarillo);
					setearInput("valorVerde", result.valorVerde);
				}
			}
		});
	});
}

function editarValorZonaRiesgoCategoria() {
	var valorAmarillo = $("#valorAmarillo").val();
	var valorMeta     = $("#valorVerde").val();
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
	addLoadingButton('btnEVZRC');
	Pace.restart();
	Pace.track(function() {
		$.ajax({  
	        data: {idCategoria : cons_id_categoria,
	        	   cont        : cons_cont_categoria,
	        	   pos  	   : cons_pos_categoria,
	        	   valor 	   : valorAmarillo,
	        	   meta        : valorMeta },
	        url: "C_categoria/editValorAmarilloCategoria",
            type: 'POST'
	  	}).done(function(data) {
	  		result = JSON.parse(data);
	  		if(result.error == 1) {
				msj('success', result.msj, null);
			} else{
				var lista = JSON.parse(result.porcent_lineas_num);
		        var porcentaje = lista.porcentaje;
		        
		        var dorado = lista.dorado;
				if(dorado == 1) {
		        	$('.linEst'+result.posicion).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect" style="height:94%"></i>');
		        } else {
		        	var amarillo   = lista.p_amarillo;
			        var verde      = lista.p_verde;
			        var aux 	   = lista.aux;
			        initGauge(parseInt(porcentaje, 10),result.posicion,amarillo,verde,'#E2574C','#F4DC51','#43AC6D',0,100);
			        
			        $('#barra'+result.posicion).removeAttr('class');
			        $('#barra'+result.posicion).attr('class', 'mdl-bar_state barraEstado '+lista.color);
			        
			        $('.highcharts-yaxis-labels').find("text").css('font-size','7px');
		        	$('.highcharts-tracker').find("tspan").css('font-size','15px');
		        	$('.highcharts-tracker').find("tspan").css('fill','#959595');
		        	$(".linEst"+result.posicion).find("tspan").append(" %");
		        	
		        	$('.highcharts-data-labels g rect').css('display','none');
		        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        	$('.highcharts-series-group circle').css('fill','#959595');
		        }
	            //$('#barra'+result.posicion).css('background-color',lista.color); 
	        	
	            msj('success', result.msj);
	        	modal('modalEditarValorAmarillo');
			}
	  		stopLoadingButton('btnEVZRC');
	     });
	});
}