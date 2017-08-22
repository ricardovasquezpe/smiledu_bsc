var tab1 = document.getElementById('tab-detalle');
var tab2 = document.getElementById('tab-compromiso');
var tab3 = document.getElementById('tab-boleta');

var swipeTab1 = new Hammer(tab1);
var swipeTab2 = new Hammer(tab2);
var swipeTab3 = new Hammer(tab3);

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

	if ( element.attr('id') == 'tab-detalle' ) {
		$('#generarBoletas').css('display','none');
		$('#menu .mfb-component__wrap').empty();
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
	} else if ( element.attr('id') == 'tab-compromiso' ) {
		$('#generarBoletas').css('display','block');
		$('#menu .mfb-component__wrap').empty();
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="crono_cuota">'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalCronogramaCuota" data-mfb-label="Filtrar Cuota">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
												'	</button>'+
												'</li>');
	} else if ( element.attr('id') == 'tab-boleta' ) {
		$('#generarBoletas').css('display','none');
		$('#menu .mfb-component__wrap').empty();
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		listarBoleta();
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
	changeTab($('#tab-compromiso'), 1);
});

swipeTab2.on("swipeleft", function(ev) {
	changeTab($('#tab-boleta'), 1);	
});

swipeTab3.on("swipeleft", function(ev) {
	changeTab($('#tab-detalle'), 1);
});

swipeTab1.on("swiperight", function(ev) {
	changeTab($('#tab-boleta'), 2);
});

swipeTab2.on("swiperight", function(ev) {
	changeTab($('#tab-detalle'), 2);
});

swipeTab3.on("swiperight", function(ev) {
	changeTab($('#tab-compromiso'), 2);
});