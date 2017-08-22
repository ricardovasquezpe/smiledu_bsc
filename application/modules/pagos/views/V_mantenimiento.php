 <?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>    
        <title>Mantenimiento | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   
	    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		
    		<?php echo $menu ?>    	
    		
    		<main class='mdl-layout__content'>
                <section >
                    <div class="mdl-content-cards">      
                        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">                  
                        <div class="mdl-card">
                            <div class="mdl-card__title ">
                                <h2 class="mdl-card__title-text">Conceptos</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableConcept">                                    
                                <?php echo $tableConceptos;?>                                    
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalRegistrarConcepto()">
                                    <i class="mdi mdi-edit"></i>
                                </button>
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" >
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
        
        <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo Concepto</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="formularioRegistro"> 
					    	  <div class="col-sm-12 p-0">
					               <select id="selectTipo" name="selectTipo" class="form-group pickerButn" onchange="mostrarFormulario();" data-live-search="true">
					                   <option value="">Seleccionar Tipo</option>
					                   <?php echo isset($optTipo) ? $optTipo : null;?>
                                   </select>
					           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonGC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarConcepto();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       	      
       	 <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Concepto</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="formularioEditar"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonEC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " onclick="actualizarConcepto();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       	 
       	 <div class="modal fade" id="modalEditarTipo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Concepto</h2>
    					</div>
					    <div class="mdl-card__supporting-text" id="formularioEditarTipo"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " onclick="actualizarConceptoTipo();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       	      
       	<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar Concepto</h2>
    					</div>
					    <div class="mdl-card__supporting-text text-left">    				
                        	<small>&iquest;Desea eliminar este concepto?.</small>
    					</div>
    					<div class="mdl-card__actions">                            
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonEL" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal"onclick="eliminarConceptos();">Aceptar</button>
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
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsmantenimiento.js"></script>
  
        <script type="text/javascript">
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
	        $('#tb_concepto').bootstrapTable({});
	        initSearchTable();
	        tableEventsUpgradeMdlComponentsMDL('tb_concepto');
	        var porDefecto = "<?php echo $tipoGeneral?>";
	        var tipoBloq   = "<?php echo $tipoEspecifico?>";  
	        init();
        </script>
	</body>
</html>