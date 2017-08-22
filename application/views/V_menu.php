<?php
$saltos = null;
$countSegments = count($this->uri->segment_array()) - 1;
if($countSegments >= 1) {
    for($i = 0; $i < $countSegments; $i++) {
        $saltos .= '../';
    }
}
?>
<header class='mdl-layout__header'>
    <?php if(isset($return)) {?>
         <a class="mdl-button__return" href="<?php echo (!isset($historyBack) ? 'javascript:history.back()' : 'javascript:void(0)')?>" <?php echo $return; ?> ><i class="mdi mdi-arrow_back"></i></a>
    <?php } ?>
	<div class='mdl-layout__header-row'>
		<div class='mdl-layout-title'>
		    <a href="<?php echo isset($rutaSalto) ? '../' : null; ?>c_main">
		        <img alt="Logo" src="<?php echo $ruta_logo_blanco?>"><?php echo (isset($nombre_logo)) ? $nombre_logo : null; ?>
	        </a>
		    <div class="mdl-card__title-text f-s-medium mdl-section-head"><?php echo (isset($titleHeader)) ? $titleHeader : null; ?></div>	
		</div>
		<div class='mdl-layout-spacer'></div>
		<nav class='mdl-navigation'>
		    <?php echo (isset($btnSearch))   ? $btnSearch : null ?>
		    <?php echo (isset($vistaPrevia)) ? $vistaPrevia : null;?>
			<button type="button" class="mdl-button mdl-js-button mdl-button--icon" data-toggle="modal" data-target="#modalApps" id="button_Apps">
		          <i class="mdi mdi-apps"></i>
            </button>
			<a id="notifications" data-toggle="tooltip" data-placement="bottom" data-original-title="Pr&oacute;ximamente"
				class="mdl-button mdl-js-button mdl-button--icon"> <i
				class="mdi mdi-notifications"></i>
			</a>

			<a id="profile" class="mdl-button mdl-js-button mdl-button--icon"> <img alt="Logo" name="foto_user_menu" src="<?php echo _getSesion('foto_usuario');?>" class="img-responsive">
			</a>
		</nav>
	</div>
	<?php echo (isset($barraSec)) ? $barraSec : null?>
</header>
<?php echo (isset($inputSearch)) ? $inputSearch : null ?>

<div class="screen-load">
    <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
</div>

<div id="modalApps" class="modal fade" tabindex="-1" style="z-index:33">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="mdl-card" >
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Ingreso r&aacute;pido</h2>
                </div>
                <div class="mdl-card__supporting-text p-tb-0 text-center">
                    <?php echo (isset($apps)) ? $apps : null?>
                </div>
                <div class="mdl-card__actions">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                </div>
                <div class="mdl-card__menu btn_close">
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>  
            </div>                    
        </div>
    </div>
</div>

<!-- <div class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="notifications" style="padding: 5px;">
	<div class="mdl-card mdl-shadow--2dp">
		<div class="mdl-card__title">
			<h2 class="mdl-card__title-text">Notificaci&oacute;n</h2>
		</div>
		<div class="mdl-card__supporting-text">Descripci&oacute;n.</div>
	</div>
</div>-->

<!-- this is the off canvas menu-->
<div id="navBar" class='mdl-layout__drawer'>
	<span class="mdl-layout-title"><a href="c_main"><img src="<?php echo $ruta_logo?>"> <?php echo (isset($nombre_logo)) ? $nombre_logo : null; ?></a>
	</span>
	<nav class="mdl-navigation mdl-nav">
		<ul class="mdl-nav">
		    <?php echo $arbolPermisosMantenimiento; ?>
			<li class="separator">
			    <a href="javascript:void(0);" onclick="openModalFeedBack()"><i class="mdi mdi-feedback"></i>
					Sugerencias
			    </a>
			</li>
			<li><a href="javascript:void(0);" onclick="toggleFullScreen()">
					Pantalla completa <i id="icon_fullScreen" class="mdi mdi-fullscreen"></i>
			</a></li>
		</ul>
	</nav>
	<div class="copy_footer">
	   <img src="<?php echo RUTA_IMG?>menu/logo_smiledu_gris.png">
	   <span><a href="http://smiledu.pe/" target="_blank">Smiledu</a> &reg;</span>
	</div>
</div>

<div id="navProfile" class='mdl-layout__drawer--right'>
		    <div id="snow" style="z-index: 3;"></div>
		    <div class="box-header">
		    </div>
   	<div class="mdl-layout-title" style="padding: 10px 20px; width: 100%;">
	   	<div class="f_perfil">
    	   	<a href="<?php echo $saltos;?>c_perfil"><img name="foto_user_menu" src="<?php $num = rand(0, 10); echo _getSesion('foto_usuario').'?lastmod='.$num;?>"
    		   style="height:55px;width: 55px; border-radius: 50%;"></a>
    		    <p style="font-size: 14px; margin-top: 10px; margin-bottom: 0; color: #FFF;">
    			    <strong><?php echo _getSesion('nombre_abvr');?></strong>
    		    </p>
        </div>
	    <div class="progress" style="display:none">
            <div class="progress-bar progress-bar-success progress-bar-striped"  role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                <span class="sr-only">40% Complete (success)</span>
            </div>                        			         
        </div>
	</div>
   	<nav class="mdl-navigation" style="z-index:4">
		<ul class="mdl-nav">
			<li class="" data-toggle="modal" data-target="#all-apps"><a href="javascript:void(0);"><i class="mdi mdi-apps"></i> Apps </a></li>		  
			<li style="display:none" class="separator"><a href="#"><i class="mdi mdi-favorite"></i> 12 </a></li>
			<li style="display:none" class=""><a href="#"><i class="mdi mdi-flash_on"></i> 20 / 100 exp </a></li>
			<li style="display:none" class=""><a href="#"><i class="mdi mdi-book"></i> 5 posts </a></li>			
			<li class="separator"><a id="logoutBtn" onclick="cerrarSesion()" href="javascript:void(0);"><i class="mdi mdi-exit_to_app"></i> Cerrar sesi&oacute;n</a></li>								
		</ul>
	</nav>
</div>

<div class="modal fade" id="modalFeedBack" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">                
            <div class="mdl-card" >
                <div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Ay&uacute;danos a mejorar</h2>
				</div>
			    <div class="mdl-card__supporting-text">    				
			       <div class="row p-0 m-0">
			           <div class="col-sm-12 p-0 m-b-15">
			               <img src="<?php echo RUTA_IMG?>/smiledu_faces/smiledu_feedback.png" id="img_feedback">
			           </div>
		               <div class="col-sm-12 p-0 m-b-15">
		                    <label>Nos encanta ayudarte, inf&oacute;rmanos sobre alguna duda, ideas nuevas o tus comentarios</label>
			                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <textarea class="mdl-textfield__input" type="text" id="feedbackMsj" name="feedbackMsj" rows="3"></textarea>       
                                <label class="mdl-textfield__label" for="feedbackMsj">Com&eacute;ntanos</label>                            
                            </div>
		               </div>
			       </div>
				</div>
				<div class="mdl-card__actions">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cancelar</button>
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="enviarFeedback()">Enviar</button>
                </div>
                <div class="mdl-card__menu">
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>     
</div>

<div id="modalSubirPaquete" class="modal fade modalPaquetes" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <div class="modal-content modal-notificacion">
            <div class="mdl-card bg_notificacion" >
                <div class="mdl-card__title bg_notificacion">
                    <div class="img-search ">
                        <img class="img_smiledu_free" id="smiledu-free" src="<?php echo RUTA_IMG?>smiledu_faces/smiledu_free.png">
                    </div>
                </div>
                <div class="mdl-card__supporting-text bg_notificacion">
                  <div class="contenedor">
                    <div class="img-search cont_paquete">    
                        <h3>Espera!</h3>   
                        <p>A&uacute;n no terminamos de construir</p> 
                        <p>esta funcionalidad.</p>
                    </div>
                    <div  id="contentPaquete" class="img-search cont_sede">
                        <h2 class="mdl-card__title-text"></h2>
                        <p>Para poder hacer uso de esta opci&oacute;n</p>
                        <p>debemos esperar un poquito m&aacute;s.</p>
                    </div>                                
                    <div class="mdl-card__actions">
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect comp_btn btn_modal btn-share">Compartir</button>
                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect comp_btn icon-share">
                            <i class="mdi mdi-share"></i>
                        </button>
                  
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised btn_modal-aceptar" data-dismiss="modal">Mas Informaci&oacute;n</button>
                    </div>
                </div> 
                <div class="mdl-card__menu btn_close">
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>               
               </div>  
            </div>                    
        </div>
    </div>
</div>

<div id="modalPasarelaPago" class="modal fade modalPaquetes" tabindex="-1" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <div class="modal-content modal-notificacion">
            <div class="mdl-card bg_notificacion" >
                <div class="mdl-card__title bg_notificacion">
                </div>
                <div class="mdl-card__supporting-text bg_notificacion">
                  <div class="contenedor">         
                      <div  id="contentPaquete" class="img-search cont_pasarela">
                          <h2 class="mdl-card__title-text">Pronto podr&aacute;s hacer tus pagos desde aqu&iacute;</h2>
                          <p>Para poder hacer uso de esta opci&oacute;n</p>
                          <p>debemos esperar un poquito m&aacute;s.</p>
                          <div class="img-search img-pasarela m-t-20" style="padding:0; margin:0">
                               <img class="img_smiledu_free" id="smiledu-free" src="<?php echo RUTA_IMG?>bancos/pasarela_pagos.png">
                          </div>                                               
                      </div> 
                      <div class="mdl-card__actions p-t-20">
                          <button class="mdl-button mdl-js-button mdl-js-ripple-effect " disabled data-dismiss="modal">Cerrar</button>
                          <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" disabled data-dismiss="modal">Siguiente</button>
                      </div>
                                        <div class="mdl-card__menu btn_close_pasarela">
                      <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                         <i class="mdi mdi-close"></i>
                      </button>
                  </div>
                  </div> 
             
               </div>  
            </div>                    
        </div>
    </div>
</div>

<div id="all-apps" class="modal fade" tabindex="-1" style="z-index:33">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="mdl-card" >
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text">Ingreso r&aacute;pido</h2>
                </div>
                <div class="mdl-card__supporting-text p-tb-0 text-center">
                    <?php echo (isset($apps)) ? $apps : null?>
                </div>
                <div class="mdl-card__actions">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Cerrar</button>
                </div>
                <div class="mdl-card__menu btn_close">
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>  
            </div>                    
        </div>
    </div>
</div>

<div class="modal fade" id="modalLeyendaAlumno" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content-cards">                
            <div class="mdl-card" >
                <div class="mdl-card__title">
					<h2 class="mdl-card__title-text">Leyenda de estados</h2>
				</div>
			    <div class="mdl-card__supporting-text">
			        <ul class="list-group">
                        <li class="list-group-item"><span class="state mt datos-incompletos" style="padding-left: 11px;padding-right: 11px;"></span>Datos Incompletos</li>
                        <li class="list-group-item"><span class="state mt pre-registro"></span>Pre Registro</li>
                        <li class="list-group-item"><span class="state mt registrado"></span>Registrado</li>
                        <li class="list-group-item"><span class="state mt matriculable"></span>Matriculable</li>
                        <li class="list-group-item"><span class="state mt matriculado"></span>Matriculado</li>
                        <li class="list-group-item"><span class="state mt promovido"></span>Promovido</li>
                        <li class="list-group-item"><span class="state mt no-promovido"></span>No Promovido</li>
                        <li class="list-group-item"><span class="state mt egresado"></span>Egresado</li>
                        <li class="list-group-item"><span class="state mt retirado"></span>Retirado</li>
                        <li class="list-group-item"><span class="state mt no-promovido-nivelacion" style="padding-left: 15px;padding-right: 15px;"></span>No Promovido-Nivelaci&oacute;n</li>
                        <li class="list-group-item"><span class="state mt verano"></span>Verano</li>
                    </ul>
				</div>
				<div class="mdl-card__actions">
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" data-dismiss="modal">Entend&iacute;</button>
                </div>
                <div class="mdl-card__menu">
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal"><i class="mdi mdi-close"></i></button>
                </div>
            </div>
        </div>
    </div>     
</div>  

<form action="<?php echo $saltos; ?>c_main/cerrar" name="formLogout" id="formLogout" method="post"></form>