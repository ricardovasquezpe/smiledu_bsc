<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_config_medida_nota extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_config_medida_nota');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_CONFIG_MEDIA_RUSH);
        $this->_idUserSess = _getSesion('nid_persona');
    }

	public function index() {        
	    $data['titleHeader']      = 'Configuracion Medida Rash';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
        $data['tablaConfigMedidaRashNota'] = $this->buildTablaConfigMedidaNotaHTML(null);
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
        
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
    	$data['comboTipoConfig'] = __buildComboByGrupo(ID_GRUPO_21_TIPO_CONFIGURACION);
    	
        $this->load->view('vf_mantenimiento/v_config_medida_nota', $data);
	}
		
	function buildTablaConfigMedidaNotaHTML($config) {
	    $tipoEce = null;
	    if($config == ID_GRUPO_22_MEDIDAS_RASH_ECE) {
	        $tipoEce = ECE_EVALUACION;
	    } else if($config == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	        $tipoEce = EAI_EVALUACION;
	    } else if($config == ID_GRUPO_23_PROMEDIOS) {
	        $tipoEce = ID_GRUPO_23_PROMEDIOS;
	    }	  
	    $listaConfigMedidaNota = ($config != null ) ? $this->m_config_medida_nota->getConfigMedidaRashNota($tipoEce) : array();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_config_medidarash_nota">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
	    $head_2 = array('data' => 'Configuraci&oacute;n');
        $head_3 = array('data' => 'Puntaje', 'class' => 'text-center');
	    
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3);
	    foreach($listaConfigMedidaNota as $row){;	    
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => $row->desc_config);
	        $row_cell_3  = array('data' => $this->getSpan("classPtje", _encodeCI($row->id_config), _encodeCI($row->grupo), _encodeCI($row->id_nota) ).intval($row->valor_numerico).'</span>', 'class' => 'text-center');
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;	   
	}
	
	function getSpan($clase, $id, $grupo, $idNota) {
	    return '<span class="'.$clase.' editable editable-click" data-pk="'.$id.'" data-grupo="'.$grupo.'" data-id_nota="'.$idNota.'">';
	}
	
	function getConfigByMedidaRashPromedio_CTRL(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $config = _simpleDecryptInt(_post('config'));
	        $dataconfig = array('config' => $config);
	        $this->session->set_userdata($dataconfig);
	        if($config == null) {
	            $data['error']    = EXIT_WARM;
	            $data['tablaConfigMedidaRashNota'] = $this->buildTablaConfigMedidaNotaHTML(null);
	            throw new Exception(null);
	        }
	        $data['tablaConfigMedidaRashNota'] = $this->buildTablaConfigMedidaNotaHTML($config);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	//MODAL POPUP INICIO	
	function comboMedidaPromedio() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $configpopup = _simpleDecryptInt(_post('configpopup'));
	        if($configpopup == null) {
	            $data['error'] = EXIT_WARM;
	            throw new Exception(null);
	        }
	        if(!in_array($configpopup, $this->m_utils->getValoresArrayTipoByGrupo(ID_GRUPO_21_TIPO_CONFIGURACION))) {
	            throw new Exception(ANP);
	        }
	        $data['optPromMedida'] = __buildComboByGrupo($configpopup);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
		
	function grabarMedidaRashPromedioPuntajesPopup(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $tipoConfig = _simpleDecryptInt(_post('configpopup'));
	        if($tipoConfig == null) {
	            $data['error'] = EXIT_WARM;
	            throw new Exception(null);
	        }
	        if(!in_array($tipoConfig, $this->m_utils->getValoresArrayTipoByGrupo(ID_GRUPO_21_TIPO_CONFIGURACION))) {
	            throw new Exception(ANP);
	        }
	        $idconfigPromMedida  = _simpleDecryptInt(_post('configPromMedida'));//id_nota
	        $puntaje = _post('puntaje');
	        if($idconfigPromMedida == null ) {
	            throw new Exception(ANP);
	        }
	        if($puntaje == null) {
	            throw new Exception("Ingrese un puntaje");
	        }
	        if(strlen($puntaje) > 6) {
	            throw new Exception('El puntaje no debe exceder 6 caracteres');
	        }
	        if(!ctype_digit((string) $puntaje)) {
	            throw new Exception("El puntaje debe tener n�meros enteros");
	        }
	        if($puntaje <= 0) {
	            throw new Exception("El puntaje debe ser mayor que cero");
	        }
	        $tipoEce = null;
	        $descConfig = null;
	        if($tipoConfig == ID_GRUPO_22_MEDIDAS_RASH_ECE) {
	            $tipoEce = ECE_EVALUACION;
	        } else if($tipoConfig == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	            $tipoEce = EAI_EVALUACION;
	        } else if($tipoConfig == ID_GRUPO_23_PROMEDIOS) {
	            $tipoEce = ID_GRUPO_23_PROMEDIOS;
	        }
	        $existe = $this->m_config_medida_nota->getExisteMedidaRash($tipoEce, $idconfigPromMedida);
	        
	        if($existe != null) {
	            throw new Exception('La configuraci�n ya se encuentra registrada');
	        }
	        //
	        $descConfig = $this->validarConfig($tipoConfig, $idconfigPromMedida, $puntaje, $tipoEce);
	        $arrayDatos = array("id_nota"        => $idconfigPromMedida,
                	            "year_config"    => _getYear(),
                	            "valor_numerico" => $puntaje,
	                            "tipo_ece"       => $tipoEce,
                	            "desc_config"    => $descConfig,
	                            "id_rash"        => $idconfigPromMedida);
	        $data = $this->m_config_medida_nota->insertaConfigPopup($arrayDatos);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['tablaConfigMedidaRashNota'] = $this->buildTablaConfigMedidaNotaHTML($tipoConfig);
	        }
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	function validarConfig($tipoConfig, $idconfigPromMedida, $puntaje, $tipoEce) {
	    if($tipoConfig == ID_GRUPO_22_MEDIDAS_RASH_ECE || $tipoConfig == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	        $aComparar = null;
	        if($idconfigPromMedida == EAI_INICIO) {
	            $aComparar = $this->m_config_medida_nota->getExisteMedidaRash($tipoEce, EAI_PROCESO);
	            if($aComparar != null && $puntaje >= $aComparar) {
	                throw new Exception('El puntaje de \'En Inicio\' no puede ser mayor o igual que el valor \'En Proceso\' ');
	            }
	            if($tipoConfig == ID_GRUPO_22_MEDIDAS_RASH_ECE) {
	                $descConfig = ECE_MEDIDA_RASH_INICIO;
	            }
	            if($tipoConfig == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	                $descConfig = EAI_MEDIDA_RASH_INICIO;
	            }
	        } else if($idconfigPromMedida == EAI_PROCESO) {
	            $aComparar = $this->m_config_medida_nota->getExisteMedidaRash($tipoEce, EAI_INICIO);
	            if($aComparar != null && $puntaje <= $aComparar) {
	                throw new Exception('El puntaje de \'En Proceso\' no puede ser menor o igual que el valor \'En Inicio\' ');
	            }
	            if($tipoConfig == ID_GRUPO_22_MEDIDAS_RASH_ECE) {
	                $descConfig = ECE_MEDIDA_RASH_PROCESO;
	            }
	            if($tipoConfig == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	                $descConfig = EAI_MEDIDA_RASH_PROCESO;
	            }
	        }
	    } else if($tipoConfig == ID_GRUPO_23_PROMEDIOS) {
	        if($puntaje <= 0 || $puntaje > 20){
	            throw new Exception("El promedio debe estar entre 0 y 20");
	        }
	        switch ($idconfigPromMedida) {
	            case 1: $descConfig = PROMEDIO_ORDEN_MERITO;break;
	            case 2: $descConfig = PROMEDIO_TERCIO_SUPERIOR;break;
	            case 3: $descConfig = PROMEDIO_FINAL;break;
	            case 4: $descConfig = PROMEDIO_SD;break;
	        }
	    }
	    return $descConfig;
	}
	
	//FIN DEL MODAL POPUP
	function editarPuntaje() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $pk = _decodeCI(_post('pk'));
	        if($pk == null) {
	            throw new Exception(ANP);
	        }
	        $pkEncry     = _post('pk');
	        $valor       = trim(utf8_decode(_post('value')) );
	        $columna     = _post('name');//columna
	        if($columna != 'valor_numerico') {
	            throw new Exception(ANP);
	        }
	        $grupo = _decodeCI(_post('grupo') );
	        if($grupo == null) {
	            throw new Exception(ANP.'3');
	        }
	        $idNota = _decodeCI(_post('id_nota') );
	        if($idNota == null) {
	            throw new Exception(ANP);
	        }
	        if($valor == null) {
	            throw new Exception("Ingrese un puntaje");
	        }
	        if(strlen($valor) > 6) {
	            throw new Exception('El puntaje no debe exceder 6 caracteres');
	        }
	        if(!ctype_digit((string) $valor)) {
	            throw new Exception("El puntaje debe tener n�meros enteros");
	        }
	        if($valor <= 0) {
	            throw new Exception("El puntaje debe ser mayor que cero");
	        }
	        $tipoEce = null;
	        if($grupo == ID_GRUPO_22_MEDIDAS_RASH_ECE) {
	            $tipoEce = ECE_EVALUACION;
	        } else if($grupo == ID_GRUPO_24_MEDIDAS_RASH_EAI) {
	            $tipoEce = EAI_EVALUACION;
	        } else if($grupo == ID_GRUPO_23_PROMEDIOS) {
	            $tipoEce = ID_GRUPO_23_PROMEDIOS;
	        }
	        $descConfig = $this->validarConfig($grupo, $idNota, $valor, $tipoEce);
	        $data = $this->m_config_medida_nota->editPuntajeConfig($pk, $columna, $valor, $tipoEce);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['pk']  = $pkEncry;
	        }
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	        header("HTTP/1.0 666 ".$data['msj'], TRUE, NULL);
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = utf8_decode(_post('feedbackMsj'));
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}