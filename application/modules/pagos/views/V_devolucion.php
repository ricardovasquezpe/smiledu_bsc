<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>  
        <title>Devolucion | <?php echo NAME_MODULO_PAGOS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />        
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
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
                                <h2 class="mdl-card__title-text" id="titleTb">Devoluciones</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b" id="tableColaboradores">  
                            	<?php echo $tableColaborador;?>                              
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main"  onclick="abrirCerrarModal('modalFiltrarDevoluciones')" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>  
        
        <div class="modal fade" id="modalFiltrarDevoluciones" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row">		
    					       <div  class="col-sm-12 mdl-input-group mdl-input-group__only">
    					           <div class="mdl-select">
    					               <select id="selectSede" name="selectSede" class="form-group pickerButn" onchange="getColaboradoresBySede();" data-live-search="true">
    					                   <option value="">Selec. Sede</option>
    					                   <?php echo $optSede; ?>
                                       </select>
    					           </div>
    				           </div>
				           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
       	<div class="modal fade" id="modalDevolver" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text mdl-color-text--grey-500">Devoluci&oacute;n</h2>
                        </div>
                        <div class="mdl-card__supporting-text">    				
	                        <small id="colaborador"></small>
	                        <div class="col-sm-12">
	                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                <input class="mdl-textfield__input" type="text" id="montoD">
	                                <label class="mdl-textfield__label" for="montoD">Monto</label>
	                            </div>
	                        </div>
	                        <div class="col-sm-12 ">
    			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
    			                    <textarea class="mdl-textfield__input" type="text" id="observacionD" maxlength="200"></textarea>
                                    <label class="mdl-textfield__label" for="observacionD">Observaci&oacute;n</label>
                                    <span class="mdl-textfield__limit" for="observacionD" data-limit="200"></span>
                                </div>
    			            </div>
    					</div>
                        <div class="mdl-card__actions">                            
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="quitarCheck();">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnDev" onclick="estadoCambiar();">Aceptar</button>
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
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscaja.js"></script>
        
        <script type="text/javascript">
        returnPage();
        $('header .mdl-button__return').removeAttr('href');
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        	initButtonLoad('btnMF' );
        	
	        $('#tb_egresos').bootstrapTable({});
	        tableEventsCaja('tb_egresos');
	        initSearchTable();
	        $(document).ready(function(){
	    	    $('[data-toggle="tooltip"]').tooltip(); 
	    	    $('[data-toggle="popover"]').popover();
	    	    $('<button>').attr({
	    			'id'   	    : 'iconColaboradores',
	    			'class'	    : 'mdl-button mdl-js-button mdl-button--icon',
	    			'onclick'   : 'abrirModalPaquete(\'Opciones de tabla\')'
	    		}).appendTo('#tableColaboradores .fixed-table-toolbar .columns-right');

	    		$('<i>').attr({
	    			'class'	: 'mdi mdi-more_vert'
	    		}).appendTo('#iconColaboradores');
	        });
        </script>
	</body>
</html>