var arrayCatPreg = [];
var arrayTiposEnc = [];
var openModalTipoEncuestados = 0;
//Categoria seleccionada en el checkbox
var catSelec = null;
//INDEX PARA TABLA DE TODAS LAS PREGUNTAS
var rowIndexAllPreg = null;
var ordenAllPreg    = null;
function initCrearEncuesta(jsonArray,idTipoEnc,flg_encuesta,arrayTipo){
	arrayTiposEnc = arrayTipo;
	if(idTipoEnc != "" && idTipoEnc != null){
		$('#contTbCategorias').css('display','block');
		$('#tb_categorias').bootstrapTable({ });
		initSearchTableById("tb_categorias");
		tableEventsCate();
	}
	if(flg_encuesta == true){
		$('#tipoEncuestado').css('display','block');
		$('#tipoEncuestadoMulti').css('display','block');
	}
	$('#selectTipoEncuesta').val(idTipoEnc);
	arrayCatPreg = jsonArray;
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	
	$("#vistaPrevia").click( function(){
        vistaPrevia(data.encuesta);
    });
}

//CATEGORIAS POR ENCUESTA////////////////////////////////////////////////////////////////////////////////////////////////////////////
var first = 0;
//warningDeleteCate = 0 ADVERTENCIA | warningDeleteCate = 1 NADA |VARIABLES PARA ELIMINAR CATEGORIA EN MODAL
var warningDeleteCate = 0;
var flg_cate_pregunta = 0;
var trsModal     = null;
var idCatModal   = null;
var idCheckModalCate = null;
var rowIndexCate = null;
var ordenCate    = null; 
//Inicializa onclick en cada celda de la tabla categorias
function initClickTbCate(fijar){
	componentHandler.upgradeAllRegistered();
	moveRowCate();
	$("#tb_categorias tr td input").change(function() {
		var checkBox =  $(this).parent().find('input:checkbox');
		var check = $(this).parent().find('input:checkbox').is(":checked");
		var idCat = $(this).parent().find('input:checkbox').attr('attr-idcategoria');
		idCheckModalCate = $(this).parent().find('input:checkbox').attr('id');
		getRowIndexValue('#tb_categorias','attr-idcategoria',idCat);
		var trs = $(this).parent().parent().parent().parent().parent()[0].childNodes;
		trsModal = trs;
		if(check == true && warningDeleteCate == 0){
			getPreguntasCategorias(idCat);
			insertDeleteCateXEncuesta(idCat,1);
			//Pintar la fila cuando se da check en el checkbox
			$("#tb_categorias tr").filter(function() {
		        return $(this).data('index') == checkBox.closest('tr').data('index');
		    }).css('background-color','rgba(255,146,0,0.2)');
			
		} else{
			if(warningDeleteCate == 0){
				$($($('#'+$(this).parent().find('input:checkbox').attr('id'))).parent()).addClass('is-checked');
				//Mantener la fila pintada, por si no cierra el modal de aceptar quitar el check
				$("#tb_categorias tr").filter(function() {
			        return $(this).data('index') == checkBox.closest('tr').data('index');
			    }).css('background-color','rgba(255,146,0,0.2)');
				warningDeleteCate = 1;
				idCatModal = idCat;
				abrirCerrarModal('modalAdvertencia');
			}
		}
	});
	
	$("#tb_categorias tr td").css('cursor','pointer');
	$("#tb_categorias tr td").click(function() {
		$("#empty").css('display', 'none');
		var check = $(this).parent().find('input:checkbox').is(":checked");
		if(check == true){
			if(($(this)[0].cellIndex == 1 || $(this)[0].cellIndex == 2 || $(this)[0].cellIndex == 3 || $(this)[0].cellIndex == 0)  && flg_cate_pregunta == 0){
				flg_cate_pregunta = 1;
				var trs = ($(this).parent().parent())[0].childNodes;
				$.each(trs, function() {
					$(this).css('background-color','white');
				});
				$(this).parent().css('background-color' , 'rgba(255,146,0,0.2)');
				var check = $(this).parent().find('input:checkbox').is(":checked");
				var idCat = $(this).parent().find('input:checkbox').attr('attr-idcategoria');
				getPreguntasCategorias(idCat);
			}
		} else{
			var trs = ($(this).parent().parent())[0].childNodes;
			$.each(trs, function() {
				$(this).css('background-color','white');
			});
			$('#contTbPreguntas').html(null);
			$('#contNuevaPregunta').css('display','none');
		}
	});	
}

$('#buttonDeleteCate').click(function(){
	removeAllPreguntasByCategoria(idCatModal);
	insertDeleteCateXEncuesta(idCatModal,3);
	$('#contNuevaPregunta').css('display','none');
	
	warningDeleteCate = 0;
	$('#contTbPreguntas').html(null);
	$('#contNuevaPregunta').css('display','none');
	$('#'+idCheckModalCate).attr('checked','checked');
	var idCategoria = $('#'+idCheckModalCate).attr('attr-idcategoria');
	
	$.each(trsModal, function() {
		$(this).css('background-color','white'); 
	});
	
	abrirCerrarModal('modalAdvertencia');
});

//Inserta o elimina la categoria por encuesta
function insertDeleteCateXEncuesta(idCat,opcion){
	Pace.restart();
	Pace.track(function() {
		try{
			var tipoEncuesta = $('#selectTipoEncuesta option:selected').val();
			$.ajax({
				data  : {idCat  	  : idCat,
						 opcion 	  : opcion,
						 tipoEncuesta : tipoEncuesta},
				url   : 'c_crear_encuesta/insertDeleteCategoriaEncuesta',
				type  : 'POST',
				async : true 
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('warning',data.msj);
					$('contTbPreguntas').html(null);
				} else{
					$('#contTbCategorias').html(data.tbCategorias);
					$('#tb_categorias').bootstrapTable({ });
					initSearchTableById("tb_categorias");
					paintCateSelected(data.idCateSel);
					tableEventsCate();
					$('#contNuevaPregunta').css('display','block');
				}
			});
		} catch(err){
			location.reload();
		}
	});
}

//Registra nueva categoria
function asignaRegistraCategoria(){
	var desc_cate = $('#nuevaCategoria').val(); 
	try{
		$.ajax({
			data  : {desc_cate    : desc_cate,
				     arrayCatPreg : arrayCatPreg},
			url   : 'c_crear_encuesta/agregarCategoria',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 1){
				mostrarNotificacion('warning',data.msj);
			} else{
				$('#nuevaCategoria').val(null);
				$('#nuevaCategoria').parent().removeClass("is-dirty");
				activarBtnAgregarCate('nuevaCategoria', 'newCate');
				$('#contTbCategorias').html(data.tbCategorias);
				$('#contTbPreguntas').html(data.tbPreguntas);
				initOpenModalPreguntas();
				$('#tb_categorias').bootstrapTable({ });
				tableEventsCate();
				initSearchTableById("tb_categorias");
				$('#contNuevaPregunta').css('display','block');
				componentHandler.upgradeAllRegistered();
				$('#tb_preguntas').bootstrapTable({ });
				initSearchTableById("tb_preguntas");
				tableEvents();
				catSelec = data.idCate;
				if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
				    $('.pickerButn').selectpicker('mobile');
				} else {
					$('.pickerButn').selectpicker();
				}
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	        	});
				mostrarNotificacion('success', "Se agreg&oacute; la categor&iacute;a");
				paintCateSelected(data.idCate);
			}
		});
	} catch(err){
		location.reload();
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//PREGUNTAS POR CATEGORIA/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Tabla de preguntas x categorias
function getPreguntasCategorias(idCat){
	Pace.restart();
	Pace.ignore(function() {
		try{
			catSelec = idCat;
			$.ajax({
				url: "c_crear_encuesta/getPreguntasCategoria",
				data: {arrayCatPreg : arrayCatPreg,
					   idCategoria  : catSelec},
		        async : true,
		        type: 'POST'
			}).done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					$('#contTbPreguntas').html(data.tbPreguntas);
					$('#tb_preguntas').bootstrapTable({ });	
					initSearchTableById("tb_preguntas");
					$('#contNuevaPregunta').css('display','block');
					componentHandler.upgradeAllRegistered();
					if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
					    $('.pickerButn').selectpicker('mobile');
					    //.selectpicker('mobile');
					} else {
						$('.pickerButn').selectpicker();
					}
					tableEvents();
					$(document).ready(function(){
		        	    $('[data-toggle="tooltip"]').tooltip(); 
		        	});
					initOpenModalPreguntas();
					flg_cate_pregunta = 0;
					moveRow();
				} else{
					$('#contAgregaPregunta').html(null);
				}
			});
		} catch(err){
			location.reload();
		}
	});
}

//Asigna o remueve preguntas a categoria
function cambioCheckAsignaPregunta(idCB,flg_obli){
	var idCbPreg     = '#'+idCB;
	var check    = $(idCbPreg).attr('checked');
	idPregunta   = $(idCbPreg).attr('attr-idpregunta'); 
	getRowIndexValue('#tb_preguntas','attr-idpregunta',idPregunta); 
    var idCBObli = "#"+$(idCbPreg).attr('attr-idcheckObli');
    var idSelect = "#"+$(idCbPreg).attr('attr-id_select');
	if(check == 'checked'){
		var obj = { 
			    idCategoria 	: catSelec,
			    idPregunta  	: idPregunta,
			    flgObli     	: flg_obli,
			    idTipoPreg  	: null,
			    cambio          : '1',
			    tipo_encuestado : null
			};
		arrayCatPreg.push(obj);
		crearEncuestaInactiva(1);
		updateRowPregCheck(idPregunta,'checked');
		updateRowPregCombo(idPregunta,null);
		updateRowPregObli(idPregunta,null,null);
	} else{
		$(idCBObli).parent().removeClass('is-checked');
		removeObjectFromArray(catSelec,idPregunta);
//		$(idSelect + ' option[value="BcB2JkCA7TyzYJ+/Lko6tmHWqPc/lJMtSQvJ0xVbIIY="]').attr('selected','selected');
//		$(idSelect).selectpicker('refresh');
//		updateRowPregCheck(idPregunta,null);
//		updateRowPregObli(idPregunta,null,'disabled');
//		updateRowPregCombo(idPregunta,'disabled');
	}
}

//Quita pregunta de la categoria
function removeObjectFromArray(idCategoria,idPregunta){
	for(var i = 0; i < arrayCatPreg.length; i++){
		if(arrayCatPreg[i].idCategoria == idCategoria && arrayCatPreg[i].idPregunta == idPregunta){
			arrayCatPreg[i].cambio  = '3';
			break;
		}
	}
	componentHandler.upgradeAllRegistered();
	crearEncuestaInactiva(3);
}

//Quita todas las preguntas de la categoria
function removeAllPreguntasByCategoria(idCategoria){
	for(var i = 0; i < arrayCatPreg.length; i++){
		if(arrayCatPreg[i].idCategoria == idCategoria.valueOf()){
			arrayCatPreg[i].cambio  = '3';
		}
	}
	crearEncuestaInactiva(1);
}

//Asigna a una pregunta que sea obligatoria
function cambioCheckFlgObligatorio(cb){
	var idCb   = '#'+cb.id;
	idCbPreg   = $(idCb).attr('attr-idcheckPreg');
	var checkPreg  = $("#"+idCbPreg).attr('checked');
	var check  = $(idCb).attr('checked');
	idPregunta = $(idCb).attr('attr-idpregunta');
	getRowIndexValue('#tb_preguntas','attr-idpregunta',idPregunta);
	if(check == 'checked'){
		if(checkPreg === 'checked'){
			for(var i = 0; i < arrayCatPreg.length; i++){
				if(arrayCatPreg[i].idCategoria == catSelec && arrayCatPreg[i].idPregunta == idPregunta){
					arrayCatPreg[i].flgObli = '1';
					arrayCatPreg[i].cambio  = '2';
					break;	
				}
			}
			crearEncuestaInactiva(1);
			updateRowPregObli(idPregunta,'checked',null);
		} else{
			$("#"+idCbPreg).prop('checked', true);
			cambioCheckAsignaPregunta(idCbPreg,'1');
		}
	} else{
		if(checkPreg == 'checked'){
			for(var i = 0; i < arrayCatPreg.length; i++){
				if(arrayCatPreg[i].idCategoria == catSelec && arrayCatPreg[i].idPregunta == idPregunta){
					arrayCatPreg[i].flgObli = null;
					arrayCatPreg[i].cambio  = '2';
					break;	
				}
			}
		}
		crearEncuestaInactiva(1);
		updateRowPregObli(idPregunta,'',null);
	}
}

var arrayOpcionesPreg = [];
var idCheckModal = null;
var input = null;
var idTipoPregGlobal = null;
//Guarda el tipo de pregunta por pregunta
function setIdTipoPreguntaCombo(select,idPregunta,idChecAsig){
	var idOptionSel = $('#'+$(select).attr('id')+" option:selected").val();	
	$("#tituloEncuesta").blur(); 
	//Evalua si existe en el array
	try{
		$.ajax({
			data  : {idOptionSel : idOptionSel, 
				     idPregunta  : idPregunta},
			url   : 'c_crear_encuesta/getOptionsByPregunta',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			idPreg = idPregunta;
			idTipoPregGlobal = data.tipoPreguntaSelected;
			getRowIndexValue('#tb_preguntas','attr-idpregunta',idPregunta);
			if(data.flg_mostrarModal == 1){
				$('#'+$(select).attr('id')+' option[value="'+data.tipoPreguntaSelected+'"]').attr('selected','selected');
//				updateRowPregCombo(idPregunta,null);
				abrirCerrarModal('modalSeleccionarTipoPreg');
				$('#contInputsOpciones').html(data.divOpciones);
				initFormAddOpcion();
				setTimeout(function() { $('#'+data.idOptionFocus).focus() }, 500);
				if(data.arrayOpciones != null){
					arrayOpcionesPreg = JSON.parse(data.arrayOpciones);
				}
			}
			if(data.flg_saveArray == 1){
				var existe = false;
				for(var i = 0; i < arrayCatPreg.length; i++){
					if(arrayCatPreg[i].idPregunta == idPregunta){
						arrayCatPreg[i].idTipoPreg = idOptionSel;
//						if(arrayCatPreg[i].cambio != undefined){
							arrayCatPreg[i].cambio = '2';
//						}
						existe = true;
						
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						break;
					}
				}
				if(existe == false){
					$('#'+idChecAsig).prop('checked',true);
					cambioCheckAsignaPregunta(idChecAsig,'0');
					setIdTipoPreguntaCombo(input,idPregunta,idChecAsig);
//					updateRowPregCombo(idPregunta,null);
				}
				crearEncuestaInactiva(1);
			}
			updateRowPregCombo(idPregunta,null);
		});
	} catch(err){
//		location.reload();
	}
}

//Abre el modal para seleccionar el tipo de pregunta para la pregunta
idPreg = null;
function abrirModalSeleccionarTipoPreg(preg,numPreg,button){
	Pace.restart();
	Pace.track(function() {
		try{
			var idbutton = '#'+button.id;
			var idCheck  = $(idbutton).attr('attr-idcheckpreg');
			idCheckModal = idCheck;
			idPreg = preg;
			$.ajax({
				data  : {preg    : preg,
					     numPreg : numPreg},
				url   : 'c_crear_encuesta/buildRadioTipoPreg',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#contRadioButtonPreg').html(data.radio);
			});
			$('#contInputsOpciones').html(null);
			abrirCerrarModal('modalSeleccionarTipoPreg');
			getInputOpciones(preg);
		} catch(err){
			location.reload();
		}
	});
}

//Registra nueva pregunta
function asignarRegistraPregunta(){
	Pace.restart();
	Pace.track(function() {
		try{
			var desc_preg = $('#nuevaPregunta').val();
			$.ajax({
				data  : {desc_preg    : desc_preg,
					     arrayCatPreg : arrayCatPreg,
					     idCate       : catSelec},
				url   : 'c_crear_encuesta/agregarPregunta',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('warning',data.msj);
				} else{
					$('#nuevaPregunta').val(null);
					$('#nuevaPregunta').parent().removeClass("is-dirty");
					activarBtnAgregarCate('nuevaPregunta', 'newPreg');
					$('#contTbPreguntas').html(data.tbPreguntas);
					$('#tb_preguntas').bootstrapTable({ });
					initSearchTableById("tb_preguntas");
					arrayCatPreg = JSON.parse(data.arrayJson);
					mostrarNotificacion('success',data.msj);
					initTbPreguntasInEvents();
					tableEvents();
					$('#contTbCategorias').html(data.tbCategorias);
					$('#tb_categorias').bootstrapTable({ });
					tableEventsCate();
					paintCateSelected(data.idCateSelected);
				}
			});
		} catch(err){
			location.reload();
		}
	});
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//OPCIONES POR PREGUNTA

//Retorna el valor por opcion
function findAndReturnValueInArrayIfExists(idAlter){
	for(var i = 0; i < arrayOpcionesPreg.length; i++){
		if(arrayOpcionesPreg[i].id_alter == idAlter){
			return arrayOpcionesPreg[i].desc_alter;
		}
	}
	return null;
}

//Setea el nuevo texto a la opcion
function setAttrCambioByAlternativaValor(idAlter,valor){
	for(var i = 0; i < arrayOpcionesPreg.length; i++){
		if(arrayOpcionesPreg[i].id_alter == idAlter){
			if(valor != arrayOpcionesPreg[i].desc_alter){
				arrayOpcionesPreg[i].cambio = "2";
				arrayOpcionesPreg[i].desc_alter = valor;
			}
		}
	}
	return null;
}

//Inicializa el formulario para ingresar mas opciones
function initFormAddOpcion(){
	
	$('#formAddOpcion')
	.bootstrapValidator({
		framework: 'bootstrap',
	    excluded: ':disabled'
	}).on('success.form.bv', function(e) {
			e.preventDefault();
		    var $form = $(e.target),
		        formData = new FormData(),
		        params   = $form.serializeArray(),
		        fv       = $form.data('bootstrapValidator');
		    var arrayOpt = [];
		    $.each(params, function(i, val) {
		    	idAlternativa = $('#opcion'+i).attr('attr-id_alter_preg');
		    	if(idAlternativa != undefined && val.value != null){
		    		value = findAndReturnValueInArrayIfExists(idAlternativa);
			    	setAttrCambioByAlternativaValor(idAlternativa,val.value);
		    	} else if(idAlternativa == undefined && val.value != null && val.value != ""){
		    		var obj = {id_alter : null,
		    				   cambio        : "1",
		    				   desc_alter    : val.value};
		    		arrayOpcionesPreg.push(obj);
		    	}
	        });
		    try{
			    $.ajax({
			        data: {arrayOpt   : arrayOpcionesPreg,
			        	   idPreg     : idPreg,
			        	   idTipoPreg : idTipoPregGlobal},
			        url: "c_crear_encuesta/saveOpcion",
			        cache: false,
		            type: 'POST'
			  	})
			  	.done(function(data) {
			  		data = JSON.parse(data);
			  		if(data.error == 0){
			  			$('#contInputsOpciones').html(data.divOpciones);
			  			componentHandler.upgradeAllRegistered();
			  			setTimeout(function() { $('#'+data.idOptionFocus).focus() }, 500);
			  			initFormAddOpcion();
			  			arrayOpcionesPreg = JSON.parse(data.arrayOpciones);
			  		} else{
			  			mostrarNotificacion('warning',data.msj);
			  		}
			     })
		     	 .fail(function(jqXHR, textStatus, errorThrown) {
		     		 //mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
			  	 })
			  	 .always(function() {		      	 
			  		
			  	 });
		    } catch(err){
				location.reload();
			}
	  });
}

//Elimina la opcion por pregunta
function deleteOpcionByPreg(idAlterPreg){
//	var idTipoPreg = $('input[name="radioVals"]:checked').val();
	Pace.restart();
	Pace.track(function() {
		try{
			$.ajax({
				data  : {idAlterPreg : idAlterPreg,
					     idPreg      : idPreg,
					     idTipoPreg  : idTipoPregGlobal},
				url   : 'c_crear_encuesta/deleteOpcionByPregunta',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					$('#contTbCategorias').html(data.tbCategorias);
		  			$('#contTbPreguntas').html(data.tbPreguntas);
		  			initOpenModalPreguntas();
		  			tableEvents();
		  			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
		  			    $('.pickerButn').selectpicker('mobile');
		  			} else {
		  				$('.pickerButn').selectpicker();
		  			}
		  			$(document).ready(function(){
		        	    $('[data-toggle="tooltip"]').tooltip(); 
		        	});
		  			$('#contInputsOpciones').html(data.divOpciones);
		  			initFormAddOpcion();
		  		} else{
		  			mostrarNotificacion('warning',data.msj);
		  		}
			});
		} catch(err){
			location.reload();
		}
	});
}

//Muestra las opciones para ciertos tipos
function getInputOpciones(idPreg){
	Pace.restart();
	Pace.track(function() {
		try{
			var idTipoPreg = $('input[name="radioVals"]:checked').val();
			$.ajax({
				data  : {idPreg     : idPreg,
					     idTipoPreg : idTipoPreg},
				url   : 'c_crear_encuesta/crearOpcionesPregunta',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 0){
					$('#contInputsOpciones').html(data.divOpciones);
//					initOptionByPregunta(data.arrayOpts);
					arrayOpcionesPreg = JSON.parse(data.arrayOpciones);
					initFormAddOpcion();
				}
			});
		} catch(err){
			location.reload();
		}
	});
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * diego 19.05
 */
function actualizarTitulo(tituloEncuesta) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : { tituloEncuesta : tituloEncuesta },
	        url   : 'c_crear_encuesta/actualizarTitulo',
	        type  : 'POST',
	        async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success',data.msj, null);
			} else if(data.error == 1) {
				mostrarNotificacion('error',data.msj, null);
			}
		});
	});
}

//tipoGrabado == 1 GRABAR CADA ACCION |  tipoGrabado == 2 DESDE EL BOTON GRABAR 
function crearEncuestaInactiva(tipoGrabado){
	Pace.restart();
	Pace.track(function() {
		var tipoEncuesta   = $('#selectTipoEncuesta option:selected').val();
		var tituloEncuesta = $('#tituloEncuesta').val();
		var check          = $('#switchAnonima').is(':checked');
		try{
			$.ajax({
				data  : {arrayCatPreg   : arrayCatPreg,
					     tipoEncuesta   : tipoEncuesta,
					     tipoGrabado    : tipoGrabado,
					     idCategoria    : catSelec,
					     tituloEncuesta : tituloEncuesta,
					     check          : check},
		        url   : 'c_crear_encuesta/saveEncuesta',
		        type  : 'POST',
		        async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				arrayCatPreg = JSON.parse(data.arrayJson);
				if(data.flg_anonima == false){
					$('#contSwitch').css('display','none');
					$('#switchAnonima').prop('checked',false);
					$('#switchAnonima').parent().removeClass('is-checked');
				} else{
					$('#switchAnonima').parent().addClass(((data.switchAnonima == 1) ? 'is-checked' : null));
					$('#switchAnonima').prop('checked',((data.switchAnonima == 1) ? true : false));
					$('#contSwitch').css('display','block');
				}
				if(data.flg_tipoEnc == "true"){
					$('#tipoEncuestado').css('display','block');
					$('#tipoEncuestadoMulti').css('display','block');
				} else{
					$('#tipoEncuestadoMulti').css('display','none');
					$('#tipoEncuestado').css('display','none');
				}
				if(data.firstTable == 1){
					setMultiCombo('selectTipoEncuestado',data.optCombos);
					if(data.cant_preg > 0){
						$('#vistaPrevia').off('click');
						$("#vistaPrevia").click( function(){
					        vistaPrevia(data.encuesta);
			            });
					}else{
						$('#vistaPrevia').off('click');
						$("#vistaPrevia").click( function(){
					        vistaPrevia(null);
			            });
					}
					$('#contTbCategorias').html(data.tbCategoria);
					$('#tb_categorias').bootstrapTable({ });
					initSearchTableById("tb_categorias");
					$('#contNuevaCategoria').css('display','block');
					mostrarNotificacion('success', data.msj, null);
				}
				if(data.comboDel == 0){
					arrayTiposEnc = JSON.parse(data.arrayTipEnc);
					setMultiCombo('selectTipoEncuestado',data.options);
				}
				if(data.error == 1){
					mostrarNotificacion('warning', data.msj, null);
					$('#contTbPreguntas').html(data.tbPreguntas);
				} else if(data.error == 0){
					$('#contTbCategorias').css('display','block');
					$('#contTbCategorias').html(data.tbCategorias);
					$('#tb_categorias').bootstrapTable({ });
					initSearchTableById("tb_categorias");
					paintCateSelected(data.idCateCrypt);
					if(tipoGrabado == 2){
						mostrarNotificacion('success',data.msj, null);
						$('#contTbPreguntas').html(data.tbPreguntas);
						$('#tb_preguntas').bootstrapTable({ });
						initSearchTableById("tb_preguntas");
						abrirCerrarModal('modalAllPreguntas');
					} else if(tipoGrabado == 3){
						$('#contTbPreguntas').html(data.tbPreguntas);
						$('#tb_preguntas').bootstrapTable({ });
						initSearchTableById("tb_preguntas");
					}
					if(data.cant_preg > 0){
						$('#vistaPrevia').off('click');
						$('#vistaPrevia').attr('onclick','vistaPrevia("'+data.encuesta+'")').unbind('click');
					}else{
						$('#vistaPrevia').off('click');
						$('#vistaPrevia').attr('onclick','vistaPrevia(null)').unbind('click');
					}
				}
				tableEventsCate();
				initTbPreguntasInEvents();
				tableEvents();
				$(document).ready(function(){
	        	    $('[data-toggle="tooltip"]').tooltip(); 
	        	});
				paintPregSelected(data.pregSelected);
			});
		} catch(err){
			location.reload();
		}
	});
}

//Redirecciona vista previa
function vistaPrevia(encuesta){
	if(encuesta == null){
		mostrarNotificacion('warning', 'Agrega preguntas para visualizar', null);
	}else{
		try{
			$.ajax({
				data  : {encuesta : encuesta},
				url   : 'c_crear_encuesta/redirectVistaPrevia',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.error == 1){
					mostrarNotificacion('warning',data.msj);
				} else{
					window.open(data.url,'_blank');
				}
			});
		} catch(err){
			location.reload();
		}
	}
}

function getTipoEncuestados(){
	Pace.restart();
	Pace.track(function() {
		try{
			$.ajax({
				url   : 'c_crear_encuesta/getTableTipoEncuestado',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				$('#contTbTipoEncuestados').html(data);
				abrirCerrarModal('modalSeleccionarTipoEncuestado');
				componentHandler.upgradeAllRegistered();
			});
		} catch(err){
			location.reload();
		}
	});
}

function setTipoEncuestadoByEncuesta(idEncuesta,idTipoEncuestado,input){
	var checked = input.is(':checked');
	try{
		$.ajax({
			data  : {idEncuesta 	  : idEncuesta,
				     idTipoEncuestado : idTipoEncuestado,
				     checked          : checked},
			url   : 'c_crear_encuesta/saveTipoEncuestadoXPregunta',
			type  : 'POST',
			async : true
		})
		.done(function(data){
		});
	} catch(err){
		location.reload();
	}
}

function activeAllCate(all){
	var checkAll = all.is(":checked");
	var idCat = $(this).parent().find('input:checkbox').attr('attr-idcategoria');
	if(checkAll == true){
		$('.checkCate').attr('checked');
		$('.checkCate').prop('checked',true);
	} else{
		$('.checkCate').attr('checked');
		$('.checkCate').prop('checked',false);
	}
	
	var tableData = $('#tb_categorias').bootstrapTable('getData');

	$.each(tableData, function( key, value ) {
		var idCat = $(value[0]).find('.checkCate').attr('attr-idcategoria');
		var check = $(value[0]).find('.checkCate').is(":checked");
		var accion = null;
		if(checkAll == true && check == false){
			insertDeleteCateXEncuesta(idCat,1);
		} else if(checkAll == false && check == true){
			insertDeleteCateXEncuesta(idCat,3);
		}
		$('#contNuevaPregunta').css('display','none');
	});
}

var idCheckAsigTable = null; 
var idPreguntaXtipoEnc = null;
var idToolTip = null;
var rowIndexPreg  = null;
var ordenPreg     = null;
function initOpenModalPreguntas(){
	$("#tb_preguntas tr").css('cursor','pointer');
	$('#tb_preguntas').find('tr').find('td').click( function(){
        column = $(this)[0].cellIndex;
	    if(column == 1){
	    	idPreguntaXtipoEnc = $(this).parent().find('input:checkbox').attr('attr-idpregunta');
	    	idCheckAsigTable = $(this).parent().find('input:checkbox').attr('id');
	    	idToolTip = '#'+$(this).find('a').attr('id');
	    	getRowIndexValue('#tb_preguntas','attr-idpregunta',idPreguntaXtipoEnc);
	    	try{
		    	$.ajax({
		    		data  : {idPregunta : idPreguntaXtipoEnc},
		    		url   : 'c_crear_encuesta/buildRadioTipoEncuestado',
		    		type  : 'POST',
		    		async : true
		    	})
		    	.done(function(data){
		    	    data = JSON.parse(data);
		    	    if(data.error == 1){
		    	    	mostrarNotificacion('warning',data.msj);
		    	    } else if(data.error == 0){
		    	    	$('#contTipoEnc').html(data.contTipoEnc);
		    	    	if(openModalTipoEncuestados == 0){
		    	    		abrirCerrarModal('modalAsignaTipoEncuestadoPregunta');
				    		openModalTipoEncuestados = 1;
		    	    	}
		    	    }
		    	});
	    	} catch(err){
				location.reload();
			}
	    } 
	});
}

function setTipoEncuestadoByPregunta(idPregunta,idTipoEnc,descTipoEncuestado,descPregunta){
	var idCheckActual = '#'+idCheckAsigTable;
	var checkedActual = $(idCheckActual).is(':checked');
	if(checkedActual != true){
		$(idCheckActual).prop('checked',true);
		var obj = { 
			    idCategoria 	: catSelec,
			    idPregunta  	: idPregunta,
			    flgObli     	: '0',
			    idTipoPreg  	: null,
			    cambio          : '1',
			    tipo_encuestado : idTipoEnc
			};
		arrayCatPreg.push(obj);
	} else{
		for(var i = 0; i < arrayCatPreg.length; i++){
			if(arrayCatPreg[i].idPregunta == idPregunta){
				arrayCatPreg[i].tipo_encuestado = idTipoEnc;
				arrayCatPreg[i].cambio = '2';
				break;
			}
		}
	}
	var newInfo = '<a data-toggle="tooltip" data-placement="bottom" data-original-title="'+descTipoEncuestado+'">'  +
				      '<i class="material-icons">info</i>'+
				  '</a> '+
				  descPregunta;
	$('#tb_preguntas').bootstrapTable('updateCell',{
		rowIndex   : (rowIndexPreg-1),
		fieldName  : 1,
		fieldValue : newInfo
	});
	initTbPreguntasInEvents();
	crearEncuestaInactiva(1);
}

function tableEvents(){
	$(function () {
	    $('#tb_preguntas').on('all.bs.table', function (e, name, args) {

	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	initTbPreguntasInEvents();
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
	    	initTbPreguntasInEvents();
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	initTbPreguntasInEvents();
	    })
	    .on('search.bs.table', function (e, text) {
	    	initTbPreguntasInEvents();
	    });
	});
}

function tableEventsAll(){
	$(function () {
	    $('#tb_all_preguntas').on('all.bs.table', function (e, name, args) {

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
	    .on('page-change.bs.table', function (e, size, number) {
	    	componentHandler.upgradeAllRegistered();
	    })
	    .on('search.bs.table', function (e, text) {
	    	componentHandler.upgradeAllRegistered();
	    });
	});
}

function tableEventsCate(){
	var textGlob = null; 
	initClickTbCate(2);
	$(function () { 
//		initClickTbCate(2);
		$('#tb_categorias').on('all.bs.table', function (e, name, args) {
			
	    })
	    .on('click-row.bs.table', function (e, row, $element) {

	    })
	    .on('dbl-click-row.bs.table', function (e, row, $element) {

	    })
	    .on('sort.bs.table', function (e, name, order) {
	    	initClickTbCate(3);
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
	    	initClickTbCate(4);
	    })
	    .on('search.bs.table', function (e, text) {
	    	textGlob = text;
	    	initClickTbCate(6);
	    })
	    .on('page-change.bs.table', function (e, size, number) {
	    	if(textGlob == null){
	    		initClickTbCate(5);
	    	}
	    });
	});
}

function abrirModalAsignarOpciones(idOption,idPregunta){
	Pace.restart();
	Pace.track(function() {
		try{
			$.ajax({
				data  : {idOption   : idOption,
					     idPregunta : idPregunta},
				url   : 'c_crear_encuesta/getDataOptionByTipo',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				if(data.flg_open_modal == 1){
					idPreg = idPregunta;
					idTipoPregGlobal = idOption;
					abrirCerrarModal('modalSeleccionarTipoPreg');
					$('#contInputsOpciones').html(data.divOpciones);
					arrayOpcionesPreg = JSON.parse(data.arrayOpciones);
					initFormAddOpcion();
				}
			});
		} catch(err){
			location.reload();
		}
	});
};


function saveTipoEncuestado(){
	var options = $('#selectTipoEncuestado').val();
	var array1;
	var array2;
	var lengthOpt = (options != null) ? options.length : 0;
	var lengthArr = (arrayTiposEnc != null) ? arrayTiposEnc.length : 0;
	if(lengthOpt > lengthArr){
		jQuery.grep(options, function(el) {
	        if (jQuery.inArray(el, arrayTiposEnc) == -1) lastSelected = el;
		});
	} else{
		jQuery.grep(arrayTiposEnc, function(el) {
	        if (jQuery.inArray(el, options) == -1) lastSelected = el;
		});
	}
	var selected = $('#selectTipoEncuestado option[value="'+lastSelected+'"]')[0].selected;
	setTipoEncuestadoByEncuesta2(lastSelected,selected);
}

function setTipoEncuestadoByEncuesta2(idTipoEncuestado,selected){
	Pace.restart();
	Pace.track(function() {
		try{
			$.ajax({
				data  : {idTipoEncuestado : idTipoEncuestado,
					     selected          : selected},
				url   : 'c_crear_encuesta/saveTipoEncuestadoXPregunta2',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				arrayTiposEnc = JSON.parse(data.arrayTipEnc);
				$.each($("#tb_categorias tr"),function(){
					$(this).css('background-color','white');
				});
				$('#contTbPreguntas').html(null);
				$('#contNuevaPregunta').css('display','none');
			});
		} catch(err){
			location.reload();
		}
	});
}

function activarBtnAgregarCate(idInput, idBtn){
	var cantPalabra = $('#'+idInput).val();
	if( (cantPalabra.length) > 0 ){
		$('#'+idBtn).prop('disabled',false);
		$('#'+idBtn).removeClass('mdl-color-text--grey-500');
		$('#'+idBtn).addClass('mdl-color--orange-500 mdl-color-text--white');
	}else if ( (cantPalabra.length) == 0){
		$('#'+idBtn).removeClass('mdl-color--orange-500 mdl-color-text--white');
		$('#'+idBtn).addClass('mdl-color-text--grey-500');
		$('#'+idBtn).prop('disabled',true);
	}
}

function paintCateSelected(idCateSelected){
	$.each($("#tb_categorias tr").find('input:checkbox') , function() {
		var cate = $(this).attr('attr-idcategoria');
		if(cate == idCateSelected){
			$(this).closest('tr').css('background-color' , 'rgba(255,146,0,0.2)');
		}
	});
}

$('#modalAsignaTipoEncuestadoPregunta').on('hidden.bs.modal', function () {
	openModalTipoEncuestados = 0;
});

$('#modalAdvertencia').on('hidden.bs.modal', function () {
	warningDeleteCate = 0;
});

function initTbPreguntasInEvents(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
	componentHandler.upgradeAllRegistered();
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip(); 
	});
	initOpenModalPreguntas();
	moveRow();
	//tableEvents();
}

function updateRowPregCheck(idPregunta,checked){
	var newCheck = '<div class="checkbox checkbox-inline checkbox-styled">'+
				       '<label>'+
					       '<input type="checkbox" attr-orden="'+ordenPreg+'" id="checkPreg'+ordenPreg+'" attr-id_select="selectTipoPregunta'+ordenPreg+'" attr-idcheckObli="flgObli'+ordenPreg+'"'+ 
					              'onclick="cambioCheckAsignaPregunta(\'checkPreg'+ordenPreg+'\',0);" '+checked+' attr-idpregunta="'+idPregunta+'">'+
					       '<span></span>'+
					   '</label>'+
			      '</div>';
	$('#tb_preguntas').bootstrapTable('updateCell',{
		rowIndex   : rowIndexPreg-1,
		fieldName  : 0,
		fieldValue : newCheck
	});
	initTbPreguntasInEvents();
}

function getRowIndexValue(idTabla,attr,equals){
	var index = null;
	var tableData = $(idTabla).bootstrapTable('getData');
	$.each(tableData, function( key, value ) {
		var valor = $(value[0]).find('input:checkbox').attr(attr);
		if(equals == valor ){
			var orden = $(value[0]).find('input').attr('attr-orden');
			if(idTabla == '#tb_categorias'){
				rowIndexCate = key;
				ordenCate    = orden;
			} else if(idTabla == '#tb_preguntas'){
				rowIndexPreg = key+1;
				ordenPreg    = orden;
			} else{
				rowIndexAllPreg = key+1
				ordenAllPreg    = orden;
			}
			return;
		}
	});
}

function initMaxLenght(idText,idLabel){
	$(document).ready(function() {
	  var input = $("#textField");
	  var label = $("#wrapperTitulo");
	  var maxVal = $("#textField").attr('maxlength');
	  input.keyup(function() {
	    var inputLength = input.val().length;
	    var counter = $("#counter");
	    $("#wrapperTitulo").html("");
	    $("#wrapperTitulo").html(inputLength + "/55");
	    
	    if ( inputLength >= maxVal ) {
	      label.css("background-color", "#F3493D");
	      label.css("color", "#F3493D");
	    } else {
	      label.css("background-color", "#FF9200");
	      label.css("color", "#FF9200");
	    }
	  });
	}); 
}

function updateRowPregObli(idPregunta,checked,disabled){
	var newCheck = '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="flgObli'+ordenPreg+'" style="margin-left: 12.5%">'+
					    '<input type="checkbox" class="mdl-switch__input" id="flgObli'+ordenPreg+'" name="" '+disabled+' attr-idcheckPreg="checkPreg'+ordenPreg+'" onclick="cambioCheckFlgObligatorio(this);" '+checked+' attr-idpregunta="'+idPregunta+'">'+
					    '<span class="mdl-switch__label"></span>'+
					'</label>';
	$('#tb_preguntas').bootstrapTable('updateCell',{
		rowIndex   : rowIndexPreg-1,
		fieldName  : 3,
		fieldValue : newCheck
	});
	initTbPreguntasInEvents();
}

function updateRowPregCombo(idPregunta,checked){
	$.ajax({
		data  : {idPregunta : idPregunta},
		url   : 'c_crear_encuesta/buildOptionsByPreguntaEncuesta',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		$(".pickerButn.open").remove();
		var newCheck = '<select id="selectTipoPregunta'+ordenPreg+'" data-live-search="true" '+checked+' data-container="body" onchange="setIdTipoPreguntaCombo(this,\''+idPregunta+'\',\'checkPreg'+ordenPreg+'\')" class="form-control pickerButn">'+
				        data+
					   '</select>';
		$('#tb_preguntas').bootstrapTable('updateCell',{
		rowIndex   : rowIndexPreg-1,
		fieldName  : 2,
		fieldValue : newCheck
		});
		initTbPreguntasInEvents();
	});
}

var arrayAux = [];
function openModalAllPreguntas(){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url   : 'c_crear_encuesta/getAllPreguntasSinAsignar',
			type  : 'POST',
			async : true
		})
		.done(function(data){
			data = JSON.parse(data);
			$('#contTbAllPreguntas').html(data.tbAllPreg);
			$('#tb_all_preguntas').bootstrapTable({ });
			initSearchTableById("tb_all_preguntas");
			abrirCerrarModal('modalAllPreguntas');
			componentHandler.upgradeAllRegistered();
			tableEventsAll();
		});
	});
}

function asignaRemuevePregunta(input,idPreg){
	getRowIndexValue('#tb_all_preguntas','attr-idpregunta',idPreg);
	var check = input.is(':checked');
	if(check == true){
		var obj = { 
			    idCategoria 	: catSelec,
			    idPregunta  	: idPreg,
			    flgObli     	: '0',
			    idTipoPreg  	: null,
			    cambio          : '1',
			    tipo_encuestado : null
			};
		arrayCatPreg.push(obj);
		var objAux = { 
					     idCategoria : catSelec,
					     idPregunta  : idPreg
					 };
		arrayAux.push(objAux);
		updateRowCheckAllPregunta(idPreg,'checked');
	} else{
		for(var i = 0; i < arrayCatPreg.length; i++){
			if(arrayCatPreg[i].idCategoria == catSelec && arrayCatPreg[i].idPregunta == idPreg){
				arrayCatPreg.splice(i, 1);
				break;
			}
		}
		for(var i = 0; i < arrayAux.length; i++){
			if(arrayAux[i].idCategoria == catSelec && arrayAux[i].idPregunta == idPreg){
				arrayAux.splice(i, 1);
				break;
			}
		}
		updateRowCheckAllPregunta(idPreg,null);
	}
}

function updateRowCheckAllPregunta(idPregunta,checked){
	var newCheck = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkPregAll'+ordenAllPreg+'">'+
				       '<input type="checkbox"  class="mdl-checkbox__input" attr-orden="'+ordenPreg+'" id="checkPregAll'+ordenAllPreg+'" '+ 
				              'onclick="asignaRemuevePregunta($(this),\''+idPregunta+'\');" '+checked+' attr-idpregunta="'+idPregunta+'">'+
				       '<span></span>'+
				   '</label>';
	$('#tb_all_preguntas').bootstrapTable('updateCell',{
		rowIndex   : rowIndexAllPreg-1,
		fieldName  : 0,
		fieldValue : newCheck
	});
	componentHandler.upgradeAllRegistered(); 
}

//DIR = 1
function moveRow(){
	$(".up,.down").click(function(){
        var dir = 0;
        if ($(this).is(".up")) {
            dir = 1;
        }
        var idPreg 	   = $(this).attr('attr-idpregunta');
        var orden  	   = $(this).attr('attr-orden');
        var direccion  = $(this).attr('attr-direccion');
        moverOrdenPregunta(orden,direccion,idPreg);
    });
}

function moverOrdenPregunta(orden, direccion, idPreg){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {orden     : orden,
					 idPreg    : idPreg,
				     direccion : direccion},
			url   : 'c_crear_encuesta/changeOrdenPregunta',
			type  : 'POST',
			async : true
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1){
				mostrarNotificacion('warning',data.msj);
			} else{
				$('#contTbPreguntas').html(data.tbPreguntas);
				$('#tb_preguntas').bootstrapTable({ });
				paintPregSelected(data.pregSelected);
				initTbPreguntasInEvents();
				tableEvents();
			}
		});
	});
}

function moveRowCate(){
	$(".upCate,.downCate").click(function(){
        var dir = 0;
        if ($(this).is(".upCate")) {
            dir = 1;
        }
        var idCate 	   = $(this).attr('attr-idcategoria');
        var orden  	   = $(this).attr('attr-orden');
        var direccion  = $(this).attr('attr-direccion');
        moverOrdenCategoria(orden,direccion,idCate);
    });
}

function moverOrdenCategoria(orden, direccion, idCate){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {orden     : orden,
					 idCate    : idCate,
				     direccion : direccion},
			url   : 'c_crear_encuesta/changeOrdenCategoria',
			type  : 'POST',
			async : true
	 	})
		.done(function(data) {
			data = JSON.parse(data);
			if(data.error == 1){
				mostrarNotificacion('warning',data.msj);
			} else{
				$('#contTbCategorias').html(data.tbCategorias);
				$('#tb_categorias').bootstrapTable({ });
				paintCateSelected(data.idCate);
				initSearchTableById("tb_categorias");
				tableEventsCate();
			}
		});
	});
}

//$('#modalAllPreguntas').on('hidden.bs.modal', function () {
//	restoreArrayCatePreg();
//});

function restoreArrayCatePreg(){
	for(var i = 0; i < arrayAux.length ; i++){
		removeObjetFromArraySplice(arrayAux[i].idCategoria,arrayAux[i].idPregunta);
	}
	arrayAux = [];
}

function removeObjetFromArraySplice(idCategoria,idPregunta){
	for(var i = 0; i < arrayCatPreg.length; i++){
		if(arrayCatPreg[i].idCategoria == idCategoria && arrayCatPreg[i].idPregunta == idPregunta){
			arrayCatPreg.splice(i, 1);
			break;
		}
	}
}

function paintPregSelected(idPregSelected){
	$.each($("#tb_preguntas tr").find('input:checkbox') , function() {
		var preg = $(this).attr('attr-idpregunta');
		if(preg == idPregSelected){
			$(this).closest('tr').css('background-color' , 'rgb(128,128,128,0.1)');
		}
	});
}

//function initOptionByPregunta(arrayOpt){
//	for(var i = 0; i < arrayOpt.length; i++){
//		$(document).ready(function() {
//      	  var input = $(arrayOpt[i].input);
//      	  var label = $(arrayOpt[i].label);
//      	  var maxVal = $("#tituloEncuesta").attr('maxlength');
//      	  var inputLength = input.val().length;
//      	  $(arrayOpt[i].label).html(inputLength + "/"+maxVal);
//      	  input.keyup(function() {
//      	    var inputLength = input.val().length;      	    
//      	    $(arrayOpt[i].label).html("");
//      	    $(arrayOpt[i].label).html(inputLength + "/"+maxVal);
//      	    
//      	    if ( inputLength >= maxVal ) {
//      	      label.css("background-color", "#F3493D");
//      	      label.css("color", "#F3493D");
//      	    } else {
//      	      label.css("background-color", "#FF9200");
//      	      label.css("color", "#FF9200");
//      	    }
//      	  });
//      	}); 
//	}
//}

function changeAnonimaEncuesta(check){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data  : {check : check},
			url   : 'c_crear_encuesta/saveAnonimaEncuesta',
			type  : 'POST',
			async : false
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success',data.msj, null);
			} else if(data.error == 1) {
				mostrarNotificacion('error',data.msj, null);
			}
		});
	});
}