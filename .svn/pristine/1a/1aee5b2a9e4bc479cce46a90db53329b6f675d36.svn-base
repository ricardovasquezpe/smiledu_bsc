var cons_tipo_filtro = null;
var cons_scroll      = 1;
var scroll           = 0;
var cons_letra       = null;
var cons_id_alumno = null;
setearInput("searchMagic", null);
/*$('body').bind('beforeunload',function(){
	deleteAllCookies();
});
$('body').on('load', function(){
	deleteAllCookies();
});*/
function init(){
	$(":input").inputmask();
	$("#tablaAlumnos").bootstrapTable({});
	initLimitInputs('motivoTraslado');
	initButtonLoad('botonAE','botonST','botonFA');
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ){
	    $('.selectButton').selectpicker('mobile');
	}else{
		$('.selectButton').selectpicker();
	}
	getGradoNivel('yearFiltro', 'sedeFiltro', 'nivelGradoFiltro','aulaFiltro');
	/*var data = document.referrer;
	var data_array = data.split('/');
	if(data_array[5] == 'c_detalle_alumno'){
		if(getCookie("tipofiltro") == 1){
			$("#searchMagic").val(getCookie("nombrefiltro"));
			setTimeout(function(){ $(".mdl-layout__tab-bar").parent().css("display", "block");}, 7);
			buscarAlumno(getCookie("tipofiltro"));
		}else if(getCookie("tipofiltro") == 2){
			setearCombo("sedeFiltro", getCookie("sedefiltro"));
			getGradoNivelBySedeFiltro('sedeFiltro', 'nivelGradoFiltro','aulaFiltro');
			setearCombo("nivelGradoFiltro", getCookie("gradonivelfiltro"));
			getAulasByNivelGradoFiltro('sedeFiltro', 'nivelGradoFiltro', 'aulaFiltro');
			setearCombo("aulaFiltro", getCookie("aulafiltro"));
			setTimeout(function(){ $(".mdl-layout__tab-bar").parent().css("display", "block");}, 7);
			buscarAlumno(getCookie("tipofiltro"));
		}else{
			
		}
	}else{
		deleteAllCookies();
	}*/
}

//$('#modalFiltro').find('button[data-dismiss="modal"]').click(function(){
//	resetSelect('yearFiltro');
//	resetSelect('sedeFiltro');
//	resetSelect('nivelGradoFiltro');
//	resetSelect('aulaFiltro');
//});

/*function deleteAllCookies() {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
    	var cookie = cookies[i];
    	var eqPos = cookie.indexOf("=");
    	var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    	document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}*/

/*function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}*/

function buscarAlumnoNombre(){
	cons_letra       = null;
	pintarAlfabeto($('#tabTodos'));
	$("#loading_cards").css("display","none");
	var nombreFiltro     = $("#searchMagic").val();
	var valorYear        = $("#yearFiltro").val();
	var valorSede        = $("#sedeFiltro").val();
	var valorGradoNivel  = $("#nivelGradoFiltro").val();
	var valorAula        = $("#aulaFiltro").val();
	if(nombreFiltro.length != 0 && nombreFiltro.length >= 3){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_alumno/buscarAlumnoNombre',
				data    : {nombre     : nombreFiltro,
					       year       : valorYear,
					       sede       : valorSede,
					       gradonivel : valorGradoNivel,
					       aula       : valorAula,
					       letra      : cons_letra},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				result = data.tablaAlumnos;
				nombreYear       = valorYear.length != 0       ? $("#yearFiltro").find("option:selected").text() : '-';
				nombreSede       = valorSede.length != 0       ? $("#sedeFiltro").find("option:selected").text() : '-';
				nombreNivelGrado = valorGradoNivel.length != 0 ? $("#nivelGradoFiltro").find("option:selected").text() : '-';
				nombreAula       = valorAula.length != 0       ? $("#aulaFiltro").find("option:selected").text() : '-';
				$("#breadYear").text(nombreYear);
				$("#breadSede").text(nombreSede);
				$("#breadGradoNivel").text(nombreNivelGrado);
				$("#breadAula").text(nombreAula);
				if(result.length != 0){
					$("#breadCrumbEst").css("display", "block");
					$("#cont_alumnos_pincipal").html(result);
					componentHandler.upgradeAllRegistered();
					$("#cont_alumnos_pincipal").highlight(nombreFiltro);
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "none");
					$("#cont_search_not_found_letter").css("display", "none");
					$(".mdl-layout__tab-bar-container").css("display", "block");
				} else {
					$("#breadCrumbEst").css("display", "none");
					$("#cont_alumnos_pincipal").html(null);
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "block");
					$("#cont_search_not_found_letter").css("display", "none");
					$(".mdl-layout__tab-bar-container").css("display", "none");
				}
				$(".mdl-layout__obfuscator").removeClass("is-visible");
				scroll           = 1;
				cons_scroll      = 1;
				cons_tipo_filtro = 1;
				//document.cookie = "nombrefiltro="+nombreFiltro;
			});
		});
	} else {
		$(".mdl-layout__obfuscator").addClass("is-visible");
		$("#breadCrumbEst").css("display", "none");
		$("#cont_alumnos_pincipal").html(null);
		$(".mdl-layout__tab-bar-container").css("display", "none");
		$("#cont_search_empty").css("display", "block");
		$("#cont_search_not_found").css("display", "none");
		$("#cont_search_not_found_letter").css("display", "none");
	}
//	setearCombo("yearFiltro", null);
//	setCombo("sedeFiltro", null, "sede");
//	setCombo("nivelGradoFiltro", null, "grado y nivel");
//	setCombo("aulaFiltro", null, "aula");
//	$("#breadCrumbEst").css("display", "none");
}

function buscarFiltros(){
	cons_letra       = null;
	pintarAlfabeto($('#tabTodos'));
	addLoadingButton('botonFA');
	$("#loading_cards").css("display","none");
	var nombreFiltro     = $("#searchMagic").val();
	var valorYear        = $("#yearFiltro").val();
	var valorSede        = $("#sedeFiltro").val();
	var valorGradoNivel  = $("#nivelGradoFiltro").val();
	var valorAula        = $("#aulaFiltro").val();

	nombreYear       = valorYear.length != 0       ? $("#yearFiltro").find("option:selected").text() : '-';
	nombreSede       = valorSede.length != 0       ? $("#sedeFiltro").find("option:selected").text() : '-';
	nombreNivelGrado = valorGradoNivel.length != 0 ? $("#nivelGradoFiltro").find("option:selected").text() : '-';
	nombreAula       = valorAula.length != 0       ? $("#aulaFiltro").find("option:selected").text() : '-';
	$("#breadYear").text(nombreYear);
	$("#breadSede").text(nombreSede);
	$("#breadGradoNivel").text(nombreNivelGrado);
	$("#breadAula").text(nombreAula);
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_alumno/buscarAlumnoNombre',
			data    : {nombre     : nombreFiltro,
				       year       : valorYear,
				       sede       : valorSede,
				       gradonivel : valorGradoNivel,
				       aula       : valorAula,
				       letra      : cons_letra},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			stopLoadingButton('botonFA');
			result = data.tablaAlumnos;
			if(result.length != 0){
				$("#cont_alumnos_pincipal").html(result);
				componentHandler.upgradeAllRegistered();
				$("#cont_alumnos_pincipal").highlight(nombreFiltro);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "block");
				$("#breadCrumbEst").css("display", "block");
			} else {
				$("#cont_alumnos_pincipal").html(null);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "block");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "none");
				$("#breadCrumbEst").css("display", "none");
			}
//				$(".mdl-layout__obfuscator").removeClass("is-visible");
//				pintarAlfabeto($('#tabTodos'));
			scroll           = 1;
			cons_tipo_filtro = 2;
			cons_scroll      = 1;

			stopLoadingButton('botonFA');
			//abrirCerrarModal('modalFiltro');
			//document.cookie = "nombrefiltro="+nombreFiltro;
		});
	});
	stopLoadingButton('botonFA');
//		$(".mdl-layout__obfuscator").addClass("is-visible");
//		stopLoadingButton('botonFA');
//		pintarAlfabeto($('#tabTodos'));
////		$("#breadCrumbEst").css("display", "none");
//		$("#cont_alumnos_pincipal").html(null);
//		$(".mdl-layout__tab-bar-container").css("display", "none");
//		$("#cont_search_empty").css("display", "block");
//		$("#cont_search_not_found").css("display", "none");
//		$("#cont_search_not_found_letter").css("display", "none");
}

function buscarAlumnoAula(){
	addLoadingButton('botonFA');
//	setearInput("searchMagic", null);
	valorAula  = $("#aulaFiltro").val();
	if(valorAula.length != 0){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_alumno/buscarAlumnoAula',
				data    : {aula : valorAula},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);	
				
				nombreSede       = $("#sedeFiltro").find("option:selected").text();
				nombreNivelGrado = $("#nivelGradoFiltro").find("option:selected").text();
				nombreAula       = $("#aulaFiltro").find("option:selected").text();
				$("#breadSede").text(nombreSede);
				$("#breadGradoNivel").text(nombreNivelGrado);
				$("#breadAula").text(nombreAula);
//				$("#breadCrumbEst").css("display", "block");
				stopLoadingButton('botonFA');
				pintarAlfabeto($('#tabTodos'));
				result = data.tablaAlumnos;
				if(result.length != 0){
					$("#cont_alumnos_pincipal").html(result);
					componentHandler.upgradeAllRegistered();
					$(".mdl-layout__tab-bar-container").css("display", "block");
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "none");
					$("#cont_search_not_found_letter").css("display", "none");
					$(".mdl-layout__tab-bar-container").css("display", "block");
					stopLoadingButton('botonFA');
				}else{
					$("#cont_alumnos_pincipal").html(null);
					$("#cont_search_empty").css("display", "none");
					$("#cont_search_not_found").css("display", "block");
					$("#cont_search_not_found_letter").css("display", "none");
					$(".mdl-layout__tab-bar-container").css("display", "none");
					stopLoadingButton('botonFA');
				}
				scroll           = 0;
				cons_tipo_filtro = 2;
				cons_scroll      = 1;

//				setearCombo('yearFiltro',null);
//				setearCombo('sedeFiltro',null);
//				setearCombo('nivelGradoFiltro',null);
//				setearCombo('aulaFiltro',null);
//				setCombo('sedeFiltro',null,'Selec. sede');
//				setCombo('nivelGradoFiltro',null,'Selec. grado y nivel');
//				setCombo('aulaFiltro',null,'Selec. aula');
				/*document.cookie = "sedefiltro="+$("#sedeFiltro").val();
				document.cookie = "gradonivelfiltro="+$("#nivelGradoFiltro").val();
				document.cookie = "aulafiltro="+$("#aulaFiltro").val();*/
				stopLoadingButton('botonFA');
				abrirCerrarModal('modalFiltro');
			});
		});
	}else{
		stopLoadingButton('botonFA');
		pintarAlfabeto($('#tabTodos'));
//		$("#breadCrumbEst").css("display", "none");
		$("#cont_alumnos_pincipal").html(null);
		$(".mdl-layout__tab-bar-container").css("display", "none");
		$("#cont_search_empty").css("display", "block");
		$("#cont_search_not_found").css("display", "none");
		$("#cont_search_not_found_letter").css("display", "none");
	}
}

/*function buscarAlumno(tipoFiltro){
	Pace.restart();
	Pace.track(function() {
		$(".mdl-layout__content").prop("onscroll", null);
		cons_tipo_filtro = tipoFiltro;
		if(tipoFiltro == 1){
			document.cookie = "tipofiltro="+tipoFiltro;
			var nombreFiltro = $("#searchMagic").val();
			if(nombreFiltro.length != 0 && nombreFiltro.length >= 3){
				$.ajax({	
					type    : 'POST',
					'url'   : 'c_alumno/buscarAlumno',
					data    : {nombre  : nombreFiltro,
							   tipo    : tipoFiltro},
					'async' : true
				}).done(function(data){
					data = JSON.parse(data);
					result = data.tablaAlumnos;
					$("#cont_alumnos_pincipal").html(data.tablaAlumnos);
					if(result.length != 0){
						$("#cont_search_empty").css("display", "none");
					}else{
						$("#cont_search_empty").css("display", "block");
					}
					pintarAlfabeto($('#tabTodos'));
					$("#rutaBusqueda").text("");
					componentHandler.upgradeAllRegistered();
					
					document.cookie = "nombrefiltro="+nombreFiltro;
				});
			}else{
				pintarAlfabeto($('#tabTodos'));
				$("#rutaBusqueda").text("");
			}
		}else{
			document.cookie = "tipofiltro="+tipoFiltro;
			valorAula  = $("#aulaFiltro").val();
			if(valorAula.length != 0){
				$.ajax({	
					type    : 'POST',
					'url'   : 'c_alumno/buscarAlumno',
					data    : {aula  : valorAula,
							   tipo  : tipoFiltro},
					'async' : true
				}).done(function(data){
					data = JSON.parse(data);	
					
					nombreSede  = $("#sedeFiltro").find("option:selected").text();
					nombreNivelGrado = $("#nivelGradoFiltro").find("option:selected").text();
					nombreAula  = $("#aulaFiltro").find("option:selected").text();
					$("#rutaBusqueda").text(nombreSede+" / "+nombreNivelGrado+" / "+nombreAula);
					
					pintarAlfabeto($('#tabTodos'));
					$("#cont_alumnos_pincipal").html(data.tablaAlumnos);
					componentHandler.upgradeAllRegistered();
					
					document.cookie = "sedefiltro="+$("#sedeFiltro").val();
					document.cookie = "gradonivelfiltro="+$("#nivelGradoFiltro").val();
					document.cookie = "aulafiltro="+$("#aulaFiltro").val();
				});
			}else{
				pintarAlfabeto($('#tabTodos'));
				$("#rutaBusqueda").text("");
				$("#cont_alumnos_pincipal").html(null);
			}
		}
		$("#cont_traslados").css('display', 'none');
		$("#cont_alumnos").css('display', 'block');
		$(".mdl-layout__tab-bar-container").css("display", "block");
	});
}*/

function pintarAlfabeto(element){
	$('.mdl-layout__tab-bar').animate({
		scrollLeft: 0
	});
	$(".mdl-layout__tab").removeClass("is-active");
	$(element).addClass("is-active");
}

function abrirModalFiltros(){
	abrirCerrarModal('modalFiltro');
	$('#nombreFiltro').focus();
}

function onScrollEvent(element){
	if(scroll == 1){
		if(Math.round($(element).scrollTop() + $(element).innerHeight())+1>=$(element)[0].scrollHeight){
			scroll = 0;
			$("#loading_cards").css("display","block");
//			Pace.restart();
//			Pace.track(function() {
			var nombreFiltro     = $("#searchMagic").val();
			var valorYear        = $("#yearFiltro").val();
			var valorSede        = $("#sedeFiltro").val();
			var valorGradoNivel  = $("#nivelGradoFiltro").val();
			var valorAula        = $("#aulaFiltro").val();
			$.ajax({
	  			type    : 'POST',
	  			'url'   : 'c_alumno/onScrollGetAlumnos',
	  			data    : {count      : cons_scroll,
	  				       letra      : cons_letra,
	  				       nombre     : nombreFiltro,
					       year       : valorYear,
					       sede       : valorSede,
					       gradonivel : valorGradoNivel,
					       aula       : valorAula},
	  			'async' : true
			}).done(function(data){
	  			data = JSON.parse(data);
	  			$("#cont_alumnos_pincipal").append(data.tablaAlumnos);
	  			var nombreFiltro = $("#searchMagic").val();
	  			$("#cont_alumnos_pincipal").highlight(nombreFiltro);
	  			componentHandler.upgradeAllRegistered();
	  			cons_scroll = cons_scroll + 1;
	  			scroll = 1;
	  			if(data.tablaAlumnos.length == 0){
	  				scroll = 0;
	  			}
	  			$("#loading_cards").css("display","none");
			});
//			});
		}
	}
}

function getSedesByYearFiltro(year, sede, gradoNivel, aula){
	cons_letra       = null;
	pintarAlfabeto($('#tabTodos'));
	addLoadingButton('botonFA');
	setCombo(sede, null, "sede");
	var valorYear = $("#"+year).val();
	nombreYear       = valorYear.length != 0       ? $("#yearFiltro").find("option:selected").text() : '-';
	nombreSede       = '-';
	nombreNivelGrado = '-';
	nombreAula       = '-';
	$("#breadYear").text(nombreYear);
	$("#breadSede").text(nombreSede);
	$("#breadGradoNivel").text(nombreNivelGrado);
	$("#breadAula").text(nombreAula);
	$("#loading_cards").css("display","none");
	var nombreFiltro     = $("#searchMagic").val();
//	if(valorYear.length != 0){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_alumno/getSedesByYear',
			data    : {year       : valorYear,
				       nombre     : nombreFiltro,
				       letra      : cons_letra},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(sede, data.comboSedes, "sede");
			setCombo(gradoNivel, null, "grado y nivel");
			setCombo(aula, null, "aula");
			stopLoadingButton('botonFA');
			result = data.tablaAlumnos;
			if(result.length != 0){
				$("#cont_alumnos_pincipal").html(result);
				componentHandler.upgradeAllRegistered();
				$("#cont_alumnos_pincipal").highlight(nombreFiltro);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "block");
				$("#breadCrumbEst").css("display", "block");
			} else {
				$("#cont_alumnos_pincipal").html(null);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "block");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "none");
				$("#breadCrumbEst").css("display", "none");
			}
			scroll           = 1;
			cons_tipo_filtro = 2;
			cons_scroll      = 1;
		});
	});
//	}else{
//		setCombo(sede, null, "Sede");
//		setCombo(gradoNivel, null, "grado y nivel");
//		setCombo(aula, null, "aula");
//		stopLoadingButton('botonFA');
//	}
//	pintarAlfabeto($('#tabTodos'));
//	$("#breadCrumbEst").css("display", "none");
}
function getGradoNivel(year, sede, gradoNivel, aula){
	var valorSede = $("#"+sede).val();
	var valorYear = $("#"+year).val();
	if(valorSede.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_alumno/getGradoNivel',
			data    : {idsede : valorSede,
					   year   : valorYear},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(gradoNivel, data.comboGradoNivel, "grado y nivel");
			setCombo(aula, null, "aula");
			stopLoadingButton('botonFA');
		});
	}
}
function getGradoNivelBySedeFiltro(year, sede, gradoNivel, aula){
	cons_letra       = null;
	pintarAlfabeto($('#tabTodos'));
	addLoadingButton('botonFA');
	$("#loading_cards").css("display","none");
	var nombreFiltro     = $("#searchMagic").val();
	var valorSede = $("#"+sede).val();
	var valorYear = $("#"+year).val();
	nombreYear       = valorYear.length != 0       ? $("#yearFiltro").find("option:selected").text() : '-';
	nombreSede       = valorSede.length != 0       ? $("#sedeFiltro").find("option:selected").text() : '-';
	nombreNivelGrado = '-';
	nombreAula       = '-';
	$("#breadYear").text(nombreYear);
	$("#breadSede").text(nombreSede);
	$("#breadGradoNivel").text(nombreNivelGrado);
	$("#breadAula").text(nombreAula);
//	if(valorSede.length != 0){
	Pace.restart();
	Pace.track(function() {
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_alumno/getGradoNivelBySede',
			data    : {idsede : valorSede,
					   year   : valorYear,
					   nombre : nombreFiltro,
					   letra  : cons_letra},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(gradoNivel, data.comboGradoNivel, "grado y nivel");
			setCombo(aula, null, "aula");
			stopLoadingButton('botonFA');

			result = data.tablaAlumnos;
			if(result.length != 0){
				$("#cont_alumnos_pincipal").html(result);
				componentHandler.upgradeAllRegistered();
				$("#cont_alumnos_pincipal").highlight(nombreFiltro);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "block");
				$("#breadCrumbEst").css("display", "block");
			} else {
				$("#cont_alumnos_pincipal").html(null);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "block");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "none");
				$("#breadCrumbEst").css("display", "none");
			}
			scroll           = 1;
			cons_tipo_filtro = 2;
			cons_scroll      = 1;
		});
	});
//	}
//	else {
//		addLoadingButton('botonFA');
//		setCombo(gradoNivel, null, "grado y nivel");
//		setCombo(aula, null, "Aula");
//		stopLoadingButton('botonFA');
//	}
//	pintarAlfabeto($('#tabTodos'));
//	$("#breadCrumbEst").css("display", "none");
//	$("#cont_alumnos_pincipal").html(null);
//	$(".mdl-layout__tab-bar-container").css("display", "none");
//	$("#cont_search_empty").css("display", "block");
//	$("#cont_search_not_found").css("display", "none");
//	$("#cont_search_not_found_letter").css("display", "none");
}

function getAulasByNivelGradoFiltro(year, sede, nivelGrado, aula){
	cons_letra       = null;
	pintarAlfabeto($('#tabTodos'));
	addLoadingButton('botonFA');
	var nombreFiltro     = $("#searchMagic").val();
	var valorSede       = $("#"+sede).val();
    var valorGradoNivel = $("#"+nivelGrado).val();
    var valorYear       = $("#"+year).val();

	nombreYear       = valorYear.length != 0       ? $("#yearFiltro").find("option:selected").text() : '-';
	nombreSede       = valorSede.length != 0       ? $("#sedeFiltro").find("option:selected").text() : '-';
	nombreNivelGrado = valorGradoNivel.length != 0 ? $("#nivelGradoFiltro").find("option:selected").text() : '-';
	nombreAula       = '-';

	$("#breadYear").text(nombreYear);
	$("#breadSede").text(nombreSede);
	$("#breadGradoNivel").text(nombreNivelGrado);
	$("#breadAula").text(nombreAula);
//    if(valorGradoNivel.length != 0){
	Pace.restart();
	Pace.track(function() {
    	$.ajax({
    		type    : 'POST',
    		'url'   : 'c_alumno/getAulasByGrado',
    		data    : {idsede  	    : valorSede,
    		           idgradonivel : valorGradoNivel,
					   year         : valorYear,
					   nombre       : nombreFiltro,
					   letra        : cons_letra},
    		'async' : true
    	}).done(function(data){
    		data = JSON.parse(data);
    		setCombo(aula, data.comboAulas, "aula");
    		stopLoadingButton('botonFA');

			result = data.tablaAlumnos;
			if(result.length != 0){
				$("#cont_alumnos_pincipal").html(result);
				componentHandler.upgradeAllRegistered();
				$("#cont_alumnos_pincipal").highlight(nombreFiltro);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "block");
				$("#breadCrumbEst").css("display", "block");
			} else {
				$("#cont_alumnos_pincipal").html(null);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "block");
				$("#cont_search_not_found_letter").css("display", "none");
				$(".mdl-layout__tab-bar-container").css("display", "none");
				$("#breadCrumbEst").css("display", "none");
			}
			scroll           = 1;
			cons_tipo_filtro = 2;
			cons_scroll      = 1;
    	});
	});
//    }else{
//    	setCombo(aula, null, "aula");
//		stopLoadingButton('botonFA');
//    }
//    pintarAlfabeto($('#tabTodos'));
//	$("#breadCrumbEst").css("display", "none");
//	$("#cont_alumnos_pincipal").html(null);
//	$(".mdl-layout__tab-bar-container").css("display", "none");
//	$("#cont_search_empty").css("display", "block");
//	$("#cont_search_not_found").css("display", "none");
//	$("#cont_search_not_found_letter").css("display", "none");
}

function getAlumnosByAlfabeto(letra, element){
	$("#loading_cards").css("display","none");
	var nombreFiltro     = $("#searchMagic").val();
	var valorYear        = $("#yearFiltro").val();
	var valorSede        = $("#sedeFiltro").val();
	var valorGradoNivel  = $("#nivelGradoFiltro").val();
	var valorAula        = $("#aulaFiltro").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data    : {nombre     : nombreFiltro,
				       year       : valorYear,
				       sede       : valorSede,
				       gradonivel : valorGradoNivel,
				       aula       : valorAula,
				       letra      : letra},
			url : 'c_alumno/getAlumnosByAlfabeto',
			async : true,
			type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			if(result.length != 0){
				$("#cont_alumnos_pincipal").html(result);
				componentHandler.upgradeAllRegistered();
				$("#cont_alumnos_pincipal").highlight(nombreFiltro);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "none");
				$("#breadCrumbEst").css("display", "block");
			} else {
				$("#cont_alumnos_pincipal").html(null);
				$("#cont_search_empty").css("display", "none");
				$("#cont_search_not_found").css("display", "none");
				$("#cont_search_not_found_letter").css("display", "block");
				$("#breadCrumbEst").css("display", "none");
			}
			scroll           = 1;
			cons_tipo_filtro = 2;
			cons_letra       = letra;
			cons_scroll      = 1;
		});
	});
}

function goToCreateAlumno(){
	/*
	$.ajax({
		url : 'c_alumno/goToCreateAlumno',
		async : false,
		type : 'POST'
	}).done(function(data) {
		setearCombo('yearFiltro',null);
		setearCombo('sedeFiltro',null);
		setearCombo('nivelGradoFiltro',null);
		setearCombo('aulaFiltro',null);
		window.location.href = 'c_detalle_alumno';
	});*/
}

function goToViewAula(idaula){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_aula/goToViewAula',
			data    : {idaula : idaula},
			'async' : true
		}).done(function(data){
			window.location.href = 'c_detalle_aula';
		});
	});
}

function goToEditAlumno(idAlumno){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data :{idalumno : idAlumno},
			url : 'c_alumno/goToEditAlumno',
			async : true,
			type : 'POST'
		}).done(function(data) {
			window.location.href = 'c_detalle_alumno';
		});
	});
}

function goToViewAlumno(idAlumno){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data :{idalumno : idAlumno},
			url : 'c_alumno/goToViewAlumno',
			async : true,
			type : 'POST'
		}).done(function(data) {
			window.location.href = 'c_detalle_alumno';
		});
	});
}

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
				setCombo("selectSedeDestino", null, "Sede");
				$("#comboSedeTraslado").css("display", "none");
				$("#contMotivoTraslado").css("display", "block");
			}else if(data.tipo == 1){//INTERSEDE
				setCombo("selectSedeDestino", data.sedes, "Sede");
				$("#comboSedeTraslado").css("display", "block");
//				$("#comboAulaTraslado").css("display", "none");
				$("#contMotivoTraslado").css("display", "block");
//				$("#modalSubirPaquete").find(".mdl-card__title-text").html("Traslado Intersedes");
//				modal("modalSubirPaquete");
			}
		});
	}else{
		$("#comboSedeTraslado").css("display", "none");
//		$("#comboAulaTraslado").css("display", "none");
		$("#contMotivoTraslado").css("display", "none");
	}
}

cons_card_estud = null;
function abrirModalConfirmDeleteAlumno(idAlumno, nombre, eleme){
	cons_id_alumno = idAlumno;
	cons_card_estud = $(eleme).parent().parent().parent().parent();
	$("#titleEliminar").html("&#191;Deseas eliminar el estudiante "+nombre+" ?")
	abrirCerrarModal("modalConfirmDeleteAlumno");
}

function eliminarEstudiante(){
	$.ajax({
		data  :{idalumno : cons_id_alumno},
		url   : 'c_alumno/eliminarEstudiante',
		async : true,
		type  : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0){
			abrirCerrarModal("modalConfirmDeleteAlumno");
			$(cons_card_estud).remove();
			mostrarNotificacion('success', data.msj , null);
		}else{
			mostrarNotificacion('warning', data.msj , null);
		}
	});
}

function abrirModalCompromisos(idestudiante){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/getDeudasByEstudiante",
			data: {idpostulante  : idestudiante},
	        type: 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
			    $("#calendarCompromisos").html(data.table);
			    $('#tb_compromisoCalendarAlu').bootstrapTable({});
				modal('modalCompromisosEstudiante');
			} else if(data.error == 1) {
			    $("#cont_compromiso").html(null);
			}
		});
	});	
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

function cambiarEstadoAlumno(){
	Pace.restart();
	addLoadingButton('botonAE');
	Pace.track(function() {
		var retiro = 0;
		if(isChecked($('#retirado'))){
			retiro = 1;
		}
		$.ajax({
			url : "c_alumno/cambiarEstadoEstudiante",
			data: {idalumno  : cons_id_alumno,
				   retiro    : retiro},
	        async: true,
	        type: 'POST'
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$(cons_card_estud).replaceWith(data.alumno);
				componentHandler.upgradeAllRegistered();
			}
			modal("modalConfirmDesabilitarAlumno");
			stopLoadingButton('botonAE');
			mostrarNotificacion('success', data.msj , null);
		});
	});	
}

function abrirModalConfirmDeclaracionJurada(alumno, nombre, element){
	$("#titleDeclaracionJurada").text("Deseas confirmar que recibiste la declaracion jurada de "+nombre+"?");
	$("#msjDecJurada").html("Recuerda: Al confirmar a este estudiante, su apoderado podr&aacute; continuar con el proceso de ratificaci&oacute;n.");
	cons_card_estud = $(element).parent().parent().parent().parent();
	cons_id_alumno = alumno;
	if(cons_id_alumno != null){
		$.ajax({
			url : "c_alumno/abrirModalConfirmDeclaracionJurada",
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
			url : "c_alumno/confirmarDeclaracion",
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
/*

//-----------------Alumno-------------------
function abrirModalRegistrarAlumno(){
	$('#modalAgregarPersona').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

var idPersonaEdit = null;
//-------------Familiar-------------------
var cons_codFamilia = null;
function getGradosByNivelOnly(idnivel, idgrado){
	var valorNivel = $("#"+idnivel).val();
	if(valorNivel != null && valorNivel.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_alumno/getGradosByNivelAll',
			data    : {idnivel : valorNivel},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			setCombo(idgrado, data.comboGrados, "Grado");
		});
	}else{
		setCombo(idgrado, null, "Grado");
	}
}

function getNivelesBySedeFiltro(sede, nivel, grado, aula){
	//CAPTURA EL VALOR DEL COMBO SELECCIONADO
	var valorSede = $("#"+sede).val();
	if(valorSede != null && valorSede.length != 0){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_alumno/getNivelesBySede',
			data    : {idsede : valorSede},
			'async' : false
		}).done(function(data){
			//EL DATA ES EL ARRAY QUE ESTAMOS ENVIANDO DESDE EL CONTROLADOR
			data = JSON.parse(data);
			//CAPTURAS EL VALOR CON data.KEY
			setCombo(nivel, data.comboNiveles, "Nivel");
			setCombo(grado, null, "Grado");
			setCombo(aula, null, "Aula");
		});
	} else {
		setCombo(nivel, null, "Nivel");
		setCombo(grado, null, "Grado");
		setCombo(aula, null, "Aula");
	}
}

function getGradosByNivelFiltro(sede, nivelGrado, aula){
	var valorSede  = $("#"+sede).val();
    var valorNivel = $("#"+nivel).val();
        
    if(valorNivel != null && valorNivel.length != 0){
    	$.ajax({
    		type    : 'POST',
    		'url'   : ' c_alumno/getGradosByNivel',
    		data    : {idnivel : valorNivel,
    			       idsede : valorSede},
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
*/