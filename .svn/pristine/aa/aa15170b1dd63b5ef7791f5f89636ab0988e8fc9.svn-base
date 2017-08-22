<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>   
        <script type="text/javascript">
            var timeInit = performance.now();             
        </script>   
        <title>Talleres | <?php echo NAME_MODULO_NOTAS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>fullcalendar/fullcalendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/submenu.css">
        
        <style type="text/css">
            #cabecera .breadcrumb li:NTH-CHILD(2){
            	padding-left: 10px
            }
            
            #cabecera .breadcrumb li:NTH-CHILD(2):BEFORE{
            	content: none;
            }
        </style>
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>	
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="row-fluid">
                        
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Mis Hijos</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTbHijos">
                                        <?php echo isset($hijos) ? $hijos : null?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Talleres</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="img-search" id="cont_select_empty_taller">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                            <p><strong>Hey!</strong></p>
                                            <p>Seleccione un hijo</p>
                                            <p>para ver mas detalles.</p>                         
                                        </div>
                                    </div>
                                    <div class="mdl-card__supporting-text br-b p-0" id="contTalleresHijo" style="text-transform: lowercase;">
                                        
                                    </div>
                                </div>
                            </div>
                                                       
                        </div>
                    </div>
                </section>
            </main>	
        </div>
        
        <!-- MODALES -->
        <div class="modal fade" id="modalAsignarGrupoTaller" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar grupo</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0" id="con_tb_grupos_taller"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnAsignarGrupo" onclick="abrirModalConfirmar()" disabled>Asignar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-toggle="tooltip" data-placement="left">
                                <i class="mdi mdi-info"></i>
                            </button>    
                        </div>
                    </div>
                </div>     
            </div>
        </div>
        
        <div class="modal fade" id="modalConfirmAsignar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleConfirmAsignar"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Recuerda: solo se podr&aacute; cambiar de grupo con una solicitud administrativa de la sede correspondiente</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnConfirmAsignarGrupo" onclick="asginarEstudianteGrupo()">Aceptar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
        
        <div class="modal fade" id="modalChangeTallerGrupo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cambio de grupo</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div class="col-sm-12 mdl-input-group mdl-input-group__only">
				               <div class="mdl-select">
					               <select id="selectTallerChange" name="selectTallerChange" class="form-control selectButton" data-live-search="true" onchange="getGruposByTallerChange()">
            			                <option value="">Seleccione taller de cambio</option>
            			           </select>
        			           </div>
    			           </div>
    			           <div class="mdl-card__supporting-text p-0" id="cont_tb_taller_change"></div>
    			           <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="cont_motivo_cambio" style="display: none">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <textarea class="mdl-textfield__input" id="motivoCambio" name="motivoCambio" max-length="100" rows="3" cols="50"></textarea>          
                                    <label class="mdl-textfield__label" for="motivoCambio">Motivo Cambio</label>    
                                    <span class="mdl-textfield__limit" for="motivoCambio" data-limit="100"></span>                            
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnChangeTallerGrupo" onclick="solicitarRealizarCambioTallerGrupo()" disabled>Solicitar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
          
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsSelecTaller.js"></script>
        <script>
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
	</body>
</html>