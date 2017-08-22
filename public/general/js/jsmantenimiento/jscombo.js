function init(){
	$("#cont_titulo_crear input, select, textarea").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			visibleOpcionesCrear();
		}
	});
	$('#tb_combos').bootstrapTable({ });
	$('.fixed-table-toolbar').addClass('mdl-card__menu');
	initSearchTableNew();
}

function abrirModalCrearCombo(){
	abrirCerrarModal("modalRegistrarcombo");
}

function visibleOpcionesCrear(){
	var titulo = $("#tituloCrear").val();
	if(titulo.length != 0){
		$("#cont_opciones_crear").css("display", "block");
	}else{
		$("#cont_opciones_crear").css("display", "none");
	}
}

var cons_contadorOpcion = 2;
function agregarOpcionCrear(){
	var opcion = $("#opcionCrear_"+(cons_contadorOpcion-1)).val();
	if(opcion.length != 0){
		var inputtext = '<div id="con_opcion_crear_'+cons_contadorOpcion+'" class="con_opcion_crear">'+
			             '<div class="col-xs-10 p-0 m-0 m-b-20">'+
							      '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
						            '<input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcionCrear" name="opcionCrear_'+cons_contadorOpcion+'" id="opcionCrear_'+cons_contadorOpcion+'">'+
						            '<label class="mdl-textfield__label" for="opcionCrear_'+cons_contadorOpcion+'">Nombre Opci&oacute;n</label>'+
						        '</div>'+
							'</div>'+
							'<div class="col-xs-2 p-0 m-0 m-b-20">'+
							'         <button type="button" onclick="agregarOpcionCrear()" id="btn_crear_opcion_'+cons_contadorOpcion+'" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-add"></i></button>'+
							'	</div>'+
						 '</div>';
		
		$("#con_opcion_crear_"+(cons_contadorOpcion-1)).after(inputtext);
		$("#btn_crear_opcion_"+(cons_contadorOpcion-1)).find("i").removeClass("md-add").addClass("md-delete");
		$("#btn_crear_opcion_"+(cons_contadorOpcion-1)).attr("onclick", "eliminarOpcionCrear('con_opcion_crear_"+(cons_contadorOpcion-1)+"')");
		
		$("#opcionCrear_"+cons_contadorOpcion).focus();
		
		componentHandler.upgradeAllRegistered();
		cons_contadorOpcion++;
	}
}

function eliminarOpcionCrear(cont){
	$("#"+cont).remove();
}

function registrarCombo(){
	var titulo = $("#tituloCrear").val();
	
	var arrayOpciones = [];
	$(".inputTextOpcionCrear").each(function(i,obj){
		if($(this).val().length != 0){
			arrayOpciones.push($(this).val());
		}
	});
	
	$.ajax({
		url: "c_combo/insertCombo",
        data: { titulo   : titulo,
        		opciones : arrayOpciones},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			setearInput("tituloCrear", null);
			var inputtext = '<div id="con_opcion_crear_1" class="con_opcion_crear">'+
					            '<div class="col-xs-10 p-0 m-0 m-b-20">'+
									      '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
								            '<input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcionCrear" name="opcionCrear_1" id="opcionCrear_1">'+
								            '<label class="mdl-textfield__label" for="opcionCrear_1">Nombre Opci&oacute;n</label>'+
								        '</div>'+
									'</div>'+
									'<div class="col-xs-2 p-0 m-0 m-b-20">'+
									'         <button type="button" onclick="agregarOpcionCrear()" id="btn_crear_opcion_1" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-add"></i></button>'
									'	</div>'+
								 '</div>';
			$("#cont_opciones_crear").html(inputtext).css("display", "none");
			$("#conTablaCombos").html(data.tablaCombos);
			$('#tb_combos').bootstrapTable({ });
			$('.fixed-table-toolbar').addClass('mdl-card__menu');
			initSearchTableNew();
			cons_contadorOpcion = 2;
			componentHandler.upgradeAllRegistered();
			mostrarNotificacion('success', data.msj, null);
		}else{
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

cons_grupo_combo = null;
function abrirModalEditCombo(grupoCombo){
	$("#cont_opciones_editar").html(null);
	$.ajax({
		url: "c_combo/detalleCombo",
        data: { grupo : grupoCombo},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		setearInput("tituloEditar", data.tituloCombo);
		
		opc = JSON.parse(data.opc);
		
		count = 0;
		for(var i = 0; i <opc.length;i++){
			var inputtext = '<div id="con_opcion_editar_'+(i+1)+'" class="con_opcion_editar">'+
								'<div class="col-xs-10 p-0 m-0 m-b-20">'+
									      '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
								            '<input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcioneditar" name="opcionEditar_'+(i+1)+'" id="opcionCrear_'+(i+1)+'" value="'+opc[i][0]+'" attr-valor="'+opc[i][1]+'">'+
								            '<label class="mdl-textfield__label" for="opcionEditar_'+(i+1)+'">Nombre Opci&oacute;n</label>'+
								        '</div>'+
									'</div>'+
									'<div class="col-xs-2 p-0 m-0 m-b-20">'+
									'         <button type="button" onclick="eliminarOpcionEditar(\''+opc[i][1]+'\', \'con_opcion_editar_'+(i+1)+'\')" id="btn_agregar_opcion_editar_'+(i+1)+'" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-delete"></i></button>'
									'	</div>'+
								 '</div>';
			
			
			$("#cont_opciones_editar").append(inputtext);
			count = i;
		}
		var inputtext = '<div id="con_opcion_editar_'+(count+2)+'" class="con_opcion_editar">'+
							'<div class="col-xs-10 p-0 m-0 m-b-20">'+
								      '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
							            '<input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcioneditar" name="opcionEditar_'+(count+2)+'" id="opcionEditar_'+(count+2)+'">'+
							            '<label class="mdl-textfield__label" for="opcionEditar_'+(count+2)+'">Nombre Opci&oacute;n</label>'+
							        '</div>'+
								'</div>'+
								'<div class="col-xs-2 p-0 m-0 m-b-20">'+
								'		  <button type="button" onclick="agregarOpcionEditar('+(count+2)+')" id="btn_agregar_opcion_editar_'+(count+2)+'" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-add"></i></button>'+
								'	</div>'+
							 '</div>';
					
					
		$("#cont_opciones_editar").append(inputtext);
		componentHandler.upgradeAllRegistered();
		cons_grupo_combo = grupoCombo;
		abrirCerrarModal("modalEditarcombo");
	});
}

function eliminarOpcionEditar(valor, cont){
	$.ajax({
		url: "c_combo/deleteOpcionCombo",
        data: { grupo : cons_grupo_combo,
        		valor : valor},
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#"+cont).remove();
			mostrarNotificacion('success', data.msj, null);
		}else{
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function agregarOpcionEditar(count){
	desc = $("#opcionEditar_"+count).val();
	if(desc.length != 0){
		$.ajax({
			url: "c_combo/agregarOpcionCombo",
	        data: { grupo : cons_grupo_combo,
	        	    desc  : desc},
	        async : false,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#opcionEditar_"+count).attr("attr-valor", data.valor);
				var inputtext = '<div id="con_opcion_editar_'+(count+1)+'" class="con_opcion_editar">'+
						             '<div class="col-xs-10 p-0 m-0 m-b-20">'+
										      '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">'+
									            '<input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcionCrear" name="opcionEditar_'+(count+1)+'" id="opcionEditar_'+(count+1)+'">'+
									            '<label class="mdl-textfield__label" for="opcionEditar_'+(count+1)+'">Nombre Opci&oacute;n</label>'+
									        '</div>'+
										'</div>'+
										'<div class="col-xs-2 p-0 m-0 m-b-20">'+
										'         <button type="button" onclick="agregarOpcionEditar('+(count+1)+')" id="btn_agregar_opcion_editar_'+(count+1)+'" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-add"></i></button>'+
										'	</div>'+
									 '</div>';

					$("#con_opcion_editar_"+count).after(inputtext);
					$("#btn_agregar_opcion_editar_"+count).find("i").removeClass("md-add").addClass("md-delete");
					$("#btn_agregar_opcion_editar_"+count).attr("onclick", 'eliminarOpcionEditar(\''+data.valor+'\', \'con_opcion_editar_'+count+'\')');//FALTA
					
					$("#opcionEditar_"+(count+1)).focus();
					
					componentHandler.upgradeAllRegistered();
				
				mostrarNotificacion('success', data.msj, null);
			}else{
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	}
}

function cambiarEstadoCombo(cb, grupo){
	foco = $(cb).attr("attr-foco");
	$.ajax({
		url: "c_combo/cambiarEstado",
        data: { grupo : grupo,
        	    foco  : foco },
        async : false,
        type: 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			if(foco == 1){
				$(cb).attr("attr-foco", 0);
			}else{
				$(cb).attr("attr-foco", 1);
			}
			mostrarNotificacion('success', data.msj, null);
		}else{
			mostrarNotificacion('warning', data.msj, null);
		}
	});
}

function editarCombo(){
	titulo = $("#tituloEditar").val();
	if(titulo.length != 0){
		var json = {};
		opciones = [];
		json.opcion = opciones;
		
		$(".inputTextOpcioneditar").each(function(i,obj){
			if($(this).val().length != 0){
				valor = $(this).attr("attr-valor");
				desc  = $(this).val();
				if(valor.length != 0){
					var opcion = {"valor" : valor, 
						          "desc"  : desc};
					json.opcion.push(opcion);
				}
			}
		});
		
		var jsonStringOpciones = JSON.stringify(json);
		
		$.ajax({
			url: "c_combo/cambiarDescCombo",
	        data: { grupo    : cons_grupo_combo,
	        	    titulo   : titulo,
	        	    opciones : jsonStringOpciones },
	        async : false,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.error == 0){
				$("#conTablaCombos").html(data.tablaCombos);
				$('#tb_combos').bootstrapTable({ });
				$('.fixed-table-toolbar').addClass('mdl-card__menu');
				initSearchTableNew();
				componentHandler.upgradeAllRegistered();
				mostrarNotificacion('success', data.msj, null);
			}else{
				mostrarNotificacion('warning', data.msj, null);
			}
		});
	}
}