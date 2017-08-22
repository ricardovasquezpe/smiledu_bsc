<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class c_config_valor_graf extends MX_Controller {

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
        $this->load->library('lib_deta_indi');
        $this->load->model('mf_config/m_config_valor_graf');
        $this->load->model('mf_usuario/m_usuario');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_indicador/m_deta_indi_modal');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_CONFIGURACION_PTJ, BSC_ROL_SESS);
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
        $data['titleHeader']      = 'Configurar Valores de Gr&aacute;ficos';
        $data['rutaSalto']        = 'SI';
        $menu         = $this->load->view('v_menu', $data, true);
        $data['menu'] = $menu;
        
        $data['tablaLineasEstrategicas']    = $this->buildTableHTMLLineasEstrategicas();
        $data['inputGrupoEduc']            = $this->buildContInputGrupoEducativo();
        
        $this->load->view('vf_config/v_config_valor_graf',$data);
    }
    
    function buildTableHTMLLineasEstrategicas(){
        $listaTable = $this->m_config_valor_graf->getAllLineasEstrategicas();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" id="tb_lineas_estrategicas">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Línea Estratégica', 'class' => 'text-left');
        $head_2 = array('data' => 'Zona de riesgo', 'class' => 'text-right');
        $head_3 = array('data' => 'Valor meta', 'class' => 'text-right');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 1;
        foreach($listaTable as $row){
            $idLineaCrypt = $this->encrypt->encode($row->_id_linea_estrategica);
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->desc_linea_estrategica, 'class' => 'text-left');
            $row_2 = array('data' => '  <div class="mdl-textfield mdl-js-textfield">
                                            <input class="mdl-textfield__input" onchange="onChangeFlgAmarilloLinea(this);" value="'.$row->flg_amarillo.'" attr-idlinea="'.$idLineaCrypt.'"  
                                                    attr-cambio="false" attr-bd="'.$row->flg_amarillo.'" id="flg_amarilloLE'.$val.'">
                                            <label class="mdl-textfield__label" for="flg_amarilloLE'.$val.'">Valor</label>
                                        </div>', 'class' => 'text-right');
            $row_3 = array('data' => '  <div class="mdl-textfield mdl-js-textfield">
                                            <input class="mdl-textfield__input" onchange="onChangeFlgVerdeLinea(this);" value="'.$row->flg_verde.'" name="puntaje" attr-cambio="false"
                                        attr-valorVerdeL="'.$row->flg_verde.'" attr-focoL="false" attr-valorAmarilloL="'.$row->flg_amarillo.'" attr-bd="'.$row->flg_verde.'" id="flg_verdeLE'.$val.'" attr-idlinea="'.$idLineaCrypt.'">
                                            <label class="mdl-textfield__label" for="flg_verdeLE'.$val.'">Valor</label>
                                        </div>', 'class' => 'text-right');
            $val++;
            $this->table->add_row($row_0, $row_1,$row_2, $row_3);
        }
        $tabla = $this->table->generate();
        return $tabla;        
    }
    
    function buildTableHTMLObjetivos($idLineaEstrategica){
        $listaTable = ($idLineaEstrategica != null) ? $this->m_config_valor_graf->getAllObetivosByLinea($idLineaEstrategica) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" id="tb_objetivos">',
            'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'class' => 'text-left');
        $head_1 = array('data' => 'Objetivo', 'class' => 'text-left');
        $head_2 = array('data' => 'Zona de riesgo', 'class' => 'text-right');
        $head_3 = array('data' => 'Valor meta', 'class' => 'text-right');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 1;
        foreach($listaTable as $row){
            $idObjetivo = $this->encrypt->encode($row->_id_objetivo);
            $row_0 = array('data' => $val, 'class' => 'text-left');
            $row_1 = array('data' => $row->desc_objetivo, 'class' => 'text-left');
            $row_2 = array('data' => '  <div class="mdl-textfield mdl-js-textfield">
                                            <input class="mdl-textfield__input" onchange="onChangeFlgAmarilloObjetivo(this);" value="'.$row->flg_amarillo.'" name="puntaje" attr-idobjetivo="'.$idObjetivo.'" 
                                        attr-cambio="false" attr-bd="'.$row->flg_amarillo.'" id="flg_amarilloO'.$val.'">
                                            <label class="mdl-textfield__label" for="flg_amarilloO'.$val.'">Valor</label>
                                        </div>', 'class' => 'text-right');
            $row_3 = array('data' => '  <div class="mdl-textfield mdl-js-textfield">
                                            <input class="mdl-textfield__input" onchange="onChangeFlgVerdeObjetivo(this);" value="'.$row->flg_verde.'" name="puntaje" attr-idobjetivo="'.$idObjetivo.'"
                                        attr-valorVerdeO="'.$row->flg_verde.'" attr-focoO="false" attr-valorAmarilloO="'.$row->flg_amarillo.'" attr-cambio="false" attr-bd="'.$row->flg_verde.'" id="flg_verdeO'.$val.'">
                                            <label class="mdl-textfield__label" for="flg_verdeO'.$val.'">Valor</label>
                                        </div>', 'class' => 'text-right');            
            $val++;
            $this->table->add_row($row_0, $row_1,$row_2, $row_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getTableObjetivosByLinea(){
        try{
            $idLinea = $this->encrypt->decode($this->input->post('idLinea'));
            if($idLinea == null || $idLinea == null) {
                    throw new Exception(ANP);
            }
            $data['tablaObjetivos'] = $this->buildTableHTMLObjetivos($idLinea);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function grabarValoresLineaEstrategica(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $msj   = null;
        $error = 0;
        try {
            $myPostData = json_decode($this->input->post('valoresJSON'), TRUE);
            $arrayGeneral = array();
            foreach($myPostData['valoresArray'] as $key =>$valoresTab){
                $idLinea       = $this->encrypt->decode($valoresTab['id_linea']);
                $valorAmarillo = $valoresTab['valAmarillo'];
                $valorVerde    = $valoresTab['valVerde'];
                $valorAmarillo = isset($valoresTab['valAmarillo']) ? $valoresTab['valAmarillo'] : null;
                $valorAmarillo = ($valorAmarillo == 0 || trim($valorAmarillo) == "" || $valorAmarillo < 0) ? null : $valorAmarillo;
                
                $valorVerde    = isset($valoresTab['valVerde'])    ? $valoresTab['valVerde'] : null;
                $valorVerde    = ($valorVerde == 0 || trim($valorVerde) == "" || $valorVerde < 0) ? null : $valorVerde;
                
                log_message('error', $valorAmarillo.'||'.$valorVerde);
                if($valorAmarillo != null && $valorVerde != null){
                    if($valorAmarillo > 100 || $valorAmarillo < 0 || $valorVerde >100 || $valorVerde < 0){
                        $error = EXIT_ERROR;
                        $msj   = "El valor zona de riesgo debe ser menor al meta";
                    }else{
                        if($valorAmarillo < $valorVerde){
                            $arrayDatos = array( "_id_linea_estrategica" => $idLinea,
                                "flg_amarillo"          => $valorAmarillo,
                                "flg_verde"             => $valorVerde);
                            array_push($arrayGeneral, $arrayDatos);
                        } else{
                            $error = EXIT_ERROR;
                            $msj   = "El valor zona de riesgo debe ser menor al meta";
                        }    
                    }
                } else{
                    $error = EXIT_ERROR;
                    $msj   = "Debe ingresar datos validos en los valores";
                }
                //array_push($arrayGeneral, $arrayDatos);
                
            }
            $data = $this->m_config_valor_graf->updateValoresLinea($arrayGeneral);

            if($data['error'] == EXIT_SUCCESS) {
                $data['tablaLineasEstrategicas'] = $this->buildTableHTMLLineasEstrategicas();
            }else{
                $data['error'] = $error;
                $data['msj']   = $msj;
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function grabarValoresObjetivos(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $msj   = null;
        $error = 0;
        $idLineaEstrategica = null;
        try {
            $myPostData = json_decode($this->input->post('valoresJSON'), TRUE);
            $arrayGeneral = array();
            foreach($myPostData['valoresArray'] as $key =>$valoresTab){
                $idObjetivo    = $this->encrypt->decode($valoresTab['id_objetivo']);
                $idLineaEstrategica = $this->m_utils->getById('bsc.objetivo', '__id_linea_estrategica', '_id_objetivo' , $idObjetivo);
                $valorAmarillo = $valoresTab['valAmarillo'];
                $valorVerde    = $valoresTab['valVerde'];
                $valorAmarillo = isset($valoresTab['valAmarillo']) ? $valoresTab['valAmarillo'] : null;
                $valorAmarillo = ($valorAmarillo == 0 || trim($valorAmarillo) == "" || $valorAmarillo < 0) ? null : $valorAmarillo;    
                $valorVerde    = isset($valoresTab['valVerde'])    ? $valoresTab['valVerde'] : null;
                $valorVerde    = ($valorVerde == 0 || trim($valorVerde) == "" || $valorVerde < 0) ? null : $valorVerde;          
                if($valorAmarillo != null && $valorVerde != null){
                    if($valorAmarillo > 100 || $valorAmarillo < 0 || $valorVerde >100 || $valorVerde < 0){
                        $error = EXIT_ERROR;
                        $msj   = "El valor zona de riesgo debe ser menor al meta";
                    } else{
                        if($valorAmarillo < $valorVerde){
                            $arrayDatos = array( "_id_objetivo" => $idObjetivo,
                                                "flg_amarillo" => $valorAmarillo,
                                                "flg_verde"    => $valorVerde);
                            array_push($arrayGeneral, $arrayDatos);
                        } else{
                            $msj   = "El valor zona de riesgo debe ser menor al meta";
                        }    
                    }
                } else{
                    $msj   = "Debe ingresar datos validos en los valores";
                }
                //array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_config_valor_graf->updateValoresObjetivos($arrayGeneral);
            
            if($data['error'] == EXIT_SUCCESS) {
                $data['tablaObjetivos'] = $this->buildTableHTMLObjetivos($idLineaEstrategica);
            }else{
                $data['error'] = $error;
                $data['msj']   = $msj;
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function buildContInputGrupoEducativo(){
        $dataGrupoEduc = $this->m_config_valor_graf->getGrupoEducativo();
        $idConfigCrypt = $this->encrypt->encode($dataGrupoEduc['id_config']);
        $div  = '   <div class="mdl-card__supporting-text p-0">
                        <div class="col-md-6 mdl-input-group mdl-input-group__only">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="valorAmarilloGE" attr-idconfig="'.$idConfigCrypt.'" attr-cambio="false" attr-bd="'.$dataGrupoEduc['valor_numerico1'].'" attr-valoramarillo="'.$dataGrupoEduc['valor_numerico2'].'" value="'.$dataGrupoEduc['valor_numerico1'].'">
                                <label class="mdl-textfield__label" for="valorAmarilloGE">Valor zona de riesgo</label>
                            </div>
                        </div>
                        <div class="col-md-6 mdl-input-group mdl-input-group__only">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="valorVerdeGE" attr-idconfig="'.$idConfigCrypt.'" attr-cambio="false" attr-bd="'.$dataGrupoEduc['valor_numerico1'].'" attr-valoramarillo="'.$dataGrupoEduc['valor_numerico2'].'" value="'.$dataGrupoEduc['valor_numerico2'].'">
                                <label class="mdl-textfield__label" for="valorVerdeGE">Valor meta</label>
                            </div>
                        </div>
                    </div>
                    <div class="mdl-card__actions text-right">
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised m-b-0" type="submit" attr-valorA="'.$dataGrupoEduc['valor_numerico1'].'" attr-valorV="'.$dataGrupoEduc['valor_numerico2'].'" id="btnGrupEduc" onclick="grabarValoresGrupoEduc();">GUARDAR</button>
                    </div>';
        return $div;
    }
    
    function grabarValoresGrupoEduc(){
        $data['msj']   = null;
        $data['error'] = 1;
        try {
            $valAmarillo = $this->input->post('valAmarillo');
            $valVerde    = $this->input->post('valVerde');
            $idConfig    = $this->encrypt->decode($this->input->post('idConfig'));
            if($valAmarillo == null || $valVerde == null){
                $data['error'] = EXIT_ERROR;
                $data['msj']   = "Debe llenar los valores zona de riesgo y meta";
            } else{
                if($valAmarillo < 0 || $valAmarillo > 100){
                    $data['error'] = EXIT_ERROR;
                    $data['msj']   = "El valor zona de riesgo debe estar entre 0 y 100";
                } else if($valVerde < 0 || $valVerde > 100){
                    $data['error'] = EXIT_ERROR;
                    $data['msj']   = "El valor meta debe estar entre 0 y 100";
                } else{
                    if($valAmarillo > $valVerde || $valAmarillo == $valVerde){
                        $data['error'] = EXIT_ERROR;
                        $data['msj']   = "El valor zona de riesgo debe ser menor al meta";
                    } else{
                        $update = array( "id_config"      => $idConfig ,
                                        "valor_numerico1" => $valAmarillo,
                                        "valor_numerico2" => $valVerde);
                        $data = $this->m_config_valor_graf->updateValoresGrupoEduc($update);
                    }
                }
            }
        } catch (Exception $e){
            
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
        $nombreRol = $this->m_utils->getById("schoowl_rol", "desc_rol", "nid_rol", $idRol);
    
        $dataUser = array("id_rol"     => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
    
        $idRol     = $this->session->userdata('nombre_rol');
    
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