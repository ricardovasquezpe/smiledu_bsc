//var currentTabl= null;
//var currentPers = null;

var idGlobal = 0;
function init(){
	mostrarChips();
}

function changeContTableNumber(tab,persona,totalCrompromisos,i){
	currentTabl = tab;
	currentPers = persona;
	if(!i){
		i=null;
	} else {
		idGlobal = i;
	}
	Pace.restart();
	Pace.track(function(){
		$.ajax({
			data  : {currentTabl : currentTabl,
					 currentPers : currentPers},
		    url   : 'c_pagos/getTableByPersona',
		    type  : 'POST',
		    async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('section .mdl-card').removeClass('mdl-calendar');
			initTableComponents(data,currentTabl);
			$('.mdl-chip').removeClass('active',true);
			$('#chip'+idGlobal).addClass('active',true);
			currentPers=data.cod_familia;
			$('#fecha_options'+currentTabl).css('display', 'block');
			$("#calendarText"+currentTabl+"").hide();
			$("#btn-group-dates"+currentTabl+"").css('display', 'none');
			$("#btn-calendario"+currentTabl+"").css('display', 'inline-block');
			activeDesactiveButton(true);
			$('#deudas'+currentTabl).css('display', 'block');
			$('#calendarText'+currentTabl).css('display', 'none');
		});
	});
}

function cambioCalendario(tab,persona){
	currentTabl = tab;
	currentPers = persona; 
	$.ajax({
		data  : {currentTabl : currentTabl,
				 currentPers : currentPers},
	    url   : 'c_pagos/getCalendarioByPersona',
	    type  : 'POST',
	    async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('section .mdl-card').removeClass('mdl-calendar');
		$('section.is-active .mdl-card').addClass('mdl-calendar');
		$("#tb_compromisos"+currentTabl+"").hide();
		$("#btn-calendario"+currentTabl+"").css('display'       , 'none');
		$("#btn-group-dates"+currentTabl+"").css('display' , 'block');		
		initCalendarComponents(data,currentTabl);
		var fecha = JSON.parse(data.fecVencimiento);
		initCalendar(currentTabl, fecha);
		$('#deudas'+currentTabl).css('display'       , 'none');
		$('#calendarText'+currentTabl).css('display' , 'block');
		$('#fecha_options'+currentTabl).css('display', 'none');
	});
}

function initCalendarComponents(data,currentTabl){
	$('#contTbCompromisos'+currentTabl).html(data.clCompromisos);
	componentHandler.upgradeAllRegistered();
}

function initTableComponents(data,currentTabl){
	var tb = 'tb_compromisos'+String(currentTabl);
	$('#contTbCompromisos'+currentTabl).html(data.tbCompromisos);
	$('#'+tb).bootstrapTable({});
	$('#datos'+String(currentTabl)).html(data.datos);
	componentHandler.upgradeAllRegistered();
	tableEventsUpgradeMdlComponentsMDL(tb);
}

function activeDesactiveButton(flg){
	if(flg == true){
		$('#payMore'+currentTabl).html('money_off');
		$('#payMore'+currentTabl).parent().attr('disabled',flg);
		$('#payMore'+currentTabl).parent().css('opacity','0.5');
		$('#payMore'+currentTabl).parent().fadeOut(500);
	} else{
		$('#payMore'+currentTabl).html('attach_money');
		$('#payMore'+currentTabl).parent().attr('disabled',flg);
		$('#payMore'+currentTabl).parent().css('opacity','1');
		$('#payMore'+currentTabl).parent().fadeIn(500);
	}
}

function initCalendar(currentTabl, fecha){
	var calendar = $("#calendar"+currentTabl+"").calendar({
	        events_source: fecha,
	        language: 'es-ES',
	        tmpl_path     : "../public/general/plugins/bootstrap-calendar-master/tmpls/",
	        onAfterViewLoad: function(view) {
				$('#calendarText'+currentTabl).text(this.getTitle());
				$('button.mdl-button, li.mdl-menu__item').removeClass('active');
				$('button.mdl-button[data-calendar-view="' + view + '"], li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
			}
         });
	$('button.mdl-button[data-calendar-nav], li.mdl-menu__item[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});
	$('button.mdl-button[data-calendar-view], li.mdl-menu__item[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});
}

function changeYear(type,tab,pers){
	$.ajax({
		data  : {type : type,
			     tab  : tab,
			     pers : pers},
		url   : 'c_pagos/changeYear',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			$('#year'+tab).html(data.year);
			initTableComponents(data,currentTabl);
		} else {
			mostrarNotificacion('warning',data.msj)
		}
	});
}

function mostrarChips(){
	if($('#div-parientes-chip').find('span').length>0){
		$('.mdl-layout__content').css('display','block');
		$('#state_empty').css('display','none');
	}
	else if($('#div-parientes-chip').find('span').length<0){
		$('.mdl-layout__content').css('display','none');
		$('#state_empty').css('display','block');
	}
	else
		$('#state_empty').css('display','block');
}