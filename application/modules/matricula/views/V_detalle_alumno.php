<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();
        </script>
        <title>Estudiantes | <?php echo NAME_MODULO_MATRICULA;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_MATRICULA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_MATRICULA?>" />
                 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>wizard/css/wizard.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>cropper/cropper.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">       
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_MATRICULA?>css/detallealumno.css"> 
        
        <script type="text/javascript">
            function yourfunction() { 
            	Pace.restart();
            	Pace.track(function(){});
            }
            window.onload = yourfunction;
            
        </script>

    </head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    	<?php echo $menu ?>
    	<main class='mdl-layout__content'>
            <section class="mdl-layout__tab-panel is-active" id="tab-1">
        	    <div id="spinner" style="display:none">
                    <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active " ></div>
                </div>
        		<div class="mdl-content-cards">
                    <div class="mdl-card" id="c-1">
        		      <div class="mdl-card__title">
                         <h2 class="mdl-card__title-text">Informaci&oacute;n Personal</h2>
                      </div>
                      <div class="mdl-card__menu" >
                         <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-original-title="Campos Obligatorios(*)" data-placement="left">
                            <i class="mdi mdi-info"></i>
                         </button>
                         <div class="progress-barra" <?php echo $barraEst?>> 
                             <span id="cantidad_maxima_estudiantes"><?php echo $cantEstudiantes?></span>   
                             <div class="progress"  data-toggle="modal" data-target="#modalSubirPaquete" data-paquete-text="N&uacute;mero m&aacute;ximo de estudiantes : 800">
                              <div class="progress-bar" id="progreso" role="progressbar" aria-valuenow="<?php echo $porcentajeEstudiantes?>" aria-valuemin="0" aria-valuemax="<?php echo CANTIDAD_MAXIMA_ESTUDIANTES?>" style="width: <?php echo $porcentajeEstudiantes?>%; cursor:pointer;">
                                <span class="sr-only">40% Complete (success)</span>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="mdl-card__supporting-text br-b">
                         <div class="row-fluid">
                            <div class="col-sm-6 col-md-4 text-center"> 
                                <div class="mdl-photo"  <?php echo ($disabled != 'disabled')? 'onclick="abrirSelectFotoPersona()"' : null?>>
                                    <img src="<?php echo $foto?>"  class="mdl-img" id="fotoPersonaImg" name="fotoPersonaImg">
                                    <span class="caption fade-caption">
                                        <i class="mdi mdi-photo_camera"></i>
                                    </span>
                                </div> 
                                <div class="state <?php echo (isset($letra) ? $letra: null)?>" id="estadoPersona" data-toggle="modal" data-target="#modalLeyendaAlumno" ></div>
                                <div class="mdl-tooltip" for="estadoPersona"><font style="text-transform: uppercase"><?php echo (isset($letra) ? $letra: null)?></font></div>
                                <input type="file" id="fotoPersona" name="fotoPersona" accept="image/*" style="display:none">
                            </div>
                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                <div class="mdl-icon">
                                    <i class="mdi mdi-account_box"></i>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input upper" type="text" id="nombrePersona" name="nombrePersona" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" value="<?php echo (isset($nombres) ? $nombres: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'nom_persona', '<?php echo $campoSchoowl?>', 0)" val-previo="<?php echo (isset($nombres) ? $nombres: null)?>">        
                                    <label class="mdl-textfield__label" for="nombrePersona">Nombre(s)(*)</label>   
                                    <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                          
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input upper" type="text" id="APPaternoPersona" name="APPaternoPersona" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" value="<?php echo (isset($apePate) ? $apePate: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'ape_pate_pers', '<?php echo $campoSchoowl?>', 0)" val-previo="<?php echo (isset($apePate) ? $apePate: null)?>">        
                                    <label class="mdl-textfield__label" for="APPaternoPersona">Apellido Paterno(*)</label>    
                                    <span class="mdl-textfield__error">Apellido Paterno debe contener letras</span>                          
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input upper" type="text" id="APMaternoPersona" name="APMaternoPersona" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" value="<?php echo (isset($apeMate) ? $apeMate: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'ape_mate_pers', '<?php echo $campoSchoowl?>', 0)" val-previo="<?php echo (isset($apeMate) ? $apeMate: null)?>">        
                                    <label class="mdl-textfield__label" for="APMaternoPersona">Apellido Materno(*)</label>  
                                    <span class="mdl-textfield__error">Apellido Materno debe contener letras</span>                          
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                  <div class="mdl-icon">                                                
                                      <i class="mdi mdi-wc"></i>
                                  </div>
                                  <div class="mdl-select">
                                      <select id="sexoPersona" name="sexoPersona" class="form-control selectButton" data-live-search="true"  <?php echo $disabled?> onchange="onSaveCampo(this, 'sexo', '<?php echo $campoSchoowl?>', 1)" val-previo="<?php echo (isset($sexo) ? $sexo: null)?>">
        					                <option value="">Selec.Sexo(*)</option>
        					                <?php echo $comboSexo?>
        					           </select>
    					          </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                <div class="mdl-icon">
                                    <i class="mdi mdi-featured_play_list"></i>
                                </div>
                                <div class="mdl-select" >
                                    <select id="tipoDoc" name="tipoDoc" class="form-control selectButton" data-live-search="true"  <?php echo $disabled?> onchange="onSaveCampo(this, 'tipo_documento', '<?php echo $campoSchoowl?>', 0); changeTipoDoc()">
    					                   <option value="">Selec. un Tipo de Doc.(*)</option>
    					                   <?php echo $comboTipoDoc?>
    					            </select>                                                
                                </div>                                                    
                             </div>
                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                 <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                     <input class="mdl-textfield__input" type="text" id="nro_documento" name="nro_documento" pattern="[0-9]+" maxlength="20" value="<?php echo (isset($nro_documento) ? $nro_documento: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'nro_documento', '<?php echo $campoSchoowl?>', 0)" val-previo="<?php echo (isset($nro_documento) ? $nro_documento: null)?>">        
                                     <label class="mdl-textfield__label" for="nro_documento">Nro. Doc.(*)</label>
                                     <span class="mdl-textfield__error">N&uacute;mero Documento solo contiene n&uacute;meros</span> 
                                 </div>
                             </div>
                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                 <div class="mdl-icon">
							        <button class="mdl-button mdl-js-button mdl-button--icon" <?php echo $disabled?>>
								       <i class="mdi mdi-date_range"></i>
							        </button>
							     </div>
                                 <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="fecNacPersona" 
                                    name="fecNacPersona" maxlength="10" 
                                    value="<?php echo (isset($fecnaci) ? $fecnaci: null)?>" 
                                    <?php echo $disabled?> onchange="onSaveCampo(this, 'fec_naci', '<?php echo $campoSchoowl?>', 0)"
                                    val-previo="<?php echo (isset($fecnaci) ? $fecnaci: null)?>">        
                                    <label class="mdl-textfield__label" for="fecNacPersona">Fecha Nacimiento(*)</label>                            
                                 </div>
                             </div>            
                          </div>
                       </div>  
                   </div>

        		   <div class="mdl-card" id="c-2">
        		      <div class="mdl-card__title">
        		          <h2 class="mdl-card__title-text">Informaci&oacute;n de Contacto</h2>
    		          </div>
    		          <div class="mdl-card__supporting-text br-b">
    		              <div class="row-fluid">
                               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon">
                                       <i class="mdi mdi-phone"></i>
                                   </div>
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="tlfPersona" name="tlfPersona" pattern="[(+0-9+)-0-9]+" maxlength="30" value="<?php echo (isset($telefono) ? $telefono: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'telf_pers', '<?php echo $campoSchoowl?>', 0)">        
                                        <label class="mdl-textfield__label" for="tlfPersona">Tel&eacute;fono</label>   
                                        <span class="mdl-textfield__error">Telefono solo contiene numeros</span>                                                                      
                                   </div>
                               </div>
                               <div class="col-sm-6 col-md-4 mdl-input-group" style="z-index:13">
                                   <div class="mdl-icon">
                                       <i class="mdi mdi-location_on"></i>
                                   </div>
                                   <div class="mdl-select s-region">
                                       <select id="pais" name="pais" class="form-control selectButton" data-live-search="true" <?php echo $disabled?> onchange="onSaveCampo(this, 'pais', '<?php echo $campoSima?>', 1);changePaisEstudiante(0)">
            					           <option value="">Selec. pa&iacute;s de procedencia</option>
            					           <?php echo $comboPaises?>
            					       </select>                                                
                                   </div>                                                    
                               </div>
                               <div class="col-sm-6 col-md-4 mdl-input-group" style="z-index: 12">
                                   <div class="mdl-select s-region">
                                       <select id="departamento" name="departamento" onchange="getProvinciaPorDepartamento('departamento', 'provincia', 'distrito', 2, null, null); onSaveCampo(this, 'departamento', '<?php echo $campoSima?>', 1);" class="form-control selectButton" data-live-search="true" <?php echo $disabled?>>
            					           <option value="">Selec. Departamento</option>
            					           <?php echo $comboDepartamento?>
            					        </select>
        					       </div>
                               </div>
                               <div class="col-sm-6 col-md-4 mdl-input-group" style="z-index: 11">
                                   <div class="mdl-select s-region">
                                       <select id="provincia" name="provincia" onchange="getDistritoPorProvincia('departamento', 'provincia', 'distrito', 3, null); onSaveCampo(this, 'provincia', '<?php echo $campoSima?>', 1);" class="form-control selectButton" data-live-search="true" <?php echo $disabled?>>
            					            <option value="">Selec. Provincia</option>
            					           <?php echo $comboProvincia?>
            					       </select>
        					       </div>
                               </div>
                               <div class="col-sm-6 col-md-4 mdl-input-group" style="z-index: 10">
                                   <div class="mdl-select" >
                                       <select id="distrito" name="distrito" class="form-control selectButton" data-live-search="true" <?php echo $disabled?> onchange="onSaveCampo(this, 'distrito', '<?php echo $campoSima?>', 1)">
            					           <option value="">Selec. Distrito</option>
            					           <?php echo $comboDistrito?>
            					       </select>
        					       </div>
                               </div>
                               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-mail"></i></div>
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="correoPersona" name="correoPersona" pattern="[0-9,a-z,_a-z,.a,A-Z,Ñ,ñ]+@[0-9,a-z,.,A-Z,Ñ,ñ]+" maxlength="100" value="<?php echo (isset($correo) ? $correo: null)?>" <?php echo $disabled?> onchange="onSaveCampo(this, 'correo_pers', '<?php echo $campoSchoowl?>', 0)">        
                                       <label class="mdl-textfield__label" for="correoPersona">Correo Electr&oacute;nico</label>
                                       <span class="mdl-textfield__error">Email no es valido</span>    
                                       <button class="mdl-button mdl-js-button mdl-button--icon icon_send_email" data-toggle="tooltip" data-original-title="Aqu&iacute; se le enviar&aacute; usuario y contraseña" data-placement="left">
                                            <i class="mdi mdi-info" data-paquete-text="Enviar correo" data-target="#modalSubirPaquete" data-toggle="modal"></i> 
                                        </button>   
                                   </div>
                               </div>
                          </div>
                      </div>
                   </div>   

                    <div class="mdl-card" id="c-3">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Informaci&oacute;n Adicional</h2>
                        </div>
                        
                        <div class="mdl-card__supporting-text br-b" >
                            <div class="row-fluid">
                                <div class="col-sm-6 col-md-4 mdl-input-group mdl-input-group__button">
    			                    <div class="mdl-icon">
                                        <i class="mdi mdi-school"></i>
                                    </div>
    			                    <div class="mdl-select s-colegio">
    					               <select id="coleProcedencia" name="coleProcedencia" class="form-control selectButton" data-live-search="true"  <?php echo $disabled?> onchange="onSaveCampo(this, 'colegio_procedencia', '<?php echo $campoSima?>', 1)">
        					               <option value="">Selec. un colegio de procedencia</option>
        					               <?php echo $comboColegios?>
        					           </select>
            			           </div>
            			           <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalCrearColegio(1)" <?php echo $disabled?>>
    								       <i class="mdi mdi-add"></i>
    								   </button>
            			           </div>
    				           </div>
    				           <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon">             
                                        <i class="mdi mdi-account_balance"></i>
                                   </div>
                                   <div class="mdl-select s-religion">
                                       <select id="religion" name="religion" class="form-control selectButton" data-live-search="true" <?php echo $disabled?> onchange="onSaveCampo(this, 'religion', '<?php echo $campoSima?>', 1)">
        					                 <option value="">Agregar Religi&oacute;n</option>
        					                 <?php echo $comboReligion?>
        					           </select>
    					           </div>
                                </div>
                                <div class="col-sm-6 col-md-4 mdl-input-group">             
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-supervisor_account"></i>
                                    </div>
                                    <div class="mdl-select s-estado">
                                        <select id="estadoCivil" name="estadoCivil" class="form-control selectButton" data-live-search="true" <?php echo $disabled?> onchange="onSaveCampo(this, 'estado_civil', '<?php echo $campoSchoowl?>', 1)">
        					                   <option value="">Selec. Estado civil</option>
        					                   <?php echo $comboEstadoCivil?>
        					            </select>
    					            </div>
                                </div>
    					    </div>   
                        </div>
                     </div>
                 </div>  
            </section>
                
            <section class="mdl-layout__tab-panel" id="tab-2">
                <div class="mdl-content-cards">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Admisi&oacute;n acad&eacute;mica</h2>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"  data-toggle="tooltip" data-original-title="Campos Obligatorios(*)" data-placement="left" >
                                <i class="mdi mdi-info"></i>
                            </button>
                        </div>
                        <div class="mdl-card__supporting-text br-b">
                            <div class="row-fluid">
                                <div class="col-sm-6 mdl-input-group">
                                    <div class="mdl-icon"><i class="mdi mdi-date_range"></i></div>
                                    <div class="mdl-select">
                                        <select id="yearIngreso" name="yearIngreso" class="form-control selectButtonAdm" data-live-search="true" onchange="onSaveCampo(this, 'year_ingreso', '<?php echo $campoSima?>', 0) ; getSedesByYearWithoutCompromiso('yearIngreso','sedeIngreso', 'nivelIngreso', 'gradoIngreso')" disabled val-previo="<?php echo (isset($yearIngreso) ? $yearIngreso: _getYear())?>">
        					                  <option value="">Selec. Año(*)</option>
        					                  <?php echo $comboYearCronograma?>
        					             </select>
    					             </div>
                                </div>
                                <div class="col-sm-6 mdl-input-group">
                                    <div class="mdl-icon"><i class="mdi mdi-school"></i> </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id=sedeGradoNivel name="observacion"  maxlength="120" disabled></textarea>
                                        <label class="mdl-textfield__label" for="sedeGradoNivel">Sede / Grado / Nivel</label>                            
                                        <span class="mdl-textfield__limit" for="sedeGradoNivel" data-limit="100"></span>   
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-remove_red_eye"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacion" name="observacion"  maxlength="120" <?php echo $disabled?> onchange="onSaveCampo(this, 'observacion', '<?php echo $campoSima?>', 0)"><?php echo (isset($observ) ? $observ: null)?></textarea>
                                        <label class="mdl-textfield__label" for="observacion">Observaciones</label>                            
                                        <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span>   
                                    </div>
                                </div> 
                             </div>
                        </div>
                    </div>   
                </div> 
            </section> 
                
            <section class="mdl-layout__tab-panel" id="tab-3">
                <div class="img-search" id="cont_search_empty" <?php echo (isset($vistaFamiliares) ? 'style="display: none;"' : 'style="display: block;"')?>>
                    <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_add.png">
                    <p>A&uacute;n no tiene familiar,</p>
                    <p>registralos por aqu&iacute;</p>
                </div>
                <div class="mdl-content-cards" id="cont_familiares">
                    <?php echo (isset($vistaFamiliares) ? $vistaFamiliares : null)?>
                </div>
                <input type="file" id="fotoFamiliar" name="fotoFamiliar" accept="image/*" style="display:none">
                <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                    </button>   
                </div> 
            </section> 
                
            <section class="mdl-layout__tab-panel" id="tab-4">
                <div class="mdl-content-cards">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Documentos</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0 br-b">   
                            <div id="cont_tb_documentos"></div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
            
    <!-- <?php if(_getSesion('accionDetalleAlumno') == 1 || _getSesion('accionDetalleAlumno') == 2){?> -->         
    <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
        <li class="mfb-component__wrap mdl-only-btn__animation">
            <button class="mfb-component__button--main" >
                <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
            </button>
            <button class="mfb-component__button--main" onclick="abrirModalRegistrarFamiliar()" data-mfb-label="Nuevo Familiar">
                <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>
            </button>
            <ul class="mfb-component__list">
                <!-- li>
                    <button class="mfb-component__button--child" data-mfb-label="Asignar Familiar" onclick="abrirModalAsignarFamiliar()">
                        <i class="mfb-component__child-icon mdi mdi-assignment_parent"></i>
                    </button>
                </li --> 
                <li>
                    <button class="mfb-component__button--child" data-mfb-label="Asignar Familia" onclick="abrirModalAsignarFamilia()">
                        <i class="mfb-component__child-icon mdi mdi-assignment_family"></i>
                    </button>
                </li>              
            </ul>  
        </li>
    </ul>
    <!-- <?php }?>   --> 
               
    <!-- MODALS -->  
    <div class="modal fade backModal" id="modalRegistrarFamiliar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="mdl-card mdl-card-fab">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text" id="tituloRegistrarEditarFamiliar">Registrar Familiar</h2>
                    </div>
                    <div class="mdl-card__supporting-text mdl-wizard">
                        <div class="row-fluid ">
                            <div class="col-sm-6 mdl-input-group">
                                <div class="mdl-icon">
                                    <i class="mdi mdi-featured_play_list"></i>
                                </div>
                                <div class="mdl-select">
                                    <select id="tipoDocRegFam" name="tipoDocRegFam" class="form-control selectButtonWiz" onchange="validateFamiliarExiste(1);changeDoc()" data-live-search="true" >
						                   <option value="">Selec.un Tipo de Doc.</option>
						                   <?php echo $comboTipoDoc?>
						            </select>
					            </div>
                            </div>
                          
                            <div class="col-sm-6 mdl-input-group">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="dniRegFam" name="dniRegFam" pattern="[0-9]+" onchange="validateFamiliarExiste(2)">        
                                    <label class="mdl-textfield__label" for="dniRegFam">Nro Doc.</label>                            
                                    <span class="mdl-textfield__error">Numero Documento solo contiene numeros</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 text-center">
                                <div class="mdl-icon" id="icon-regFam">
                                    <i class="mdi mdi-more_horiz"></i>
                                </div>
                            </div>
                            <p id="mensaje_reg_familia" style="display: none; text-align: center"></p>               
                            <div class="familiar_encontrado" id="cont_tb_familiar_encontrado" style="display: none"></div>
                            
                            <div class="col-xs-12 p-0 m-0 text-center mdl-wizard" id="cont-reg-fam">
                                <div id="rootwizard1" class="form-wizard form-wizard-horizontal cont_reg_familiar" style="display: none">                                    
								   <div class="form-wizard-nav">
									    <div class="progress">
										   <div class="progress-bar progress-bar-primary" style="width: 0%;"></div>
										</div>
										<ul class="nav nav-justified nav-pills">
											<li class="active wizard-label" id="li1">
											    <a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1">
											        <span class="step"></span>
											        <span class="title">Info. B&aacute;sica</span>
											        
											    </a>
											</li>
											<li class="wizard-label" id="li2">
											    <a data-toggle="tab" aria-expanded="false" href="#tab2" id="step2">
											        <span class="step"></span>
											        <span class="title">Refer. Domicilio</span>
											        
											    </a>
											</li>
											<li class="wizard-label" id="li3">
											    <a data-toggle="tab" aria-expanded="false" href="#tab3" id="step3">
											        <span class="step"></span>
											        <span class="title">Otras Refer.</span>
											    </a>
											</li>
											<li class="wizard-label" id="li4">
											    <a data-toggle="tab" aria-expanded="false" href="#tab4" id="step4">
											        <span class="step"></span>
											        <span class="title">Refer. Laborales</span>
											    </a>
											</li>
										</ul>
									</div>
								</div>                                  
                            
								<div class="tab-content cont_reg_familiar" style="display: none">
								    <div class="tab-pane active" id="tab1">
								        <div class="row-fluid">
    								        <div class="col-sm-12 mdl-wizzard__title">
                                                <h2 class="mdl-card__title-text display-label">Info. B&aacute;sica</h2>
                                            </div>
    								        <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-person"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato1 upper" type="text" id="nombrePersonaRegFam" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" name="nombrePersonaRegFam" maxlength="60" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">        
                                                    <label class="mdl-textfield__label" for="nombrePersonaRegFam">Nombres</label>
                                                    <span class="mdl-textfield__error">Nombre solo contiene letras</span>
                                                                                
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato1 upper" type="text" id="APPaternoPersonaRegFam" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" name="APPaternoPersonaRegFam" maxlength="60" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">        
                                                    <label class="mdl-textfield__label" for="APPaternoPersonaRegFam">Apellido Paterno</label>
                                                    <span class="mdl-textfield__error">Apellido Paterno solo contiene letras</span>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato1 upper" type="text" id="APMaternoPersonaRegFam" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" name="APMaternoPersonaRegFam" maxlength="60" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">        
                                                    <label class="mdl-textfield__label" for="APMaternoPersonaRegFam">Apellido Materno</label>                            
                                                    <span class="mdl-textfield__error">Apellido Materno solo contiene letras</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-favorite"></i>
                                                </div>
                                                <div class="mdl-select " >
                                                    <select id="viveRegFam" name="viveRegFam" class="form-control selectButtonWiz dato1" data-live-search="true" onchange="changeVive(); onChangeRegFam(); pasarSiguienteTab('dato1')">
                    						            <option value="">&#191;Vive?</option>
                    						            <?php echo $comboSiNoSinEncrypt?>
                    						        </select>
                						        </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
    												<button class="mdl-button mdl-js-button mdl-button--icon icon-left">
    													<i class="mdi mdi-date_range"></i>
    												</button>
    											</div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato1" type="text" id="fecNacPersonaRegFam" name="fecNacPersonaRegFam" onchange="onChangeRegFam();pasarSiguienteTab('dato1')" maxlength="10">        
                                                    <label class="mdl-textfield__label" for="fecNacPersonaRegFam">Fecha Nacimiento</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-mood"></i>
                                                </div>
                                                <div class="mdl-select s-parentesco">
                                                    <select id="parentescoRegFam" name="parentescoRegFam" class="form-control selectButtonWiz dato1" data-live-search="true" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">
                						                  <option value="">Selecione parentesco</option>
                						                  <?php echo $comboParentezco?>
            						                </select>
        						                </div>
                                            </div>
                					        <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-attach_money"></i>
                                                </div>
                                                <div class="mdl-select s-resp">
                                                    <select id="respeconomicoRegFam" name="respeconomicoRegFam" class="form-control selectButtonWiz dato1" data-live-search="true" onchange="onChangeRegFam(); pasarSiguienteTab('dato1')">
                						                  <option value="">&#191;Responsable econ&oacute;mico?</option>
                						                  <?php echo $comboSiNo?>
                						             </select>
            						             </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group ">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-supervisor_account"></i>
                                                </div>
                                                <div class="mdl-select s-apoderado">
                                                    <select id="apoderadoRegFam" name="apoderadoRegFam" class="form-control selectButtonWiz dato1" data-live-search="true" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">
                						                  <option value="">&#191;Apoderado?</option>
                						                  <?php echo $comboSiNo?>
                						             </select>
            						             </div>
                                            </div>   
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-contact_mail"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato1" type="text" id="correo1RegFam" name="correo1RegFam" pattern="[0-9,a-z,_a-z,.a,A-Z]+@[0-9,a-z,.,A-Z]+"  maxlength="100" onchange="onChangeRegFam();pasarSiguienteTab('dato1')">        
                                                    <label class="mdl-textfield__label" for="correo1RegFam">Correo Electronico 1</label>
                                                    <span class="mdl-textfield__error">Email no es valido</span>                            
                                                </div>
                                            </div>
                					    </div>
            					    </div>
            					    <div class="tab-pane" id="tab2">
								        <div class="row-fluid">
                					        <div class="col-sm-12 mdl-wizzard__title ">
                                                <h2 class="mdl-card__title-text display-label">Referencia Domicilio</h2>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-icon">
                                                   <i class="mdi mdi-home"></i>
                                               </div>
                                               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input dato2" type="text" id="direccionRegFam" name="direccionRegFam" onchange="onChangeRegFam();pasarSiguienteTab('dato2')" maxlength="100">   
                                                   <label class="mdl-textfield__label" for="direccionRegFam">Direcci&oacute;n</label>                                                                               
                                               </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input dato2" id="referenciaRegFam" name="referenciaRegFam" onchange="onChangeRegFam();pasarSiguienteTab('dato2')" maxlength="100">   
                                                   <label class="mdl-textfield__label" for="referenciaRegFam">Referencia</label>                            
                                               </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-icon">
                                                   <i class="mdi mdi-location_on"></i>
                                               </div>
                                               <div class="mdl-select s-region">
                                                   <select id="paisRegFam" name="paisRegFam" class="form-control selectButtonWiz dato2" data-live-search="true" onchange="changePaisRegFam();onChangeRegFam();pasarSiguienteTab('dato2');">
                						                  <option value="">Selec. pa&iacute;s</option>
                						                  <?php echo $comboPaisesSinEnc?>
                						           </select>
            						           </div>
                                            </div>                                           
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-select s-region">
                                                   <select id="departamentoRegFam" name="departamentoRegFam" onchange="getProvinciaPorDepartamento('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 2, null, null);pasarSiguienteTab('dato2')" class="form-control selectButtonWiz dato2" data-live-search="true"  data-noneSelectedText="Selec.Departamento">
                						                <option value="">Selec. Departamento</option>
                						                 <?php echo $comboDepartamento?>
                						           </select>
            						           </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-select s-region">
                                                   <select id="provinciaRegFam" name="provinciaRegFam" onchange="getDistritoPorProvincia('departamentoRegFam', 'provinciaRegFam', 'distritoRegFam', 3, null);pasarSiguienteTab('dato2')" class="form-control selectButtonWiz dato2" data-live-search="true" >
                						                  <option value="">Selec. Provincia</option>
                						           </select>
            						           </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-select s-region">
                                                   <select id="distritoRegFam" name="distritoRegFam" class="form-control selectButtonWiz dato2" data-live-search="true" onchange="onChangeRegFam();pasarSiguienteTab('dato2')">
                						                  <option value="">Selec. Distrito</option>
                						           </select>
            						           </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                               <div class="mdl-icon">
                                                   <i class="mdi mdi-phone"></i>
                                               </div>
                                               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input dato2" type="text" id="telfFijoRegFam" name="telfFijoRegFam" onchange="onChangeRegFam();pasarSiguienteTab('dato2')" maxlength="15">        
                                                   <label class="mdl-textfield__label" for="telfFijoRegFam">Tel&eacute;fono Fijo</label>                            
                                               </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato2" type="text" id="telfCelularRegFam" name="telfCelularRegFam" onchange="onChangeRegFam();pasarSiguienteTab('dato2')" maxlength="15">        
                                                    <label class="mdl-textfield__label" for="telfCelularRegFam">Tel&eacute;fono Celular</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-language"></i>
                                                </div>
                                                <div class="mdl-select  select-overflow">
                                                    <select id="idiomaRegFam" name="idiomaRegFam" class="form-control selectButtonWiz dato2" data-live-search="true" onchange="onChangeRegFam();pasarSiguienteTab('dato2')">
                						                   <option value="">Selec.un idioma</option>
                						                   <?php echo $comboIdioma?>
                						            </select>
            						            </div>
                                            </div>
                                        </div>
            					    </div>
            					    
            						<div class="tab-pane" id="tab3">
								        <div class="row-fluid">
                						    <div class="col-sm-12 mdl-wizzard__title">
                                                <h2 class="mdl-card__title-text display-label">Otras referencias</h2>
                                            </div>
                							<div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-supervisor_account"></i>
                                                </div>
                                                <div class="mdl-select ">
                                                    <select id="estadoCivilRegFam" name="estadoCivilRegFam" class="form-control selectButtonWiz dato3" data-live-search="true"  onchange="verificar(3,0)">
                						                   <option value="">Selec.Estado civil</option>
                						                   <?php echo $comboEstadoCivil?>
                						            </select>
            						            </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-school"></i>
                                                </div>
                                                <div class="mdl-select ">
                                                    <select id="exalumnoRegFam" name="exalumnoRegFam" class="form-control selectButtonWiz dato3" data-live-search="true" onchange="verificar(3,0)">
                						                   <option value="">&#191;Ex-Alumno?</option>
                						                   <?php echo $comboSiNo?>
                						            </select>
            						            </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-date_range"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato3" type="text" id="yearEgresoRegFam" name="yearEgresoRegFam" maxlength="4" onchange="verificar(3,0)">        
                                                    <label class="mdl-textfield__label" for="yearEgresoRegFam">A&ntilde;o egreso</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group mdl-input-group__button">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-school"></i>
                                                </div>
                                                <div class="mdl-select">
                                                     <select id="coleEgresoRegFam" name="coleEgresoRegFam" class="form-control selectButtonWiz dato3" data-live-search="true" onchange="verificar(3,0)">
                						                  <option value="">Selec.un colegio</option>
                						                  <?php echo $comboColegios?>
                						             </select>
                					             </div>
                					             <div class="mdl-btn">
                    					             <button class="mdl-button mdl-js-button mdl-button--icon" id="btnAgregarColegio">
    										             <i class="mdi mdi-add"></i>
    										         </button>
                			                     </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-mail"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato3" type="text" id="correo2RegFam" name="correo2RegFam" pattern="[0-9,a-z,_a-z,.a,A-Z]+@[0-9,a-z,.,A-Z]+" maxlength="100" onchange="verificar(3,0)">        
                                                    <label class="mdl-textfield__label" for="correo2RegFam">Correo Electronico 2</label>
                                                    <span class="mdl-textfield__error">Email no es valido</span>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-accessibility"></i>
                                                </div>
                                                <div class="mdl-select ">
                                                    <select id="religionRegFam" name="religionRegFam" class="form-control selectButtonWiz dato3" data-live-search="true" onchange="verificar(3,0)">
                						                  <option value="">Selec.Religi&oacute;n</option>
                						                  <?php echo $comboReligion?>
                						             </select>
            						             </div>
                                            </div>
                                        </div>
            					    </div>
            					    
            					    <div class="tab-pane" id="tab4">
								        <div class="row-fluid">
                					        <div class="col-sm-12 mdl-wizzard__title">
                                                <h2 class="mdl-card__title-text display-label">Referencias Laborales</h2>
                                            </div>
                					        <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-business"></i>
                                                </div>
                                                <div class="mdl-select ">
                                                    <select id="situacionLaboralRegFam" name="situacionLaboralRegFam" class="form-control selectButtonWiz dato4" data-live-search="true"  onchange="changeSituacionLaboral();verificar(4,0)">
                						                   <option value="">Situaci&oacute;n Laboral</option>
                						                   <?php echo $comboSituacionLabo?>
                						            </select>
            						            </div>
                                            </div>
            							    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-work"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="ocupacionRegFam" name="ocupacionRegFam" maxlength="90" onchange="onChangeRegFam();verificar(4,0)">        
                                                    <label class="mdl-textfield__label" for="ocupacionRegFam">Ocupaci&oacute;n</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon"></div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="centroTrabajoRegFam" name="centroTrabajoRegFam" maxlength="100" onchange="onChangeRegFam();verificar(4,0)">        
                                                    <label class="mdl-textfield__label" for="centroTrabajoRegFam">Centro de trabajo</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-location_on"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="direccionTrabajoRegFam" name="direccionTrabajoRegFam" maxlength="100" onchange="verificar(4,0)">
                                                    <label class="mdl-textfield__label" for="direccionTrabajoRegFam">Direcci&oacute;n</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-select s-region">
                                                    <select id="departamentoTrabajoRegFam" name="departamentoTrabajoRegFam" onchange="getProvinciaPorDepartamento('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 2, null, null);verificar(4,0)" class="form-control selectButtonWiz dato4" data-live-search="true">
                						                   <option value="">Selec.Departamento</option>
                						                   <?php echo $comboDepartamento?>
                						            </select>
            						            </div>
                                             </div>
                                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-select s-region">
                                                    <select id="provinciaTrabajoRegFam" name="provinciaTrabajoRegFam" onchange="getDistritoPorProvincia('departamentoTrabajoRegFam', 'provinciaTrabajoRegFam', 'distritoTrabajoRegFam', 3, null);verificar(4,0)" class="form-control selectButtonWiz dato4" data-live-search="true" >
                						                   <option value="">Selec.Provincia</option>
                						            </select>
            						            </div>
                                             </div>
                                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-select s-region" >
                                                    <select id="distritoTrabajoRegFam" name="distritoTrabajoRegFam" class="form-control selectButtonWiz dato4" data-live-search="true" onchange="verificar(4,0)">
                						                   <option value="">Selec.Distrito</option>
                						            </select>
            						            </div>
                                             </div>
                                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-phone"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="telefonoTrabajoRegFam" pattern="[(+0-9+)-0-9]+" name="telefonoTrabajoRegFam" maxlength="15" onchange="verificar(4,0)">        
                                                    <label class="mdl-textfield__label" for="telefonoTrabajoRegFam">Tel&eacute;fono</label>  
                                                    <span class="mdl-textfield__error">Tel&eacute;fono solo contiene digitos</span>                           
                                                </div>
                                             </div>
                                             <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-monetization_on"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="sueldoRegFam" pattern="[s/. ,$/. 0-9.]+" name="sueldoRegFam" maxlength="10" onchange="verificar(4,0)">        
                                                    <label class="mdl-textfield__label" for="sueldoRegFam">Sueldo</label>    
                                                    <span class="mdl-textfield__error">Sueldo no puede contener letras</span>                         
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 mdl-input-group">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input dato4" type="text" id="cargoRegFam" name="cargoRegFam" maxlength="100" onchange="verificar(4,0)">        
                                                    <label class="mdl-textfield__label" for="cargoRegFam">Cargo</label>                            
                                                </div>
                                            </div>
                                        </div>
        					        </div>
								</div>
							</div>
                        </div>
                    </div>
			       <div class="mdl-card__actions">
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-save__load" onclick="InsertarFamiliar()" id="botonGuardarFamiliar">Guardar</button>
                    </div>         
                 </div>
             </div>
          </div>
       </div>
            
       <div class="modal fade backModal" id="modalAsignarFamiliar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content ">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Asignar Familiares</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0">
                            <div class="row m-0 p-0"> 
                                <div class="col-sm-12 mdl-input-format p-r-20 p-l-20">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_nombre_familiar">
                                        <input class="mdl-textfield__input upper" type="text" id="nombreFamiliar" name="nombreFamiliar">        
                                        <label class="mdl-textfield__label" for="nombreFamiliar">Nombre familiar</label>                            
                                    </div>
                                </div>
                                <div id="cont_tabla_familiares_busqueda"></div>
                            </div>
                        </div>
    					<div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button " data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-button--raised" id="buttonEstado" onclick="abrirModalConfirmAsignarFamiliares()">Aceptar</a>
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
            
        <div class="modal fade backModal" id="modalAsignarFamilia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Asignar Familia</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0 br-b">
                            <div class="row-fluid"> 
                                <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_nombre_familia">
                                        <input class="mdl-textfield__input upper" type="text" id="nombreFamilia" name="nombreFamilia" onkeyup="activeDesactiveSearch();">        
                                        <label class="mdl-textfield__label" for="nombreFamilia">Nombre familia</label>                            
                                    </div>
                                    <div class="mdl-btn">
            			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscar" onclick = "busquedaFamilias();">
								            <i class="mdi mdi-search"></i>
								        </button>
            			            </div> 
                                </div>
                                <div id="cont_tabla_familias_busqueda"></div>  
                            </div>
                        </div>                                         
                    </div>
                </div>
            </div>
        </div>
        
        <div id="modalConfirmDesagsinarFamiliar" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text"> &#191;Deseas desasignar al familiar&#63;</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <p>Al desagsinar al familiar de la familia, este familiar no podra tener acceso a la informaci&oacute;n del estudiante</p>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button " data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-save__load accept" id="buttonDesignar" onclick="desagsinarFamiliar()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalConfirmAgsinarFamilia" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Asignar estudiante a familia?</h2>
    					</div>
    					<div class="mdl-card__supporting-text"> 
    					   Familia:
    					   <p id="nombreFamiliaAsignar"></p>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised" id="buttonEstado" onclick="agsinarFamilia()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalConfirmAgsinarFamiliares" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar alumno a familia</h2>
    					</div>
    					<div class="mdl-card__supporting-text"> 
    					   &#191;Deseas asignar a todos los familiares seleccionados?
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button " data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised" id="buttonEstado" onclick="asignarFamiliares()">Aceptar</</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade backModal" id="modalRegistrarColegio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Registrar Colegio</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-r-20 p-l-20 m-b-15">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreColegio" name="nombreColegio" maxlength="100" onchange="buscarColegio()">        
                                        <label class="mdl-textfield__label" for="nombreColegio">Colegio</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 p-0 m-0 m-b-20">
                                    <div id="cont_tb_colegios"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button" data-dismiss="modal">CERRAR</button></a>
                            <button class="mdl-button mdl-js-button mdl-button--raised" onclick="registrarColegio()">GUARDAR</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    
        <div class="modal fade backModal" id="modalChangeFechaDocumento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cambiar Fecha de entrega</h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <div class="row-fluid">
                                <div class="col-sm-12 mdl-input-group">
                                    <div class="mdl-icon">
    							        <button class="mdl-button mdl-js-button mdl-button--icon">
    								       <i class="mdi mdi-date_range"></i>
    							        </button>
    							    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fechaDocumentoEdit" name="fechaDocumentoEdit" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fechaDocumentoEdit">Fecha Entrega</label>                            
                                    </div>
                                </div>
                            </div>
    					</div>    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button " data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised" id="buttonEstado" onclick="changeFechaDocumento()">Aceptar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div id="modalConfirmGenerarUsuario" class="modal fade in" tabindex="-1">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="tituloGenerarUsuario"></h2>
    					</div>
    					<div class="img-search i-detalle">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_email.png">
                            <p>Se le enviar&aacute; a su correo</p>
                            <p>el usuario y contraseña</p>
                        </div>
						<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button " data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-button--raised send" id="buttonGenerar" onclick="generarUsuario()">Enviar</button>
                        </div>
                    </div>                    
    			</div>
    		</div>
    	</div>
    	
    	<div class="modal fade" id="modalEditarFotoEstudiante" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Foto Estudiante</h2>
    					</div>
					    <div class="mdl-card__supporting-text text-center m-b-10">    	 				
					        <div class="img-container">
                              <img id="fotoRecortarEstudiante" class="img-responsive">
                            </div>					        
    					</div>    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick ="subirImagenRecortadaEstudiante()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEditarFotoFamiliar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Foto Familiar</h2>
    					</div>
					    <div class="mdl-card__supporting-text text-center m-b-10">    	 				
					        <div class="img-container">
                              <img id="fotoRecortarFamiliar" class="img-responsive">
                            </div>					        
    					</div>    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick ="subirImagenRecortadaFamiliar()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>

    	<div class="modal fade" id="modalRegistrarColegio" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
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
    	
    	<div class="modal fade" id="modalAulasCantidad" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-md">
    			<div class="modal-content">
    				<div class="mdl-card">
    					<div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Aulas encontradas</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-rl-0" id="cont_tabla_aulas_cantidad"></div>
    					<div class="mdl-card__actions">
    						<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
    					</div>
    					<div class="mdl-card__menu" >
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>                               
                        </div>
    				</div>
    			</div>
    		</div>
    	</div>
            
        <img id="fotoPrueba" style="display:none">
    	
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
	    <script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>wizard/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>cropper/cropper.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>velocity/js/velocity.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_PUBLIC_MATRICULA?>js/jsdetallealumno.js" charset="UTF-8"></script>
        <script type="text/javascript">
       	    returnPage();
            var paisPeru = '<?php echo $paisPeru?>';
            var paisPeruEnc = '<?php echo $paisPeruEnc?>';
            init();
            <?php if($tipoDoc != null){?>
                        if(<?php echo $tipoDoc?> == 2){
                    		$("#nro_documento").attr('maxlength','8');
                    	} else if(<?php echo $tipoDoc?> == 1){
                    		$("#nro_documento").attr('maxlength','12');
                    	}
            <?php }?>
            changePaisEstudiante(1);
            <?php if($disabled == 'disabled') {?>
                      disEnabledInputComboGroup(["sexoPersona","tipoDoc","pais","departamento","provincia","distrito",
                                                 "coleProcedencia","religion","estadoCivil"],true);
            <?php }?>
            setTimeout(function(){ $('.mfb-component__button--main').removeClass('is-up'); }, 500);

            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
            	    $('.selectButton').selectpicker('mobile');
            	} else {
            	    $('.selectButton').selectpicker();
            	}
            });   
            $(document).ready(function() {
        	    $('[data-toggle="tooltip"]').tooltip(); 
        	    $('[data-toggle="popover"]').popover();
            });
        </script>
    </body>
</html>