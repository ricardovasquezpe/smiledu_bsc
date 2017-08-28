var $rotate = $("#updateImg"), degree = 0, timer;
function rotate() {
    $rotate.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});  
    $rotate.css({ '-moz-transform': 'rotate(' + degree + 'deg)'});                      
    timer = setTimeout(function() {
        ++degree; rotate();
    },5);
}

function initTree(){
  $('#tree').treegrid({
	  treeColumn: 0,
      expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
      expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down',
      onChange: function() {}
	});
  if($(".treegrid-expanded").length){
	  clase = $(".treegrid-expanded").first().attr("class").replace("treegrid-expanded", "");
	  $('.'+clase).treegrid('getChildNodes').treegrid('collapseRecursive');
  }
  
  
  if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
  
  if($( window ).width() <= 930){
		$('.bandgeActual').css("display","block");
	}else{
		 $('.bandgeActual').css("display","none");
	}
	 
	
  $( window ).resize(function() {
  	if($( window ).width() <= 930){
  		$('.bandgeActual').css("display","block");
  	}else{
  		$('.bandgeActual').css("display","none");
  	}
	});
}

function initGrafico(){
	var med = null;
	$.ajax({  
		url: "c_detalle_indicador/getGraficos",
		async: false,
		type : 'POST'
  	})
  	.done(function(data) {
  		if(data == ""){
			location.reload();
		} else{
	  		data = JSON.parse(data);
	  		med = data;
	  		$("#contGauge").html(data.contGauge);
	  		$("#menuGauge").html(data.contMenuGauge);
	  		var porcentaje = $('.linEst1').attr('data-porcentaje');
			porcentaje = parseInt(porcentaje, 10);
			
			var amarillo   = $('.linEst1').attr('data-porcent1');
	        var verde      = $('.linEst1').attr('data-porcent2');
	        var colorVerde = $('.linEst1').attr('data-colorVerde');
	        var colorRojo  = $('.linEst1').attr('data-colorRojo');
	        var inicioG    = $('.linEst1').attr('data-inicioG');
	        var finG       = $('.linEst1').attr('data-finG');
	        var tipo        = $('.linEst1').attr('data-tipo');
	        var cod        = $('.linEst1').attr('data-codInd');
	        $('#condInd').html('Cod.'+cod);
	        $('.barraEstado').addClass($('.linEst1').attr('data-cBack'));
	        initGauge(porcentaje,1,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
	        setDivHeight();
	        if(tipo == "NORMAL"){
	        	$('.highcharts-tracker').find("tspan").append(' %');
	        }
		} 		
  		$('.highcharts-tracker').find("tspan").css('fill','#959595');
     })
 	 .fail(function(jqXHR, textStatus, errorThrown) {
 		 mostrarNotificacion('error','Comun&iacute;quese con alguna persona a cargo :(', 'Error');
  	 })
  	 .always(function() {		      	 
     
  	 });
	
	 initGraficoMediciones(med);
}

function initGraficoMediciones(data){
	try{
		var metas = data.metas;
		arrayMetas = JSON.parse(metas);
		var actuales = data.actuales;
		arrayActuales = JSON.parse(actuales);
		var mediciones = data.mediciones;
		arrayMediciones = JSON.parse(mediciones);
		
		if(arrayMediciones.length == 0){
			$('#container').html('	<div class="img-search">'+
							     '      <img src="'+window.location.protocol+'//'+window.location.hostname+'/smiledu/public/general/img/smiledu_faces/empty_state_tendencia.png">'+
							     '      <p><strong>Hey!</strong></p>'+
							     '      <p>A\u00fan no tienes datos para mostrar una tendencia.</p>'+
							     '  </div>');
		}else{
		    $('#container').highcharts({
		        title: {
		            text: ''
		        },chart: {
		        	 renderTo: 'container',
		             borderWidth: 1,
		             backgroundColor: null,
		             borderWidth: 0,
		             color:"white"
		        },
		        subtitle: {
		            text: '',
		            x: -20
		        },
		        xAxis: {
		            categories: arrayMediciones
		        },
		        exporting: {
		            enabled: false
		        },
		        yAxis: {
		            title: {
		                text: ''
		            },
		            plotLines: [{
		                value: 0,
		                width: 1,
		                color: '#808080'
		            }],
		            reversed: (data.ppu == 1) ? true : false
		        },credits: {
		            enabled: false
		        },
		        tooltip: {
		            valueSuffix: (data.ppu != 1) ? '%' : ''
		        },
		        legend: {
		            layout: 'horizontal',
		            align: 'right',
		            verticalAlign: 'bottom',
		            borderWidth: 0
		        },
		        series: [{
		            name: 'Actuales',
		            data: arrayActuales,
		            color: '#43AC6D'
		        }, {
		            name: 'Metas',
		            data: arrayMetas,
		            color:'#4A734C'
		        }]
		    });
		}
	}catch(err){
		$('#container').html('	<div class="img-search">'+
						     '      <img src="'+window.location.protocol+'//'+window.location.hostname+'/smiledu/public/general/img/smiledu_faces/empty_state_tendencia.png">'+
						     '      <p><strong>Hey!</strong></p>'+
						     '      <p>A\u00fan no tienes datos para mostrar una tendencia.</p>'+
						     '  </div>');
	}
	
}

function init(){
	initTree();
	$('#tb_frecuencias').bootstrapTable({});
	$('#tb_comparativas_x_indicador').bootstrapTable({ });
	//initValidatorEditMeta();
	//initXEditable();
	initGrafico();
	initButtonLoad('botonGE','botonNM','botonEVZ','save-buttom','botonCCI');
	initButtonCalendarDays('fechaMedicion');
    initMaskInputs('fechaMedicion');  
}

function logOutDI() {
	$.ajax({
		url  : 'c_detalle_indicador/logOutDI', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		location.reload();
	});
}

function openEditMeta(idIndDeta, num) {
	$('#idIndDeta').val(idIndDeta);
	setearInput("meta", num);
	abrirCerrarModal('modalEditMeta');
}

function openEditEstructura(data) {
	$.ajax({  
		data : { id_indicador_deta  : data},  
		url: "c_detalle_indicador/getEstructuraByIndicador",
		async: false,
		type : 'POST'
  	})
  	.done(function(data) {
  		if(data == "") {
			location.reload();
		} else{
	  		data = JSON.parse(data);
	  		$('#cont_tb_estructura').html(data.tabla_estructura);
	  		$('#tb_estructura').bootstrapTable({ });
			modal('modalAgregarEstructura');
		}
     })
 	 .fail(function(jqXHR, textStatus, errorThrown) {
 		 mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
  	 })
  	 .always(function() {		      	 
     
  	 });
	componentHandler.upgradeAllRegistered();
}

function initValidatorEditMeta(){	
	$('#formEditMeta')	  
	.bootstrapValidator({	
		framework: 'bootstrap',
	    excluded: ':disabled',
	    fields: {
	    	meta : {
	        	 validators: {
	        		 notEmpty: {
	                     message: 'Ingrese la meta'
	                 },
		             numeric: {
		                 message: 'La meta debe contener solo dígitos'
		             },
		             greaterThan: {
                        value: 1,
                        message: 'La meta tiene que ser mayor a 0'
                    }
	             }
	        }
	     }
	}).on('success.form.bv', function(e) {
			e.preventDefault();
		    var $form = $(e.target),
		        formData = new FormData(),
		        params   = $form.serializeArray(),
		        fv       = $form.data('bootstrapValidator');
		    formData.append('idIndDeta', $('#idIndDeta').val());
		    formData.append('displayHisto', $('#displayHisto').css('display'));
		    $.each(params, function(i, val) {
	            formData.append(val.name, val.value);
	        });	
		    rotate();
		    Pace.restart();
		    Pace.track(function() {
		    $.ajax({  
		        data: formData,
		        url: "c_detalle_indicador/editMeta",
		        cache: false,
	            contentType: false,
	            processData: false,
	            type: 'POST'
		  	})
		  	.done(function(data) {
		  		if(data == ""){
					location.reload();
				} else{
			  		data = JSON.parse(data);
					if(data.error == 0){//SUCCESS
						mostrarNotificacion('success', data.msj , null);
						$('#contArbolIndiDeta').html(data.tablaHijos);
						initTree();
						$('#historiaCardDiv').html(data.historiaInd);
						$('#valorAmarillo').val(data.flg_amarillo);
						
						$('#detalleLastModi').html(data.detalleModal);
						$('#fechaModi').text(data.fechaModi);
						
						$("#contGauge").html(data.contGauge);
				  		$("#menuGauge").html(data.contMenuGauge);
				  		var porcentaje = $('.linEst1').attr('data-porcentaje');
						porcentaje = parseInt(porcentaje, 10);
						
						var amarillo   = $('.linEst1').attr('data-porcent1');
				        var verde      = $('.linEst1').attr('data-porcent2');
				        var colorVerde = $('.linEst1').attr('data-colorVerde');
				        var colorRojo  = $('.linEst1').attr('data-colorRojo');
				        var inicioG    = $('.linEst1').attr('data-inicioG');
				        var finG       = $('.linEst1').attr('data-finG');
				        var tipo        = $('.linEst1').attr('data-tipo');

				        var cod        = $('.linEst1').attr('data-codInd');
				        $('#condInd').html('Cod.'+cod);
				        $('.barraEstado').css('background-color',$('.linEst1').attr('data-cBack'));
				        initGauge(porcentaje,1,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
				        
				        if(tipo == "NORMAL"){
				        	$('.highcharts-tracker').find("tspan").append(' %');
				        }
				        
				        $('.highcharts-data-labels g rect').css('display','none');
			        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
			        	$('.highcharts-series-group circle').css('fill','#959595');
						
			        	clearTimeout(timer);
			        	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
						abrirCerrarModal('modalEditMeta');
					} else if(data.error == 1){//ERROR
						clearTimeout(timer);
						mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
					} else if(data.error == 2) {
						clearTimeout(timer);
						mostrarNotificacion('warning', data.msj, 'Ojo:');
					}
					}
			     })
		     	 .fail(function(jqXHR, textStatus, errorThrown) {
		     		 mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
			  	 })
			  	 .always(function() {		      	 
			     
			  	 });
		    });
	  });
}

function editMeta(){
	Pace.restart();
    Pace.track(function() {
	    $.ajax({  
	        data: {
	        	idIndDeta : $('#idIndDeta').val(),
	        	displayHisto : $('#displayHisto').css('display'),
	        	meta : $("#meta").val()
	        },
	        url: "c_detalle_indicador/editMeta",
	        type: 'POST'
	  	})
	  	.done(function(data) {
  		if(data == ""){
			location.reload();
		} else{
	  		data = JSON.parse(data);
			if(data.error == 0){//SUCCESS
				mostrarNotificacion('success', data.msj, data.cabecera);
				$('#contArbolIndiDeta').html(data.tablaHijos);
				initTree();
				$('#historiaCardDiv').html(data.historiaInd);
				$('#valorAmarillo').val(data.flg_amarillo);
				
				$('#detalleLastModi').html(data.detalleModal);
				$('#fechaModi').text(data.fechaModi);
				
				$("#contGauge").html(data.contGauge);
		  		$("#menuGauge").html(data.contMenuGauge);
		  		var porcentaje = $('.linEst1').attr('data-porcentaje');
				porcentaje = parseInt(porcentaje, 10);
				
				var amarillo   = $('.linEst1').attr('data-porcent1');
		        var verde      = $('.linEst1').attr('data-porcent2');
		        var colorVerde = $('.linEst1').attr('data-colorVerde');
		        var colorRojo  = $('.linEst1').attr('data-colorRojo');
		        var inicioG    = $('.linEst1').attr('data-inicioG');
		        var finG       = $('.linEst1').attr('data-finG');
		        var tipo        = $('.linEst1').attr('data-tipo');

		        var cod        = $('.linEst1').attr('data-codInd');
		        $('#condInd').html('Cod.'+cod);
		        $('.barraEstado').css('background-color',$('.linEst1').attr('data-cBack'));
		        initGauge(porcentaje,1,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
		        
		        if(tipo == "NORMAL"){
		        	$('.highcharts-tracker').find("tspan").append(' %');
		        }
		        
		        $('.highcharts-data-labels g rect').css('display','none');
	        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
	        	$('.highcharts-series-group circle').css('fill','#959595');
				
	        	clearTimeout(timer);
	        	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
	        	componentHandler.upgradeAllRegistered();
				abrirCerrarModal('modalEditMeta');
			} else if(data.error == 1){//ERROR
				clearTimeout(timer);
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			} else if(data.error == 2) {
				clearTimeout(timer);
				mostrarNotificacion('warning', data.msj, 'Ojo:');
			}
			}
	     })
     	 .fail(function(jqXHR, textStatus, errorThrown) {
     		 mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
	  	 })
	  	 .always(function() {		      	 
	     
	  	 });
    });
}

function cambioCheckEstructura(cb){
	var index = $(cb).closest('tr').attr('data-index');//console.log('index: '+index);
	onChangeCheckEstructura("tb_estructura", index, 1, cb.checked, cb.id, cb.getAttribute('attr-idEst'),cb.getAttribute('attr-idIndDeta'),
			                 cb.getAttribute('attr-descReg'),cb.getAttribute('attr-bd'));
}

function onChangeCheckEstructura(idTable, index, column, nuevoValor, id, id_Est, id_IndDeta, descReg, bd){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal){
		cambioCheck = false;
	}else{
		cambioCheck = true;
	}
	var checkEst = 		'<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'">'+
					    '    <input type="checkbox" class="mdl-checkbox__input" onclick="cambioCheckEstructura(this);" id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambioCheck+'" attr-idEst="'+id_Est+'" attr-descReg="'+descReg+'" attr-idIndDeta="'+id_IndDeta+'" '+check+'> '+
					    '    <span class="mdl-checkbox__label"></span> '+
					    '</label>';
	
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : column,
		fieldValue : checkEst
	});
	componentHandler.upgradeAllRegistered();
}

function guardarEstructura(){
	addLoadingButton('botonGE')
	console.time('Test performance');
	var json = {};
	var estructuras = [];
	json.estructura = estructuras;
	var arrayData = getCheckedFromTablaByAttr('tb_estructura', 1);
	$.each( arrayData, function( key, value ) {
		var idIndDeta  = $(value).find(':checkbox').attr('attr-idIndDeta');
		var idEst      = $(value).find(':checkbox').attr('attr-idEst');
		var descReg    = $(value).find(':checkbox').attr('attr-descReg');
		var valor      = $(value).find(':checkbox').is(':checked');
		var estructura = {"idEst" : idEst, "valor" : valor, "idIndDeta" : idIndDeta, "descReg" : descReg};
 		json.estructura.push(estructura);
	});
	var displayHisto = $('#displayHisto').css('display');
	var jsonStringEstructura = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_detalle_indicador/grabarEstructura',
		data : { estructuras  : jsonStringEstructura,
			     displayHisto : displayHisto}, 
		async : true
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0){
				mostrarNotificacion('success', data.msj, data.cabecera);
				
				//$('#cont_tb_estructura').html(data.tabla_estructura);
		  		$('#tb_estructura').bootstrapTable({ });
				$('#contArbolIndiDeta').html(data.tablaHijos);
				initTree();
				$('#detalleLastModi').html(data.detalleModal);
				$('#historiaCardDiv').html(data.historiaInd);

		  		$("#contGauge").html(data.contGauge);
		  		$("#menuGauge").html(data.contMenuGauge);
		  		var porcentaje = $('.linEst1').attr('data-porcentaje');
				porcentaje = parseInt(porcentaje, 10);
				
				var amarillo   = $('.linEst1').attr('data-porcent1');
		        var verde      = $('.linEst1').attr('data-porcent2');
		        var colorVerde = $('.linEst1').attr('data-colorVerde');
		        var colorRojo  = $('.linEst1').attr('data-colorRojo');
		        var inicioG    = $('.linEst1').attr('data-inicioG');
		        var finG       = $('.linEst1').attr('data-finG');
		        var tipo        = $('.linEst1').attr('data-tipo');

		        var cod        = $('.linEst1').attr('data-codInd');
		        $('#condInd').html('Cod.'+cod);
		        $('.barraEstado').css('background-color',$('.linEst1').attr('data-cBack'));
		        initGauge(porcentaje,1,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
		        
		        if(tipo == "NORMAL"){
		        	$('.highcharts-tracker').find("tspan").append(' %');
		        }
		        
		        $('.highcharts-data-labels g rect').css('display','none');
	        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
	        	$('.highcharts-series-group circle').css('fill','#959595');
	        	
				abrirCerrarModal('modalAgregarEstructura');
				$("body").tooltip({ selector: '[data-toggle=tooltip]' });
			}else if(data.error == 1){
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
			}
			stopLoadingButton('botonGE');
		}
	});
	console.timeEnd('Test performance');
}

function openModalDetalleByIndicador(idIndDeta) {
	$('#idIndDeta').val(idIndDeta);
	$.ajax({
		type  : 'POST',
		url   : 'c_detalle_indicador/getDetalleIndi',
		data  : { idIndDeta   : idIndDeta}
	}).done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.comboCond == 1) {
				$('#contCombo').html(data.combo);
				$('select[name=selectCombo]').val("");
				$('#selectCombo').selectpicker('refresh');
				$('#contTbIndicadoresModal').html('');
		  		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
		  		    $('.pickerButn').selectpicker('mobile');
		  		} else {
		  			$('.pickerButn').selectpicker();
		  		}
			} else {
			    var titleObj = $('#modalConsultarDetalle').find('.mdl-card__title-text');
				if(data.nroCopas != undefined) {
					titleObj.text('Competencias');
				} else if (data.participaron != undefined) {
				} else if (data.totalDocentesCertificado != undefined) {
					titleObj.text('Docente');
				} else if (data.totalPPu != undefined) {
					titleObj.text('Detalle del indicador (puesto) ');
				} else if (data.porcentajetard != undefined) {
					titleObj.text('Indice de Tardanza Escolar ');
				} else if (data.totalInicio != undefined || data.totalProceso != undefined || data.totalSatisfa != undefined) {
				    $('#lblAptos').html("Satisfactorio: " + data.totalSatisfa);
					$('#lblPorcentaje').html("Proceso: " + data.totalProceso);
				    $('#lblNivelLogro').html("Inicio: " + data.totalInicio);
				    $('#lblOtros').html("Otros: " + data.otros);
				    titleObj.text('Niveles de Logros de los Alumnos');
				} else if (data.totalDocentesInglesNativo != undefined) {
					titleObj.text('Docente');
				} else if (data.notasSD != undefined) {
					titleObj.text('Detalle de notas del SD');
				} else if(data.tutor) {
					titleObj.text('Estudiantes');
				}
			}
			if(data.comboCond == 1) {
				$('#contComboIndicadoresModal').html(data.combo);
				if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
				    $('.pickerButn').selectpicker('mobile');
				} else {
					$('.pickerButn').selectpicker();
				}
				$('#contTbIndicadoresModal').html(null);
			} else {
				$('#contComboIndicadoresModal').html(null);
				$('#contTbIndicadoresModal').html(data.tabla);
			}
		    $('#tb_detalle_indicador').bootstrapTable({ });
		}
	});
	modal('modalConsultarDetalle');
}

function getAlumnosByAulaOrdenMerito(){
	var idAula = $('#selectCombo option:selected').val();
	
	$.ajax({
		type  : 'POST',
		url   : 'c_detalle_indicador/getAlumnosByAulaMerito',
		data  : { idAula : idAula},
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);
			if(data.error == 0){
				$('#contTbIndicadoresModal').html(data.tabla);
		  		$('#tb_detalle_indicador').bootstrapTable({ });
			}
		}
	});
}

/**
 * Metodo que actualiza la meta
 * @author dfloresgonz
 * @since 01.10.2015
 */
function actualizarActual() {
	Pace.restart();
	Pace.track(function() {
		rotate();
		var displayHisto = $('#displayHisto').css('display');
		$.ajax({
	        'url' : "c_detalle_indicador/actualizarActual",
	        type  : 'POST',
	        data  : { displayHisto : displayHisto}
	  	})
	  	.done(function(data) {
	  		if(data == "") {
				location.reload();
			} else {
		  		data = JSON.parse(data);
				if(data.error == 0) {
					mostrarNotificacion('success', data.msj, data.cabecera);
					$('#contArbolIndiDeta').html(data.tablaHijos);
					initTree();
					$('#detalleLastModi').html(data.detalleModal);
					$('#fechaModi').text(data.fechaModi);
					$('#historiaCardDiv').html(data.historiaInd);	
					
					$("#contGauge").html(data.contGauge);
			  		$("#menuGauge").html(data.contMenuGauge);
			  		var porcentaje = $('.linEst1').attr('data-porcentaje');
					porcentaje = parseInt(porcentaje, 10);
					
					var amarillo   = $('.linEst1').attr('data-porcent1');
			        var verde      = $('.linEst1').attr('data-porcent2');
			        var colorVerde = $('.linEst1').attr('data-colorVerde');
			        var colorRojo  = $('.linEst1').attr('data-colorRojo');
			        var inicioG    = $('.linEst1').attr('data-inicioG');
			        var finG       = $('.linEst1').attr('data-finG');
			        var tipo        = $('.linEst1').attr('data-tipo');
			        
			        var cod        = $('.linEst1').attr('data-codInd');
			        $('#condInd').html('Cod.'+cod);
			        $('.barraEstado').css('background-color',$('.linEst1').attr('data-cBack'));
			        initGauge(porcentaje,1,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
			        
			        if(tipo == "NORMAL"){
			        	$('.highcharts-tracker').find("tspan").append(' %');
			        }
					
					$('.highcharts-data-labels g rect').css('display','none');
		        	$('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		        	$('.highcharts-series-group circle').css('fill','#959595');
		        	
		        	clearTimeout(timer);
		        	abrirCerrarModal("modalActualizarActual");
		        	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
				} else if(data.error == 1){//ERROR
					mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
					clearTimeout(timer);
				} else if(data.error == 2) {
					mostrarNotificacion('warning', data.msj, 'Ojo:');
					clearTimeout(timer);
				}
			}
	     })
	 	 .fail(function(jqXHR, textStatus, errorThrown) {
	 		 mostrarNotificacion('error','Comuníquese con alguna persona a cargo :(', 'Error');
	  	 })
	  	 .always(function() {		      	 
	     
	  	 });
	});
}

function openModalActualizarActual(){
	abrirCerrarModal("modalActualizarActual");
}

function editarValorZonRiesgoIndicador(){
	addLoadingButton('botonEVZ');
	Pace.restart();
	Pace.track(function() {
		valor = $("#valorAmarillo").val();
		$.ajax({  
	        data: {valor : valor},
	        url: "C_detalle_indicador/editValorAmarillo",
	        type: 'POST'
	  	})
	  	.done(function(data) {
	  		if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
		  		if(data.error == 0){
		  			var idCont = data.idCont;
		  			var posicion = data.posicion;
		  			$('#'+idCont).replaceWith(data.contGauge);
		  			var porcentaje = $('.linEst'+posicion).attr('data-porcentaje');
		  			porcentaje = parseInt(porcentaje, 10);
		  			var amarillo   = $('.linEst'+posicion).attr('data-porcent1');
		  	        var verde      = $('.linEst'+posicion).attr('data-porcent2');
		  	        var inicioG    = $('.linEst'+posicion).attr('data-inicioG');
		  	        var finG       = $('.linEst'+posicion).attr('data-finG');
		  	        var tipo        = $('.linEst'+posicion).attr('data-tipo');
		  	        var colorVerde = $('.linEst'+posicion).attr('data-colorVerde');
		            var colorRojo  = $('.linEst'+posicion).attr('data-colorRojo');
		            var dorado     = $('.linEst'+posicion).attr('data-dorado');
		  	        
		            if(dorado == 1){
		            	$('.linEst'+posicion).html('<img class="icon-cup" src="'+window.location.origin+'/bsc/public/files/images/indicador/icon_cup.png">');
		            }else{
		            	 initGauge(porcentaje,posicion,amarillo,verde,colorVerde,'#F4DC51',colorRojo,Number(inicioG),Number(finG));
		            }
		  	        $('#barra').css('background-color',$('.linEst'+posicion).attr('data-cBack'));
		            
		            if(tipo == "NORMAL"){
		            	$(".linEst"+posicion).find("tspan").append(" %");
		            }
		  	       
		  	        $('.highcharts-data-labels g rect').css('display','none');
		  	        $('.highcharts-container svg path[fill="transparent"]').css('fill','white');
		      		$('.highcharts-series-group circle').css('fill','#959595');
		      		$('.highcharts-tracker').find("tspan").css('fill','#959595');
		  			mostrarNotificacion('success', data.msj, data.cabecera);
		  			abrirCerrarModal('modalEditar');
		  		} else{
		  			
		  		}
		  		stopLoadingButton('botonEVZ');
			}
	     });
	 });
}
/*function openModalFrecuencia(){
	$.ajax({
		type : 'POST',
		url : 'c_frecuencia_medicion/getAllFrecuenciasXIndicador',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);			
		$('#contTbFrecuencias').html(data.tabla);
		$('#numeroMedicion').val(data.lastMedicion);
		$('#tbFrecuencias').bootstrapTable({ });
	});
	initXEditable();
	abrirCerrarModal('modalFrecuencia');
}*/

function grabarNuevaMedicion(){
	addLoadingButton('botonNM');
	var nroMedicion    = $('#numeroMedicion').val();
	var descFrecuencia = $('#descFrecuencia').val();
	var fechaMedicion  = $('#fechaMedicion').val();
	if($.isNumeric(nroMedicion) && descFrecuencia != null && fechaMedicion != null && descFrecuencia != "" && fechaMedicion != "" && isDate(fechaMedicion)){
		$.ajax({
			data  : { nroMedicion    : nroMedicion,
				      fechaMedicion  : fechaMedicion,
				      descFrecuencia : descFrecuencia},
			url   : "c_frecuencia_medicion/addNuevaMedicion",
			type  : 'POST',
			async : true
		})
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
				if(data.error == 0){
					$('#contTbFrecuencias').html(data.tabla);
					$('#numeroMedicion').val(data.lastMedicion);
			  		$('#tb_frecuencias').bootstrapTable({ });
			  		//initXEditable();
			  		abrirCerrarModal('modalAddFrecuencia');
			  		stopLoadingButton('botonNM');
			  		mostrarNotificacion('success' , data.msj , 'Registró');			  		
			  		postTrans("formNuevaMedi");
				}else{
					stopLoadingButton('botonNM');
					mostrarNotificacion('warning' , data.msj , 'Ojo');
				}
			}			
		});
	} else{
		if(!isDate(fechaMedicion)){
			stopLoadingButton('botonNM');
			mostrarNotificacion('warning' , 'Ingrese una fecha valida' , 'Ojo');
		} else{
			stopLoadingButton('botonNM');
			mostrarNotificacion('warning' , 'No deben haber campos vacios' , 'Ojo');
		}
	}
	stopLoadingButton('botonNM');
}

function limpiarModalAddFrecuencia(){
	$('#descFrecuencia').val("");
	$('#fechaMedicion').val("");
	postTrans("formNuevaMedi");
}

function validXEditableDescripcion(){
	$('td').find(".classDescrip").editable({
        type: 'text',
        name: 'desc_frecuencia',
        url: 'c_frecuencia_medicion/editarFreqMedicion',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene la descripci&oacute;n';
            }
            if(value.length > 40){
            	return 'La descripci&oacute;n no debe exceder 40 caracteres';
            }
        },
        success: function(response, newValue) {
            var data = JSON.parse(response);
            var index = $(this).closest('tr').attr('data-index');
            successValid('tb_frecuencias', index, 1, data.pk, newValue, null,'classDescrip');
            initXEditable();
        }
    });
}

function validXEditableFecha(){
	$('td').find(".classFecha").editable({
        type: 'text',
        name: 'fecha_medicion',
        url: 'c_frecuencia_medicion/editarFreqMedicion',
        validate: function(value) {
        	if($.trim(value) == '') {
                return 'Llene la fecha de medici&oacute;n';
            }
            if(value.length > 10){
            	return 'La fecha no debe exceder 10 caracteres';
            }
            if(!isDate(value)) {
            	mostrarNotificacion('error', 'El formato de la fecha es incorrecto', 'Error');
            	return 'El formato de la fecha es incorrecto';
            }
           /* if(existCampoEdit('razon_social', value,'escuela', $(this).attr('data-pk'),'id_escuela') == 1){
            	return 'Ya existe la razón social';
            }*/
        },
        success: function(response, newValue) {
        	if(response == ""){
    			location.reload();
    		} else{
	            var data = JSON.parse(response);
	            var index = $(this).closest('tr').attr('data-index');
	            successValid('tb_frecuencias', index, 2, data.pk, newValue, null,'classFecha');
	            initXEditable();
	            mostrarNotificacion('success',data.msj,'Registró');
    		}
        },
        error: function(data) {
        	if(data == ""){
    			location.reload();
    		} else{
	            data = JSON.parse(data.responseText);
	            initXEditable();
	        	mostrarNotificacion('warning',data.msj,'Ojo');
    		}
        }
    });
}

function initXEditable(){
	$.fn.editable.defaults.mode = 'inline';
	validXEditableDescripcion();
    validXEditableFecha();
}


function openModalAsignarComparativa(){
	$.ajax({
		type : 'POST',
		url : 'c_comparativa/getAllComparativasByIndicador',
		async : false
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);	
			$('#contTbComparativas').html(data.tablaCompXIndi);
			$('#tb_comparativas_x_indicador').bootstrapTable({ });
		}
	});
	abrirCerrarModal('modalAsignarComparativa');
}

function cambioCheckComparativa(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckComparativa("tb_comparativas_x_indicador", index, 3, cb.checked, cb.id, cb.getAttribute('attr-idcomparativa'),
			                 cb.getAttribute('attr-bd'));
}

function onChangeCheckComparativa(idTable, index, column, nuevoValor, id, idIndicador, bd){
	var check = "checked";
	if(nuevoValor == false ) {
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal){
		cambioCheck = false;
	}else{
		cambioCheck = true;
	}	
	var checkComp = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'">'+
				    '    <input type="checkbox"  class="mdl-checkbox__input" '+check+' id="'+id+'" attr-bd="'+bd+'" + attr-cambio="'+cambioCheck+'" attr-idcomparativa="'+idIndicador+'"> '+
				    '    <span class="mdl-checkbox__label"></span> '+
				    '</label>';
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 3,
		fieldValue : checkComp
	});
	componentHandler.upgradeAllRegistered();
}

function capturarComparativasXIndicador(){
	addLoadingButton('botonCCI');
	var json = {};
	var comparativas = [];
	json.comparativa = comparativas;
	var arrayData = getCheckedFromTablaByAttr('tb_comparativas_x_indicador', 3);
	$.each( arrayData, function( key, value ) {
		var idComparativa = $(value).find(':checkbox').attr('attr-idcomparativa');
		var valor       = $(value).find(':checkbox').is(':checked');
		var comparativa = {"idComparativa" : idComparativa, "valor" : valor};
 		json.comparativa.push(comparativa);
	});
	var jsonStringPersona = JSON.stringify(json);
	$.ajax({
		type : 'POST',
		url : 'c_detalle_indicador/grabarComparativasIndicador',
		data : {comparativas   : jsonStringPersona}, 
		async : true
	})
	.done(function(data){
		if(data == ""){
			location.reload();
		} else{
			data = JSON.parse(data);	
			if(data.error == 1){
				
				mostrarNotificacion('error', data.msj , 'Error');
				stopLoadingButton('botonCCI');
			} else{
				$('#contTbComparativas').html(data.tablaCompXIndi);
				$('#tb_comparativas_x_indicador').bootstrapTable({ });
				
				mostrarNotificacion('success', data.msj , data.cabecera);
				stopLoadingButton('botonCCI');
				//abrirCerrarModal('modalAsignarComparativa');
			}
		}
		componentHandler.upgradeAllRegistered();
		stopLoadingButton('botonCCI');
	});
}

function backDetalle(data) {
	if(data == 1) {
		$('#backDetalle').addClass('backDetalle');
	} else {
		$('#backDetalle').removeClass('backDetalle');
	}
}

function openModalEditarValorAmarilloDetalle(data, cont, pos) {
	$.ajax({
		data : { idIndicador  : data,
				 idCont       : cont,
				 pos          : pos },
		url  : 'c_detalle_indicador/getValorAmarillo',
		type : 'POST'
	}).done(function(data) {
		if(data == "") {
			location.reload();
		} else {
			data = JSON.parse(data);
			if(data.error == 1) {
				msj('warning', data.msj);
			} else if(data.error == 0) {
				setearInput("valorAmarillo", data.valorAmarillo);
				setearInput("valorMeta", data.valorMeta);
				modal('modalEditar');
			}
		}
	});
}

function verImagenResponsable(foto,nombre,telf,correo,id) {
	$("#img_repsonsable").attr("src",foto);
	$('#nombreReponsableModal').text(nombre);

	$("#btnllamada").click(function(){ window.location = "tel:"+telf; });
	$('#btnperfil').attr("onclick", "goToPerfilUsuario('"+id+"')");
	$("#btnemail").click(function(){ window.open('mailto:'+correo); });

	modal("modalViewResponsable");
}
/*INICIO DEL MODAL POPUP 
 * 02/11/2015
AGREGAR PERSONAS RESPONSABLES DE MEDICION*/
function openModalNewResponsableMedicion() {
	abrirCerrarModal("modalAsignaPersonas");
	setFirstInput();
}

function getPersonasAddIndicador(){
	var nombrePersona = $.trim($('#nombrePersona').val());
	if(nombrePersona == null || nombrePersona == ""){
		mostrarNotificacion('warning','No ha ingresado ningún nombre','Ojo');
		return;
	}
	if(nombrePersona.length >= 3) {
		$.ajax({
			type	: 'POST',
			'url'	: 'c_detalle_indicador/tablePersonasAddIndicador',
			data	: { nombrePersona : nombrePersona },
			'async' : false
		})
		.done(function(data){
			if(data == ""){
				location.reload();
			} else{
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbPersonasModal').html(data.tablePersonasModal);
					$('#tb_persona_by_nombre').bootstrapTable({});
				} else{
					mostrarNotificacion('error', 'Acción no permitida Ingrese un Indicador', 'ERROR');
				}
			}
		});
	}
	componentHandler.upgradeAllRegistered();
}

function cambioCheckIndicador(cb){
	var index = $(cb).closest('tr').attr('data-index');
	onChangeCheckRol("tb_persona_by_nombre", index, 2, cb.checked, cb.id, cb.getAttribute('attr-idpersona'),
			         cb.getAttribute('attr-bd'), cb.getAttribute('attr-idindicador'));
}

function onChangeCheckRol(idTable, index, column, nuevoValor, id, idPersona, bd, idIndicador){
	var check = "checked";
	if(nuevoValor == false ){
		check = "";
	}
	var bdVal = (bd == 'checked') ? true : false; 
	var cambioCheck = false;
	if(nuevoValor == bdVal){
		cambioCheck = false;
	}else{
		cambioCheck = true;
	}
	
	var checkIndicador = 	'<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="'+id+'">'+
						    '    <input type="checkbox"  class="mdl-checkbox__input" '+check+' id="'+id+'" attr-bd="'+bd+'" attr-cambio="'+cambioCheck+'" attr-idpersona="'+idPersona+'" attr-idindicador="'+idIndicador+'" onclick="cambioCheckIndicador(this);"> '+
						    '    <span class="mdl-checkbox__label"></span> '+
						    '</label>';
		
	$('#'+idTable).bootstrapTable('updateCell',{
		rowIndex   : index,
		fieldName  : 2,
		fieldValue : checkIndicador
	});
	componentHandler.upgradeAllRegistered();
}

function capturarIndicadoresPersona(){
	var nombrePersona = $.trim($('#nombrePersona').val());
	var condicion = 0;
	var json = {};
	var personas = [];
	json.persona = personas;
	if(nombrePersona == null || nombrePersona == ""){
		mostrarNotificacion('warning', 'No se ingreso ningun nombre', 'Ojo');
	} else{
		var arrayData = getCheckedFromTablaByAttr('tb_persona_by_nombre', 2);
		$.each( arrayData, function( key, value ) {
			var idPersona 	= $(value).find(':checkbox').attr('attr-idpersona');
			var idIndicador = $(value).find(':checkbox').attr('attr-idindicador');
			var valor       = $(value).find(':checkbox').is(':checked');
			var persona = {"idPersona" : idPersona, "valor" : valor , "idIndicador" : idIndicador};
	 		json.persona.push(persona);
	 		condicion = 1;
		});
		var jsonStringPersona = JSON.stringify(json);
		var idIndicador = $('#selectIndicador option:selected').val();
		if(condicion == 0){
			abrirCerrarModal('modalAsignaPersonas');
			mostrarNotificacion('warning','No se hicieron cambios','Ojo');
		} else{
			$.ajax({
				type : 'POST',
				url : 'c_detalle_indicador/grabarIndicadoresPersona',
				data : { personas : jsonStringPersona}, 
				async : false
			})
			.done(function(data) {
				if(data == ""){
					location.reload();
				} else{
					data = JSON.parse(data);
						if(data.error == 1) {
							mostrarNotificacion('error', 'Acci&oacute;n no permitida', 'ERROR');
						} else {
							$('#divRespo').html(data.responsables);
							mostrarNotificacion('success', 'Datos Registrados', 'SUCCESS');
							abrirCerrarModal('modalAsignaPersonas');
							$('#contTbPersonasModal').html(null);
							$('#nombrePersona').val(null);
							$("body").tooltip({ selector: '[data-toggle=tooltip]' });
						}
				}
			});
		}
	}
	componentHandler.upgradeAllRegistered();
}

function getTablaByArea() {
	var idAreaEspecifica = $('#selectCombo option:selected').val();
	var idAreaGeneral    = $('#selectCombo').attr('attr-idarea');
	var idSede           = $('#selectCombo').attr('attr-idsede');
	var idIndi			 = $('#selectCombo').attr('attr-idindicador');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
			url   : 'c_detalle_indicador/getTablaByArea',
			data  : { idAreaEspecifica : idAreaEspecifica,
					  idAreaGeneral    : idAreaGeneral,
					  idSede		   : idSede,
					  idIndi		   : idIndi}
		})
		.done(function(data) {
			if(data == "") {
				location.reload();
			} else {
				data = JSON.parse(data);
				if(data.error == 0) {
					$('#contTbIndicadoresModal').html(data.tabla);
			  		$('#tb_detalle').bootstrapTable({ });
				} else {
					mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+data.msj, 'ERROR');
				}
			}
		});
	});
}

function verDetalleAsistenciaPuntualidad(nif) {
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
			url   : 'c_detalle_indicador/getDetalleAsistenciaPuntualidad',
			data  : { nif : nif }
		}).done(function(data) {
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#contAsistPuntua').html(data.tabla);
		  		$('#tb_deta_asis_punt').bootstrapTable({ });
		  		modal('modal_asist_puntu');
			}
		});
	});
}

function abrirModalVerResponsables(){
	$.ajax({
		url   : 'c_detalle_indicador/verResponsablesIndicador',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#cont_tb_resp_indi').html(data.personas);
	});
	abrirCerrarModal('modalResponsablesIndicador');
}

function cerrarIndicador() {
	addLoadingButton('save-buttom');
	Pace.restart();
	Pace.track(function() {
		var displayHisto = $('#displayHisto').css('display');
		$.ajax({
			type  : 'POST',
			url   : 'c_detalle_indicador/cerrarIndicador',
			data  : { displayHisto : displayHisto},
			async : false
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success', data.msj, data.cabecera);
				location.reload();
			} else if(data.error == 1) {//ERROR
				mostrarNotificacion('error', CONFIG.get('MSJ_ERR')+' - '+data.msj, CONFIG.get('CABE_ERR'));
				clearTimeout(timer);
			} else if(data.error == 2) {
				mostrarNotificacion('warning', data.msj, 'Ojo:');
				clearTimeout(timer);
			}
			stopLoadingButton('save-buttom');
		});
	});
}

function goToPerfilUsuario(data){
	window.open(
			window.location.origin+'/schoowl/c_perfil?usuario='+data,
			  '_blank' 
			);
}

function abrirModalVerResponsables(){
	$.ajax({
		url   : 'c_detalle_indicador/getResponsablesIndicador',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#tableResponsables').html(data.table);
		$('#tb_responsables').bootstrapTable({ });
		modal('modalShowResponsables');
	});
}