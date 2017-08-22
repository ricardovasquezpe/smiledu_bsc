function onchangeRoles(){
	var fecInicio = $('#fecInicioEva').val();
	var fecFin    = $('#fecFinEva').val();
	$.ajax({
		data  : {fecInicio   : fecInicio,
				 fecFin      : fecFin,
				 selectedEv  : $('#selectEvaluador').val()/*,
				 selectedRol : $('#selectRoles').val()*/ },
		url   : 'c_desempeno_evaluadores/getEvaluacionesPorEstado',
		async : false,
		type  : 'POST'
	}).done(function(data){
		data = JSON.parse(data);
		initGraficoRoles(data);
		initGraficoEvaluadores(data);
		initGraficoLineaTiempo(data);
	});
}

//function abrirModalFitros(){
//	initMultiEvaluadores();
//	abrirCerrarModal('modalFiltro');
//}

function initGraficoRoles(data){
	arrayGeneral = JSON.parse(data.general);
	arrayEstados = JSON.parse(data.estados);
	arrayRoles   = JSON.parse(data.roles);
	arrayColores = JSON.parse(data.colores);
	if(arrayGeneral.length == 0){
		$('#container1Eva').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
	} else{
		var options = {
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: arrayRoles
	        },
	        yAxis: {
	            min: 0,
	            labels: {
	                formatter:function() {
	                    return Highcharts.numberFormat(this.value,0,',') + '%';
	                }
	            }
	        },
	        tooltip: {
	            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
	        },
	        legend: {
	            reversed: true
	        },
	        plotOptions: {
	            series: {
	                stacking: 'percent',
	                cursor: 'pointer',
	                point: {
	                    events: {
	                        click: function () {
	                        	mostrarDetalleRoles(this.series.name,this.category);
	                        }
	                    }
	                }
	            }
	        }
	    }
		$('#container1Eva').highcharts(options);
		var chart = $('#container1Eva').highcharts();
		
		for(i = 0; i<arrayEstados.length;i++){
			chart.addSeries({
		        name  : arrayEstados[i],
		        data  : arrayGeneral[i],
		        color : arrayColores[i],	        
		        zIndex: 1,
		        pointWidth: 20,
		        animation : false
		    });
		}
	}
}

function initGraficoEvaluadores(data){
	arrayGeneral = JSON.parse(data.generalEv);
	arrayEstados = JSON.parse(data.estadosEv);
	arrayNombres = JSON.parse(data.nombresEv);
	arrayColores = JSON.parse(data.coloresEv);
	if(arrayGeneral.length == 0){
		$('#container2Eva').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
	} else{
		var options = {
	        chart: {
	            type: 'bar'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: arrayNombres
	        },
	        yAxis: {
	            min: 0,
	            labels: {
	                formatter:function() {
	                    return Highcharts.numberFormat(this.value,0,',') + '%';
	                }
	            }
	        },
	        tooltip: {
	            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
	        },
	        legend: {
	            reversed: true
	        },
	        plotOptions: {
	            series: {
	                stacking: 'percent',
	                cursor: 'pointer',
	            	point: {
	                    events: {
	                        click: function () {
	                        	mostrarDetalleEvaluadores(this.category);
	                        }
	                    }
	                }
	            }
	        }
	    }
		arrayIds = [1]
		$('#container2Eva').highcharts(options);
		var chart = $('#container2Eva').highcharts();
		for(i = 0; i<arrayEstados.length;i++){
			chart.addSeries({
		        name  : arrayEstados[i],
		        data  : arrayGeneral[i],
		        color : arrayColores[i],
		        id    : arrayIds[i],
		        zIndex: 1,
		        pointWidth: 15
		    });
		}
		/*options.series[0].data = json;
		chart = new Highcharts.Chart(options);
		$.each(options.series[0].data, function (key, value){
			//value.events.click = function (){ mostrarDetalle(this.id);};
		});*/
	}
}
/*
function initMultiEvaluadores(){
	$('#selectEvaluador').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		maxHeight : 300,
		enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        nonSelectedText: 'Seleccione Evaluadores',
        numberDisplayed: 1,
        buttonWidth: '100%',
        nSelectedText: 'Seleccionado',
        onChange: function(element, checked) {
        	var brands = $('#selectEvaluador option:selected');
        	selectedEv = [];
            $(brands).each(function(index, brand){
            	selectedEv.push([$(this).val()][0]);
            }); 
        }
	});
}*/

function initGraficoLineaTiempo(data){
	arrayGeneral = JSON.parse(data.generalLin);
	arrayColores = JSON.parse(data.coloresLin);
	arrayFechas  = JSON.parse(data.fechasLin);
	arrayEstado  = JSON.parse(data.estadoLin);
	if(arrayGeneral.length == 0){
		$('#container3Eva').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
	} else{
		var options = {
	        chart: {
	            type: 'line',
	            zoomType: 'xy'
	        },
	        title: {
	            text: ''
	        },
	        xAxis: {
	            categories: arrayFechas
	        },
	        yAxis: {
	            min: 0,
	            labels: {
	                formatter:function() {
	                    return Highcharts.numberFormat(this.value,0,',');
	                }
	            },
	            title: {
	                text: 'Cantidad'
	            }
	        },
	        tooltip: {
	            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
	            shared: true
	        },
	        plotOptions: {
	        	series: {
	                stacking: 'normal',
	                cursor: 'pointer',
	            	point: {
	                    events: {
	                        click: function () {
	                        	mostrarDetalleLinea(this.series.name,this.category);
	                        }
	                    }
	                }
	            }
	        }
	    }
		$('#container3Eva').highcharts(options);
		var chart = $('#container3Eva').highcharts();
		for(i = 0; i<arrayEstado.length;i++){
			chart.addSeries({
				name  : arrayEstado[i],
				color : arrayColores[i],
		        data  : arrayGeneral[i],
		        zIndex: 1,
		        pointWidth: 15
		    });
		}
	}
}

function mostrarDetalleEvaluadores(cat){
	var fecInicio = $('#fecInicioEva').val();
	var fecFin    = $('#fecFinEva').val();
	$.ajax({
		data  : {cat   	   : cat,
				 fecInicio : fecInicio,
				 fecFin    : fecFin},
		url   : 'c_desempeno_evaluadores/getDetalleEvaluadores',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#body').html(data.datos);
		$('#tittle').html(data.tittle);
		abrirCerrarModal('modalDetalleEvaluadores');
	});
}

function mostrarDetalleRoles(estado,cat){
	var fecInicio = $('#fecInicioEva').val();
	var fecFin    = $('#fecFinEva').val();
	$.ajax({
		data  : {cat   	   : cat,
				 estado    : estado,
				 fecInicio : fecInicio,
				 fecFin    : fecFin},
		url   : 'c_desempeno_evaluadores/getDetalleRoles',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#body').html(data.datos);
		$('#titleTable').html(data.titleTable);
		$('#tb_detalleRol').bootstrapTable({ });
		initSearchTable();
		abrirCerrarModal('modalDetalleEvaluadores');
	});
}

function mostrarDetalleLinea(estado,fecha){
	$.ajax({
		data  : {estado    : estado,
			     fecha     : fecha},
		url   : 'c_desempeno_evaluadores/getDetalleLinea',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#body').html(data.datos);
		$('#titleTable').html(data.titleTable);
		$('#tb_detalleRol').bootstrapTable({ });
		initSearchTable();
		abrirCerrarModal('modalDetalleEvaluadores');
	});
}

function verDetalleEvaluaciones(idEvaluador){
	var fecInicio = $('#fecInicioEva').val();
	var fecFin    = $('#fecFinEva').val();
	$.ajax({
		data  : {idEvaluador : idEvaluador,
				 fecInicio   : fecInicio,
				 fecFin      : fecFin},
		url   : 'c_desempeno_evaluadores/getDetalleByEvaluador',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#bodyDetalle').html(data.datos);
		$('#titleTableDetalle').html(data.titleTable);
		$('#tbEvaluaciones').bootstrapTable({ });
		initSearchTable();
		abrirCerrarModal('modalDetalleEvaluacionesEvaluadores');
	});
}