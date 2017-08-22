var pagoMenu = null;
//CAMBIAR DE MODULO
function modalGoToSistema(data, rol) {
	$.ajax({
	    data : { id_sis : data,
			     rol    : rol },
		url  : '../c_main/setIdSistemaInSession',
		type : 'POST'
	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.err == 0) {
			if(data.ses == 1) {
				setTimeout(function() {
					var win = window.open(data.url, '_blank');
					win.focus();
				}, 525);
			}
		} else {
			msj('error', data.msj);
		}
	});
};

//OPEN PERFIL BAR
$('#profile').on('click', function() {
	$('.mdl-layout__drawer--right').addClass('is-visible');
	$('.mdl-layout__obfuscator').addClass('is-visible');
});

//CLOSE PERFIL BAR
$("body").click(function(event) {
	if (!$('.mdl-layout__obfuscator').hasClass('is-visible')) {
		$('#navProfile').removeClass('is-visible');
		$('#navBar').removeClass('is-visible');
		$('.mdl-nav ul li').removeClass('activeMenu');
		$('.mdl-header-input-group' ).removeClass( 'active' );
		$(".mdl-header-input-group .mdl-icon").find(".mdi").addClass("mdi-magic");
		$(".mdl-header-input-group .mdl-icon").find(".mdi").removeClass("mdi-mic mdl-button mdl-js-button mdl-button--icon");
		$('#searchMagic').css("color","#fff");
		if(pagoMenu == null){
			$('.mdl-header-input-group' ).removeAttr( 'style' );
		}
		$( '#menu' ).removeAttr( 'style' );
	}
});

$('#mantenimiento').click(function(event) {
	$('.mdl-nav ul li').toggleClass('activeMenu');
});

// SEARCH MAGIC BOX
$( "#searchMagic, #openSearch" ).click(function() {
	reintentarBusqueda();
});

$( "#searchMagic" ).mouseleave(function() {
	$( 'body.bg-fab-black #menu' ).removeAttr( 'style' );
});

$('.mdl-header-input-group').click(function(){
	micro = $("#searchMagic").attr("microphone");
	if(micro == 1){
		$(".mdl-header-input-group .mdl-icon").find(".mdi").removeClass("mdi-magic");
		$(".mdl-header-input-group .mdl-icon").find(".mdi").addClass("mdi-mic mdl-button mdl-js-button mdl-button--icon");
		$(".mdl-header-input-group .mdl-icon").find(".mdi").attr("onclick", "modalSearchVoice()");
	}
});

//SEARCH MAGIC BOX
$( "#openSearch" ).click(function() {
	$( '.mdl-header-input-group' ).fadeIn();
});

$( "#closeSearch" ).click(function() {
	$('.mdl-layout__obfuscator').removeClass('is-visible');
	$( 'body.bg-fab-black #menu' ).removeAttr( 'style' );
});

function reintentarBusqueda(){
	$('#searchMagic').focus();
	$("#searchMagic").select();
	$( '.mdl-header-input-group' ).addClass('active');
	$('.mdl-layout__obfuscator').addClass('is-visible');
	$( '.mdl-header-input-group' ).css( 'z-index' , '32' );
	$( 'body.bg-fab-black #menu' ).css( 'z-index' , '4' );
	$('.img-search').find('.mdl-button').css('visibility', 'visible');
}

//SEARCH : FOCUS EN RESPONSIVO
function setFocus(input){
	setTimeout(function() {
		$(input).focus();
	}, 100);
}

//SCROLL CLASS REDUCIDO
$('.mdl-layout__content').scroll(function() {
    if($(this).scrollTop() == 0) {
    	$('.mdl-layout__header').removeClass('mdl-height__header');
    } else {
    	$('.mdl-layout__header').addClass('mdl-height__header');
    }
});

$(window).resize(function(){
	if ($(document).height() <= $(window).height()) {
		$("#menu").fadeIn();
    }
});

$(document).bind("DOMSubtreeModified",function(){
	if ($(document).height() <= $(window).height()) {
		$("#menu").fadeIn();
    }
});

//RIPPLE AL FAB
$('.mfb-component__button--main').addClass('mdl-js-button mdl-js-ripple-effect');
$('.mfb-component__button--child').addClass('mdl-js-button mdl-js-ripple-effect');


//APARECER Y DESVANECER FAB
var lastScrollTop = 0;
$('main').scroll(function(event){
   var st = $(this).scrollTop();
   if (st > lastScrollTop){//OCULTAR
	   $("#menu").fadeOut();
   } else {
	   if(st + $('main').height() < $(document).height()) {//MOSTRAR
		   $("#menu").fadeIn();
    	}
   }
   lastScrollTop = st;
});

//OPEN ROLES DEL MODULO
var alt;
function openPermisos(id){
	$("#"+id).find('.closed').on('click',function () {
		  $('.ui-state-default .mdl-card__actions').css("height", "35px");
		  $('.ui-state-default .mdl-card__actions .mdl-button').css("height", "35px");
		  $('.ui-state-default .mdl-card__title').fadeIn(0);
		  $('.ui-state-default .mdl-button li:nth-child(1) a i').css('visibility', 'visible');
		  $('.open').addClass('closed').removeClass('open');	
		  
		  $("#"+id).find('.mdl-card__title').fadeOut(0);
		  $("#"+id).find('.mdl-card__actions').css("height", "155px");
		  $("#"+id).find('.mdl-card__actions .mdl-button').css("height" , "155px");
		  $("#"+id).find('.mdl-button').css("height" , "211px");
		  $('#'+id).find('.mdl-button li:nth-child(1) a i').css('visibility', 'hidden');
		  $('#'+id).find('.closed').addClass('open').removeClass('closed');
		  return false;
	});
	$('body').click(function () {
		$("#"+id).find('.mdl-card__actions').css("height", "35px");
			$("#"+id).find('.mdl-card__actions .mdl-button').css("height", "35px");
			$("#"+id).find('.mdl-card__title').fadeIn(500);
			$('#'+id).find('.mdl-button li:nth-child(1) a i').css('visibility', 'visible');
			$('#'+id).find('.open').addClass('closed').removeClass('open');
	});
};

//ITEMS SELECCIONADOS
var arrayChecked = [];

function assignItem(id, idTable, idCard){
	var inputs = $('#'+idTable).find('input:checkbox');
	var tableData = $('#'+idTable).bootstrapTable('getData');
	var countCheck = 0;
	$.each(tableData, function(val,i){
		var input = $(this[0]).find('input').attr('id');
		var checked = $('#'+input).is(':checked');
		if(checked == true){
            countCheck++;
		}
	});
	if(countCheck > 0){
		$('#'+idCard+' .mdl-assign').fadeIn();
		$('#'+idCard+' .mdl-assign .text').html(countCheck+' items seleccionados.');
	} else{
		$('#'+idCard+' .mdl-assign').fadeOut();
	}
}

//CHANGE POSITION TABS
$('header a.mdl-layout__tab').click(function(event) {
	var btn = $('.mdl-layout__tab-bar').find( 'a.mdl-layout__tab' ).index( this );
	var tab = $('.mdl-layout__tab-bar').children();
	var div = $(this).outerWidth();	
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
	
	if ( ( $('#menu').find('.mfb-component__button--main').length ) > 0  ) {
		$('.mfb-component__button--main').removeClass('is-up');
		setTimeout(function(){
			$('.mfb-component__button--main').addClass('is-up');
		}, 250);
	}	
});

//DETECT DEVICE FOR FAB
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

var menuFab  = '#menu';
var closeFab = '#menu[data-mfb-state="closed"]';
var openFab	 = '#menu[data-mfb-state!="closed"]';
var btnFab	 = '#menu .mfb-component__button--main';
var listFab	 = '.mfb-component__list li';

$( document ).ready(function() {
	var page = window.location.href;	
	$('#navBar .mdl-nav').find('a.mdl-button[href="'+page+'"]').closest( "li" ).addClass('active');
	
	$('body').append( '<div class="opacidad-fab"></div>' );
		
	if( isMobile.any() ) {				
		$(menuFab).attr( { 'data-mfb-toggle' : 'click',  'data-mfb-state' : 'closed' });	

		$(closeFab).find('.mfb-component__button--main:NTH-CHILD(1)').click(function(){
			$('body').toggleClass('bg-fab-black');
			$(closeFab).attr('data-mfb-state', 'open');
			$(btnFab+':NTH-CHILD(2)').addClass('active');
		});		
		
		$('.opacidad-fab').click(function(){
			$( 'body' ).removeClass('bg-fab-black');
			$(btnFab+':NTH-CHILD(2)').removeClass('active');
			$(openFab).attr('data-mfb-state', 'closed');
		});
		
	} else {		
		if ( $(menuFab).find('.mfb-only__save').length > 0 ) {
			$(menuFab).attr("data-mfb-toggle", "click");
		} else {
			$(menuFab).attr("data-mfb-toggle", "hover");
		}				
	}	
	
	if ( ( $('#menu').find('.mfb-component__button--main').length ) > 0  ) {		
		$('.mfb-component__button--main').removeClass('is-up');
		setTimeout(function(){
			$('.mfb-component__button--main').addClass('is-up');
		}, 250);		
	}
	
	resizeContent();
    
	$(window).resize(function() {
	    resizeContent();
	});
});

if( isMobile.any() ) {		
	if ( ( $(listFab).find('button').length ) > 0 ){
		$(listFab).find('button').click(function(){
			$(openFab).attr('data-mfb-state', 'closed');
			$(btnFab+'.active:NTH-CHILD(2)').removeClass('active');
			$('body').removeClass('bg-fab-black');
		});			
	}
	
	$(openFab).find('.mfb-component__button--main:NTH-CHILD(2)').click(function(){
		$(openFab).attr('data-mfb-state', 'closed');
		$('body').toggleClass('bg-fab-black');
		$(btnFab+':NTH-CHILD(2)').toggleClass('active');
	});	
} else {
	$(menuFab)
	.mouseenter(function() {
		$('body').addClass('bg-fab-black');
		$(btnFab+':NTH-CHILD(2)').addClass('active');
		$( 'body.bg-fab-black #menu' ).css( 'z-index' , '11' );
	})
	.mouseleave(function() {
		$(openFab).attr('data-mfb-state', 'closed');
		$(btnFab+'.active:NTH-CHILD(2)').removeClass('active');
		$('body').removeClass('bg-fab-black');
		$( '.mdl-header-input-group' ).removeAttr( 'style' );
	});
}

if ( $(menuFab).find('.mfb-only__save').length > 0 ) {
	$(btnFab).click(function() {
		$(menuFab).find('.mfb-only__save').addClass('mfb-save__load');
		$(menuFab + ' .mfb-only__save').find('.mfb-component__button--main').before( '<div class="mfb-load"></div>' );
		$(btnFab).attr("disabled", "disabled");				
		setTimeout(function(){
			$(btnFab).removeAttr("disabled");
			$(btnFab).removeClass('is-up');
			$(menuFab).find('.mfb-save__load').removeClass('mfb-save__load');
			$(menuFab).find('.mfb-load').remove();
			$(btnFab).addClass('is-up');
		}, 4000);
	});
} 

//ONCLICK CLOSE FAB 
function closeFab(){
	if( isMobile.any() ) {
		$(openFab).attr('data-mfb-state', 'closed');
		$(btnFab+'.active:NTH-CHILD(2)').removeClass('active');
	}
}

//RETORNAR ATRAS
function returnPage(){
	$('header:first-child').addClass('mdl-layout__header__return');
}

//FULL SCREEN
function toggleFullScreen() {
	  if ((document.fullScreenElement && document.fullScreenElement !== null)
			|| (!document.mozFullScreen && !document.webkitIsFullScreen)) {
		if (document.documentElement.requestFullScreen) {
			document.documentElement.requestFullScreen();
		} else if (document.documentElement.mozRequestFullScreen) {
			document.documentElement.mozRequestFullScreen();
		} else if (document.documentElement.webkitRequestFullScreen) {
			document.documentElement
					.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
		$('#icon_fullScreen').removeClass('mdi-fullscreen');
		$('#icon_fullScreen').addClass('mdi-fullscreen_exit');
	} else {
		if (document.cancelFullScreen) {
			document.cancelFullScreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitCancelFullScreen) {
			document.webkitCancelFullScreen();
		}
		$('#icon_fullScreen').removeClass('mdi-fullscreen_exit');
		$('#icon_fullScreen').addClass('mdi-fullscreen');
	}
}

//INIT MAIN IMAGE
function imageMainHeader(image){
	$(document).ready(function(){
		var body = $(this).outerWidth();
		if( isMobile.any() ) {
	    	if( body <= 640 ){
	        	$('header span.mdl-layout-title a').text("").append('<img src="'+ window.location.protocol+'//'+window.location.hostname+'/smiledu/public/general/img/iconsSistem/'+image+'_blanco.png" style="margin-right: 10px;">');
	        	$('header span.mdl-layout-title a, header span.mdl-layout-title a img').css("display", "block");
	    	}
		}
	});
}

// CLOSE NAVBAR PROFILE 
$('li[data-target="#all-apps"]').on('click', function() {
	$('#profile').removeClass('is-visible');
	$('.mdl-layout__obfuscator').removeClass('is-visible');
})

// ADD COLOR ICON FOR INPUT/SELECT GROUP
function focusAddIconColor(id, element, color){
	var forElement 		= null; 
	var colorElement	= null;
	var elementInvalid	= null;
	if( element == 1 ){
		forElement  	= $(id).attr('id');
		elementInvalid  = $('#'+forElement).closest('.mdl-textfield');
		if ( !elementInvalid.hasClass('is-invalid')) {
			$('.mdl-input-group').find('.mdl-icon i').removeAttr('style');
		}
	} else {
		forElement 		= $(id).find('button.selectpicker').attr('data-id');
		$('.mdl-input-group').find('.mdl-icon i').removeAttr('style');
	}
	$(id).closest('.mdl-input-group').find('.mdl-icon i').css('color', color);
}

var colorDefault = $('header.mdl-layout__header').css('background-color');

if( colorDefault == 'rgb(255, 255, 255)') {
	colorDefault = "#004062"
}

$('.mdl-textfield__input').click(function() {
	focusAddIconColor(this, 1, colorDefault);
});

$('.mdl-select').click(function() {
	focusAddIconColor(this, 2, colorDefault);
});

$('.mdl-textfield__input').focusout(function(){
	var input = $(this).closest('.mdl-textfield');
	if ( !input.hasClass('is-invalid')) {
		$('.mdl-input-group').find('.mdl-icon i').removeAttr('style');
	}
});

/*
$('.mdl-select').focusout(function(){
	var select = $(this).clossest('li');		
	$("").val($("").data("default-value"));
});*/

$(window).keyup(function (e) {
	var code = (e.keyCode ? e.keyCode : e.which);
    
    var focus		 = $(document.activeElement);
    var focusElement = null;
    var focusType	 = null;
    var colorElement = null;
    
    var focusArea	 = null;
    if ( focus.hasClass('mdl-textfield__input')) {
    	focusElement = focus;
    	focusArea	 = focus;
    	focusType 	 = 1;
    	colorElement = colorDefault;
    } else if ( focus.hasClass('selectpicker') ) {
    	focusElement = focus;
    	focusType 	 = 2;
    	colorElement = colorDefault;
	} else { 
		focusElement = 0;
		$('.mdl-input-group').find('.mdl-icon i').removeAttr('style');
	}
    
    if ( code == 9 && ( focusElement.length > 0 )  ) {  
    	focusAddIconColor(focusElement, focusType, colorElement);     	
    }
    
    if ( focusArea != null ) {
    	if ( focusArea.closest('.mdl-textfield').find('.mdl-textfield__limit') != 0 ) {
    		var idTextArea  	= focusArea.attr('id');
        	var textAreaLength 	= focusArea.val().length;
        	var spanValue		= $('.mdl-textfield__limit[for="'+idTextArea+'"]');
        	var maxValText		= spanValue.attr('data-limit');
        	spanValue.html(textAreaLength + "/" + maxValText);
        	if ( textAreaLength > maxValText ) {
        		focusArea.closest('.mdl-textfield').addClass('is-invalid');
        		colorElement = '#D50000';
        		focusAddIconColor(focusArea, 1, colorElement);
        	} else {
        		//focusArea.closest('.mdl-textfield').removeClass('is-invalid');
        		$('.mdl-input-group').find('.mdl-icon i').removeAttr('style');
        		colorElement = colorDefault;
        		focusAddIconColor(focusElement, 1, colorElement);
        	}
    	}
    }
    
    if ( code == 13 ) {
    	$('.mdl-layout__obfuscator').removeClass('is-visible');
    	$('body.bg-fab-black #menu').removeAttr('style');
    	$('.mdl-header-input-group').removeClass('active');
		$('.mdl-header-input-group').removeAttr('style');
		$('#menu').removeAttr('style');  
		$('.mdl-header-input-group' ).find('input').blur();
		$('.img-search').find('.mdl-button').css('visibility', 'visible');
    }
    
});
	
//LOGO FOOTER NAV
function resizeContent(){
	var getWindowHeight 	= $(window).height();
	var getNavTitleHeight 	= $('#navBar').find('.mdl-layout-title').height();
	var getNavigatiHeight	= $('#navBar').find('.mdl-navigation').height();
	var getHeight			= getNavTitleHeight + getNavigatiHeight;
	
	if( getHeight >= getWindowHeight || getWindowHeight <= 220) {
		$('.copy_footer').css({
			'position'		: 'relative',
			'padding-top'	: '20px'
		});
	} else {
		$('.copy_footer').removeAttr('style');
	}	
}

// ICON INIT BUTTON DATEPCIKER
// DAYS/MOUTH/YEAR
function initCalendarDays(id){
	$("#"+id).bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		date	: true, 
		time	: false, 
		format 	: 'DD/MM/YYYY'
	});
}

function initButtonCalendarDays(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this))',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarDays(newInput);
		}
		$("#"+newInput).focus();			
	});		
	var valueNewInput = $("#"+newInput).val();   
	id.text(valueNewInput);
}

//DAYS/MOUTH
function initCalendarDayAndMounth(id){
	$("#"+id).bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		date	: true, 
		time	: false, 
		format 	: 'DD/MM'
	});
}

function initButtonCalendarDayAndMounth(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this))',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarDayAndMounth(newInput);
		}
		$("#"+newInput).focus();			
	});		
	var valueNewInput = $("#"+newInput).val();   
	id.text(valueNewInput);
}

//DAYS MIN TODAY
function initCalendarDaysMinToday(id){	
	$("#"+id).bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		date	: true, 
		time	: false, 
		format 	: 'DD/MM/YYYY',
		minDate : new Date()
	});
}

function initButtonCalendarDaysMinToday(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this))',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarDaysMinToday(newInput);
		}
		$("#"+newInput).focus();
	});		
	var valueNewInput = $("#"+newInput).val();   
	id.text(valueNewInput);
}

//DAYS MAX TODAY
function initCalendarDaysMaxToday(id){
	$("#"+id).bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		date	: true, 
		time	: false, 
		format 	: 'DD/MM/YYYY',
		maxDate : new Date()
	});
}

function initButtonCalendarDaysMaxToday(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this))',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarDaysMaxToday(newInput);
		}
		$("#"+newInput).focus();			
	});		
	var valueNewInput = $("#"+newInput).val();   
	id.text(valueNewInput);
}

//MIN 18 YEARS
function initCalendarMinDate18YearsAgo(id){
	var date = new Date();
	var year	= date.getFullYear()  - 18;
	var mounth 	= date.getMonth();
	var today	= date.getDate();
	
	$("#"+id).bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		date	: true, 
		time	: false, 
		format 	: 'DD/MM/YYYY',
		maxDate : new Date(year, mounth, today)
	});
}

function initButtonCalendarMinDate18YearsAgo(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this))',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarMinDate18YearsAgo(newInput);
		}
		$("#"+newInput).focus();			
	});		
	var valueNewInput = $("#"+newInput).val();   
	id.text(valueNewInput);
}

//START AND FINISH DATE 
/* FALTA CORREGIR
 * NO SE SETEA LA MINIMA FECHA EN EL SEGUNDO INPUT
 * SOLO FUNCIONA LA PRIMERA VEZ
 * DE AHI NO FUNCIONA
*/
function initCalendarStartDate(idButtonInicio, idButtonFin) {
	$("#"+idButtonInicio).bootstrapMaterialDatePicker({
		weekStart 	: 0,
		date		: true,
		time		: false,
		format 		: 'DD/MM/YYYY'
	}).on('change', function(e, date){
		$('#'+idButtonFin).bootstrapMaterialDatePicker({
			weekStart	: 0,
			date		: true,
			time		: false,
			format 		: 'DD/MM/YYYY',
			minDate		: date
		});
	});
}

function initButtonCalendarStartDate(idButtonInicio, idButtonFin) {
	var textInicio		= idButtonInicio;
	var idInicio		= $("#"+textInicio);
	var newInputInicio 	= textInicio+'ForCalendar';
	var iconButtonIni 	= idInicio.closest('.mdl-input-group').find('.mdl-icon');
	
	var textFin			= idButtonFin;
	var idFin			= $("#"+textFin);
	var newInputFin 	= textFin+'ForCalendar';
	var iconButtonFin 	= idFin.closest('.mdl-input-group').find('.mdl-icon');
	
	if ( $('#'+newInputInicio).length < 1 ) {
		$('<input>').attr({
		    type		: 'text',
		    id			: newInputInicio,
		    name		: newInputInicio,
		    'data-time'	: textInicio,
		    onchange 	: 'clonarFecha($(this))',
		    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
		}).appendTo(iconButtonIni);
	}
	
	if ( $('#'+newInputFin).length < 1 ) {
		$('<input>').attr({
		    type		: 'text',
		    id			: newInputFin,
		    name		: newInputFin,
		    'data-time'	: textFin,
		    onchange 	: 'clonarFecha($(this))',
		    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
		}).appendTo(iconButtonFin);
	}
	
	initCalendarStartDate(newInputInicio, newInputFin);
	$("#"+newInputInicio).focus();
	
	var valueNewInput = $("#"+newInputInicio).val();
	idInicio.text(valueNewInput);
}

//MAX DATE 
/* FALTA CORREGIR
 * NO SE SETEA LA MAXIMA FECHA EN EL SEGUNDO INPUT
 * SOLO FUNCIONA LA PRIMERA VEZ
 * DE AHI NO FUNCIONA
*/
function initCalendarMaxDate(idButtonInicio, idButtonFin) {
	$("#"+idButtonInicio).bootstrapMaterialDatePicker({
		weekStart 	: 0,
		date		: true,
		time		: false,
		format 		: 'DD/MM/YYYY'
	}).on('change', function(e, date){
		$('#'+idButtonFin).bootstrapMaterialDatePicker({
			weekStart	: 0,
			date		: true,
			time		: false,
			format 		: 'DD/MM/YYYY',
			maxDate		: date
		});
	});
}

function initButtonCalendarMaxDate(idButtonInicio, idButtonFin) {
	var textInicio		= idButtonInicio;
	var idInicio		= $("#"+textInicio);
	var newInputInicio 	= textInicio+'ForCalendar';
	var iconButtonIni 	= idInicio.closest('.mdl-input-group').find('.mdl-icon');
	
	var textFin			= idButtonFin;
	var idFin			= $("#"+textFin);
	var newInputFin 	= textFin+'ForCalendar';
	var iconButtonFin 	= idFin.closest('.mdl-input-group').find('.mdl-icon');
	
	if ( $('#'+newInputInicio).length < 1 ) {
		$('<input>').attr({
		    type		: 'text',
		    id			: newInputInicio,
		    name		: newInputInicio,
		    'data-time'	: textInicio,
		    onchange 	: 'clonarFecha($(this))',
		    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
		}).appendTo(iconButtonIni);
	}
	
	if ( $('#'+newInputFin).length < 1 ) {
		$('<input>').attr({
		    type		: 'text',
		    id			: newInputFin,
		    name		: newInputFin,
		    'data-time'	: textFin,
		    onchange 	: 'clonarFecha($(this))',
		    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
		}).appendTo(iconButtonFin);
	}
	
	initCalendarMaxDate(newInputInicio, newInputFin);
	$("#"+newInputInicio).focus();
	
	var valueNewInput = $("#"+newInputInicio).val();
	idInicio.text(valueNewInput);
}

//HOURS
function initCalendarHours(id){
	$("#"+id).bootstrapMaterialDatePicker({  
		date 		: false, 
		time		: true,
		format		: 'h:mm a',
		shortTime	: true
	});
}

function initButtonCalendarHours(idButton) {
	var text 		= idButton;
	var id 			= $("#"+text);
	var newInput	= null;
	var iconButton 	= id.closest('.mdl-input-group').find('.mdl-icon');
	iconButton.find('.mdl-button').click(function(){
		newInput = text+'ForCalendar';
		if ( $('#'+newInput).length < 1 ) {
			$('<input>').attr({
			    type		: 'text',
			    id			: newInput,
			    name		: newInput,
			    'data-time'	: text,
			    onchange 	: 'clonarFecha($(this),"'+idButton+'")',
			    style		: 'position: absolute; top: 40px; border: transparent; color: transparent; z-index: -4'
			}).appendTo(iconButton);
			initCalendarHours(newInput,idButton);
		}
		$("#"+newInput).focus();
		var valueNewInput = $("#"+newInput).val();
		id.text(valueNewInput);
	});
}

function clonarFecha(inputNew,idButton) {
	$('#'+inputNew.data('time')).val(inputNew.val());
	$('#'+inputNew.data('time')).focus();
	$('#'+inputNew.data('time')).blur();
	if(idButton){
		$("#"+idButton).trigger("change");
	}
}

$('.mdl-icon .mdl-button').click(function(){
	$(this).closest('.mdl-input-group').find('.mdl-textfield').addClass('is-dirty');
});

/* BOTON GUARDAR */
function initButtonLoad(){
	for(var i = 0; i < arguments.length; i++) {
		var id 		= arguments[i];
		var btn		= $('#'+id);
		btn.empty();
		if ( !btn.hasClass('mdl-save__load') ){
			btn.addClass('mdl-save__load');
		}
		$('<div>').attr({
			'for'	: id,
		    'class'	: 'mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active'
		}).appendTo(btn);
	}
}

function addLoadingButton(id) {
	$('#'+id).addClass('mdl-save__load__loading');
}

function stopLoadingButton(id) {
	$('#'+id).removeClass('mdl-save__load__loading');
}

//LIMIT INPUTS
function initLimitInputs() {
	for(var i = 0; i < arguments.length; i++){
		var text 		= arguments[i];
		var textArea	= $('#'+text);
		var inputLength	= textArea.val().length;
		var spanValue	= $('.mdl-textfield__limit[for="'+text+'"]');
		var maxValInput = spanValue.attr('data-limit');
		spanValue.html(inputLength + "/" + maxValInput);
	}
}

$( document ).ready(function() {
	if( $('.mdl-icon__save').length > 0 ){
		$.each($('.mdl-icon__save'),function(i,val){
			var idSave = $(this).attr('id');
			$('<div>').attr({
				'for'	: idSave,
			    'class'	: 'mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active'
			}).appendTo( '#'+idSave );
		});
	}
});

function initSavingButton(id){
	$('#'+id).addClass('mdl-icon__saving');
}

function stopSavingButton(id) {
	$('#'+id).removeClass('mdl-icon__saving');
	$('#'+id).find('i').removeClass('mdi-save');
	$('#'+id).find('i').addClass('mdi-cloud_done');	
	setTimeout(function() {
		$('#'+id).find('i').removeClass('mdi-cloud_done');
		$('#'+id).find('i').addClass('mdi-save');
	}, 1000);
}

function initUpdateButton(){
	$('#btn-update-data').addClass('mdl-icon__saving');
}

function stopUpdateButton(id) {
	$('#btn-update-data').removeClass('mdl-icon__saving');
	$('#btn-update-data').find('i').removeClass('mdi-refresh');
	$('#btn-update-data').find('i').addClass('mdi-cloud_done');	
	setTimeout(function() {
		$('#btn-update-data').find('i').removeClass('mdi-cloud_done');
		$('#btn-update-data').find('i').addClass('mdi-refresh');
	}, 2000);
}

/* CAMBIO DE ICONO DEL MAGIC SEARCH CUANDO NO ESTA EN EL MAIN */
function magicIcon(){
	$(".mdl-header-input-group .mdl-icon").find(".mdi").removeClass("mdi-search");
	$(".mdl-header-input-group .mdl-icon").find(".mdi").addClass("mdi-magic");
	$("#openSearch").find(".mdi").removeClass("mdi-search");
	$("#openSearch").find(".mdi").addClass("mdi-magic");
}



/* MASK DE FECHA */
function initMaskInputs() {
	for(var i = 0; i < arguments.length; i++) {
		var text	= arguments[i];
		var input	= $('#'+text);
		input.mask('00/00/0000');
	}
}

/* MASK DE HORAS */
function initMaskTime() {
	for(var i = 0; i < arguments.length; i++) {
		var text	= arguments[i];
		var input	= $('#'+text);
		input.mask('00:00');
	}
}

/* RESET INIT SELECT PICKER */
function resetSelect(element){
	var idElement = $("#"+element);
	var groupElement = idElement.closest('.mdl-input-group');
	var ulElement = groupElement.find(".dropdown-menu");
	var textElement = ulElement.find('li[data-original-index]:FIRST-CHILD span.text').text();
	ulElement.find('li').removeClass('selected');
	ulElement.find('li').removeClass('active');
	ulElement.find('li[data-original-index]:FIRST-CHILD').addClass('selected active');
	groupElement.find('button[data-id="'+element+'"] .filter-option').text(textElement);
}

$('[data-target="#modalSubirPaquete"]').click(function(){
	var content = $('#contentPaquete');
	var titlePaquete = $(this).attr('data-paquete-text');
	content.find('h2').text(titlePaquete);
});

function abrirModalPaquete(msj){
	var content = $('#contentPaquete');
	content.find('h2').text(msj);
	$('#modalSubirPaquete').modal('toggle');
}

$('[data-target="#modalPasarelaPago"]').click(function(){
	var content = $('#contentPaquete');
	var titlePaquete = $(this).attr('data-paquete-text');
	content.find('h2').text(titlePaquete);
});

function abrirModalPasarela(msj){
	var content = $('#contentPaquete');
	content.find('h2').text(msj);
	$('#modalPasarelaPago').modal('toggle');
}

//RESIZE MARGIN-BOTTOM FOR MDL-WIZZARD
function initWizardVertical(id, index){
	var wizzard	= $('#'+id);
	var card	= wizzard.closest('.mdl-card');
	var barPg	= wizzard.find('.progress-bar');
	var wTab	= card.find('.tab-content');
	var body 	= $('body').outerWidth();
	var wLi		= null,
		wHeight	= null,
		wMarBot	= null,
		heightPg= null,
		widthPg	= null,
		sizePg	= null;

	wLi 		= wizzard.find('ul.nav').children().length;
	wLiHeight	= wizzard.find('ul.nav li').height();
	
	if (!wizzard.find('ul.nav li').attr('data-index-li')){		
		for ( var i = 1; i <= wLi; i++ ){
			wizzard.find('ul.nav li:NTH-CHILD('+i+')').attr('data-index-li', i);
			wTab.find('.tab-pane:NTH-CHILD('+i+')').attr('data-index-tab', i);
		}
	}
	
	wHeight 	= parseFloat(wTab.find('.tab-pane[data-index-tab="'+index+'"]').outerHeight());
	wMarBot		= parseFloat(wHeight) - ( ( wLiHeight + 20 ) * wLi ) + 20;

	wizzard.find('ul.nav').find('li').css('margin-bottom', '20px');
	wizzard.find('ul.nav').find('li[data-index-li="'+index+'"]').css('margin-bottom', Math.abs(wMarBot)+'px');
	
	setTimeout(function(){
		if ( index == 1 ){
			heightPg	= 0;
			widthPg		= 0;
		} else if ( index > 1 ) {
	    	if( body <= 860 ){
	    		sizePg = parseFloat(barPg.parent('.progress').height());
	    	} else {
	    		sizePg = parseFloat(barPg.parent('.progress').width());
	    	}
			
			heightPg	= ( ((36*index) + (20*(index-1))) * 100 ) / sizePg;
			widthPg		= ( (index-1) / (wLi-1) ) * 100;
		}
	
		barPg.css({ width: widthPg + '%',height: heightPg + '%'});
	}, 250);
	
}

$('.form-wizard-nav ul.nav li a').click(function(){
	var id 		= $(this).closest('.form-wizard').attr('id');
	var indexLi	= $(this).parent().attr('data-index-li');
	initWizardVertical(id, indexLi);
});

$(window).resize(function() {
	var idLiWizzard 	= $('.form-wizard-nav ul.nav li a').closest('.form-wizard').attr('id');
	var indexTabWizzard = $('#'+idLiWizzard).closest('.mdl-card').find('.tab-content').find('.tab-pane.active').attr('data-index-tab');
	initWizardVertical(idLiWizzard, indexTabWizzard);
});

//PRELOADER
function screenLoader(timeInit) {
	var main 	= $('main.mdl-layout__content');
	var load 	= $('.screen-load');
	var top 	= $('header.mdl-layout__header').height() + 1;
	var body	= $('body').outerHeight();
	load.css({
		'top': top+'px',
		'padding-top': ((body/2) - (top - 1) - 24)+'px'
	});
	var timeLoad = performance.now();
	setTimeout( function() {
		load.removeAttr('style');
		load.css({
			'top': top+'px',
			'visibility': 'hidden',
			'opacity': '0',
			'padding-top': ((body/2) - (top - 1) - 24)+'px'
		});	
	}, (timeLoad));
	setTimeout( function() {
		main.addClass('is-visible');
	}, (timeLoad + 1000));
}

function tabLoader(id, timeInit,timeEnd){
//	var timeLoad 	= (new Date()).getTime();
//	console.log(timeInit);
//	console.log(timeLoad);
	var segs 		= timeInit - timeEnd;
	var height 		= $('header.mdl-layout__header').height() + 1;
	$('.screen-load').addClass('screen-load__tab');
	$(id).css({
		'position': 'fixed',
		'bottom': '0',
		'right': '0',
		'left': '0',
		'top': '100%',
		'opacity': '0',
		'visibility': 'hidden',
	});
	
	$('.screen-load').css({
		'top': height+'px',
		'visibility': 'visible',
		'opacity': '1'
	});
	
	setTimeout( function(){
		$('.screen-load').css({
			'top': '100%',
			'opacity': '0'
		});
	}, (segs + 250));
	
	setTimeout( function(){
		$(id).css({
			'top': height+'px',
			'visibility': 'visible',
			'opacity': '1'
		});
	}, (segs + 750));
		
	setTimeout( function(){
		$('.screen-load, '+id).removeAttr('style');
		$('.screen-load').removeClass('screen-load__tab');
	}, (segs + 1750));	
}

//TOOGLE CARD EFFECT SISTEMA
function openSistema(id){
	$('.mdl-app_content').removeClass('mdl-flipped');
	$('#'+id).toggleClass('mdl-flipped');
}

$('.mdl-app_content').mouseleave(function() {
	$('.mdl-app_content').removeClass('mdl-flipped');
});

//SOLO PARA ADMISION - REPORTES
$( document ).ready(function(){
	var urlSmiledu 	= window.location.protocol+'//'+window.location.hostname+'/';
	var urlReportes = urlSmiledu+'smiledu/admision/c_reportes';
	var listaMenu	= $("#navBar .mdl-nav li").length;
	var aReportes	= null;
	var urlSelect	= null;
	for ( var i = 1; i <= listaMenu; i++ ){
		var aLista	 = $("#navBar .mdl-nav li:NTH-CHILD("+i+") a");
		var urlLista = aLista.attr('href');
		
		if ( urlLista == urlReportes){
			aReportes = aLista;
			
			aReportes.attr({
				"href"					: "javascript:void(0)",
				"title"					: "Proximamente",
				"data-toggle"			: "tooltip",
				"data-placement"		: "bottom",
				"data-original-title"	: "Pr&oacute;ximamente"
			});
		}
	}
});

function assignItemAUX(id, idTable, idCard){
	var inputs = $('#'+idTable).find('input:checkbox');
	var tableData = $('#'+idTable).bootstrapTable('getData');
	var countCheck = 0;
	$.each(tableData, function(val,i){
		var input = $(this[0]).find('input').attr('id');
		var checked = $('#'+input).is(':checked');
		if($(this[0]).find('input').prop( "checked" )== true){
            countCheck++;
		}
	});
	if(countCheck > 0){
		$('#'+idCard+'.mdl-assign').fadeIn();
		$('#'+idCard+'.mdl-assign .text').html(countCheck+' items seleccionados.');
	} else{
		$('#'+idCard+'.mdl-assign').fadeOut();
	}
}

// SOLO PARA EL CARD DE INSCRITOS
function getNextOrPrev(element, type) {
	var card 	= element.closest('.mdl-inscritos').find('.mdl-card__title');
	var ul 	 	= card.find('ul');
	var li	 	= ul.children().length;
	var next 	= card.find('.nav-pills__right');
	var prev 	= card.find('.nav-pills__left');
	var active	= ul.find('.active');

	if ( li == 1 ){
		prev.addClass('disabled');
		next.addClass('disabled');
		prev.removeAttr('onclick').removeAttr('data-toggle');
		next.removeAttr('onclick').removeAttr('data-toggle');
		prev.attr('href', 'javascript:void(0)');
		next.attr('href', 'javascript:void(0)');
	} else {
		active.removeClass('active');
		
		if ( active[0] == active.parent().find('li').first()[0] ) {
			prev.attr('href', ul.find('li').last().find('a').attr('href'));
			next.attr('href', active.next().find('a').attr('href'));
			if ( type == 1 ) {
				ul.find('li').last().addClass('active');
			} else if ( type == 2 ) {
				active.next().addClass('active');
			}
		} else if ( active[0] != active.parent().find('li').first()[0] && active[0] != active.parent().find('li').last()[0] ) {
			prev.attr('href', active.prev().find('a').attr('href'));
			next.attr('href', active.next().find('a').attr('href'));
			if ( type == 1 ) {
				active.prev().addClass('active');
			} else if ( type == 2 ) {
				active.next().addClass('active');
			}
		} else if ( active[0] == active.parent().find('li').last()[0] ) {
			next.attr('href', ul.find('li').first().find('a').attr('href'));
			prev.attr('href', active.prev().find('a').attr('href'));			
			if ( type == 1 ) {
				active.prev().addClass('active');
			} else if ( type == 2 ) {
				ul.find('li').first().addClass('active');
			}
		}
	}
}