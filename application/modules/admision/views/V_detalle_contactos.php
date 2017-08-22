<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>              
		<title>Detalle Contacto | <?php echo NAME_MODULO_ADMISION?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_ADMISION?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_ADMISION?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active " id="tab-1">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Detalle postulante</h2>
                            </div>  
                            <div class="mdl-card__supporting-text br-b">
                                <div class="row-fluid">
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-account_box"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apPaternoPostulante" name="apPaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" maxlength="60" <?php echo $disabled?> value="<?php echo (isset($apePaterno) ? $apePaterno: null)?>"
                                                   onchange="onChangeCampo('ape_paterno', '<?php echo $noEnc?>', 'apPaternoPostulante')" val-previo="<?php echo (isset($apePaterno) ? $apePaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Paterno (*)</label>  
                                            <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                                                                      
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apMaternoPostulante" name="apMaternoPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" maxlength="60" <?php echo $disabled?> value="<?php echo (isset($apeMaterno) ? $apeMaterno: null)?>"
                                                   onchange="onChangeCampo('ape_materno', '<?php echo $noEnc?>', 'apMaternoPostulante')" val-previo="<?php echo (isset($apeMaterno) ? $apeMaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Materno (*)</label> 
                                            <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                                                                         
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="nombresPostulante" name="nombresPostulante" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" maxlength="60" <?php echo $disabled?> value="<?php echo (isset($nombres) ? $nombres: null)?>"
                                                   onchange="onChangeCampo('nombres', '<?php echo $noEnc?>', 'nombresPostulante')" val-previo="<?php echo (isset($nombres) ? $nombres: null)?>">
                                            <label class="mdl-textfield__label" for="">Nombres (*)</label>
                                            <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                                                                          
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-wc"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectSexoPostulante" name="selectSexoPostulante" class="form-control selectButton" data-live-search="true" 
                                                    data-noneSelectedText="Selec. sexo" <?php echo $disabled?> onchange="onChangeCampo('sexo', '<?php echo $enc?>', 'selectSexoPostulante')">
        						                <option value="">Selec. sexo</option>
                			                    <?php echo $comboSexo?>
        						            </select>                                           
                                        </div>                                                    
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-school"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton" onchange="onChangeCampo('grado_nivel', '<?php echo $enc?>', 'selectGradoNivel');getSedesByNivel('selectGradoNivel','selectSedeInteres')" data-live-search="true" value="<?php echo (isset($gradoNivel) ? $gradoNivel: null)?>"
                                                    data-noneSelectedText="Selec. Grado y Nivel" <?php echo $disabled?>>
        						                  <option value="">Selec. Grado y Nivel</option>
        						                  <?php echo $comboGradoNivel?>
        						             </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-select">
                                            <select id="selectSedeInteres" name="selectSedeInteres" class="form-control selectButton" data-live-search="true" value="<?php echo (isset($sedeInt) ? $sedeInt: null)?>"
                                                    data-noneSelectedText="Selec. Sede de inter&eacute;s" attr-abc="nid_nivel" <?php echo $disabled?> onchange="onChangeCampo('sede_interes', '<?php echo $enc?>', 'selectSedeInteres')">
        						                  <option value="">Selec. Sede de inter&eacute;s</option>
        						             </select>                                              
                                        </div>                                                    
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-select">
                                            <select id="selectColegioProc" name="selectColegioProc" class="form-control selectButton" data-live-search="true"  value="<?php echo (isset($colegioProcedencia) ? $colegioProcedencia: null)?>"
                                                    data-noneSelectedText="Selec. Colegio de procedencia" <?php echo $disabled?> onchange="onChangeCampo('colegio_procedencia', '<?php echo $enc?>', 'selectColegioProc')">
        						                  <option value="">Selec. Colegio de procedencia</option>
                			                          <?php echo $comboColegios?>
        						             </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                    <div class="mdl-icon">
    							        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfechaNacPostulante" >
    								       <i class="mdi mdi-date_range"></i>
    							        </button>                                                   
                                    </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaNacPostulante" name="fechaNacPostulante" 
                                            maxlength = "10" onchange="onChangeCampo('fecha_nacimiento', '<?php echo $noEnc?>', 'fechaNacPostulante')" <?php echo $disabled?> value="<?php echo (isset($fechaNac) ? $fechaNac: null)?>">
                                            <label class="mdl-textfield__label" for="fechaNacPostulante">Fecha de nacimiento del postulante</label>                                                                 
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-featured_play_list"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectTipoDocumentoPostulante" name="selectTipoDocumentoPostulante" class="form-control selectButton" data-live-search="true" 
    					                    data-noneSelectedText="Selec. tipo de documento" <?php echo $disabled?> value="<?php echo (isset($tipoDocPostulante) ? $tipoDocPostulante: null)?>" onchange="onChangeCampo('tipo_documento', '<?php echo $noEnc?>', 'selectTipoDocumentoPostulante'); habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante'); changeMaxlength('selectTipoDocumentoPostulante','nroDocumentoPostulante')">
                			                <option value="">Selec. tipo de documento</option>
                			                    <?php echo $comboTipoDocumento?>
                			                </select>
                			            </div>
    					            </div>
    					            <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                            <input class="mdl-textfield__input" type="text" id="nroDocumentoPostulante" name="nroDocumentoPostulante" maxlength = "12" 
                                            <?php echo $disabled?> value="<?php echo (isset($nroDoc) ? $nroDoc: null)?>" onchange="onChangeCampo('nro_documento', '<?php echo $noEnc?>', 'nroDocumentoPostulante')">
                                            <label class="mdl-textfield__label" for="nroDocumentoPostulante">N&uacute;mero del documento</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-remove_red_eye"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="observacion" name="observacion" maxlength="65" <?php echo $disabled?> value="<?php echo (isset($observacion) ? $observacion: null)?>"
                                            onchange="onChangeCampo('obser_solicitud', '<?php echo $noEnc?>', 'observacion')">        
                                            <label class="mdl-textfield__label" for="observacion">Observaci&oacute;n</label>
                                            <span class="mdl-textfield__limit" for="observacion" data-limit="60"></span>                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-2">
                        <div class="mdl-content-cards" id="cont_parientes">
                            <?php echo $parientes?>
                        </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-3">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Historial del postulante</h2>
                            </div>
                            <div id="tbHistorial" class="mdl-card__supporting-text p-0 br-b table-responsive">
                                <?php echo $tablaHistorial?>
                            </div>
                        </div>
                    </div>
                </section>
                
            </main>
        </div>
        
        <!-- Modals -->        
        <div class="modal fade" id="modalDetalleContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="tituloModalDetalleContacto"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row-fluid">					       
                               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-supervisor_account"></i></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectParentescoDetalleContacto" name="selectParentescoDetalleContacto" data-live-search="true">
                			                <option value="">Seleccione parentesco (*)</option>
                			                <?php echo (isset($comboParentesco) ? $comboParentesco : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apPaternoContactoDetalle" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apPaternoContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apPaternoContactoDetalle">Apellido Paterno (*)</label>    
                                        <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                          
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apMaternoContactoDetalle" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="apMaternoContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apMaternoContactoDetalle">Apellido Materno</label>                            
                                        <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                                      
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreContactoDetalle" pattern="[A-Z,a-z,��, ,�����,�����,�����,�����]*" name="nombreContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreContactoDetalle">Nombre (*)</label>
                                        <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                              
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-credit_card"></i></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectTipoDocDetalleContacto" name="selectTipoDocDetalleContacto" data-live-search="true"
    					                       onchange="habilitarCampo('selectTipoDocDetalleContacto', 'nroDocumentoContactoDetalle')">
                			                <option value="">Seleccione Tipo Documento</option>
                			                <?php echo (isset($comboTipoDocumento) ? $comboTipoDocumento : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                        <input class="mdl-textfield__input" type="text" id="nroDocumentoContactoDetalle" name="nroDocumentoContactoDetalle" maxlength="12" disabled>        
                                        <label class="mdl-textfield__label" for="nroDocumentoContactoDetalle">N&uacute;mero de documento</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-wc"></i></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectSexoDetalleContacto" name="selectSexoDetalleContacto" data-live-search="true">
                			                <option value="">Seleccione sexo</option>
                			                <?php echo (isset($comboSexo) ? $comboSexo : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon">
                                        <i class="mdi mdi-email"></i>
                                    </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="correoContactoDetalle" pattern="[��,A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" name="correoContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="correoContactoDetalle">Correo Electr&oacute;nico</label>   
                                        <span class="mdl-textfield__error">El formato del correo es incorrecto</span>                                                                 
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon">
                                        <i class="mdi mdi-phone"></i>
                                    </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="celularContactoDetalle" pattern="[(+0-9+)-0-9, ]+"  name="celularContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="celularContactoDetalle">Tel&eacute;fono Celular</label>
                                         <span class="mdl-textfield__error">Numero de celular solo debe contener numeros</span> 
                                                
                                                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectOperadorDetalleContacto" name="selectOperadorDetalleContacto" data-live-search="true">
                			                <option value="">Seleccione operador telef&oacute;nico</option>
                			                <?php echo (isset($comboOperadores) ? $comboOperadores : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fijoContactoDetalle" pattern="[(+0-9+)-0-9, ]+" name="fijoContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="fijoContactoDetalle">Tel&eacute;fono Fijo</label>  
                                        <span class="mdl-textfield__error">Numero de fijo solo debe contener numeros</span>                          
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-group_work"></i></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectCanalDetalleContacto" name="selectCanalDetalleContacto" data-live-search="true">
                			                <option value="">Seleccione canal de captaci&oacute;n</option>
                			                <?php echo (isset($comboCanales) ? $comboCanales : null)?>
                			           </select>
            			           </div>
					           </div>
					           
					           <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"><i class="mdi mdi-location_on"></i></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectDepartamentoDetalleContacto" name="selectDepartamentoDetalleContacto" data-live-search="true"
    					                onchange='getProvinciaPorDepartamento("selectDepartamentoDetalleContacto", "selectProvinciaDetalleContacto", "selectDistritoDetalleContacto", 2)'>
                			                <option value="">Seleccione Departamento</option>
                			                <?php echo (isset($comboDepartamento) ? $comboDepartamento : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectProvinciaDetalleContacto" name="selectProvinciaDetalleContacto" data-live-search="true" onchange='getDistritoPorProvincia("selectDepartamentoDetalleContacto", "selectProvinciaDetalleContacto", "selectDistritoDetalleContacto", 3)'>
                			                <option value="">Seleccione Provincia</option>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-4 mdl-input-group">
                                   <div class="mdl-icon"></div>
                                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectDistritoDetalleContacto" name="selectDistritoDetalleContacto" data-live-search="true">
                			                <option value="">Seleccione Distrito</option>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-12 col-md-12 mdl-input-group">
					                <div class="mdl-icon">
                                        <i class="mdi mdi-home"></i>
                                    </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="referenciaContactoDetalle" name="referenciaContactoDetalle" maxlength="100">        
                                        <label class="mdl-textfield__label" for="referenciaContactoDetalle">Domicilio</label>                            
                                    </div>
				               </div>					           
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnGuardarDetalleContacto">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmeDeleteContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar contacto</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0 ">
					           &#191;Deseas eliminar el contacto seleccionado?
					       </div>
    					</div>
    					<div class="mdl-card__actions p-t-20">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" onclick="eliminarContacto()">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalRazonInasistencia" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Raz&oacute;n de inasistencia</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					        <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="razonInasistencia" name="razonInasistencia" rows="5" cols="50" disabled>-</textarea>
                                        <label    class="mdl-textfield__label" for="razonInasistencia">Observaci&oacute;n</label>
                                    </div>
            					</div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap" id="li_menu_1" style="display: none;">
                 <?php echo (isset($btnCrearPariente) ? $btnCrearPariente: null)?> 
            </li>
        </ul>
        
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mark/jquery.highlight.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsdetallecontactos.js"></script>
        
        <script type="text/javascript">
            init();
            $(".mdl-button__return").click(function() {
            	location.href = "c_contactos";
            });
            returnPage();
            setearCombo('selectSexoPostulante', '<?php echo $sexo?>');
            setearCombo('selectTipoDocumentoPostulante', '<?php echo $tipoDocPostulante?>');
            setearCombo('selectColegioProc', '<?php echo $colegioProcedencia?>');
            setearCombo('selectGradoNivel', '<?php echo $gradoNivel?>');
            if($("#selectGradoNivel").val().length != 0){
            	getSedesByNivel('selectGradoNivel','selectSedeInteres');
            	setearCombo('selectSedeInteres', '<?php echo $sedeInt?>');

                if('<?php echo $disabled?>' == 'disabled'){
                	$('#selectSedeInteres').prop('disabled', true);
              	    $('#selectSedeInteres').selectpicker('refresh');
                }
            }else if(  '<?php echo $gradoNivel?>'.length == 0){
                	$('#selectSedeInteres').attr('disabled', true);
            }if('<?php echo $comboTipoDocumento?>'.length == 0){
            	$('#nroDocumentoPostulante').attr('disabled', true);
            }
        </script>
	</body>
</html>