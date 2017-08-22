/*CONSTANTES*/
var grafico = 1;
var ruta_filter_fab	= 	'<div class="img-search">'+
						'	<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/filter_fab.png">'+
						'	<p><strong>&#161;Hey!</strong></p>'+
						'	<p>Primero debemos filtrar</p>'+
						'	<p>para poder visualizar los</p>'+
						'	<p>reportes gr&aacute;ficos</p>'+
						'</div>';

var ruta_not_data_found	= 	'<div class="img-search">'+
							'	<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/not_data_found.png">'+
							'	<p><strong>&#161;Ups!</strong></p>'+
							'	<p>No hay informacion disponible</p>'+
							'</div>';

var ruta_not_data_fab	= 	'<div class="img-search">'+
							'	<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/not_filter_fab.png">'+
							'	<p><strong>&#161;Ups!</strong></p>'+
                            '	<p>No se encontraron</p>'+
                            '	<p>resultados.</p>'+
							'</div>';
/*FIN CONSTANTES*/
function tabAction(nGrafico){
	grafico = nGrafico;
	$('#imgAyuda').attr("src",window.location.origin+"/smiledu/public/modulos/senc/img/graficos_ayuda/graf_"+((nGrafico == 6) ? 5 : nGrafico)+".PNG");
	if(nGrafico == 1){
		$("#textoAyuda").html(decodeURIComponent(escape("Este gráfico le mostrará el acumulado de todas las respuestas de las preguntas que usted seleccione o por la sede que usted seleccione")));
	} else if(nGrafico == 2){
		$("#textoAyuda").html(decodeURIComponent(escape("Este gráfico le mostrará el nivel de satisfacción o insatisfacción de las preguntas que usted seleccione, cada pregunta es independiente" +
				"                                          <br/><br/>" +
				"                                        <p class='text-left'>-(Satisfacción = Muy Satisfecho + Satisfecho)" +
				"											<br/>" +
				"                                         -(Insatisfacción = Muy Insatisfecho + Insatisfecho)</p>")));
	} else if(nGrafico == 3){
		$("#textoAyuda").html(decodeURIComponent(escape("Este gráfico le mostrará el porcentaje de personas que hayan escogido las propuestas de mejora que ustede seleccione por encuesta")));
	} else if(nGrafico == 4){
		$("#textoAyuda").html(decodeURIComponent(escape("Este apartado le mostrará un reporte de todos los gráficos de la encuesta que usted seleccione")));
	} else if(nGrafico == 5){
		$("#textoAyuda").html(decodeURIComponent(escape("Este apartado le mostrará el ranking de todas las preguntas por encuesta")));
	} else if(nGrafico == 6){
		$("#textoAyuda").html(decodeURIComponent(escape("Este apartado le mostrará el resultado de las encuestas de tutoría")));
	}
}

tabAction(grafico);

function init(){
	initButtonLoad('btnMFGE', 'btnMFGCP', 'btnMFPM', 'btnMFGEP', 'btnFTP', 'btnMFT');
}

init();

function abrirModalFiltro(){
	if(grafico == 1){
		abrirCerrarModal("modalFiltroGraficoEncuesta");
	} else if(grafico == 2){
		abrirCerrarModal("modalFiltroGraficoCompararPreguntas");
	} else if(grafico == 3){
		abrirCerrarModal("modalFiltroGraficoPropuestaMejora");
	} else if(grafico == 4){
		abrirCerrarModal("modalFiltroGraficoEncuestaPreguntas");
	} else if(grafico == 5){
		abrirCerrarModal('modalFiltroTopPreguntas');
	} else if(grafico == 6){
		abrirCerrarModal('modalFiltroTutoria');
	}
}

function changeTypeGrafico(id, type) {
	var chart = $('#' + id).highcharts();
	if (chart != undefined) {
		for (var i = 0; i < chart.series.length; i++) {
			chart.series[i].update({
				type : type
			});
		}
	}
}