function buscarIndicador(){
	var timeInit = performance.now();
	screenLoader(timeInit);
	Pace.restart();
	Pace.track(function() {
		desc = $("#searchMagic").val();
		if(desc.length != 0 && desc.length >= 3){
			$.ajax({
				url   : "C_main/getIndicadores",
				type  : 'POST',
				data  : { desc : desc },
				'async' : true
			})
			.done(function(data) {
				data = JSON.parse(data);
				$("#cont_indi").html(data.indicadores);
				$("#cont_indi").highlight(desc);
				if(data.count > 0){
					initIndicadores(data.count);
					setDivHeight();
		    		$('.highcharts-data-labels g rect').css('display','none');
		        	$('.highcharts-series-group circle').css('fill','#959595');
		        	$(document).ready(function(){
		        	    $('[data-toggle="tooltip"]').tooltip(); 
		            });
		        	$("#cont_search").css("display", "none");
					$("#cont_search_not_found").css("display", "none");
				}else{
					$("#cont_search").css("display", "none");
					$("#cont_search_not_found").css("display", "block");
				}
			});
		}else{
			$("#cont_indi").html(null);
			$("#cont_search_not_found").css("display", "none");
			$("#cont_search").css("display", "block");
		}
	});
}

function initIndicadores(cuenta){
	for(var i = 0; i<cuenta;i++){
		var dorado     = $('.linEst'+i).attr('data-dorado');
		var tipo       = $('.linEst'+i).attr('data-tipo');
		if(dorado == 1){
        	$('.linEst'+i).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png"><i id="shideEffect" style="height:48%"></i>');
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

function goToIndicadorDetalle(data){
	$.ajax({
		data : { id_indicador  : data},  
		url  : 'c_main/goToIndicadorDetalle', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.href = data;
	});
}

function verImagenResponsable(foto,nombre,telf,correo,id){
	$("#img_repsonsable").attr("src",foto);
	$('#nombreReponsableModal').text(nombre);
	
	$("#btnllamada").click(function(){ window.location = "tel:"+telf; });
	$('#btnperfil').attr("onclick", "goToPerfilUsuario('"+id+"')");
	$("#btnemail").click(function(){ window.open('mailto:'+correo); });
	
	abrirCerrarModal("modalViewResponsable");
}

function modalSearchVoice(){
	modal("modalSearchVoice");
}

function showResponsables(indicador){
	$.ajax({
		data  : {indicador : indicador},
		url   : 'c_main/getResponsablesIndicador',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tableResponsables').html(data.table);
		$('#tb_responsables').bootstrapTable({ });
		modal('modalShowResponsables');
	});
}