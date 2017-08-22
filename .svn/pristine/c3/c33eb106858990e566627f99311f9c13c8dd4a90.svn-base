<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>      
        <title>Configuraci&oacute;n | <?php echo NAME_MODULO_PAGOS?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_PAGOS?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_PAGOS;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
		<link type='text/css' rel="stylesheet" href="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/css/jquery.treegrid.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">  
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_PAGOS?>css/submenu.css">
        <style type="text/css">
            #btnBuscar{
            	margin-top: 0
            }
            
            #switchCI + .mdl-switch__label{
            	left: 16px
            }
            
            #modalVistaPreviaCronograma .modal-dialog.modal-sm .modal-content{
        	    max-width: 565px;
            }
            
            @media (min-width: 798px) {
                #modalVistaPreviaCronograma .modal-sm {
                	width: 565px;
                }
            }
            
            #modalVistaPreviaCronograma table tbody ul{
            	padding: 0
            }
            
            @media (min-width: 992px){
                #verCronoCompromisosAlumno #combosSedeNivelGrado .btn-group.bootstrap-select.pickerButn.col-md-4{
                	width: 33.33333333% !important
                }
            }
        </style>
    </head>
    
	<body >	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >    		
    		<?php echo $menu ?>    		
    		<main class='mdl-layout__content is-visible' onscroll="onScrollEvent(this);" style="">
    		       <div id="spinner" style="display:none">
                        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active " ></div>
                    </div>
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                 
                    <div class="mdl-content-cards">
                        <div class="img-search empty_filter">
                            <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                            <p>Primero debemos filtrar para</p>
                            <p>visualizar las pensiones.</p>
                        </div>

                        <div class="row-fluid" id="cards_tab" style="display: none;">
                            <div class="col-sm-12" id="cabecera">
                                <ol class="breadcrumb" >
                                    <li class="active"><strong>Filtro:</strong></li>
                    				<li class=""></li>
                    			</ol>
                            </div>
                            <div class="col-sm-5">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">A&ntilde;o &nbsp; </h2>
                                        <h2 class="mdl-card__title-text" id="tittleBySedes"></h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b">
                                        <div id="tableSSedes"></div>
                                    </div>
                                    <div class="mdl-card__menu" id = "flechasNavegacion"></div>
                                </div>
                            </div>

                            <div class="col-sm-7">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Niveles</h2>
                                    </div>
                                    <div class="mdl-card__supporting-text p-0 br-b" >
                                        <div id="tableNivel" class="table-responsive">
                                            <div class="img-search">
                                                <img src="<?php echo RUTA_IMG?>smiledu_faces/select_empty_state.png">
                                                <p>Seleccione una sede para</p>
                                                <p>visualizar sus niveles.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu" id="iconCerrar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mdl-layout__tab-panel" id="tab-2" style="display: none;">
                    <div class="mdl-content-cards">
                        <div class="col-md-8 col-md-offset-2">
                        <div id="cronograma_pagos" class="mdl-card">
                            <div class="mdl-card__title ">
                                    <h2 class="mdl-card__title-text">Cronograma</h2>
                                </div>
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <?php echo $tableCronograma;?>
                                </div>
                                <div class="mdl-card__menu">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="modal" data-target="#modalInfoCrono">
                                        <i class="mdi mdi-info"></i>
                                    </button>
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" >
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="img-search" id="empty_card" style="display: none">
                           <img src="<?php echo base_url();?>public/general/img/smiledu_faces/not_data_found.png">
                           <p><strong>Ups!</strong></p>
                           <p>No encontraron registros.</p>
                        </div>

                        <div id="cardsCompromisosPorAlumno"></div>
                        <div id="calendarCronograma"></div>
                    </div>
                </section>

                <section class="mdl-layout__tab-panel" id="tab-3" style="display: none;">
                    <div class="mdl-content-cards">
                        <div class="row-fluid">
                            <div id="becasPromo" style="display: block">
                                <div class="col-sm-6"  id="divBecas">
                                    <div class="mdl-card">
                                        <div class="mdl-card__title ">
                                            <h2 class="mdl-card__title-text" id="becas&Promociones">Becas</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text p-0 br-b" id="tableBecasP">
                                            <?php echo $tableBecas;?>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-more_vert"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6" id="divPromociones">
                                    <div class="mdl-card">
                                        <div class="mdl-card__title ">
                                            <h2 class="mdl-card__title-text" id="becas&Promociones">Promociones</h2>
                                        </div>
                                        <div class="mdl-card__supporting-text p-0 br-b" id="tablePromociones">
                                            <?php echo $tablePromociones;?>
                                        </div>
                                        <div class="mdl-card__menu">
                                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                <i class="mdi mdi-more_vert"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel" id="tab-4" style="display: none;">
                    <div class="mdl-content-cards">
                        <div class="m-b-10" id="filtroCompromisos" style="display:none;">
                            <div class="row-fluid p-0 m-0">
                                <div class="col-xs-12 p-0" id="filtroExtras" style="display:none;">
			                       <ol class="breadcrumb">
			                            <li>Filtro:</li>
	                    				<li id="laelSede"></li>
	                    				<li id="laelNivel"></li>
	                    				<li id="laelGrado"></li>
	                    				<div style="float:right;">
    	                    				<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-global-aulas">
                                        		<input type="checkbox" id="checkbox-global-aulas" class="mdl-checkbox__input">
                                         		<span class="mdl-checkbox__label m-l-10"> Todos</span>
                                    		</label>
                                		</div>
	                    			</ol>
	                             </div>
                            </div>
                        </div>
                        
                        <div class="img-search" id="imgExtras">
                           <img src="<?php echo RUTA_IMG?>smiledu_faces/filter_fab.png">
                           <p>Primero debemos filtrar para </p>
                           <p>visualizar las aulas</p>
                        </div>

                        <div id="tableCompromisosGenerados" style="display: none;">
                            <div class="mdl-card">
                                 <div class="mdl-card__title ">
                                     <h2 class="mdl-card__title-text">Desasignar compromisos</h2>
                                 </div>
                                 <div class="mdl-card__supporting-text p-0 br-b"></div>
                                 <div class="mdl-assign" id="cabeConfirm">
                                    <div class="text">
                                        0 item seleccionado 
                                    </div>
                                    <div class="option">
                                        <button class="mdl-button mdl-js-button m-0" data-toggle="modal" data-target="#modalEliminarCompromisosExtras"><i class="mdi mdi-delete"></i> Eliminar</button>                                                
                                    </div>
                                </div>
                             </div>
                         </div>
                        
                        <div id="cardsCompromisos"></div>
                    </div>
                </section>
    		</main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation" id="pensiones_pago_fg">
                <button class="mfb-component__button--main" data-toggle="modal" data-target="#modalFiltroPensiones" data-mfb-label="Filtrar Pensiones">
                    <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                </button>
            </li>
        </ul>
        
        <div class="modal fade" id="modalCrearBeca" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nuevo Descuento</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">    	
					        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a href="#becas" class="mdl-tabs__tab is-active">Becas</a>
					               <a href="#promociones" class="mdl-tabs__tab">Promociones</a>
					           </div>
					           
					           <div class="mdl-tabs__panel p-15 is-active" id="becas">
					               <div class="row">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                       <input class="mdl-textfield__input" type="text" id="desc" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*">
		                                       <label class="mdl-textfield__label" for="desc">Descripci&oacute;n</label>
		                                       <span class="mdl-textfield__error">Ingrese solo letras.</span>
		                                    </div>
							           </div>
							           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                       <input class="mdl-textfield__input" type="text" id="porcentajeB" pattern="^[1-9]?[0-9]{1}$|^100$">
		                                       <label class="mdl-textfield__label" for="porcentajeB">Porcentaje de la Beca(%)</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros entre 0 y 100%</span>
		                                    </div>
							           </div>
						            </div>
			    					<div class="mdl-card__actions p-0 m-t-15">
    		                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
    		                            <button id="botonRB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised"  onclick="registrarBeca();">Guardar</button>
    		                        </div>
						           
					           </div>
					           <div class="mdl-tabs__panel p-15" id="promociones">
					               <div class="row">	
					                   <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                       <input class="mdl-textfield__input" type="text" id="descP" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*">
		                                       <label class="mdl-textfield__label" for="descP">Descripci&oacute;n</label>
		                                       <span class="mdl-textfield__error">Ingrese solo letras.</span>
		                                    </div>
							           </div>				       
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                       <input class="mdl-textfield__input" type="text" id="cantC" pattern="-?[0-9]*(\.[0-9]+)?">
		                                       <label class="mdl-textfield__label" for="cantC">Cant. Cuotas</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros enteros</span>
		                                    </div>
							           </div>
							           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
							               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
		                                       <input class="mdl-textfield__input" type="text" id="porcentajeP" pattern="^[1-9]?[0-9]{1}$|^100$">
		                                       <label class="mdl-textfield__label" for="porcentajeP">Porcentaje de Descuento(%)</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros entre 0 y 100%</span>
		                                    </div>
							           </div> 
        					       </div>
    					           <div class="mdl-card__actions p-0 m-t-15">
			                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
			                            <button id="botonRP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarPromocion();">Guardar</button>
			                        </div>
					           </div>
					        </div>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
       	       	      
       	 <div class="modal fade" id="modalEditarBeca" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Beca</h2>
    					</div>  				
                        <div class="mdl-card__supporting-text">
                            <div class="row">   				
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="descEdit" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*">
                                       <label class="mdl-textfield__label" for="descEdit">Descripci&oacute;n</label>
		                                       <span class="mdl-textfield__error">Ingrese solo letras.</span>
                                    </div>
    				           </div>
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="porcentajeBecaEdit" pattern="^[1-9]?[0-9]{1}$|^100$">
                                       <label class="mdl-textfield__label" for="porcentajeBecaEdit">Porcentaje de la Beca</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros entre 0 y 100%</span>
                                    </div>
    				           </div>
				            </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="botonAB" onclick="actualizarBeca();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>    
        
        <div class="modal fade" id="modalEditarPromocion" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Editar Promocion</h2>
                        </div>  				
                        <div class="mdl-card__supporting-text">
                            <div class="row">			
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                   <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                   		<input class="mdl-textfield__input" type="text" id="descEditP" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*">
                                    	<label class="mdl-textfield__label" for="descEditP">Descripci&oacute;n</label>
		                                       <span class="mdl-textfield__error">Ingrese solo letras.</span>
                                   </div>
                                </div>				       
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                	<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    	<input class="mdl-textfield__input" type="text" id="cantCEditP" pattern="-?[0-9]*(\.[0-9]+)?">
                                    	<label class="mdl-textfield__label" for="cantCEditP">Cant. Cuotas</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros enteros</span>
                                	</div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    	<input class="mdl-textfield__input" type="text" id="porcentajeEditP" pattern="^[1-9]?[0-9]{1}$|^100$">
                                    	<label class="mdl-textfield__label" for="porcentajeEditP">Porcentaje de Descuento(%)</label>
		                                       <span class="mdl-textfield__error">Ingreso solo n&uacute;meros entre 0 y 100%</span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="botonAP" onclick="actualizarPromocion();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>  
        
        <div class="modal fade" id="modalCerrarSede" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="sedeNombre"></h2>
    					</div>
    					<div class="mdl-card__supporting-text">
    						<small>Recuerda: Al aceptar ya no podr&aacute;s realizar ning&uacute;n cambio en la opci&oacute;n que selecciones.</small>
    						<div class="p-t-20" id="contRadioCerrar">
        						
    						</div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonCSP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="cerrarSedesPagos();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
                	      
        <div class="modal fade" id="modalEditarCuota" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" style="max-width: 420px;">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreSede"></h2>
    					</div>
    					<div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-placement="left" data-original-title="Los campos bloqueados no pueden ser editados">
                                <i class="mdi mdi-info"></i>
                            </button>
                        </div>
					    <div class="mdl-card__supporting-text p-0 br-b">  
					        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a id="couta_pensiones" class="mdl-tabs__tab is-active" redirect="pensiones" onclick="cuotaIngreso($(this));">Pensiones</a>
					               <a id="cuota_prom" class="mdl-tabs__tab" redirect="cuotaIngreso" onclick="cuotaIngreso($(this));">Promociones</a>
					               <a id="couta_ingreso" class="mdl-tabs__tab" onclick="cuotaIngreso($(this));">C. Ingreso</a>
					           </div>
					           <div class="mdl-tabs__panel is-active" id="pensiones">
					               <div class="row-fluid">
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only p-t-15">
 
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
   
                                               <input class="mdl-textfield__input" type="text" id="montoMatriculal" name="montoMatriculal" pattern="^\d*[.]?\d*$">
       					                       <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearSede">
                                                   <i class="mdi mdi-lock" id=bloquear></i>
                                               </div> 
                                               <label class="mdl-textfield__label" for="montoMatriculal">Monto Matr&iacute;cula (S/)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente el monto.</span>
                                            </div>
        					           </div>				
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoCuotas" name="montoCuotas" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoCuotas">Pensi&oacute;n (S/)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
        					           </div>				
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="descuentoSede" name="descuentoSede">
                                               <label class="mdl-textfield__label" for="descuentoSede">Descuento (S/.)</label>
                                            </div>
        					           </div>
        					           <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                            <a href="#promocionesSede" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="cuotaIngresoBTN" onclick="actualizarPensionesSedes()">Aceptar</a>
                                        </div>
        					       </div>
					           </div>
					           <div class="mdl-tabs__panel" id="promocionesSede">
					               <div class="row-fluid">
					                   <div class="col-sm-12 mdl-input-group mdl-input-group__only text-center p-t-15">
                                           <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switchProm">
                                               <input type="checkbox" id="switchProm" class="mdl-switch__input">
                                               <span class="mdl-switch__label">¿Activar?</span>
                                           </label>
        					           </div>
					                   <div class="col-sm-12 mdl-input-group mdl-input-group__only p-t-15">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoMatriculaPromSede" name="montoMatriculaPromSede" pattern="^\d*[.]?\d*$">
                                               <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearPromSede">
                                                   <i class="mdi mdi-lock" id=bloquear></i>
                                               </div> 
                                               <label class="mdl-textfield__label" for="montoMatriculaPromSede">Ratificaci&oacute;n(S/.)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente el monto.</span>
                                            </div>
        					           </div>
        					           <div class="col-md-12 mdl-input-group">
        				                   <div class="mdl-icon mdl-icon__button">
        				                       <button class="mdl-button mdl-js-button mdl-button--icon" id="iconfpromocion">
        				                           <i class="mdi mdi-today"></i>
        			                           </button>
        		                           </div>
            				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="fpromocion" name="fpromocion" maxlength="10">        
                                               <label class="mdl-textfield__label" for="fpromocion">Fecha de promoci&oacute;n</label>
                                           </div>
        				               </div>
        					           <!--  <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoCuotaPromoSede" name="montoCuotaPromoSede" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoCuotaPromoSede">Pensi&oacute;n (S/)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
        					           </div>
        					           -->
        					           <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <a id="btnMontosPromS" href="#cuotaIngreso" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizaMontosPromocionSede()">Aceptar</a>
                                       </div>
        					       </div>
					           </div>
					           <div class="mdl-tabs__panel" id="cuotaIngreso">
					               <div class="row-fluid">
					                   <div class="col-sm-12 mdl-input-group mdl-input-group__only text-center p-t-15">
                                           <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switchCI">
                                               <input type="checkbox" id="switchCI" class="mdl-switch__input" onclick="">
                                               <span class="mdl-switch__label">¿Activar?</span>
                                           </label>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <select id="selectTipoCI" name="selectTipoCI" class="form-control pickerButn" data-live-search="true" data-container="body" >
                    			                <option value="">Seleccionar Tipo</option>
                    			                <option value="1">Por Alumno</option>
                    			                <option value="2">Por Familia</option>
                    			           </select>
        					           </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoInicial" name="montoInicial" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoInicial">Cuota de Ingreso (S/)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente la couta incial.</span>
                                           </div>
        					           </div>
        					           <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="botonRAC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarActualizarConfigCI()">Aceptar</button>
                                       </div>
        					       </div>
					           </div>
					       </div>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEditarCuotaVerano" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sl" style="max-width: 420px;">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreSede"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">  
					        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a id="couta_pensiones" class="mdl-tabs__tab is-active" redirect="pensiones" onclick="cuotaIngreso($(this));">Pensiones</a>
					           </div>
					           <div class="mdl-tabs__panel is-active" id="pensiones">
					               <div class="row-fluid">			
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoCuotasVerano" name="montoCuotasVerano" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoCuotasVerano">Pensi&oacute;n (S/)</label>
		                                       <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
        					           </div>				
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="descuentoSedeVerano" name="descuentoSedeVerano">
                                               <label class="mdl-textfield__label" for="descuentoSedeVerano">Descuento (S/.)</label>
                                            </div>
        					           </div>
        					           <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="cuotaVeranoBTN" onclick="actualizarPensionesSedesVerano()">Aceptar</a>
                                        </div>
        					       </div>
					           </div>
					       </div>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        
        <div class="modal fade" id="modalEditarNivel" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreSede1"></h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
    					    <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    				            <div class="mdl-tabs__tab-bar">
    				                <a id="pensionesNivelTab"   href="#pensionesNivel" redirect="pensionesNivel" class="mdl-tabs__tab is-active">Pensiones</a>
    				                <a id="promocionesNivelTab" href="#promocionesNivel" class="mdl-tabs__tab">Promociones</a>
    				            </div>
    					        <div class="mdl-tabs__panel is-active" id="pensionesNivel">
    					            <div class="row-fluid">
            					        <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="display: none;" id="contCIngresoNivel">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="montoInicialNivel" name="montoInicialNivel" data-index-table="1" pattern="^\d*[.]?\d*$">
                                                <label class="mdl-textfield__label" for="montoInicialNivel">Cuota de Ingreso (S/)</label>
            		                            <span class="mdl-textfield__error">Ingreso correctamente la couta de ingreso.</span>
                                            </div>
            					        </div>				
            					        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            					                <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearNivel">
                                                    <i class="mdi mdi-lock"></i>
                                                </div> 					                   
                                                <input class="mdl-textfield__input" type="text" id="montoMatriculalNivel" name="montoMatriculalNivel" data-index-table="2" pattern="^\d*[.]?\d*$">
                                                <label class="mdl-textfield__label" for="montoMatriculalNivel">Matr&iacute;cula (S/)</label>
            		                            <span class="mdl-textfield__error">Ingreso correctamente la matr&iacute;cula.</span>
                                            </div>
            					        </div>				
            					        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="montoCuotasNivel" name="montoCuotasNivel" data-index-table="3" pattern="^\d*[.]?\d*$">
                                                <label class="mdl-textfield__label" for="montoCuotasNivel">Pensi&oacute;n (S/)</label>
            		                            <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
            					        </div>				
            					        <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="descuentoNivel" name="descuentoNivel" data-index-table="4" pattern="^\d*[.]?\d*$">
                                                <label class="mdl-textfield__label" for="descuentoNivel">Descuento (S/.)</label>
            		                            <span class="mdl-textfield__error">Ingreso solo n&uacute;meros entre 0 y 100%</span>
                                            </div>
            					        </div>
            					        <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                                            <button id="botonAPS" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizarPensionesSedesNivel();">Guardar</button>
                                        </div>
            					   </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="promocionesNivel">
    					           <div class="row-fluid">
    					               <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoMatriculaPromNivel" name="montoMatriculaPromNivel" pattern="^\d*[.]?\d*$">
                                               <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearNivelProm">
                                                    <i class="mdi mdi-lock"></i>
                                                </div>
                                               <label class="mdl-textfield__label" for="montoMatriculaPromNivel">Ratificaci&oacute;n(S/.)</label>
    		                                   <span class="mdl-textfield__error">Ingreso correctamente el monto.</span>
                                           </div>
            					       </div>
            					       <!-- <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoCuotaPromoNivel" name="montoCuotaPromoNivel" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoCuotaPromoNivel">Pensi&oacute;n (S/)</label>
    		                                   <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                           </div>
            					       </div> -->
            					       <div class="mdl-card__actions">
                                           <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                           <button id="btnMontosPromN" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizaMontosPromocionNivel()">Aceptar</button>
                                       </div>
            					   </div>
    					       </div>
    					    </div>
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEditarGrado" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="nombreSede2"></h2>
    					</div>
					    <div class="mdl-card__supporting-text">  
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    				            <div class="mdl-tabs__tab-bar">
    				                <a id="pensionesGradoTab" href="#pensionesGrado" redirect="pensionesGrado" class="mdl-tabs__tab is-active">Pensiones</a>
    				                <a id="promocionesGradoTab" href="#promocionesGrado" class="mdl-tabs__tab">Promociones</a>
    				            </div>
    					        <div class="mdl-tabs__panel is-active" id="pensionesGrado">
    					            <div class="row-fluid">
            					        <div class="col-sm-12 mdl-input-group mdl-input-group__only" style="display: none;" id="contCIngresoGrado">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoInicialGrado" name="montoInicialGrado" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoInicialGrado">Cuota de Ingreso (S/)</label>
                                               <span class="mdl-textfield__error">Ingreso correctamente la couta de ingreso.</span>
                                            </div>
        					           </div>				
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
        					                   <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearGrado">
                                                   <i class="mdi mdi-lock"></i>
                                               </div> 
                                               <input class="mdl-textfield__input" type="text" id="montoMatriculalGrado" name="montoMatriculalGrado" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoMatriculalGrado">Matr&iacute;cula (S/)</label>
                                               <span class="mdl-textfield__error">Ingreso correctamente la matr&iacute;cula.</span>
                                            </div>
                                       </div>
        					           <div class="col-sm-12 mdl-input-group mdl-input-group__only">
        					                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="montoCuotasGrado" name="montoCuotasGrado" pattern="^\d*[.]?\d*$">
                                               <label class="mdl-textfield__label" for="montoCuotasGrado">Pensi&oacute;n (S/)</label>
                                               <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
        					           </div>
            					        <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                                            <button id="botonAPSNG"class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizarPensionesSedesNivelGrado();">Guardar</button>
                                        </div>
            					   </div>
    					       </div>
     					       <div class="mdl-tabs__panel" id="promocionesGrado">
    					            <div class="row-fluid">
    					                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="montoMatriculaPromGrado" name="montoMatriculaPromGrado" pattern="^\d*[.]?\d*$">
                                                <div class="mdl-icon" style="position: absolute;top: 0;right: 0;z-index:110" id="bloquearGradoProm">
                                                    <i class="mdi mdi-lock"></i>
                                                </div>
                                                <label class="mdl-textfield__label" for="montoMatriculaPromGrado">Ratificaci&oacute;n(S/.)</label>
    		                                    <span class="mdl-textfield__error">Ingreso correctamente el monto.</span>
                                            </div>
            					        </div>
            					        <!--<div class="col-sm-12 mdl-input-group mdl-input-group__only">
            					            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="montoCuotaPromoGrado" name="montoCuotaPromoGrado" pattern="^\d*[.]?\d*$">
                                                <label class="mdl-textfield__label" for="montoCuotaPromoNivel">Pensi&oacute;n (S/)</label>
    		                                    <span class="mdl-textfield__error">Ingreso correctamente la pensi&oacute;n.</span>
                                            </div>
            					        </div> -->
            					        <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                            <button id="btnMontosPromG" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="actualizaMontosPromocionGrado()">Aceptar</button>
                                        </div>
            					    </div>
    					       </div>
    					   </div>			           
    					</div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalSedesCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0">
					               <select id="sedes_cronograma" name="idSede" class="form-control pickerButn" data-live-search="true" data-container="body" onchange="getCronogramaSede()">
            			                <option value="">Seleccionar sede</option>
				                        <?php echo $optSede; ?>
            			           </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal" onclick="getCronogramaSede()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalVistaPreviaCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" >
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 id=tituloCronograma class="mdl-card__title-text">Vista Previa</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">    				
					       <div class="row p-0 m-0">
					           
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--teal-500" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalCrearCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text" id="crearCrono"></h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b">   
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
					           <div class="mdl-tabs__tab-bar">
					               <a id="direccionarNuevoCronograma" href="#nuevoCronograma" class="mdl-tabs__tab is-active">Nueva</a>
					               <a id="direccionarNuevaPlantilla"  class="mdl-tabs__tab" href="#plantillaCronograma" data-paquete-text="Plantilla">Plantilla</a>
					           </div>
					           
					           <div class="mdl-tabs__panel is-active" id="nuevoCronograma">
					               <div class="row m-0 p-t-15">					       
    					               <div class="row m-0">					       
            					           <div class="col-sm-11 m-l-10 m-b-15">
            					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="yearCrono" name="yearCrono" maxlength="4">
                                                   <label class="mdl-textfield__label" for="yearCrono">A&ntilde;o</label>
                                                </div>
            					           </div>
            					           <div class="col-sm-11 m-l-10 p-b-40">
            					               <select id="selectTipoCronoNuevo" name="selectTipoCronoNuevo" class="form-control pickerButn" data-live-search="true" data-container="body">
            					                   <option value="">Seleccionar Tipo Cronograma</option>
            					                   <?php echo $optTiposCrono;?>
                                               </select>
            					           </div>
            					           <div class="mdl-card__actions">
                                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" id="btnRC" onclick="registrarCronograma()">Aceptar</button>
                                            </div>
            					       </div>
					               </div>
					           </div>
					           <div class="mdl-tabs__panel" id="plantillaCronograma">
					               <div class="row m-0 p-t-15">					       
        					           <div class="col-sm-11 m-l-10 m-b-15">
        					               <select id="Cronogramas" name="Cronogramas" class="form-control pickerButn" data-live-search="true" data-container="body">
                    			                <option value="">Seleccionar Cronograma</option>
        				                        <?php echo $optCronograma; ?>
                    			            </select>					               
        					           </div>
        					           <div class="col-sm-11 m-l-10 m-b-15">
        					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                               <input class="mdl-textfield__input" type="text" id="yearCrear">
                                               <label class="mdl-textfield__label" for="yearCrear">A&ntilde;o</label>
                                            </div>
        					           </div> 
        					           <div class="mdl-card__actions">
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarPlantillaCronograma()">Aceptar</button>
                                        </div>
        					       </div>
					           </div>
					          
					       </div>
				       </div>    					
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalPlantillaCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Crear un nuevo cronograma de una plantilla</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="yearCrono">
                                       <label class="mdl-textfield__label" for="yearCrono">A&ntilde;o</label>
                                    </div>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarPlantillaCronograma()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        
        <div class="modal fade" id="modalVerEstudiantes" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Estudiantes</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 br-b"></div>
					    <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltroAlumnoCompromiso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar alumnos</h2>
    					</div>    
					    <div class="mdl-card__supporting-text">
					       <div class="row">
					     	   <div class="col-sm-12 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="apellidos" onchange="inputApellidos()">
                                       <label class="mdl-textfield__label" for="apellidos">Estudiante</label>
                                    </div>
					           </div>
					     	   <div class="col-sm-6 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="codigoAlumno" onchange="inputCodigo()">
                                       <label class="mdl-textfield__label" for="codigoAlumno">C&oacute;digo de alumno</label>
                                    </div>
					           </div>
					     	   <div class="col-sm-6 mdl-input-group mdl-input-group__only">
					               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="codigoFamilia" onchange="inputCodigoFamilia()">
                                       <label class="mdl-textfield__label" for="codigoFamilia">C&oacute;digo de familia</label>
                                    </div>
					           </div>
				           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonFEC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalSaveCompromisosAlumno" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Compromiso de pago masivo</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
					       &#191;Deseas guardar los compromisos para los alumnos seleccionados&#63;	
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="saveCompromisosAlu()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalEliminarCompromisosExtras" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Desasignar compromisos</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
					       &#191;Deseas eliminar los compromisos de los alumnos seleccionados&#63;	
					       <div class="col-sm-12 p-0">
                                 <div class="mdl-textfield mdl-js-textfield">
                                    <textarea class="mdl-textfield__input" type="text" rows= "6" id="obsDeleteCompromisos" ></textarea>
                                    <label class="mdl-textfield__label" for="obsDeleteCompromisos">Observaci&oacute;n</label>
                                  </div>
    					    </div>	
    					</div>
    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="deleteCompromisosAluExtra()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal small fade" id="verCronoCompromisosAlumno" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Compromisos de pago</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-rl-0">
					       <div class="row m-0 p-0">
    					       <div class="col-sm-6"> 
    					           <input type="hidden" name="persona_compromiso" id="persona_compromiso">
    					           <input type="hidden" name="sede_compromiso" id="sede_compromiso">
    					           <input type="hidden" name="nivel_compromiso" id="nivel_compromiso">
    					           <input type="hidden" name="grado_compromiso" id="grado_compromiso">
    					           <select class="form-group pickerButn" id="tipoCronograma" onchange="verCompromisosAlumno()" data-live-search="true"></select>
    					       </div>
    					       <div class="col-sm-6"> 
    					       	   <select class="form-group pickerButn" id="YearCronoCompromisosAlumno" onchange="verCompromisosAlumno()" data-live-search="true"></select>
    					       </div>
    					       <div class="col-sm-12 p-0" id="combosSedeNivelGrado">
    					       
    					       </div>
    					       <div class="col-sm-12 p-0 m-t-10" id="calendarCompromisos">
    					       
    					       </div>	   
					       </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonRCA" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registroCompromisoAlumno()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalSaveCompromisos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Compromiso de pago masivo</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 
					       <div class="col-sm-12 p-0">
                               <select class="form-group pickerButn" id="conceptosCompromisos" data-live-search="true">
                                   
                               </select>
                           </div>
                           <div class="col-sm-12" id="nuevo_concepto"> 
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"> 
                                    <input class="mdl-textfield__input" type="text" id="descripcion">
                                    <label class="mdl-textfield__label" for="descripcion">Concepto</label>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="text" id="monto">
                                    <label class="mdl-textfield__label" for="monto">Monto de referencia</label>
                                </div>
                           </div> 		
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="registrarCompromisosMultiples()" id="save">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltroCompromiso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0">
					               <select id="selectSede" name="selectSede" class="form-group pickerButn" onchange="getNivelesBySede();" data-live-search="true">
					                   <option value="">Selec. Sede</option>
					                   <?php echo $optSede; ?>
                                   </select>
					           </div>
					           <div class="col-sm-12 p-0">
					               <select id=selectNivel name="selectNivel" class="form-group pickerButn" onchange="getGradosByNivel()" data-live-search="true">
					                   <option>Selec. Nivel</option>
                                   </select>
					           </div>
					           <div class="col-sm-12 p-0">
					               <select id="selectGrado" name="selectGrado" class="form-group pickerButn" onchange="getGrados()" data-live-search="true">
					                   <option>Selec. Grado</option>
                                   </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="botonCFMC" data-dismiss="modal" onclick="CerrarFiltroMultiCompromisos()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltroCompromisoDelete" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Eliminar Compromisos</h2>
    					</div>
					    <div class="mdl-card__supporting-text"> 		
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0">
					               <select value="" id="selectCompromisosGlobales" name="selectSede" class="form-group pickerButn" onchange="getCompromisosGlobales();" data-live-search="true">
                                   </select>
					           </div>
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalDetalleAulaCompromiso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" style="min-width:800px;">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text mdl-color-text--white">Estudiantes</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           
					       </div>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalInfoCrono" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                    		<h2 class="mdl-card__title-text">Informaci&oacute;n</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-t-0">   
                            <small>Al abrir una sede podr&aacute;s ver sus cronogramas y realizar mas acciones.</small>
                        </div>    
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal" data-dismiss="modal">Entend&iacute;</button>
                        </div>					
                    </div>
                </div>
            </div>       
        </div>
        
        <div class="modal fade" id="modalEliminarCronograma" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" >
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 id=tituloCronograma class="mdl-card__title-text">¿Desea eliminar el cronograma seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-t-0">
					       <small>Se eliminar&aacute;n todos los compromisos del cronograma</small>
    					</div>
    					<div class="mdl-card__actions text-right">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonEC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" id="btnEC" onclick="eliminarCronograma()">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarBecas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Becas</h2>
    					</div>
    					<div class="mdl-card__supporting-text">        					
    					   <div class="row"> 			
    				           <div class="col-sm-12 mdl-input-group mdl-input-group__text-btn p-rl-16">
    				               <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="buscarEstudiantes" onkeyup="activeDesactiveSearch();">
                                       <label class="mdl-textfield__label" for="buscarEstudiantes">Estudiantes</label>
                                   </div>
                                   <div class="mdl-btn">
            			               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="getAlumnosByName();" disabled id="btnBuscar">
								            <i class="mdi mdi-search" style="vertical-align:middle;"></i>
								       </button>
            			           </div>
    				           </div>
    				           <div class="col-sm-12 p-0" id="contTbEstudiantes"></div>
				           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-toggle="tooltip" data-original-title="Debes registrar una beca, para poder asignarla al alumno" data-placement="left">
                              <i class="mdi mdi-info" style="color:#FFFFFF"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalQuitarBeca" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Est&aacute;s seguro de quitar la beca al estudiante seleccionado?</h2>
    					</div>
					    <div class="mdl-card__supporting-text text-left">    				
                        	<small>Recuerda: Al quitarle la beca al estudiante volver&aacute; a pagar como un estudiante normal, sin descuento a ning&uacute;n monto que afecte la beca.</small>
    					</div>
    					<div class="mdl-card__actions">                            
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonQAB" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="quitarBeca();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarBeca" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Asignar Beca</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">   
					       <div class="row-fluid"> 				
                               <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                   <div class="mdl-select">
    					               <select id="selectBeca" name="selectBeca" class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Tipo de Beca</option>
    					                   <?php echo $optTipoBeca;?>
                                       </select>
                                   </div>
                                   <div class="mdl-select">
    					               <select id="selectYear" name="selectYear" class="form-group pickerButn" data-live-search="true">
    					                   <option value="">Selec. año</option>
    					                   <?php echo $optYear;?>
                                       </select>
                                   </div>
					           </div>
				           </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button id="botonABD" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarBeca();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalFiltroPensiones" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar Pensiones</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					        <div class="row">
					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
				                    <div class="mdl-select">
                                        <select class="pickerButn" id="selectTipoCronoPensiones" onchange="getSedesByTipoPago()" data-live-search="true">
                                            <option value="">Seleccione un tipo de cronograma</option>
                                            <?php echo $optTiposCrono?>
                                        </select>
                                    </div>
                                </div>
					        </div>					 
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="botonCFP" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" data-dismiss="modal">Aceptar</button>
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
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>bootstrap-tooltip/bootstrap_tooltip.js"></script>
   		<script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>treegrid-5a0511e/js/jquery.treegrid.bootstrap3.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-mask/jquery.mask.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/velocity.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>hammer/hammer.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>  	
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jshammer__configuracion.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jsbecas.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jspensiones.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscronograma.js"></script>
        <script src="<?php echo RUTA_PUBLIC_PAGOS?>js/jscompromisos.js"></script>
        <script type="text/javascript">
            initButtonLoad('botonRB','botonRP','botonAB','botonAP','btnMontosPromS','botonABD','botonQAB','botonCFP','cuotaIngresoBTN','botonRAC','botonCSP','botonAPS','btnRC','botonEC','botonFEC', 'cuotaVeranoBTN');
            init();
            initButtonCalendarDays('fpromocion');
            initMaskInputs('fpromocion');
            $('#iconfpromocion').click(function(){
            	initButtonCalendarMaxDate( 'fpromocion', null);
            });
        	initCalendarDaysMinToday('fpromocion');
        	initButtonCalendarDaysMinToday('fpromocion');
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip(); 
            });
            
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        	    $('.pickerButn').selectpicker('mobile');
        	} else {
        		$('.pickerButn').selectpicker();
        	}
        	
            $('.tree').treegrid({
            	initialState: 'collapsed',
                expanderExpandedClass: 'mdi mdi-keyboard_arrow_up',
                expanderCollapsedClass: 'mdi mdi-keyboard_arrow_down'
            });

            setTimeout(function() {
        		$('#tab-2, #tab-3, #tab-4').removeAttr('style');
        	}, 500);
        </script>
	</body>
</html>