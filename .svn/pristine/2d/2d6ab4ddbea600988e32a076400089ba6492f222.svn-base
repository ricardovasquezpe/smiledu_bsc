function init() {
	$("#loginForm input, select, textarea").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			logear();
		}
	});
	$("#loginFormPadres input, select, textarea").keypress(function(event) {
		if (event.which == 13) {
			event.preventDefault();
			loginPadres();
		}
	});
	resizeContent();
}

var errorLogin = 0;

function logear() {
	user = $('#usuario').val();
	pass = $('#password').val();
	check = '0';
	if ($('#check').is(":checked")) {
		check = '1';
	}
	$.ajax({
		data : {
			user : user,
			pass : pass,
			check : check
		},
		url : 'logear',
		async : false,
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.err == 0) {
			location.href = data.url;
		} else if (data.err == 1) {
			$("#cont_error_google").css('display','none');
			$("#cont_error").css('display','block');
			$("#msj_error").text("Ingresa un usuario y/o contrase\u00f1a correcta");
			if (data.sw == 1) {
				$("#cont_usuario").addClass("is-invalid");
				$("#cont_usuario").focus();
			} else {
				$("#cont_usuario").addClass("is-invalid");
				$("#cont_clave").addClass("is-invalid");
				$("#cont_clave").focus();
			}
			$("#cont_clave").addClass("is-invalid");
			$('#cont_clave').val('');
			$('#error').html(data.error);
			if (errorLogin < 2) {
				errorLogin++;
			} else {
				$('.toggle').toggleClass('changed');
			}
		}
	});
}

function loginPadres() {
	var user = $('#usuario').val();
	var pass = $('#password').val();
	var sede = getComboVal('cmbSede');
	if(!sede || !user || !pass ) {
		return;
	}
	var check = '0';
	if ($('#check').is(":checked")) {
		check = '1';
	}
	$.ajax({
		data : {
			user  : user,
			pass  : pass,
			sede  : sede,
			check : check
		},
		url : 'padres/logearPadres',
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.err == 0) {
			location.href = data.url;
		} else if (data.err == 1) {
			$("#cont_error_google").css('display','none');
			$("#cont_error").css('display','block');
			$("#msj_error").text("Ingresa un usuario y/o contrase\u00f1a correcta");
			if (data.sw == 1) {
				$("#cont_usuario").addClass("is-invalid");
				$("#cont_usuario").focus();
			} else {
				$("#cont_usuario").addClass("is-invalid");
				$("#cont_clave").addClass("is-invalid");
				$("#cont_clave").focus();
			}
			$("#cont_clave").addClass("is-invalid");
			$('#cont_clave').val('');
			$('#error').html(data.error);
			if (errorLogin < 2) {
				errorLogin++;
			} else {
				$('.toggle').toggleClass('changed');
			}
		}
	});
}

function cerrarOjos() {
	$("#logo_principal").attr("src",
			"public/files/images/logo_principal_cerrado.svg");
}

function abrirOjos() {
	$("#logo_principal").attr("src", "public/files/images/logo_principal.svg");
}

function openModalCorreo() {
	$("#correo").val("");
	$("#correo").removeClass("dirty");
}

function openModalIngreso() {
	abrirCerrarModal("modalIngreso");
}

$('#modalIngreso').on('shown.bs.modal', function() {
	$('#usuario').focus();
})

function enviarCorreo() {
	var correoUsuario = $("#correo").val();
	if(!correoUsuario) {
		return;
	}
	$.ajax({
		data : { correoUsuario : correoUsuario },
		url : 'restablecer',
		type : 'POST'
	}).done(function(data) {
		data = JSON.parse(data);
		if (data.error == 1) {
			$("#cont_error_clave").css('display','block');
		} else {
			msj('success', data.msj);
			$('.close').click();
			$("#correo").val(null);
		}
	});
}

$('#user').click(function() {
	$('#user').removeClass('has-error');
});

$('#passw').click(function() {
	$('#passw').removeClass('has-error');
});

function logRed(btn) {
	var url = btn.data('href_url');
	window.location.href = url;
}

$(window).resize(function() {
    resizeContent();
});

function resizeContent(){
	var body				= $('body');
	var getBodyHeight 		= body.height();
	var getBodyWidth 		= body.width();
	var container			= $('.header-schoowl ~ .container');
	var getContainerHeight 	= container.height();
	var getContainerWidth 	= container.width();
	var topContainer		= (getBodyHeight - getContainerHeight) / 2;
	var leftContainer		= (getBodyWidth - getContainerWidth) / 2;
	
	if ( topContainer <= 0 ){
		topContainer = 10;
	}
	if ( leftContainer <= 0 ){
		leftContainer = 0;
	}
	container.css({
		top 	: topContainer,
		left	: leftContainer
	});
}