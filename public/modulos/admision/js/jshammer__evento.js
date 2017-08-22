var tab1 = document.getElementById('invitados');
var tab2 = document.getElementById('otros');


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
}

swipeTab1.on("swipeleft", function(ev) {
	changeTab($('#invitados'), 1);
});

swipeTab2.on("swipeleft", function(ev) {
	changeTab($('#otros'), 1);	
});

swipeTab1.on("swiperight", function(ev) {
	changeTab($('#otros'), 2);
});

swipeTab2.on("swiperight", function(ev) {
	changeTab($('#invitados'), 2);
});

