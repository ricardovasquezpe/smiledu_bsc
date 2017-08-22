<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_responsable_indicador extends MX_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;

    public function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_indicador/m_responsable_indicador');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_RESPONSABLE, BSC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
    }
     
    public function index(){
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
	    $data['titleHeader']      = 'Responsables';
	    $data['rutaSalto']        = 'SI';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $data['tablePersonaIndicador']      = $this->buildTablePersona(null);
	    $data['lineaEstrat'] = _buildLineaEstrategica();
	    $this->load->view('vf_indicador/v_responsable_indicador',$data);
    }
    
    function buildTablePersona($idIndicador){
        $listaTable = ($idIndicador != null) ? $this->m_responsable_indicador->getAllPersonasByIndicador($idIndicador) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   id="tb_persona_x_indicadores">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Roles', 'class' => 'text-left');
        $head_3 = array('data' => 'Asignador', 'class' => 'text-left');
        $head_4 = array('data' => 'Fecha', 'class' => 'text-center');
        $head_5 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
        $val = 1;
        foreach($listaTable as $row){
            $idCryptPersona   = $this->encrypt->encode($row->nid_persona);
            $idCryptIndicador = $this->encrypt->encode($idIndicador);
            $row_col0         = array('data' => $val, 'class' => 'text-left');
            $row_col1         = array('data' => $row->nombrecompleto, 'class' => 'text-left');
            $row_col2         = array('data' => $row->roles, 'class' => 'text-left');
            $row_col3         = array('data' => $row->nombre_asignador, 'class' => 'text-left');
            $row_col4         = array('data' => _fecha_tabla($row->audi_fec_regi, 'd/m/Y'), 'class' => 'text-center');
            $row_col5         = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" type="button" attr-idpersona="'.$idCryptPersona.'" attr-idindicador="'.$idCryptIndicador.'" onclick="abirModalEliminarResponsable(this);" id="borrar'.$val.'"><i class="mdi mdi-delete"></i></button>', 'class' => 'text-center');
            $val++;
            $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col4, $row_col5);
        }
        
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function comboObjetivos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idLinea = $this->encrypt->decode(_post('idLinea'));
            if($idLinea == null) {
                throw new Exception(null);
            }
            $data['comboObjetivo'] = _buildComboObjetivosByLinea($idLinea);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboCategorias(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idObjetivo = $this->encrypt->decode(_post('idObjetivo'));
            if($idObjetivo == null) {
                throw new Exception(null);
            }
            $data['comboCategoria'] = _buildComboCategoriaByObjetivo($idObjetivo);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comboIndicadores(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idCategoria = $this->encrypt->decode(_post('idCategoria'));
            if($idCategoria == null) {
                throw new Exception(null);
            }
            $data['comboIndicador'] = _buildComboIndicadorByCategoria($idCategoria);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getIndicadoresByNombreCod(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $codNombreIndicador = _post("indicador");
            if($codNombreIndicador == null) {
                throw new Exception(null);
            }
            $indicadores = $this->m_responsable_indicador->getIndicadoresByNombreCod($codNombreIndicador);
            $opcion = '';
            foreach ($indicadores as $ind){
                $idIndicador = _encodeCI($ind->_id_indicador);
                $opcion .= '<option value="'.$idIndicador.'">'.$ind->desc_indicador.' ('.$ind->cod_indi.')</option>';
            }
            $data['comboIndicador'] = $opcion;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablePersonasByIndicador(){
        try{
            $idIndicador = $this->encrypt->decode($this->input->post('idIndicador'));
            
            $data['tablaPersonas'] = $this->buildTablePersona($idIndicador);
        }catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function deleteResponsableByIndicador(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idPersona   = $this->encrypt->decode($this->input->post('idPersona'));
            $idIndicador = $this->encrypt->decode($this->input->post('idIndicador'));
            $update = array('__id_persona'   => $idPersona,
                            '__id_indicador' => $idIndicador,
                            'flg_acti'       => FLG_INACTIVO
                            );
            $data = $this->m_responsable_indicador->deleteResposableByIndicador($update);
            if($data['error'] == EXIT_SUCCESS){
                $data['tablaPersonas'] = $this->buildTablePersona($idIndicador);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTablePersonaAddIndicadorHTML($idIndicador,$nombrePersona){
        $listaTable = ($nombrePersona != null) ? $this->m_responsable_indicador->getAllPersonasByNombre($idIndicador,$nombrePersona) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" id="tb_persona_by_nombre">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Cant. Indi');
        $head_3 = array('data' => 'Acci&oacute;n');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 1;
        
        foreach($listaTable as $row){
            $check_indicador  = ($row->flg_acti == '1') ? 'checked' : null; 
            $idCryptPersona   = $this->encrypt->encode($row->nid_persona);
            $idCryptIndicador = $this->encrypt->encode($idIndicador);
            $row_col0         = array('data' => $val);
            $row_col1         = array('data' => $row->nombrecompleto, 'class' => 'text-left');
            $row_col2         = array('data' => $row->count_ind, 'class' => 'text-center');
            $checBox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="responsable'.$val.'">
                           <input type="checkbox" id="responsable'.$val.'" class="mdl-checkbox__input" attr-bd="'.$check_indicador.'" '.$check_indicador.'  
                                  attr-idpersona="'.$idCryptPersona.'" attr-cambio="false" attr-idindicador="'.$idCryptIndicador.'" onchange="cambioCheckIndicador(this);">
                           <span class="mdl-checkbox__label"></span>
                        </label>';
            $row_col3         = array('data' => $checBox);
            $val++;
            $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3);
        }
        
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function tablePersonasAddIndicador(){
        $nombrePersona   = $this->input->post('nombrePersona');
        $idIndicador     = $this->encrypt->decode($this->input->post('idIndicador'));
        if($idIndicador != null && $nombrePersona != null){
            $data['tablePersonasModal'] = $this->buildTablePersonaAddIndicadorHTML($idIndicador,$nombrePersona);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function grabarIndicadoresPersona(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode($this->input->post('personas'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            $idIndicador   = $this->encrypt->decode($this->input->post('idIndicador'));
            foreach($myPostData['persona'] as $key => $persona){
                $logeoUsario = _getSesion('nid_persona');
                $nombrePersona = _getSesion('nombre_completo');
                $idPersona  = $this->encrypt->decode($persona['idPersona']);
                if($idPersona == null) {
                    throw new Exception(ANP);
                }
                $newVal = ($persona['valor'] == null) ? '0' : '1';
                $idIndicador = $this->encrypt->decode($persona['idIndicador']);
                $condicion = $this->m_responsable_indicador->evaluaInsertUpdate($idPersona,$idIndicador);
                $arrayDatos = array();
                $arrayDatos = array("flg_acti"       => $newVal,
                                    "__id_persona"    => $idPersona,
                                    "__id_indicador" => $idIndicador,
                                    "year"           => date("Y"),
                                    "audi_nomb_usua" => "nd",
                                    "audi_id_usua"   => $this->_idUserSess,
                                    "audi_fec_modi"  => date('D, d M Y H:i:s'),
                                    "condicion"      => $condicion);
                array_push($arrayGeneral, $arrayDatos);
                if($condicion == 0){
                    $correo = $this->m_utils->getById("persona", "correo_inst", "nid_persona", $idPersona);
                    if($correo == null){
                        $correo = $this->m_utils->getById("persona", "correo_admi", "nid_persona", $idPersona);
                        if($correo == null){
                            $correo = $this->m_utils->getById("persona", "correo_pers", "nid_persona", $idPersona);
                        }
                    }
                    $arrayInsertCorreo = array('correos_destino'         => $correo,
                                                'asunto'                  => utf8_decode("Has sido asignado como responsable de medición!"),
                                                'body'                    => "Has sido asignado como responsable de medici&oacute;",
                                                'estado_correo'           => "PENDIENTE",
                                                'sistema'                 => 'BSC');
                    //$this->m_utils->insertarEnviarCorreo($arrayInsertCorreo);
                }
            }
            $data = $this->m_responsable_indicador->updateInsertIndicadorPersona($arrayGeneral);
            if($data['error'] == EXIT_SUCCESS){
                $data['tablePersonaIndicador'] =  $this->buildTablePersona($idIndicador);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function cambioRol(){
        $idRolEnc = $this->input->post('id_rol');
        $idRol = $this->lib_utils->simple_decrypt($idRolEnc,CLAVE_ENCRYPT);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
    
        $dataUser = array("id_rol"     => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
    
        $idRol     = _getSesion('nombre_rol');
    
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function setIdSistemaInSession(){
        $idSistema = $this->encrypt->decode($this->input->post('id_sis'));
        $idRol     = $this->encrypt->decode($this->input->post('rol'));
        if($idSistema == null || $idRol == null){
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarFeedBack(){
        $nombre = _getSesion('nombre_usuario');
        $mensaje = $this->input->post('feedbackMsj');
        $url = $this->input->post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}