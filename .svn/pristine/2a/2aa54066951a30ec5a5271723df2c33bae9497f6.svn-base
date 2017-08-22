<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title><?php echo $titleHeader?> | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">        
	</head>

	<body onload="screenLoader(timeInit);">	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
    		<main class='mdl-layout__content'>
                <section>                      
                    <div class="mdl-content-cards" >                    
                        <div class="mdl-card">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text"><?php echo $tittleTable?></h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b p-0">
                                <div id="lista_cronograma"><?php echo ($lista_cronograma=='') ? $this->session->userdata("lista_cronograma_sesion") : $lista_cronograma;?></div>
                            </div>
                            <div class="mdl-card__menu" id="botonesCuotas">
                                <?php echo $botones?>
                            </div>
                        </div>
                    </div>
                </section>
    		</main>	
        </div>
        
        <div class="modal fade" id="modalConceptoEditCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md"> 
                <div class="modal-content">                
                       <div class="mdl-card m-b-0">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Editar concepto de un Cronograma</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-t-0">  
                                <input type="hidden" id="conceptoeditar" name="conceptoeditar">
                                <div class="row-fluid">
                                    <div class="col-sm-6 mdl-input-group ">
                                        <div class="mdl-icon">
                                        	<i class="mdi mdi-description"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label no-transparent"> 
                                           <input class="mdl-textfield__input" type="text" id="desc_detalle" name="desc_detalle"  maxlength="50">
                                           <label class="mdl-textfield__label" for="desc_detalle">Descripci&oacute;n</label>
                                        </div>                                           
                                    </div>                                    
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
                                        	<button class="mdl-button mdl-js-button mdl-button--icon" id="inconfVencimiento">
				                                 <i class="mdi mdi-today"></i>
			                                </button>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                           <input class="mdl-textfield__input" id="ffvencimiento" name="ffvencimiento"  maxlength="10">                                            <label class="mdl-textfield__label" for="ffvencimiento">Fecha de vencimiento</label>
                                        </div>                                           
                                    </div>
                                    
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon">
                                        	<i class="mdi mdi-multiline_chart"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                           <input class="mdl-textfield__input" type="text" id="mora" name="mora">
                                           <label class="mdl-textfield__label" for="mora">% Mora</label>
                                        </div>                                          
                                    </div>                                    
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
                                        	<button class="mdl-button mdl-js-button mdl-button--icon" id="inconfdescuento">
				                                 <i class="mdi mdi-today"></i>
			                                </button>
                                            
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                           <input class="mdl-textfield__input" id="ffdescuento" name="ffdescuento"  maxlength="10">
                                           <label class="mdl-textfield__label" for="ffdescuento">Fecha de descuento</label>
                                        </div>                                        
                                    </div>

    					            <div class="col-sm-12 mdl-input-group text-center" id="radiosEdit" style="min-height: 0;"></div>
    					       </div>     
                         </div>
                         <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnEditarConcepto" onclick="editarConceptoCronograma()">Aceptar</button>
                         </div>
                         <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="left" data-original-title="Recuerda que debes definir tus montos en pensiones">
                                <i class="mdi mdi-error"></i>
                            </button>
                         </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalCalendarioEditCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm"> 
                <div class="modal-content">                
                       <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Concepto por mes</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0">  
                            <div class="row p-0 m-0"></div>     
                       </div>
                       <div class="mdl-card__actions">
                          <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnEditCalendario" onclick="registrarCuotasCalendario()">Guardar</button>
                       </div>
                    </div>
                </div>
            </div>     
        </div>

        <div class="modal fade" id="modalCrearDetalleCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo concepto del cronograma</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">    				
					       <div class="row-fluid">
					           <div class="col-md-6 mdl-input-group">
					                <?php if(_getSesion('id_tipo_crono_sess') == CRONO_CREATIVE_SUMMER || _getSesion('id_tipo_crono_sess') == CRONO_SPORT_SUMMER){?>
    					                <select id="selectPaquete" class="form-control pickerButn" data-live-search="true">
    					           		    <option>Seleccione un paquete</option>
    					           		    <?php echo $comboTipo?>
    					           		</select>
					           		<?php } else{?>
    					           		<div class="mdl-icon">
    					           			<i class="mid mdi-description"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label no-transparent"> 
                                           <input class="mdl-textfield__input" type="text" id="desc_detalle" name="desc_detalle">
                                           <label class="mdl-textfield__label" for="desc_detalle">Descripci&oacute;n</label>
                                        </div>                                           
                                    <?php }?>
                                </div>
					            <div class="col-md-6 mdl-input-group">
				                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfvencimiento">
				                            <i class="mdi mdi-today"></i>
			                            </button>
		                            </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fvencimiento" name="fvencimiento" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fvencimiento">Fecha de vencimiento</label>
                                    </div>
				                </div>
                                <div class="col-md-6 mdl-input-group">
                                    <div class="mdl-icon">
                                    	<i class="mdi mdi-multiline_chart"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="mora" name="mora">
                                       <label class="mdl-textfield__label" for="mora">% Mora</label>
                                    </div>                                          
                                </div>
                                <div class="col-md-6 mdl-input-group">
				                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfdescuento">
				                            <i class="mdi mdi-today"></i>
			                            </button>
		                            </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fdescuento" name="fdescuento" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fdescuento">Fecha de descuento</label>
                                    </div>
				                </div>
                               
					           <div class="col-sm-12 mdl-input-group text-center" id="radiosRegistrar" style="min-height: 0;">
					                                        
                               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnCrearCronograma" onclick="registrarConceptosCronograma()">Aceptar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="left" data-original-title="Recuerda que debes definir tus montos en pensiones">
                                <i class="mdi mdi-error"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEliminarConcCrono" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title ">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar el concepto del cronograma seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <small>Al eliminar este concepto no podr&aacute; recuperar la informaci&oacute;n registrada anteriormente.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnEmilinarConcepto" onclick="eliminarConceptoCronograma()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDefinirCuotas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title ">
    						<h2 class="mdl-card__title-text">&#191;Desea Definir las cuotas del cronograma?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">    				
					       <small>Al definir el cronograma ya no se podr&aacute; realizar ning&uacute;n cambio, ya que esta acci&oacute;n es una vez al a&ntilde;o.</small>
    					</div>
    					<div id="radiobutton">
    					   <?php echo $radios?>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnDefinirCoutas" onclick="definirCuotas()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscronograma.js"></script>
        
        <script type="text/javascript">
            init();           
            $( document ).ready(function(){
            	returnPage();
            	initButtonCalendarDays('fdescuento','ffdescuento');
                initMaskInputs('fvencimiento', 'fdescuento', 'ffvencimiento','ffdescuento');
                //initButtonLoad('btnDefinirCoutas', 'btnEmilinarConcepto', 'btnCrearCronograma', 'btnEditCalendario', 'btnEditarConcepto');
                $('#iconfvencimiento').click(function(){
                	initButtonCalendarMaxDate( 'fvencimiento', 'fdescuento');
                });
                $('#inconfdescuento').click(function(){
                    initButtonCalendarMaxDate('ffvencimiento','ffdescuento');
                });
                
            	$('[data-toggle="tooltip"]').tooltip();
            });

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}

        </script>
	</body>
</html>