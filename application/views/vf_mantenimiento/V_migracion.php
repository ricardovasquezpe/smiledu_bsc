<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Migraci&oacute;n | <?php echo NAME_SMILEDU;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON_SIST_AV;?>" />        
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
                
        <style type="text/css">
            #modalEditDatos .mdl-modal_row:NTH-CHILD(2n){
            	padding-left: 0;
            	padding-right: 8px;	
            }
        
            #modalEditDatos .mdl-modal_row:NTH-CHILD(1n){
            	padding-right: 0;
            	padding-left: 8px;	
            }
        </style>
               
	</head>

	<body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		
    		<?php echo $menu ?>

            <main class='mdl-layout__content'>
                <section> 
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text" id="titleTb">Migraci&oacute;n</h2>
                            </div>
                        	
                            <div class="mdl-card__supporting-text p-0 br-b">
                                <div id="contMigracion">
                                    <?php echo $tablaMigracion;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>  
            </main>
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main">
                    <i class="mfb-component__main-icon--resting mdi mdi-add" ></i>
                </button>
                <button class="mfb-component__button--main" onclick="abrirCerrarModal('modalConfirmMigrar');" data-mfb-label="Migraci&oacute;n">
                    <i class="mfb-component__main-icon--active mdi mdi-view_week" ></i>
                </button>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child " onclick="abrirCerrarModal('modalTablaMigracion');" data-mfb-label="Historial de Migraciones">
                            <i class="mfb-component__child-icon mdi mdi-view_agenda"></i>
                        </button>
                    </li>
                    <li>
                        <button class="mfb-component__button--child " onclick="getPersonal();" data-mfb-label="Filtrar">
                            <i class="mfb-component__child-icon mdi mdi-view_list"></i>
                        </button>
                    </li>
                    <li>
                        <button class="mfb-component__button--child " onclick="getPersonalRecibos();" data-mfb-label="Personal por RECIBOS">
                            <i class="mfb-component__child-icon mdi mdi-view_module"></i>
                        </button>
                    </li>
                </ul>
            </li>
        </ul>
    		                
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Realiza tu b&uacute;squeda</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                            
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo" type="button" data-dismiss="modal">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalConfirmMigrar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">&#191;Desea realizar la migraci&oacute;n?</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="cmbTipoMigraFin" name="cmbTipoMigraFin" data-live-search="true" title="Tipo" class="form-control pickerButn" onchange="verGruposByTipo();">
                                        <option value="">Selec. Tipo</option><?php echo $tipoMigra;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <a id="btnM" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="migrar();">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalTablaMigracion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Historial de migraci&oacute;n</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="cmbTipoMigra" name="cmbTipoMigra" data-live-search="true" title="Tipo" class="form-control pickerButn" onchange="verGruposByTipo();">
                                        <option value="">Selec. Tipo</option><?php echo $tipoMigra;?>
                                    </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="cmbGrupo" name="cmbGrupo" data-live-search="true" title="Grupos" class="form-control pickerButn" onchange="verHistorialByGrupo();">
                                        <option value="">Selec. Grupo</option>
                                    </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="cmbGrupo" name="cmbGrupo" data-live-search="true" title="Grupos" class="form-control pickerButn" onchange="verHistorialByGrupo();">
                                        <option value="">Selec. Grupo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <a id="btnMG" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" data-dismiss="modal">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalEditDatos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="modalEditDatosTitle"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div class="row-fluid">
                                <div class="col-xs-12 mdl-modal_row m-0">
        					       <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            					       <input type="text" class="mdl-textfield__input" id="correo_pers" maxlength="150"  name="correo_pers">
                    				   <label class="mdl-textfield__label" for="correo_pers">Correo Personal</label>
                				   </div>
        					   </div>
        					   <div class="col-xs-12 mdl-modal_row m-0">
        					       <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            					       <input type="text" class="mdl-textfield__input" id="correo_inst" maxlength="150"  name="correo_inst">
                    				   <label class="mdl-textfield__label" for="correo_inst">Correo Institucional</label>
                				   </div>
        					   </div>
        					   <div class="col-xs-12 mdl-modal_row m-0">
        					       <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            					       <input type="text" class="mdl-textfield__input" id="correo_admin" maxlength="150"  name="correo_admin">
                    				   <label class="mdl-textfield__label" for="correo_admin">Correo Administrativo</label>
                				   </div>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbAreaGeneral" name="cmbAreaGeneral" data-live-search="true" title="Selec. &Aacute;rea General" class="form-control pickerButn" 
                                           onchange="getAreasEspecifCargos();" ><!--  -->
                                    </select>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbAreaEspec" name="cmbAreaEspec" data-live-search="true" title="Selec. &Aacute;rea Espec&iacute;fica" class="form-control pickerButn">
                                    </select>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbCargo" name="cmbCargo" data-live-search="true" title="Selec. Cargo" class="form-control pickerButn">
                                    </select>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbJornLab" name="cmbJornLab" data-live-search="true" title="Selec. Jornada Laboral" class="form-control pickerButn">
                                    </select>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbSedeCtrl" name="cmbSedeCtrl" data-live-search="true" title="Selec. Sede Control" class="form-control pickerButn">
                                    </select>
        					   </div>
        					   <div class="col-xs-6 mdl-modal_row">
        					       <select id="cmbNivelCtrl" name="cmbNivelCtrl" data-live-search="true" title="Selec. Nivel Control" class="form-control pickerButn">
                                   </select>
        					   </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect" type="button" data-dismiss="modal">Cancelar</a>
                            <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" type="button" onclick="guardarDatos();">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>      
        
        <div class="modal fade backModal" id="modalRegPorRecibos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header textoColor1 modal-header-escuela">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title textoColor1" id="modalRegRecibosTitle"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form floating-label">
                            <div class="row" style="margin-left: 2px;margin-right: 2px">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" style="margin-right: 10px" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--white mdl-color--indigo">GUARDAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js"></script>  
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jsMigracion.js"></script>
   		
   		
		<script type="text/javascript">
		    //var simplebar = new Nanobar();
		    initMigracion();
		    marcarNodo("HorariodeDocentes");
    		(function($) {
                $.fn.clickToggle = function(func1, func2) {
                    var funcs = [func1, func2];
                    this.data('toggleclicked', 0);
                    this.click(function() {
                        var data = $(this).data();
                        var tc = data.toggleclicked;
                        $.proxy(funcs[tc], this)();
                        data.toggleclicked = (tc + 1) % 2;
                    });
                    return this;
                };
            }(jQuery));
    		initSearchTableNew();
		</script>
	</body>
</html>