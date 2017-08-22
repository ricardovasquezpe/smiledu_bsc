<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Alumno ECE | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">

	</head>

	<body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >    		
    		<?php echo $menu ?>
    		<main class='mdl-layout__content is-visible'>
                <section> 
                    <div class="mdl-content-cards">
                        <div class="img-search">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debes buscar</p>
                            <p>para poder ver lo que buscas.</p>
                        </div>
                        <ol class="breadcrumb" style="display: none;">
                            <li class="active"><strong>Filtro: </strong></li>
            				<li class="null-content"></li>
            				<li class=""></li>
            				<li class=""></li>
            				<li class=""></li>
            			</ol>
                        <div class="mdl-card" style="display: none">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">ECE Alumnos</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div id="contTbAlumnos" class="form floating-label table_distance">
						            <?php echo $tablaAlumnos;?>
						        </div>
                            </div>
                        </div>            
                    </div>       
                </section>  
            </main>
        </div> 
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main">
                    <i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
                </button>
                <button class="mfb-component__button--main" onclick="abrirCerrarModal('modalFiltro')" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--active mdi mdi-filter_list" ></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child " onclick="abrirModalMostrarAulas()" data-mfb-label="Aulas">
                            <i class="mfb-component__child-icon mdi mdi-classroom"></i>
                        </button>
                    </li>
                </ul>
            </li>
        </ul>
        
    	<div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
			        <div class="mdl-card mdl-card-fab">
					    <div class="mdl-card__title">
					        <h2 class="mdl-card__title-text">Filtros</h2>
				        </div>
				        <div class="mdl-card__supporting-text">
							<div class="row">
							    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							        <div class="mdl-select">
    									<select id="cmbYear" name="cmbYear" title="Año" class="form-control pickerButn" onchange="setYear();">
    										<option value="">Selec. Año.</option>
    										<option value="<?php echo _encodeCI(2014);?>">2014</option>
    										<option value="<?php echo _encodeCI(2015);?>">2015</option>
    										<option value="<?php echo _encodeCI(2016);?>">2016</option>
    									</select>
									</div>
								</div>
								<div class="col-sm-12 mdl-input-group mdl-input-group__only">
								    <div class="mdl-select">
    									<select id="selectSede" name="selectSede" data-live-search="true" title="Sede" class="form-control pickerButn"  onchange="getNivelBySede()">
    										<option value="">Selec. Sede.</option>
    										<?php echo $comboSedes;?>
    									</select>
									</div>
								</div>
								<div class="col-sm-12 mdl-input-group mdl-input-group__only">
								    <div class="mdl-select">
    									<select id="selectNivel" name="selectNivel" data-live-search="true" title="Nivel" class="form-control pickerButn" onchange="getGradosByNivel();">
    										<option value="">Selec. Nivel.</option>
    									</select>
									</div>
								</div>
								<div class="col-sm-12 mdl-input-group mdl-input-group__only">
								    <div class="mdl-select">
    									<select id="selectGrado" name="selectGrado" data-live-search="true" title="Grado" class="form-control pickerButn">
    										<option value="">Selec. Grado</option>
    									</select>
									</div>
								</div>							
							</div>
				        </div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMG" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="getMostrarGrado()">Aceptar</button>
						</div>
				    </div>
                </div>     
            </div>
        </div>	
	  	
                
                
        <div class="modal fade" id="modalExcel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
			        <div class="mdl-card mdl-card-fab">
					    <div class="mdl-card__title">
					        <h2 class="mdl-card__title-text">Subir Excel</h2>
				        </div>
				        <div class="mdl-card__supporting-text">
							<div class="row-fluid">
							    <div class="col-xs-12 p-0 m-0 m-b-20">
									<select id="cmbYearSubir" name="cmbYearSubir" title="Año" class="form-control pickerButn" onchange="setYearSubir();">
										<option value="">Selec. Año.</option>
										<option value="<?php echo _encodeCI(2014);?>">2014</option>
										<option value="<?php echo _encodeCI(2015);?>">2015</option>
										<option value="<?php echo _encodeCI(2016);?>">2016</option>
									</select>
								</div>
								<div class="col-xs-12 p-0 m-0 m-b-20">
									<select id="selectSedeExcel" name="selectSedeExcel" data-live-search="true" title="Sede" class="form-control pickerButn"  onchange="getNivelBySede_Excel()">
										<option value="">Selec. Sede.</option>
										<?php echo $comboSedes;?>
									</select>
								</div>
								<div class="col-xs-12 p-0 m-0 m-b-20">
									<select id="selectNivelExcel" name="selectNivelExcel" data-live-search="true" title="Nivel" class="form-control pickerButn" onchange="getGradosByNivel_Excel()">
										<option value="">Selec. Nivel.</option>
									</select>
								</div>
								<div class="col-xs-12 p-0 m-0 m-b-20">
									<select id="selectGradoExcel" name="selectGradoExcel" data-live-search="true" title="Grado" class="form-control pickerButn">
										<option value="">Selec. Grado</option>
									</select>
								</div>
								<div class="col-xs-12 p-0 m-0 m-b-20">
									<input type="file" id="itExcel" name="itExcel"/>
                                    <form action="c_ece_alumnos/setExcel" name="expexcel" id="expexcel" method="post"></form>
								</div>
							</div>
				        </div>
						<div class="mdl-card__actions">					    
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect " type="button" data-dismiss="modal">Cancelar</a>
							<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="subirExcel()">Subir</a>
						</div>
				    </div>
                </div>     
            </div>
        </div>
         
         
         
         <div class="modal fade" id="modalMostrarAulas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Poner Letra de Aula</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid">
                                <div class="col-xs-12 m-0 m-b-20 m-t-15">
									<select id="cmbYearFiltro" name="cmbYearFiltro" title="Año" class="form-control pickerButn" onchange="setYearFiltro();">
										<option value="">Selec. Año.</option>
										<option value="<?php echo _encodeCI(2014);?>">2014</option>
										<option value="<?php echo _encodeCI(2015);?>">2015</option>
										<option value="<?php echo _encodeCI(2016);?>">2016</option>
									</select>
								</div>
                                <div class="col-xs-12 m-0 m-b-20">
                                    <select id="selectSede_" name="selectSede_" data-live-search="true" title="Sede" class="form-control pickerButn"  onchange="getNivelBySede_();">
										<option value="">Selec. Sede.</option>
										<?php echo $comboSedes;?>
									</select>
                                </div>
                                <div class="col-xs-12 m-0 m-b-20">
                                    <select id="selectNivel_" name="selectNivel_" data-live-search="true" title="Nivel" class="form-control pickerButn"  onchange="getGradosByNivel_();">
										<option value="">Selec. Nivel.</option>
									</select>
                                </div>
                                <div class="col-xs-12 m-0 m-b-20">
                                    <select id="selectGrado_" name="selectGrado_" data-live-search="true" title="Grado" class="form-control pickerButn" onchange="getMostrarGrado_();">
                                        <option value="">Selec. Grado</option>
	                                </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <div id="contTabAulas" class="form floating-label table_distance">
    									 <?php echo isset($tablaAulas) ? $tablaAulas : null;?>
    								  </div> 
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">                
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" type="button" data-dismiss="modal">Cancelar</a>
                            <a id="botonMF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="CapturarAulas($(this))">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>  
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script> 		   	
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jsece_alumnos.js"></script>
        
		<script type="text/javascript">
		initEceAlumnos();
		$(function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
    		(function($) {
            $.fn.clickToggle = function(func1, func2) {
                var funcs = [func1, func2];
                this.data('toggleclicked', 0);
                this.click(function() {
                    var data = $(this).data();
                    var tc = data.toggleclicked;
                    $.proxy(funcs[tc], this)();
                    data.toggleclicked = (tc + 1) % 2;
                });
                return this;
            };
        }(jQuery));
    
    		initSearchTableNew();
        
        var lastScrollTop = 0;
        $(window).scroll(function(event){
           var st = $(this).scrollTop();
           if (st > lastScrollTop){//OCULTAR
        	   $("#menu").fadeOut();
           } else {
        	   if(st + $(window).height() < $(document).height()) {//MOSTRAR
        		   $("#menu").fadeIn();
        	       
    	    	}
           }
           lastScrollTop = st;
        });
		</script>
	</body>
</html>