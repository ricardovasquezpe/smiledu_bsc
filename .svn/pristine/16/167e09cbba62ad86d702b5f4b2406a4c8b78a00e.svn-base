<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_contactos extends CI_Controller {

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
        $this->load->model('m_utils_admision');
        $this->load->model('mf_contactos/m_contactos');
        $this->load->model('mf_evento/m_detalle_evento');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_ADMISION, ID_PERMISO_CONTACTOS, ADMISION_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(ADMISION_ROL_SESS);
    }
   
	public function index() {
	    $data = _searchInputHTML('Busca tus contactos', 'onchange = "buscarPersona()"');
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_ADMISION, ADMISION_FOLDER);
	    ////Modal Popup Iconos///
    	$rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_ADMISION, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['titleHeader']      = "Contactos";
	    $data['ruta_logo']        = MENU_LOGO_ADMISION;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_ADMISION;
	    $data['nombre_logo']      = NAME_MODULO_ADMISION;
	    $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                   <a href="#tab-1"  class="mdl-layout__tab" id="tabProspectos" onclick = "getFamiliasProspecto(\''._simple_encrypt(ESTADO_CONTACTO_CONTACTADO).'\', this)">Prospecto</a>
                                   <a href="#tab-3"  class="mdl-layout__tab" onclick = "getFamiliasByEstado(\''._simple_encrypt(ESTADO_CONTACTO_TOUR).'\', this)">Tour</a>
                                   <a href="#tab-4"  class="mdl-layout__tab" id="tabEval" onclick = "getFamiliasEvaluacion(\''._simple_encrypt(ESTADO_CONTACTO_EVALUADO_PROCESO).'\', this)">Evaluaci&oacute;n</a>
                                   <a href="#tab-7"  class="mdl-layout__tab" id="tabPorMatricular" onclick = "getFamiliasPorMatricular(\''._simple_encrypt(ESTADO_CONTACTO_PAGO_CUOTA_INGRESO).'\', this)">Por matricular</a>
                                   <a href="#tab-9"  class="mdl-layout__tab" onclick = "getFamiliasByEstado(\''._simple_encrypt(ESTADO_CONTACTO_MATRICULADO).'\', this)">Matriculado</a>
                                   <a href="#tab-10" class="mdl-layout__tab" onclick = "getFamiliasVerano(\''._simple_encrypt(ESTADO_CONTACTO_VERANO).'\',this,\''._simple_encrypt(CRONO_SPORT_SUMMER).'\')">Verano</a>
                                </div>';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    //_log(print_r($this->session->userdata(),true));
	    
	    $data['comboSeguimiento'] = __buildComboByGrupo(COMBO_SEGUIMIENTO);
	    $data['comboCanales']     = __buildComboByGrupo(COMBO_CANAL_COMUNICACION);
	    $data['comboYear']        = __buildComboYearContactos();
	    $data['comboYearVerano']  = __buildComboYearVerano();
	    
	    $data['comboSexo']          = __buildComboByGrupo(COMBO_SEXO);
	    $data['comboTipoDocumento'] = __buildComboByGrupoNoEncryptId(COMBO_TIPO_DOC);
	    $data['comboColegios']      = '<option value="0">'.strtoupper('En casa').'</option>'.__buildComboColegios();
	    $data['comboGradoNivel']    = __buildComboGradoNivel();
	    
	    $data['contactado']      = _simple_encrypt(ESTADO_CONTACTO_CONTACTADO);
	    $data['porContactar']    = _simple_encrypt(ESTADO_CONTACTO_POR_CONTACTAR);
	    $data['evaluadoProceso'] = _simple_encrypt(ESTADO_CONTACTO_EVALUADO_PROCESO);
	    $data['evaluadoApto']    = _simple_encrypt(ESTADO_CONTACTO_EVALUADO_APTO);
	    $data['evaluadoNoApto']  = _simple_encrypt(ESTADO_CONTACTO_EVALUADO_NO_APTO);
	    $data['pagoCuota']       = _simple_encrypt(ESTADO_CONTACTO_PAGO_CUOTA_INGRESO);
	    $data['pagoMatricula']   = _simple_encrypt(ESTADO_CONTACTO_PAGO_MATRICULA);
	    $data['verano']          = _simple_encrypt(ESTADO_CONTACTO_VERANO);

	    $data['sport']           = _simple_encrypt(CRONO_SPORT_SUMMER);
	    $data['creative']        = _simple_encrypt(CRONO_CREATIVE_SUMMER);
	    //OPCIONES LISTA
	    $data['opcAsistira']     = _simple_encrypt(OPCION_ASISTIRA);
	    $data['opcTalvez']       = _simple_encrypt(OPCION_TALVEZ);
	    $data['opcNoAsistira']   = _simple_encrypt(OPCION_NO_ASISTIRA);
	    
	    $grupo = $this->m_contactos->getGrupo(NUMERO_FAMILIAS_CARGA, $this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'));
	    
	    if(empty($grupo)){
	       $data['cardsFamilia'] = null;
	    } else {
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, "Editar");
            $data['cardsFamilia'] = $result['cardsFamilia'];
            $data['max_cod_grupo'] = $result['max_cod_grupo'];
	    }
	    $data['btnVerMas'] = null;
        $countPersonas = $this->m_contactos->getCountPersonas($this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'));
	    $data['cantidadScroll'] = count($grupo);
        if($data['cantidadScroll']<$countPersonas){
            $data['btnVerMas']='<button class="mdl-button mdl-js-button" id="btnVerMasFamilias" onclick="verMasFamilias()">VER M&Aacute;S</button>';
	    }
	    
	    if($this->_idRol == ID_ROL_MARKETING){
	        $data['fabFiltrar'] = '<li>
                                        <button class="mfb-component__button--child" data-toggle="modal" data-target="#modal" data-mfb-label="Filtrar" >
                                            <i class="mfb-component__child-icon mdi mdi-filter_list"></i>
                                        </button>
                                    </li>  
                                     <li>
                                        <button class="mfb-component__button--child" data-mfb-label="Correos" onclick="descargarCorreos()">
                                            <i class="mfb-component__child-icon mdi mdi-mail"></i>
                                        </button>
                                    </li> ';
	    }
	    
	    $this->load->view('v_contactos',$data);
	}
	
	function buscarPersona(){
	    $data['error'] = EXIT_ERROR;
	    $textoBusqueda = (_post('textoBusqueda')!= null) ? _post('textoBusqueda') : null;
	    try {
    	    if(strlen($textoBusqueda)>=3 || strlen($textoBusqueda) == 0){
    	        $grupo = $this->m_contactos->getGrupoByBusqueda($textoBusqueda, NUMERO_FAMILIAS_CARGA, 0, $this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona')); 
    	    }
	        if(!empty($grupo)){
	            $data['count'] = count($grupo);
	            $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                        <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	            $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	            $data['cardsFamilia'] = $result['cardsFamilia'];
	            $data['max_cod_grupo'] = $result['max_cod_grupo'];
	        }else{
	            $data['cardsFamilia'] = null;
	        }
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function onScrollGetFamilias(){
        $data['error'] = EXIT_ERROR;
	    $scroll         = _post('scroll');
	    $mult           = _post('mult');
	    $textoBusqueda  = _post('textobusqueda');
	    $year           = _simpleDecryptInt(_post('year'));
	    $sede           = _simpleDecryptInt(_post('sede'));
	    $gradoNivel     = _post('gradoNivel')  != null ? _simple_decrypt(_post('gradoNivel'))  : null;
	    $estado         = _post('estado')      != null ? _simpleDecryptInt(_post('estado'))    : null;
	    $cod_grupo      = _post('maxgrupo');
	    $tipoproceso    = _post('tipoproceso') != null ? _simple_decrypt(_post('tipoproceso')) : null;
	    if($scroll == 1){
	        $grupo = $this->m_contactos->getGrupo(NUMERO_FAMILIAS_CARGA, $this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),$cod_grupo);
	    } else if ($scroll == 2){
	        if(strlen($textoBusqueda)>=3){                                // BUSQUEDA
	            $grupo = $this->m_contactos->getFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA,$this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),$cod_grupo, $tipoproceso);
	        } else if($year != null){                                     // FILTRO
	            if($gradoNivel != null){
	                $gradoNivel = explode("_", $gradoNivel);
	                $grupo = $this->m_contactos->getFamiliasByEstadoYear($estado, $year,$sede,$gradoNivel[1],$gradoNivel[0],NUMERO_FAMILIAS_CARGA, $cod_grupo);
	            } else {
	                $grupo = $this->m_contactos->getFamiliasByEstadoYear($estado, $year,$sede,null,null,NUMERO_FAMILIAS_CARGA, $cod_grupo);
	            }
	        } else if(strlen($textoBusqueda)==0 && $year == null){
	            $grupo = $this->m_contactos->getFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA,$this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),$cod_grupo, $tipoproceso);
	        }
	    } else if ($scroll == 3){
	        $grupo = $this->m_contactos->getGrupoByBusqueda($textoBusqueda, NUMERO_FAMILIAS_CARGA, $cod_grupo,$this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'));
	    }
	    $data['tipoproceso'] = $tipoproceso;
	    if(empty($grupo)){
            $data['cardsFamilia']  = NULL;
	    } else {
    	    $data['estado'] = ($estado!=null) ? $estado : 1;
    	    $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
    	    if($tipoproceso != null){
    	    	$opc = '<li class="mdl-menu__item" onclick="abrirModalInscribirVerano(this)">Inscribir a Verano</li>';
    	    }
    	    
    	    $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
            $data['cardsFamilia']  = $result['cardsFamilia'];
            $data['max_cod_grupo'] = $result['max_cod_grupo'];
    	    $data['error'] = EXIT_SUCCESS;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function verMasFamilias(){
        $data['error'] = EXIT_ERROR;
	    $mult  = _post('mult');
	    $count = _post('count');
	    $cod_grupo = _post('maxgrupo');
	    $grupo = $this->m_contactos->getGrupo(NUMERO_FAMILIAS_CARGA, $this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),$cod_grupo);
	    if(empty($grupo)){
            $data['cardsFamilia'] = NULL;
	    } else {
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia']  = $result['cardsFamilia'];
            $data['max_cod_grupo'] = $result['max_cod_grupo'];;
	        $data['error'] = EXIT_SUCCESS;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getFamiliasByEstado(){
	    $data['error']  = EXIT_ERROR;
	    $textoBusqueda  = _post('textobusqueda');
	    $estado         = _simple_decrypt(_post('estadocontacto'));
	    $year           = _simpleDecryptInt(_post('year'));
	    $sede           = _simpleDecryptInt(_post('sede'));
	    $gradoNivel     = (_post('gradoNivel')!=null) ? _simple_decrypt(_post('gradoNivel')) : null;
	    $tipoproceso    = _post('tipoproceso') != null ? _simple_decrypt(_post('tipoproceso')) : null;
	    if(strlen($textoBusqueda)>=3){                                // BUSQUEDA
	        $grupo = $this->m_contactos->getFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA,$this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),0,$tipoproceso);
	    } else if($year != null){                                     // FILTRO
	        if($gradoNivel != null){
	            $gradoNivel = explode("_", $gradoNivel);
	            $grupo = $this->m_contactos->getFamiliasByEstadoYear($estado, $year,$sede,$gradoNivel[1],$gradoNivel[0],NUMERO_FAMILIAS_CARGA);
	        } else {
	            $grupo = $this->m_contactos->getFamiliasByEstadoYear($estado, $year,$sede,null,null,NUMERO_FAMILIAS_CARGA);
	        }
	    } else if(strlen($textoBusqueda)==0 && $year == null){
	        $grupo = $this->m_contactos->getFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA,$this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'),0,$tipoproceso);
	    }
	    if($tipoproceso != null){
	    	$data['cont'] = $tipoproceso == CRONO_CREATIVE_SUMMER ? '11' : ''.ESTADO_CONTACTO_VERANO;
	    } else {
	    	$data['cont'] = ''.$estado;
	    }
	    if(empty($grupo)){
	        $data['cardsFamilia']  = NULL;
	    } else {
	        $data['count'] = count($grupo);
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        if($tipoproceso != null){
	        	$opc = '<li class="mdl-menu__item" onclick="abrirModalInscribirVerano(this)">Inscribir a Verano</li>';
	        }
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
            $data['cardsFamilia']  = $result['cardsFamilia'];
            $data['max_cod_grupo'] = $result['max_cod_grupo'];
	        $data['error'] = EXIT_SUCCESS;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function abrirModalLlamadas(){
	    $id_contacto = _simpleDecryptInt(_post('idcontacto'));
	    $data['telefono'] = _simpleDecryptInt(_post('telefono'));
	    $data['correo'] = _simple_decrypt(_post('correo'));
	    $llamadas = $this->m_contactos->getLlamadas($id_contacto);
	    $data['tablaLlamadas'] = _createTableLlamadas($llamadas);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function agregarLlamada(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = MSJ_ERROR;
	    
	    $id_evento     = _simpleDecryptInt(_post('idevento'));
	    $tipo_llamada  = _simpleDecryptInt(_post('tipoLlamada'));
	    $observacion   = (strlen(_post('observacion')) == 0) ? null : _post('observacion');
	    $id_contacto   = _simpleDecryptInt(_post('idcontacto'));
	    $opcion        = _post('opcion');
	    $id_persona_registro = ($opcion == 1) ? $this->_idUserSess : _simpleDecryptInt(_post('idcontacto'));
	    
	    try {
	        if($id_contacto == null){
	            throw new Exception(ANP);
	        }
	        if($id_evento == null){
	            throw new Exception('Debe seleccionar un evento');
	        }
	        if($tipo_llamada == null){
	            throw new Exception('Debe seleccionar un tipo de llamada');
	        }
	        
	        $data = array(
	            "id_evento"            => $id_evento,
	            "tipo_llamada"         => $tipo_llamada,
	            "observacion"          => (strlen($observacion)==0) ? null : utf8_decode($observacion),
	            "id_contacto"          => $id_contacto,
	            "id_persona_registro"  => $id_persona_registro
	        );
	        $data = $this->m_contactos->insertarLlamada($data);
	        if($opcion == 1){
	            if($data['error'] == 0){
	                $llamadas = $this->m_contactos->getLlamadas($id_contacto);
	                $data['tablaLlamadas'] = _createTableLlamadas($llamadas);
	            }
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getFamiliasByYear(){
	    $year = _simpleDecryptInt(_post('year'));
        $data['error'] = EXIT_ERROR;
	    
	    if ($year == null){
	        $grupo = $this->m_contactos->getGrupo(NUMERO_FAMILIAS_CARGA, $this->_idRol, _getSesion('id_sede_trabajo'), _getSesion('nid_persona'));
	    } else {
	        $grupo = $this->m_contactos->getFamiliasByEstadoYear(ESTADO_CONTACTO_CONTACTADO, $year,null,null,null, NUMERO_FAMILIAS_CARGA);
	        $sedes = $this->m_contactos->getSedeinteresByYear($year);
	        if($sedes != null){
	            $sedes = explode(",", $sedes);
	            $data['comboSedes'] = $this->createComboSedeInteres($sedes);
	        }
	        $gradoNivel = $this->m_contactos->getGradoNivelByYear($year,null);
	        if($gradoNivel != null){
	            $gradoNivel = explode(",", $gradoNivel);
	            $data['comboGradoNivel'] = $this->createComboGradoNivelByYear($gradoNivel);
	        }
	    }
	    if(empty($grupo)){
	        $data['cardsFamilia'] = null;
	    } else {
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia']  = $result['cardsFamilia'];
	        $data['max_cod_grupo'] = $result['max_cod_grupo'];
	        $data['error'] = EXIT_SUCCESS;
	    }
	    
	    //$data = $this->getFamiliasByYearAux($year,ESTADO_CONTACTO_CONTACTADO);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getFamiliasBySedeInteres(){
	    $data['error'] = EXIT_ERROR;
	    $year          = _simpleDecryptInt(_post('year'));
	    $sede          = _simpleDecryptInt(_post('sede'));
	    
	    if($year != null && $sede != null){
	        $grupo = $this->m_contactos->getFamiliasByEstadoYear(ESTADO_CONTACTO_CONTACTADO,$year,$sede,null,null,NUMERO_FAMILIAS_CARGA);
	        $gradoNivel = $this->m_contactos->getGradoNivelByYear($year, $sede);
	        if($gradoNivel != null){
	            $gradoNivel = explode(",", $gradoNivel);
	            $data['comboGradoNivel'] = $this->createComboGradoNivelByYear($gradoNivel);
	        }
	    } else if($sede == null){
	        $gradoNivel = $this->m_contactos->getGradoNivelByYear($year, $sede);
	        if($gradoNivel != null){
	            $gradoNivel = explode(",", $gradoNivel);
	            $data['comboGradoNivel'] = $this->createComboGradoNivelByYear($gradoNivel);
	        }
	    }
	    
	    if(empty($grupo)){
	        $data['cardsFamilia'] = null;
	    } else {
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia']  = $result['cardsFamilia'];
	        $data['max_cod_grupo'] = $result['max_cod_grupo'];
	        $data['error']         = EXIT_SUCCESS;
	    }

	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getFamiliasByGradoNivel(){
	    $data['error'] = EXIT_ERROR;
	    $year       = _simpleDecryptInt(_post('year'));
	    $sede       = _simpleDecryptInt(_post('sede'));
	    $gradoNivel = (_post('gradoNivel')!=null) ? _simple_decrypt(_post('gradoNivel')) : null;
	    
	    if($gradoNivel != null){
            $gradoNivel = explode("_", $gradoNivel);
	        $grupo = $this->m_contactos->getFamiliasByEstadoYear(ESTADO_CONTACTO_CONTACTADO,$year,$sede,$gradoNivel[1],$gradoNivel[0],NUMERO_FAMILIAS_CARGA);
	    } else if($gradoNivel == null){
	        $grupo = $this->m_contactos->getFamiliasByEstadoYear(ESTADO_CONTACTO_CONTACTADO,$year,$sede,null,null,NUMERO_FAMILIAS_CARGA);
	    }

	    if(empty($grupo)){
	        $data['cardsFamilia'] = null;
	    } else {
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia']  = $result['cardsFamilia'];
	        $data['max_cod_grupo'] = $result['max_cod_grupo'];    	    
	        $data['error'] = EXIT_SUCCESS;
        }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//COMBOS

	function createComboSedeInteres($sedes){
	    $sedes = $this ->m_contactos->getSedeByIdSede($sedes);
	    $opcion = '';
	    foreach ($sedes as $var){
	        $opcion .= '<option value="'._simple_encrypt($var->nid_sede).'">'.strtoupper($var->desc_sede).'</option>';
	    }
	    return $opcion;
	}
	
	function createComboGradoNivelByYear($gradoNivel){
	    $gradoNivel = $this ->m_contactos->getGradoNivelByIDs($gradoNivel);
	    $opcion = '';
	    foreach ($gradoNivel as $var){
	        $opcion .= '<option value="'._simple_encrypt($var->id_grado_nivel).'">'.strtoupper($var->descrip).'</option>';
	    }
	    return $opcion;
	}
	
	//UTILES
	
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
	
	function cambioRol() {
	    $idRol = _simple_decrypt($this->_idRol);
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion('id_rol');
	    $roles  = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol = _simple_encrypt($var->nid_rol);
	        $return  .= "<li class='".$class."'>";
	        $return .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    //POR DEFINIR
    /*function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}*/
    
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function invitarContacto(){
        $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $idevento     = (_post('idevento')!=null) ? _simple_decrypt(_post('idevento')) : null;
            $cod_grupo    = (_post('codgrupo')!=null) ? _simple_decrypt(_post('codgrupo')) : null;
	        $arrayHoras           = _post("horas");
	        $arrayOpciones        = _post("opciones");
	        $arrayContactos       = _post("contactos");
	        $arrayObservaciones   = _post("observaciones");
            $tipo_evento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idevento);
	        
            /*if( $cod_grupo == null ){
                throw new Exception(ANP);
            }*/
	        if(count($arrayContactos) <= 0 || $idevento == null ){
	            throw new Exception(ANP);
	        }
	        
	        $postulante_asist   = 0;
	        $pariente_asist     = 0;
	        $noAsistiran        = 0;
	        $tal_vez_estudiante = 0;
	        $tal_vez_pariente   = 0;
	        for ($i = 0; $i < count($arrayContactos); $i++) {
	            $est = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", _simpleDecryptInt($arrayContactos[$i]));
	            if($est == FLG_ESTUDIANTE){
	                if($arrayOpciones[$i] == OPCION_TALVEZ){
	                    $tal_vez_estudiante++;
	                }
	                if ($arrayOpciones[$i] == OPCION_ASISTIRA){
	                    $postulante_asist++;
	                }
	            } else {
	                if($arrayOpciones[$i] == OPCION_TALVEZ){
	                    $tal_vez_pariente++;
	                }
	                if ($arrayOpciones[$i] == OPCION_ASISTIRA){
	                    $pariente_asist++;
	                }
	            }
	            if($arrayOpciones[$i] == OPCION_NO_ASISTIRA){
	                $noAsistiran++;
	            }
	        }
	        
	        if($tipo_evento == TIPO_EVENTO_EVALUACION){
	            if($noAsistiran != count($arrayContactos)){
	                if($pariente_asist > 0 && $postulante_asist == 0){
                        throw new Exception("Para la evaluación, debe elegir por lo menos a un postulante");
	                }
	                if($postulante_asist > 0 && $pariente_asist == 0){
	                    throw new Exception("Para la evaluación, debe elegir por lo menos a un pariente");
	                }
                    if($postulante_asist == 0 && $tal_vez_estudiante == 0){
                        throw new Exception("Para la evaluación, debe elegir por lo menos a un postulante");
                    }
                    if($pariente_asist == 0 && $tal_vez_pariente == 0){
                        throw new Exception("Para la evaluación, debe elegir por lo menos a un pariente");
                    }
                }
	        } else {
	            if($noAsistiran != count($arrayContactos)){
	                if($pariente_asist == 0 && $tal_vez_pariente == 0){
	                    throw new Exception("Para un tour, debe elegir por lo menos a un pariente");
	                }
	                if($postulante_asist > 0 && $pariente_asist == 0){
	                    throw new Exception("Para un tour, debe elegir por lo menos a un pariente");
	                }
	            }
	        }
	        
	        for ($i = 0; $i < count($arrayContactos); $i++) {
	            $idContacto = _simpleDecryptInt($arrayContactos[$i]);
	            $existeContacto = $this->m_detalle_evento->validExisteContactoInvitacionEvento($idContacto, $idevento);
	            $valor = null;
	            if($arrayOpciones[$i] == OPCION_ASISTIRA && ($tipo_evento == TIPO_EVENTO_EVALUACION || $tipo_evento == TIPO_EVENTO_EVALUACION_VERANO) && strlen($arrayHoras[$i]) != 0){
	                $valor = _simpleDecryptInt($arrayHoras[$i]);
	            }
	            if($existeContacto == 0){//INSERT
	                $arrayInsert = array("id_evento"              => $idevento,
                	                     "id_contacto"            => $idContacto,
                	                     "opcion"                 => $arrayOpciones[$i],
                	                     "asistencia"             => INASISTENCIA_CONTACTO,
                	                     "flg_asistencia_directa" => ASISTENCIA_INVITACION_CONTACTO,
                	                     "id_hora_cita"           => strlen($arrayHoras[$i]) != 0 ? $valor : null,
	                                     "razon_inasistencia"     => strlen($arrayObservaciones[$i]) != 0 ? utf8_decode($arrayObservaciones[$i]) : null);
	                $data = $this->m_detalle_evento->insertInvitacionEvento($arrayInsert);
	            }else{//UPDATE
	                $arrayUpdate = array("opcion"                 => $arrayOpciones[$i],
	                                     "id_hora_cita"           => strlen($arrayHoras[$i]) != 0 ? $valor : null, 
	                                     "razon_inasistencia"     => strlen($arrayObservaciones[$i]) != 0 ? utf8_decode($arrayObservaciones[$i]) : null);
	                $data = $this->m_detalle_evento->updateInvitacionEvento($arrayUpdate, $idevento, $idContacto);
	            }
	        }
	        if($data['error'] == EXIT_SUCCESS){
	            $data['codgrupo']           = $cod_grupo;
	            $data['cantidadInvitados'] = $this->m_contactos->countInvitadosEventoByGrupo($cod_grupo);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getSedesByNivel(){
	    $valorNivel = (_post('valorNivel'))==null ? null : _simple_decrypt(_post('valorNivel'));
	    if($valorNivel != null){
	        $valorNivel = explode('_', $valorNivel);
	        $data['comboSedes'] = __buildComboSedesAdmision($valorNivel[1]);
	    } else if($valorNivel == null){
	        $data['comboSedes'] = __buildComboSedesAdmision(null);
	    }
	
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToViewContacto(){
	    $idcontacto = (_post('idcontacto'))!=null ? _simpleDecryptInt(_post('idcontacto')) : null;
	    $dataUser = array("idContactoEdit"        => $idcontacto,
	                      "accionDetalleContacto" => 0
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function goToEditContacto(){
	    $idcontacto = (_post('idcontacto'))!=null ? _simpleDecryptInt(_post('idcontacto')) : null;
	    $dataUser = array("idContactoEdit"         => $idcontacto,
	                       "accionDetalleContacto" => 1
	    );
	    $this->session->set_userdata($dataUser);
	}
	
	function agregarHermanoFamilia(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	
	    try {
	        $apePaterno = _post('apepaterno');
	        $apeMaterno = _post('apematerno');
	        $nombre     = _post('nombre');
	        $fechaNac   = _post('fechanac');
	        $numeroDoc  = _post('numerodoc');
	        $gradoNivel = (_post('gradonivel') != null) ?_simple_decrypt(_post('gradonivel')) : null;
	        $sede       = (_post('sede') != null) ?_simple_decrypt(_post('sede')) : null;
	        $sexo       = (_post('sexo') != null) ?_simple_decrypt(_post('sexo')) : null;
	        $colegio    = (_post('colegio') != null) ?_simple_decrypt(_post('colegio')) : null;
	        $tipoDoc    = _post('tipodoc');
	        $codGrupo   = (_post('codgrupo') != null) ?_simple_decrypt(_post('codgrupo')) : null;
	         
	        if(strlen(trim($apePaterno.$apeMaterno.$nombre)) == 0){
	            throw new Exception('Debe ingresar como m&iacute;nimo un dato b&aacute;sico');
	        }
	        if($gradoNivel != null){
	            $gradoNivel = explode('_', $gradoNivel);
	        }
	        if($fechaNac != null){
	            if(strlen($fechaNac)!=10){
	                throw new Exception('La fecha es incorrecta');
	            }
	            $fechaNacArray = explode('/', $fechaNac);
	            if(ctype_digit($fechaNacArray[0]) == false || ctype_digit($fechaNacArray[1]) == false || ctype_digit($fechaNacArray[2]) == false){
	                throw new Exception('La fecha solo puede contener d&iacute;gitos');
	            }
	            if($fechaNacArray[0]>31){
	                throw new Exception('El d&iacute;a ingresado no puede ser mayor a 31');
	            }
	            if($fechaNacArray[1]>12){
	                throw new Exception('El mes ingresado no puede ser mayor a 12');
	            }
	            if($fechaNacArray[2]>_getYear()){
	                throw new Exception('El a&ntilde;o ingresado no puede ser mayor al actual');
	            }
	        }
	        if($tipoDoc != null){
	            if(trim($numeroDoc) == null){
	                throw new Exception('Ingresar un n&uacute;mero de documento');
	            }
	        }
	        if($numeroDoc != null){
	            $numeroDoc = trim($numeroDoc);
	            if($tipoDoc == TIPO_DOC_DNI){
	                if(ctype_digit($numeroDoc)==false){
	                    throw new Exception('Solo ingresar n&uacute;meros en el dni');
	                }
	                if(strlen($numeroDoc)!=8){
	                    throw new Exception('Ingresar 8 digitos en el dni');
	                }
	                $countDNI = $this->m_utils->countDNI($numeroDoc);
	                if($countDNI > 0){
	                    throw new Exception('El dni especificado ya est&aacute; registrado');
	                }
	            } else if($tipoDoc == TIPO_DOC_CARNET_EXTRANJERO){
	                if(strlen($numeroDoc) != 12) {
	                    throw new Exception('El carnet de extranjer&iacute;a debe tener 12 caracteres');
	                }
	                $countDocExtran = $this->m_utils->countCarnetExtran($numeroDoc);
	                if($countDocExtran > 0){
	                    throw new Exception('El carnet de extranjer&iacute;a especificado ya est&aacute; registrado');
	                }
	            }
	        }
	         
	        $data = array(
	            "ape_paterno"          => _ucwords(__only1whitespace(utf8_decode($apePaterno))),
	            "ape_materno"          => _ucwords(__only1whitespace(utf8_decode($apeMaterno))),
	            "nombres"              => _ucwords(__only1whitespace(utf8_decode($nombre))),
	            "fecha_nacimiento"     => _fecha_tabla($fechaNac, "Y/m/d"),
	            "nro_documento"        => ($numeroDoc)!=null ? ($numeroDoc) : NULL,
	            "grado_ingreso"        => ($gradoNivel != null) ? $gradoNivel[0] : null,
	            "nivel_ingreso"        => ($gradoNivel != null) ? $gradoNivel[1] : null,
	            "sede_interes"         => ($sede)!=null ? ($sede) : NULL,
	            "sexo"                 => ($sexo)!=null ? ($sexo) : NULL,
	            "colegio_procedencia"  => ($colegio)!=null ? ($colegio) : NULL,
	            "tipo_documento"       => ($tipoDoc)!=null ? ($tipoDoc) : NULL,
	            "cod_grupo"            => $codGrupo,
	            "flg_estudiante"       => FLG_ESTUDIANTE,
                "id_persona_registro"  => _getSesion('id_rol') != null ? _getSesion('id_rol') : 0
	        );
	        $data = $this->m_contactos->insertarHermano($data);
	        if($data['error'] == EXIT_SUCCESS){
	            $detallePostulante = $this->m_contactos->getDetalleContactoCard($data['idContacto']);
	            $data['postulante'] =  '<div id="child-'.$data['idContacto'].'" class="tab-pane fade">
                                    	<div class="row">
                                    		<div class="col-xs-12 inscrito-title">'.$detallePostulante['nombrecompleto'].'</div>
                                    		<div class="col-xs-7 inscrito-item" >Detalles del Inscrito</div>
                                    		<div class="col-xs-5 inscrito-value">-</div>
                                    		<div class="col-xs-6 inscrito-item" >Sede de inter&eacute;s</div>
                                    		<div class="col-xs-6 inscrito-value">'.$detallePostulante['desc_sede'].'</div>
                                    		<div class="col-xs-4 inscrito-item" >Nivel</div>
                                    		<div class="col-xs-8 inscrito-value">'.$detallePostulante['desc_nivel'].'</div>
                                    		<div class="col-xs-4 inscrito-item" >Grado</div>
                                    		<div class="col-xs-8 inscrito-value">'.$detallePostulante['abvr_grado'].'</div>
                                    	</div>
                                    </div>';
	            $data['li'] = '<li data-id-contacto='._simple_encrypt($data['idContacto']).' data-cod-grupo='._simple_encrypt($codGrupo).'>
                                 <a data-toggle="pill" href="#child-'.$data['idContacto'].'">
                                     <img alt="Estudiante" src="'.RUTA_IMG.'profile/nouser.svg">
                                 </a>
                             </li>';
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function enviarMensajeContacto(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $idContacto = _simpleDecryptInt(_post("contacto"));
	        $asunto     = _post("asunto");
	        $mensaje    = _post("mensaje");
	        $correo     = $this->m_utils->getById("admision.contacto", "correo", "id_contacto", $idContacto);
	        if($idContacto == null || $asunto == null || $mensaje == null){
	            throw new Exception("Ingrese todos los campos");
	        }
	        if($correo != null){
	            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
	                throw new Exception("Ingrese un correo v&aacute;lido");
	            }
	        }else{
	            throw new Exception("No tiene correo");
	        }
	        $data = $this->lib_utils->enviarEmail("rikardo308@gmail.com", $asunto, $mensaje);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function descargarCorreo(){
	    $textoBusqueda = _post('textobusqueda');
	    $estado        = (_post('estadocontacto') != null) ? _simple_decrypt(_post('estadocontacto')) : null;
	    $year          = _simpleDecryptInt(_post('year'));
	    $sede          = _simpleDecryptInt(_post('sede'));
	    $gradoNivel    = (_post('gradoNivel')!=null) ? _simple_decrypt(_post('gradoNivel')) : null;
	    $cod_grupo     = _post('maxgrupo');
	    $correo = null;
	    if(strlen($textoBusqueda)>=3){                                // BUSQUEDA
	        $correo = $this->m_contactos->getCorreoFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA);
	    } else if($year != null){                                     // FILTRO
	        if($gradoNivel != null){
	            $gradoNivel = explode("_", $gradoNivel);
	            $correo = $this->m_contactos->getCorreosFamiliasByEstadoYear($estado, $year,$sede,$gradoNivel[1],$gradoNivel[0]);
	        } else {
	            $correo = $this->m_contactos->getCorreosFamiliasByEstadoYear($estado, $year,$sede,null,null);
	
	        }
	    } else if(strlen($textoBusqueda)==0 && $year == null){
	        $correo = $this->m_contactos->getCorreoFamiliasByEstadoBusqueda($textoBusqueda,$estado,NUMERO_FAMILIAS_CARGA);
	    }
	    
	}
	
	
	function selectByTipoEvento(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
    	    $evento      = (_post('idevento') != null) ? _simpleDecryptInt(_post('idevento')) : null;
    	    $id_contacto = (_post('idcontacto') != null) ? _simpleDecryptInt(_post('idcontacto')) : null;
    	    if($evento == null || $id_contacto == null){
    	        throw new Exception('ANP');
    	    }
    	    
    	    $familia     = $this->m_contactos->getFamiliaInvitar($id_contacto, $evento);
            $tipo_evento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $evento);
    	    $horarios = null;
    	    if($tipo_evento == TIPO_EVENTO_EVALUACION || $tipo_evento == TIPO_EVENTO_EVALUACION_VERANO){
    	        $horarios = $this->m_detalle_evento->getHorasCitaEvento($evento);
    	        $data['comboHorariosInvitar'] = $this->buildComboHorarios($horarios);
    	    }

    	    $data['tabla'] = _createTableInvitarVistaContacto($familia,$horarios,$tipo_evento);

    	    $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}

	function buildComboHorarios($horarios){
	    $opcion = null;
	    foreach ($horarios as $hor){
	        $opcion .= '<option value="'._simple_encrypt($hor->correlativo).'">'.$hor->desc_hora_cita.' ('._fecha_tabla($hor->hora_cita, 'h:i a').')</option>';
	    }
	    return $opcion;
	}
	
	function habilitarButton(){
	    $data['error'] = EXIT_SUCCESS;
	    $idevento   = (_post('idevento')!=null) ? _simple_decrypt(_post('idevento')) : null;
        $arrayHoras        = _post("horas");
        $arrayOpciones     = _post("opciones");
        $arrayContactos    = (array)_post("contactos");
        $tipo_evento = $this->m_utils->getById("admision.evento", "tipo_evento", "id_evento", $idevento);
        $count = 0;
        $count = count($arrayContactos);
        if(count($arrayContactos) <= 0 || $idevento == null ){
            $data['error'] = EXIT_ERROR;
        }
        
        $postulante_asist   = 0;
        $pariente_asist     = 0;
        $noAsistiran        = 0;
        $tal_vez_estudiante = 0;
        $tal_vez_pariente   = 0;
        for ($i = 0; $i < count($arrayContactos); $i++) {
            $est = $this->m_utils->getById("admision.contacto", "flg_estudiante", "id_contacto", _simpleDecryptInt($arrayContactos[$i]));
            if($est == FLG_ESTUDIANTE){
                if($arrayOpciones[$i] == OPCION_TALVEZ){
                    $tal_vez_estudiante++;
                }
                if ($arrayOpciones[$i] == OPCION_ASISTIRA){
                    $postulante_asist++;
                }
            } else {
                if($arrayOpciones[$i] == OPCION_TALVEZ){
                    $tal_vez_pariente++;
                }
                if ($arrayOpciones[$i] == OPCION_ASISTIRA){
                    $pariente_asist++;
                }
            }
            if($arrayOpciones[$i] == OPCION_NO_ASISTIRA){
                $noAsistiran++;
            }
        }
        
        if($tipo_evento == TIPO_EVENTO_EVALUACION){
            if($noAsistiran != count($arrayContactos)){
                if($pariente_asist > 0 && $postulante_asist == 0){
                    $data['error'] = EXIT_ERROR;
                }
                if($postulante_asist > 0 && $pariente_asist == 0){
                    $data['error'] = EXIT_ERROR;
                }
                if($postulante_asist == 0 && $tal_vez_estudiante == 0){
                    $data['error'] = EXIT_ERROR;
                }
                if($pariente_asist == 0 && $tal_vez_pariente == 0){
                    $data['error'] = EXIT_ERROR;
                }
            }
        } else {
            if($noAsistiran != count($arrayContactos)){
                if($pariente_asist == 0 && $tal_vez_pariente == 0){
                    $data['error'] = EXIT_ERROR;
                }
                if($postulante_asist > 0 && $pariente_asist == 0){
                    $data['error'] = EXIT_ERROR;
                }
            }
        }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarContactosPorFechas(){
	    $fechaInicio = _post("fechainicio");
	    $fechaFin    = _post("fechafin");
	    $grupo = $this->m_contactos->getFamiliasByFechas($fechaInicio, $fechaFin);
	    $data = null;
	    if(!empty($grupo)){
	        $data['count'] = count($grupo);
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia'] = $result['cardsFamilia'];
	    }else{
	        $data['cardsFamilia'] = null;
	    }
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarContactosPorCanalComunicacion(){
	    $canalCom    = _simpleDecryptInt(_post("canalCom"));
	    $fechaInicio = (_post("fechainicio")=="")?null:_post("fechainicio");
	    $fechaFin    = (_post("fechafin")=="")?null:_post("fechafin");
	    $grupo = $this->m_contactos->getFamiliasByCanalCom($canalCom, $fechaInicio, $fechaFin);
	    $data  = null;
	    if(!empty($grupo)){
	        $data['count'] = count($grupo);
	        $opc = '<li class="mdl-menu__item" onclick="abrirModalInvitarAlEvento(this)">Invitar a evento</li>
                    <li class="mdl-menu__item" onclick="abrirModalAgregarHermanoFamilia(this)">Agregar hermano</li>
                    <li class="mdl-menu__item" onclick="abrirModalconfirmEliminarFamilia(this)">Eliminar Familia</li>';
	        $result = _createCardFamilias($grupo, $opc, ID_PERMISO_CONTACTOS, 'Editar');
	        $data['cardsFamilia'] = $result['cardsFamilia'];
	    }else{
	        $data['cardsFamilia'] = null;
	    }
	     
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function eventosInvitadosContacto(){
	    $codGrupo = _simple_decrypt(_post("codgrupo"));
	    $eventos = $this->m_contactos->getEventosFuturosGrupoInvitado($codGrupo);
	    $opcion = '';
	    foreach ($eventos as $var) {
	        $opcion	.= '<option value='._simple_encrypt($var->id_evento).'>'.strtoupper($var->desc_evento).' ('.date_format(date_create($var->fecha_realizar), 'd/m/Y').')    <i>'.$var->invitado.'</i></option>';
	    }
	    $data['opcion'] = $opcion;
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function eliminarFamilia(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $codGrupo = _simpleDecryptInt(_post("grupo"));
	        if($codGrupo == null){
	            throw new Exception(ANP);
	        }
	        
	        $invitaciones = $this->m_contactos->countInvitadosEventoByGrupoValidate($codGrupo);
	        if($invitaciones > 0){
	            throw new Exception("La familia ya ha sido invitada a por lo menos 1 evento");
	        }
	        
	        $segu = $this->m_contactos->countSeguimientoByGrupoValidate($codGrupo);
	        if($invitaciones > 0){
	            throw new Exception("La familia ya tiene un registro de seguimiento");
	        }
	        
	        $data = $this->m_contactos->deleteFamiliaByCodGrupo($codGrupo);
	        if($data['error'] == EXIT_SUCCESS){
	            
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function validateDatosCompletos(){
	    $idContacto = _simpleDecryptInt(_post("contacto"));
	    $datos = $this->m_contactos->validateDatosCompletos($idContacto);
	    $data['opc'] = 0;
	    if(strlen($datos['nombres']) == 0 || strlen($datos['ape_paterno']) == 0 || strlen($datos['ape_materno']) == 0 || strlen($datos['nivel_ingreso']) == 0 ||
	       strlen($datos['grado_ingreso']) == 0){
	        $data['opc']        = 1;
	        $data['nombres']    = $datos['nombres'];
	        $data['apePaterno'] = $datos['ape_paterno'];
	        $data['apeMaterno'] = $datos['ape_materno'];
	        $data['gradoNivel'] = ($datos['grado_ingreso']!= null)?_simple_encrypt($datos['grado_ingreso'].'_'.$datos['nivel_ingreso']):null;
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function confirmarDatosPostulantes(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = MSJ_ERROR;
	    try {
	        $idContacto = _simpleDecryptInt(_post("contacto"));
	        $gradoNivel = _simple_decrypt(_post("gradonivel"));
	        $apePaterno = _post("apepaterno");
	        $apeMaterno = _post("apematerno");
	        $nombres    = _post("nombre");
	        if($idContacto == null){
	            throw new Exception(ANP);
	        }
	        if($apePaterno == null || $apeMaterno == null || $nombres ==  null || $gradoNivel == null){
	            throw new Exception("Ingrese todos los campos (*)");
	        }
	        $gradoNivel = explode("_", $gradoNivel);
	        $arrayUpdate = array("nombres"       => __only1whitespace(_ucwords($nombres)),
	                             "ape_paterno"   => __only1whitespace(_ucwords($apePaterno)),
                	             "ape_materno"   => __only1whitespace(_ucwords($apeMaterno)),
                	             "nivel_ingreso" => $gradoNivel[0],
                	             "grado_ingreso" => $gradoNivel[1]);
	        
	        $data = $this->m_contactos->updateContacto($arrayUpdate, $idContacto);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function goToInscripcionVerano(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = MSJ_ERROR;
		try {
			$codGrupo   = _post("codgrupo")   != null ? _simpleDecryptInt(_post("codgrupo")) : null;
			$idContacto = _post("idcontacto") != null ? _simpleDecryptInt(_post("idcontacto")) : null;
			$year       = _post("valoryear");
			
			if($codGrupo == null || $idContacto == null ){
				throw  new Exception(ANP);
			}
			if($year == null ){
				throw  new Exception('Debe seleccionar un a&ntilde;o');
			}
			$data = $this->m_contactos->procesoMatriculaVerano($idContacto, $codGrupo, $year);
			if($data['error'] == EXIT_SUCCESS){
				$data['msj'] = 'La familia se insert&oacute; correctamente';
			}
			$data['url'] = RUTA_SMILEDU.'pagos/c_configuracion';
			$dataUser = array(PAGOS_ROL_SESS   => $this->_idRol);
			$this->session->set_userdata($dataUser);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function verificarPostulante(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = MSJ_ERROR;
		try {
			$codGrupo   = _post("codgrupo")   != null ? _simpleDecryptInt(_post("codgrupo")) : null;
			$idContacto = _post("idcontacto") != null ? _simpleDecryptInt(_post("idcontacto")) : null;
			if($codGrupo == null || $idContacto == null ){
				throw  new Exception(ANP);
			}
			$idContactoMatricula = $this->m_utils->getById("admision.contacto", "id_contacto_matricula", "id_contacto", $idContacto);
			if($idContactoMatricula != null){
				throw  new Exception('Ya puedes inscribir al alumno en el m&oacute;dulo de pagos');
			}
			$data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
		
	}
}