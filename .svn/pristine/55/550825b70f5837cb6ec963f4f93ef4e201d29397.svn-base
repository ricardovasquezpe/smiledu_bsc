var fecInicioGlobal = null;
var fecFinGlobal = null;

function openModalAlumnos(tipo){
	$.ajax({
		data  : {tipo      : tipo,
				 fecInicio : fecInicioGlobal,
				 fecFin    : fecFinGlobal},
		url   : 'c_pensiones_pagadas/createTableModal',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tableAlumnos').html(data.tablaAlumnos);
		$('#tb_alumnos').bootstrapTable({});
		abrirCerrarModal('modalAlumnosPagos');
	});
}

function getNivelesBySedePP() {
	var idSede =  $('#selectSedePP option:selected').val();
	fecInicioGlobal = $('#fecInicioPP').val();
	fecFinGlobal    = $('#fecFinPP').val();
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
			url : "c_pensiones_pagadas/comboSedesNivel",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idSede 	 : idSede},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    setCombo('selectNivelPP', data.optNivel, 'Nivel',null);
			    setCombo('selectGradoPP', null, 'Grado',null);
			    setCombo('selectAulaPP', null, 'Aula',null);
			    $('#'+tabGlobal).addClass('is-active');
					$('#tablePagados').html(data.tableGeneral);
					$('#tb_general').bootstrapTable({});
					$("#img_table_empty").css("display", "none");
					$("#tablaP").css("display", "block");

				initSearchTable();
			}else if(data.error == 1) {
				setCombo('selectNivelPP', data.optNivel, 'Nivel',null);
				setCombo('selectGradoPP', null, 'Grado',null);
			    setCombo('selectAulaPP', null, 'Aula',null);
			}
		});
	});
}

function getGradosByNivelPP() {
	var idSede  = null;
	var idNivel = null;
		fecInicioGlobal = $('#fecInicioPP').val();
		fecFinGlobal    = $('#fecFinPP').val();
			idSede  = $('#selectSedePP option:selected').val();
			idNivel = $('#selectNivelPP option:selected').val();
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
				url : "c_pensiones_pagadas/getComboGradoByNivel",
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
				    	setCombo('selectGradoPP', data.optGrado, 'Grado',null);
						setCombo('selectAulaPP', null, 'Aula',null);
						$('#tablePagados').html(data.tableGeneral);
						$('#tb_general').bootstrapTable({});
						$("#img_table_empty").css("display", "none");
						$("#tablaP").css("display", "block");
						if(!$('#tablePagados').is(':visible')){
							buildChartByTab('tab1');
						}
					initSearchTable();
				}else if(data.error == 1){
					setCombo('selectGradoPP', null, 'Grado',null);
				    setCombo('selectAulaPP', null, 'Aula',null);
				}
			});
		});
}

function getAulasByGradoPP() {
	var idSede  = null;
	var idGrado = null;
	var idNivel = null;
			idSede  = $('#selectSedePP option:selected').val();
			idGrado = $('#selectGradoPP option:selected').val();
			idNivel = $('#selectNivelPP option:selected').val();
			fecInicioGlobal = $('#fecInicioPP').val();
			fecFinGlobal    = $('#fecFinPP').val();
			if(fecInicioGlobal.trim( ) == '' || fecInicioGlobal.length == 0 || /^\s+$/.test(fecInicioGlobal)){
				return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
			}
			if(fecInicioGlobal > fecFinGlobal){
//				return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
			}
			if(fecFinGlobal.trim( ) == '' || fecFinGlobal.length == 0 || /^\s+$/.test(fecFinGlobal)){
				return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
			}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_pensiones_pagadas/comboAulasByGrado",
	        data: {fecInicio : fecInicioGlobal,
				   fecFin    : fecFinGlobal, 
		           idGrado   : idGrado,
	        	   idNivel   : idNivel,
	        	   idSede    : idSede,},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $('#'+tabGlobal).addClass('is-active');
			    	setCombo('selectAulaPP', data.optAula, 'Aula',null);
					$('#tablePagados').html(data.tableGeneral);
					$('#tb_general').bootstrapTable({});
					$("#img_table_empty").css("display", "none");
					$("#tablaP").css("display", "block");
				
				initSearchTable();
			}else if(data.error == 1) {
				setCombo('selectAulaPP', null, 'Aula',null);
			}
		});
	});
}

function getAlumnosByAulaPP() {
	var idSede  = null;
	var idGrado = null;
	var idNivel = null;
	var idAula  = null;
		idSede  = $('#selectSedePP option:selected').val();
		idGrado = $('#selectGradoPP option:selected').val();
		idNivel = $('#selectNivelPP option:selected').val();
		idAula  = $('#selectAulaPP option:selected').val();
		fecInicioGlobal = $('#fecInicioPP').val();
		fecFinGlobal    = $('#fecFinPP').val();
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
			url : "c_pensiones_pagadas/getAlumnosFromAula",
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
			$('#'+tabGlobal).addClass('is-active');
				$('#tablePagados').html(data.tableGeneral);
				$('#tb_general').bootstrapTable({});
				$("#cont_filter_empty3").css("display", "none");
				$("#tablaP").css("display", "block");
				if(!$('#tablePagados').is(':visible')){
					buildChartByTab('tab1');
				};
			initSearchTable();
		});
	});
}


function buildChartByTab(tab){
	var idSede  		= $('#selectSedePP option:selected').val();
	var idGrado 		= $('#selectGradoPP option:selected').val();
	var idNivel 		= $('#selectNivelPP option:selected').val();
	var idAula  		= $('#selectAulaPP option:selected').val();
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
		url   : 'c_pensiones_pagadas/buildGraficoByTab',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		initGrafico(JSON.parse(data.series));
	});
}

function initGrafico(series){
	var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Pensiones Pagadas'
        },
        exporting: { enabled: false },
        tooltip: {
            pointFormat: '<b>{point.percentage:.2f}%</b>'
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
    $('#container_grafico_pagados').highcharts(options);
    var chart = $('#container_grafico_pagados').highcharts();
	for(i = 0; i<series.length;i++){
		chart.addSeries({
			colorByPoint: true,
	        data  : series[i],
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
}

function changeVisibilityByIconTab1(icon){
	if(!$('#tablePagados').is(':visible')){
		getAlumnosByAulaPP();
		$('#tablePagados').fadeIn();
		$('#container_grafico_pagados').fadeOut();
		$('#iconPagados').removeClass().addClass('mdi mdi-insert_chart');
	} else{
		$('#tablePagados').fadeOut();
		$('#container_grafico_pagados').fadeIn();
		$('#iconPagados').removeClass().addClass('mdi mdi-view_column');
		buildChartByTab('tab1');
	}
} 