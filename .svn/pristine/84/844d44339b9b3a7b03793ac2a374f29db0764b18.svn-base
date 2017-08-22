var tab1 = document.getElementById('tab-vencimiento');
var tab2 = document.getElementById('tab-puntual');
var tab3 = document.getElementById('tab-pagados');
var tab4 = document.getElementById('tab-auditoria');

var swipeTab1 = new Hammer(tab1);
var swipeTab2 = new Hammer(tab2);
var swipeTab3 = new Hammer(tab3);
var swipeTab4 = new Hammer(tab4);

function changeTab(element, type){
	var btn = element.index() - 1;
	var tab = $('.mdl-layout__tab-bar').children();
	var div = $('a.mdl-layout__tab[href="#'+element.attr('id')+'"]').outerWidth();	
	var mov = null;
	
	if( btn == 0 ){
		mov = 0;
	} else if ( btn == ( tab.length - 1 ) ) {
		mov = $('.mdl-layout__tab-bar').outerWidth();
	} else {
		mov = div * btn;
	}
	
	$('.mdl-layout__tab-bar').animate({
		scrollLeft: mov
	});	
	
	$('#menu .mfb-component__wrap').empty();
	
	if ( element.attr('id') ==  "tab-vencimiento" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
												'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarVencidos" data-mfb-label="Filtrar">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>'+
												'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>'+
												'	</button>'+
												'</li>');
	} else if ( element.attr('id') ==  "tab-puntual" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="cronograma_pago_fg">'+
												'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarPagados" data-mfb-label="Filtrar">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list" style="transform: rotate(0deg); top: 11px;"></i>'+
												'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top: 11px;"></i>'+
												'	</button>'+
												'</li>');	
	} else if ( element.attr('id') ==  "tab-pagados" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
												'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarPagados" data-mfb-label="Filtrar pensiones pagadas">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
												'	</button>'+
												'</li>');		
	} else {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">'+
												'	<button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalSelectFiltro" data-mfb-label="Filtrar auditoria del sistema">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active mdi mdi-filter_list"></i>'+
												'	</button>'+
												'</li>');		
	}
		
	if ( ( $('#menu').find('.mfb-component__button--main').length ) > 0  ) {
		$('.mfb-component__button--main').removeClass('is-up');
		setTimeout(function(){
			$('.mfb-component__button--main').addClass('is-up');
		}, 250);
	}
	
	$('main section.mdl-layout__tab-panel').removeClass('is-active');
	$('header .mdl-layout__tab').removeClass('is-active');
	element.addClass('is-active');
	if ( type == 1 ) {
		element.addClass('is-left');
	} else {
		element.addClass('is-right');
	}
	setTimeout(function(){
		element.addClass('is-now');
	}, 250);
	setTimeout(function(){
		element.removeClass('is-left');
		element.removeClass('is-right');
		element.removeClass('is-now');
		$('a.mdl-layout__tab[href="#'+element.attr('id')+'"]').addClass('is-active');
	}, 500);
}

swipeTab1.on("swipeleft", function(ev) {
	changeTab($('#tab-puntual'), 1);
});

swipeTab2.on("swipeleft", function(ev) {
	changeTab($('#tab-pagados'), 1);	
});

swipeTab3.on("swipeleft", function(ev) {
	changeTab($('#tab-auditoria'), 1);
});

swipeTab4.on("swipeleft", function(ev) {
	changeTab($('#tab-vencimiento'), 1);
});

swipeTab1.on("swiperight", function(ev) {
	changeTab($('#tab-auditoria'), 2);
});

swipeTab2.on("swiperight", function(ev) {
	changeTab($('#tab-vencimiento'), 2);
});

swipeTab3.on("swiperight", function(ev) {
	changeTab($('#tab-puntual'), 2);
});

swipeTab4.on("swiperight", function(ev) {
	changeTab($('#tab-pagados'), 2);
});