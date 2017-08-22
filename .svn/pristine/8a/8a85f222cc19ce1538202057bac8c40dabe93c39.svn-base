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
               
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_FONTS?>roboto.css" />       
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS?>m-p.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo RUTA_CSS?>menu.css" />
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
    			<form id="loginFormPadres" class="m-r-30 m-l-30">
    			    <select id="cmbSede" name="cmbSede" class="form-control pickerBut m-b-20" style="border-bottom: 1px solid #757575;">
                        <option value="">Selec. Sede</option>
                        <?php echo $sedes;?>
                    </select> 
    				<div class="input-container" id="user">
    				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_usuario">
                            <input class="mdl-textfield__input" type="text" id="usuario" value="<?php echo isset($usuarioLogin) ? $usuarioLogin : null;?>"/>
                            <label class="mdl-textfield__label" for="Username">Usuario</label>
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
        				<button onclick="loginPadres()" name="ingresar" value="Ingresar"
        					class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" style="right:5px">Ingresar
        				</button>
        			</div>
    				<br/>
    			</form>
    			<div style="position: absolute;">
    			    
    			</div>
    			<div class="p-0 m-l-30">
    			    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="check" style="width: auto;top:7px;left:7px">
                        <input type="checkbox" id="check" class="mdl-checkbox__input" <?php echo isset($checkLogin) ? $checkLogin : null;?>>
                        <span class="mdl-checkbox__label mdl-typography--body-2 mdl-typography--font-light " style="color: #757575;">Recordarme</span>
                    </label>
    			    <div class="" style="float:right;margin-right: 26px;">
        			    <button type="button" class="google-button mdl-shadow--2dp" onclick="logRed($(this));" data-href_url="<?php echo isset($authUrl) ? $authUrl : null;?>" style="margin-right:5px">
                          <span class="google-button__icon">
                              <svg viewBox="50 80 350 350"><path d="M211.9 197.4h-36.7v59.9h36.7V433.1h70.5V256.5h49.2l5.2-59.1h-54.4c0 0 0-22.1 0-33.7 0-13.9 2.8-19.5 16.3-19.5 10.9 0 38.2 0 38.2 0V82.9c0 0-40.2 0-48.8 0 -52.5 0-76.1 23.1-76.1 67.3C211.9 188.8 211.9 197.4 211.9 197.4z" fill="#3b5998"></path></svg>
                          </span>
                        </button>
                        
                        <button type="button" class="google-button mdl-shadow--2dp" onclick="logRed($(this));" data-href_url="<?php echo isset($authUrlGoogle) ? $authUrlGoogle : null;?>" >
                          <span class="google-button__icon">
                              <svg viewBox="0 0 366 372"><path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335"></path><path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05"></path><path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4"></path><path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853"></path></svg>
                          </span>
                        </button>
                        
                        <button type="button" class="google-button mdl-shadow--2dp" onclick="logRed($(this));" data-href_url="<?php echo isset($authUrlOutlook) ? $authUrlOutlook : null;?>" style="display:none;">
                          <span class="google-button__icon">
                              <img src="<?php echo RUTA_IMG?>/outlook.svg" width="20" height="20">
                          </span>
                        </button>
                    </div>
    			</div>		
    			
    			
    		</div>
    		<div class="card alt" style="cursor: not-allowed;">
    			<div class="toggle" style="pointer-events:none;">
    			</div>
    			<h1 class="title">
    				Recuperar<br>contrase&ntilde;a
    				<div class="close"></div>
    			</h1>
    			<div class="alert alert-danger alert-dismissible m-20 m-b-0 m-t-0" role="alert" id="cont_error_google" style="position: relative; display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Espera!</strong> Error clave! 
                </div>
    			<form  class="m-30">  
    				<div class="input-container">
    				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="correo" id="correo" autofocus="autofocus" />
                            <label class="mdl-textfield__label" for="Password">Correo</label>
                        </div>
    				</div>
    				<div class="mdl-card_actions again">
        				<button onclick="enviarCorreo()" name="reestablecer" value="Reestablecer"
        					class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Reestablecer
        				</button>
        			</div>
    			</form>
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
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"/></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>	
    	<script src="<?php echo RUTA_JS?>libs/spin.js/spin.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/autosize/jquery.autosize.min.js"></script>
    	<script src="<?php echo RUTA_JS?>core/cache/63d0445130d69b2868a8d28c93309746.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jslogic/jslogin.js"></script>
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
    				$('select').selectpicker();
      			    $("#input").click(function(){
      			        $("#showpas").fadeIn();     			        
      			    });

        			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
            			$('.pickerButn').selectpicker('mobile');
            	    } else {
                	    $('.pickerButn').selectpicker();
            		 }
      			});
  		</script>
    </body>
</html>