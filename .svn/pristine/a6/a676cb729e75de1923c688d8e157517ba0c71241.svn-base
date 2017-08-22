var cons_scroll   = 1;
var idAulaElegida = null;
var sedeRol       = null;
var ciclo         = null;
var scroll        = 1;

setearCombo("selectYearFiltroAulas", null);

function init(){
	initButtonLoad('botonFL');
	$("#tabAulas").bootstrapTable({});
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	
	setearCombo("selectYearFiltroAulas", null);
	/*$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/getSedeRol',
		data    : {},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if (data.error == 1){//NO ES ADMIN
			$('#div').hide();
			sedeRol = data.sedeRol;
		} else if (data.error == 0){//ADMIN
			$('#div').show();
		}
	});*/
}

var x = ":" + location.port;
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
	    $('#tbAlumnosAula .no-records-found .img-search').empty();
	    $('#tbAlumnosAula .no-records-found .img-search').html('<img src="/smiledu_basico/public/general/img/smiledu_faces/not_data_found.png">'
	    													  +'<p>No hay estudiantes asignados</p>'
	    													  +'<p>a esta aula.</p>');
		abrirCerrarModal("modalAlumnos");

	});
	
}

function getSedesByYear(year, sede, nivelgrado){

	addLoadingButton('botonFL');
	$(".mdl-layout__tab-bar").parent().css("display", "none");
	if(sedeRol != null){
		getGradoNivelBySedeYear(year,sede,nivelgrado);
	    setearInput("searchMagic",null);
	} else {
		var valorYear  = $("#"+year).val();
		var valorSede  = $("#"+sede).val();
	    setearInput("searchMagic",null);
    	if(valorYear != null && valorYear.length != 0){
        	$.ajax({
        		type    : 'POST',
        		'url'   : 'c_aula/getSedesByYear',
        		data    : {year : valorYear},
        		'async' : true
        	}).done(function(data){
        		data = JSON.parse(data);
        		anio = $("#selectYearFiltroAulas").find("option:selected").text();
        		setCombo(sede, data.comboSedes, "Sede");
        		setCombo(nivelgrado, null, "Grado y Nivel");
        		$("#cont_search_empty").css("display", "block");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar").parent().css("display", "none");
				$("#cont_tabla_aulas").html(null);
				$(".mdl-content-cards .breadcrumb li:NTH-CHILD(1)").text(anio);				
		    	stopLoadingButton('botonFL');
        	});
        } else {
    		setCombo(sede, null, "Sede");
    		setCombo(nivelgrado, null, "Grado y Nivel");
    		$("#cont_search_empty").css("display", "block");
			$("#cont_search_not_found").css("display", "none");
			$("#cont_search_not_found_letter").css("display", "none");
			$(".mdl-layout__tab-bar").parent().css("display", "none");
			$(".mdl-content-cards .breadcrumb").css('display','none');
	    	stopLoadingButton('botonFL');
			$("#cont_tabla_aulas").html(null);
        }
    }
}

function getGradoNivelBySedeYear(year,sede,gradoNivel){
	addLoadingButton('botonFL');
	var valorSede = $("#"+sede).val();
	if(sedeRol != null){
		$("#img-filtrar-reporte").fadeOut();
		valorSede = sedeRol;
		
	}
    setearInput("searchMagic",null);
	var valorYear = $("#"+year).val();
	if(valorSede != null && valorSede.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_aula/getGradoNivelBySedeYear',
			data    : {idsede : valorSede,
				       year   : valorYear},
			'async' : true
			
		}).done(function(data){
			data = JSON.parse(data);
    		sede = $("#selectSedeFiltroAulas").find("option:selected").text();
			setCombo(gradoNivel, data.comboGradoNivel, "Grado y Nivel");
			$("#cont_search_empty").css("display", "block");
			$("#cont_search_not_found").css("display", "none");
			$("#cont_search_not_found_letter").css("display", "none");
			$(".mdl-layout__tab-bar").parent().css("display", "none");
			$(".mdl-content-cards .breadcrumb li:NTH-CHILD(2)").text(sede);				
			
			$("#cont_tabla_aulas").html(null);
	    	stopLoadingButton('botonFL');
		});
	} else {
		setCombo(gradoNivel, null, "Grado y Nivel");
		$("#cont_search_empty").css("display", "block");
		$("#cont_search_not_found").css("display", "none");
		$("#cont_search_not_found_letter").css("display", "none");
		$(".mdl-layout__tab-bar").parent().css("display", "none");
		$(".mdl-content-cards .breadcrumb").css('display','none');
		$("#cont_tabla_aulas").html(null);
    	stopLoadingButton('botonFL');
	}
}

function getAulasByGradoNivelSedeYear(year, sede, gradoNivel){
	addLoadingButton('botonFL');
	Pace.restart();
	Pace.track(function() {
		scroll = 1;
		pintarAlfabeto($('#tabTodos'));
		var valorYear       = $("#"+year).val();
		var valorSede       = $("#"+sede).val();
		if(sedeRol != null){
			valorSede = sedeRol;
		}
	    var valorGradoNivel = $("#"+gradoNivel).val();
	    setearInput("searchMagic",null);
	    if(valorGradoNivel.length != 0){
	    	 $.ajax({
				type    : 'POST',
				'url'   : 'c_aula/getTablaAulasByGradoNivelSedeYear',
				data    : {year    : valorYear,
					       idsede  : valorSede,
					       idgradonivel : valorGradoNivel,
					       ciclo        : ciclo},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
	    		grado = $("#selectGradoNivelFiltroAulas").find("option:selected").text();				
				result = data.tablaAulas;
				
				if(result.length != 0){
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "none");
					$("#cont_search_not_found_letter").css("display", "none");
					$('.img-search').find('.mdl-button').css('visibility', 'hidden');
					$(".mdl-layout__tab-bar").parent().css("display", "block");
					$(".mdl-content-cards .breadcrumb li:NTH-CHILD(3)").text(grado);		
					$(".mdl-content-cards .breadcrumb").removeAttr('style');
					$("#cont_tabla_aulas").html(data.tablaAulas);
					componentHandler.upgradeAllRegistered();
				}else{
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "block");
					$("#cont_search_not_found_letter").css("display", "none");
					$('.img-search').find('.mdl-button').css('visibility', 'hidden');
					$(".mdl-layout__tab-bar").parent().css("display", "none");
					$(".mdl-content-cards .breadcrumb").css('display','none');
					$("#cont_tabla_aulas").html(null);
			    	stopLoadingButton('botonFL');
				}
				stopLoadingButton('botonFL');
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
			});
	    }else{
	    	$("#cont_search_empty").css("display", "block");
			$("#cont_search_not_found").css("display", "none");
			$("#cont_search_not_found_letter").css("display", "none");
			$('.img-search').find('.mdl-button').css('visibility', 'hidden');
			$(".mdl-layout__tab-bar").parent().css("display", "none");
			$("#cont_tabla_aulas").html(null);
			stopLoadingButton('botonFL');
	    }
	});
}

function pintarAlfabeto(element){
	$(".mdl-layout__tab").removeClass("is-active");
	$(element).addClass("is-active");
}

function getAulasPendientes(){
	Pace.restart();
	Pace.track(function() {
		scroll = 0;
		setearCombo('selectYearFiltroAulas', null, "Año");
    	setCombo("selectSedeFiltroAulas", null, "Sede", null);
    	setCombo("selectGradoNivelFiltroAulas", null, "Grado y Nivel", null);
		$.ajax({	
  			type    : 'POST',
  			'url'   : 'c_aula/getAulasPendientes',
  			'async' : true
  		}).done(function(data){
			data = JSON.parse(data);
			result = data.tablaAulas;
			if(result.length != 0){
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$("#cont_tabla_aulas").html(data.tablaAulas);
				componentHandler.upgradeAllRegistered();
			}else{
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "block");
				$("#cont_tabla_aulas").html(null);
			}
			pintarAlfabeto($('#tabTodos'));
			$(".mdl-layout__tab-bar").parent().css("display", "none");
  		});
	});
}

function onScrollEvent(element){
	if(scroll == 1){
		if($(element).scrollTop() + $(element).innerHeight()>=$(element)[0].scrollHeight){
			$("#loading_cards").css("display","block");
			Pace.restart();
			Pace.track(function() {
				$.ajax({	
		  			type    : 'POST',
		  			'url'   : 'c_aula/onScrollGetAulas',
		  			data    : {count  : cons_scroll},
		  			'async' : true
		  		}).done(function(data){
		  			data = JSON.parse(data);
		  			$("#cont_tabla_aulas").append(data.tablaAulas);
		  			componentHandler.upgradeAllRegistered();
		  			cons_scroll = cons_scroll + 1;
		  			$("#loading_cards").css("display","none");
		  		});
			});
		}
	}
}

function buscarAula(){
	Pace.restart();
	Pace.track(function() {
		$(".mdl-content-cards .breadcrumb").css('display','none');
		scroll = 0;
		var textoBusqueda = $("#searchMagic").val();
		pintarAlfabeto($('#tabTodos'));
		if($.trim(textoBusqueda).length > 0){
			setearCombo('selectYearFiltroAulas', null, "Año");
	    	setCombo("selectSedeFiltroAulas", null, "Sede", null);
	    	setCombo("selectGradoNivelFiltroAulas", null, "Grado y Nivel", null);
		}
		if($.trim(textoBusqueda).length >= 3){
			$.ajax({
				type: 'POST',
				url: "c_aula/buscarAula",
		        data: { textoBusqueda : textoBusqueda,
		        	    ciclo         : ciclo},
		        async: false
		  	})
		  	.done(function(data) {
		  		data = JSON.parse(data);		
				if(data.error == 0) {
					result = data.tablaAulas;
					if(result.length != 0){
						$("#cont_search_empty").css("display", "none");
						$("#cont_search_not_found").css("display", "none");
						$("#cont_search_not_found_letter").css("display", "none");
						$(".mdl-layout__tab-bar").parent().css("display", "block");
						$("#cont_tabla_aulas").html(data.tablaAulas);
						componentHandler.upgradeAllRegistered();
					}else{
						$("#cont_search_empty").css("display", "none");
						$("#cont_search_not_found").css("display", "block");
						$("#cont_search_not_found_letter").css("display", "none");
						$(".mdl-layout__tab-bar").parent().css("display", "none");
						$("#cont_tabla_aulas").html(null);
					}
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
				}else{
					msj('error', data.msj, null);
				}
		  	});
		}else{
			$("#cont_search_empty").css("display", "block");
			$("#cont_search_not_found").css("display", "none");
			$("#cont_search_not_found_letter").css("display", "none");
			$(".mdl-layout__tab-bar").parent().css("display", "none");
			$("#cont_tabla_aulas").html(null);
		}
	});
}

function getAulasByTipoCiclo(tipociclo, element){
	Pace.restart();
	Pace.track(function() {
		scroll = 0;
		var valorYear       = $("#selectYearFiltroAulas").val();
		var valorSede       = $("#selectSedeFiltroAulas").val();
		if(sedeRol != null){
			valorSede = sedeRol;
		}
	    var valorGradoNivel = $("#selectGradoNivelFiltroAulas").val();
	    var textoBusqueda   = $("#searchMagic").val();
    	 $.ajax({
			type    : 'POST',
			'url'   : 'c_aula/getAulasByTipoCiclo',
			data    : {year          : valorYear,
				       idsede        : valorSede,
				       idgradonivel  : valorGradoNivel,
				       textobusqueda : textoBusqueda,
				       ciclo         : tipociclo},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			result = data.tablaAulas;
			if(result.length != 0){
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$("#cont_tabla_aulas").html(data.tablaAulas);
				componentHandler.upgradeAllRegistered();
			}else{
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "block");
				$("#cont_tabla_aulas").html(null);
			}
			pintarAlfabeto($(element));
		});
	});
}

function goToViewAula(idaula){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_aula/goToViewAula',
			data    : {idaula : idaula},
			'async' : false
		}).done(function(data){
			window.location.href = 'c_detalle_aula';
		});
	});
}

function goToCreateAula(idaula){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_aula/goToCreateAula',
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
			'url'   : 'c_aula/goToEditAula',
			data    : {idaula : idaula},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			if (data.error == 1) {
				mostrarNotificacion('success', data.msj, null);
			} else {
				window.location.href = 'c_detalle_aula';
			}
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
	if(sedeRol != null){
		valorSede = sedeRol;
	}
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
		           ciclo         : ciclo},
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

/*

function editarAula(){
	var year        = $("#yearEditar").val();
	var descaula    = $("#desc_aulaEditar").val();
	var capamax     = $("#capaMaxEditar").val();
	var turno       = $('#selectTurnoEditar option:selected').val();
	var sede        = $('#selectSedeEditar option:selected').val();
	var nivel       = $('#selectNivelEditar option:selected').val();
	var grado       = $('#selectGradoEditar option:selected').val();
	var docente     = $('#selectDocenteEditar option:selected').val();
	var tipoEva     = $('#selectTipoEvaluacionEditar option:selected').val();
	var tutor       = $('#selectDocenteEditar option:selected').val();
	var nombreLetra = $("#ugelEditar").val();
	var observa     = $('#observacionEditar').val();
	var tipoNota    = $('#selectTipoEvaluacionEditar option:selected').val();
	//COMBOS
	var valorSede  = $("#selectSedeFiltroAulas option:selected").val();
    var valorNivel = $("#selectNivelFiltroAulas option:selected").val();
    var valorGrado = $("#selectGradoFiltroAulas option:selected").val();
	
	$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/editarDetalleAula',
		data    : {yearaula    : year,
			       descaula    : descaula,
			       capamaxaula : capamax,
			       idaula      : idAulaElegida,
			       sede        : sede,
			       nivel       : nivel,
			       grado       : grado,
			       tutor       : tutor,
			       nombreletra : nombreLetra,
			       observac    : observa,
			       tiponota    : tipoNota,
			       //COMBOS
				   idnivelFiltro : valorNivel,
			       idsedeFiltro  : valorSede,
			       idgradoFiltro : valorGrado
			       },
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#cont_tabla_aulas").html(data.tablaAulas);
			$("#tabAulas").bootstrapTable({});
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			componentHandler.upgradeAllRegistered();
			abrirCerrarModal("modalEditarAula");
			mostrarNotificacion('success', data.msj, null);
		}else {
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function verAlumnosAula(idaula){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_aula/getAlumnosAula',
		data    : {idaula  : idaula},
		'async' : false
	}).done(function(data){
		data = JSON.parse(data);
		$("#cont_tabla_AlumnosAula").html(data.tablaAlumnos);
		$("#tbAlumnosAula").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		idAulaElegida = idaula;
		abrirCerrarModal("modalAlumnos");
	});
}

function getGradosByNivelYear(year, sede, nivel, grado){
	var valorYear = $("#"+year).val();
	var valorSede  = $("#"+sede).val();
    var valorNivel = $("#"+nivel).val();
        
    if(valorNivel != null && valorNivel.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_aula/getGradosByNivelYear',
    		data    : {idnivel : valorNivel,
    			       idsede  : valorSede,
    			       year    : valorYear},
    		'async' : false
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(grado, data.comboGrados, "Grado");
    		$("#cont_tabla_aulas").html(null);
    	});
    } else {
    	setCombo(grado, null, "Grado");
    	$("#cont_tabla_aulas").html(null);
    }
}

function getNivelesBySede(sede, nivel, grado, aula){
	var valorSede = $("#"+sede).val();
	if(valorSede != null && valorSede.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_aula/getNivelesBySede',
			data    : {idsede : valorSede,},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(nivel, data.comboNiveles, "Nivel");
			setCombo(grado, null, "Grado");
			setCombo(aula, null, "Aula");
		});
	} else {
		setCombo(nivel, null, "Nivel");
		setCombo(grado, null, "Grado");
		setCombo(aula,null,"Aula");
	}
}

function getGradosByNivel(sede, nivel, grado, aula){
	var valorSede  = $("#"+sede).val();
    var valorNivel = $("#"+nivel).val();
        
    if(valorNivel != null && valorNivel.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_aula/getGradosByNivel',
    		data    : {idnivel : valorNivel,
    			       idsede  : valorSede},
    		'async' : false
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(grado, data.comboGrados, "Grado");
    		setCombo(aula, null, "Aula");
    	});
    } else {
    	setCombo(grado, null, "Grado");
    	setCombo(aula, null, "Aula");
    }
}

function getAulasNoActiBySede(sede, grado, aula){
	var valorSede  = $("#"+sede).val();
    var valorGrado = $("#"+grado).val();
	if(valorGrado != null && valorGrado.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_aula/getAulasNoActiBySede',
    		data    : {idsede : valorSede},
    		'async' : false
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(aula, data.comboGetAulasNoActiBysede, "Aula");
    	});
    } else {
    	setCombo(grado, null, "Grado");
    }
}

*/