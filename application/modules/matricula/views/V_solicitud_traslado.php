<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Traslado | <?php echo NAME_MODULO_MATRICULA;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
		
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
		
        <style type="text/css">
            .display-none{
            	display: none !important;
            }
            .fixed-table-toolbar.mdl-card__menu{
            	padding: 10px
            }
        </style>
    </head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="mdl-content-cards">
                        <div class="row-fluid" id="cont_alumnos">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="mdl-card mdl-shadow--2dp">
                                    <div class="mdl-card__title" >
                                        <h2 class="mdl-card__title-text" id="titleTb">Traslados</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-rl-0 br-b">   
                                        <div id="contTablaBusqueda" class="table-responsive">
                                            <?php echo $tablaSolicitudes?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-search" id="cont_search_not_found" style="display: none;">
                        <img src="<?php echo RUTA_IMG?>smiledu_faces/magic_not_found.png">
                        <p><strong>&#161;Ups!</strong></p>
                        <p>No se encontraon</p>
                        <p>resultados.</p>
                    </div>
                </section>
            </main>
        </div>       
            
        <div class="modal fade backModal" id="modalSolicitudDeTraslado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Solicitud de traslado</h2>
                        </div>
                        <div class="mdl-card__supporting-text ">
                            <div class="row p-0 m-0">
                                <div class="col-sm-6 p-0 m-0 m-b-20 divSede p-r-10">
                                    <select id="selectSedeDestino" name="selectSedeDestino" class="form-control selectButton" data-live-search="true"
                                    data-noneSelectedText="Seleccione un sede destino" onchange = "getAulasBySede('selectSedeDestino','selectAulaDestino')">
    					                  <option value="">Seleccione Sede</option>
    					              </select>
                                 </div>
                                <div class="col-sm-6 p-0 m-0 m-b-20 p-l-10" >
                                    <select id="selectAulaDestino" name="selectAulaDestino" class="form-control selectButton" data-live-search="true" 
                                    data-noneSelectedText="Seleccione un aula destino">
    					                  <option value="">Seleccione aula</option>
    					              </select>
                                 </div> 
                                 <div class="col-xs-12 col-md-12 col-lg-12 p-0 m-0 m-b-20">
                                     <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="motivoTraslado" name="motivoTraslado" maxlength="50"></textarea>        
                                        <label    class="mdl-textfield__label" for="motivoTraslado">Motivo de Traslado</label> 
                                     </div>
                                 </div>
                            </div>
                        </div>     
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="enviarSolicitud();">Enviar</button>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalMotivosTraslado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Motivos</h2> 
                        </div>
                        <div class="mdl-card__supporting-text p-r-0 p-l-0">
                            <div class="row-fluid">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="cont_motivo_traslado">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="motivoTrasladoDetalle" name="motivoTrasladoDetalle" max-length="200" rows="3" cols="50" readonly disabled style="pointer-events: none"></textarea>          
                                        <label class="mdl-textfield__label" for="motivoTrasladoDetalle">Motivo de Traslado</label>    
                                        <span class="mdl-textfield__limit" for="motivoTrasladoDetalle" data-limit="200"></span>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="cont_motivo_confirmacion">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="motivoConfirmacion" name="motivoConfirmacion" maxlength="200" rows="3" cols="50" readonly disabled style="pointer-events: none"></textarea>      
                                        <label class="mdl-textfield__label" for="motivoConfirmacion">Motivo de confirmaci&oacute;n</label>       
                                        <span class="mdl-textfield__limit" for="motivoConfirmacion" data-limit="200"></span>                        
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalConfirmTraslado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">&iquest;Trasladar estudiante?</h2>
                        </div>
                        <div class="mdl-card__supporting-text br-b">
                            <p style="font-weight: bold;display: inline-block;" id="nombreAlumnoTralado"></p>
                            <div class="row-fluid">
                                <div class="col-sm-12 text-center m-t-10 m-b-10">
                                    <button id="rechazar" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored mdl-color-text--red" onclick="selectOpcionTraslado('<?php echo $botonRechazar?>', 1)">RECHAZAR</button>
                                    <button id="aceptar" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored mdl-color-text--green" onclick="selectOpcionTraslado('<?php echo $botonAceptar?>', 0)">ACEPTAR</button>
                                </div>
                                <div class="col-sm-12 p-0 m-0 m-b-20 m-t-20" id="cont_aula_destino" style="display: none">
                                    <select id="selectAulaDestinoConfirm" name="selectAulaDestinoConfirm" class="form-control selectButton" data-live-search="true" 
                                            data-noneSelectedText="Seleccione un aula destino">
    					                  <option value="">Seleccione aula</option>
    					              </select>
                                </div>
                                <div class="col-xs-12 col-md-12 col-lg-12 p-0 m-0 m-b-20" id="cont_motivo_rechazo" style="display: none;">
                                     <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="motivoRechazo" name="motivoRechazo" maxlength="200" rows="5" cols="50"></textarea>        
                                        <label    class="mdl-textfield__label" for="motivoRechazo">Motivo de Rechazo</label> 
                                        <span class="mdl-textfield__limit" for="motivoRechazo" data-limit="200"></span>         
                                     </div>
                                 </div>
                            </div>
                        </div>     
                        <div class="mdl-card__actions" style="display: none">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="buttonEstado" onclick="trasladarAlumno();">Enviar</button>
                        </div> 
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close" style="font-size:20px;top:4px;"></i>
                            </button>
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
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jstraslado.js"></script>
    
        <script type="text/javascript">
           init();

           $(document).ready(function(){
       	    $('[data-toggle="tooltip"]').tooltip(); 
       	});
        </script>
    </body>
</html>