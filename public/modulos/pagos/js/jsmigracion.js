var arraySedes = [];

$("#userfile").change(function(){
	subirTxt();
});

function init(){
	initButtonLoad('botonImport','botonExport');
}

function importarTxt(){
	$('#userfile').trigger('click'); 
}

function subirTxt(){
	addLoadingButton('botonImport');
	var inputFileImage = document.getElementById("userfile");
    var file = inputFileImage.files[0];
    var formData = new FormData(); 
    formData.append('userfile', file);
	$.ajax({
		data : formData,
		url : "c_migracion/upload_txt",
		cache : false,
		contentType : false,
		processData : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		$('#userfile').val(null);
		if(data.error == 0){
			updateMigracionByBanco(data.namefile);
		} else{
			stopLoadingButton('botonImport');
			msj('warning','El archivo no es valido');
		}
	});
}

function updateMigracionByBanco(namefile){
	$.ajax({
		data : {namefile   : namefile,
				arraySedes : arraySedes,
				id_banco   : id_banco,
				accionM    : accionM,
				id_empresa : id_empresa},
		url : "c_migracion/update_migracion_banco",
		type  : 'POST',
		async : true
	}).done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0){
			abrirCerrarModal('modalImportarDatos');
			//Tabla de vista previa
			$('#tablePreview').html(data.tableUpd);
			$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
			modal('modalPreviewMigracion');
			$('#tb_preview').bootstrapTable({});
			initTablePreview();
		} else{
			mostrarNotificacion('warning', data.msj);
		}
		$('#formExcel').html(null);
		var input = '<input type="hidden" id="filename" name="filename">';
		$('#formExcel').html(input);
		//Elimina el documento
		setTimeout(function(){
			deleteFile(data.namefile);
		}, 3000);
		stopLoadingButton('botonImport');
	});
}

var empresaId = null; 
function abrirModalExportarCont(button){
	arraySedes = button.data('sedes');
	empresaId  = button.data('empresa');
	abrirCerrarModal('modalTipoExportacionCont');
}

function generateTxtFileByEmpresa(){
	var tipoTxt = $('#selectTipoExport option:selected').val();
	var mes     = $('#selectMes option:selected').val();
	var year    = $('#selectYear option:selected').val();
	var empresa = empresaId;
	$.ajax({
		data  : {arraySedes : arraySedes,
				 tipoTxt    : tipoTxt,
				 mes        : mes,
				 year       : year,
				 empresa    : empresa},
		url   : 'c_migracion/exportarDatosSiscont',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$('#filename').val(data.filename);
			$('#formExcel').submit();
			$('#tableEmpresas').html(data.table);
			$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
			setTimeout(function(){
				deleteFile(data.filename);
			}, 3000);
			abrirCerrarModal('modalTipoExportacionCont');
		}else{
			mostrarNotificacion('warning',data.msj);
//			deleteFile(data.filename);
//			abrirCerrarModal('modalTipoExportacionCont');
		}	
	});
}

function deleteFile(filename){
	$.ajax({
		data  : {filename : filename},
		url   : 'c_migracion/deleteFile',
		type  : 'POST',
		async : false
	})
	.done(function(data){
	});
}

var id_banco   = null;
var id_empresa = null;
var accionM    = null;

function openModalMigracion(banco/*,empresa*/,accion,button){
	abrirCerrarModal('modalMigrarDatos');//abre el modal
	arraySedes  = button.data('sedes');
	var text    = button.data('text');
	var textRef = button.data('ref');
	var empresa = button.data('empresa');
	$('#textAccion').html(text);
	$('#textRef').html(textRef);
	id_banco    = banco;
	accionM     = accion;
	id_empresa  = empresa;
}
function openModalImportar(banco,accion,button){
	abrirCerrarModal('modalImportarDatos');
	arraySedes  = button.data('sedes');
	var text    = button.data('text');
	var textRef = button.data('ref');
	var empresa = button.data('empresa');
	$('#textAccionI').html(text);
	$('#textRefI').html(textRef);
	id_banco    = banco;
	accionM     = accion;
	id_empresa  = empresa;
}

function migrarDatosBanco(){
	addLoadingButton('botonExport');
	$.ajax({
		data  : {sedes      : arraySedes,
			     id_banco   : id_banco,
			     accionM    : accionM,
			     id_empresa : id_empresa},
	    url   : 'c_migracion/migrarDatosBanco',//se envian las variables de data al controlador
	    type  : 'POST',
	    async : true
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$('#filename').val(data.nombre);//data.nombre contiene el nombre orignal del archivo para forzar la descarga
			$('#formExcel').submit();//ejecuta la descarga del txt generado
			$('#tableBancos').html(data.tabla);//repinta la tabla con los nuevos datos
			abrirCerrarModal('modalMigrarDatos');//cierra el modal
		}
		$(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
		$('.tree').treegrid({
			initialState: 'collapsed',
            expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
            expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
        });//establece que la table se encuentre colapsada
		stopLoadingButton('botonExport');
		setTimeout(function(){
			deleteFile(data.nombre);//elimina el archivo del sistema
		}, 3000);
		mostrarNotificacion('warning',data.msj);
	});
}

function confirmarMigracion(){
	$.ajax({
		url   : 'c_migracion/executeImportData',
	    async : true,
	    type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			modal('modalPreviewMigracion');
		} 
		msj('warning',data.msj);
	});
}

function initTablePreview(){
	$(function () {
	    $('#tb_preview').on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
	    })
	    .on('check.bs.table', function (e, row) {
	    	componentHandler.upgradeAllRegistered();

	    })
	    .on('uncheck.bs.table', function (e, row) {
	    	componentHandler.upgradeAllRegistered();

	    })
	    .on('check-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();

	    })
	    .on('uncheck-all.bs.table', function (e) {
	    	componentHandler.upgradeAllRegistered();

	    })
	    .on('load-success.bs.table', function (e, data) {

	    })
	    .on('load-error.bs.table', function (e, status) {

	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
	    })
	    .on('search.bs.table', function (e, text) {
	    	$(document).ready(function(){
	            $('[data-toggle="tooltip"]').tooltip(); 
	        });
	    });
	});
}