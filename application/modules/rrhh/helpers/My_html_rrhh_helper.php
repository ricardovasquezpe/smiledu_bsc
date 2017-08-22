<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('_createVistaPersonal')) {
    function _createVistaPersonal($personal){
        $html = null;
        foreach ($personal as $pers){
           $html .= '<div class="mdl-card mdl-collaborator">            
                        <div class="mdl-card__title">
                            <img alt="collaborator" src="'.RUTA_IMG.'profile/nouser.svg" id="fotoFamiliar1" class="mdl-img">
                        </div>
                                    
                        <div class="mdl-card__supporting-text">
                            <div class="row p-0 m-0">
                                <div class="col-xs-12 collaborator-lastname">'.$pers->apellidocompleto.'</div>
                                <div class="col-xs-12 collaborator-name">'.$pers->nom_persona.'</div>
                                <div class="col-xs-12 collaborator-rol">Administrador</div>
                                <div class="col-xs-12 collaborator-head"><strong>Detalles del colaborador</strong></div>
                                <div class="col-xs-3  collaborator-item">Sede</div>
                                <div class="col-xs-9  collaborator-value">-</div>
                                <div class="col-xs-3  collaborator-item">DNI</div>
                                <div class="col-xs-9  collaborator-value"></div>
                                <div class="col-xs-3  collaborator-item">Tel&eacute;fono</div>
                                <div class="col-xs-9  collaborator-value"> '.$pers->telf_pers.' </div>
                                <div class="col-xs-3  collaborator-item">Correo</div>
                                <div class="col-xs-9  collaborator-value"> '.$pers->correo_pers.' </div>
                            </div>
                        </div>
                        
                        <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" >Ver</button>
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Editar</button>
                        </div>
                        
                        <div class="mdl-card__menu">
                            <button id="collaborator-'.$pers->nid_persona.'" class="mdl-button mdl-js-button mdl-button--icon">
                                <i class="mdi mdi-more_vert"></i>
                            </button>                                
                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" data-mdl-for="collaborator-'.$pers->nid_persona.'">
                                <li class="mdl-menu__item" data-toggle="modal" onclick="verRolesPorUsuario(\''._simple_encrypt($pers->nid_persona).'\')">Roles</li>
                                <li class="mdl-menu__item mdl-menu__item--full-bleed-divider" data-toggle="modal" data-target="#modalAsignarPermisos">Permisos</li>
                                <li class="mdl-menu__item" data-toggle="modal" data-target="#modalConfirmarEliminar">Desactivar</li>
                            </ul>                    
                        </div>
                    </div>';
       }
       
       return $html;
    }
}

if(!function_exists('_buildTableRolesPorUsuario')) {
    function _buildTableRolesPorUsuario($rolesPers){
        $CI =& get_instance();
        $tmpl= array ('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tbRolesPersona">',
            'table_close' => '</table>');
        $CI->table->set_template($tmpl);
        $head_0 = array('data'=> '#', 'class' => 'text-left');
        $head_1 = array('data'=> 'Descripci&oacute;n', 'class' => 'text-left');
        $head_2 = array('data'=> 'Asignado', 'class' => 'text-left');
         
        $CI->table->set_heading($head_0, $head_1, $head_2);
        $cont = 1;
        foreach ($rolesPers as $row){
            $row_0 = array('data' => $cont);
            $row_1 = array('data' => $row->desc_rol);
            $check = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-'.$cont.'">
                          <input type="checkbox" id="checkbox-'.$cont.'" class="mdl-checkbox__input cb_rolesPersona" attr-rol="'._simple_encrypt($row->nid_rol).'"  '.$row->check.'>
                          <span class="mdl-checkbox__label"></span>
                      </label>';
            $row_2 = array('data' => $check);
            $CI->table->add_row($row_0, $row_1, $row_2);
            $cont++;
        }
        $table = $CI->table->generate();
        return $table;
    }
}