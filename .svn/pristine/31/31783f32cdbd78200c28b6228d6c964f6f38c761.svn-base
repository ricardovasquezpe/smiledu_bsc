var count_scroll = 1;
var idCondicion = null;
var idCondicionPromo = null;
var idAlumnoQuitar = null;
var idAlumnoAsignar = null;

function init(){
	initButtonLoad('botonABD','botonQAB');
}

function openModalQuitarBeca($id){
	idAlumnoQuitar = $id;
	abrirCerrarModal('modalQuitarBeca');
}

function openModalAsignarBeca($id){
    idAlumnoAsignar = $id;
	setearCombo("selectBeca", null);
	abrirCerrarModal('modalAsignarBeca');
}

function asignarBeca(){
	addLoadingButton('botonABD');
	var buscador    = $('#buscarEstudiantes').val().trim();
	var tipoBeca    = $('#selectBeca option:selected').val();
	var yearBeca    = $('#selectYear option:selected').val();
	if(tipoBeca.length == 0){
	    stopLoadingButton('botonABD');
		return mostrarNotificacion('warning', 'Seleccione Tipo de Beca');
	}
	if(yearBeca.length == 0){
	    stopLoadingButton('botonABD');
		return mostrarNotificacion('warning', 'Seleccione A&ntilde;o');
	}
	Pace.restart();
	Pace.track(function() {
	$.ajax({
		    data  : {idAlumnoAsignar : idAlumnoAsignar,
			         searchMagic     : buscador,
			         tipoBeca        : tipoBeca,
			         yearBeca        : yearBeca},
		    url   : 'c_becas/asignarBeca',
		    type  : 'POST',
		    async : true
		}).done(function(data) {
			data = JSON.parse(data);
  			if (data.error == 1) {
  				stopLoadingButton('botonABD');
  				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('warning', data.msj);
				$('#contTbEstudiantes').html(data.tableEstudiantes);
				$('#tableBecasP').html(data.tableBecas);
				$('#tb_estudiantes').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
			    abrirCerrarModal('modalAsignarBeca');
			    stopLoadingButton('botonABD');
			}
		});
	});
}

function quitarBeca(){
	addLoadingButton('botonQAB');
    var searchMagic   = $('#buscarEstudiantes').val().trim();
	$.ajax({
		data  : {idAlumnoQuitar : idAlumnoAsignar,
			     searchMagic    : searchMagic},
		url   : 'c_becas/quitarBeca',
		type  : 'POST',
		async : true
		}).done(function(data) {			
			data = JSON.parse(data);
  			if (data.error == 1) {
  				stopLoadingButton('botonQAB');
  				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('warning', data.msj);
				$('#contTbEstudiantes').html(data.tableEstudiantes);
				$('#tb_estudiantes').bootstrapTable({ });
				componentHandler.upgradeAllRegistered();
				stopLoadingButton('botonQAB');
				abrirCerrarModal('modalQuitarBeca');
			}
		});
}

function openModaleditarBeca(id) {
	idCondicion=id;
	$.ajax({
			data  : {id : id},
			url   : 'c_becas/mostrarDetalle',
			type  : 'POST',
			async : false
		}).done(function(data) {
			data = JSON.parse(data);
			setearInput("porcentajeBecaEdit", data.porcentaje_beca);
			setearInput("descEdit", data.desc_condicion);
			abrirCerrarModal('modalEditarBeca');
		});
}

function openModaleditarPromocion(id) {
	idCondicionPromo=id;
	$.ajax({
			data  : {idCondicionPromo : idCondicionPromo},
			url   : 'c_becas/promocionDetalle',
			type  : 'POST',
			async : false
		}).done(function(data) {
			data = JSON.parse(data);
			setearInput("descEditP", data.desc_promo);
			setearInput("cantCEditP", data.cant_cuotas);
			setearInput("porcentajeEditP", data.porcentaje_descuento);
			abrirCerrarModal('modalEditarPromocion');
		});
}

function actualizarBeca() {
	addLoadingButton('botonAB');
	var desc      = $('#descEdit').val();
	desc          = desc.trim();

	var procentaje     = $('#porcentajeBecaEdit').val();
	procentaje = procentaje.trim();
	if(desc.trim( ) == '' || desc.length == 0 || /^\s+$/.test(desc)){
		return mostrarNotificacion('warning', 'Ingrese una Descripcion');
	}
	if(procentaje.trim() == '' || procentaje.length == 0 || /^\s+$/.test(procentaje)){
		return mostrarNotificacion('warning', 'Ingrese el porcentaje de beca');
	}
	if(procentaje > 100){
		return mostrarNotificacion('warning', 'El procentaje no debe ser mayor a 100%');
	}
	if(procentaje < 0){
		return mostrarNotificacion('warning', 'El procentaje debe ser un numero positivo');
	}
	if(procentaje == 0){
		return mostrarNotificacion('warning', 'El procentaje no puede ser 0%');
	}
	if( isNaN(procentaje) ) {
		return mostrarNotificacion('warning', 'Solo Numeros en procentaje');
	}
	
	$.ajax({
			data : {
				desc           : desc,
				procentaje     : procentaje,
				idCondicion    : idCondicion
			},
			url   : 'c_becas/updateBeca',
			type  : 'POST',
			async : true
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				stopLoadingButton('botonAB');
				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('warning', data.msj);
				$('#tableBecasP').html(data.tableBecas);
				$('#tb_becas').bootstrapTable({});
				initSearchTable();
				componentHandler.upgradeAllRegistered();
				tableEventsUpgradeMdlComponentsMDL('tb_becas');
				abrirCerrarModal('modalEditarBeca');
			}
		});
}

function actualizarPromocion() {
	addLoadingButton('botonAP');
	var desc      = $('#descEditP').val();
	desc          = desc.trim();
	var cantidad  = $('#cantCEditP').val();
	cantidad = cantidad.trim();
	var procentaje     = $('#porcentajeEditP').val();
	procentaje = procentaje.trim();

	if(desc.trim( ) == '' || desc.length == 0 || /^\s+$/.test(desc)){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'Ingrese una Descripcion');
	}
	if(cantidad.trim( ) == '' || cantidad.length == 0 || /^\s+$/.test(cantidad)){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'Ingrese la cantidad de cuotas');
	}
	if( isNaN(cantidad) ) {
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'Solo Numeros en la cantidad de cuotas');
	}
	if(procentaje.trim() == '' || procentaje.length == 0 || /^\s+$/.test(procentaje)){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'Ingrese el porcentaje de beca');
	}
	if(procentaje > 100){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'El procentaje no debe ser mayor a 100%');
	}
	if(procentaje < 0){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'El procentaje debe ser un numero positivo');
	}
	if(procentaje == 0){
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'El procentaje no puede ser 0%');
	}
	if( isNaN(procentaje) ) {
		stopLoadingButton('botonAP');
		return mostrarNotificacion('warning', 'Solo Numeros en procentaje');
	}
	
	$.ajax({
		data : { desc             : desc,
				 procentaje       : procentaje,
				 idCondicionPromo : idCondicionPromo,
				 cantidad         : cantidad },
		url   : 'c_becas/updatePromocion',
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			stopLoadingButton('botonAP');
			mostrarNotificacion('warning', data.msj);
		} else {
			mostrarNotificacion('succes', data.msj);
			$('#tablePromociones').html(data.tablePromociones);
			$('#tb_promociones').bootstrapTable({});
			initSearchTable();
			componentHandler.upgradeAllRegistered();
			tableEventsUpgradeMdlComponentsMDL('tb_promociones');
			abrirCerrarModal('modalEditarPromocion');
			stopLoadingButton('botonAP');
		}
	});
}

function openModalcrearBeca() {
	setearInput("porcentajeB", null);
	setearInput("desc", null);
	setearInput("descP", null);
	setearInput("cantC", null);
	setearInput("porcentajeP", null);
	abrirCerrarModal('modalCrearBeca');
}

function getAllAlumnosByFiltroB(){
	count_scroll    = 1;
	var idSede      =  $('#selectSede option:selected').val();
    var searchMagic = $('#searchMagic').val().trim();
    var idSede      =  $('#selectSedeB option:selected').val();
	var idGrado     =  $('#selectGradoB option:selected').val();
	var idNivel     =  $('#selectNivelB option:selected').val();
	var idAula      = $('#selectAulaB option:selected').val();
    if(searchMagic == null){
    } else{
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {
						 searchMagic   : searchMagic,
						 idGrado 	   : idGrado,
			    		 idNivel 	   : idNivel,
			    	     idSede  	   : idSede,
			    	     idAula  	   : idAula,
						 count 		   : count_scroll},
				url   : 'c_becas/getAlumnosByFiltro',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#cardsAlumnos').show();
				$('#cardsAlumnos').html(data.cards);
				componentHandler.upgradeAllRegistered();
			});
		});
	}
}

function getNivelesBySedeB() {
	count_scroll    = 1;
	var idSede      =  $('#selectSedeB option:selected').val();
	var sedeText    = $('#selectSedeB option:selected').text();
	var searchMagic = $('#searchMagic').val().trim();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	        data: { idSede 	      : idSede,
				    searchMagic   : searchMagic,
					count 		  : count_scroll },
					url : "c_becas/comboSedesNivel",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#filtroCompromisos").show();
			    setCombo('selectNivelB', data.optNivel, 'Nivel',null);
			    setCombo('selectGradoB', null, 'Grado',null);
			    setCombo('selectAulaB', null, 'Aula',null);
			    $('#laelSede').html(sedeText);
			    $('#laelNivel').html('-');
			    $('#laelGrado').html('-');
			    $('#laelAula').html('-');
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelSede').addClass('active');
			}else if(data.error == 1) {
				setCombo('selectNivelB', data.optNivel, 'Nivel',null);
				setCombo('selectGradoB', null, 'Grado',null);
			    setCombo('selectAulaB', null, 'Aula',null);
			}
			$('#cardsAlumnos').show();
			$('#cardsAlumnos').html(data.cards);
			componentHandler.upgradeAllRegistered();
		});
	});
}

function getGradosByNivelB() {
	count_scroll = 1;
	var idSede =  $('#selectSedeB option:selected').val();
	var idNivel =  $('#selectNivelB option:selected').val();
	var sedeText = $('#selectSedeB option:selected').text();
	var nivelText = $('#selectNivelB option:selected').text();
	var searchMagic   = $('#searchMagic').val().trim();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	        data: { idNivel 	  : idNivel,
	        	    idSede  	  : idSede,
					searchMagic   : searchMagic,
					count         : count_scroll},
			url : "c_becas/getComboGradoByNivel_Ctrl",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#filtroCompromisos").show();
			   setCombo('selectGradoB', data.optGrado, 'Grado',null);
			   setCombo('selectAulaB', null, 'Aula',null);
			   $('#laelSede').html(sedeText);
			   $('#laelNivel').html(nivelText);
			    $('#laelGrado').html('-');
			    $('#laelAula').html('-');
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelNivel').addClass('active');
			}else if(data.error == 1){
				setCombo('selectGrado', null, 'Grado',null);
			    setCombo('selectAula', null, 'Aula',null);
			}
			$('#cardsAlumnos').show();
			$('#cardsAlumnos').html(data.cards);
			componentHandler.upgradeAllRegistered();
		});
	});
}

function getAulasByNivelSedeB() {
	count_scroll = 1;
	var idSede =  $('#selectSedeB option:selected').val();
	var idGrado =  $('#selectGradoB option:selected').val();
	var idNivel =  $('#selectNivelB option:selected').val();
	var sedeText = $('#selectSedeB option:selected').text();
	var nivelText = $('#selectNivelB option:selected').text();
	var gradoText = $('#selectGradoB option:selected').text();
	var searchMagic   = $('#searchMagic').val().trim();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	        data: { idGrado : idGrado,
	        		idNivel : idNivel,
	        	    idSede  : idSede,
					searchMagic   : searchMagic,
					 count 		   : count_scroll
	        	    },
	        url  : "c_becas/comboAulasByGradoUtils",
	        async: true,
	        type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#filtroCompromisos").show();
			    setCombo('selectAulaB', data.optAula, 'Aula',null);
			    $('#laelSede').html(sedeText);
				$('#laelNivel').html(nivelText);
				$('#laelGrado').html(gradoText);
			    $('#laelAula').html('-');
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelGrado').addClass('active');
			}else if(data.error == 1) {
				setCombo('selectAulaB', null, 'Aula',null);
			}
			$('#cardsAlumnos').show();
			$('#cardsAlumnos').html(data.cards);
			componentHandler.upgradeAllRegistered();
		});
	});
}

function getAlumnosByAulaB() {
	count_scroll = 1;
	var idSede =  $('#selectSedeB option:selected').val();
	var idGrado =  $('#selectGradoB option:selected').val();
	var idNivel =  $('#selectNivelB option:selected').val();
	var idAula = $('#selectAulaB option:selected').val();
	var sedeText = $('#selectSedeB option:selected').text();
	var nivelText = $('#selectNivelB option:selected').text();
	var gradoText = $('#selectGradoB option:selected').text();
	var aulaText = $('#selectAulaB option:selected').text();
	var searchMagic   = $('#searchMagic').val().trim();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
	        data: {  idGrado 	   : idGrado,
		    		 idNivel 	   : idNivel,
		    	     idSede  	   : idSede,
		    	     idAula  	   : idAula,
					 searchMagic   : searchMagic,
					 count 		   : count_scroll},
			url : "c_becas/getAlumnosFromAula",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			$("#filtroCompromisos").show();
			$('#cardsAlumnos').show();
			$('#cardsAlumnos').html(data.cards);
			$('#laelSede').html(sedeText);
			$('#laelNivel').html(nivelText);
			$('#laelGrado').html(gradoText);
			$('#laelAula').html(aulaText);
		    $('.breadcrumb').find('li').removeClass('active');
		    $('#laelAula').addClass('active');
			componentHandler.upgradeAllRegistered();
			}else if(data.error == 1) {
			}
		});
	});
}

function registrarBeca(){
	addLoadingButton('botonRB');
	var desc       = $('#desc').val();
	desc           = desc.trim();
	
	var procentaje = $('#porcentajeB').val();
	procentaje = procentaje.trim();
	
	if(desc.trim( ) == '' || desc.length == 0 || /^\s+$/.test(desc)){
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'Ingrese una Descripcion');
	}
	
	if(procentaje.trim() == '' || procentaje.length == 0 || /^\s+$/.test(procentaje)){
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'Ingrese el porcentaje de beca');
	}
	if(procentaje > 100){
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'El procentaje no debe ser mayor a 100%');
	}
	if(procentaje < 0){
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'El procentaje debe ser un numero positivo');
	}
	if(procentaje == 0){
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'El procentaje no puede ser 0%');
	}
	if( isNaN(procentaje) ) {
		stopLoadingButton('botonRB');
		return mostrarNotificacion('warning', 'Solo Numeros en procentaje');
		}
	$.ajax({
			data : {
				desc           : desc,
				procentaje     : procentaje
			},
			url   : 'c_becas/guardarBeca',
			type  : 'POST',
			async : true
		  }).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				stopLoadingButton('botonRB');
				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('succes', data.msj);
				$('#becasPromo').css('display', 'block');
				$('#tableBecasP').html(data.tableBecas);
				$('#tb_becas').bootstrapTable({});
				initSearchTable();
				componentHandler.upgradeAllRegistered();
				tableEventsUpgradeMdlComponentsMDL('tb_becas');
				abrirCerrarModal('modalCrearBeca');
				setCombo('selectBeca', data.optTipoBeca , ' Beca');
				stopLoadingButton('botonRB');
			}
		 });
}

function onScrollEvent(element){
	if($(element).scrollTop() + $(element).innerHeight()>=$(element)[0].scrollHeight){
		var idSede =  $('#selectSedeB option:selected').val();
		var idGrado =  $('#selectGradoB option:selected').val();
		var idNivel =  $('#selectNivelB option:selected').val();
		var idAula = $('#selectAulaB option:selected').val();
		var searchMagic   = $('#searchMagic').val().trim();
		Pace.restart();
		Pace.track(function() {
			$.ajax({	
				data: {  idGrado     : idGrado,
		    		     idNivel 	 : idNivel,
		    	         idSede  	 : idSede,
		    	         idAula  	 : idAula,
					     searchMagic : searchMagic,
					     count 		 : count_scroll},
	  			url     : 'c_becas/onScrollGetAlumnos',
	  			type    : 'POST',
	  			async   : true
	  		}).done(function(data){
	  			data = JSON.parse(data);
	  			if (data.error == 1) {
				} else 
				$('#cardsAlumnos').show();
	  			$("#cardsAlumnos").append(data.cards);
	  			componentHandler.upgradeAllRegistered();
			    count_scroll = count_scroll + 1;
	  		});
		});
	}
}

function becasByAlumnos(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({	
			data: {  idGrado     : idGrado,
	    		     idNivel 	 : idNivel,
	    	         idSede  	 : idSede,
	    	         idAula  	 : idAula,
				     searchMagic : searchMagic,
				     count 		 : count_scroll},
  			url     : 'c_becas/onScrollGetAlumnos',
  			type    : 'POST',
  			async   : true
  		}).done(function(data){
  			data = JSON.parse(data);
  			if (data.error == 1) {
			} else {
			$('#cardsAlumnos').show();
  			$("#cardsAlumnos").append(data.cards);
  			componentHandler.upgradeAllRegistered();
		    count_scroll = count_scroll + 1;
			}
  		});
	});
}

function registrarPromocion(){
	addLoadingButton('botonRP');
	var desc       = $('#descP').val(); 
	desc           = desc.trim(); 
	var cantidad   = $('#cantC').val();
	cantidad       = cantidad.trim();
	var procentaje = $('#porcentajeP').val();
	procentaje     = procentaje.trim();
	procentaje = procentaje.trim();
	if(desc.trim( ) == '' || desc.length == 0 || /^\s+$/.test(desc)){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'Ingrese una Descripcion');
	}
	
	if(cantidad.trim( ) == '' || cantidad.length == 0 || /^\s+$/.test(cantidad)){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'Ingrese la cantidad de cuotas');
	}
	if( isNaN(cantidad) ) {
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'La cantidad de cuotas debe un numero');
		}
	if(procentaje.trim() == '' || procentaje.length == 0 || /^\s+$/.test(procentaje)){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'Ingrese el porcentaje de la promocion');
	}
	if(procentaje > 100){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'El procentaje no debe ser mayor a 100%');
	}
	if(procentaje < 0){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'El procentaje debe ser un numero positivo');
	}
	if(procentaje == 0){
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'El procentaje no puede ser 0%');
	}
	if( isNaN(procentaje) ) {
		stopLoadingButton('botonRP');
		return mostrarNotificacion('warning', 'Solo Numeros en procentaje');
		}
	$.ajax({
			data : {
				cantidad           : cantidad,
				procentaje     : procentaje,
				desc           :desc
			},
			url   : 'c_becas/guardarPromocion',
			type  : 'POST',
			async : true
		  }).done(function(data) {
			data = JSON.parse(data);
			if (data.error == 1) {
				stopLoadingButton('botonRP');
				mostrarNotificacion('warning', data.msj);
			} else {
				mostrarNotificacion('succes', data.msj);
				$('#becasPromo').css('display', 'block');
				$('#tablePromociones').html(data.tablePromociones);
				$('#tb_promociones').bootstrapTable({});
				initSearchTable();
				componentHandler.upgradeAllRegistered();
				tableEventsUpgradeMdlComponentsMDL('tb_promociones');
				abrirCerrarModal('modalCrearBeca');
				stopLoadingButton('botonRP');
			}
		 });
}

function openModalAsignarBeca(){
	$('#contTbEstudiantes').html(null);
	$('#buscarEstudiantes').val(null);
	$('#buscarEstudiantes').parent().removeClass('is-dirty');
	modal('modalAsignarBecas');
}

function activeDesactiveSearch(){
	var nameEstudiante = $("#buscarEstudiantes").val();
	if($.trim(nameEstudiante).length >= 3){
		$('#btnBuscar').attr('disabled', false);
		$('#btnBuscar').addClass('mdl-button--raised');
	} else {
		$("#cont_tabla_alumnos_sinaula").html(null);
		$('#btnBuscar').attr('disabled', true);
		$('#btnBuscar').removeClass('mdl-button--raised');
	}
}

function getAlumnosByName(){
	var nameEstudiante = $("#buscarEstudiantes").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {nameEstudiante : nameEstudiante},
			url   : 'c_becas/getEstudiantesByFiltro',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contTbEstudiantes').html(data.tableEstudiantes);
			$('#tb_estudiantes').bootstrapTable({ });
			componentHandler.upgradeAllRegistered();
		});
	});
}

function asignarQuitarBeca(cb){
	var checked = cb.is(':checked');
	idAlumnoAsignar = cb.attr('attr-persona');
	if(checked == true){
		cb.prop('checked',false);
		cb.parent().removeClass('is-checked');
		modal('modalAsignarBeca');
		$("#selectBeca option:first").attr('selected','selected');
		$("#selectBeca").selectpicker('render');
		$("#selectYear option:first").attr('selected','selected');
		$("#selectYear").selectpicker('render');
	} else{
		cb.prop('checked',true);
		cb.parent().addClass('is-checked');
		modal('modalQuitarBeca');
	}
}