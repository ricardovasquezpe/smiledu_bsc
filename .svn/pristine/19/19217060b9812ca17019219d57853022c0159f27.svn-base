var EVENTS = {
	SWIPE 	: [ "swipeLeft"	, "swipeRight" ]
};

function initSwipe(el, collection, delegate, preventDefault) {
	for (var _i = 0, _len = collection.length; _i < _len; _i++) {
		if (delegate) tabSwipe(el, collection[_i]);
	}
};

function tabSwipe(el, event_name) {
	el[event_name](function(event) {
		var tab 	= $('.mdl-layout__tab-bar').children();
		var aTb 	= null;
		var div 	= null;
		var mov 	= null;
		var next	= null;
		var prev	= null;
		var id 	 	= null;
		var orient	= null;
		
		if ( event_name == "swipeLeft" ) {
			orient	 = $(this).next();		
		} else if ( event_name == "swipeRight" ) {
			orient	 = $(this).prev();
		}
		
		id 	= '#' + orient.attr('id');
		aTb	= $('.mdl-layout__tab[href="' + id + '"]').index();
		div = $('.mdl-layout__tab[href="' + id + '"]').outerWidth();
		
		if ( orient.index() != 1 && orient.index() != tab.length ) {
			$('.mdl-layout__tab[href="#' + $(this).attr('id') + '"]').removeClass('is-active');
			$(this).removeClass('is-active');
			orient.addClass('is-active');
			$('.mdl-layout__tab[href="' + id + '"]').addClass('is-active');			
		}
		
		if (aTb == 0) {
			mov = 0;
		} else if (aTb == (tab.length - 1)) {
			mov = $('.mdl-layout__tab-bar').outerWidth();
		} else {
			mov = div * aTb;
		}

		$('.mdl-layout__tab-bar').animate({
			scrollLeft : mov
		});

		if (($('#menu').find('.mfb-component__button--main').length) > 0) {
			$('.mfb-component__button--main').removeClass('is-up');
			setTimeout(function() {
				$('.mfb-component__button--main').addClass('is-up');
			}, 250);
		}

		console.info("delegate :", event_name, event.quoData);
	});
}

/* COLOCAR PARA QUE FUNCIONE
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.ajax.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.css.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.element.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.environment.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.output.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.query.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.events.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>quojs/quo.gestures.js"></script>
	<script src="<?php echo RUTA_JS?>jsgesture.js"></script>
*/
initSwipe($$("section.mdl-layout__tab-panel"), EVENTS.SWIPE, true);