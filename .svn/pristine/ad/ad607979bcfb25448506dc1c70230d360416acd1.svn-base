<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head> 
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>     
        <title>Migraci&oacute;n | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />        
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/movimiento.css">
    </head>

	<body onload="screenLoader(timeInit);">
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
    		<main class='mdl-layout__content'>
    		
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Bancos</h2>
                            </div>
                           <div class="bootstrap-table">
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableBancos">    
                                                            
                                    <?php echo $tbBancos;?>  
                                </div>                                      
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <input type="file" id="userfile" name="userfile" style="display: none;">
                <section class="mdl-layout__tab-panel" id="tab-2">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Empresas</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableEmpresas">                                    
                                <?php echo $tbEmpresas;?>                                    
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                
    		</main>	
    		
        </div>
        
        <div class="modal fade" id="modalMigrarDatos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card m-b-0" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="textAccion"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small id="textRef"></small>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonExport" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="migrarDatosBanco();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal fade" id="modalImportarDatos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card m-b-0" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="textAccionI"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
                            <small id="textRefI"></small>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonImport" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="importarTxt();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        <div class="modal fade" id="modalTipoExportacionCont" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Exportar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0">
					           <div class="col-sm-12">
					               <select id="selectTipoExport" name="selectTipoExport" class="form-group pickerButn" data-live-search="true">
					                   <option value="-1">Selec. tipo</option>
					                   <option value="0">Ventas</option>
					                   <option value="1">Asientos o cancelaci&oacute;n</option>
                                   </select>
					           </div>
					           <div class="col-sm-12">
					               <select id="selectYear" name="selectYear" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Selec. A&ntilde;o</option>
					                   <?php echo $optYears;?>
                                   </select>
					           </div>
					           <div class="col-sm-12">
					               <select id="selectMes" name="selectMes" class="form-group pickerButn" data-live-search="true">
					                   <option value="">Selec. Mes</option>
					                   <?php echo $optMeses;?>
                                   </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="generateTxtFileByEmpresa();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalPreviewMigracion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card m-b-0" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Deseas importar estos datos?</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="tablePreview">    				
                            
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonImport" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="confirmarMigracion();">ACEPTAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <form action="c_txt_download" method="post" id="formExcel">
            <input type="hidden" id="filename" name="filename">
        </form>
        
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
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
    	<script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsmigracion.js"></script>
        
        <script type="text/javascript">
    		init();
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        	$('.mdl-layout__tab-panel').removeClass('is-active');
        	var tabActive = '<?php echo $tabActivo;?>';
        	$('#'+tabActive).addClass('is-active');
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
            $('.tree').treegrid({
				initialState: 'collapsed',
                expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
                expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
            });
        </script>
	</body>
</html>