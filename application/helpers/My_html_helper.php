<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__buildArbolPermisos')) {
    function __buildArbolPermisos($idRol, $idSistema, $modulo) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $arbol = null;
        $arbolPadres = null;        
        $hijos = $CI->m_usuario->getPermisosBySistemaRol($idRol, $idSistema);
        $var = 0;
        foreach ($hijos as $hijo) {
            $arbol .= '<li><a  class="mdl-button mdl-js-button mdl-js-ripple-effect" href="'.base_url().$modulo.'/'.$hijo->id_obj_html.'"><i class="mdi mdi-'.$hijo->css_icon.'"></i> '.$hijo->desc_permiso.'</a></li>';
            $var++;
        }
        $pie = '</ul>
            </li>';
        return $arbol;
    }
}

if(!function_exists('__buildArbolPermisosBase')) {
    function __buildArbolPermisosBase($idPersona) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $arbol = null;
        $hijos = $CI->m_usuario->getPermisoBaseByPersona($idPersona);
        $var = 0;
        foreach ($hijos as $hijo) {
            $arbol .= '<li>
                           <a  class="mdl-button mdl-js-button mdl-js-ripple-effect" href="'.base_url().$hijo->id_obj_html.'">
                               <i class="mdi mdi-'.$hijo->css_icon.'"></i> '.$hijo->desc_permiso.'</a></li>';
            $var++;
        }
        $pie = '</ul>
            </li>';
        return $arbol;
    }
}

if(!function_exists('__buildModulosByRol')) {
    function __buildModulosByRol($rolSistemas, $idPersona) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $result = "";
        $idDesc = null;
        $contador = 0;
        foreach ($rolSistemas as $var) {
            $roles     = $CI->m_usuario->getRolesOnlySistem($idPersona, $var->nid_sistema);
            $opcionesArry   = __rolesByModulo($roles, $var->url_sistema, $var->nid_sistema,$var->logo_sistema,$var->desc_sist);
            $opciones       = $opcionesArry[0];//HTML SI ES 1 solo viene el onclick
            $cantRoles      = $opcionesArry[1];//Contador de roles por modulo
            $opcionesHTML = null;
            $onclickHTML = null;
            $effectHTML = null;
            
            $not_app_style = null;
            $not_app_title = null;
            $app_icon  = "arrow_forward";
            
            if($cantRoles > 1) {
                $opcionesHTML = $opciones;
                $onclickHTML = 'onclick="openSistema(\'card-'.$contador.'\')"';
            } else if($cantRoles == 1) {
                $onclickHTML = $opciones;
                $effectHTML  = 'data-rippleria data-rippleria-duration="500"';
            }

            if( $var->nid_sistema == ID_SISTEMA_SEGURIDAD   || $var->nid_sistema == ID_SISTEMA_GEDUCA       || $var->nid_sistema == ID_SISTEMA_INSTRUMENTOS || 
                $var->nid_sistema == ID_SISTEMA_RRHH        || $var->nid_sistema == ID_SISTEMA_BIBLIOTECA   || $var->nid_sistema == ID_SISTEMA_JUEGOS       || 
                $var->nid_sistema == ID_SISTEMA_MOVILIDAD   || $var->nid_sistema == ID_SISTEMA_MEDICO       || $var->nid_sistema == ID_SISTEMA_COMEDOR      || 
                $var->nid_sistema == ID_SISTEMA_PSICOLOGIA  || $var->nid_sistema == ID_SISTEMA_JUEGOS ) {
                $not_app_style    = 'mdl-app__none';
                $not_app_title    = 'title="Pr&oacute;ximamente"';
                $onclickHTML      = null;
                $effectHTML       = null;
                $app_icon         = "lock";
            }
            
            $result .= '<div class="mdl-card mdl-app_content '.$not_app_style.'" id="card-'.$contador.'" '.$onclickHTML.' '.$not_app_title.'>
            		          <div class="mdl-card__supporting-text mdl-card__front" '.$effectHTML.'>
            		              <img src="'.base_url().$var->logo_sistema.'">
            		              <div class="mdl-app_text">
            		                  <label>'.$var->desc_sist.'</label>
            		                  <i class="mdi mdi-'.$app_icon.'"></i>
        		                  </div>
            		          </div>
            		          <div class="mdl-card__supporting-text mdl-card__back">
            		              <h4>'.$var->desc_sist.'</h4>
            		              <ul>
            		                  '.$opcionesHTML.'
            		              </ul>
            		          </div>
            		      </div>';
            $contador++;
        }
        if($result == null){
            $result = '<div class="img-search m-b-30" id="img_table_empty">
                           <img src="'.RUTA_IMG.'smiledu_faces/not_data_found.png">
                           <p>Ups! A&uacute;n no se han registrado datos.</p>
                       </div>';
        }
        return $result;
    }
}

if(!function_exists('__rolesByModulo')) {
    function __rolesByModulo($roles, $url, $idSistema, $flg_realizado, $desc_sist) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $opciones = null;
        $gris = null;
        if($flg_realizado == 0) {
            $gris = "convertirgris";
        }
        $rolesByModulo = array();
        $countRoles = count($roles);
        if($countRoles > 1) {
            foreach ($roles as $rol) {
                $opciones .= '<li data-rippleria data-rippleria-duration="500" onclick="modalGoToSistema(\''._encodeCI($idSistema).'\',\''._encodeCI($rol->nid_rol).'\');"><i class="mdi mdi-open_in_new"></i><label>'.$rol->desc_rol.'</label></li>';
            }
        } else if($countRoles == 1) {
            $opciones = 'onclick="modalGoToSistema(\''._encodeCI($idSistema).'\',\''._encodeCI($roles[0]->nid_rol).'\');"';
        }
        return array($opciones, $countRoles);
    }
}

if(!function_exists('_searchInputHTML')) {
    function _searchInputHTML($texto, $metodos = null) {
        $data['btnSearch'] = '<a type="button" class="mdl-button mdl-js-button mdl-button--icon" onclick="setFocus(\'#searchMagic\')" id="openSearch">
                                  <i class="mdi mdi-search md-0"></i>
                              </a>';
        $data['inputSearch'] = '<div class="mdl-header-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-search md-0"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield" id="cont_inputtext_busqueda">
                                        <input class="mdl-textfield__input" type="text" id="searchMagic" '.$metodos.'>
                                        <label class="mdl-textfield__label" for="searchMagic">'.$texto.'</label>
                                    </div>
                                    <div class="mdl-icon mdl-right">
                                        <a type="button" class="mdl-button mdl-js-button mdl-button--icon" id="closeSearch">
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    </div>
                                </div>';
        return $data;
    }
}


if(!function_exists('__bodyMensajeCambiarClave')) {
    function __bodyMensajeCambiarClave($datosCorreo) {
        $html = '<table style="border-collapse:collapse;width:500px" width="500" cellpadding="0" cellspacing="0">
                     <tbody>
                         <tr>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:middle;background-color:#000000">
                                 <table style="width:100%;border-collapse:collapse">
                                     <tbody>
                                         <tr>
                                             <td width="140px" style="width:140px;padding: 5px" align="left">
                                                 <img alt="Smiledu" src="'.RUTA_SMILEDU.'public/general/img/menu/logo-smiledu.png" border="0" style="border:0px;max-height:40px; float:left;" class="CToWUd">
                                                 <label style="color:#ffffff;font-size:24px; padding-top: 5px; padding-left: 5px; float:left;">Smiledu</label>
                                             </td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </td>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                         </tr>
                         <tr>
                             <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 0px 5px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:top;background-color:#fafafa;font-size:14px;color:#333333">
                                 <br>
                                 <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;font-size:19px">
                                     <p>¡Hola <b>'.$datosCorreo['nombres'].'!</b></p>
                                 </div>
                                 <br>
                                     Recibimos tu solicitud de cambio de clave, ingresa al siguiente link y actualiza tu clave.
                                     <p>Recuerda usar una dificil de descifrar.</p>
                                     Ten en cuenta que el link expira 5 minutos después de enviada tu solicitud.
                                 <br><br>
                                 <div>
                                     <table style="border-collapse:collapse;width:460px">
                                         <tbody>
                                             <tr>
                                                 <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:100%" valign="middle">
                                                     <b>Ingresa a este link:</b>
                                                     <br>
                                                     <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%"><a href="'.$datosCorreo['url'].'">Hazme Click</a></div>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                 <br>
                             </div>
                         </td>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 5px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;font-size:10px;color:#888888"><br>
                             Si no fuiste tú quien solicitó el cambio de clave, comunícate con el Administrador de la plataforma :)
                         </td>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                 </tbody>
             </table>';
        return '<div align="center" style="text-align:center;"><center>'.$html.'</center></div>';
    }
}

if(!function_exists('__bodyMensajeResetearClave')) {
    function __bodyMensajeResetearClave($datosCorreo) {
            $html = '<table style="border-collapse:collapse;width:500px" width="500" cellpadding="0" cellspacing="0">
                 <tbody>
                     <tr>
                         <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;background-color:#000000">
                             <table style="width:100%;border-collapse:collapse">
                                 <tbody>
                                     <tr>
                                         <td width="140px" style="width:140px;padding: 5px" align="left">
                                             <img alt="Smiledu" src="'.RUTA_SMILEDU.'public/general/img/menu/logo-smiledu.png" border="0" style="border:0px;max-height:40px; float:left;" class="CToWUd">
                                             <label style="color:#ffffff;font-size:24px; padding-top: 5px; padding-left: 5px; float:left;">Smiledu</label>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>
                         </td>
                         <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 0px 5px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:top;background-color:#fafafa;font-size:14px;color:#333333">
                             <br>
                             <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;font-size:19px">
                                 <p>¡Hola <b>'.$datosCorreo['nombres'].'!</b></p>
                             </div>
                             <br>
                                 Tu clave fue cambiada, recuerda cambiarla con regularidad, no la anotes en lugares visibles.
                                 <p>Todo lo que se haga con tu cuenta es tu responsabilidad, protege tus datos.</p>
                             <br>
                         </td>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 5px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;font-size:10px;color:#888888"><br>
                             Si no fuiste tú quien cambió la clave, comunícate con el Administrador de la plataforma :)
                         </td>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                 </tbody>
             </table>';
        return '<div align="center" style="text-align:center;"><center>'.$html.'</center></div>';
    }
}

if(!function_exists('__bodyMensajeEnvioCredenciales')) {
    function __bodyMensajeEnvioCredenciales($datosCorreo) {
        $html = '<table style="border-collapse:collapse;width:500px" width="500" cellpadding="0" cellspacing="0">
                 <tbody>
                     <tr>
                         <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;background-color:#000000">
                             <table style="width:100%;border-collapse:collapse">
                                 <tbody>
                                     <tr>
                                         <td width="140px" style="width:140px;padding: 5px" align="left">
                                             <img alt="Smiledu" src="'.RUTA_SMILEDU.'public/general/img/menu/logo-smiledu.png" border="0" style="border:0px;max-height:40px; float:left;" class="CToWUd">
                                             <label style="color:#ffffff;font-size:24px; padding-top: 5px; padding-left: 5px; float:left;">Smiledu</label>
                                         </td>
                                     </tr>
                                 </tbody>
                             </table>
                         </td>
                         <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 0px 5px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:top;background-color:#fafafa;font-size:14px;color:#333333">
                             <br>
                             <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;font-size:19px">
                                 <p>¡Hola <b>'.$datosCorreo['nombres'].'!</b></p>
                             </div>
                             <br>
                                 Estas son tus credenciales de acceso a la plataforma SMILEDU
                                 <p>Tu clave fue cambiada, recuerda cambiarla con regularidad, no la anotes en lugares visibles.</p>
                                 Todo lo que se haga con tu cuenta es tu responsabilidad, protege tus datos.
                             <br><br>
                             <div>
                                 <table style="border-collapse:collapse;width:460px">
                                     <tbody>
                                         <tr>
                                             <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                 <b>Usuario</b>
                                                 <br>
                                                 <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['usuario'].'</div>
                                             </td>
                                             <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                 <b>Clave</b>
                                                 <br>
                                                 <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['clave'].'</div>
                                             </td>
                                         </tr>
                                     </tbody>
                                 </table>
                                 <br>
                             </div>
                         </td>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 5px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;font-size:10px;color:#888888"><br>
                             Si no fuiste tú quien cambió la clave, comunícate con el Administrador de la plataforma :)
                         </td>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                 </tbody>
             </table>';
        return '<div align="center" style="text-align:center;"><center>'.$html.'</center></div>';
    }
}

if(!function_exists('__enviarFeedBack')) {
    function __enviarFeedBack($msj, $url, $nombre) {
        $fecha = date('Y-m-d H:i:s');
        $asunto			=	'Feedback Smiledu';
        $body			=	$msj."<br/><br/>".$url."<br/>".$nombre."<br/>".$fecha;
        __enviarEmail(CORREO_BASE, $asunto, $body);
    }
}

///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
//////////////////////////        PERMISOS SISTEMAS    ////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
if(!function_exists('__rolesSistem')) {
    function __rolesSistem($roles, $url, $idSistema, $flg_realizado, $desc_sist) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $opciones = null;
        $gris = null;
        if($flg_realizado == 0) {
            $gris = "convertirgris";
        }
        $idSistema = $CI->encrypt->encode($idSistema);
        foreach ($roles as $rol){
            $opciones .= '<li><a href="javascript:void(0)" onclick="modalGoToSistema(\''.$idSistema.'\',\''.$CI->encrypt->encode($rol->nid_rol).'\');"><i class="mdi mdi-open_in_new"></i>'.$rol->desc_rol.'</a></li>';
        }
        return $opciones;
    }
}

if(!function_exists('__createSistemas_x_rol')) {
    function __createSistemas_x_rol($rolSistemas) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $result = "";
        $idDesc = null;
        foreach ($rolSistemas as $var) {
            $roles     = $CI->m_usuario->getRolesOnlySistem($CI->session->userdata('id_persona'),$var->nid_sistema);
            $opciones  = __rolesSistem($roles, $var->url_sistema, $var->nid_sistema,$var->logo_sistema,$var->desc_sist);
            $result .= '<div class="col-sm-3 col-md-4 col-sm-5 col-xs-6">';
            $result .= '<div class="sistemas" style="margin-top:40px">';
            $result .= $opciones.'
                       <p style="color:#959595;text-align:center">'.__getDescReduce($var->desc_sist,20).'</p>';
            $result .= '</div>';
            $result .= '</div>';
        }
        return $result;
    }
}

if(!function_exists('__modalCreateSistemasByrol')) {
    function __modalCreateSistemasByRol($rolSistemas) {
        $CI =& get_instance();
        $CI->load->model('mf_usuario/m_usuario');
        $result = null;
        $contador = 0;
        foreach ($rolSistemas as $var) {
            $idSistema = _encodeCI($var->nid_sistema);
            $roles     = $CI->m_usuario->getRolesOnlySistem(_getSesion('nid_persona'),$var->nid_sistema);
            $opciones  = __rolesSistem($roles, $var->url_sistema, $var->nid_sistema, $var->flg_realizado, $var->desc_sist);

            $classSenc = "";
            $styleDesactivado = null;

            $result .= '<div class="col-md-3 col-sm-3 col-xs-6 ui-state-default" style="padding: 0 5px">
                            <div class="mdl-app mdl-card mdl-shadow--2dp" id="card-'.$contador.'">
                                <div class="mdl-card__title mdl_height_2x" >
                                    <img src="'.base_url().$var->logo_sistema.'">
                                </div>
                                <div class="mdl-card__actions closed">
                                    <div class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored">
                                        <ul class="mdl-nav">
                                            <li><a onclick="openPermisos(\'card-'.$contador.'\');">'.$var->desc_sist.'<i class="mdi mdi-arrow_forward"></i></a><span class="transparencia"></span></li>
                                            '.$opciones.'
                                        </ul>
                                    </div>
                                </div>
                                <div class="mdl-card__menu">
                            </div>
                        </div>
                    </div>';
            $contador++;
        }
        return $result;
    }
}