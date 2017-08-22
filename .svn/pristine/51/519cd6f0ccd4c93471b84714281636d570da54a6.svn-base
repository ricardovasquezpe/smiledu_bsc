cons_btn_ver_mas_aulas = 0;
cons_btn_ver_mas_alumnos = 0;

$("#cont_inputtext_busqueda input, select, textarea").keypress(function(event) {
	if (event.which == 13) {
		event.preventDefault();
		busquedaGeneral()
	}
});

function busquedaGeneral(){
	Pace.restart();
	Pace.track(function() {
		var valorGeneral = $("#searchMagic").val();
		var pathArray = window.location.pathname.split( '/' );
		varpath = "busquedaGeneral";
		if(pathArray.length == 4){
			varpath = "c_main/busquedaGeneral";
		}
		if(valorGeneral.length != 0 && valorGeneral.length >= 3){
			$.ajax({	
				type    : 'POST',
				'url'   : varpath,
				data    : {valorGeneral : valorGeneral},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				var alumnos = data.tablaAlumnos;
				var aulas = data.tablaAulas;
				var string = data.tablaAlumnos + data.tablaAulas;
				
				if($.trim(alumnos) == ""){
					$("#alumnosTitulo").css("display", "none");
					$("#btnVerMasAlumnos").css("display", "none");
				}else{
					$("#alumnosTitulo").css("display", "block");
					$("#btnVerMasAlumnos").css("display", "block");
					$("#cont_imagen_magic").css("display", "none");
				}
				
				if($.trim(aulas) == ""){
					$("#aulasTitulo").css("display", "none");
					$("#btnVerMasAulas").css("display", "none");
				}else{
					$("#aulasTitulo").css("display", "block");
					$("#btnVerMasAulas").css("display", "block");
					$("#cont_imagen_magic").css("display", "none");
				}
				
				if($.trim(string) != ""){
					$("#cont_search_empty").css("display", "none");
					$("#cont_imagen_magic").css("display", "none");
				}else{
					$("#cont_imagen_magic").css("display", "none");
					$("#cont_search_empty").css("display", "block");
				}
				
				if(data.countAlumnos <= 5){
					$("#btnVerMasAlumnos").css("display", "none");
				}
				if(data.countAulas <= 5){
					$("#btnVerMasAulas").css("display", "none");
				}
				
				$("#cont_busqueda").html(data.tablaAlumnos);
				$("#cont_busqueda1").html(data.tablaAulas);
				cons_btn_ver_mas_aulas = 0;
				cons_btn_ver_mas_alumnos = 0;
				$("#btnVerMasAulas").html("VER M&aacute;S");
				$("#btnVerMasAlumnos").html("VER M&aacute;S");
				componentHandler.upgradeAllRegistered();
			});
		} else {
			$("#alumnosTitulo").css("display", "none");
			$("#aulasTitulo").css("display", "none");
			$("#cont_busqueda").html(null);
			$("#cont_busqueda1").html(null);
			$("#cont_imagen_magic").css("display", "none");
			$("#cont_search_empty").css("display", "block");
		}
	});
}

function abrirModalAlumnos(idaula){
	var pathArray = window.location.pathname.split( '/' );
	varpath = "c_main/abrirModalAlumnos";
	if(pathArray.length == 5){
		varpath = "abrirModalAlumnos";
	}
	$.ajax({
		type    : 'POST',
		'url'   : varpath,
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

function verMas(element, tipo){
	Pace.restart();
	Pace.track(function() {
		var valorGeneral = $("#searchMagic").val();
		var pathArray = window.location.pathname.split( '/' );
		varpath = "c_main/busquedaGeneralVerMas";
		if(pathArray.length == 5){
			varpath = "busquedaGeneralVerMas";
		}
		if(valorGeneral.length != 0 && valorGeneral.length >= 3){
			c = null;
			if(tipo == 1){
				c = cons_btn_ver_mas_aulas;
			}else{
				c = cons_btn_ver_mas_alumnos;
			}
			$.ajax({	
				type    : 'POST',
				'url'   : varpath,
				data    : {valorGeneral : valorGeneral,
						   tipo: tipo,
						   c : c},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				var alumnos = data.tablaAlumnos;
				var aulas = data.tablaAulas;
				
				if(tipo == 1){
					if(cons_btn_ver_mas_aulas == 0){
						$("#cont_busqueda1").html(aulas);
						cons_btn_ver_mas_aulas = 1;
						$(element).html('VER MENOS');
					}else{
						$("#cont_busqueda1").html(aulas);
						cons_btn_ver_mas_aulas = 0;
						$(element).html('VER M&aacute;S');
					}
				}else{
					if(cons_btn_ver_mas_alumnos == 0){
						$("#cont_busqueda").html(alumnos);
						cons_btn_ver_mas_alumnos = 1;
						$(element).html('VER MENOS');
					}else{
						$("#cont_busqueda").html(alumnos);
						cons_btn_ver_mas_alumnos = 0;
						$(element).html('VER M&aacute;S');
					}
				}
				componentHandler.upgradeAllRegistered();
			});
		}
	});
}

function goToViewAlumno(idAlumno){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data :{idalumno : idAlumno},
			url : 'c_main/goToViewAlumno',
			async : false,
			type : 'POST'
		}).done(function(data) {
			window.location.href = 'c_detalle_alumno';
		});
	});
}

function goToEditAlumno(idAlumno){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data :{idalumno : idAlumno},
			url : 'c_main/goToEditAlumno',
			async : false,
			type : 'POST'
		}).done(function(data) {
			window.location.href = 'c_detalle_alumno';
		});
	});
}

function goToViewAula(idaula){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_main/goToViewAula',
			data    : {idaula : idaula},
			'async' : false
		}).done(function(data){
			window.location.href = 'c_detalle_aula';
		});
	});
}

function goToEditAula(idaula){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_main/goToEditAula',
			data    : {idaula : idaula},
			'async' : false
		}).done(function(data){
			window.location.href = 'c_detalle_aula';
		});
	});
}

function abrirModalCompromisos(idestudiante){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/getDeudasByEstudiante",
			data: {idpostulante  : idestudiante},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#calendarCompromisos").html(data.table);
			    $('#tb_compromisoCalendarAlu').bootstrapTable({});
				componentHandler.upgradeAllRegistered();
				modal('modalCompromisosEstudiante');
			} else if(data.error == 1) {
			    $("#cont_compromiso").html(null);
			}
		});
	});	
}

function onScrollEvent(element){
	if(scroll == 1){
		if($(element).scrollTop() + $(element).innerHeight()>=$(element)[0].scrollHeight){
			$("#loading_cards").css("display","block");
			$(".pace").find(".pace-progress").css("background-color","red !important");
			Pace.restart();
			Pace.track(function() {
				  var nombreFiltro = $("#searchMagic").val();
		    	  $.ajax({
		  			type    : 'POST',
		  			'url'   : 'c_/onScrollGetAlumnos',
		  			data    : {count  : cons_scroll,
		  				       nombre : nombreFiltro,
		  				       letra  : cons_letra},
		  			'async' : true
		  		}).done(function(data){
		  			data = JSON.parse(data);
		  			$("#").append(data.tablaAlumnos);
		  			componentHandler.upgradeAllRegistered();
		  			cons_scroll = cons_scroll + 1;
		  			$("#loading_cards").css("display","none");
		  		});
			});
		}
	}
}

function abrirModalConfirmDesactivarAlumno(alumno, nombre, element, estado){
	if(estado == '1'){
		setChecked("retirado", false);
		$("#cont_check_retiro").css('display', 'block');
		$("#titleDesabilitarAlumno").text("Deseas desactivar a "+nombre+"?");
		$("#msjDesactAlumno").html("Recuerda: Al desactivar a esta persona no tendr&aacute; acceso al sistema, pero sus datos hist&oacute;ricos a&uacute;n se podr&aacute;n visualizar.");
	}else{
		setChecked("retirado", false);
		$("#cont_check_retiro").css('display', 'none');
		$("#titleDesabilitarAlumno").text("Deseas activar a "+nombre+"?");
		$("#msjDesactAlumno").html("Recuerda: Al activar a este estudiante se le volver&aacute; tomar en cuenta en esta aula.");
	}
	cons_card_estud = $(element).parent().parent().parent().parent();
	cons_id_alumno = alumno;
	
	modal("modalConfirmDesabilitarAlumno");
}

//function cambiarEstadoAlumno(){
//	Pace.restart();
//	addLoadingButton('botonAE');
//	Pace.track(function() {
//		var retiro = 0;
//		if(isChecked($('#retirado'))){
//			retiro = 1;
//		}
//		$.ajax({
//			url : "c_alumno/cambiarEstadoEstudiante",
//			data: {idalumno  : cons_id_alumno,
//				   retiro    : retiro},
//	        async: true,
//	        type: 'POST'
//		}).done(function(data){
//			data = JSON.parse(data);
//			if(data.error == 0) {
//				$(cons_card_estud).replaceWith(data.alumno);
//				componentHandler.upgradeAllRegistered();
//			}
//			modal("modalConfirmDesabilitarAlumno");
//			stopLoadingButton('botonAE');
//			mostrarNotificacion('success', data.msj , null);
//		});
//	});	
//}

function abrirModalTrasladar(idAlumno){
	cons_id_alumno = idAlumno;
	$("#comboSedeTraslado").css("display", "none");
	$("#comboAulaTraslado").css("display", "none");
	$("#contMotivoTraslado").css("display", "none");
	setearInput("motivoTraslado", null);
	setearCombo("cmbTipTraslado", null);
	abrirCerrarModal("modalSolicitudDeTraslado");
}

function enviarSolicitud(){	
	tTraslado = $("#cmbTipTraslado").val();
	sede = $("#selectSedeDestino").val();
	//aula = $("#selectAulaDestino").val();
	motivo = $("#motivoTraslado").val();
	if(tTraslado.length != 0 
	//&& aula.length != 0
				){
		addLoadingButton('botonST');
		$.ajax({
			data  :{tipoTraslado   : tTraslado,
				    sedeDestino    : sede,
				    //aulaDestino	   : aula,
				    motivoTraslado : motivo,
				    idAlumno : cons_id_alumno},
			url   : 'c_alumno/enviarSolicitud',
			async : true,
			type  : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0){
				stopLoadingButton('botonST');
				mostrarNotificacion('success', data.msj , null);
				abrirCerrarModal("modalSolicitudDeTraslado");				
			}else{
				stopLoadingButton('botonST');
				mostrarNotificacion('warning', data.msj , null);
			}
		});
	}
}

function changeTipoTraslado(){
	tTraslado = $("#cmbTipTraslado").val();
	if(tTraslado.length != 0){
		$.ajax({
			data  :{traslado : tTraslado,
				    idalumno : cons_id_alumno},
			url   : 'c_alumno/evaluarTipoTraslado',
			async : false,
			type  : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.tipo == 0){//INTRASEDE
				$("#comboSedeTraslado").css("display", "none");
				$("#contMotivoTraslado").css("display", "block");
			}else if(data.tipo == 1){//INTERSEDE
				setearCombo("cmbTipTraslado", null);
				$("#comboSedeTraslado").css("display", "none");
				$("#comboAulaTraslado").css("display", "none");
				$("#contMotivoTraslado").css("display", "none");
				$("#modalSubirPaquete").find(".mdl-card__title-text").html("Trasnlado Intersedes");
				modal("modalSubirPaquete");
			}
		});
	}else{
		$("#comboSedeTraslado").css("display", "none");
		$("#comboAulaTraslado").css("display", "none");
		$("#contMotivoTraslado").css("display", "none");
	}
}

function abrirModalConfirmDeclaracionJurada(alumno, nombre, element){
	$("#titleDeclaracionJurada").text("Deseas confirmar que recibiste la declaracion jurada de "+nombre+"?");
	$("#msjDecJurada").html("Recuerda: Al confirmar a este estudiante, su apoderado podr&aacute; continuar con el proceso de ratificaci&oacute;n.");
	cons_card_estud = $(element).parent().parent().parent().parent();
	cons_id_alumno = alumno;
	if(cons_id_alumno != null){
		$.ajax({
			url : "c_main/abrirModalConfirmDeclaracionJurada",
			data: {idalumno  : cons_id_alumno},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo("sedeIngreso", data.comboSedes, "Sede");
				setearCombo("sedeIngreso", data.sedeActual);
				document.getElementById('msjConfirmaRatificar').innerHTML = "Su proceso de ratificaci&oacute;n es correspondiente a: "+data.gradoNivel+".";
				modal("modalDeclaracionJurada");
			} else {
				mostrarNotificacion('success', data.msj , null);			
			}
		});
	}
}

function confirmarDeclaracion(){
	var valorSede = $("#sedeIngreso").val();

	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/confirmarDeclaracion",
			data: {idalumno  : cons_id_alumno,
				   idsede    : valorSede},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$(cons_card_estud).replaceWith(data.alumno);
				componentHandler.upgradeAllRegistered();
				modal("modalDeclaracionJurada");
			}
			mostrarNotificacion('success', data.msj , null);
		});
	});
}
var idAulaEliminar  = null;
var cont_aula_selec = null;
function abrirModalConfirmarEliminarAula(idaula, cont_aula){
	idAulaEliminar  = idaula;
	cont_aula_selec = cont_aula;
	$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/abrirModalConfirmarEliminarAula',
		data    : {idaula : idaula},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			document.getElementById('msjConfirmaEliminar').innerHTML = '&#191;Est&aacute; seguro que desea eliminar el aula "'+data.desc_aula+'" ?';
			abrirCerrarModal("modalConfirmarEliminarAula");
		}else if (data.error == 1){
			mostrarNotificacion('success', data.msj, null);
		}
	});
}

function eliminarAula(){
	var valorYear       = $("#selectYearFiltroAulas").val();
	var valorSede       = $("#selectSedeFiltroAulas").val();
    var valorGradoNivel = $("#selectGradoNivelFiltroAulas").val();
    var textoBusqueda   = $("#searchMagic").val();
	$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/eliminarAula',
		data    : {idaula        : idAulaEliminar,
			       year          : valorYear,
		           idsede        : valorSede,
		           idgradonivel  : valorGradoNivel,
		           textobusqueda : textoBusqueda,
		           ciclo         : null},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#"+cont_aula_selec).remove();
			componentHandler.upgradeAllRegistered();
			abrirCerrarModal("modalConfirmarEliminarAula");
			cons_scroll = 1;
		}
		msj('success', data.msj, null);
	});
}


/*
function entrar(){
	var usuario = $("#usuarioLogin").val();
	var clave   = $("#claveLogin").val();
	
	$.ajax({
		data : {
			user : usuario,
			pass : clave
		},
		url : 'c_main/login',
		async : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if(data.validacion == 1){
			alert('entro');
		}else{
			alert('no entro');
		}
		console.log(data);
	});
}


function openPermisosList(id){
	$("#"+id).find('.mdl-card__actions.closed').on('click',function () {
		$("#"+id).find('.mdl-card__title').fadeOut(0);
		  $("#"+id).find('.mdl-card__actions').css("height", "155px");
		  $("#"+id).find('.mdl-card__actions .mdl-button').css("height" , "155px");
		  $("#"+id).find('.mdl-button').css("height" , "202px");
		  
		  $('#'+id).find('.mdl-button li:nth-child(1) a i').css('visibility', 'hidden');
		  $('#'+id).find('.closed').addClass('open').removeClass('closed');
		  return false;
	});
	$('body').click(function () {
		$("#"+id).find('.mdl-card__actions').css("height", "35px");
			$("#"+id).find('.mdl-card__actions .mdl-button').css("height", "35px");
			$("#"+id).find('.mdl-card__title').fadeIn(500);
			$('#'+id).find('.mdl-button li:nth-child(1) a i').css('visibility', 'visible');
			$('#'+id).find('.open').addClass('closed').removeClass('open');
	});
};




*/