function guardarObservacion(){
	Pace.restart();
	Pace.track(function() {
		observacion = $("#observacionResp").val();
		$.ajax({
			data : { observacion : observacion},
			url  : 'c_confirmar_evento/guardarObservacion',
			type : 'POST'
		})
		.done(function(data) {
			try {
				data = JSON.parse(data);
			    msj("success", data.msj, null);
			} catch(err) {
				location.reload();
			}
		});
	});
}