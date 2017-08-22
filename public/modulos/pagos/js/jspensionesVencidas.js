var fecInicioGlobal = null;
var fecFinGlobal    = null;
var tablaVencidos   = 0;
function init(){
	initButtonLoad('botonPV');
}

function getNivelesBySedeV() {
	var idSede =  $('#selectSedeV option:selected').val();
	fecInicioGlobal = $('#fecInicioV').val();
	fecFinGlobal    = $('#fecFinV').val();	
	if(idSede == '' || idSede == null) {
		setCombo('selectNivelV' , null, 'Nivel',null);
		setCombo('selectGradoV' , null, 'Grado',null);
	    setCombo('selectAulaV'  , null, 'Aula',null);
		return mostrarNotificacion('warning', 'Seleccione una sede');
	}
	if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
	}
	if(fecInicioGlobal > fecFinGlobal){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
	}
	if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_pensiones_vencidas/comboSedesNivel",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idSede 	 : idSede},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    setCombo('selectNivelV', data.optNivel, 'Nivel',null);
			    setCombo('selectGradoV', null, 'Grado',null);
			    setCombo('selectAulaV', null, 'Aula',null);
			}else if(data.error == 1) {
				setCombo('selectNivelV', data.optNivel, 'Nivel',null);
				setCombo('selectGradoV', null, 'Grado',null);
			    setCombo('selectAulaV', null, 'Aula',null);
			}
			tablaVencidos = ((data.totalVenc > 0) ? 1 : 0);
		});
	});
}

function getGradosByNivelV() {
	var idSede  = null;
	var idNivel = null;
	var idCronograma = null ;
	var idCuota = null ;
	fecInicioGlobal = $('#fecInicioV').val();
	fecFinGlobal    = $('#fecFinV').val();
	idSede  		= $('#selectSedeV option:selected').val();
	idNivel 		= $('#selectNivelV option:selected').val();
	if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
	}
	if(fecInicioGlobal > fecFinGlobal){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
	}
	if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_pensiones_vencidas/getComboGradoByNivel",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idNivel 	 : idNivel,
	        	   idSede  	 : idSede},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#'+tabGlobal).addClass('is-active')
				setCombo('selectGradoV', data.optGrado, 'Grado',null);
				setCombo('selectAulaV', null, 'Aula',null);
				initSearchTable();
			}else if(data.error == 1){
				setCombo('selectGradoV', null, 'Grado',null);
			    setCombo('selectAulaV', null, 'Aula',null);
			}
			tablaVencidos = ((data.totalVenc > 0) ? 1 : 0);
			});
	});
}

function getAulasByGradoV() {
	var idSede  = null;
	var idGrado = null;
	var idNivel = null;
	var idCronograma = null ;
	var idCuota = null ;
	idSede  = $('#selectSedeV option:selected').val();
	idGrado = $('#selectGradoV option:selected').val();
	idNivel = $('#selectNivelV option:selected').val();
	fecInicioGlobal = $('#fecInicioV').val();
	fecFinGlobal    = $('#fecFinV').val();
	if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
	}
	if(fecInicioGlobal > fecFinGlobal){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
	}
	if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_pensiones_vencidas/comboAulasByGrado",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idGrado   : idGrado,
	        	   idNivel   : idNivel,
	        	   idSede    : idSede},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $('#'+tabGlobal).addClass('is-active');
				setCombo('selectAulaV', data.optAula, 'Aula',null);
//				$('#tableVencidos').html(data.tableVenc);				
//				$('#tb_vencido').bootstrapTable({});
//				$("#img_table_empty1").css("display", "none");
//				$("#tablaV").css("display", "block");
//				initSearchTable();
			}else if(data.error == 1) {
				setCombo('selectAulaV', null, 'Aula',null);
			}
			tablaVencidos = ((data.totalVenc > 0) ? 1 : 0);
		});
	});
}

var tablaVencidos = 0;
function getAlumnosByAulaV() {
	addLoadingButton('botonPV');
	var idSede  = null;
	var idGrado = null;
	var idNivel = null;
	var idAula  = null;
	var idCronograma = null ;
	var idCuota = null ;
		idSede  = $('#selectSedeV option:selected').val();
		idGrado = $('#selectGradoV option:selected').val();
		idNivel = $('#selectNivelV option:selected').val();
		idAula  = $('#selectAulaV option:selected').val();
		fecInicioGlobal = $('#fecInicioV').val();
		fecFinGlobal    = $('#fecFinV').val();
		if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
			return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
		}
		if(fecInicioGlobal > fecFinGlobal){
			return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
		}
		if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
			return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
		}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_pensiones_vencidas/getAlumnosFromAula",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idGrado 	 : idGrado,
		    	   idNivel 	 : idNivel,
		    	   idSede  	 : idSede,
		    	   idAula  	 : idAula},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$("#cont_filter_empty1").css("display", "none");
			$('#'+tabGlobal).addClass('is-active');
			$('#tableVencidos').html(data.tableVenc);
			$('#tb_vencido').bootstrapTable({});
			$("#img_table_empty1").css("display", "none");
			$("#tablaV").css("display", "block");
			$('#tablaP').css('display', 'none');
			initSearchTable();
			tablaVencidos = ((data.totalVenc > 0) ? 1 : 0);
			stopLoadingButton('botonPV');
			modal('modalFiltrarVencidos');
		});
	});
}

function openModalAlumnosDetalles(idPersona) {
	fecInicioGlobal = $('#fecInicioV').val();
	fecFinGlobal    = $('#fecFinV').val();
	$('.tabs__panel').removeClass('is-active');
	$('#tabPensiones').addClass('is-active');
	$.ajax({
		data  : {idPersona : idPersona,
				 fecInicio : fecInicioGlobal,
				 fecFin    : fecFinGlobal},
		url   : 'c_pensiones_vencidas/createTableDetalleAlumno',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		console.log(data);
		$('#nombreCompleto').html(data.nombreCompleto);
		$('#tabsDetalles').html(data.tabs);
		$('#tabsDetalles').addClass('is-active');
		$('#apoderados').html(data.apoderados);
		$('#tablePensVenc').html(data.tablaPensVenc);
//		$('#tb_pensiones').bootstrapTable({ });
		modal('modalAlumnosDetalles');
	});
}

function cambiarTab(tab) {
	$('.tabsDetalles').removeClass('is-active');
	tab.addClass('is-active');
	$('.detalleDescripcion').removeClass('is-active');
	var idDiv = tab.data('iddiv');
	$(idDiv).addClass('is-active');
}

function buildChartVencidos(tab) {
	var idSede  		= $('#selectSedeV option:selected').val();
	var idGrado 		= $('#selectGradoV option:selected').val();
	var idNivel 		= $('#selectNivelV option:selected').val();
	var idAula  		= $('#selectAulaV option:selected').val();
	var fecInicioGlobal = $('#fecInicioV').val();
	var fecFinGlobal    = $('#fecFinV').val();
	$.ajax({
		data  : {tab	      : tab,
				 fecInicio    : fecInicioGlobal,
				 fecFin       : fecFinGlobal,
				 idGrado      : idGrado,
	    		 idNivel      : idNivel,
	    	     idSede       : idSede,
	    	     idAula       : idAula},
		url   : 'c_pensiones_vencidas/buildGraficoByTab',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(tablaVencidos == 0) {
			mostrarNotificacion('warning' , 'No se puede generar gr&aacute;ficos sin datos.','');
		} else {
			initGraficoVencidas(JSON.parse(data.series), JSON.parse(data.cate));
		}
	});
}

function initGraficoVencidas(series, cate) {
	var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'column'
        },
        title: {
            text: null
        },
        xAxis: {
        	categories: cate
        },
        yAxis: {
        	title: {
                text: 'Valores'
            }
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
    $('#container_grafico_vencidos').highcharts(options);
    var chart = $('#container_grafico_vencidos').highcharts();
	for(i = 0; i<series.length;i++){
		chart.addSeries({
	        data  : series[i],
	        name  : 'Vencidos',
	        color : 'red',
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
}

function changeVisibilityByIconTab2(icon) {
	if(tablaVencidos == 1) {
		if(!$('#tableVencidos').is(':visible')){
			$("#cont_filter_empty1").css("display", "none");
			$('#tableVencidos').fadeIn();
			$('#container_grafico_vencidos').fadeOut();
			$('#iconVencidos').removeClass().addClass('mdi mdi-insert_chart');
			
		} else{
			$("#cont_filter_empty1").css("display", "none");
			$('#tableVencidos').fadeOut();
			$('#container_grafico_vencidos').fadeIn();
			$('#iconVencidos').removeClass().addClass('mdi mdi-view_column');
			buildChartVencidos('tab2');
		}	
	}	
} 