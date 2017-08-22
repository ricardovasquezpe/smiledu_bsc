var toog2 = 0;
var error = 0;

function initEvaluar() {
	$('#tbEvaluaciones').bootstrapTable({ });
	
	Dropzone.autoDiscover = false;

	$("#dropzone").dropzone({
		url: "c_evaluar/subirArchivos",
		addRemoveLinks: true,
		autoProcessQueue: false,
		parallelUploads: 20,
		maxFilesize: 50,//Mbs
		dictResponseError: "Ha ocurrido un error en nuestro servicio",
		dictDefaultMessage: "Arrastra tus evidencias aqu&iacute; o Haz click en esta zona",
		acceptedFiles: CONFIG.get('TIPOS'),
		complete: function(file){
			if(file.status == "success"){// SECOND
		        //SUBIO LA IMAGEN
				error = 0;
			}
		},
		removedfile: function(file, serverFileName){
			var name = file.name;
			var element;
			(element = file.previewElement) != null ? element.parentNode.removeChild(file.previewElement) : false;
			toog2--;
		},
		init: function() {
			this.on("error", function(file, message) {
			    message = JSON.parse(message);
				msj('error', message.msj);
				error = 1;
	            this.removeFile(file);
			});
		    var submitButton = document.querySelector("#btnAddNewEvidencias")
		        myDropzone = this; // closure
		    //evento submit subimos todo
		    submitButton.addEventListener("click", function() {
		    	Pace.restart();
		    	Pace.track(function() {
		    		myDropzone.processQueue();
		    	});    	
		    	// Tell Dropzone to process all queued files.
		       });
		    // You might want to show the submit button only when 
		    // files are dropped here:
		    this.on("addedfile", function() {
		    	toog2++;
		      // Show submit button here and/or inform user to click it.
		    });
		    this.on('complete', function () {//THIRD
	            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
	            	if(error == 0) {
	            		modal('modalCargarEvidencias');
	            	}
	            }
	        });
		    this.on("queuecomplete", function (file) {//LAST
			    this.removeAllFiles();
		    	if(error == 0) {
		    		msj('success','Se subieron tus evidencias :D ');
		    	}
		    });
	        this.on("success", function(file, responseText) {// FIRST
	        	 //concatEvi += responseText+'_';
	        });
		  }
	});
}

var divFactorGlobal = null;
function verValores(idCriterio, div, divCrit, idIndicador, idDiv) {
	$('#hidIdiv').val(idDiv);
	$('#hidIdivCrit').val(div.attr('data-critpk'));
	$('#hidIndi').val(div.attr('data-indipk'));
	$('#hidDivCrit').val($('#'+divCrit).attr('id'));
	divFactorGlobal = div.closest('.divFactor').attr('id');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { idCriterio  : idCriterio,
				     idIndicador : idIndicador}, 
			url  : 'c_evaluar/getValores',
			type : 'POST'
		})
		.done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	        	$('#contDivValores').html(data.tablaValores);
	        	$('#tbVals').bootstrapTable({ });
			    componentHandler.upgradeAllRegistered();
	        	var val = $('#'+idDiv).attr('data-valor_radio_real');
	        	$("input[name='radioVals'][value='"+val+"']").prop('checked', true);
	        	$('#switch_aplicar').closest('.mdl-card__menu').css('display', 'none');
	        	if(data.replicarFlg == 1) {
	        		$('#switch_aplicar').closest('.mdl-card__menu').css('display', 'block');
	        	}
	        	modal('modalValores');
	    	}
		});
	});
}

var abrirFinalizarGlobal = null;
var evalFinalizada = null;

function guardarValor() {
	Pace.restart();
	Pace.track(function() {
		var divID = $('#hidIdiv').val();
	    var valor = $('input[name="radioVals"]:checked').val();
	    var idIndi = $('#hidIndi').val();
	    var idCriterio = $('#hidIdivCrit').val();
	    $.ajax({
			data : { idCriterio : idCriterio,
				     valor      : valor,
				     idIndi     : idIndi }, 
			url  : 'c_evaluar/guardarValor',
			type : 'POST'
		})
		.done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	    		$('#'+divFactorGlobal).html(data.critTabla);
	    		divFactorGlobal = null;
	    		
	    		$('#'+divID).attr('data-valor_radio_real', valor);
	    		var valorMostrar = (valor == -1) ? 'N.A.' : (Math.round(valor * 100) / 100) ;
	    		$('#'+divID).html(valorMostrar);
	    		
	    		$('#'+divID).removeClass().addClass('label '+data.cssIndiPromedio);
	    		$('#div'+divID).effect("highlight", {color : $('#'+divID).css("background-color") }, 3000);
	    		
	            $('#'+$('#hidDivCrit').val()).html(data.promedio);
	            $('#'+$('#hidDivCrit').val()).removeClass().addClass('label '+data.cssPromedio);
	            $('#td'+$('#hidDivCrit').val()).effect("highlight", {color : $('#'+$('#hidDivCrit').val()).css("background-color") }, 3000);
	            
	            $('#notaFinal').html(data.notaFinal);
	            $('#notaFinal').attr('class', data.colorGeneral);
	            msj('success', data.msj);
	            if(data.terminoFicha == 0 && abrirFinalizarGlobal == null ) {
            		modal('modalTerminarFicha');
            		$('#menu').find('.mdi-close').parent().remove();
            		$('#menu').find('.mdi-send').parent().remove();
            		$('.boton_add').after('<button class="mfb-component__button--main is-up" data-mfb-label="Finalizar" onclick="modal(\'modalTerminarFicha\');">'+
                                          '    <i class="mfb-component__main-icon--active mdi mdi-send"></i>'+
                                          '</button>');
					abrirFinalizarGlobal = 1;
	            }
	            modal('modalValores');
	    	} else {
	    	    msj('error', data.msj, 'Error');
	        }
		});
	});
}

function reactivarSubFactor(switch_aplicar) {
	var checked = switch_aplicar.is(":checked");
	if(!checked) {
		return;
	}
	switch_aplicar.closest('.mdl-card__menu').css('display', 'none');
	var switchHtml = '<div style="position: absolute; right: 40px; top: 2.5px; color: #757575">Aplicar</div>'+
				     '    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_aplicar">'+
				     '        <span class="mdl-switch__label"></span>'+
					 '        <input type="checkbox" id="switch_aplicar" class="mdl-switch__input" onchange="reactivarSubFactor($(this));">'+
					 '    </label>';
	switch_aplicar.closest('.mdl-card__menu').html(switchHtml);
	componentHandler.upgradeAllRegistered();
	///////////////
	Pace.restart();
	Pace.track(function() {
		var idIndi = $('#hidIndi').val();
	    var idCriterio = $('#hidIdivCrit').val();
	    $.ajax({
			data : { idFactor    : idCriterio,
				     idSubFactor : idIndi },
			url  : 'c_evaluar/reactivarSubFactor',
			type : 'POST'
		})
		.done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 0) {
	    		$('#contDivValores').html(data.tablaValores);
	        	$('#tbVals').bootstrapTable({ });
	        	
	        	componentHandler.upgradeAllRegistered();
	        	
	        	$('#'+divFactorGlobal).html(data.critTabla);
	    		divFactorGlobal = null;
	        	
	        	var divID = $('#hidIdiv').val();
	        	$('#'+divID).attr('data-valor_radio_real', null);
	    		$('#'+divID).html(null);
	    		$('#'+divID).removeClass().addClass('label label-danger');
	    		if(data.terminoFicha != 0) {
	    			abrirFinalizarGlobal = null;
				    $('#menu').find('.mdi-send').parent().remove();
				    if(!$('.mdi.mdi-close')) {
				    	$('.boton_add').after('<button class="mfb-component__button--main is-up">'+
				                              '<i class="mfb-component__main-icon--active mdi mdi-close"></i>'+
				                              '</button>');
				    }
	            }
	    	} else {
	    	    msj('error', data.msj);
	        }
		});
	});
}

function toggleFullScreen() {
	  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
	   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
	    if (document.documentElement.requestFullScreen) {  
	      document.documentElement.requestFullScreen();  
	    } else if (document.documentElement.mozRequestFullScreen) {  
	      document.documentElement.mozRequestFullScreen();  
	    } else if (document.documentElement.webkitRequestFullScreen) {  
	      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
	    }  
	    $('#icon_fullScreen').removeClass('md-fullscreen');
	$('#icon_fullScreen').addClass('md-fullscreen-exit');
	  } else {  
	    if (document.cancelFullScreen) {  
	      document.cancelFullScreen();  
	    } else if (document.mozCancelFullScreen) {  
	      document.mozCancelFullScreen();  
	    } else if (document.webkitCancelFullScreen) {  
	      document.webkitCancelFullScreen();  
	    } 
	  $('#icon_fullScreen').removeClass('md-fullscreen-exit');
      $('#icon_fullScreen').addClass('md-fullscreen'); 
	  }  
	}

function grabarPuntajeFinal() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url  : 'c_evaluar/guardarEvaluacionFin', 
			type : 'POST'
		})
		.done(function(data) {
	    	data = JSON.parse(data);
	    	if(data.error == 1) {
				msj('warning',data.msj);
	    	} else if(data.error == 0) {
	    		location.reload();
	    	}
		});
	});
}

var gallery = null;

function modalVerEvidencias() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
		    type    : "POST",
	        url     : 'c_evaluar/getEvidenciasEvaluacion'
	    }).done(function(data) {
	        data = JSON.parse(data);
	 		$('#contenidoImgRut').html(data.divFotos);
	 		modal('modalVerEvidencias');
	 		document.getElementById('evidDivs').onclick = function (event) {
			    event = event || window.event;
			    var target = event.target || event.srcElement,
			        link = target.src ? target.parentNode : target,
			        options = {prevClass: 'prev',nextClass: 'next',index: link, event: event},
			        links = this.getElementsByTagName('a');
			    gallery = blueimp.Gallery(links, options);
		  	};
	 	});
		
	});
}

function openConfirmBorrar() {
	var idEvidencia = $('#idEvidencia').val();
	if($.trim(idEvidencia) == '' || idEvidencia == null || idEvidencia == undefined) {
		msj('warning', 'No se puede borrar el archivo');
		return;
	}//999999
	$('#modalConfirmDelete').css('z-index', '9999999');
	modal('modalConfirmDelete');
}

function borrarArchivo() {
	var idEvidencia = $('#idEvidencia').val();
	if($.trim(idEvidencia) == '' || idEvidencia == null || idEvidencia == undefined) {
		mostrarNotificacion('warning', 'No se puede borrar el archivo', null);
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
		    type    : "POST",
	        url     : 'c_evaluar/borrarEvidencia',
	        data    : {idEvidencia : idEvidencia}
	    }).done(function(data) {
	        data = JSON.parse(data);
	        if(data.error == 0) {
	        	mostrarNotificacion('success', data.msj, null);
	     		abrirCerrarModal('modalVerEvidencias');
	     		abrirCerrarModal('modalConfirmDelete');
	     		gallery.close();
	        } else {
	        	mostrarNotificacion('error', data.msj, null);
	        }
	 	});
	});
}

function verDocente() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
		    type    : "POST",
	        url     : 'c_evaluar/verDocente'
	    }).done(function(data) {
	        data = JSON.parse(data);
	        if(data.error == 0) {
	        	$('#fotoDocente').attr('src', data.foto_persona);
	        	$('#nomb_docente').val(data.docente);
	        	$('#curso').val(data.curso);
	        	$('#aula').val(data.aula);
	        	$('#fecha').val(data.fecha);
			    componentHandler.upgradeAllRegistered();
	        	abrirCerrarModal('modalDocente');
	        } else {
	        	mostrarNotificacion('error', data.msj, null);
	        }
	 	});
	});
}

function getOpenTema() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
		    type    : "POST",
	        url     : 'c_evaluar/getTema'
	    }).done(function(data) {
	        data = JSON.parse(data);
	        if(data.error == 0) {
	        	$('#txtTema').val(data.tema);
	        	abrirCerrarModal('modalTema');
	        } else {
	        	mostrarNotificacion('error', data.msj, null);
	        }
	 	});
	});
}

function grabarTema() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
		    type    : "POST",
	        url     : 'c_evaluar/grabarTema',
	        data    : { tema : $('#txtTema').val()}
	    }).done(function(data) {
	        data = JSON.parse(data);
	        if(data.error == 0) {
	        	abrirCerrarModal('modalTema');
	        } else {
	        	mostrarNotificacion('error', data.msj, null);
	        }
	 	});
	});
}

function abrirModalSubirEvidencias() {
    modal('modalCargarEvidencias');
}

function cerrarModalEvidencias() {
	if(toog2 ==	0){
		abrirCerrarModal('modalCargarEvidencias');
	}
}
/*
function logOut() {
	$.ajax({
		url  : 'c_evaluar/logout', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
		window.close();
	});
}*/