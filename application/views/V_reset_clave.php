<!DOCTYPE html>
<html>
<head>
	<title>Cambia tu Clave</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="your,keywords">
	<meta name="description" content="Short explanation about this website">
	<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />
        
	<link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_FONTS?>roboto.css" />       
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_FONTS?>material-icons.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS?>m-p.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS?>login.css" />
</head>
<body>
    <div class="header-schoowl"></div>
    	<div class="container p-0">
            <div class="card"></div>
            <div class="card">
                <h1 class="title p-rl-0 text-center br-0" style="border-right: 5px solid;">
    				<img alt="Logo" class="logo" src="<?php echo RUTA_IMG?>header/sistema_avantgard.png">
    			</h1>
        	    <div class="card-head text-center"><header><?php echo isset($correo_cambio) ? $correo_cambio :  (isset($rpta) ? $rpta : 'La solicitud ha expirado.');?></header>
        		</div><!--end .card-head -->
        		<div id="loginForm" class="m-r-30 m-l-30 "> 
        		    <?php if(isset($correo_cambio)) {?>
        		    <div class="input-container">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="clave"/>
                            <label class="mdl-textfield__label" for="clave">Nueva Contrase&ntilde;a</label>
                        </div>
					</div>
					<div class="input-container">
					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="otroclave"/>
                            <label class="mdl-textfield__label" for="otroclave">Confirmar Contrase&ntilde;a</label>
                        </div>
					</div>
					<div class="mdl-card_actions m-b-10 m-t-20">
					    <button style="margin-right: 10px" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="actualizarClave();">Actualizar Contrase&ntilde;a</button>
					</div>
					<?php } else {?>
					    <div style="display: flex;justify-content: center;">
					        
					    </div>
					<?php }?>
				</div>
        	</div>
        </div>
    <?php if(isset($correo_cambio)) {?>
        <script>
            function actualizarClave() {
            	var clave  = $('#clave').val();
            	var _clave = $('#otroclave').val();
                if($.trim(clave) == '' || $.trim(_clave) == '') {
                	msj('error', 'Ingrese su nueva clave');
                	return;
                }
                if(clave != _clave) {
                	msj('error', 'Las claves no coinciden');
                	$('#clave').val(null);
                	$('#otroclave').val(null);
                	$('#clave').focus();
                	return;
                }
            	$.ajax({
            		data : { clave  : clave ,
                		     _clave : _clave },  
            		url  : 'reset_clave/cambiarClave', 
            		type : 'POST'
            	}).done(function(data) {
            		data = JSON.parse(data);
            		if(data.error == 1) {
            			msj('error', data.msj);
            		} else {
            			msj('success', data.msj);
                		$('#loginForm').html(data.msj);
            		}
            	});
            }
        </script>
    <?php }?>
    <script src="<?php echo RUTA_JS?>jquery-3.1.0.min.js"></script>
    <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"/></script>
	<script src="<?php echo RUTA_JS?>libs/spin.js/spin.min.js"></script>
	<script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
	<script src="<?php echo RUTA_JS?>libs/autosize/jquery.autosize.min.js"></script>
	<script src="<?php echo RUTA_JS?>core/cache/63d0445130d69b2868a8d28c93309746.js"></script>              
    <script src="<?php echo RUTA_JS?>jslogic/jslogin.js"></script>
    <script src="<?php echo RUTA_JS?>Utils.js"></script>
</body>
</html>