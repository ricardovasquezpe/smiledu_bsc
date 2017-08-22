var socket   = null;
var flg_node = false;

function init(evento, tab, nivel, grado, curso) {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	
	if(getCookie("nivelfiltro") != null){
		nivel = getCookie("nivelfiltro");
	}
	if(getCookie("gradofiltro") != null){
		grado = getCookie("gradofiltro");
	}
	if(getCookie("cursofiltro") != null){
		curso = getCookie("cursofiltro");
	}
    returnPage();
    initSocket(evento, tab, nivel, grado, curso);
}

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function initSocket(evento, tab, nivel, grado, curso) {
	if(socket != null){
		socket.disconnect();
	}
	socket = io.connect(nodeServer,{ query: "evento="+evento+"&estado="+tab+"&nivel="+nivel+"&grado="+grado+"&curso="+curso+'&param=LLAMADAS'});
    socket.on('notification', function (data) {
    	if(flg_node) {
    		return;
    	}
    	postulantes = data.postulantes;
    	$('.mdl-inscritos').each(function(i,e) {
    	    id = $(this).attr("id");
    	    if (!postulantes.contains(id)) {
   	    	   $("#"+id).addClass("mdl-card__delete");
 	    	   setTimeout(function(){ $("#"+id).remove(); }, 800);
 	    	} else {
 	    		var index = postulantes.indexOf(id);
 	    		if (index > -1) {
 	    			postulantes.splice(index, 1);
 	    		}
 	    	}
        });
    	if(postulantes.length > 0) {
    		postulantesRestantes(postulantes);
    	}
    });
}

function postulantesRestantes(postulantes){
	Pace.restart();
	Pace.track(function() {
		flg_node = true;
		$.ajax({
			data : { postulantes : postulantes },
			url  : 'c_evaluacion/postulantesRestantes',
			type : 'POST'
		})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.count > 0){
				$("#cont_contactos_"+cons_index_elegido).append(data.contactos);
				componentHandler.upgradeAllRegistered();
			}
			flg_node = false;
		});
	});
}

function getContactosByEstado(estado, index){
	Pace.restart();
	Pace.track(function() {
		gradoNivel = $("#selectGradoNivelFiltro").val();
		curso      = $("#selectCursoFiltro").val();
		$.ajax({
			data : { estado     : estado,
				     gradonivel : gradoNivel,
				     curso      : curso },
			url  : 'c_evaluacion/contactosPorEstado',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$("#cont_contactos_1").html(null);
				$("#cont_contactos_2").html(null);
				$("#cont_contactos_3").html(null);
				$("#cont_contactos_4").html(null);
				cons_estado_elegido = estado;
				cons_index_elegido  = index;
				if(data.count > 0){
					$("#cont_contactos_"+index).html(data.contactos);
					componentHandler.upgradeAllRegistered();
					$("#cont_empty_evaluados").css("display", "none");
				}else{
					$("#cont_empty_evaluados").css("display", "block");
				}
				socket.disconnect();
				initSocket(data.evento, data.tab, 0, 0, 0);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function goToEditContacto(elem){
	var idContacto = $(elem).parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	if(idContacto != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_evaluacion/goToEvaluarContacto',
			data    : {idcontacto : idContacto},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.opc == 0){
				window.location.href = 'c_detalle_evaluacion';
			}else{
				cons_contacto = idContacto;
				abrirModalProcesoMatricula();
			}
		});
	}
}

function verDiagnosticoTabla(idContaco, nombreContact){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { contacto : idContaco },
			url  : 'c_evaluacion/resumenDiagnosticos',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$("#cont_tb_resumen_diagnostico").html(data.tabla);
				$("#tbResumenDiagnostico").bootstrapTable({});
				$("#cont_tb_diagnostico_subdirector").html(data.tablaSubdirector);
				$("#tbDiagnosticoSubdirector").bootstrapTable({});
				$("#tituloResumenDiag").html("Resumen Diagnostico: "+nombreContact);
				modal("modalResumenDiag");
			} catch(err) {
				location.reload();
			}
		});
	});
}

var cons_contacto = null;
function abrirModalProcesoMatricula(contacto){
    modal('modalMigrarMatricula');
    cons_contacto = contacto;
}
 
function ingresarAMatricula(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { contacto : cons_contacto },
			url  : 'c_evaluacion/procesoMatricula',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				if(data.error == 0){
					modal('modalMigrarMatricula');
				}
				msj('success', data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function refrescar(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { estado : cons_estado_elegido },
			url  : 'c_evaluacion/contactosPorEstado',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				$("#cont_contactos_1").html(null);
				$("#cont_contactos_2").html(null);
				$("#cont_contactos_3").html(null);
				if(data.count > 0){
					$("#cont_contactos_"+cons_index_elegido).html(data.contactos);
					componentHandler.upgradeAllRegistered();
					$("#cont_empty_evaluados").css("display", "none");
				}else{
					$("#cont_empty_evaluados").css("display", "block");
				}
			} catch(err) {
				location.reload();
			}
		});
	});
}

function abrirModalFiltrar(){
	modal("modalFiltrar");
}

function getCursosByGradoNivel(){
	gradoNivel = $("#selectGradoNivelFiltro").val();
	if(gradoNivel.length == 0){
		setCombo("selectCursoFiltro", null, "Cursos");
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { gradonivel : gradoNivel},
			url  : 'c_evaluacion/cursosByGradoNivel',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				setCombo("selectCursoFiltro", data.cursos, "Cursos");
			} catch(err) {
				location.reload();
			}
		});
	});
}

function filtrarPostulantes(){
	//nombre     = $("#nombreFiltrar").val();
	socket.disconnect();
	gradoNivel = $("#selectGradoNivelFiltro").val();
	curso      = $("#selectCursoFiltro").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { estado     : cons_estado_elegido,
					 gradonivel : gradoNivel,
					 curso      : curso
				     /*nombre : nombre*/},
			url  : 'c_evaluacion/filtrarPostulantes',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
				/*$("#cont_contactos_1").html(null);
				$("#cont_contactos_2").html(null);
				$("#cont_contactos_3").html(null);
				if(data.count > 0){
					$("#cont_contactos_"+cons_index_elegido).html(data.contactos);
					componentHandler.upgradeAllRegistered();
					$("#cont_empty_evaluados").css("display", "none");
				}else{
					$("#cont_empty_evaluados").css("display", "block");
				}*/
				document.cookie = "nivelfiltro="+data.nivel;
				document.cookie = "gradofiltro="+data.grado;
				document.cookie = "cursofiltro="+data.curso;
				initSocket(data.evento, data.tab, data.nivel, data.grado, data.curso);
				modal("modalFiltrar");
			} catch(err) {
				location.reload();
			}
		});
	});
}

Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] === needle) {
    	   return true;
       }
   }
   return false;
}