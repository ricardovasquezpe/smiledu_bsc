<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
		<title>Configuraci&oacute;n | <?php echo NAME_MODULO_SPED?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SPED?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SPED?>" />
	    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>jquery-ui-theme5e0a.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SPED?>css/submenu.css">
		
		<style type="text/css">
            .input-group-addon{
            	border: none;
            	background-color: transparent;
            	color: #757575;
            }
            
            .form-control-static{
                padding-top: 17.5px	
            }
        </style>
        
	</head>

	<body onload="screenLoader(timeInit);">    	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section>
                    <div class="mdl-content-cards">		
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Configuraciones</h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b">
                                <div class="row-fluid">
                                    <div class="col-xs-12">
                                        <h5 class="text-left">Cantidad M&iacute;nima y M&aacute;xima de Evaluaciones para Coordinadores</h5>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon" id="range-value1"><?php echo $sub_val_num_1;?></div>
                                                <div class="input-group-content form-control-static">
                                                	<div id="slider-range"></div>
                                                </div>
                                                <div class="input-group-addon" id="range-value2"><?php echo $sub_val_num_2;?></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <h5 class="text-left">Cantidad M&iacute;nima y M&aacute;xima de Evaluaciones para Subdirectores</h5>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon" id="range-value3"><?php echo $cor_valor_num_1;?></div>
                                                <div class="input-group-content form-control-static">
                                                    <div id="slider-range2"></div>
                                                </div>
                                                <div class="input-group-addon" id="range-value4"><?php echo $cor_valor_num_2;?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__menu">
                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="grabarConfig();">
                                    <i class="mdi mdi-save"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
		    </main>	
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script type="text/javascript">
            
        	marcarNodo("Configuracion");
            $(document).ready(function() {
            	
        	});

            $("#slider-range").slider({range: true, min: <?php echo $sub_val_min;?>, max: <?php echo $sub_val_max;?>, values: [<?php echo $sub_val_num_1;?>, <?php echo $sub_val_num_2;?>],
    			slide: function (event, ui) {
    				$('#range-value1').empty().append(ui.values[0]);
    				$('#range-value2').empty().append(ui.values[1]);
    			}
    		});

            $("#slider-range2").slider({range: true, min: <?php echo $cor_val_min;?>, max: <?php echo $cor_val_max;?>, values: [<?php echo $cor_valor_num_1;?>, <?php echo $cor_valor_num_2;?>],
    			slide: function (event, ui) {
    				$('#range-value3').empty().append(ui.values[0]);
    				$('#range-value4').empty().append(ui.values[1]);
    			}
    		});

            function grabarConfig() {
            	var val1 = $('#range-value1').html();
            	var val2 = $('#range-value2').html();

            	var val3 = $('#range-value3').html();
            	var val4 = $('#range-value4').html();

            	if($.trim(val1) == "" || $.trim(val2) == "" || $.trim(val3) == "" || $.trim(val4) == "") {
            		mostrarNotificacion('error', 'Seleccione los valores', null);
            		return;
            	}
            	if(!($.isNumeric(val1) && Math.floor(val1) == val1 && $.isNumeric(val2) && Math.floor(val2) == val2 &&
            	   $.isNumeric(val3) && Math.floor(val3) == val3 && $.isNumeric(val4) && Math.floor(val4) == val4)) {
            		mostrarNotificacion('error', 'Valores incorrectos', null);
            		return;
            	}
            	$.ajax({
            		data : {val1 : val1,
            			    val2 : val2,
            			    val3 : val3,
            			    val4 : val4},
            	    url  : 'c_config/grabarConfig_CTRL',
            	    async: false,
            	    type : 'POST'
            	})
            	.done(function(data){
            		data = JSON.parse(data);
            		if(data.error == 0) {
            			mostrarNotificacion('success', data.msj, null);
            		} else {
            			mostrarNotificacion('error', data.msj, null);
            		}
            	});
            }        
		</script>
	</body>
</html>