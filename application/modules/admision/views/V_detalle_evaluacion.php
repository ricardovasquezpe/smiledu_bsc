<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();
        </script>
		<title><?php echo NAME_MODULO_ADMISION?></title>
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
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <style type="text/css">
            /* -----
            SVG Icons - svgicons.sparkk.fr
            ----- */
            
            /*.svg-icon {
              width: 30em;
              height: 30em;
            }*/
            /*
            .svg-icon path,
            .svg-icon polygon,
            .svg-icon rect {
              fill: #4691f6;
            }*/
            
            .svg-icon circle {
              stroke: #e5e5e5;
              stroke-width: 1;
            }
        </style>
	</head>
	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel is-active " id="tab-1">
                    <div class="mdl-content-cards" style="text-align: center;">  
                                 <?php echo $cardsDiagnosticos?>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="tab-2">
                    <div class="mdl-content-cards m-b-20" style="text-align: center;" id="cardEvaEntrev">                    
                        <?php if($completoCursos == 1){
                                  echo $cardEntrevista;
                              } else { ?> 
                            <div class="img-search">
                                <img src="<?php echo RUTA_IMG?>smiledu_faces/teacher_not_found.png">
                                <p><strong>&#161;Ups!</strong></p>
                                <p>No se ha terminado de evaluar al estudiante</p>
                            </div>
                        <?php } ?>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel" id="tab-3">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Detalle postulante</h2>
                            </div>
                            <div class="mdl-card__supporting-text br-b">
                                <div class="row-fluid">
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-account_circle"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apPaternoPostulante" name="apPaternoPostulante" maxlength="60" disabled value="<?php echo (isset($apePaterno) ? $apePaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Paterno (*)</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apMaternoPostulante" name="apMaternoPostulante" maxlength="60" disabled value="<?php echo (isset($apeMaterno) ? $apeMaterno: null)?>"
                                                   onchange="onChangeCampo('ape_materno', '<?php echo $noEnc?>', 'apMaternoPostulante')" val-previo="<?php echo (isset($apeMaterno) ? $apeMaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Materno (*)</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="nombresPostulante" name="nombresPostulante" maxlength="60" disabled value="<?php echo (isset($nombres) ? $nombres: null)?>"
                                                   onchange="onChangeCampo('nombres', '<?php echo $noEnc?>', 'nombresPostulante')" val-previo="<?php echo (isset($nombres) ? $nombres: null)?>">
                                            <label class="mdl-textfield__label" for="">Nombres (*)</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-wc"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectSexoPostulante" name="selectSexoPostulante" class="form-control selectButton" data-live-search="true"
                                                    data-noneSelectedText="Selec. sexo" disabled onchange="onChangeCampo('sexo', '<?php echo $enc?>', 'selectSexoPostulante')">
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
                                            <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton" onchange="onChangeCampo('grado_nivel', '<?php echo $enc?>', 'selectGradoNivel');" data-live-search="true" value="<?php echo (isset($gradoNivel) ? $gradoNivel: null)?>"
                                                    data-noneSelectedText="Selec. Grado y Nivel" val-previo="<?php echo (isset($gradoNivel) ? $gradoNivel: null)?>">
        						                  <option value="">Selec. Grado y Nivel</option>
        						                  <?php echo $comboGradoNivel?>
        						             </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-select">
                                            <select id="selectSedeInteres" name="selectSedeInteres" class="form-control selectButton" data-live-search="true" value="<?php echo (isset($sedeInt) ? $sedeInt: null)?>"
                                                    data-noneSelectedText="Selec. Sede de inter&eacute;s" attr-abc="nid_nivel" onchange="onChangeCampo('sede_interes', '<?php echo $enc?>', 'selectSedeInteres')" val-previo="<?php echo (isset($sedeInt) ? $sedeInt: null)?>">
        						                  <option value="">Selec. Sede de inter&eacute;s</option>
        						             </select>
                                        </div>                                                    
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-select">
                                            <select id="selectColegioProc" name="selectColegioProc" class="form-control selectButton" data-live-search="true" value="<?php echo (isset($colegioProcedencia) ? $colegioProcedencia: null)?>"
                                                    data-noneSelectedText="Selec. Colegio de procedencia" disabled onchange="onChangeCampo('colegio_procedencia', '<?php echo $enc?>', 'selectColegioProc')">
        						                  <option value="">Selec. Colegio de procedencia</option>
                			                          <?php echo $comboColegios?>
        						             </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                    <div class="mdl-icon">
                                            <i class="mdi mdi-date_range"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaNacPostulante" data-inputmask="'alias': 'date'" name="fechaNacPostulante" 
                                            maxlength = "10" onchange="onChangeCampo('fecha_nacimiento', '<?php echo $noEnc?>', 'fechaNacPostulante')" disabled value="<?php echo (isset($fechaNac) ? $fechaNac: null)?>">
                                            <label class="mdl-textfield__label" for="fechaNacPostulante">Fecha de nacimiento del postulante</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-chrome_reader_mode"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="selectTipoDocumentoPostulante" name="selectTipoDocumentoPostulante" class="form-control selectButton" data-live-search="true" 
    					                    data-noneSelectedText="Selec. tipo de documento" disabled value="<?php echo (isset($tipoDocPostulante) ? $tipoDocPostulante: null)?>" onchange="onChangeCampo('tipo_documento', '<?php echo $noEnc?>', 'selectTipoDocumentoPostulante'); habilitarCampo('selectTipoDocumentoPostulante','nroDocumentoPostulante'); changeMaxlength('selectTipoDocumentoPostulante','nroDocumentoPostulante')">
                			                <option value="">Selec. tipo de documento</option>
                			                    <?php echo $comboTipoDocumento?>
                			                </select>
                			            </div>
    					            </div>
    					            <div class="col-sm-6 col-md-4 mdl-input-group">
                                        <div class="mdl-icon"></div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                            <input class="mdl-textfield__input" type="text" id="nroDocumentoPostulante" name="nroDocumentoPostulante" maxlength = "12" 
                                            disabled value="<?php echo (isset($nroDoc) ? $nroDoc: null)?>" onchange="onChangeCampo('nro_documento', '<?php echo $noEnc?>', 'nroDocumentoPostulante')">
                                            <label class="mdl-textfield__label" for="nroDocumentoPostulante">N&uacute;mero del documento</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-remove_red_eye"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="observacion" name="observacion" maxlength="60" disabled value="<?php echo (isset($observacion) ? $observacion: null)?>"
                                            onchange="onChangeCampo('obser_solicitud', '<?php echo $noEnc?>', 'observacion')">        
                                            <label class="mdl-textfield__label" for="observacion">Observaci&oacute;n</label>
                                            <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span>                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-4">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Ficha Psicológica</h2>
                            </div>
                            <div id="cont_psicologica" class="mdl-card__supporting-text p-0 br-b table-responsive">
                                <?php echo $ficha?>    
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
        
        <?php echo isset($fabEntrevista)?$fabEntrevista:null; ?>
        
        <div class="modal fade" id="entrevista" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Llamar a familia</h2>
    					</div>
    					<div class="mdl-card__menu" style="right: 24px; top: 16px; width: 175px">
    						<div style="position: absolute; right: 42px; top: 2.5px; color: #FF9800" id="timer"></div>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
			                   <div class="col-md-3 col-xs-3">
			                       <svg style="width: 150px;height: 100%;cursor:pointer"  viewBox="0 0 80 40" onclick="llamar($(this));" id="svgLlamar">
        							   <path d="M22 20c-2 2-2 4-4 4s-4-2-6-4-4-4-4-6 2-2 4-4-4-8-6-8-6 6-6 6c0 4 4.109 12.109 8 16s12 8 16 8c0 0 6-4 6-6s-6-8-8-6z" fill="#4CAF50"></path>
        						   </svg>
        						           						   
        						   <p id="llamar_msj">Llamar al PPFF</p>        						   
			                   </div>
			                   <div class="col-md-4 col-xs-4">
                                    <div id="circleG" style="display:none">
                                    	<div id="circleG_1" class="circleG"></div>
                                    	<div id="circleG_2" class="circleG"></div>
                                    	<div id="circleG_3" class="circleG"></div>
                                    </div>				                   
			                   </div>
			                   <div class="col-md-3 col-xs-3">
			                       <svg style="width: 150px; height: 100%; cursor:pointer"  class="svg-icon" viewBox="0 0 80 40" id="svgColgar" onclick="colgar($(this));">
        							   <path d="M22 20c-2 2-2 4-4 4s-4-2-6-4-4-4-4-6 2-2 4-4-4-8-6-8-6 6-6 6c0 4 4.109 12.109 8 16s12 8 16 8c0 0 6-4 6-6s-6-8-8-6z" fill="#9E9E9E"></path>
        						   </svg>
        						   <p id="colgar_msj"></p>
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
        
        <div class="modal fade" id="cancel_entrevista" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cancelar Entrevista</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-12 mdl-input-group m-b-0">
			                        <div class="mdl-icon"><i class="mdi mdi-comment"></i></div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="detalle_entrev_cancel" name="detalle_entrev_cancel" 
                                                  rows="3"></textarea>
                                        <label class="mdl-textfield__label" for="detalle_entrev_cancel">Detalle (*)</label>
                                    </div>
                                </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="cancelarEntrevista()" id="btnCancelEntrev">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="datos_entrevista" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Detalle</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0" id="contDetalle"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modals -->
        <div class="modal fade" id="modalAgregarArchivos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar archivos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
                                <input type="file" id="documentosPublicacion" name="documentosPublicacion[]" accept="image/*" multiple />
                                <div id="imagenesPreview" class="m-t-15"></div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarArchivos()" id="btnGuardarImagenes">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAgregarArchivosEntrevista" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar archivos</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
                                <input type="file" id="documentosPublicacionEntrevista" name="documentosPublicacionEntrevista[]" accept="image/*" multiple />
                                <div id="imagenesPreviewEntrevista" class="m-t-15"></div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarArchivosEntrevista()" id="btnGuardarImagenesEntrevista">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
            
        <div class="modal fade" id="modalConfirAgregarArchivos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Confirmaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           &#191;Deseas agregar los archivos seleccionados?
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarArchivos()">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalIndicadoresCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="titleIndicadoresCurso"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
                                <div id="cont_tb_indicadores"></div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="cerrarModalIndicadores()">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarIndicadoresCurso()" id="btnGuardarIndicadores">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalMigrarMatricula" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">¿Estas segura de pasarlo al proceso de matricula?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
                                Recuerda que al pasarlo al proceso de matricula todos sus datos del estudiante y de sus familiares se enviaran al sistema de matricula.
                                Se enviará las credenciales del sistema de matricula al correo del familiar registrado
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="migrarMAtricula()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsdetalleevaluacion.js" charset="utf-8"></script>
        <script type="text/javascript">
            var modalInit = '<?php echo isset($modalInit) ? $modalInit : null; ?>';
            returnPage();
            init();
            
            setearCombo('selectSexoPostulante', '<?php echo $sexo?>');
            setearCombo('selectTipoDocumentoPostulante', '<?php echo $tipoDocPostulante?>');
            setearCombo('selectColegioProc', '<?php echo $colegioProcedencia?>');
            setearCombo('selectGradoNivel', '<?php echo $gradoNivel?>');
            if($("#selectGradoNivel").val().length != 0){
            	getSedesByNivel('selectGradoNivel','selectSedeInteres',0);
            	setearCombo('selectSedeInteres', '<?php echo $sedeInt?>');
            }else if(  '<?php echo $gradoNivel?>'.length == 0){
                	$('#selectSedeInteres').attr('disabled', true);
            }if('<?php echo $comboTipoDocumento?>'.length == 0){
            	$('#nroDocumentoPostulante').attr('disabled', true);
            }

            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });

            <?php if($disableEntrevista == null){?>
            setearCombo('select_diagnostico_entrevista', '<?php echo $diagFinal?>');
            <?php }?>
            
            
            disableEnableCombo("selectTipoDocumentoPostulante", true);
            disableEnableCombo("selectColegioProc", true);
            disableEnableCombo("selectSexoPostulante", true);
        </script>
	</body>
</html>