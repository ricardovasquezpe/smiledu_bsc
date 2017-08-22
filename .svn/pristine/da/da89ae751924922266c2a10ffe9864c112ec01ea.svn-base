function goToSistema(data, rol) {
	$.ajax({
		data : { id_sis  : data,
			     rol     : rol },
		url  : 'setIdSistemaInSession',
		async: false,
		type : 'POST'
	})
	.done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data=JSON.parse(data);
			if(data.err == 0) {
				if(data.ses == 1) {
					setTimeout(function() {
						var win = window.open(data.url, '_blank');
						win.focus();
					}, 525);
				}
			}
		}
	});
}

function init() {
	if($(window).width() <= 531) {
		$('#bscDesc').text("BSC");
		$('#spedDesc').text("ED");
	} else {
		$('#bscDesc').text("Balanced Scorecard");
		$('#spedDesc').text("Evaluación de docentes");
	}
	$(window).resize(function() {
		if($( window ).width() <= 531){
			$('#bscDesc').text("BSC");
			$('#spedDesc').text("ED");
	    } else {
	    	$('#bscDesc').text("Balanced Scorecard");
	    	$('#spedDesc').text("Evaluación de docentes");
	    }
	    if($("body").hasClass("menubar-pin")) {
	    	if($(window).width() >= 1200) {
	    		$('#bscDesc').text("BSC");
	 			$('#spedDesc').text("ED");
	 		}
	 	}
	});
}

function openPermisosList(id) {
	$("#"+id).find('.mdl-card__actions.closed').on('click',function () {
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

function getSistemasByFiltro(){
	var search = $('#searchMagic').val();
	$.ajax({
		data  : {search : search},
		url   : 'c_main/getSistemasByFiltro',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#sortable').html(data.sistemas);//animar(10000);
//		console.log($('.ui-state-default draggable ui-sortable-handle'));
//		$('.ui-state-default draggable ui-sortable-handle').addClass('animated');
//		$('.ui-state-default draggable ui-sortable-handle').css('transition-delay','1.04s');
	});
};

function animar(speed){
//	  var speed = 700;
	  var container =  $('.display-animation');  
	  container.each(function() {   
	    var elements = $(this).children();
	    elements.each(function() {      
	      var elementOffset = $(this).offset(); 
	      var offset = elementOffset.left*1.5 + elementOffset.top;
	      var delay = parseFloat(offset/speed).toFixed(2);
	      $(this)
	        .css("-webkit-transition-delay", delay+'s')
	        .css("-o-transition-delay", delay+'s')
	        .css("transition-delay", delay+'s')
	        .addClass('animated');
	    });
	  });
}