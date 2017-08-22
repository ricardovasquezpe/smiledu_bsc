<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Movimiento | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">     
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/movimiento.css">        
	</head>

	<body>	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
    		<main class='mdl-layout__content is-visible' onscroll="onScrollEvent(this);">   
                <section class="mdl-layout__tab-panel" id="tab-1">
                    <div class="mdl-content-cards">
                        <div class="m-b-10" id="filtroMovimiento" style="display:none;">
	                       <ol class="breadcrumb">
                				<li class="active">Filtro:</li>
                				<li id="laelSede" style="display:none"><?php echo $sedeDesc;?></li>
                				<li id="laelNivel" style="display:none"></li>
                				<li id="laelGrado" style="display:none"></li>
                				<li id="laelAula" style="display:none"></li>
            				</ol>
                        </div>
                        <div id="cardsIngreso">
                        </div>
                        <div class="img-search" id="cont_img_search_alum">
            	            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar tus ingresos.</p>
            			</div>
                        <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </button>   
                        </div>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="tab-2">
                    <div class="mdl-content-cards">
                        <div class="m-b-10" id="filtroEgresos" style="display:none;">
	                       <ol class="breadcrumb">
                				<li class="active">Filtro:</li>
                				<li id="laelArea" style="display:none"></li>
            				</ol>
                        </div>
                        <div id="cardsEgreso">
                        </div>
                        <div class="img-search" id="cont_img_search_col">
            	            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar tus egresos.</p>
            			</div>
                        <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </button>   
                        </div>
                    </div>                    
                </section>                
    		</main>	    		
        </div>        
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="cronograma_pago_fg">
                <button class="mfb-component__button--main" id="main_button" data-toggle="modal" data-target="#modalIngresos" data-mfb-label="Filtrar">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
        
        <div class="modal fade" id="modalEgresos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a href="#colaboradores" class="mdl-tabs__tab is-active p-l-10 p-r-10">Colaboradores</a>
					               <a href="#proveedores" class="mdl-tabs__tab p-l-10 p-r-10">Proveedores</a>
					           </div>
					           <div class="mdl-tabs__panel is-active" id="colaboradores">
					               <div class="row-fluid">
        					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__text-btn">					               
            					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="searchAula" name="searchAula" onkeyup="activeDesactivesearchAula('searchAula','btnBuscarAula')" onchange="getColaboradoresByFiltro()">
                                               <label class="mdl-textfield__label" for="searchAula">Busca a tus colaboradores</label>
                                           </div>
                                           <div class="mdl-btn">
                            			       <button class="mdl-button mdl-js-button mdl-button--icon" disabled id="btnBuscarAula" onclick="getColaboradoresByFiltro()">
                							       <i class="mdi mdi-search"></i>
                						       </button>
                            			   </div>
            					       </div>
            					       <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
            					           <div class="mdl-select">
                					           <select class="form-group pickerButn" id="selectArea" name="selectArea" onchange="getColaboradoresByFiltro()" data-live-search="true">
                					               <option value="">Selec. &aacute;rea</option>
                					               <?php echo $optAreas;?>
                                               </select>
                                           </div>
                                       </div>
                                       <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="botonFE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal" onclick="getColaboradoresByFiltro()">Aceptar</button>
                                       </div>
        					       </div>
					           </div>
					           
					           <div class="mdl-tabs__panel" id="proveedores">
					               <div class="row-fluid">
					                   <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__text-btn">					               
            					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="searchProveedor" name="searchProveedor" onkeyup="activeDesactivesearchAula('searchProveedor','btnBuscarProveedor')" onchange="getProveedoresByFiltro()">
                                               <label class="mdl-textfield__label" for="searchProveedor">Busca a tus proveedores</label>
                                           </div>
                                           <div class="mdl-btn">
                            			       <button class="mdl-button mdl-js-button mdl-button--icon" disabled id="btnBuscarProveedor" onclick="getProveedoresByFiltro()">
                							       <i class="mdi mdi-search"></i>
                						       </button>
                            			   </div>
            					       </div>
            					       <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="botonEP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal" onclick="getProveedoresByFiltro()">Aceptar</button>
                                       </div>
        					       </div>
					           </div>
					       </div>
					       
					   </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalIngresos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content ">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtro</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
					       <div class="row-fluid">
    					       <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__text-btn">					               
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="searchInput" name="searchInput" onkeyup="activeDesactivesearchInput()" onchange="getAllAlumnosByFiltro()">
                                       <label class="mdl-textfield__label" for="searchInput2">Busca a tus estudiantes</label>
                                   </div>
                                    <div class="mdl-btn">
                			           <button class="mdl-button mdl-js-button mdl-button--icon" disabled id="btnBuscarLista" onclick = "">
    								       <i class="mdi mdi-search"></i>
    							       </button>
                			       </div>
					           </div>
					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectSede" name="selectSede" class="form-group pickerButn" onchange="getNivelesBySede();" data-live-search="true" >
    					                   <option value="">Selec. Sede</option>
      					                   <?php echo $optSede; ?>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectNivel" name="selectNivel" class="form-group pickerButn" onchange="getGradosByNivel();" data-live-search="true" >
    					                   <option value="">Selec. nivel</option>
    					                   <?php echo $optNivel?>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectGrado" name="selectGrado" class="form-group pickerButn" onchange="getAulasByNivelSede();" data-live-search="true" >
    					                   <option value="">Selec. grado</option>
                                       </select>
                                   </div>
					           </div>
					           <div class="col-sm-12 p-0 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
    					               <select id="selectAula" name="selectAula" class="form-group pickerButn" onchange="getAlumnosByAula();" data-live-search="true" >
    					                   <option value="">Selec. aula</option>
                                       </select>
                                   </div>
 					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>
                            <button id="botonFI" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal" onclick="getAllAlumnosByFiltro();">Aceptar</button>
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
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>  	
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsmovimientos.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jshammer__movimientos.js"></script>
        <script type="text/javascript">
            init();
        </script>  
        <script type="text/javascript">
            var current_tab = '<?php echo $tabActive;?>';
//             $('#searchInput').keyup(function(){
//     			if(current_tab == 'tab-1'){
//     				getAllAlumnosByFiltro();
//         		}else if(current_tab == 'tab-2'){
//         			getColaboradoresByFiltro();
//                 }
//     	    });
            var text = (current_tab == 'tab-1') ? 'Busca tus estudiantes' : 'Busca a tus colaboradores';
    	    var search = $('#searchInput').val();
    	    if(current_tab == 'tab-1'){
    	    	$('#main_button').attr('data-target' , '#modalIngresos');
    	    	if(search != null){
    	    		getAllAlumnosByFiltro();
        	    }
        	} else{
        		$('#main_button').attr('data-target' , '#modalEgresos');
        		if(search != null){
        			getColaboradoresByFiltro();
        		}
        	} 
//     	    $('#lblsearchInput').text(text);
            $('#'+current_tab).addClass('is-active');
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        </script>
	</body>
</html>