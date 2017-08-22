<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>              
		<title>Confirmaci&oacute;n de datos</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="#FF9200">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" /> 
        
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/formulario.css" >
	</head>
	
	<style>
        .header-body{
        	background-color: #2196F3;
        }    	  

        .mdl-button.mdl-button--icon{
	       background: #2196F3;
        }
        .is-invalid-psico{
        	color : #F57C00;
        }
        
        .mdl-tabs .col-sm-12{
	        padding:0px;
        }
        
        .mdl-formulario .mdl-card__supporting-text {
            text-align: center;
            padding-top: 0;
            padding-left: 0px;
            padding-right: 0px;
        }
	</style>
	
     <body>
        <header class="mdl-layout__header" style="display: none"></header>
	    <div class="header-body"></div>
            <div class="mdl-layout-title text-center">
                <img style ="width: 30px;margin-right:10px;" src="http://buhooweb.com/smiledu/public/general/img/iconsSistem/icon_admision_blanco.png">Admisi&oacute;n | Confirmaci&oacute;n de datos
    		</div>
	    
	    
        <div id="formulario-confirmar" class="mdl-card mdl-formulario">
            <div class="mdl-card__supporting-text">  
                <div class="row-fluid">
                    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                    <div class="col-sm-12">
					    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">                         
                            <div class="mdl-tabs__tab-bar">
                                <a href="#tab1" class="mdl-tabs__tab is-active" id="barTab1" onclick="changeTabFamiliar()">Datos Pariente</a>
                                <a href="#tab2" class="mdl-tabs__tab" id="barTab2" onclick="getDatosHijos()">Datos Postulante</a>
                                <a href="#tab3" class="mdl-tabs__tab" id="barTab3" onclick="getDatosHijosPsico()">Ficha Psicol&oacute;gica</a>
                            </div>
                            <div class="mdl-tabs__panel is-active p-rl-10" id="tab1">
                                <?php if($generico == 1){?>
                                <div class="tab-pane" id="tab-editperson-fam">
		                    		<div id="cont_cards_familia2"></div>
		                    		<div class="img-search" id="cont_addperson">
		                                <img src="<?php echo RUTA_IMG?>smiledu_faces/simple_search.png">
		                                <p><strong>&#161;Hola!</strong></p>
		                                <p>Puedes buscar a un contacto o a su familiar</p>
		                                <p>desde el bot&oacute;n flotante.</p>
		                            </div>
		                        </div>
		                        <?php }?>
		                        <div id="cont_datos_pariente" style="display: <?php echo ($generico == 1)?'none':'block'?>">
                                    <div class="col-sm-12 mdl-card-supporting__title">
                                        <h2 class="mdl-card__title-text">Familiares</h2>
                                    </div>
                                    <div class="col-sm-12 text-left cont-agregar p-b-15 m-l-10" id="cont_cabe_familiares" style="margin-top: 20px">
                                        <?php echo isset($cabeFam)?$cabeFam:null?>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                          <div class="mdl-icon">
                                            <i class="mdi mdi-supervisor_account"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectParentesco" name="selectParentesco" class="form-control selectButton" data-live-search="true" 
            					                    data-noneSelectedText="Selec. Parentesco" onchange="cambioCampoFamiliar(this)">
                        			                <option value="">Selec. Parentesco (*)</option>
                        			                <?php echo $comboParentesco?>
                			                </select>
                			            </div>
    					            </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-account_circle"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apellidoPaternoPariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*"  name="apellidoPaternoPariente" maxlength="100" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($apePaterno)?$apePaterno:null?>">        
                                            <label class="mdl-textfield__label" for="apellidoPaternoPariente">Apellido paterno del pariente (*)</label>  
                                            <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                          
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apellidoMaternoPariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoMaternoPariente" maxlength="100" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($apeMaterno)?$apeMaterno:null?>">        
                                            <label class="mdl-textfield__label" for="apellidoMaternoPariente">Apellido materno del pariente (*)</label> 
                                            <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                           
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="nombrePariente" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="nombrePariente" maxlength="100" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($nombres)?$nombres:null?>">        
                                            <label class="mdl-textfield__label" for="nombrePariente">Nombre del pariente (*)</label>                            
                                            <span class="mdl-textfield__error">Nombre solo debe contener letras</span>  
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-chrome_reader_mode"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectTipoDocumento" name="selectTipoDocumento" class="form-control selectButton" data-live-search="true" 
            					                    data-noneSelectedText="Selec. tipo de documento" 
            					                    onchange="habilitarCampo('selectTipoDocumento','nroDocumento'); changeMaxlength('selectTipoDocumento','nroDocumento')">
                        			                <option value="">Selec. tipo de documento (*)</option>
                        			                <?php echo $comboTipoDocumento?>
                			                </select>
                			            </div>
    					            </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                            <input class="mdl-textfield__input" type="text" id="nroDocumento" name="nroDocumento" maxlength = "12" onchange="cambioCampoFamiliar(this)" disabled >        
                                            <label class="mdl-textfield__label" for="nroDocumento">N&uacute;mero del documento (*)</label>                            
                                        </div>
                                    </div>
    					            <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-wc"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectSexoPariente" name="selectSexoPariente" class="form-control selectButton" data-live-search="true" 
            					                    data-noneSelectedText="Selec. sexo del pariente" onchange="cambioCampoFamiliar(this)">
                        			                <option value="">Selec. sexo del pariente (*)</option>
                        			                <?php echo $comboSexo?>
                			                </select>
                			            </div>
    					            </div>
    					            <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon transparent" id="iconFecNaciConfirmar">
    				                            <i class="mdi mdi-event_note"></i>
    			                            </button>
    		                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaNacPariente" name="fechaNacPariente" maxlength = "10" onchange="cambioCampoFamiliar(this)">        
                                            <label class="mdl-textfield__label" for="fechaNacPariente">Fecha de nacimiento del pariente (*)</label> 
                                        </div>
                                    </div>
    					            <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-contact_phone"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="telefonoPariente" pattern="[(+0-9+)-0-9, ]+" name="telefonoPariente" maxlength="10" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($telefonoFijo)?$telefonoFijo:null?>">        
                                            <label class="mdl-textfield__label" for="telefonoPariente">Tel&eacute;fono fijo del pariente</label>   
                                            <span class="mdl-textfield__error">N&uacute;mero de fijo solo debe contener numeros</span>                                                                  
                                        </div>
                                    </div>
    					            <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-phone"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="celularPariente" pattern="[(+0-9+)-0-9, ]+" name="celularPariente" maxlength="12" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($telefonoCelular)?$telefonoCelular:null?>">        
                                            <label class="mdl-textfield__label" for="celularPariente">Celular del pariente</label>    
                                            <span class="mdl-textfield__error">N&uacute;mero de celular solo debe contener numeros</span>                         
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-email"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="correoPariente" pattern="[��,A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" name="correoPariente" maxlength="30" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($correo)?$correo:null?>">        
                                            <label class="mdl-textfield__label" for="correoPariente">Correo del pariente</label>        
                                            <span class="mdl-textfield__error">El formato del correo es incorrecto</span>                    
                                        </div>
                                    </div>                                
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-location_on"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="departamentoFam" name="departamentoFam" class="form-control selectButton" data-live-search="true" 
    					                           onchange="getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 2); cambioCampoFamiliar(this)">
        						                   <option value="">Selec. Departamento actual (*)</option>
        						                   <?php echo $comboDepartamento?>
        						            </select>
    						            </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-select">
                                            <select id="provinciaFam" name="provinciaFam" class="form-control selectButton" data-live-search="true" 
    					                    onchange="getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 3); cambioCampoFamiliar(this)" disabled>
        						                   <option value="">Selec. Provincia actual (*)</option>
        						            </select>
    						            </div>
                                    </div>
                                    <div class="col-sm-4 mdl-input-group">
                                        <div class="mdl-select">
                                            <select id="distritoFam" name="distritoFam" class="form-control selectButton" data-live-search="true" 
    					                    disabled onchange="cambioCampoFamiliar(this) ; habilitarCampo('distritoFam','referencia_domicilio')">
        						                   <option value="">Selec. Distrito actual (*)</option>
        						            </select>
    						            </div>
                                    </div>
                                    <div class="col-sm-8 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-home"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                            <input class="mdl-textfield__input" type="text" id="referencia_domicilio" name="referencia_domicilio" maxlength="300" onchange="cambioCampoFamiliar(this)" value="<?php echo isset($referencia)?$referencia:null?>" disabled>        
                                            <label class="mdl-textfield__label" for="referencia_domicilio">Direcci&oacute;n del domicilio (*)</label>                            
                                        </div>
                                   </div>
                                   
                                   </div>
                                   
                               </div> 
                           </div>
                       </div>    
                        <div class="mdl-tabs__panel p-rl-10" id="tab2">
                             <div class="col-sm-12 mdl-card-supporting__title">
                                <h2 class="mdl-card__title-text">Hijos</h2>
                            </div>
                            <div class="col-sm-12 text-left cont-agregar p-b-15 m-l-10" id="cont_cabe_hijos" style="margin-top: 20px"></div>
                                <div class="col-sm-4 mdl-input-group">
                                      <div class="mdl-icon">
                                        <i class="mdi mdi-account_circle"></i>
                                    </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoPaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoPaternoPostulante" maxlength="100" onchange="cambioCampoPostulante(this)">        
                                        <label class="mdl-textfield__label" for="apellidoPaternoPostulante">Apellido paterno del postulante (*)</label>
                                        <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                            
                                    </div>
                                </div>
                                <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoMaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apellidoMaternoPostulante" maxlength="100" onchange="cambioCampoPostulante(this)">        
                                        <label class="mdl-textfield__label" for="apellidoMaternoPostulante">Apellido materno del postulante (*)</label>  
                                        <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                            
                                    </div>
                                </div>
                                <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombrePostulante" name="nombrePostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" maxlength="100" onchange="cambioCampoPostulante(this)">        
                                        <label class="mdl-textfield__label" for="nombrePostulante">Nombre del postulante (*)</label>
                                        <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                            
                                    </div>
                                </div>
                                <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon transparent" id="iconFecNaciConfirmar">
				                            <i class="mdi mdi-event_note"></i>
			                            </button>
		                            </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fechaNacPostulante" name="fechaNacPostulante" maxlength = "10" onchange="cambioCampoPostulante(this)">        
                                        <label class="mdl-textfield__label" for="fechaNacPostulante">Fecha de nacimiento del postulante (*)</label> 
                                    </div>
                                </div>
                                <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-chrome_reader_mode"></i>
                                    </div>
                                    <div class="mdl-select">
                                        <select id="selectTipoDocumentoPostulante" name="selectTipoDocumentoPostulante" class="form-control selectButton" data-live-search="true" 
					                    data-noneSelectedText="Selec. tipo de documento" 
					                    onchange="habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante'); changeMaxlength('selectTipoDocumentoPostulante','nroDocumentoPostulante');">
            			                <option value="">Selec. tipo de documento (*)</option>
            			                    <?php echo $comboTipoDocumento?>
            			                </select>
            			            </div>
					            </div>
                                <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                        <input class="mdl-textfield__input" type="text" id="nroDocumentoPostulante" name="nroDocumentoPostulante" maxlength = "12" onchange="editarPariente()" disabled>
                                        <label class="mdl-textfield__label" for="nroDocumentoPostulante">N&uacute;mero del documento (*)</label>                            
                                    </div>
                                </div>
					            <div class="col-sm-4 mdl-input-group mdl-input-group__button">
					                <div class="mdl-icon">
                                        <i class="mdi mdi-chrome_reader_mode"></i>
                                    </div>
                                    <div class="mdl-select">
                                        <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton" data-live-search="true" 
					                    data-noneSelectedText="Selec. Grado y Nivel de inter&eacute;s" onchange="cambioCampoPostulante(this);">
            			                <option value="">Selec. Grado y Nivel de inter&eacute;s (*)</option>
            			                    <?php echo $comboGradoNivel?>
            			                </select>
        			                </div>
					            </div>
					            <div class="col-sm-4 mdl-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-wc"></i>
                                    </div>
                                    <div class="mdl-select">
                                        <select id="selectSexoPostulante" name="selectSexoPostulante" class="form-control selectButton" data-live-search="true" 
					                    data-noneSelectedText="Selec. Sexo del postulante" onchange="cambioCampoPostulante(this)">
            			                <option value="">Selec. Sexo del postulante (*)</option>
            			                    <?php echo $comboSexo?>
            			                </select>
            			            </div>
				            </div>
                        </div>
                         <div class="mdl-tabs__panel p-rl-10" id="tab3">
                             <div class="col-sm-12 mdl-card-supporting__title">
                                <h2 class="mdl-card__title-text">Hijos</h2>
                            </div>
                            <div class="col-sm-12 text-left cont-agregar p-b-15 m-l-10" id="cont_cabe_hijos_psico" style="margin-top: 20px"></div>
                            <div id="cont_ficha_psicologica_contacto" class="p-rl-10"></div>
                        </div>
                    </div>
                </div>         
            </div>
        </div>
        
        <div class="modal fade" id="modalFiltrar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog bg-filtro modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <?php if($generico == 1){?>
                                    <div id="cont_busqueda_familia">
                					    <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-l-15">                                                    
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="busquedaFamiia" name="busquedaFamiia" maxlength="100" onchange="buscarFamilia()" onkeyup="activeDesactiveSearch();">        
                                                <label class="mdl-textfield__label" for="busquedaFamiia">Buscar Familiar</label>                           
                                            </div>
                                            <div class="mdl-btn">
                    			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscarFamilia" onclick="buscarFamilia();" >
                						            <i class="mdi mdi-search"></i>
                						        </button>
                    			            </div>                            
                                        </div>
                    			        <div class="col-sm-12 mdl-input-group mdl-input-group__only p-b-30">
                    			            <div class="mdl-select">
                                                <select id="selectFamiia" name="selectFamiia" class="form-control selectButton" data-live-search="true" 
                					                    data-noneSelectedText="Selec. Parentesco" onchange="traeInfoFamilia()" disabled>
                            			                <option value="">Selec. Familia</option>
                    			                </select>
                    			            </div>
                                        </div>
                                    </div>
                                <?php }?>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <?php if($generico == 1){?>
            <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
        	    <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                    <button class="mfb-component__button--main" data-mfb-label="Buscar" id="btnGuardarFamiliar" onclick="abrirModalBuscar()">
                        <i class="mfb-component__main-icon--resting mdi mdi-search"></i>
                        <i class="mfb-component__main-icon--active  mdi mdi-search"></i>
                    </button>
                </li>
            </ul> 
        <?php }else{?>
            <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
        	    <li class="mfb-component__wrap mfb-only-btn">
                    <button class="mfb-component__button--main" data-mfb-label="Guardar" id="btnGuardarFamiliar" onclick="guardarDatosFamiliar()" disabled>
                        <i class="mfb-component__main-icon--resting mdi mdi-save"></i>
                        <i class="mfb-component__main-icon--active  mdi mdi-save"></i>
                    </button>
                </li>
            </ul> 
        <?php }?>
        
        <p class="text-center"><a style="color: #757575;margin-top:20px;" class="link-smiledu" href="http://www.smiledu.pe" target="_blank"><strong>Smiledu</strong>&reg;</a> Created by <a class="link-smiledu" href="http://www.softhy.pe/" target="_blank" style="text-decoration:none;color: #757575;">Softhy</a></strong>.</p>
        
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
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
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsconfirmardatos.js"></script>
        <script>
            init('<?php echo isset($contacto)?$contacto:''?>');
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });   

            if(<?php echo $generico?> == 0){
            	setearCombo("selectParentesco", '<?php echo isset($parentesco)?$parentesco:null?>');
                setearCombo("selectTipoDocumento", <?php echo isset($tipoDoc)?($tipoDoc!=null)?$tipoDoc:"null":"null"?>);

                habilitarCampo('selectTipoDocumento','nroDocumento'); 
                changeMaxlength('selectTipoDocumento','nroDocumento');
                setearInput("nroDocumento", '<?php echo isset($nroDoc)?$nroDoc:null?>');
                setearCombo("selectSexoPariente", '<?php echo isset($sexo)?$sexo:null?>');

                setearCombo("departamentoFam", '<?php echo isset($departamento)?$departamento:null?>');
                if($("#departamentoFam").val() != null){
                	getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 2);
                	setearCombo("provinciaFam", '<?php echo isset($provincia)?$provincia:null?>');
                	if($("#departamentoFam").val() != null){
                		getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 'referencia_domicilio', 3);
                		setearCombo("distritoFam", '<?php echo isset($distrito)?$distrito:null?>');
                    }
                }
            }
        </script>
	</body>
</html>