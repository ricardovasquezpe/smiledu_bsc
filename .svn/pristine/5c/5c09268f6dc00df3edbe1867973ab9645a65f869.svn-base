<?php $this->load->helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Perfil | <?php echo NAME_SMILEDU;?></title>
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
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>logic/profile.css"> 
        
        <style>
            .mdl-tabs__tab:nth-child(3) span.mdl-ripple{
            	display: none;
            }
            
            .pace .pace-progress{
            	background-color: transparent !important;
            }
        </style> 
          
    </head>
    
	<body onload="screenLoader(timeInit);">
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
            <?php echo $menu?>
            <main class='mdl-layout__content'>
                <section>
                    <div class="header-profile">
                        <div class="bg-header"></div>
                        <div id="snow"></div>
                        <img id="foto_perfil" class="img-circle img-responsive"src="<?php echo $this->session->userdata('foto_usuario');?>" alt="Profile">
                        <h2 class="mdl-card__title-text"><?php echo $nombrePersona?></h2>
                        <h2 class="mdl-card__title-text"><?php echo $nombreUsuario?></h2>
                        <button class="mdl-button mdl-js-button mdl-button--icon" id="cambiar_foto" onclick="abrirCerrarModal('elegirModo')">
                            <i class="mdi mdi-mode_edit"></i>
                        </button>
                    </div>
                    <div class="content-profile">
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="mdl-tabs__tab-bar">
                                <a href="#informacion" class="mdl-tabs__tab is-active"><i class="mdi mdi-account_circle"></i><label>Informaci&oacute;n</label></a>
                                <a href="#configuracion" class="mdl-tabs__tab "><i class="mdi mdi-settings"></i><label>Configuraci&oacute;n</label></a>
                                <a style="cursor:no-drop;" href="#" class="mdl-tabs__tab disabled" title="Pr&oacute;ximamente"><i class="mdi mdi-insert_chart"></i><label>Desempe&ntilde;o</label></a>
                            </div>
                            
                            <div class="mdl-tabs__panel is-active" id="informacion">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Informaci&oacute;n b&aacute;sica</h2>
                                    </div>                                    
                                    <div class="mdl-card__supporting-text br-b">
                                        <div class="row-fluid">
                                            <div class="col-sm-6 col-md-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-account_box"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                    <input class="mdl-textfield__input" type="text" id="nombres" name="nombres" disabled value="<?php echo $nombrePersona?>">        
                                                    <label class="mdl-textfield__label" for="nombres">Nombre(s)</label>                            
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon"></div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="apellidoPaterno" name="apellidoPaterno" disabled value="<?php echo $apellidoPatPersona.' '.$apellidoMatPersona?>">
                                                   <label class="mdl-textfield__label" for="apellidoPaterno">Apellidos</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-wc"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="sexo" name="sexo" disabled value="<?php echo $sexo?>">
                                                   <label class="mdl-textfield__label" for="sexo">Sexo</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-date_range"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="fechaNacimiento" name="fechaNacimiento" disabled value="<?php echo $fnaciPersona?>">
                                                   <label class="mdl-textfield__label" for="fechaNacimiento">Fecha de Nacimiento</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-local_hospital"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="lugarNacimiento" name="lugarNacimiento" disabled>
                                                   <label class="mdl-textfield__label" for="lugarNacimiento">Lugar de Nacimiento</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-language"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="nacionalidad" name="nacionalidad" disabled>
                                                   <label class="mdl-textfield__label" for="nacionalidad">Nacionalidad</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-account_balance"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="religion" name="religion" disabled>
                                                   <label class="mdl-textfield__label" for="religion">Creencia religiosa</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-info"></i></button>
                                    </div>
                                </div>
                                
                                <div class="mdl-card m-t-10">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Informaci&oacute;n de contacto</h2>
                                    </div>                               
                                    <div class="mdl-card__supporting-text p-b-0">
                                        <div class="row-fluid">
                                            <div class="col-xs-12 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-home"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="lugarVivienda" name="lugarVivienda">
                                                   <label class="mdl-textfield__label" for="lugarVivienda">Lugar de Vivienda</label>
                                               </div>
                                           </div>
                                           <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-wc"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="estadoCivil" name="estadoCivil">
                                                   <label class="mdl-textfield__label" for="estadoCivil">Estado civil</label>
                                               </div>
                                           </div>
                                           <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-phone"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="telefono" name="telefono" value="<?php echo $telefonoPersona?>" onchange="cambioInfoContacto()">
                                                   <label class="mdl-textfield__label" for="telefono">Tel&eacute;fono</label>
                                               </div>
                                           </div>
                                           <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-contact_mail"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="emailPersonal" name="emailPersonal" onchange="cambioInfoContacto()">
                                                   <label class="mdl-textfield__label" for="emailPersonal">Correo personal</label>
                                               </div>
                                           </div>
                                           <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon"><i class="mdi mdi-mail"></i></div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="emailInst" name="emailInst" onchange="cambioInfoContacto()">
                                                   <label class="mdl-textfield__label" for="emailInst">Correo institucional</label>
                                               </div>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" id="btnGuardar1" onclick="guardarInformacionContacto()" disabled>Guardar</button>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-info"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-tabs__panel" id="configuracion">
                                <div class="mdl-card">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Cuenta</h2>
                                    </div>                                    
                                    <div class="mdl-card__supporting-text p-b-0">
                                        <div class="row-fluid">
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-account_circle"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="nombreUsuario" name="nombreUsuario" value="<?php echo $nombreUsuario?>">
                                                   <label class="mdl-textfield__label" for="nombreUsuario">Nombre de Usuario</label>
                                               </div>
                                            </div>                                       
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-group_work"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="rolUsuario" name="rolUsuario" disabled>
                                                   <label class="mdl-textfield__label" for="rolUsuario">Rol</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-domain"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="sedeUsuario" name="sedeUsuario" value="<?php echo $sedeTrabajo?>" disabled>
                                                   <label class="mdl-textfield__label" for="sedeUsuario">Sede</label>
                                               </div>
                                            </div>                                          
                                            <div class="col-sm-6 mdl-input-group">
                                                <div class="mdl-icon"></div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="areaUsuario" name="areaUsuario" disabled>
                                                   <label class="mdl-textfield__label" for="areaUsuario">&Aacute;rea</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" disabled>Guardar</button>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-info"></i></button>
                                    </div>
                                </div>
                                
                                <div class="mdl-card m-t-10">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Cambiar contrase&ntilde;a</h2>
                                    </div>                               
                                    <div class="mdl-card__supporting-text p-b-0">
                                        <div class="row-fluid">
                                            <div class="col-sm-4 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-lock"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="password" id="currPassword" name="currPassword">
                                                   <label class="mdl-textfield__label" for="currPassword">Contrase&ntilde;a Actual</label>
                                               </div>
                                            </div>
                                            <div class="col-sm-4 mdl-input-group">
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="password" id="newPassword" name="newPassword">
                                                   <label class="mdl-textfield__label" for="newPassword">Contrase&ntilde;a nueva</label>
                                               </div>
                                            </div>
                                            <div class="col-sm-4 mdl-input-group">
                                                <div class="mdl-icon"></div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="password" id="newPassword2" name="newPassword2">
                                                   <label class="mdl-textfield__label" for="newPassword2">Repetir contrase&ntilde;a nueva</label>
                                               </div>
                                           </div>
                                           
                                        </div>
                                    </div>
                                    <div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="grabarClave();">Guardar</button>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-info"></i></button>
                                    </div>
                                </div>
                                
                                <div class="mdl-card m-t-10">
                                    <div class="mdl-card__title">
                                        <h2 class="mdl-card__title-text">Redes Sociales</h2>
                                    </div>                                    
                                    <div class="mdl-card__supporting-text p-b-0">
                                        <div class="row-fluid">
                                            <div class="col-xs-12 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-facebook"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="facebookUsuario" name="facebookUsuario">
                                                   <label class="mdl-textfield__label" for="facebookUsuario">Facebook</label>
                                               </div>
                                            </div>
                                            <div class="col-xs-12 mdl-input-group">
                                                <div class="mdl-icon">
                                                    <i class="mdi mdi-twitter"></i>
                                                </div>
                                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" id="twitterUsuario" name="twitterUsuario">
                                                   <label class="mdl-textfield__label" for="twitterUsuario">Twitter</label>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-card__actions">
                                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect" disabled>Guardar</button>
                                    </div>
                                    <div class="mdl-card__menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"><i class="mdi mdi-info"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-tabs__panel" id="desempeno">
                            2
                            </div>
                        </div>
                    </div>
                </section>
                
            	<!-- 
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
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="email">Correo Electr�nico</label>
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
                                                   <input class="mdl-textfield__input" type="text" name="nro_doc" id="nro_doc" value="<?php echo $nro_documento?>" maxlength="8">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="usuario">Documento de Identidad</label>
                                               </div>
                	                       </div>
                	                       <div class="col-md-6 col-xs-12">
                	                           <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                   <input class="mdl-textfield__input" type="text" name="telefono" id="telefono" value="<?php echo $telefonoPersona?>" maxlength="200">
                                                   <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="telefono">Tel�fono</label>
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
                	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--indigo" id="btnCambioDatos">GUARDAR</button>
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
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--indigo" onclick="saveInteresesHobby()">Guardar</a>
                                </div>
                	            <?php }?>
            	           </div>
            	       </div> 
            	       
            	       <?php if(!isset($_GET['usuario']) || $_GET['usuario'] == null){?>
            	       <div class="col-xs-12 col-xs-offse-0 col-sm-4 m-b-15   p-r-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">
            	                   <h2 class="mdl-card__title-text">Cambiar contrase�a</h2>
            	               </div>
            	               <form id="formCambioClave" method="post">
            	                   <div class="mdl-card__supporting-text">
            	                       <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="lastPass" id="lastPass">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="lastPass">Contrase�a actual</label>
                                            </div>
                	                   </div>
                	                   <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="newPass" id="newPass">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="newPass">Nueva contrase�a</label>
                                            </div>
                	                   </div>
                	                   <div class="col-md-12 col-xs-12">
                	                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="password" name="newPass2" id="newPass2">
                                                <label class="mdl-textfield__label mdl-color-text--grey-800 mdl-typography--body-1" for="newPass2">Repetir nueva contrase�a</label>
                                            </div>
                	                   </div>
            	                   </div>
            	                   
                	               <div class="mdl-card__actions">
                	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--indigo" >GUARDAR</button>
                	               </div>
            	               </form>
            	           </div>
            	       </div>
            	       <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 m-b-15 p-l-0 p-r-0">
            	           <div class="mdl-card mdl-shadow--2dp">
            	               <div class="mdl-card__title">   
            	                   <h2 class="mdl-card__title-text">Tama�o de letra</h2>
            	               </div>
        	                   <div class="mdl-card__supporting-text">
        	                       <div class="col-md-12 text-center">
            	                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-m" type="submit">A-</button>
            						    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-d" type="submit" onclick="setFont14px()">A</button>
            						    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" id="jfontsize-p" type="submit">A+</button>
            	                   </div>
            	                   <div class="col-md-12">
                                        <p id="contentPrueba" class="mdl-text mdl-color-text--grey-800 mdl-typography--body-1" type="password" name="newPass" id="newPass">"La gente piensa que enfocarse significa decir s� a aquello en lo que te enfocas, pero no es as�. Significa decir no a otras cientos de ideas buenas que hay."</p>
            	                   </div>
        	                   </div>
        	                   
            	               <div class="mdl-card__actions">
            	                   <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--indigo" onclick="saveFontSize()">GUARDAR</button>
            	               </div>
            	           </div>
            	       </div>            	       
            	       <?php }?> 
            	           
            	   </div>
            	</div>-->
    		</main>
    	</div>
    
    	<div class="modal fade" id="elegirModo" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Foto</h2>
    					</div>
    					<div class="mdl-card__supporting-text br-b">
    					   <ul class="demo-list-control mdl-list">
    					       <li class="mdl-list__item p-0">
    					           <span class="mdl-list__item-primary-content">Subir foto</span>
    					           <span class="mdl-list__item-secondary-action">
    					               <button class="mdl-button mdl-js-button mdl-button--icon" onclick="elegirImagen()">
    					                   <i class="mdi mdi-add_a_photo"></i>
    					               </button>
    					           </span>
    					       </li>
    					       <li class="mdl-list__item p-0">
    					           <span class="mdl-list__item-primary-content">Editar foto</span>
    					           <span class="mdl-list__item-secondary-action">
    					               <button class="mdl-button mdl-js-button mdl-button--icon" id="cambiar_foto1" onclick="abrirEditarFoto('<?php echo $this->session->userdata('foto_usuario');?>')">
    					                   <i class="mdi mdi-photo_size_select_large"></i>
    					               </button>
    					           </span>
    					       </li>
    					   </ul>
    					</div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal"><i class="mdi mdi-close"></i></button>
                        </div>
					</div>
				</div>
			</div>
    	</div>
    	
    	
        <div class="modal fade" id="modalEditarFoto" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    		<div class="modal-dialog">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
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
    
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js" defer></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>cropper/tooltip.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>cropper/cropper.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js" charset="UTF-8"></script>
        
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-validator/bootstrapValidator.min.js" charset="UTF-8"></script>
        <script src="<?php echo RUTA_PLUGINS?>fontSize/jquery.jfontsize-1.0.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
    	<script src="<?php echo RUTA_JS?>Utils.js"></script>
    	<script src="<?php echo RUTA_JS?>jslogic/jsperfil.js"></script>
    	<script type="text/javascript">
        	$(document).ready(function(){    
        	    $('main').scroll(function (event) {
        	        var sc = $('main').scrollTop();
        	        var wd = $(window).width();
        	        if ( wd < 721 ) {
            	        if ( sc >= 184 ) {
            	        	$('.mdl-layout__content section').addClass('mdl-scroll');
            	        	$('header.mdl-layout__header').css('box-shadow', 'none');
            	        } else {
            	        	$('.mdl-layout__content section').removeClass('mdl-scroll');
            	        	$('header.mdl-layout__header').removeAttr('style');
            	        }
        	        }
        	    });    
        	})        	
        	
        	returnPage();
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