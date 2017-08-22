var verde = 1;
var amarillo = 1;
var rojo = 1;
var dorado = 1;

function init(){
	$( "#main_button" ).hover(function() {
		$('#blackModal').modal({
   	        show: true
   	    });	
	});
	initButtonLoad('btnEIR');
}

function mouseOverEye(elem){
	$("#viewID").find("after").addClass("hide");
}

function mouseOutEye(elem){
	$("#viewID").find(":after").addClass("hide");
}

function initIndicadores(cuenta){
	//console.time('Test performance');
	for(var i = 0; i<cuenta;i++){
		var dorado     = $('.linEst'+i).attr('data-dorado');
		var tipo       = $('.linEst'+i).attr('data-tipo');
		if(dorado == 1){
        	$('.linEst'+i).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect" style="height:48%"></i>');
        	$('#barra'+i).css("display", "none");
		}else{
        	var porcentaje = $('.linEst'+i).attr('data-porcentaje');
    		porcentaje = parseInt(porcentaje, 10);
    		
    		var amarillo   = $('.linEst'+i).attr('data-porcent1');
            var verde      = $('.linEst'+i).attr('data-porcent2');
            var colorVerde = $('.linEst'+i).attr('data-colorVerde');
            var colorRojo  = $('.linEst'+i).attr('data-colorRojo');
            var inicioG    = $('.linEst'+i).attr('data-inicioG');
            var finG       = $('.linEst'+i).attr('data-finG');

        	initGauge(porcentaje,i,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
        }
        var background = $('.linEst'+i).attr('data-cBack');
        $('#barra'+i).addClass(background); 
        
        if(tipo == "NORMAL"){
        	$(".linEst"+i).find("tspan").append(" %");
        }
    }
	$('.highcharts-tracker').find("tspan").css('fill','#959595');
    $('.highcharts-container svg path[fill="transparent"]').css('fill','red');
	//console.timeEnd('Test performance');
}

$(window).on('load resize', function(){
    setDivHeight();        
});

function logOutIndRapido() {
	$.ajax({
		url  : 'c_indicador_rapido/logOutIndRapido', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}

function openModalEditar(data,cont,pos){
	$.ajax({
		data : { idIndicador  : data,
				 idCont       : cont,
				 pos          : pos}, 
		url  : 'c_indicador_rapido/getValorAmarillo', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 1){
				mostrarNotificacion('success', data.msj , null);
			} else if(data.error == 0){
				setearInput("valorAmarillo", data.valorAmarillo);
				setearInput("valorVerde", data.valorMeta);
				abrirCerrarModal('modalEditar');
			}
		}
	});
}

function editarValorZonaRiesgoIndicador(){
	addLoadingButton('btnEIR');
	Pace.restart();
	Pace.track(function() {
		valor = $("#valorAmarillo").val();
		$.ajax({  
	        data: {valor : valor},
	        url: "C_indicador_rapido/editValorAmarillo",
            type: 'POST'
	  	})
	  	.done(function(data) {
	  		if(data == ""){
				location.reload();
			} else{
		  		data = JSON.parse(data);
		  		if(data.error == 1){
		  			mostrarNotificacion('success', data.msj , null);
		  		} else{
		  			var idCont = data.idCont;
		  			var posicion = data.posicion;
		  			$('#'+idCont).replaceWith(data.contGauge);
		  			var porcentaje = $('.linEst'+posicion).attr('data-porcentaje');
		  			porcentaje = parseInt(porcentaje, 10);
		  			var amarillo   = $('.linEst'+posicion).attr('data-porcent1');
		  	        var verde      = $('.linEst'+posicion).attr('data-porcent2');
		  	        var inicioG    = $('.linEst'+posicion).attr('data-inicioG');
		  	        var finG       = $('.linEst'+posicion).attr('data-finG');
		  	        var tipo        = $('.linEst'+posicion).attr('data-tipo');
		  	        var colorVerde = $('.linEst'+posicion).attr('data-colorVerde');
		            var colorRojo  = $('.linEst'+posicion).attr('data-colorRojo');
		            var dorado     = $('.linEst'+posicion).attr('data-dorado');
		  	        
		            if(dorado == 1){
		            	$('.linEst'+posicion).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png">');
		            }else{
		            	 initGauge(porcentaje,posicion,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
		            }
		            
		  	        $('#barra'+posicion).css('background-color',$('.linEst'+posicion).attr('data-cBack'));
		            
		            if(tipo == "NORMAL"){
		            	$(".linEst"+posicion).find("tspan").append(" %");
		            }
		  	       
		  	        $('.highcharts-data-labels g rect').css('display','none');
		  	        $('.highcharts-container svg path[fill="transparent"]').css('fill','transparent');
		      		$('.highcharts-series-group circle').css('fill','#959595');
		      		$('.highcharts-tracker').find("tspan").css('fill','#959595');
		      		setDivHeight();
		      		mostrarNotificacion('success', data.msj , null);
		  			abrirCerrarModal('modalEditar');
		  		}
		  		stopLoadingButton('btnEIR');
			}
	     });
	});
}

function clickFiltro(color){
	if(color == 0){
		if(verde == 1){
			$('#divVerde').css("opacity",'0.4');
			verde = 0;
		}else{
			$('#divVerde').css("opacity",'1');
			verde = 1;
		}
	}else if(color == 1){
		if(amarillo == 1){
			$('#divAmarillo').css("opacity",'0.4');
			amarillo = 0;
		}else{
			$('#divAmarillo').css("opacity",'1');
			amarillo = 1;
		}
	}else if(color == 2){
		if(rojo == 1){
			$('#divRojo').css("opacity",'0.4');
			rojo = 0;
		}else{
			$('#divRojo').css("opacity",'1');
			rojo = 1;
		}
	}else{
		if(dorado == 1){
			$('#divDorado').css("opacity",'0.4');
			dorado = 0;
		}else{
			$('#divDorado').css("opacity",'1');
			dorado = 1;
		}
	}
	filtrarGauges();

	$("#img_not_found_ind").css("display", "none");
	setTimeout(function(){ 
		var indicadores = $('.mdl-indicator').length; 
		var indicadoresInactivos = $('.mdl-indicator__inactive').length;
		if(indicadores == indicadoresInactivos){
			$("#img_not_found_ind").css("display", "block");
		}
	}, 500);
}

function filtrarGauges(){
	var idColor0 = $( "div[data-idcolor='0']" ).parent().parent();
	var idColor1 = $( "div[data-idcolor='1']" ).parent().parent();
	var idColor2 = $( "div[data-idcolor='2']" ).parent().parent();
	var idColor3 = $( "div[data-idcolor='3']" ).parent().parent();
	if(verde == 0){
		idColor0.addClass('mdl-indicator__inactive');
		setTimeout(function(){ idColor0.css('display', 'none'); }, 300);
	}else{
		idColor0.removeAttr('style');
		setTimeout(function(){ idColor0.removeClass('mdl-indicator__inactive'); }, 500);
	}
	if(amarillo == 0){
		idColor1.addClass('mdl-indicator__inactive');
		setTimeout(function(){ idColor1.css('display', 'none'); }, 500);
	}else{
		idColor1.removeAttr('style');
		setTimeout(function(){ idColor1.removeClass('mdl-indicator__inactive'); }, 500);
	}
	if(rojo == 0){
		idColor2.addClass('mdl-indicator__inactive');
		setTimeout(function(){ idColor2.css('display', 'none'); }, 500);
	}else{
		idColor2.removeAttr('style');
		setTimeout(function(){ idColor2.removeClass('mdl-indicator__inactive'); }, 500);
	}
	if(dorado == 0){
		idColor3.addClass('mdl-indicator__inactive');
		setTimeout(function(){ idColor3.css('display', 'none'); }, 500);
	}else{
		idColor3.removeAttr('style');
		setTimeout(function(){ idColor3.removeClass('mdl-indicator__inactive'); }, 500);
	}
}

var vistaR = 0;
function vistaResponsables(){
	if(vistaR == 0){
		$.ajax({
			url  : 'C_indicador_rapido/reponsablesIndicadores', 
			async: true,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			var responsables      = data.responsables;
			var arrayResponsables = JSON.parse(responsables);
			for(var i = 0; i < arrayResponsables.length; i++){
				$("#respons"+i).html(arrayResponsables[i]);
				$("#respons"+i).css("display", "block");
			}
			
		});
		vistaR = 1;
	}else{
		$(".respons").empty();
		$(".respons").css("display", "none");
		vistaR = 0;
	}
}

function goToIndicadorDetalle(data){
	$.ajax({
		data : { id_indicador  : data},  
		url  : 'c_indicador/goToIndicadorDetalle', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.href = data;
	});
}

function goToTablaIndi(){
	window.location.assign("c_indicador");
}

function verImagenResponsable(foto,nombre,telf,correo,id){
	$("#img_repsonsable").attr("src",foto);
	$('#nombreReponsableModal').text(nombre);
	
	$("#btnllamada").click(function(){ window.location = "tel:"+telf; });
	$('#btnperfil').attr("onclick", "goToPerfilUsuario('"+id+"')");
	$("#btnemail").click(function(){ window.open('mailto:'+correo); });
	
	abrirCerrarModal("modalViewResponsable");
}