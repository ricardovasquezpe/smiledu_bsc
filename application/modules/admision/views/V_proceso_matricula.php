<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS;?>m-p.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_ADMISION;?>css/submenu.css">
        
        <style>
            button.big{
            	width: 100%;
            	margin-left: 0;
            }
            
            header.mdl-layout__header{
            	z-index: 7
            }
            
            main.mdl-layout__content{
            	width: -webkit-calc(100% - 1010px);
            	width: -moz-calc(100% - 1010px);
            	width: -ms-calc(100% - 1010px);
            	width: -o-calc(100% - 1010px);
            	width: calc(100% - 1010px);
            }
            
            .inscrito-value{
	           text-decoration:none !important;
            	cursor:default !important;
            }
        </style>
                
	</head>

	<body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    	    		
    		<?php echo $menu ?>
	    	
	    	<iframe id ="myframe" name="myframe" src="http://181.224.225.82/" class="myframe" marginwidth="0" scrolling="yes" frameborder="0"></iframe>
            <main class='mdl-layout__content is-visible'>
                <section class="mdl-layout__tab-panel is-active" id="tab-1">
                    <div class="mdl-content-cards">
                    	<div class="mdl-card">
	                    	<div class="mdl-card__title">
	                    		<h2 class="mdl-card__title-text">Datos</h2>
	                    	</div>
							<div class="mdl-card__supporting-text">
									<div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apPaternoPostulante" name="apPaternoPostulante" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" disabled value="<?php echo (isset($apePaterno) ? $apePaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Paterno (*)</label>  
                                            <span class="mdl-textfield__error">Apellido paterno solo debe contener letras</span>                                                                      
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="apMaternoPostulante" name="apMaternoPostulante" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" disabled value="<?php echo (isset($apeMaterno) ? $apeMaterno: null)?>">
                                            <label class="mdl-textfield__label" for="">Apellido Materno (*)</label> 
                                            <span class="mdl-textfield__error">Apellido materno solo debe contener letras</span>                                                                         
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="nombresPostulante" name="nombresPostulante" pattern="[A-Z,a-z,Ññ, ,áéíóú,àèìòù,ÀÈÌÒÙ,ÁÉÍÓÚ]*" maxlength="60" disabled value="<?php echo (isset($nombres) ? $nombres: null)?>">
                                            <label class="mdl-textfield__label" for="">Nombres (*)</label>
                                            <span class="mdl-textfield__error">Nombre solo debe contener letras</span>                                                                          
                                        </div>
                                    </div>
    					            <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label divInput">
                                            <input class="mdl-textfield__input" type="text" id="nroDocumentoPostulante" name="nroDocumentoPostulante" maxlength = "12" value="<?php echo (isset($nroDoc) ? $nroDoc: null)?>" disabled>
                                            <label class="mdl-textfield__label" for="nroDocumentoPostulante">N&uacute;mero del documento</label>                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectSexoPostulante" name="selectSexoPostulante" class="form-control selectButton" data-live-search="true" data-noneSelectedText="Selec. sexo" disabled>
        						                <option value="">Selec. sexo</option>
                			                    <?php echo $comboSexo?>
        						            </select>                                           
                                        </div>                                                    
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectGradoNivel" name="selectGradoNivel" class="form-control selectButton" data-live-search="true" value="<?php echo (isset($gradoNivel) ? $gradoNivel: null)?>" data-noneSelectedText="Selec. Grado y Nivel">
        						                  <option value="">Selec. Grado y Nivel</option>
        						                  <?php echo $comboGradoNivel?>
        						             </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                        <div class="mdl-select">
                                            <select id="selectColegioProc" name="selectColegioProc" class="form-control selectButton" data-live-search="true"  value="<?php echo (isset($colegioProcedencia) ? $colegioProcedencia: null)?>" disabled>
        						                  <option value="">Selec. Colegio de procedencia</option>
                			                          <?php echo $comboColegios?>
        						             </select>
                                        </div>
                                    </div>
						    </div>
                    	</div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-2">
                    <div class="mdl-filter">
        				<div class="p-r-15 p-l-15">
        					<div class="mdl-content-cards mdl-content__overflow">
                                <?php echo isset($parientes) ? $parientes : null?>
                            </div>
        				</div>
        			</div>
        			<div class="p-r-15 p-l-15">
        				<div class="mdl-content-cards">
        				    <div class="mdl-card">
    	                    	<div class="mdl-card__title">
    	                    		<h2 class="mdl-card__title-text">Datos</h2>
    	                    	</div>
    							<div class="mdl-card__supporting-text">
    	                    	</div>
                        	</div>
        				</div>
    				</div>
                </section>
            </main>
        </div>
        
      
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>rippleria-master/js/jquery.rippleria.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>        
   		<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_PUBLIC_ADMISION?>js/jsprocesomatricula.js"></script>
        <script type="text/javascript">
    		returnPage();
        	init();
        	setearCombo('selectSexoPostulante', '<?php echo $sexo?>',null,1);
            setearCombo('selectColegioProc', '<?php echo $colegioProcedencia?>',null,1);
            setearCombo('selectGradoNivel', '<?php echo $gradoNivel?>',null,1);
        </script>
	</body>
</html>