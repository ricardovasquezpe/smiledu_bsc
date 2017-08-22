var tab1 = document.getElementById('tab-1');
var tab2 = document.getElementById('tab-3');
var tab3 = document.getElementById('tab-4');
var tab4 = document.getElementById('tab-2');



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
	
	if ( element.attr('id') ==  "tab-3" ) {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html('<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">'
									        +'<li class="mfb-component__wrap" id="li_menu_1" style="display: none;">'
									        +'<button class="mfb-component__button--main" >'
									        +'<i class="mfb-component__main-icon--resting mdi mdi-add"></i>'
									        +'</button>'
									        +'<?php echo (isset($btnAsignarRecursoMaterial) ? $btnAsignarRecursoMaterial: null)?>' 
									        +'<ul class="mfb-component__list">'
									        +'<li>'
									        +'<?php echo (isset($btnCrearRecurso) ? $btnCrearRecurso: null)?>'
									        +'</li>'                
									        +'</ul>'    
									        +'</li>'
									        +'<li class="mfb-component__wrap" id="li_menu_2" style="display: none;">'
									        +'<?php echo (isset($fabAsignarApoyoAdm) ? $fabAsignarApoyoAdm: null)?>'  
									        +'</li>'
									        +'</ul>'
									        );
		
		
	} else {
		$('#menu .mfb-component__wrap').removeClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').addClass('mfb-only-btn mdl-only-btn__animation');
		$('#menu .mfb-component__wrap').html('<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">'
										        +'<li class="mfb-component__wrap" id="li_menu_1" style="display: none;">'
										        +'<button class="mfb-component__button--main" >'
										        +'<i class="mfb-component__main-icon--resting mdi mdi-add"></i>'
										        +'</button>'
										        +'<?php echo (isset($btnAsignarRecursoMaterial) ? $btnAsignarRecursoMaterial: null)?>' 
										        +'<ul class="mfb-component__list">'
										        +'<li>'
										        +'<?php echo (isset($btnCrearRecurso) ? $btnCrearRecurso: null)?>'
										        +'</li>'                
										        +'</ul>'    
										        +'</li>'
										        +'<li class="mfb-component__wrap" id="li_menu_2" style="display: none;">'
										        +'<?php echo (isset($fabAsignarApoyoAdm) ? $fabAsignarApoyoAdm: null)?>'  
										        +'</li>'
										        +'</ul>'
										        );
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
//1 3 4 2
swipeTab1.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-3'), 1);//colocar ventana que esta a la derecha
	} else {
		changeTab($('#tab-2'), 2);//colocar ventana que esta a la derecha
	}
});

swipeTab2.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-4'), 1);
	} else {
		changeTab($('#tab-1'), 2);
	}
});

swipeTab3.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-2'), 1);
	} else {
		changeTab($('#tab-3'), 2);
	}
});

swipeTab4.on("swipeleft swiperight", function(ev) {
	if ( ev.type == 'swipeleft') {
		changeTab($('#tab-1'), 1);
	} else {
		changeTab($('#tab-4'), 2);
	}
});