<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>    
        <title>Solicitud Personal | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type='text/css' rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        
        <style type="text/css">
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

	<body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>   
        		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>  
            
                <div class="row-fluid">                
                    <section id="base_1" class="mdl-layout__tab-panel is-active">
                        <div class="mdl-content-cards">
                           <div class="mdl-card">
                               <div class="mdl-card__title">
                                   <h2 class="mdl-card__title-text" id="titleTb">Solicitud de personal</h2>
                               </div>
                               <div class="mdl-card__supporting-text">
                                   <div id="contTablaSolicitudes">
    						         <?php echo isset($tableSolicitud) ? $tableSolicitud : null;?>
    						       </div>
                               </div>
                           </div>
                        </div>
                    </section>                
                </div>                
            </main>
            
    	</div>	
    		
    		<div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content b-o">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Registrar Solicitud</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-20">
                                <div class="row-fluid">
                                    <div class="col-xs-12 p-0 m-0 m-b-20">
                                        <select id="selectPuesto" name="selectPuesto" data-live-search="true" title="Puesto" class="form-control pickerButn">
                                            <option value="">Selec. Puesto</option>
    	                                </select>
                                    </div>
                                    <div class="col-xs-12 p-0 m-0 m-b-20">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input type="text" class="mdl-textfield__input" name="cantidad" id="cantidad" value="1" style="text-align: center;"/>
                                            <label class="mdl-textfield__label" for="cantidad">Cantidad</label>                    
                                        </div>
                                    </div>
                                    <div class="col-xs-12 p-0 m-0 m-b-20">
                                        <select id="selectArea" name="selectArea" onchange="getAreasEspeficicas();" data-live-search="true" title="Area" class="form-control pickerButn">
                                            <option value="">Selec. Area</option>
    	                                </select>
                                    </div>
                                    <div class="col-xs-12 p-0 m-0 m-b-20">
                                        <select id="selectAreaEsp" name="selectAreaEsp" data-live-search="true" title="Area" class="form-control pickerButn">
                                            <option value="">Selec. Area Especifica</option>
    	                                </select>
                                    </div>
                                    <div class="col-xs-12 p-0 m-0">
                                        <select id="selectSede" name="selectSede" data-live-search="true" title="Sede" class="form-control pickerButn">
                                            <option value="">Selec. Sede</option>
    	                                </select>
                                    </div>
                                    <div class="col-xs-12 p-0 m-0">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <textarea type="text" class="mdl-textfield__input" rows="3" name="textAareaObs" id="textAareaObs"></textarea>
                                            <label class="mdl-textfield__label" for="textAareaObs">Observaciones</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <a class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</a>
                                <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="insertarSolicitudes();">Aceptar</a>
                                
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
    		
    		
    		<div class="modal fade backModal" id="modalFiltro2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header textoColor1 modal-header-escuela">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title textoColor1" id="myModalLabelTitle">Registrar Solicitud</h4>
                  </div>
                    <div class="modal-body" >
                        <div class="form floating-label">
                            <div class="row" style="margin-left: 2px;margin-right: 2px">
                                <div class="form floating-label">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <select id="selectPuesto" name="selectPuesto" data-live-search="true" title="Puesto" class="form-control pickerButn">
                                                <option value="">Selec. Puesto</option>
        	                                </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" style="margin-right: 10px" onclick="insertarSolicitudes();" class="btn ink-reaction btn-flat btn-primary pull-right">GUARDAR</button>
                                    <button class="btn ink-reaction btn-flat btn-default-dark" data-dismiss="modal" style="width: 100px;float:right">CANCELAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>     
                </div>
            </div>
            
            <div class="modal fade backModal" id="modalCambiaEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header textoColor1 modal-header-escuela">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title textoColor1" id="myModalLabelTitle">Modificar Solicitud</h4>
                  </div>
                    <div class="modal-body" >
                        <div class="form floating-label">
                            <div class="row" style="margin-left: 2px;margin-right: 2px">
                                <div class="form floating-label">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12" id="contRadioEstados" style="text-align:center">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="contBtnCambia"></div>
                                    <button class="btn ink-reaction btn-flat btn-default-dark" data-dismiss="modal" style="width: 100px;float:right">CANCELAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>     
                </div>
            </div>
            
            <?php echo isset($btnFlotante) ? $btnFlotante : null;?>

            
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsrh/jssolicitudPersonal.js"></script>
            
   		
		<script type="text/javascript">        	
		initSolicitud();   
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