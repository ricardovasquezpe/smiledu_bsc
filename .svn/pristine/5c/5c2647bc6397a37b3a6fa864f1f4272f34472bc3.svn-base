<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Profesor - Curso | <?php echo NAME_MODULO;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo FAVICON;?>" />
        
        <!-- Tipografia -->
        <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/css/fonts/roboto.css"/>

        <!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/theme-default/bootstrap94be.css?1422823238" />
		<link rel='stylesheet' type='text/css' href="<?php echo base_url();?>public/plugins/b_select/css/bootstrap-select.min.css">
		<link rel='stylesheet' type='text/css' href="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.css">
        
        <!-- Theme Default -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/theme-default/materialadminb0e2.css?1422823243" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/theme-default/font-awesome.min753e.css?1422823239" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url()?>public/css/theme-default/material-design-iconic-font.mine7ea.css?1422823240" />
        
        <!-- MDL -->
		<link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/plugins/mdl/css/material.min.css">
        <link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/fonts/material-icons.css">
        
		<!-- Toastr -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/plugins/toaster/toastr.css">
        
        <!-- Fab -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/plugins/floating-button/mfb.css">		
		
		<!-- Style -->
		<link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/css/menu.css">
		<link rel='stylesheet' type='text/css' type='text/css' href="<?php echo base_url()?>public/css/m-p.css">
		
		<!-- Plugins -->
		<link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/plugins/bootstrap_select/css/bootstrap-select.min.css"/>
		
        
        <style type="text/css">
            body,
            .mdl-layout{
                overflow: hidden;	
            }       
            .popover.bottom>.arrow:after {
            	border-bottom-color: #FF9200;
            }
            .popover{
            	padding: 0px;
            }
            .mdl-card{
            	width: 100%;
            }
            .columns.columns-right.btn-group.pull-right{
            	margin: 0 !important
            }
        </style>
    </head>
<body>
    
    
    <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
        
    		<?php echo $menu ?>
    		
            <main class='mdl-layout__content p-t-30 p-b-30'>  
                <div class="row-fluid">                
                    <section id="base_1" class="p-0 m-0">
                        <div class="section-body m-0">
                            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 p-0">
                                <div class="mdl-card mdl-card__alter mdl-shadow--2dp">
                                    <div class="mdl-card__title p-t-20">
                                        <h2 class="mdl-card__title-text mdl-color-text--grey-600 mdl-typography--font-regular f-s-regular" id="titleTb">Profesores</h2>
                                    </div>
                                	
                                    <!-- Estilo de tabla p-0 pegar todo al card, m-t-30 margin top  -->
                                    <div class="mdl-card__supporting-text p-0 m-t-30">
                                        <div id="cont_tabla_aula" class="table_distance">
                                            <?php echo $tablaAula?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>                
                </div>
                
                <ul id="menu" class="mfb-component--br mfb-zoomin " style="z-index:1">
                  <li class="mfb-component__wrap">
                    <a href="javascript:void(0)" class="mfb-component__button--main mdl-color--indigo" onclick="abrirCerrarModal('modalFiltro')" id="main_button">
                      <i class="mfb-component__main-icon--resting md md-filter-list" style="font-size:26px;padding-top: 3px;color:white;margin-top:0px;transform:rotate(0deg)"></i>
                    </a>
                  </li>
                </ul>
                
            </main>
        </div>
	
        <div class="modal fade backModal" id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Filtro</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectSede" name="selectSede" onchange="getNivelesBySede()" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione una Sede">
						                  <option value="">Seleccione Sede</option>
						                  <?php echo $sedes?>
						             </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectNivel" name="selectNivel" onchange="getGradosByNivel()" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione un Nivel">
						                  <option value='0'>Seleccione Nivel</option>
						             </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectGrado" name="selectGrado" onchange="getAulasByGrado()" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione un grado">
						                   <option value='0'>Seleccione Grado</option>
						              </select>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <button class="btn ink-reaction btn-flat btn-primary" style="float:right;width: 100px" onclick="insertarAula()">GUARDAR</button>
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect m-0 mdl-color-text--grey-500" type="button" data-dismiss="modal">Aceptar</a>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalAlumnos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Alumnos</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <div id="cont_tabla_AlumnosAula"></div>
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
        
        <div class="modal fade backModal" id="modalDocentes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Docentes</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <div id="cont_tabla_docentes"></div>
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
        
        <div class="modal fade backModal" id="modalAgregarProfesor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title mdl-color--indigo p-20">
                            <h2 class="mdl-card__title-text mdl-typography--headline mdl-color-text--white">Filtrar</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-20">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectDocente" name="selectDocente" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione un Profesor" >
						                 <option value="">Seleccione Profesor</option>
						                 <?php echo $docentes?>
						             </select>
                                </div>
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <select id="selectCurso" name="selectCurso" class="form-control selectButton" data-live-search="true" data-container="body" data-noneSelectedText="Seleccione un Curso" >
						                <option value="">Seleccione Curso</option>
						                <?php echo $cursos?>
						             </select>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions p-10 p-r-20 p-l-20">
                            <button class="btn ink-reaction btn-flat btn-primary" style="float:right;width: 100px" onclick="insertarProfesor()">GUARDAR</button>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
        
	    <!-- JQuery -->
		<script src="<?php echo base_url()?>public/js/libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/jquery-ui/js/jquery-ui.min.js"></script>
    	
    	<!-- Boostrap -->
    	<script src="<?php echo base_url()?>public/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>public/plugins/b_select/js/bootstrap-select.min.js"></script>
		<script charset="UTF-8" src="<?php echo base_url()?>public/plugins/bTable/bootstrap-table.min.js"></script>
    	<script charset="UTF-8" src="<?php echo base_url()?>public/plugins/bTable/bootstrap-table-es-MX.js"></script>
    	<!-- Toastr -->
   		<script src="<?php echo base_url()?>public/plugins/toaster/toastr.js" charset="UTF-8"></script>
    	
    	<!-- Fab -->
   		<script src="<?php echo base_url()?>public/plugins/floating-button/mfb.js"></script>
   		
    	<!-- MDL -->
    	<script src="<?php echo base_url()?>public/plugins/mdl/js/material.min.js" defer></script>
    	
    	<!-- Velocity -->
    	<script src="<?php echo base_url()?>public/plugins/velocity/js/velocity.js"></script>
    	
    	<!-- Scripts -->
    	<script src="<?php echo base_url()?>public/plugins/bootstrap_select/js/bootstrap-select.min.js" charset="UTF-8"></script>
    	<script src="<?php echo base_url()?>public/js/jsmenu.js"></script>
    	<script src="<?php echo base_url()?>public/js/Utils.js"></script>
    	<script src="<?php echo base_url()?>public/js/jslogic/jsprofesoraula.js" charset="UTF-8"></script>
    
        <script type="text/javascript">
            init();
        </script>
</body>
</html>