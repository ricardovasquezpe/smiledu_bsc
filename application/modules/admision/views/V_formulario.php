<!DOCTYPE html>
<html lang="en">
    <head>
		<title>Registro</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="#FF9200">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" /> 
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/formulario.css">
        
        <style type="text/css">
            body.bg-fab-black .opacidad-fab {
            	height: 100%;
            	background-color: transparent !important;
            } 
            
            ::-webkit-scrollbar {
                width: 3px;
                height: 5px;
            }

            @media ( max-width : 1040px) {
            	 .mdl-layout-title{
            	       margin-right: 100px !important ;
            	}
            }
        </style>
	</head>
	
	<body>
	    <header class="mdl-layout__header" style="display: none"></header>
	    <div class="header-body"></div>
	    <div class="mdl-layout-title text-center" style="padding: 15px;color: #fff;margin-right: 700px;margin-top:3vh;">
            <img style ="width: 30px;margin-right:10px;" src="http://buhooweb.com/smiledu/public/general/img/iconsSistem/icon_admision_blanco.png">Formulario Admisi&oacute;n 2017
		</div>
        <div class="mdl-card mdl-formulario admision">
            <div class="mdl-card__supporting-text">   
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="form-wizard form-wizard-horizontal" id="rootwizard1">
                            <div class="form-wizard-nav">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary" Id ="progressBar"></div>
                                </div>
                                <ul class="nav nav-justified nav-pills">
                                    <li class="active m-b-20" id="li1">
                                        <a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1" onclick="progressBarByStep(1)" style="text-align: left;">
                                            <span class="step text-center"></span>
                                            <span class="title">Familiares</span>
                                        </a>
                                    </li>
                                    <li class="" id="li2" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab2" class ="my-link" id="step2" onclick="progressBarByStep(2)" style="margin-right: 25%;">
                                            <span class="step"></span>
                                            <span class="title" >Postulantes</span>
                                        </a>
                                    </li>
                                    <li class="" id="li3" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab3" class ="my-link" id="step3" onclick="progressBarByStep(3)" style="margin-left: 25%;">
                                            <span class="step"></span>
                                            <span class="title">T&eacute;rminos y condiciones</span>
                                        </a>
                                    </li>
                                    <li class="" id="li4" class ="my-link">
                                        <a data-toggle="tab" aria-expanded="false" href="#tab4"  class ="my-link" id="step4" onclick="progressBarByStep(4)" style="text-align: right;">
                                            <span class="step text-center"></span>
                                            <span class="title" style="margin-left: -10px;">Evento</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
						</div>
                    </div>
                    <div class="col-xs-12">
                    	<div class="tab-content" >
                            <div class="tab-pane active" id="tab1">
		                    	<div class="tab-pane" id="tab-addperson-fam">
		                    		<div id="cont_cards_familia2"></div>
		                    		<div class="img-search" id="cont_addperson">
		                                <img src="<?php echo RUTA_IMG?>smiledu_faces/addperson.png">
		                                <p><strong>&#161;Hola!</strong></p>
		                                <p>Puedes registrar un familiar</p>
		                                <p>desde el bot�n flotante.</p>
		                            </div>
		                        </div>
		                        <div style="display:none" id="tab-datos-fam">
	                                <div class="col-sm-12 mdl-card-supporting__title">
	                                    <h2 class="mdl-card__title-text">Familiares</h2>
	                                </div>
	                                <div class="col-sm-12 text-left" id="cont_parientes"></div>
	<!--                                 <div class="col-sm-12 text-left cont-agregar p-b-15" id="cont_agregar">
	                                    <button class="btn-big mdl-chip__button" id="btnAgregarPariente" onclick="guardarPariente()" style="top:8.5px"><i class="mdi mdi-save" style="top:8px"></i></button>
	                                        <div class="mdl-tooltip" for="btnAgregarPariente">Guardar</div> -->
	<!--                                 </div> -->
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-supervisor_account"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectParentesco" name="selectParentesco" class="form-control selectButton datoFam" data-live-search="true" 
						                    data-noneSelectedText="Selec. Parentesco<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?>" onchange="editarPariente(); camposFam()" attr-obli= "12">
	            			                <option value="">Selec. Parentesco<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                <?php echo $comboParentesco?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-account_circle"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="apellidoPaternoPariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*"  name="apellidoPaternoPariente" maxlength="100" onchange="editarPariente(); camposFam()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="apellidoPaternoPariente">Apellido paterno del pariente<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>  
	                                        <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                          
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="apellidoMaternoPariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoMaternoPariente" maxlength="100" onchange="editarPariente(); camposFam()" attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="apellidoMaternoPariente">Apellido materno del pariente<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label> 
	                                        <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                           
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="nombrePariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="nombrePariente" maxlength="100" onchange="editarPariente(); camposFam()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="nombrePariente">Nombre del pariente<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>                            
	                                        <span class="mdl-textfield__error">Nombre solo debe contener letras</span>  
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group" <?php echo $mostrar?>>
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-share"></i>
	                                    </div>
	                                    <div class="mdl-select s-overflow">
	                                        <select id="selectCanal" name="selectCanal" class="form-control selectButton" data-live-search="true" 
						                    onchange="editarPariente(); camposFam()" attr-obli= "12" attr-obli= "2">
						                    <option value="">Selec. Canal de comucaci&oacute;n<?php echo (isset($obligatorio) ? $obligatorio: null)?></option>
	                                            <?php echo $comboCanales?>
	    						            </select>
							            </div>
	                                </div>
						            <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-contact_phone"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="telefonoPariente" pattern="[(+0-9+)-0-9, ]+" name="telefonoPariente" maxlength="10" onchange="editarPariente(); camposFam()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="telefonoPariente">Tel&eacute;fono fijo del pariente (*)</label>   
	                                        <span class="mdl-textfield__error">N&uacute;mero fijo solo debe contener n&uacute;meros</span>                                                                  
	                                    </div>
	                                </div>
						            <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-phone"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="celularPariente" pattern="[(+0-9+)-0-9, ]+" name="celularPariente" maxlength="12" onchange="editarPariente(); camposFam()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="celularPariente">Celular del pariente (*)</label>    
	                                        <span class="mdl-textfield__error">N&uacute;mero de celular solo debe contener n&uacute;meros</span>                         
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-chrome_reader_mode"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectTipoDocumento" name="selectTipoDocumento" class="form-control selectButton datoFam" data-live-search="true" 
						                   data-noneSelectedText="Selec. tipo de documento<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?>" 
						                    onchange="editarPariente(); habilitarCampo('selectTipoDocumento','nroDocumento'); changeMaxlength('selectTipoDocumento','nroDocumento'); camposFam()" attr-obli= "1">
	            			                <option value="">Selec. tipo de documento<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                    <?php echo $comboTipoDocumento?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="nroDocumento" name="nroDocumento" maxlength = "12" onchange="editarPariente(); camposFam()" disabled attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="nroDocumento">N&uacute;mero del documento<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>                            
	                                    </div>
	                                </div>
						            <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-wc"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectSexoPariente" name="selectSexoPariente" class="form-control selectButton datoFam" data-live-search="true" 
						                    data-noneSelectedText="Selec. sexo del pariente<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?>" onchange="editarPariente(); camposFam()" attr-obli= "1">
	            			                <option value="">Selec. sexo del pariente<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                    <?php echo $comboSexo?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-help"></i>
	                                    </div>
	                                    <div class="mdl-select s-overflow">
	                                        <select id="medioColegio" name="medioColegio" class="form-control selectButton datoFam" data-live-search="true" 
						                    onchange="editarPariente(); camposFam()" attr-obli= "1">
						                    <option value="">&iquest;C&oacute;mo te enteraste?<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	                                            <?php echo $comboMedioColegio?>
	    						            </select>
							            </div>
	                                </div>                                
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-location_on"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="departamentoFam" name="departamentoFam" class="form-control selectButton datoFam" data-live-search="true" 
						                    onchange="getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 2); editarPariente(); camposFam()" attr-obli= "1">
	    						                   <option value="">Selec. Departamento actual<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	    						                   <?php echo $comboDepartamento?>
	    						            </select>
							            </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-select">
	                                        <select id="provinciaFam" name="provinciaFam" class="form-control selectButton datoFam" data-live-search="true" 
						                    onchange="getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 3); editarPariente(); camposFam()" disabled attr-obli= "1">
	    						                   <option value="">Selec. Provincia actual<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	    						            </select>
							            </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-select">
	                                        <select id="distritoFam" name="distritoFam" class="form-control selectButton datoFam" data-live-search="true" 
						                    disabled onchange="editarPariente() ; habilitarCampo('distritoFam','referencia_domicilio'); camposFam()" attr-obli= "1">
	    						                   <option value="">Selec. Distrito actual<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	    						            </select>
							            </div>
	                                </div>
	                                <div class="col-sm-12 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-home"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
	                                        <input class="mdl-textfield__input datoFam" type="text" id="referencia_domicilio" name="referencia_domicilio" maxlength="300" onchange="editarPariente(); camposFam()" disabled attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="referencia_domicilio">Direcci&oacute;n del domicilio<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>                            
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
                                    	<div class="mdl-icon">
                                        	<i class="mdi mdi-settings_input_antenna"></i>
                                    	</div>
	                                    <div class="mdl-select">
	                                        <select id="selectOperador" name="selectOperador" class="form-control selectButton" data-live-search="true" 
						                    data-noneSelectedText="Selec. operador telef&oacute;nico" onchange="editarPariente(); camposFam()" attr-obli= "0">
	            			                <option value="">Selec. operador telef&oacute;nico</option>
	            			                    <?php echo $comboOperador?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-email"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input" type="text" id="correoPariente" pattern="[��,A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" name="correoPariente" maxlength="30" onchange="editarPariente(); camposFam()" attr-obli= "0">        
	                                        <label class="mdl-textfield__label" for="correoPariente">Correo del pariente</label>        
	                                        <span class="mdl-textfield__error">El formato del correo es incorrecto</span>                    
	                                    </div>
	                                </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="tab2">
		                    	<div class="tab-pane" id="tab-addperson-est">
		                    		<div id="cont_cards_familia2"></div>
		                    		<div class="img-search" id="cont_addperson">
		                                <img src="<?php echo RUTA_IMG?>smiledu_faces/addperson.png">
		                                <p><strong>&#161;Hola!</strong></p>
		                                <p>Puedes registrar un estudiante</p>
		                                <p>desde el bot�n flotante.</p>
		                            </div>
		                        </div>
		                        <div style="display:none" id="tab-datos-est">
	                                <div class="col-sm-12 mdl-card-supporting__title">
	                                    <h2 class="mdl-card__title-text">Postulantes</h2>
	                                </div>
	                                <div class="col-sm-12 text-left"  id="cont_postulantes"></div>
	<!--                                 <div class="col-sm-12 text-left cont-agregar p-b-15" id="cont_agregarPostulantes">
	                                    <button class="btn-big mdl-chip__button" id="btnAgregarPostulante" onclick="guardarPostulante()" style="top:8.5px"><i class="mdi mdi-save"></i></button>
	                                         <div class="mdl-tooltip" for="btnAgregarPostulante">Guardar</div> -->
	<!--                                 </div> -->
	                                <!--div class="col-sm-12 text-center">
	                                    <div class="" onclick="abrirSelectFotoPersona()">
	                                        <img src="<?php echo base_url()?>public/img/profile-default.png"  class="mdl-img" id="fotoPersonaImg" name="fotoPersonaImg">
	                                        <span class="caption fade-caption">
	                                            <i class="mdi mdi-photo_camera"></i>
	                                        </span>
	                                    </div>
	                                    <div class="" id="estadoPersona"></div>
	                                    <input type="file" id="fotoPersona" name="fotoPersona" accept="image/*" style="display:none">
	                                </div-->
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectProceso" name="selectProceso" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-none-selected-text="Selec. tipo proceso (*)" onchange="editarPostulante();camposPos()" multiple attr-obli= "12">
	            			                    <?php echo $optTiposCrono?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-account_circle"></i>
	                                    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoEst" type="text" id="apellidoPaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoPaternoPostulante" maxlength="100" onchange="editarPostulante();camposPos()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="apellidoPaternoPostulante">Apellido paterno del postulante<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>
	                                        <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                            
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoEst" type="text" id="apellidoMaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoMaternoPostulante" maxlength="100" onchange="editarPostulante();camposPos()" attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="apellidoMaternoPostulante">Apellido materno del postulante<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>  
	                                        <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                            
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoEst" type="text" id="nombrePostulante" name="nombrePostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" maxlength="100" onchange="editarPostulante();camposPos()" attr-obli= "12">        
	                                        <label class="mdl-textfield__label" for="nombrePostulante">Nombre del postulante<?php echo (isset($obligatorio) ? $obligatorio: null)?><?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>
	                                        <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                            
	                                    </div>
	                                </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon mdl-icon__button">
	    							        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFechaNacPostulante">
	    								       <i class="mdi mdi-date_range"></i>
	    							        </button>
	    							    </div>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
	                                        <input class="mdl-textfield__input datoEst" type="text" id="fechaNacPostulante" name="fechaNacPostulante" maxlength = "10" onchange="editarPostulante();camposPos()" attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="fechaNacPostulante">Fecha de nacimiento del postulante<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label> 
	                                    </div>
	                                </div>
						            <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-wc"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectSexoPostulante" name="selectSexoPostulante" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-noneSelectedText="Selec. Sexo del postulante<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?>" onchange="editarPostulante();camposPos()" attr-obli= "1">
	            			                <option value="">Selec. Sexo del postulante<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                    <?php echo $comboSexo?>
	            			                </select>
	            			            </div>
						            </div>
						            <div class="col-sm-6 mdl-input-group">
	                                    <div class="mdl-select">
	                                        <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-noneSelectedText="Selec. Grado y Nivel de inter&eacute;s (*)" onchange="editarPostulante(); getSedesByNivel('selectGradoNivel','selectSedeInteres');camposPos()" attr-obli= "12">
	            			                <option value="">Selec. Grado y Nivel de inter&eacute;s (*)</option>
	            			                    <?php echo $comboGradoNivel?>
	            			                </select>
	        			                </div>
						            </div>
						            <div class="col-sm-6 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-school"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectSedeInteres" name="selectSedeInteres" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-noneSelectedText="Selec. Sede de inter&eacute;s (*)" onchange="editarPostulante();camposPos()" disabled attr-obli= "12">
	            			                <option value="">Selec. Sede de inter&eacute;s (*)</option>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-icon">
	                                        <i class="mdi mdi-chrome_reader_mode"></i>
	                                    </div>
	                                    <div class="mdl-select">
	                                        <select id="selectTipoDocumentoPostulante" name="selectTipoDocumentoPostulante" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-noneSelectedText="Selec. tipo de documento" 
						                    onchange="editarPostulante(); habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante'); changeMaxlength('selectTipoDocumentoPostulante','nroDocumentoPostulante');camposPos()" attr-obli= "1">
	            			                <option value="">Selec. tipo de documento<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                    <?php echo $comboTipoDocumento?>
	            			                </select>
	            			            </div>
						            </div>
	                                <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
	                                        <input class="mdl-textfield__input datoEst" type="text" id="nroDocumentoPostulante" name="nroDocumentoPostulante" maxlength = "12" onchange="editarPostulante()" disabled attr-obli= "1">
	                                        <label class="mdl-textfield__label" for="nroDocumentoPostulante">N&uacute;mero del documento<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></label>                            
	                                    </div>
	                                </div>
						            <div class="col-sm-4 mdl-input-group">
	                                    <div class="mdl-select">
	                                        <select id="selectColegioProcedencia" name="selectColegioProcedencia" class="form-control selectButton datoEst" data-live-search="true" 
						                    data-noneSelectedText="Selec. Centro educativo de procedencia<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?>" onchange="editarPostulante();camposPos()" attr-obli= "1">
	            			                <option value="">Selec. Centro educativo de procedencia<?php echo (isset($obligatorioFam) ? $obligatorioFam: null)?></option>
	            			                    <?php echo $comboColegios?>
	            			                </select>
	            			            </div>
	            			            <div class="mdl-icon">
	                                        <i class="mdi mdi-add" onclick="abrirModalCrearColegio()" style="cursor: pointer;" id="btnAgregarColegio"></i>
	                                        <div class="mdl-tooltip" for="btnAgregarColegio">Agregar Colegio</div>
	                                    </div>
						            </div>
	                                <div class="col-sm-12 mdl-input-group" <?php echo $mostrar?>>
	                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
	                                        <input class="mdl-textfield__input" type="text" id="observacion_postulante" name="observacion_postulante" maxlength="200" onchange="editarPostulante()" attr-obli= "1">        
	                                        <label class="mdl-textfield__label" for="observacion_postulante">Observaci&oacute;n</label>
	                                        <span class="mdl-textfield__limit" for="observacion_postulante" data-limit="200"></span>                             
	                                    </div>
	                                </div>
<!-- 	                                <div class="col-sm-12 text-right p-t-30"> -->
<!-- 	                                    <div id=""> 
	                                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="nextStep(3)" id="btnNext" <?php echo $disabledNext?>>SIGUIENTE</button>
   	                                    </div> -->
<!-- 	                                </div> -->
                            	</div>
	                        </div>
                            <div class="tab-pane" id="tab3">
                                <div class="row-fluid">
                                    <div class="col-sm-12 mdl-card-supporting__title">
                                        <h2 class="mdl-card__title-text">T&eacute;rminos y condiciones</h2>
                                    </div>
                                    <div>
                                        <p class="text-left" style="text-align:justify;padding : 0 20px;" >Con la finalidad de atender la postulaci&oacute;n de su hijo(a) y su incorporaci&oacute;n como alumno(a), usted autoriza a La Merced
                                         para que realice el tratamiento de sus datos personales y los de su hijo(a), de manera indefinida 
                                         o hasta que revoque su autorizaci&oacute;n. El tratamiento podr&aacute; ser efectuado por La Merced o por 
                                         cualquier tercero que esta autorice, siempre garantizando la seguridad y confidencialidad de sus datos personales y los de su hijo(a). 
                                         Su autorizaci&oacute;n resulta obligatoria para atender la postulaci&oacute;n y eventual incorporaci&oacute;n de su hijo(a), y en caso de negativa,
                                         dicha labor no se podr&aacute; realizar. Usted puede revocar su consentimiento o ejercer cualquiera de los derechos previstos en 
                                         la Ley 29733, de manera gratuita, enviando un correo a marketing@nslm.edu.pe 
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-card-supporting__checkbox">
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-2" onchange="habilitarNextStep(1)" id="check2">
                                        <input type="checkbox" id="checkbox-2" class="mdl-checkbox__input">
                                        <span class="mdl-checkbox__label">Acepto los t&eacute;rminos y condiciones.</span>
                                    </label>
                                </div>
                                
                                <div class="col-sm-12 text-right p-t-30">
                                    <div id="cont_enviar_formulario">
                                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="enviarFormulario()" id="btnEnviarFormulario" disabled>ENVIAR FORMULARIO</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab4">
                                <div class="row-fluid">
                                    <div class="col-sm-12 mdl-card-supporting__title">
                                        <h2 class="mdl-card__title-text">Evento</h2>
                                    </div>
                                    <div id="cont_evento">
                                        <div class="col-sm-12 mdl-input-group" <?php echo $mostrar?>>
                                            <div class="mdl-icon">
                                                <i class="mdi"></i>
                                            </div>
                                            <div class="mdl-select">
                                                <select id="selectEvento" name="selectEvento" class="form-control selectButton" data-live-search="true" 
        					                    onchange ="getDatosEvento(); habilitarNextStep(2)">
        					                    <option value="">Selec. un evento</option>
                                                    <?php echo (isset($comboEventosFuturos) ? $comboEventosFuturos: null)?>
            						            </select>
        						            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group" <?php echo $mostrar?>>
                                            <div class="mdl-icon">
                                                <i class="mdi"></i>
                                            </div>
                                            <div class="mdl-select">
                                                <select id="selectOpcion" name="selectOpcion" class="form-control selectButton" data-live-search="true" onchange="habilitarNextStep(2)">
        					                    <option value="">Selec. una opci&oacute;n</option>
                                                    <?php echo (isset($comboOpciones) ? $comboOpciones: null)?>
            						            </select>
        						            </div>
                                        </div>
                                        <div class="col-sm-4 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-assignment_turned_in"></i>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="titulo" name="titulo" value="<?php echo (isset($evento) ? $evento: null)?>" disabled >
                                                <label class="mdl-textfield__label" for="titulo">T&iacute;tulo del evento</label>         
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mdl-input-group">
                                            <div class="mdl-icon mdl-icon__button">
    	    							        <button class="mdl-button mdl-js-button mdl-button--icon transparent" id="iconFecha" disabled>
    	    								       <i class="mdi mdi-event"></i>
    	    							        </button>
    	    							    </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="fecha" name="fecha" value="<?php echo (isset($fecha) ? $fecha: null)?>" disabled onchange="habilitarNextStep(2)">        
                                                <label class="mdl-textfield__label" for="fecha">Fecha</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mdl-input-group">
                                            <div class="mdl-icon mdl-icon__button">
                                                <button class="mdl-button mdl-js-button mdl-button--icon transparent" id="iconhoraAgendar" disabled>
                                                    <i class="mdi mdi-watch_later"></i>
                                                </button>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="hora" name="hora" value="<?php echo (isset($hora) ? $hora: null)?>" disabled onchange="habilitarNextStep(2)">        
                                                <label class="mdl-textfield__label" for="hora">Hora</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-input-group">
                                            <div class="mdl-icon">
                                                <i class="mdi mdi-remove_red_eye"></i>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="observacion" name="observacion" value="<?php echo (isset($observacion) ? $observacion: null)?>" disabled>        
                                                <label class="mdl-textfield__label" for="observacion">Observaciones</label>   
                                                <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span>                                                                            
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mdl-card-supporting__checkbox" id="checkbox-group" <?php echo (isset($checkgroup) ? $checkgroup: null)?>>
                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1" onchange="habilitarNextStep(2)" id="check1" style="padding-right:20px;">
                                             <input type="checkbox" id="checkbox-1" class="mdl-checkbox__input">
                                                <span class="mdl-checkbox__label">S&iacute; acepto me gustar&iacute;a participar </span>
                                            </label>
                                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-3" onchange="" id="check3">
                                                <input type="checkbox" id="checkbox-3" class="mdl-checkbox__input">
                                                <span class="mdl-checkbox__label">Agregar google calendar</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-12 text-right p-t-30">
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="display:inline-block" 
                                            onclick="inscribirAEvento('<?php echo (isset($idevento) ? $idevento: null)?>')" id="btnInscribir" disabled>INSCRIBIRME A EVENTO</button>
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnSalirFinal">FINALIZAR</button>
                                        </div>
                                    </div>
                                    <div id="cont_final" style="display:none" class="text-center p-t-30">
                                        <div class="img-search" id="cont_search_empty">
                                            <img src="<?php echo RUTA_IMG?>smiledu_faces/logoFinal.png">                        
                                        </div>
            					        <div class ="text-center">
            					            <p class="text-center" style="font-size: 20px">&iexcl;Ya est&aacute;s inscrito al evento!</p>
            					            <p class="text-center" style="font-size: 16px">Esperemos tu pronta asistencia, no te olvides que puedes ir llenando tu informaci&oacute;n en el link enviado a tu correo</p>
                					        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnFinalizar">Finalizar registro</button>
                					    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>         
            </div>
        </div>
        
        <div class="modal fade" id="modalEnvioFormulario" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title" style="background-color: #fff !important;margin: auto;">
    					   <img style="margin-bottom: -16px;" src="<?php echo RUTA_IMG?>smiledu_faces/logoFinal.png" class="img-responsive">
    					</div>
					    <div class="mdl-card__supporting-text ">
					        <p class="text-center" style="font-size: 25px">&iexcl;Ya est&aacute;s registrado!</p>
					        <div class ="text-center">
					            <?php echo $toEvent?>
					        </div>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFinalRegistro" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card" >
					    <div class="mdl-card__supporting-text ">
					        <p class="text-center" style="font-size: 25px">&iexcl;Ya est&aacute;s inscrito al evento!</p>
					        <div class ="text-center p-t-30">
					            <p class="text-center" style="font-size: 18px">Esperemos tu pronta asistencia, no te olvides que puedes ir llenando tu informaci&oacute;n en el link enviado a tu correo</p>
    					        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="location.reload()" id="btnFinalizar">Finalizar registro</button>
    					    </div>					        
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalRegistrarColegio" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Registrar Colegio</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreColegioCrear" name="nombreColegioCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreColegioCrear">Colegio</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMRC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarColegio()">GUARDAR</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmarEliminarPariente" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar al pariente&#63;</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <p>Al confirmar se eliminar&aacute;n los datos registrados del pariente</p>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="btnConfirmaFam" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="confirmarEliminarPariente()">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmarEliminarEstudiante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar al estudiante&#63;</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <p>Al confirmar se eliminar&aacute;n los datos registrados del estudiante</p>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="btnConfirmaEst" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="confirmarEliminarEstudiante()">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalParientes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Buscar parientes</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
    					   <p>Si tienes alg&uacute;n hijo que est&aacute; o estuvo matriculado en nuestro colegio puedes buscar los datos registrados de los parientes.</p>
					       <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="buscarParientes" name="buscarParientes" onkeyup="activeDesactiveSearch();">        
                                    <label class="mdl-textfield__label" for="buscarParientes">Nombre del pariente o n&uacute;mero de documento</label>                            
                                </div>
                                <div class="mdl-btn">
        			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscar" onclick="busquedaFamilias()">
							            <i class="mdi mdi-search"></i>
							        </button>
        			            </div>                                     
			               </div>
		                   <div id="cont_parientes_matricula"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="modal fade backModal" id="modalVistaFamiliares" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 33">
            <div class="modal-dialog modal-md">
                <div class="modal-content ">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Familiares</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0 br-b">
                            <div class="row m-0 p-0"> 
                                <div id="cont_tabla_familiares_by_CodFam"></div>
                            </div>
                        </div>
                                        
                    </div>
                </div>
            </div>
        </div>
    	
    	<div id="modalConfirmAsignarFamilia" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea agregar los parientes seleccionados?</h2>
    					</div>
    					<div class="mdl-card__supporting-text"> 
    					   Familia:
    					   <p id="nombreFamiliaAsignar"></p>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised" id="btnAgregarParientes" onclick="agregarParientes()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
     	
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap" id="menuFamCrear">
    	    	<button class="mfb-component__button--main">
                    <i style="left:10px;" class="mfb-component__main-icon mdi mdi-person_add"></i>
                </button>
                <button class="mfb-component__button--main" data-mfb-label="Crear familiar" id="btnCrearFamiliar" onclick="nuevoFamiliar()">
                    <i style="left:10px;" class="mfb-component__main-icon mdi mdi-person_add"></i>
                </button>
                <ul class="mfb-component__list" <?php echo (isset($obligatorio) ? '' : 'style="display:none"')?>>
                	<li>
                    	<button class="mfb-component__button--child" data-toggle="modal" data-target="#modalParientes" data-mfb-label="Buscar parientes">
                        	<i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                     </li>                     
                </ul>
            </li>
            <li class="mfb-component__wrap" id="menuFamSave" style="display:none">
                <button class="mfb-component__button--main guardar btnGuardarFamiliar" disabled>
                    <i class="mfb-component__main-icon--resting mdi mdi-save"></i>
                </button>
                <button class="mfb-component__button--main guardar btnGuardarFamiliar" data-mfb-label="Guardar familiar" id="btnGuardarFamiliar" onclick="guardarPariente()" disabled>
                    <i class="mfb-component__main-icon--active mdi mdi-save"></i>
                </button>
                <ul class="mfb-component__list" <?php echo (isset($obligatorio) ? '' : 'style="display:none"')?>>
                	<li>
                    	<button class="mfb-component__button--child" data-toggle="modal" data-target="#modalParientes" data-mfb-label="Buscar parientes">
                        	<i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                        </button>
                     </li>                     
                </ul>
            </li>
            <li class="mfb-component__wrap mfb-only-btn" id="menuEstCrear" style="display:none">
                <button class="mfb-component__button--main" data-mfb-label="Crear estudiante" id="btnCrearEstudiante" onclick="nuevoEstudiante()">
                    <i class="mfb-component__main-icon--resting mdi mdi-person_add"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-person_add"></i>
                </button>
            </li>
            <li class="mfb-component__wrap mfb-only-btn" id="menuEstSave" style="display:none">
                <button class="mfb-component__button--main guardar" data-mfb-label="Guardar estudiante" id="btnGuardarEstudiante" onclick="guardarPostulante()" disabled>
                    <i class="mfb-component__main-icon--resting mdi mdi-save"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-save"></i>
                </button>
            </li>
        </ul>

        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mark/jquery.highlight.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsformulario.js"></script>

        <script>
            init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });   
        </script>
	</body>
</html>