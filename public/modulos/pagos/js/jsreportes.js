tabGlobal = null;

function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}	
}

$('.mdl-layout__tab[href="#tab-vencimiento"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
											'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarVencidos" data-mfb-label="Filtrar">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-puntual"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="cronograma_pago_fg">'+
											'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarCuota" data-mfb-label="Filtrar">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-pagados"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
											'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarPagados" data-mfb-label="Filtrar pensiones pagadas">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-verano"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
											'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarVerano" data-mfb-label="Filtrar pensiones pagadas">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$('.mdl-layout__tab[href="#tab-auditoria"]').click(function(){
	$('#menu .mfb-component__wrap').empty();
	$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
	$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
											'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalSelectFiltro" data-mfb-label="Filtrar auditoria del sistema">'+
											'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
											'		<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
											'	</button>'+
											'</li>');
});

$(window).load(function() {
	$('main section').removeAttr('style');
});