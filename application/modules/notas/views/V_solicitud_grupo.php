<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Solicitu de Grupo | <?php echo NAME_MODULO_NOTAS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width    =device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>cropper/cropper.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
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

	<body>
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">                                
                        <div class="row-fluid">
                            <div class="col-sm-12" id="cabecera" style="display: none;">
                                <ol class="breadcrumb">
                                    <li class="active"><strong>Filtro:</strong></li>
                    				<li class=""></li>
                    				<li class=""></li>
                    			</ol>                     
                			</div>
                            <div class="col-sm-6">
                                <div id="tAlumnos" class="mdl-card" style="display: none">
                                        <div class="mdl-card__title">
                                            <h2 id="mostrarGrupo"class="mdl-card__title-text"></h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b p-0" id="contTbAulumnos">                                               
                                        </div>
                                        <div class="mdl-card__menu">
                                            <button id="izq" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="moverIzDer(0)">
                                                <i class="mdi mdi-keyboard_arrow_left"></i>
                                            </button>
                                            <button id="der" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="moverIzDer(1)">
                                                <i class="mdi mdi-keyboard_arrow_right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-lg-20">
                                    <div id="tSolicitudes" class="mdl-card">
                                        <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Solicitudes</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text br-b p-0" id="contTbSolicitudes">
                                                <?php echo $tbSolicitudes; ?>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-more_vert"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>                  
                            </div>
                        </div>
                    </div>
                </section>
            </main>	
        </div>        
        

        
        
        <!-- Modals -->
        <div class="modal fade" id="modalDocentes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">    				
					       <div class="row-fluid">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="cmbTaller" name="cmbTaller" class="form-control pickerButn" data-live-search="true" title="Selec. Taller" onchange="getCmbGrupos();">
                			                <option value="">Selec. Taller</option>
                			                <?php echo isset($cmbTaller) ? $cmbTaller : null;?>
                			           </select>
            			           </div>
					           </div>				           
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="display:none" id="contCmbGrupo">
					               
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnFD" onclick="getTbSolicitudes()"></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalMotivo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Motivo</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="contMotivo">    				 
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="mdConfirmAcepRech" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                   <div class="mdl-card">
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text rechAcep"></h2>
                       </div>
                       <div class="mdl-card__supporting-text">    	  
                           <small id="comentario"></small>
                       </div>
                       <div class="mdl-card__actions">
                           <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                           <button id="btnCDD" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick=""></button>
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
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>cropper/cropper.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsSolicitudGrupo.js"></script>
        <script>
            $('main.mdl-layout__content').addClass('is-visible');
            init();
        </script>   
	</body>
</html>
