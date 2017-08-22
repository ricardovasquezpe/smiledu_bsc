function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	}
}

function getNotasBimestre() {
	var idCurso    = $('#cmbCursos option:selected').val();
	var idBimestre = $('#cmbBimestres option:selected').val();
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			type  : 'POST',
			'url' : 'c_detalle_alumno/getCursoNotas',
			data  : { idCurso    : idCurso,
					  idBimestre : idBimestre }
		}).done(function(data) {
			data = JSON.parse(data);
			$('#contTbNotas').html(data.tablaCursoNotas);
			$('#tbNotasCursos').bootstrapTable({ });
		}); 
	});
	
}