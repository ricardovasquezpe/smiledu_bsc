<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Horario Docente | <?php echo NAME_MODULO;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/materialadminb0e2.css?1422823243" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>fonts/roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />            
        
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>		
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/font-awesome.min753e.css?1422823239" />
       
		
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

    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >    		
    		<?php echo $menu ?>
    		<main class='mdl-layout__content p-t-30 p-b-30'>
    		    <div class="row-fluid">
    		        <section id="base_1" class="p-0 m-0">
                        <div class="section-body m-0">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="mdl-card mdl-shadow--2dp">
                                    <div class="mdl-card__title">
    		       					    <h2 class="mdl-card__title-text" id="titleTb">Horario Docente</h2>
    		        			    </div>                       				      			
    	            			    <div class="mdl-card__supporting-text p-0 br-b">
    		         			        <div id="contTablaHorario">
                     			            <?php echo $tbHorario;?>
        						        </div>
    							    </div>
                    	        </div>
                            </div>
                        </div>
                    </section>
        	    </div>
    		</main>
    	</div>
    	
    	<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
	            <button class="mfb-component__button--main">
	            	<i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
	            </button>
	            <button class="mfb-component__button--main" onclick="abrirCerrarModal('modalFiltro')" data-mfb-label="Cargar Horarios">
	             	<i class="mfb-component__main-icon--active mdi mdi-file_upload" ></i>
	            </button>
		        <ul class="mfb-component__list">
			        <li class="mfb-component__wrap">
			           <button class="mfb-component__button--child " onclick="abrirModalFiltros()" data-mfb-label="Nuevo Horario">
			         	<i class="mdi mdi-view_list"></i>
			           </button>
	                </li>
                </ul>
    		</li>
        </ul> 
    		
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="myModalLabelTitle">Nuevo Horario</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20" >
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectDocente" name="selectDocente" data-live-search="true" title="Docente" class="form-control pickerButn" onchange="checkIfHorarioExiste();">
                                        <option value="">Selec. Docente</option>
                                        <?php echo $comboProfe;?>
	                                </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectCurso" name="selectCurso" data-live-search="true" title="Curso" class="form-control pickerButn" onchange="checkIfHorarioExiste();">
                                        <option value="">Selec. Curso</option>
                                        <?php echo $comboCursos;?>
	                                </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectAula" name="selectAula" data-live-search="true" title="Aula" class="form-control pickerButn" onchange="checkIfHorarioExiste();">
                                        <option value="">Selec. Aula</option>
                                        <?php echo $comboAulas;?>
	                                </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <label id="msjHorario" class="label label-danger" style="font-size: 100%"></label>
                                </div>                              
                            </div>     
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" id="btnHorario" data-dismiss="modal">GUARDAR</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect " type="button" data-dismiss="modal">CANCELAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade backModal" id="modalConfirmDeleteHorario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card ">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="tituModal">Eliminar</h2>
                        </div>  
                        <div class="mdl-card__supporting-text">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <p style="text-align: center;display: inline;" class="textoColor">&#191;Eliminar el <strong>horario </strong>?</p>
                                    <input type="hidden" id="hidIdHorario">
                                </div>                               
                            </div>    
                        </div>
                        <div class="mdl-card__actions">                            
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" type="button" data-dismiss="modal">CANCELAR</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" data-i_dd="" id="btnDeleteHorario" data-dismiss="modal">ACEPTAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
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
        <script src="<?php echo RUTA_JS?>public/js/jsmantenimiento/jshorario.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js" charset="UTF-8"></script>
        
		<script type="text/javascript">        	
		initHorario();
		$('.fixed-table-toolbar').addClass('mdl-card__menu');
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