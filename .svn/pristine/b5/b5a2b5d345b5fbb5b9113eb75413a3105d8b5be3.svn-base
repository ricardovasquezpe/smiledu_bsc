function changeTableAuditoria() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_auditoria/getTablaAuditoria",
	        data: {},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#'+tabGlobal).addClass('is-active');
				$('#tableContabilidad').html(data.tableConta);
				$('#tb_conta').bootstrapTable({});
				$('#tableSedesBanco').html(data.tableSedeBanco);
				$('.tree').treegrid({
					initialState: 'collapsed',
		            expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
		            expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
		        });
				$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip(); 
				});
				$('#tableMovimiento').html(data.tableMov);
				$('#tb_mov').bootstrapTable({});
				$("#img_table_empty3").css("display", "none");
				$("#tablaC").css("display", "block");
				$("#tablaSB").css("display", "block");
				$("#tablaM").css("display", "block");
			initSearchTable();
		});
	});
}

function openModalContaHistorial(idEmpresa, fechaIni, fechaFin) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_auditoria/changeContaHistorial",
	        data: {idEmpresa : idEmpresa,
	        	   fechaIni  : fechaIni,
	        	   fechaFin  : fechaFin},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
  			if (data.error == 1) {
  				mostrarNotificacion('warning', data.msj);
			} else {
				$('#tittleConta').html('Historial de '+data.nameEmpresa);
				$('#tableContaHistorial').html(data.tableContaHistorial);
				$('#tb_conta_historial').bootstrapTable({});
				initSearchTable();
				abrirCerrarModal('modalEmpresaHistorial');
			}
		});
	});	
}

function openModalMovHistorial(idPersona, fechaIni, fechaFin) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_auditoria/changeMovHistorial",
	        data: {idPersona : idPersona,
	        	   fechaIni  : fechaIni,
	        	   fechaFin  : fechaFin},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
  			if (data.error == 1) {
  				mostrarNotificacion('warning', data.msj);
			} else {
				$('#tittleMov').html('Historial de '+data.namePersona);
				$('#tableMovHistorial').html(data.tableMovHistorial);
				$('#tb_mov_historial').bootstrapTable({});
				initSearchTable();
				abrirCerrarModal('modalPersonaHistorial');
			}
		});
	});	
}

function openModaBancosHistorial(idBanco, idEmpresa, fechaIni, fechaFin) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_auditoria/changeBancoHistorial",
	        data: {idEmpresa  : idEmpresa,
	        	   idBanco    : idBanco,
	        	   fechaIni   : fechaIni,
	        	   fechaFin   : fechaFin},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
  			if (data.error == 1) {
  				mostrarNotificacion('warning', data.msj);
			} else {
				$('#tittleBanco').html('Historial de '+data.nameBanco);
				$('#tableBancoHistorial').html(data.tableBancoHistorial);
				$('#tb_banco_historial').bootstrapTable({ });
				initSearchTable();
				abrirCerrarModal('modalBancoHistorial');
			}
		});
	});	
}

function getTableByFiltro(){
	addLoadingButton('botonAS');
	var tableSelected = $('#selectTableFiltro option:selected').val();
	var bancoSelected = $('#selectBancoFiltro option:selected').val();
	var fechaInicio   = $('#fecInicioAS').val();
	var fechaFin      = $('#fecFinAS').val();
	
	if(tableSelected.trim( ) == '' || tableSelected.length == 0 || /^\s+$/.test(tableSelected)){
		return mostrarNotificacion('warning', 'Seleccione un  filtro');
	}
	if(fechaInicio.trim( ) == '' || fechaInicio.length == 0 || /^\s+$/.test(fechaInicio)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio');
	}
	if(fechaInicio > fechaFin){
		return mostrarNotificacion('warning', 'Ingrese Fecha Inicio debe ser menor a Fecha Fin');
	}
	if(fechaFin.trim( ) == '' || fechaFin.length == 0 || /^\s+$/.test(fechaFin)){
		return mostrarNotificacion('warning', 'Ingrese Fecha Fin');
	}
	$.ajax({
		data  : {tableSelected : tableSelected,
				 bancoSelected : bancoSelected,
			     fechaInicio   : fechaInicio,
			     fechaFin      : fechaFin},
		url   : 'c_auditoria/getTableByFiltro',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tab-auditoria .mdl-card').css('display','none');
		$('#'+data.content).parent().parent().css('display','block');
		$('#'+data.content).html(data.table);
		$('#tb_conta').bootstrapTable({ });
		$('#tb_mov').bootstrapTable({ });
		$('.tree').treegrid({
			initialState: 'collapsed',
            expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
            expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
        });
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip(); 
		});
		$('#cont_filter_empty4').css('display','none');
		stopLoadingButton('botonAS');
		modal('modalSelectFiltro');
	});
}

function changeVisibilityByIconTab(icon){
	if(!$('#tableSedesBanco').is(':visible')){
		$('#tableSedesBanco').fadeIn();
		$('#container_grafico_bancos').fadeOut();
		$('#iconBancos').removeClass().addClass('mdi mdi-insert_chart');
		var chart = $('#container_grafico_bancos').highcharts();
		if(chart != undefined) {
			chart.destroy();
		}
	} else{
		$('#tableSedesBanco').fadeOut();
		$('#container_grafico_bancos').fadeIn();
		$('#iconBancos').removeClass().addClass('mdi mdi-view_column');
		buildChartByTab('tab4');
	}
}

function buildChartByBanco(tab){
	var fecInicioGlobal = $('#fecInicioAS').val();
	var fecFinGlobal    = $('#fecFinAS').val();
	$.ajax({
		data  : {tab	      : tab,
				 fecInicio    : fecInicioGlobal,
				 fecFin       : fecFinGlobal},
		url   : 'c_auditoria/buildGraficoByTab',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			mostrarNotificacion('warning', 'No se puede generar gr&aacute;ficos sin datos.');
		}else {
			initGraficoBanco(JSON.parse(data.series));
			var chart = $('#container_grafico_bancos').highcharts();
			setTimeout(function(){
				chart.reflow();
		    },200);
		}
	});
}

function initGraficoBanco(series){
	var options = {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Bancos m\u00E1s usados'
        },
        exporting: { enabled: false },
        tooltip: {
            pointFormat: '<b>{point.percentage:.2f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        }
    };
    $('#container_grafico_bancos').highcharts(options);
    var chart = $('#container_grafico_bancos').highcharts();
	for(i = 0; i<series.length;i++){
		chart.addSeries({
			colorByPoint: true,
	        data  : series[i],
	        zIndex: 1,
	        pointWidth: 25
	    });
	}
}

$('#selectTableFiltro').change(function(){
	var index = $("#selectTableFiltro")[0].selectedIndex;
	if(index == 4){
		$('#comboBancos').css('display', 'block');
	} else{
		$('#comboBancos').css('display', 'none');
	}
});
