var scroll            = 0;
var mult              = 2;
var idcontactollamada = null;
var cons_codGrupo     = null;
var cons_classGrupo   = null;
var cons_contacto     = null;
var procesoScroll     = null;
var cantidadScroll    = 0;
var estadoScroll      = 0;
var	max_grupo = 0;

function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	initButtonLoad('botonEF','botonGL','btnInvitar','botonAHF','botonFiltro','botonS');
	initMaskInputs('fechaInicioFiltro','fechaFinFiltro');
	$(":input").inputmask();
	initButtonCalendarDays('fechaInicioFiltro');
	initButtonCalendarDays('fechaFinFiltro')
	initMaskInputs('fechaInicioFiltro','fechaFinFiltro'); 
}

function buscarPersona(){
	Pace.restart();
	Pace.track(function(){
		var textoBusqueda = $("#searchMagic").val();
		setearCombo("selectYear",null);
		setearCombo("selectSede",null);
		setearCombo("selectGradoNivel",null);
		setCombo("selectSede",null,'sede');
		setCombo("selectGradoNivel",null,'grado y nivel');
		$("#tabsProspecto").css("display", "none");
		$("#tabsEvaluacion").css("display", "none");
		$(".mdl-layout__tab").removeClass("is-active"); 
		scroll=0;
		mult = 2;
		cantidadScroll = 0;
		cardsFamiliaHtmlNull();
		if($.trim(textoBusqueda).length >= 3 || $.trim(textoBusqueda).length == 0){
			$.ajax({
				type: 'POST',
				url: "c_contactos/buscarPersona",
		        data: {textoBusqueda : textoBusqueda}
		  	})
		  	.done(function(data) {
		  		data = JSON.parse(data);
				if(data.error == 0) {
					result = data.cardsFamilia;
					if(result.length != 0){
						scroll = 3;
						pintarTabPanel($('#tab-1'));
						$(".liprospectos").removeClass("active");
//						$("#liPros").addClass("active");
						$(".tab-pane").removeClass("active");
						$("#tab-contactados").addClass("active");
	  					$("#cont_cards_familia1").css("display", "block");
	  					$("#cont_cards_familia1").html(result);
	  					//MARCAR
	  					$("#cont_cards_familia1").highlight(textoBusqueda);
	  					max_grupo = data.max_cod_grupo;
	  				}else{
						scroll=0;
						pintarTabPanel($('#tab-1'));
						$("#tab-contactados").addClass("active");
						$(".liprospectos").removeClass("active");
						cardsFamiliaHtmlNull();
	  					$("#cont_cards_familia1").css("display", "none");
	  					$("#cont_search_empty1").css("display", "block");
	  				}
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					componentHandler.upgradeAllRegistered();
					cantidadScroll = data.count;
				} else {
					scroll=0;
					msj('error', 'Contacte con la persona a cargo', 'Error');
				}
		  	});
		}else{
			scroll=0;
			$("#cont_cards_familia1").html(null);
  			$("#cont_btn_ver_mas").css("display", "none");
			$("#cont_search_empty1").css("display", "none");
			$(".mdl-layout__tab").removeClass("is-active");
			cardsFamiliaHtmlNull();
		}
	});
}

function pintarTab(element){
	$(".mdl-layout__tab").removeClass("is-active");
	$(element).addClass("is-active");
}
function pintarTabPanel(element){
	$(".mdl-layout__tab-panel").removeClass("is-active");
	$(element).addClass("is-active");
}

function onScrollEvent(element){
	$('.mfb-component--br').css("opacity","1");
	Pace.restart();
	Pace.track(function() {
		if(scroll != 0){
			if($(element).scrollTop() + $(element).innerHeight()+1>=$(element)[0].scrollHeight){
				var valorYear       = $("#selectYear option:selected").val();
				var valorSede       = $("#selectSede option:selected").val();
				var valorGradoNivel = $("#selectGradoNivel option:selected").val();
				var textoBusqueda    = $("#searchMagic").val();
				$.ajax({
		  			type    : 'POST',
		  			'url'   : 'c_contactos/onScrollGetFamilias',
		  			data    : {countScroll   : cantidadScroll,
		  				       mult          : mult,
		  				       scroll        : scroll,
		  				       textobusqueda : textoBusqueda,
		  				       estado        : estadoScroll,
		  				       year          : valorYear,
		  				       sede          : valorSede,
		  				       gradoNivel    : valorGradoNivel,
		  				       maxgrupo      : max_grupo,
		  				       tipoproceso   : procesoScroll}
		  		}).done(function(data){
		  			data = JSON.parse(data);
		  			if(data.error == 0){
		  				if((data.tipoproceso).length != 0){
		  					$("#cont_cards_familia"+data.tipoproceso).append(data.cardsFamilia);
		  				} else {
		  					$("#cont_cards_familia"+data.estado).append(data.cardsFamilia);
		  				}
						$('.fixed-table-toolbar').addClass('mdl-card__menu');
			  			componentHandler.upgradeAllRegistered();
			  			cantidadScroll = data.count;
			  			$("#cont_btn_ver_mas").css("display", "none");
			  			$("#cont_cards_familia"+data.estado).highlight(textoBusqueda);
			  			mult++;
	  					max_grupo = data.max_cod_grupo;
		  			}
		  		});
			}
		}
	});
}

function verMasFamilias(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
  			type    : 'POST',
  			'url'   : 'c_contactos/verMasFamilias',
  			data    : {count 	: cantidadScroll,
  				       mult  	: mult,
  				       maxgrupo : max_grupo}
  		}).done(function(data){
  			data = JSON.parse(data);
  			if(data.error == 0){
  				$("#cont_cards_familia1").append(data.cardsFamilia);
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
	  			componentHandler.upgradeAllRegistered();
	  			cantidadScroll= data.count;
	  			scroll = 1;
	  			mult++;
	  			$("#cont_btn_ver_mas").css("display", "none");
				max_grupo = data.max_cod_grupo;
  			}
  		});
	});
}

function getFamiliasByEstado(estadocontacto, element,proceso){
	Pace.restart();
	Pace.track(function() {
		scroll         = 0;
		mult           = 2;
		cantidadScroll = 0;
		var textoBusqueda    = $("#searchMagic").val();
		var valorYear        = $("#selectYear option:selected").val();
		var valorSede        = $("#selectSede option:selected").val();
		var valorGradoNivel  = $("#selectGradoNivel option:selected").val();
		cardsFamiliaHtmlNull();
   	 	$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/getFamiliasByEstado',
			data    : {textobusqueda   : textoBusqueda,
				       estadocontacto  : estadocontacto,
				       year            : valorYear,
				       sede            : valorSede,
				       gradoNivel      : valorGradoNivel,
				       maxgrupo        : max_grupo,
				       tipoproceso     : proceso}
		}).done(function(data){
			data = JSON.parse(data);
  			if(data.error == 0){
  				result = data.cardsFamilia;
  				if(result.length != 0){
  					$("#cont_search_empty"+ data.cont).css("display", "none");
  					$("#cont_cards_familia"+data.cont).css("display", "block");
  					$("#cont_cards_familia"+data.cont).html(result);
  					$("#cont_cards_familia"+data.cont).highlight(textoBusqueda);
  	  				cantidadScroll = data.count;
  	  				estadoScroll   = estadocontacto;
  	  				procesoScroll  = proceso;
  	  				scroll = 2;
  					max_grupo = data.max_cod_grupo;
  				} else {
					scroll = 0;
  					$("#cont_search_empty"+data.cont).css("display", "block");
  					$("#cont_cards_familia"+data.cont).css("display", "none");
  				}
  				$('.fixed-table-toolbar').addClass('mdl-card__menu');
  				componentHandler.upgradeAllRegistered();
  			} else if(data.error == 1) {
				scroll = 0;
  				$("#"+data.cont).html(null);
  				$("#cont_cards_familia"+data.cont).html(null);
  				if($.trim(textoBusqueda).length < 3 && $.trim(textoBusqueda).length != 0){
  	  				$("#cont_search_empty"+data.cont).css("display", "none");
  				} else {
  	  				$("#cont_search_empty"+data.cont).css("display", "block");
  				}
  			}
		});
	});
}

function cardsFamiliaHtmlNull(){
	max_grupo = 0;
	$("#cont_cards_familia1").html(null);
	$("#cont_cards_familia2").html(null);
	$("#cont_cards_familia3").html(null);
	$("#cont_cards_familia4").html(null);
	$("#cont_cards_familia5").html(null);
	$("#cont_cards_familia6").html(null);
	$("#cont_cards_familia7").html(null);
	$("#cont_cards_familia8").html(null);
	$("#cont_cards_familia9").html(null);
	$("#cont_cards_familia10").html(null);
	$("#cont_search_empty1").css("display", "none");
	$("#cont_search_empty2").css("display", "none");
	$("#cont_search_empty3").css("display", "none");
	$("#cont_search_empty4").css("display", "none");
	$("#cont_search_empty5").css("display", "none");
	$("#cont_search_empty6").css("display", "none");
	$("#cont_search_empty7").css("display", "none");
	$("#cont_search_empty8").css("display", "none");
	$("#cont_search_empty9").css("display", "none");
	$("#cont_search_empty10").css("display", "none");
	$("#cont_btn_ver_mas").html(null);
	$("#cont_btn_ver_mas").css("display", "none");
}

function getFamiliasEvaluacion(estadocontacto, element){
	if($(element).attr("id") == "tabEval"){
		$("#tabsEvaluacion").css("display", "block");
	}
	pintarTab($(element));
	$(".licontactos").removeClass("active");
	$("#li1").addClass("active");
	$(".tab-pane").removeClass("active");
	$("#tab-proceso").addClass("active");
	getFamiliasByEstado(estadocontacto,element);
}

function getFamiliasProspecto(estadocontacto, element){
	if($(element).attr("id") == "tabProspectos"){
		$("#tabsProspecto").css("display", "block");
	}
	pintarTab($(element));
	$(".liprospectos").removeClass("active");
	$("#liPros").addClass("active");
	$(".tab-pane").removeClass("active");
	$("#tab-contactados").addClass("active");
	getFamiliasByEstado(estadocontacto,element);
}

function getFamiliasPorMatricular(estadocontacto, element){
	if($(element).attr("id") == "tabPorMatricular"){
		$("#tabsPorMatricular").css("display", "block");
	}
	pintarTab($(element));
	$(".liPorMatricular").removeClass("active");
	$("#liPago").addClass("active");
	$(".tab-pane").removeClass("active");
	$("#tab-cuota").addClass("active");
	getFamiliasByEstado(estadocontacto,element);
}

function abrirModalLlamadas(idcontacto,telefono,correo){
	$.ajax({
		type    : 'POST',
		'url'   : 'c_contactos/abrirModalLlamadas',
		data    : {idcontacto : idcontacto,
			       telefono   : telefono,
			       correo     : correo}
	}).done(function(data){
		data = JSON.parse(data);
		$(".tabLlamadas").removeClass("is-active");
		$("#tabLlamadas").addClass("is-active");
		$(".panelLlamadas").removeClass("is-active");
		$("#llamadas").addClass("is-active");
		
		setearCombo("selectEvento",null);
		setearCombo("selectTipoLlamada",null);
		setearInput("observacion",null);
		$("#cont_table_llamadas").html(data.tablaLlamadas);
		$("#tbLlamadas").bootstrapTable({});
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
		componentHandler.upgradeAllRegistered();
		idcontactollamada = idcontacto;
		modal("modalLlamadas");

		$("#numTelefono").text(data.telefono);
		$("#correoSeguimiento").text(data.correo);
	});
}

function agregarLlamada(opcion){
	addLoadingButton('botonS');
	var idevento      = $("#selectEvento").val();
	var tipoLlamada = $("#selectTipoLlamada").val();
	var observacion = $("#observacion").val();
	$.ajax({
		type    : 'POST',
		'url'   : 'c_contactos/agregarLlamada',
		data    : {idevento      : idevento,
			       tipoLlamada   : tipoLlamada,
			       observacion   : observacion,
			       idcontacto    : idcontactollamada,
			       opcion        : opcion},
		'async' : true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			setearCombo("selectEvento",null);
			setearCombo("selectTipoLlamada",null);
			setearInput("observacion",null);
			$("#cont_table_llamadas").html(data.tablaLlamadas);
			$("#tbLlamadas").bootstrapTable({});
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			componentHandler.upgradeAllRegistered();
			msj('success', data.msj, null);
		} else {
			msj('success', data.msj, null);
		}
		stopLoadingButton('botonS');
	});
}

function getFamiliasByYear(year,sede,gradoNivel){
	Pace.restart();
	addLoadingButton('botonFiltro');
	Pace.track(function(){
		pintarTabPanel($('#tab-1'));
		pintarTab($('#tabProspectos'));
		$(".liprospectos").removeClass("active");
		$("#liPros").addClass("active");
		$(".tab-pane").removeClass("active");
		$("#tab-contactados").addClass("active");
		
		var valorYear = $("#"+year+" option:selected").val();

		setearInput("searchMagic",null);
		setCombo(sede,null,'sede');
		setCombo(gradoNivel,null,'grado y nivel');
		
		scroll=0;
		mult = 2;
		cantidadScroll = 0;
		cardsFamiliaHtmlNull();
	    $("#tabsProspecto").css("display", "block");
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/getFamiliasByYear',
			data    : {year : valorYear},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				result = data.cardsFamilia;
				if(valorYear.length == 0){
					$("#cont_cards_familia1").css("display", "block");
					$("#cont_cards_familia1").html(result);
					$("#tabsProspecto").css("display", "none");
					$(".mdl-layout__tab").removeClass("is-active");
  					max_grupo = data.max_cod_grupo;
					scroll = 1;
					stopLoadingButton('botonFiltro');

				} else if(result.length != 0){
					$("#cont_cards_familia1").css("display", "block");
					$("#cont_cards_familia1").html(result);
  					max_grupo = data.max_cod_grupo;
					scroll = 2;
					stopLoadingButton('botonFiltro');
				}
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
				
				if(data.comboSedes != null){
					setCombo(sede,data.comboSedes,'sede');
					stopLoadingButton('botonFiltro');
				}
				if(data.comboGradoNivel != null){
					setCombo(gradoNivel,data.comboGradoNivel,'grado y nivel');
					stopLoadingButton('botonFiltro');
				}
				stopLoadingButton('botonFiltro');
			} else {
				scroll = 0;
				if(data.comboSedes != null){
					setCombo(sede,data.comboSedes,'sede');
					stopLoadingButton('botonFiltro');
				}
				if(data.comboGradoNivel != null){
					setCombo(gradoNivel,data.comboGradoNivel,'grado y nivel');
					stopLoadingButton('botonFiltro');
				}
				$("#cont_search_empty1").css("display", "block");
				$("#cont_cards_familia1").css("display", "none");
				stopLoadingButton('botonFiltro');
			}   
		});
	});
}

function getFamiliasBySedeInteres(year,sede,gradoNivel){
	Pace.restart();
	addLoadingButton('botonFiltro');
	Pace.track(function(){
		pintarTabPanel($('#tab-1'));
		pintarTab($('#tabProspectos'));
		$(".liprospectos").removeClass("active");
		$("#liPros").addClass("active");
		$(".tab-pane").removeClass("active");
		$("#tab-contactados").addClass("active");
		var valorYear = $("#"+year+" option:selected").val();
		var valorSede = $("#"+sede+" option:selected").val();
		setCombo(gradoNivel,null,'Nivel y Grado');
		cardsFamiliaHtmlNull();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/getFamiliasBySedeInteres',
			data    : {year : valorYear,
				       sede : valorSede},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				result = data.cardsFamilia;
				if(result.length != 0){
					scroll = 2;
  					max_grupo = data.max_cod_grupo;
					$("#cont_cards_familia1").css("display", "block");
					$("#cont_cards_familia1").html(result);
					stopLoadingButton('botonFiltro');
				}
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();

				if(data.comboGradoNivel != null){
					setCombo(gradoNivel,data.comboGradoNivel,'grado y nivel');
					stopLoadingButton('botonFiltro');
				}
			} else {
				if(data.comboGradoNivel != null){
					setCombo(gradoNivel,data.comboGradoNivel,'grado y nivel');
					stopLoadingButton('botonFiltro');
				}
				$("#cont_search_empty1").css("display", "block");
				$("#cont_cards_familia1").css("display", "none");
				stopLoadingButton('botonFiltro');
			}
		});
	});
}

function getFamiliasByGradoNivel(year,sede,gradoNivel){
	Pace.restart();
	addLoadingButton('botonFiltro');
	Pace.track(function(){
		scroll         = 0;
		mult           = 2;
		cantidadScroll = 0;
		pintarTabPanel($('#tab-1'));
		pintarTab($('#tabProspectos'));
		$(".liprospectos").removeClass("active");
		$("#liPros").addClass("active");
		$(".tab-pane").removeClass("active");
		$("#tab-contactados").addClass("active");
		var valorYear       = $("#"+year+" option:selected").val();
		var valorSede       = $("#"+sede+" option:selected").val();
		var valorGradoNivel = $("#"+gradoNivel+" option:selected").val();
		cardsFamiliaHtmlNull();
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/getFamiliasByGradoNivel',
			data    : {year       : valorYear,
				       sede       : valorSede,
				       gradoNivel : valorGradoNivel},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				result = data.cardsFamilia;
				if(result.length != 0){
					scroll = 2;
  					max_grupo = data.max_cod_grupo;
					$("#cont_cards_familia1").css("display", "block");
					$("#cont_cards_familia1").html(result);
					stopLoadingButton('botonFiltro');
				}
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
			} else {
				$("#cont_search_empty1").css("display", "block");
				$("#cont_cards_familia1").css("display", "none");
				stopLoadingButton('botonFiltro');
			}
		});
	});
}

function abrirModalInvitarAlEvento(elem){
	var idContacto = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	var codGrupo   = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-cod-grupo");
	setearCombo('selectEventoInvitar',null);
	setearCombo('selectHorario',null);
	setearCombo('selectOption',null);
	$("#cont_horario").css("display", "none");
	$("#cont_option").css("display", "none");
	$('#btnInvitar').attr("disabled", true);

	$("#cont_tb_familiares_invitar").html(null);

	if(codGrupo != null && idContacto != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/eventosInvitadosContacto',
			data    : {codgrupo : codGrupo}
		}).done(function(data){
			data = JSON.parse(data);
			cons_codGrupo   = codGrupo;
			cons_contacto   = idContacto;
			modal("modalInvitarContacto");

			setCombo("selectEventoInvitar", data.opcion, "Evento", null);
			
		});
	}
}

function abrirModalAgregarPostulante(elem){
	var idContacto = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	
	if(idContacto != null){
		cons_contacto = idContacto;
		modal("modalAgregarPostulante");
	}
}

function invitarContacto(){
	arrayContactos      = [];
	arrayOpciones       = [];
	arrayHoras          = [];
	arrayObservaciones  = [];
	addLoadingButton('btnInvitar');
	Pace.restart();
	Pace.track(function() {
		$('.cmb_invitar').each(function(i, obj) {
		    if(($("#"+$(this).find('select').attr("id")).val()).length > 0){
		    	arrayContactos.push($(this).find('select').attr("data-id-contacto"));
		    	valorHoras = $("#"+$(this).find('select').attr("data-id-select1")).val();
		    	if($("#"+$(this).find('select').attr("data-id-select1")).length && valorHoras.length != 0){
		    		arrayHoras.push(valorHoras); 
		    	} else {
		    		arrayHoras.push(null); 
		    	}
		    	valorOpciones = $("#"+$(this).find('select').attr("id")).val();
		    	if($("#"+$(this).find('select').attr("id")).length && valorOpciones.length != 0){
		    		arrayOpciones.push(valorOpciones); 
		    	}
		    	valorObservaciones = $("#"+$(this).find('select').attr("data-id-observ")).val();
		    	if($("#"+$(this).find('select').attr("data-id-observ")).length && valorObservaciones.length != 0){
		    		arrayObservaciones.push(valorObservaciones); 
		    	} else {
		    		arrayObservaciones.push(null); 
		    	}
		    }
		});		
		if(arrayOpciones.length > 0){
			var idevento      = $("#selectEventoInvitar option:selected").val();
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/invitarContacto',
				data    : {idevento       : idevento,
			               contactos      : arrayContactos,
			               horas          : arrayHoras,
			               opciones       : arrayOpciones,
			               observaciones  : arrayObservaciones,
			               codgrupo       : cons_codGrupo},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if (data.error ==0) {
					stopLoadingButton('btnInvitar');
					msj('success', data.msj, null);
					if(data.cantidadInvitados > 0){
						$("#mdl-inscritos-cont-"+data.codgrupo).addClass("mdl-invitados");
					}			
				} else {
					msj('success', data.msj, null);
				}
			});
		}
	});
}

function llenarFormulario(){
	//window.location.href = "registro";
	var win = window.open("registro", '_blank');
	win.focus();
}

function goToViewContacto(elem){
	var idDetalleContacto = $(elem).parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	
	if(idDetalleContacto != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/goToViewContacto',
			data    : {idcontacto : idDetalleContacto},
			'async' : true
		}).done(function(data){
			//window.location.href = 'c_detalle_contactos';
			var win = window.open("c_detalle_contactos", '_blank');
			win.focus();
		});
	}
}

function goToEditContacto(elem){
	var idDetalleContacto = $(elem).parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	
	if(idDetalleContacto != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/goToEditContacto',
			data    : {idcontacto : idDetalleContacto},
			'async' : true
		}).done(function(data){
			//window.location.href = 'c_detalle_contactos';
			var win = window.open("c_detalle_contactos", '_blank');
			win.focus();
		});
	}
}


function abrirModalAgregarHermanoFamilia(elem){
	var codGrupo = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-cod-grupo");
	var classGroup = $(elem).parent().parent().parent().parent().attr("id");
	
	setearInput("apellidoPatPostulanteCrear", null);
	setearInput("apellidoMatPostulanteCrear", null);
	setearInput("nombrePostulanteCrear", null);
	setearInput("fecNaciPostulanteCrear", null);
	setearInput("numeroDocPostulanteCrear", null);
	
	setearCombo("selectGradoNivelPostulanteCrear", null);
	setearCombo("selectSedePostulanteCrear", null);
	setearCombo("selectSexoPostulanteCrear", null);
	setearCombo("selectColegioProcPostulanteCrear", null);
	setearCombo("selectTipoDocPostulanteCrear", null);
	
	disableEnableCombo("selectSedePostulanteCrear", true);
	disableEnableInput("numeroDocPostulanteCrear", true);

	if(codGrupo != null){
		cons_codGrupo   = codGrupo;
		cons_classGrupo = classGroup;
		modal("modalAgregarPostulante");
	}
}

function agregarHermanoFamilia(){
	addLoadingButton('botonAHF');
	var apellidoPaterno = $("#apellidoPatPostulanteCrear").val();
	var apellidoMaterno = $("#apellidoMatPostulanteCrear").val();
	var nombrePostulante = $("#nombrePostulanteCrear").val();
	var fecNaci = $("#fecNaciPostulanteCrear").val();
	var numeroDoc = $("#numeroDocPostulanteCrear").val();
	var gradoNivel = $("#selectGradoNivelPostulanteCrear option:selected").val();
	var sede = $("#selectSedePostulanteCrear option:selected").val();
	var sexo = $("#selectSexoPostulanteCrear option:selected").val();
	var colegio = $("#selectColegioProcPostulanteCrear option:selected").val();
	var tipoDoc = $("#selectTipoDocPostulanteCrear option:selected").val();
	
	$.ajax({
		type    : 'POST',
		'url'   : 'c_contactos/agregarHermanoFamilia',
		data    : {apepaterno : apellidoPaterno,
			       apematerno : apellidoMaterno,
			       nombre     : nombrePostulante,
			       fechanac   : fecNaci,
			       numerodoc  : numeroDoc,
			       gradonivel : gradoNivel,
			       sede       : sede,
			       sexo       : sexo,
			       colegio    : colegio,
			       tipodoc    : tipoDoc,
			       codgrupo   : cons_codGrupo},
		'async' : true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#"+cons_classGrupo).find(".mdl-card__title").find(".nav-pills").append(data.li);
			$("#"+cons_classGrupo).find(".mdl-card__supporting-text").find(".tab-content").append(data.postulante);
			msj('success', data.msj, null);
			modal("modalAgregarPostulante");
			stopLoadingButton('botonAHF');
		} else if(data.error == 1) {
			stopLoadingButton('botonAHF');
			msj('success', data.msj, null);
		}
	});
}

function getSedesByNivel(nivel,sede){
	var valorNivel = $('#'+nivel+' option:selected').val();
	if(valorNivel.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/getSedesByNivel',
			data    : {valorNivel : valorNivel}
		}).done(function(data){
			data = JSON.parse(data);
			$('#'+sede).attr("disabled", false);
			setCombo(sede, data.comboSedes,"Sede de inter&eacute;s");
			$('.selectButton').selectpicker('refresh');
		});
	}else{
		setCombo(sede, null,"Sede de inter&eacute;s");
		disableEnableCombo(sede, true);
	}
}

function habilitarCampo(element1, element2){
	var val1 = $('#'+element1+' option:selected').val();
	setearInput(element2,null);
	if(val1.length != 0){
		$('#'+element2).attr("disabled", false);
		$('.divInput').removeClass('is-disabled');
	} else {
		$('#'+element2).attr("disabled", true);
	}
}

function changeMaxlength(tipoDoc,nroDoc){
	var valorTipo = $('#'+tipoDoc+' option:selected').val();
	if(valorTipo == 1){
		$("#"+nroDoc).attr('maxlength','12');
	}else if (valorTipo == 2){
		$("#"+nroDoc).attr('maxlength','8');
	}
}

function abrirModalenviarCorreoPariente(idContacto){
	idcontactomensaje = idContacto;
	setearInput("asuntoCorreoEnviar", null);
	setearInput("mensajeCorreoEnviar", null);
	modal("modalEnviarMensajePariente");
}

function enviarCorreoPariente(){
	asunto  = $("#asuntoCorreoEnviar").val();
	mensaje = $("#mensajeCorreoEnviar").val();
	if($.trim(asunto).length != 0 && $.trim(mensaje).length != 0){
		Pace.restart();
		Pace.track(function(){
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/enviarMensajeContacto',
				data    : {contacto : idcontactomensaje,
						   asunto   : asunto,
						   mensaje  : mensaje},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				msj('success', data.msj, null);
				modal("modalEnviarMensajePariente");
			});
		});
	}else{
		msj('success', "Ingrese todos los campos", null);
	}
}

function descargarCorreos(){
	var textoBusqueda    = $("#searchMagic").val();
	var valorYear        = $("#selectYear option:selected").val();
	var valorSede        = $("#selectSede option:selected").val();
	var valorGradoNivel  = $("#selectGradoNivel option:selected").val();
 	$.ajax({
		type    : 'POST',
		'url'   : 'c_contactos/descargarCorreo',
		data    : {textobusqueda   : textoBusqueda,
			       estadocontacto  : estadoScroll,
			       year            : valorYear,
			       sede            : valorSede,
			       gradoNivel      : valorGradoNivel}
	}).done(function(data){
		
	});
}

function selectByTipoEvento(element1){
	var idevento = $("#"+element1).val();
	setearCombo('selectHorario',null);
	setearCombo('selectOption',null);
	if(idevento.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/selectByTipoEvento',
			data    : {idevento     : idevento,
				       idcontacto : cons_contacto}
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#cont_tb_familiares_invitar").html(data.tabla);
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				    $('.selectButton').selectpicker('mobile');
				} else {
					$('.selectButton').selectpicker();
				}
				componentHandler.upgradeAllRegistered();
			} else {
				msj('success', data.msj, null);
			}
		});
	} else {
		$("#cont_tb_familiares_invitar").html(null);
	}
}

var postulante_confirm_datos = null;
function habilitarSelect(num, elem, flg){
	if(flg == 1){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/validateDatosCompletos',
				data    : {contacto   : $(elem).attr("data-id-contacto")}
			}).done(function(data){
				data = JSON.parse(data);
				if(data.opc == 1){
					setearCombo($(elem).attr("id"), null);
					postulante_confirm_datos = $(elem).attr("data-id-contacto");
					setearInput("apellidoPatPostulanteConfirmar", data.apePaterno);
					setearInput("apellidoMatPostulanteConfirmar", data.apeMaterno);
					setearInput("nombresPostulanteConfirmar", data.nombres);
					setearCombo("selectGradoNivelPostulanteConfirmar", data.gradoNivel);
					modal("modalConfirmarDatosPostulantes");
					flg = 3;
				}
			});
		});
	}
	if(flg != 3){
		if(($("#"+$(elem).attr("id")).val()).length == 0){
			disableEnableCombo("observacion"+num, true);
		} else if(($("#"+$(elem).attr("id")).val()).length != 0){
			$('#cont_observ'+num).removeClass('is-disabled');
			disableEnableCombo("observacion"+num, false);
		}
		if($("#"+$(elem).attr("id")).val() == 2 || $("#"+$(elem).attr("id")).val() == 3 || ($("#"+$(elem).attr("id")).val()).length == 0){
			disableEnableCombo("selectHoraCitadaInvitado"+num, true);
		}else{
			disableEnableCombo("selectHoraCitadaInvitado"+num, false);
		}
		setearInput("observacion"+num, null);
		setearCombo("selectHoraCitadaInvitado"+num, null);
		habilitarButton();
	}
}

function guardarDatosContacto(){
	Pace.restart();
	Pace.track(function() {
		apePaterno = $("#apellidoPatPostulanteConfirmar").val();
		apeMaterno = $("#apellidoMatPostulanteConfirmar").val();
		nombre     = $("#nombresPostulanteConfirmar").val();
		gradoNivel = $("#selectGradoNivelPostulanteConfirmar").val();
		if(apePaterno.length == 0 || apeMaterno.length == 0 || nombre.length == 0 || gradoNivel.length == 0){
			msj("success", "Ingrese todos los campos", null);
			return;
		}
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/confirmarDatosPostulantes',
			data    : {contacto   : postulante_confirm_datos,
				       apepaterno : apePaterno,
				       apematerno : apeMaterno,
				       nombre     : nombre,
				       gradonivel : gradoNivel}
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				modal("modalConfirmarDatosPostulantes");
			}
			msj("success", data.msj, null);
		});
	});
}

function habilitarButton(){
	arrayContactos   = [];
	arrayOpciones    = [];
	arrayHoras       = [];
	Pace.restart();
	Pace.track(function() {
		$('.cmb_invitar').each(function(i, obj) {
		    if(($("#"+$(this).find('select').attr("id")).val()).length > 0){
		    	arrayContactos.push($(this).find('select').attr("data-id-contacto"));
		    	valorHoras = $("#"+$(this).find('select').attr("data-id-select1")).val();
		    	if($("#"+$(this).find('select').attr("data-id-select1")).length && valorHoras.length != 0){
		    		arrayHoras.push(valorHoras); 
		    	} else {
		    		arrayHoras.push(null); 
		    	}
		    	valorOpciones = $("#"+$(this).find('select').attr("id")).val();
		    	if($("#"+$(this).find('select').attr("id")).val().length && valorOpciones.length != 0){
		    		arrayOpciones.push(valorOpciones); 
		    	}
		    }
		});
		
		if(arrayOpciones.length > 0){
			var idevento      = $("#selectEventoInvitar option:selected").val();
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/habilitarButton',
				data    : {idevento   : idevento,
			               contactos  : arrayContactos,
			               horas      : arrayHoras,
			               opciones   : arrayOpciones}
			}).done(function(data){
				data = JSON.parse(data);
				if (data.error == 0) {
					$('#btnInvitar').attr("disabled", false);
				} else {
					$('#btnInvitar').attr("disabled", true);
				}
			});
		}else{
			$('#btnInvitar').attr("disabled", true);
		}
	});
}

function abrirModalAgregarLlamadaInvitar(element, idcontacto){
	idcontactollamada = idcontacto;
	modal(element);
}

function guardarLlamada(opcion){
	addLoadingButton('botonGL');
	var tipoLlamada = $("#selectTipoLlamada2 option:selected").val();
	var idevento    = $("#selectEventoInvitar option:selected").val();
	$.ajax({
		type    : 'POST',
		'url'   : 'c_contactos/agregarLlamada',
		data    : {idevento      : idevento,
			       tipoLlamada   : tipoLlamada,
			       idcontacto    : idcontactollamada,
			       opcion        : opcion},
		'async' : true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			modal('modalGuardarLlamada');
			stopLoadingButton('botonGL');
			msj('success', data.msj, null);
		} else {
			stopLoadingButton('botonGL');
			msj('success', data.msj, null);
		}
	});
}

function addRemoveFavourite(){
	
}

function toogleFavorite(id) {
	var favorite = $('#'+id);
	favorite.toggleClass('mdl-disabled');
}

function filtrarPorFechas(){
	fechaInicio = $("#fechaInicioFiltro").val();
	fechaFin = $("#fechaFinFiltro").val();
	var textoBusqueda = $("#searchMagic").val();
	setearCombo("selectYear",null);
	setearCombo("selectSede",null);
	setearCombo("selectGradoNivel",null);
	$("#tabsProspecto").css("display", "none");
	$("#tabsEvaluacion").css("display", "none");
	$(".mdl-layout__tab").removeClass("is-active");
	cardsFamiliaHtmlNull();
	if(fechaInicio.length == 10 && fechaFin.length == 10){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/buscarContactosPorFechas',
				data    : {fechainicio : fechaInicio,
					       fechafin    : fechaFin}
			}).done(function(data){
				data = JSON.parse(data);
				result = data.cardsFamilia;
				if(result.length != 0){
					$(".liprospectos").removeClass("active");
					$("#liPros").addClass("active");
					$(".tab-pane").removeClass("active");
					$("#tab-contactados").addClass("active");
  					$("#cont_cards_familia1").css("display", "block");
  					$("#cont_cards_familia1").html(result);
				}
				componentHandler.upgradeAllRegistered();
			});
		});
	}
}

function getFamiliasByCanalComunicacion(){
	canalCom = $("#selectCanalComFiltro").val();
	fechaInicio = $("#fechaInicioFiltro").val();
	fechaFin = $("#fechaFinFiltro").val();
	setearCombo("selectYear",null);
	setearCombo("selectSede",null);
	setearCombo("selectGradoNivel",null);
	$("#tabsProspecto").css("display", "none");
	$("#tabsEvaluacion").css("display", "none");
	$(".mdl-layout__tab").removeClass("is-active");
	cardsFamiliaHtmlNull();
	if(canalCom.length != 0){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/buscarContactosPorCanalComunicacion',
				data    : {canalCom    : canalCom,
					       fechainicio : fechaInicio,
					       fechafin    : fechaFin}
			}).done(function(data){
				data = JSON.parse(data);
				result = data.cardsFamilia;
				if(result.length != 0){
					$(".liprospectos").removeClass("active");
					$("#liPros").addClass("active");
					$(".tab-pane").removeClass("active");
					$("#tab-contactados").addClass("active");
  					$("#cont_cards_familia1").css("display", "block");
  					$("#cont_cards_familia1").html(result);
				}
				componentHandler.upgradeAllRegistered();
			});
		});
	}else{
		$("#tabProspectos").trigger( "click" );
	}
}

function abrirModalconfirmEliminarFamilia(elem){
	var codGrupo = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-cod-grupo");
	var classGroup = $(elem).parent().parent().parent().parent().attr("id");
	if(codGrupo != null){
		cons_codGrupo   = codGrupo;
		cons_classGrupo = classGroup;
		modal("modalConfirmDeleteFam");
	}
}

function elimiarFamilia(){
	addLoadingButton('botonEF');
	if(cons_codGrupo != null){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_contactos/eliminarFamilia',
				data    : {grupo : cons_codGrupo},
				'async' : true
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					stopLoadingButton('botonEF');
					modal("modalConfirmDeleteFam");
					$("#"+cons_classGrupo).addClass('mdl-card__delete');
					setTimeout(function() {
						$("#"+cons_classGrupo).remove();
					}, 1250);					
				}
				msj('success', data.msj, null);
				stopLoadingButton('botonEF');

			});
		});
	}
}

function getFamiliasVerano(estadocontacto,element,proceso){
	$("#tabsVerano").css("display", "block");
	pintarTab($(element));
	$(".lisummer").removeClass("active");
	$("#lisport").addClass("active");
	$(".tab-pane").removeClass("active");
	$("#tab-sport").addClass("active");
//  traer familias que eligieron sport summer
	getFamiliasByEstado(estadocontacto,element,proceso);
}

function abrirModalInscribirVerano(elem){
	var idContacto = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-id-contacto");
	var codGrupo   = $(elem).parent().parent().parent().parent().find(".mdl-card__title").find("li[class='active']").attr("data-cod-grupo");
	setearCombo('selectYearVerano',null);
	$('#btnInscribir').attr("disabled", true);
	if(idContacto != null && codGrupo != null){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/verificarPostulante',
			data    : {codgrupo   : codGrupo,
					   idcontacto : idContacto}
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				cons_codGrupo   = codGrupo;
				cons_contacto   = idContacto;
				modal("modalInscripcionVerano");
			} else {
				msj('success', data.msj, null);
			}
		});
	}
}

function goToInscripcionVerano(){
	var valorYear      = $("#selectYearVerano option:selected").val();
	if(cons_codGrupo != null && cons_contacto != null && valorYear.length != 0){
		$.ajax({
			type    : 'POST',
			'url'   : 'c_contactos/goToInscripcionVerano',
			data    : {codgrupo   : cons_codGrupo,
					   idcontacto : cons_contacto,
					   valoryear  : valorYear}
		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$('#btnInscribir').attr("disabled", true);
				setearCombo('selectYearVerano',null);
//				modal("modalInscripcionVerano");
				msj('success', data.msj, null);
//				window.location.href = data.url;
				var win = window.open(data.url, '_blank');
				win.focus();
				
			} else {
				msj('success', data.msj, null);
			}
		});
	}
	
}

function habilitarConfirmarcionVerano(btn){
	var valorYear      = $("#selectYearVerano option:selected").val();
	if(valorYear.length != 0){
		$('#'+btn).attr("disabled", false);
	} else {
		$('#'+btn).attr("disabled", true);
	}
}