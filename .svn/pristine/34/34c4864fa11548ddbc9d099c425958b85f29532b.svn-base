var count_scroll = 1;

function init(){
	initButtonLoad('botonFI','botonFE','botonEP');
}

$('.mdl-layout__tab[href="#tab-1"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalIngresos" data-mfb-label="Filtrar Pensiones">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-2"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
											'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalEgresos" data-mfb-label="Filtrar Pensiones">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

function getAllAlumnosByFiltro(){
	count_scroll    = 1;
	var searchMagic = $('#searchInput').val().trim();
	var idSede 	    = $('#selectSede option:selected').val();
	var idGrado     = $('#selectGrado option:selected').val();
	var idNivel     = $('#selectNivel option:selected').val();
	var idAula      = $('#selectAula option:selected').val();
	
	var sedeText = (idSede == null || idSede == "") ? " " :  $('#selectSede option:selected').text();
	if((searchMagic == "" && searchMagic.length < 1) && (idGrado== null || idGrado == "") && (idNivel == null || idNivel == "") && (idAula == null || idAula == "")) {
		$('#cont_img_search_alum').css('display', 'block');
	} else {
		$('#cont_img_search_alum').css('display','none');
	}
	if((searchMagic != null && searchMagic.length < 1)){
	} else{
		addLoadingButton('botonFI');
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {idGrado      : idGrado,
			    		 idNivel      : idNivel,
			    	     idAula       : idAula,
						 searchMagic  : searchMagic,
						 count 		  : count_scroll,
						 idSede       : idSede},
				url   : 'c_movimientos/getAlumnosByFiltro',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 1) {
					$('main').removeAttr('onscroll');
				}else {
					$('main').attr('onscroll', 'onScrollEvent(this)');
				}
			    $('#laelSede').css('display','initial');
			    $('#laelSede').html(sedeText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#filtroMovimiento').removeAttr('style');
			    $('#laelSede').addClass('active');		
				$('#cont_img_search_alum').css('display','none');
				$('#cardsIngreso').html(data.cards);
				componentHandler.upgradeAllRegistered();
				stopLoadingButton('botonFI');
				scroll = 1;
			});
		});
	}
}

function getNivelesBySede() {
	count_scroll = 1;
	var idSede      = $('#selectSede option:selected').val();
	var idNivel 	= $('#selectNivel option:selected').val();
	var searchMagic = $('#searchInput').val().trim();
	var sedeText = (idSede == null || idSede == "") ? " " :  $('#selectSede option:selected').text();
	addLoadingButton('botonFI');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_movimientos/comboSedesNivel",
	        data: { idSede 	      : idSede,
	        	    searchMagic   : searchMagic,
					count 		  : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
//				if(idNivel == null || idNivel == "") {
//					$('.breadcrumb').css('display','none');
//					$('main').removeAttr('onscroll');
//				}else {
//					$('main').attr('onscroll', 'onScrollEvent(this)');
//				}
			    setCombo('selectNivel', data.optNivel, 'Nivel',null);
			    setCombo('selectGrado', null, 'Grado',null);
			    setCombo('selectAula', null, 'Aula',null);
//				$("#filtroMovimiento").show();
			    $('#laelSede').html(sedeText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelSede').addClass('active');			    
			    $('#laelNivel').css('display','none');
			    $('#laelGrado').css('display','none');
			    $('#laelAula').css('display','none');
			    $('#laelSede').css('display','initial');	
		    }else if(data.error == 1) {
			setCombo('selectNivel', data.optNivel, 'Nivel',null);
			setCombo('selectGrado', null, 'Grado',null);
		    setCombo('selectAula', null, 'Aula',null);
			}
			$('#cont_img_search_alum').css('display','none');
			$('#cardsIngreso').html(data.cards);
			stopLoadingButton('botonFI');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getGradosByNivel() {
	count_scroll = 1;
	var idSede 		= $('#selectSede option:selected').val();
	var idNivel 	= $('#selectNivel option:selected').val();
	var searchMagic = $('#searchInput').val().trim();
//	var sedeText = $('#selectSede option:selected').text();
//	var nivelText = $('#selectNivel option:selected').text();
	var sedeText  = (idSede  == null || idSede  == "") ? " " :  $('#selectSede option:selected').text();
	var nivelText = (idNivel == null || idNivel == "") ? " " :  $('#selectNivel option:selected').text();
	addLoadingButton('botonFI');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_movimientos/getComboGradoByNivel_Ctrl",
	        data: { idNivel 	: idNivel,
	        	    idSede  	: idSede,
					searchMagic : searchMagic,
					count       : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				if(idNivel == null || idNivel == "") {
					$('.breadcrumb').css('display','none');
					$('main').removeAttr('onscroll');
				}else {
					$('main').attr('onscroll', 'onScrollEvent(this)');
				}
			    setCombo('selectGrado', data.optGrado, 'grado',null);
			    setCombo('selectAula', null, 'aula',null);
			    $("#filtroMovimiento").show();
//			    $('#laelSede').html(sedeText);
			    $('#laelSede').css('display','initial');
			    $('#laelNivel').html(nivelText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelNivel').addClass('active');
			    $('#laelNivel').html(nivelText);
			    $('#laelNivel').addClass('active');
			    $('#laelGrado').css('display','none');
			    $('#laelAula').css('display','none');
			    $('#laelNivel').css('display','initial');
			}else if(data.error == 1){
			    setCombo('selectGrado', null, 'Grado',null);
			    setCombo('selectAula', null, 'Aula',null);
			}
			$('#cont_img_search_alum').css('display','none');
			$('#cardsIngreso').html(data.cards);
			stopLoadingButton('botonFI');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getAulasByNivelSede() {
	count_scroll = 1;
	var idSede =  $('#selectSede option:selected').val();
	var idGrado =  $('#selectGrado option:selected').val();
	var idNivel =  $('#selectNivel option:selected').val();
	var searchMagic   = $('#searchInput').val().trim();
	var sedeText  = (idSede  == null || idSede  == "") ? " " :  $('#selectSede option:selected').text();
	var nivelText = (idNivel == null || idNivel == "") ? " " :  $('#selectNivel option:selected').text();
	var gradoText = (idGrado == null || idGrado == "") ? " " :  $('#selectGrado option:selected').text();
	addLoadingButton('botonFI');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_movimientos/comboAulasByGradoUtils",
	        data: { idGrado 	: idGrado,
	        		idNivel 	: idNivel,
	        	    idSede  	: idSede,
					searchMagic : searchMagic,
					count 		: count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
			    setCombo('selectAula', data.optAula, 'aula',null);
			    $('#laelSede').html(sedeText);
			    $('#laelNivel').html(nivelText);
			    $('#laelGrado').html(gradoText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelGrado').addClass('active');
			    $('#laelGrado').html(gradoText);
			    $('#laelGrado').addClass('active');
				$('#laelAula').css('display','none');
				$('#laelGrado').css('display','initial');
			}else if(data.error == 1) {
				setCombo('selectAula', null, 'Aula',null);
			}
			$('#cont_img_search_alum').css('display','none');
			$('#cardsIngreso').html(data.cards);
			stopLoadingButton('botonFI');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getAlumnosByAula() {
	count_scroll    = 1;
	var idSede      = $('#selectSede option:selected').val();
	var idGrado     = $('#selectGrado option:selected').val();
	var idNivel     = $('#selectNivel option:selected').val();
	var idAula      = $('#selectAula option:selected').val();
	var searchMagic = $('#searchInput').val().trim();
	var sedeText    = (idSede  == null || idSede  == "") ? " " :  $('#selectSede option:selected').text();
	var nivelText   = (idNivel == null || idNivel == "") ? " " :  $('#selectNivel option:selected').text();
	var gradoText   = (idGrado == null || idGrado == "") ? " " :  $('#selectGrado option:selected').text();
	var aulaText    = (idAula  == null || idAula  == "") ? " " :  $('#selectAula option:selected').text();
	addLoadingButton('botonFI');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_movimientos/getAlumnosFromAula",
	        data: {  idGrado 	 : idGrado,
		    		 idNivel 	 : idNivel,
		    	     idSede  	 : idSede,
		    	     idAula  	 : idAula,
					 searchMagic : searchMagic,
					 count 		 : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#cont_img_search_alum').css('display','none');
			$('#cardsIngreso').html(data.cards);
		    $('#laelSede').html(sedeText);
		    $('#laelNivel').html(nivelText);
		    $('#laelGrado').html(gradoText);
		    $('#laelAula').html(aulaText);
		    $('.breadcrumb').find('li').removeClass('active');
		    $('#laelAula').addClass('active');
			componentHandler.upgradeAllRegistered();
		    $('#laelAula').html(aulaText);
		    $('#laelAula').addClass('active');
		    $('#laelAula').css('display','initial');
		    stopLoadingButton('botonFI');
		});
	});
}

function goToPagosAlumno(idPersona){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : 'c_movimientos/goToDetallePagoPersona',
	        data: {idPersona   : idPersona,
			       current_tab : current_tab},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			window.location.href = data.url;
		});
	});
}

function changeDataTarget(target){
	$('#main_button').attr('data-target',target);
	var text = (target == '#modalIngresos') ? 'Busca tus alumnos' : 'Busca a tus colaboradores';
	$('#lblSearchMagic').text(text);
	$('#searchInput').val(null);
	$('#searchInput').parent().removeClass('is-dirty');
	$("#searchInput").unbind("keyup");
	$('#searchInput').keyup(function(){
		if(target == '#modalIngresos'){
			current_tab = 'tab-1';
			getAllAlumnosByFiltro();
		} else{
			current_tab = 'tab-2';
			getColaboradoresByFiltro();
			$("#filtroEgresos").css("display","none");
		}
	});
}

function getColaboradoresByFiltro(){
	var area        = $('#selectArea option:selected').val();
	var searchMagic = $('#searchAula').val().trim();
	var areaText = (area == null || area == "") ? "" :  $('#selectArea option:selected').text();
	if(areaText == "" || areaText == null) {
		$("#filtroEgresos").hide();
	} else {
		$("#filtroEgresos").show();
	}
	if((searchMagic == null || searchMagic == "") && areaText == "") {
		$('#cont_img_search_col').css('display', 'block');
	}else {
		$('#cont_img_search_col').css('display', 'none');
	}
	if((searchMagic != null && searchMagic.length >= 1) || (area != null && area != "")){
		addLoadingButton('botonFE');
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {area        : area,
						 searchMagic : searchMagic},
				url   : 'c_movimientos/getAllColaboradores',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#cardsEgreso').html(data.cards);
				$('#laelArea').html(areaText);
				$('#laelArea').css('display','initial');
				stopLoadingButton('botonFE');
			});
		});
	}
}

function goToEgresosColaborador(persona){
	$.ajax({
		data  : {persona     : persona,
			     current_tab : current_tab},
		url   : 'c_movimientos/goToDetalleEgresosPersona',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		window.location.href = data;
	});
}

var scroll=0;
function onScrollEvent(element){
	if (scroll == 1){
		if($(element).scrollTop() + $(element).innerHeight()>=$(element)[0].scrollHeight){
			$("#loading_cards").css("display","block");
			var idSede  = $('#selectSede option:selected').val();
			var idGrado = $('#selectGrado option:selected').val();
			var idNivel = $('#selectNivel option:selected').val();
			var idAula  = $('#selectAula option:selected').val();
			var searchMagic = $('#searchInput').val().trim();
			Pace.restart();
			Pace.track(function() {
				$.ajax({	
		  			type    : 'POST',
		  			url     : 'c_movimientos/onScrollGetAlumnos',
		  			data    : {idGrado     : idGrado,
				    		   idNivel     : idNivel,
				    	       idSede      : idSede,
				    	       idAula  	   : idAula,
				    	       count   	   : count_scroll,
				    	       searchMagic : searchMagic},
		  			'async' : true
		  		}).done(function(data){
		  			data = JSON.parse(data);
		  			$("#cardsIngreso").append(data.cards);
		  			componentHandler.upgradeAllRegistered();
				    count_scroll = count_scroll + 1;
				    $("#loading_cards").css("display","none");
		  		});
			});
		}
	}
}

function activeDesactivesearchInput(){
	var namecont = $("#searchInput").val();
	if($.trim(namecont).length>=1){
		$('#btnBuscarLista').attr('disabled', false);
		$('#btnBuscarLista').addClass('mdl-button--raised');
	} else{
		$("#cardsIngreso").html(null);
		$('#btnBuscarLista').attr('disabled', true);
		$('#btnBuscarLista').removeClass('mdl-button--raised');
	}
}

function activeDesactivesearchAula(input,btn){
	var namecont = $("#"+input).val();
	if($.trim(namecont).length>=1){
		$('#'+btn).attr('disabled', false);
		$('#'+btn).addClass('mdl-button--raised');
	} else{
		$("#cardsEgreso").html(null);
		$('#'+btn).attr('disabled', true);
		$('#'+btn).removeClass('mdl-button--raised');
	}
}

function getProveedoresByFiltro(){
	var searchMagic = $('#searchProveedor').val().trim();
	if((searchMagic == null || searchMagic == "")) {
		$('#cont_img_search_col').css('display', 'block');
	}else {
		$('#cont_img_search_col').css('display', 'none');
	}
	if((searchMagic != null && searchMagic.length >= 1) ){
		addLoadingButton('botonEP');
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {searchMagic : searchMagic},
				url   : 'c_movimientos/getAllProveedores',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#cardsEgreso').html(data.cardProveedores);
				stopLoadingButton('botonEP');
			});
		});
	}
}

function goToEgresosProveedor(proveedor){
	$.ajax({
		data  : {proveedor : proveedor},
		url   : 'c_movimientos/goToDetalleEgresosProveedor',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		window.location.href = data;
	});
}
