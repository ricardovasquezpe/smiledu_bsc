var tab1 = document.getElementById('grafico1');
var tab2 = document.getElementById('grafico2');
var tab3 = document.getElementById('grafico3');
var tab4 = document.getElementById('grafico4');
var tab5 = document.getElementById('grafico5');
var tab6 = document.getElementById('grafico6');

var swipeTab1 = new Hammer(tab1);
var swipeTab2 = new Hammer(tab2);
var swipeTab3 = new Hammer(tab3);
var swipeTab4 = new Hammer(tab4);
var swipeTab5 = new Hammer(tab5);
var swipeTab6 = new Hammer(tab6);

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
		
	if ( element.attr('id') ==  "grafico1" ) {
		tabAction(1);
	} else if ( element.attr('id') ==  "grafico2" ) {
		tabAction(2);
	} else if ( element.attr('id') ==  "grafico3" ) {
		tabAction(3);
	} else if ( element.attr('id') ==  "grafico4" ) {
		tabAction(4);
	} else if ( element.attr('id') ==  "grafico5" ) {
		tabAction(5);
	} else {
		tabAction(6);
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
	changeTab($('#grafico2'), 1);
});

swipeTab2.on("swipeleft", function(ev) {
	changeTab($('#grafico3'), 1);	
});

swipeTab3.on("swipeleft", function(ev) {
	changeTab($('#grafico4'), 1);
});

swipeTab4.on("swipeleft", function(ev) {
	changeTab($('#grafico5'), 1);
});

swipeTab5.on("swipeleft", function(ev) {
	changeTab($('#grafico6'), 1);
});

swipeTab6.on("swipeleft", function(ev) {
	changeTab($('#grafico1'), 1);
});

swipeTab1.on("swiperight", function(ev) {
	changeTab($('#grafico6'), 2);
});

swipeTab2.on("swiperight", function(ev) {
	changeTab($('#grafico1'), 2);
});

swipeTab3.on("swiperight", function(ev) {
	changeTab($('#grafico2'), 2);
});

swipeTab4.on("swiperight", function(ev) {
	changeTab($('#grafico3'), 2);
});

swipeTab5.on("swiperight", function(ev) {
	changeTab($('#grafico4'), 1);
});

swipeTab6.on("swiperight", function(ev) {
	changeTab($('#grafico5'), 1);
});