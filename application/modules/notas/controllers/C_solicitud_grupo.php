<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitud_grupo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_solicitud_grupo');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_SOLICITUD_GRUPO, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);       
    }

    public function index() {
        $idUserSess = $this->_idUserSess;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);       
        $data['titleHeader']      = 'Docentes por aula';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;

        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
          
        $data2 = _searchInputHTML('Busca tus Aulas');
        $data = array_merge($data, $data2);

        $data['cmbTaller']     = __buildComboTalleres(null);
        $data['tbSolicitudes'] = $this->getTbSolicitudes();
        
        $this->load->view('v_solicitud_grupo', $data);
    }
    
    function getComboGrupo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idTaller = _decodeCI(_post('idTaller'));
            if($idTaller == null) {
                throw new Exception(ANP);
            }
            $comboGrupos = __buildComboGrupos($idTaller);
            $htmlComboGrupos = '<select id="cmbGrupo" name="cmbGrupo" class="form-control pickerButn" data-live-search="true" title="Selec. Grupo">
            		                <option value="">Selec. Grupo</option>'
                                    .$comboGrupos.
                               '</select>';
            $data['error']    = EXIT_SUCCESS;
            $data['cmbGrupo'] = $htmlComboGrupos;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTbSolicitudes() {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbSolicitudes" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        
        $head_0 = array('data' => '#'                  , 'class' => 'text-left');
        $head_1 = array('data' => 'Alumno'             , 'class' => 'text-left');
        $head_2 = array('data' => 'Taller'             , 'class' => 'text-center');
        $head_3 = array('data' => 'Fecha de Solicitud' , 'class' => 'text-center');
        $head_4 = array('data' => 'Accion'             , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $i = 1;
  
        $grupos = $this->m_solicitud_grupo->getSolicitudes();
        foreach ($grupos as $row) {
            $indicador = $this->capacidadGrupo($row['__id_main']); 
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
                                 <p class="classroom-value" style="display: inline">'.$row['nombre_alumno'].'</p>';
            $row_0 = $i;
            $row_1 = array('data' => $imageStudent, 'class' => 'text-left');
            $row_2 = array('data' => $row['nombre_grupo'] , 'class' => 'text-left btnID');
            $row_3 = array('data' => $row['fecha'] , 'class' => 'text-left');
            $motivo   = '<button class="mdl-button mdl-js-button mdl-button--icon" data-id_main_solicitud="'._simple_encrypt($row['__id_main_solicitud']).'" onclick="getMotivoModal($(this))" title="ver motivo">
                             <i class="mdi mdi-visibility"></i>
                         </button>
                         <button class="mdl-button mdl-js-button mdl-button--icon"  onclick="getRechazarAceptarModal($(this), '.GRUPO_RECHAZADO.')" title="Rechazar">
                             <i class="mdi mdi-clear"></i>
                         </button>
                         <button class="mdl-button mdl-js-button mdl-button--icon"  onclick="getRechazarAceptarModal($(this), '.$indicador.')" title="Aceptar">
                             <i class="mdi mdi-done_all"></i>
                         </button>';
            $row_4 = array('data' => $motivo, 'class' => 'text-center btnM',  'data-id_main' => _encodeCI($row['__id_main']), 'data-motivo' => $row['motivo_cambio']);
       
            $this->table->add_row($row_0, $row_1, $row_2, $row_3,$row_4);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function capacidadGrupo($idMain) {
        $val = $this->m_solicitud_grupo->getLimiteGrupoByCantAlum($idMain);
        if($val == null) {
            $indic = GRUPO_SIN_CAPACIDAD;
            return $indic;
        } else {
            $indic = GRUPO_CON_CAPACIDAD;
            return $indic;
        }
    }
        
    function rechazarSolicitud() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idMainRechazar  = _decodeCI(_post('idMain'));

            if($idMainRechazar == '' || $idMainRechazar == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_solicitud_grupo->rechazarSolicitud($idMainRechazar);     
            $data['tbSolicitudes'] = $this->getTbSolicitudes();   
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function aceptarSolicitud() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idMain          = _decodeCI(_post('idMain'));            
            if($idMain == '' || $idMain == null) {
                throw new Exception(ANP);
            }
            $val = $this->m_solicitud_grupo->getLimiteGrupoByCantAlum($idMain);
            if($val == null) {
                throw new Exception('error','Este grupo ya se encuentra lleno');
            }
             
            $data = $this->m_solicitud_grupo->aceptarSolicitud($idMain); 
            $data['tbSolicitudes'] = $this->getTbSolicitudes();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}