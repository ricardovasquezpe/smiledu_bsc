<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Realizar Encuesta | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color"               content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
		<meta name="description"               content="Llena tu encuesta sobre el evento JSM y ayudanos a mejorar">
        <meta property="og:url"                content="<?php echo base_url()?>c_encuesta" />
        <meta property="og:type"               content="website"/>
        <meta property="og:title"              content="Encuesta Jesucristo Supermercedario" />
        <meta property="og:description"        content="Llena tu encuesta sobre el evento JSM y ayudanos a mejorar" />
        <meta property="og:image"              content="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_SENC;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/submenu.css">   
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/effects-schoowl.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/animate.css">        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_SENC?>css/logic/encuesta.css">
        
     </head>

	 <body>    
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>        		
            <header class='mdl-layout__header'>
                <div class='mdl-layout__header-row'>	    
                    <div class='mdl-layout-title'>
                        <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png" style="position: absolute; top: -10px; left: -50px;">
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;"><?php echo $tituloEncuesta?></h2>
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;">Libre</h2>
                    </div>
                    <div class='mdl-layout-spacer'></div>
                </div>
                <div class="mdl-layout__tab-bar" id="categorias">            
                </div>    
            </header>
        		
    	   <main class='mdl-layout__content is-visible'>	          
	          <section>
		          <div class="row-fluid">    	
    		          <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 p-0 card-width">
                        <div id="modal-init" style="display: block;">
                            <div class="cards" style="min-height: 400px !important">
                                <input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control" />
                                <div class="card card--init mdl-shadow--2dp p-30">
                                    <div class="row-fluid">
                                        <div class="col-xs-8 col-xs-offset-2 p-0">
                                            <img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
                                        </div>
                                        <div class="col-xs-12 p-0 m-0">
                                            <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10" id="failTitle">Hola, soy 'Steven'</h4>
                                            <?php if($flg_anonima == FLG_ANONIMA) {?>
                                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; 
                                                que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.<br>&#191;Eres ...?</h5>
                                            <?php } else if($flg_anonima == FLG_NO_ANONIMA && isset($flg_need_login) ) { ?>
                                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">Debes ingresar con tu usuario/clave para poder realizar la encuesta.</h5>
                                            <?php } else if($flg_anonima == FLG_NO_ANONIMA && !isset($flg_need_login) ) { ?>
                                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px" id="failSubTitle">Recuerda la encuesta no es an&oacute;nima.</h5>
                                            <?php } ?>
                                        </div>
                                        <div id="anonima">
                                            <?php if( ($isAperturada == 1 && $flg_anonima == FLG_ANONIMA) ||
                                                      ($isAperturada == 1 && $flg_anonima == FLG_NO_ANONIMA && !isset($flg_need_login) ) ) {?>
                                                <div class="col-xs-12 p-0 m-0 m-t-5">
                                                    <?php echo $arraTipoEncuestadoHTML; ?>	
                                                </div>
                                                <div class="col-xs-12 p-0 m-t-5 text-center">
                                                    <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="goToNextCard();" style="display: none">EMPEZAR</label>  
                                                </div>			
                                            <?php }?>
                                        </div>
                                        <?php if($flg_anonima == FLG_NO_ANONIMA && isset($flg_need_login) ) { ?>
                                            <div class="col-xs-12 p-0 m-t-5 text-center">
                                                <label for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="goToLogin()" style="display: block">IR AL LOGIN</label>  
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                                
                                <input id="vk" name="card-control" type="radio" class="card-control" />
                                <div class="card card--services mdl-shadow--2dp p-30">
                                    <div class="row">
                                        <div class="col-xs-12 p-0">
                                            <h2 class="mdl-typography--title mdl-typography--font-regular m-0 m-b-10">Antes br&iacute;ndanos unos datos</h2>
                                        </div>
                                        <div class="col-xs-12 p-0" id="divCombosPadreAlum">
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                                <div class="mdl-select">
                                                    <select id="cmbSede" name="cmbSede" data-live-search="true" class="form-control pickerBut" onchange="getGradosNivel();">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="GradoNivel">
                                                <div class="mdl-select">
                                                    <select id="cmbGradoNivel" name="cmbGradoNivel" data-live-search="true" class="form-control pickerButn" onchange="getAulasByGradoNivel();">
                                                        <option value="">Seleccione Grado - Nivel</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="Aulas">
                                                <div class="mdl-select">
                                                    <select id="cmbAula" name="cmbAula" data-live-search="true" class="form-control pickerButn" onchange="selectAula();">
                                                        <option value="">Seleccione Aula</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="divCombosAdmin">
                                            <div class="mdl-select" id="divAdmin">
                                                    <select id="cmbAdmin" name="cmbAdmin" data-live-search="true" class="form-control pickerBut" onchange="selectAreaGeneral()">
                                                        <option value="">Seleccione &Aacute;rea General</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="divCombosNiveles">
                                            <div class="mdl-select" id="divNiveles">
                                                    <select id="cmbDocente" name="cmbDocente" data-live-search="true" class="form-control pickerBut" onchange="getAreaEbySede();">
                                                        <option value="">Seleccione Nivel</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="divCombosAreaEsp">
                                            <div class="mdl-select" id="areaEsp">
                                                    <select id="cmbAreaEsp" name="cmbAreaEsp" data-live-search="true" class="form-control pickerBut" onchange="selectAreaEsp()">
                                                        <option value="">Seleccione &Aacute;rea Espec&iacute;fica</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12" id="divPregInicial" style="display: none;"></div>
                                        
                                        <a id="empezar" class="btn-go" onclick="initEncuesta();" style="display: none">&#161;EMPEZAR YA!</a>
                                        <a id="listo" class="btn-go" onclick="initEncuesta()">LISTO!</a>
                                    </div>
                                </div>
                                <!-- <div class="main-nav">
                                    <label for="dribbble" class="btn active">TOUR DE AYUDA</label>
                                    <?php if($isAperturada == 1 ){?>
                                        <label id="btnEmpezarUno" for="vk" class="btn" onclick="goToNextCard();" style="display: none">EMPEZAR</label>  
                                    <?php }?>
                                </div> -->
                                <p class="softhy">2016, creado por <strong><a href="http://softhy.pe/" target="_blank" style="text-decoration: none; color: #757575;">Softhy</a></strong> - Avantgard</p>
                                </div>   
                            </div>
                        </div>     
                   </div>                         
                   <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect" style="display: none" id="contEnc">                                    
                                                                                                                    		                                		                                                                  
                   </div>                                                 
                </section>
                <section class="p-rl-0" id="preguntas">
                      <!-- preguntasHTML -->
                </section> 
                <div class="position-progress" id="barraProgreso" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 99999; display: none">
                    <div class="progress progress-striped active m-0">
        				<div class="progress-bar progress-bar-warning" id="progressBar">
        					<div class="question-count" id="divAvance"></div>
        				</div>
                    </div>
                </div>
            </main>   
        </div>
        
        <div class="bg-modal"></div>  
                            
        <div class="fab">
    		<i class="mdi mdi-send fab-icon"></i>
    		<form class='cntt-wrapper'> 
    		    <div id="fab-hdr">
					<h4>Propuesta de mejora</h4>
    			</div>
    			<div class="cntt">
		          <div class="row-fluid">
						<div class="col-xs-12 p-0 m-0 m-t-20 " id="divSelect">       					     
					        <select class="form-control pickerButn" data-none-selected-text="Seleccione sus propuestas" id="selectPropM" data-live-search="true" multiple onchange="selectPropMejora()">
					           <?php echo $arraPropMHTML?>      	 
							</select>	       
					    </div>
					    <div class="col-sm-12 p-0 m-0 m-t-10">      
                            <div class="mdl-input-button-group text-right">    
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" maxlength="200" name="newPropM" id="newPropM" onkeyup="activarBtnAgregar()" value="">
                                    <label class="mdl-textfield__label" for="newPropM">Nueva Propuesta</label>                                        
                                </div>
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color-text--grey-500" onclick="envNuevaPropM();" id="nuevaPropM" name="nuevaPropM">
                                    
                                </button>
                            </div>
                        </div>
					    <div class="col-xs-12 p-0 m-0" id="comentarioPropM">
					       <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
					           <textarea class="mdl-textfield__input" type="text" id="textAPropM" name="textAPropM" rows="4" disabled="disabled"></textarea>
					           <label class="mdl-textfield__label" for="textAPropM">&#191;Qu&eacute; opinas al respecto&#63;</label>    					           
					       </div>      					    
					    </div>
					</div>
			     </div>
    			<div class="btn-wrapper">
    			     <div class="send" id="sendEncuesta">
						<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="siVista" name="siVista" 
						    style="float: right" onclick="enviarEncuesta()">enviar</button>
						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="noVista" name="noVista"
						    style="float: right" >editar</button>
					</div>
        			 <div class="finish">
						<button style="display: none" class="btn" 
						    onclick="window.close();">salir</button>
					</div>
    			</div> 
			</form>
		</div>
		
		<div id="modalFinal" style="display: none;">
		     <div class='mdl-header'></div>
             <main>
                <section>
                    <div class="mdl-content-cards">
                        <div id="modalFinalEnc" class=" mdl-card">
                            <div class="mdl-card__title">
                                <img class="img-responsive"src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
                            </div>
                            <div class="mdl-card__supporting-text br-b">
                                <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">MUCHAS GRACIAS POR SU PACIENCIA</h4>
                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20">Esta informaci&oacute;n nos ayudar&aacute; a mejorar y darte un mejor servicio.</h5>
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
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>        
    	<script src="<?php echo RUTA_PLUGINS?>clientjs-master/dist/client.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script> 
    	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsencuestanew.js"></script>
    	<script>
             	// Inicializar sonido evento
 	            var finishSound = createsoundbite('<?php echo RUTA_PUBLIC_SENC?>css/sound/sound_finish_survey.mp3');
             	var arraFavoPropM = <?php echo $arrayFavProp?>;
//              	initMagicSuggest();
                
				var ci = 0;
                $('.list-icon').click(function() {                	
              	 if(ci != 0){
              		 $('.list-icon').each(function() {
                           $(this).removeClass('selected');
                     });
                  }
              	$(this).addClass('selected');
                   ci = 1;	
                });

             	var datosClient = [];
             	var client = new ClientJS();

             	var ratio = window.devicePixelRatio || 1;

             	var is_touch_device = 'ontouchstart' in document.documentElement;
             	var touch_status = (is_touch_device) ? 'SI' : 'NO';
             	
             	datosClient.push({
          			browser   : client.getBrowser()+' '+client.getBrowserVersion(),
          			sist_oper : client.getOS()+' '+client.getOSVersion(),
          			device    : client.getDevice(),
          			device_tipo : client.getDeviceType(),
          			device_vendor : client.getDeviceVendor(),
          			resolution_device :  screen.width * ratio+'x'+screen.height * ratio,
                    touch : touch_status
      		    });
        </script>
        
        <script type="text/javascript">
        	$("#inputPropM").on("click", function(){
                $(this).find("label.control-label").addClass("active-input");
        	});

        	$("#comentarioPropM").on("click", function(){
        		$(this).find("label.control-label").addClass("active-input");
        	});
        	
        	$('#selectPropM').selectpicker({noneSelectedText: 'Seleccione sus propuestas'});
        	$(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
        	});

			$('#newPropM').on('blur', function() {
        		$('.fab.active').removeClass('bottom');
        	});

        	$(".bg-modal").on('click', function(event) {
        		if(!$('.fab.active').hasClass('bottom')) {
        			$('.fab.active').removeClass('bottom');
        		}
        	});

        	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
				$('.pace .pace-progress').css('background-color', 'transparent');
        	}
        	
        </script>
    </body>
</html>