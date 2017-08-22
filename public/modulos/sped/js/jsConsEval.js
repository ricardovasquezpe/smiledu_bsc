var toog2 = 0;
var $_idEvaluacionGlobal = null;

function verRubrica(boton) {
	var row = boton.closest("tr");
    var idEval = row.find(".classPK").attr("data-pk");
    $('<input type="hidden" name="idEval" id="idEval"/>').val(idEval).appendTo('#formPdfRubrica');
    $('#formPdfRubrica').submit();
}

function adjuntarEnChat() {
	$('#fileToUpload').val(null);
	$('#fileToUpload').click();
}

$("#fileToUpload").change(function(e) {
	var file = this.files[0];
	var extFile = $.trim(file.name.split('.').pop());
	canvasResize(file, {
		width: 500,
		height: 0,
		crop: false,
		quality: 80,
		callback: function(data, width, height) {
			$("#fotoAdjuntar").attr("src", data);
			modal('modalPrevioImgAdj');
		}
	});
});

$('#modalPrevioImgAdj').on('hidden.bs.modal', function () {
    $('#fileToUpload').val(null);
});

function verEvidencias(boton) {
	var row = boton.closest("tr");
    var idEval = row.find(".classPK").attr("data-pk");
    Pace.restart();
	Pace.track(function() {
		$.ajax({
    	    type    : "POST",
            url     : 'c_cons_eval/getEvidenciasEvaluacion',
            data    : {idEval : idEval}
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.error == 0) {
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
            }
     	});
	});
}

function verMensajes(boton, nombrePers) {
	var row = boton.closest("tr");
    var idEval = row.find(".classPK").attr("data-pk");
    $('#hidIdEval').val(idEval);
    $('#comentario').val(null);
    Pace.restart();
	Pace.track(function() {
		$.ajax({
    	    type    : "POST",
            url     : 'c_cons_eval/getMensajesEva',
            data    : { idEval : idEval }
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.error == 0) {
            	$('#ulMensajes').html(data.mensajes);
            	$('#nombrePersMensaje').text(nombrePers);
            	$('#'+data.notifID).html(null);
            	modal('modalVerMensajes');
            	setTimeout(function(){
            		$("#scroll1").animate({ scrollTop: $('#scroll1').prop("scrollHeight")}, 1000);
            	}, 150);
            	$(document).ready(function(){
            	    $('[data-toggle="tooltip"]').tooltip();
                });
            	document.getElementById('ulMensajes').onclick = function (event) {
            	    event = event || window.event;
            	    var target  = event.target || event.srcElement;
            	    if( !(target.src && target.getAttribute("data-galeria") ) ) {
            	    	return;
            	    }
            	    var link    = (target.src && target.getAttribute("data-galeria") ) ? target.parentNode : null;
            	    var options = {prevClass: 'prev',nextClass: 'next',index: link, event: event};
            	    var links   = this.getElementsByTagName('a');
            	    if(link) {
            	    	gallery = blueimp.Gallery(links, options);
            	    }
              	};
            }
     	});
	});
}

function guardarMsj() {
    var divEmojis = $('#comentario').next();
    var comentario = divEmojis.html();
	var idEval = $('#hidIdEval').val();

	comentario = comentario.replace(/&nbsp;/g, '');
    if($.trim(comentario) == '') {
        msj('error', 'Ingrese el comentario');
        return;
    }
    if(idEval == null || idEval == undefined || $.trim(idEval) == '') {
    	msj('error', 'Error inesperado');
        return;
    }
    Pace.restart();
	Pace.track(function() {
		$.ajax({
    	    type : "POST",
            url  : 'c_cons_eval/insertMensaje',
            data : { idEval : idEval,
                     msj    : $.trim(comentario) }
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.error == 0) {
            	$('#ulMensajes').append(data.mensajes);
            	$('#comentario').val(null);
            	$('#comentario').removeClass("dirty");
            	divEmojis.html(null);
            	$("#scroll1").animate({ scrollTop: $('#scroll1').prop("scrollHeight")}, 1000);
            	$(document).ready(function(){
            	    $('[data-toggle="tooltip"]').tooltip();
                });
            	msj('success', data.msj);
            } else {
            	msj('error', data.msj);
            }
     	});
	});
}

function guardarMsjAdj() {
    var divEmojis = $('#comentarioAdj').next();
    var comentario = divEmojis.html();
	var idEval = $('#hidIdEval').val();

	comentario = comentario.replace(/&nbsp;/g, '');
    if(!idEval) {
    	msj('error', 'Error inesperado');
        return;
    }
    Pace.restart();
	Pace.track(function() {
		$.ajax({
    	    type : "POST",
            url  : 'c_cons_eval/insertMensajeAdj',
            data : { idEval : idEval,
                     msj    : $.trim(comentario),
                     img64  : $("#fotoAdjuntar").attr("src") }
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.error == 0) {
            	$('#fileToUpload').val(null);
            	modal('modalPrevioImgAdj');
            	$('#ulMensajes').append(data.mensajes);
            	$('#comentarioAdj').val(null);
            	$('#comentarioAdj').removeClass("dirty");
            	divEmojis.html(null);
            	$("#scroll1").animate({ scrollTop: $('#scroll1').prop("scrollHeight")}, 1000);
            	
            	$(document).ready(function(){
            	    $('[data-toggle="tooltip"]').tooltip();
                });
            	
            	document.getElementById('ulMensajes').onclick = function (event) {
            	    event = event || window.event;
            	    var target  = event.target || event.srcElement;
            	    if( !(target.src && target.getAttribute("data-galeria") ) ) {
            	    	return;
            	    }
            	    var link    = (target.src && target.getAttribute("data-galeria") ) ? target.parentNode : null;
            	    var options = {prevClass: 'prev',nextClass: 'next',index: link, event: event};
            	    var links   = this.getElementsByTagName('a');
            	    if(link) {
            	    	gallery = blueimp.Gallery(links, options);
            	    }
              	};
            	msj('success', data.msj);
            } else {
            	msj('error', data.msj);
            }
     	});
	});
}

function abrirModalSubirEvidencias(btn) {
    var idEval = btn.closest("tr").find(".classPK").attr("data-pk");
    $_idEvaluacionGlobal = idEval;
    modal('modalCargarEvidencias');
}

function initDropzone() {
	Dropzone.autoDiscover = false;

	$("#dropzone").dropzone({
		url: "c_cons_eval/subirArchivos",
		addRemoveLinks: true,
		autoProcessQueue: false,
		parallelUploads: 20,
		maxFilesize: 50,//Mbs
		dictResponseError: "Ha ocurrido un error en nuestro servicio.",
		dictDefaultMessage: "Arrastra tus evidencias aqu&iacute; o Haz click en esta zona",
		acceptedFiles: CONFIG.get('TIPOS'),
		complete: function(file){
			if(file.status == "success"){// THIRD
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
		    this.on('complete', function () {//FOURTH
	            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
	            	if(error == 0) {
	            		modal('modalCargarEvidencias');
	            	}
	            }
	        });
		    this.on("queuecomplete", function (file) {//LAST
			    this.removeAllFiles();
		    	if(error == 0) {
		    		$_idEvaluacionGlobal = null;
			      	msj('success','Se subieron tus evidencias :D ');
		    	}
		    });
	        this.on("success", function(file, responseText) {//SECOND
	        	 //concatEvi += responseText+'_';
	        });
	        this.on("sending", function(file, xhr, formData) {//FIRST
    	        if(!$_idEvaluacionGlobal) {
    	            return;
    	        }
	        	formData.append("idEvaluacion", $_idEvaluacionGlobal);
  	        });
		}
	});
}

function tableEventsEvaluaciones() {
	$(function () {
		$('#tbEvaluaciones').on('all.bs.table', function (e, name, args) {
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    })
	    .on('check-all.bs.table', function (e) {
	    })
	    .on('uncheck-all.bs.table', function (e) {
	    })
	    .on('load-success.bs.table', function (e, data) {
	    })
	    .on('load-error.bs.table', function (e, status) {
	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}