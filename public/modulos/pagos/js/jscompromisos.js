var total_seleccionados=0; var count_scroll_c = 1;
var count_check_compromisosGlobales = 0;
var count_check_compromisosAlu = 0;
var flg_filtro_activo = 0;

function init(){
	initButtonLoad('botonRCA','botonCFMC','save');
}

function loadCompromisosModal(id,combo){
	componentHandler.upgradeAllRegistered();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/loadConceptos",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				if(data.opConceptos != ''){ 
					$("#"+id+" #"+combo).parent().show();
					setCombo(combo, data.opConceptos, 'Concepto',null);
					componentHandler.upgradeAllRegistered();
				}
				else{
					$("#"+id+" #"+combo).parent().hide();
				}
			    $("#"+id+" #"+combo).change(function(){
			    	if($(this).val()!='')
					{   
						$("#"+id+" #descripcion").parent().hide();
						setearInput('monto', $(this).find(':selected').attr("data-monto"));
					}
					else{
						$("#"+id+" #descripcion").parent().show();
						$("#"+id+" #monto").val('');
					}
			    });
			}
			else if(data.error == 1) {
			}
		});
	});	
}

function registrarCompromisosMultiples() {
	addLoadingButton('save');
	var alumnos     = Array(); f=0;
	var concepto    = $("#modalSaveCompromisos #conceptosCompromisos").val();
	var descripcion = $("#modalSaveCompromisos #descripcion").val();
	var monto 	    = $("#modalSaveCompromisos #monto").val();
	
	for(i=1;i<=$("#cardsCompromisos #total_cards").val();i++){
		j=1;
		$("#cardsCompromisos #aula_"+i+" #lista_estudiantes table tbody tr td").each(function(){
			if($(this).hasClass("id")){
				if($('#cardsCompromisos input[name="student_aulas['+i+']['+j+']"]').attr("checked"))
				{alumnos[f] = $('#cardsCompromisos input[name="student_aulas['+i+']['+j+']"]').val();
				 j++; 
				 f++;
				}
			}
		});
	} 
	$("#modalSaveCompromisos #save").attr("disabled");
	$.ajax({
		url : "c_compromisos/saveCompromisosMulti",
        data: { alumnos 	      : alumnos,
        		concepto          : concepto,
        	    descripcion       : descripcion,
        	    monto             : monto
        	  },
        async: true,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			mostrarNotificacion('warning' , data.msj,'');
			$("#modalSaveCompromisos #conceptosCompromisos").val('');
			$("#cardsCompromisos .mdl-card .mdl-card__menu .mdl-checkbox__input").parent().removeClass("is-checked");
			$("#cardsCompromisos input").removeAttr("checked");
			$("#modalSaveCompromisos").hide();
			$("#filtroCompromisos").hide();
			$("#cardsCompromisos").hide();
			$("#imgExtras").show();
		//	$("#imgExtras").html(data.imagen);
			stopLoadingButton('save');
		}else if(data.error == 1) {
			stopLoadingButton('save');
			mostrarNotificacion('warning' , data.msj,'');
		}
	});
}

function getNivelesBySede() {
	addLoadingButton('botonCFMC');
	var idSede =  $('#modalFiltroCompromiso #selectSede option:selected').val();
	var sedeText = $('#modalFiltroCompromiso #selectSede option:selected').text();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/comboSedesNivel",
	        data: { idSede 	      : idSede},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#tableCompromisosGenerados").hide();
			    setCombo('selectNivel', data.optNivel, 'Nivel',null);
			    setCombo('selectGrado', null, 'Grado',null);
			    $("#filtroExtras").show();
			    $("#filtroCompromisos").show();
			    $("#cardsCompromisos").show();
				$('#cardsCompromisos').html(data.cards);
				$("#imgExtras").hide();
				$("#filtroCompromisos label").removeClass("is-checked");
				$('#laelSede').html(sedeText);
				//$('#laelNivel').html('-');
				//$('#laelGrado').html('-');
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelSede').addClass('active');
			    $('#laelSede').css('visibility','visible');
			    $('#laelNivel').css('visibility','hidden');
			    $('#laelGrado').css('visibility','hidden');
			    stopLoadingButton('botonCFMC');
				componentHandler.upgradeAllRegistered();
			}else if(data.error == 1) {
				setCombo('selectNivel', data.optNivel, 'Nivel',null);
				setCombo('selectGrado', null, 'Grado',null);
				stopLoadingButton('botonCFMC');
			    $('#laelSede').css('visibility','hidden');
				
			}
		});
	});
}


function getGradosByNivel() {
	addLoadingButton('botonCFMC');
	var idSede    =  $('#modalFiltroCompromiso #selectSede option:selected').val();
	var idNivel   =  $('#modalFiltroCompromiso #selectNivel option:selected').val();
	var sedeText  = $('#modalFiltroCompromiso #selectSede option:selected').text();
	var nivelText = $('#modalFiltroCompromiso #selectNivel option:selected').text();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/comboNivelGrado",
	        data: { idSede 	      : idSede,
	        	    idNivel       : idNivel
	        },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#tableCompromisosGenerados").hide();
				$("#cardsCompromisos").show();
			    setCombo('selectGrado', data.optGrado, 'Nivel',null);
			    $("#filtroCompromisos").show();
				$('#cardsCompromisos').html(data.cards);
				$("#filtroCompromisos label").removeClass("is-checked");
				$('#laelSede').html(sedeText);
				$('#laelNivel').html(nivelText);
				//$('#laelGrado').html('-');
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelNivel').addClass('active');
			    $('#laelNivel').css('visibility','visible');
			    $('#laelGrado').css('visibility','hidden');
			    stopLoadingButton('botonCFMC');
				componentHandler.upgradeAllRegistered();
			}else if(data.error == 1) {
				setCombo('selectGrado', null, 'Nivel',null);
				stopLoadingButton('botonCFMC');
			    $('#laelNivel').css('visibility','hidden');
			}
			
		});
	});
}

function getGrados() {
	addLoadingButton('botonCFMC');
	var idSede    = $('#modalFiltroCompromiso #selectSede option:selected').val();
	var idNivel   = $('#modalFiltroCompromiso #selectNivel option:selected').val();
	var idGrado   = $('#modalFiltroCompromiso #selectGrado option:selected').val();
	var sedeText  = $('#modalFiltroCompromiso #selectSede option:selected').text();
	var nivelText = $('#modalFiltroCompromiso #selectNivel option:selected').text();
	var gradoText = $('#modalFiltroCompromiso #selectGrado option:selected').text();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/comboGrado",
	        data: { idSede 	      : idSede,
	        	    idNivel       : idNivel,
	        	    idGrado       : idGrado
	        	  },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$("#tableCompromisosGenerados").hide();
				$("#filtroCompromisos").show();
				$("#cardsCompromisos").show();
				$('#cardsCompromisos').html(data.cards);
				$("#filtroCompromisos label").removeClass("is-checked");
				$('#laelSede').html(sedeText);
				$('#laelNivel').html(nivelText);
				$('#laelGrado').html(gradoText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelGrado').addClass('active');
				stopLoadingButton('botonCFMC');
			    $('#laelGrado').css('visibility','visible');
				componentHandler.upgradeAllRegistered();
			}else if(data.error == 1) {
				stopLoadingButton('botonCFMC');
			    $('#laelGrado').css('visibility','hidden');
			}
		});
	});
}

function AllcheckUpdateCompromisoDelete() {
	var tableData = $("#tb_lista_compromisoAlu").bootstrapTable('getData');

	if(($("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso_global").parent().hasClass("is-checked"))){
	    $("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso_global").parent().removeClass("is-checked");
	    var cont = 1;
		$.each(tableData, function(val,i){
			var mov = $(this[0]).find('input').attr('data-mov');
			noCheck =   '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'+cont+'">' +
						    '<input type="checkbox" data-mov = "'+mov+'" id="checkbox-compromiso-'+cont+'" class="mdl-checkbox__input"  onclick="checkUpdateCompromisoDelete(\''+cont +'\');assignItemAUX(this.id, \'tb_lista_compromisoAlu\', \'cabeConfirm\');">'+
						    '<span class="mdl-checkbox__label"></span>' +
						 '</label>';
			$('#tableCompromisosGenerados #tb_lista_compromisoAlu').bootstrapTable('updateCell',{
				rowIndex   : cont-1,
				fieldName  : 0,
				fieldValue : noCheck 
		    });
			cont++;
		});
		assignItemCOM('tb_lista_compromisoAlu', 'cabeConfirm');
		count_check_compromisosGlobales=0;
	} else{
		$("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso_global").parent().addClass("is-checked");
		var cont1 = 1;
		$.each(tableData, function(val,i){
			var mov = $(this[0]).find('input').attr('data-mov');
			Check ='<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events is-upgraded is-checked" for="checkbox-compromiso-'+cont1+'" data-upgraded=",MaterialCheckbox,MaterialRipple">'+
						'<input type="checkbox" data-mov  = "'+mov+'" id="checkbox-compromiso-'+cont1+'" checked class="mdl-checkbox__input" onclick="checkUpdateCompromisoDelete(\''+cont1+'\');assignItemAUX(this.id, \'tb_lista_compromisoAlu\', \'cabeConfirm\');">'+
						'<span class="mdl-checkbox__label"></span>'+
						'<span class="mdl-checkbox__focus-helper"></span><span class="mdl-checkbox__box-outline">'+
						'<span class="mdl-checkbox__tick-outline"></span></span>'+
						'<span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">'+
						'<span class="mdl-ripple"></span></span>'+
					'</label>';
			$('#tableCompromisosGenerados #tb_lista_compromisoAlu').bootstrapTable('updateCell',{
				rowIndex   : cont1-1,
				fieldName  : 0,
				fieldValue : Check 
		    });
			cont1++;
		});
		assignItemCOM('tb_lista_compromisoAlu', 'cabeConfirm');
		count_check_compromisosGlobales = tableData;
	}
	tableEventsMeses("#tb_lista_compromisoAlu");
}

function checkUpdateCompromisoDelete(id) {
	var mov = $('#checkbox-compromiso-'+id).data('mov');
	if(($("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso-"+id).parent().hasClass("is-checked"))) {
	    $("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso-"+id).parent().removeClass("is-checked");
	    newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'+id+'">' +
				      '<input type="checkbox" data-mov="'+mov+'" id="checkbox-compromiso-'+id+'" class="mdl-checkbox__input" onclick="checkUpdateCompromisoDelete(\''+id +'\');assignItemAUX(this.id, \'tb_lista_compromisoAlu\', \'cabeConfirm\');">'+
				      '<span class="mdl-checkbox__label"></span>' +
				   '</label>';
	        count_check_compromisosGlobales--;
	} else{
			$("#tableCompromisosGenerados #tb_lista_compromisoAlu #checkbox-compromiso-"+id).parent().addClass("is-checked");
			
			newCheck =' <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events is-upgraded is-checked" for="checkbox-compromiso-'+id+'" data-upgraded=",MaterialCheckbox,MaterialRipple">'+
				        	'<input type="checkbox" data-mov="'+mov+'" id="checkbox-compromiso-'+id+'" checked class="mdl-checkbox__input" onclick="checkUpdateCompromisoDelete(\''+id+'\');assignItemAUX(this.id, \'tb_lista_compromisoAlu\', \'cabeConfirm\');">'+
				        	'<span class="mdl-checkbox__label"></span>'+
				        	'<span class="mdl-checkbox__focus-helper"></span><span class="mdl-checkbox__box-outline">'+
				        	'<span class="mdl-checkbox__tick-outline"></span></span>'+
				        	'<span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">'+
				        	'<span class="mdl-ripple"></span></span>'+
				        '</label>';
			count_check_compromisosGlobales++;
	}
	$('#tableCompromisosGenerados #tb_lista_compromisoAlu').bootstrapTable('updateCell',{
			rowIndex   : (id-1),
			fieldName  : 0,
			fieldValue : newCheck,
			className  : 'ddd'
	});
	tableEventsMeses("#tb_lista_compromisoAlu");
}

function check_cronogramas_compromisos_global(cod,year) {
	var tableData = $('#tb_compromisoCalendarAlu-'+year+'-'+cod).bootstrapTable('getData').length;

	if($("#checkbox-compromiso-global-"+cod).parent().hasClass("is-checked")){
		    $("#checkbox-compromiso-global-"+cod).parent().removeClass("is-checked");
	} else{ 
		    $("#checkbox-compromiso-global-"+cod).parent().addClass("is-checked");
	}
	var ini = 0;
	var tableDataDatos = $('#verCronoCompromisosAlumno #tb_compromisoCalendarAlu-'+year+'-'+cod).bootstrapTable('getData');
	for(id=1; id<=tableData; id++) {
		if($("#checkbox-compromiso-global-"+cod).parent().hasClass("is-checked")) {
				count_check_compromisosAlu += tableData;
				newCheck =' <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events is-upgraded is-checked" for="checkbox-compromiso-'+cod+'-'+id+'" data-upgraded=",MaterialCheckbox,MaterialRipple">'+
						    	'<input type="checkbox" id="checkbox-compromiso-'+cod+'-'+id+'" checked class="mdl-checkbox__input" onclick="check_cronogramas_compromisos(\''+cod +'\',\''+year +'\',\''+id +'\')">'+
						    	'<span class="mdl-checkbox__label"></span>'+
						    	'<span class="mdl-checkbox__focus-helper"></span><span class="mdl-checkbox__box-outline">'+
						    	'<span class="mdl-checkbox__tick-outline"></span></span>'+
						    	'<span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">'+
						    	'<span class="mdl-ripple"></span></span>'+
						    '</label>';	
				ini++;
		} else{
				count_check_compromisosAlu-=tableData;
				newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'+cod+'-'+id+'">' +
					    		'<input type="checkbox" id="checkbox-compromiso-'+cod+'-'+id+'" class="mdl-checkbox__input" onclick="check_cronogramas_compromisos(\''+cod +'\',\''+year +'\',\''+id +'\')">'+
					    		'<span class="mdl-checkbox__label"></span>' +
					    	'</label>';
		}
		$('#tb_compromisoCalendarAlu-'+year+'-'+cod).bootstrapTable('updateCell',{
			rowIndex   : (id-1),
			fieldName  : 'checkbox',
			fieldValue : newCheck
		});
	}
	tableEventsMeses("#tb_compromisoCalendarAlu-"+year+'-'+cod);
	$("#alu_"+cod+" #student_year[alu_"+cod+"]").val($("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno").val());
	$("#alu_"+cod+" #student_detalles_cronogramas[alu_"+cod+"]["+id+"]").val($(is_checked).parents("td").attr("data-detalle"));
	if(ini == 0){
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").removeClass("is-checked");
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").css({"background-color":"rgba(158, 158, 158, .2)","background-color":"#009688"});
		$("#alu_"+cod).find(".mdl-card__menu label").removeClass("is-checked");
	} else{
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").addClass("is-checked");
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").css({"color":"#ccc","background-color":"#009688"});
		$("#alu_"+cod).find(".mdl-card__menu label").addClass("is-checked");
	}
	if(0<count_check_compromisosAlu){
		$("#cronograma_pago_fg").hide();$("#main_save_uno").parent().show();
	} else{
		$("#cronograma_pago_fg").show();$("#main_save_uno").parent().hide();
	}
}

function check_cronogramas_compromisos(cod,year,id) {
	is_checked = $("#checkbox-compromiso-"+cod+'-'+id);
	var ini = 0;
	if(is_checked.parent().hasClass("is-checked")){
			newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'+cod+'-'+id+'">' +
				    		'<input type="checkbox" id="checkbox-compromiso-'+cod+'-'+id+'" class="mdl-checkbox__input" onclick="check_cronogramas_compromisos(\''+cod +'\',\''+year +'\',\''+id +'\')">'+
				    		'<span class="mdl-checkbox__label"></span>' +
				    	'</label>';
			count_check_compromisosAlu--;
	} else{
			newCheck =' <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-js-ripple-effect--ignore-events is-upgraded is-checked" for="checkbox-compromiso-'+cod+'-'+id+'" data-upgraded=",MaterialCheckbox,MaterialRipple">'+
					    	'<input type="checkbox" id="checkbox-compromiso-'+cod+'-'+id+'" checked class="mdl-checkbox__input" onclick="check_cronogramas_compromisos(\''+cod +'\',\''+year +'\',\''+id +'\')">'+
					    	'<span class="mdl-checkbox__label"></span>'+
					    	'<span class="mdl-checkbox__focus-helper"></span><span class="mdl-checkbox__box-outline">'+
					    	'<span class="mdl-checkbox__tick-outline"></span></span>'+
					    	'<span class="mdl-checkbox__ripple-container mdl-js-ripple-effect mdl-ripple--center" data-upgraded=",MaterialRipple">'+
					    	'<span class="mdl-ripple"></span></span>'+
					    '</label>';
			count_check_compromisosAlu++; ini++;
	}
	$('#tb_compromisoCalendarAlu-'+year+'-'+cod).bootstrapTable('updateCell',{
		rowIndex   : (id-1),
		fieldName  : 'checkbox',
		fieldValue : newCheck
	});
	tableEventsMeses("#tb_compromisoCalendarAlu-"+year+'-'+cod);
	$("#alu_"+cod+" #student_year[alu_"+cod+"]").val($("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno").val());
	$("#alu_"+cod+" #student_detalles_cronogramas[alu_"+cod+"]["+id+"]").val($(is_checked).parents("td").attr("data-detalle"));
	if(ini == 0){
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").removeClass("is-checked");
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").css({"background-color":"rgba(158, 158, 158, .2)","background-color":"#009688"});
		$("#alu_"+cod).find(".mdl-card__menu label").removeClass("is-checked");
	} else{
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").addClass("is-checked");
		$("#alu_"+cod).find(".mdl-card__supporting-text #btn_ver_compromisos").css({"color":"#ccc","background-color":"#009688"});
		$("#alu_"+cod).find(".mdl-card__menu label").addClass("is-checked");
	}
	if(0<count_check_compromisosAlu){
		$("#cronograma_pago_fg").hide();$("#main_save_uno").parent().show();
	} else{
		$("#cronograma_pago_fg").show();$("#main_save_uno").parent().hide();
	}
}

function deleteCompromisosAluExtra() {
	var tableData = $("#tb_lista_compromisoAlu").bootstrapTable('getData');
	var movimientos = new Array();
	i=0;
	$.each(tableData,function(key,value){ 
		var cb = $(this[0]).find('input');
		var id = $(this[0]).find('input').attr('id')
		var mov = $(this[0]).find('input').attr('data-mov');
		if(cb.prop( "checked" ) == true){
			movimientos[i] = mov; i++;
		}
	});
	var observacion = $("#modalEliminarCompromisosExtras #obsDeleteCompromisos").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/anularCompromisosExtras",
			data: { 
				   movimientos : movimientos,
				   observacion : observacion,
				   idGlobal    : idGlobal
				  },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				tableDataL = $("#tb_lista_compromisoAlu").bootstrapTable('getData').length;
				for(key=1 ; key<=tableDataL; key++){ 
					noCheck =   '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-compromiso-'+key+'">' +
								    '<input type="checkbox" id="checkbox-compromiso-'+key+'" class="mdl-checkbox__input" onclick="checkUpdateCompromisoDelete(\''+key +'\')">'+
								    '<span class="mdl-checkbox__label"></span>' +
								 '</label>';
				    $('#tableCompromisosGenerados #tb_lista_compromisoAlu').bootstrapTable('updateCell',{
						rowIndex   : key-1,
						fieldName  : 'checkbox',
						fieldValue : noCheck 
				    });
				}
				getCompromisosGlobales(); $("#modalEliminarCompromisosExtras").modal("hide");
				$('#cabeConfirm').css('display' ,'none');
			}else if(data.error == 1) {
			}
			mostrarNotificacion('warning' , data.msj,'');check_cronogramas_compromisos
		});
	});
}

function loadComboCompromisosGlobales() {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/getCompromisosGlobales",
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				setCombo("selectCompromisosGlobales", data.combo, 'compromisos',null);
//				closeFab();
			}else if(data.error == 1) {

			}
		});
	});
}

function getCompromisosGlobales() {
	idGlobal = $('#selectCompromisosGlobales option:selected').val().trim();
	if(idGlobal == null || idGlobal == "") {
		return mostrarNotificacion('warning', 'Seleccione un compromiso');
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/listaCompromisosGlobales",
	        data: { compromisoglobal : idGlobal
	        },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#filtroCompromisos').fadeOut();
				$("#cardsCompromisos").hide();
				$("#tableCompromisosGenerados").show();
				$("#tableCompromisosGenerados .mdl-card__supporting-text").html(data.lista);
				componentHandler.upgradeAllRegistered();
				$('#tb_lista_compromisoAlu').bootstrapTable({});
				$('#imgExtras').hide();
				componentHandler.upgradeAllRegistered();
			} else if(data.error == 1) {

			}
		});
	});
}

var idGlobal = null;
function CerrarFiltroMultiCompromisos() {
	$("#tableCompromisosGenerados").hide();
	$("#cardsCompromisos").show();
}

function CerrarFiltroAluCompromisos() {
	$("#lista_cronograma").hide();
//	$("#cronograma_pagos").hide();
	$("#cardsCompromisosPorAlumno").show();
}

function click_total_compromisosAula() {
	var idtable = $("#modalVerEstudiantes .mdl-card__supporting-text .fixed-table-body table").attr("id");
	id=idtable.substr(13,idtable.length);
	clickCheckAula(id);
	tableData = $("#modalVerEstudiantes .mdl-card__supporting-text #"+idtable).bootstrapTable('getData');
	if($("#modalVerEstudiantes #"+idtable+" thead th.id label").hasClass("is-checked")){
		$("#modalVerEstudiantes #"+idtable+" thead th.id label").removeClass("is-checked");
		checked="no checked";
		$("#checkbox-all-aula_"+id).parent().addClass("is-checked");
		$("#cardsCompromisos #aula_"+id+" #checkbox-all-aula_"+id).parent().removeClass("is-checked");
	}
	else{
		$("#modalVerEstudiantes #"+idtable+" thead th.id label").addClass("is-checked");
		checked="checked";
		$("#checkbox-all-aula_"+id).parent().addClass("is-checked");
		$("#cardsCompromisos #aula_"+id+" #checkbox-all-aula_"+id).parent().addClass("is-checked");
	}
	$.each(tableData,function(key,value){ 
		if(checked == 'checked'){
			r = $(value[0]).addClass("is-checked");
		}
		else{
			r = $(value[0]).removeClass("is-checked");
		}
		$("#modalVerEstudiantes .mdl-card__supporting-text #"+idtable).bootstrapTable('updateCell',{
			rowIndex   : key,
			fieldName  : 0,
			fieldValue : r[0]['outerHTML']
	    });
	});
	componentHandler.upgradeAllRegistered();
}

function click_check_estudent_comp(id) {
	var idtable = $("#modalVerEstudiantes .mdl-card__supporting-text .fixed-table-body table").attr("id");
	var id_row = $("#modalVerEstudiantes #"+idtable+" #"+id+'--').parents("tr").attr("data-index");
	if($("#modalVerEstudiantes #"+idtable+" #"+id+'--').parent().hasClass("is-checked")) {   
		$("#"+id).removeAttr("checked");
		$("#"+id+'--').parent().removeClass("is-checked");
		check = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'--">'+
						  '<input type="checkbox" id="'+id+'--" class="mdl-checkbox__input" onclick="click_check_estudent_comp(\''+id +'\')">'+
						  '<span class="mdl-checkbox__label"></span>'+
						'</label>';
		$("#modalVerEstudiantes .mdl-card__supporting-text #"+idtable).bootstrapTable('updateCell',{
			rowIndex   : id_row,
			fieldName  : 0,
			fieldValue : check 
	    });
	}
	else {
		$("#"+id).attr("checked",'checked');
		$("#"+id+'--').parent().addClass("is-checked");
		check = '<label class="mdl-checkbox mdl-js-checkbox is-upgraded is-checked" for="'+id+'--" data-upgraded=",MaterialCheckbox">'+
					'<input type="checkbox" id="'+id+'--" class="mdl-checkbox__input" onclick="click_check_estudent_comp(\''+id +'\')">'+
					'<span class="mdl-checkbox__label"></span><span class="mdl-checkbox__focus-helper"></span>'+
					 '<span class="mdl-checkbox__box-outline">'+
		             '<span class="mdl-checkbox__tick-outline"></span></span>'+
		          '</label>';
		$("#modalVerEstudiantes .mdl-card__supporting-text #"+idtable).bootstrapTable('updateCell',{
			rowIndex   : id_row,
            fieldName  : 0,
            fieldValue : check
        });
	}
	componentHandler.upgradeAllRegistered();
}

function getBodyTable(id) {
	td = '';
	$(id).each(function(){
		td+='<tr>';
		$(this).find("td").each(function(){
				//dat= $(this).attr("data-field")
				td 	  += '<td >';
			if($(this).hasClass("id")){
				input  = $(this).find("input"); checked=input.attr('checked');
				label  = '<label class="mdl-checkbox mdl-js-checkbox" for="'+input.attr("id")+'--" >';
				label += '<input type="checkbox" id="'+input.attr("id")+'--" class="mdl-checkbox__input" '+checked+' onclick="click_check_estudent_comp(\''+input.attr("id")+'\')">';
				label += '<span class="mdl-checkbox__label"></span></label>';
				td    += label;
				td	  += '</td>';
			} else{td+=$(this).html();td+='</td>';}
		});
		td+='</tr>';
	});
	return td;
}

function getAllCheckbox() {
	var total_check = $("#total_cards").val();
	var t=0;
	for(i=1;i<=total_check;i++){
		t += parseInt($("#cardsCompromisos #aula_"+i+" #n_checked").html());
	}
	return t;
}

function getTotalCheckboxAula(id) {
	tbody = $("#cardsCompromisos #aula_"+id).find("#lista_estudiantes table tbody tr"); 
	var total = 0;
	tbody.each(function(){
		$(this).find("td").each(function(){
			if($(this).hasClass("id"))
			{	
				if($(this).find("input").attr("checked") == "checked")
				{
					total+=1;
				}	
			}	
		});
	});
	$("#cardsCompromisos #aula_"+id).find("#n_checked").html(total);
	return parseInt(total);
}

var rg=0;
$("#filtroCompromisos label input").clickToggle(function(){
	var total_check = $("#total_cards").val();
	for(i=1;i<=total_check;i++){
		$("#cardsCompromisos #checkbox-all-aula_"+i).parent().addClass("is-checked");
		setTotalCheckboxAula2(i);
		getTotalCheckboxAula(i); 
	}
	AllCheckboxAulas();
},function(){
	var total_check = $("#total_cards").val();
	for(i=1;i<=total_check;i++){
		$("#cardsCompromisos #checkbox-all-aula_"+i).parent().removeClass("is-checked");
		setTotalCheckboxAula2(i);
		getTotalCheckboxAula(i);
	}
	AllCheckboxAulas();
	
});

function AllCheckboxAulas() {
	var total_check = $("#total_cards").val();
	total_seleccionados=0;
	for(i=1;i<=total_check;i++){
		tbody = $("#cardsCompromisos #aula_"+i).find("#lista_estudiantes table tbody tr"); 
		tbody.each(function(){
			$(this).find("td").each(function(){
				if($(this).hasClass("id"))
				{	
					if($(this).find("input").attr("checked") == "checked")
					{
						total_seleccionados+=1;
					}	
				}	
			});
		});
	}
}

function setTotalCheckboxAula(id) {
	thead = $("#cardsCompromisos #aula_"+id+" #lista_estudiantes table thead .id");
	if ($("#cardsCompromisos #aula_"+id+" #checkbox-all-aula_"+id).parent().hasClass('is-checked')) {
		    $("#checkbox-all-aula_"+id).removeAttr("checked");
		    thead.find("input").removeAttr("checked");
		    thead.find("label").removeClass("is-checked");
	} else{
		    $("#checkbox-all-aula_"+id).attr("checked","checked");
		    thead.find("input").attr("checked","checked");
		    thead.find("label").addClass("is-checked");
	}
	tbody = $("#cardsCompromisos #aula_"+id+" #lista_estudiantes table tbody tr");
	tbody.each(function(){
		$(this).find("td").each(function(){
			if($(this).hasClass("id"))
			{	
				if ($("#checkbox-all-aula_"+id).parent().hasClass('is-checked')) {
					    $(this).find("input").removeAttr("checked");
				} else{
					    $(this).find("input").attr("checked","checked");
				}
			}	
		});
	});
}

function setTotalCheckboxAula2(id) {
	tbody = $("#cardsCompromisos #aula_"+id+" #lista_estudiantes table tbody tr");
	tbody.each(function(){
		$(this).find("td").each(function(){
			if($(this).hasClass("id")) {	
				if ($("#checkbox-all-aula_"+id).parent().hasClass('is-checked')) {
					    $(this).find("input").attr("checked","checked");
				} else{
					    $(this).find("input").removeAttr("checked");
				}
			}	
		});
	});
}

function modalDetalleAulaCompromiso(id) { 
	table_estudiantes_hidden = $("#cardsCompromisos #aula_"+id).find("#lista_estudiantes");
	$("#modalVerEstudiantes .mdl-card__supporting-text").html(table_estudiantes_hidden.html());
	
	td=getBodyTable("#modalVerEstudiantes .mdl-card__supporting-text table tbody tr");
	$("#modalVerEstudiantes .mdl-card__supporting-text table tbody").html(td);
	componentHandler.upgradeAllRegistered();
	$('#modalVerEstudiantes #tb_compromiso'+id).bootstrapTable({});
	
	tableEventsMeses('#modalVerEstudiante #tb_compromiso'+id);
	$("#modalVerEstudiantes").bind('hidden.bs.modal', function () {
		total_seleccionados=getTotalCheckboxAula(id);
		
	});
}

function clickCheckAula(id) {
	setTotalCheckboxAula(id);
	getTotalCheckboxAula(id);
	AllCheckboxAulas();
}

function clickCheckAlu(id) {
	setTotalCheckboxAula(id);
	getTotalCheckboxAula(id);
	AllCheckboxAulas();
}

var cont_comp_alu=0;

function inputNombre() {
	var nombre = $("#modalFiltroAlumnoCompromiso #nombre").val(); 
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/inputNombreAlumnos",
			data: { nombre 	    : nombre,
					count  		: count_scroll_c
			},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){ 
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			
			if(data.error == 0) {
				if(result.length != 0){
					$('#empty_card').css('display', 'none');
					$("#cronograma_pagos").hide();
					$("#cardsCompromisosPorAlumno").show();
					componentHandler.upgradeAllRegistered();
					$("#cardsCompromisosPorAlumno").html(data.tablaAlumnos);	
					$('#cardsCompromisosPorAlumno #tb_compromisoAlu').bootstrapTable({});
					componentHandler.upgradeAllRegistered();
					tableEventsMeses("#tb_compromisoAlu");
					$('#empty_card').css('display', 'none');
					flg_filtro_activo = 1;
				} else {
					$('#empty_card').css('display', 'block');
					$('#cardsCompromisosPorAlumno').css('display','none');
				}
			} else if(data.error == 1) {
				$("#cronograma_pagos").show();
			}			
		});
	});
}

function inputApellidos() {
	addLoadingButton('botonFEC');
	var nombre = $("#modalFiltroAlumnoCompromiso #nombre").val();
	var apellidos = $("#modalFiltroAlumnoCompromiso #apellidos").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/inputApellidosAlumnos",
			data: { nombre 	    : nombre,
					apellidos       : apellidos,
					count  			: count_scroll_c
			      },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			if(data.error == 0) {
				if(result.length != 0){
					$('#empty_card, #cronograma_pagos, #calendarCronograma').css('display', 'none');
					$("#cronograma_pagos").hide();
					$("#cardsCompromisosPorAlumno").show();
					componentHandler.upgradeAllRegistered();
					$("#cardsCompromisosPorAlumno").html(data.tablaAlumnos);
					$('#cardsCompromisosPorAlumno #tb_compromisoAlu').bootstrapTable({});
					componentHandler.upgradeAllRegistered();
					tableEventsMeses("#tb_compromisoAlu");
					flg_filtro_activo = 1;
				} else {
					$('#cronograma_pagos, #calendarCronograma, #cardsCompromisosPorAlumno').css('display', 'none');
					$('#empty_card').css('display', 'block');
				}
			} else if(data.error == 1) {
				$("#cronograma_pagos").show();
			}
			stopLoadingButton('botonFEC');
		});
	});
}

function inputCodigo() {
	var nombre = $("#modalFiltroAlumnoCompromiso #nombre").val();
	var apellidos = $("#modalFiltroAlumnoCompromiso #apellidos").val();
	var codigo = $("#modalFiltroAlumnoCompromiso #codigoAlumno").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/inputCodigoAlumno",
			data: { nombre 	     : nombre,
					apellidos    : apellidos,
					codAlu       : codigo,
	        	    count  		 : count_scroll_c
				  },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			if(data.error == 0) {
				if(result.length != 0){
					$('#empty_card, #cronograma_pagos, #calendarCronograma').css('display', 'none');
					$("#cronograma_pagos").hide();
					$("#cardsCompromisosPorAlumno").show();
					componentHandler.upgradeAllRegistered();
					$("#cardsCompromisosPorAlumno").html(data.tablaAlumnos);
					$('#cardsCompromisosPorAlumno #tb_compromisoAlu').bootstrapTable({});
					componentHandler.upgradeAllRegistered();
					tableEventsMeses("#tb_compromisoAlu");
					flg_filtro_activo = 1;
				} else {
					$('#cronograma_pagos, #calendarCronograma, #cardsCompromisosPorAlumno').css('display', 'none');
					$('#empty_card').css('display', 'block');
				}
			} else if(data.error == 1) {
				$("#cronograma_pagos").show();
				
			}			
		});
	});
}

function inputCodigoFamilia() {
	var nombre = $("#modalFiltroAlumnoCompromiso #nombre").val();
	var apellidos = $("#modalFiltroAlumnoCompromiso #apellidos").val();
	var codigo = $("#modalFiltroAlumnoCompromiso #codigoAlumno").val();
	var codFamilia = $("#modalFiltroAlumnoCompromiso #codigoFamilia").val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/inputCodigoFamiliaAlumno",
			data: { nombre  	: nombre,
				    apellidos 	: apellidos,
				    codAlu      : codigo,
        	        codFamilia  : codFamilia,
        	        count  		: count_scroll_c
				  },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			result = data.tablaAlumnos;
			if(data.error == 0) {
				if(result.length != 0){
					$('#empty_card, #cronograma_pagos, #calendarCronograma').css('display', 'none');
					$("#cronograma_pagos").hide();
					$("#cronograma_pagos").hide();
					$("#cardsCompromisosPorAlumno").show();
					$("#cardsCompromisosPorAlumno").html(data.tablaAlumnos);
					componentHandler.upgradeAllRegistered();
					tableEventsMeses("#tb_compromisoAlu");
					flg_filtro_activo = 1;
				} else {
					$('#cronograma_pagos, #calendarCronograma, #cardsCompromisosPorAlumno').css('display', 'none');
					$('#empty_card').css('display', 'block');
				}
			} else if(data.error == 1) {
				$("#cronograma_pagos").show();
			}			
		});
	});
}

function getYearCronoAlu(estudent,sede,nivel,grado,mora) {
//	if(mora == 0){
		$('#combosSedeNivelGrado').html(null);
		$("#verCronoCompromisosAlumno #persona_compromiso").val(estudent);
		$("#verCronoCompromisosAlumno #sede_compromiso").val(sede);
		$("#verCronoCompromisosAlumno #nivel_compromiso").val(nivel);
		$("#verCronoCompromisosAlumno #grado_compromiso").val(grado);
		var sede_co = sede;
		$("#verCronoCompromisosAlumno #calendarCompromisos").hide();
		//$("#verCronoCompromisosAlumno #calendarCompromisos").html("");
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url : "c_compromisos/mostrarYearCronoAlumno",
				data: {sede       : sede_co },
		        async: true,
		        type: 'POST'
			})
			.done(function(data){ 
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#combosSedeNivelGrado').html(data.comboNiveles);
					$('#calendarCompromisos').html(data.table);
					$("#verCronoCompromisosAlumno #calendarCompromisos").show();
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
					    $('.pickerButn').selectpicker('mobile');
					} else {
						$('.pickerButn').selectpicker();
					}
					modal('verCronoCompromisosAlumno');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectSede"]').parent().addClass('col-md-4');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectNivel"]').parent().addClass('col-md-4');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectGrado"]').parent().addClass('col-md-4');
					setCombo("tipoCronograma", data.optTiposCrono, 'tipo cronograma',null);
					setCombo("YearCronoCompromisosAlumno", data.opYear, 'a&ntilde;o',null);
	//				$('#verCronoCompromisosAlumno #YearCronoCompromisosAlumno').attr('disabled','disabled');
					$("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno option:first").attr('selected','selected');
					verCompromisosAlumno();
				} else if(data.error == 1) {
	
				}			
			});
		});	
//	} else{
//		msj('warning','No puedes generar a un estudiante con deudas');
//	}
}

function registroCompromisoAlumno() {
	addLoadingButton('botonRCA');
	var lista = new Array();
	var beca = new Array(); 
	var montofinal = new Array(); 
	i=0;
	
	/*var year = $("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno").val();
	var idpersona = $("#verCronoCompromisosAlumno #persona_compromiso").val();
	var tableData = $('#verCronoCompromisosAlumno #calendarCompromisos table').bootstrapTable('getData');
	$(tableData).each(function(){
		var input   = $(this['checkbox']).find('input');
		var idAux   = input.attr('id');
		if(input.is(':checked') == true){
			var detalle = $('#verCronoCompromisosAlumno #'+(idAux)).closest('td').attr("data-detalle");
			lista [i] = detalle;
			i++;
		}
	});*/
	var year 	  =  $("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno").val();
//	var sede      =  $("#verCronoCompromisosAlumno #sede_compromiso").val();
//	var nivel     =  $("#verCronoCompromisosAlumno #nivel_compromiso").val(); 
//	var grado     =  $("#verCronoCompromisosAlumno #grado_compromiso").val();
	var sede  	  = $('#selectSede     option:selected').val();
	var nivel 	  = $('#selectNivel    option:selected').val();
	var grado 	  = $('#selectGrado    option:selected').val();
	var idpersona =  $("#verCronoCompromisosAlumno #persona_compromiso").val();
	var tipoCrono =  $("#verCronoCompromisosAlumno #tipoCronograma").val();
	$("#verCronoCompromisosAlumno #calendarCompromisos table tbody tr").each(function(){
		if($(this).find("td label").hasClass("is-checked")){
			lista [i]      = $(this).find("td").attr("data-detalle"); 
			montofinal [i] = $(this).find(".monto_final").html();
			beca [i] = $(this).find(".beca").html();
			i++; 
		}
	});
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_compromisos/saveCompromisosAlu",
			data: { year       : year,
					sede       : sede,
					nivel      : nivel,
					grado      : grado,
					detalles   : lista,
					idpersona  : idpersona,
					montofinal : montofinal,
					beca       : beca,
					tipoCrono  : tipoCrono
				  },
	        async: true,
	        type: 'POST'
		})
		.done(function(data){ 
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('warning' , data.msj,''); verCompromisosAlumno();
				stopLoadingButton('botonRCA');
				abrirCerrarModal('verCronoCompromisosAlumno');
			} else if(data.error == 1) {
				mostrarNotificacion('warning' , data.msj,'');
				stopLoadingButton('botonRCA')
			}						
			stopLoadingButton('botonRCA');
		});
	});	
}

function verCompromisosAlumno() {
	var year 	    =  $("#verCronoCompromisosAlumno #YearCronoCompromisosAlumno").val();
	var id_persona  =  $("#verCronoCompromisosAlumno #persona_compromiso").val();
	var sede        =  $("#verCronoCompromisosAlumno #sede_compromiso").val();
	var nivel       =  $("#verCronoCompromisosAlumno #nivel_compromiso").val();
	var grado       =  $("#verCronoCompromisosAlumno #grado_compromiso").val();
	var tipoCrono 	=  $("#verCronoCompromisosAlumno #tipoCronograma").val();
	if(year != '' || tipoCrono != ''){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				url : "c_compromisos/mostrarCompromisosYearAlumno",
				data: { id_persona : id_persona,
						year       : year,
						sede       : sede,
						nivel      : nivel,
						grado      : grado,
						tipoCrono  : tipoCrono,
						flg_combo  : '2'
					},
		        async: true,
		        type: 'POST'
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').html(data.combos);
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
					    $('.pickerButn').selectpicker('mobile');
					} else {
						$('.pickerButn').selectpicker();
					}
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id]').parent().css('display', 'none');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectSede"]').parent().addClass('col-md-4');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectNivel"]').parent().addClass('col-md-4');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id="selectGrado"]').parent().addClass('col-md-4');
					$('#verCronoCompromisosAlumno #combosSedeNivelGrado').find('button[data-id]').parent().removeAttr('style');
					$("#verCronoCompromisosAlumno #calendarCompromisos").show();
					//if($('#tb_compromisoCalendarAlu-'+year+"-"+data.codigo).length == 0){
				    $("#calendarCompromisos").html(data.table);
				    $('#tb_compromisoCalendarAlu-'+year+'-'+data.codigo).bootstrapTable({});
				    tableEventsMeses('#tb_compromisoCalendarAlu-'+year+'-'+data.codigo);
				    n = $('#tb_compromisoCalendarAlu-'+year+"-"+data.codigo).bootstrapTable('getData').length;
				    count_check_compromisosAlu-=n;
				    if(count_check_compromisosAlu < 0){
				        count_check_compromisosAlu = 0;
				    }
				} else{
					msj('warning',data.msj);
				}
			});
		});	
	} else{
		$("#calendarCompromisos").html(null);
	}
}

function tableEventsMeses(idTable) {
	$(function () {
		componentHandler.upgradeAllRegistered();
	    $(idTable).on('all.bs.table', function (e, name, args) { 
	    	componentHandler.upgradeAllRegistered(); 
	    })
	    .on('click-row.bs.table', function (e, row, $element) { 
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	componentHandler.upgradeAllRegistered();
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
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('load-error.bs.table', function (e, status) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('column-switch.bs.table', function (e, field, checked) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('hidden.bs.modal', function () {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('shown', function () {
		})
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('click-cell.bs.table', function (field,value,row,$element) {
	    	componentHandler.upgradeAllRegistered();
	    	if(value=="checkbox_info"){
	    		id=$element._checkbox_info_data.field;
	    		estudent   = $("#checkbox_info_"+id).find("#student_"+id).val();
	    		sede       = $("#checkbox_info_"+id).find("#student_sede_"+id).val();
	    		nivel      = $("#checkbox_info_"+id).find("#student_nivel_"+id).val();
	    		grado 	   = $("#checkbox_info_"+id).find("#student_grado_"+id).val();
	    		if($("#checkbox_info_"+id).parents("tr").find(".id label").hasClass("is-checked")){
	    			getYearCronoAlu(estudent,sede,nivel,grado);
		    		$("#verCronoCompromisosAlumno").modal();
	    		}
	    	}
	    });
	});
}

/**********************************COMPROMISOS DE ALUMNOS MULTIPLES CRONOGRAMA*************************************************/
function selectAllAluCompromisos(){
	$("#modalSaveCompromisosAlumno").modal()
}

function saveCompromisosAlu() {
	var year     = Array();
	var detCrono = Array();
	var student  = Array();
	var sede     = Array();
	var nivel    = Array();
	var grado    = Array();
	i=0;
	$("#cardsCompromisosPorAlumno .mdl-student").each(function(){ 
		cod = $(this).attr("id"); //#btn_ver_compromisos
		if($(this).find("#btn_ver_compromisos").hasClass("is-checked")){
			id 			= $(this).attr("id")
			year[i]     = $(this).find(".mdl-card__menu").find("#student_year").val();
			detCrono[i] = $(this).find(".mdl-card__menu").find("#student_detalles_cronogramas").val();
			
			student[i]  = $(this).find(".mdl-card__menu").find("#student").val();
			sede[i]     = $(this).find(".mdl-card__menu").find("#student_sede").val();
			nivel[i]    = $(this).find(".mdl-card__menu").find("#student_nivel").val();
			grado[i]    = $(this).find(".mdl-card__menu").find("#student_grado").val(); 
			i++;
		}
	});
}

function selectCheckAluCompromisos(id) {
	count=0;
	if($("#cardsCompromisosPorAlumno #student_p_"+id).parent().hasClass("is-checked")){
		count++;
	}
	$("#cardsCompromisosPorAlumno .mdl-student").each(function(){ 
		if($(this).find(".mdl-card__menu label").hasClass("is-checked")){count++;}
	});
}

function clickCheckAluCompromisos() {
	if($("#filtroCompromisosCronograma label").hasClass("is-checked")) {   
		$("#cardsCompromisosPorAlumno .mdl-student .mdl-card__menu label").removeClass("is-checked");
	}
	else{
		$("#cardsCompromisosPorAlumno .mdl-student .mdl-card__menu label").addClass("is-checked");
	}
	count = $("#cardsCompromisosPorAlumno .mdl-student .mdl-card__menu label.is-checked").length;
}

function onScrollEvent(element) {
	if($(element).scrollTop() + $(element).innerHeight()>=$(element)[0].scrollHeight){
		var nombre = $("#modalFiltroAlumnoCompromiso #nombre").val();
		var apellidos = $("#modalFiltroAlumnoCompromiso #apellidos").val();
		var codigo = $("#modalFiltroAlumnoCompromiso #codigoAlumno").val();
		var codFamilia = $("#modalFiltroAlumnoCompromiso #codigoFamilia").val();
		if($("#cardsCompromisosPorAlumno").is(":visible") && flg_filtro_activo == 1){
			Pace.restart();
			Pace.track(function() {
				$.ajax({	
		  			type    : 'POST',
		  			url     : 'c_compromisos/inputSearchAluCompromisos',
		  			data    : { nombre  	: nombre,
							    apellidos 	: apellidos,
							    codAlu      : codigo,
			        	        codFamilia  : codFamilia,
			        	        count  		: count_scroll_c
	        	              },
		  			'async' : true
		  		}).done(function(data){
		  			data = JSON.parse(data);
		  			$("#cronograma_pagos").hide();
		  			$("#cardsCompromisosPorAlumno").append(data.tablaAlumnos);
		  			componentHandler.upgradeAllRegistered();
		  			count_scroll_c = count_scroll_c + 1;
		  		});
			});
		}
	}
}

tabGlobal = null;
function createFabByTab (tab){
	sessionStorage.lastTab = tab;
	if(tab == 'tab-4') {
		setTimeout(function(){
			modal('modalSubirPaquete');
			$('#'+sessionStorage.lastTab).addClass('is-active');
			$('#tabCompromisoExtra').removeClass('is-active');
		},50)
	} else{
		$.ajax({
			data  : {tab : tab},
			url   : 'c_configuracion/createFab',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#menu').html(data.menu);
			$('#menu').find('button').addClass('is-up');
//			$('#img_table_empty').html(data.img);
//			$('#img_table_empty1').html(data.img);
//			$('#img_table_empty2').html(data.img);
		});
	}
	
}

function getCompromisosByGrado(){
	var sede  	  = $('#selectSede     option:selected').val();
	var nivel 	  = $('#selectNivel    option:selected').val();
	var grado 	  = $('#selectGrado    option:selected').val();
	var tipoCrono = $('#tipoCronograma option:selected').val();
	var year      = $('#YearCronoCompromisosAlumno option:selected').val();
	var idpersona =  $("#verCronoCompromisosAlumno #persona_compromiso").val();
	$.ajax({
		data  : {sede       : sede,
			     grado      : grado,
			     nivel      : nivel,
			     tipoCrono  : tipoCrono,
			     year       : year,
			     id_persona : idpersona,
			     flg_combo  : '1'},
		url   : 'c_compromisos/mostrarCompromisosYearAlumno',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			    $('.pickerButn').selectpicker('mobile');
			} else {
				$('.pickerButn').selectpicker();
			}
			$("#verCronoCompromisosAlumno #calendarCompromisos").show();
			//if($('#tb_compromisoCalendarAlu-'+year+"-"+data.codigo).length == 0){
		    $("#calendarCompromisos").html(data.table);
		    $('#tb_compromisoCalendarAlu-'+year+'-'+data.codigo).bootstrapTable({});
		    tableEventsMeses('#tb_compromisoCalendarAlu-'+year+'-'+data.codigo);
		    n = $('#tb_compromisoCalendarAlu-'+year+"-"+data.codigo).bootstrapTable('getData').length;
		    count_check_compromisosAlu-=n;
		    if(count_check_compromisosAlu < 0){
		        count_check_compromisosAlu = 0;
		    }
		} else{
			msj('warning',data.msj);
		}
	});
}

function getNivelesBySede(){
	var sede  	  = $('#selectSede option:selected').val();
	$.ajax({
		data  : {idSede : sede},
		url   : 'c_compromisos/getComboNivelBySede',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#calendarCompromisos').html(null);
		setCombo('selectNivel', data.optNivel, 'Nivel');
		setCombo('selectGrado', null, 'Grado');
	});
}

function getGradosByNivelSede(){
	var sede  	  = $('#selectSede  option:selected').val();
	var nivel     = $('#selectNivel option:selected').val();
	$.ajax({
		data  : {idSede  : sede,
			     idNivel : nivel},
		url   : 'c_compromisos/getGradoBySedeCompromisos',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#calendarCompromisos').html(null);
		setCombo('selectGrado', data.optGrado, 'Grado');
	});
}



