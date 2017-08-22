function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	$('#tbNivelesGradoConfig').bootstrapTable({});
	componentHandler.upgradeAllRegistered();
	initButtonLoad('btnAddCurso', 'btnDeleteCurso');
	initLimitInputs('observacionCurso');
}

var cons_grado_select = null;
function verCursos(event, idGrado, nombre){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/cursosGradoNivel',
	    	data   : {grado : idGrado}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       $('#tbNivelesGradoConfig').find('tr').removeAttr('style');
	       event.parent().parent().css('background-color', '#EEEEEE');
	       $("#contCursosNivelGrados").html(data.tbCursos);
	       $('#tbCursosNivelesGradoConfig').bootstrapTable({});
	       $("#cont_select_empty_cursos").css("display", "none");
	       $("#btnAddCursos").css("display", "inline-block");
	       $("#titleCursos").html("Cursos - "+nombre);
	       cons_grado_select = idGrado;
	       componentHandler.upgradeAllRegistered();
	       $(document).ready(function(){
       	       $('[data-toggle="tooltip"]').tooltip();
           });
	    });
	});
}

function abrirModalAddCurso(){
	setearInput("descripcionCurso", null);
	modal("modalAddCurso");
}

function addCurso(){
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnAddCurso');
		var descripcion = $("#descripcionCurso").val();
		if(descripcion.length != 0){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_config_eval/addCursoNivelGrado',
		    	data   : {grado       : cons_grado_select,
		    		      descripcion : descripcion}
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   $("#contTbNivelGrados").html(data.tbGradosNiveles);
			       $('#tbNivelesGradoConfig').bootstrapTable({});

			       $("#contCursosNivelGrados").html(data.tbCursos);
			       $('#tbCursosNivelesGradoConfig').bootstrapTable({});
			       $('#tbCursosNivelesGradoConfig tbody tr:last-CHILD').addClass('mdl-parpadea');
			       componentHandler.upgradeAllRegistered();
			       $(document).ready(function(){
		       	       $('[data-toggle="tooltip"]').tooltip();
		           });
			       modal("modalAddCurso");
		       }
		       stopLoadingButton('btnAddCurso');
		       msj("success", data.msj, null);
		    });
		}else{
			stopLoadingButton('btnAddCurso');
			msj("success", 'Ingrese una decripci&oacute;n', null);
		}
	});
}

var cons_curso_selec = null;
function abrirModalDeleteCurso(idCurso){
	cons_curso_selec = idCurso;
	modal("modalDeleteCurso");
}


function deleteCurso(){
    $(document).ready(function(){
	       $('[data-toggle="tooltip"]').tooltip();
    });
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnDeleteCurso');
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/deleteCursoNivelGrado',
	    	data   : {grado : cons_grado_select,
	    		      curso : cons_curso_selec}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#contTbNivelGrados").html(data.tbGradosNiveles);
		       $('#tbNivelesGradoConfig').bootstrapTable({});
		       $("#contCursosNivelGrados").html(data.tbCursos);
		       $('#tbCursosNivelesGradoConfig').bootstrapTable({});
		       componentHandler.upgradeAllRegistered();
		       $(document).ready(function(){
	       	       $('[data-toggle="tooltip"]').tooltip();
	           });
		       modal("modalDeleteCurso");
	       }
	       stopLoadingButton('btnDeleteCurso');
	       msj("success", data.msj, null);
	    });		       $(document).ready(function(){
    	       $('[data-toggle="tooltip"]').tooltip();
        });
	});
}

function changeEstado(idCurso){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/changeEstadoCurso',
	    	data   : {grado : cons_grado_select,
	    		      curso : idCurso}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#contTbNivelGrados").html(data.tbGradosNiveles);
		       $('#tbNivelesGradoConfig').bootstrapTable({});
	       }
	       msj("success", data.msj, null);
	    });
	});
}

function openSelectFile(idCurso){
	cons_curso_selec = idCurso;
	$("#subirArchivo").trigger("click");
}

$("#subirArchivo").change(function(e){
	var formData = new FormData();
	var file = e.target.files[0];
	formData.append("documento", file);
	formData.append("grado", cons_grado_select);
	formData.append("curso", cons_curso_selec);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			cache: false,
	        contentType: false,
	        processData: false,
	        type: 'POST',
	    	'url'  : 'C_config_eval/uploadDocCurso',
	    	data   : formData
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#contTbNivelGrados").html(data.tbGradosNiveles);
		       $('#tbNivelesGradoConfig').bootstrapTable({});
	       }
	       msj("success", data.msj, null);
	    });
	});
});

function addTemasCurso(){
	Pace.restart();
	Pace.track(function() {
		desc = $("#descripcionTemaCurso").val();
		if(desc.length != 0){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_config_eval/crearTemasCurso',
		    	data   : {grado : cons_grado_select,
		    		      curso : cons_curso_selec,
		    		      desc  : desc},
		   	   'async' : true
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   setearInput("descripcionTemaCurso", null);
		    	   $("#cont_tb_temas_curso").html(data.tabla);
				   $('#tbTemasCurso').bootstrapTable({});
		       }
		       msj("success", data.msj, null);
		    });
		}else{
			msj("success", "Ingrese una descripci&oacute;n", null);
		}
	});
}

function gaurdarObservacionCurso(){
	Pace.restart();
	Pace.track(function() {
		desc = $("#observacionCurso").val();
		if(desc.length != 0){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_config_eval/guardarTituloObservacion',
		    	data   : {grado : cons_grado_select,
		    		      curso : cons_curso_selec,
		    		      desc  : desc},
		   	   'async' : true
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   $("#contCursosNivelGrados").html(data.tbCursos);
			       $('#tbCursosNivelesGradoConfig').bootstrapTable({});
			       componentHandler.upgradeAllRegistered();
			       $(document).ready(function(){
		       	       $('[data-toggle="tooltip"]').tooltip();
		           });
			       modal("modalChangeObservacionCurso");
		       }
		       msj("success", data.msj, null);
		    });
		}else{
			msj("success", "Ingrese una descripci&oacute;n", null);
		}
	});
}

function addOpcionesCurso(){
	Pace.restart();
	Pace.track(function() {
		desc = $("#descripcionOpcionCurso").val();
		if(desc.length != 0){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_config_eval/crearOpcionesCurso',
		    	data   : {grado : cons_grado_select,
		    		      curso : cons_curso_selec,
		    		      desc  : desc},
		       'async' : true
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   setearInput("descripcionOpcionCurso", null);
		    	   $("#cont_tb_opciones_curso").html(data.tabla);
				   $('#tbOpcionesCurso').bootstrapTable({});
		       }
		       msj("success", data.msj, null);
		    });
		}else{
			msj("success", "Ingrese una descripci&oacute;n", null);
		}
	});
}

function abrirModalConfiguracionGeneral(idCurso, curso, observacion){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/configuracionGeneralCurso',
	    	data   : {grado : cons_grado_select,
	    		      curso : idCurso}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       cons_curso_selec = idCurso;
	       setearInput("descripcionOpcionCurso", null);
	       setearInput("descripcionTemaCurso", null);
		   $("#titleConfiguracionGeneral").html(curso+" (Config. de evaluaci&oacute;n)");
		   setearInput("observacionCurso", observacion);
		   $("#cont_tb_opciones_curso").html(data.tablaOpciones);
		   $('#tbOpcionesCurso').bootstrapTable({});
		   $("#cont_tb_temas_curso").html(data.tablaIndicadores);
		   $('#tbTemasCurso').bootstrapTable({});
		   $("#cont_tb_opc_indicador").html(data.tablaIndicadoresOpcion);
		   $('#tbOpcionesIndicador').bootstrapTable({});
		   modal("modalConfiguracionGeneralCurso");
	    });
	});
}

var idGlobal = null
function modalDeleteIndicadoresCurso(id, indic) {
	if(indic == 1) {
		$('.pregunta').text('\u00BFEst\u00E1 seguro de eliminar este Indicador?');
		$('#elim').attr('onclick', 'deleteIndicadoresCurso()');
	}
	if(indic == 2) {
		$('.pregunta').text('\u00BFEst\u00E1 seguro de eliminar?');
		$('#elim').attr('onclick', 'deleteNivelesCurso()');
	}
	if(indic == 3) {
		$('.pregunta').text('\u00BFEst\u00E1 seguro de eliminar?');
		$('#elim').attr('onclick', 'deleteIndicadoresOpcion()');
	}
	idGlobal = id;
	modal('modalDelete');
}

function deleteIndicadoresCurso() {
	if(idGlobal == null || idGlobal == '') {
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/deleteIndicadorCurso',
	    	data   : {grado : cons_grado_select,
  		              curso : cons_curso_selec,
  		              id    : idGlobal }
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#cont_tb_temas_curso").html(data.tabla);
			   $('#tbTemasCurso').bootstrapTable({});
			   modal('modalDelete');
	       }
	       msj("success", data.msj, null);
	    });
	});
}

function deleteNivelesCurso(id) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/deleteNivelesCurso',
	    	data   : {grado : cons_grado_select,
  		              curso : cons_curso_selec,
  		              id    : idGlobal}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#cont_tb_opciones_curso").html(data.tabla);
			   $('#tbOpcionesCurso').bootstrapTable({});
			   modal('modalDelete');
	       }
	       msj("success", data.msj, null);
	    });
	});
}

function addOpcionesIndicadores(){
	Pace.restart();
	Pace.track(function() {
		desc = $("#descripcionOpcIndicadores").val();
		if(desc.length != 0){
			$.ajax({
		    	type   : 'POST',
		    	'url'  : 'C_config_eval/crearOpcionesIndicador',
		    	data   : {grado : cons_grado_select,
		    		      curso : cons_curso_selec,
		    		      desc  : desc},
		       'async' : true
		    })
		    .done(function(data) {
		       data = JSON.parse(data);
		       if(data.error == 0){
		    	   setearInput("descripcionOpcIndicadores", null);
		    	   $("#cont_tb_opc_indicador").html(data.tabla);
				   $('#tbOpcionesIndicador').bootstrapTable({});
		       }
		       msj("success", data.msj, null);
		    });
		}else{
			msj("success", "Ingrese una descripci&oacute;n", null);
		}
	});
}

function deleteIndicadoresOpcion(id){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	    	type   : 'POST',
	    	'url'  : 'C_config_eval/deleteOpcionIndicador',
	    	data   : {grado : cons_grado_select,
  		              curso : cons_curso_selec,
  		              id    : idGlobal}
	    })
	    .done(function(data) {
	       data = JSON.parse(data);
	       if(data.error == 0){
	    	   $("#cont_tb_opc_indicador").html(data.tabla);
			   $('#tbOpcionesIndicador').bootstrapTable({});
			   modal('modalDelete');
	       }
	       msj("success", data.msj, null);
	    });
	});
}