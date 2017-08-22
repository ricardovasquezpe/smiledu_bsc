<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Smiledu | Configuración Puntaje</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="your,keywords">
		<meta name="description" content="Short explanation about this website">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">

	    <link rel="shortcut icon" type="image/png" href="<?php echo base_url()?>public/files/images/favicon1.ico"/>
		<link href="<?php echo base_url()?>public/css/fonts/roboto.css" rel='stylesheet' type='text/css'/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/bootstrap94be.css?1422823238" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/materialadminb0e2.css?1422823243" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/font-awesome.min753e.css?1422823239" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>theme-default/material-design-iconic-font.mine7ea.css?1422823240" />
        <link href="<?php echo RUTA_CSS?>logic/maincss.css" rel='stylesheet' type='text/css'/>
		<link href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"></link>
		<link href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.css" rel="stylesheet" type="text/css">
		<link href="<?php echo RUTA_PLUGINS?>toaster/toastr.css" rel="stylesheet" type="text/css">
	    <link href="<?php echo RUTA_CSS?>general.css" rel='stylesheet' type='text/css'/>
	    <link href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css" rel="stylesheet">
	    <link href="<?php echo RUTA_PLUGINS?>xeditable/bootstrap-editable.css" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_CSS?>icons_schoowl/icons_schoowl.css" rel='stylesheet' type='text/css'/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />        
        
        <style>
            .img_colegio{
            	height: 40px; width: 40px;
            	background-size:40px;
            	background-image: url('<?php echo base_url();?>public/files/images/avantgardLogo.png');
            }
             .fixed-table-container{
                 border:none;
             }
            .mdl-card{
            	width: 100%;
            }
        </style>
        
        <style>
			@media (min-width: 769px) {
			  #menubar{
				margin-top: 64px;
			  }
              #base_1{
            		margin-top: 80px;
              }
			} 
            @media (max-width: 768px) {
            	#base_1{
            		margin-top: 20px;
            	}
            }
		</style>
	</head>

	<body class="menubar-hoverable header-fixed">
    	
        <?php echo $cabecera?>
    	
        <div id="base">
		    <div id="content" style="font-size: <?php echo $font_size?>">        
                <section id="base_1">
            		<div class="section-body no-margin">
            	        <div class="row">
            				<div class="col-md-12">
            				    <div class="card">
            						<div class="card-body">
                						<div id="contTablaConfigPtje" class="form floating-label table_distance">
                						<?php echo $tablaConfigPtje;?>
                						</div>
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
	            </section>
		    </div>		
		
        <ul id="menu" class="mfb-component--br mfb-zoomin" style="z-index:1">
              <li class="mfb-component__wrap">
                <a href="javascript:void(0)" class="mfb-component__button--main" id="main_button">
                  <i class="mfb-component__main-icon--resting md md-edit" onclick="btnNuevoPtjeUniv();" style="font-size:26px;padding-top: 0px;color:white;margin-top:0px;transform: rotate(0deg);"></i>
                </a>
              </li>
        </ul>
  
        <?php echo $menu?>
          
  		<div class="offcanvas">

	    </div>

	</div>
<div class="modal fade" id="modalTipoExam" tabindex="-1" role="dialog"
	 aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header textoColor1 modal-header-escuela">
			    <button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">×</button>
				    <h4 class="modal-title textoColor1" id="simpleModalLabel">Asignar Puntaje a la Universidad</h4>
				</div>
				<div class="modal-body">
					<div class="form floating-label">
							<div class="container-fluid">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<select id="selectUniv" name="selectUniv"
												data-live-search="true" title="Universidad"
												class="form-control pickerButn">
												<option>Selec. Univ.</option>
                                                <?php echo $optUniv; ?>
                                            </select>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<input type="text" class="form-control" id="yearPopup"
												   value="<?php echo date('Y'); ?>" maxlength="4"
												   name="yearPopup" readonly="readonly">
										</div>
									</div>
									<div class="col-sm-12" id="divNvlCompe">
										<div class="form-group">
											<input type="text" maxlength="5" class="form-control"
												   name="puntaje" id="puntaje">
										    <label style="font-size:13px" for="puntaje">Puntaje</label>
										</div>
									</div>

									<div class="col-sm-12" style="margin-top: 20px">
										 <button class="btn ink-reaction btn-flat btn-primary" onclick="grabarPuntajeUnivPopup()" style="float:right;width: 100px" id="save-buttom" type="submit">GUARDAR</button>
                                         <button class="btn ink-reaction btn-flat btn-default-dark" data-dismiss="modal" style="float:right;width: 100px">CANCELAR</button>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
		<script src="<?php echo RUTA_JS?>public/js/libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/spin.js/spin.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/autosize/jquery.autosize.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/core/cache/63d0445130d69b2868a8d28c93309746.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/core/demo/Demo.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/jquery.ui.touch-punch.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/Utils.js"></script>
        <script src="<?php echo RUTA_JS?>public/js/jsmantenimiento/jsconfig_ptje.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>public/plugins/b_select/js/bootstrap-select.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>public/plugins/xeditable/bootstrap-editable.min.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>public/plugins/bTable/bootstrap-table.js" charset="UTF-8"></script>
   		<script src="<?php echo RUTA_PLUGINS?>public/plugins/toaster/toastr.js" charset="UTF-8"></script>
   		<script src="<?php echo RUTA_PLUGINS?>public/plugins/floating-button/mfb.js"></script>
		<script src="<?php echo RUTA_PLUGINS?>public/plugins/bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
		
		<script type="text/javascript">        	
		initConfigPtje();
		marcarNodo("ConfiguracionPuntaje");
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
    generarBotonMenu();
    initXEditable();

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