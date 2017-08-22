var countScroll = 1;
function init(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.selectButton').selectpicker('mobile');
	} else {
		$('.selectButton').selectpicker();
	}
	
	$(":input").inputmask();
}

function abrirModalNuevoColaborador(){
	setearInput("apellidoPatColaboradorCrear", null);
	setearInput("apellidoMatColaboradorCrear", null);
	setearInput("nombreColaboradorCrear", null);
	setearInput("fecNaciColaboradorCrear", null);
	setearInput("numeroColaboradorCrear", null);
	setearInput("correoColaboradorCrear", null);
	
	setearCombo("selectSexoColaboradorCrear", null);
	setearCombo("selectTipoDocColaboradorCrear", null);
	abrirCerrarModal("modalAgregarColaborador");
}

function crearColaborador(){
	apePaterno = $("#apellidoPatColaboradorCrear").val();
	apeMaterno = $("#apellidoMatColaboradorCrear").val();
	nombres    = $("#nombreColaboradorCrear").val();
	fecNaci    = $("#fecNaciColaboradorCrear").val();
	sexo 	   = $("#selectSexoColaboradorCrear").val();
	tipoDoc    = $("#selectTipoDocColaboradorCrear").val();
	numeroDoc  = $("#numeroColaboradorCrear").val();
	correo 	   = $("#correoColaboradorCrear").val();
	telefono   = $("#telefonoColaboradorCrear").val();
	
	$.ajax({
		type    : 'POST',
		'url'   : 'c_colaboradores/crearColaborador',
		data    : {apepaterno : apePaterno,
			       apematerno : apeMaterno,
			       nombres    : nombres,
			       fecnaci    : fecNaci,
			       sexo       : sexo,
			       tipoDoc    : tipoDoc,
			       numeroDoc  : numeroDoc,
			       correo	  : correo,
			       telefono   : telefono},
		'async' : false
	}).done(function(data){
		try {
			data = JSON.parse(data);
			if(data.error == 0){
				
			}else{
				
			}	
		} catch(err) {
			location.reload();
		}
	});
}

function onScrollEvent(element){
	if($(element).scrollTop() + $(element).innerHeight()+1>=$(element)[0].scrollHeight){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_colaboradores/onScrollGetColaboradores',
				data    : {countScroll : countScroll},
				'async' : false
			}).done(function(data){
				try {
					data = JSON.parse(data);
					$("#cont_colaboradores").append(data.colaboradores);
		  			componentHandler.upgradeAllRegistered();
					countScroll = countScroll + 1;
				} catch(err) {
					location.reload();
				}
			});
		});
	}
}

function buscarColaborador(){
	texto = $("#searchMagic").val();
	if(texto.length >= 3){
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_colaboradores/buscarColaborador',
				data    : {texto : texto},
				'async' : false
			}).done(function(data){
				try {
					data = JSON.parse(data);
					$("#cont_colaboradores").html(data.colaboradores);
		  			componentHandler.upgradeAllRegistered();
				} catch(err) {
					location.reload();
				}
			});
		});
	}
}
var ses_persona = null;
function verRolesPorUsuario(persona){
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_colaboradores/verRolesPorUsuario',
			data    : {persona : persona},
			'async' : false
		}).done(function(data){
			try {
				data = JSON.parse(data);
				$("#cont_tb_RolesPersona").html(data.tabla);
				$("#tbRolesPersona").bootstrapTable({});
				componentHandler.upgradeAllRegistered();
				tableEventsUpgradeMdlComponentsMDL("tbRolesPersona");
				ses_persona = persona;
	  			modal("modalAsignarRoles");
			} catch(err) {
				location.reload();
			}
		});
	});
}

function guardarRolesPersona(){
	Pace.restart();
	Pace.track(function() {
		var json = {};
		var roles = [];
		json.rol = roles;	
		
		$('.cb_rolesPersona').each(function(i, obj) {
			if(isChecked(this)){
				rol = $(this).attr("attr-rol")
				var rol    = {"rol" : rol};			
				json.rol.push(rol);
			}
		});
		
		var jsonStringPersonaRol = JSON.stringify(json);
		
		$.ajax({
			type    : 'POST',
			'url'   : 'c_colaboradores/editarRolesPersona',
			data    : {json    : jsonStringPersonaRol,
				       persona : ses_persona},
			'async' : false
		}).done(function(data){
			/*try {
				data = JSON.parse(data);
				//mensaje
			} catch(err) {
				location.reload();
			}*/
		});
	});
}