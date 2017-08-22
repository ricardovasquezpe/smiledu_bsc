<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_indicador extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;

    public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('../m_utils');
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
        if(!isset($_COOKIE[$this->config->item('sess_cookie_name')])) {
            $this->session->sess_destroy();
            redirect('','refresh');
        }
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
        $data['titleHeader']      = 'Indicadores';
        $data['rutaSalto']        = 'SI';
	    
	    if(_getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_PROMOTOR || _getSesion(BSC_ROL_SESS) == ID_ROL_DIRECTOR_CALIDAD) {
	        if(_getSesion('es_director_direc_promot_ver_cate') != null) {
	            $data['return'] = '';
	        }
    	}
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    if($this->_idRol == ID_ROL_MEDICION) {
	        $indicadoresRespMedicion = $this->m_indicador->getIndicadoresAsigRespMedicion($this->_idUserSess, 1, 0);
	        $data['tbIndicadores'] = $this->createTableIndicadores($indicadoresRespMedicion);
	    } else if($this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	        $indicadoresRespMedicion = $this->m_indicador->getIndicadoresPlanEstrategico();
	        $data['tbIndicadores'] = $this->createTableIndicadores($indicadoresRespMedicion);
	    } else {
	        if(_getSesion('es_director_direc_promot_ver_cate') == null) {
	            $indicadores = $this->m_indicador->getIndicadoresPlanEstrategico();
	        } else {
	            $idCategoriaEnc = _getSesion('id_categoria');
    	        $idCategoria    = _simpleDecryptInt($idCategoriaEnc);
    	        $indicadores = $this->m_indicador->getIndicadoresByCategoria($idCategoria, 1, 0);
	        }
	        $data['tbIndicadores'] = $this->createTableIndicadores($indicadores);
	    }
	    
	    $this->load->view('vf_indicador/v_indicador', $data);
	}
	
	function createTableIndicadores($indicadores) {
	    $CI =& get_instance();
	    $CI->load->library('table');
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" 
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tb_indicadores">',
	                  'table_close' => '</table>');
	    $CI->table->set_template($tmpl);
	    $head_0 = array('data' => '#', 'class' => 'text-left');
	    $head_1 = array('data' => 'Descripción', 'class' => 'text-left');
	    $head_2 = array('data' => 'Valor Actual','class' => 'text-center');
	    $head_3 = array('data' => 'Meta','class' => 'text-right');
	     
	    $head_5 = array('data' => 'Frecuencia Medi','class' => 'text-left', 'data-visible' => 'false');
	    $head_6 = array('data' => 'EFQM','class' => 'text-left', 'data-visible' => 'true');
	     
	    $head_7 = array('data' => 'Comparativa 1','class' => 'text-left', 'data-visible' => 'false');
	    $head_8 = array('data' => 'Comparativa 2','class' => 'text-left', 'data-visible' => 'false');
	    $head_9 = array('data' => 'Comparativa 3','class' => 'text-left', 'data-visible' => 'false');
	    $head_10 = array('data' => 'Acción', 'class' => 'text-center');
	     
	    $val = 1;
	    $idIndicadorActual = 0;
	    $CI->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_5, $head_6, $head_7, $head_8, $head_9,$head_10);
	     
	    $row_col0   = null;
	    $row_col1   = null;
	    $row_col2   = null;
	    $row_col3   = null;
	     
	    $row_col5   = null;
	    $row_col6   = null;
	     
	    $row_col7   = null;
	    $row_col8   = null;
	    $row_col9   = null;
	    $row_col10   = null;
	    $cantCompar = 7;
	     
	    foreach($indicadores as $row){ //SEGUIR AGREGANDO COMPARATIVAS AL ROW
	        $tipoValor   = $row->tipo_valor;
	        $idIndicador = _simple_encrypt($row->_id_indicador);
	        if($idIndicadorActual == $row->_id_indicador){
	            $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
	            ${'row_col' . $cantCompar} = array('data' => $compar);
	            $cantCompar++;
	        }else if($idIndicadorActual != 0 && $idIndicadorActual != $row->_id_indicador){// CERRAR EL ROW Y AGERGAR NUEVO INDICADOR
	            $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col5, $row_col6, $row_col7, $row_col8, $row_col9,$row_col10);
	             
	            $row_col7 = null;
	            $row_col8 = null;
	            $row_col9 = null;
	             
	            $cantCompar = 7;
	            $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
	            $row_col0    = array('data' => $val,'class' => ' text-left');
	            $row_col1    = array('data' => '<small>('.$row->cod_indi.')</small>'.$row->desc_registro, 'class' => ' text-left');
	            $row_col2    = array('data' => $row->valor_actual_porcentaje.$tipoValor._calculateIcon($row->diff_actual_y_anterior, $tipoValor),'class' => 'text-center');
	            $row_col3    = array('data' => $row->valor_meta.$tipoValor,'class' => 'text-right');
	            $row_col5    = array('data' => $row->desc_frecuencia,'class' => 'text-center');
	            $row_col6    = array('data' => $row->__codigo_criterio_efqm,'class' => 'text-left');
	            ${'row_col' . $cantCompar} = array('data' => $compar);
	            $idIndicadorActual = $row->_id_indicador;
	            $val++;
	            $cantCompar++;
	        }else{//PARA EL 0(PRIMER DATO)
	            $compar = $row->desc_comparativa.' - '.$row->valor_comparativa.$row->tipo_valor;
	            $row_col0    = array('data' => $val);
	            $row_col1    = array('data' => '<small>('.$row->cod_indi.')</small>'.$row->desc_registro.'</p>');
	            $row_col2    = array('data' => $row->valor_actual_porcentaje.$tipoValor._calculateIcon($row->diff_actual_y_anterior, $tipoValor),'class' => 'text-center');
	            $row_col3    = array('data' => $row->valor_meta.$tipoValor,'class' => 'text-right');
	            $row_col5    = array('data' => $row->desc_frecuencia,'class' => 'text-center');
	            $row_col6    = array('data' => $row->__codigo_criterio_efqm,'class' => 'text-left');
	            ${'row_col' . $cantCompar} = array('data' => $compar);
	            $idIndicadorActual = $row->_id_indicador;
	            $val++;
	            $cantCompar++;
	        } 
	        $verDetalle='<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="goToIndicadorDetalle(\''.$idIndicador.'\')"><i class="mdi mdi-edit"></i></button>';
	        $row_col10  = array('data' => $verDetalle);
	    }
	    $CI->table->add_row($row_col0, $row_col1, $row_col2, $row_col3,$row_col5,$row_col6,$row_col7, $row_col8, $row_col9,$row_col10);
	    $tabla = $CI->table->generate();
	    return $tabla;
	}
	
	function editarMeta(){
	    $data['err'] = 1;
	    $data['msg'] = null;
	    try {
	        $valor = $this->encrypt->decode($this->input->post('pk'));
	        if($valor == null){
	            throw new Exception(ANP);
	        }
	        $data['pk']    = $valor;
	        $data['valor'] = 'si funciona';
	    } catch (Exception $e) {
	        $data['msg'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToIndicadorDetalle(){
	    $idIndicadorEncr = _post('id_indicador');
	    $idIndicador     = _simpleDecryptInt($idIndicadorEncr);
	    
	    $nIndicador = $this->m_utils->getById('bsc.indicador','desc_indicador','_id_indicador',$idIndicador);
	    $nombreIndicador = _getDescReduce($nIndicador,40);
	    $dataUser = array("nombre_indicador"       => $nombreIndicador,
	                      "nombre_indicador_large" => $nIndicador
	    );
	    $this->session->set_userdata($dataUser);
	    
	    $url = '';
	    if($idIndicador != null){
	        $tipo =  $this->m_utils->getById("bsc.indicador","_id_tipo_estructura","_id_indicador",$idIndicador);
	        $dataUser = array("id_indicador" => $idIndicadorEncr, "tipo_estructura_indicador" => $tipo);        
	        $this->session->set_userdata($dataUser);
	        $url = 'c_detalle_indicador';
	    }
	    echo $url;
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