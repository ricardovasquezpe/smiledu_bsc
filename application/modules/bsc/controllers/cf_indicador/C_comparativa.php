<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_comparativa extends CI_Controller {

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
        $this->load->model('mf_indicador/m_comparativa');
        $this->load->library('table');

        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_COMPARATIVAS, BSC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
    }
     
    public function index(){
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
	    $data['titleHeader']      = 'Comparativas';
	    $data['rutaSalto']        = 'SI';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $data['tipoComparativa']            = $this->comboTipoComparativa();
	    $data['tablaComparativas']          = $this->buildTableComparativas();
	    
	    $this->load->view('vf_indicador/v_comparativa',$data);
    }
    
    function comboTipoComparativa(){
        $opcion = '';
        $opcion .= '<option value="HISTORICO">HISTÓRICO</option>';
        $opcion .= '<option value="OTRO">OTRO</option>';
        return $opcion;
    }
    
    function buildTableComparativas(){
        $listaComparativas = $this->m_comparativa->getAllComparativas();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-search="true"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                     id="tb_comparativas">',
                                               'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Comparativa', 'class' => 'text-left');
        $head_2 = array('data' => 'Valor', 'class' => 'text-center');
        $head_3 = array('data' => 'Tipo', 'class' => 'text-left');
        $head_4 = array('data' => 'Año', 'class' => 'text-right');
        $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4);
        $val = 0;
        foreach($listaComparativas as $row){
            $val++;
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->desc_comparativa, 'class' => 'text-left');
            $row_2 = array('data' => $row->valor_comparativa, 'class' => 'text-center');
            $row_3 = array('data' => $row->tipo_comparativa, 'class' => 'text-left');
            $row_4 = array('data' => $row->year, 'class' => 'text-right');         
            $this->table->add_row($row_0,$row_1,$row_2,$row_3,$row_4);
        }
        $tabla = $this->table->generate();
        
        return $tabla;
    }
    
    function setComboInputComparativa(){
        $tipoComparativa = _post('tipoComparativa');
        if($tipoComparativa != 'HISTORICO' && $tipoComparativa != 'OTRO'){
            $data['error'] = 1;
            $data['msj']   = 'El tipo de comparativa no es valido';
        } else{
            $option = '';
            $input  = '';
            try{
                if($tipoComparativa == 'HISTORICO'){
                    $option .= _buildComboAllIndicadores();
                    $data['comboIndicadores'] = $option;
                    $data['condContDesc'] = EXIT_ERROR;//CREA COMBO cond '1'
                } else if($tipoComparativa == 'OTRO'){
                    $input  .= '<label>Comparativa</label>';
                    $input  .= '<input type="text" class="form-control"  id="comparativaModal" name="comparativaModal">';
                    $data['inputDesc'] = $input;
                    $data['condContDesc'] = EXIT_SUCCESS;//CREA INPUT cond '0'
                }
                $data['error'] = EXIT_SUCCESS;//CREA COMBO
            } catch (Exception $e){
                $data['error'] = EXIT_ERROR;
                $data['msj']   = $e->getMessage();
            }
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getValorNumerico(){
        $idIndicador = $this->encrypt->decode($this->input->post('idIndi'));
        $valor = $this->m_comparativa->getValorActualUltimoByIndicador($idIndicador);
        $data['valor'] = $valor['valor_actual_ultimo'];
        $valorActual   = array("valor_actual_ultimo" => $valor['valor_actual_ultimo']);
        $descRegistro  = array("desc_registro"       => $valor['desc_registro']);
        $yearIndicador = array("yearIndi"            => $valor['year']);
        $this->session->set_userdata($valorActual);
        $this->session->set_userdata($descRegistro);
        $this->session->set_userdata($yearIndicador);
        $data['year'] = $valor['year'];
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function agregarComparativa(){
        $tipoComparativa  = $this->input->post('selectTipoModal');
        $idIndicador      = $this->encrypt->decode($this->input->post('selectIndi'));
        $valorComp        = $this->input->post('valorModal');
        $valorIndi        = $this->session->userdata('valor_actual_ultimo');
        $descRegistroComp = trim($this->input->post('comparativaModal'));
        $descRegistroIndi = $this->session->userdata('desc_registro');
        $idPersona        = $this->session->userdata('id_persona');
        $nombrePersona    = $this->session->userdata('nombre');
        $yearIndicador    = $this->session->userdata('yearIndi');
        
        if($valorComp > 100 || $valorComp < 0){
            $data['error'] = 1;
            $data['msj']   = 'El valor de la comparativa debe ser mayor que 0 y menor que 100';
        } else{
            if($tipoComparativa != 'HISTORICO' && $tipoComparativa != 'OTRO'){
                $data['error'] = 2;
                $data['msj']   = 'Tipo de Comparacion no es valido';
            } else if($tipoComparativa == 'HISTORICO'){//INSERT CON INDICADOR
                if($idIndicador == null){
                    $data['error'] = 3;
                    $data['msj']   = 'El Indicador no es validoControl';
                } else{
                    $cant = null;
                    $cant = $this->m_comparativa->existeComparativaById('indicador',$idIndicador);
                    if($cant == 1){
                        $data['error'] = 1;
                        $data['msj']   = 'La comparativa/indi ya fue registrado en el año control';
                    } else {
                        $insert = array('desc_comparativa'  => $descRegistroIndi,
                            'year'              => date('Y'),
                            'tipo_comparativa'  => $tipoComparativa,
                            'id_indicador'      => $idIndicador,
                            'audi_fec_regi'     => date('Y-m-d h:i:sa'),
                            'audi_id_pers'      => $idPersona,
                            'audi_nomb_pers'    => $nombrePersona,
                            'valor_comparativa' => $valorIndi,
                            'year_indicador'    => $yearIndicador);
                        $data = $this->m_comparativa->insertNuevaComparativa($insert);
                        $data['tbComparativas'] = $this->buildTableComparativas();
                    }
                }
            } else if($tipoComparativa == 'OTRO'){//INSERT SIN INDICADOR
                $cant = null;
                $cant = $this->m_comparativa->existeComparativaById('comparativa',$descRegistroComp);
                if($cant == 1){
                    $data['error'] = 1;
                    $data['msj']   = 'La comparativa ya fue registrado en el año control';
                } else {
                    $insert = array('desc_comparativa'  => $descRegistroComp,
                                    'year'              => date('Y'),
                                    'tipo_comparativa'  => $tipoComparativa,
                                    'audi_fec_regi'     => date('Y-m-d h:i:sa'),
                                    'audi_id_pers'      => $idPersona,
                                    'audi_nomb_pers'    => $nombrePersona,
                                    'valor_comparativa' => $valorComp);
                    $data = $this->m_comparativa->insertNuevaComparativa($insert);
                    $data['tbComparativas'] = $this->buildTableComparativas();
                }
            }           
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function existComparativa(){
        $valor       = _post('valor');
        $campo       = _post('campo');
        $cant = null;
        if($campo == 'indicador'){
            $valor = $this->encrypt->decode($valor);
            $cant = $this->m_comparativa->existeComparativaById($campo,$valor);
        } else if($campo == 'comparativa'){
            $cant = $this->m_comparativa->existeComparativaById($campo,$valor);
        } else{
            $cant = 1;
        }
        echo $cant;
    }
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function grabarComparativasIndicador() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idIndicador   = _simpleDecryptInt(_getSesion('id_indicador'));
            if($idIndicador == null) {
                throw new Exception(ANP);
            }
            $myPostData = json_decode(_post('comparativas'), TRUE);
            $strgConcatIdPersonas = null;
            $arrayGeneral = array();
            foreach($myPostData['comparativa'] as $key => $comparativa) {
                $idComparativa  = _decodeCI($comparativa['idComparativa']);
                $newVal         = ($comparativa['valor'] == null) ? '0' : '1';
                $yearActual     = date('Y');
                 
                $condicion = $this->m_comparativa->evaluaInsertUpdateComparativaXIndicador($idIndicador, $idComparativa);
                if($idComparativa == null) {
                    throw new Exception(ANP);
                }
                $arrayDatos = array(
                    "__id_indicador"   => $idIndicador,
                    "__id_comparativa" => $idComparativa,
                    "flg_acti"         => $newVal,
                    "year_comparativa" => $yearActual,
                    "condicion"        => $condicion['cuenta']
                );
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_comparativa->updateInsertComparativasXIndicador($arrayGeneral);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception('No se registraron los datos');
            }
            $data['tablaCompXIndi'] = __buildTableAsignarComprativasXIndicador($idIndicador);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    

    function getAllComparativasByIndicador(){
        $idIndicador     = _simpleDecryptInt(_getSesion('id_indicador'));
        $data['tablaCompXIndi'] = __buildTableAsignarComprativasXIndicador($idIndicador);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambioRol(){
        $idRolEnc =_post('id_rol');
        $idRol = _simpleDecryptInt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
    
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
    
        $idRol = _getSesion('nombre_rol');
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
        $nombre = $this->session->userdata('nombre_usuario');
        $mensaje = $this->input->post('feedbackMsj');
        $url = $this->input->post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}