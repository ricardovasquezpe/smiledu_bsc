 <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>     
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>    
        <title>Caja | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>"/>
                
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/movimiento.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/caja.css">
        
        <style>
            #tb_caja tbody tr:nth-child(2){
	           background-color: #E8F5E9;
            }
            
            #tb_caja tbody tr:nth-child(3){
	           background-color: #FFEBEE;
            }
            
            .detalles{
                width: 850px;
                max-width: 100%;
                margin: auto;
            }
            
            .form-wizard.form-wizard-horizontal .nav .step{
                display: inline-block;
                line-height: 28px;
                width: 40px;
                height: 40px;
                border-radius: 999px;
                border: 4px solid #e5e6e6;
                background: #ffffff;
                color: #e5e6e6;
                font-size: 20px;
            }
            
            .form-wizard.form-wizard-horizontal .nav li.active a, .form-wizard.form-wizard-horizontal .nav li:hover a{
	            padding-bottom:5px;
            }
            
            @media (max-width: 860px){
            	.mdl-wizard .mdl-card__title{
	                width:initial !important;
            	}
	           .mdl-wizard .mdl-card__supporting-text{
	               width:initial !important;
	           }
	           
	           .mdl-wizard .form-wizard{
	               width: initial !important;
	           }
	           
	           .mdl-wizard .form-wizard.form-wizard-horizontal .form-wizard-nav .nav-justified>li{
	               width:49% !important;
	           }
	           .mdl-wizard .progress {
                   right: 0px !important;
                   top: 0px !important;
                   bottom: 20px !important;
                   height: 5px !important;
	           	   width:50% !important;
               }
             }
             
        </style>
        
	</head>

	<body onload="screenLoader(timeInit);">
	   
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    	
    		<main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active" id="miCaja">
                    <div class="mdl-content-cards">
                        <?php if($rolSession == 'true'){?>
                            <div class="mdl-card mdl-wizard detalles">
                                <div class="mdl-card__title ">
                                    <h2 id="cabeceraDetalle" class="mdl-card__title-text"><?php echo isset($fechaFiltro) ? ('Detalles del '.$fechaFiltro ): 'Detalles'?></h2>
                                </div>
                                <div class="mdl-card__menu" style="z-index:99999">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="option_1">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="option_1">
                                        <li class="mdl-menu__item" onclick="descargarPdfCaja();">
                                            <i class="mdi mdi-file_download"></i> Descargar PDF
                                        </li>
                                    </ul>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b" id="tbGeneral">
                                    <?php echo $tbCaja;?>
                                </div>
                                <div id="rootwizard1" class="form-wizard form-wizard-horizontal mdl-wizard m-b-15">
                                    <form class="form floating-label">
    								    <div class="form-wizard-nav">
    									    <div class="progress">
    									        <div class="progress-bar progress-bar-primary" id="wizardCaja" style="width: <?php echo $width?>"></div>
    									    </div>
    									    <ul class="nav nav-justified nav-pills">
    									  		<li id="tab1" class="<?php echo (isset($completeApert)?  $completeApert : null);?>" data-estado="SI">
    									  		    <a data-toggle="tab" aria-expanded="true" 
    									  		        <?php if($rolSession == 'true'){?>
    									  		            onclick="openModalAperturar('modaAperturarCaja')"
    									  		        <?php }?>>
    									  		        <span class="step"></span>
    									  		        <span class="title">Aperturada</span>
    									  		    </a>
    									  		</li> 
    									      	<li id="tab2" class="<?php echo ($completeCerrada);?>" data-estado="SI">
        									  	    <a data-toggle="tab" aria-expanded="false" 
        									  	        <?php if($rolSession == 'true'){?>
        									  	            onclick="openModalCerrar('modaAperturarCaja')"
        									  	        <?php }?>>
        									  	        <span class="step"></span>
        									  	        <span class="title">Cerrada</span>
        									  	    </a>
    									  	    </li>
    									    </ul>
    									</div>
    								</form>
    							</div>
                            </div>
                        <?php } else { echo isset($cardSecretaria) ? $cardSecretaria : null;}?>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="otraCaja">
                    <div class="mdl-content-cards">
                        <?php echo $cajasAsignadas;?>
                    </div>
                </section>
            </main>
        </div>
        
        <?php  if($rolSession == 'true') {?>
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" id="main_button">
                    <i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
                </button>
                <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalFiltrarCaja" data-mfb-label="Filtrar Caja">
                    <i class="mfb-component__main-icon--active mdi mdi-filter_list" style="top:8px"></i>
                </button>
                <ul class="mfb-component__list">
                   <?php if(_getSesion(PAGOS_ROL_SESS) == ID_ROL_SECRETARIA){?>
                       <li>
                           <button class="mfb-component__button--child " onclick="redirectDevoluciones();" id="main_button" data-toggle="modal" data-mfb-label="Devoluciones">
                               <i class="mfb-component__child-icon mdi mdi-strikethrough_s" style="top:7px"></i>
                           </button>
                       </li>
                       <li>
                           <button class="mfb-component__button--child " onclick="redirectMisIncidencias('<?php echo $secretaria?>');" id="Incidencias_button" data-toggle="modal" data-mfb-label="Mis Incidencias">
                               <i class="mfb-component__child-icon mdi mdi-add"></i>
                           </button>
                       </li>
                   <?php } else {?>
                       <li>
                           <button class="mfb-component__button--child " onclick="redirectIncidencias('<?php echo $secretaria?>');" id="main_button" data-toggle="modal" data-mfb-label="Incidencias">
                               <i class="mfb-component__child-icon mdi mdi-add"></i>
                           </button>
                       </li>
                   <?php }?>
                </ul>
            </li>
        </ul>
        <?php }?>
        
        <div class="modal fade" id="modalFiltrarCaja" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-10 p-l-0"> 
                            <div class="col-sm-12 mdl-input-group">
                                <div class="mdl-icon mdl-icon__button">
                                    <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                        <i class="mdi mdi-today"></i>
                                    </button>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fechaInicio" name="fechaInicio" maxlength="10"/>
                                    <label class="mdl-textfield__label" for="fechaInicio">Fecha Inicio</label>
                                </div>                                                  
                            </div>                             
                            <div class="col-sm-12 mdl-input-group">
                                <div class="mdl-icon mdl-icon__button">
                                    <button class="mdl-button mdl-js-button mdl-button--icon">				                            
                                        <i class="mdi mdi-today"></i>
                                    </button>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fechaFin" name="fechaFin" maxlength="10"/>
                                    <label class="mdl-textfield__label" for="fechaFin">Fecha Fin</label>
                                </div>                                                  
                            </div>  	
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonFC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="filtroCajaByFechas()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(_getSesion(PAGOS_ROL_SESS) == ID_ROL_SECRETARIA){?>
        <div class="modal fade" id="modaAperturarCaja" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Abrir Caja</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small>Se aperturar&aacute; tu caja con el &uacute;ltimo monto que tuviste. &#191;Est&aacute;s seguro&#63;</small>
                            <?php if($flg_cerrar == APERTURADA){?>
                                <br>
                                <small>Tu &uacute;ltima caja no fue cerrada, la cerraremos antes de aperturar la de hoy.</small>
                            <?php }?>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnApC" onclick="aperturarCaja()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <?php }?>
        
        <?php if(_getSesion(PAGOS_ROL_SESS) == ID_ROL_SECRETARIA){?>
        <div class="modal fade" id="modalCerrarCaja" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cerrar Caja</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
					       <div class="row">
					           <div class="mdl-input-group mdl-input-group__only" style="min-height: 1px">   				
                                    <small>Selecciona un tipo de cerrado y se cerrar&aacute; tu caja con el monto que tienes actualmente. &#191;Est&aacute;s seguro&#63;</small>
                                </div>
                                <div id="contTipoCerrarCaja" class="mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectTipo" name="selectTipo" class="form-group pickerButn" onchange="selectTipoCerrado('#selectTipo');" data-live-search="true">
                                            <option value="">Seleccione un tipo</option>
                                            <?php echo $optCerrar;?>
                                        </select>
                                    </div>
                                </div>
                                <div id="contentFiltro" class="mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectSecretaria" name="selectSecretaria" disabled class="form-group pickerButn" data-live-search="true">
                                            <option value="">Selecciona una secretaria(o)</option>
                                            <?php echo $optSecretarias;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mdl-input-group mdl-input-group__only" style="min-height: 1px">
                                    <small>Selecciona una incidencia</small>
                                </div>
                                <div id="contentIncidencia" class="mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectTipoIncidencia" name="selectTipoIncidencia" class="form-group pickerButn" data-live-search="true">
                                            <option value="">Seleccione una incidencia</option>
                                            <?php echo $optInci;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="montoIncidencia" name="montoIncidencia">
                                        <label class="mdl-textfield__label" for="montoIncidencia">Monto</label>
                                    </div>
                                </div>
                                <div class="mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="observacionInci" name="observacionInci">
                                        <label class="mdl-textfield__label" for="montoIncidencia">Observaci&oacute;n</label>
                                    </div>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="cerrarCaja()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <?php }?>
        
        <div class="modal fade" id="modalAceptarPersonal" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Confirmar Solicitud</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
                            <small id="infoSolicitar"></small>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="enviarSolicitud()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalIngresos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" style="color: #009688">Ingresos</h2>
    					</div>
                        <div class="mdl-card__supporting-text p-0 br-b" id="tableIngresos">
                            <?php echo $tableIngresos;?>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEgresos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" style="color: #F44336">Egresos</h2>
    					</div>
                        <div class="mdl-card__supporting-text p-0 br-b" id="tableEgresos">   
                            <div class="bootstrap-table">                                     
                                <?php echo $tableEgresos;?>
                            </div>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <form action="C_caja/createPdfDocument" name="formPdfDownload" id="formPdfDownload" method="post">
            <input type="hidden" id="fechaInicioForm" name="fechaInicioForm">
            <input type="hidden" id="fechaFinForm" name="fechaFinForm">
        </form>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>nanoscroller/jquery.nanoscroller.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>    	
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscaja.js"></script>
        
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
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js" charset="UTF-8"></script>
        
        <script type="text/javascript">
            var width         = '<?php echo isset($width) ? $width : null;?>';
            var flgAperturada = '<?php echo isset($completeApert) ? $completeApert : null;?>';
            var flgCerrada    = '<?php echo isset($completeCerrada) ? $completeCerrada : null ;?>';
	        tableEventsCaja('tb_colaborador');
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}          
            var hoy = getFechaHoy_dd_mm_yyyy();
        	$("#fechaInicio").val(hoy);
        	$("#fechaFin").val(hoy);
        	$("#tab2").bind('click',false);
        	init();
        </script>
	</body>
</html>