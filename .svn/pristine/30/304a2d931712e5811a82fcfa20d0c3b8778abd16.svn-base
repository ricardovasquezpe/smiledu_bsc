<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Curso | <?php echo NAME_MODULO;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width; initial-scale=1, maximum-scale=1"> 
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON;?>" />
        
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.min.css" />
		<link type='text/css' rel='stylesheet' href="<?php echo base_url()?>public/plugins/b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/plugins/floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/plugins/toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/fonts/roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/plugins/mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/fonts/material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/mdl-card-style.css">   
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/theme-default/font-awesome.min753e.css?1422823239" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/wizard.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/plugins/pace/pace_css.css"/>
        
        <style type="text/css">
            .popover.bottom>.arrow:after {
            	border-bottom-color: #FF9200;
            }
            .popover{
            	padding: 0px;
            }
            .columns.columns-right.btn-group.pull-right{
            	margin: 0 !important
            }
        </style>
    </head>
    
    <body>
    
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >        
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content '>              
                <section >
                    <div class="row p-0 m-0">
                        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
                            <div class="mdl-card">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text" id="titleTb">Grados</h2>
                                </div>
                            	
                                <div class="mdl-card__supporting-text p-0 br-b">
                                    <div id="cont_tabla_grados" class="table_distance">
                                         <?php echo $tablaGrados?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>   
            </main>
        </div>
        
        <ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
            <li class="mfb-component__wrap mfb-only-btn">
                 <button class="mfb-component__button--main" id="main_button" onclick="abrirCerrarModal('modalFiltro')" data-mfb-label="Filtrar">
                     <i class="mfb-component__main-icon--resting mdi mdi-filter_list"></i>
                     <i class="mfb-component__main-icon--active  mdi mdi-filter_list"></i>
                 </button>
             </li>            
        </ul>
        
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card mdl-card-fab" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtros</h2>
    					</div>
    					<div class="mdl-card__supporting-text ">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-0 m-b-15">
                                     <select id="selectSede" name="selectSede" onchange="getNivelesBysede();" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione una Sede">
						                  <option value="">Seleccione Sede</option>
						                  <?php echo $optSedes;?>
						             </select>
                                </div>
                                <div class="col-sm-12 p-0 m-b-15">
                                     <select id="selectNivel" name="selectNivel" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione una Nivel">
						                  <option value="">Seleccione Nivel</option>
						             </select>
                                </div>
                                <div class="col-sm-12 p-0">
                                     <select id="selectYear" name="selectYear" onchange="greadoByNivel();" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione un A�o">
						                  <option value="">Seleccione A&ntilde;o</option>
						                  <option value='2015'>2015</option>
						        	      <option value='2016'>2016</option>
						             </select>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalEditarGrado" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" style="z-index: 100">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Grado</h2>
    					</div>
    					<div class="mdl-card__supporting-text ">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-0">
                                     <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="peso" name="peso">        
                                        <label class="mdl-textfield__label" for="peso">Peso</label>                            
                                    </div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarGrado();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalEditarGrado" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" style="z-index: 100">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Editar Grado</h2>
    					</div>
    					<div class="mdl-card__supporting-text ">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-0">
                                     <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="peso" name="peso">        
                                        <label class="mdl-textfield__label" for="peso">Peso</label>                            
                                    </div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="editarGrado();">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalGradoxCursoDetalle" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true" style="z-index: 100">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Cursos</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-0">
                                    <div id="cont_tabla_cursos_grado"></div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalAsignarCurso" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Asignar Cursos</h2>
    					</div>
    					<div class="mdl-card__supporting-text p-0">
                            <div class="row m-0 p-0">
                                <div class="col-sm-12 p-0">
                                    <div id="cont_tabla_cursos"></div>
                                </div>
                            </div>            
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
        
         <div class="modal fade backModal" id="modalAsignarCurso1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Asignar Curso</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <div id="cont_tabla_cursos"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo base_url()?>public/js/libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo base_url()?>public/js/libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo base_url()?>public/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo base_url()?>public/js/libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>public/plugins/b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url()?>public/plugins/floating-button/mfb.js"></script>
        <script src="<?php echo base_url()?>public/plugins/toaster/toastr.js"></script>
        <script src="<?php echo base_url()?>public/plugins/mdl/js/material.min.js"></script>
        <script src="<?php echo base_url()?>public/plugins/bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/bTable/bootstrap-table-es-MX.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/velocity/js/velocity.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/inputmask/jquery.inputmask.bundle.min.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/pace/pace.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/js/jsmenu.js"></script>
    	<script src="<?php echo base_url()?>public/js/jslogic/jscursosgrado.js"></script>
    
        <script type="text/javascript">
            init();
        </script>
    </body>
</html>