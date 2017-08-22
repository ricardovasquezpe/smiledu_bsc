var arrayIndicadores = [];
var x;
var estado_llamada = null;
var interval;
var elapsed_seconds = 0;

function init(){
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initLimitInputs('observacion');
	initButtonLoad('btnGuardarIndicadores','btnGuardarImagenes');
	if(modalInit == 'SI') {
		estado_llamada = CONFIG.get('EST_LLAMAR');
		x = 1;
		$('#svgColgar').find(">:first-child").attr('fill', '#ff0000');
		$('#llamar_msj').html('Llamando al PPFF...<br>(Presionar si el PPFF llega a la entrevista)');
		$('#colgar_msj').html('Presionar si el PPFF no atiende el llamado');
		interval = setInterval(function(){ marcar($('#svgLlamar')); }, 1000);
		modal('entrevista');
	}
}

function guardarLlamada(tipo_accion) {
	var rpta = false;
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/guardar_llamada',
			data    : { tipo_accion : tipo_accion },
			async   : false
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				msj('success', data.msj);
				rpta = true;
			} else {
				if(tipo_accion == CONFIG.get('EST_LLAMAR')) {
					$('#contDetalle').html(data.detalle);
					modal('datos_entrevista');
				}
				if(data.msj) {
					msj('error', data.msj);	
				}
			}
		});
	});
	return rpta;
}

function llamar(btn) {
	//$('#circleG').removeAttr('style');
	x = 1;
	if(estado_llamada == null) {
		var ok = guardarLlamada(CONFIG.get('EST_LLAMAR'));
		if(ok) {
			estado_llamada = CONFIG.get('EST_LLAMAR');
			$('#svgColgar').find(">:first-child").attr('fill', '#ff0000');
			$('#llamar_msj').html('Llamando al PPFF...<br>(Presionar si el PPFF llega a la entrevista)');
			$('#colgar_msj').html('Presionar si el PPFF no atiende el llamado');
			interval = setInterval(function(){ marcar(btn); }, 1000);
		}
	} else {
		var ok = guardarLlamada(CONFIG.get('EST_ENTREV'));
		if(ok) {
			estado_llamada = CONFIG.get('EST_ENTREV');
			clearInterval(interval);
			$('#svgLlamar').css('width', '30em');
			$('#svgLlamar').css('heigth', '30em');
			$('#svgLlamar').find(">:first-child").attr('fill', '#4CAF50');
			$('#svgColgar').find(">:first-child").attr('fill', '#9E9E9E');
			$('#llamar_msj').html('El PPFF llegó a la entrevista');
			$('#colgar_msj').html('');
			$("#menu").unbind("click");
			$("#menu").prop('onclick', null);
			$('#menu').find(':button').attr('data-mfb-label', 'Cancelar entrevista');
			$("#menu").find("button").css("background-color", "red!important");
			$("#menu").prop("style", "background-color: red!important");
			$('#menu').click(function() {
			    modal('cancel_entrevista');
		    });
			$('#observaciones_entrevista').prop('disabled', false);
			$('#observaciones_entrevista').parent().removeClass('is-disabled');
			$('#select_taller_verano_entrevista').prop('disabled', false);
			$('#select_taller_verano_entrevista').parent().removeClass('is-disabled');
			$('#select_diagnostico_entrevista').prop('disabled', false);
			$('#select_diagnostico_entrevista').selectpicker('refresh');
			$('#btnSaveEditEntrevista').prop('disabled', false);
			$('#btnSaveEditEntrevista').click(function() {
				guardarEntrevista();
		    });
//			$('#circleG').css('display','none');
			modal('entrevista');
		}
	}
}

function colgar(btn) {
	var ok = guardarLlamada(CONFIG.get('EST_PERDID'));
	if(ok) {
		estado_llamada = null;
		$('#svgLlamar').css('width', '30em');
		$('#svgLlamar').css('heigth', '30em');
		$('#svgLlamar').find(">:first-child").attr('fill', '#4CAF50');
		$('#svgColgar').find(">:first-child").attr('fill', '#9E9E9E');
		$('#llamar_msj').html('Volver a llamar al PPFF');
		$('#colgar_msj').html('El PPFF perdió su turno');
		clearInterval(interval);	
	}
}

function marcar(btn) {
    if (x === 1) {
        color = "#9E9E9E";
        btn.css('width', '25em');
        btn.css('heigth', '25em');
        x = 2;
    } else {
        color = "#4CAF50";
        btn.css('width', '30em');
        btn.css('heigth', '30em');
        x = 1;
    }
    elapsed_seconds++;
    $('#timer').text(get_elapsed_time_string(elapsed_seconds));
    btn.find(">:first-child").attr('fill', color);
}

function cancelarEntrevista() {
	var detalleCancel = $('#detalle_entrev_cancel').val();
	if(!detalleCancel) {
		msj('error', 'Ingrese la razón');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/cancelar_entrevista',
			data    : { detalleCancel : detalleCancel }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#observaciones_entrevista').prop('disabled', true);
				$('#select_taller_verano_entrevista').prop('disabled', true);
				$('#select_diagnostico_entrevista').prop('disabled', true);
				$('#btnSaveEditEntrevista').prop('disabled', true);
				$("#menu").unbind("click");
				$("#menu").prop('onclick', null);
				$('#menu').find(':button').attr('data-mfb-label', 'Entrevistar');
				$('#menu').click(function() {
				    modal('entrevista');
			    });
				$("#menu").css("background-color", "");
				msj('success', data.msj);
				modal('cancel_entrevista');
			} else {
				msj('error', data.msj);
			}
		});
	});
}

function get_elapsed_time_string(total_seconds) {
  function pretty_time_string(num) {
    return ( num < 10 ? "0" : "" ) + num;
  }

  var hours = Math.floor(total_seconds / 3600);
  total_seconds = total_seconds % 3600;

  var minutes = Math.floor(total_seconds / 60);
  total_seconds = total_seconds % 60;

  var seconds = Math.floor(total_seconds);

  hours = pretty_time_string(hours);
  minutes = pretty_time_string(minutes);
  seconds = pretty_time_string(seconds);

  var currentTimeString = hours + ":" + minutes + ":" + seconds;

  return currentTimeString;
}

function saveCampo(campo, enc, id){
	var valor = $("#"+id).val();
	if(id.length != 0){
		$.ajax({
			type	: 'POST',
			'url'	: 'c_detalle_evaluacion/saveCampo',
			data	: {campo	:	campo,
					   enc		:	enc,
					   valor	:	valor}
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				msj('success', data.msj, null);
			} else if (data.error == 1){
				setearInput(id, $("#"+id).attr("val-previo"));
				msj('success', data.msj, null);
			}
		});
	}
}

$("#documentosPublicacion").change(function(e){
	$('.docsPrev').remove();
	var files = e.target.files ,
	 filesLength = files.length ;
	 for (var i = 0; i < filesLength ; i++) {
		 var f = files[i]
		 var fileReader = new FileReader();
		 fileReader.onload = (function(e) {
			 var file = e.target;
			 var content = '<div class="col-sm-4 docsPrev" style="text-align:center"><img  width=100 height=120 src="'+e.target.result+'" title="'+file.name+'" class="imageThumb"></div>';
			 $(content).insertAfter("#imagenesPreview");
		 });
		 fileReader.readAsDataURL(f);
	 }
});

cons_id_diagnostico = null;
cons_cont_i = null;
function abrirModalAgregarDocumento(i, idDiagnostico){
	cons_id_diagnostico = idDiagnostico;
	cons_cont_i = i;
	setearInput("documentosPublicacion",null);
	$('.docsPrev').remove();
	abrirCerrarModal('modalAgregarArchivos');
}

function agregarArchivos(){
	addLoadingButton('btnGuardarImagenes');
	var formData = new FormData(); 
	var inputFileDoc = document.getElementById("documentosPublicacion");
    var files = inputFileDoc.files;
	for (var i = 0, len = files.length; i < len; i++) {
        formData.append("file" + files.length+i, files[i]);
    }
	formData.append("diagnostico", cons_id_diagnostico);
	$.ajax({
        data: formData,
        url: "C_detalle_evaluacion/agregarArchivos",
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
  	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0){
			$("#cont_archivos_"+cons_cont_i).html(data.docs);
			abrirCerrarModal('modalAgregarArchivos');
			stopLoadingButton('btnGuardarImagenes');
		}
		msj('success', data.msj, null);
		stopLoadingButton('btnGuardarImagenes');
	});
}

function getSedesByNivel(nivel,sede, opc){
	var valorNivel = $('#'+nivel+' option:selected').val();
	if(valorNivel != null && opc == 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/getSedesByNivel',
			data    : {valorNivel : valorNivel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(sede, data.comboSedes,"Sede de inter&eacute;s");
			$('.selectButton').selectpicker('refresh');
		});
	}else{
		setearCombo(sede, $("#"+sede).attr("val-previo"));
		$('.selectButton').selectpicker('refresh');
	}
}

function onChangeCampo(campo, enc, id){
	var valor = $("#"+id).val();
	if(id.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_detalle_evaluacion/onChangeCampo',
			data    : {campo : campo,
			           enc   : enc,
			           valor : valor},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#"+id).attr("val-previo", valor);
				if(campo == 'grado_nivel'){
					getSedesByNivel('selectGradoNivel','selectSedeInteres',0);
				}
				msj('success', data.msj, null);
			} else if (data.error == 1){
				setearCombo(id, $("#"+id).attr("val-previo"));
				/*if(campo == 'grado_nivel'){console.log(1);
					setearCombo('selectSedeInteres', $("#selectSedeInteres").attr("val-previo"));
				}*/
				
				msj('success', data.msj, null);
			}
		});
	}
}

function guardarDiagnostico(i, tipo, element){
	Pace.restart();
	Pace.track(function() {
		diagnostico  = $("#select_diagnostico_"+i).val();
		tallerVerano = $("#select_taller_verano_"+i).val();
		observacion  = $("#observaciones_"+i).val();

		var result = arrayIndicadores.filter(function( obj ) {
		  return obj.curso == i;
		});
		var json = {};
		var indicadores = [];
		json.indicador = indicadores;
		if(result && result.length){
			if(result && result.length){
				for(j = 0; j < result[0].length; j++){
					desc  = $(this).attr("attr-desc");
					var indicador    = {"valor" : result[0][j].nombre,
					    		        "desc"  : result[0][j].desc};			
				    json.indicador.push(indicador);
				}
			}
		}else{
			msj("success", "Ingresa todos los indicadores", null);
			return;
		}

		var jsonStringIndicador = JSON.stringify(json);
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/guardarDiagnostico',
			data    : {diagnostico : diagnostico, 
				       verano      : tallerVerano,
				       observacion : observacion,
				       tipo        : tipo,
				       indicadores : jsonStringIndicador},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#btnGuardar"+i).text("editar");
				$("#nombre_eva"+i).text(data.nombre_eva);
				$(element).attr("onclick", 'editarDiagnostico('+i+', \''+data.diag+'\', this)');
				$("#foto_persona_"+i).replaceWith(data.foto);
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	            });
				$("#btnIndicadores"+i).prop("disabled", false);
				//$("#btnSubirArchivo"+i).prop("disabled", false);
				$("#btnIndicadores"+i).attr("onclick", 'abrirModalIndicadores('+i+', \''+data.diag+'\', \''+data.curso+'\', \''+data.nomcurso+'\', "enabled")');
				//$("#btnSubirArchivo"+i).attr("onclick", 'abrirModalAgregarDocumento('+i+', \''+data.diag+'\')');
				$("#icon_estado_"+i).removeClass("mdi-priority_high").addClass("mdi-check");
				if(data.cardEntrevista) {
					$('#cardEvaEntrev').html(data.cardEntrevista);
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
					    $('#select_diagnostico_entrevista').selectpicker('mobile');
					} else {
						$('#select_diagnostico_entrevista').selectpicker();
					}
				}
			}
			msj("success", data.msj, null);
		});
	});
}

function editarDiagnostico(i, diag){
	Pace.restart();
	Pace.track(function() {
		diagnostico  = $("#select_diagnostico_"+i).val();
		tallerVerano = $("#select_taller_verano_"+i).val();
		observacion  = $("#observaciones_"+i).val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/editarDiagnostico',
			data    : {diagnostico : diagnostico, 
				       verano      : tallerVerano,
				       observacion : observacion,
				       diag        : diag},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#foto_persona_"+i).replaceWith(data.foto);
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	            });
			}
			msj("success", data.msj, null);
		});
	});
}

var arrayIndicadoresLlenados

function abrirModalIndicadores(i, diag, idCurso, curso, dis){
	if(diag.length == 0){
		$("#btnGuardarIndicadores").css("display", "none");
		$("#btnIndicadores").css('color','#757575');
	}else{
		$("#btnGuardarIndicadores").css("display", "inline-block");
		$("#btnIndicadores").css('color','#2196F3');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/indicadoresDiagnostico',
			data    : {diag  : diag,
				       curso : idCurso,
				       i : i},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_tb_indicadores").html(data.tabla);
			$('#tbIndicadoresResultado').bootstrapTable({});
			$("#titleIndicadoresCurso").html("Indicadores - "+curso);
			cons_id_diagnostico = diag;
			cons_cont_i         = i;
			if(dis == 'disabled'){
				$("#btnGuardarIndicadores").prop("disabled", true);
				$("#indicadores"+i).prop("disabled", true);
			}else{
				$("#btnGuardarIndicadores").prop("disabled", false);
				$("#indicadores"+i).prop("disabled", false);
			}
			if(diag.length == 0){
				var result = arrayIndicadores.filter(function( obj ) {
      			  return obj.curso == cons_cont_i;
      			});
				if(result && result.length){
					for(j = 0; j < result[0].length; j++){
						$("#resIndicador"+(j+1)).val(result[0][j].nombre);
					}
				}
			}
			modal("modalIndicadoresCurso");
		});
	});
}

function cerrarModalIndicadores(){
	removeObjectFromArray();
	var obj = [];
	obj['curso'] = cons_cont_i;
	var i = 1;
	$(".indicador"+cons_cont_i).each(function() {
		var nombre = 'indicador'+i;
		obj1 = {nombre : $(this).val(),
				desc   : $(this).attr("attr-desc")};
		obj.push(obj1);
		i++;
	});
	arrayIndicadores.push(obj);
}

function removeObjectFromArray(){
	for(var i = 0; i < arrayIndicadores.length; i++){
		if(arrayIndicadores[i].curso == cons_cont_i){
			arrayIndicadores.splice(i, 1);
			break;
		}
	}
}

function guardarIndicadoresCurso(){
	addLoadingButton('btnGuardarIndicadores');
	var json = {};
	var indicadores = [];
	json.indicador = indicadores;
	$(".indicador"+cons_cont_i).each(function() {
	    valor = $(this).val();
	    desc  = $(this).attr("attr-desc");
	    var indicador    = {"valor" : valor,
	    		            "desc"  : desc};			
		json.indicador.push(indicador);
	});
	var jsonStringIndicador = JSON.stringify(json);
	
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/guardarIndicadoresCurso',
			data    : {indicadores : jsonStringIndicador,
				       diagnostico : cons_id_diagnostico},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				modal("modalIndicadoresCurso");
				stopLoadingButton('btnGuardarIndicadores');
				if($('resIndicador'+cons_cont_i).length == 0){
					$("#btnIndicadores"+cons_cont_i).css('color','#757575');
				}
				if($('resIndicador'+cons_cont_i).length > 0){
					$("#btnIndicadores"+cons_cont_i).css('color','#2196F3');
				}	
			}
			msj("success", data.msj, null);
			stopLoadingButton('btnGuardarIndicadores');
		});
	});
}

function guardarEntrevista(){
	addLoadingButton('btnSaveEditEntrevista');
	Pace.restart();
	Pace.track(function() {
		diagnostico  = $("#select_diagnostico_entrevista").val();
		tallerVerano = $("#select_taller_verano_entrevista").val();
		observacion  = $("#observaciones_entrevista").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/guardarEntrevista',
			data    : { diagnostico : diagnostico, 
				        verano      : tallerVerano,
				        observacion : observacion }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#btnSaveEditEntrevista").attr("onclick", 'editarEntrevista()');
				$('#btnSaveEditEntrevista').text("Editar");
				$("#foto_persona_entrevista").replaceWith(data.foto);
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	            });
				//$("#btnSubirArchivoEntrevista").prop("disabled", false);
				$("#icon_estado_entrevista").removeClass("mdi-priority_high").addClass("mdi-check");
				stopLoadingButton('btnSaveEditEntrevista');
				$("#menu").remove();
			}
			msj('success', data.msj);
			stopLoadingButton('btnSaveEditEntrevista');
		});
	});
}

function editarEntrevista(){
	Pace.restart();
	Pace.track(function() {
		diagnostico  = $("#select_diagnostico_entrevista").val();
		tallerVerano = $("#select_taller_verano_entrevista").val();
		observacion  = $("#observaciones_entrevista").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_detalle_evaluacion/editarEntrevista',
			data    : {diagnostico : diagnostico, 
				       verano      : tallerVerano,
				       observacion : observacion},
			'async' : false
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0){
				$("#foto_persona_entrevista").replaceWith(data.foto);
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	            });
			}
			msj("success", data.msj, null);
		});
	});
}

$("#documentosPublicacionEntrevista").change(function(e){
	$('.docsPrevEntrevista').remove();
	var files = e.target.files ,
	 filesLength = files.length ;
	 for (var i = 0; i < filesLength ; i++) {
		 var f = files[i]
		 var fileReader = new FileReader();
		 fileReader.onload = (function(e) {
			 var file = e.target;
			 var content = '<div class="col-sm-4 docsPrevEntrevista" style="text-align:center"><img  width=100 height=120 src="'+e.target.result+'" title="'+file.name+'" class="imageThumb"></div>';
			 $(content).insertAfter("#imagenesPreviewEntrevista");
		 });
		 fileReader.readAsDataURL(f);
	 }
});

function abrirModalAgregarDocumentoEntrevista(){
	setearInput("documentosPublicacionEntrevista",null);
	$('.docsPrevEntrevista').remove();
	abrirCerrarModal('modalAgregarArchivosEntrevista');
}

function agregarArchivosEntrevista(){
	var formData = new FormData(); 
	var inputFileDoc = document.getElementById("documentosPublicacionEntrevista");
    var files = inputFileDoc.files;
	for (var i = 0, len = files.length; i < len; i++) {
        formData.append("file" + files.length+i, files[i]);
    }
	$.ajax({
        data: formData,
        url: "C_detalle_evaluacion/agregarArchivosEntrevista",
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
  	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0){
			$("#cont_archivos_entrevista").html(data.docs);
			abrirCerrarModal('modalAgregarArchivosEntrevista');
		}
		msj('success', data.msj, null);
	});
}