<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('_createCardFamilias')) {
    function _createCardFamilias($grupo, $opcMenu = null, $pantalla = null, $btnEditar) {
        $CI =& get_instance();
        $cons_cod_grupo        = null;
        $cons_cont_hijos       = null;
        $cons_cont_parientes   = null;
        $hijos                 = null;
        $parientes             = null;
        $first                 = null;
        $firstPariente         = null;
        $activeCont            = null;
        $cardsFamilia          = null;
        $data['max_cod_grupo'] = null;
        $contInvitados         = 0;
    
        foreach ($grupo as $grp){
            $idContactoEncryptado = _simple_encrypt($grp->id_contacto);
    
            if($cons_cod_grupo == null){
                $cons_cod_grupo = $grp->cod_grupo;
            }
    
            if($cons_cod_grupo == $grp->cod_grupo){//Misma familia
                    if($pantalla == ID_PERMISO_CONTACTOS){
                        $contInvitados = $contInvitados + $grp->invitados;
                    }
                if($grp->flg_estudiante == 1){
                    $active     = '';
                    $activeCont = '';
                    if($first == 0){
                        $active     = ' class="active"';
                        $activeCont = ' in active';
                    }
                    $detalleEval = null;
                    if($pantalla == ID_PERMISO_EVALUACION){
                        $detalleEval = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verDiagnosticoTabla(\''.$idContactoEncryptado.'\', \''.$grp->nombrecompleto.'\')">
                                            <i class="mdi mdi-timeline" style="color: #FFFFFF; font-size: 18px"></i>
                                        </button>';
                    }
    
                    $first++;
                    $codGrupoEncryptado = _simple_encrypt($grp->cod_grupo);
                    $hijos.='<li'.$active.' data-id-contacto='.$idContactoEncryptado.' data-cod-grupo='.$codGrupoEncryptado.'>
                                     <a data-toggle="pill" href="#child-'.$grp->id_contacto.'">
                                         <img alt="Estudiante" src="'.RUTA_IMG.'profile/nouser.svg">
                                     </a>
                                 </li>';
                    $icono = '';
                    $cons_cont_hijos.= '<div id="child-'.$grp->id_contacto.'" class="tab-pane fade'.$activeCont.'">
                                                <div class="row">
                                                    <div class="col-xs-12 inscrito-title">'.$grp->nombrecompleto.'</div>
                                                    <div class="col-xs-12 inscrito-title lastname">'.$grp->apellidos.'</div>
                                                    <div class="col-xs-12 inscrito-title name">'.$grp->nombres.'</div>
                                                    <div class="col-xs-7 inscrito-item p-t-5" >Detalles del Inscrito</div>
                                                    <div class="col-xs-5 inscrito-value">'.$detalleEval.'</div>
                                                    <div class="col-xs-6 inscrito-item" >Sede de inter&eacute;s</div>
                                                    <div class="col-xs-6 inscrito-value">'.$grp->desc_sede.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Nivel</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->desc_nivel.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Grado</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->abvr_grado.'</div>
                                                </div>
                                            </div>';
                }else{
                    if($grp->telefono_celular != null){
                        $telfNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->telefono_celular;
                    } else {
                        $telfNulo = '>-';
                    }
                    
                    if($grp->correo != null){
                        $correoNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->correo;
                    } else {
                        $correoNulo = '>-';
                    }
                    $canal_com="-";
                    if($grp->canal_comunicacion != null){
                        $canal_com = $grp->canal_comunicacion;
                    }
    
                    $parentesco = $grp->desc_parentesco;
                    $isActive = '';
                    if($firstPariente == 0){
                        $isActive =' is-active';
                    }
                    $firstPariente++;
    
                    if($firstPariente <= 3){
                        if($firstPariente == 3){
                            $parentesco = 'OTROS';
                        }
                        $parientes.='<a href="#par'.$grp->parentesco.'"   class="mdl-tabs__tab'.$isActive.'">'.$parentesco.'</a>';
                        $cons_cont_parientes.= '<div class="mdl-tabs__panel'.$isActive.'" id="par'.$grp->parentesco.'">
                                                    <div class="row">
                                                        <div class="col-xs-12 inscrito-title">'.$grp->nombrecompleto.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Celular</div>
                                                        <div class="col-xs-8 inscrito-value" '.$telfNulo.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Correo</div>
                                                        <div class="col-xs-8 inscrito-value" '.$correoNulo.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Canal com.</div>
                                                        <div class="col-xs-7 inscrito-value">'.$canal_com.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Persona Reg.</div>
                                                        <div class="col-xs-7 inscrito-value">'.(isset($grp->persona_registro)?$grp->persona_registro:null).'</div>
                                                    </div>
                                                 </div>';
                    }
                }
            }else{//Otra familia
                $btnEstrella = null;
                if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $pantalla != ID_PERMISO_EVALUACION){
                    $btnEstrella = '<button type="button" class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect mdl-favorite" onclick="toogleFavorite(this.id)" id="favorite'.$cons_cod_grupo.'">
                                        <i class="mdi mdi-star"></i>
                                    </button>
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToViewContacto(this)" id="btnView'.$cons_cod_grupo.'">Ver</button>';
                }
                
                $btnVerEditar = '<div class="mdl-card__actions" id="btnGroup'.$cons_cod_grupo.'">
                                     '.$btnEstrella.'
                                     <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised big" onclick="goToEditContacto(this)" id="btnEdit'.$cons_cod_grupo.'">'.$btnEditar.'</button>
                                  </div>';
                
                if($hijos != null){
                    $hijos = '<div class="mdl-card__title">
                                   <ul class="nav nav-pills">'.
                              $hijos.
                              '    </ul>
                                   <a class="nav-pills__left" data-toggle="pill" href="#" onclick="getNextOrPrev($(this), 1)">
                                      <i class="mdi mdi-keyboard_arrow_left"></i>
                                   </a>
                                   <a class="nav-pills__right" data-toggle="pill" href="#" onclick="getNextOrPrev($(this), 2)">
                                      <i class="mdi mdi-keyboard_arrow_right"></i>
                                   </a>
                               </div>';
                }
                $invitados = "";
                if($pantalla == ID_PERMISO_CONTACTOS){
                    if($contInvitados>0){
                        $invitados = "mdl-invitados";
                    }
                }
                $cardsFamilia .= '<div class="mdl-card mdl-inscritos '.$invitados.'" id="mdl-inscritos-cont-'.$cons_cod_grupo.'">';
                $cardsFamilia .= $hijos.'<div class="mdl-card__supporting-text"><div class="tab-content">';
                $cardsFamilia .= $cons_cont_hijos.'</div><div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                            <div class="mdl-tabs__tab-bar">';
                $cardsFamilia .=$parientes.'</div>';
                $opcMenu1 = null;
                if($opcMenu != null){
                    $opcMenu1 = '<div class="mdl-card__menu">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="menu_desp'.$cons_cod_grupo.'">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                        for="menu_desp'.$cons_cod_grupo.'">
                                        '.$opcMenu.'
                                    </ul>
                                </div>';
                }
                $cardsFamilia .= $cons_cont_parientes.'</div></div>
                                        '.$btnVerEditar.$opcMenu1.'
                                 </div>';
                
                $first               = 0;
                $firstPariente       = 0;
                $cons_cod_grupo      = $grp-> cod_grupo;
                $hijos               = null;
                $cons_cont_hijos     = null;
                $parientes           = null;
                $cons_cont_parientes = null;
                $contInvitados       = 0;
                if($pantalla == ID_PERMISO_CONTACTOS){
                    $contInvitados = $contInvitados + $grp->invitados;
                }
                if($grp->flg_estudiante == 1){
                    $active     = '';
                    $activeCont = '';
                    if($first == 0){
                        $active     = ' class="active"';
                        $activeCont = ' in active';
                    }
                    $detalleEval = null;
                    if($pantalla == ID_PERMISO_EVALUACION){
                        $detalleEval = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verDiagnosticoTabla(\''.$idContactoEncryptado.'\', \''.$grp->nombrecompleto.'\')">
                                            <i class="mdi mdi-content_paste"></i>
                                        </button>';
                    }
                    $first++;
                    $codGrupoEncryptado = _simple_encrypt($grp->cod_grupo);
                    $hijos.='<li'.$active.' data-id-contacto='.$idContactoEncryptado.' data-cod-grupo='.$codGrupoEncryptado.'>
                                     <a data-toggle="pill" href="#child-'.$grp->id_contacto.'">
                                         <img alt="Estudiante" src="'.RUTA_IMG.'profile/nouser.svg">
                                     </a>
                                 </li>';
                    $cons_cont_hijos.= '<div id="child-'.$grp->id_contacto.'" class="tab-pane fade'.$activeCont.'">
                                                <div class="row">
                                                    <div class="col-xs-12 inscrito-title">'.$grp->nombrecompleto.'</div>
                                                    <div class="col-xs-12 inscrito-title lastname">'.$grp->apellidos.'</div>
                                                    <div class="col-xs-12 inscrito-title name">'.$grp->nombres.'</div>
                                                    <div class="col-xs-7 inscrito-item" >Detalles del Inscrito</div>
                                                    <div class="col-xs-5 inscrito-value">'.$detalleEval.'</div>
                                                    <div class="col-xs-6 inscrito-item" >Sede de inter&eacute;s</div>
                                                    <div class="col-xs-6 inscrito-value">'.$grp->desc_sede.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Nivel</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->desc_nivel.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Grado</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->abvr_grado.'</div>
                                                </div>
                                            </div>';
                }else{
                    if($grp->telefono_celular != null){
                        $telfNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->telefono_celular;
                    } else {
                        $telfNulo = '>-';
                    }
                    
                    if($grp->correo != null){
                        $correoNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->correo;
                    } else {
                        $correoNulo = '>-';
                    }
                    $canal_com="-";
                    if($grp->canal_comunicacion != null){
                        $canal_com = $grp->canal_comunicacion;
                    }
    
                    $parentesco = $grp->desc_parentesco;
                    $isActive = '';
                    if($firstPariente == 0){
                        $isActive =' is-active';
                    }
                    $firstPariente++;
    
                    if($firstPariente <= 3){
                        if($firstPariente == 3){
                            $parentesco = 'OTROS';
                        }
                        $parientes.='<a href="#par'.$grp->parentesco.'"   class="mdl-tabs__tab'.$isActive.'">'.$parentesco.'</a>';
                        $cons_cont_parientes.= '<div class="mdl-tabs__panel'.$isActive.'" id="par'.$grp->parentesco.'">
                                                    <div class="row">
                                                        <div class="col-xs-12 inscrito-title">'.$grp->nombrecompleto.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Celular</div>
                                                        <div class="col-xs-8 inscrito-value" '.$telfNulo.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Correo</div>
                                                        <div class="col-xs-8 inscrito-value" '.$correoNulo.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Canal com.</div>
                                                        <div class="col-xs-7 inscrito-value">'.$canal_com.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Persona Reg.</div>
                                                        <div class="col-xs-7 inscrito-value">'.(isset($grp->persona_registro)?$grp->persona_registro:null).'</div>
                                                    </div>
                                                 </div>';
                    }
                }
            }
            $data['max_cod_grupo'] = $grp->cod_grupo;
        }
        $btnEstrella = null;
        if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $pantalla != ID_PERMISO_EVALUACION){
            $btnEstrella = '<button type="button" class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect mdl-favorite" onclick="toogleFavorite(this.id)" id="favorite'.$cons_cod_grupo.'">
                                <i class="mdi mdi-star"></i>
                            </button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="goToViewContacto(this)" id="btnView'.$cons_cod_grupo.'">Ver</button>';
        }
        
        $btnVerEditar = '<div class="mdl-card__actions" id="btnGroup'.$cons_cod_grupo.'">
                             '.$btnEstrella.'
                             <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised big" onclick="goToEditContacto(this)" id="btnEdit'.$cons_cod_grupo.'">'.$btnEditar.'</button>
                          </div>';
        if($hijos != null){
            $hijos = '<div class="mdl-card__title">
                           <ul class="nav nav-pills">'.
                                   $hijos.
                          '</ul>
                           <a class="nav-pills__left" data-toggle="pill" href="#" onclick="getNextOrPrev($(this), 1)">
                              <i class="mdi mdi-keyboard_arrow_left"></i>
                           </a>
                           <a class="nav-pills__right" data-toggle="pill" href="#" onclick="getNextOrPrev($(this), 2)">
                              <i class="mdi mdi-keyboard_arrow_right"></i>
                           </a>
                       </div>';
        }
        $invitados = "";
        if($pantalla == ID_PERMISO_CONTACTOS){
            if($contInvitados>0){
                $invitados = "mdl-invitados";
            }
        }
        $cardsFamilia.='<div class="mdl-card mdl-inscritos '.$invitados.'" id="mdl-inscritos-cont-'.$cons_cod_grupo.'">';
        $cardsFamilia.=$hijos.'<div class="mdl-card__supporting-text">
                        <div class="tab-content">';
        $cardsFamilia.=$cons_cont_hijos.'</div><div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                            <div class="mdl-tabs__tab-bar">';
        $cardsFamilia.=$parientes.'</div>';
        $opcMenu1 = null;
        if($opcMenu != null){
            $opcMenu1 = '<div class="mdl-card__menu">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="menu_desp'.$cons_cod_grupo.'">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                                        for="menu_desp'.$cons_cod_grupo.'">
                                        '.$opcMenu.'
                                    </ul>
                                </div>';
        }
        $cardsFamilia.=$cons_cont_parientes.'</div></div>
                                    '.$btnVerEditar.$opcMenu1.'
                                    </div>';
        $data['cardsFamilia'] = $cardsFamilia;
        return $data;
    }
}

if(!function_exists('_createTableInvitarVistaContacto')) {
    function _createTableInvitarVistaContacto($familiares, $horas, $tipoEvento){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbFamiliaresAInivitar">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Tel&eacute;fono', 'class' => 'text-left');
        $head_6 = array('data' => 'Opci&oacute;n', 'class' => 'text-left');
        $head_4 = array('data' => 'Hora citada', 'class' => 'text-left');
        $head_5 = array('data' => 'Observaci&oacute;n', 'class' => 'text-left');
        if($horas != null){
            $CI->table->set_heading($head_0, $head_1, $head_2, $head_6,$head_4, $head_5);
        } else {
            $CI->table->set_heading($head_0, $head_1, $head_2, $head_6, $head_5);
        }
        $i = 1;
        $idEventoEnc = _simple_encrypt(_getSesion('id_evento_detalle'));
        foreach ($familiares as $row){
    
            $idContactoEnc = _simple_encrypt($row->id_contacto);
    
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $row_1 = array('data' => $row->nombrecompleto.' <strong>('.$row->parentesco.')</strong>' , 'class' => 'text-left');
            $row_2 = array('data' => '<div style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalAgregarLlamadaInvitar(\'modalGuardarLlamada\',\''.$idContactoEnc.'\')">'.$row->telefono_celular.'</div>' , 'class' => 'text-left');
    
            $opcionGrupo = __buildComboByGrupoNoEncryptId(COMBO_TIPO_OPCION_ASISTENCIA, $row->opcion);
            $opcionSelect = '<div class="cmb_invitar" id="cont_Invitar'.$i.'">
                                    <select class="form-control selectButton" data-live-search="true" id="selectOpcionInvitado'.$i.'"
                                     name="selectOpcionInvitado'.$i.'" onchange="habilitarSelect('.$i.', this, '.$row->flg_estudiante.');"
                                         data-id-select1="selectHoraCitadaInvitado'.$i.'" data-id-contacto="'.$idContactoEnc.'" data-id-observ="observacion'.$i.'">
            	                    <option value="">Selec. una opci&oacute;n</option>
                                     '.$opcionGrupo.'
            	                    </select></div>';
            $row_6 = array('data' => $opcionSelect , 'class' => 'text-center');
            $disOpc = $row->opcion == null ? "disabled" : "";
            $observacion = '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_observ'.$i.'">
                                    <input class="mdl-textfield__input" type="text" id="observacion'.$i.'" name="observacion'.$i.'"
                                        maxlength="45" '.$disOpc.' value="'.$row->razon_inasistencia.'" onchange = "habilitarButton()">
                                    <label class="mdl-textfield__label" for="observacion'.$i.'">Observaci&oacute;n</label>
                                    <span class="mdl-textfield__limit" for="observacion" data-limit="40"></span> 
                                </div>';
            $row_5 = array('data' =>$observacion , 'class' => 'text-center');
            if($horas != null && ($tipoEvento == TIPO_EVENTO_EVALUACION || $tipoEvento == TIPO_EVENTO_EVALUACION_VERANO)){
                $comboHorarios = __buildComboHorarios($horas, $row->id_hora_cita);
                $disabled = "";
                if($row->id_hora_cita == null && $row->opcion == null){
                    $disabled = "disabled";
                }
                $select = '<select class="form-control selectButton" data-live-search="true" id="selectHoraCitadaInvitado'.$i.'"
                               name="selectHoraCitadaInvitado'.$i.'" '.$disabled.' onchange = "habilitarButton()">
    		                  <option value="">Selec. un horario</option>
                              '.$comboHorarios.'
    		               </select>';
                $row_4 = array('data' => $select , 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_1, $row_2, $row_6, $row_4, $row_5);
            }else{
                $CI->table->add_row($row_0, $row_1, $row_2, $row_6, $row_5);
            }
    
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}
if(!function_exists('_createTableLlamadas')) {
    function _createTableLlamadas($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbLlamadas">',//este id se lee en el js
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'style' => 'text-align:left');
        $head_1 = array('data' => 'Tipo de seguimiento', 'style' => 'text-align:left');
        $head_2 = array('data' => 'Evento', 'style' => 'text-align:left');
        $head_3 = array('data' => 'Fecha de seguimiento', 'style' => 'text-align:left');
        $head_4 = array('data' => 'Observaci&oacute;n', 'style' => 'text-align:left');
        $head_5 = array('data' => 'Personal', 'style' => 'text-align:left');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        $i = 1;
        foreach ($data as $row){
            $row_0 = $i;
            $row_1   = array('data' => $row->desc_tipo_llamada);
            $row_2   = array('data' => $row->desc_evento);
            $row_3   = array('data' => date_format(date_create($row->fecha_registro), 'd/m/Y H:i:s'));
            $row_4   = array('data' => $row->observacion);
            $row_5   = array('data' => $row->nombrecompleto);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createVistaParientes')) {
    function _createVistaParientes($parientes){
        $CI =& get_instance();
        $vista = null;
        $i = 0;
        foreach ($parientes as $par){
            $i++;
            $idContactoEnc = _simple_encrypt($par->id_contacto);
            $parentesco = $CI->m_utils->getDescComboTipoByGrupoValor(COMBO_PARENTEZCO, $par->parentesco);
    
            $tipoDoc = 'Documento';
            if($par->tipo_documento != null){
                $tipoDoc = strtoupper($CI->m_utils->getDescComboTipoByGrupoValor(COMBO_TIPO_DOC, $par->tipo_documento));
            }
    
            $btns = null;
            if(_getSesion('accionDetalleContacto') == 1){
                $btns = '<div class="mdl-card__actions">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect" onclick="abrirModalDetallePariente(\''.$idContactoEnc.'\', 2)">Ver</button>
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="abrirModalDetallePariente(\''.$idContactoEnc.'\', 1)">Editar</button>
                            </div>
                             <div class="mdl-card__menu">
                                <button id="parent-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="parent-'.$i.'">
                                    <li class="mdl-menu__item" onclick="abrirModadalConfirmDeleteContacto(\''.$idContactoEnc.'\')"><i class="mdi mdi-delete"></i> Eliminar</li>
                                </ul>
                            </div>';
            }
    
            $vista .= '<div class="mdl-card mdl-parent">
                                    <div class="mdl-card__title">
                                        <div class="mdl-photo">
                                            <img alt="Parent" src="'.RUTA_IMG.'profile/nouser.svg" id="fotoFamiliar1" class="mdl-img">
                                            <span class="caption fade-caption">
                                                <i class="mdi mdi-photo_camera"></i>
                                            </span>
                                        </div>
                                    </div>
    
                                    <div class="mdl-card__supporting-text br-b">
                                        <div class="row p-0 m-0">
                                            <div class="col-xs-12 parent-name">'.$par->nombrecompleto.'</div>
                                            <div class="col-xs-12 parent-head"><strong>Detalles del familiar</strong></div>
                                            <div class="col-xs-4  parent-item">Parentesco</div>
                                            <div class="col-xs-8  parent-value">'.strtoupper($parentesco).'</div>
                                            <div class="col-xs-5  parent-item">'.$tipoDoc.'</div>
                                            <div class="col-xs-7 parent-value">'.$par->nro_documento.'</div>
                                            <div class="col-xs-3  parent-item">Correo</div>
                                            <div class="col-xs-9  parent-value">'.$par->correo.'</div>
                                            <div class="col-xs-4  parent-item">Celular</div>
                                            <div class="col-xs-8  parent-value">'.$par->telefono_celular.'</div>
                                        </div>
                                    </div>
                                    '.$btns.'
                                </div>';
        }
         
        return $vista;
    }
}
if(!function_exists('_createTableInvitadosAsistieron')) {
    function _createTableInvitadosAsistieron($invitados, $idEvento, $tipoEvento){
        $tabla     = '<table id="treeInvitadosAsistieron" class="tree table">';
        $colAsistencia = null;
        if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
            $colAsistencia = '<td class="text-center" style="border-top: none;">Invitar</td>';
        }
        $tabla    .= '<tr >
                           <td class="text-center" style="border-top: none;">#</td>
	                       <td style="border-top: none;" class="col-sm-1"></td>
                           <td class="text-left" style="border-top: none;">Nombre</td>
                           <td class="text-center" style="border-top: none;">Hora llegada</td>
                           <td class="text-center" style="border-top: none;">¿Asistencia directa?</td>
                           '.$colAsistencia.'
                     </tr>';
        $i = 1;
        $j = 0;
        $cons_cod_grupo = null;
        foreach ($invitados as $row) {
            $idContactoEnc = _simple_encrypt($row->id_contacto);
            $parent = null;
            $j++;
            if($cons_cod_grupo == null){
                if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
                    $colAsistencia = '<td></td>';
                }
                $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' '.$parent.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td></td>
    	                          <td></td>
    	                          '.$colAsistencia.'
                	          </tr>';
                
                if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
                    $colAsistencia = '<td class="text-center">
                                          <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.$j.'">
                                              <input type="radio" id="option-'.$j.'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_ASISTIRA).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                              <span class="mdl-radio__label">Asistir&aacute;</span>
                                          </label>
                                          <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.($j+1).'">
                                              <input type="radio" id="option-'.($j+1).'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_TALVEZ).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                              <span class="mdl-radio__label">Tal vez</span>
                                          </label>
                                      </td>';
                    $j++;
                }
                
                $parent = "treegrid-parent-$row->cod_grupo";
                $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
    	                          <td class="text-center">'._fecha_tabla($row->hora_llegada, 'h:i A').'</td>
    	                          <td class="text-center">'.$row->flg_asistencia_directa.'</td>
    	                          '.$colAsistencia.'
                	          </tr>';
                $cons_cod_grupo = $row->cod_grupo;
            }
            else{
                if($cons_cod_grupo == $row->cod_grupo){
                    if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
                        $colAsistencia = '<td class="text-center">
                                              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.$j.'">
                                                  <input type="radio" id="option-'.$j.'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_ASISTIRA).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                                  <span class="mdl-radio__label">Asistir&aacute;</span>
                                              </label>
                                              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.($j+1).'">
                                                  <input type="radio" id="option-'.($j+1).'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_TALVEZ).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                                  <span class="mdl-radio__label">Tal vez</span>
                                              </label>
                                          </td>';
                        $j++;
                    }
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
        	                          <td class="text-center">'._fecha_tabla($row->hora_llegada, 'h:i A').'</td>
        	                          <td class="text-center">'.$row->flg_asistencia_directa.'</td>
        	                          '.$colAsistencia.'
                    	          </tr>';
                }else{
                    if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
                        $colAsistencia = '<td></td>';
                    }
                    $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' style="background: #ececec" '.$parent.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td></td>
    	                          <td></td>
    	                          '.$colAsistencia.'
                	          </tr>';
                    if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $tipoEvento == TIPO_EVENTO_TOUR){
                        $colAsistencia = '<td class="text-center">
                                              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.$j.'">
                                                  <input type="radio" id="option-'.$j.'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_ASISTIRA).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                                  <span class="mdl-radio__label">Asistir&aacute;</span>
                                              </label>
                                              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-'.($j+1).'">
                                                  <input type="radio" id="option-'.($j+1).'" class="mdl-radio__button" name="opciones'.$i.'" onchange="invitarDra(\''._simple_encrypt($idEvento).'\', \''._simple_encrypt(OPCION_TALVEZ).'\', \''._simple_encrypt($row->id_contacto).'\', this)">
                                                  <span class="mdl-radio__label">Tal vez</span>
                                              </label>
                                          </td>';
                        $j++;
                    }
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
        	                          <td class="text-center">'._fecha_tabla($row->hora_llegada, 'h:i A').'</td>
        	                          <td class="text-center">'.$row->flg_asistencia_directa.'</td>
        	                          '.$colAsistencia.'
                    	          </tr>';
                    $cons_cod_grupo = $row->cod_grupo;
                }
            }
            $i++;
        }
         
        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('_createTableAsistenciaEventoDRA')) {
    function _createTableAsistenciaEventoDRA($invitados, $idEvento, $opcion){
        $id = "treeAsistenciaEvento";
        if($opcion == 1){
            $id = "treeAsistenciaEvento_1";
        }
         
        $tabla     = '<table id="'.$id.'" class="tree table">';
        $tabla    .= '<tr >
                               <td class="text-center" style="border-top: none;">#</td>
    	                       <td style="border-top: none;" class="col-sm-1"></td>
                               <td class="text-left" style="border-top: none;">Nombre</td>
                               <td class="text-center" style="border-top: none;">Asistencia</td>
                         </tr>';
        $i = 1;
        $cons_cod_grupo = null;
        foreach ($invitados as $row) {
            $idContactoEnc = _simple_encrypt($row->id_contacto);
            $asistFam = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalEditarAsistenciaFamiliaDRA(\''.$idContactoEnc.'\', \''.$idEvento.'\')"><i class="mdi mdi-assignment"></i></button>';
            if($opcion == 1){
                $asistFam = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalPasarAsistenciaFamiliaDRA(\''.$idContactoEnc.'\', \''.$idEvento.'\')"><i class="mdi mdi-assignment"></i></button>';
            }
            $parent = null;
            if($cons_cod_grupo == null){
                $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td class="text-center">'.$asistFam.'</td>
                	          </tr>';
                $cons_cod_grupo = $row->cod_grupo;
                $i++;
            }else{
                if($cons_cod_grupo != $row->cod_grupo){
                    $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' style="background: #ececec" '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td class="text-center">'.$asistFam.'</td>
                	          </tr>';
                    $cons_cod_grupo = $row->cod_grupo;
                    $i++;
                }
            }
        }
    
        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('_createTableAsistenciaEventoTour')) {
    function _createTableAsistenciaEventoTour($invitados, $idEvento, $opcion){
        $id = "treeAsistenciaEvento";
        if($opcion == 1){
            $id = "treeAsistenciaEvento_1";
        }
         
        $tabla     = '<table id="'.$id.'" class="tree table">';
        $tabla    .= '<tr >
                               <td class="text-center" style="border-top: none;">#</td>
    	                       <td style="border-top: none;" class="col-sm-1"></td>
                               <td class="text-left" style="border-top: none;">Nombre</td>
                               <td class="text-center" style="border-top: none;">Asistencia</td>
                         </tr>';
        $i = 1;
        $cons_cod_grupo = null;
        foreach ($invitados as $row) {
            $idContactoEnc = _simple_encrypt($row->id_contacto);

            /*$asist = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-'.$i.'">
                              <input type="checkbox" id="checkbox-'.$i.'" class="mdl-checkbox__input" '.$row->asistencia.' onchange="asistenciaInvitado(\''.$idContactoEnc.'\', \''.$idEvento.'\', this)">
                              <span class="mdl-checkbox__label"></span>
                          </label>';
            if($opcion == 1){
                $asist = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="agregarListaInvitado(\''.$idContactoEnc.'\')"><i class="mdi mdi-reply_all"></i></button>';
            }*/
            
            $asistFam = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalEditarAsistenciaFamiliaDRA(\''.$idContactoEnc.'\', \''.$idEvento.'\')"><i class="mdi mdi-assignment"></i></button>';
            if($opcion == 1){
                $asistFam = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalPasarAsistenciaFamiliaDRA(\''.$idContactoEnc.'\', \''.$idEvento.'\')"><i class="mdi mdi-assignment"></i></button>';
            }
             
            $parent = null;
            if($cons_cod_grupo == null){
                $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' '.$parent.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td>'.$asistFam.'</td>
                	          </tr>';
                 
                $parent = "treegrid-parent-$row->cod_grupo";
                $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left">'.$row->nombrecompleto.'</td>
    	                          <td class="text-center"></td>
                	          </tr>';
                $cons_cod_grupo = $row->cod_grupo;
            }else{
                if($cons_cod_grupo == $row->cod_grupo){
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.'</td>
        	                          <td class="text-center"></td>
                    	          </tr>';
                }else{
                    $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' style="background: #ececec" '.$parent.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.'</strong></td>
    	                          <td>'.$asistFam.'</td>
                	          </tr>';
                     
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.'</td>
        	                          <td class="text-center"></td>
                    	          </tr>';
                    $cons_cod_grupo = $row->cod_grupo;
                }
            }
            $i++;
        }

        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('_createTableInvitadosOpcion')) {
    function _createTableInvitadosOpcion($invitados){
        $tabla     = '<table id="treeDetalleEvento" class="tree table">';
        $tabla    .= '<tr >
                               <td class="text-center" style="border-top: none;">#</td>
    	                       <td style="border-top: none;" class="col-sm-1"></td>
                               <td class="text-left" style="border-top: none;">Nombre</td>
                               <td class="text-left" style="border-top: none;">Sede Interés</td>
                               <td class="text-left" style="border-top: none;">Teléfono</td>
                               <td class="text-left" style="border-top: none;">Correo</td>
                         </tr>';
        $i = 1;
        $cons_cod_grupo = null;
        $countHijos = 0;
        foreach ($invitados as $row) {
            $countHijos = 0;
            $idContactoEnc = _simple_encrypt($row->id_contacto);
             
            $parent = null;
            if($cons_cod_grupo == null){
                $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' '.$parent.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.' ('.$row->cantidad_post.')</strong></td>
    	                          <td></td>
    	                          <td></td>
    	                          <td></td>
                	          </tr>';
                 
                $parent = "treegrid-parent-$row->cod_grupo";
                $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
    	                          <td class="text-left">'.$row->desc_sede_interes.'</td>
    	                          <td class="text-left">'.$row->telefono_celular.'</td>
    	                          <td class="text-left">'.$row->correo.'</td>
                	          </tr>';
                $cons_cod_grupo = $row->cod_grupo;
            }
            else{
                if($cons_cod_grupo == $row->cod_grupo){
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
    	                              <td class="text-left">'.$row->desc_sede_interes.'</td>
        	                          <td class="text-left">'.$row->telefono_celular.'</td>
    	                              <td class="text-left">'.$row->correo.'</td>
                    	          </tr>';
                }else{
                    $tabla .='<tr class="backgroundPapa treegrid-'.$row->cod_grupo.' style="background: #ececec" '.$parent.'">
    	                          <td class="text-left"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->apellidofamilia.' ('.$row->cantidad_post.')</strong></td>
    	                          <td></td>
    	                          <td></td>
    	                          <td></td>
                	          </tr>';
                     
                    $parent = "treegrid-parent-$row->cod_grupo";
                    $tabla .='<tr class="treegrid-'.$row->cod_grupo.$i.' '.$parent.'">
        	                          <td class="text-center">'.$i.'</td>
        	                          <td></td>
        	                          <td class="text-left">'.$row->nombrecompleto.' ('.$row->parentesco.')</td>
    	                              <td class="text-left">'.$row->desc_sede_interes.'</td>
        	                          <td class="text-left">'.$row->telefono_celular.'</td>
    	                          <td class="text-left">'.$row->correo.'</td>
                    	          </tr>';
                    $cons_cod_grupo = $row->cod_grupo;
                }
            }
            $i++;
        }
    
        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('_createTableInvitadosGrados')) {
    function _createTableInvitadosGrados($invitados){
        $tabla     = '<table id="treeDetalleEvento" class="tree table">';
        $tabla    .= '<tr >
                           <td class="text-center" style="border-top: none;">#</td>
	                       <td style="border-top: none;" class="col-sm-1"></td>
                           <td class="text-left" style="border-top: none;">Nombre</td>
                           <td class="text-center" style="border-top: none;">Cantidad</td>
                      </tr>';
        $i = 1;
        $j = 1;
        $cons_grado = null;
        $countHijos = 0;
        foreach ($invitados as $row) {
            $parent = null;
            if($cons_grado == null){
                $tabla .='<tr class="treegrid-'.$row->grado_ingreso.'">
	                          <td class="text-center"></td>
	                          <td></td>
	                          <td class="text-left"><strong>'.$row->desc_grado.' ( '.$row->desc_nivel.' )</strong></td>
	                          <td>'.$row->cant_grado.'</td>
            	          </tr>';
                 
                $parent = "treegrid-parent-$row->grado_ingreso";
                $tabla .='<tr class="treegrid-'.$row->grado_ingreso.$i.$j.' '.$parent.'">
	                          <td class="text-center">'.$i.'</td>
	                          <td></td>
	                          <td class="text-left">'.$row->nombrecompleto.'</td>
	                          <td>'._fecha_tabla($row->horario, 'h:i A').'</td>
            	          </tr>';
                $cons_grado = $row->grado_ingreso;
            }
            else{
                if($cons_grado == $row->grado_ingreso){
                    $parent = "treegrid-parent-$row->grado_ingreso";
                    $tabla .='<tr class="treegrid-'.$row->grado_ingreso.$i.$j.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left">'.$row->nombrecompleto.'</td>
    	                          <td>'._fecha_tabla($row->horario, 'h:i A').'</td>
                	          </tr>';
                }else{
                    $j++;
                    $tabla .='<tr class="treegrid-'.$row->grado_ingreso.'">
    	                          <td class="text-center"></td>
    	                          <td></td>
    	                          <td class="text-left"><strong>'.$row->desc_grado.' ( '.$row->desc_nivel.' )</strong></td>
    	                          <td>'.$row->cant_grado.'</td>
                	          </tr>';
                     
                    $parent = "treegrid-parent-$row->grado_ingreso";
                    $tabla .='<tr class="treegrid-'.$row->grado_ingreso.$i.$j.' '.$parent.'">
    	                          <td class="text-center">'.$i.'</td>
    	                          <td></td>
    	                          <td class="text-left">'.$row->nombrecompleto.'</td>
    	                          <td>'._fecha_tabla($row->horario, 'h:i A').'</td>
                	          </tr>';
                    $cons_grado = $row->grado_ingreso;
                }
            }
            $i++;
        }

        $tabla .= '</table>';
        return $tabla;
    }
}

if(!function_exists('_createTableRecursosHumanos')) {
    function _createTableRecursosHumanos($recursos, $idSede){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbRecursosHumanos">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-center');
        $head_0   = array('data' => 'Nombre', 'class' => 'text-left');
        $head_1   = array('data' => 'Fecha Reg.', 'class' => 'text-left');
        $head_2   = array('data' => 'Cantidad', 'class' => 'text-center');
        $head_3   = array('data' => 'Resp. asignar', 'class' => 'text-left');
        $head_5   = array('data' => 'Observ.', 'class' => 'text-left');
        $head_6   = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $CI->table->set_heading($head_0_1, $head_0, $head_1, $head_2, $head_3, $head_5, $head_6);
        $i = 1;
    
        $fechaEvento  = ($CI->m_utils->getById("admision.evento", "fecha_realizar", "id_evento", _getSesion('id_evento_detalle')));
        $estadoEvento = ($CI->m_utils->getById("admision.evento", "estado", "id_evento", _getSesion('id_evento_detalle')));
        foreach ($recursos as $row){
            $idRecursoEventoEnc = _simple_encrypt($row->id_recurso_x_evento);
    
            $onclickBorrar = null;
            $opacityBorrar = '0.4';
            if(_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_VER){
                $onclickBorrar = null;
                $opacityBorrar = '0.4';
            }
    
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                $onclickBorrar = 'abrirModalConfirmDeleteApoyoAdministrativo(\''.$idRecursoEventoEnc.'\')';
                $opacityBorrar = '1';
            }
             
            $btnAsignarPersonas = null;
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                || ($row->id_sede == $idSede && $estadoEvento == EVENTO_PENDIENTE && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR) || (_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $row->id_sede == null) || (_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_encargada", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))){
                $btnAsignarPersonas = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                                               onclick="abrirModalAsignarPersonasApoyoMaterial(\''.$idRecursoEventoEnc.'\', '.$i.')" style="display:inline; margin-left:10px">
                                           <i class="mdi mdi-person_add"></i>
                                       </button>';
            }
    
            $row_0_1 = $i;
            $row_0   = array('data' => $CI->m_utils->getById("rol", "desc_rol", "nid_rol", $row->id_recurso).' '.$row->toma_asistencia, 'class' => 'text-left');
            $row_1   = array('data' => _fecha_tabla($row->fecha_registro, 'd/m/Y'), 'class' => 'text-left');
            $row_2   = array('data' => '<p id="cantidad_apoyo_adm_'.$i.'" onclick="abrirModalCantidadApoyoAdministrativoEvento(\''.$idRecursoEventoEnc.'\', '.$i.')" data-attr-cantidad="'.$row->cantidad.'" style="display:inline" class="link-dotted">'.$row->cant_personas.'/'.$row->cantidad.'</p>'.$btnAsignarPersonas, 'class' => 'text-center');
            $row_3   = array('data' => $row->desc_sede, 'class' => 'text-left');
            $observacion = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                                        onclick="abrirModalObservacionApoyoAdministratico(\''.$row->observacion_pedido.'\')"><i class="mdi mdi-remove_red_eye"></i></button>';
            $row_5   = array('data' => ($row->observacion_pedido != null) ? $row->observacion_pedido : '-', 'class' => 'text-left');
            $borrar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="opacity: '.$opacityBorrar.'" onclick="'.$onclickBorrar.' "  data-toggle="tooltip" data-placement="bottom" data-original-title="Eliminar">
                                        <i class="mdi mdi-delete"></i>
                           </button>';
            $row_6   = array('data' => $borrar, 'class' => 'text-center');
    
            $CI->table->add_row($row_0_1, $row_0, $row_1, $row_2, $row_3, $row_5, $row_6);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableRecursosMateriales')) {
    function _createTableRecursosMateriales($recursos){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbRecursosMateriales">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-center');
        $head_0   = array('data' => 'Nombre', 'class' => 'text-left');
        $head_1   = array('data' => 'Fecha Reg.', 'class' => 'text-left');
        $head_2   = array('data' => 'Cantidad', 'class' => 'text-center');
        $head_3   = array('data' => 'Encargado', 'class' => 'text-left');
        $head_4   = array('data' => 'Conformidad', 'class' => 'text-center');
        $head_5   = array('data' => 'Observ.', 'class' => 'text-center display-none');
        $head_6   = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $head_7   = array('data' => 'Asistencia', 'class' => 'text-center');
        $CI->table->set_heading($head_0_1, $head_0, $head_1, $head_2, $head_3, $head_4,$head_7, $head_6);
        $i = 1;
        $estadoEvento = ($CI->m_utils->getById("admision.evento", "estado", "id_evento", _getSesion('id_evento_detalle')));
        $fechaEvento  = ($CI->m_utils->getById("admision.evento", "fecha_realizar", "id_evento", _getSesion('id_evento_detalle')));
        $personaRegistro  = $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle'));
        foreach ($recursos as $row){
            $idRecursoEventoEnc = _simple_encrypt($row->id_recurso_x_evento);
    
            $classClick         = null;
            $onclickCantidad    = null;
            $onClickConformidad = null;
            $opacityConformidad = '0.4';
            $onclickBorrar      = null;
            $opacityBorrar      = '0.4';
    
            $onClickConfirmacion = null;
            $opacityConfirmacion = '0.4';
    
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $personaRegistro == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                $onclickBorrar      = 'abrirModalConfirmDeleteRecursoMaterial(\''.$idRecursoEventoEnc.'\')';
                $opacityBorrar      = '1';
                $classClick         = 'link-dotted';
                $onclickCantidad    = 'abrirModalChangeCantidadRecursoMaterial(\''.$idRecursoEventoEnc.'\', '.$i.')';
            }
    
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $personaRegistro == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                $onClickConformidad = 'abrirModalConformidadRecursoMaterial(\''.$row->checked.'\', \''.$row->observacion_cumplimiento.'\', \''.$idRecursoEventoEnc.'\')';
                $opacityConformidad = '1';
            }
    
            if(($estadoEvento == EVENTO_PENDIENTE && $row->id_responsable == _getSesion('nid_persona')) || ($estadoEvento == EVENTO_PENDIENTE && $personaRegistro == _getSesion('nid_persona'))){
                $onClickConfirmacion = 'clickConfirmacion(\''.$idRecursoEventoEnc.'\', this)';
                $opacityConfirmacion = '1';
            }
    
            $row_0_1 = $i;
            $row_0   = array('data' => $row->recurso_desc, 'class' => 'text-left');
            $row_1   = array('data' => _fecha_tabla($row->fecha_registro, 'd/m/Y'), 'class' => 'text-left');
            $row_2   = array('data' => '<p id="cantidadRecursoMaterial'.$i.'" onclick="'.$onclickCantidad.'" data-attr-cantidad="'.$row->cantidad.'" class="'.$classClick.'">'.$row->cantidad.'<p>', 'class' => 'text-center');
            

            $foto = $row->foto_persona_google;
            if($foto == null){
                $foto = ((file_exists(FOTO_PROFILE_PATH.$row->foto_persona)) ? RUTA_IMG_PROFILE.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
            }
            
            $row_3 = array('data' => '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'"  data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombre_responsable.'">'.$row->nombreabreviado , 'class' => 'text-left');
    
            $modalCheck = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="opacity: '.$opacityConformidad.'"
                                        onclick="'.$onClickConformidad.'">
                                       <i class="mdi mdi-spellcheck"></i>
                               </button>';
            $row_4   = array('data' => $modalCheck, 'class' => 'text-center');
            $observacion = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                                        onclick="abrirModalObservacionRecursoMaterial(\''.$row->observacion_pedido.'\', \''.$row->observacion_cumplimiento.'\', \''.$row->observacion_resp.'\')" ><i class="mdi mdi-remove_red_eye"></i></button>';
            $row_5   = array('data' => $observacion, 'class' => 'text-center');
            $borrar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                       onclick="abrirModalObservacionRecursoMaterial(\''.$row->observacion_pedido.'\', \''.$row->observacion_cumplimiento.'\', \''.$row->observacion_resp.'\')"  data-toggle="tooltip" data-placement="bottom" data-original-title="Observaci&oacute;n">
                           <i class="mdi mdi-remove_red_eye"></i>
                       </button>
                       <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="display:inline-block; opacity: '.$opacityBorrar.'" onclick="'.$onclickBorrar.'"  data-toggle="tooltip" data-placement="bottom" data-original-title="Eliminar">
                           <i class="mdi mdi-delete"></i>
                       </button> ';
            $row_6   = array('data' => $borrar, 'class' => 'text-center');
            $confirmar = '<button class="mdl-button mdl-js-button mdl-button--icon" style="display:inline-block;opacity: '.$opacityConfirmacion.'" onclick="'.$onClickConfirmacion.'">
                                  <i class="mdi mdi-'.$row->pulgar.'" style="color: '.$row->color_pulgar.'"></i>
                              </button>';
            $row_7   = array('data' => $confirmar, 'class' => 'text-center');
    
            $CI->table->add_row($row_0_1, $row_0, $row_1, $row_2, $row_3,$row_4, $row_7, $row_6);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableEncargadosApoyoAdministrativo')) {
    function _createTableEncargadosApoyoAdministrativo($encargados, $recursoEvento){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbApoyoAdiministrativoAsignados">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left p-r-170');
        $head_2 = array('data' => 'Teléfono', 'class' => 'text-right');
        $head_3 = array('data' => 'Correo', 'class' => 'text-left');
        $head_4 = array('data' => 'Hora Asistencia', 'class' => 'text-center');
        $head_5 = array('data' => 'Asistencia', 'class' => 'text-center');
        $head_6 = array('data' => 'Confirmación', 'class' => 'text-center');
        $head_7 = array('data' => 'Acción', 'class' => 'text-center p-l-15 p-r-15');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
    
        $idSede = $CI->m_utils->getById("admision.recurso_x_evento", "id_sede", "id_recurso_x_evento", _simpleDecryptInt($recursoEvento));
        $estadoEvento = $CI->m_utils->getById("admision.evento", "estado", "id_evento", _getSesion('id_evento_detalle'));
        $personaRegistro  = $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle'));
        $i = 1;
        foreach ($encargados as $row){
            $idPersonaEnc = _simple_encrypt($row->nid_persona);
            
    
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $foto = $row->foto_persona_google;
            if($foto == null){
                $foto = ((file_exists(FOTO_PROFILE_PATH.$row->foto_persona)) ? RUTA_IMG_PROFILE.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
            }
            
            $row_1 = array('data' => '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombrecompleto.'">'.$row->nombreabreviado , 'class' => 'text-left');
            $row_2 = array('data' => $row->telf_pers , 'class' => 'text-right');
            $row_3 = array('data' => $row->correo_admi , 'class' => 'text-left');
    
            $disableCheck       = 'disabled';
            $onchangeAsistencia = null;
            $opacityBorrar = '0.4';
            $onClickBorrar = null;
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                $disableCheck       = null;
                $onchangeAsistencia = 'asistenciasApoyoAdministrativo(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\', this, '.$i.')';
                $opacityBorrar       = '1';
                $onClickBorrar      = 'modalDeletePersonaRecursoEvento(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\')';
            }
    
            if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $idSede == _getSesion("sede_trabajo") && $estadoEvento == EVENTO_PENDIENTE){
                $opacityBorrar = '1';
                $onClickBorrar = 'modalDeletePersonaRecursoEvento(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\')';
            }
    
            $asist = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-'.$i.'">
                          <input type="checkbox" id="checkbox-'.$i.'" class="mdl-checkbox__input" '.$row->check.' onchange="'.$onchangeAsistencia.'" '.$disableCheck.'>
                          <span class="mdl-checkbox__label"></span>
                      </label>';
            $row_5 = array('data' => $asist , 'class' => 'text-center');
            $row_4 = array('data' => '<p id="horaAsistenciaApoyoAdm'.$i.'">'.(($row->hora_llegada == null) ? '-' : _fecha_tabla($row->hora_llegada, 'h:i A')).'</p>' , 'class' => 'text-center');

            $disabled = "disabled";
            if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $idSede == _getSesion("sede_trabajo") && $estadoEvento == EVENTO_PENDIENTE){
                $opacityBorrar = '1';
                $onClickBorrar = 'modalDeletePersonaRecursoEvento(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\')';
                $disabled      = null;
            }
    
            if(_getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING && $idSede == null){
                $opacityBorrar = '1';
                $onClickBorrar = 'modalDeletePersonaRecursoEvento(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\')';
                $disabled      = null;
            }
    
            $borrar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="opacity: '.$opacityBorrar.'" onclick="'.$onClickBorrar.'" '.$disabled.'>
                           <i class="mdi mdi-delete"></i>
                       </button>';
            
            $opacityConfirmacion = '0.4';
            $onClickConfirmacion = null;
            if(($row->nid_persona == _getSesion('nid_persona')) || ($estadoEvento == EVENTO_PENDIENTE && $personaRegistro == _getSesion('nid_persona'))){
                $opacityConfirmacion = '1';
                $onClickConfirmacion = 'confirmarAsistenciaRecursoEventoPersona(\''.$recursoEvento.'\', \''.$idPersonaEnc.'\', this)';
            }
    
            $confirmar = '<button class="mdl-button mdl-js-button mdl-button--icon" style="display:inline-block;opacity: '.$opacityConfirmacion.'" onclick="'.$onClickConfirmacion.'">
                              <i class="mdi mdi-'.$row->pulgar.'" style="color: '.$row->color_pulgar.'"></i>
                          </button>';
    
            $row_6 = array('data' => $confirmar , 'class' => 'text-center');
            
            $observ = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="" onclick="verObservacionApoyoAdministrativoAux(\''.$row->observacion_ped.'\', \''.$row->observacion_resp.'\')">
                           <i class="mdi mdi-visibility"></i>
                       </button>';
            
            $row_7 = array('data' => $borrar.$observ    , 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6, $row_7);
    
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableAuxiliaresApoyoAdministrativo')) {
    function _createTableAuxiliaresApoyoAdministrativo($auxiliares, $idRecursoEvento){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbBusquedaAuxiliaresApoyoAdm">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Teléfono', 'class' => 'text-left');
        $head_3 = array('data' => 'Correo', 'class' => 'text-left');
        $head_4 = array('data' => 'Asignar', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
    
        $i = 1;
        foreach ($auxiliares as $row){
            if($row->flg == 0){
                $idPersonaEnc = _simple_encrypt($row->nid_persona);
                
                $foto = $row->foto_persona_google;
                if($foto == null){
                    $foto = ((file_exists(FOTO_PROFILE_PATH.$row->foto_persona)) ? RUTA_IMG_PROFILE.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
                }
                
                $row_0 = array('data' => $i , 'class' => 'text-left');
                $row_1 = array('data' => '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombrecompleto.'">'.$row->nombreabreviado, 'class' => 'text-left');
                $row_2 = array('data' => $row->telf_pers   , 'class' => 'text-left');
                $row_3 = array('data' => $row->correo_admi , 'class' => 'text-left');
    
                $asignar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                                     onclick="abrirModalAsignarApoyoAdm($(this),\''.$idRecursoEvento.'\', \''.$idPersonaEnc.'\')">
                                <i class="mdi mdi-add"></i>
                            </button>';
                $row_4 = array('data' => $asignar , 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
    
                $i++;
            }
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableHorasCita')) {
    function _createTableHorasCita($horasCita){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbHorarioEvaluacion">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
    
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Descripción', 'class' => 'text-left');
        $head_2 = array('data' => 'Hora', 'class' => 'text-left');
        $head_3 = array('data' => 'Cant. Postulantes', 'class' => 'text-center');
        $head_4 = array('data' => 'Acción', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $i = 1;
        $idEvento = _simple_encrypt(_getSesion('id_evento_detalle'));
        foreach ($horasCita as $row){
            $idCorrelativo = _simple_encrypt($row->correlativo);
            $row_0   = array('data' => $i, 'class' => 'text-left');
            $row_1   = array('data' => $row->desc_hora_cita, 'class' => 'text-left');
            $row_2   = array('data' => _fecha_tabla($row->hora_cita, 'h:i A'), 'class' => 'text-left');
    
            $deleteHora = null;
            $opacity    = '0.4';
    
            if(_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_VER){
                $deleteHora = null;
                $opacity    = '0.4';
            }
    
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SECRETARIA){
                $deleteHora = 'abrirModalConfirmDeleteHorarioEvaluacion(\''.$idCorrelativo.'\')';
                $opacity  = '1';
            }
    
            $row_3   = array('data' => '<p class="link-dotted" onclick="mostrarPostulantesHorario(\''.$idCorrelativo.'\', \''._fecha_tabla($row->hora_cita, 'h:i A').'\')">'.$row->cantidad.'</p>', 'class' => 'text-center');
            $borrar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" style="opacity: '.$opacity.'" onclick="'.$deleteHora.'">
                               <i class="mdi mdi-delete"></i>
                           </button>';
            $row_4   = array('data' => $borrar, 'class' => 'text-center');
    
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $i++;
        }
    
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableSedesRuta')) {
    function _createTableSedesRuta($sedesRutaEvento){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbSedesRuta">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-left');
        $head_0   = array('data' => 'Sede', 'class' => 'text-left');
        $head_2   = array('data' => 'Subdirector', 'class' => 'text-left');
        $head_3   = array('data' => 'Acci&oacute;n.', 'class' => 'text-center');
        $head_4   = array('data' => 'Orden', 'class' => 'text-center');
        $CI->table->set_heading($head_0_1, $head_0, $head_2,/*$head_1, */ $head_3, $head_4);
        $i = 1;
        foreach ($sedesRutaEvento as $row){
            $idSede = _simple_encrypt($row->nid_sede);
            
            $row_0_1 = array('data' => $i, 'class' => 'text-left');
            $row_0   = array('data' => $row->desc_sede, 'class' => 'text-left');
            $row_2   = array('data' => '<img alt="Student" src="'.$row->foto_persona.'" WIDTH=25 HEIGHT=25 class="img-circle m-r-5" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombrecompleto.'">'.$row->nombreabreviado, 'class' => 'text-left');
            $disabled     = 'disabled';
            $onclickBtn   = null;
            $onchangeSede = null;
            $opacity      = '0.4';
            if(_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_VER){
                $disabled     = 'disabled';
                $onclickBtn   = null;
                $onchangeSede = null;
                $opacity      = '0.4';
            }
    
            if((_getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_SUBDIRECTOR && $CI->m_utils->getById("admision.evento", "id_persona_registro", "id_evento", _getSesion('id_evento_detalle')) == _getSesion('nid_persona'))
                || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_MARKETING || _getSesion('accionDetalleEvento') == ACCION_DETALLE_EVENTO_EDITAR && _getSesion(ADMISION_ROL_SESS) == ID_ROL_PROMOTOR){
                $disabled     = null;
                $opacity      = '1';
                $onclickBtn   = 'moveRow(this, \''.$idSede.'\', \''._simple_encrypt($row->orden).'\')';
                $onchangeSede = 'changeSedeRuta(\''.$idSede.'\')';
            }
    
    
    
            $usar = '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="check_sede_ruta'.$i.'">
                              <input type="checkbox" id="check_sede_ruta'.$i.'" onchange="'.$onchangeSede.'" class="mdl-switch__input" '.$row->checked.' '.$disabled.'>
                              <span class="mdl-switch__label"></span>
                         </label>';
            $row_4   = array('data' => $usar, 'class' => 'text-center');
    
    
            $botonArriba = '<button class="mdl-button mdl-js-button mdl-button--icon up" onclick="'.$onclickBtn.'" style="opacity:'.$opacity.'"><i class="mdi mdi-arrow_drop_up"></i></button>';
            $botonAbajo = '<button class="mdl-button mdl-js-button mdl-button--icon down" onclick="'.$onclickBtn.'" style="opacity:'.$opacity.'"><i class="mdi mdi-arrow_drop_down"></i></button>';
            if($i == 1){
                $botonArriba = null;
            }
            if($i == $row->cant_ruta_sede){
                $botonAbajo = null;
            }
    
            if($row->checked == null){
                $botonAbajo  = null;
                $botonArriba = null;
            }
            $row_5  = array('data' => $botonArriba.$botonAbajo != null ? $botonArriba.$botonAbajo:'-' , 'class' => 'text-center');
    
            $CI->table->add_row($row_0_1, $row_0, $row_2, $row_4, $row_5);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableInvitar')) {
    function _createTableInvitar($familiares, $horas, $tipoEvento){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbFamiliaresAInivitar">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Teléfono', 'class' => 'text-left');
        $head_3 = array('data' => 'Correo', 'class' => 'text-left');
        $head_4 = array('data' => 'Hora citada', 'class' => 'text-left');
        $head_5 = array('data' => 'Invitar', 'class' => 'text-center');
        if($horas != null){
            $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        }else{
            $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_5);
        }
        $i = 1;
        $idEventoEnc = _simple_encrypt(_getSesion('id_evento_detalle'));
        foreach ($familiares as $row){
            $idContactoEnc = _simple_encrypt($row->id_contacto);
    
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $row_1 = array('data' => $row->nombrecompleto.' ('.$row->parentesco.')' , 'class' => 'text-left');
            $row_2 = array('data' => $row->telefono_celular , 'class' => 'text-left');
            $row_3 = array('data' => $row->correo , 'class' => 'text-left');
    
            $invt = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="check_familia_invitar_'.$i.'">
                             <input type="checkbox" id="check_familia_invitar_'.$i.'" class="mdl-checkbox__input persona_invitar" data-id-contacto="'.$idContactoEnc.'"
                                    onchange="habilitarSelectHoraCitada('.$i.', this)" data-id-select="selectHoraCitadaInvitado'.$i.'" '.$row->check.'>
                             <span class="mdl-checkbox__label"></span>
                         </label>';
            $row_5 = array('data' => $invt , 'class' => 'text-center');
    
    
            if($horas != null && $tipoEvento == TIPO_EVENTO_EVALUACION){
                $comboHorarios = __buildComboHorarios($horas, $row->id_hora_cita);
                $select = '<select class="form-control selectButton" data-live-search="true" id="selectHoraCitadaInvitado'.$i.'" name="selectHoraCitadaInvitado'.$i.'" '.$row->hora.'>
    		                  <option value="">Selecciona un horario</option>
                              '.$comboHorarios.'
    		               </select>';
                $row_4 = array('data' => $select , 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
            }else{
                $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_5);
            }
    
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableNivelesGrados')) {
    function _createTableNivelesGrados($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbNivelesGradoConfig">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_1 = array('data' => 'Nivel', 'class' => 'text-left');
        $head_2 = array('data' => 'Grado', 'class' => 'text-left');
        $head_3 = array('data' => 'Cursos', 'class' => 'text-right');
        $head_4 = array('data' => 'Acción', 'class' => 'text-center');
        $CI->table->set_heading($head_1, $head_2, $head_3, $head_4);
        $i = 1;
        foreach ($data as $row){
            $idGrado = _simple_encrypt($row->nid_grado);
            $row_1   = array('data' => $row->desc_nivel, 'class' => 'text-left');
            $row_2   = array('data' => $row->desc_grado, 'class' => 'text-left');
            $row_3   = array('data' => $row->cant_cursos, 'class' => 'text-right');
            $btnCursos =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="verCursos($(this), \''.$idGrado.'\', \''.$row->abrev_grado.' '.$row->abrev_nivel.'\')">
                               <i class="mdi mdi-visibility"></i>
                           </button>';
            $row_4   = array('data' => $btnCursos, 'class' => 'text-center');

            $CI->table->add_row($row_1, $row_2, $row_3, $row_4);
            $i++;
        }

        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableCursosNivelesGrados')) {
    function _createTableCursosNivelesGrados($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbCursosNivelesGradoConfig">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Descripción', 'class' => 'text-left');
        $head_2 = array('data' => 'Persona Reg.', 'class' => 'text-left');
        $head_3 = array('data' => 'Fecha Reg.', 'class' => 'text-center');
        $head_5 = array('data' => 'Activar', 'class' => 'text-center');
        $head_6 = array('data' => 'Acción', 'class' => 'text-center p-r-15 p-l-15');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_5, $head_6);
        $i = 1;
        foreach ($data as $row){
            $idConfig = _simple_encrypt($row->id_config_eval);
            $row_0   = array('data' => $i, 'class' => 'text-left');
            $row_1   = array('data' => $row->descripcion, 'class' => 'text-left');
            $row_2   = array('data' => '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.((file_exists(FOTO_PROFILE_PATH.'estudiantes/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'estudiantes/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombrecompletousuariocambio.'">'.$row->nombrecompletousuariocambio_1, 'class' => 'text-left');
            $row_3   = array('data' => _fecha_tabla($row->fecha_modi, 'd/m/Y'), 'class' => 'text-center');
            $btnUpload =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalConfiguracionGeneral(\''.$idConfig.'\', \''.$row->descripcion.'\', \''.$row->titulo_observacion.'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Config. General">
                               <i class="mdi mdi-settings"></i>
                           </button>';
            $opc = ($row->flg_activo) ? "checked" : null;
            $check = '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="check-'.$i.'">
                          <input type="checkbox" id="check-'.$i.'" class="mdl-switch__input" '.$opc.' onchange="changeEstado(\''.$idConfig.'\')">
                          <span class="mdl-switch__label"></span>
                      </label>';
            $row_5   = array('data' => $check, 'class' => 'text-center');
            $btnCursos =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="abrirModalDeleteCurso(\''.$idConfig.'\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Eliminar">
                               <i class="mdi mdi-delete"></i>
                           </button>';
            $row_6   = array('data' => $btnUpload.$btnCursos, 'class' => 'text-center');

            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_5, $row_6);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableResumenDiagnostico')) {
    function _createTableResumenDiagnostico($data){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbResumenDiagnostico">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Evaluaci&oacute;n', 'class' => 'text-left');
        $head_3 = array('data' => 'Evaluador', 'class' => 'text-left');
        $head_4 = array('data' => 'Diagn&oacute;stico', 'class' => 'text-left');
        $head_5 = array('data' => 'Fecha', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_3, $head_4, $head_5);

        $i = 1;
        foreach ($data as $row){
            $class = null;
            if($row->fecha_registro != null){
                $class = 'default';
            }
            $row_0 = array('data' => $i , 'class' => 'text-left '.$class);
            $row_1 = array('data' => $row->descripcion , 'class' => 'text-left '.$class);
            $foto = null;
            if($row->fecha_registro != null){
                $foto = $row->foto_persona_google;
                if($foto == null){
                    $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'colaboradores/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg");
                }
                $foto = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'">';
            }
            $row_2 = array('data' => $foto.$row->evaluador , 'class' => 'text-left '.$class);
            $row_3 = array('data' => $row->diagnostico_final , 'class' => 'text-left '.$class);
            $row_4 = array('data' => $row->fecha_registro != null ? _fecha_tabla($row->fecha_registro, 'd/m/Y h:i A') : '-' , 'class' => 'text-center '.$class);
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableIndicadoresCurso')) {
    function _createTableIndicadoresCurso($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbTemasCurso">',
	                 'table_close' => '</table>');
	    $CI->table->set_template($tmpl);
	    $head_0 = array('data' => '#', 'class' => 'text-left');
	    $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
	    $head_3 = array('data' => 'Acción', 'class' => 'text-center');
	    $CI->table->set_heading($head_0, $head_2, $head_3);
	    $i = 1;
	    if(count($data) > 0){
	        foreach ($data as $var){
	            $row_0   = array('data' => $i, 'class' => 'text-left');
	            $row_2   = array('data' => $var->descripcion, 'class' => 'text-left');
	            $btnEdit =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="modalDeleteIndicadoresCurso(\''._simple_encrypt($var->id).'\', 1)">
                               <i class="mdi mdi-delete"></i>
                           </button>';
	            $row_3   = array('data' => $btnEdit, 'class' => 'text-center');
	            $CI->table->add_row($row_0, $row_2, $row_3);
	            $i++;
	        }
	    }
	    $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableOpcionesCurso')) {
    function _createTableOpcionesCurso($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbOpcionesCurso">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_3 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_2, $head_3);
        $i = 1;
        if(count($data) > 0){
            foreach ($data as $var){
                $row_0   = array('data' => $i, 'class' => 'text-left');
                $row_2   = array('data' => $var->descripcion, 'class' => 'text-left');
                $btnEdit =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="modalDeleteIndicadoresCurso(\''._simple_encrypt($var->id).'\', 2)">
                               <i class="mdi mdi-delete"></i>
                           </button>';
                $row_3   = array('data' => $btnEdit, 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_2, $row_3);
                $i++;
            }
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableOpcionesIndicador')) {
    function _createTableOpcionesIndicador($data){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbOpcionesIndicador">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_2 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_3 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_2, $head_3);
        $i = 1;
        if(count($data) > 0){
            foreach ($data as $var){
                $row_0   = array('data' => $i, 'class' => 'text-left');
                $row_2   = array('data' => $var->descripcion, 'class' => 'text-left');
                $btnEdit =  '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" onclick="modalDeleteIndicadoresCurso(\''._simple_encrypt($var->id).'\', 3)">
                               <i class="mdi mdi-delete"></i>
                           </button>';
                $row_3   = array('data' => $btnEdit, 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_2, $row_3);
                $i++;
            }
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableIndicadoresCursoResultado')) {
    function _createTableIndicadoresCursoResultado($data, $j, $resp){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   id="tbIndicadoresResultado">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_2 = array('data' => 'Resultado', 'class' => 'text-left');
        $CI->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        if(count($data) > 0){
            foreach ($data as $var){
                $row_0   = array('data' => $i, 'class' => 'text-left');
                $row_1   = array('data' => $var->descripcion, 'class' => 'text-left');
                $desc = null;
                if($resp != null){
                    foreach($resp as $rep){
                        if($rep->indicador == $var->descripcion){
                            $desc = $rep->valor;
                        }
                    }  
                }
                $row_2   = array('data' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <textarea class="mdl-textfield__input indicador'.$j.'" id="resIndicador'.$i.'" type="text" rows= "3" attr-desc="'._simple_encrypt($var->descripcion).'" style="resize:none">'.$desc.'</textarea>       
                                                <label class="mdl-textfield__label"></label>
                                            </div>', 'class' => 'text-center');
                $CI->table->add_row($row_0, $row_1, $row_2);
                $i++;
            }
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableDiagnosticoSubdirector')) {
    function _createTableDiagnosticoSubdirector($data){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbDiagnosticoSubdirector">',
                     'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => 'Evaluador', 'class' => 'text-left');
        $head_1 = array('data' => 'Fecha', 'class' => 'text-left');
        $head_2 = array('data' => 'Diagn&oacute;stico', 'class' => 'text-left');
        $CI->table->set_heading($head_0, $head_2, $head_1);
        $class = null;
        if($data['fecha_registro'] != null){
            $class = 'default';
        }
        $foto = null;
        if($data['fecha_registro'] != null){
            $foto = $data['foto_persona_google'];
            if($foto == null){
                $foto = ((file_exists(FOTO_PROFILE_PATH.'colaboradores/'.$data['foto_persona']   )) ? RUTA_IMG_PROFILE.'colaboradores/'.$data['foto_persona'] : RUTA_IMG_PROFILE."nouser.svg");
            }
            $foto = '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$foto.'">';
        }
        $row_0 = array('data' => $data['evaluador'] != null ?  $foto.$data['evaluador'] : '-', 'class' => 'text-left '.$class);
        $row_1 = array('data' => $data['fecha_registro'] != null ? _fecha_tabla($data['fecha_registro'], 'd/m/Y h:i A') : '-' , 'class' => 'text-left '.$class);
        $row_2 = array('data' => $data['diagnostico'] != null ? $data['diagnostico'] : '-', 'class' => 'text-left '.$class);
        $CI->table->add_row($row_0, $row_2, $row_1);
        $table = $CI->table->generate();
        
        
        return $table;
    }
}

if(!function_exists('_creteTableAsistenciaFamilia')) {
    function _creteTableAsistenciaFamilia($familiares, $idEvento, $tipo = 0){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbEditarAsistenciaFamiliaDRA">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Asistencia', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        $idEventoEnc = _simple_encrypt($idEvento);
        foreach ($familiares as $row){
            $idContactoEnc = _simple_encrypt($row->id_contacto);
            $asist = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkboxEditarFam-'.$i.'">
                          <input type="checkbox" id="checkboxEditarFam-'.$i.'" class="mdl-checkbox__input checkAsistenciaFam" attr-cont="'.$idContactoEnc.'" attr-tipo="'._simple_encrypt($row->flg_estudiante).'" '.$row->asistencia.'>
                          <span class="mdl-checkbox__label"></span>
                      </label>';
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $row_1 = array('data' => $row->nombrecompleto.' '.$row->desc_post, 'class' => 'text-left');
            $row_2 = array('data' => $asist , 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableHorasCitaPostulantes')) {
    function _createTableHorasCitaPostulantes($postulantes){
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbPostulantesHorario">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Grado', 'class' => 'text-left');
        $CI->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        foreach ($postulantes as $row){
            $row_0   = array('data' => $i, 'class' => 'text-left');
            $row_1   = array('data' => $row->nombrecompleto, 'class' => 'text-left');
            $row_2   = array('data' => $row->desc_grado.' ('.$row->desc_nivel.')', 'class' => 'text-left');
            $CI->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }

        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_create_cards_evaluacion')) {
    function _create_cards_evaluacion($grupo, $secretaria = null){
        $CI =& get_instance();
        $cons_cod_grupo           = null;
        $cons_cont_hijos          = null;
        $cons_cont_cabe_hijos     = null;
        $cons_cont_parientes      = null;
        $cons_cont_cabe_parientes = null;
        $cardsFamilia             = null;
        $cont_card_damilia_last   = null;
        
        $i = 1;
        foreach ($grupo as $grp){
            $idContactoEncryptado = _simple_encrypt($grp->id_contacto);
        
            if($cons_cod_grupo == null){
                $cons_cod_grupo = $grp->cod_grupo;
            }
            
            $codGrupoEncryptado = _simple_encrypt($grp->cod_grupo);
            
            if($cons_cod_grupo == $grp->cod_grupo){
                if($grp->flg_estudiante == FLG_FAMILIAR){
                    $active = null;
                    if($i == 1){
                        $active = 'is-active';
                    }
                    
                    $cons_cont_cabe_parientes .= '<a href="#par'.$grp->id_contacto.'" class="mdl-tabs__tab '.$active.'">Madre<span class="mdl-tabs__ripple-container mdl-js-ripple-effect">
				                                  <span class="mdl-ripple"></span></span></a>';
                    
                    if($grp->telefono_celular != null){
                        $telfNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->telefono_celular;
                    } else {
                        $telfNulo = '>-';
                    }
                    
                    if($grp->correo != null){
                        $correoNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->correo;
                    } else {
                        $correoNulo = '>-';
                    }
                    $canal_com="-";
                    if($grp->canal_comunicacion != null){
                        $canal_com = $grp->canal_comunicacion;
                    }
                    
                    $cons_cont_parientes .= '<div class="mdl-tabs__panel '.$active.'" id="par'.$grp->id_contacto.'">
                                                    <div class="row">
                                                        <div class="col-xs-12 inscrito-title">'.utf8_encode($grp->nombrecompleto).'</div>
                                                        <div class="col-xs-4 inscrito-item" >Celular</div>
                                                        <div class="col-xs-8 inscrito-value" '.$telfNulo.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Correo</div>
                                                        <div class="col-xs-8 inscrito-value" '.$correoNulo.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Canal com.</div>
                                                        <div class="col-xs-7 inscrito-value">'.$canal_com.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Persona Reg.</div>
                                                        <div class="col-xs-7 inscrito-value">'.(isset($grp->persona_registro)?$grp->persona_registro:null).'</div>
                                                    </div>
                                                </div>';
                    $i++;
                }
            }else{
                $cons_cod_grupo = $grp->cod_grupo;
                $cons_cont_cabe_parientes = null;
                $cons_cont_parientes = null;
                $i = 1;
                if($grp->flg_estudiante == FLG_FAMILIAR){
                    $active = null;
                    if($i == 1){
                        $active = 'is-active';
                    }
                    $cons_cont_cabe_parientes .= '<a href="#par'.$grp->id_contacto.'" class="mdl-tabs__tab '.$active.'">'.$grp->desc_parentesco.'<span class="mdl-tabs__ripple-container mdl-js-ripple-effect">
				                                  <span class="mdl-ripple"></span></span></a>';
                
                    if($grp->telefono_celular != null){
                        $telfNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->telefono_celular;
                    } else {
                        $telfNulo = '>-';
                    }
                    
                    if($grp->correo != null){
                        $correoNulo = 'style="display: inline; cursor:pointer; text-decoration: underline" onclick ="abrirModalLlamadas(\''.$idContactoEncryptado.'\',\''._simple_encrypt($grp->telefono_celular).'\',\''._simple_encrypt($grp->correo).'\')">'.$grp->correo;
                    } else {
                        $correoNulo = '>-';
                    }
                    $canal_com="-";
                    if($grp->canal_comunicacion != null){
                        $canal_com = $grp->canal_comunicacion;
                    }
                    
                    $cons_cont_parientes .= '<div class="mdl-tabs__panel '.$active.'" id="par'.$grp->id_contacto.'">
                                                    <div class="row">
                                                        <div class="col-xs-12 inscrito-title">'.utf8_encode($grp->nombrecompleto).'</div>
                                                        <div class="col-xs-4 inscrito-item" >Celular</div>
                                                        <div class="col-xs-8 inscrito-value" '.$telfNulo.'</div>
                                                        <div class="col-xs-4 inscrito-item" >Correo</div>
                                                        <div class="col-xs-8 inscrito-value" '.$correoNulo.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Canal com.</div>
                                                        <div class="col-xs-7 inscrito-value">'.$canal_com.'</div>
                                                        <div class="col-xs-5 inscrito-item" >Persona Reg.</div>
                                                        <div class="col-xs-7 inscrito-value">'.(isset($grp->persona_registro)?$grp->persona_registro:null).'</div>
                                                    </div>
                                                </div>';
                    $i++;
                }
            }
            
            if($grp->flg_estudiante == FLG_ESTUDIANTE){
                $detalleEval = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="verDiagnosticoTabla(\''.$idContactoEncryptado.'\', \''.$grp->nombrecompleto.'\')">
                                    <i class="mdi mdi-timeline" style="color: #FFFFFF; font-size: 18px"></i>
                                </button>';
                $cons_cont_hijos = '<div id="child-'.$grp->id_contacto.'" class="tab-pane fade in active">
                                        <div class="row">
                                            <div class="col-xs-12 inscrito-title">'.$grp->nombrecompleto.'</div>
                                                    <div class="col-xs-12 inscrito-title lastname">'.$grp->apellidos.'</div>
                                                    <div class="col-xs-12 inscrito-title name">'.$grp->nombres.'</div>
                                                    <div class="col-xs-7 inscrito-item p-t-5" >Detalles del Inscrito</div>
                                                    <div class="col-xs-5 inscrito-value">'.$detalleEval.'</div>
                                                    <div class="col-xs-6 inscrito-item" >Sede de inter&eacute;s</div>
                                                    <div class="col-xs-6 inscrito-value">'.$grp->desc_sede.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Nivel</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->desc_nivel.'</div>
                                                    <div class="col-xs-4 inscrito-item" >Grado</div>
                                                    <div class="col-xs-8 inscrito-value">'.$grp->abvr_grado.'</div>
                                        </div>
                                    </div>';
                $cons_cont_cabe_hijos = '<li class="active" data-id-contacto="'.$idContactoEncryptado.'" data-cod-grupo="'.$codGrupoEncryptado.'">
                                                <a data-toggle="pill" href="#child-'.$grp->id_contacto.'">
                                					<img alt="Estudiante" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg">
                                                </a>
                                          </li>';

                //TARJETA FINAL
                $btn = '<div class="mdl-card__actions" id="btnGroup'.$cons_cod_grupo.'"">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised big" onclick="goToEditContacto(this)" id="btnEdit804">evaluar<span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></button>
                        </div>';
                if($secretaria != null){
                    $btn = '<div class="mdl-card__actions" id="btnGroup'.$cons_cod_grupo.'"">
                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised big" onclick="abrirModalProcesoMatricula(\''.$idContactoEncryptado.'\')" id="btnEdit804">Proceso Matr&iacute;cula<span class="mdl-button__ripple-container"><span class="mdl-ripple"></span></span></button>
                            </div>';
                }
                
                $cardsFamilia = '<div class="mdl-card mdl-inscritos mdl-card__new" id="mdl-inscritos-cont-'.$grp->id_contacto.'">
                                     <div class="mdl-card__title">
                                         <ul class="nav nav-pills">'
                                         .$cons_cont_cabe_hijos.
                                        '</ul>
                                      </div>
                                          <div class="mdl-card__supporting-text">
                                              <div class="tab-content">'
                                              .$cons_cont_hijos.
                                         '</div>'.
                                         '<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                                         <div class="mdl-tabs__tab-bar">'
                                              .$cons_cont_cabe_parientes.
                                         '</div>'
                                          .$cons_cont_parientes.
                                          '</div>'.
                                     '</div>
                                         '.$btn.'
                                         </div>';
                
                $cont_card_damilia_last .= $cardsFamilia;
            }
        }
        
        $data['cardsFamilia'] = $cont_card_damilia_last;
        return $data;
    }
}

if(!function_exists('_createTableEventosEnlazados')) {
    function _createTableEventosEnlazados($EventosEnlazados){
        //_log(print_r($EventosEnlazados,true));
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
											   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
											   id="tbEventosEnlazados">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_0 = array('data' => '#', 'class' => 'text-right');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-center');
        $head_2 = array('data' => 'Fecha Realizar', 'class' => 'text-center');
        $head_3 = array('data' => 'Hora de Inicio', 'class' => 'text-center');
        $head_4 = array('data' => 'Hora de Fin', 'class' => 'text-center');
        $head_5 = array('data' => 'Estado', 'class' => 'text-center');
        $head_6 = array('data' => 'Ir Evento', 'class' => 'text-center');

        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $i = 1;
        foreach ($EventosEnlazados as $row){
            $idEventoEnc = _simple_encrypt($row->id_evento);
            $row_0   = array('data' => $i, 'class' => 'text-center');
            $row_1   = array('data' => $row->desc_evento, 'class' => 'text-center');
            $row_2   = array('data' => date('d/m/Y', strtotime($row->fecha_realizar)), 'class' => 'text-center');
            $row_3   = array('data' => date('H:i', strtotime($row->hora_inicio)), 'class' => 'text-center');
            $row_4   = array('data' => date('H:i', strtotime($row->hora_fin)), 'class' => 'text-center');
            $row_5   = array('data' => $row->estado, 'class' => 'text-center');
            $btnIr =  '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="irDetalleEvento(\''.$idEventoEnc.'\');">
				   <i class="glyphicon glyphicon-menu-right"></i>
				   </button>';
            $row_6   = array('data' => $btnIr, 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
    
}
if(!function_exists('_createTableInvitados')) {
    function _createTableInvitados($Invitados){
        //_log(print_r($EventosEnlazados,true));
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
											   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
											   id="tbInvitados">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_0 = array('data' => '#', 'class' => 'text-right');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-center');
        $head_2 = array('data' => 'Apellidos', 'class' => 'text-center');
        $head_3 = array('data' => 'Fecha Nacimiento', 'class' => 'text-center');
        $head_4 = array('data' => 'Teléfono', 'class' => 'text-center');
        
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $i = 1;
        foreach ($Invitados as $row){
            //$idEventoEnc = _simple_encrypt($row->nid_persona);
            $row_0   = array('data' => $i, 'class' => 'text-center');
            $row_1   = array('data' => (($row->nombres == null) ? '-' : $row->nombres), 'class' => 'text-center');
            $row_2   = array('data' => (($row->apellidocompleto == null) ? '-' : $row->apellidocompleto), 'class' => 'text-center');
            $row_3   = array('data' => (($row->fecha_nacimiento == null) ? '-' : _fecha_tabla($row->fecha_nacimiento, 'd/m/Y')) , 'class' => 'text-center');
            $row_4   = array('data' => (($row->telefono_celular == null) ? '-' : $row->telefono_celular), 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }

}
if(!function_exists('_createTableColaboradores')) {
    function _createTableColaboradores($Colaboradores){
        //_log(print_r($EventosEnlazados,true));
        $CI =& get_instance();
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
											   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
											   id="tbColaboradores">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);

        $head_0 = array('data' => '#', 'class' => 'text-right');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-center');
        $head_2 = array('data' => 'Apellidos', 'class' => 'text-center');
        $head_3 = array('data' => 'Fecha Nacimiento', 'class' => 'text-center');
        $head_4 = array('data' => 'Telefono', 'class' => 'text-center');

        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $i = 1;
        foreach ($Colaboradores as $row){
            //$idEventoEnc = _simple_encrypt($row->nid_persona);
            $row_0   = array('data' => $i, 'class' => 'text-center');
            $row_1   = array('data' => (($row->nombre == null) ? '-' : $row->nombre), 'class' => 'text-center');
            $row_2   = array('data' => (($row->apellidocompleto == null) ? '-' : $row->apellidocompleto), 'class' => 'text-center');
            $row_3   = array('data' => (($row->fec_naci == null) ? '-' : _fecha_tabla($row->fec_naci, 'd/m/Y')), 'class' => 'text-center');
            $row_4   = array('data' => (($row->telf_pers == null) ? '-' : $row->telf_pers), 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }

}
if(!function_exists('_createTableAuxiliaresApoyoAdministrativoSede')) {
    function _createTableAuxiliaresApoyoAdministrativoSede($auxiliares){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbBusquedaAuxiliaresApoyoAdmSede">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Teléfono', 'class' => 'text-left');
        $head_3 = array('data' => 'Correo', 'class' => 'text-left');
        $head_4 = array('data' => 'Asignar', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);

        $i = 1;
        foreach ($auxiliares as $row){
            $idPersonaEnc = _simple_encrypt($row->nid_persona);
            $row_0 = array('data' => $i , 'class' => 'text-left');
            $row_1 = array('data' => '<img class="img-circle m-r-5" WIDTH=25 HEIGHT=25 src="'.$row->foto_persona.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$row->nombrecompleto.'">'.$row->nombreabreviado, 'class' => 'text-left');
            $row_2 = array('data' => $row->telf_pers   , 'class' => 'text-left');
            $row_3 = array('data' => $row->correo , 'class' => 'text-left');

            $asignar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"
                                 onclick="abrirModalAsignarApoyoAdmSede(\''.$idPersonaEnc.'\')">
                            <i class="mdi mdi-add"></i>
                        </button>';
            $row_4 = array('data' => $asignar , 'class' => 'text-center');
            $CI->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);

            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableRecursosHumanosSede')) {
    function _createTableRecursosHumanosSede($recursos){
        $CI =& get_instance();
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                                   id="tbRecursosHumanosSede">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0_1 = array('data' => '#', 'class' => 'text-center');
        $head_0   = array('data' => 'Nombre', 'class' => 'text-left');
        $head_1   = array('data' => 'Correo', 'class' => 'text-left');
        $head_2   = array('data' => 'Tel&eacute;fono', 'class' => 'text-left');
        $head_3   = array('data' => 'Agregado por', 'class' => 'text-left');
        $head_4   = array('data' => 'Fecha Reg.', 'class' => 'text-left');
        $head_5   = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $CI->table->set_heading($head_0_1, $head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        $i = 1;
        foreach ($recursos as $row){
            $idPersonaEnc = _simple_encrypt($row->nid_persona);$row_0_1 = $i;
            $row_0   = array('data' => $row->nombreabreviado ,'class' => 'text-left');
            $row_1   = array('data' => $row->correo ,'class' => 'text-left');
            $row_2   = array('data' => $row->telf_pers ,'class' => 'text-left');
            $row_3   = array('data' => $row->persona_reg ,'class' => 'text-left');
            $row_4   = array('data' => _fecha_tabla($row->fecha_reg, 'd/m/Y') ,'class' => 'text-left');
            $borrar = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" data-toggle="tooltip" data-placement="bottom" data-original-title="Eliminar"
                                 onclick="abrirModalDeleteApoyoAdmSede(\''.$idPersonaEnc.'\')">
                            <i class="mdi mdi-delete"></i>
                        </button>';
            $row_5   = array('data' => $borrar, 'class' => 'text-center');

            $CI->table->add_row($row_0_1, $row_0, $row_1, $row_2, $row_3, $row_4, $row_5);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}

if(!function_exists('_createTableParientesBusqueda')) {
    function _createTableParientesBusqueda($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
		                                   id="tablaParientesBusqueda">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0= array('data'=> '#', 'class' => 'text-left');
        $head_1= array('data'=> 'Apellidos', 'class' => 'text-left');
//         $head_2= array('data'=> 'C&oacute;digo', 'class' => 'text-center');
        $head_3= array('data'=> 'Asignar', 'class' => 'text-center');
        $CI->table->set_heading($head_0, $head_1, $head_3);
        $i = 0;
        foreach ($data as $row){
        	$i++;
            $codFamEnc = _simple_encrypt($row->cod_familia);
            $row_0= array('data'=> $i);
            $row_1= array('data'=> $row->apellidoscompleto);
//             $row_2= array('data'=> $row->cod_familia);
            $btnAsignar       = '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="abrirModalConfirmAsignarFamilia(\''.$codFamEnc.'\', \''.$row->apellidoscompleto.'\')"><i class="mdi mdi-add"></i></a>';
            $btnVerFamiliares = '<a class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" onclick="verFamiliares(\''.$codFamEnc.'\')"><i class="mdi mdi-group"></i></a>';
            $row_3= array('data'=> $btnAsignar.$btnVerFamiliares);
    
            $CI->table->add_row($row_0, $row_1, $row_3);
    	
        }
        $table = $CI->table->generate();
         
        return $table;
    }
}

if(!function_exists('_createTableFamiliaresByCodFam')) {
    function _createTableFamiliaresByCodFam($data){
        $CI =& get_instance();
        $tmpl = array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tablaFamiliaresByCodFam">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_1 = array('data'=> 'Nombres');
        $head_2 = array('data'=> 'Parentesco');
//         $head_3 = array('data'=> 'Cod. Familia');
        $CI->table->set_heading($head_1, $head_2);
        $i = 0;
        foreach ($data as $row){
        	$foto = '<img src="'.((file_exists(FOTO_PROFILE_PATH.'familiares/'.$row->foto_persona)) ? RUTA_IMG_PROFILE.'familiares/'.$row->foto_persona : RUTA_IMG_PROFILE."nouser.svg").'" 
        			id="fotoFamiliar'.$i.'" class="img-circle m-r-10" alt="Parent" WIDTH=30 HEIGHT=30>';        	
            $row_1 = array('data'=> $foto.$row->nombrecompleto);
            $row_2 = array('data'=> $row->parentesco);
//             $row_3 = array('data'=> $row->cod_familiar);
            $CI->table->add_row($row_1, $row_2);
            $i++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}