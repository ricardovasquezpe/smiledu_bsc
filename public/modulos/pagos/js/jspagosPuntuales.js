var tablaPuntuales = 0;
var fecInicioGlobal = null;
var fecFinGlobal = null;

function init(){
	initButtonLoad('botonPP');
    $(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip(); 
    });	
}

function getCuotaByCronograma(){
	var idCronograma =  $('#selectCronograma option:selected').val();
//	fecInicioGlobal  = $('#fecInicioPU').val();
//	fecFinGlobal     = $('#fecFinPU').val();
//	
//	if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
//	}
//	if(fecInicioGlobal > fecFinGlobal){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
//	}
//	if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
//	}
	
	$.ajax({
		url : "c_pagos_puntuales/comboCronogramaCuota",
        data: {idCronograma    : idCronograma,
        	   fecFinGlobal    : fecFinGlobal,
        	   fecInicioGlobal : fecInicioGlobal},
        async: true,
        type: 'POST'
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
		    setCombo('selectCuota' , data.optCuotas, 'Cuota',null);
			initSearchTable();
		}else{
			setCombo('selectCuota' , null, 'Cuota',null);
		}
		setCombo('selectNivelC', null, 'Nivel' ,null);
		setCombo('selectGradoC', null, 'Grado' ,null);
	    setCombo('selectAulaC' , null, 'Aula'  ,null);
	});
}

function getNivelesByCuotas() {
	var idCronograma =  $('#selectCronograma option:selected').val();
	var idCuota =  $('#selectCuota option:selected').val();
	$.ajax({
        data: {idCronograma : idCronograma,
        	   idCuota 	    : idCuota},
        url : "c_pagos_puntuales/comboCuotasNivel",
        async: true,
        type: 'POST'
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectNivelC', data.optNivel, 'Nivel',null);
		    setCombo('selectGradoC', null, 'Grado',null);
		    setCombo('selectAulaC', null, 'Aula',null);
		}else if(data.error == 1) {
			setCombo('selectNivelC', data.optNivel, 'Nivel',null);
			setCombo('selectGradoC', null, 'Grado',null);
		    setCombo('selectAulaC', null, 'Aula',null);
		}
		tablaPuntuales = ((data.totalPunt > 0) ? 1 : 0);
	});
}

function getGradosByNivelP() {
	var idSede  = null;
	var idNivel = null;
	var idCronograma = null ;
	var idCuota = null ;
	idNivel = $('#selectNivelC option:selected').val();
	idCronograma =  $('#selectCronograma option:selected').val();
	idCuota =  $('#selectCuota option:selected').val();
	$.ajax({
		url : "c_pagos_puntuales/getComboGradoByNivel",
        data: {idNivel 	 : idNivel,
        	   idCronograma : idCronograma,
        	   idCuota 	    : idCuota},
        async: true,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			setCombo('selectGradoC', data.optGrado, 'Grado',null);
			setCombo('selectAulaC', null, 'Aula',null);
		}else if(data.error == 1){
			setCombo('selectGrado', null, 'Grado',null);
		    setCombo('selectAula', null, 'Aula',null);
		}
		tablaPuntuales = ((data.totalPunt > 0) ? 1 : 0);
	});
}

function getAulasByGradoP() {
	var idSede  = null;
	var idGrado = null;
	var idNivel = null;
	var idCronograma = null ;
	var idCuota = null ;
	idGrado = $('#selectGradoC option:selected').val();
	idNivel = $('#selectNivelC option:selected').val();
	idCronograma =  $('#selectCronograma option:selected').val();
	idCuota =  $('#selectCuota option:selected').val();
	$.ajax({
		url: "c_pagos_puntuales/comboAulasByGrado",
        data: {idGrado   : idGrado,
        	   idNivel   : idNivel,
        	   idCronograma : idCronograma,
        	   idCuota 	    : idCuota},
        async: true,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
		    setCombo('selectAulaC', data.optAula, 'Aula',null);
		}else if(data.error == 1) {
			setCombo('selectAula', null, 'Aula',null);
		}
		tablaPuntuales = ((data.totalPunt > 0) ? 1 : 0);
	});
}

function getAlumnosByAulaP() {
	addLoadingButton('botonPP');
	var idGrado = null;
	var idNivel = null;
	var idAula  = null;
	var idCronograma = null ;
	var idCuota = null ;
	idGrado         = $('#selectGradoC option:selected').val();
	idNivel         = $('#selectNivelC option:selected').val();
	idAula          = $('#selectAulaC option:selected').val();
	idCronograma    = $('#selectCronograma option:selected').val();
	idCuota         = $('#selectCuota option:selected').val();
//	fecInicioGlobal = $('#fecInicioPU').val();
//	fecFinGlobal    = $('#fecFinPU').val();
//	
//	if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
//	}
//	if(fecInicioGlobal > fecFinGlobal){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
//	}
//	if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
//		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
//	}
//	
	if(idCuota.trim( ) == ''  || idCuota.length == 0) {
		mostrarNotificacion('warning' , 'Debe seleccionar una cuota','');
		stopLoadingButton('botonPP');
	} else {
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url : "c_pagos_puntuales/getAlumnosFromAula",
		        data: {idGrado 	       : idGrado,
			    	   idNivel 	       : idNivel,
			    	   idAula  	       : idAula,
		        	   idCronograma    : idCronograma,
		        	   idCuota 	       : idCuota,
		        	   fecInicioGlobal : fecInicioGlobal,
		        	   fecFinGlobal    : fecFinGlobal},
		        async: true,
		        type: 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				$("#cont_filter_empty2").css("display", "none");
				$('#tablaPU').css('display','block');
				$('#'+tabGlobal).addClass('is-active');
				$('#tablePuntuales').html(data.tablePunt);
				$('#tb_puntual').bootstrapTable({});
				$("#img_table_empty2").css("display", "none");
				$("#tablaPP").css("display", "block");
				initSearchTable();
				tablaPuntuales = ((data.totalPunt > 0) ? 1 : 0);
				stopLoadingButton('botonPP');
				modal('modalFiltrarCuota');
			});
		});
	}
}

function buildChartPuntuales(tab) {
	var idSede  		= $('#selectCronograma option:selected').val();
	var idGrado 		= $('#selectGradoC option:selected').val();
	var idNivel 		= $('#selectNivelC option:selected').val();
	var idAula  		= $('#selectAulaC option:selected').val();
	var fecInicioGlobal = $('#fecInicioPP').val();
	var fecFinGlobal    = $('#fecFinPP').val();
	$.ajax({
		data  : {tab	      : tab,
				 fecInicio    : fecInicioGlobal,
				 fecFin       : fecFinGlobal,
				 idGrado      : idGrado,
	    		 idNivel      : idNivel,
	    	     idSede       : idSede,
	    	     idAula       : idAula},
		url   : 'c_pagos_puntuales/buildGraficoByTab',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(tablaPuntuales == 0) {
			mostrarNotificacion('warning' , 'No se puede generar gr&aacute;ficos sin datos.','');
		}else {
			initGraficoPuntuales(JSON.parse(data.series1), JSON.parse(data.series2), JSON.parse(data.cate));
		}
	});
}

function initGraficoPuntuales(series1, series2, cate) {
	var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'column'
        },
        title: null,
        xAxis: {
        	categories: cate
        },
        exporting: { enabled: false },
        tooltip: {
            pointFormat: '<b>{point.y:.2f}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        }
    };
    $('#container_grafico_puntuales').highcharts(options);
    var chart = $('#container_grafico_puntuales').highcharts();
	for(i = 0; i<series1.length;i++){
		chart.addSeries({
			colorByPoint: true,
			name  : 'Pronto Pago',
	        data  : series1[i],
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
	for(i = 0; i<series2.length;i++){
		chart.addSeries({
			colorByPoint: true,
			name  : 'Normal',
	        data  : series2[i],
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
}

function changeVisibilityByIconTab3(icon) { 
	if(tablaPuntuales == 1) {
		if(!$('#tablePuntuales').is(':visible')){
			$("#cont_filter_empty2").css("display", "none");
			$('#tablePuntuales').fadeIn();
			$('#container_grafico_puntuales').fadeOut();
			$('#iconPuntuales').removeClass().addClass('mdi mdi-insert_chart');
		} else{
			$("#cont_filter_empty2").css("display", "none");
			$('#tablePuntuales').fadeOut();
			$('#container_grafico_puntuales').fadeIn();
			$('#iconPuntuales').removeClass().addClass('mdi mdi-view_column');
			buildChartPuntuales('tab3');
		}
	}
} 