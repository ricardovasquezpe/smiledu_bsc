<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
		<title>R&uacute;brica | <?php echo NAME_MODULO_SPED?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED?>" />
	    
	    <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED;?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);"> 	
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text custom-toolbar">Factores</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div id="contTabRubricas" class="form floating-label table_distance">
                                            <?php echo $tbFactores; ?>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <h2 class="mdl-card__title-text custom-toolbar" style="display: block;">Peso:&nbsp;<strong class="<?php echo isset($pesoTotalCSS) ? $pesoTotalCSS : null;?>" id="pesoTotal"><?php echo isset($pesoTotal) ? $pesoTotal : 0;?></strong></h2>
                                    </div>
                                </div>   
                            </div>
                            <div class="col-sm-6">                         
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text custom-toolbar">SubFactores</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div class="img-search" id="img_not_data">
                                            <img src="<?php echo RUTA_IMG;?>smiledu_faces/not_data_found.png">
                                            <p>Primero debes seleccionar un factor.</p>
                                        </div>
                                        <div id="contTabSubFactor" class="form floating-label table_distance">
                                            <?php echo $tbSubFactores; ?>
                                        </div>
                                    </div>
                                    <?php if($flg_editar) {?>
                                        <div class="mdl-card__menu">
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="abrirModalCrearIndicador()">
                                                <i class="mdi mdi-add"></i>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
    	</div>

    	<div class="offcanvas"></div>

        <?php if($flg_editar) {?>
            <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
                <li class="mfb-component__wrap mdl-only-btn__animation">
                    <button class="mfb-component__button--main" >
                        <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                    </button>
                    <button class="mfb-component__button--main" data-mfb-label="Factores" onclick="abrirModalCrearCriterio()">
                        <i class="mfb-component__main-icon--active mdi mdi-edit"></i>
                    </button>
                    <ul class="mfb-component__list">
                       <li>
                           <button class="mfb-component__button--child" data-mfb-label="Niveles" onclick="abrirModalComboValor()">
                               <i class="mfb-component__child-icon mdi mdi-list"></i>
                           </button>
                       </li>                         
                    </ul>    
                </li>
            </ul>
        <?php } ?>
        
        <!-- INICIO MODAL ASIGNAR INDICADORES -->
        <div class="modal fade backModal" id="modalAsignarIndicadores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asigna indicadores</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">   
                            <div id="contTbIndicadorModal" class="form floating-label table_distance">
			                </div>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="capturarIndicadores();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INICIO MODAL ASIGNAR VALORES -->
        <div class="modal fade backModal" id="modalAsignarValores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asigna sus valores</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-r-0 p-l-0"> 
                            <div id="contTbValModal" class="form floating-label table_distance">
                            </div>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="capturarValores($(this));">Guardar</button>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
        
        <!-- INICIO MODAL NUEVO CRITERIO -->
        <div class="modal fade backModal" id="modalCrearCriterio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Nuevo Factor</h2>
                        </div>                        
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" maxlength="300" name="descripcionCriterio" id="descripcionCriterio" onkeyup="enableRegistrar('descripcionCriterio', 'newFactor')">
                                        <label class="mdl-textfield__label" for="descripcionCriterio">Descripci&oacute;n</label>
                                    </div>
                                    <div class="mdl-btn">
                                        <button class="mdl-button mdl-js-button" data-upgraded=",MaterialButton" onclick="nuevoCriterio();" id="newFactor" name="newFactor">
                                            <i class="mdi mdi-add"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="contTbFactorAsignar" class="form floating-label table_distance"></div>
                        </div>
                        <div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="asignarFactoresARubrica();">Guardar</button>
                        </div>
                        <div class="mdl-card__menu ">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- INICIO MODAL NUEVO INDICADOR -->
        <div class="modal fade backModal" id="modalCrearIndicador" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="titleTb">Nuevo SubFactor</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" maxlength="300" name="descripcionIndicador" id="descripcionIndicador" onkeyup="enableRegistrar('descripcionIndicador', 'newIndi')">
                                        <label class="mdl-textfield__label" for="descripcionIndicador">Descripci&oacute;n</label>
                                    </div>                                    
                                    <div class="mdl-btn">
                                        <button class="mdl-button mdl-js-button" data-upgraded=",MaterialButton" onclick="nuevoIndicador();" id="newIndi" name="newIndi">
                                            <i class="mdi mdi-add"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="contTbSFAsignar" class="form floating-label table_distance"></div>
                        </div>
                    	<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="asignarSubFactoresAFactor();">Guardar</button>
                        </div>
                        <div class="mdl-card__menu ">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- INICIO MODAL NUEVO VALOR -->
        <div class="modal fade backModal" id="modalValores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                        	<h2 class="mdl-card__title-text ">Niveles</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select">
                                        <select id="selectValor" name="selectValor" data-live-search="true" class="form-control pickerButn">
                                            <option value="">Selec. Cantidad de niveles</option>
                                            <?php echo $optEvmvalo;?>
                                        </select>                                    
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarValor();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
              
        <!-- INICIO MODAL ELIMINAR SUBFACTOR -->
        <div class="modal fade" id="mdConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card " >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar el Subfactor?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    					   
                            <small>Usted eliminar&aacute; el Subfactor seleccionado.</small>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="borrarSubFactor()" id="save-buttom" type="submit">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- INICIO MODAL ELIMINAR FACTOR -->
        <div class="modal fade" id="mdConfirmDeleteFactor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar el Factor?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
                            <small>Usted eliminar&aacute; el Factor seleccionado y sus subfactores.</small>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="borrarFactor()" id="save-buttom" type="submit">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- INICIO MODAL EDITAR PESO FACTOR -->
        <div class="modal fade" id="modalEditarPesoFactor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar peso del factor</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
                            <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="number" name="peso" id="peso">
                                        <label class="mdl-textfield__label" for="peso">Peso</label>
                                    </div>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions ">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarPesoFactor()" id="save-buttom" type="submit">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    	<form action="c_main/logout" name="logout" id="logout" method="post"></form>

    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>cookies/jquery.cookie.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_SPED;?>js/jsrubrica.js"></script>
        
        <script type="text/javascript">  
          marcarNodo("Rubrica");
          initRubrica();
          returnPage();
          setValor('selectValor', <?php echo "'".$idFichaVal."'"; ?>);
        	(function($) {
    	        $.fn.clickToggle = function(func1, func2) {
    	            var funcs = [func1, func2];
    	            this.data('toggleclicked', 0);
    	            this.click(function() {
    	                var data = $(this).data();
    	                var tc = data.toggleclicked;
    	                $.proxy(funcs[tc], this)();
    	                data.toggleclicked = (tc + 1) % 2;
    	            });
    	            return this;
    	        };
    	    }(jQuery));
    	    initSearchTable();
        </script>
	</body>
</html>