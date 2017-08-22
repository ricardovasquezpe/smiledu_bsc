var tab1 = document.getElementById('tab-1');
var tab2 = document.getElementById('tab-2');
var tab3 = document.getElementById('tab-3');
var tab4 = document.getElementById('tab-4');

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
	
	if ( element.attr('id') ==  "tab-1" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroPensiones" data-mfb-label="Filtrar Pensiones">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>'+
												'		<i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>'+
												'	</button>'+
												'</li>');
	} else if ( element.attr('id') ==  "tab-2" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="cronograma_pago_fg">'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroAlumnoCompromiso" data-mfb-label="Generar Compromisos">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-assignment_ind"></i>'+
												'		<i class="mfb-component__main-icon--active  mdi mdi-assignment_ind"></i>'+
												'	</button>'+
												'</li>');		
	} else if ( element.attr('id') ==  "tab-3" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap">'+
												'	<button class="mfb-component__button--main">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>'+
												'	</button>'+
												'	<button class="mfb-component__button--main" data-mfb-label="Asignar becas para estudiante" onclick="openModalAsignarBeca();">'+
												'		<i class="mfb-component__main-icon--active mdi mdi-new_student" ></i>'+
												'	</button>'+
												'	<ul class="mfb-component__list">'+
												'		<li class="">'+
												'			<button class="mfb-component__button--child " id="main_save_multi"  onclick="openModalcrearBeca();" data-mfb-label="Nuevo descuento">'+
												'				<i class="mdi mdi-mode_edit"></i>'+
												'			</button>'+
												'		</li>'+
												'	</ul>'+
												'</li>');		
	} else {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html(	'<li class="mfb-component__wrap">'+
												'	<button class="mfb-component__button--main">'+
												'		<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>'+
												'	</button>'+
												'	<button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroCompromiso" data-mfb-label="Filtrar y Asignar">'+
												'		<i class="mfb-component__main-icon--active mdi mdi-filter_list" ></i>'+
												'	</button>'+
												'	<ul class="mfb-component__list">'+
												'		<li class="">'+
												'			<button class="mfb-component__button--child" id="main_save_multi" data-toggle="modal" data-target="#modalSaveCompromisos" onclick="loadCompromisosModal(\'modalSaveCompromisos\',\'conceptosCompromisos\')" data-mfb-label="Guardar Compromisos de aulas">'+
												'				<i class="mdi mdi-save"></i>'+
												'			</button>'+
												'		</li>'+
												'		<li class="">'+
												'			<button class="mfb-component__button--child" id="main_save_multiDelete" data-toggle="modal" data-target="#modalFiltroCompromisoDelete" onclick="loadComboCompromisosGlobales();" data-mfb-label="Eliminar Compromisos extras">'+
												'				<i class="mdi mdi-delete"></i>'+
												'			</button>'+
												'		</li>'+
												'	</ul>'+
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
	changeTab($('#tab-2'), 1);
});

swipeTab2.on("swipeleft", function(ev) {
	changeTab($('#tab-3'), 1);	
});

swipeTab3.on("swipeleft", function(ev) {
	changeTab($('#tab-4'), 1);
});

swipeTab4.on("swipeleft", function(ev) {
	changeTab($('#tab-1'), 1);
});

swipeTab1.on("swiperight", function(ev) {
	changeTab($('#tab-4'), 2);
});

swipeTab2.on("swiperight", function(ev) {
	changeTab($('#tab-1'), 2);
});

swipeTab3.on("swiperight", function(ev) {
	changeTab($('#tab-2'), 2);
});

swipeTab4.on("swiperight", function(ev) {
	changeTab($('#tab-3'), 2);
});