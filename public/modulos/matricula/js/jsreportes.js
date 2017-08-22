cons_data = null;
cons_num  = null;
cons_view = 1;

function init(){
	initButtonLoad('botonFR');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
}

function getComboByTipoReporte(){
	addLoadingButton('botonFR');
	var valorReporte = $("#selectTipoReporte").val();
	if(valorReporte.length != 0){
		$("#img-filtrar-reporte").fadeIn();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/TipoReporte',
			data    : {valreporte : valorReporte},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			$("#comboReportes").html(data.combos);
			$("#comboReportes_6").html(null);
			//$("#cont_tabla_reportes1").html(null);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			    $('.selectButton').selectpicker('mobile');
			} else {
				$('.selectButton').selectpicker();
			}
			
			$("#cont_alumnos").css("display","none");
			$("#cont_tabla_reportes1").html(null);
			$("#cont_filter_empty").css("display","block");
			
			componentHandler.upgradeAllRegistered();
			stopLoadingButton('botonFR');
		});
	}else if(valorReporte.length==0){
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#comboReportes").html(null);
		$("#cont_filter_empty").css("display","block");
		stopLoadingButton('botonFR');
	}else{
		$("#cont_tabla_reportes1").html(null);
		$("#comboReportes").html(null);
		$("#cont_filter_empty").css("display","block");
		stopLoadingButton('botonFR');
	}
}

function getSedesByYear(year, idSede, idGradoNivel, idAula){
	addLoadingButton('botonFR');
	valorYear = $("#"+year).val();
	if(valorYear.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getSedesByYear',
			data    : {year   : valorYear},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idSede, data.comboSedes, "Sede");
			setCombo(idGradoNivel, null, "Grado - Nivel");
			setCombo(idAula, null, "Aula");
			setearCombo('selectEstadoReporte', null);
			//CASOS ESPECIALES
			if($("#selectAulaReporte").length){
				habilitarCombo('selectAulaReporte','selectMesReporte');
				habilitarRadioButton('selectAulaReporte', 'rbAulas');
				habilitarRadioButton('selectAulaReporte', 'rbTraslado');
				habilitarCombo('selectAulaReporte','selectEstadoReporte');
			}
			
			//$("#cont_tabla_reportes1").html(null);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			    $('.selectButton').selectpicker('mobile');
			} else {
				$('.selectButton').selectpicker();
			}
			$("#cont_alumnos").css("display","none");
			$("#cont_tabla_reportes1").html(null);
			$("#cont_filter_empty").css("display","block");
			disableEnableRadioButton('rbAulas', true);
			disableEnableRadioButton('rbTraslado', true);
			disableEnableRadioButton('rbRatificacion', true);
	    	stopLoadingButton('botonFR');
		});
	}else{
		setCombo(idSede, null, "Sede");
		setCombo(idGradoNivel, null, "Grado - Nivel");
		setCombo(idAula, null, "Aula");
		setearCombo('selectEstadoReporte', null);
//		document.getElementById('btnGrafico').style.display = 'none';
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
		disableEnableRadioButton('rbAulas', true);
		disableEnableRadioButton('rbTraslado', true);
		//CASOS ESPECIALES
		if($("#selectAulaReporte").length){
			habilitarCombo('selectAulaReporte','selectMesReporte');
			habilitarRadioButton('selectAulaReporte', 'rbAulas');
			habilitarRadioButton('selectAulaReporte', 'rbTraslado');
			habilitarCombo('selectAulaReporte','selectEstadoReporte');
		}
    	stopLoadingButton('botonFR');
	}
}

function habilitarCombo(comboE, comboR){
	var valor = $("#"+comboE).val();
	if(valor.length != 0){
		disableEnableCombo(comboR, false);
	}else{
		setearCombo(comboR, null);
		disableEnableCombo(comboR, true);
	}
}

function habilitarCheckBox(comboE, cbR){
	var valor = $("#"+comboE).val();
	if(valor.length != 0){
		disableEnableCheckbox(cbR, false);
	}else{
		disableEnableCheckbox(cbR, true);
	}
}

function habilitarRadioButton(comboE, rbR){
	var valor = $("#"+comboE).val();
	if(valor.length != 0) {
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
		disableEnableRadioButton(rbR, false);
	} else {
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
		disableEnableRadioButton(rbR, true);
	}
}

function getGradoNivelBySedeYear(year, idSede, idGradoNivel, idAula){
	addLoadingButton('botonFR');
	var valorYear = $("#"+year).val();
	var valorSede = $("#"+idSede).val();
	var valorReporte = $("#selectTipoReporte").val();
	if( valorSede.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getGradoNivelBySedeYear',
			data    : {idsede  : valorSede,
				       year    : valorYear,
				       reporte : valorReporte},
			'async' : true
		}).done(function(data){
			$("#img-filtrar-reporte").fadeOut();
			data = JSON.parse(data);
			setCombo(idGradoNivel, data.comboGradoNivel, "Grado - Nivel");
			setCombo(idAula, null, "Aula");
			setearCombo('selectEstadoReporte', null);
			//$("#cont_tabla_reportes1").html(null);
			//CASOS ESPECIALES
			if($("#selectAulaReporte").length){
				habilitarCombo('selectAulaReporte','selectMesReporte');
				habilitarRadioButton('selectAulaReporte', 'rbAulas');
				habilitarRadioButton('selectAulaReporte', 'rbTraslado');
				habilitarCombo('selectAulaReporte','selectEstadoReporte');
			}
			
			if(data.reporte != 5){
				$("#cont_alumnos").css("display","none");
				$("#cont_tabla_reportes1").html(null);
				$("#cont_filter_empty").css("display","block");
			}
			stopLoadingButton('botonFR');
		});
	} else {
		setCombo(idGradoNivel, null, "Grado - Nivel");
		setCombo(idAula, null, "Aula");
		setearCombo('selectEstadoReporte', null);
		
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
		//CASOS ESPECIALES
		if($("#selectAulaReporte").length){
			habilitarCombo('selectAulaReporte','selectMesReporte');
			habilitarRadioButton('selectAulaReporte', 'rbAulas');
			habilitarRadioButton('selectAulaReporte', 'rbTraslado');
			habilitarCombo('selectAulaReporte','selectEstadoReporte');
		}
		stopLoadingButton('botonFR');
	}
}

function getReporteByTipoReporte(num, opc){
	addLoadingButton('botonFR');
	Pace.restart();
	Pace.track(function() {
		cons_num = num;
		var data = null;
		var pass = null;
		cons_view = 1;
		$("#cont_tabla_reportes2").css("display","none");
		$("#cont_tabla_reportes1").css("display","block");
		$("#btn-reporte").css("display","none");
		if(num == 1){
			valorYear        = $("#selectYearReporte").val();
			valorSede        = $("#selectSedeReporte").val();
			valorGradoNivel  = $("#selectGradoNivelReporte").val();
			if(valorGradoNivel.length != 0){
				data = {year    	 : valorYear,
				        idsede       : valorSede,
				        idgradonivel : valorGradoNivel,
				        tipo         : num};
				cons_data = data;
				pass = 1;
			} else {
				$("#cont_alumnos").css("display","none");
				$("#cont_tabla_reportes1").html(null);
				$("#cont_filter_empty").css("display","block");
			}
		}else if(num == 2){
			valorAula      = $("#selectAulaReporte").val();
			valorMes       = $("#selectMesReporte").val();
			if(valorAula.length != 0){
				$("#img-filtrar-reporte").fadeIn();
				data = {idaula : valorAula,
				        mes    : valorMes,
				        tipo   : num};
				cons_data = data;
				pass = 1;
			} else {
				$("#cont_alumnos").css("display","none");
				$("#cont_tabla_reportes1").html(null);
				$("#cont_filter_empty").css("display","block");
			}
		}else if(num == 3){
			valorAula      = $("#selectAulaReporte").val();
			valorTipo      = $('input[name=rbAulas]:checked').val();
			if(valorAula.length != 0){
				data = {idaula  : valorAula,
				        valorcb : valorTipo,
				        tipo    : num};
				cons_data = data;
				pass = 1;
			}
		}else if(num == 4){
			valorSede         = $("#selectSedeReporte").val();
			valorGradoNivel   = $("#selectGradoNivelReporte").val();
			valorEstado       = $("#selectEstadoReporte").val();
			if(valorGradoNivel.length != 0){
				data = {idsede       : valorSede,
						idgradonivel : valorGradoNivel,
				        estado       : valorEstado,
				        tipo   		 : num};
				cons_data = data;
				pass = 1;
			} else {
				$("#cont_alumnos").css("display","none");
				$("#cont_tabla_reportes1").html(null);
				$("#cont_filter_empty").css("display","block");
			}
		}else if(num == 5){
			valorYear        = $('#selectYearReporte option:selected').val();
			valorSede        = $("#selectSedeReporte").val();
			valorGradoNivel  = null;
			if(opc == 1){
				valorGradoNivel  = $("#selectGradoNivelReporte").val();
			}
			if(valorSede.length != 0){
				data = {year    	 : valorYear,
				        idsede       : valorSede,
				        idgradonivel : valorGradoNivel,
				        tipo         : num};
				cons_data = data;
				pass = 1;
			}
		}else if(num == 6){
			valorParentezco = $("#selectParentezcoReporte").val();
			valorBusqueda   = $("#selectTipoBusquedaReporte").val();
			valorAula       = $("#selectAulaReporte").val() != null ? $("#selectAulaReporte").val() : '';
			valorDistrito   = $("#selectDistritoReporte").val() != null ? $("#selectDistritoReporte").val() : '';
			if($("#selectAulaReporte").length && valorAula.length != 0) {
				data = {idaula 		   : valorAula,
						idtipobusqueda : valorBusqueda,
						idparentezcos  : valorParentezco,
				        tipo           : num};
				cons_data = data;
				pass = 1;
			} else if(("#selectDistritoReporte").length && valorDistrito.length != 0) {
				valorDepartamento = $("#selectDepartamentoReporte").val();
				valorProvincia    = $("#selectProvinciaReporte").val();
				data = {iddepartamento : valorDepartamento,
						idprovincia    : valorProvincia,
						iddistrito 	   : valorDistrito,
						idtipobusqueda : valorBusqueda,
						idparentezcos  : valorParentezco,
				        tipo           : num};
				cons_data = data;
				pass = 1;
			}
		}else if(num == 7){
			valorCursos      = $("#selectCursoReporte").val();
			valorYear        = $("#selectYearReporte").val();
			valoSede         = $("#selectSedeReporte").val();
			valorGradoNivel  = $("#selectGradoNivelReporte").val() != null ? $("#selectGradoNivelReporte").val() : '';
			
			if($("#selectGradoNivelReporte").length && valorGradoNivel.length != 0){
				data = {idcursos      : valorCursos,
						year          : valorYear,
						idsede        : valoSede,
						idgradonivel  : valorGradoNivel,
				        tipo          : num};
				cons_data = data;
				pass = 1;
			}
		}else if(num == 8){
			valorYear        = $("#selectYearReporte").val();
			valoSede         = $("#selectSedeReporte").val();
			valorTipo        = $('input[name=rbTraslado]:checked').val();
			if($("#selectSedeReporte").length && valoSede.length != 0){
				data = {year          : valorYear,
						idsede        : valoSede,
						valorcb       : valorTipo,
				        tipo          : num};
				cons_data = data;
				pass = 1;
			}
		} else if(num == 9){
			valorYear          = $("#selectYearReporte").val();
			valorSede          = $("#selectSedeReporte").val();
			valorGradoNivel    = $("#selectGradoNivelReporte").val();
			valorAula          = $("#selectAulaReporte").val();
			valorTipo          = $('input[name=rbRatificacion]:checked').val();
			if($("#selectSedeReporte").length && valorSede.length != 0){
				data = {year          : valorYear,
						idsede        : valorSede,
						idgradonivel  : valorGradoNivel,
						idaula 		  : valorAula,
						valorcb       : valorTipo,
				        tipo          : num};
				cons_data = data;
				pass = 1;
			}
		}
		if(pass == 1){
			$("#cont_filter_empty").css("display", "none");
			$("#cont_alumnos").css("display", "block");
			$.ajax({
				type    : 'POST',
				'url'   : 'c_reportes/getReporteByTipo',
				data    : data,
				'async' : true
			}).done(function(data){
				$("#img-filtrar-reporte").fadeOut();
				data = JSON.parse(data);
				$("#cont_tabla_reportes1").html(data.resultado);
				//$("#btnGrafico").css("display", "");
				$("#tbResultado").bootstrapTable({});
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				generarBotonMenu(num);
				componentHandler.upgradeAllRegistered();
				initSearchTableNew();
				stopLoadingButton('botonFR');
				$('[data-toggle="tooltip"]').tooltip();	
			});
		}else{
			stopLoadingButton('botonFR');
		}

	});
}

function generarBotonMenu(num){
	var div = $('#tbResultado').parent().parent().parent().find(".fixed-table-toolbar .columns.columns-right.btn-group.pull-right").first();
	
	if(num == 2 || num == 5 || num == 6 || num == 7 || num == 8){
		div.append('<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="btn-grafico" onclick="getGrafico()">'+
				'<i class="mdi mdi-insert_chart"></i>'+
				'</button>');
	}
		
	div.append('<button id="demo-menu-lower-right"class="mdl-button mdl-js-button mdl-button--icon"><i class="mdi mdi-more_vert"></i></button>'+
			    '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"'+
			        'for="demo-menu-lower-right">'+
			        '<li class="mdl-menu__item" onclick="getPDFByTipo()">Descargar PDF</li>'+
			        '<li class="mdl-menu__item" data-toggle="modal" data-target="#modalSubirPaquete" data-paquete-text="Enviar reporte por correo">Enviar Correo</li>'+
			    '</ul>');
}

function abrirModalAlumnosSexo(idaula, sexo){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_reportes/abrirModalAlumnosSexo',
		data    : {idaula : idaula,
			       sexo   : sexo},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_AlumnosAula").html(data.tablaAlumnos);
		$("#tbAlumnosAula").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		abrirCerrarModal("modalAlumnos");
	});
}

function abrirModalAlumnos(idaula){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/abrirModalAlumnos',
		data    : {idaula    : idaula},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_AlumnosAula").html(data.tablaAlumnos);
		$("#tbAlumnosAula").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		abrirCerrarModal("modalAlumnos");
	});
}

function goToViewAlumno(idAlumno){
	$.ajax({
		data :{idalumno : idAlumno},
		url : 'c_alumno/goToViewAlumno',
		async : false,
		type : 'POST'
	}).done(function(data) {
		window.location.href = 'c_detalle_alumno';
	});
}

function getPDFByTipo(){
	var path = '';
	$.ajax({
		type    : 'POST',
		'url'   : 'c_reportes/getPDFByTipo',
		data    : cons_data,
		'async' : false
	}).done(function(data){
		path = data;
		window.open(data, '_blank');
	})
	.always(function(jqXHR, textStatus, jqXHR2) {
		 setTimeout(function(){
			 borrar(path);
			}, 1500);
	});
}

function borrar(path){
	$.ajax({
		data : { ruta : path },
        url: "c_reportes/borrarPDF",
        async : false,
        type: 'POST'
	})
	.done(function(data) {
	});
}

function getReporteTable(){
	$("highcharts").hide();
	$("#tabla-reporte").show();
}

function changeView(){
	if(cons_view == 2){
		$("#cont_tabla_reportes2").css("display","none");
		$("#cont_tabla_reportes1").css("display","block");
		$("#btn-reporte").css("display","none");		
	}else{
		$("#cont_tabla_reportes2").css("display","block");
		$("#cont_tabla_reportes1").css("display","none");
		$("#btn-reporte").css("display","inline-block");
	}	
}

function getGrafico(){
	$("#cont_tabla_reportes1").css("display", "none");
	$("#cont_tabla_reportes2").css("display", "block");
	$("#btn-reporte").css("display","inline-block");
	cons_view = 2;
	if(cons_num == 2){
		valorAula  = $("#selectAulaReporte").val();
		valorMes   = $("#selectMesReporte").val();
		
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getGrafico',
			data    : {idaula : valorAula,
				       mes    : valorMes,
				       tipo   : cons_num},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			
			cant  = JSON.parse(data.cant);
			mes   = JSON.parse(data.meses);
			
			var options = {
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: 'Estudiantes'
		        },
		        subtitle: {
		            text: 'Cumplea\u00f1os'
		        },
		        xAxis: {
		            categories: mes,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: 'Estudiantes (#)'
		            }
		        },
		        tooltip: {
		            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
		            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad: </td>' +
		                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
		            footerFormat: '</table>',
		            shared: true,
		            useHTML: true
		        },
		        plotOptions: {
		            column: {
		                pointPadding: 0.2,
		                borderWidth: 0
		            }
		        }
			}

			$('#cont_tabla_reportes2').highcharts(options);
			var chart = $('#cont_tabla_reportes2').highcharts();
			
			chart.addSeries({
				name: 'meses',
	            data: cant
	        });
		});
	} else if(cons_num == 5){
		valorYear  = $("#selectYearReporte").val();
		valorSede  = $("#selectSedeReporte").val();
		valorGradoNivel = $("#selectGradoNivelReporte").val();
		
		if(valorSede.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_reportes/getGrafico',
				data    : {year    	    : valorYear,
				           idsede       : valorSede,
				           idgradonivel : valorGradoNivel,
				           tipo         : cons_num},
				'async' : false
			}).done(function(data){
				data = JSON.parse(data);
				
				aula     = JSON.parse(data.aula);
				varones  = JSON.parse(data.varones);
				mujeres  = JSON.parse(data.mujeres);
				var options = {
			        chart: {
			            type: 'column'
			        },
			        title: {
			            text: 'Estudiantes'
			        },
			        subtitle: {
			            text: 'Sexo'
			        },
			        xAxis: {
			            categories: aula,
			            crosshair: true
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: 'Estudiantes (#)'
			            }
			        },
			        tooltip: {
			            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			            footerFormat: '</table>',
			            shared: true,
			            useHTML: true
			        },
			        plotOptions: {
			            column: {
			                pointPadding: 0.2,
			                borderWidth: 0
			            }
			        }
				}

				$('#cont_tabla_reportes2').highcharts(options);
				var chart = $('#cont_tabla_reportes2').highcharts();
				chart.addSeries({
					name  : 'varones',
		            data: varones
		        });
				
				chart.addSeries({
					name  : 'mujeres',
		            data: mujeres
		        });
			});
		}
	} else if(cons_num == 6){
		valorAula       = $("#selectAulaReporte").val();
		valorDistrito   = $("#selectDistritoReporte").val();
		valorParentezco = $("#selectParentezcoReporte").val();
		valorBusqueda   = $("#selectTipoBusquedaReporte").val();
		
		if($("#selectAulaReporte").length && valorAula.length != 0){
			
			$.ajax({
				type    : 'POST',
				'url'   : 'c_reportes/getGrafico',
				data    : { idaula 		   : valorAula,
							idtipobusqueda : valorBusqueda,
							idparentezcos  : valorParentezco,
					        tipo           : cons_num},
				'async' : false
			}).done(function(data){
				data = JSON.parse(data);
				
				cant        = JSON.parse(data.cant);
				parentesco  = JSON.parse(data.parentesco);
				var options = {
			        chart: {
			            type: 'column'
			        },
			        title: {
			            text: 'Familiares'
			        },
			        subtitle: {
			            text: 'Parentesco'
			        },
			        xAxis: {
			            categories: parentesco,
			            crosshair: true
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: 'Parientes (#)'
			            }
			        },
			        tooltip: {
			            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad: </td>' +
			                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			            footerFormat: '</table>',
			            shared: true,
			            useHTML: true
			        },
			        plotOptions: {
			            column: {
			                pointPadding: 0.2,
			                borderWidth: 0
			            }
			        }
				}

				$('#cont_tabla_reportes2').highcharts(options);
				var chart = $('#cont_tabla_reportes2').highcharts();
				chart.addSeries({
					name  : 'Parentesco',
		            data: cant
		        });
			});
		} else if(("#selectDistritoReporte").length && valorDistrito.length != 0){
			valorDepartamento = $("#selectDepartamentoReporte").val();
			valorProvincia    = $("#selectProvinciaReporte").val();
			
			$.ajax({
				type    : 'POST',
				'url'   : 'c_reportes/getGrafico',
				data    : {iddepartamento  : valorDepartamento,
							idprovincia    : valorProvincia,
							iddistrito 	   : valorDistrito,
							idtipobusqueda : valorBusqueda,
							idparentezcos  : valorParentezco,
					        tipo           : cons_num},
				'async' : false
			}).done(function(data){
				data = JSON.parse(data);
				
				cant        = JSON.parse(data.cant);
				parentesco  = JSON.parse(data.parentesco);
				var options = {
			        chart: {
			            type: 'column'
			        },
			        title: {
			            text: 'Familiares'
			        },
			        subtitle: {
			            text: 'Parentesco'
			        },
			        xAxis: {
			            categories: parentesco,
			            crosshair: true
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: 'Parientes (#)'
			            }
			        },
			        tooltip: {
			            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad: </td>' +
			                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			            footerFormat: '</table>',
			            shared: true,
			            useHTML: true
			        },
			        plotOptions: {
			            column: {
			                pointPadding: 0.2,
			                borderWidth: 0
			            }
			        }
				}

				$('#cont_tabla_reportes2').highcharts(options);
				var chart = $('#cont_tabla_reportes2').highcharts();
				chart.addSeries({
					name  : 'Parentesco',
		            data  : cant
		        });
			});
		}
	} else if(cons_num == 7){
		valorCursos     = $("#selectCursoReporte").val();
		valorYear        = $("#selectYearReporte").val();
		valoSede        = $("#selectSedeReporte").val();
		valorGradoNivel = $("#selectGradoNivelReporte").val();
		if($("#selectGradoNivelReporte").length && valorGradoNivel.length != 0){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_reportes/getGrafico',
				data    :  {idcursos      : valorCursos,
							year          : valorYear,
							idsede        : valoSede,
							idgradonivel  : valorGradoNivel,
					        tipo          : cons_num},
				'async' : false
			}).done(function(data){
				data = JSON.parse(data);
				
				cant     = JSON.parse(data.cant);
				aulas  = JSON.parse(data.aulas);
				var options = {
			        chart: {
			            type: 'column'
			        },
			        title: {
			            text: 'Aulas'
			        },
			        subtitle: {
			            text: 'Docentes'
			        },
			        xAxis: {
			            categories: aulas,
			            crosshair: true
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: 'Docentes (#)'
			            }
			        },
			        tooltip: {
			            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad: </td>' +
			                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			            footerFormat: '</table>',
			            shared: true,
			            useHTML: true
			        },
			        plotOptions: {
			            column: {
			                pointPadding: 0.2,
			                borderWidth: 0
			            }
			        }
				}

				$('#cont_tabla_reportes2').highcharts(options);
				var chart = $('#cont_tabla_reportes2').highcharts();
				chart.addSeries({
					name  : 'Aulas',
		            data  : cant
		        });
			});
		}
	} else if(cons_num == 8){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getGrafico',
			data    :  cons_data,
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			
			niveles     = JSON.parse(data.niveles);
			intrasede   = JSON.parse(data.intrasede);
			intersedes  = JSON.parse(data.intersedes);
			var options = {
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: 'Traslados'
		        },
		        subtitle: {
		            text: 'Niveles'
		        },
		        xAxis: {
		            categories: niveles,
		            crosshair: true
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: 'Traslados (#)'
		            }
		        },
		        tooltip: {
		            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
		            pointFormat: '<tr><td style="color:{series.color};padding:0">Cantidad: </td>' +
		                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
		            footerFormat: '</table>',
		            shared: true,
		            useHTML: true
		        },
		        plotOptions: {
		            column: {
		                pointPadding: 0.2,
		                borderWidth: 0
		            }
		        }
			}

			$('#cont_tabla_reportes2').highcharts(options);
			var chart = $('#cont_tabla_reportes2').highcharts();
			chart.addSeries({
				name  : 'Intrasede',
	            data  : intrasede
	        });
			chart.addSeries({
				name  : 'Intersedes',
	            data  : intersedes
	        });
		});
	}
}

function disableEnableRadioButton(nameRB, disaEna){
	$('input[name='+nameRB+']').each(function (index, value) {
		$(this).attr("disabled", disaEna);
		$(this).parent().removeClass("is-checked");
		$(this).prop('checked', false);
		if(disaEna == false){
			$(this).parent().removeClass("is-disabled");
		}else{
			$(this).parent().addClass("is-disabled");
		}
		componentHandler.upgradeAllRegistered();
	});
}

function getAulasByGradoNivelSedeYear(year, idSede, idGradoNivel, idAula){
	addLoadingButton('botonFR');
	var valorYear       = $("#"+year).val();
	var valorSede       = $("#"+idSede).val();
    var valorGradoNivel = $("#"+idGradoNivel).val();
    if(valorGradoNivel.length != 0){
    	 $.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getTablaAulasByGradoNivelSedeYear',
			data    : {year         : valorYear,
				       idsede       : valorSede,
				       idgradonivel : valorGradoNivel},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idAula, data.comboAula, "Aula");
			$("#cont_alumnos").css("display","none");
			$("#cont_tabla_reportes1").html(null);
			$("#cont_filter_empty").css("display","block");
			//CASOS ESPECIALES
			if($("#selectAulaReporte").length){
				habilitarCombo('selectAulaReporte','selectMesReporte');
				habilitarRadioButton('selectAulaReporte', 'rbAulas');
				habilitarCombo('selectAulaReporte','selectEstadoReporte');
			}
			disableEnableRadioButton('rbRatificacion', false);
	    	stopLoadingButton('botonFR');
		});
    } else {
		setCombo(idAula, null, "Aula");
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
		//CASOS ESPECIALES
		if($("#selectAulaReporte").length){
			habilitarCombo('selectAulaReporte','selectMesReporte');
			habilitarRadioButton('selectAulaReporte', 'rbAulas');
			habilitarCombo('selectAulaReporte','selectEstadoReporte');
		}
		disableEnableRadioButton('rbRatificacion', false);
    	stopLoadingButton('botonFR');
    }
}

function selectCurso(idCurso){
	valores = $("#"+idCurso).val();
	if(valores != null){
		disableEnableCombo("selectYearReporte", false);
		disableEnableCombo("selectSedeReporte", false);
		disableEnableCombo("selectGradoNivelReporte", false);
		getReporteByTipoReporte(7);
	} else {
		setearCombo("selectYearReporte", null);
		setearCombo("selectSedeReporte", null);
		setearCombo("selectGradoNivelReporte", null);
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
		disableEnableCombo("selectYearReporte", true);
		disableEnableCombo("selectSedeReporte", true);
		disableEnableCombo("selectGradoNivelReporte", true);
	}
}

function selectParentezto(idParentezco){
	valores = $("#"+idParentezco).val();
	if(valores != null){
		disableEnableCombo("selectTipoBusquedaReporte", false);
		getReporteByTipoReporte(6);
		stopLoadingButton('botonFR');
	}else{
		setearCombo("selectTipoBusquedaReporte", null);
		$("#comboReportes_6").html(null);
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
		disableEnableCombo("selectTipoBusquedaReporte", true);
		stopLoadingButton('botonFR');
	}
}

function abrirModalDocentes(idaula){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_reportes/mostrarDocentesAula',
		data    : {idaula    : idaula},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_DocentesAula").html(data.tablaDocentes);
		$("#tbDocentesAula").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		abrirCerrarModal("modalDocentes");
	});
}

function selectTipoBusqueda(){
	var tipoBusqueda = $("#selectTipoBusquedaReporte").val();
	if(tipoBusqueda.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_reportes/getComboReporte6',
			data    : {valbusqueda : tipoBusqueda},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#comboReportes_6").html(data.combos);
			//$("#cont_tabla_reportes1").html(null);
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			    $('.selectButton').selectpicker('mobile');
			} else {
				$('.selectButton').selectpicker();
			}
			componentHandler.upgradeAllRegistered();
		});
	}else{
		$("#comboReportes_6").html(null);
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
	}
}

function getProvinciaPorDepartamento(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep = $("#"+idcomboDep).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getUbigeoByTipo',
			data    : {idubigeo : valorDep,
					   tipo     : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboProv, data.comboUbigeo, "Provincia");
			setCombo(idComboDist, null, "Distrito");
			//$("#cont_tabla_reportes1").html(null);
		});
	}else{
		setCombo(idComboProv, null, "Provincia");
		setCombo(idComboDist, null, "Distrito");
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
	}
}

function getDistritoPorProvincia(idcomboDep, idComboProv, idComboDist, tipo){
	var valorDep  = $("#"+idcomboDep).val();
	var valorProv = $("#"+idComboProv).val();
	if(valorDep != null && valorDep.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_detalle_alumno/getUbigeoByTipo',
			data    : {idubigeo  : valorDep,
				       idubigeo1 : valorProv,
					   tipo      : tipo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idComboDist, data.comboUbigeo, "Distrito");
			//$("#cont_tabla_reportes1").html(null);
		});
	}else{
		setCombo(idComboDist, null, "Distrito");
		$("#cont_alumnos").css("display","none");
		$("#cont_tabla_reportes1").html(null);
		$("#cont_filter_empty").css("display","block");
//		document.getElementById('btnGrafico').style.display = 'none';
		
	}
}

function goToViewAlumno(idAlumno){
	$.ajax({
		data :{idalumno : idAlumno},
		url : 'c_alumno/goToViewAlumno',
		async : false,
		type : 'POST'
	}).done(function(data) {
		window.location.href = 'c_detalle_alumno';
	});
}

function verFamiliares(codFamiliar){
	$.ajax({	
		type    : 'POST',
		'url'   : 'c_detalle_alumno/verFamiliaresCodFamiliar',
		data    : {codFamiliar : codFamiliar},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_familiares_by_CodFam").html(data.tablaFamiliares);
		$("#tablaFamiliaresByCodFam").bootstrapTable({});
		abrirCerrarModal("modalVistaFamiliares");
	});
}

function abrirModalHijos(idfamiliar){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_reportes/mostrarHijosFamiliar',
		data    : {idfamiliar    : idfamiliar},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_HijosFamiliar").html(data.tablaHijos);
		$("#tbHijosFamiliar").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		abrirCerrarModal("modalHijos");
	});
}

function selectEstadoRatificacion(){
	addLoadingButton('botonFR');
	$("#cont_alumnos").css("display","none");
	$("#cont_tabla_reportes1").html(null);
	$("#cont_filter_empty").css("display","block");
	disableEnableRadioButton('rbRatificacion', false);

	stopLoadingButton('botonFR');
}