<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Propuesta mejora | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC;?>css/submenu.css">
    </head>
    
    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
    		<main class='mdl-layout__content is-visible'>
    		    <section>
                    <div class="mdl-content-cards">
                        <div class="img-search" id="cont_not_found_fab">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar el gr&aacute;fico.</p>
                        </div>
                        <div class="mdl-card" style="display: none;">
                            <div class="mdl-card__title">
    							<h2 class="mdl-card__title-text" id="titleTb">Propuestas</h2>
    						</div>
    						<div class="mdl-card__supporting-text br-b p-0 ">
    						    <div id="contTabPropuestas" class="form floating-label table_distance">
    						    <?php echo $tbPropuestas?>
    						    </div>
    						</div>
                        </div>
                    </div>
                </section>
    		</main>
    	</div>
    	
    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main" id="main_button" data-toggle="modal" onclick="abrirCerrarModal('modalFiltro');" data-target="#modalIngresos" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
    	
    	<div id="editarPropuestasMejora" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
        			<div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Propuestas</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div class="row">
        					   <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="divSelect"> 
        					       <div class="mdl-select">      					     
            					       <select class="form-control pickerButn" data-none-selected-text="Seleccione sus propuestas" id="selectPropM" data-live-search="true" multiple>
            					           
            						   </select>
        						   </div>	       
        					   </div>
        					   <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-l-15">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" maxlength="200" name="newPropM" id="newPropM" onkeyup="activarBtnAgregar()" value="">
                                       <label class="mdl-textfield__label" for="newPropM">Nueva Propuesta</label>
                                   </div>
                                   <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="envNuevaPropM();" id="nuevaPropM" name="nuevaPropM">
								            <i class="mdi mdi-add"></i>
								       </button>
            			           </div>
                               </div>
                           </div>
    					</div>
    					<div class="mdl-card__actions">
    					    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnEPM" onclick="linkComentarioPropuesta()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    									<select id="selectTipoEncuesta" name="selectTipoEncuesta" data-live-search="true" class="form-control pickerButn" onchange="getEncuestasByTipo()" data-none-selected-text="Seleccione un tipo de encuesta">
    										<option value="">Seleccione Tipo de encuesta</option>
    										<?php echo $tipo_encuesta?>
                                        </select>
    								</div> 
    							</div>
    							<div class="col-sm-12 mdl-input-group mdl-input-group__only">
    								<div class="mdl-select">
    									<select id="selectEncuesta" name="selectEncuesta" data-live-search="true" class="form-control pickerButn" onchange="getComentarioPropuestasMejoraByEncuesta()">
    										<option value="">Seleccione Encuesta</option>
    									</select>
    								</div>
    							</div>
    							<div class="p-r-0 p-l-0" id="contCombosNiveles"></div>
							</div>
						</div>
						<div class="mdl-card__actions">
						    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
        				    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnMF" data-dismiss="modal">Aceptar</button>
						</div>
					</div>
    			</div>
    		</div>
    	</div>
    				
        <form action="c_excel_plantilla" method="post" id="formExcel">
            <input type="hidden" name="id_encu" id="id_encu">
            <input type="hidden" name="cantEncuestados" id="cantEncuestados">
        </form>
        
    	<div class="offcanvas"></div>
            
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jspropuestaMejora.js"></script>
    
    	<script>
    	    $('#tb_comentarios').bootstrapTable({ });
        	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}

    	</script>
    </body>
</html>