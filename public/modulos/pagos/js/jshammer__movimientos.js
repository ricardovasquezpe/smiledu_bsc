var tab1 = document.getElementById('tab-1');
var tab2 = document.getElementById('tab-2');

var swipeTab1 = new Hammer(tab1);
var swipeTab2 = new Hammer(tab2);

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
	
	if ( element.attr('id') ==  "tab-1" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalIngresos" data-mfb-label="Filtrar Pensiones">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
												'	</button>'+
												'</li>');
	} else {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalEgresos" data-mfb-label="Filtrar Pensiones">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
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

swipeTab1.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-2'), 1);
	} else {
		changeTab($('#tab-2'), 2);
	}
});

swipeTab2.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-1'), 1);
	} else {
		changeTab($('#tab-1'), 2);
	}
});