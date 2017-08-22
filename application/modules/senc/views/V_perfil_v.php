<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Mural | Sistema AvantGard</title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID?>">
        
        <link rel="shortcut icon" type="image/png" href="<?php echo base_url()?>public/img/header/Avantgardfavi.ico" />
        
        <!-- Boostrap -->        
        <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/plugins/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/plugins/bootstraptour/css/bootstrap-tour.min.css"/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/plugins/bootstraptour/css/bootstrap-tour-standalone.min.css"/>
        
        <!-- MDL -->
        <link rel="stylesheet" href="<?php echo base_url()?>public/fonts/material-icons.css">
        <link rel="stylesheet" href="<?php echo base_url()?>public/plugins/mdl/css/material.min.css">
        <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/css/animate.css" />
        <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/css/m-p.css" />
        
        <!-- Style View -->        
        <link rel="stylesheet" type='text/css' href="<?php echo base_url()?>public/css/menu.css">
        <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>public/css/logic/perfil.css" />
        

        <link rel="stylesheet" href="<?php echo base_url()?>public/plugins/cropper/cropper.css">
        <link rel='stylesheet' type='text/css' href="<?php echo base_url();?>public/plugins/toaster/toastr.css">
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/plugins/bootstrap-validator/bootstrapValidator.min.css" />
    </head>
    
    <body>
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
      
        	<?php echo $menu?>
        		
        	<main class='mdl-layout__content is-visible'>
            	<div class="header-profile">
            		<div class="image-header">
                	     <div class="row  p-0 p-t-30">
                	       <div class="col-sm-6 col-sm-offset-2 col-xs-10 col-xs-offset-1 ">
                        			     <div class="row-fluid">
                        			         <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-0 col-md-3 col-md-offset-0">
                        			             <div class="mdl-image full-bleed ">
                                        		     <img id="foto_perfil" class="img-circle img-responsive" src="<?php echo $this->session->userdata('foto_usuario');?>" alt="Profile">
                                        		</div>
                                        		<div class="mdl-edit ">
                                                    <i class="mdi mdi-mode_edit" id="cambiar_foto" onclick="abrirCerrarModal('elegirModo')"></i>
                                                </div>
                                                
                        			         </div>
                        			             <div class="col-md-9 col-md-offset-0 col-sm-8 col-sm-offset-0">
                        			                 <div class="col-xs-12 col-md-8 col-sm-8 text-left">
                        			                     <h3 class="mdl-typography--headline m-0 m-t-5" style="text-transform: capitalize !important;"><?php echo $nombrePersona ?></h3>
                                    	                  <h5 class="mdl-typography--caption m-0"><?php echo $nombreUsuario?></h5>                                    	                                          			         
                        			                 </div>
                        			                 <div class="col-xs-12 col-md-8 col-sm-8 p-10 m-t-15">
                                    	                   <div class="progress">
                        			                         <div class="progress-bar progress-bar-success progress-bar-striped"   role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                        			                           <span class="sr-only">40% Complete (success)</span>
                        			                        </div>                        			         
                        			                       </div>                        			                                                     	                                                	        
                        			                      </div>
                        			                      
                        			            </div>
                        			       </div>    
                                    		
                                    </div>
                        			<div class="col-sm-2 m-0 text-right ">
                        			     <div class="row-fluid">
                        			        <div class="col-sm-12 col-xs-4 m-0 p-0">
                        			             <div class="col-sm-2 col-sm-push-10 m-0 p-0">
                        			                 <i class="mdi mdi-favorite"></i>
                        			             </div>
                        			             <div class="col-sm-10 col-sm-pull-2 m-0 p-0">
                        			                 <h4 class="m-0 p-0" >12</h4>
                        			             </div>
                        			        </div>                        			        
                                            <div class="col-sm-12 col-xs-4 m-0 p-0">
                                                <div class="col-sm-2 col-sm-push-10 m-0 p-0">
                                                    <i class="mdi mdi-star"></i>
                                                </div>
                                                <div class="col-sm-10 col-sm-pull-2 m-0 p-0">
                                                    <h4 class="m-0 p-0 id="favorite">48</h4> 
                                                </div>                        			                
                        			         </div>
                        			         <div class="col-sm-12 col-xs-4 m-0 p-0">
                        			             <div class="col-sm-2 col-sm-push-10 m-0 p-0">
                        			                 <i class="mdi mdi-today"></i>
                        			             </div>
                        			             <div class="col-sm-10 col-sm-pull-2 m-0 p-0">
                        			                 <h4 class="m-0 p-0" id="today">88</h4>
                        			             </div>                        			             
                        			         </div>
                        			     </div>
                        			 
                    			 </div>
                			 
                    </div>
            	</div>
            	</div>
            	
            	<div class="content-profile">
            	   <div class="row-fluid">
            	       <div class="col-xs-12 col-xs-offset-0  col-sm-8 col-sm-offset-2  col-md-8 col-md-offset-2 m-b-15 p-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">
            	                   <h2 class="mdl-card__title-text">Informaci&oacute;n B&aacute;sica</h2>
            	               </div>
            	               <form id="formCambioDatos" method="post">
                	               <div class="mdl-card__supporting-text">
                	                   <div class="row-fluid">
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="usuario" name="usuario" value="<?php echo $nombreUsuario?>" maxlength="30">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="usuario">Usuario</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" data-inputmask="'alias': 'email'" name="email" id="email" value="<?php echo $correoPersona?>" maxlength="150">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="email">Correo Electrónico</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" data-inputmask="'alias': 'date'" name="fechaNac" id="fechaNac" value="<?php echo $fnaciPersona?>">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="fechaNac">Fecha de Nacimiento</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" name="nro_documento" id="nro_documento" value="<?php echo $nro_documento?>" maxlength="20">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="usuario">Documento de Identidad</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" name="telefono" id="telefono" value="<?php echo $telefonoPersona?>" maxlength="200">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="telefono">Teléfono</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" name="tipoSangre" id="tipoSangre" value="<?php echo $tipoSangre?>" maxlength="10">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="telefono">Tipo de Sangre</label>
                                               </div>
                	                       </div>
                                       </div>
                	               </div>
                	               <?php if(!isset($_GET['usuario']) || $_GET['usuario'] == null){?>
                	               <div class="mdl-card__actions">
                	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="btnCambioDatos">GUARDAR</button>
                	               </div>
                	               <?php }?>
                               </form>
            	           </div>
            	       </div>
            	          <div class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-2 m-b-15 p-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">
            	                   <h2 class="mdl-card__title-text">Informaci&oacute;n Adicional</h2>
            	               </div>
            	               <div class="mdl-card__supporting-text">
                                    <div class="col-md-12" id="formInfoAdicional">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <textarea class="mdl-textfield__input" name="hobby" id="hobby" rows="6"><?php echo isset($hobby) ? $hobby : null;?></textarea>
                                            <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="hobby">Hobbies</label>
                                        </div>
                                    </div>                	                   
                                </div>
                                
                                <?php if(!isset($_GET['usuario']) || $_GET['usuario'] == null){?>
                                <div class="mdl-card__actions">
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect " onclick="saveInteresesHobby()">Guardar</a>
                                </div>
                	            <?php }?>
            	           </div>
            	       </div> 
            	       
            	       <?php if(!isset($_GET['usuario']) || $_GET['usuario'] == null){?>
            	       <div class="col-xs-12 col-xs-offse-0 col-sm-4 m-b-15   p-r-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">
            	                   <h2 class="mdl-card__title-text">Cambiar contraseña</h2>
            	               </div>
            	               <form id="formCambioClave" method="post">
            	                   <div class="mdl-card__supporting-text">
            	                       <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="lastPass" id="lastPass">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="lastPass">Contraseña actual</label>
                                            </div>
                	                   </div>
                	                   <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="newPass" id="newPass">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="newPass">Nueva contraseña</label>
                                            </div>
                	                   </div>
                	                   <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="newPass2" id="newPass2">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="newPass2">Repetir nueva contraseña</label>
                                            </div>
                	                   </div>
            	                   </div>
            	                   
                	               <div class="mdl-card__actions">
                	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--blue-500" >GUARDAR</button>
                	               </div>
            	               </form>
            	           </div>
            	       </div>
            	       <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 m-b-15 p-l-0 p-r-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">   
            	                   <h2 class="mdl-card__title-text">Tamaño de letra</h2>
            	               </div>
        	                   <div class="mdl-card__supporting-text">
        	                       <div class="col-md-12 text-center">
            	                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-m" type="submit">A-</button>
            						    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-d" type="submit" onclick="setFont14px()">A</button>
            						    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-p" type="submit">A+</button>
            	                   </div>
            	                   <div class="col-md-12">
                                        <p id="contentPrueba" class="mdl-text mdl-color-text--grey-800 mdl-typography--body-1" type="password" name="newPass" id="newPass">"La gente piensa que enfocarse significa decir sí a aquello en lo que te enfocas, pero no es así. Significa decir no a otras cientos de ideas buenas que hay."</p>
            	                   </div>
        	                   </div>
        	                   
            	               <div class="mdl-card__actions">
            	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="saveFontSize()">GUARDAR</button>
            	               </div>
            	           </div>
            	       </div>            	       
            	       <?php }?>
            	       
            	     
            	       
            	       <!--?php if(!isset($_GET['usuario']) || $_GET['usuario'] == null){?-->
                            <!--div class="col-md-8">
                                <div class="card" id="infoBasica">
            						<div class="card-head">
            							<header>Cumpleaños del Mes</header>
            						</div>
            						<div class="form floating-label table_distance">
        						        <!--?php echo $tbCumple;?-->
        						    </div>
            					</div>
            				</div-->
				        <!--?php }?-->
            	           
            	   </div>
            	</div>
    		</main>
    	</div>
    
    	<div class="modal fade" id="elegirModo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog">
    			<div class="modal-content">
    				<div class="modal-body">
    				    <div class="row text-center">
    				        <div class="col-xs-6" onclick="elegirImagen()">
    				            <h4><i class="mdi mdi-camera_alt"></i> Subir Foto</h4>
        				    </div>
        				    <div class="col-xs-6" id="cambiar_foto1" onclick="abrirEditarFoto('<?php echo $this->session->userdata('foto_usuario');?>')">
        				         <h4><i class="mdi mdi-mode_edit"></i> Editar Foto</h4>
        				    </div>
    				    </div>
    				</div>
    			</div><!-- /.modal-content -->
    		</div><!-- /.modal-dialog -->
    	</div>
    	
    	
        <div class="modal fade" id="modalEditarFoto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    					<h4 class="modal-title" id="simpleModalLabel">Editar Foto</h4>
    				</div>
    				<div class="modal-body">
    				    <div class="img-container">
                          <img id="fotoPerfilRecortar">
                        </div> 	
                        <div class="row">
        				    <div class="col-sm-12">
        				        <button class="btn ink-reaction btn-flat btn-primary" onclick="guardarImagenRecortadaPerfil()" style="float:right;display:none" id="btnGuardarFoto">GUARDAR</button>
                                <button class="btn ink-reaction btn-flat btn-primary" data-method="getCroppedCanvas" onclick="initRecortarPerfil(this.id, 'fotoPerfilRecortar')" id="botonRecortarPerfil" style="float:right">RECORTAR</button>
                                <button class="btn ink-reaction btn-flat btn-default-dark" data-dismiss="modal" style="width: 100px;float:right">CANCELAR</button>
        				    </div>
        				</div>			
    				</div>
    			</div><!-- /.modal-content -->
    		</div><!-- /.modal-dialog -->
    	</div>
    
    	<input type="file" id="itFotoUpd" style="display: none;">
    
    	<script src="<?php echo base_url()?>public/js/libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/mdl/js/material.min.js" defer></script>
    	<script src="<?php echo base_url()?>public/plugins/bootstrap/js/bootstrap.min.js"></script>
    	<script src="<?php echo base_url()?>public/plugins/cropper/tooltip.min.js"></script>
        <script src="<?php echo base_url()?>public/plugins/cropper/cropper.js"></script>
        <script src="<?php echo base_url()?>public/plugins/toaster/toastr.js"></script>
        <script src="<?php echo base_url()?>public/plugins/inputmask/jquery.inputmask.bundle.min.js" charset="UTF-8"></script>
        
        <script src="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.js" charset="UTF-8"></script>
        <script src="<?php echo base_url()?>public/plugins/bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo base_url()?>public/plugins/fontSize/jquery.jfontsize-1.0.js"></script>
        <script src="<?php echo base_url()?>public/js/jsmenu.js"></script>
    	<script src="<?php echo base_url()?>public/js/Utils.js"></script>
    	<script src="<?php echo base_url()?>public/js/jslogic/jsperfil.js"></script>
    	<script type="text/javascript">
    	    $(":input").inputmask();
    	    initV2();

    	    $('#contentPrueba').jfontsize({
                btnMinusClasseId: '#jfontsize-m',
                btnPlusClasseId: '#jfontsize-p'
            });

            function setFont14px(){
            	$('#contentPrueba').css('font-size','14px');
            }
    	</script>
    </body>
</html>