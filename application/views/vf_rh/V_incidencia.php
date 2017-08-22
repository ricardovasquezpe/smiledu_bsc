<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Incidencias | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">        
        
	</head>

	<body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >    		
    		<?php echo $menu ?>
    		<main class='mdl-layout__content is-visible'>
    		    <section>
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text" id="titleTb">Incidencias</h2>
							</div>
							
							<div class="mdl-card__supporting-text p-0">
							    <div id="contTablaIncidencia">
    						        <?php echo $tb_incidencias;?>
    						    </div>
							</div>
        			    </div>
                    </div>
                </section> 		
    		</main>
        </div>         
    	
    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main"  onclick="abrirModalRegIncidencia();" data-mfb-label="Nueva incidencia">
                    <i class="mfb-component__main-icon--resting mdi mdi-edit"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-edit"></i>
                </button>
            </li>
        </ul>    
        
        <div class="modal fade backModal" id="modalRegIncidencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Nueva Incidencia</h2>
                        </div>                        
                        <form id="formNuevaIncidencia" method="post" class="form-vertical form_distance">
                            <div class="mdl-card__supporting-text">
                                <div class="row">
                                    <div class="col-sm-9 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input type="text" maxlength="200" class="mdl-textfield__input"  name="personalElegido" id="personalElegido" value="" readonly>
                                            <label class="mdl-textfield__label" for="username">Personal</label>
                                        </div>     
                                        <div class="mdl-btn">
                			                <button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalPersonal()">
    								            <i class="mdi mdi-add"></i>
    								        </button>
                			            </div>      
                                    </div>
                                    <div class="col-sm-3 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                             <input type="text" maxlength="200" class="mdl-textfield__input" name="fecha" id="fecha">
                                             <label class="mdl-textfield__label" for="fecha">Fecha</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectSede" name="selectSede" data-live-search="true" class="form-control pickerButn">
                                                <option value="">Selec. Sede</option>
                                                <?php echo $sedes?>                                              
                                            </select>
                                        </div>                           
                                    </div>
                                    <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectArea" name="selectArea" data-live-search="true" class="form-control pickerButn" onchange="changeAreaGeneral()">
                                                <option value="">Selec. &Aacute;rea</option>
                                                <?php echo $areas?>                                             
                                            </select>
                                        </div>                           
                                    </div>
                                    <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectAreaEsp" name="selectAreaEsp" data-live-search="true" class="form-control pickerButn">
                                                <option value="">Selec. &Aacute;rea Especifica</option>                                            
                                            </select>
                                        </div>                           
                                    </div>
                                    <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectTincidencia" name="selectTincidencia" data-live-search="true" class="form-control pickerButn" onchange="changeTipoIncidencia()">
                                                <option value="">Selec. Tipo incidencia</option>
                                                <?php echo $tIncidencias?>                                             
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="checkbox checkbox-inline checkbox-styled" id="cont_cb"> </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield  mdl-textfield--floating-label">
                                            <textarea class="mdl-textfield__input" type="text" rows= "3" id="descripcion" name="descripcion" ></textarea>
                                            <label class="mdl-textfield__label" for="detalle">Descripci&oacute;n</label>
                                        </div>
                                    </div>                                   
                                </div>                         
                            </div>
                            <div class="mdl-card__actions">
                                <a class="mdl-button mdl-js-button mdl-js-ripple-effect" type="button" data-dismiss="modal">CANCELAR</a>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="save-buttom">GUARDAR</button>
                            </div>
                        </form>
                    </div>
                </div>     
            </div>
        </div>
          
        <!--div class="modal fade backModal" id="modalPersonal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white" id="tituModal">Elegir Personal</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <input type="text" maxlength="200" class="mdl-textfield__input" name="personaBusqueda" id="personaBusqueda" value="" onchange="getPersonasByNombre()">
                                    <label class="mdl-textfield__label" for="username"></label>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20" id="cont_tab_personal">                               
                                </div>                           
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">                           
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">CERRAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div-->
        
        <div class="modal fade" id="modalPersonal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Elegir Personal</h2>
                        </div>                        
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-r-15 p-l-15">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input type="text" maxlength="200" class="mdl-textfield__input"  name="personaBusqueda" id="personaBusqueda" value="" onchange="getPersonasByNombre()">
                                        <label class="mdl-textfield__label" for="personaBusqueda">Personal</label>
                                    </div>           
                                </div>
                                <div class="col-xs-12 p-0">                                
                                    <div id="cont_tab_personal"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">CERRAR</button>
                        
                        </div>  
                    </div>
                </div>
            </div>
        </div>
          
        <!--div class="modal fade backModal" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white" id="desc_cambiarEstado">Cambiar Estado</h2>
                        </div>  
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20" id="contRadioEstados">
                                    <label class="radio-inline radio-styled radio-success">
                                        <input type="radio" name="radioVals" value="1" onchange="showFechaRealizado(1)">
                                        <span>S�</span>
                                    </label>
                                    <label class="radio-inline radio-styled radio-danger">
                                        <input type="radio" name="radioVals" value="0" onchange="showFechaRealizado(0)">
                                        <span>No</span>
                                    </label>
                                </div>                       
                                <div class="col-xs-12 p-0 m-0 m-b-20" style="display: none;" id="divFechaResuelto">
                                    <input type="text" class="form-control" id="fechaResuelto" data-inputmask="'alias': 'date'">
                                    <label for="fechaResuelto"></label>
                                </div>                                                    
                            </div>        
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" id="save-buttom" onclick="cambiarEstado()">GUARDAR</button>
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>    
                </div>
            </div>
        </div-->
        
        <div class="modal fade backModal" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text mdl-color-text--black-500" id="desc_cambiarEstado">Cambiar Estado</h2>
                    </div>  
                    <div class="modal-body">
                        <div class="col-sm-12" id="contRadioEstados" style="text-align:center">
                            <label class="radio-inline radio-styled radio-success">
                                <input type="radio" name="radioVals" value="1" onchange="showFechaRealizado(1)">
                                <span>S&iacute;</span>
                            </label>
                            <label class="radio-inline radio-styled radio-danger">
                                <input type="radio" name="radioVals" value="0" onchange="showFechaRealizado(0)">
                                <span>No</span>
                            </label>
                        </div>                      
                        <div class="col-sm-12" style="display: none;" id="divFechaResuelto">
                            <div class="form-group floating-label">
                                <input type="text" class="form-control" id="fechaResuelto" data-inputmask="'alias': 'date'">
                        	    <label for="fechaResuelto"></label>
                            </div>
                        </div>                       
                        <br/>
                        <br/>
                        <br/>
                        <div class="mdl-card__actions text-right p-r-20 p-l-20">                           
                            <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" data-dismiss="modal">CANCELAR</button>
                            <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" onclick="cambiarEstado()" id="save-buttom" type="submit">GUARDAR</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>            
            
        <!--div class="modal fade backModal" id="modalObservacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white" id="tituModal">Observaci�n</h2>
                        </div>  
                        <div class="mdl-card__supporting-text p-2">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <textarea name="textObservacion" id="textObservacion" class="mdl-textfield__input" style="resize: none;" rows="3" placeholder="" disabled></textarea>
                                    <label class="mdl-textfield__label" for="textarea2"></label>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">CERRAR</a>
                                </div>
                            </div>        
                        </div>
                    </div>    
                </div>
            </div>
        </div-->
        
        <div class="modal fade backModal" id="modalObservacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text mdl-color-text--black-500" id="tituModal">Observaci&oacute;n</h2>
                    </div>  
                    <div class="modal-body">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        	<textarea name="textObservacion" id="textObservacion" class="mdl-textfield__input" style="resize: none;" rows="3" disabled></textarea>
                            <label class="mdl-textfield__label" for="textarea2"></label>
                        </div>
                        <div style="text-align: center" class="row">
                            <button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CERRAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>  
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsrh/jsincidencia.js"></script>
   		
		<script type="text/javascript">        	
		init();
		$('#fecha').bootstrapMaterialDatePicker({ weekStart : 0, time: false, format : 'DD/MM/YYYY'});
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
    
        initSearchTableNew();
        
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