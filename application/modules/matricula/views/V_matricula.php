<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
		<title>Datos b&aacute;sicos | <?php echo NAME_MODULO_MATRICULA;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BLANCO_MATRICULA?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/matricula.css" >
        
    </head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>
        		<section class="mdl-layout__tab-panel is-active" id="tab-0">
                    <div class="mdl-content-cards">
                        <div id="cont_alumnos_pincipal">
                            <?php echo isset($tablaAlumnos)?$tablaAlumnos:null?>
                            <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                                <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                    <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                                </button>   
                            </div>
                        </div>
                    </div>
        		</section>
        		<section class="mdl-layout__tab-panel p-0" id="tab-1">
        			<div class="mdl-filter">
        				<div class="p-r-15 p-l-15">
        					<div class="mdl-content-cards mdl-content__overflow" id = "cont_parientes">
                            </div>
        				</div>
        			</div>
        			<div class="p-r-15 p-l-15">
        				<div class="mdl-content-cards">
        					<div class="mdl-card ">
        					    <div class="mdl-card__supporting-text mdl-wizard br-b">
        					        <div class="form-wizard form-wizard-horizontal " id="rootwizard1">
        								<div class="form-wizard-nav">
        									<div class="progress">
        										<div class="progress-bar progress-bar-primary" Id="progressBar"></div>
        									</div>
        									<ul class="nav nav-justified nav-pills">
        										<li class="active wizard-label" id="li1"><a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1" onclick ="stepWizardPar(1)">
        										    <span class="step"></span>
        										    <span class="title">Personal</span>
        										</a></li>
        										<li class="" id="li2"><a data-toggle="tab" aria-expanded="false" href="#tab2" class="my-link-par" id="step2"> <span class="step"></span>
        												<span class="title">Domicilio</span>
        										</a></li>
        										<li class="" id="li3"><a data-toggle="tab" aria-expanded="false" href="#tab3" class="my-link-par" id="step3"> <span class="step"></span>
        												<span class="title">Estudios</span>
        										</a></li>
        										<li class="" id="li4"><a data-toggle="tab" aria-expanded="false" href="#tab4" class="my-link-par" id="step4"> <span class="step"></span>
        												<span class="title">Laboral</span>
        										</a></li>
        									</ul>
        								</div>
        							</div>
        							<div class="tab-content">
        								<div class="tab-pane pane-par active" id="tab1">    
        								    <div class="row-fluid">
        										<div class="col-sm-12 mdl-wizzard__tittle">
        											<h2 class="mdl-card__title-text">Personal</h2>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-supervisor_account"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectParentesco" name="selectParentesco" class="form-control selectButtonFam" data-live-search="true" disabled onchange="onChangeCampo('wizard1','parentesco', '<?php echo (isset($enc) ? $enc : null)?>', 'selectParentesco')">
        													<option value="">Selec. Parentesco</option>
                            			                    <?php echo $comboParentesco?>
                                    			        </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-featured_play_list"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectTipoDocumento" name="selectTipoDocumento" class="form-control selectButtonFam" data-live-search="true" onchange="onChangeCampo('wizard1','tipo_doc_identidad', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectTipoDocumento'); changeTipoDoc('selectTipoDocumento','nroDocumento')">
        													<option value="">Selec. tipo de documento(*)</option>
                            			                    <?php echo $comboTipoDocumento?>
                                    			         </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput1">
        												<input class="mdl-textfield__input" type="text" id="nroDocumento" name="nroDocumento" maxlength="12"
        													value="<?php echo isset($nroDoc) ? $nroDoc : null?>" onchange="onChangeCampo('wizard1','nro_doc_identidad', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'nroDocumento')">
        												<label class="mdl-textfield__label" for="nroDocumento">N&uacute;mero
        													del documento</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-person"></i>
        											</div>
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text" id="apellidoPaternoPariente" name="apellidoPaternoPariente" maxlength="100" value="<?php echo isset($ape_paterno) ? $ape_paterno : null?>" onchange="onChangeCampo('wizard1','ape_paterno', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'apellidoPaternoPariente')">
        												<label class="mdl-textfield__label" for="apellidoPaternoPariente">
        													Apellido paterno(*)
        												</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text" id="apellidoMaternoPariente" name="apellidoMaternoPariente" maxlength="100" value="<?php echo isset($ape_materno) ? $ape_materno : null?>" onchange="onChangeCampo('wizard1','ape_materno', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'apellidoMaternoPariente')">
        												<label class="mdl-textfield__label" for="apellidoMaternoPariente">
        													
        													Apellido materno(*)
        												</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text" id="nombrePariente" name="nombrePariente" maxlength="100" value="<?php echo isset($nombres) ? $nombres : null?>" onchange="onChangeCampo('wizard1','nombres', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'nombrePariente')">
        												<label class="mdl-textfield__label" for="nombrePariente">
        												Nombre(*)
        												</label>
        											</div>
        										</div>											
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-record_voice_over"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectApoderado" name="selectApoderado"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','flg_apoderado', '<?php echo (isset($enc) ? $enc : null)?>', 'selectApoderado')"
        													disabled>
        													<option value="">&iquest;Es apoderado?</option>
                                    			                    <?php echo $comboSiNo?>
                                    			         </select>                              			         
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-monetization_on"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectResponsableEconomico"
        													name="selectResponsableEconomico"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','flg_resp_economico', '<?php echo (isset($enc) ? $enc : null)?>', 'selectResponsableEconomico')">
        													<option value="">&iquest;Es responsable econ&oacute;mico?(*)</option>
                                    			                    <?php echo $comboSiNo?>
                                    			         </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-favorite"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectVive" name="selectVive"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','flg_vive', '<?php echo (isset($enc) ? $enc : null)?>', 'selectVive')">
        													<option value="">&iquest;Vive?(*)</option>
                            			                    <?php echo $comboSiNo?>
                            			                </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-wc"></i>
        											</div>
        											<div class="mdl-select">
        												<select class="form-control selectButtonFam"
        													id="selectSexoPariente" name="selectSexoPariente"
        													data-live-search="true"
        													onchange="onChangeCampo('wizard1','sexo', '<?php echo (isset($enc) ? $enc : null)?>', 'selectSexoPariente')">
        													<option value="">Selec. Sexo(*)</option>
                                        			                <?php echo (isset($comboSexo) ? $comboSexo : null)?>
                                        			            </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon ">
        												<button class="mdl-button mdl-js-button mdl-button--icon">
        													<i class="mdi mdi-date_range"></i>
        												</button>
        											</div>
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="fechaNacPariente"
        													name="fechaNacPariente" maxlength="10"
        													value="<?php echo isset($fec_naci) ? $fec_naci : null?>"
        													onchange="onChangeCampo('wizard1','fec_naci', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'fechaNacPariente')">
        												<label class="mdl-textfield__label" for="fechaNacPariente">Fecha
        													de nacimiento(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-location_on"></i>
        											</div>
        											<div class="mdl-select s-nacionalidad s-region">
        												<select id="selectPais" name="selectPais"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','nacionalidad', '<?php echo (isset($enc) ? $enc : null)?>', 'selectPais')">
        													<option value="">Selec. Nacionalidad(*)</option>
                                					                   <?php echo $comboPaises?>
                                					            </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-contact_phone"></i>
        											</div>
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="telefonoPariente" name="telefonoPariente"
        													maxlength="10"
        													value="<?php echo isset($fijo) ? $fijo : null?>"
        													onchange="onChangeCampo('wizard1','telf_fijo', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'telefonoPariente')"> 
        													<label class="mdl-textfield__label" for="telefonoPariente">Tel&eacute;fono
        													fijo(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-phone"></i>
        											</div>
        											<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="celularPariente" name="celularPariente" maxlength="12"
        													value="<?php echo isset($celular) ? $celular : null?>"
        													onchange="onChangeCampo('wizard1','telf_celular', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'celularPariente')">
        												<label class="mdl-textfield__label" for="celularPariente">Celular(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-tap_and_play"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectMovilDatos" name="selectMovilDatos"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','movil_datos', '<?php echo (isset($enc) ? $enc : null)?>', 'selectMovilDatos')">
        													<option value="">&iquest;Tiene celular con datos(Internet)?(*)</option>
                                    			                    <?php echo $comboSiNo?>
                                    			                </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-adb"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectSO" name="selectSO"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','so_movil', '<?php echo (isset($enc) ? $enc : null)?>', 'selectSO')">
        													<option value="">Selec. Sistema Operativo del m&oacute;vil(*)</option>
                    					                    <?php echo $comboSistemaOperativo?>
                    					               </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-contact_mail"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="correoPersona" name="correoPersona" maxlength="100"
        													value="<?php echo isset($email1) ? $email1 : null?>"
        													onchange="onChangeCampo('wizard1','email1', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'correoPersona')">
        												<label class="mdl-textfield__label" for="correoPersona">Correo
        													Electr&oacute;nico(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-account_balance"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectReligion" name="selectReligion"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard1','religion', '<?php echo (isset($enc) ? $enc : null)?>', 'selectReligion')">
        													<option value="">Selec. Religi&oacute;n(*)</option>
                        					                 <?php echo $comboReligion?>
                        					             </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-language"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectIdioma" name="selectIdioma"
        													class="form-control selectButtonFam dato2"
        													data-live-search="true"
        													onchange="onChangeCampo('wizard1','idioma', '<?php echo (isset($enc) ? $enc : null)?>', 'selectIdioma')">
        													<option value="">Seleccione un idioma(*)</option>
                						                    <?php echo $comboIdioma?>
                    						            </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-accessibility"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectEstadoCivil" name="selectEstadoCivil"
        													class="form-control selectButtonFam dato3"
        													data-live-search="true"
        													onchange="onChangeCampo('wizard1','estado_civil', '<?php echo (isset($enc) ? $enc : null)?>', 'selectEstadoCivil')">
        													<option value="">Seleccione Estado civil(*)</option>
                						                    <?php echo $comboEstadoCivil?>
                						                </select>
        											</div>
        										</div>
        									</div>
        								</div>
        										
        								<div class="tab-pane pane-par" id="tab2">
        								    <div class="row-fluid">
        										<div class="col-sm-12 mdl-wizzard__tittle">
        											<h2 class="mdl-card__title-text">Domicilio(*)</h2>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-location_on"></i>
        											</div>
        											<div class="mdl-select s-region">
        												<select id="departamentoFam" name="departamentoFam"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="getProvinciaPorDepartamento('departamentoFam', 'provinciaFam', 'distritoFam', 2); onChangeCampo('wizard2','departamento', '<?php echo (isset($enc) ? $enc : null)?>', 'departamentoFam')">
        													<option value="">Selec. Departamento actual(*)</option>
                    						                <?php echo $comboDepartamento?>
                    						            </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-select s-region">
        												<select id="provinciaFam" name="provinciaFam"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="getDistritoPorProvincia('departamentoFam', 'provinciaFam', 'distritoFam', 3); onChangeCampo('wizard2','provincia', '<?php echo (isset($enc) ? $enc : null)?>', 'provinciaFam')">
        													<option value="">Selec. Provincia actual(*)</option>
        												</select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-select s-region">
        												<select id="distritoFam" name="distritoFam"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard2','distrito', '<?php echo (isset($enc) ? $enc : null)?>', 'distritoFam')">
        													<option value="">Selec. Distrito actual(*)</option>
        												</select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-8 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-home"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="direccion" name="direccion" maxlength="300"
        													value="<?php echo isset($direccion) ? $direccion : null?>"
        													onchange="onChangeCampo('wizard2','direccion_hogar', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'direccion')">
        												<label class="mdl-textfield__label" for="direccion">Direcci&oacute;n
        													del domicilio(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input" type="text"
        													id="referencia_domicilio" name="referencia_domicilio"
        													maxlength="300"
        													value="<?php echo isset($referencia) ? $referencia : null?>"
        													onchange="onChangeCampo('wizard2','refer_domicilio', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'referencia_domicilio')">
        												<label class="mdl-textfield__label"
        													for="referencia_domicilio">Referencia del domicilio(*)</label>
        											</div>
        										</div>
        									</div>
        								</div>
        								<div class="tab-pane pane-par" id="tab3">
        								    <div class="row-fluid">
        										<div class="col-sm-12 mdl-wizzard__tittle">
        											<h2 class="mdl-card__title-text">Estudios</h2>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-school"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="nivelInstrFam" name="nivelInstrFam"
        													class="form-control selectButtonFam dato2"
        													data-live-search="true"
        													onchange="onChangeCampo('wizard3','nivel_instruccion', '<?php echo (isset($enc) ? $enc : null)?>', 'nivelInstrFam')">
        													<option value="">&#191;Nivel de instrucci&oacute;n?(*)</option>
                        						                      <?php echo $comboNivelInstr?>
                        						               </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group mdl-input-group__button">
                				                   <div class="mdl-icon"><i class="mdi mdi-domain"></i></div>
                				                   <div class="mdl-select">
                    					               <select id="selectColegioEgreso" name="selectColegioEgreso"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard3','flg_ex_alumno', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectColegioEgreso'); changeColegioEgreso()">
        													<option value="">&iquest;En qu&eacute; colegio egresaste?(*)</option>
                            			                    <?php echo $comboColegioEgreso?>
                                			           </select>
                            			           </div>
                					            </div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-date_range"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        												<input class="mdl-textfield__input dato5" type="text"
        													id="yearEgresoRegFam" name="yearEgresoRegFam"
        													maxlength="4"
        													value="<?php echo isset($yearEgreso) ? $yearEgreso : null?>"
        													onchange="onChangeCampo('wizard3','year_egreso', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'yearEgresoRegFam')">
        												<label class="mdl-textfield__label" for="yearEgresoRegFam">A&ntilde;o
        													egreso(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-language"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectNivelDominioIngles"
        													name="selectNivelDominioIngles"
        													class="form-control selectButtonFam" data-live-search="true"
        													onchange="onChangeCampo('wizard3','flg_nivel_dom_ingles', '<?php echo (isset($enc) ? $enc : null)?>', 'selectNivelDominioIngles')">
        													<option value="">Selec. el nivel de ingl&eacute;s(*)</option>
                						                    <?php echo $comboNivelIngles?>
                            			                </select>
        											</div>
        										</div>
        									</div>
        								</div>
        								<div class="tab-pane pane-par" id="tab4">
        									<div class="row-fluid">
        										<div class="col-sm-12 mdl-wizzard__tittle">
        											<h2 class="mdl-card__title-text">Laboral</h2>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-view_list"></i>
        											</div>
        											<div class="mdl-select">
        												<select id="selectSituacionLaboralRegFam"
        													name="selectSituacionLaboralRegFam"
        													class="form-control selectButtonFam"
        													data-live-search="true"
        													onchange="onChangeCampo('wizard4','situacion_laboral', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectSituacionLaboralRegFam'); changeSituacionLaboral()">
        													<option value="">Situacion Laboral(*)</option>
                    						                <?php echo $comboSituacionLabo?>
                    						            </select>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        												<i class="mdi mdi-work"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label dato4">
        												<input class="mdl-textfield__input" type="text"
        													id="ocupacionRegFam" name="ocupacionRegFam"
        													maxlength="90"
        													value="<?php echo isset($ocupacion) ? $ocupacion : null?>"
        													onchange="onChangeCampo('wizard4','ocupacion', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'ocupacionRegFam')">
        												<label class="mdl-textfield__label" for="ocupacionRegFam">Ocupaci&oacute;n(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        											   <i class="mdi mdi-supervisor_account"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label dato4">
        												<input class="mdl-textfield__input" type="text"
        													id="cargoRegFam" name="cargoRegFam" maxlength="100"
        													value="<?php echo isset($cargo) ? $cargo : null?>"
        													onchange="onChangeCampo('wizard4','cargo', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'cargoRegFam')">
        												<label class="mdl-textfield__label" for="cargoRegFam">Cargo(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-6 col-md-4 mdl-input-group">
        											<div class="mdl-icon">
        											   <i class="mdi mdi-domain"></i>
        											</div>
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label dato4">
        												<input class="mdl-textfield__input" type="text"
        													id="centroTrabajoRegFam" name="centroTrabajoRegFam"
        													maxlength="100"
        													value="<?php echo isset($centroTrabajo) ? $centroTrabajo : null?>"
        													onchange="onChangeCampo('wizard4','centro_trabajo', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'centroTrabajoRegFam')">
        												<label class="mdl-textfield__label"
        													for="centroTrabajoRegFam">Centro Trabajo(*)</label>
        											</div>
        										</div>
        										<div class="col-sm-12 col-md-8 mdl-input-group">
        											<div
        												class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label dato4">
        												<input class="mdl-textfield__input" type="text"
        													id="direccionTrabajoRegFam" name="direccionTrabajoRegFam"
        													maxlength="100"
        													value="<?php echo isset($direcTrab) ? $direcTrab : null?>"
        													onchange="onChangeCampo('wizard4','direccion_trabajo', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'direccionTrabajoRegFam')">
        												<label class="mdl-textfield__label"
        													for="direccionTrabajoRegFam">Direcci&oacute;n(*)</label>
        											</div>
        										</div>
        									</div>
        								</div>
        							</div>
        						</div>
        						<div class="mdl-card__menu">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="left" data-original-title="Campos Obligatorios(*)">
                                        <i class="mdi mdi-info"></i>
                                    </button>
                                </div>
        					</div>
        				</div>
        			</div>
        		</section>
    
        		<section class="mdl-layout__tab-panel p-0" id="tab-2"  <?php echo isset($ingreso) ? $ingreso : null?>>
        			<div class="mdl-filter">
        				<div class="p-r-15 p-l-15">
        					<div class="mdl-content-cards mdl-content__overflow" id = "cont_postulantes">
                            </div>
        				</div>
        			</div>
        			<div class="p-r-15 p-l-15">			
        				<div class="mdl-content-cards">
        				    <div class="mdl-content-cards">
        				        <div class="mdl-card ">
            					    <div class="mdl-card__supporting-text mdl-wizard br-b p-b-0">
            					        <div class="form-wizard form-wizard-horizontal" id="rootwizard2">
            								<div class="form-wizard-nav">
            									<div class="progress">
            										<div class="progress-bar progress-bar-primary" id="progressBarPos"></div>
            									</div>
            									<ul class="nav nav-justified nav-pills">
            										<li class="active" id="li1Pos"><a data-toggle="tab"
            											aria-expanded="true" href="#tab1Pos" id="step1Pos" onclick="stepWizardPos(1)">
            											<span class="step"></span> <span
            												class="title">Personal</span>
            										</a></li>
            										<li id="li2Pos"><a data-toggle="tab" aria-expanded="false"
            											href="#tab2Pos" class="my-link-pos" id="step2Pos"> 
            											<span class="step"></span> <span
            												class="title">Nacimiento</span>
            										</a></li>
            										<li id="li3Pos"><a data-toggle="tab" aria-expanded="false"
            											href="#tab3Pos" class="my-link-pos" id="step3Pos"> 
            											<span class="step"></span> <span
            												class="title">Ficha m&eacute;dica</span>
            										</a></li>
            										<li id="li4Pos"><a data-toggle="tab" aria-expanded="false"
            											href="#tab4Pos" class="my-link-pos" id="step4Pos"> 
            											<span class="step"></span> <span
            												class="title">Contacto</span>
            										</a></li>
            										<li id="li5Pos"><a data-toggle="tab" aria-expanded="false"
            											href="#tab5Pos" class="my-link-pos" id="step5Pos"> 
            											<span class="step"></span> <span
            												class="title">Ficha de autorizaci&oacute;n</span>
            										</a></li>
            										<li id="li6Pos"><a data-toggle="tab" aria-expanded="false"
            											href="#tab6Pos" class="my-link-pos" id="step6Pos"
            											onclick="stepWizardPos(6)"> 
            											<span class="step"></span> <span
            												class="title">Pagos</span>
            										</a></li>
            									</ul>
            								</div>
            							</div>
                						<div class="tab-content">
                							<div class="tab-pane pane-pos active" id="tab1Pos">    
                							    <div class="row-fluid">
                									<div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Personal</h2>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-person"></i>
                										</div>
                										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="apellidoPaternoPostulante"
                												name="apellidoPaternoPostulante" maxlength="100"
                												value="<?php echo isset($ape_paterno_pos) ? $ape_paterno_pos : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','ape_pate_pers', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'apellidoPaternoPostulante')">
                											<label class="mdl-textfield__label" for="apellidoPaternoPariente">Apellido paterno(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="apellidoMaternoPostulante"
                												name="apellidoMaternoPostulante" maxlength="100"
                												value="<?php echo isset($ape_materno_pos) ? $ape_materno_pos : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','ape_mate_pers', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'apellidoMaternoPostulante')">
                											<label class="mdl-textfield__label"
                												for="apellidoMaternoPariente">Apellido materno(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="nombrePostulante" name="nombrePostulante"
                												maxlength="100"
                												value="<?php echo isset($nombres_pos) ? $nombres_pos : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','nom_persona', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'nombrePostulante')">
                											<label class="mdl-textfield__label" for="nombrePariente">Nombre(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-language"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectLenguaMaterna" name="selectLenguaMaterna" class="form-control selectButtonPos dato2" data-live-search="true" onchange="onChangeCampoPostulante('wizard1pos','lengua_materna', '<?php echo (isset($enc) ? $enc : null)?>', 'selectLenguaMaterna')">
                												<option value="">Selecc. lengua materna</option>
                        						                <?php echo $comboIdioma?>
                        						            </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-group"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectPadresJuntos" name="selectPadresJuntos"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard1pos','flg_padres_juntos', '<?php echo (isset($enc) ? $enc : null)?>', 'selectPadresJuntos')">
                												<option value="">&iquest;Sus padres viven juntos?</option>
                                			                    <?php echo $comboSiNo?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="convivencia" name="convivencia" maxlength="50"
                												value="<?php echo isset($convivencia) ? $convivencia : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','convivencia', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'convivencia')">
                											<label class="mdl-textfield__label" for="convivencia">&iquest;Con
                												qui&eacute;n vive?</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-supervisor_account"></i>
                										</div>
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label ">
                											<input class="mdl-textfield__input" type="text"
                												id="familiarFrecuente" name="familiarFrecuente"
                												maxlength="30"
                												value="<?php echo isset($familiar_frecuente) ? $familiar_frecuente : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','familiar_frecuente', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'familiarFrecuente')">
                											<label class="mdl-textfield__label" for="familiarFrecuente">&iquest;Con
                												qui&eacute;n pasa m&aacute;s tiempo?</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-wc"></i>
                										</div>
                										<div class="mdl-select">
                											<select class="form-control selectButtonPos"
                												id="selectSexoPostulante" name="selectSexoPostulante"
                												data-live-search="true" onchange="onChangeCampoPostulante('wizard1pos','sexo', '<?php echo (isset($enc) ? $enc : null)?>', 'selectSexoPostulante')">
                												<option value="">Selec. Sexo</option>
                                    			                <?php echo (isset($comboSexo) ? $comboSexo : null)?>
                                    			            </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-featured_play_list"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectTipoDocumentoPos"
                												name="selectTipoDocumentoPos"
                												class="form-control selectButtonPos" data-live-search="true"
                												data-container=".mdl-select[for='selectTipoDocumentoPos']"
                												data-noneSelectedText="Selec. tipo de documento"
                												onchange="onChangeCampoPostulante('wizard1pos','tipo_documento', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectTipoDocumentoPos'); changeTipoDoc('selectTipoDocumentoPos','nroDocumentoPos')">
                												<option value="">Selec. tipo de documento</option>
                                			                    <?php echo $comboTipoDocumento?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput2">
                											<input class="mdl-textfield__input" type="text"
                												id="nroDocumentoPos" name="nroDocumentoPos" maxlength="12"
                												value="<?php echo isset($nroDoc_pos) ? $nroDoc_pos : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','nro_documento', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'nroDocumentoPos')">
                											<label class="mdl-textfield__label" for="nroDocumento">N&uacute;mero
                												del documento(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-group_work"></i>
                										</div>
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="totalHermanos" name="totalHermanos" maxlength="2"
                												value="<?php echo isset($total_hermano) ? $total_hermano : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','total_hermano', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'totalHermanos')">
                											<label class="mdl-textfield__label" for="totalHermanos">Total
                												de hermanos(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="lugarHermanos" name="lugarHermanos" maxlength="2"
                												value="<?php echo isset($nro_hermano) ? $nro_hermano : null?>"
                												onchange="onChangeCampoPostulante('wizard1pos','nro_hermano', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'lugarHermanos')">
                											<label class="mdl-textfield__label" for="lugarHermanos">Lugar
                												que ocupa de los hermanos(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-account_balance"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectReligionPos" name="selectReligionPos"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard1pos','religion', '<?php echo (isset($enc) ? $enc : null)?>', 'selectReligionPos')">
                												<option value="">Selec. Religi&oacute;n(*)</option>
                                			                    <?php echo $comboReligion?>
                                			                </select>
                										</div>
                									</div>
                									<!-- div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-school"></i>
                                                        </div>
                                                        <div class="mdl-select">
                                                            <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButtonPos" data-live-search="true" data-container="body" 
                                                             onchange="onChangeCampoPostulante('wizard1pos','grado_nivel', '<?php echo (isset($enc) ? $enc : null)?>', 'selectGradoNivel')"
                                                             data-noneSelectedText="Selec. Grado y Nivel">
                        						                  <option value="">Selec. Grado y Nivel</option>
                        						             </select>
                                                        </div>
                                                    </div -->
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-location_on"></i>
                										</div>
                                                        <div class="mdl-select s-region">
                                                            <select id="selectPaisPos" name="selectPaisPos" class="form-control selectButtonPos" data-live-search="true"
                    					                     onchange = "onChangeCampoPostulante('wizard1pos','pais', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectPaisPos'); changePais()">
                                			                     <option value="">Selec. Nacionalidad(*)</option>
                                			                     <?php echo $comboPaisesSinEncrypt?>
                                			                </select>
                                			            </div>
                    					            </div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-select s-region">
                											<select class="form-control selectButtonPos"
                												id="selectDepartamentoPostulante"
                												name="selectDepartamentoPostulante"
                												data-live-search="true"
                												onchange="getProvinciaPorDepartamento('selectDepartamentoPostulante', 'selectProvinciaPostulante', 'selectDistritoPostulante', 2); onChangeCampoPostulante('wizard1pos','departamento', '<?php echo (isset($enc) ? $enc : null)?>', 'selectDepartamentoPostulante')">
                												<option value="">Selec. Departamento actual(*)</option>
                                    			                <?php echo (isset($comboDepartamento) ? $comboDepartamento : null)?>
                                    			           </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-select ">
                											<select class="form-control selectButtonPos"
                												id="selectProvinciaPostulante"
                												name="selectProvinciaPostulante" data-live-search="true"
                												onchange="getDistritoPorProvincia('selectDepartamentoPostulante', 'selectProvinciaPostulante', 'selectDistritoPostulante', 3); onChangeCampoPostulante('wizard1pos','provincia', '<?php echo (isset($enc) ? $enc : null)?>', 'selectProvinciaPostulante')">
                												<option value="">Selec. Provincia actual(*)</option>
                											</select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-select s-region">
                											<select class="form-control selectButtonPos"
                												id="selectDistritoPostulante"
                												name="selectDistritoPostulante" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard1pos','distrito', '<?php echo (isset($enc) ? $enc : null)?>', 'selectDistritoPostulante')">
                												<option value="">Selec. Distrito actual(*)</option>
                											</select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group mdl-input-group__button">
                										<div class="mdl-icon">
                											<i class="mdi mdi-school"></i>
                										</div>
                										<div class="mdl-select icon-add-uso s-colegio" for="selectColegioProcedencia">
                											<select id="selectColegioProcedencia"
                												name="selectColegioProcedencia"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard1pos','colegio_procedencia', '<?php echo (isset($enc) ? $enc : null)?>', 'selectColegioProcedencia')">
                												<option value="">Selec. Centro educativo de procedencia(*)</option>
                                			                    <?php echo $comboColegios?>
                                			                </select>
                										</div>
                                			            <div class="mdl-btn">
                                			                <button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalCrearColegio(2)" id="btnAgregarColegio">
                										       <i class="mdi mdi-add"></i>
                										    </button>
                										    <div class="mdl-tooltip" for="btnAgregarColegio">Agregar Colegio</div>
                                			            </div>
                									</div>
                								</div>
                							</div>
                							
                							<div class="tab-pane pane-pos" id="tab2Pos">
                							     <div class="row-fluid">
                									<div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Nacimiento</h2>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon ">
                											<button class="mdl-button mdl-js-button mdl-button--icon">
                												<i class="mdi mdi-date_range"></i>
                											</button>
                										</div>
                										<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="fechaNacPostulante"
                												name="fechaNacPostulante" maxlength="10"
                												value="<?php echo isset($fec_naci_pos) ? $fec_naci_pos : null?>"
                												onchange="onChangeCampoPostulante('wizard2pos','fec_naci', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'fechaNacPostulante')">
                											<label class="mdl-textfield__label"
                												for="fechaNacPostulante">Fecha de nacimiento</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-child_care"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectNacRegistrado" name="selectNacRegistrado"
                												class="form-control selectButtonPos" data-live-search="true"														
                												onchange="onChangeCampoPostulante('wizard2pos','flg_nac_registrado', '<?php echo (isset($enc) ? $enc : null)?>', 'selectNacRegistrado')">
                												<option value="">&iquest;Nacimiento registrado?(*)</option>
                                			                    <?php echo $comboSiNo?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-select">
                											<select id="selectNacComplicacion"
                												name="selectNacComplicacion"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard2pos','nac_complicaciones', '<?php echo (isset($enc) ? $enc : null)?>', 'selectNacComplicacion')">
                												<option value="">&iquest;Nacimiento con complicaciones?(*)</option>
                                			                    <?php echo $comboSiNo?>
                                			                </select>
                										</div>
                									</div>
                								</div>
                							</div>
                							
                							<div class="tab-pane pane-pos" id="tab3Pos">
                								<div class="row-fluid">
                									<div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Ficha m&eacute;dica</h2>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-local_hospital"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectDiscapacidad" name="selectDiscapacidad"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard3pos','tipo_discapacidad', '<?php echo (isset($enc) ? $enc : null)?>', 'selectDiscapacidad')">
                												<option value="">Selec. Discapacidad(*)</option>
                                			                    <?php echo $comboDiscapacidad?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-attach_file"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="tipoSangrePostulante"
                												name="tipoSangrePostulante"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard3pos','tipo_sangre', '<?php echo (isset($enc) ? $enc : null)?>', 'tipoSangrePostulante')">
                												<option value="">Tipo de sangre(*)</option>
                                			                    <?php echo $comboTipoSangre?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="pesoPostulante" name="pesoPostulante" maxlength="10"
                												value="<?php echo isset($peso) ? $peso : null?>"
                												onchange="onChangeCampoPostulante('wizard3pos','peso', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'pesoPostulante')">
                											<label class="mdl-textfield__label" for="tallaPostulante">Peso(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-accessibility"></i>
                										</div>
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="tallaPostulante" name="tallaPostulante" maxlength="10"
                												value="<?php echo isset($talla) ? $talla : null?>"
                												onchange="onChangeCampoPostulante('wizard3pos','talla', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'tallaPostulante')">
                											<label class="mdl-textfield__label" for="tallaPostulante">Talla
                												del postulante(*)</label>
                										</div>
                									</div>
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-attach_file"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectTieneAlergia" name="selectTieneAlergia"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard3pos','flg_alergia', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'selectTieneAlergia'); changeTieneAlergia()">
                												<option value="">&iquest;Tiene alguna alergia?(*)</option>
                                			                    <?php echo $comboSiNoSinEncrypt?>
                                			                </select>
                										</div>
                									</div>
                									<!-- PONER DISABLED A ESTE CAMPO -->
                									<div class="col-sm-6 col-md-4 mdl-input-group">
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<input class="mdl-textfield__input" type="text"
                												id="alergiasObs" name="alergiasObs" maxlength="50"
                												value="<?php echo isset($alergia) ? $alergia : null?>"
                												onchange="onChangeCampoPostulante('wizard3pos','alergia', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'alergiasObs')">
                											<label class="mdl-textfield__label" for="alergiasObs">Alergia
                												del postulante(*)</label>
                										</div>
                									</div>
                								</div>
                							</div>
                							
                							<div class="tab-pane pane-pos" id="tab4Pos">
                								<div class="row-fluid">
                						           <div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Contacto</h2>
                									</div>
                									<div class="col-sm-12 mdl-input-group m-b-30">
                										<div class="mdl-icon">
                											<i class="mdi mdi-account_box"></i>
                										</div>
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<textarea class="mdl-textfield__input"
                												id="evacuacionContacto" name="evacuacion_contacto"
                												rows="5"
                												onchange="onChangeCampoPostulante('wizard4pos','evacuacion_contacto', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'evacuacionContacto')"><?php echo isset($evacuacion_contacto) ? $evacuacion_contacto : null?></textarea>
                											<label class="mdl-textfield__label"
                												for="evacuacionContacto">Contacto en caso de
                												evacuaci&oacute;n(Nombre, DNI, Tel&eacute;fono,etc)(*)</label>
                												<span class="mdl-textfield__limit" for="evacuacionContacto" data-limit="100"></span>               
                										</div>
                									</div>
                									<div class="col-sm-12 mdl-input-group m-b-30">
                										<div class="mdl-icon">
                											<i class="mdi mdi-supervisor_account"></i>
                										</div>
                										<div
                											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                											<textarea class="mdl-textfield__input"
                												id="encargadoContacto" name="encargadoContacto"
                												rows="5"
                												onchange="onChangeCampoPostulante('wizard4pos','encargado_contacto', '<?php echo (isset($noEnc) ? $noEnc : null)?>', 'encargadoContacto')"><?php echo isset($encargado_contacto) ? $encargado_contacto : null?></textarea>
                											<label class="mdl-textfield__label"
                												for="encargadoContacto">Contacto encargado de recoger
                												al alumno(Nombre, DNI, Tel&eacute;fono,etc)(*)</label>
                												<span class="mdl-textfield__limit" for="encargadoContacto" data-limit="100"></span>                 
                										</div>
                									</div>
                								</div>
                							</div>
                							
                							<div class="tab-pane pane-pos" id="tab5Pos">
                								<div class="row-fluid">
                									<div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Ficha de autorizaci&oacute;n(*)</h2>
                									</div>
                									<div class="col-sm-6 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-chrome_reader_mode"></i>
                										</div>
                										<div class="mdl-select" >
                											<select id="selectPermisoDatos" name="selectPermisoDatos"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard5pos','flg_permiso_datos', '<?php echo (isset($enc) ? $enc : null)?>', 'selectPermisoDatos')">
                												<option value="">&iquest;Los datos se pueden publicar en
                													el directorio telef&oacute;nico?(*)</option>
                                			                    <?php echo $comboSiNo?>
                                			                </select>
                										</div>
                									</div>
                									<div class="col-sm-6 mdl-input-group">
                										<div class="mdl-icon">
                											<i class="mdi mdi-chrome_reader_mode"></i>
                										</div>
                										<div class="mdl-select">
                											<select id="selectPermisoFotos" name="selectPermisoFotos"
                												class="form-control selectButtonPos" data-live-search="true"
                												onchange="onChangeCampoPostulante('wizard5pos','flg_permiso_fotos', '<?php echo (isset($enc) ? $enc : null)?>', 'selectPermisoFotos')">
                												<option value="">&iquest;Las fotos del alumno se pueden
                													publicar?(Web, Bolet&iacute;n, etc)(*)</option>
                                			                    <?php echo $comboSiNo?>
                                			                </select>
                										</div>
                									</div>
                								</div>
                							</div>
                							
                							<div class="tab-pane pane-pos" id="tab6Pos">
                								<div class="row-fluid">
                									<div class="col-sm-12 mdl-wizzard__tittle">
                										<h2 class="mdl-card__title-text">Pagos</h2>
                									</div>
                									<div class="col-sm-12 p-rl-0" id="cont_compromiso"></div>
                									<div class="col-sm-12 text-right p-tb-16 p-rl-0">
                									    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="allCompromisos()" id="btnAllCompromisos">Cronograma completo</button>
                										<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="abrirCerrarModal('modalConfirmarDatos')" id="btnGenerarCompromisos">ACEPTAR COMPROMISOS</button>
                										<!--  button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="display: none" onclick="" id="btnAlumnoDatosCompletos">ACEPTAR</button> -->
                									</div>
                								</div>
                							</div>
                						</div>
                					</div>
                    				<div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-title="" data-toggle="tooltip" data-placement="left" data-original-title="Campos Obligatorios(*)">
                                          <i class="mdi mdi-info"></i>
                                        </button>
                                     </div>                					
                				</div>
            				 </div>
        				 </div>
        			</div>
        		</section>
    		</main>
    	</div>

    	<div class="modal fade" id="modalRegistrarColegio" tabindex="-1"
    		role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Registrar Colegio</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<div class="row p-0 m-0">
    							<div class="col-sm-12 p-0 m-b-15">
    								<div
    									class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    									<input class="mdl-textfield__input" type="text"
    										id="nombreColegioCrear" name="nombreColegioCrear"
    										maxlength="100"> <label class="mdl-textfield__label"
    										for="nombreColegioCrear">Colegio</label>
    								</div>
    							</div>
    						</div>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect"
    							data-dismiss="modal">Cerrar</button>
    						<button
    							class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised"
    							onclick="registrarColegio()">GUARDAR</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>

    	<div class="modal fade" id="modalConfirmarDatos" tabindex="-1"
    		role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Est&aacute;s conforme con todos los datos llenados del estudiante?</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small>Al estar conforme con todos tus datos llenados tambi&eacute;n generar&aacute;s tu compromiso de pago.<br>
    					   <!--  Espera!, si confirmas ya no podr&aacute;s editar ning&uacute;n dato m&aacute;s.--></small>
    					</div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
    						<button
    							class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnFinalizar" onclick="generarRatificacion1()">CONFIRMAR</button>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>

        <div class="modal fade" id="modalConfirmarRatificacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <!--  <h2 class="mdl-card__title-text">&#191;Deseas generar la Ratificaci&oacute;n?</h2>-->
                            <h2 class="mdl-card__title-text">Declaraci&oacute;n Jurada</h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <!--  <small id="msjConfirmaRatificar"></small> -->
                            <div class="m-t-10" onclick="abrirModalDeclaracionPDF()" style="cursor: pointer;">
                                <i class="mdi mdi-picture_as_pdf" style="    position: relative; top: 5px;"></i>
                                <label style="margin-left: 5px; max-width: 80%; border-bottom: 1px solid #757575; cursor: pointer;">Ver 'Declaraci&oacute;n Jurada'</label>
                            </div>
                            <div class="text-left m-t-10 m-b-10">
<!--                                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1" id="checkConforme">
                                    <input type="checkbox" id="checkbox-1" class="mdl-checkbox__input cb-estudiante">
                                    <span class="mdl-checkbox__label" style="font-size: 12px">Conforme con los t&eacute;rminos y condiciones</span></label>-->
                                    <small class="m-t-10" id ="msjMatricula">Para iniciar el proceso de ratificaci&oacute;n debes enviar la declaraci&oacute;n jurada al colegio.
                                    Puedes descargarla desde aqu&iacute; o usar la que recibiste en la agenda de tu hijo/a.</small>
                            </div>
<!--                             <small class="m-t-10">Al estar conforme con todos tus datos llenados tambi&eacute;n generar&aacute;s tus compromisos de pagos.</small> -->
                            <p class="text-center" style="font-size: 15px"></p>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
<!--                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="generarRatificacion()" id="btnConfirmar">ACEPTAR</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
	
        <div class="modal small fade" id="modalCompromisosEstudiante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
        					<h2 class="mdl-card__title-text">Deudas pendientes hasta la fecha actual</h2>
        				</div>
        			    <div class="mdl-card__supporting-text p-r-0 p-l-0">
        			       <div class="row m-0 p-0">
        				       <div class="col-sm-12 p-0" id="calendarCompromisos">
        				       </div>	   
        			       </div>
        				</div>
        				<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalPDF" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel">
        	<div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                       <div class="mdl-card__title">
    					  <h2 class="mdl-card__title-text" id="titleTable">Documento</h2>
    				   </div>
					   <div class="mdl-card__supporting-text p-0 br-b">
					       <div class="iframe-container" id= "doc_declaracion"><iframe src="<?php echo RUTA_PUBLIC_MATRICULA?>files/declaracion_2017_fam.pdf#zoom=80"></iframe></div>
					   </div>
				       <div class="mdl-card__menu">
				          <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				               <i class="mdi mdi-close"></i>
			              </button>
				       </div>
			       </div>
                </div>
            </div>
        </div>
	
        <div class="modal small fade" id="modalCompromisosCompleto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
        					<h2 class="mdl-card__title-text">Compromisos del año</h2>
        				</div>
        				<div class="mdl-card__supporting-text">
                            <div class="text-left m-t-10 m-b-10">
                                <small class="m-t-10" id="textCompromisos">Este es el listado de los compromisos a generar antes de empezar el año lectivo XXXX, son los montos base si Ud tiene algún tipo de descuento este va a figurar antes de ser generados.</small>
                                <div class="col-sm-12 p-0" id="compromisosCompleto">
        				        </div>
                            </div>
                        </div>
        				<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal small fade" id="modalRatificacionCulminada" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
        					<h2 class="mdl-card__title-text">Compromisos generados</h2>
        				</div>
        				<div class="mdl-card__supporting-text">
                            <div class="text-left m-b-10">
                                <p>¡Listo! Ya generaste tu(s) compromiso(s). Ahora solo te falta pagar, puedes ir a la sede 
                                donde estudia tu hijo/a y pagar en secretar&iacute;a o desde cualquiera de estos bancos.</p>
                            </div>
                            <ul class="bancos p-0">
                                <li><img src="<?php echo RUTA_IMG?>bancos/banbif.png"></li>
                                <li><img src="<?php echo RUTA_IMG?>bancos/bcp.png"></li>
                                <li><img src="<?php echo RUTA_IMG?>bancos/comercio.png" style="max-height: 35px;"></li>
                                <li><img src="<?php echo RUTA_IMG?>bancos/bbva.png"></li>
                            </ul>
                            
                        </div>
        				<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon">
                                <i class="mdi mdi-done" style="color: #00E676;"></i>
                            </button>    
                        </div>
                    </div>
                </div>
            </div>     
        </div>

    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>jquery-mask/jquery.mask.min.js"></script> 
    	<script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsmatricula.js"></script>
    
    	<script type="text/javascript">
    		cons_idpariente   = <?php echo (isset($idfamiliar) ? $idfamiliar: 0)?>;
    		cons_idpostulante = <?php echo (isset($idpostulante) ? $idpostulante: 0)?>;
    		
    		init();
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
	</body>
</html>