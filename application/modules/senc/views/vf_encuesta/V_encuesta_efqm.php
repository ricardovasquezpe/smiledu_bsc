<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Encuesta EFQM | <?php echo NAME_MODULO_SENC;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color"            content="<?php echo COLOR_BARRA_ANDROID_SENC?>">
		<meta name="description"            content="Llena tu encuesta sobre el evento JSM y ayudanos a mejorar">
        <meta property="og:url"             content="<?php echo base_url()?>c_encuesta" />
        <meta property="og:type"            content="website"/>
        <meta property="og:title"           content="Encuesta Padre de Familia EFQM" />
        <meta property="og:description"     content="Llena tu encuesta sobre el evento JSM y ayudanos a mejorar" />
        <meta property="og:image"           content="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png"/>
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
        <?php if(_get('aula') != null && isset($realizoLocal) && $realizoLocal == 0) {?>
        <!-- encuesta para padres segun el hijo -->
            <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">        		
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">	    
                        <div class="mdl-layout-title">
                            <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png" style="position: absolute; top: -10px; left: -50px;">
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Encuesta EFQM</h2>
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;">
                                <?php if(_decodeCI(str_replace(" ","+", _get('tipo'))) == TIPO_ENCUESTA_PADREFAM) { ?>
                                    Padre de Fam.
                                <?php } else { ?>
                                    Estudiantes
                                <?php } ?>
                            </h2>
                        </div>
                        <div class="mdl-layout-spacer"></div>
                    </div>
                    <div class="mdl-layout__tab-bar" id="categorias"></div>
                </header>
                <main class="mdl-layout__content is-visible">
                    <section>
                        <div class="row-fluid">
                            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 card-width">
                                <div id="modal-init" style="display: block;" >
                                    <div class="cards" style="min-height: 400px !important">
                                        <input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control" />
                                        <?php if(_decodeCI(str_replace(" ","+", _get('tipo'))) == TIPO_ENCUESTA_PADREFAM) { ?>
                                            <div class="card card--init mdl-shadow--2dp p-30"">
                                                <div class="row-fluid">
                                                    <div class="col-xs-8 col-xs-offset-2 p-0">
                                                        <img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png" style="max-width:180px;">
                                                    </div>
                                                    <div class="col-xs-12 p-0 m-0">
                                                        <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                                                        <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.</h5>
                                                        <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20" style="line-height: 17px">(*)Preguntas obligatorias</h5>
                                                    </div>
                                                    <div class="col-xs-12 p-0 p-r-5 m-t-5 text-center 1">
                                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="width:90%;">EMPEZAR</label>  
                                                    </div>					
                                                </div> 
                                            </div>
                                            <input id="vk" name="card-control" type="radio" class="card-control" />
                                            <div class="card card--services mdl-shadow--2dp p-30">
                                                <div class="row-fluid text-center">
                                                <div class="col-xs-12" style="padding: 0px;">
                                                    <h2 class="mdl-typography--title m-t-10 m-b-30"><i class="mdi mdi-tag"></i> &#191;Qu&eacute; servicio(s) usa&#63;</h2>
                                                </div>
                                                <?php echo $arraServiHTML?>
                                                <a class="btn-go" id="empezar" onclick="initEncuesta()" style="display: block">&#161;EMPEZAR YA&#33;</a>
                                                <a class="btn-go" id="listo" onclick="initEncuesta()">LISTO!</a>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="card card--init mdl-shadow--2dp p-30">
                                                <div class="row-fluid">
                                                    <div class="col-xs-8 col-xs-offset-2 p-0">
                                                        <img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
                                                    </div>
                                                    <div class="col-xs-12 p-0 m-0">
                                                        <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                                                        <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.<br></h5>
                                                    </div>                                                	
                                                    <div class="col-xs-12 p-0 p-r-5 m-t-5 text-center 2">
                                                        <?php if($this->session->userdata('idEncuestaActiva') != null){?>
                                                            <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="display: block">EMPEZAR</label>
                                                        <?php }?> 
                                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="display: none">EMPEZAR</label>
                                                    </div>					
                                                </div>
                                            </div>
                                            <input id="vk" name="card-control" type="radio" class="card-control" />
                                            <div class="card card--services mdl-shadow--2dp p-30">
                                                <div class="row-fluid text-center">
                                                    <div class="col-xs-12" style="padding: 0px;">
                                                        <h2 class="mdl-typography--title m-t-10 m-b-30"><i class="mdi mdi-tag"></i> &#191;Qu&eacute; servicio(s) usa&#63;</h2>
                                                    </div>
                                                    <?php echo $arraServiHTML?>
                                                    <a class="btn-go" id="empezar" onclick="initEncuesta()" style="display: block">&#161;EMPEZAR YA&#33;</a>
                                                    <a class="btn-go" id="listo" onclick="initEncuesta()">LISTO!</a>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>   
                                </div>
                            </div>
                            <div class="section-body m-0 p-0">                                
                                <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect" style="display: none" id="contEnc"></div>
                            </div>
                        </div>
                    </section>
                    <section id="preguntas"></section>
                    <div class="position-progress" id="barraProgreso" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 2; display: none">
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
                        <h4>Propuesta de Mejora</h4>
                    </div>
                    <div class="cntt">
                        <div class="row-fluid">
                            <div class="col-xs-12 p-0 m-t-20 m-b-10" id="divSelect">
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
                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color-text--grey-500" onclick="envNuevaPropM();" id="nuevaPropM" name="nuevaPropM"></button>
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
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="siVista" name="siVista" style="float: right" onclick="enviarEncuesta();">enviar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="noVista" name="noVista" style="float: right" >editar</button>
                        </div>
                        <div class="finish">
                            <button style="display: none" class="btn" onclick="window.top.close();">salir</button>
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
                                <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="siVista" name="siVista" onclick="redirect('<?php echo RUTA_SMILEDU;?>','<?php echo _simple_encrypt(_getSesion('idEncuestaActiva')); ?>');">Siguiente</button>
                                    <form id="siguienteForm" action="c_encuesta_efqm/redirect" method="post"></form>
                                </div>
                            </div>
                        </div>
                    </section>
                 </main>
            </div>
        
            
        <?php } else if(_get('codfam') != null) { ?>  <!--padre de familia inicial-->
        
            <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                        <div class="mdl-layout-title">
                            <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png"style="margin-right: 10px; position: absolute; top: -10px; left: -50px;">
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Encuesta EFQM</h2>
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;">Padre de Fam.</h2>
                        </div>
                    </div>
                </header>
    	        <main class="mdl-layout__content is-visible">
    	          <section>
        		      <div class="row-fluid">
        		          <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 card-width">
    							<div id="modal-init" style="display: block;">
    								<div class="cards">
    									<input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control"/>
    									<div class="card card--services">
    										<div class="row-fluid">
    											<div class="col-xs-12 p-0">
    												<h2 class="mdl-typography--title m-t-20 m-b-30">Selecciona un hijo</h2>
    											</div>
    											<div class="col-sm-12">
    											   <?php echo $hijosFam?>
    											</div>
    						                    <a class="btn-go" onclick="redirectLogin()">SALIR</a>
    										</div>
    									</div>
    								</div>
        	                   </div>
    					  </div>
    			     </div>
    			  </section>
    		    </main>
    		</div>
    		
    	<?php } else if(isset($_GET['enc_aula']) && (_getSesion(SENC_ROL_SESS) == ID_ROL_DOCENTE || _getSesion(SENC_ROL_SESS) == ID_ROL_OPERADOR_TICE || _getSesion(SENC_ROL_SESS) == ID_ROL_DIRECTOR_TI)) {?>
    	<!-- encuesta para alumnos -->
        <?php if($this->m_encuesta->countEncuestaByTipoEnc(TIPO_ENCUESTA_ALUMNOS) == 1) { ?>
            <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                        <div class="mdl-layout-title">
                            <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png" style="margin-right: 10px; position: absolute; top: -10px; left: -50px;">
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Encuesta EFQM</h2>
                            <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;">Para Alumnos</h2>
                        </div>
                        <div class="mdl-layout-spacer"></div>
                    </div>
                    <div class="mdl-layout__tab-bar" id="categorias"></div>
                </header>
                <main class="mdl-layout__content is-visible">
                    <section>
                        <div class="row-fluid">
                            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 card-width">
                                <div id="modal-init" style="display: block;">
                                    <div class="cards">
                                        <input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control" />
                                        <div class="card card--init mdl-shadow--2dp p-30" style="padding-top: 90px !important; padding-bottom: 90px !important;">
                                            <div class="row-fluid">
                                                <div class="col-xs-8 col-xs-offset-2 p-0">
                                                    <img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
                                                </div>
                                                <div class="col-xs-12 p-0 m-0">
                                                    <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                                                    <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.</h5>
                                                </div>
                                                <div class="col-xs-12 p-0 m-t-5 text-center 3">
                                                    <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="initEncuestaDocente();">EMPEZAR</label> 
                                                </div>
                                            </div>
                                        </div>
                                        <input id="vk" name="card-control" type="radio" class="card-control"/>
                                        <div class="card card--services mdl-shadow--2dp">
                                            <div class="row-fluid">
                                                <div class="col-xs-12 p-0">
                                                    <h2 class="mdl-typography--title m-t-20 m-b-30">Selecciona el aula</h2>
                                                </div>
                                                <div class="col-xs-12 p-0 p-r-20 p-l-20 m-0 m-b-20">
                                                    <select id="selectSede" name=selectSede data-live-search="true" class="pickerButn" onchange="getGradosNivel()"></select>
                                                </div>
                                                <div class="col-xs-12 p-0 p-r-20 p-l-20 m-0 m-b-10">
                                                    <select id="selectGradoNivel" name=selectGradoNivel data-live-search="true" class="pickerButn" onchange="getAulasByGradoNivel();"></select>
                                                </div>
                                                <div class="col-xs-12 p-0 p-r-20 p-l-20 p-0 m-0 m-b-20">
                                                    <select id="selectAula" name="selectAula" data-live-search="true" class="pickerButn" onchange="getInfoAulaElegida();"></select>
                                                </div>
                                                <div id="infoAulaElegida"  name="infoAulaElegida" style="display: none;">
                                                    <div class="col-xs-12" id="contUrlGenerada">
                                                        <p>URL Generada:</p>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="modal('modal_url_generada')">
                                                            <i class="mdi mdi-flip_to_front" style="color: #FFFFFF"></i>
                                                        </button>
                                                        <input type="text" style="color: #FFFFFF; margin-bottom: 30px; background-color: transparent; border: none; border-bottom: 1px solid #FFFFFF; outline: none" id="urlGenerada" />
                                                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="actualizarCantAlumnos()" id="btnActAlumnos">
                                                            <i class="mdi mdi-refresh" style="color: #FFFFFF"></i>
                                                        </button>
                                                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="lockAulaEncuestaEFQM_Estu()" id="btnLockAlum">
                                                            <i class="" style="color: #FFFFFF"></i>
                                                        </button>
                                                    </div>
                                                	<div class="col-xs-6 col-xs-offset-2 text-left p-0">
                                                		<p># de estudiantes</p>
                                                	</div>
                                                	<div class="col-xs-2 p-0 text-right">
                                                		<p id="cantEstudiantes"></p>
                                                	</div>
                                                	<div class="col-xs-6 col-xs-offset-2 text-left p-0">
                                                		<p># de encuestas realizadas</p>
                                                	</div>
                                                	<div class="col-xs-2 p-0 text-right">
                                                		<p id="cantEncRealizadas">0</p>
                                                	</div>
                                                </div>
                                                <a class="btn-go" onclick="window.close();">SALIR</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        
            <div id="modal_url_generada" class="modal fade in" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="mdl-card mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">URL</h2>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <div class="row-fluid">
                                    <p id="urlGenerada1" style="font-size: 104px; line-height: 1;"></p>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php } else { ?>
        
            <div id="modal-init" class="row-fluid">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 card-width m-t-30">
                    <div class="mdl-card mdl-shadow--2dp text-center" style="opacity: 1">
                        <div class="mdl-card__title">
                            <img class="img-responsive" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png" style="margin: auto; max-height: 150px;">
                        </div>
                        <br>
                        <div class="mdl-card__supporting-text">
                            <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                            <?php if(isset($msj)) { ?>
                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px"><?php echo $msj;?></h5>	
                            <?php }?>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="logoutEncuestaDocente()">SALIR</button>
                        </div>
                    </div>
                </div>
                <div class="main-nav"> 
                    <?php if($this->session->userdata('idEncuestaActiva') != null && !isset($msj)) { ?>
                        <label id="btnEmpezarUno" for="dribbble" class="btn" onclick="evaluaServiciosByEncuesta()" style="display: block">EMPEZAR</label>
                    <?php }?>
                </div>
            </div>
            
        <?php } ?>
        <?php } else if(isset($encu_fisicaHTML)) {
            echo $encu_fisicaHTML; //HTML CON COMBOS PARA ENTRAR A LA ENCUESTA
        } else if (_getSesion('realizo_encuesta') == NO_REALIZO_ENCUESTA && count($_GET) == 0) { ?>
            <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                    <div class="mdl-layout-title">
                        <img alt="Logo" src="<?php echo RUTA_IMG?>iconsSistem/icon_encuestas_blanco.png" style="margin-right: 10px; position: absolute; top: -10px; left: -50px;">
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-medium" style="float: left;padding-right: 15px;border-right: 1px solid;">Encuesta EFQM</h2>
                        <h2 class="mdl-card__title-text mdl-color-text--white mdl-typography--font-light" style="float: right;padding-left: 15px;"><?php echo isset($tipoEncuestaDesc) ? $tipoEncuestaDesc : null;?></h2>
                    </div>
                    <div class="mdl-layout-spacer"></div>
                    </div>
                    <div class="mdl-layout__tab-bar" id="categorias"></div>
                </header>
                <main class='mdl-layout__content is-visible'>
                    <section>
                        <div class="row-fluid">
                            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 card-width">
                                <div id="modal-init" style="display: block;">
                                    <div class="cards">
                                        <input id="dribbble" name="card-control" type="radio" checked="checked" class="card-control"/>
                                        <div class="card card--init mdl-shadow--2dp p-30">
                                            <div class="row-fluid">
                                                <div class="col-xs-12 p-0">
                                                    <img class="img--init" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png">
                                                </div>
                                                <div class="col-xs-12 p-0 m-0">
                                                    <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                                                    <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px">No te preocupes, cuidar&eacute; 
                                                    que tu identidad se mantenga completamente AN&Oacute;NIMA en esta encuesta.<br></h5>
                                                    <?php if(isset($msj)) { ?>
                                                        <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px"><?php echo $msj;?></h5>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-xs-12 p-0 p-r-5 m-t-5 text-center 4">
                                                    <?php if(_getSesion('idEncuestaActiva') != null && !isset($msj)) { ?>
                                                        <label id="btnEmpezarUno" for="vk" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="evaluaServiciosByEncuesta();" >EMPEZAR</label>
                                                    <?php }?>
                                                </div>
                                                <div class="col-xs-12 p-0 p-r-5 m-t-5 text-center 4">
                                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-0" onclick="logoutEncuestaDocente()">SALIR</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="vk" name="card-control" type="radio" class="card-control" />
                                        <div class="card card--services mdl-shadow--2dp  p-30">
                                            <div class="row-fluid">
                                                <div class="col-xs-12" style="padding: 0px;">
                                                    <h2 class="mdl-typography--title m-t-10"><i class="mdi mdi-tag"></i> &#191;Qu&eacute; servicio(s) usa&#63;</h2>
                                                </div>
                                                <?php echo $arraServiHTML?>
                                                <a class="btn-go" id="empezar" onclick="initEncuesta()" style="display: block">&#161;EMPEZAR YA&#33;</a>
                                                <a class="btn-go" id="listo" onclick="initEncuesta()">LISTO!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="preguntas"></section>
                    <div class="position-progress" id="barraProgreso" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 2; display: none">
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
    					<h4>Propuesta de Mejora</h4>
        			</div>
        			<div class="cntt">
    		            <div class="row-fluid">
    					   	<div class="col-xs-12 p-0 m-t-20 m-b-10" id="divSelect">
        					    <select class="form-control pickerButn" data-none-selected-text="Seleccione sus propuestas" id="selectPropM" data-live-search="true" multiple onchange="selectPropMejora()">
        					        <?php echo $arraPropMHTML; ?>
        					    </select>
        					</div>
    					    <div class="col-sm-12 p-0 m-t-10 m-b-10">
                                <div class="mdl-input-button-group text-right">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" maxlength="200" name="newPropM" id="newPropM" onkeyup="activarBtnAgregar()" value="">
                                        <label class="mdl-textfield__label" for="newPropM">Nueva Propuesta</label>
                                    </div>
                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color-text--grey-500" onclick="envNuevaPropM();" id="nuevaPropM" name="nuevaPropM">
                                    </button>
                                </div>
                            </div>
    					    <div class="col-xs-12 p-0 m-t-10 m-b-10" id="comentarioPropM">
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
    						<button style="display: none" class="btn" onclick="window.close();">salir</button>
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
            
    	<?php } else if(_getSesion('realizo_encuesta') == REALIZO_ENCUESTA || isset($realizoLocal) && $realizoLocal == 1) { ?>
            <div id="modal-init" class="row-fluid">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 card-width m-t-30">
                    <div class="mdl-card mdl-shadow--2dp text-center" style="opacity: 1">
                        <div class="mdl-card__title">
                            <img class="img-responsive" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_sunglasses.png" style="margin: auto; max-height: 150px;" >
                        </div>
                        <br>
                        <div class="mdl-card__supporting-text">
                            <h4 class="mdl-typography--subhead  mdl-typography--font-regular m-0 m-b-10">Hola, soy 'Steven'</h4>
                            <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20"">Usted ya termin&oacute; de llenar su encuesta, pr&oacute;ximamente le traeremos otra.</h5>
                            <?php if(isset($msj)) { ?>
                                <h5 class="mdl-typography--body-2 mdl-typography--font-regular mdl-color-text--grey-500 m-0 m-b-20 " style="line-height: 17px"><?php echo $msj;?></h5>	
                            <?php } ?>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="logoutEncuestaDocente()">SALIR</button>
                        </div>
                    </div>
                </div>
                <div class="main-nav">
                    <?php if(_getSesion('idEncuestaActiva') != null && !isset($msj)) { ?>
                        <label id="btnEmpezarUno" for="dribbble" class="btn" onclick="evaluaServiciosByEncuesta()" style="display: block">EMPEZAR</label>
                    <?php } ?>  
                </div>
            </div>
    	<?php }?>
    	
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
	    <?php if (_getSesion('realizo_encuesta') == NO_REALIZO_ENCUESTA || _get('aula') != null || _get('codfam') != null || isset($_GET['enc_aula'])) { ?>
        	<script src="<?php echo RUTA_PUBLIC_SENC?>js/jsencuestaNewEfqm.js"></script>
    	<?php } ?>
    	<script type="text/javascript">
    	   <?php if (_getSesion('realizo_encuesta') == NO_REALIZO_ENCUESTA || _get('aula') != null) { ?>
   	           if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
   	        	   $('#selectPropM').selectpicker('mobile');
   	        	   $('#selectSede').selectpicker('mobile');
      	           $('#selectGradoNivel').selectpicker('mobile');
      	           $('#selectAula').selectpicker('mobile');
        	   } else {
        		   $('#selectPropM').selectpicker({noneSelectedText: 'Seleccione sus Propuestas'});
        		   $('#selectSede').selectpicker({ });
        		   $('#selectGradoNivel').selectpicker({ });
        		   $('#selectAula').selectpicker({ });
       		   }
               	// Inicializar sonido evento
               	var finishSound = createsoundbite('<?php echo base_url()?>public/general/sound/sound_finish_survey.mp3');
               	$(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });
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
           	<?php }?>
        </script>
        
        <script type="text/javascript">
        	$("#inputPropM").on("click", function(){
                $(this).find("label.control-label").addClass("active-input");
        	});
        	$("#comentarioPropM").on("click", function() {
        		$(this).find("label.control-label").addClass("active-input");
        	});
        	$('#selectPropM').selectpicker({noneSelectedText: 'Seleccione sus propuestas'});
			$('#newPropM').on('focus', function() {
        		$('.fab.active').addClass('bottom');
        	});
			$('#newPropM').on('blur', function() {
        		$('.fab.active').removeClass('bottom');
        	});
        	$(".bg-modal").on('click', function(event) {
        		if(!$('.fab.active').hasClass('bottom')) {
        			$('.fab.active').removeClass('bottom');
        		}
        	});
        	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
				$('.pace .pace-progress').css('background-color', 'transparent');
        	}

        	function logoutEncuestaDocente(){
        		$.ajax({
        			url   : 'c_encuesta_efqm/logoutAux',
        			async : false,
        			type  : 'POST'
        		})
        		.done(function(data){
        			window.location = data; 
        		});
        	}
        </script>
    </body>
</html>