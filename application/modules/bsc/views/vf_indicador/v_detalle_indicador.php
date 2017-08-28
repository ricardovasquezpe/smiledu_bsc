<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Detalle del Indicador | <?php echo NAME_MODULO_BSC?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_BSC?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_BSC?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
    	<!-- link type='text/css' rel='stylesheet' href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/-->
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>treegrid-5a0511e/css/jquery.treegrid.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC?>css/shideEffect.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_BSC;?>css/detalle_indicador.css">
        
	</head>

	<body onload="screenLoader(timeInit);">
	   <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
	       <?php echo $menu ?>
           <main class='mdl-layout__content'>  
                <section>
                    <div class="mdl-content-cards">
                        <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR) { ?>
                            <ol class="breadcrumb">
                        		<li><a href="<?php echo base_url()?>bsc/c_linea_estrategica" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_lineaEstrat');?>"><?php echo _getSesion('nombre_lineaEstrat');?></a></li>
                        		<li><a href="<?php echo base_url()?>bsc/cf_indicador/c_categoria" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_objetivo');?>"><?php echo _getSesion('nombre_objetivo');?></a></li>
                        		<li><a href="<?php echo base_url()?>bsc/cf_indicador/c_indicador_rapido" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_categoria');?>"><?php echo _getSesion('nombre_categoria');?></a></li>
                        		<li class="active"><a data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo _getSesion('nombre_indicador');?>"><?php echo _getSesion('nombre_indicador');?></a></li>
                        	</ol>    
                        <?php } else { ?>
                    	    <ol class="breadcrumb">
            				    <li><a href="<?php echo base_url()?>cf_indicador/c_indicador">Indicadores</a></li>
                				<li class="active"><?php echo _getSesion('nombre_indicador');?></li>
                			</ol>
                    	<?php }?>
                    	<div id="content">                                    
                            <div class="row-fluid">                            
                			     <div class="col-sm-9 p-0" >
                			             <div id="responsiveSubniveles">
                			                 <div class="mdl-card " id="infoBasica">
                                                <div class="mdl-card__title">
                                                    <h2 class="mdl-card__title-text">Detalles</h2>
                                                </div>
                                                <div class="mdl-card__supporting-text p-0">
                                                    <div id="contArbolIndiDeta" class="form floating-label table_distance">
                                                        <?php echo $tablaHijos?>
                                                    </div>
                                                </div>
                                                <div class="mdl-card__menu">
                                                   <?php if(strlen($fechaModi)!=0){?>
                                                    <button class="mdl-button mdl-js-button " id="fechaModi">
                                                        <?php echo $fechaModi?>
                                                    </button>
                                                    <?php }?>
                                                    <?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_MEDICION || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR){?>
                                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalActualizarActual();">
                                                            <i class="mdi mdi-refresh"></i>
                                                        </button>
                                                     <?php } ?>
                                                </div>
                        					</div>
                			             </div>
                			             
                			             <div class="mdl-card " id="responsiveSubniveles">
                                            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                                <div class="mdl-tabs__tab-bar">
                                                  <a href="#tendencia" class="mdl-tabs__tab is-active">Tendencia</a>
                                                  <a href="#medicion" class="mdl-tabs__tab">F.Medici&oacute;n</a>
                                                  <a href="#comparativa" class="mdl-tabs__tab">Comparativa</a>
                                                  <a href="#cierre" class="mdl-tabs__tab">Cierre</a>
                                                </div>
                                                <div class="mdl-tabs__panel p-t-25 is-active" id="tendencia">
                                                    <div id="container"></div>
                                                </div>
                                                <div class="mdl-tabs__panel" id="medicion">
                                                    <div class="row-fluid">
                                                        <form id=formEditMeta2 method="post" class="form-vertical table_distance">
                                                              <input type="hidden" id="idDetalleIndicador">
                                                              <div class = "col-sm-12 p-0 p-b-15">
                                                                  <div id="contTbFrecuencias" class="form floating-label table_distance">
                                                                      <?php echo utf8_decode($tabla)?>
                                                                  </div>
                                                              </div>
                                                        </form>
                                                        <div class="mdl-card__actions">
                                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" data-toggle="modal" onclick="limpiarModalAddFrecuencia();" href="#modalAddFrecuencia" >AGREGAR</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mdl-tabs__panel" id="comparativa">
                                                    <div class="row-fluid">
                                                        <div class = "col-sm-12 p-0">
                                                             <div id="contTbComparativas" >
                                                                 <?php echo utf8_decode($tb_comparativas)?>
                                                             </div>
                                                         </div>
                                                         <div class="mdl-card__actions">
                                                            <button id="botonCCI" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-0" onclick="capturarComparativasXIndicador();">GUARDAR</button>
                                                         </div>
                                                     </div>
                                                </div>
                                                <div class="mdl-tabs__panel" id="cierre">
                                                    <div class="row-fluid">
                                                        <div class="col-sm-12"> 
                                                            <div class="img-search">
                                                                <img src="<?php echo RUTA_IMG?>smiledu_faces/empty_state_cierre.png">
                                                                <div class="m-b-20">    
                                                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-toggle="modal" href="#modalCerrarIndicador">
                                                                        <i style="vertical-align:middle; margin-right: 5px;" class="mdi mdi-lock"></i>Cierre de Indicador
                                                                    </button> 
                                                                </div>
                                                                <p>Se verificará que las mediciones se hayan realizado completamente,</p>
                                                                <p>una vez realizada esta acci&oacute;n se guardar&aacute;n los datos como</p>
                                                                <p>hist&oacute;ricos y el indicador ser&aacute; actualizado al presente año.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                			             </div>
                			         </div>                                
                				</div>
                				
                                <div class="col-sm-3 p-0">                                                              
                                    <div class="mdl-card mdl-indicator" id="cardGauge" >
                                        <div class="mdl-card__title" >
                                            <div id="contGauge"></div>
                                        </div>
                                        <div class="mdl-card__supporting-text">
                                            <h2 id="condInd" class="mdl-card__title-text"></h2>
                                            <h4 class="desc_linea" class="m-b-10"><?php echo _getSesion('nombre_indicador_large');?></h4>
                                            <div id="divRespo"><?php echo $responsables;?></div>
                                        </div>
                                        <div class="mdl-card__menu" id="menuGauge"></div>
                                        <div id="barra" class="mdl-bar_state barraEstado"></div>
                                    </div>
                                    <div class="mdl-card" id="infoHistorial">
            				            <div class="mdl-card__title">
                                            <h2 class="mdl-card__title-text">Historial</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text">
                                            <div>
                                                <div class="timeline-centered" id="historiaCardDiv">
                                                    <?php echo $historiaInd?>
                                                </div>                                            
                                            </div>
                                        </div>
                					</div>
                				</div>     					           			
                            </div>
                        </div>                   
                </section>  
           </main>
       </div>
       
       <!-- MODALES -->          
       <div class="modal fade backModal" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Valores</h2>
    					</div>    
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="contInputValor">
                                        <input class="mdl-textfield__input" type="text" id="valorAmarillo" name="valorAmarillo">        
                                        <label class="mdl-textfield__label" for="valorAmarillo">Ingrese valor</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-6 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="valorMeta" name="valorMeta" disabled readonly="readonly">        
                                        <label class="mdl-textfield__label" for="valorMeta">Valor Meta</label>                            
                                    </div>
                                </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEVZ" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarValorZonRiesgoIndicador()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>              
        
        <div class="modal fade backModal" id="modalCerrarIndicador" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cerrar Indicador</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div id="detalleLastModi">
                                ¿Está seguro que desea cerrar el indicador?
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="save-buttom" type="submit" onclick="cerrarIndicador()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade backModal" id="modalAddFrecuencia" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar Nueva Medici&oacute;n</h2>
    					</div>  
					    <div class="mdl-card__supporting-text p-r-0 p-l-0">				
					       <div class="row-fluid" id="formNuevaMedi">
                                <input type="hidden" id="idDetalleIndicador">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="numeroMedicion" value="<?php echo isset($lastMedicion) ? $lastMedicion : null;?>" id="numeroMedicion" disabled>        
                                        <label class="mdl-textfield__label" for="numeroMedicion">Nro medici&oacute;n</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="descFrecuencia" name="descFrecuencia">        
                                        <label class="mdl-textfield__label" for="descFrecuencia">Descripcion de medici&oacute;n</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group">
				                    <div class="mdl-icon mdl-icon__button">
				                        <button class="mdl-button mdl-js-button mdl-button--icon" id="iconFecNaciColaboradorCrear">
				                            <i class="mdi mdi-event_note"></i>
			                            </button>
		                            </div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fechaMedicion" name="fechaMedicion" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fechaMedicion">Fecha de medici&oacute;n</label>    
                                        <span class="mdl-textfield__error"></span>                        
                                    </div>
				               </div>                  
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonNM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="grabarNuevaMedicion();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
        <div class="modal fade backModal" id="modalAsignaPersonas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Responsable</h2>
    					</div>  
					    <div class="mdl-card__supporting-text p-0">				
					       <div class="row p-0 m-0">
                                <input type="hidden" id="idIndDeta">
                                <div class="col-sm-12 m-b-15">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="nombrePersona" id="nombrePersona" onchange="getPersonasAddIndicador();">        
                                        <label class="mdl-textfield__label" for="nombrePersona">Nombres</label>                            
                                    </div>
                                </div>
                                <div class="col-sm-12 p-0 m-b-15">
                                    <div id="contTbPersonasModal" class="form floating-label table_distance">
                					</div>
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="capturarIndicadoresPersona()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div> 
        
       <div class="modal fade backModal" id="modalResponsablesIndicador" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Responsables de medici&oacute;n</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row p-0 m-0">
					           <div class="mdl-list" id="cont_tb_resp_indi">
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
            
       <div class="modal fade backModal" id="modalViewResponsable" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreReponsableModal"></h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 text-center">
					               <img id="img_repsonsable">
					           </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnllamada"><i class="mdi mdi-phone"></i></button>
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnperfil"><i class="mdi mdi-account_circle"></i></button>
                            <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="btnemail"><i class="mdi mdi-email"></i></button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>  
        
        <div class="modal fade backModal" id="modalConsultarDetalle" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title p-b-0">
    						<h2 class="mdl-card__title-text"></h2>
    					</div>
    					    <div class="mdl-card__supporting-text p-0">				
    					       <div class="row p-0 m-0">
    					           <div class="col-sm-12 m-b-15 text-center">
    					                <div id="contComboIndicadoresModal"></div>
    					           </div>
    					           <div class="col-sm-12 p-0 m-b-15">
                                        <div id="contTbIndicadoresModal" class="form floating-label table_distance"></div>
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
        
        <div class="modal fade backModal" id="modalActualizarActual" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Actualizar Informaci&oacute;n</h2>
    					</div>  
    					<?php if(_getSesion(BSC_ROL_SESS) == ID_ROL_MEDICION || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR){?>
    					    <div class="mdl-card__supporting-text">		
    			                <div id="detalleLastModi"><?php echo isset($cardDetalle) ? $cardDetalle : null;?></div>
        					</div>
        					<div class="mdl-card__actions m-t-20">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizarActual()">Actualizar</button>
                            </div>
                        <?php } else { ?>
                            <div class="mdl-card__supporting-text">		
    			                Ups! No puede hacer esta acci&oacute;n
        					</div>
        					<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalAgregarEstructura" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Estructura</h2>
    					</div>  
					    <div id=formEditMeta class="form_distance">
    					    <div class="mdl-card__supporting-text p-0">				
    					       <div class="row p-0 m-0">
                                    <div id="cont_tb_estructura"></div>
                                </div>
        					</div>
        					<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                <button id="botonGE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarEstructura()">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalEditMeta" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Meta</h2>
    					</div>  
				        <input type="hidden" id="idIndDeta">
					    <div class="mdl-card__supporting-text">				
					       <div class="row p-0 m-0" id="contInputValor">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" maxlength="5" name="meta" id="meta">        
                                    <label class="mdl-textfield__label" for="meta">Meta</label>                            
                                </div>
                            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editMeta()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modal_asist_puntu" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" style="margin-top: 5%">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Detalle</h2>
    					</div>  
					    <form id="formEditMeta">
					        <input type="hidden" id="idIndDeta">
    					    <div class="mdl-card__supporting-text p-0">				
    					       <div class="row p-0 m-0">
                                    <div id="contAsistPuntua" class="form floating-label table_distance"></div>
                                </div>
        					</div>
        					<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalShowResponsables" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Responsables</h2>
    					</div>  
					    <div class="mdl-card__supporting-text">				
					       <div class="row">
					           <div class="col-sm-12 text-center" id="tableResponsables">
                                    
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
        
	    <div class="offcanvas"></div>        
        
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script> 
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/highcharts-more.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS;?>highcharts/js/modules/exporting.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>  
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.cookie.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsutilsbsc.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsindicadorRapido.js"></script>
        <script src="<?php echo RUTA_PUBLIC_BSC;?>js/jsdetalleIndicador.js"></script>    
        <script>
            returnPage();
    	    initSearchTable();	
    	    function deplegarHistoria(){
				$('.small-padding').addClass('cardDisplay');
				$('.small-padding').css('display');
        	}

			var i = 0;
        	function cerrarPaneles(){
            	if(i == 1){
                	$("#responsiveSubniveles").removeClass("col-md-8");
                	$("#responsiveSubniveles").addClass("col-md-12");
            	}else{
					i++;
               	}
        	}

        	init();
        	$('.highcharts-data-labels g rect').css('display','none');
        	$('.highcharts-series-group circle').css('fill','#959595');
        	$(document).ready(function(){
        	    $('[data-toggle="tooltip"]').tooltip();
            }); 
        </script>
	</body>
</html>