<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script> 
		<title>Comparativas | <?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/submenu.css">
        
	</head>

   <body onload="screenLoader(timeInit);">   
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
        
    		<?php echo $menu ?>
    		          
            <main class='mdl-layout__content'>  
                 <section>
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Comparativas</h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b p-0" id="contTbComparativas">
                                <?php echo $tablaComparativas?>
                            </div>
                        </div>
                    </div>
                </section>
             </main>
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn">
                 <button class="mfb-component__button--main" data-mfb-label="Agregar comparativa" onclick="abrirModalAddComparativas()">
                     <i class="mfb-component__main-icon--resting mdi mdi-edit"></i>
                     <i class="mfb-component__main-icon--active  mdi mdi-edit"></i>
                 </button>
             </li>
        </ul>
                    
        <div class="offcanvas"></div>
        
        <!-- Modals -->
        <div class="modal fade" id="modalAddComparativas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar Comparativa</h2>
    					</div>
    					<form id=formEditComparativa method="post" class="m-0 p-0">
                            <input type="hidden" id="idIndDeta">
    					    <div class="mdl-card__supporting-text">    				
    					       <div class="row p-0 m-0">
    					           <div class="col-sm-12 p-0 m-b-20">
    					               <select class="form-control" id="selectTipoModal" name="selectTipoModal"  onchange="onChangeComboComparativas();" data-live-search="true">
                			                <option value="">Selec. Tipo de Comparativa</option>
                                            <?php echo $tipoComparativa?>
                			           </select>
    					           </div>
    					           
    					           <div class="col-sm-12 p-0 m-b-15" id="contComboSelectIndi">
    					               <select id="selectIndi" name="selectIndi" onchange="capturarValorNumerico();" data-live-search="true" class="form-control">
                                           <option value="">Selec. Indicador</option>
                                       </select>
    					           </div>
    					           
    					           <div class="col-sm-12 p-0" id="contDescComparativa">
    					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					                   <input class="mdl-textfield__input" type="text" id="comparativaModal" name="comparativaModal" onchange="onChangeComparativa();">
                			               <label class="mdl-textfield__label" for="comparativaModal">Comparativa</label>
                			           </div>
    					           </div>
    					           
    					           <div class="col-sm-12 p-0">
    					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					                   <input class="mdl-textfield__input" type="text" id="valorModal" name="valorModal">
                			               <label class="mdl-textfield__label" for="valorModal">Valor</label>
                			           </div>
    					           </div>
    					           
    					           <div class="col-sm-12 p-0" id="contYearComparativa">
    					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    					                   <input class="mdl-textfield__input" type="text" id="yearModal" name="yearModal" disabled="true">
                			               <label class="mdl-textfield__label" for="yearModal">A&ntilde;o</label>
                			           </div>
    					           </div>
    					       </div>
        					</div>
        					<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="agregarBtn">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>     
        </div>
        
		<form action="c_main/logout" name="logout" id="logout" method="post"></form>

		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>       
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>        
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC?>js/jscomparativa.js"></script>
        
        <script type="text/javascript">
        initComparativa();
    	initSearchTableNew();
        marcarNodo("Comparativas");
        initValidComparativa();
        </script>
	</body>
</html>