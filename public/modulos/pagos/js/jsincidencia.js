function cambiarCalendario(tab, persona){
	currentTabl = tab;
	currentPers = persona; 
	$.ajax({
		data  : {currentTabl : currentTabl,
				 currentPers : currentPers},
	    url   : 'c_incidencia/getCalendario',
	    type  : 'POST',
	    async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('section .mdl-card').removeClass('mdl-calendar');
		$('section.is-active .mdl-card').addClass('mdl-calendar');
		$("#tb_incidencia"+currentTabl+"").hide();
		$("#btn-calendario"+currentTabl+"").css('display'  , 'none');
		$("#btn-group-dates"+currentTabl+"").css('display' , 'block');		
		initCalendarComponents(data,currentTabl);
		var fecha = JSON.parse(data.fecIncidencia);
		initCalendar(currentTabl, fecha);
		$('#deudas'+currentTabl).css('display'       , 'none');
		$('#calendarText'+currentTabl).css('display' , 'block');
		$('#fecha_options'+currentTabl).css('display', 'none');
	});
}

function initCalendar(currentTabl, fecha){
	var calendar = $('#calendar'+currentTabl).calendar({
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

function initCalendarComponents(data,currentTabl){
	$('#contTbIncidencia'+currentTabl).html(data.clIncidencia);
	componentHandler.upgradeAllRegistered();
}

var idGlobal = 0;
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
		    url   : 'c_incidencia/getTableByPersona',
		    type  : 'POST',
		    async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('section .mdl-card').removeClass('mdl-calendar');
			currentPers=data.cod_familia;
			$("#calendar"+currentTabl+"").hide();
			$("#btn-group-dates"+currentTabl+"").css('display', 'none');
			$("#btn-calendario"+currentTabl+"").css('display', 'inline-block');
			$('#fecha_options'+currentTabl).css('display', 'block');
			activeDesactiveButton(true);
			initTableComponents(data,currentTabl);
			$('#deudas'+currentTabl).css('display', 'block');
			$('#calendarText'+currentTabl).css('display', 'none');
			$('#contTbCompromisos'+currentTabl).css('display', 'block');
		});
	});
}

function initTableComponents(data,currentTabl){
	var tb = 'tb_compromisos'+String(currentTabl);
	$('#contTbIncidencia'+currentTabl).html(data.tablaIncidencia);
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