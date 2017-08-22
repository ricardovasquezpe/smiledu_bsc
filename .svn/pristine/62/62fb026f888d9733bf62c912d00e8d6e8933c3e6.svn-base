	var grafico     = 1;
	var arrayCombos = [];
	tabAction(grafico);
	
	function init(){
		initButtonLoad('btnMFGE', 'btnMFGCP', 'btnMFPM', 'btnMFGEP', 'btnFTP', 'btnMFT');
	}
	
	init();
	
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

	function getEncuestasByTipo4() {
		addLoadingButton('btnMFGEP');
		var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
		if(tipo_encuesta.length != 0 || tipo_encuesta.length == 0){
			setMultiCombo('selectGradoGrafico4', null);
			setMultiCombo('selectAulaGrafico4', null);
			setMultiCombo('selectAreaGrafico4', null);
			setMultiCombo('selectSedeGrafico4', null);
			setMultiCombo('selectNivelGrafico4', null);
			$("#contNivelesByTipoEnc").css('display','none');
		}
		if(!tipo_encuesta) {
			setMultiCombo('selectEncuestaGrafico4', null);
			setMultiCombo('selectPreguntaGrafico4', null);
			stopLoadingButton('btnMFGEP');
			return;
		} else {
			Pace.restart();
			Pace.track(function() {
				$.ajax({
					data  : { tipo_encuesta : tipo_encuesta },
					url   : 'C_senc_graficos/getEncuestaByTipoEncuesta',
					type  : 'POST',
					async : true
				}).done(function(data) {
					data = JSON.parse(data);
					setMultiCombo('selectEncuestaGrafico4', data.optEnc);
					setMultiCombo('selectPreguntaGrafico4', null);
					stopLoadingButton('btnMFGEP');
				});
			});
		}
	}

	function getPreguntasByEncuesta4() {
		addLoadingButton('btnMFGEP');
		Pace.restart();
		Pace.track(function() {
			var encuesta = $('#selectEncuestaGrafico4').val();
			var tipo_encuesta = $('#selectTipoEncuestaGrafico4 option:selected').val();
			if(tipo_encuesta.length != 0 || tipo_encuesta.length == 0){
				setMultiCombo('selectGradoGrafico4', null);
				setMultiCombo('selectAulaGrafico4' , null);
				setMultiCombo('selectAreaGrafico4' , null);
				setMultiCombo('selectSedeGrafico4' , null);
				setMultiCombo('selectNivelGrafico4', null);
				$("#contNivelesByTipoEnc").css('display','none');
			}
			if(encuesta == null) {
				$('#container_grafico_4').html(ruta_not_data_fab);
				setCombo('selectPreguntaGrafico4', null, ' Pregunta');
				setCombo('selectSedeGrafico4', null, ' Sede');
				$('#cont_selectTipoEncuestadoGrafico4').css('display','none');
				stopLoadingButton('btnMFGEP');
				return;
			}
			$.ajax({
				data  : { encuesta      : encuesta,
					      tipo_encuesta : tipo_encuesta },
				url   : 'C_senc_graficos/getPreguntasByEncuesta',
				type  : 'POST',
				async : true
			}).done(function(data) {
				data = JSON.parse(data);
				$('#container_grafico_4').html(data.ruta_not_data_found);
				setMultiCombo('selectPreguntaGrafico4',data.optPreg);
				$("#container_grafico_4").empty();
				if(data.optEncTipo) {
					$("#cont_selectTipoEncuestadoGrafico4").css('display','block');
					setCombo('selectTipoEncuestadoGrafico4', data.optEncTipo, 'tipo de encuestado');
				} else {
					$('#contNivelesByTipoEnc').html(data.optNiveles);
					$("#contNivelesByTipoEnc").css('display','block');
					$('#selectSedeGrafico4').selectpicker({noneSelectedText: 'Seleccione Sede'});
		    		$('#selectNivelGrafico4').selectpicker({noneSelectedText: 'Seleccione Nivel'});
		    		$('#selectGradoGrafico4').selectpicker({noneSelectedText: 'Seleccione Grado'});
		    		$('#selectAulaGrafico4').selectpicker({noneSelectedText: 'Seleccione Aula'});
		    		$('#selectAreaGrafico4').selectpicker({noneSelectedText: 'Seleccione &Aacute;rea'});
				}
				stopLoadingButton('btnMFGEP');
			});
		});
	}

	function getGraficoEncuestaPregunta4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		Pace.track(function() {
			array_preguntas = [];
			var pregunta  = $('#selectPreguntaGrafico4').val();
			if(pregunta == 0){
				setMultiCombo('selectGradoGrafico4', null);
				setMultiCombo('selectAulaGrafico4' , null);
				setMultiCombo('selectAreaGrafico4' , null);
				setMultiCombo('selectSedeGrafico4' , null);
				setMultiCombo('selectNivelGrafico4', null);
				$("#contNivelesByTipoEnc").css('display','none');
			}
			if(pregunta != null){
				if(pregunta.length == 1 && Object.keys(array_preguntas).length == 0){
			        $("#container_grafico_4").empty();
				}
				if($.selectTodo == true && estadoActual == 0){
					$("#container_grafico_4").empty();
			        $.selectTodo = undefined;
			        estadoActual = 1;
				}
				if(pregunta.length > array_preguntas.length){
					var encuesta  = $('#selectEncuestaGrafico4').val();
					var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
					var pregunta  = $('#selectPreguntaGrafico4').val();
					$.ajax({
						data  : {
								 pregunta  : pregunta.diff(array_preguntas),
							     encuesta  : encuesta,
							     tipo_encu : tipo_encu},
						url   : 'C_senc_graficos/getGraficoEncuestaByPregunta',
						type  : 'POST',
						async : true
					})
					.done(function(data){
						data = JSON.parse(data);
						if(data.preguntas){
							if(Object.keys(data.preguntas).length == 0){
								('#container_grafico_4').html(data.ruta_not_data_fab);
								setMultiCombo('selectSedeGrafico4', null);
							}
							
						}
						array_preguntas = pregunta;
					});
				}else{
					$('div[data-pregunta="' + array_preguntas.diff(pregunta)[0] + '"]').remove();
					array_preguntas = pregunta;
				}
				stopLoadingButton('btnMFGEP');
			}else{
				estadoActual = 0;
				getPreguntasByEncuesta4();
				stopLoadingButton('btnMFGEP');
			}
		});
	}

	Array.prototype.diff = function(a) {
	    return this.filter(function(i) {return a.indexOf(i) < 0;});
	};

	function getGraficoTipoEncuestado4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		Pace.track(function() {
			var encuesta    = $('#selectEncuestaGrafico4').val();
			var tencuestado = $('#selectTipoEncuestadoGrafico4 option:selected').val();
			var pregunta    = $('#selectPreguntaGrafico4').val();
			if(tencuestado.length == 0){
				setMultiCombo('selectGradoGrafico4', null);
				setMultiCombo('selectAulaGrafico4' , null);
				setMultiCombo('selectAreaGrafico4' , null);
				setMultiCombo('selectSedeGrafico4' , null);
				setMultiCombo('selectNivelGrafico4', null);
				$("#contNivelesByTipoEnc").css('display','none');
			}
			if(tencuestado.length != 0 && encuesta != null){
				$.ajax({
					data  : {tencuestado : tencuestado,
						     encuesta    : encuesta,
						     pregunta    : pregunta	},
					url   : 'C_senc_graficos/getGraficobyTipoEncuestado',
					type  : 'POST',
				})
				.done(function(data){
					data = JSON.parse(data);
					$('#contNivelesByTipoEnc').html(data.combos);
					if(data.combos != null){
						$('#contNivelesByTipoEnc').css('display','block');
						$('#selectSedeGrafico4').selectpicker({noneSelectedText : 'Seleccione Sede'});
			    		$('#selectNivelGrafico4').selectpicker({noneSelectedText: 'Seleccione Nivel'});
			    		$('#selectGradoGrafico4').selectpicker({noneSelectedText: 'Seleccione Grado'});
			    		$('#selectAulaGrafico4').selectpicker({noneSelectedText: 'Seleccione Aula'});
			    		$('#selectAreaGrafico4').selectpicker({noneSelectedText : 'Seleccione &Aacute;rea'});
					}
					$("#container_grafico_4").empty();
					stopLoadingButton('btnMFGEP');
				});
			}else{
				$("#container_grafico_4").empty();
				array_preguntas = [];
				getGraficoEncuestaPregunta4();
				stopLoadingButton('btnMFGEP');
			}
		});
	}

	function getNivelesBySedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		Pace.track(function() {
			var sedes     = $('#selectSedeGrafico4').val();
			var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
			var pregunta  = $('#selectPreguntaGrafico4').val();
			var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
			if(pregunta == 0){
				setMultiCombo('selectGradoGrafico4', null);
				setMultiCombo('selectAulaGrafico4' , null);
				setMultiCombo('selectAreaGrafico4' , null);
				setMultiCombo('selectSedeGrafico4' , null);
				setMultiCombo('selectNivelGrafico4', null);
			}
			
			$.ajax({
				data  : {sedes     : sedes,
					     pregunta  : pregunta,
					     encuesta  : encuesta,
					     tipo_encu : tipo_encu},
				url   : 'C_senc_graficos/getNivelesBySedeGrafico',
				type  : 'POST'
			}).done(function(data) {
				data = JSON.parse(data);
				setMultiCombo('selectGradoGrafico4', null);
				setMultiCombo('selectAulaGrafico4', null);
				setMultiCombo('selectAreaGrafico4', null);
				setMultiCombo('selectNivelGrafico4', data.comboNiveles);
				stopLoadingButton('btnMFGEP');
			});
		});
	}

	function getAreasByNivelSedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		Pace.track(function() {
			var sedes = $('#selectSedeGrafico4').val();
			var nivel = $('#selectNivelGrafico4').val();
			var encuesta = $('#selectEncuestaGrafico4 option:selected').val();
			var pregunta  = $('#selectPreguntaGrafico4').val();
			var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
			$.ajax({
				data  : {sedes     : sedes,
						 pregunta  : pregunta,
					     encuesta  : encuesta,
					     nivel     : nivel,
					     tipo_encu : tipo_encu},
				url   : 'C_senc_graficos/getAreasGraficoByNivel',
				type  : 'POST',
				//async : true
			})
			.done(function(data){
				data = JSON.parse(data);
			    setMultiCombo('selectAreaGrafico4', data.comboAreas);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getGradosByNivelSedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var niveles   = $('#selectNivelGrafico4').val();
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     niveles   : niveles},
				url   : 'C_senc_graficos/getGraficoTutoriaBySedeNivel',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				setMultiCombo('selectGradoGrafico4' , data.comboGrados);
				setMultiCombo('selectAulaGrafico4'  , null);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getAulasByNivelGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var niveles   = $('#selectNivelGrafico4').val();
		var grados    = $('#selectGradoGrafico4').val();
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     niveles   : niveles,
					     grados    : grados},
				url   : 'C_senc_graficos/getGraficoTutoriaBySedeNivelGrado',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				setMultiCombo('selectAulaGrafico4', data.comboAulas);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getGraficoByAreaNivelSedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var niveles   = $('#selectNivelGrafico4').val();
		var area      = $('#selectAreaGrafico4').val();
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     niveles   : niveles,
					     area      : area},
				url   : 'C_senc_graficos/getGraficoTutoriaBySedeNivelGrado',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getGraficoByAula4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestadoGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var niveles   = $('#selectNivelGrafico4').val();
		var grado     = $('#selectGradoGrafico4').val();
		var aula      = $('#selectAulaGrafico4').val();
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     niveles   : niveles,
					     grado     : grado,
					     aula      : aula},
				url   : 'C_senc_graficos/getGraficoTutoriaBySedeNivelGrado',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getAreasBySedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var pregunta  = $('#selectPreguntaGrafico4').val();
		if(pregunta == 0){
			setMultiCombo('selectGradoGrafico4', null);
			setMultiCombo('selectAulaGrafico4' , null);
			setMultiCombo('selectAreaGrafico4' , null);
			setMultiCombo('selectSedeGrafico4', null);
			setMultiCombo('selectNivelGrafico4', null);
		}
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     pregunta  : pregunta},
				url   : 'C_senc_graficos/getAreasBySedeGrafico',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				setMultiCombo('selectAreaGrafico4', data.comboAreas);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	
	function getGraficoByAreaSedeGrafico4(){
		addLoadingButton('btnMFGEP');
		Pace.restart();
		var tipo_encu = $('#selectTipoEncuestaGrafico4 option:selected').val();
		var encuesta  = $('#selectEncuestaGrafico4 option:selected').val();
		var sedes     = $('#selectSedeGrafico4').val();
		var pregunta  = $('#selectPreguntaGrafico4').val();
		var area      = $('#selectAreaGrafico4').val();
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {tipo_encu : tipo_encu,
					     encuesta  : encuesta,
					     sedes     : sedes,
					     pregunta  : pregunta,
					     area 	   : area},
				url   : 'C_senc_graficos/getAreasBySedeGrafico',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				stopLoadingButton('btnMFGEP');
			});
		});
	}
	