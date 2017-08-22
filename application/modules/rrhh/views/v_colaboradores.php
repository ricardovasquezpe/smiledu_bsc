<!DOCTYPE html>
<html lang="en">
    <head>      
        <title>Colaboradores | <?php echo NAME_MODULO_RRHH?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_RRHH?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_RRHH?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS;?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_RRHH;?>css/submenu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_RRHH;?>css/mdl-card-style.css">      
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        
	</head>

	<body>
	
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>    		
    		<?php echo $menu ?>    		
            <main class='mdl-layout__content' onscroll="onScrollEvent(this)" id="mainColab">
                <section >
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1 mdl-content-cards" id="cont_colaboradores">     
                            <?php echo isset($colaboradores) ? $colaboradores : null?>
                        </div>
                    </div> 
                </section>                
            </main>	
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" >
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <button class="mfb-component__button--main" data-mfb-label="Nuevo colaborador" onclick="abrirModalNuevoColaborador()">
                    <i class="mfb-component__main-icon--active mdi mdi-person_add"></i>
                </button>
                <ul class="mfb-component__list">
                   <li>
                       <button class="mfb-component__button--child" data-mfb-label="Filtrar" data-toggle="modal" data-target="#modalFiltrarColaboradores" >
                           <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                       </button>
                   </li>         
                   <li>
                       <a href="c_permisos_rol" class="mfb-component__button--child" data-mfb-label="Permisos por sistemas" >
                           <i class="mfb-component__child-icon mdi mdi-beenhere"></i>
                       </a>
                   </li>                      
                </ul>    
            </li>
        </ul>
        
        <!-- Modals -->
        <div class="modal fade" id="modalFiltrarColaboradores" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar</h2>
    					</div>
					    <div class="mdl-card__supporting-text">    				
					       <div class="row p-0 m-0">
					           <div class="col-sm-12 p-0 m-b-15">
					               <select class="form-control">
            			                <option value="">Seleccione rol</option>
            			           </select>
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
        
        <div class="modal fade" id="modalAsignarRoles" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Roles</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0" id="cont_tb_RolesPersona"></div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="guardarRolesPersona()">Guardar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>    
                        </div> 
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAsignarPermisos" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Permisos</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0">    	 				
					       <!-- Aca va la tabla de permisos -->
    					</div>    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Guardar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-js-button mdl-button--icon" data-dismiss="modal">
                                <i class="mdi mdi-close"></i>
                            </button>    
                        </div> 
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">&#191;Desea eliminar a <label class="m-l-5" id="nombreColaborador">Nombre</label>?</h2>
    					</div>
					    <div class="mdl-card__supporting-text text-left">    	 				
					        Se eliminara a este colaborado.
    					</div>    					
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade" id="modalAgregarColaborador" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Agregar colaborador</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
			               <div class="row p-0 m-0">
			                   <div class="col-sm-6 col-md-6 mdl-input-group">
			                        <div class="mdl-icon"><i class="mdi mdi-account_circle"></i></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoPatColaboradorCrear" name="apellidoPatColaboradorCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoPatColaboradorCrear">Apellido Paterno</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="apellidoMatColaboradorCrear" name="apellidoMatColaboradorCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="apellidoMatColaboradorCrear">Apellido Materno</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="nombreColaboradorCrear" name="nombreColaboradorCrear" maxlength="100">        
                                        <label class="mdl-textfield__label" for="nombreColaboradorCrear">Nombres</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                    <div class="mdl-icon"><i class="mdi mdi-event_note"></i></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="fecNaciColaboradorCrear" name="fecNaciColaboradorCrear" data-inputmask="'alias': 'date'" maxlength="10">        
                                        <label class="mdl-textfield__label" for="fecNaciColaboradorCrear">Fecha Nacimiento</label>                            
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                   <div class="mdl-icon"><i class="mdi mdi-wc"></i></div>
				                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectSexoColaboradorCrear" name="selectSexoColaboradorCrear" data-live-search="true" data-container="body">
                			                <option value="">Selec. Sexo</option>
                			                <?php echo (isset($comboSexo) ? $comboSexo : null)?>
                			           </select>
            			           </div>
					           </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
				                   <div class="mdl-icon"><i class="mdi mdi-chrome_reader_mode"></i></div>
				                   <div class="mdl-select">
    					               <select class="form-control selectButton" id="selectTipoDocColaboradorCrear" name="selectTipoDocColaboradorCrear" data-live-search="true" data-container="body" onchange="changeTipoDoc('selectTipoDocColaboradorCrear', 'numeroDocColaboradorCrear')">
                			                <option value="">Selec. Tipo Documento</option>
                			                <?php echo (isset($comboTipoDocumento) ? $comboTipoDocumento : null)?>
                			           </select>
            			           </div>
					           </div>
					           <div class="col-sm-6 col-md-6 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="numeroDocColaboradorCrear" name="numeroDocColaboradorCrear" maxlength="8"  disabled>        
                                        <label class="mdl-textfield__label" for="numeroDocColaboradorCrear">N&uacute;mero de documento</label>
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="correoColaboradorCrear" name="correoColaboradorCrear" maxlength="40">        
                                        <label class="mdl-textfield__label" for="correoColaboradorCrear">Correo Eletronico</label>
                                    </div>
				               </div>
				               <div class="col-sm-6 col-md-6 mdl-input-group">
					                <div class="mdl-icon"></div>
    				                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="telefonoColaboradorCrear" name="telefonoColaboradorCrear" maxlength="8">        
                                        <label class="mdl-textfield__label" for="telefonoColaboradorCrear">Telefono</label>
                                    </div>
				               </div>
			               </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="crearColaborador()">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
          	 
      	<script src="<?php echo RUTA_JS;?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS;?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS;?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS;?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_JS;?>Utils.js"></script>
        <script src="<?php echo RUTA_JS;?>jsmenu.js"></script>        
        <script src="<?php echo RUTA_PUBLIC_RRHH?>js/jscolaborador.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        
        <script>   
            init();         
        </script>
	</body>
</html>