function init(){
	initMaskInputs("fechaInicio");
	initMaskInputs("fechaFin");
	initMaskInputs("fechaInicioMatricula");
	initButtonLoad('botonCT', 'botonCM');
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ){
	    $('.selectButton').selectpicker('mobile');
	}else{
		$('.selectButton').selectpicker();
	}	
	initButtonCalendarDayAndMounth("fechaInicio");
	initButtonCalendarDayAndMounth("fechaFin");
	initButtonCalendarDayAndMounth("fechaInicioMatricula");
	initButtonCalendarDayAndMounth("fechaInicioRatificacion");
}

function saveFechas(){
	addLoadingButton('botonCT');
	Pace.restart();
	Pace.track(function() {
		fechaInicio = $("#fechaInicio").val();
		fechaFin = $("#fechaFin").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_configuracion/guardarFechas',
			data    : {fechaInicio : fechaInicio,
				       fechaFin    : fechaFin},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			stopLoadingButton('botonCT');
			msj('success', data.msj , null);
		});
	});
}

function saveFechaMatricula(){
	addLoadingButton('botonCM');
	Pace.restart();
	Pace.track(function() {
		fechaInicio = $("#fechaInicioMatricula").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_configuracion/guardarFechaMatricula',
			data    : {fechaInicio : fechaInicio},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			stopLoadingButton('botonCM');
			msj('success', data.msj , null);
		});
	});
}

function saveFechaRatificacion(){
	addLoadingButton('botonCR');
	Pace.restart();
	Pace.track(function() {
		fechaInicio = $("#fechaInicioRatificacion").val();
		$.ajax({
			type    : 'POST',
			'url'   : 'C_configuracion/guardarFechaRatificacion',
			data    : {fechaInicio : fechaInicio},
			'async' : true
		}).done(function(data){
			data = JSON.parse(data);
			stopLoadingButton('botonCR');
			msj('success', data.msj , null);
		});
	});
}