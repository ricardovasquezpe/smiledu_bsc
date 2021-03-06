<!DOCTYPE html>
<html lang="en">
    <head>  
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script> 
        <title>Detalle de Evento | <?php echo NAME_MODULO_ADMISION?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION?>css/contacto.css">
        
		<style type="text/css">

            .display-none{
            	display: none !important;
            }
            
            #tablaRutasSedes .fixed-table-body{
            	overflow-y: hidden
            }
            
            @media (min-width: 798px){
                #modalDetalleApoyoAdministrativo .modal-dialog{
	               width: 948px;
                } 
            }
            .pace .pace-progress{
	           background-color: transparent !important;
            }
            
            .mdl-chip{
	           height: 29px !important;
            }
            
            .mdl-chip img{
	           height: 25.5px !important;
               width:25.5px !important;
            }
            
            .mdl-chip__text{
	           margin-top: -8px;
            }
            
            #cont_table_recurso_humano .pull-left.pagination-detail{
	           
            }
        </style>
        
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
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><strong>Datos del evento</strong></h2>
                            </div>
                            <div class="mdl-card__menu">
                            </div>
                            <div class="mdl-card__supporting-text p-t-0 p-b-0 br-b">
                                <div class="row-fluid">
                                    <div class="col-sm-6 mdl-input-group">             
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-event i-mdi-event"></i>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="tituloEvento" name="tituloEvento" maxlength="100" value="<?php echo (isset($nombreEvento) ? $nombreEvento: null)?>"
                                                   onchange="changeInputSave('desc_evento', 'tituloEvento', '<?php echo $noEnc?>')" attr-old-value="<?php echo (isset($nombreEvento) ? $nombreEvento: null)?>" <?php echo $disabled?>>        
                                            <label class="mdl-textfield__label" for="tituloEvento">T&iacute;tulo del evento</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFechaEvento" <?php echo $disabled?>>
    				                            <i class="mdi mdi-date_range"></i>
    			                            </button>
    		                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="fechaEvento" name="fechaEvento" maxlength="10" value="<?php echo (isset($fechaEvento) ? $fechaEvento: null)?>"
                                                   onchange="changeInputSave('fecha_realizar', 'fechaEvento', '<?php echo $noEnc?>')" attr-old-value="<?php echo (isset($fechaEvento) ? $fechaEvento: null)?>" <?php echo $disabled?>>        
                                            <label class="mdl-textfield__label" for="fechaEvento">Fecha del evento</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraInicio" <?php echo $disabled?>>
    				                            <i class="mdi mdi-access_time"></i>
    			                            </button>
    		                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="horaInicio" name="horaInicio" maxlength="8" value="<?php echo (isset($horaInicio) ? $horaInicio: null)?>"
                                                   attr-old-value="<?php echo (isset($horaInicio) ? $horaInicio: null)?>" <?php echo $disabled?>>        
                                            <label class="mdl-textfield__label" for="horaInicio">Hora inicio</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group">  
                                        <div class="mdl-icon mdl-icon__button">
    				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraFin" <?php echo $disabled?>>
    				                            <i class="mdi mdi-timelapse"></i>
    			                            </button>
    		                            </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="horaFin" name="horaFin" maxlength="8" value="<?php echo (isset($horaFin) ? $horaFin: null)?>"
                                                   attr-old-value="<?php echo (isset($horaFin) ? $horaFin: null)?>" <?php echo $disabled?>>        
                                            <label class="mdl-textfield__label" for="horaFin">Hora fin</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group">
                                        <div class="mdl-icon">
                                            <i class="mdi mdi-supervisor_account" style="left:11px"></i>
                                        </div>
                                        <div class="mdl-select">
                                            <select id="cmbEncargado" name="cmbEncargado" class="form-control selectButton cmb" data-live-search="true" data-noneSelectedText="Seleccione un encargado/apoyo general"
                                                    onchange="abrirModalConfirmAsignarEncargado()" <?php echo $disabled?>>
            					                   <option value="">Seleccione un encargado/apoyo general</option>
            					                   <?php echo $comboSubDirectores?>
            					            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mdl-input-group" style="bottom:17.8px !important">
                                        <div class="mdl-icon mdl-icon__button">
                                            <button class="mdl-button mdl-js-button mdl-button--icon"  style="top: 18px;left: 2px;" <?php echo $disabled?>>
                                                <i class="mdi mdi-remove_red_eye"></i>
    			                            </button>
                                        </div>
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="vertical-align:middle;">
                                            <textarea class="mdl-textfield__input" id="observacion" name="observacion" maxlength="100" onchange="changeInputSave('observacion', 'observacion', '<?php echo $noEnc?>')"
                                                      attr-old-value="<?php echo (isset($observEvento) ? $observEvento: null)?>" <?php echo $disabled?>><?php echo (isset($observEvento) ? $observEvento: null)?></textarea>   
                                            <label class="mdl-textfield__label" for="observacion">Observaci&oacute;n</label>
                                            <span class="mdl-textfield__limit" for="observacion" data-limit="100"></span>                      
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                        
                        <?php if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_TOUR){?>
                            <div class="mdl-card">
                                <?php if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_TOUR || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_CHARLA){?>                                          
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text"><strong>Ruta</strong></h2>
                                    </div>
                                    <div id="tablaRutasSedes">
                                        <?php echo (isset($tablaRutas) ? $tablaRutas: null)?>
                                    </div>
                                <?php }else{?>
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text"><strong>Horarios</strong></h2>
                                        <?php echo (isset($btnAgregarHorario) ? $btnAgregarHorario: null)?>  
                                    </div>
                                    <div id="tablaHorarioEvaluacion">
                                        <?php echo (isset($tablaHoras) ? $tablaHoras: null)?>
                                    </div>
                                <?php }?>
                            </div>  
                        <?php }?>
                    </div> 
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-2">
                    <div class="mdl-filter">
        				<div class="p-r-15 p-l-15">
        					<div class="mdl-content-cards mdl-content__overflow">
        					    <ul class="nav nav-pills">
        					       <?php echo (isset($filterEstadosContacto) ? $filterEstadosContacto: null)?>
    					        </ul>
                			</div>
            			</div>
        			</div>		
                    <div class="mdl-content-cards mdl-content__overflow">                                 
                        <?php echo (isset($tabContEstadosContacto) ? $tabContEstadosContacto: null)?>
                        <div class="mdl-spinner__position" id="loading_cards" style="display: none;">
                            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                                <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                            </button>
                        </div>
                    </div>
                </section>
                      
                <?php if(_getSesion('tipo_evento_detalle') == TIPO_EVENTO_EVALUACION || _getSesion('tipo_evento_detalle') == TIPO_EVENTO_TOUR){?>          
                    <section class="mdl-layout__tab-panel" id="tab-3">
                        <div class="mdl-content-cards">
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text p-0 br-b">Apoyo Administrativo</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <div class="table-responsive" id="cont_table_recurso_humano_sede">
                                        <?php echo (isset($tablaRecursosHumanos) ? $tablaRecursosHumanos : null)?>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </section> 
                <?php }else{?>
                    <section class="mdl-layout__tab-panel" id="tab-3">
                        <div class="mdl-content-cards">
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text p-0 br-b">Apoyo Administrativo Sede</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <div class="table-responsive" id="cont_table_recurso_humano_sede">
                                        <?php echo (isset($tablaRecursosHumanosSede) ? $tablaRecursosHumanosSede : null)?>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </section>
                <?php }?>
                
                <section class="mdl-layout__tab-panel" id="tab-4">
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Recursos Materiales</h2>
                            </div>
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div class="table-responsive" id="cont_table_recurso_material">
                                    <?php echo (isset($tablaRecursosMateriales) ? $tablaRecursosMateriales : null)?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>       

        <?php if(isset($btnFab)){?>
            <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
                <?php if(isset($btnAsignarRecursoMaterial)){?>
                    <li class="mfb-component__wrap" id="li_menu_1" style="display: none;">
                         <button class="mfb-component__button--main" >
                             <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                         </button>
                         
                         <?php echo (isset($btnAsignarRecursoMaterial) ? $btnAsignarRecursoMaterial: null)?> 
                        <ul class="mfb-component__list">
                            <li>
                                <?php echo (isset($btnCrearRecurso) ? $btnCrearRecurso: null)?>
                            </li>                
                        </ul>    
                    </li>
                <?php }?>
                <li class="mfb-component__wrap" id="li_menu_2" style="display: none;">
                     <?php echo (isset($fabAsignarApoyoAdm) ? $fabAsignarApoyoAdm: null)?>  
                </li>
            </ul>
        <?php }?>
        
        <div class="modal fade" id="modalCrearRecurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Crear Recurso Material</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreRecursoCrear" name="nombreRecursoCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreRecursoCrear">Nombre Recurso</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " onclick="crearRecurso()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmDeleteRecursoMaterial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text"> &#191;Deseas eliminar el recurso material seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    
				           <small>Recuerda: Al eliminar el recurso material seleccionado, se enviar&aacute; un correo al encargado.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCDRM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="borrarRecursoMaterial()">aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarRecursoMaterial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Recurso Material</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
                                        <select class="form-control selectButton" id="selectRecursoMaterialAsignar" name="selectRecursoMaterialAsignar" data-live-search="true">
                			                <option value="">Seleccionar Recurso Material</option>
                			                <?php echo (isset($comboRecursoMaterial) ? $comboRecursoMaterial : null)?>
                			            </select>
            			            </div>
        			           </div>
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="cantidadAsignarRecursoMaterial" name="cantidadAsignarRecursoMaterial" maxlength="3">        
                                        <label class="mdl-textfield__label" for="cantidadAsignarRecursoMaterial">Cantidad</label>                            
                                    </div>
				               </div>        			           
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        			                <div class="mdl-select">
                                        <select class="form-control selectButton" id="selectEncargadoRecursoMaterialAsignar" name="selectEncargadoRecursoMaterialAsignar" data-live-search="true">
                			                <option value="">Seleccionar Encargado</option>
                			                <?php echo (isset($encargadosRecursos) ? $encargadosRecursos : null)?>
                			            </select>
            			            </div>
        			           </div>
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="ObservacionAsignarRecursoMaterial" name="ObservacionAsignarRecursoMaterial" maxlength="100">        
                                        <label class="mdl-textfield__label" for="ObservacionAsignarRecursoMaterial">Observaci&oacute;n</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMARM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised asign" onclick="AsignarRecursoMaterial()">Asignar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalObservacionRecursoMaterial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Observaci&oacute;n Recurso Material</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only" >
    					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacionPedidoRecursoMaterial" name="observacionPedidoRecursoMaterial"  maxlength="100" disabled readonly></textarea>   
                                        <label class="mdl-textfield__label" for="observacionPedidoRecursoMaterial">Observaci&oacute;n Pedido</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" disabled>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacionRespuestaRecursoMaterial" name="observacionRespuestaRecursoMaterial"  maxlength="100" disabled readonly></textarea>   
                                        <label class="mdl-textfield__label" for="observacionRespuestaRecursoMaterial">Observaci&oacute;n Respuesta</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" disabled>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacionCumplimientoRecursoMaterial" name="observacionCumplimientoRecursoMaterial"  maxlength="100" disabled readonly></textarea>   
                                        <label class="mdl-textfield__label" for="observacionCumplimientoRecursoMaterial">Observaci&oacute;n Conformidad</label>                            
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
        
        <div class="modal fade" id="modalChangeCantidadRecursoMaterial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cantidad Recurso Material</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="cambiarCantidadRecursoMaterial" name="cambiarCantidadRecursoMaterial" maxlength="3">        
                                        <label class="mdl-textfield__label" for="cambiarCantidadRecursoMaterial">Cantidad</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCCRC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarCantidadRecursoMaterial()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
            
        <div class="modal fade" id="modalChangeConformidadRecursoMaterial" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Conformidad Recurso Material</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only text-center">
					               <p>&#191;Conforme?</p>
					               <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="conforme_recurso_material">
                                      <input type="checkbox" id="conforme_recurso_material" onchange="" class="mdl-switch__input">
                                      <span class="mdl-switch__label"></span>
                                   </label>
					           </div>
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="observacionConformidadRecursoMaterial" name="observacionConformidadRecursoMaterial" maxlength="100">        
                                        <label class="mdl-textfield__label" for="observacionConformidadRecursoMaterial">Observaci&oacute;n</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="btnCCRM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarConformidadRecursoMaterial()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
            
        <div class="modal fade" id="modalConfirmDeleteApoyoAdministrativo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas eliminar el apoyo administrativo seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0 text-left">
					           <small><strong>Recuerda: Al eliminar el apoyo administrativo, se enviar&aacute; un correo a todas las personas asignadas.</strong></small>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCDAA" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete mdl-save__load" onclick="borrarRecursoApoyoAdministrativo()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
            
        <div class="modal fade" id="modalAsignarRecursoApoyoAdministrativo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content" style="width:360px">                
                    <div class="mdl-card" style="width:360px">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar apoyo administrativo</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-select">
                                       <select class="form-control selectButton" id="selectRecursoApoyoAdministrativoAsignar" name="selectRecursoApoyoAdministrativoAsignar" data-live-search="true">
                			                <option value="">Seleccionar apoyo administrativo</option>
                			                <?php echo (isset($comboApoyoAdministrativo) ? $comboApoyoAdministrativo : null)?>
                			           </select>
            			           </div>
        			           </div>
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="cantidadAsignarApoyoAdministrativo" name="cantidadAsignarApoyoAdministrativo" maxlength="3">        
                                        <label class="mdl-textfield__label" for="cantidadAsignarApoyoAdministrativo">Cantidad</label>                            
                                    </div>
				               </div>        			           
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="min-height: 20px">
            			           <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="chBox_tomarAsistencia">
                                       <input type="checkbox" id="chBox_tomarAsistencia" class="mdl-checkbox__input">
                                       <span class="mdl-checkbox__label">&iquest;Tomar&aacute; asistencia de invitados?</span>
                                   </label>
                               </div>
        			           <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn">
					               <div class="mdl-select">
                                       <select class="form-control selectButton" id="selectSedeApoyoAdministrativoAsignar" name="selectSedeApoyoAdministrativoAsignar" data-live-search="true">
                			                <option value="">&iquest;Qui&eacute;n asignar&aacute; el apoyo?</option>
                			                <?php echo (isset($comboSedes) ? $comboSedes : null)?>
                			           </select>
                			       </div>  

            			               <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" style="top: 13px;" data-toggle="tooltip" data-original-title="Responsable de asignar el apoyo administrativo" data-placement="left">
								            <i class="mdi mdi-info"></i>
								       </button>

        			           </div>

        			           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="ObservacionAsignarApoyoAdministrativo" name="ObservacionAsignarApoyoAdministrativo" maxlength="100">        
                                        <label class="mdl-textfield__label" for="ObservacionAsignarApoyoAdministrativo">Observaci&oacute;n</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMARAA" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised asign" onclick="asignarApoyoAdministrativo()">Asignar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDetalleApoyoAdministrativo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Detalle Asignados</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0 p-t-0">    				
					       <div class="row p-0 m-0 text-center">				           
					           <div id="cont_asignado_apoyo_adm"></div>
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
        
        <div class="modal fade" id="modalConfirmInvitarContacto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Invitar a contacto</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
				            <div class="row">
    					       <div id="cont_tb_familiares_invitar"></div>
    					       <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="cont_input_razon_invitar">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="razonInasistenciaContactoInvitar" name="razonInasistenciaContactoInvitar">        
                                        <label class="mdl-textfield__label" for="razonInasistenciaContactoInvitar">Raz&oacute;n</label>                            
                                    </div>
    			               </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCIC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised invit" onclick="invitarContacto()">Invitar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalBuscarPersonasApoyoAdministrativo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Seleccionar apoyo administrativo</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row">			
    					       <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="buscarAsignarApoyoAdministrativo" name="buscarAsignarApoyoAdministrativo" onkeyup="buscarApoyoAdministrativo(event);activeDesactiveSearch();">        
                                        <label class="mdl-textfield__label" for="buscarAsignarApoyoAdministrativo">Nombre</label>                            
                                    </div>
                                    <div class="mdl-btn">
            			                <button class="mdl-button mdl-js-button mdl-button--icon"  disabled id="btnBuscar" onclick = "buscarApoyoAdministrativo()">
								            <i class="mdi mdi-search"></i>
								        </button>
            			            </div>                                     
    			               </div>
    			               <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="min-height: 20px">
    			                 <strong>Al asignar a una persona como apoyo administrativo, le llegar&aacute; un correo de confirmaci&oacute;n.</strong>
    			               </div>
			                   <div id="cont_busqueda_apoyo_administrativo"></div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalCrearHorarioEvaluacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Horario</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreHorarioCrear" name="nombreHorarioCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreHorarioCrear">Nombre Horario</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 mdl-input-group mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconHoraInicioHorario" <?php echo $disabled?>>
				                            <i class="mdi mdi-access_time"></i>
			                            </button>
		                            </div>				                    
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="horaHorarioCrear" name="horaHorarioCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="horaHorarioCrear">Hora</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCHE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised create" onclick="crearHorarioEvaluacion()">Crear</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmDeleteHorarioEvaluacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Deseas eliminar el horario de la evaluaci&oacute;n?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <small>Recuerda: Al eliminar el horario de la evaluaci&oacute;n, ya no se podr&aacute; usar este horario para futuros planes.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCDE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" onclick="deleteHorarioEvaluacion()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalLlamadas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text">Seguimiento</h2>
    					</div>
    					<div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    					       <div class="mdl-tabs__tab-bar">
    					           <a href="#llamadas" class="mdl-tabs__tab tabLlamadas is-active" id="tabLlamadas">Agregar seguimiento</a>
                                   <a href="#historial" class="mdl-tabs__tab tabLlamadas" >Historial</a>
                               </div>
    					       <div class="mdl-tabs__panel panelLlamadas is-active" id="llamadas">
        					       <div class="row-fluid">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only text-center">
    						               <h2 name="numTelefono" id="numTelefono">Tel&eacute;fono</h2>
            					       </div>
        					           <!-- div class="col-sm-12 text-center p-15" >
    						               <h2 name="correoSeguimiento" id="correoSeguimiento">Correo</h2>
            					       </div-->
            					       <div class="col-sm-4 mdl-input-group mdl-input-group__only p-t-25">
            					           <div class="mdl-select">
                					            <select id="selectTipoLlamada" name="selectTipoLlamada" class="form-control selectButton"
                                                   data-live-search="true" data-noneSelectedText="Selec. Tipo de seguimiento">
            						               <option value="">Selec. Tipo de seguimiento</option>
            						               <?php echo $comboSeguimiento?>
            						            </select>
        						            </div>
            					       </div>  
            					       <div class="col-sm-4 mdl-input-group mdl-input-group__only p-t-25">
            					           <div class="mdl-select">
                					           <select id="selectEvento" name="selectEvento" class="form-control selectButton"
                                                    data-live-search="true" data-noneSelectedText="Selec. Evento">
            						                <option value="">Selec. Evento</option>
            						                <?php echo $comboEventos?>
            						           </select>
        						           </div>
            					       </div>
            					       <div class="col-sm-4 mdl-input-group mdl-input-group__only">
            					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="observacion" name="observacion" maxlength="60">        
                                               <label class="mdl-textfield__label">Observaciones</label>                            
                                           </div>
            					       </div>
                        			   <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="btnMLL" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="agregarLlamada(1)">Guardar</button>
                                       </div>   
                                   </div>
    					       </div>
    					       <div class="mdl-tabs__panel panelLlamadas" id="historial">			
        					       <div class="row-fluid p-rl-0 m-b-15">
    					               <div id="cont_table_llamadas"></div>
    					           </div>
                					<div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                                    </div>
    					       </div>
					       </div>
    					</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalConfirmSelectEncargadoEvento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Est&aacute;s seguro de asignar a la persona seleccionada como persona encargada?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">    				
					       <div class="row p-0 m-0 text-left">
					           <small><strong>Recuerda: Al asignar a una persona encargada, se le enviar&aacute; un correo de recordatorio</strong></small>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="resetComboEncargado()">Cerrar</button>
                            <button id="btnMCSEE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarEncargadoEvento()">Asignar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text pregunta"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    					   <small></small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised delete" id="elim" onclick = "" >Eliminar</button>
                        </div>
                    </div>
                </div>     
            </div>
        </div>    
        
        <div class="modal fade" id="modalPostulantesHorario" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="title_postulantes_horario"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div id="cont_tab_postulantes_horario"></div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmAsignApoyoAdm" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar una observaci&oacute;n</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="observacionAsignarApoyoAdministrativoAux" name="observacionAsignarApoyoAdministrativoAux" maxlength="100">        
                                        <label class="mdl-textfield__label" for="observacionAsignarApoyoAdministrativoAux">Observaci&oacute;</label>                            
                                    </div>
				               </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCR" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " onclick="asignarAuxiliarApoyoAdm()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>     
        
        <div class="modal fade" id="modalObservacionApoyoAdministrativoAux" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Observaci&oacute;n Apoyo Administrativo</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					           <div class="col-sm-12 mdl-input-group mdl-input-group__only" >
    					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacionPedidoApoyoAdministrativoAux" name="observacionPedidoApoyoAdministrativoAux"  maxlength="100" disabled readonly></textarea>   
                                        <label class="mdl-textfield__label" for="observacionPedidoApoyoAdministrativoAux">Observaci&oacute;n Pedido</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only" disabled>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="observacionRespuestaApoyoAdministrativoAux" name="observacionRespuestaApoyoAdministrativoAux"  maxlength="100" disabled readonly></textarea>   
                                        <label class="mdl-textfield__label" for="observacionRespuestaApoyoAdministrativoAux">Observaci&oacute;n Respuesta</label>                            
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
        
        <div class="modal fade" id="modalAsignarApoyoAdministrativoSede" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Apoyo Administrativo - Sede</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="buscarAsignarApoyoAdministrativoSede" name="buscarAsignarApoyoAdministrativoSede" onchange="buscarApoyoAdministrativoSede()">        
                                    <label class="mdl-textfield__label" for="buscarAsignarApoyoAdministrativoSede">Nombre</label>                            
                                </div>
                                <div class="mdl-btn">
        			                <button class="mdl-button mdl-js-button mdl-button--icon" id="btnBuscar" onclick="buscarApoyoAdministrativoSede()">
							            <i class="mdi mdi-search"></i>
							        </button>
        			            </div>                                     
			               </div>
			               <!--div class="col-sm-12 mdl-input-group mdl-input-group__only" style="min-height: 20px">
			                 <strong>mensaje</strong>
			               </div-->
		                   <div id="cont_busqueda_apoyo_administrativo_sede"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade" id="modalConfirmAsignarApoyoAdmSede" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Confirmaci&oacute;n Apoyo Administrativo</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row">
					       
					           �Seguro de agregarlo a este evento?    
					       
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised " onclick="guardarApoyoAdmSede()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmDeleteApoyoAdmSede" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text"> &#191;Deseas eliminar a la persona seleccionada?</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    
				           <small>Recuerda: Al eliminar a la persona, no tendr&aacute; acceso al evento.</small>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMCDRM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="eliminarApoyoAdmSede()">aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <!-- <div class="modal fade" id="modalEditHorarioInvitacion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cambiar Horario</h2>
    					</div>
					    <div class="mdl-card__supporting-text">   
					       <div id="cont_tb_horarios_invitados"></div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> -->
        
        <!-- <div class="modal fade" id="modalEnviarMensajePariente" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Enviar Mensaje</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-12 p-0">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="asuntoCorreoEnviar" name="asuntoCorreoEnviar" maxlength="100">        
                                        <label class="mdl-textfield__label" for="asuntoCorreoEnviar">Asunto</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-12 p-0">
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" id="mensajeCorreoEnviar" name="mensajeCorreoEnviar" maxlength="200"></textarea>        
                                        <label class="mdl-textfield__label" for="mensajeCorreoEnviar">Mensaje</label>                            
                                    </div>
				               </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions p-t-0">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMEMP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised send" onclick="enviarCorreoPariente()">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> -->
        	
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>     
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jshammer__detalle_evento.js"></script>        
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsdetalleevento.js"></script>
        
        <script type="text/javascript"> 
     	    <?php if($disabled == 'disabled'){?>
   	                  $('#cmbEncargado').attr('disabled', true);
          	          $('#cmbEncargado').selectpicker('refresh');    
            	      disableEnableCombo("cmbEncargado", false);  	 
            <?php }?>

      	    initButtonCalendarDaysMinToday('fechaEvento');
            returnPage();
            var enc   = '<?php echo $enc?>';
            var noEnc = '<?php echo $noEnc?>';
            var cmbEncargado = '<?php echo $personaEncargada?>';
            setearCombo('cmbEncargado', cmbEncargado);
            <?php if($disabled == 'disabled'){?>
                      $('#cmbEncargado').attr('disabled', true);
            	      $('#cmbEncargado').selectpicker('refresh');    	 
            <?php }?>
            function asignarEncargadoEvento() {
            	var encargado = $("#cmbEncargado").val();
            	if(encargado.length != 0) {
            		changeInputSave('id_persona_encargada', 'cmbEncargado', '<?php echo $enc?>');
            		modal("modalConfirmSelectEncargadoEvento");
            	}
            }
            $(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip(); 
            });
        	initButtonCalendarHours('horaInicio');
        	initButtonCalendarHours('horaFin');
        	initButtonCalendarHours('horaHorarioCrear');
        	initButtonLoad('btnMARAA', 'btnMCDAA', 'btnMARM', 'btnCCRM', 'btnMCDRM', 'btnMCR', 'btnMCCRC', 'btnMCIC', 'btnMCHE', 'btnMCDE', 'btnMLL', 'btnMCSEE');
        </script>
    </body>
</html>