function initSimulacro() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	$('#tb_simulacro').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	tableEventsCerti();
	initButtonLoad( 'btnMF' );
}

function getPostulantesByUniv() {
	addLoadingButton('btnMF');
	var idAula  = $('#selectAula option:selected').val();
	var idUniv  = $('#selectUniv option:selected').val();
	var idGrado =  $('#selectGrado option:selected').val();
	var idSede  =  $('#selectSede option:selected').val();
	var nroSimu = $('#selectNroSimulacro option:selected').val();
	$.ajax({
		url: "c_simulacro/getPostulantesByUniv_CTRL",
        data: { idAula   : idAula,
        	    idUniv   : idUniv,
        	    idGrado  : idGrado,
        	    idSede   : idSede,
        	    nroSimu  : nroSimu},
        async : true,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
			stopLoadingButton('btnMF');
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error==2) {
				//setCombo('selectSede', null, 'Sede');
				setCombo('selectGrado', null, 'Grado');
				setCombo('selectAula', null, 'Aula');
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				$('#tb_simulacro').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				generarBotonMenu();
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();			
				if(data.comboNroSimu == 1) {
					setCombo('selectNroSimulacro', null, 'Nro. Simulacro');
				} else {
					setCombo('selectNroSimulacro', data.comboNroSimu, 'Nro. Simulacro');
				}

				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(2)').text($('#selectUniv option:selected').text());				
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectSede option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectAula option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectGrado option:selected').text());
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(6)').text($('#selectNroSimulacro option:selected').text());
				stopLoadingButton('btnMF');
			} else if(data.error == 1) {
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
				stopLoadingButton('btnMF');
			}
		}
	});
}

function getGradosBySede() {
	addLoadingButton('btnMF');
	var idSede =  $('#selectSede option:selected').val();
	$.ajax({
		url: "c_simulacro/comboGradosSede",
        data: { idSede   : idSede },
        async : true,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
			stopLoadingButton('btnMF');
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {
				setCombo('selectGrado', data.optGrado, 'Grado');
				setCombo('selectAula', null, 'Aula');
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				$('#tb_simulacro').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(3)').text($('#selectSede option:selected').text());
				componentHandler.upgradeAllRegistered();
				stopLoadingButton('btnMF');
			} else if(data.error == 1) {
				stopLoadingButton('btnMF');
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
		}
	});
}

function getAulasByGrado() {
	addLoadingButton('btnMF');	
	var idGrado =  $('#selectGrado option:selected').val();
	var idSede  =  $('#selectSede option:selected').val();
	var idUniv = $('#selectUniv option:selected').val();
	$.ajax({
		url: "c_simulacro/comboAulasGrado",
        data: { idGrado   : idGrado,
        	    idSede    : idSede,
        	    idUniv    : idUniv},
        async : true,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
			stopLoadingButton('btnMF');
		} else{
			data = JSON.parse(data);
			if(data.error == 0 || data.error == 2) {;
			setCombo('selectAula', data.optAula, 'Aula');
			if(data.comboNroSimu == 1) {
				setCombo('selectNroSimulacro', null, 'Nro. Simulacro');
			} else {
				setCombo('selectNroSimulacro', data.comboNroSimu, 'Nro. Simulacro');
			}
			$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
//			$('#tb_simulacro').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
			$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectAula option:selected').text());
			$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectGrado option:selected').text());
			$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(6)').text($('#selectNroSimulacro option:selected').text());
			stopLoadingButton('btnMF');
			} else if(data.error == 1){
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
				stopLoadingButton('btnMF');
			}
		}
	});
}

function getAlumnosByAula() {
	addLoadingButton('btnMF');	
	var idAula   = $('#selectAula option:selected').val();
	var idUniv   = $('#selectUniv option:selected').val();
	var nroSimu  = $('#selectNroSimulacro option:selected').val();
	Pace.restart();
	Pace.track(function() {
		addLoadingButton('btnMF');
		$.ajax({
			url: "c_simulacro/getAlumnosFromAula_CTRL",
	        data: { idAula   : idAula,
	        	    idUniv   : idUniv,
	        	    nroSimu  : nroSimu},
	        async : true,
	        type: 'POST'
		})
		.done(function(data){
			if(data == ""){
				location.reload();
				stopLoadingButton('btnMF');
			} else{
				stopLoadingButton('btnMF');
				data = JSON.parse(data);
				if(data.error == 0||data.error == 2) {
					$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
					$('#tb_simulacro').bootstrapTable({ });
					$('main section .mdl-content-cards .img-search').css('display', 'none');
					$('main section .mdl-content-cards .mdl-card').removeAttr('style');
					generarBotonMenu();
					$('.fixed-table-toolbar').addClass('mdl-card__menu');
					initSearchTableNew();
					tableEventsCerti();
					componentHandler.upgradeAllRegistered();	
					$('main section .mdl-content-cards .breadcrumb').css('display','block');
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(4)').text($('#selectAula option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(5)').text($('#selectGrado option:selected').text());
					$('main section .mdl-content-cards .breadcrumb li:NTH-CHILD(6)').text($('#selectNroSimulacro option:selected').text());
					stopLoadingButton('btnMF');
				} else if(data.error == 1) {
					$('main section .mdl-content-cards .breadcrumb').css('display','none');
					mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
					stopLoadingButton('btnMF');
				}	
			}
		});
	});
}

function guardarSimulacro(){ge
	var error = 0;
	var cont = 0;
	var json = {};
	var alumnos = [];
	json.alumno = alumnos;
	var arryDivs = getCheckedFromTablaByAttrFOCO('tb_simulacro', 3);
	
	var idUniv = $('#selectUniv option:selected').val();
	var flg_univ = null;
	$.ajax({
		url: "c_simulacro/checkUniv",
        data: { idUniv : idUniv},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		if(data == ""){
			location.reload();
			stopLoadingButton('btnMF');
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				flg_univ = data.flg_univ;
			} else {
				console.log('error al chekear la univ');
			}
		}
	});
	$.each( arryDivs, function( key, value ) {
		var idAlumno    	 = $(value).find(':checkbox').attr('attr-idalumno');
		var ptje_apt  		 = $(value).find(':checkbox').attr('attr-ptje_apto');
		var idSimu     		 = $(value).find(':checkbox').attr('attr-id_simu');
		var checkParticipo   = $(value).find(':checkbox').is(':checked');
		var fila             = $(value).find(':checkbox').attr('attr-fila');
		var alumno			 = {"idAlumno" 		 : idAlumno,
								"ptje_apto"		 : ptje_apt, 
								"idSimu"         : idSimu, 
								"checkParticipo" : checkParticipo,
								"fila"           : fila};
		if(flg_univ == 1) {
			if(checkParticipo == true && ptje_apt.trim().length == 0) {
				mostrarNotificacion('warning', 'Ingrese un puntaje en la fila: '+fila, '');
				error = 1;
				return;
			}
		} else {
			if(!(/^\d+(\.\d{0,}|\,\d{0,})?$/.test(ptje_apt)) && checkParticipo == true){
				mostrarNotificacion('warning', 'Ingrese un puntaje num&eacute;rico en la fila: '+fila, '');
				error = 1;
				return;
			}
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
	var idUniv   = $('#selectUniv option:selected').val();
	var idAula   = $('#selectAula option:selected').val();
	var idGrado  = $('#selectGrado option:selected').val();
	var idSede   = $('#selectSede option:selected').val();
	var nroSimu  = $('#selectNroSimulacro option:selected').val();
	$.ajax({
		type : 'POST',
		url : 'c_simulacro/guardarSimulacro_CTRL',
		data : { alumnos : jsonStringAlumnos ,
			     idUniv  : idUniv,
			     idAula  : idAula,
			     idGrado : idGrado,
			     idSede  : idSede,
			     nroSimu : nroSimu},
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contTablaAlumnosAula').html(data.tablaAlumnosAula);
				$('#tb_simulacro').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				generarBotonMenu();
				initSearchTableNew();	
				componentHandler.upgradeAllRegistered();
				//mostrarNotificacion('success', ''+data.msj, data.cabecera);
				if(cont == 1) {
					mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			    }else {
			    	mostrarNotificacion('success', 'Se ha modificado', 'Registro');
			    }
				if(data.comboNroSimu == 1) {
					setCombo('selectNroSimulacro', null, 'Nro. Simulacro');
				} else {
					setCombo('selectNroSimulacro', data.comboNroSimu, 'Nro. Simulacro');
					$('#selectNroSimulacro').val(data.id_nro_simu);
			    	$('#selectNroSimulacro').selectpicker('refresh');
				}
			}
		}
	});
}

function cambioCheckSimu(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckSimulacro("tb_simulacro", index, 3, cb.checked, cb.id, cb.getAttribute('attr-idalumno'), cb.getAttribute('attr-id_simu'),cb.getAttribute('attr-bd'));
}

function onChangeCheckSimulacro(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idSimu, bd){
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
	var ckPjje_simu = $('#check'+idx).attr('attr-ptje_apto');
	
	if(nuevoValor == false) {
		ckPjje_simu = "";
		var newCheck2 = null;
		var idUniv = $('#selectUniv option:selected').val();
		var flg_univ = null;
		$.ajax({
			url: "c_simulacro/checkUniv",
	        data: { idUniv : idUniv},
	        async : false,
	        type: 'POST'
		})
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
				if(data.error == 0) {
					flg_univ = data.flg_univ;
				} else {
					console.log('error al chekear la univ');
				}
			}
		});
		
		var ptjeSimu_BD = $('#ptje_apto_'+idx).attr('attr-bd');
		if(flg_univ) {
			var ptjeSimu_Checked = ($('#check'+idx).is(':checked')) == true ? 'checked' : null;
			newCheck2 = '    <label for="ptje_apto_'+idx+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
			            '       <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckIngreso(this);" id="ptje_apto_'+idx+'" attr-idalumno="'+idAlumno+'" attr-id_simu="'+idSimu+'" attr-bd="'+ptjeSimu_BD+'" attr-cambio="false" > '+
			            '        <span class="mdl-checkbox__label"></span>'+
			            '    </label>';
		}else {
			var ptjeSimu_Checked = $('#ptje_apto_'+idx).val();
			newCheck2 = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
			                 '<input type="text" onchange="onChangePuntaje(this);" class="mdl-textfield__input" value="" name="puntaje" id="ptje_apto_'+(idx)+'" attr-idalumno="'+idAlumno+'" '+
                             '       attr-id_simu="'+idSimu+' attr-bd="'+ptjeSimu_BD+'" attr-cambio="false" >'+
                             '<label class="mdl-textfield__label" for="ptje_apto_'+(idx)+'"></label>'+
                        '</div>';
		}

		$('#'+idTabla).bootstrapTable('updateCell',{
			rowIndex   : indexRow,
			fieldName  : 2,
			fieldValue : newCheck2
		});
	}
	
	
    var cambioPtje_apto = $('#ptje_apto_'+idx).attr('attr-cambio');
    var demas = false;
    if(cambioPtje_apto == 'true') {
		demas = true;
	}
    var foco = $('#check'+idx).attr('attr-foco');
    foco = (demas == false) ? cambio : foco;

	var newCheck = 
	               '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
	               '        <input type="checkbox" onclick="cambioCheckSimu(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'"  attr-idalumno="'+idAlumno+'" '+
	               '               attr-id_simu="'+idSimu+'" attr-ptje_apto="'+ckPjje_simu+'" attr-fila="'+idx+'" attr-foco="'+foco+'" class="mdl-checkbox__input"> '+
	               '        <span class="mdl-checkbox__label"></span>'+
                   '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
    	rowIndex   : indexRow,
    	fieldName  : indexCampo,//Celda 0 en la tabla
    	fieldValue : newCheck
    });
	
	componentHandler.upgradeAllRegistered();
}

function onChangePuntaje(input) {
	var index = $(input).closest('tr').attr('data-index');
	updateCellPuntaje("tb_simulacro", index, 3, input.value, input.id, input.getAttribute('attr-idalumno'), input.getAttribute('attr-id_simu'), input.getAttribute('attr-bd'));
}

function updateCellPuntaje(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idSimu, bd) {
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
	var parti_BD      = $('#check'+idx).attr('attr-bd');
	
	var cambioParticipo = $('#check'+idx).attr('attr-cambio');
	var demas = false;
	if(cambioParticipo == 'true') {
		demas = true;
	} 	 
	var foco = $('#check'+idx).attr('attr-foco');
	foco = (!demas) ? cambio : foco;
	var newCheck = '    <label for="check'+idx+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
				   '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckSimu(this);" '+check+' id="check'+idx+'" attr-ptje_apto="'+nuevoValor+'" attr-bd="'+parti_BD+'" attr-cambio="'+cambioParticipo+'" attr-idalumno="'+idAlumno+'" attr-id_simu="'+idSimu+'" '+
				   '               attr-foco="'+foco+'" attr-fila="'+idx+'" > '+
				   '        <span class="mdl-checkbox__label"></span>'+
				   '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
	   rowIndex   : indexRow,
	   fieldName  : indexCampo,
	   fieldValue : newCheck
	});
	
	var newCheck2 = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
	                    '<input type="text" onchange="onChangePuntaje(this);" class="mdl-textfield__input" value="'+nuevoValor+'" name="puntaje" id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'" attr-idalumno="'+idAlumno+'" attr-id_simu="'+idSimu+'">'+
	                    '<label class="mdl-textfield__label" for="'+id+'"></label>'+
	                '</div>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : 2,
		fieldValue : newCheck2
	});
	componentHandler.upgradeAllRegistered();
}

function cambioCheckApto(cb){
	var index = $(cb).closest('tr').attr('data-index');//console.log('index: '+index);
	onChangeCheckApto("tb_simulacro", index, 3, cb.checked, cb.id, cb.getAttribute('attr-idalumno'), cb.getAttribute('attr-id_simu'),cb.getAttribute('attr-bd'));
}

function onChangeCheckApto(idTabla, indexRow, indexCampo, nuevoValor, id, idAlumno, idSimu, bd){
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
	 var parti_Checked = ($('#check'+idx).is(':checked')) == true ? 'checked' : null;
	 var parti_BD      = $('#check'+idx).attr('attr-bd');
		
	 var cambioParticipo = $('#check'+idx).attr('attr-cambio');
	 var demas = false;
	 if(cambioParticipo == 'true') {
			demas = true;
	 }
	 var foco = $('#check'+idx).attr('attr-foco');
	 foco = (demas == false) ? cambio : foco;	
	 
	 var checkParti = '    <label for="check'+idx+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
					  '        <input type="checkbox" onclick="cambioCheckSimu(this);" '+check+' id="check'+idx+'" attr-ptje_apto="'+nuevoValor+'" attr-bd="'+parti_BD+'" attr-cambio="'+cambioParticipo+'"  attr-idalumno="'+idAlumno+'" attr-id_simu="'+idSimu+'" '+
					  '               attr-foco="'+foco+'" attr-fila="'+idx+'" class="mdl-checkbox__input"> '+
					  '        <span class="mdl-checkbox__label"></span>'+
					  '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
		rowIndex   : indexRow,
		fieldName  : indexCampo,
		fieldValue : checkParti
	});

	var newCheck = '    <label for="'+id+'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'+
	               '        <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckApto(this);" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambio+'"  attr-idalumno="'+idAlumno+'"  attr-id_simu="'+idSimu+'"> '+
	               '        <span class="mdl-checkbox__label"></span>'+
                   '    </label>';
	$('#'+idTabla).bootstrapTable('updateCell',{
    	rowIndex   : indexRow,
    	fieldName  : 2,
    	fieldValue : newCheck
    });
	componentHandler.upgradeAllRegistered();
}

function generarBotonMenu(){
	var div = $('#contTablaAlumnosAula .bootstrap-table .fixed-table-toolbar .columns.columns-right.btn-group.pull-right .keep-open.btn-group');
	div.append('<button type="button" class="mdl-button mdl-js-button mdl-button--icon dropdown-toggle" onclick="guardarSimulacro()" id="btnGuardarTabla">'+
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
		url  : 'c_simulacro/logOut', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		window.location.href = "";
	});
}

function tableEventsCerti(){
	var textGlob = null; 
	$(function () { 
		$('#tb_simulacro').on('all.bs.table', function (e, name, args) {
			
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