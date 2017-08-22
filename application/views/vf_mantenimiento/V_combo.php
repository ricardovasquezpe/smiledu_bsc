<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Combo - Smiledu </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        
        <link rel="shortcut icon" type="image/png" href="<?php echo RUTA_IMG?>header/Avantgardfavi.ico" />
        
		<!-- Tipografia -->
        <link rel='stylesheet' type='text/css' href="<?php echo RUTA_CSS?>fonts/roboto.css"/>
		
		<!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/bootstrap94be.css?1422823238" />
		<link rel='stylesheet' type='text/css' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap/css/bootstrap.min.css">
		
		<!-- Theme Default -->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/materialadminb0e2.css?1422823243" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/font-awesome.min753e.css?1422823239" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/material-design-iconic-font.mine7ea.css?1422823240" />
		
		<!-- MDL -->
		<link rel="stylesheet" type='text/css' href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link rel="stylesheet" type='text/css' href="<?php echo RUTA_FONTS?>material-icons.css">
    
        <!-- Toastr -->
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        
        <!-- Fab -->
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">		
		
		<!-- Style -->
		<link rel="stylesheet" type='text/css' href="<?php echo RUTA_CSS?>menu.css">
		<link rel='stylesheet' type='text/css' type='text/css' href="<?php echo RUTA_CSS?>m-p.css">
		
        <style type="text/css">
            body,
            .mdl-layout{
                overflow: hidden;	
            }       
            .popover.bottom>.arrow:after {
            	border-bottom-color: #FF9200;
            }
            .popover{
            	padding: 0px;
            }
            .mdl-card{
            	width: 100%;
            }
            .columns.columns-right.btn-group.pull-right{
            	margin: 0 !important
            }
        </style>
	</head>

	<!-- el body no tiene class -->
	<body>
	
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		
    		<?php echo $menu ?>

            <main class='mdl-layout__content'>
                <section> 
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 p-0">
                            <div class="mdl-card mdl-card__alter">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" id="titleTb">Combos</h2>
                                </div>
                            	
                                <!-- Estilo de tabla p-0 pegar todo al card, m-t-30 margin top  -->
                                <div class="mdl-card__supporting-text p-0">
                                    <div id="conTablaCombos" class="table_distance">
                                        <?php echo $tablaCombos;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>              
            </main>
        </div>
    		
		<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover" style="display: none">
    	    <li class="mfb-component__wrap mfb-only-btn">
                <button class="mfb-component__button--main" onclick="abrirModalCrearCombo();" data-mfb-label="Crear combo">
                    <i class="mfb-component__main-icon--resting mdi md-edit"></i>
                    <i class="mfb-component__main-icon--active  mdi md-edit"></i>
                </button>
            </li>
        </ul>	
    	
        <div class="modal fade backModal" id="modalRegistrarcombo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Registrar Combo</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20" id="cont_titulo_crear">
        						      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                          <input type="text" maxlength="200" class="mdl-textfield__input" name="tituloCrear" id="tituloCrear" value="">
                                          <label class="mdl-textfield__label" for="tituloCrear">Titulo</label>
                                      </div>
            					</div>
            					<hr/>
            					<div id="cont_opciones_crear" style="display: none">
            					   <div id="con_opcion_crear_1" class="con_opcion_crear">
            					       <div class="col-xs-10 p-0 m-0 m-b-20">
            						      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                              <input type="text" maxlength="200" class="mdl-textfield__input inputTextOpcionCrear" name="opcionCrear_1" id="opcionCrear_1">
                                              <label class="mdl-textfield__label" for="opcionCrear_1">Nombre Opci�n</label>
                                          </div>
                    					</div>
                    					<div class="col-xs-2 p-0 m-0 m-b-20">
                						      <button type="button" onclick="agregarOpcionCrear()" id="btn_crear_opcion_1" class="btn btn-icon-toggle" data-toggle="tooltip" data-placement="top"><i class="md md-add"></i></button>
                    					</div>
            					   </div>
            					</div>
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                              <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">CANCELAR</a>
                              <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" id="save-buttom" onclick="registrarCombo()">GUARDAR</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalEditarcombo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Editar Combo</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20" id="cont_titulo_editar">
        						      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                          <input type="text" maxlength="200" class="mdl-textfield__input" name="tituloEditar" id="tituloEditar">
                                          <label class="mdl-textfield__label" for="tituloEditar">Titulo</label>
                                      </div>
            					</div>
            					<hr/>
            					<div id="cont_opciones_editar"></div>
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                              <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">CANCELAR</a>
                              <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" id="save-buttom" onclick="editarCombo()">GUARDAR</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JQuery -->
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
    	
    	<!-- Boostrap -->
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js" charset="UTF-8"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	
    	<!-- Toastr -->
   		<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js" charset="UTF-8"></script>
    	
    	<!-- Fab -->
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
   		
    	<!-- MDL -->
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	
    	<!-- Scripts -->
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jscombo.js"></script>
   		
		<script type="text/javascript">			
		   init();
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
		</script>
	</body>
</html>