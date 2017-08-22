function initExcelCorreos() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}	
	initButtonLoad( 'btnMF' );
}

function getNivelGradosBySede() {
	var idSede = $('#cmbSede option:selected').val();
	Pace.restart();
	Pace.track(function() {
		if($.trim(idSede).length > 0) {
			$.ajax({
				url: "c_excel_correos/getComboGradosNivelBySede",
		        data: { idSede   : idSede },
		        type: 'POST'
			})
			.done(function(data) {
				data = JSON.parse(data);
				if(data.error == 0 || data.error == 2) {
					setCombo('cmbGradoNivel', data.optGradoNivel, 'Nivel Grado');
					setCombo('cmbAula', null, 'Aula');
					actualizarTabla(data);
				} else if(data.error == 1) {
					mostrarNotificacion('error', data.msj, 'Error');
				}
			});
		} else {
			setCombo('cmbGradoNivel', null, 'Nivel Grado');
			setCombo('cmbAula', null, 'Aula');
			actualizarTabla(null);
		}
	});
}

function getAulasByGradoNivel() {
	var idSede = $('#cmbSede option:selected').val();
	var idgradoNivel = $('#cmbGradoNivel option:selected').val();
	Pace.restart();
	Pace.track(function() {
		if($.trim(idgradoNivel).length > 0) {
			$.ajax({
				url: "c_excel_correos/getComboAulasByGradoNivel",
		        data: { idSede       : idSede,
		        	    idgradoNivel : idgradoNivel },
		        type: 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0 || data.error == 2) {
					setCombo('cmbAula', data.optAulas, 'Aula');
					actualizarTabla(data);
				} else if(data.error == 1) {
					mostrarNotificacion('error', data.msj, 'Error');
				}
			});
		} else {
			getNivelGradosBySede();
		}
	});
}

var arrayCorreos = [];
function getCorreosByAula() {
	addLoadingButton('btnMF');
	var idSede = $('#cmbSede option:selected').val();
	var idgradoNivel = $('#cmbGradoNivel option:selected').val();
	var idAula = $('#cmbAula option:selected').val();
	Pace.restart();
	Pace.track(function() {
		if($.trim(idAula).length > 0) {
			$.ajax({
				url: "c_excel_correos/getCorreosBySede",
		        data: { idAula       : idAula,
			        	idSede       : idSede,
			    	    idgradoNivel : idgradoNivel},
		        type: 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0) {
					//refrescar tabla
					actualizarTabla(data);
					
					stopLoadingButton('btnMF');
				} else {
					$('#contTablaCorreos').html(null);
					stopLoadingButton('btnMF');
				}
			});
		} else {
			getAulasByGradoNivel();
			stopLoadingButton('btnMF');
		}
	});
}

function actualizarTabla(data) {
	$('#contTablaCorreos').html((data != null) ? data.tablaCorreos : null);
	arrayCorreos = JSON.parse((data != null)   ? data.correosArray : null);
	$('#tb_correos').bootstrapTable({ });
	initSearchTableNew();
	generarBotonMenu();
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEventsCerti();
	$('main section .mdl-content-cards .img-search').css('display', 'none');
	$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text(  getComboVal('cmbSede') != '' ? $('#cmbSede option:selected').text() : 'Ninguna sede');
	$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text( (getComboVal('cmbGradoNivel') != '' && getComboVal('cmbSede') != '') ? $('#cmbGradoNivel option:selected').text() : ( getComboVal('cmbSede') == '' ? 'Ningún grado' : 'Todos los grados' ));
	$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text( (getComboVal('cmbAula') != '' && getComboVal('cmbSede') != '') ? $('#cmbAula option:selected').text() : (getComboVal('cmbSede') == '' ? 'Ningún aula' : 'Todos las aulas') );
	$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
	$('main section .mdl-content-cards .mdl-card').removeAttr('style');
	componentHandler.upgradeAllRegistered();
}

function quitarPonerCorreos(chkBox) {
	var checked = chkBox.is(":checked");
	var cnt = 0;
	$.each(arrayCorreos, function( index, value ) {
		if(value.correo == chkBox.data('correo')) {//ELIMINA
			arrayCorreos.splice(index, 1);
			cnt++;
			return false;
		}
	});
	if(cnt == 0) {
		arrayCorreos.splice(arrayCorreos.length, 0, {correo: chkBox.data('correo') } );
	}
}

function generarExcel() {
	//var idAula = $('#cmbAula option:selected').val();
	if(arrayCorreos.length > 0 /*&& $.trim(idAula).length > 0*/) {
		/*var url = '/users/' + $('#user_id').val();
	    $('#myform').attr('action', url);*/
	    var data = JSON.stringify({ "param": arrayCorreos });
	    $('<input type="hidden" name="json" id="json"/>').val(data).appendTo('#formExcel');
	    //$('<input type="hidden" name="idAula" id="idAula"/>').val(idAula).appendTo('#formExcel');
		$('#formExcel').submit();
	}
}

function generarBotonMenu(){
	var div = $('#contTablaCorreos .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="generarExcel()" id="btnGenExcel" >'+
	        		'<i class="mdi mdi-save"></i>'+
			    '</button>');
}

function abrirCerrarModal(){
	$('#modalFiltro').modal({
	    backdrop: 'static',
	    keyboard: false
	});
}

function tableEventsCerti(){
	var textGlob = null; 
	$(function () { 
		$('#tb_correos').on('all.bs.table', function (e, name, args) {
			
	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {

	    })
	    .on('uncheck.bs.table', function (e, row) {

	    })
	    .on('check-all.bs.table', function (e) {

	    })
	    .on('uncheck-all.bs.table', function (e) {

	    })
	    .on('load-success.bs.table', function (e, data) {

	    })
	    .on('load-error.bs.table', function (e, status) {

	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}