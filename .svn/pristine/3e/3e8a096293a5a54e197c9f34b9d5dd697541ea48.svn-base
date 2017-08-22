<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_categoria extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;

    public function __construct() {
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
        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_GRAFICOS_BSC, BSC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
        $data['titleHeader']      = 'Categor&iacute;as';
        $data['rutaSalto']        = 'SI';
	    
	    if($this->_idRol == ID_ROL_DIRECTOR || $this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_SUBDIRECTOR || $this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	        $idObjetivoEnc     = _getSesion('id_objetivo');
	        $idObjetivo        = _simple_decrypt($idObjetivoEnc);
	        $data['return']    = '';
	        $categorias = $this->m_lineaEstrat->getCategoriasByObjetivo($idObjetivo, 0);
	        $data['categorias']      = $this->createVistaRapida_categorias($categorias);
	        $data['countCategorias'] = count($categorias);
	    }
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $this->load->view('vf_indicador/v_categoria',$data);    
	}
	
	function createVistaRapida_categorias($data) {
	    $vista    = null;
	    $var      = 0;
	    $cabecera = '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="padding-bottom:15px">';
	    foreach ($data as $row) {
	        $dorado = 0;
	        if($row->dorados == $row->tod) {
	            $dorado = 1;
	        }
	        $idCategoria = _simple_encrypt($row->id_categoria);
	        $descripcion = _getDescReduce($row->desc_categoria,113);
	        $color       = $this->getColoresGraficos($row->flg_amarillo, $row->flg_verde, $row->porcentaje, $dorado);

	        $vista .= '      <div class="mdl-card mdl-indicator">
                                <div class="mdl-card__title backCirc'.$var.'">
                                    <div class="mdl-graphic container-gauge linEst'.$var.'" id="cont'.$var.'"  data-porcentaje="'.$row->porcentaje.'" data-porcent1="'.$row->flg_amarillo.'" data-porcent2="'.$row->flg_verde.'"
                                     data-cBack="'.$color.'" data-dorado="'.$dorado.'" ></div>
                                </div>
                                <div class="mdl-card__supporting-text" >
                                    <h4 class="desc_linea" data-toggle="tooltip" title="'.$descripcion.' ('.$row->porcentaje.' %)" data-placement="bottom">'.$descripcion.'</h4>
                                </div>
                                <div class="mdl-card__menu">
                                    <button id="menu-'.$var.'" class="mdl-button mdl-js-button mdl-button--icon">
                                        <i class="mdi mdi-more_vert"></i>
                                    </button>
                                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$var.'">
                                        <li class="mdl-menu__item" onclick="openEditarValorAmarilloCategoria(\''.$idCategoria.'\' , \'cont'.$var.'\' , \''.$var.'\')"><i class="mdi mdi-edit"></i>Editar valor</li>
                                    </ul>
                                </div>
                                <div class="mdl-card__actions">
                                    <ul class="mdl-list-numbers">
                                        <li data-toggle="tooltip" data-title="Copa" data-placement="top"><i class="mdi mdi-cup"></i> '.$row->dorados.'</li>
                                        <li data-toggle="tooltip" data-title="Indicadores" data-placement="top"><i class="mdi mdi-assistant_photo"></i> '.$row->nro_indicadores.'</li>
                                    </ul>
                                </div>
                                <div class="mdl-card__actions ingresar text-center m-b-5">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="goToIndicadores(\''.$idCategoria.'\')">Ingresar</button>  
                                </div>
                                <div id="barra'.$var.'" class="mdl-bar_state barraEstado"></div>
                            </div>';
	        $var++;
	    }
	     
	    return $vista;
	}
	
	function getColoresGraficos($amarillo, $verde, $porcentaje, $dorado) {
	    $color = null;
	    if($porcentaje <= $amarillo) {
	        $color = "mdl-state__red";
	    } else if($porcentaje < $verde && $porcentaje >= $amarillo) {
	        $color = "mdl-state__yellow";
	    } else if($porcentaje >= $verde && $porcentaje <= 100) {
	        $color = "mdl-state__cyan";
	    }
	    if($dorado == 1) {
	        $color = "#E1A032";
	    }
	    return $color;
	}
	
	function goToIndicadores() {
	    $idCategoriaEnc = _post('id_categoria');
	    $idCategoria    = _simple_decrypt($idCategoriaEnc);
	     
	    $nombreCategoria = _getDescReduce($this->m_utils->getById('bsc.categoria','desc_categoria','id_categoria',$idCategoria),25);
	     
	    $dataUser = array("nombre_categoria" => $nombreCategoria);
	    $this->session->set_userdata($dataUser);
	     
	    $url = '';
	    if($idCategoria != null){
	        $dataUser = array("id_categoria" => $idCategoriaEnc);
	        if($this->_idRol == ID_ROL_DIRECTOR_CALIDAD) {
	            $dataUser['es_director_calidad'] = true;
	        }
	        if($this->_idRol == ID_ROL_PROMOTOR || $this->_idRol == ID_ROL_DIRECTOR) {
	            $dataUser['es_director_direc_promot_ver_cate'] = true;
	        }
	        $this->session->set_userdata($dataUser);
	        $url = base_url().'bsc/cf_indicador/c_indicador_rapido';
	    }
	    echo $url;
	}
	
	function getValorAmarilloCategoria() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idCategoriaEncry = _post('idCategoria');
	        $idCategoria  = _simpleDecryptInt($idCategoriaEncry, CLAVE_ENCRYPT);
	        $idCont = _post('idCont');
	        $pos    = _post('pos');
	        if($idCategoria == null) {
	            throw new Exception('El id de indicador no es válido');
	        }
	        $datosObj = $this->m_utils->getCamposById('bsc.categoria', array('flg_amarillo', 'flg_verde'), 'id_categoria', $idCategoria);
	        $data['valorAmarillo'] = $datosObj['flg_amarillo'];
	        $data['valorVerde']    = $datosObj['flg_verde'];
	        $data['error']         = EXIT_SUCCESS;
	        $dataUser = array(
	            'idCont'       => $idCont,
	            'id_categoria' => $idCategoriaEncry,
	            '"posicion'    => $pos);
	        $this->session->set_userdata($dataUser);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function editValorAmarilloCategoria() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idCategoria   = _simpleDecryptInt(_post('idCategoria'));
	        $valorAmarillo = _post('valor');
	        $valorMeta     = _post('meta');
	        $posicion      = _getSesion('posicion');
	        $idCont        = _getSesion('idCont');
	        if(strlen($valorAmarillo) == 0 || strlen($valorMeta) == 0) {
	            throw new Exception('Ingrese los valores');
	        }
	        if (!is_float($valorAmarillo) && !is_numeric($valorAmarillo) ) {
	            throw new Exception('La zona de riesgo debe ser un n&uacute;mero');
	        }
	        if (!is_float($valorMeta) && !is_numeric($valorMeta) ) {
	            throw new Exception('La meta debe ser un n&uacute;mero');
	        }
	        if($valorAmarillo >= $valorMeta) {
	            throw new Exception('La zona de riesgo tiene que ser menor a la meta');
	        }
	        if($valorMeta > 100) {
	            throw new Exception('error', 'La meta no puede ser mayor a 100%');
	        }
	        $update        = array('id_categoria' => $idCategoria,
	                               'flg_amarillo' => $valorAmarillo,
	                               'flg_verde'    => $valorMeta);
	        $data = $this->m_lineaEstrat->updateFlgAmarillo($update, 'categoria', 'id_categoria');
	        $data = $this->buildGaugeCategoriaHTML($idCategoria, $posicion, $data);
	        $data['idCont']    = $idCont;
	        $data['posicion']  = $posicion;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildGaugeCategoriaHTML($idCategoria, $var, $result){
	    $idObjetivoEnc = _getSesion('id_objetivo');
	    $idObjetivo    = _simple_decrypt($idObjetivoEnc);
	    $data = $this->m_lineaEstrat->getCategoriasByObjetivo($idObjetivo, $idCategoria);
	    $porcentaje = $data['porcentaje'];
	    $dorado = 0;
	    if($data['dorados'] == $data['tod']) {
	        $dorado = 1;
	    }
	    $color = $this->getColoresGraficos($data['flg_amarillo'], $data['flg_verde'], $porcentaje, $dorado);
	    $list =  array('porcentaje' => $porcentaje,
	                   'p_verde'    => $data['flg_verde'],
	                   'p_amarillo' => $data['flg_amarillo'],
	                   'color'      => $color,
	                   'dorado'     => $dorado
	    );
	    $dorado = 0;
	    if($data['porcentaje'] == 100) {
	        $dorado = 1;
	    }
	    $result['porcent_lineas_num'] = json_encode($list);
	    return $result;
	}
	
	function enviarFeedBack(){
	    $nombre = $this->session->userdata('nombre_usuario');
	    $mensaje = $this->input->post('feedbackMsj');
	    $url = $this->input->post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
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
	
	    $idRol     = $this->session->userdata('nombre_rol');
	
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
}