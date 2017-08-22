function buscarGeneral(){
	texto = $("#searchMagic").val();
	if(texto.length >= 3){	
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_main/buscarGeneral',
				data    : {texto : texto},
				'async' : false
			}).done(function(data){
				data = JSON.parse(data);
				if($.trim(data.eventos) == ""){
					$("#cont_search_eventos").html(null);
					$("#cont_imagen_magic").css("display", "none");
					$("#cont_search_not_found").css("display", "block");
				}else{
					$("#cont_imagen_magic").css("display", "none");
					$("#cont_search_not_found").css("display", "none");
					$("#cont_search_eventos").html(data.eventos);
				}
			  });
		});
	}else{
		$("#cont_search_eventos").html(null);
		$("#cont_imagen_magic").css("display", "none");
		$("#cont_search_not_found").css("display", "block");		
	}
	
}
function irDetalleEvento(dataEvento){
	$.ajax({	
		type    : 'POST',
		'url'   : 'c_main/detalleEvento',
		data    : {idevento : dataEvento},
		'async' : false
	}).done(function(data){
		window.location.href = 'c_detalle_evento';
	});
}
function verEventosEnlazados(dataEvento){
	if(dataEvento != null){
		$.ajax({	
			type    : 'POST',
			'url'   : 'c_main/eventosEnlazados',
			data    : {idevento : dataEvento},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_eventos_enlazados").html(data.eventos);
			if(data.count > 0){
				$("#cont_eventos_enlazados").html(data.eventos);
				$("#cont_teacher_empty8").css("display", "none");

			}else{
				$("#cont_teacher_empty8").css("display", "block");
			}
			modal("modalEventosEnlazados");
		});
	}
}
function verColaboradoresEvento(dataEvento){
	if(dataEvento != null) {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_main/colaboradoresEvento',
			data    : {idevento : dataEvento},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_colaboradores_asistieron").html(data.evento);
			if(data.count > 0){
				$("#cont_colaboradores_asistieron").html(data.evento);
				$("#cont_teacher_empty8").css("display", "none");
			}else{
				$("#cont_teacher_empty8").css("display", "block");
			}
			modal("modalDetalleColaboradores");
		});
	} 
}
function verInvitadosEvento(dataEvento){
	if(dataEvento != null) {
		$.ajax({
			type    : 'POST',
			'url'   : 'c_main/InvitadosEvento',
			data    : {idevento : dataEvento},
			'async' : false
		}).done(function(data){
			data = JSON.parse(data);
			$("#cont_invitados_asistieron").html(data.evento);
			if(data.count > 0){
				$("#cont_invitados_asistieron").html(data.evento);
				$("#tbInvitados").bootstrapTable({ }); 
				$("#cont_teacher_empty8").css("display", "none");
			}else{
				$("#tbInvitados").bootstrapTable({ }); 
				$("#cont_teacher_empty8").css("display", "block");
			}
			modal("modalDetalleInvitados");
		});
	} 
}