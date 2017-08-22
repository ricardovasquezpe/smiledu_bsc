<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_pesos_asistencia extends MX_Controller {

    private $_idRol     = null;
    private $_idUsuario = null;
    
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
        $this->load->model('m_pesos_asistencia');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_PESO_ASISTENCIA, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }

    public function index() {
        $data['titleHeader'] = 'Asistencia';
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);  
	    $data['ruta_logo']        = MENU_LOGO_NOTAS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
	    $data['nombre_logo']      = NAME_MODULO_NOTAS;
                
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
       
        $data['tablaAsistencia']            = $this->tablaselectAsistencia();
        $data['tablaselectAsistenciaCalif'] = $this->tablaselectAsistenciaCalif();
        $this->load->view('v_pesos_asistencia', $data);
    }
    
    function logout() {
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
        
    function tablaselectAsistencia() {
        $arrayAsistencia = $this->m_pesos_asistencia->selectTableAsist();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbAsistencia" data-show-columns="false">',
                                        'table_close' => '</table>');
        $this->table->set_template($tmpl);
    
        $head_0 = array('data' => 'Descripci&oacute;n', 'class' => 'text-left');
        $head_1 = array('data' => 'Peso'              , 'class' => 'text-right');
        $head_2 = array('data' => 'Acci&oacute;n'       , 'class' => 'text-center');
    
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 0;
        foreach($arrayAsistencia as $row) {
            $val++;
            $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" data-peso="'.$row['peso'].'" data-id_asistencia="'._encodeCI($row['id_asist_config']).'" onclick="modalAsignarPeso($(this))">
                            <i class="mdi mdi-edit"></i>
                        </button>';
            //ORDEN    
            $row_0 = array('data' => $row['desc_asist_config'], 'class' => 'text-left');
            $row_1 = array('data' => $row['peso'], 'class' => 'text-right');
            $row_2 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function tablaselectAsistenciaCalif() {
        $arrayAsistenciaCalif = $this->m_pesos_asistencia->selectTableAsistCalif();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbAsistenciaCalif" data-show-columns="false">',
                                       'table_close' => '</table>');
        $this->table->set_template($tmpl);
    
        $head_0 = array('data' => 'Peso L&iacute;mite'     , 'class' => 'text-left');
        $head_1 = array('data' => 'Nota Alfab&eacute;tica' , 'class' => 'text-left');
        $head_2 = array('data' => 'Nota Num&eacute;rica'   , 'class' => 'text-right');
        $head_3 = array('data' => 'Acci&oacute;n'            , 'class' => 'text-center');
    
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 0;
        foreach($arrayAsistenciaCalif as $row) {
            $val++;
            $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" data-id_asis_calif ='._encodeCI($row['id_asist_calif_config']).' data-limite='.$row['rango_limite'].' data-nota_numerica='.$row['nota_numerica'].' nota_alf='.$row['nota_alfabetica'].' onclick="modalAgregarLimiteCalificacion($(this))">
                            <i class="mdi mdi-edit"></i>
                        </button>';
            //ORDEN
            $row_0 = array('data' => $row['rango_limite'], 'class' => 'text-left');
            $row_1 = array('data' => $row['nota_alfabetica'], 'class' => 'text-left');
            $row_2 = array('data' => $row['nota_numerica'], 'class' => 'text-right');
            $row_3 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function agregarPeso() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAsistencia   = _decodeCI(_post('idAsistencia'));
            $peso           = _post('peso');
            if($idAsistencia == null || $peso == null) {
                throw new Exception(ANP);
            }
            $asist = array(
               'peso' => $peso
            );
            
            $this->m_pesos_asistencia->agregarPeso($asist, $idAsistencia);
            $data['error'] = EXIT_SUCCESS;
            $data['tabla_asistencia'] = $this->tablaselectAsistencia();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function agregarAsistCalif() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAsistCalif = _decodeCI(_post('idAsistCalif'));
            $limite       = _post('limite');
            $notaAlf      = _post('notaAlf');
            $notaNum      = _post('notaNum');
            if($idAsistCalif == null || $limite == null || $notaAlf == null || $notaNum == null) {
                throw new Exception(ANP);
            }
            $asistCalif = array(
                'rango_limite'    => $limite,
                'nota_alfabetica' => $notaAlf,
                'nota_numerica'   => $notaNum
            );
        
            $data = $this->m_pesos_asistencia->agregarCalif($asistCalif, $idAsistCalif);
            $data['tabla_asistenciaCalif'] = $this->tablaselectAsistenciaCalif();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        }
        
}