<!DOCTYPE html>
<html lang="en">
    <head>	    
	    <title>Evaluar | <?php echo NAME_MODULO_SPED;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>dropzone/dropzone-theme23ba.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>imageGallery/css/blueimp-gallery.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED;?>css/submenu.css">
        
        <style type="text/css">
            #modalDocente .mdl-textfield__label{
            	top: 20px
            }
        </style>
    </head>

    <body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
            
                <?php echo $menu;?>
                
                <main class='mdl-layout__content is-visible'>
            		<section>
            			<div class="row-fluid">
            				<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
            					<div class="mdl-card">
            						<div class="mdl-card__title">
            							<h2 class="mdl-card__title-text">R&uacute;brica</h2>
            						</div>
            						<div class="mdl-card__supporting-text br-b">
            							<div id="contRubrica" class="form floating-label table_distance">
                                            <?php echo $tbRubrica;?>
                                        </div>
            							<img id="img-out" style="width: 100%">
            						</div>
            						<div class="mdl-card__menu">
            							<h2 class="mdl-card__title-text custom-toolbar mdl-color-text--grey-500" style="display: block;">
            								Nota final:&nbsp;
            								<strong class="<?php echo $colorGeneral?>" id="notaFinal"> <?php echo $notaFinal;?></strong>
            							</h2>
            						</div>
            					</div>
            				</div>
            			</div>
            		</section>
    		  </main>
    	</div>
    
    	<div class="offcanvas"></div>
            
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main boton_add">
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <?php if($fabFinalizar != null) { ?>
                    <button class="mfb-component__button--main" data-mfb-label="Finalizar" onclick="modal('modalTerminarFicha');">
                        <i class="mfb-component__main-icon--active mdi mdi-send"></i>
                    </button>
                <?php } else { ?>
                    <button class="mfb-component__button--main">
                        <i class="mfb-component__main-icon--active mdi mdi-close"></i>
                    </button>
                <?php } ?>
                <ul class="mfb-component__list">
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Tema" onclick="getOpenTema()">
                            <i class="mfb-component__child-icon mdi mdi-edit"></i>
                       </button>
                   </li>  
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Subir Evidencia" onclick="abrirModalSubirEvidencias()">
                            <i class="mfb-component__child-icon mdi mdi-file_upload"></i>
                       </button>
                   </li>    
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Ver Evidencia" onclick="modalVerEvidencias()">
                            <i class="mfb-component__child-icon mdi mdi-remove_red_eye"></i>
                       </button>
                   </li> 
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Docente" onclick="verDocente()">
                            <i class="mfb-component__child-icon mdi mdi-school"></i>
                       </button>
                   </li>                    
                </ul>    
            </li>
        </ul>
    
    
    	<!-- MODAL TERMINAR FICHA -->
    	<div class="modal fade" id="modalTerminarFicha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Ya asign&oacute; todos los valores. &#191;Desea terminar la evaluaci&oacute;n?</h2>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="grabarPuntajeFinal();">Finalizar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL DOCENTE -->
    	<div class="modal fade" id="modalDocente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="myModalLabelTitle">Datos</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<form class="form" role="form">
    							<div class="row-fluid">
    								<div class="col-xs-12 text-center">
    									<img id="fotoDocente" class="img-circle" width="150">
    								</div>
    								<div class="col-xs-12 p-0">
    								    <div class="mdl-textfield mdl-js-textfield">
    								        <input class="mdl-textfield__input" type="text" id="nomb_docente" disabled>
    								        <label class="mdl-textfield__label" for="nomb_docente">Docente</label>
								        </div>
							        </div>
    								<div class="col-xs-12 p-0">
    								    <div class="mdl-textfield mdl-js-textfield">
    								        <input class="mdl-textfield__input" type="text" id="curso" disabled>
    								        <label class="mdl-textfield__label" for="curso">Curso</label>
								        </div>
							        </div>
    								<div class="col-xs-12 p-0">
    								    <div class="mdl-textfield mdl-js-textfield">
    								        <input class="mdl-textfield__input" type="text" id="aula" disabled>
    								        <label class="mdl-textfield__label" for="aula">Aula</label>
								        </div>
							        </div>
    								<div class="col-xs-12 p-0">
    								    <div class="mdl-textfield mdl-js-textfield">
    								        <input class="mdl-textfield__input" type="text" id="fecha" disabled>
    								        <label class="mdl-textfield__label" for="fecha">Fecha</label>
								        </div>
							        </div>
    							</div>
    						</form>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL VER EVIDENCIA -->
    	<div class="modal fade" id="modalVerEvidencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" >Evidencias</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<div class="row-fluid">
    							<div id="contenidoImgRut"></div>
    						</div>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL CARGAR EVIDENCIAS -->
    	<div class="modal fade" id="modalCargarEvidencias" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Subir evidencias</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<div class="row-fluid">
    							<div id="dropzone" class="dropzone"></div>
    						</div>
    					</div>
    					<div class="mdl-card__actions ">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="cerrarModalEvidencias();" id="btnAddNewEvidencias" name="btnAddNewEvid">Subir</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL TEMA -->
    	<div class="modal fade" id="modalTema" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Tema</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<form class="form" role="form">
    							<div class="mdl-textfield mdl-js-textfield">
    								<textarea class="mdl-textfield__input" type="text" rows="3" name="txtTema" id="txtTema"></textarea>
    								<label class="mdl-textfield__label" for="txtTema">Escriba aqu&iacute;</label>
    							</div>
    						</form>
    					</div>
    					<div class="mdl-card__actions text-right p-r-20 p-l-20">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="grabarTema();">Grabar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL VALORES -->
    	<div class="modal fade" id="modalValores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-lg">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Valores</h2>
    					</div>
    					<div class="mdl-card__menu">
    						<div style="position: absolute; right: 40px; top: 2.5px; color: #757575">Aplicar</div>
    						<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch_aplicar">
    						    <span class="mdl-switch__label"></span>
    							<input type="checkbox" id="switch_aplicar" class="mdl-switch__input" onchange="reactivarSubFactor($(this));">
    						</label>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
    						<input type="hidden" id="hidIdiv">
    						<input type="hidden" id="hidIdivCrit">
    						<input type="hidden" id="hidIndi">
    						<input type="hidden" id="hidDivCrit">
    						<div id="contDivValores"></div>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarValor();">Grabar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<!-- MODAL VALORES -->
    	<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea borrar el archivo?</h2>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="borrarArchivo();">Confirmar</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    
    	<div id="blueimp-gallery" class="blueimp-gallery">
    		<div class="slides"></div>
    		<h3 class="title" onclick="openConfirmBorrar();"></h3>
    		<a class="prev"></a>
    		<a class="next"></a>
    		<a class="close">x</a>
    		<a class="play-pause"></a>
    		<ol class="indicator"></ol>
    	</div>
    	<input type="hidden" id="idEvidencia">
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>
    
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>dropzone/dropzone.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>imageGallery/js/blueimp-gallery.min.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsevaluar.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>pace/pace.min.js"></script>
        
    	<script>
        	marcarNodo("Evaluar");
	            $(document).ready(function() {
            	initEvaluar();
            });
            <?php if($fabFinalizar != null) { ?>
                $('#menuAtag').find('.mdi-close').remove();
            <?php }?>
        </script>
    </body>
</html>