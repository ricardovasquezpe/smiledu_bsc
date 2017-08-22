<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>      
		<title><?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
		
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION;?>css/submenu.css">
        
        <style>
            button.big{
            	width: 100%;
            	margin-left: 0;
            }
            
            .inscrito-value{
	           text-decoration:none !important;
            	cursor:default !important;
            }
        </style>
                
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    	    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <?php echo (isset($tabs) ? $tabs : null)?>
                
            <div class="img-search" id="cont_empty_evaluados" style="display:<?php echo $display?>">
                <img src="<?php echo RUTA_IMG?>smiledu_faces/not_data_found.png">
                <p><strong>&#161;Ups!</strong></p>
                <p>A&uacute;n no se muestran contactos</p>
            </div>
            </main>
        </div>

        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap mfb-only-btn">
                <button class="mfb-component__button--main"  onclick="abrirModalFiltrar()" data-mfb-label="Buscar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>  
        
        <div class="modal fade" id="modalResumenDiag" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="tituloResumenDiag"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div id="cont_tb_resumen_diagnostico"></div>
					       </div>
    					</div>
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Entrevista con Sub-director</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">    				
					       <div class="row-fluid">
					           <div id="cont_tb_diagnostico_subdirector"></div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalMigrarMatricula" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Estas segura de pasarlo al proceso de matricula?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
                                Recuerda que al pasarlo al proceso de matricula todos sus datos del estudiante y de sus familiares se enviaran al sistema de matricula.
                                Se enviará las credenciales del sistema de matricula al correo del familiar registrado
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="ingresarAMatricula()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltrar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <!--div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreFiltrar" name="nombreFiltrar" maxlength="100" onchange="filtrarPostulantes()">        
                                        <label class="mdl-textfield__label" for="nombreFiltrar">Nombre</label>                            
                                    </div>
				               </div-->
				               <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectGradoNivelFiltro" name="selectGradoNivelFiltro" class="form-control selectButton" data-live-search="true"
                                                data-noneSelectedText="Selec. Grado y Nivel" onchange="getCursosByGradoNivel()">
    						                  <option value="">Selec. Grado y Nivel</option>
    						                  <?php echo $comboGradoNivel?>
    						             </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectCursoFiltro" name="selectCursoFiltro" class="form-control selectButton" data-live-search="true"
                                                data-noneSelectedText="Selec. Curso">
    						                  <option value="">Selec. Curso</option>
    						             </select>
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="filtrarPostulantes()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_PLUGINS?>socket.io/socket.io.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsevaluacion.js" charset="utf-8"></script>
        
        <script>
            var nodeServer = '<?php echo $server_node;?>';
            init(<?php echo $evento?>, <?php echo $tab?>, 0, 0, 0);
            var cons_estado_elegido = '<?php echo $estadoTab?>';
            var cons_index_elegido  = <?php echo $indexTab?>; 
        </script>
	</body>
</html>