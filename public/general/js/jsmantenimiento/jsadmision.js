function initAdmision() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_admision').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEventsCerti('contTablaAlumnosAula');
	initButtonLoad( 'btnMF' );
}

function getPostulantesByUniv() {
	var idAula = $('#selectAula option:selected').val();
	var idUniv = $('#selectUniv option:selected').val();
	$.ajax({
		url: "c_admision/getPostulantesByUniv_CTRL",
        data: { idAula   : idAula,
        	    idUniv   : idUniv},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error==2) {
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				$('#tb_admision').bootstrapTable({ });
				generarBotonMenu();
				initSearchTableNew();				
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				tableEventsCerti('tb_admision');
				componentHandler.upgradeAllRegistered();
			} else if(data.error == 1) {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getGradosBySede() {
	var idSede = $('#selectSede option:selected').val();
	$.ajax({
		url: "c_admision/comboGradosSede",
        data: { idSede   : idSede },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectGrado', data.optGrado, 'Grado');
				setCombo('selectAula', null, 'Aula');
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				//$('#tb_admision').bootstrapTable({ });
				//initSearchTable();
			} else if(data.error == 1) {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getAulasByGrado() {
	var idGrado =  $('#selectGrado option:selected').val();
	var idSede  =  $('#selectSede option:selected').val();
	$.ajax({
		url: "c_admision/comboAulasGrado",
        data: { idGrado   : idGrado,
        	    idSede    : idSede},
        async : false,
        type: 'POST'
	})
	.done(function(data) {
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectAula', data.optAula, 'Aula');
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				tableEventsCerti('contTablaAlumnosAula');
				$('#cont_search_empty .img-search').removeAttr('style');
				//$('#tb_admision').bootstrapTable({ });
				//initSearchTable();
			} else if(data.error == 1) {
				$('#cont_search_empty .img-search').removeAttr('style');
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getAlumnosByAula() {
	addLoadingButton('botonMF');
	var idAula = $('#selectAula option:selected').val();
	var idUniv = $('#selectUniv option:selected').val();
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMF');
		$.ajax({
			url: "c_admision/getAlumnosFromAula_CTRL",
	        data: { idAula   : idAula,
	        	    idUniv   : idUniv},
	        async : true,
	        type: 'POST'
		})
		.done(function(data){
			if(data == ""){
				location.reload();
				stopLoadingButton('botonMF');
			} else{
				stopLoadingButton('btnMF');
				data = JSON.parse(data);
				if(data.error == 0||data.error == 2) {
					$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
					$('#tb_admision').bootstrapTable({ });
					$('main section .mdl-content-cards .img-search').css('display', 'none');
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectUniv  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectSede  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectGrado option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectAula  option:selected').text());
					$('main section .mdl-content-cards .breadcrumb').removeAttr('style');
					$('main section .mdl-content-cards .mdl-card').removeAttr('style');
					initSearchTableNew();
					generarBotonMenu();
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					abrirCerrarModal("modalFiltro");
					componentHandler.upgradeAllRegistered();
					stopLoadingButton('botonMF');
				} else if(data.error == 1) {
					mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
					stopLoadingButton('botonMF');
				}
			}
		});
	});
}

function guardarAdmision(){
	var error = 0;
	var cont = 0;
	var json = {};
	var alumnos = [];
	json.alumno = alumnos;
	var arryDivs = getCheckedFromTablaByAttrFOCO('tb_admision', 3);
	
	$.each( arryDivs, function( key, value ) {
		var idAlumno       = $(value).find(':checkbox').attr('attr-idalumno');
		var ptje_ingr      = $(value).find(':checkbox').attr('attr-ptje_ingre');
		var ingreso        = $(value).find(':checkbox').attr('attr-flg_ingre');
		var idAdmin        = $(value).find(':checkbox').attr('attr-id_admin');
		var checkParticipo = $(value).find(':checkbox').is(':checked');
		var fila           = $(value).find(':checkbox').attr('attr-fila');
		var alumno         = {"idAlumno"       : idAlumno, 
						      "ptje_ingre"     : ptje_ingr, 
						      "ingreso"        : ingreso,
						      "idAdmin"        : idAdmin, 
						      "checkParticipo" : checkParticipo, 
						      "fila"           : fila};
		if(checkParticipo == true && ptje_ingr.trim().length == 0) {
			mostrarNotificacion('warning', 'Ingrese un puntaje en la fila: '+fila, '');
			error = 1;
			return;
		}
		if(!(/^\d+(\.\d{0,}|\,\d{0,})?$/.test(ptje_ingr)) && checkParticipo == true) {
			mostrarNotificacion('warning', 'Ingrese un puntaje num&eacute;rico en la fila: '+fila, '');
			error = 1;
			return;
		}
		json.alumno.push(alumno);
		cont++;
	});
	if(error == 1) {
		return;
	}
	if(cont == 0) {
		//mostrarNotificacion('warning', 'Haga cambios para actualizar');
		return;
	}
	var jsonStringAlumnos = JSON.stringify(json);
	var idUniv  = $('#selectUniv option:selected').val();
	var idAula  = $('#selectAula option:selected').val();
	var idGrado = $('#selectGrado option:selected').val();
	var idSede  = $('#selectSede option:selected').val();
	$.ajax({
		type : 'POST',
		url : 'c_admision/guardarAdmision_CTRL',
		data : { alumnos : jsonStringAlumnos ,
			     idUniv  : idUniv,
			     idAula  : idAula,
			     idGrado : idGrado,
			     idSede  : idSede},
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				$('#tb_admision').bootstrapTable({ });
				initSearchTableNew();
				generarBotonMenu();
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				componentHandler.upgradeAllRegistered();
				if(cont == 1) {
					mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			    }else {
			    	mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			    }
			} 
		}
	});
}

function cambioCheckAdmin(cb) {
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckAdmision("tb_admision", index, 3, cb.checked, cb.id, cb.getAttribute('attr-idalumno'),cb.getAttribute('attr-id_admin'), cb.getAttribute('attr-bd'));
	tableEventsCerti('tb_admision');
}

function onChangeCheckAdmision(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idAdmin, bd){
	var check = "checked";
	if(nuevoValor == false){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	var classC = "";
	 if(nuevoValor == bdVal) {
		cambio = false;
		classC = "";
	} else  {
		cambio = true;
		classC = "checkbox-gris";
	}
	var idx = parseInt(indexRow) + 1;
	var ckPjje_ingre = $('#check'+idx).attr('attr-ptje_ingre');
	
	if(nuevoValor == false) {
		var ingre_BD = $('#ingre_'+idx).attr('attr-bd');
		newCheckIngreso = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="ingre_'+idx+'"> '+
		              '        <input type="checkbox" onclick="cambioCheckIngreso(this);" class="mdl-checkbox__input" id="ingre_'+idx+'" attr-idalumno="'+idAlumno+'" attr-id_admin="'+idAdmin+'" attr-bd="'+ingre_BD+'" attr-cambio="false" > '+
		              '        <span class="mdl-checkbox__label"></span>'+
			              '</label> ';
		
		$('#'+idTabla).bootstrapTable('updateCell',{
			rowIndex   : indexRow,
			fieldName  : 4,
			fieldValue : newCheckIngreso
		});
		
		var ptjeIngre_Checked = $('#pjte_ingre_'+idx).val();
		var ptjeIngre_BD = $('#pjte_ingre_'+idx).attr('attr-bd');
		newCheckPtje = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
			           '<input type="text" onchange="onChangePuntaje(this);" class="mdl-textfield__input" value="" name="puntaje" id="pjte_ingre_'+(idx)+'" attr-idalumno="'+idAlumno+'" '+
		               '       attr-id_admin="'+idAdmin+' attr-bd="'+ptjeIngre_BD+'" attr-cambio="false" class="mdl-textfield__input" >'+
		               '<label class="mdl-textfield__label" for="pjte_ingre_'+(idx)+'"></label>'+
		               '</div>';
		$('#'+idTabla).bootstrapTable('updateCell',{
			rowIndex   : indexRow,
			fieldName  : 2,
			fieldValue : newCheckPtje
		});
		//componentHandler.upgradeAllRegistered();
	}
	
	var cambioPtje_ingre = $('#pjte_ingre_'+idx).attr('attr-cambio');
	var cambio_ingre     = $('#ingre_'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioPtje_ingre == 'true'|| cambio_ingre == 'true') {
		demas = true;
	}
	var foco = $('#check'+idx).attr('attr-foco');
    foco = (demas == false) ? cambio : foco;
	
    var ckPjje_ingre = $('#check'+idx).attr('attr-ptje_ingre');
	var newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'"> '+
	               '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckAdmin(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idalumno="'+idAlumno+'" '+
	               '               attr-id_admin="'+idAdmin+'" attr-ptje_ingre="'+ckPjje_ingre+'" attr-fila="'+idx+'" attr-foco="'+foco+'" > '+        
	               '        <span class="mdl-checkbox__label"></span>'+
                   '</label> ';
	
	$('#'+idTabla).bootstrapTable('updateCell',{
    	rowIndex   : indexRow,
    	fieldName  : indexCampo,
    	fieldValue : newCheck
    });
	
	componentHandler.upgradeAllRegistered();
	/*componentHandler.upgradeElement($("#"+id).parent()[0]);
	componentHandler.upgradeElement($("#ingre_"+idx).parent()[0]);
	componentHandler.upgradeElement($("#pjte_ingre_"+(idx)).parent()[0]);*/
}


function onChangePuntaje(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellPuntaje("tb_admision", index, 3, input.value, input.id, input.getAttribute('attr-idalumno'), input.getAttribute('attr-id_admin'), input.getAttribute('attr-bd'));
	
}

function updateCellPuntaje(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idAdmin, bd) {
	var check = "checked";
	if(nuevoValor.length == 0) {
		check = "";
	}
	var cambio = false; 
	var classC = "";
	 if(nuevoValor == bd) {
		cambio = false;
		classC = "";
	} else  {
		cambio = true;
		classC = "checkbox-gris";
	}
	var idx = parseInt(indexRow) + 1;
	var parti_Checked = ($('#check'+idx).is(':checked')) == true ? 'checked' : null;
	var ingre_Checked = ($('#ingre_'+idx).is(':checked')) == true ? 'checked' : null;
	var parti_BD      = $('#check'+idx).attr('attr-bd');
	
	var cambioParticipo = $('#check'+idx).attr('attr-cambio');
	var cambioIngreso   = $('#ingre_'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioParticipo == 'true' || cambioIngreso == 'true') {
		demas = true;
	}
	var foco = $('#check'+idx).attr('attr-foco');
    foco = (!demas) ? cambio : foco;
	var newCheck = '<label for="check'+idx+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" id="cont_cb_'+idx+'">'+ 			
				   '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckAdmin(this);" '+check+' id="check'+idx+'" attr-ptje_ingre="'+nuevoValor+'" attr-flg_ingre="'+ingre_Checked+'" attr-bd="'+parti_BD+'" attr-cambio="'+cambioParticipo+'" attr-idalumno="'+idAlumno+'" attr-id_admin="'+idAdmin+'" '+
				   '               attr-foco="'+foco+'" attr-fila="'+idx+'" > '+
				   '        <span class="mdl-checkbox__label"></span>'+
				   '</label> ';
	$('#'+idTabla).bootstrapTable('updateCell',{
	    rowIndex   : indexRow,
	    fieldName  : indexCampo,
	    fieldValue : newCheck
	}); 
	var newCheck2 = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
	                   '<input type="text" onchange="onChangePuntaje(this);" class="mdl-textfield__input" value="'+nuevoValor+'" name="puntaje" id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idalumno="'+idAlumno+'" attr-id_admin="'+idAdmin+'">'+
	                   '<label class="mdl-textfield__label" for="'+id+'"></label>'+
	                 '</div>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck2
	});
	componentHandler.upgradeAllRegistered();
}

function cambioCheckIngreso(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckIngreso("tb_admision", index, 3, cb.checked, cb.id, cb.getAttribute('attr-idalumno'), cb.getAttribute('attr-id_admin'),cb.getAttribute('attr-bd'));
	tableEventsCerti('tb_admision');
}

function onChangeCheckIngreso(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idAdmin, bd){
	var check = "checked";
	if(nuevoValor == false){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false;
	var cambio = false;
	var classC = "";
	 if(nuevoValor == bdVal) {
		cambio = false;
		classC = "";
	} else {
		cambio = true;
		classC = "checkbox-gris";
	}
	var idx = parseInt(indexRow) + 1;
	var parti_Checked = ($('#check'+idx).is(':checked')) == true ? 'checked' : null;
	var parti_BD      = $('#check'+idx).attr('attr-bd');
	
	var ptje = $('#pjte_ingre_'+idx).val();
	
	var cambioParticipo = $('#check'+idx).attr('attr-cambio');
	var cambioPtje      = $('#pjte_ingre_'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioParticipo == 'true' || cambioPtje == 'true') {
		demas = true;
	}
	var foco = $('#check'+idx).attr('attr-foco');
    foco = (demas == false) ? cambio : foco;

    var ckParti = (ptje == null || ptje.trim().length == 0) && (check == "") ? '' : 'checked';
    var checkIngre = (check == "checked") ? "true" : '';
   	var checkParti = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="check'+idx+'"> '+
				     '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckAdmin(this);" '+ckParti+' id="check'+idx+'" attr-ptje_ingre="'+ptje+'" attr-flg_ingre="'+checkIngre+'" attr-bd="'+parti_BD+'" attr-cambio="'+cambioParticipo+'"  attr-idalumno="'+idAlumno+'" attr-id_admin="'+idAdmin+'" '+
				     '               attr-foco="'+foco+'" attr-fila="'+idx+'" > '+
				     '        <span class="mdl-checkbox__label"></span>'+
				     '</label> ';
	$('#'+idTabla).bootstrapTable('updateCell',{
	    rowIndex   : indexRow,
	    fieldName  : indexCampo,
	    fieldValue : checkParti
	});
	
	var newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'"> '+
	               '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckIngreso(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idalumno="'+idAlumno+'" attr-id_admin="'+idAdmin+'"> '+
	               '        <span class="mdl-checkbox__label"></span>'+
                   '</label> ';
	$('#'+idTabla).bootstrapTable('updateCell',{
       	rowIndex   : indexRow,
    	fieldName  : 4,
    	fieldValue : newCheck
    });
	
	//componentHandler.upgradeAllRegistered();
}

function generarBotonMenu(){
	var div = $('#contTablaAlumnosAula .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="guardarAdmision()" id="btnGuardarTabla" >'+
	        		'<i class="mdi mdi-save"></i>'+
			   '</button>'+
			   '<div class="btn-group btn-group-sm pull-right">'+
			        '<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" data-toggle="dropdown" id="buttonMoreVert">'+
		            	'<i class="mdi mdi-more_vert"></i>'+
			        '</button>'+
			        '<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="buttonMoreVert">' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-print"></i> Imprimir</li>' +
			        	'<li class="mdl-menu__item"><i class="mdi mdi-file_download"></i> Descargar</li>' +
					'</ul>'+
			    '</div>');
}

function logOut(){
	$.ajax({
		url  : 'c_admision/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function tableEventsCerti(idTablaContenedora){
	var textGlob = null; 
	$(function () { 
		$('#'+idTablaContenedora).on('all.bs.table', function (e, name, args) {
			
	    })
	    .on('click-row.bs.table', function (e, row, $element) {
	    	//componentHandler.upgradeAllRegistered();
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    	//$element.removeClass("text-center");
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	//componentHandler.upgradeAllRegistered();
	    })
	    .on('check.bs.table', function (e, row) {
	    	//componentHandler.upgradeAllRegistered();
	    })
	    .on('uncheck.bs.table', function (e, row) {
	    	//componentHandler.upgradeAllRegistered();
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