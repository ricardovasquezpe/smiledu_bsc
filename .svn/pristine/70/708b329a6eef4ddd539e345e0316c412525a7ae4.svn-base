var anioGlobal   = null;
var idAulaGlobal = null;
var idMainGlobal = null;

function init(){
	initLimitInputs('textComentario','message-text');
	initButtonLoad('botonEB','botonEM');
	initSearchTableNew();
}

function getAlumnos(btn) {
	var idAula = btn.closest('tr').find('.btnAulaID').data('id_aula');
	var year   = btn.closest('tr').find('.btnIdMain').data('year');	

	if(idAula == null ||  year == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/getAlumnos',
	    	data   : { idAula  : idAula, 
	    			   year    : year },
	       'async' : true
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   anioGlobal   = year;
	    	   idAulaGlobal = idAula;
	    	   idxTr = btn.closest('tr').data('index');
	    	   $('#tDocentes').css('display', 'none');
	    	   $('#container1').css('display', 'none');
	    	   $('#container2').css('display', 'none');
	    	   //$('#container3').css('display', 'none');
	    	   $('#container4').css('display', 'none');
	    	   $('#tAlumnos').css('display', 'block');
			   $('#contTbAlumnos').html(data.tabla_Alumnos);
			   $('#tbAlumnos').bootstrapTable({ });	
			   tableEventsAlumnosVisibility();
			   componentHandler.upgradeAllRegistered();
	    	   $.each($("#tbAulas tbody>tr"), function() {
	    		   $(this).css('background-color','white');
			   });
		       $("#tbAulas tr").filter(function() {
		    	   idxTr = btn.closest('tr').data('index');
			       return $(this).data('index') == idxTr;
			   }).css('background-color','rgb(245, 245, 245)');
	        } else {
	        	mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			 }	    	  
	    });
	});
}
var opcBimestreGlobal = null;
var idAlumnoGlobal    = null;
function getModalBimestre(btnBim, opcBim) {
	var idAlumno = btnBim.closest('tr').find('.btnAlumnoID').data('id_alumno');
	if(opcBim == null || idAulaGlobal == null || anioGlobal == null || idAlumno == null) {
		return;
	}
	modal('modalBimestre');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/getModalTableBimestre',
	    	data   : { idAula 	   : idAulaGlobal,
	    			   year 	   : anioGlobal,
	    			   opcBimestre : opcBim, 
	    			   idAlumno    : idAlumno }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   opcBimestreGlobal = opcBim;
	    	   idAlumnoGlobal    = idAlumno; 
			  $('#contCursos').html(data.tabla_CursosBimestrales);
			  $('#tbCursos').bootstrapTable({ });
			  tableEventsNotaBimestreVisibility();
			  componentHandler.upgradeAllRegistered();
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
	
}

function getGraficosAula() {
	if(idAulaGlobal == null || anioGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/getGraficosAula',
	    	data   : { idAula : idAulaGlobal,
	    			   year   : anioGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   modal('modalGrafico');	
	    	   initGraficoAulaPromedio(JSON.parse(data.arrayNotasAlumnosAula), 'contGrafico');
	    	   initGraficoCirculeAsistencia(JSON.parse(data.asistenciaAula)  , 'contGraficoAsistenciaAula');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function getDocentes(btn) {
	var idAula = btn.closest('tr').find('.btnAulaID').data('id_aula');
	var year   = btn.closest('tr').find('.btnIdMain').data('year');	
	if(idAula == null || year == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/getDocentes',
	    	data   : { idAula 	   : idAula,
	    			   year 	   : year }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	  $('#tAlumnos').css('display', 'none');
	    	  $('#container1').css('display', 'none');
	    	  $('#container2').css('display', 'none');
	    	  $('#container3').css('display','none');
	    	  $('#container4').css('display','none');
	    	  $('#tDocentes').css('display', 'block');
			  $('#contTbDocentes').html(data.tabla_Docentes);
			  $('#tbDocentes').bootstrapTable({ });
			  tableEventsDocentesVisibility();
			  componentHandler.upgradeAllRegistered();
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function getDetalleNotas(btn) {
	var idGrado = btn.data('id_grado');
	var idCurso	= btn.data('id_curso');
	if(anioGlobal == null || idGrado == null || idCurso == null || opcBimestreGlobal == null || idAlumnoGlobal == null || idAulaGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/getDetalleNotas',
	    	data   : { idGrado 	   : idGrado,
	    			   year 	   : anioGlobal,
	    			   idAlumno    : idAlumnoGlobal,
	    			   idCurso     : idCurso,
	    			   opcBim      : opcBimestreGlobal,
	    			   idAula      : idAulaGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   modal('modalDetalleNotas');
			   $('#contTbDetalleNotas').html(data.tabla_DetalleNotas);
			   $('#tbDetalleNotas').bootstrapTable({ });
			   tableEventsDetalleNotaVisibility();
			   
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function ver_graficosAlumno(liObj) {
	var idAlum = liObj.closest('tr').find('.btnAlumnoID').data('id_alumno');
	if(idAlum == null || anioGlobal == null) {
		return;
	}
		Pace.restart();
		Pace.track(function() {
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'c_tutoria/ver_graficosAlumno',
		    	data   : { idAlumno : idAlum,
	    			   	   Anio     : anioGlobal,
	    			       idMain   : idMainGlobal,
	    			       idAula   : idAulaGlobal }
		    }).done(function(data) { 
			       data = JSON.parse(data);
			       if(data.error == 0) {
			    	   modal('modalGraficoAlumno');
			    	   //$('#tAlumnos').css('display','none');
			    	   initGraficoBarrasPromedio(JSON.parse(data.arraysPromedioBim), 'contGraficAlumnos');
			    	   initGraficoBarrasPromedio(JSON.parse(data.arraysPromedioBim), 'container');
			    	   initGraficoCirculeAsistencia(data,'contGraficAsistencia');
			       } else {
						mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
					}	    	  
			    });
			});	
}

function getCorreo(btn) {
	var correo = btn.data('correo_doc');
	$('#correoDocente').val(correo);
	modal('emailModal');
}

var idAlumGlobal = null;
function getModalLibreta(liObj) {
	$('#icon'+bimGlobal).css('display', 'inline-block');
	$('#gen'+bimGlobal).css('display' , 'none');
	$('#coment').css('display', 'none');
	idAlumGlobal = liObj.closest('tr').find('.btnAlumnoID').data('id_alumno');
	modal('modalElegirBimestre');
	$(".mdl-button.mdl-button--raised").prop("disabled",true);
}

var bimGlobal = null;
function selecBimestre(opc) {
	$('#icon'+bimGlobal).css('display', 'inline-block');
	$('#gen'+bimGlobal).css('display' , 'none');
	bimGlobal = opc;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/printInputCompentario',
	    	data   : { idAlumno : idAlumGlobal,
	    			   idAula   : idAulaGlobal,
	    			   Anio     : anioGlobal,
	    			   bimestre : bimGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	   	       $('#textComentario').val(data.comen_Bim);
	    	   $('#coment').css('display', 'inline-block');
	    	   $(".mdl-button.mdl-button--raised").prop("disabled",false);
	    	   $('#gen'+opc).css('display', 'inline-block');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});	
}

function generarLibreta() {		
	if(bimGlobal == null || bimGlobal == '') {
		return;
	}
	
	if(bimGlobal == 1) {
		var comentario_bim = $('#textComentario').val();
		$('#bimestre').val(bimGlobal);
	} else if(bimGlobal == 2) {
		var comentario_bim = $('#textComentario').val();
		$('#bimestre').val(bimGlobal);
	} else if(bimGlobal == 3) {
		var comentario_bim = $('#textComentario').val();
		$('#bimestre').val(bimGlobal);
	} else if(bimGlobal == 4) {
		var comentario_bim = $('#textComentario').val();
		$('#bimestre').val(bimGlobal);
	}
	
	if(comentario_bim == null || idAlumGlobal == null || idAulaGlobal == null || anioGlobal == null) {
		return;
	}
	addLoadingButton('botonEB');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/agregarComentario',
	    	data   : { comentario_bim : comentario_bim,
	    			   idAlumno    	  : idAlumGlobal,
	    			   idAula         : idAulaGlobal,
	    			   Anio           : anioGlobal,
	    			   bimestre       : bimGlobal,
	    			   idMain         : idMainGlobal }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	   		   $('#coment').css('display', 'inline-block');
			   $('#icon'+bimGlobal).css('display', 'inline-block');
			   $('#gen'+bimGlobal).css('display' , 'none');
	    	   
	    	   
	    	   initGraficoBarrasPromedio(JSON.parse(data.arraysPromedioBim),'container1');
	    	   initGraficoBarrasPromedio(JSON.parse(data.arraysPromedioCursos),'container2');
	    	   initGraficoAsistencia(JSON.parse(data.asistencia), 'container3');
	    	   var base64G1 = getBaseByGraficoSVG('container1');
	    	   var base64G2 = getBaseByGraficoSVG('container2');
	    	   var base64G3 = getBaseByGraficoSVG('container3');
	    	   $('#imagenGrafico1').val(base64G1);
	    	   $('#imagenGrafico2').val(base64G2);
	    	   $('#imagenGrafico3').val(base64G3);
	    	   $('#idAula').val(idAulaGlobal);
	           $('#idAnio').val(anioGlobal);
	    	   $('#idAlumno').val(idAlumGlobal);
	    	   $('#idMainGlobal').val(idMainGlobal);
	    	   $('#formLibreta').submit();
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}
	       stopLoadingButton('botonEB');
	    });
	});	
}

function agregarComentario(bimestre) {
	if(bimestre == null) {
		return;
	}
	if(bimestre == 1) {
		var comentario_bim = $('#textComentario_b1').val();
	} else if(bimestre == 2) {
		var comentario_bim = $('#textComentario_b2').val();
	} else if(bimestre == 3) {
		var comentario_bim = $('#textComentario_b3').val();
	} else if(bimestre == 4) {
		var comentario_bim = $('#textComentario_b4').val();
	}
	
	if(comentario_bim == null || idAlumGlobal == null || idAulaGlobal == null || anioGlobal == null) {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'c_tutoria/agregarComentario',
	    	data   : { comentario_bim : comentario_bim,
	    			   idAlumno    	  : idAlumGlobal,
	    			   idAula         : idAulaGlobal,
	    			   Anio           : anioGlobal,
	    			   bimestre       : bimestre }
	    }).done(function(data) { 
	       data = JSON.parse(data);
	       if(data.error == 0) {
	    	   $('#contComentario').val('Agregado');
	       } else {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj);
			}	    	  
	    });
	});
}

function initGraficoBarrasPromedio(arrays, container) {
	var options = {
        chart: {
            type: 'column',
            width: '870'
        },
        credits: {
        	enabled: false 		
        },
        
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}'
                }
            }
        },

        title: {
            text: ''
        },
        exporting: {
            enabled: false,
            type: 'image/jpeg'
        },
        xAxis: {
            categories: JSON.parse(arrays.arrayCate)
        },

        yAxis: {
            title: {
                text: ''
            },
            allowDecimals: false,
            min: 0,
            max: 20
        },

        series: [{
            name: '',
            showInLegend: false,  
            data: 
            	JSON.parse(arrays.arrayProm),
            
            color: '#F5851D'
        }, {
            type: 'spline',
            color: '#757575',
            showInLegend: false,
            data: JSON.parse(arrays.arrayProm),           
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: '#757575'
            }
        }]
    };
	$('#'+container).highcharts(options);
}

function initGraficoAsistencia(data, container) {
	var options = {
		chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        credits: {
        	enabled: false 		
        },
        title: {
            text: '',
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%']
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            innerSize: '50%',
            data: [
                ['', JSON.parse(data.tarde_justif)],
                ['', JSON.parse(data.tarde)],
                ['', JSON.parse(data.falta)],
                ['', JSON.parse(data.falta_justi)],
                {
                    name: 'Proprietary or Undetectable',
                    y: 0.2,
                    dataLabels: {
                        enabled: false
                    }
                }
            ]
        }]
	};
	$('#'+container).highcharts(options);
}

function initGraficoCirculeAsistencia(data, container) {
	var options = {
	        chart: {
	        	width: '870',
	            type: 'pie' 
	        },
	        title: {
	            text: 'Asistencia'
	        },
	        subtitle: {
	            text: 'Asistencia del Aula'
	        },
	        plotOptions: {
	            series: {
	                dataLabels: {
	                    enabled: true,
	                    format: '{point.name}: {point.y:.1f}%'
	                }
	            }
	        },

	        tooltip: {
	            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
	        },
	        
	        legend: {
	            enabled: false
	        },
	        
	        series: [{
	            name: 'Brands',
	            colorByPoint: true,
	            data: [{
	                name: 'Tardanza',
	                y: JSON.parse(data.tarde),
	                drilldown: 'Tardanza'
	            }, {
	                name: 'Tardanza Justificada',
	                y: JSON.parse(data.tarde_justif),
	                drilldown: 'Tardanza Justificada'
	            }, {
	                name: 'Falta',
	                y: JSON.parse(data.falta),
	                drilldown: 'Falta'
	            }, {
	                name: 'Falta Justificada',
	                y: JSON.parse(data.falta_justif),
	                drilldown: 'Falta Justificada'
	            }]
	        }]
	};
	$('#'+container).highcharts(options);
}

function initGraficoAulaPromedio(data, container) {
	var options = {
	        chart: {
	            type: 'column',
	            width: '870'
	        },
	        title: {
	            text: 'Promedio de cada alumno del Aula'
	        },
	        
	        xAxis: {
	            type: 'category',
	            labels: {
	                rotation: -45,
	                style: {
	                    fontSize: '13px',
	                    fontFamily: 'Verdana, sans-serif'
	                }
	            }
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: 'Notas'
	            }
	        },
	        legend: {
	            enabled: false
	        },
	        tooltip: {
	            pointFormat: 'Notas Finales'
	        },
	        series: [{
	            name: 'Population',
	            data: JSON.parse(data.arrayGeneral),
	            dataLabels: {
	                enabled: true,
	                rotation: -90,
	                color: '#FFFFFF',
	                align: 'right',
	                format: '{point.y:.1f}', // one decimal
	                y: 10, // 10 pixels down from the top
	                style: {
	                    fontSize: '13px',
	                    fontFamily: 'Verdana, sans-serif'
	                }
	            }
	        }]
	};
	$('#'+container).highcharts(options);
}

function getBaseByGraficoSVG(container) {
	var chart = $('#'+container).highcharts();
	svg = chart.getSVG();
	svg = "data:image/svg+xml,"+svg;
	$('#binary').attr('src', svg);
	   
    return getBase64Image(document.getElementById("binary"));
}

function tableEventsAlumnosVisibility() {
	$(function () {
	    $('#tbAlumnos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsDocentesVisibility() {
	$(function () {
	    $('#tbDocentes').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsDetalleNotaVisibility() {
	$(function () {
	    $('#tbDetalleNotas').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsNotaBimestreVisibility() {
	$(function () {
	    $('#tbCursos').on('all.bs.table', function (e, name, args) {})
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}