<!DOCTYPE html>
<html lang="en">
    <head>      
        <script type="text/javascript">
             var timeInit = performance.now();             
        </script>
        <title>Detalle de curso | <?php echo NAME_MODULO_NOTAS;?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_NOTAS;?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo MENU_LOGO_NOTAS;?>"/>
        
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-3.3.6-dist/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/css/calendar.min.css"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>fullscreen-select/bootstrap-fullscreen-select.css"/>
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>b_select/css/bootstrap-select.min.css">
		<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.css" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>datetimepicker/css/bootstrap-material-datetimepicker.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>floating-button/mfb.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>toaster/toastr.css">
    	<link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>pace/pace_css.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>paper-collapse/paper-collapse.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS?>rippleria-master/css/jquery.rippleria.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>roboto.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS?>material-icons.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>menu.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>m-p.css">    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS?>mdl-card-style.css">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/submenu.css">    
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PUBLIC_NOTAS?>css/detalle_cursos.css">
                
	</head>
	<body onload="screenLoader(timeInit);">
    	<div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
    		<?php echo $menu ?>
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel p-0 is-active" id="tab-1">
                    <div class="mdl-filter">
    					<div class="mdl-content-cards">
    					   <div class="col-sm-3 col-xs-6 m-0 mdl-input-group mdl-input-group__only">
    					       <div class="mdl-select">
                                    <select id="cmbCompetencias" name="cmbCompetencias" class="form-control"
                                            data-title="Selec. Competencia" data-btntext-save="Seleccionar" data-btntext-cancel="Cancelar">
                                        <option value="">Selec. Competencia</option>
                                        <?php echo isset($cmbCompetencias) ? $cmbCompetencias : null;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6 m-0 mdl-input-group mdl-input-group__only">
                                <div class="mdl-select">
                                    <select id="cmbCapacidad" name="cmbCapacidad" class="form-control"
                                            data-title="Selec. Capacidad" data-btntext-save="Seleccionar" data-btntext-cancel="Cancelar">
                                        <option value="">Selec. Capacidad</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6 m-0 mdl-input-group mdl-input-group__only">
                                <div class="mdl-select">
                                    <select id="cmbIndicador" name="cmbIndicador" class="form-control" data-title="Selec. Indicador"
                                            data-btntext-save="Seleccionar" data-btntext-cancel="Cancelar">
                                        <option value="">Selec. Indicador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6 m-0 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                <div class="mdl-select p-t-0">
                                    <select id="cmbInstrumento" name="cmbInstrumento" class="form-control" data-title="Selec. Instrumento"
                                            data-btntext-save="Seleccionar" data-btntext-cancel="Cancelar">
                                        <option value="">Selec. Instrumento</option>
                                    </select>
                                </div>
                                <div class="mdl-btn">
    					             <button class="mdl-button mdl-js-button mdl-button--icon" onclick="openModalInstrumentos();">
                                        <i class="mdi mdi-add"></i>
                                     </button>
                                </div>
                             </div>
                        </div>
        			</div>
        			
                    <div class="mdl-content-cards" id="cont_estudiantes">
                        <?php echo isset($estuAsist) ? $estuAsist : null; ?>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-2">
                    <div class="mdl-filter">
    					<div class="mdl-content-cards">
    					   <ul class="nav nav-pills">
                              <li class="miniTabCalend active"><a href="#tab-calendar" data-toggle="tab">Calendario</a></li>
                              <li class="miniTabAsist"><a href="#tab-graphics" data-toggle="tab" id="btnTabGrafiAsit">Gr&aacute;ficas</a></li>
                           </ul>  
    					</div>
					</div>
                    <div class="mdl-content-cards">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-calendar">
                                <div class="row">
                                    <div class="col-xs-12 text-right p-0 m-0">
                                        <div class="mdl-card mdl-calendar">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
                                            </div>
                                            <div class="mdl-card__supporting-text br-b p-r-5 p-l-5">
                                                <div id="calendar" class="m-b-20"></div>
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="prev">
                                                    <i class="mdi mdi-keyboard_arrow_left"></i>
                                                </button>
                                               	<button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="next">
                                               	    <i class="mdi mdi-keyboard_arrow_right"></i>
                                           	    </button>
                                           	    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-nav="today">Hoy</button>
                                              	<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="month">Mes</button>
                                               	<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="week">Semana</button>
            									<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="day">D&iacute;a</button>
            									<button class="mdl-button mdl-js-button mdl-button--icon" data-button-type="menu" id="more-calendar">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="more-calendar">
                                                    <li class="mdl-menu__item" data-calendar-nav="today">Hoy</li>
                                                    <li class="mdl-menu__item" data-calendar-view="month">Mes</li>
                                                    <li class="mdl-menu__item" data-calendar-view="week">Semana</li>
                                                    <li class="mdl-menu__item" data-calendar-view="day">D&iacute;a</li>
                                                </ul>
        									</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-graphics">
                                <div class="row p-0 m-0" >
                                    <div class="col-md-6">
                                        <div class="mdl-card">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Gr&aacute;fico 1</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text br-b">
                                                <div id="cont_graf_asist"></div>
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-filter_list"></i>
                                                </button>
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect refresh_graf" data-graf="1">
                                                    <i class="mdi mdi-refresh"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mdl-card">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Gr&aacute;fico 2</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text br-b">
                                                <div id="cont_graf_linea_asist"></div>
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-filter_list"></i>
                                                </button>
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect refresh_graf" data-graf="2">
                                                    <i class="mdi mdi-refresh"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mdl-card ">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Gr&aacute;fico 3</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text br-b">
                                                <div id="cont_graf_barr_sexo"></div>
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-filter_list"></i>
                                                </button>
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect refresh_graf" data-graf="3">
                                                    <i class="mdi mdi-refresh"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mdl-card ">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Gr&aacute;fico 4</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text br-b">
                                                <div id="cont_heat_map"></div>
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-filter_list"></i>
                                                </button>
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect refresh_graf" data-graf="4">
                                                    <i class="mdi mdi-refresh"></i>
                                                </button>
                                            </div>
                                        </div>                                                    
                                    </div>
                                </div>                                            
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="mdl-layout__tab-panel p-0" id="tab-3">
                    <div class="page-content">
                        <div class="img-search">
                            <img src="<?php echo base_url()?>public/general/img/smiledu_faces/select_empty_state.png">
                            <p><strong>Hey!</strong></p>
                            <p>Primero debes de filtar</p>
                            <p>a un estudiante.</p>
                        </div>
                        <div class="row p-0 m-0 barra_info_estu" style="display: none;">
                            <div class="mdl-filter">
            					<div class="mdl-content-cards mdl-content__overflow">
                                    <ul class="nav nav-pills">
                                        <li class="active"><a href="#tab-profile" data-toggle="tab" >Bio</a></li>
                                        <li ><a href="#tab-notes" data-toggle="tab" >Notas</a></li>
                                        <li ><a href="#tab-assitence" data-toggle="tab" >Asistencia</a></li>
                                        <li ><a href="#tab-courses" data-toggle="tab" >Cursos</a></li>
                                        <li ><a href="#tab-messages" data-toggle="tab" >Mensajes</a></li>
                                    </ul>  
            					</div>
        					</div>
        					<div class="mdl-content-cards p-rl-16">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-profile">
                                        <div class="mdl-card m-b-10">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Perfil</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text">
                                                <div class="row-fluid">
                                                    <div class="col-sm-6 col-md-4 text-center">
                                                        <img src="<?php echo RUTA_SMILEDU.FOTO_PATH_ESTUDIANTE.(isset($foto) ? $foto : "default_avatar_user.png")?>"  class="mdl-img" id="fotoPersonaImg" name="fotoPersonaImg">
                                                        <input type="file" id="fotoPersona" name="fotoPersona" accept="image/*" style="display:none">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-account_circle"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="nombrePersona" name="nombrePersona" maxlength="60" disabled>
                                                            <label class="mdl-textfield__label" for="nombrePersona">Nombre(s)(*)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="apePaterno" name="apePaterno" maxlength="60" disabled>
                                                            <label class="mdl-textfield__label" for="apePaterno">Apellido Paterno(*)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="apeMaterno" name="apeMaterno" maxlength="60" disabled>
                                                            <label class="mdl-textfield__label" for="apeMaterno">Apellido Materno(*)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-chrome_reader_mode"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="dniPersona" name="dniPersona" maxlength="20" disabled>
                                                            <label class="mdl-textfield__label" for="dniPersona">Nro. Doc.(*)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-chrome_reader_mode"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="docExtranjeriaPersona" name="docExtranjeriaPersona" maxlength="12" disabled>
                                                            <label class="mdl-textfield__label" for="docExtranjeriaPersona">Doc. Extranjeria</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-date_range"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="fecNacPersona" name="fecNacPersona" data-inputmask="'alias': 'date'" maxlength="10" disabled>
                                                            <label class="mdl-textfield__label" for="fecNacPersona">Fecha Nacimiento(*)</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-contact_phone"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="tlfPersona" name="tlfPersona" maxlength="30" disabled>
                                                            <label class="mdl-textfield__label" for="tlfPersona">Tel&eacute;fono</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-location_on"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="pais" name="pais" maxlength="40" disabled>
                                                            <label class="mdl-textfield__label" for="pais">Pa&iacute;s</label>
                                                        </div>
                                                     </div>
                                                     <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="lugar_naci" name="lugar_naci" maxlength="100" disabled>
                                                            <label class="mdl-textfield__label" for="lugar_naci">Lugar de Nac.</label>
                                                        </div>
                                                     </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">                                                
                                                            <i class="mdi mdi-wc"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="sexoEstu" name="sexoEstu" maxlength="20" disabled>
                                                            <label class="mdl-textfield__label" for="sexoEstu">Sexo</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">                                
                                                            <i class="mdi mdi-email"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="correoPersona" name="correoPersona" maxlength="100" disabled>
                                                            <label class="mdl-textfield__label" for="correoPersona">Correo Electr&oacute;nico</label>                            
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">
                                                        <div class="mdl-icon">             
                                                            <i class="mdi mdi-account_balance"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="religion" name="religion" maxlength="30" disabled>
                                                            <label class="mdl-textfield__label" for="religion">Religi&oacute;n</label>                            
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4 mdl-input-group">             
                                                        <div class="mdl-icon">
                                                            <i class="mdi mdi-child_friendly"></i>
                                                        </div>
                                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                            <input class="mdl-textfield__input" type="text" id="estado_civil" name="estado_civil" maxlength="30" disabled>
                                                            <label class="mdl-textfield__label" for="estado_civil">Estado Civil</label>                            
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                            <div class="mdl-card__actions">
                                            
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mdl-card m-b-10">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Formaci&oacute;n</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text p-0">
                                                <div id="divContHistoriaEstu"></div>
                                            </div>
                                            <div class="mdl-card__actions">
                                            
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mdl-card m-b-10">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Familiares</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text p-0">
                                                <div id="divContFamiliares"></div>
                                            </div>
                                            <div class="mdl-card__actions">
                                            
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-notes">
                                        <div class="mdl-card mdl-shadow--2dp">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Notas</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text">
                                            
                                            </div>
                                            <div class="mdl-card__actions">
                                            
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-assitence">
                                        <div class="row p-0 m-0" >
                                            <div class="col-md-7">
                                                <div class="mdl-card m-b-10">
                                                    <div class="mdl-card__title">
                                                        <h2 class="mdl-card__title-text">Hist&oacute;rico</h2>
                                                    </div>
                                                    <div class="mdl-card__supporting-text">
                                                        <div id="cont_linea_regre"></div>
                                                    </div>
                                                    <div class="mdl-card__actions">
                                                    
                                                    </div>
                                                    <div class="mdl-card__menu">
                                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                            <i class="mdi mdi-more_vert"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="mdl-card m-b-10">
                                                    <div class="mdl-card__title">
                                                        <h2 class="mdl-card__title-text">Desempe&ntilde;o</h2>
                                                    </div>
                                                    <div class="mdl-card__supporting-text">
                                                        <div id="cont_radar"></div>
                                                    </div>
                                                    <div class="mdl-card__actions">
                                                    
                                                    </div>
                                                    <div class="mdl-card__menu">
                                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                            <i class="mdi mdi-more_vert"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row p-0 m-0" >
                                            <div class="col-md-5">
                                                <div class="mdl-card m-b-10">
                                                    <div class="mdl-card__title">
                                                        <h2 class="mdl-card__title-text">Detalle</h2>
                                                    </div>
                                                    <div class="mdl-card__supporting-text">
                                                        <div id="cont_graf_asist_estu"></div>
                                                    </div>
                                                    <div class="mdl-card__actions">
                                                    
                                                    </div>
                                                    <div class="mdl-card__menu">
                                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                            <i class="mdi mdi-more_vert"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="mdl-card m-b-10">
                                                    <div class="mdl-card__title">
                                                        <h2 class="mdl-card__title-text">Ranking</h2>
                                                    </div>
                                                    <div class="mdl-card__supporting-text p-0">
                                                        <div id="divContRankEstu"></div>
                                                    </div>
                                                    <div class="mdl-card__actions">
                                                    
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
                                    <div class="tab-pane" id="tab-courses">
                                        <div class="mdl-card">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Cursos</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text p-0">
                                                <div id="contCursosEstu"></div>
                                            </div>
                                            <div class="mdl-card__actions">
                                            
                                            </div>
                                            <div class="mdl-card__menu">
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                                    <i class="mdi mdi-more_vert"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-messages">
                                        <div class="mdl-card">
                                            <div class="mdl-card__title">
                                                <h2 class="mdl-card__title-text">Mensajes</h2>
                                            </div>
                                            <div class="mdl-card__supporting-text">
                                            
                                            </div>
                                            <div class="mdl-card__actions">
                                            
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
                    </div>
                </section>
            </main>
        </div>
        
        <div class="modal fade backModal" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text"></h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid" id="contAsistentes">
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalSelecEstudiante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content b-o">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Estudiantes</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0">
                            <div class="row-fluid" id="contEstudiantes">
                                <?php echo isset($estudiantes) ? $estudiantes : null; ?>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMSE" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="selectEstuData();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade backModal" id="modalAsistencia" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text"></h2>
    					</div>
					    <div class="mdl-card__supporting-text text-center br-b">    				
					       <ul class="ul-assistance">
					           <li onclick="guardarAsistencia($(this));" data-asistencia="<?php echo _encodeCI(ASISTENCIA_PRESENTE);?>">
					               <div></div>
					               <small>Asisti&oacute;</small>
					           </li>
					           <li onclick="guardarAsistencia($(this));" data-asistencia="<?php echo _encodeCI(ASISTENCIA_TARDE);?>">
					               <div></div>
					               <small>Tardanza</small>
					           </li>
					           <li onclick="guardarAsistencia($(this));" data-asistencia="<?php echo _encodeCI(ASISTENCIA_TARDE_JUSTIF);?>">
					               <div></div>
					               <small>Tardanza justificada</small>
					           </li>
					           <li onclick="guardarAsistencia($(this));" data-asistencia="<?php echo _encodeCI(ASISTENCIA_FALTA);?>">
					               <div></div>
					               <small>Falt&oacute;</small>
					           </li>
					           <li onclick="guardarAsistencia($(this));" data-asistencia="<?php echo _encodeCI(ASISTENCIA_FALTA_JUSTIF);?>">
					               <div></div>
					               <small>Falta justificada</small>
					           </li>
					       </ul>
    					</div>
                    </div>
                </div>
            </div>     
        </div>  
        
        <div class="modal fade backModal" id="modalCalificar" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nombre del estudiante</h2>
    					</div>
					    <div class="mdl-card__supporting-text br-b">    				
					       <ul class="ul-assistance">
					           <li>
					               <div></div>
					               <small>Asisti&oacute;</small>
					           </li>
					           <li>
					               <div></div>
					               <small>Tardanza</small>
					           </li>
					           <li>
					               <div></div>
					               <small>Tardanza justificada</small>
					           </li>
					           <li>
					               <div></div>
					               <small>Falt&oacute;</small>
					           </li>
					           <li>
					               <div></div>
					               <small>Falta justificada</small>
					           </li>
					       </ul>
    					</div>
                    </div>
                </div>
            </div>     
        </div>       
        
        <div class="modal fade backModal" id="modalAvances" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                
                    <div class="mdl-card" >
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Nombre del curso ( Aula - Seccion ) </h2>
    					</div>
					    <div class="mdl-card__supporting-text">    		
					    
    					</div>
    					<div class="mdl-card__menu">
                           <button class="mdl-button mdl-js-button mdl-button--icon">
                               <i class="mdi mdi-edit"></i>
                           </button>
                           <button class="mdl-button mdl-js-button mdl-button--icon">
                               <i class="mdi mdi-mail"></i>
                           </button>
                           <button class="mdl-button mdl-js-button mdl-button--icon">
                               <i class="mdi mdi-print"></i>
                           </button>
                       </div>
                    </div>
                </div>
            </div>     
        </div>
        
        <div class="modal fade backModal" id="modalFiltroFechas" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Filtrar asistencia con fechas</h2>
    					</div>
					    <div class="mdl-card__supporting-text">
					        <div class="row m-0 p-0">
					            <div class="col-sm-12 mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
                                    	<button class="mdl-button mdl-js-button mdl-button--icon" id="inconFecIni">
			                                 <i class="mdi mdi-today"></i>
		                                </button>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="fecIni" name="fecIni" maxlength="10">  
                                       <label class="mdl-textfield__label" for="fecIni">Fecha Inicio</label>
                                    </div>                                           
                                </div>
					            <div class="col-sm-12 mdl-input-group">
                                    <div class="mdl-icon mdl-icon__button">
                                    	<button class="mdl-button mdl-js-button mdl-button--icon" id="inconFecFin">
			                                 <i class="mdi mdi-today"></i>
		                                </button>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                       <input class="mdl-textfield__input" type="text" id="fecFin" name="fecFin" maxlength="10">  
                                       <label class="mdl-textfield__label" for="fecFin">Fecha Fin</label>
                                    </div>                                           
                                </div>
					        </div>
    					</div>
    					<div class="mdl-card__actions">
    					   <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                           <button id="btnMFF" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="filtrarAsistenciasGraphs();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>

        <div class="modal fade" id="modalAsigInstrumento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Asignar instrumento</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-0 p-b-10">
                            <div class="row p-0 m-0">
                                <div class="col-sm-6 col-md-8 m-0 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="concepto" name="concepto">
                                        <label class="mdl-textfield__label" for="concepto">Concepto de evaluaci&oacute;n</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 m-0 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-select" style="padding-top: 22.5px">
                                        <select id="cmbCicloAcad" name="cmbCicloAcad" class="form-control pickerButn" data-live-search="true" title="Selec. Ciclo Acad.">
                                            <option value="">Selec. Ciclo Acad.</option>
                                            <?php echo isset($cmbCicloAcad) ? $cmbCicloAcad : null;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                <div class="mdl-tabs__tab-bar">
                                    <a href="#instru_favs" class="mdl-tabs__tab is-active">Mis favoritos</a>
                                    <a href="#instru_usando" class="mdl-tabs__tab">Los uso</a>
                                    <a href="#instru_buscar" class="mdl-tabs__tab">Buscar</a>
                                </div>
                                <div class="mdl-tabs__panel is-active" id="instru_favs">
                                    <div class="row p-0 m-0">
                                        <div id="contFavoritosTb"></div>
                                    </div>
                                </div>
                                <div class="mdl-tabs__panel" id="instru_usando">
                                    <div class="row p-0 m-0">
                                        <div id="contTbUsados"></div>
                                    </div>
                                </div>
                                <div class="mdl-tabs__panel" id="instru_buscar">
                                    <div class="row p-rl-0 m-0">                            
                                        <div class="col-sm-12 m-0 mdl-input-group mdl-input-group__text-btn p-rl-16">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="buscar" name="buscar" maxlength="60" onkeyup="buscarInstrumentos(event);">
                                                <label class="mdl-textfield__label" for="buscar">Buscar</label>
                                            </div>
                                            <div class="mdl-btn">
                					             <button class="mdl-button mdl-js-button mdl-button--icon" onclick="buscarInstrumentos();">
                                                    <i class="mdi mdi-search"></i>
                                                 </button>
                                            </div>
                                        </div>
                                        <div id="contBusqInst"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnMAI" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="asignarInstrumento();">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalInstrumento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title">
    						<h2 class="mdl-card__title-text">Instrumento</h2>
    					</div>
					    <div class="mdl-card__supporting-text p-0 p-b-10">
					        <div class="row p-0 m-0">
					           <div class="col-sm-12" id="contInstrum"></div>
					        </div>
    					</div>
    					<div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <h2 class="mdl-card__title-text custom-toolbar" style="display: inline-block;">
								Nota final:&nbsp;
								<strong id="notaFinal"></strong>
							</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAwards" data-live-search="true" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__title titulo_tutor_css">
                            <h2 class="mdl-card__title-text">Dar premios</h2>
					    </div>
					    <div class="mdl-card__supporting-text p-0">
					       <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
    					      <div class="mdl-tabs__tab-bar">
                                  <a href="#tab_positivos"  class="mdl-tabs__tab is-active"  onclick="addMdlAwards()">Positivos</a>
                                  <a href="#tab_nece_mejor" class="mdl-tabs__tab"            onclick="addMdlAwards()">Necesitan mejorar</a>
                                  <a href="#tab_historia"   class="mdl-tabs__tab"                                    >Hist&oacute;rico</a>
                              </div>
                               <div class="mdl-tabs__panel m-0 p-0 is-active" id="tab_positivos">
                                   <div class="p-0 m-0 text-center" id="cont_awards_positive">
                                   </div>
    					       </div>
    					       <div class="mdl-tabs__panel m-0 p-0" id="tab_nece_mejor">
                                   <div class="p-0 m-0 text-center" id="cont_awards_negatives">
                                   </div>
    					       </div>
    					       <div class="mdl-tabs__panel" id="tab_historia">
        					       <div class="row p-0 m-0" id="contTabHistAward">
        					       </div>
    					       </div>
					       </div>
					    </div>
					    <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="refreshHistorAward(1)"><i class="mdi mdi-autorenew"></i></button>
                        </div>
				    </div>
                </div>
            </div>
         </div>

        <ul id="menu" class="mfb-component--br mfb-zoomin display-none" data-mfb-toggle="hover">
            <li class="mfb-component__wrap">
                <button class="mfb-component__button--main" data-plus-minifab="1">
                    <i class="mfb-component__main-icon--resting mdi mdi-add"></i>
                </button>
                <?php if($cant_asist == 0) { ?>
                    <button class="mfb-component__button--main" data-mfb-label="Asistencia" onclick="setAsistenciasTemprano();">
                        <i class="mfb-component__main-icon--active mdi mdi-assignment_parent"></i>
                    </button>
                <?php } else { ?>
                    <button class="mfb-component__button--main" data-toggle="modal" data-mfb-label="Calificar" onclick="calificarFab();">
                        <i class="mfb-component__main-icon--active mdi mdi-new_family"></i>
                    </button>
                <?php } ?>
                <ul class="mfb-component__list">
                    <li>
                        <button class="mfb-component__button--child" data-mfb-label="Avances calificaciones" data-toggle="modal" data-target="#modalAvances">
                            <i class="mfb-component__child-icon mdi mdi-insert_chart"></i>
                        </button>
                    </li>
                </ul>
            </li>
        </ul>

		<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js"></script>
        <script src="<?php echo RUTA_JS?>libs/bootstrap/bootstrap.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>jquery-ui/js/jquery-ui.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>b_select/js/i18n/defaults-es_CL.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>floating-button/mfb.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>toaster/toastr.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/language/es-ES.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>bootstrap-calendar-master/js/calendar.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table.min.js"></script>
    	<script src="<?php echo RUTA_PLUGINS?>bTable/bootstrap-table-es-MX.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>moment/moment.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>pace/pace.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>inputmask/jquery.inputmask.bundle.min.js"></script>        
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highstock.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/highcharts-more.js"></script>        
    	<script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/exporting.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>highcharts/js/modules/heatmap.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>paper-collapse/paper-collapse.min.js"></script>        
        <script src="<?php echo RUTA_PLUGINS;?>jquery-mask/jquery.mask.min.js"></script>
        <script src="<?php echo RUTA_PLUGINS?>fullscreen-select/bootstrap-fullscreen-select.js"></script>        
        <script src="<?php echo RUTA_JS?>Utils.js"></script>
        <script src="<?php echo RUTA_JS?>jsmenu.js"></script>
        <script src="<?php echo RUTA_PUBLIC_NOTAS?>js/jsdetalleCurso.js"></script>
        
        <script>
        	returnPage();
            $(":input").inputmask();
            var firstLoad          = null;
            var firstLoadGrafAsist = null;
            var $estadoFabGlobal   = 'CALIFICAR';//ASISTENCIA
            tabActionsChange();
            initDetalleCurso();
            var asistenciaMarcadaGlobal = null;
            <?php if($cant_asist > 0) { ?>
                      asistenciaMarcadaGlobal = 1;
            <?php } ?>
        </script>
	</body>
</html>