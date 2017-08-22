var throttle = false;
var time = 0.5;
var imagenRecortada = null;

$("#itFotoUpd").change(function(){
	subirFoto();
});

if(document.querySelector('.full-bleed')) {
	document.querySelector('.full-bleed').addEventListener('click', function (evt) {
	    if (!throttle && evt.detail === 7) {
	    	abrirCerrarModal('modalSchoowl');
	        throttle = true;
	        setTimeout(function () {  
	            throttle = false;
	        }, 1000);
	    }
	});	
}

function init(){
	  /*ICONO EDITAR FOTO*/
      $( "#foto_perfil" )
	  .mouseenter(function() {
		  $("#cambiar_foto").css("display", "block");
	  })
	  .mouseleave(function() {
		  $("#cambiar_foto").css("display", "none");
	  });
      
      $( "#cambiar_foto" )
	  .mouseenter(function() {
		  $("#cambiar_foto").css("display", "block");
	  });

	/*TOUR*/
	var tour = new Tour({
		  name : "tourPerfil",
		  template: "<div class='popover tour' style='text-align:center'><div class='arrow'></div><h3 class='popover-title' style='background-color:#99A5BD;color:white'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-default' data-role='prev'>Atras</button><span data-role='separator'></span><button class='btn btn-default' data-role='next'>Siguiente</button></div><button class='btn btn-default' data-role='end' style='margin-bottom:5px'>Terminar</button></nav></div>",
		  steps: [
		  {
		    element: "#cabecera_perfil",
		    title: "Tu muro",	
		    content: "Aqui podras ver tu muro"
		  },
		  {
		    element: "#foto_perfil",
		    title: "Tu foto",
		    content: "Aqui podras ver tu foto de perfil",
		    backdrop:true,
		    reflex: true,
		    animation: true
		  }
		]});

		//tour.init();
		//tour.start();
	initValidDatos();
	$('#tb_cumple').bootstrapTable({ });
	initSearchTable();
}

function initV2() {
	initValidDatos();
	//initSearchTable();
	//$('#tb_cumple').bootstrapTable({ });
}

function abrirEditarFoto(urlFoto){
	
	abrirCerrarModal("modalEditarFoto");
	setTimeout(function(){
		$('.cropper-container.cropper-bg').remove();
		$('#fotoPerfilRecortar').replaceWith('<img id="fotoPerfilRecortar">');
		$('#fotoPerfilRecortar').attr('src',urlFoto);
		initCropper('fotoPerfilRecortar');
		$("#cambiar_foto").css("display", "none");
	}, 150);
	
}

function grabarClave() {
	var currPass = $('#currPassword').val();
	var newPass  = $('#newPassword').val();
	var newPass2 = $('#newPassword2').val();
	if(!currPass || !newPass || !newPass2) {
		$('#currPassword').focus();
		msj('error', 'Ingrese las claves');
		return;
	}
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			data : { currPass : currPass,
				     newPass  : newPass,
				     newPass2 : newPass2 },  
			url  : 'c_perfil/cambiarClave', 
			async: false,
			type : 'POST'
		}).done(function(data) {
			data = JSON.parse(data);
			try {
				if(data.error == 0) {
					msj('success', data.msj);
				} else {
					msj('error', data.msj);
				}
				$('#currPassword').val(null);
				$('#newPassword').val(null);
				$('#newPassword2').val(null);
			} catch(err) {
				location.reload();
			}
		});
	});
}

function initValidDatos(){
	$('#formCambioDatos').bootstrapValidator({
	    feedbackIcons: {
	        /*valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'*/
	    },
	    fields: {
	    	usuario: {
	            validators: {
	                notEmpty: {
	                    message: 'Ingrese su nombre de usuario'
	                },
	                stringLength: {
                        max: 30,
                        message: 'El nombre de usuario debe tener como máximo 30 caracteres'
                    },
	                callback: {
                        callback: function (value, validator) { 
                        	if(value != ""){
                        		result = existCampoById('usuario', value, 'persona');
    	                        if(result == '1'){//Existe
    	                        	return false;
    	                        }else{
    	                        	return true;
    	                        }
                        	}else{
                        		return true;
                        	}
                        }
                    }
	            }
	        },
	        email: {
	            validators: {
	                notEmpty: {
	                    message: 'Ingrese su email'
	                },
	                stringLength: {
                        max: 150,
                        message: 'El email debe tener como máximo 150 caracteres'
                    },
                    emailAddress: {
                        message: 'Ingrese un formato válido'
                    },
                    callback: {
                        message: 'El email ya esta registrado',
                        callback: function (value, validator) { 
                        	if(value != ""){
                        		result = existCampoById('correo', value, 'persona');
    	                        if(result == '1'){//Existe
    		                        return false;
    	                        }else{
    		                        return true;
    	                        }
                        	}else{
                        		return true;
                        	}
                        }
                    }
	            }
	        },
	        fechaNac: {
	            validators: {
	                date: {
                        format: 'DD/MM/YYYY',
                        message: 'La fecha debe tener el formato YYYY/MM/DD'
                    }
	            }
	        },
	        dni: {
	            validators: {
	                notEmpty: {
	                    message: 'Ingrese su número de DNI'
	                },	
	                stringLength: {
	                	min: 8,
                        max: 8,
                        message: 'El DNI debe tener como exactamente 8 caracteres'
                    },
                    integer: {
                        message: 'El DNI solo debe contener números',
                        // The default separators
                        thousandsSeparator: '',
                        decimalSeparator: '.'
                    },
                    callback: {
                        message: 'El Nro. Doc. ingresado ya esta registrado',
                        callback: function (value, validator) { 
                        	if(value != ""){
                        		result = existCampoById('nro_documento', value, 'persona');
    	                        if(result == '1'){//Existe
    		                        return false;
    	                        }else{
    		                        return true;
    	                        }
                        	}else{
                        		return true;
                        	}
                        }
                    }
	            }
	        },
	        telefono: {
	            validators: {
	                stringLength: {
                        max: 200,
                        message: 'El teléfono debe tener como máximo 200 caracteres'
                    }
	            }
	        },
	        tipoSangre: {
	        	validators: {
	        		stringLength: {
                        max: 10,
                        message: 'El tipo de sangre debe tener como máximo 10 caracteres'
                    } 
	        	}
	        }
	    }
	})
	.on('success.form.bv', function(e) {
		e.preventDefault();
		//$('#btnCambioDatos').attr('value', "<i class='fa fa-spinner fa-spin'></i> Loading...");
		
		//$('#btnCambioDatos').addClass('disabled');
	    var $form    = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        bv       = $form.data('bootstrapValidator');

	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    
	    $.ajax({
	        data: formData,
	        url: "editar",
	        cache: false,
	        contentType: false,
	        processData: false,
	        type: 'POST'
	  	})
	  	.done(function(data) {
	  		data = JSON.parse(data);
			if(data.error == 0){
				postTrans("formCambioDatos");
				$('#formCambioDatos').data('bootstrapValidator').resetForm(true);
				$('#usuario').val(data.nombreUsuario);
				$('#email').val(data.email);
				$('#fechaNac').val(data.fecha);
				$('#dni').val(data.dni);
				$('#telefono').val(data.telefono);
				$('#usuarioBarra').text(data.nombreUsuario);
				$('#usuarioCabecera').text(data.nombreUsuario);
				$('#tipoSangre').val(data.tipoSangre);
				$(":input").inputmask();
				mostrarNotificacion('success', 'Se ha modificado', '');
			}else{
				mostrarNotificacion('error', 'Contacte con la persona a cargo', 'Error');
			}
	  	})
	  	.fail(function(jqXHR, textStatus, errorThrown) {
	  		
	  	});
	});
}	
//id = ID DEL BOTON DE RECORTE
//idFoto = ID DE LA FOTO A RECORTAR
function initRecortarPerfil(id, idFoto){
    $image = initCropper(idFoto);
    var $this = $('#'+id);
    var data = $this.data();
    var $target;
    var result;
    
    //VERIFICAR SI EL BOTON ESTA DISABLED
    if ($this.prop('disabled') || $this.hasClass('disabled')) {
      return;
    }
    
    //VERIFICANDO QUE EXISTE
    if (data.method) {
      data = $.extend({}, data);
      if (typeof data.target !== 'undefined') {
        $target = $(data.target);
        if (typeof data.option === 'undefined') {
          try {
            data.option = JSON.parse($target.val());
          } catch (e) {
          }
        }
      }
      
      //RECORTAR IMAGEN
      result = $image.cropper('getCroppedCanvas', data.option, data.secondOption);
      if (data.method === "getCroppedCanvas" && result) {
        imagenRecortada = convertCanvasToImage(result);
        $('#fotoPerfilRecortar').next().replaceWith('<img id="fotoPerfilRecortar">');
        $("#fotoPerfilRecortar").remove();
        document.getElementById('fotoPerfilRecortar').setAttribute( 'src', imagenRecortada);
        initCropper('fotoPerfilRecortar');
        guardarImagenRecortadaPerfil();
        //$("#btnGuardarFoto").fadeIn();
      }
    }
}

function convertCanvasToImage(canvas) {
	var image = new Image();
	return canvas.toDataURL("image/png");
}

function guardarImagenRecortadaPerfil(){
	if(imagenRecortada != null){
		$.ajax({
			data : { urlImage  : imagenRecortada},  
			url  : 'cambiarFoto', 
			async: false,
			type : 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			$('img[name="foto_user_menu"]').attr('src','data:image/png;base64,'+data.foto);
			$('#foto_perfil').attr('src','data:image/png;base64,'+data.foto);
			$("#cambiar_foto1").attr('onclick', 'abrirEditarFoto("data:image/png;base64,'+data.foto+'")');
			
			abrirCerrarModal("modalEditarFoto");
			abrirCerrarModal("elegirModo");
			mostrarNotificacion('success', 'Se ha modificado', 'Registro');
		});
	}
}

function subirFoto(){
	var inputFileImage = document.getElementById("itFotoUpd");
    var file = inputFileImage.files[0];
    
    var formData = new FormData(); 
    formData.append('itFotoUpd', file);
    
    $.ajax({
        data: formData,
        url: "subirFoto",
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
  	})
	.done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
			$('img[name="foto_user_menu"]').attr('src','data:image/png;base64,'+data.foto);
			$('#foto_perfil').attr('src','data:image/png;base64,'+data.foto);
			$("#cambiar_foto1").attr('onclick', 'abrirEditarFoto("'+data.fotoUrl+'")');
			
			abrirCerrarModal("elegirModo");
			mostrarNotificacion('success', 'Se ha modificado', 'Registro');
		}
	});
}

function elegirImagen(){
	$('#itFotoUpd').trigger('click'); 
}

function saveFontSize(){
	fontSize = $("#contentPrueba").css('font-size');
	
	$.ajax({
		data : { pixel  : fontSize},  
		url  : 'c_perfil/cambiarFontSize', 
		async: false,
		type : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			mostrarNotificacion('success', 'Se ha modificado', 'Registro');
		}else{
			mostrarNotificacion('error', 'Contacte con la persona a cargo', 'Error');
		}
	});
} 

function saveInteresesHobby(){
	var hobby = $('#hobby').val();
	console.log(hobby);
	$.ajax({
		data  : {hobby : hobby},
		url   : 'c_perfil/saveIntereses',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 1){
			mostrarNotificacion('warning',data.cabecera);
		} else{
			mostrarNotificacion('success','Se actualiz&oacute;');
		}
	});
}

//GUARDAR INFO
function cambioInfoContacto(){
	$("#btnGuardar1").prop('disabled', false);
}

function guardarInformacionContacto(){
	var telefono   = $("#telefono").val();
	var correoIns  = $("#emailInst").val();
	var correoPers = $("#emailPersonal").val();
	
	$.ajax({
		data  : {telefono   : telefono,
			     correopers : correoPers, 
			     correoins  : correoIns},
		url   : 'C_perfil/guardarInformacionContacto',
		type  : 'POST',
		async : false
	})
	.done(function(data){
		data = JSON.parse(data);
		if(data.error == 0){
			$("#btnGuardar1").prop('disabled', true);
		}
		
		msj('success', data.msj, null);
	});
}