<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
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
        <?php $cookie_name = 'intro';
        if(!isset($_COOKIE[$cookie_name])) { ?>
        <div id="load_screen">
    		<div id="loading"></div>
    	</div>
    	<?php } ?>
    	<div class="header-schoowl"><div id="snow"></div></div>
    	<div class="container p-0">
    		<div class="card"></div>
    		<div class="card">
    			<h1 class="title">
    				<img alt="Logo" class="logo" src="<?php echo RUTA_IMG?>header/sistema_avantgard.png">
    			</h1>
    			
    			<?php if(isset($_COOKIE['error_google'])) {
    			          unset($_COOKIE['error_google']);
                          setcookie('error_google', null, -1, '/');?>
                        <div class="alert alert-danger alert-dismissible m-20 m-b-0 m-t-0" role="alert" id="cont_error_google">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Espera!</strong> Tu cuenta de Google no est&aacute; vinculada con una cuenta de Avantgard. Intente ingresar con su usuario y contrase&ntilde;a.
                        </div>
    			<?php } ?>
    			
    			
    			<div class="alert alert-danger alert-dismissible m-r-20 m-l-20" role="alert" id="cont_error" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p id="msj_error"><strong>Espera!</strong> Tu correo de Google no lo tenemos, intenta con tu usuario de Smiledu.</p>
                </div>
    			
    			<form id="loginForm" class="m-r-30 m-l-30 ">    			     
    				<div class="input-container" id="user">
    				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_usuario">
                            <input class="mdl-textfield__input" type="text" id="usuario" autofocus="autofocus" value="<?php echo isset($usuarioLogin) ? $usuarioLogin : null;?>"/>
                            <label class="mdl-textfield__label" for="Username">Usuario o correo</label>
                        </div>
    				</div>
    				
    				<div class="input-container" id="passw">
    				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label " id="cont_clave" >
                            <input class="mdl-textfield__input" type="password" id="password" value="<?php echo isset($passwordLogin) ? $passwordLogin : null;?>"/>                            	                                                      
                            <label class="mdl-textfield__label" for="Password">Contrase&ntilde;a  </label>
                            <a id="showpas"  class="mdl-button mdl-js-button mdl-js-button-ripple-effect see-pass"><i  class="mdi mdi-remove_red_eye text-rigth "></i></a>
                        	
                        </div>                        
    				</div>
    				<div class="mdl-card_actions" >
        				<button onclick="logear()" name="ingresar" value="Ingresar"
        					class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button">Ingresar
        				</button>
        			</div>
    				<br/>
    			</form>
    			      			    			
    			<div class="p-0 m-l-30">
        			<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="check" style="width: auto;">
                        <input type="checkbox" id="check" class="mdl-checkbox__input" <?php echo isset($checkLogin) ? $checkLogin : null;?>>
                        <span class="mdl-checkbox__label mdl-typography--body-2 mdl-typography--font-light " style="color: #757575;">Recordarme</span>
                    </label>
                    
                    
                    
    			</div>		
    			<div style="position: absolute; bottom:25px; right: 30px;"><?php echo isset($html_google) ? $html_google : null;?></div> 
    			
    		</div>
    		<div class="card alt">
    			<div class="toggle">
    			</div>
    			<h1 class="title">
    				Recuperar<br>contrase&ntilde;a
    				<div class="close"></div>
    			</h1>
    			<div class="alert alert-danger alert-dismissible m-20 m-b-0 m-t-0" role="alert" id="cont_error_google" style="position: relative; display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Espera!</strong> Error clave! 
                </div>
				<div class="input-container p-l-15 p-r-15">
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="correo" id="correo" autofocus="autofocus" />
                        <label class="mdl-textfield__label" for="Password">Correo</label>
                    </div>
				</div>
				<div class="mdl-card_actions again p-l-15 p-r-15 p-t-30">
    				<button onclick="enviarCorreo()" name="reestablecer" value="Reestablecer"
    					class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Reestablecer
    				</button>
    			</div>
    		</div>
    		
    		<p class="m-0 m-t-10 m-b-10 text-center"><a class="link-smiledu" href="http://www.smiledu.pe" target="_blank"><strong>Smiledu</strong>&reg;</a> Created by <a class="link-smiledu" href="http://www.softhy.pe/" target="_blank" style="text-decoration:none">Softhy</a></strong>.</p>
    		
    	</div>
    	
    	<script>
    	     <?php $cookie_value = true;
    	     if(!isset($_COOKIE[$cookie_name])) {
    	         setcookie($cookie_name, $cookie_value, -1, '/'); ?>
        	     window.addEventListener("load", function() {
                  	setTimeout(function() {
                  	var load_screen = document.getElementById("load_screen");
                  	document.body.removeChild(load_screen);}, 2600);
                  });
    	     <?php } ?>
            
		</script>
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
    	<script type="text/javascript">
    	init();
    		$('.toggle').on('click', function() {
    			  $('.container').stop().addClass('active');
    			});
    
    			$('.close').on('click', function() {
    			  $('.container').stop().removeClass('active');
    			});

    			var show=0;
    			$( "#showpas" ).click(function() {
					if(show==0){
  			    		$("#password").attr('type','password');
						show=1; }
					else if(show==1) {
						$("#password").attr('type','text');
						show=0;}
  			 	 })
  			  
  			  
    			$(document).ready(function(){
      			    $("#input").click(function(){
      			        $("#showpas").fadeIn();     			        
      			    });
      			});
      			
  		</script>
  		
    </body>
    
</html>