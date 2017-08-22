<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Capacitación - Smiledu</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        
        <link rel="shortcut icon" type="image/png" href="<?php echo RUTA_IMG?>header/Avantgardfavi.ico" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        
        <!-- Tipografia -->
        <link rel='stylesheet' type='text/css' href="<?php echo RUTA_CSS?>fonts/roboto.css"/>
		
		<!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/bootstrap94be.css?1422823238" />
		<link rel='stylesheet' type='text/css' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<!--link rel="stylesheet" href="<?php echo base_url()?>public/plugins/bootstrap/css/bootstrap.min.css"-->
		
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
		<link rel='stylesheet' href="<?php echo RUTA_PLUGINS?>fullcalendar/fullcalendar.min.css"/>
		
        <style type="text/css">
            #all-products{
            	display: none
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
        </style>
	</head>

	<body class="menubar-hoverable header-fixed">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		
    		<?php echo $menu ?>
            <main class='mdl-layout__content p-t-30 p-b-30'>  
                <div class="row-fluid">                
                    <section id="base_1" class="p-0 m-0">
                        <div class="section-body m-0">
                            <div class="col-lg-10 col-lg-offset-1">
                                <div class="mdl-card mdl-card__alter mdl-shadow--2dp">
                                    <div class="mdl-card__title p-t-20">
                                        <h2 class="mdl-card__title-text mdl-color-text--grey-600 mdl-typography--font-regular f-s-regular" id="titleTb">Capacitaciones</h2>
                                    </div>
                                	
                                    <!-- Estilo de tabla p-0 pegar todo al card, m-t-30 margin top  -->
                                    <div class="mdl-card__supporting-text p-20 m-t-30">
                                       <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>                
                </div>
                
            </main>
    		
    	
    	<div class="modal fade backModal" id="modalNewCapacitacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header textoColor1 modal-header-escuela">
                    <h4 class="modal-title textoColor1" id="myModalLabelTitle">Capacitación</h4>
                  </div>
                  <div class="modal-body">
                      <div class="form floating-label">
                          <form id="formNuevaCapacitacion" method="post" class="form-vertical form_distance">
                              <div class="container-fluid">
                                  <div class="row">
                                      <div class="col-xs-12 p-0 m-0 m-b-20">
                                          <select id="selectSede" name="selectSede" data-live-search="true" class="form-control pickerButn">
                                              <option value="">Selec. Sede</option>
                                              <?php echo $sedes?>                                              
                                          </select>                                      
                                      </div>
                                      <div class="col-xs-12 p-0 m-0 m-b-20">
                                          <select id="selectArea" name="selectArea" data-live-search="true" class="form-control pickerButn" onchange="changeAreaGeneral()">
                                              <option value="">Selec. Área</option>
                                              <?php echo $areas?>                                             
                                          </select>                           
                                      </div>
                                      <div class="col-xs-12 p-0 m-0 m-b-20">
                                          <select id="selectAreaEsp" name="selectAreaEsp" data-live-search="true" class="form-control pickerButn">
                                              <option value="">Selec. Área Especifica</option>                                            
                                          </select>                                      
                                      </div>
                                      <div class="col-xs-12 p-0 m-0 m-b-20">
                                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="descripcion" name="descripcion" maxlength="30">
                                                <label class="mdl-textfield__label" for="descripcion">Descripción</label>
                                            </div>
                                      </div>
                                      <div class="col-xs-12 p-0 m-0 m-b-20">
                                          <div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label">
                                            <textarea class="mdl-textfield__input" type="text" rows= "3" id="detalle" name="detalle" ></textarea>
                                            <label class="mdl-textfield__label" for="detalle">Detalle</label>
                                          </div>                                 
                                      </div>
                                        
                                      <div class="mdl-card__actions text-right p-r-20 p-l-20">                          	      
                                          <a class="mdl-button mdl-js-button mdl-color-text--grey-500" data-dismiss="modal">CANCELAR</a>
                                          <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" id="save-buttom" type="submit">GUARDAR</button>
                                      </div>
                                  </div>
                              </div>
                          </form>
                      </div> 
                    </div>
                </div>
            </div>     
		</div>
		
		<div class="modal fade backModal" id="modalEditEvent" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header textoColor1 modal-header-escuela">
                        <h4 class="modal-title textoColor1" id="myModalLabelTitle">Cambiar Capacitación</h4>
                      </div>
                      <div class="modal-body">               

                            <div class="form floating-label">
                              <form id="formEditCapacitacion" method="post" class="form-vertical form_distance">
                                  <div class="container-fluid">
                                      <div class="row">
                                          <div class="col-xs-12 p-0 m-0 m-b-20">
                                              <select id="selectSedeEdit" name="selectSedeEdit" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Sede</option>
                                                  <?php echo $sedes?>                                              
                                              </select>
                                          </div>
            						      <div class="col-xs-12 p-0 m-0 m-b-20">
                                              <select id="selectAreaEdit" name="selectAreaEdit" data-live-search="true" class="form-control pickerButn" onchange="changeAreaGeneral()">
                                                  <option value="">Selec. Área</option>
                                                  <?php echo $areas?>                                             
                                              </select>                         
                                          </div>             
        						          <div class="col-xs-12 p-0 m-0 m-b-20">
                                              <select id="selectAreaEspEdit" name="selectAreaEspEdit" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Área Especifica</option>                                            
                                              </select>
                                          </div>
                                            
                                          <div class="col-xs-12 p-0 m-0 m-b-20">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="text" id="fechaEdit" name="fechaEdit" data-inputmask="'alias': 'date'">
                                                    <label class="mdl-textfield__label" for="descripcion">Fecha de realización</label>
                                                </div>
                                          </div>
                                          <div class="col-xs-12 p-0 m-0 m-b-20">
                                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                  <input class="mdl-textfield__input" type="text" id="descripcionEdit" name="descripcionEdit">
                                                  <label class="mdl-textfield__label" for="descripcionEdit">Descripción</label>
                                              </div>
                                          </div>
                                            
                                            <div class="col-xs-12 p-0 m-0 m-b-20">
                                              <div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label">
                                                  <textarea class="mdl-textfield__input" type="text" rows= "3" id="detalleEdit" name="detalleEdit" ></textarea>
                                                  <label class="mdl-textfield__label" for="detalleEdit">Detalle</label>
                                              </div>
                                          </div>
                                            
                                          <div class="mdl-card__actions p-10 p-r-20 p-l-20">                               	      
                                              <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" data-dismiss="modal">CANCELAR</a>
                                              <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" id="save-buttom" type="submit">GUARDAR</button>
                                          </div>
                                      </div>
                                  </div>
                              </form>
                          </div> 

                      </div>
                    </div>
                </div>     
			</div>
        
        
        <!-- JQuery -->
		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js" charset="UTF-8"></script>
    	
    	<!-- Boostrap -->
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
		<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script charset="UTF-8" src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
    	
    	<!-- Toastr -->
   		<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js" charset="UTF-8"></script>
    	
    	<!-- Fab -->
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
   		
    	<!-- MDL -->
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	
    	<!-- Scripts -->
    	<script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>fullcalendar/fullcalendar.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>fullcalendar/lang-all.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Sjsrh/jscapacitacion.js" charset="UTF-8"></script>  		
   		
   		
		<script type="text/javascript">
		$(":input").inputmask();
        $(document).ready(function() {
        	var datos = <?php echo json_encode($calendarData)?>;
        	initCalendar(datos);
        	initValidatorNuevaCapacitacion();
        	initValidatorEditarCapacitacion();
        	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker({});
        	}        	
    	});
		$(function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
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
        
        var lastScrollTop = 0;
        $(window).scroll(function(event){
           var st = $(this).scrollTop();
           if (st > lastScrollTop){//OCULTAR
        	   $("#menu").fadeOut();
           } else {
        	   if(st + $(window).height() < $(document).height()) {//MOSTRAR
        		   $("#menu").fadeIn();
        	       
    	    	}
           }
           lastScrollTop = st;
        });
		</script>
	</body>
</html>