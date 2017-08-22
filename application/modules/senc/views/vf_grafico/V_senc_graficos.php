<!DOCTYPE html>
<html lang="en">
    <head>        
        <title>Gr&aacute;ficos | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>highcharts/">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC;?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/logic/graficos.css">
        
    </head>
    
    <body>
    
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
    		<main class='mdl-layout__content is-visible'>
                <section class="mdl-layout__tab-panel is-active" id="grafico1">
                    <div class="mdl-content-cards">
            			<div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b" id="container_grafico_1">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<div class="mdl-card__menu">
    							<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" disabled onclick="refreshSection1();" data-refresh="true">
                                    <i class="mdi mdi-refresh"></i>
                                </button>
    							<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" onclick='changeTypeGrafico("container_grafico_1", "pie");' disabled>
                                    <i class="mdi mdi-pie_chart"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" onclick='changeTypeGrafico("container_grafico_1", "column");' disabled>
                                    <i class="mdi mdi-insert_chart"></i>
                                </button>
                                <button id="options_1" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_1" disabled>
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="options_1">
                                    <li class="mdl-menu__item" onclick="exportGraficoToExcel('container_grafico_1', 1, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.xls)</li>
                                    <li class="mdl-menu__item" onclick="exportGraficoToPdf('container_grafico_1', 1, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.pdf)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino('container_grafico_1', 1, 1)">
                                        <i class="mdi mdi-send"></i> Enviar Email (.xls)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino1('container_grafico_1', 1, 1)">
                                        <i class="mdi mdi-send"></i> Enviar Email (.pdf)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="grafico2" style="display:none">
                    <div class="mdl-content-cards">
            			<div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b" id="container_grafico_2">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<div class="mdl-card__menu">
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity" onclick="refreshSection2();" data-refresh="true" disabled>
                                    <i class="mdi mdi-refresh"></i>
                                </button>
    							<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_2" onclick='changeTypeGrafico("container_grafico_2", "line");'>
                                    <i class="mdi mdi-show_chart"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_2" onclick='changeTypeGrafico("container_grafico_2", "column");'>
                                    <i class="mdi mdi-insert_chart"></i>
                                </button>
                                <button id="options_2" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_2">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="options_2">
                                    <li class="mdl-menu__item" onclick="exportGraficoToExcel('container_grafico_2', 2, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.xls)</li>
                                    <li class="mdl-menu__item" onclick="exportGraficoToPdf('container_grafico_2', 2, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.pdf)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino('container_grafico_2', 2, 1)">
                                        <i class="mdi mdi-file_download"></i> Enviar Email (.xls)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino1('container_grafico_2', 2, 1)">
                                        <i class="mdi mdi-file_download"></i> Enviar Email (.pdf)</li>
                                </ul>
                            </div>
						</div>
                    </div>
                </section>  
                <section class="mdl-layout__tab-panel" id="grafico3" style="display:none">
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b" id="container_grafico_3">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<div class="mdl-card__menu">
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity" onclick="refreshSection3();" data-refresh="true" disabled>
                                    <i class="mdi mdi-refresh"></i>
                                </button>
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_3" onclick='changeTypeGrafico("container_grafico_3", "pie");'>
                                    <i class="mdi mdi-pie_chart"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_3" onclick='changeTypeGrafico("container_grafico_3", "column");'>
                                    <i class="mdi mdi-insert_chart"></i>
                                </button>
                                <button id="options_3" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_3">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="options_3">
                                    <li class="mdl-menu__item" onclick="exportGraficoToExcel('container_grafico_3', 3, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.xls)</li>
                                    <li class="mdl-menu__item" onclick="exportGraficoToPdf('container_grafico_3', 3, 0);">
                                        <i class="mdi mdi-file_download"></i> Descargar (.pdf)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino('container_grafico_3', 3, 1)">
                                        <i class="mdi mdi-file_download"></i> Enviar Email (.xls)</li>
                                    <li class="mdl-menu__item" onclick="openModalCorreoDestino1('container_grafico_3', 3, 1)">
                                        <i class="mdi mdi-file_download"></i> Enviar Email (.pdf)</li>
                                </ul>
                            </div>
						</div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="grafico4" style="display:none">
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b" id="container_grafico_4">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<div class="mdl-card__menu">
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity" onclick="refreshSection4();" data-refresh="true" disabled>
                                    <i class="mdi mdi-refresh"></i>
                                </button>
                                <button id="options_4" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_4">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="options_4">
                                    <li class="mdl-menu__item" onclick="downloadAllGraficosPDF();">
                                        <i class="mdi mdi-file_download"></i> Descargar Vertical (.pdf)</li>
                                    <li class="mdl-menu__item" onclick="downloadAllGraficosPDF_1();">
                                        <i class="mdi mdi-file_download"></i> Descargar Horizontal (.pdf)</li>
                                    <li class="mdl-menu__item" onclick="donwloadExcelEncuesta()">
                                        <i class="mdi mdi-file_download"></i> Descargar (.xls)</li>
                                </ul>
                            </div>
						</div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="grafico5" style="display:none">
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b p-0" id="container_grafico_5">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<div class="mdl-card__menu">
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity" onclick="refreshSection5();" data-refresh="true" disabled>
                                    <i class="mdi mdi-refresh"></i>
                                </button>
						        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_5" disabled onclick="getGraficoByEncuestaGrafico5('container_grafico_5','tabla')">
                                    <i class="mdi mdi-reorder"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_5" disabled onclick="getGraficoByEncuestaGrafico5('container_grafico_5','column')">
                                    <i class="mdi mdi-insert_chart"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_5" disabled onclick="getGraficoByEncuestaGrafico5('container_grafico_5','pie')">
                                    <i class="mdi mdi-pie_chart"></i>
                                </button>
                            </div>
						</div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="grafico6" style="display:none">
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_filter_empty">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar los gr&aacute;ficos</p>
                            <p>del reporte.</p>                             
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Gr&aacute;ficos</h2>
							</div>
							<div class="mdl-card__supporting-text br-b" id="container_grafico_6">
                                <div class="img-search">
                                    <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">
                                    <p><strong>&#161;Ups!</strong></p>
                                    <p>No se encontraron</p>
                                    <p>resultados.</p>
                                </div>
                            </div>
							<canvas width="500" height="300" id="canvas" style="display:none;" >
							</canvas>
							<div class="mdl-card__menu">
							    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick='abrirCerrarModal("modalInfoGrafico")'>
                                    <i class="mdi mdi-info"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity" onclick="refreshSection6();" data-refresh="true" disabled>
                                    <i class="mdi mdi-refresh"></i>
                                </button>
						        <a class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect btn_opacity btn_cambio_vista_6" download href="#" disabled id="exportImgTuto">
                                    <i class="mdi mdi-file_download"></i>
                                </a>
                            </div>
						</div>
                    </div>
                </section>
    		</main>    		
    	</div> 
    	
    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    		<li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
        		<button  class="mfb-component__button--main" id="main_button" onclick="abrirModalFiltro();" data-mfb-label="Filtrar"> 
        			<i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
    		    </button>
		    </li>
    	</ul>
    
    	<div class="offcanvas"></div>    
    	
        <div class="modal fade backModal" id="modalFiltroGraficoEncuesta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestaGrafico1"
    										name="selectTipoEncuestaGrafico1" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getEncuestasByTipo()">
    										<option>Seleccione Tipo de Encuesta</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    									<select id="selectEncuestaGrafico1"
    										name=selectEncuestaGrafico1 data-live-search="true"
    										class="form-control pickerButn"  
    										onchange="getPreguntasByEncuesta()" data-none-selected-text="Seleccione una encuesta">
    										<option>Seleccione Encuesta</option>
    									</select>
									</div>
    							</div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectPreguntaGrafico1"
    										name="selectPreguntaGrafico1" data-live-search="true"
    										class="form-control pickerButn" multiple data-actions-box="true"
    										onchange="getGraficoEncuestaPregunta()" data-none-selected-text="Seleccione una pregunta">
    										<option>Seleccione Pregunta</option>
    									</select>
									</div>
							    </div>
						        <div id="contCombosGraficos1" style="padding: 0 10px;"></div>
						        <div id="contCombosSubGrafico1" style="padding: 0 10px;"></div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnMFGE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalFiltroGraficoCompararPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestaGrafico2"
    										name="selectTipoEncuestaGrafico2" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getPreguntasByTipoEcnuestaGrafico2()" multiple
    										data-none-selected-text="Seleccione un tipo de encuesta">
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectPreguntaGrafico2"
    										name="selectPreguntaGrafico2" data-live-search="true"
    										class="form-control pickerButn" data-actions-box="true"
    										onchange="getGraficoByPreguntaGrafico2()" multiple
    										data-none-selected-text="Seleccione una pregunta">
    									</select>
									</div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectYearGrafico2"
    										name="selectYearGrafico2" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getGraficoByYearGrafico2()" multiple
    										data-none-selected-text="Seleccione un a&ntilde;o">
    									</select>
									</div>
							    </div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btnCambiarSatisfaccionTipoGrafico2" style="float: left" data-toggle="tooltip" data-placement="right" data-original-title="Cambiar a insatisfacción"><i class="mdi mdi-sentiment_dissatisfied"></i></button> 
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnMFGCP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalCombosGraficoCompararPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 m-b-15">
							        <div id="comboGrafico2"></div>
							    </div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	    	
    	<div class="modal fade backModal" id="modalFiltroGraficoPropuestaMejora" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestaGrafico3"
    										name="selectTipoEncuestaGrafico3" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getEncuestasByTipo3()" data-none-selected-text="Seleccione un tipo de encuesta">
    										<option value="">Seleccione Tipo de encuesta</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectEncuestaGrafico3"
    										name="selectEncuestaGrafico3" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getPropuestasMejora3()">
    										<option value="">Seleccione Encuesta</option>
    									</select>
									</div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectPropuestaMejoraGrafico3"
    										name="selectPropuestaMejoraGrafico3" data-live-search="true"
    										class="form-control pickerButn" data-actions-box="true"
    										onchange="getGraficoByPropuestaMejoraGrafico3()" multiple
    										data-none-selected-text="Seleccione Propuesta de Mejora">
    									</select>
									</div>
							    </div>
							    <div class="col-sm-12">
							        <div id="contCombosGraficos3"></div>
							    </div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnMFPM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalFiltroGraficoEncuestaPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestaGrafico4"
    										name="selectTipoEncuestaGrafico4" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getEncuestasByTipo4()">
    										<option value="">Seleccione Tipo de Encuesta</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectEncuestaGrafico4"
    										name=selectEncuestaGrafico4 data-live-search="true"
    										class="form-control pickerButn" multiple data-actions-box="true"
    										onchange="getPreguntasByEncuesta4()"
    										data-none-selected-text="Seleccione un tipo de encuesta">
    										<option>Seleccione Encuesta</option>
    									</select>
									</div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectPreguntaGrafico4"
    										name="selectPreguntaGrafico4" data-live-search="true"
    										class="form-control pickerButn" multiple data-actions-box="true"
    										onchange="getGraficoEncuestaPregunta4(); getNivelesBySedeGrafico4(); getAreasBySedeGrafico4();" 
    										data-none-selected-text="Seleccione una pregunta">
    										<option>Seleccione Pregunta</option>
    									</select>
									</div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="cont_selectTipoEncuestadoGrafico4" style="display:none">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestadoGrafico4"
    										name="selectTipoEncuestadoGrafico4" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getGraficoTipoEncuestado4()" 
    										data-none-selected-text="Seleccione un Tipo de Encuestado">
    										<option value="">Seleccione Tipo de encuestado</option>
    										<?php echo $tipo_encuestados?>
    									</select>
									</div>
							    </div>
							    <div class="p-r-10 p-l-10" id="contNivelesByTipoEnc" style="display: none;"></div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnMFGEP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalFiltroTopPreguntas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuesta"
    										name="selectTipoEncuesta" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getEncuestasByTipoGrafico5()">
    										<option value="">Seleccione Tipo de Encuestado</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectEncuestaGrafico5"
    										name="selectEncuestaGrafico5" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getGraficoByEncuestaGrafico5('container_grafico_5','tabla')"
    										data-none-selected-text="Seleccione una encuesta">
    										<option value="">Seleccione una encuesta</option>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="contComboTop" style="display: none;">
                                    <div class="mdl-select">
    							        <select id="selectTopPreg"
    										name="selectTopPreg" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getGraficoByEncuestaGrafico5('container_grafico_5','tabla')">
    										<option value="">Seleccione el top de preguntas</option>
    										<option value="5">5</option>
    										<option value="10">10</option>
    										<option value="20">20</option>
                                        </select>
                                    </div>
							    </div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btnCambiarSatisfaccionGrafico5" style="float: left" data-toggle="tooltip" data-placement="right" data-original-title="Cambiar a insatisfacción"><i class="mdi mdi-sentiment_dissatisfied"></i></button>
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnFTP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="enviarEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    			    <div class="mdl-card" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Escriba el correo destinatario</h2>
						</div>
						<div class="mdl-card__supporting-text">
						    <div class="row">  
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="email" id="correoDestino" name="correoDestino">
                                        <label class="mdl-textfield__label" for="correoDestino">Correo</label>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Aceptar</button>
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnEnviarEmail">Enviar</button>
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalComentarioPropuesta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
    			    <div class="mdl-card" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text" id="tituloPropuesta"></h2>
						</div>
						<div class="mdl-card__supporting-text p-0 m-b-15">
    					    <div id="cont_tabla_comentarios"></div>
						</div>						
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalInfoGrafico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Ayuda</h2>
						</div>
						<div class="mdl-card__supporting-text p-0 m-b-15">
    					    <div class="col-sm-12">
							    <p id="textoAyuda" class="text-center"></p>
							    <img id="imgAyuda" class="img-responsive"></img>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Entend&iacute;</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalFiltroTutoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    			    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
							<h2 class="mdl-card__title-text">Filtros</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectTipoEncuestaTuto"
    										name="selectTipoEncuestaTuto" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getEncuestasByTipoGrafico6()">
    										<option value="">Seleccione Tipo de Encuestado</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
    							        <select id="selectEncuestaGrafico6"
    										name="selectEncuestaGrafico6" data-live-search="true"
    										class="form-control pickerButn"
    										onchange="getGraficoByEncuestaGrafico6('container_grafico_6')"
    										data-none-selected-text="Seleccione una encuesta">
    										<option value="">Seleccione una encuesta</option>
                                        </select>
                                    </div>
							    </div>
							    <div class="col-sm-12">
							        <div id="contCombosGraficos6"></div>
							    </div>
							    <div class="col-sm-12">
							        <div id="contCombosSubGrafico6"></div>
							    </div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
						    <button id="btnMFT" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button> 
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    
    	<form id="myForm" action="../c_download_excel" method="post" target="_blank">
    		<input type='hidden' id='jsonChart' name='jsonChart' value='' /> <input
    			type='hidden' id='typeChart' name='typeChart' value='column' /> <input
    			type='hidden' id='filtroChart' name='filtroChart' value='column' /> <input
    			type='hidden' id='filtroChartEspecifico' name='filtroChartEspecifico'
    			value='' /> <input type='hidden' id='nGrafico' name='nGrafico'
    			value='column' />
    			<input type='hidden' id='enviarEmail' name='enviarEmail' value='' />
    			<input type='hidden' id='correoDestinoCont' name='correoDestinoCont' value='' />
    	</form>
    	
    	<form id="myForm_1" action="../c_download_excel_1" method="post" target="_blank">
    	   <input type='hidden' id="jsonencuestas" name="jsonencuestas" value='' />
    	   <input type='hidden' id="jsonpreguntas" name="jsonpreguntas" value='' />
    	</form>
    
    	<div id="tableNone" style="display: none"></div>
    	<div id="tableNone1" style="display: none"></div>
    
    	<img id="logo_avantgard_none"
    		src="<?php echo RUTA_PUBLIC_SENC?>img/logo/avantgard_logo.jpg"
    		style="display: none;">
    	<img id="logo_merced_none"
    		src="<?php echo RUTA_PUBLIC_SENC?>img/logo/la_merced.jpg"
    		style="display: none;">
    
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap_select/js/bootstrap-select.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>        
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>       
        
    	<script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts-more.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/export-csv.js"></script>
    	
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/FileSaver.js/FileSaver.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Blob.js/Blob.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Blob.js/BlobBuilder.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Deflate/deflate.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/Deflate/adler32cs.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/rgbcolor.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/StackBlur.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/canvg/canvg.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.addimage.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.cell.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.from_html.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.split_text_to_size.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jspdf.plugin.standard_fonts_metrics.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jspdf/jquery.print.js"></script>
    	    	
        <script src="<?php echo RUTA_PUBLIC_SENC?>js/jshammer__graficos.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsgraficos/jsSencGraficos.js"></script>
    	
        <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        var ruta_not_data_fab = '<div class="img-search">'+
                                '   <img src="<?php echo RUTA_IMG?>smiledu_faces/not_filter_fab.png">'+
                                '   <p><strong>&#161;Ups!</strong></p>'+
                                '   <p>No se encontraron</p>'+
                                '   <p>resultados.</p>'+
                                '</div>';
            setTimeout(function(){ $(".mdl-layout__tab-panel").css("display",""); }, 400);
        	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        		$('#selectTipoEncuestaGrafico1').selectpicker('mobile');
        	    $('#selectEncuestaGrafico1').selectpicker('mobile');
        	    $('#selectPreguntaGrafico1').selectpicker('mobile');
        	    
        	    $('#selectTipoEncuestaGrafico2').selectpicker('mobile');
        	    $('#selectPreguntaGrafico2').selectpicker('mobile');
        	    $('#selectYearGrafico2').selectpicker('mobile');
        	    
        	    $('#selectTipoEncuestaGrafico3').selectpicker('mobile');
        	    $('#selectPropuestaMejoraGrafico3').selectpicker('mobile');
    
        	    $('#selectTipoEncuestaGrafico4').selectpicker('mobile');
        	    $('#selectEncuestaGrafico4').selectpicker('mobile');
        	    $('#selectPreguntaGrafico4').selectpicker('mobile');
        	    $('#selectTipoEncuestadoGrafico4').selectpicker('mobile');

        	    $('#selectTopPreg').selectpicker('mobile');
        	}else{
        		$('#selectTipoEncuestaGrafico1').selectpicker({noneSelectedText: 'Seleccione un tipo de encuestaaaa'});
        		$('#selectEncuestaGrafico1').selectpicker({noneSelectedText: 'Seleccione un tipo de encuesta'});
        		$('#selectPreguntaGrafico1').selectpicker({noneSelectedText: 'Seleccione una pregunta'});
        		
        		$('#selectTipoEncuestaGrafico2').selectpicker({noneSelectedText: 'Seleccione un tipo de encuesta'});
        		$('#selectPreguntaGrafico2').selectpicker({noneSelectedText: 'Seleccione una pregunta'});
        		$('#selectYearGrafico2').selectpicker({noneSelectedText: 'Seleccione un a&ntilde;o'});
        		
        		$('#selectPropuestaMejoraGrafico3').selectpicker({noneSelectedText: 'Seleccione una Propuesta de Mejora'});
        		$('#selectTipoEncuestaGrafico3').selectpicker({noneSelectedText: 'Seleccione un tipo de encuesta'});
        		$('#selectEncuestaGrafico3').selectpicker({noneSelectedText: 'Seleccione una encuesta'});
    
        		$('#selectTipoEncuestaGrafico4').selectpicker({noneSelectedText: 'Seleccione un tipo de encuesta'});
        		$('#selectEncuestaGrafico4').selectpicker({noneSelectedText: 'Seleccione una encuesta'});
        		$('#selectPreguntaGrafico4').selectpicker({noneSelectedText: 'Seleccione una pregunta'});
        		$('#selectTipoEncuestadoGrafico4').selectpicker({noneSelectedText: 'Seleccione un Tipo de Encuestado'});

        		$('#selectTopPreg').selectpicker({ });
        	}
            $('.pickerButn').selectpicker({ });
        	(function (H) {
        	    H.Chart.prototype.createCanvas = function (divId){
        	        var svg    = this.getSVG(),
        	            width  = parseInt(svg.match(/width="([0-9]+)"/)[1]),
        	            height = parseInt(svg.match(/height="([0-9]+)"/)[1]),
        	            canvas = document.createElement('canvas');
        	        canvas.setAttribute('width', width);
        	        canvas.setAttribute('height', height);
        	        if(canvas.getContext && canvas.getContext('2d')){
        	            canvg(canvas, svg);
        	            return canvas.toDataURL("image/jpeg");
        	        }else{
        	            alert("Tu navegador no soporta esta funcionalidad, porfavor actualiza tu navegador");
        	            return false;
        	        }
        	    }
        	}(Highcharts));
    	</script>   	
    </body>
</html>