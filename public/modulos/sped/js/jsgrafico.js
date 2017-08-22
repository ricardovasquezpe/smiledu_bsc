var tipoGrafico = null;
/*var selectedIndi = [];
var selectedDoc  = [];*/
var cons_id_subfactor = null;
var cons_id_subfactor_ASC = null;
var cons_id_evaluado = null;
var cons_id_evaluador = null;
var cons_id_evaluado2 = null;
var cons_id_persona = null;
function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#fecInicio').pickadate({
    	container: '#divFechas'
	});
	$('#fecFin').pickadate({
    	container: '#divFechas'
	});
	changeIdModal('modalDocentes');
}

var jsonDataGraf1_Global = null;
function drawChartDoc1() {
    try {
    	jsonDataGrafDoc1 = JSON.parse(jsonDataGrafDoc1);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc1);
	var options = {
	  max : 20,
	  min: 0,
	  bar: {groupWidth: "75%"},
	  legend: { position: "none" },
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('containerDoc'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc1').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	    	var idSubF1 = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
	    	cons_id_subfactor_ASC = idSubF1;
	    	chart.setSelection();
	    	if(idSubF1) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getDetalleSubFact',
					    data : { idSubF1 : idSubF1 },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				jsonDataGraf1_Global = JSON.parse(data.datos);
	    				
	    				$('#titleTableSubF').html('Detalle Subfactor: ');
						modal('modalDetaSubf');
						setTimeout(function(){
						    google.charts.setOnLoadCallback(drawChartSubFact);
						}, 200);
	    			});
	    		});	
	    	}
	    }
	}
}

var jsonDataGraf2_Global = null;
function drawChartDoc2() {
	try {
    	jsonDataGrafDoc2 = JSON.parse(jsonDataGrafDoc2);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc2);
	var options = {
      max : 20,
      min: 0,
	  bar: {groupWidth: "75%"},
	  legend: { position: "none" }
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('containerDoc2'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc2').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	    	var idSubF = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
	    	cons_id_subfactor = idSubF;
	    	chart.setSelection();
	    	if(idSubF) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getDetalleSubFactLow',
					    data : { idSubF : idSubF },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				jsonDataGraf2_Global = JSON.parse(data.datos);
	    				$('#titleTableSubFLow').html('Detalle Subfactor: ');
						modal('modalDetaSubfLow');
						setTimeout(function(){
						    google.charts.setOnLoadCallback(drawChartSubFactLow);
						}, 200);
	    			});
	    		});	
	    	}
	    }
	}
}

function drawChartDoc3() {
	try {
    	jsonDataGrafDoc3 = JSON.parse(jsonDataGrafDoc3);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc3);
	var options = {
	    width : 450,
        redFrom: 0      , redTo: 10,
        yellowFrom:10.1 , yellowTo: 14.4,
        greenFrom : 14.5, greenTo: 20,
        minorTicks: 3,
        max : 20,
        min: 0
    };
	var chart = new google.visualization.Gauge(document.getElementById('containerDoc3'));
	google.visualization.events.addListener(chart, 'select', selectHandler);

	chart.draw(data, options);

	document.getElementById('aDownloadDoc3').removeEventListener("click", descargarGauge);
	document.getElementById('aDownloadDoc3').addEventListener('click', descargarGauge);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	      var value = data.getValue(selectedItem.row, 2);
	      alert('The user selected ' + value);
	    }
	}
}

var jsonDataGraf4_1_Global = null;
function drawChartDoc4() {
	try {
    	jsonDataGrafDoc4 = JSON.parse(jsonDataGrafDoc4);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc4);
	var options = {
	    //bar       : {groupWidth : "75%"  },
	    //chartArea : {width      : '50%'  },
	    legend    : { position  : "none" }
	};
	var chart = new google.visualization.BarChart(document.getElementById('containerDoc4'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc4').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var desc_area    = null;
	    if (selectedItem) {
	    	desc_area = data.getValue(selectedItem.row, 0);
	    }
		var idArea = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
		chart.setSelection();
		if(idArea) {
			Pace.restart();
			Pace.track(function(){
				$.ajax({
				    url  : 'c_graficos/getDetalleDoceCantEvas',
				    data : { idArea : idArea },
				    type : 'POST'	
				}).done(function(data) {
					data = JSON.parse(data);
					jsonDataGraf4_1_Global = JSON.parse(data.datos);
					
					$('#titleTableDetaDoceArea').html('Evaluaciones del &Aacute;rea: '+desc_area);
					modal('modalDetaDoceArea');
					setTimeout(function(){
						google.charts.setOnLoadCallback(drawDetalleGrafDocente4);
					}, 200);
				});
			});			
		}
	}
}

function drawChartDoc5() {
    try {
    	jsonDataGrafDoc5 = JSON.parse(jsonDataGrafDoc5);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc5);
	var options = {
	  bar: {groupWidth: "75%"},
	  min : 0,
	  max : 20,
	  legend: { position: "none" },
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('container5Doc'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc5').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var id_evaluado    = null;
	    if (selectedItem) {
	    	id_evaluado = data.getValue(selectedItem.row, 0);
	    }
	    if (selectedItem) {
	    	var id_evaluado = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
	    	cons_id_evaluado = id_evaluado;
	    	chart.setSelection();
	    	if(id_evaluado) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDetaEvaDocentes1',
					    data : { id : id_evaluado },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvalDocentes1').html(data.tabla);
	    				$('#titleTableDetaTopDoc').html('Detalle evaluaci&oacute;n a docente: '+data.nombrePersona);
						modal('modalDetaEvalDocentes1');
	    			});
	    		});	
	    	}
	    }
	}
}

function drawChartDoc6() {
	try {
    	jsonDataGrafDoc6 = JSON.parse(jsonDataGrafDoc6);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGrafDoc6);
	var options = {
      max : 20,
      min: 0,
	  bar: {groupWidth: "75%"},
	  legend: { position: "none" },
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('container6Doc'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc6').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var id_evaluado2    = null;
	    if (selectedItem) {
	    	id_evaluado2 = data.getValue(selectedItem.row, 0);
	    }
	    if (selectedItem) {
	    	var id_evaluado2 = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
	    	cons_id_evaluado2 = id_evaluado2;
	    	chart.setSelection();
	    	if(id_evaluado2) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDetaEvaDocentes2',
					    data : { id : id_evaluado2 },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvalDocentes2').html(data.tabla);
	    				$('#titleTableDetaDocxMejorar').html('Detalle Evaluaci&oacute;n a docentes por mejorar: '+data.nombrePersona);
						modal('modalDetaEvalDocentes2');
	    			});
	    		});	
	    	}
	    }
	}
}

function drawDetalleGrafDocente4() {
	try {
		jsonDataGraf4_1_Global = JSON.parse(jsonDataGraf4_1_Global);
    } catch(err) {
    }
	var data = google.visualization.arrayToDataTable(jsonDataGraf4_1_Global);
	var chart = new google.visualization.BarChart(document.getElementById('containerDoc4_deta'));
	var options = {
	    //bar       : {groupWidth : "75%"  },
	    //chartArea : {width      : '80%'  },
	    //bars: 'horizontal',
	    legend    : { position  : "none" }
	};
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDetaDoc4').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var orden    = null;
	    if (selectedItem) {
	    	orden = data.getValue(selectedItem.row, 0);
	    }
	    if (selectedItem) {
	    	var orden = data['Tf'][chart.getSelection()[0].row]['c'][1]['id'];
	    	cons_id_persona = orden;
	    	chart.setSelection();
	    	if(orden) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDetaEvaArea',
					    data : { id : cons_id_persona,
					    		 orden  : orden },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableDetaEvaArea').html(data.tabla);
	    				$('#titleTableDetaEvaArea').html('Detalle de evaluaciones por &Aacute;rea: '+data.nombrePersona);
						modal('modalDetaEvaArea');
	    			});
	    		});	
	    	}
	    }
	}
}

function descargarGauge() {
	html2canvas(document.getElementById('containerDoc3'), {
	    onrendered: function(canvas) {
	    	var a = document.createElement('a');
	    	document.body.appendChild(a);
	    	a.style.display = 'none';
	    	a.download = 'gauge.png';
	    	a.href = canvas.toDataURL();
	    	a.click();
	    }
	});
}

function drawChart() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf1);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
		pieHole: 0.5,
        pieSliceTextStyle: {
        	color: 'black',
        },
        legend: {position: 'top', alignment : 'center', maxLines : 2, textStyle: {color: 'blue', fontSize: 13}}
	};
	var chart = new google.visualization.PieChart(document.getElementById('container4Eva'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownload').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var id_evaluador    = null;
	    if (selectedItem) {
	    	id_evaluador = data.getValue(selectedItem.row, 0);
	    }
	    if (selectedItem) {
	    	var id_evaluador = data['Tf'][chart.getSelection()[0].row]['c'][2]['v'];
	    	cons_id_evaluador = id_evaluador;
	    	chart.setSelection();
	    	if(id_evaluador) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDetaEvaHechasXHacer',
					    data : { idEvaluador : cons_id_evaluador},
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvalHechasXHacer').html(data.tabla);
	    				$('#titleTableDetaHechasXHacer').html('Detalle evaluaciones hechas y por hacer: '+data.nombrePersona);
						modal('modalDetaEvalHechasXHacer');
	    			});
	    		});	
	    	}
	    }
	}
}

function drawChart1() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
	  legend: {position: 'top', maxLines: 3},
	  bar: { groupWidth: '78%' },
	  isStacked: false,
	  series: {
		    0:{color:'#4CAF50'}, //OPINADA
		    1:{color:'#2196F3'}, //NO OPINADA
		    2:{color:'#F44336'}, //SEMI OPINADA
		  }
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('container1EvaTipoVisita'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownload3').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
		var id_evaluador    = null;
	    if (selectedItem) {
	    	id_evaluador = data.getValue(selectedItem.row, 0);console.log(data.getValue(selectedItem.row, 2));
	    }
	    if (selectedItem) {
	    	var id_evaluador = data['Tf'][chart.getSelection()[0].row]['c'][1]['idEvaluador'];
	    	var tipo_visita  = data['Tf'][chart.getSelection()[0].row]['c'][selectedItem.column]['tipo_visita'];
	    	cons_id_evaluador = id_evaluador;
	    	chart.setSelection();
	    	if(id_evaluador) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDetaEvaTipoVisita',
					    data : { idEvaluador : cons_id_evaluador,
					    	     tipoVisita  : tipo_visita},
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvalTipoVisita').html(data.tabla);
	    				$('#titleTableDetaTipoVisita').html('Detalle evaluaci&oacute;n por tipo de visita: '+data.nombrePersona);
						modal('modalDetaEvalTipoVisita');
	    			});
	    		});	
	    	}
	    }
	}
}

function drawChart2() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf2);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
	    hAxis: {
	      title: 'Tiempo'
	    },
	    vAxis: {
	      title: 'Cant. evaluaciones'
	    }
	};
	var chart = new google.visualization.LineChart(document.getElementById('container2Eva'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
    $('#aDownload2').attr("href", chart.getImageURI());
		//document.getElementById('container4Eva').innerHTML = '<img src="' + chart.getImageURI() + '">';
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	      var value = data.getValue(selectedItem.row, 2);
	      alert('The user selected ' + value);
	    }
	}
}

function drawChart3() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf3);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
	  legend: {position: 'top', maxLines: 3},
	  bar: { groupWidth: '78%' },
	  isStacked: true,
	  series: {
		    0:{color:'#2196F3'}, //PENDIENTE
		    1:{color:'#4CAF50'}, //EJECUTADO
		    2:{color:'#FFC107'}, //NO EJECUTADO
		    3:{color:'#F44336'}, //INJUSTIFICADO
		    4:{color:'#000000'},  //POR JUSTIFICAR
		    5:{color:'#9C27B0'} //JUSTIFICADO
		  }
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('container3Eva'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownload3').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	      var value = data.getValue(selectedItem.row, 2);
	      alert('The user selected ' + value);
	    }
	}
}

function drawChart5() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf5);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
		pieHole: 0.5,
        pieSliceTextStyle: {
        	color: 'black',
        },
        legend: {position: 'top', alignment : 'center', maxLines : 2, textStyle: {color: 'blue', fontSize: 13}}
	};
	var chart = new google.visualization.PieChart(document.getElementById('container5Doc'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc5').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	      var value = data.getValue(selectedItem.row, 2);
	      alert('The user selected ' + value);
	    }
	}
}

function drawChart6() {
	var data = null;
	try {
		data = google.visualization.arrayToDataTable(jsonDataGraf6);
    } catch(err) {
    	//console.log(err.message);
    }
    if(data == null) {
    	return;
    }
	var options = {
		pieHole: 0.5,
        pieSliceTextStyle: {
        	color: 'black',
        },
        legend: {position: 'top', alignment : 'center', maxLines : 2, textStyle: {color: 'blue', fontSize: 13}}
	};
	var chart = new google.visualization.PieChart(document.getElementById('container6Doc'));
	
	google.visualization.events.addListener(chart, 'select', selectHandler);
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDoc6').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	      var value = data.getValue(selectedItem.row, 2);
	      alert('The user selected ' + value);
	    }
	}
}

function getGraficosAll() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getGraficosEvas',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf =  JSON.parse(data.datos);
			jsonDataGraf1 = JSON.parse(data.datos1);
			jsonDataGraf2 = JSON.parse(data.datos2);
			jsonDataGraf3 = JSON.parse(data.datos3);
			google.charts.setOnLoadCallback(drawChart);
			google.charts.setOnLoadCallback(drawChart1);
			google.charts.setOnLoadCallback(drawChart2);
			google.charts.setOnLoadCallback(drawChart3);
			
		});
	});
}

function getGraficoDoc1() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataTopSubFactores',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGrafDoc1 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChartDoc1);
		});
	});
}

function getGraficoDoc2() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataLowSubFactores',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGrafDoc2 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChartDoc2);
		});
	});
}

function getGraficoDoc3() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGaugesPromediosSedeGrupoEduc',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGrafDoc3 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChartDoc3);
		});
	});
}

function getGraficoDoc5() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataTopDocentes',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGrafDoc5 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChartDoc5);
		});
	});
}

function getGraficoDoc6() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataLowDocentes',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGrafDoc2 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChartDoc6);
		});
	});
}

function getGrafico() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGraficoEvaluadoresCantidad',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf1 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChart);
		});
	});
}

function getGrafico2() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGraficoCantidadEvasByFechas',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf2 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChart2);
		});
	});
}

function getGrafico3() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGraficoEstadoEvaluacionesCant',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf3 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChart3);
		});
	});
}

function getGrafico5() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGraficoCantDocentes',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf1 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChart5);
		});
	});
}

function getGrafico6() {
	Pace.restart();
	Pace.track(function(){
		$.ajax({
		    url  : 'c_graficos/getDataGraficoLowCantidadDocentes',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
			jsonDataGraf2 = JSON.parse(data.datos);
			google.charts.setOnLoadCallback(drawChart6);
		});
	});
}

function refreshGraf(graf) {
	if(graf == 1) {
		getGrafico();
	} else if(graf == 2) {
		getGrafico2();
	} else if(graf == 3) {
		getGrafico3();
	} else if(graf == 4) {
		getGraficoDoc1();
	} else if(graf == 5) {
		getGraficoDoc2();
	} else if(graf == 6) {
		getGraficoDoc3();
	}
}

function abrirModalFitros(modal){
	/*initMultiIndi();
	initMultiDocentes();*/
	/*if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}*/
	abrirCerrarModal(modal);
}

function drawChartVersus() {
	var cantSubFactSelected = parseInt($('#selectIndi').val().length);
	try {
		jsonDataGraf_versus = JSON.parse(jsonDataGraf_versus);
    } catch(err) {
    	//console.log(err.message);
    }
	var data = google.visualization.arrayToDataTable(jsonDataGraf_versus);
	var chart = new google.visualization.ComboChart(document.getElementById('container_versus'));
	var options = {
		/*title : 'Monthly Coffee Production by Country',
	    vAxis: {title: 'Cups'},
	    hAxis: {title: 'Month'},*/
		legend: {position: 'top', alignment : 'center', maxLines : 2, textStyle: {color: 'black', fontSize: 10}},
	    seriesType: 'bars',
	    series: ''
	};

	myObj = {};
	myObj[cantSubFactSelected] = {type : "line"};
	options.series = myObj;
	/*google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDetaDoc4').attr("href", chart.getImageURI());
    });*/
	chart.draw(data, options);
}

function drawChartSubFactLow() {
	try {
		jsonDataGraf2_Global = JSON.parse(jsonDataGraf2_Global);
    } catch(err) {
    	//console.log(err.message);
    }
    var data = google.visualization.arrayToDataTable(jsonDataGraf2_Global);
	var chart = new google.visualization.LineChart(document.getElementById('containerDoc5_deta'));
	var options = {
			        hAxis: {
			          title: String('N\u00FAmero Evaluaci\u00F3n')
			        },
			        vAxis: {
			          title: 'Nota'
			        },
			        max : 20,
			        min : 0
			    };
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDetaDoc5').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	    	var orden = data['Tf'][chart.getSelection()[0].row]['c'][0]['v'];
	    	chart.setSelection();
	    	if(orden) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDocFechaLow',
					    data : { idSubF : cons_id_subfactor,
					    		 orden  : orden },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvaDocente').html(data.tabla);
						modal('modalDetaDocFechaLow');
	    			});
	    		});	
	    	}
	    }
	}
}




function drawChartSubFact() {
	try {
		jsonDataGraf1_Global = JSON.parse(jsonDataGraf1_Global);
    } catch(err) {
    	//console.log(err.message);
    }
    var data = google.visualization.arrayToDataTable(jsonDataGraf1_Global);
	var chart = new google.visualization.LineChart(document.getElementById('containerDoc11_deta'));
	var options = {
			        hAxis: {
			          title: String('N\u00FAmero Evaluaci\u00F3n')
			        },
			        vAxis: {
			          title: 'Nota'
			        },
			        max : 20,
			        min : 0
			    };
	google.visualization.events.addListener(chart, 'ready', function () {
        $('#aDownloadDetaDoc11').attr("href", chart.getImageURI());
    });
	chart.draw(data, options);
	google.visualization.events.addListener(chart, 'select', selectHandler);
	function selectHandler() {
		var selectedItem = chart.getSelection()[0];
	    if (selectedItem) {
	    	var orden = data['Tf'][chart.getSelection()[0].row]['c'][0]['v'];
	    	chart.setSelection();
	    	if(orden) {
	    		Pace.restart();
	    		Pace.track(function(){
	    			$.ajax({
	    				url  : 'c_graficos/getTablaDocFechaAscendente',
					    data : { idSubF1 : cons_id_subfactor_ASC,
					    		 orden  : orden },
					    type : 'POST'
	    			}).done(function(data) {
	    				data = JSON.parse(data);
	    				$('#cont_tableEvaDocenteAscendente').html(data.tabla);
						modal('modalDetaDocFechaAscendente');
	    			});
	    		});	
	    	}
	    }
	}
}

var jsonDataGraf_versus = null;
function getGraficoVersus() {
	if(!$('#selectIndi').val()) {
		msj('error', 'Seleccione al menos un subfactor');
		return;
	}
	if(!$('#selectDocente').val()) {
		msj('error', 'Seleccione al menos un docente');
		return;
	}
	var fechaIni = $('#fecInicioDoc').val();
	var fechaFin = $('#fecFinDoc').val();
	if(!isDate(fechaIni)) {
		msj('error', 'Formato de fecha inicio incorrecto');
		return;
	}
	if(!isDate(fechaFin)) {
		msj('error', 'Formato de fecha inicio incorrecto');
		return;
	}
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			data : { fecInicio    : fechaIni,
				     fecFin       : fechaFin,
				     selectedIndi : $('#selectIndi').val(),
				     selectedDoc  : $('#selectDocente').val()
				   },
		    url  : 'c_graficos/getDataSubFactores_vs_Docentes',
		    type : 'POST'	
		}).done(function(data) {
			data = JSON.parse(data);
			$('#container_versus').html(null);
			if(JSON.parse(data.datos).length > 1) {
				jsonDataGraf_versus = JSON.parse(data.datos);
				setTimeout(function(){
					google.charts.setOnLoadCallback(drawChartVersus);
				}, 200);
			}
		});
	});
}

function onChangeIndicador(){
	var cont  		= $('#cDoc').is(":visible");
	var cont2 		= $('#c2Doc').is(":visible");
	var cont3 		= $('#c3Doc').is(":visible");
	var cont5 		= $('#c5Doc').is(":visible");
	var fecInicio   = $('#fecInicio').val();
	var fecFin   = $('#fecFin').val();
	evaluaTipoGrafico();
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			data : {fecInicio    : fecInicio,
				    fecFin       : fecFin,
				    selectedIndi : $('#selectIndi').val(),
				    selectedDoc  : $('#selectDocente').val(),
				    cont         : cont,
				    cont2		 : cont2,
				    cont3		 : cont3,
				    cont5		 : cont5},
		    url  : 'c_grafico/dataGraficoByIndicador',
		    type : 'POST'	
		})
		.done(function(data) {
			data = JSON.parse(data);
	        //0 AMBOS | 1 PORCENTAJE | 2 INDICADOR | NINGUNO
			if(JSON.parse(data.porcentaje).length != 0){
				initPieChart(data);
			} else{
				$('#container2Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
			if(JSON.parse(data.notas).length != 0){
				initGrafico(data , '#containerDoc','indicador','line');
			} else{
				$('#containerDoc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
			if(JSON.parse(data.notaA).length != 0){
				initGrafico(data , '#container3Doc','docente por indicador ' ,'column');
			} else{
				$('#container3Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
			if(JSON.parse(data.promedios).length != 0){
				initAreaChart(data);
			} else{
				$('#container5Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
		});
	});
}

function initGrafico(data, cont, name, type) {
	if(cont == '#container3Doc') {
		var desc = data.descA;
		arrayDesc = JSON.parse(desc);
		var nota = data.notaA;
		arrayNota = JSON.parse(nota);
		var fecha = data.fechaA;
		arrayFec  = JSON.parse(fecha);
	} else {
		var arrayDesc = JSON.parse(data.desc);
		var arrayFec  = JSON.parse(data.fechas);
		var arrayNota = JSON.parse(data.notas);
	}
	var event = null;
	if(name == 'indicador') {
		pointFormat = '<span style="color:{series.color}">{series.name}</span>: ({point.y})<br/>';
		event = {
	                click: function () {
	                	getDetalleEvalIndi(this.series.name,this.category);
	                }
	            };
	} else if(name == 'docente') {
		pointFormat = '<span style="color:{series.color}">{series.name}</span>: ({point.y:,.2f})<br/>';
		event = {
		            click: function () {
		            	getDetalleEvalDoce(this.series.name,this.category,this.y);
		            }
		        };
	} else {
		pointFormat = '<span style="color:{series.color}">{series.name}</span>: ({point.y:,.2f})<br/>';
		event = {
		            click: function () {
		            	getDetalleEvalDoceIndi(this.series.name, this.category);
		            }
		        };
	}
	
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
	var fileName = name + today;
	var options = {
		chart: {
            zoomType: 'xy',
            type : type
        },
        exporting: {
            filename: fileName
        },
        title: {
            text: ''
        },
        xAxis: [{
            categories: arrayFec,
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true,
            type: 'category'

        }],
        tooltip: {
            pointFormat: pointFormat,
            shared: true
        },
        plotOptions: {
        	series: {
        		cursor: 'pointer',
            	point: {
                    events: event
                }
            }
        }
	}
	$(cont).highcharts(options);
	var chart = $(cont).highcharts();
	for(i = 0; i<arrayNota.length;i++) {
		chart.addSeries({
	        name  : arrayDesc[i],
	        color : getRandomColor(),
	        data  : arrayNota[i],
	        zIndex: 1,
	        pointWidth: 15
	    });
	}
}

function onChangeDocente() {
	var idIndicador = $('#selectIndi option:selected').val();
	var idDocente   = $('#selectDocente option:selected').val();
	var fecInicio   = $('#fecInicio').val();
	var fecFin      = $('#fecFin').val();
	var cont3 		= $('#c3Doc').is(":visible");
	var cont4 		= $('#c4Doc').is(":visible");
	var cont6 		= $('#c6Doc').is(":visible");
	evaluaTipoGrafico();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : {idIndicador  : idIndicador,
				    idDocente    : idDocente,
				    fecInicio    : fecInicio,
				    fecFin       : fecFin,
				    selectedIndi : $('#selectIndi').val(),
				    selectedDoc  : $('#selectDocente').val(),
				    cont4		 : cont4,
				    cont3		 : cont3,
				    cont6		 : cont6},
		    url  : 'c_grafico/dataGraficosDocente',
		    type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(JSON.parse(data.notas).length == 0){
				$('#container4Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			} else {
				initGrafico(data , '#container4Doc','docente','line');
			}
			if(JSON.parse(data.notaA).length != 0){
				initGrafico(data , '#container3Doc','docente por indicador ' ,'column');
			} else{
				$('#container3Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
			if(JSON.parse(data.promedio).length != 0){
				initGraficosBarra(data);
			} else{
				$('#container6Doc').html('<img src="'+window.location.origin+'/sped/public/img/smiledu_faces/filter_fab.png"><p>Primero debes filtrar para poder ver los rsultados.</p>');
			}
		});
	});
}

function onChangeFecha(){
	evaluaTipoGrafico();
	if(tipoGrafico == 1){
		onChangeDocente();
	} else if(tipoGrafico == 2){
		onChangeDocente();
	} else if(tipoGrafico == 3){
		onChangeIndicador();
	} else if(tipoGrafico == 4){
		mostrarNotificacion('warning','Seleccione un indicador o docente');
	}
}

//1 = DocenteGeneral || 2 = DocenteXIndicador || 3 = IndicadorGeneral || 4 = Sin Grafico 
function evaluaTipoGrafico(){
	var idIndicador = ($('#selectIndi option:selected').val() == undefined) ? "" : $('#selectIndi option:selected').val();
	var idDocente   = ($('#selectDocente option:selected').val() == undefined) ? "" : $('#selectDocente option:selected').val();
	if(idIndicador == "" && idDocente != ""){
		tipoGrafico = 1;
	} else if(idIndicador != "" && idDocente != ""){
		tipoGrafico = 2;
	} else if(idIndicador != "" && idDocente == ""){
		tipoGrafico = 3;
	} else{
		tipoGrafico = 4;
	}
}
/*
function initMultiIndi(){
	$('#selectIndi').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        nonSelectedText: 'Seleccione Indicadores',
        numberDisplayed: 1,
        buttonWidth: '100%',
        nSelectedText: 'Seleccionado',
        onChange: function(element, checked) {
        	var brands = $('#selectIndi option:selected');
        	console.log(brands);
        	selectedIndi = [];
            $(brands).each(function(index, brand){
            	selectedIndi.push([$(this).val()][0]);
            });
            onChangeIndicador();
        },
	});
}

function initMultiDocentes(){
	$('#selectDocente').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        nonSelectedText: 'Seleccione Docentes',
        numberDisplayed: 1,
        buttonWidth: '100%',
        nSelectedText: 'Seleccionado',
        onChange: function(element, checked) {
        	var brands = $('#selectDocente option:selected');
        	selectedDoc = [];
            $(brands).each(function(index, brand){
            	selectedDoc.push([$(this).val()][0]);
            });
        	onChangeDocente();
        }
	});
}*/

function getRandomColor() {
    var data = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += data[Math.round(Math.random() * 15)];
    }
    return color;
}

function initPieChart(data){
	arrayPorcentaje = JSON.parse(data.porcentaje);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
	var fileName = 'indicador_porcentaje_' + today;
	var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        exporting: {
            filename: fileName
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
    }
	$('#container2Doc').highcharts(options);
	var chart = $('#container2Doc').highcharts();	
	chart.addSeries({
		name: 'Porcentaje',
        data: arrayPorcentaje,
        zIndex: 1,
        pointWidth: 15
    });
}

function initAreaChart(data){
	arrayYear = JSON.parse(data.years);
	arrayProm = JSON.parse(data.promedios);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
	var fileName = 'indicador_anual_' + today;
    var options = {
        chart: {
            type: 'area'
        },
        exporting: {
            filename: fileName
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: arrayYear,
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: 'Porcentaje'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b>({point.y:,.2f})<br/>',
            shared: true
        },
        plotOptions: {
            area: {
                stacking: 'percent',
                lineColor: '#ffffff',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#ffffff'
                }
            }
        }
    }
    $('#container5Doc').highcharts(options);
	var chart = $('#container5Doc').highcharts();
	for(i=0;i<arrayProm.length;i++){
		chart.addSeries({
			name: arrayProm[i].name,
	        data: arrayProm[i].data,
	        zIndex: 1,
	        pointWidth: 15
	    });
	}
	
}

function showHidePanel(cb){
	var id = cb.id;
	var contid = null;
	var checked = cb.checked;
	if(id == 'indicador'){
		if(checked == true){
			$("#c2Doc").addClass('col-sm-6');
			$("#c2Doc").removeClass('col-sm-12');
			$("#cDoc").fadeIn();
		} else{
			$("#cDoc").fadeOut();
			$("#c2Doc").removeClass('col-sm-6');
			$("#c2Doc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		//chart.reflow();
		contid = '#cDoc';
	} else if(id == 'porcIndi'){
		if(checked == true){
			$("#cDoc").addClass('col-sm-6');
			$("#cDoc").removeClass('col-sm-12');
			$("#c2Doc").fadeIn();
		} else{
			$("#c2Doc").fadeOut();
			$("#cDoc").removeClass('col-sm-6');
			$("#cDoc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		//chart.reflow();
		contid = '#c2Doc';
	} else if(id == 'areas'){
		if(checked == true){
			$("#c4Doc").addClass('col-sm-6');
			$("#c4Doc").removeClass('col-sm-12');
			$("#c3Doc").fadeIn();
		} else{
			$("#c3Doc").fadeOut();
			$("#c4Doc").removeClass('col-sm-6');
			$("#c4Doc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		contid = '#c3Doc';
	} else if(id == 'doc'){
		if(checked == true){
			$("#c3Doc").addClass('col-sm-6');
			$("#c3Doc").removeClass('col-sm-12');
			$("#c4Doc").fadeIn();
		} else{
			$("#c4Doc").fadeOut();
			$("#c3Doc").removeClass('col-sm-6');
			$("#c3Doc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		contid = '#c4Doc';
	} else if(id == 'prom'){
		if(checked == true){
			$("#c6Doc").addClass('col-sm-6');
			$("#c6Doc").removeClass('col-sm-12');
			$("#c5Doc").fadeIn();
		} else{
			$("#c5Doc").fadeOut();
			$("#c6Doc").removeClass('col-sm-6');
			$("#c6Doc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		contid = '#c5Doc';
	} else if(id == 'promd'){
		if(checked == true){
			$("#c5Doc").addClass('col-sm-6');
			$("#c5Doc").removeClass('col-sm-12');
			$("#c6Doc").fadeIn();
		} else{
			$("#c6Doc").fadeOut();
			$("#c5Doc").removeClass('col-sm-6');
			$("#c5Doc").addClass('col-sm-12');
		}
		$(window).trigger('resize')
		contid = '#c6Doc';
	}
}

function initGraficosBarra(data){
	arrayYear  = JSON.parse(data.year);
	arrayProm  = JSON.parse(data.promedio);
	arrayDoc   = JSON.parse(data.docente);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'-'+mm+'-'+yyyy;
	var fileName = 'docentes_anual_' + today;
	var options = {
	    chart: {
	        type: 'column'
	    },
        exporting: {
            filename: fileName
        },
	    title: {
	        text: ''
	    },
	    xAxis: {
	        categories: arrayDoc
	    },
	    yAxis: {
	        min: 0,
	        title: {
                text: 'Promedio'
            }
	    },
	    tooltip: {
	        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
	        shared: false
	    }
	}
	$('#container6Doc').highcharts(options);
	var chart = $('#container6Doc').highcharts();
	for(i=0;i<arrayYear.length;i++){
		chart.addSeries({
			name: arrayYear[i],
	        data: arrayProm[i],
	        zIndex: 1,
	        pointWidth: 15
	    });
	}
}

function getDetalleEvalIndi(nomIndi, fecha) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {nomIndi : nomIndi,
					 fecha   : fecha},
		    url   : 'c_grafico/getDetalleEvaluacionesIndicador',
		    type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#titleTableDetalle2').html(data.titleTable);
			$('#contTableDetalle').html(data.table);
			$('#modalTamano').removeClass('modal-sm');
			$('#modalTamano').addClass('modal-lg');
			$('#tbDetalle').bootstrapTable({ });
			initSearchTable();
			abrirCerrarModal('modalDetalle');
		});
	});
}

function getDetalleEvalDoce(nomDoce, fecha, value) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { nomDoce : nomDoce,
				 	 fecha   : fecha,
				 	 value   : value },
		   url   : 'c_grafico/getDetalleEvaluacionDocente',
		   type  : 'POST'
		}).
		done(function(data) {
			data = JSON.parse(data);
			$('#modalTamano').removeClass('modal-lg');
			$('#modalTamano').addClass('modal-sm');
			$('#contTableDetalle').removeClass('p-0');
			$('#contTableDetalle').html(data.body);
			$('#titleTableDetalle2').html(data.title);
			abrirCerrarModal('modalDetalle');
		});
	});
}

function getDetalleEvalDoceIndi(dato, fecha) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {dato  : dato,
				 	 fecha : fecha},
		   url   : 'c_grafico/getDetalleEvaluacionIndiDoce',
		   type  : 'POST',
		   async : false
		}).
		done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#modalTamano').removeClass('modal-lg');
				$('#modalTamano').addClass('modal-sm');
				$('#contTableDetalle').removeClass('p-0');
				$('#contTableDetalle').html(data.body);
				$('#titleTableDetalle2').html(data.title);
				abrirCerrarModal('modalDetalle');
			} else {
				
			}
		});
	});
}

function getDetalleEvalDoce(nomIndi, fecha) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {nomIndi : nomIndi,
					 fecha   : fecha},
		    url   : 'c_grafico/getDetalleEvaluacionesIndicador',
		    type  : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#titleTableDetalle2').html(data.titleTable);
			$('#contTableDetalle').html(data.table);
			$('#modalTamano').removeClass('modal-sm');
			$('#modalTamano').addClass('modal-lg');
			$('#tbDetalle').bootstrapTable({ });
			initSearchTable();
			abrirCerrarModal('modalDetalle');
		});
	});
}

function changeIdModal(modal) {
	$("#optionFiltro").unbind("click");
	if(modal == 'modalDocentes') {
		$('#liGraficosPanel').css('display','block');
	} else{
		$('#liGraficosPanel').css('display','none');
	}
	$('#optionFiltro').click(function() {
		abrirModalFitros(modal);
	});
}

var firstLoadTab2 = null;
$('a[href="#tab-1"]').click(function(event) {
	$('#menu .mfb-component__list').removeAttr( 'style' );
});

$('a[href="#tab-2"]').click(function(event) {
	$('#menu .mfb-component__list').css('display', 'none');
	if(firstLoadTab2 == null) {
		getGraficosAll();
	}
	firstLoadTab2 = 1;
});