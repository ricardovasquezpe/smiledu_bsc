<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Competencias | <?php echo NAME_SMILEDU;?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css"/> 
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">
        
	</head>
    <body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header' >
    		<?php echo $menu ?>
    		<main class='mdl-layout__content'>
                <section> 
                    <div class="mdl-content-cards">
                        <div class="mdl-card">
                            <div class="mdl-card__title">
								<h2 class="mdl-card__title-text" id="titleTb">Competencias</h2>
							</div>   							
							<div class="mdl-card__supporting-text p-0">
							     <div id="contTabCompe">
    						         <?php echo $tablaCompetencias;?>
    						      </div>
							</div>
        			    </div>
                    </div>
                </section>
    		</main>  		
    	</div>	
    		
		<ul id="menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
    	    <li class="mfb-component__wrap mfb-only-btn mdl-only-btn__animation">
                <button class="mfb-component__button--main"  onclick="abrirModalReg();" data-mfb-label="Nueva compentencia">
                    <i class="mfb-component__main-icon--resting mdi mdi-edit"></i>
                    <i class="mfb-component__main-icon--active  mdi mdi-edit"></i>
                </button>
            </li>
        </ul>    		
    	
    	<div class="modal fade backModal" id="modalNuevaCompetencia" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                     <div class="mdl-card mdl-card-fab">    
                         <div class="mdl-card__title">                                  
                             <h2 class="mdl-card__title-text">Nueva Competencia</h2>
                         </div>
                          
                         <form id=formNewCompetencia method="post" class="form-vertical form_distance">
                             <div class="mdl-card__supporting-text">
                                 <div class="row">
                         	         <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                         	             <div class="mdl-select">
                                             <select id="selectTipoDisciplina" name="selectTipoDisciplina" data-live-search="true" title="Tipo Disciplina" class="form-control pickerButn">
                                                 <option value="">Selec. Tipo Disiciplina</option>
                                                 <?php 
                                            	      foreach($tiposDisciplina as $var){
                                            		      echo '<option value='.$var.'>'.$var.'</option>';
                                            	      }
                                                  ?>                                
                                             </select>
                                         </div>                                      
                                     </div>
                    				 <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                    				      <div class="mdl-select">
                                              <select id="selectDisciplina" name="selectDisciplina" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Disciplina</option>                                                                                              
                                              </select>
                                          </div>                       
                    				  </div>
                					  <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            						      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                              <input type="text" maxlength="8" class="mdl-textfield__input" name="fecCompe" id="fecCompe" value="">
                                              <label class="mdl-textfield__label" for="fecCompe">Fecha</label>
                                          </div>
                					  </div>
                					  <div class="col-sm-12 mdl-input-group mdl-input-group__only" id="divNvlCompe">
                					      <div class="mdl-select">
                                              <select id="selectNivelCompetitivo" name="selectNivelCompetitivo" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Nivel Competitivo</option>
                                                  <?php 
                                            	      foreach($nivelCompetitivo as $var){
                                            		      echo '<option value='.$var.'>'.$var.'</option>';
                                            	      }
                                                  ?>
                                              </select>
                                          </div>
                					  </div>
                					  <div class="col-sm-12 mdl-input-group mdl-input-group__only">
            						      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                              <input type="text" maxlength="200" class="mdl-textfield__input" name="organizador" id="organizador" value="">
                                              <label class="mdl-textfield__label" for="username">Organizador</label>
                                          </div>
                					  </div>
                					  <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                					      <div class="mdl-select">
                                              <select id="selectNivelAcademico" name="selectNivelAcademico" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Nivel Acad&eacute;mico</option>
                                                  <?php echo $nivelesAcademicos; ?>
                                              </select>
                                          </div>                           
                					  </div>
                					  <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                					      <div class="mdl-select">
                                              <select id="selectDocentes" name="selectDocentes" data-live-search="true" class="form-control pickerButn">
                                                  <option value="">Selec. Docente</option>
                                                  <?php echo $docentesChoice; ?>                                         
                                              </select>
                                          </div>
                					  </div>
                				      <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                					      <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="user">
                                              <input type="text" class="mdl-textfield__input" maxlength="3" name="nroCopas" id="nroCopas" value="">
                                              <label class="mdl-textfield__label" for="username">N&uacute;mero de Copas o Galardones</label>
                                          </div>
                				      </div>
                                  </div>
                              </div>
                              <div class="mdl-card__actions">
                                  <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CANCELAR</button>
                                  <button id="btnMNC" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" >GUARDAR</button>
                              </div>    
                         </form>
                     </div>                  
                 </div>     
            </div>
        </div>
  
  
        <div class="modal fade backModal" id="mdConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card ">    
                       <div class="mdl-card__title">
                           <h2 class="mdl-card__title-text" id="tituModal">Eliminar</h2>
                       </div>  
                        <div class="mdl-card__supporting-text">
                            <div class="row-fluid">
                                <div class="col-xs-12 p-0 m-0 m-b-20">
                                    <p style="text-align: center;display: inline;" class="textoColor">&#191;Eliminar la <strong>Competencia </strong>?</p>
                                </div>
                            </div>                             
                        </div>
                        <div class="mdl-card__actions">                            
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">CANCELAR</button>
                            <button id="btnMCE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="confirmDelete(this)">ACEPTAR</button>
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
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>         		
        <script src="<?php echo RUTA_JS?>jsmantenimiento/jsdisciplinas.js"></script>
   		
		<script type="text/javascript">        	
    		initDisciplina();
    		$('#fecCompe').bootstrapMaterialDatePicker({ weekStart : 0, time: false, format : 'DD/MM/YYYY'});
    		$(function() {
                $( "#sortable" ).sortable();
                $( "#sortable" ).disableSelection();
            });
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
            
            var lastScrollTop = 0;
            $(window).scroll(function(event){
               var st = $(this).scrollTop();
               if (st > lastScrollTop){//OCULTAR
            	   $("#menu").fadeOut();
               } else {
            	   if(st + $(window).height() < $(document).height()) {//MOSTRAR
            		   $("#menu").fadeIn();
            	       
        	    	}
               }
               lastScrollTop = st;
            });
		</script>
	</body>
</html>