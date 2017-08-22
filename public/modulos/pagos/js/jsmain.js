var ruta_not_data_found = '<div class="img-search">' +
	                          '<img src="'+window.location.origin+'/smiledu/public/general/img/smiledu_faces/not_data_found.png">'+
						      '<p>No se encontraron datos.</p>'+
						  '</div>';
var countScroll = 1;

function init() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
	    $('.pickerButn').selectpicker('mobile');
	} else {
		$('.pickerButn').selectpicker();
	} 
	initButtonLoad('botonFM');
}

$("#cont_inputtext_busqueda input, select, textarea").keypress(function(event) {
	if (event.which == 13) {
		event.preventDefault();
		getAllAlumnosByFiltro();
	}
});

function getAllAlumnosByFiltro(){
	count_scroll = 1;
	var valorGeneral = $('#searchMagic').val().trim();

	var year        = $('#selectYear option:selected').val();
	var idSede      = $('#selectSedeByYear option:selected').val();
	var idGrado     = $('#selectGradoByYear option:selected').val();
	var idNivel     = $('#selectNivelByYear option:selected').val();
	var idAula      = $('#selectAulaByYear option:selected').val();

	var yearText    = (year  == null || year  == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText    = (idSede  == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	var nivelText   = (idNivel == null || idNivel == "") ? " " :  $('#selectNivelByYear option:selected').text();
	var gradoText   = (idGrado == null || idGrado == "") ? " " :  $('#selectGradoByYear option:selected').text();
	var aulaText    = (idAula  == null || idAula  == "") ? " " :  $('#selectAulaByYear option:selected').text();
	
	if(((searchMagic != null && searchMagic.length < 1) || (valorGeneral == "" || valorGeneral == null)) && (idSede == null || idSede == "")){
		$('#contAulas').html(null);
		$('#contAlumnos').html(null);
		$('#cont_imagen_magic').css('display', 'block');
	} else{
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				data  : {valorGeneral  : valorGeneral,
						 year          : year,
		        	     idGrado       : idGrado,
			    		 idNivel 	   : idNivel,
			    	     idSede  	   : idSede,
			    	     idAula  	   : idAula,
						 count 		   : count_scroll},
				url   : 'c_main/busquedaGeneral',
				type  : 'POST',
				async : true
			})
			.done(function(data){
				data = JSON.parse(data);
				$('#cont_search_empty').css('display','none');
				$('alumnosTitulo').removeAttr('style');	
				$("#cont_imagen_magic").css("display", "none");
				if(data.cardsAlumnos != ""){
					$('#contAlumnos').html(data.cardsAlumnos);
					$('#alumnosTitulo').css('display','block');
				} else{
					$('#contAlumnos').html(null);
					$('#alumnosTitulo').css('display','none');
				}
				if(data.cardsAulas != ""){
					$('#contAulas').html(data.cardsAulas);
					$('#aulasTitulo').css('display','block');
					$('#cont_search_empty').css('display','none');
				} else {
					$('#contAulas').html(null);
					$('#aulasTitulo').css('display','none');
				}
				if(data.cardsAlumnos == "" && data.cardsAulas == ""){
					$('#contAulas').html(null);
					$('#contAlumnos').html(null);
					$('#alumnosTitulo').css('display','none');
					$('#aulasTitulo').css('display','none');
					$('#cont_search_empty').css('display','block');
				}
				componentHandler.upgradeAllRegistered();
				scroll = 1;
			});
		});
	}
}

function goToDetalleAlumno(url){
	window.location = url;
}

function verGraficoAlumno(persona){
	$.ajax({
		data  : {persona : persona},
		url   : 'c_main/getGraficoByAlumno',
		async : true,
		type  : 'POST'
	})
	.done(function(data){
		data = JSON.parse(data);
		initChartAlumno(JSON.parse(data.arr));
		$('#titleDetaAlumno').text(data.nombre);
		modal('detalleAlumno');
	});
}

function initChartAlumno(data){
	arrayJson = JSON.parse(data.arrayJson);
	arrayCate = JSON.parse(data.arrayCate);
	if(arrayCate.length == 0){
		var chart = $('#contGraficoAlumno').highcharts();
	    if(chart != undefined){
	    	chart.destroy();
	    }
	    $('#contGraficoAlumno').html(ruta_not_data_found);
	} else{
		var yourLabels = ["Very Low", "Low", "Medium", "High", "Very High"];
		var options = {
			    chart : {
		            zoomType: 'xy'
			    },
		        title: {
		            text: ' '
		        },
		        exporting : {
		        	enabled: false
		        },
		        xAxis: {
		            categories: arrayCate
		        },
		        yAxis: {
		            allowDecimals: false,
		            min: 0,
		            title: {
		                text: ' '
		            }
		        },plotOptions: {
		            line: {
		                dataLabels: {
		                    enabled: true,
		                    format: 'S/.{point.y:.2f}',
		                }
		            }
		        },
		        tooltip: {
		            formatter: function () {
		                return '<b>'+ this.point.category + '</b><br/>' +
		                    'S/. ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
		            }
		        },
		        series : [{
		        	data : arrayJson,
		        	name : 'Pagos'
		        }]
		    };
		$('#contGraficoAlumno').highcharts(options);
		var chart = $('#contGraficoAlumno').highcharts();
	    setTimeout(function(){
	    	chart.reflow();
	    },200);
	}
}

function modalDetalleAulaCompromiso(aula){
	$.ajax({
		data  : { aula : aula},
		url   : 'c_main/getDetaAula',
		type  : 'POST',
		async : true
	})
	.done(function(data){
		data = JSON.parse(data);
		$('#titleDetaAula').text(data.aula);
		$('#contTableAula').html(data.table);
		modal('detalleAula');
		$(document).ready(function(){
    	    $('[data-toggle="tooltip"]').tooltip();
        });
	});
}

function goToPagosAlumno(idPersona){
	current_tab = 'tab-1';
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : 'c_movimientos/goToDetallePagoPersona',
	        data: {idPersona   : idPersona,
			       current_tab : current_tab},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			window.location.href = data.url;
		});
	});
}

function onScrollEvent(element){
	var valorGeneral = $('#searchMagic').val().trim();
	var year         = $('#selectYear option:selected').val();
	var idSede       = $('#selectSedeByYear option:selected').val();
	var idGrado      = $('#selectGradoByYear option:selected').val();
	var idNivel      = $('#selectNivelByYear option:selected').val();
	var idAula       = $('#selectAulaByYear option:selected').val();

	if($(element).scrollTop() + $(element).innerHeight()+1>=$(element)[0].scrollHeight){
		$("#loading_cards").css("display","block");
		Pace.restart();
		Pace.track(function() {
			$.ajax({
				type    : 'POST',
				'url'   : 'c_main/onScrollGetCards',
				data    : {valorGeneral  : valorGeneral,
	        	           year          : year,
	        	           idGrado       : idGrado,
		    		       idNivel       : idNivel,
		    	           idSede  	     : idSede,
		    	           idAula        : idAula,
					       countScroll   : countScroll},
				'async' : true
			}).done(function(data){
				try {
					data = JSON.parse(data);
					$("#contAlumnos").append(data.cardsAlumnos);
					$("#contAulas").append(data.cardsAulas);
		  			componentHandler.upgradeAllRegistered();
					countScroll = countScroll + 1;
					$("#loading_cards").css("display","none");
				} catch(err) {
					location.reload();
				}
			});
		});
	}
}

function getSedesByYear() {
//	addLoadingButton('');
	count_scroll    = 1;
	var valorGeneral = $('#searchMagic').val().trim();
	
	var year     = $('#selectYear option:selected').val();
	var idSede   = $('#selectSedeByYear option:selected').val(); 
	var yearText = (year  == null || year  == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText = (idSede == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	addLoadingButton('botonFM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/comboSedesByYear",
	        data: {  valorGeneral  : valorGeneral,
	        	     year          : year,
	        	     idSede        : idSede,
					 count 		   : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.cardsAlumnos == null && data.cardsAulas == null) {
				$('#cont_imagen_magic').css('display','block');
				$('#contAulas').html(null);
				$('#contAlumnos').html(null);
				$('#aulasTitulo').html(null);
				$('#alumnosTitulo').html(null);
			}
			if(data.error == 0) {
				if(year == null || year == "") {
					$('.breadcrumb').css('display','none');
					$('main').removeAttr('onscroll');
				}else {
					$('main').attr('onscroll', 'onScrollEvent(this)');
				}
				$('#cont_search_empty').css('display','none');
			    setCombo('selectSedeByYear', data.optSede, 'Sede',true);
			    setCombo('selectNivelByYear', data.optNivel, 'Nivel',null);
			    setCombo('selectGradoByYear', null, 'Grado',null);
			    setCombo('selectAulaByYear', null, 'Aula',null);
			    $('#laelYear').html(yearText);
			    $('.breadcrumb').find('li').removeClass('active');
//			    $('#filtroMain').removeAttr('style');
			    $('#laelyear').addClass('active');
			    $('#laelSede').css('display', 'none');
			    $('#laelNivel').css('display','none');
			    $('#laelGrado').css('display','none');
			    $('#laelAula').css('display','none');
			    $('#laelYear').css('display','initial');
		    }else if(data.error == 1) {
				setCombo('selectSedeByYear', null, 'Sede',null);
				setCombo('selectNivelByYear', null, 'Nivel',null);
				setCombo('selectGradoByYear', null, 'Grado',null);
			    setCombo('selectAulaByYear', null, 'Aula',null);
			    $('alumnosTitulo').css('display','none');
			}
			$('#cont_imagen_magic').css('display','none');
			$('#contAulas').html(data.cardsAulas);
			$('#contAlumnos').html(data.cardsAlumnos);
			stopLoadingButton('botonFM');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getNivelesBySede() {
	count_scroll = 1;
	var valorGeneral = $('#searchMagic').val().trim();
	var year         = $('#selectYear option:selected').val();
	var idSede       = $('#selectSedeByYear option:selected').val();

	var yearText     = (year == null || year == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText     = (idSede  == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	addLoadingButton('botonFM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/comboSedesNivel",
	        data: {  valorGeneral  : valorGeneral,
	        	     year          : year,
		    	     idSede  	   : idSede,
					 count 		   : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.cardsAlumnos == null && data.cardsAulas == null) {
				$('#cont_imagen_magic').css('display','block');
				$('#contAulas').html(null);
				$('#contAlumnos').html(null);
			}
			if(data.error == 0) {
				if(year == null || year == "") {
					$('.breadcrumb').css('display','none');
					$('main').removeAttr('onscroll');
				}else {
					$('main').attr('onscroll', 'onScrollEvent(this)');
				}
				$('#cont_search_empty').css('display','none');
				sede = $("#selectSedeByYear").find("option:selected").text();
			    setCombo('selectNivelByYear', data.optNivel, 'Nivel',null);
			    setCombo('selectGradoByYear', null, 'Grado',null);
			    setCombo('selectAulaByYear', null, 'Aula',null);
//				$("#filtroMovimiento").show();
			    $('#laelyear').html(yearText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#filtroMain').removeAttr('style');
			    $('#laelyear').addClass('active');
			    $('#laelSede').html(sedeText);
			    $('#laelSede').addClass('active');
			    $('#laelSede').text(sede);
			    $('#laelSede').removeAttr('style');
			    $('#laelNivel').css('display','none');
			    $('#laelGrado').css('display','none');
			    $('#laelAula').css('display','none');
			    $('#laelYear').css('display','initial');
			    $('#alumnosTitulo').css('display','block');	
			    $('#aulasTitulo').css('display','block');
		    }else if(data.error == 1) {
			    setCombo('selectNivelByYear', null, 'Nivel',null);
			    setCombo('selectGradoByYear', null, 'Grado',null);
			    setCombo('selectAulaByYear', null, 'Aula',null);
			    $('#alumnosTitulo').css('display','none');	
			    $('#aulasTitulo').css('display','none');
			}
			$('#cont_imagen_magic').css('display','none');
			$('#contAulas').html(data.cardsAulas);
			$('#contAlumnos').html(data.cardsAlumnos);
			stopLoadingButton('botonFM');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getGradosByNivel() {
	count_scroll = 1;
	var valorGeneral = $('#searchMagic').val().trim();
	var year         = $('#selectYear option:selected').val();
	var idSede       = $('#selectSedeByYear option:selected').val();
	var idNivel      = $('#selectNivelByYear option:selected').val();

	var yearText     = (year == null || year == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText     = (idSede  == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	var nivelText    = (idNivel == null || idNivel == "") ? " " :  $('#selectNivelByYear option:selected').text();
	addLoadingButton('botonFM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/getComboGradoByNivel_Ctrl",
	        data: {  valorGeneral  : valorGeneral,
	        	     year          : year,
       	     		 idNivel   	   : idNivel,
		    	     idSede  	   : idSede,
					 count 		   : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.cardsAlumnos == null && data.cardsAulas == null) {
				$('#cont_imagen_magic').css('display','block');
				$('#contAulas').html(null);
				$('#contAlumnos').html(null);
			}
			if(data.error == 0) {
				if(idNivel == null || idNivel == "") {
					$('.breadcrumb').css('display','none');
					$('main').removeAttr('onscroll');
				}else {
					$('main').attr('onscroll', 'onScrollEvent(this)');
				}
				$('#cont_search_empty').css('display','none');
			    setCombo('selectGradoByYear', data.optGrado, 'grado',null);
			    setCombo('selectAulaByYear', null, 'aula',null);
				nivel = $("#selectNivelByYear").find("option:selected").text();
			    $("#filtroMovimiento").show();
			    $('#laelSede').html(sedeText);
			    $('#laelSede').css('display','initial');
			    $('#laelNivel').html(nivelText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#filtroMain').removeAttr('style');
			    $('#laelNivel').addClass('active');
			    $('#laelNivel').text(nivel);
			    $('#laelNivel').addClass('active');
			    $('#laelGrado').css('display','none');
			    $('#laelAula').css('display','none');
			    $('#laelNivel').css('display','initial');
			}else if(data.error == 1){
			    setCombo('selectGradoByYear', null, 'Grado',null);
			    setCombo('selectAulaByYear', null, 'Aula',null);
			}
			$('#cont_imagen_magic').css('display','none');
			$('#contAulas').html(data.cardsAulas);
			$('#contAlumnos').html(data.cardsAlumnos);
			stopLoadingButton('botonFM');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getAulasByNivelSede() {
	count_scroll = 1;
	var valorGeneral = $('#searchMagic').val().trim();
	var year         = $('#selectYear option:selected').val();
	var idSede       = $('#selectSedeByYear option:selected').val();
	var idGrado      = $('#selectGradoByYear option:selected').val();
	var idNivel      = $('#selectNivelByYear option:selected').val();
	var idAula       = $('#selectAulaByYear option:selected').val();

	var yearText     = (year  == null || year  == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText     = (idSede  == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	var nivelText    = (idNivel == null || idNivel == "") ? " " :  $('#selectNivelByYear option:selected').text();
	var gradoText    = (idGrado == null || idGrado == "") ? " " :  $('#selectGradoByYear option:selected').text();
	var aulaText     = (idAula  == null || idAula  == "") ? " " :  $('#selectAulaByYear option:selected').text();
	addLoadingButton('botonFM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url: "c_main/comboAulasByGradoUtils",
	        data: {  valorGeneral  : valorGeneral,
	        	     year          : year,
	       	     	 idGrado 	   : idGrado,
		    		 idNivel   	   : idNivel,
		    	     idSede  	   : idSede,
		    	     idAula  	   : idAula,
					 count 		   : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			if(data.cardsAlumnos == null && data.cardsAulas == null) {
				$('#cont_imagen_magic').css('display','block');
				$('#contAulas').html(null);
				$('#contAlumnos').html(null);
			}
			if(data.error == 0) {
				$('#cont_search_empty').css('display','none');
				aula= $('#selectAulaByYear').find("option:selected").text();
			    setCombo('selectAulaByYear', data.optAula, 'aula',null);
			    $('#laelSede').html(sedeText);
			    $('#laelNivel').html(nivelText);
			    $('#laelGrado').html(gradoText);
			    $('.breadcrumb').find('li').removeClass('active');
			    $('#laelGrado').addClass('active');
			    $('#laelGrado').text(gradoText);
			    $('#laelGrado').addClass('active');
				$('#laelAula').css('display','none');
				$('#laelGrado').css('display','initial');
			}else if(data.error == 1) {
				setCombo('selectAula', null, 'Aula',null);
			}
			$('#cont_imagen_magic').css('display','none');
			$('#contAulas').html(data.cardsAulas);
			$('#contAlumnos').html(data.cardsAlumnos);
			stopLoadingButton('botonFM');
			componentHandler.upgradeAllRegistered();
			scroll = 1;
		});
	});
}

function getAlumnosByAula() {
	count_scroll    = 1;
	var valorGeneral = $('#searchMagic').val().trim();
	var year         = $('#selectYear option:selected').val();
	var idSede       = $('#selectSedeByYear option:selected').val();
	var idGrado      = $('#selectGradoByYear option:selected').val();
	var idNivel      = $('#selectNivelByYear option:selected').val();
	var idAula       = $('#selectAulaByYear option:selected').val();

	var yearText     = (year  == null || year  == "") ? " " :  $('#selectYear option:selected').text();
	var sedeText     = (idSede  == null || idSede  == "") ? " " :  $('#selectSedeByYear option:selected').text();
	var nivelText    = (idNivel == null || idNivel == "") ? " " :  $('#selectNivelByYear option:selected').text();
	var gradoText    = (idGrado == null || idGrado == "") ? " " :  $('#selectGradoByYear option:selected').text();
	var aulaText     = (idAula  == null || idAula  == "") ? " " :  $('#selectAulaByYear option:selected').text();
	addLoadingButton('botonFM');
	Pace.restart();
	Pace.track(function() {
		$.ajax({
			url : "c_main/getAlumnosFromAula",
	        data: {  valorGeneral  : valorGeneral,
	        	     year          : year,
	        	     idGrado       : idGrado,
		    		 idNivel 	   : idNivel,
		    	     idSede  	   : idSede,
		    	     idAula  	   : idAula,
					 count 		   : count_scroll},
	        async: true,
	        type: 'POST'
		})
		.done(function(data){
			data = JSON.parse(data);
			aula = $('#')
			if(data.cardsAlumnos == null && data.cardsAulas == null) {
				$('#cont_imagen_magic').css('display','block');
				$('#contAulas').html(null);
				$('#contAlumnos').html(null);
			}
			$('#cont_search_empty').css('display','none');
		    $('#laelSede').html(sedeText);
		    $('#laelNivel').html(nivelText);
		    $('#laelGrado').html(gradoText);
		    $('#laelAula').html(aulaText);
		    $('.breadcrumb').find('li').removeClass('active');
		    $('#laelAula').addClass('active');
			componentHandler.upgradeAllRegistered();
		    $('#laelAula').html(aulaText);
		    $('#laelAula').addClass('active');
		    $('#laelAula').css('display','initial');
			$('#cont_imagen_magic').css('display','none');
			$('#contAulas').html(data.cardsAulas);
			$('#contAlumnos').html(data.cardsAlumnos);
		    stopLoadingButton('botonFM');
		});
	});
}
