<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_rubrica extends CI_Controller {

    private $puedeEditarGlobal = false;
    private $_idRubrica        = null;
    private $_idUserSess       = null;
    private $_idRol            = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->load->model('m_rubrica');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_utils');
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_RUBRICA, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
        
        $this->_idRubrica = _getSesion('nid_ficha');
        if($this->_idRubrica != null) {
            $this->puedeEditarGlobal = $this->m_rubrica->puedeEditar($this->_idRubrica);
        }
    }

    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SPED, SPED_FOLDER);
        $data['tbConsRub'] = $this->buildTabla_Fichas();

        ////Modal Popup Iconos///
        $data['titleHeader'] = 'R&uacute;brica';
	    $data['ruta_logo'] = MENU_LOGO_SPED;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SPED;
	    $data['nombre_logo'] = NAME_MODULO_SPED;
        $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_SPED, $this->_idUserSess);
        $data['apps']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);

        //MENU Y CABECERA
        $data['return'] = 'onclick="finalizar();"';
        $data['historyBack'] = 1;
        $menu           = $this->load->view('v_menu', $data, true);
        $data['menu']   = $menu;
        if($this->_idRubrica == null) {
            $this->load->view('v_cons_rubrica', $data);
        } else {
            //mandar variable a la vista para poder esconder HTML editar
            $data['flg_editar'] = $this->puedeEditarGlobal;
            $data['optEvmvalo'] = __buildComboCantidadNivelesRubrica();//$this->lib_utils->buildComboValores();
            $data['tbFactores'] = $this->buildTabla_Rubricas($this->_idRubrica);
            $data['tbSubFactores'] = null;
            $valor = $this->m_utils->getById('sped.rubrica', 'cant_valores', 'nid_ficha', $this->_idRubrica);
            $valor = ($valor != 0 && $valor != null) ? _simple_encrypt($valor) : null;
            $data['idFichaVal'] = $valor;
            $data['pesoTotal'] = $this->m_rubrica->getSumaPesosByRubrica($this->_idRubrica);
            $data['pesoTotalCSS'] = ($data['pesoTotal'] == 100) ? 'mdl-color-text--green-500' : 'mdl-color-text--red-500';
            $this->load->view('v_rubrica', $data);
        }
    }
      
    function cambioRol() {
        $idRolEnc = $this->input->post('id_rol');
        $idRol = _simple_decrypt($idRolEnc);     
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = $this->session->userdata('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function buildTabla_Rubricas($idRubrica = null) {
        $listaCriterios = ($idRubrica == 0 || $idRubrica == null ) ? array() : $this->m_rubrica->getCriteriosByRubrica($idRubrica);
        $valor = ($idRubrica == 0 ) ? array() : $this->m_rubrica->getbuscarIdIndicadorRci($idRubrica);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               id="tbFactores">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => utf8_decode('Factor'));
        $head_2 = array('data' => 'Peso'    , 'class' => 'text-center');
        $head_4 = array('data' => 'Acciones', 'class' => 'text-center');
        
        $quitar = null;
        if($this->puedeEditarGlobal) {
            $quitar = '<li class="mdl-menu__item" onclick="borrarFactorModal($(this));"><i class="mdi mdi-delete"></i> Quitar</li>';
            $head_3 = array('data' => 'Orden', 'class' => 'text-center');
            $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        } else {
            $this->table->set_heading($head_0, $head_1, $head_2, $head_4);
        }
        $val = 0;
        foreach($listaCriterios as $row) {           
            $val++;
            $botonGeneral = '<button id="menu-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                 <i class="mdi mdi-more_vert"></i>    
                             </button>
                             <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="menu-'.$val.'">
                                <li class="mdl-menu__item" onclick="editarPesoFactorModal($(this));"><i class="mdi mdi-edit"></i> Editar</li>
                                '.$quitar.'
                                <li class="mdl-menu__item" onclick="getSubFactoresByFactor($(this));"><i class="mdi mdi-visibility"></i> Ver</li>
                             </ul>';
            $row_0 = array('data' => $val, 'data-id_factor' => (_simple_encrypt($row->nid_criterio)), 'class' => 'btnID');
            $row_1 = array('data' => $row->desc_criterio );
            $peso = (($row->peso_porcentaje == null) ? 0 : $row->peso_porcentaje);
            $row_2 = array('data' => $peso.' %' , 'data-peso' => $peso, 'class' => 'classPeso');
            $row_4 = array('data' => $botonGeneral, 'class' => 'text-center');
            if($this->puedeEditarGlobal) {
                $hiddenUp = ($val == 1) ? ';visibility : hidden' : null;
                $hiddenDown = ($val == count($listaCriterios)) ? ';visibility : hidden' : null;
                $botonArriba = '<a href="javascript:void(0)" class="up" attr-orden="'.$val.'" attr-direccion="1" style="margin-left:5px'.$hiddenUp.'"><i class="mdi mdi-arrow_drop_up"></i></a>';
                $botonAbajo  = '<a href="javascript:void(0)" class="down" attr-orden="'.$val.'" attr-direccion="0" style="margin-left:5px'.$hiddenDown.'"><i class="mdi mdi-arrow_drop_down"></i></a>';
                $row_3 = array('data' => '<div class="col-xs-6">'.$botonArriba.'</div>'.'<div class="col-xs-6">'.$botonAbajo.'</div>');
                $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            } else {
                $this->table->add_row($row_0, $row_1, $row_2, $row_4);
            }
            $valI = 0;
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function traerSubFactoresByFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            $data['tbSubFactores'] = $this->getSubFactoresByFactorAux($this->_idRubrica, $idFactor);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function traerFactoresParaAgregar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $data['tbFactoresAsignar'] = $this->traerFactoresParaAgregarAux($this->_idRubrica);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function traerFactoresParaAgregarAux($idRubrica) {
        $lstFactores = $this->m_rubrica->getFactoresParaAsignar($idRubrica);
        
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               id="tbFactoresAsignar" data-search="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'data-field' => 'nro');
        $head_1 = array('data' => utf8_decode('Factor'), 'data-field' => 'desc_factor');
        $head_2 = array('data' => 'Asignar', 'class' => 'text-center', 'data-field' => 'checkbox');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $valI = 0;
        foreach ($lstFactores as $fact) {
            $valI++;
            $row_0 = array('data' => $valI);
            $row_1 = array('data' => $fact['desc_criterio']);
            $row_2 = array('data' => '<label for="chk_Fac_'.$valI.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
						   	       	      <input type="checkbox" class="mdl-checkbox__input" id="chk_Fac_'.$valI.'" onclick="manejarCheckFactAsignar($(this));" data-id_factor_asig="'._simple_encrypt($fact['nid_criterio']).'">
								       	  <span class="mdl-checkbox__label"></span>
								 	  </label>',
                           'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        return $this->table->generate();
    }
    
    function getSubFactoresByFactorAux($idRubrica, $idFactor) {
        $lstIndicadores = $this->m_rubrica->getIndicadores($idRubrica, $idFactor);
        
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               id="tbSubFactores" data-search="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'SubFactor');
        $head_3 = array('data' => 'Leyendas'  , 'class' => 'text-center');
        if($this->puedeEditarGlobal) {
            $head_2 = array('data' => 'Borrar', 'class' => 'text-center');
            $head_4 = array('data' => 'Orden', 'class' => 'text-center');
            $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        } else {
            $this->table->set_heading($head_0, $head_1, $head_3);
        }
        $valI = 0;
        foreach ($lstIndicadores as $indi) {
            $valI++;
            $row_0 = array('data' => $valI, 'data-idCripk' => (_simple_encrypt($indi->id_criterio)),'data-idIndpk' => (_encodeCI($indi->id_indicador)), 'class' => 'btnIDCrit');
            $row_1 = array('data' => $indi->desc_indicador);
            $row_3 = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="mostrarValores($(this));"><i class="mdi mdi-list"></i></button>');
            if($this->puedeEditarGlobal) {
                $row_2 = array('data' => '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="borrarSubFactorModal($(this));"><i class="mdi mdi-delete"></i></button>');
                $hiddenUp = ($valI == 1) ? ';visibility : hidden' : null;
                $hiddenDown = ($valI == count($lstIndicadores)) ? ';visibility : hidden' : null;
                $botonArriba = '<a href="javascript:void(0)" class="upSF" attr-orden="'.$valI.'" attr-direccion="1" style="margin-left:5px'.$hiddenUp.'"><i class="mdi mdi-arrow_drop_up"></i></a>';
                $botonAbajo  = '<a href="javascript:void(0)" class="downSF" attr-orden="'.$valI.'" attr-direccion="0" style="margin-left:5px'.$hiddenDown.'"><i class="mdi mdi-arrow_drop_down"></i></a>';
                $row_4 = array('data' => '<div class="col-xs-6">'.$botonArriba.'</div>'.'<div class="col-xs-6">'.$botonAbajo.'</div>');
                $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
            } else {
                $this->table->add_row($row_0, $row_1, $row_3);
            }
        }
        return $this->table->generate();
    }
    
    function getTablaHTMLIndicadoresPorAsinar($idFactor) {
        $lstIndicadores = $this->m_rubrica->getIndicadoresParaAsignar($this->_idRubrica, $idFactor);
        
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
    			                               id="tbSFAsignar" data-search="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#', 'data-field' => 'nro');
        $head_1 = array('data' => utf8_decode('SubFactor'), 'data-field' => 'desc_subfactor');
        $head_2 = array('data' => 'Asignar', 'class' => 'text-center', 'data-field' => 'checkbox');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $valI = 0;
        foreach ($lstIndicadores as $indi) {
            $valI++;
            $row_0 = array('data' => $valI);
            $row_1 = array('data' => $indi['desc_indicador']);
            $row_2 = array('data' => '<label for="chk_'.$valI.'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">
								 	      <input type="checkbox" class="mdl-checkbox__input" id="chk_'.$valI.'" onclick="manejarChecksSubFactAsignar($(this));" data-id_subfactor_asig="'._simple_encrypt($indi['nid_indicador']).'">
								       	  <span class="mdl-checkbox__label"></span>
								 	  </label>',
                           'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        return $this->table->generate();
    }
    
    function traerSubFactoresParaAgregar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            if($this->m_rubrica->getPesoFromFactor($this->_idRubrica, $idFactor) == 0) {
                throw new Exception('Asigne el peso del factor');
            }
            $data['tbSFAsignar'] = $this->getTablaHTMLIndicadoresPorAsinar($idFactor);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function asignarSubFactoresAFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            $arrySubFactores = _post('arrayIndisAsig');
            if(!is_array($arrySubFactores) ) {
                throw new Exception(ANP);
            }
            if(count($arrySubFactores) == 0) {
                throw new Exception('Seleccione subfactores');
            }
            ////////////////////////////////////////////////////////////////////////////////
            $arrayGeneral = array();
            $nextOrden  = $this->m_rubrica->getNextOrdenSubFactor($this->_idRubrica, $idFactor);
            
            foreach ($arrySubFactores as $indsArry) {
                $idIndicador = _simpleDecryptInt($indsArry['id_subfactor_asig']);
                if($idIndicador == null) {
                    throw new Exception(ANP);
                }
                $descripInd = $this->m_rubrica->buscarDescripIdIndicador($idIndicador);
                $descrip    = $this->m_rubrica->getBuscarDescripIdCriterio($idFactor);
                $arrayDatos = array(
                    "id_rubrica"     => $this->_idRubrica,
                    "id_criterio"    => $idFactor,
                    "id_indicador"   => $idIndicador,
                    "desc_criterio"  => $descrip,
                    "desc_indicador" => $descripInd,
                    "orden"          => $nextOrden
                );
                array_push($arrayGeneral, $arrayDatos);
                $nextOrden++;
            }
            $data = $this->m_rubrica->insertarFactores_x_SubFactores($arrayGeneral, $this->_idRubrica, $idFactor);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbSubFactores'] = $this->getSubFactoresByFactorAux($this->_idRubrica, $idFactor);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function asignarFactoresARubrica() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $arrayFactAsig = _post('arrayFactAsig');
            if(!is_array($arrayFactAsig) ) {
                throw new Exception(ANP);
            }
            if(count($arrayFactAsig) == 0) {
                throw new Exception('Seleccione factores');
            }
            ////////////////////////////////////////////////////////////////////////////////
            $arrayGeneral = array();
            $nextOrden  = $this->m_rubrica->getNextOrdenFactor($this->_idRubrica);
            
            foreach ($arrayFactAsig as $factArry) {
                $idFactor = _simpleDecryptInt($factArry['id_factor_asig']);
                if($idFactor == null) {
                    throw new Exception(ANP);
                }
                $arrayDatos = array(
                    "id_rubrica"   => $this->_idRubrica,
                    "id_criterio"  => $idFactor,
                    "orden"        => $nextOrden
                );
                array_push($arrayGeneral, $arrayDatos);
                $nextOrden++;
            }
            $data = $this->m_rubrica->insertar_factores_a_rubrica($arrayGeneral);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbFactores'] = $this->buildTabla_Rubricas($this->_idRubrica);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }

    function crearCriterio() {
        $data['error'] = EXIT_ERROR;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $descrip = utf8_decode(trim(_post('descrip'))) ;
            if($descrip == null || strlen($descrip) == 0 ) {
                throw new Exception('Ingrese el factor');
            }
            $data = $this->m_rubrica->insertCriterio($descrip);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));        
    }
    
    function crearIndicador() {
        $data['error'] = EXIT_ERROR;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $descrip = utf8_decode(trim(_post('descrip'))) ;
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            if($descrip == null || strlen($descrip) == 0 ) {
                throw new Exception('Escriba el indicador');
            }
            $data = $this->m_rubrica->insertIndicador($descrip);
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTabla_Criterios($ficha) {
        $listaTable =  $this->m_rubrica->getAllCriteriosByFicha($ficha);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" 
                                               id="tbcriterio">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);   
        $head_0 = array('data' => '#','class' => 'text-right');
        $head_1 = array('data' => 'Criterios','class' => 'text-left');
        $head_2 = array('data' => 'Asignar','class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 1;

        foreach($listaTable as $row) {
            $check = ($row->id_rubrica != null) ? 'checked' : null;
            $row_col0        = array('data' => $val, 'class' => 'text-right');
            $row_col1        = array('data' => $row->desc_criterio, 'class' => 'text-left');
            $row_col2        = array('data' => '<div class="checkbox checkbox-inline checkbox-styled">
    										        <label>
        											    <input type="checkbox" '.$check.' id="criterio'.$val.'" attr-cambio="false" attr-idcriterio="'._simple_encrypt($row->nid_criterio).'" 
                                                               attr-bd="'.$check.'"  onchange="cambioCheckCriterio(this);">
        											    <span></span>
        										    </label>
        									    </div>','class' => 'text-center');
            $val++;
            $this->table->add_row($row_col0, $row_col1, $row_col2);
        }   
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    /*
    function grabarCriterios() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode($this->input->post('criterios'), TRUE);
            $idRubrica = $this->session->userdata('nid_ficha');
            $arrayGeneral = array(); 
            $valor = $this->lib_utils->simple_decrypt($this->input->post('val'),CLAVE_ENCRYPT);
            foreach($myPostData['criterio'] as $key => $criterio) {
                $idCriterio   = $this->encrypt->decode($criterio['nid_criterio']);
                //flg valor 1 cuando tiene check, sino tiene check es null
                $flg  = $criterio['valor'];
              //$ordenMax = $this->m_rubrica->getOrdenMaxByEvdficr($idRubrica);
                $descrip = $this->m_rubrica->getBuscarDescripIdCriterio($idCriterio);
                if($idCriterio == null) {
                    throw new Exception(ANP);
                }             
                $data = $this->m_rubrica->insertDeleteRubCriInd($idRubrica,$idCriterio,0,$descrip,$flg,0,0,$valor);
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['tbRub'] = $this->buildTabla_Rubricas($idRubrica);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }*/
    
    ///Fin del Modal Popup Lapiz///
    
    ///Modal Popup del detalle//
    /*function mostrarIndicadores() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $nidCriterio = _decodeCI($this->input->post('idCriterio'));
            if($nidCriterio == null) {
                throw new Exception(ANP);
            }
            $data['tablaIndicador'] = $this->buildTabla_Indicacores(_getSesion('nid_ficha'), $nidCriterio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }*/
   
    function buildTabla_Indicacores($nidFicha,$nidCriterio) {
        $lstIndicadores =  $this->m_rubrica->getAllIndicadoresByCriterio($nidFicha);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#customBarIndi" data-page-size="6"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-search="true"
                                               id="tbindicador">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#','class' => 'text-right');
        $head_1 = array('data' => 'Indicadores','class' => 'text-left');
        $head_2 = array('data' => 'Asignar?','class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 1;
        foreach($lstIndicadores as $row){
            $idCryptIndicador= _encodeCI($row->nid_indicador);
            $idCryptFicha    = _encodeCI($nidFicha);
            $idCryptCriterio = _encodeCI($nidCriterio);
            $check = ($row->flg != null) ? 'checked' : null;
            $row_col0        = array('data' => $val,'class' => 'text-right');
            $row_col1        = array('data' => $row->desc_indicador,'class' => 'text-left');
            $row_col2        = array('data' => '<div class="checkbox checkbox-inline checkbox-styled">
    										        <label>
        											    <input type="checkbox" '.$check.' id="indicador'.$val.'" attr-cambio="false" attr-idindicador="'.$idCryptIndicador.'"
                                                               attr-idcriterio="'.$idCryptCriterio.'" attr-idficha="'.$idCryptFicha.'" attr-bd="'.$check.'"  onchange="cambioCheckIndicador(this);">
        											    <span></span>
        										    </label>
        									    </div>','class' => 'text-center');
            $val++;
            $this->table->add_row($row_col0, $row_col1, $row_col2);
        }
        $tabla = $this->table->generate();
        $tabla .= '<div id="customBarIndi">
                    <p style="font-size:15px">Buscar Indicadores</p>
                </div>';        
        return $tabla;
    }
  
    function grabarIndicacores() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $valor = _simple_decrypt(_post('val'));
            $cont = 1;
            $myPostData = json_decode($this->input->post('indicadores'), TRUE);
            $arrayGeneral = array();
            foreach($myPostData['indicador'] as $key => $indicador) {
                $idIndicador = $this->encrypt->decode($indicador['id_indicador']);
                $idCriterio  = $this->encrypt->decode($indicador['id_criterio']);
                $idFicha     = $this->encrypt->decode($indicador['id_rubrica']);
                $valorMax    = $this->m_rubrica->getValorMaxByNidFicha($idFicha);
                //flg valor 1 cuando tiene check, sino tiene check es null
                $flg  = $indicador['valor'];
                $condicion  = $this->m_rubrica->buscarIdIndicador($idFicha,$idCriterio);
                $cantVals   = $this->m_rubrica->getCountValorByCriterio($idFicha,$idCriterio);
                $descripInd = $this->m_rubrica->buscarDescripIdIndicador($idIndicador);
                $descrip    = $this->m_rubrica->getBuscarDescripIdCriterio($idCriterio);
                $countIndi  = $this->m_rubrica->getCountNidFichaByRci($idFicha,$idCriterio);
                if($condicion != 0 && $cont == 0  ||$condicion != 0 && $cont!=0){
                   $condicion = 1;
                   $countIndi =  $countIndi+ $cont++;
                }
                if($condicion == 0){
                   $condicion = 0;
                   $countIndi = $cont++;
                }
                if($idIndicador == null || $idCriterio == null || $idFicha == null ) {
                    throw new Exception(ANP);
                }
                if($flg == 1) {
                    $arrayDatos = array("id_rubrica"         => $idFicha,
                                        "id_criterio"        => $idCriterio,
                                        "desc_criterio"      => $descrip,
                                        "id_indicador"       => $idIndicador,
                                        "desc_indicador"     => $descripInd,
                                        "cant_vals"          => $countIndi,    
                                        "cant_indis"         => $countIndi,
                                        "valor_max_criterio" => $valorMax,
                                        "ACCION"             => $condicion);
                     array_push($arrayGeneral, $arrayDatos);
                 }
            }
            $data = $this->m_rubrica->updateDeleteIndicadores($arrayGeneral,$flg,$valor);
            if($data['error'] == EXIT_SUCCESS) {
               $data['tbRub'] = $this->buildTabla_Rubricas($idFicha);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }  

    function borrarFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_rubrica->borrarFactorModelo($this->_idRubrica, $idFactor);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbFactores'] = $this->buildTabla_Rubricas($this->_idRubrica);
                $data['pesoTotalCSS'] = ($data['suma_pesos'] == 100) ? 'mdl-color-text--green-500' : 'mdl-color-text--red-500';
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function borrarSubFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $idFactor    = _simpleDecryptInt(_post('idFactor'));
            $idSubFactor = _decodeCI(_post('idSubFactor'));
            if($idFactor == null || $idSubFactor == null) {
                throw new Exception(ANP);
            }
            $data = $this->m_rubrica->getBorrarIndicador($idFactor, $this->_idRubrica, $idSubFactor);
            if($data['error'] == EXIT_SUCCESS){
                $data['tbSubFactores'] = $this->getSubFactoresByFactorAux($this->_idRubrica, $idFactor);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function editarPesoFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idFactor = _simpleDecryptInt(_post('idFactor'));
            $peso     = trim(_post('peso'));
            if($idFactor == null) {
                throw new Exception(ANP);
            }
            if(strlen($peso) == 0) {
                throw new Exception('Ingrese el peso');
            }
            if (!is_float($peso) && !is_numeric($peso) ) {
                throw new Exception('El peso debe ser numérico');
            }
            if($peso > 100) {
                throw new Exception('El peso no puede ser mayor a 100');
            }
            if($peso <= 0) {
                throw new Exception('El peso no puede ser cero');
            }
            $data = $this->m_rubrica->cambiarPesoFactor($this->_idRubrica, $idFactor, $peso);
            if($data['error'] == EXIT_SUCCESS) {
                $data['pesoTotalCSS'] = ($data['suma_pesos'] == 100) ? 'mdl-color-text--green-500' : 'mdl-color-text--red-500';
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    
    function changeOrdenFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden          = _post('orden');
            $idFactor       = _simpleDecryptInt(_post('idFactor'));
            $direccion      = _post('direccion');
            if($direccion !== ORDEN_SUBIR && $direccion !== ORDEN_BAJAR){
                throw new Exception(ANP);
            }
            if($idFactor == null){
                throw new Exception(ANP);
            }
            //VERIFICAR QUE FACTOR EXISTA EN RUBRICA
            $existe = $this->m_rubrica->verificarSiExiste_FactorRubrica($this->_idRubrica, $idFactor);
            if($existe != 1) {
                throw new Exception(ANP);
            }
            //
            $idFactorChange = $this->m_rubrica->getFactorACambiarOrden($this->_idRubrica, $orden, $direccion);
            if($idFactorChange == null){
                throw new Exception(ANP);
            }
            //ACTUALIZA EL SIGUIENTE O ANTERIOR FACTOR
            $arrayUpdate1 = array(
                'id_rubrica'  => $this->_idRubrica,
                'id_criterio' => $idFactorChange,
                'orden'       => $orden
            );
            //CAMBIA EL NUEVO ORDEN PARA EL FACTOR SELECCIONADO
            $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array(
                'id_rubrica'  => $this->_idRubrica,
                'id_criterio' => $idFactor,
                'orden'       => $orden
            );
            $data = $this->m_rubrica->updateFactor_Orden($arrayUpdate1, $arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbFactores'] = $this->buildTabla_Rubricas($this->_idRubrica);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeOrdenSubFactor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden        = _post('orden');
            $idFactor     = _simpleDecryptInt(_post('idFactor'));
            $idSubFactor  = _decodeCI(_post('idSubFactor'));
            $direccion    = _post('direccion');
            if($direccion !== ORDEN_SUBIR && $direccion !== ORDEN_BAJAR){
                throw new Exception(ANP);
            }
            if($idSubFactor == null){
                throw new Exception(ANP);
            }
            if($idFactor == null){
                throw new Exception(ANP);
            }
            //VALIDAR QUE EL SUBFACTOR PERTENEZCA AL FACTOR Y RUBRICA
            $existe = $this->m_rubrica->verificarSiExiste_SubFactorFactor($this->_idRubrica, $idFactor, $idSubFactor);
            if($existe != 1) {
                throw new Exception(ANP);
            }
            //
            $idSubFactorChange = $this->m_rubrica->getSubFactorACambiarOrden($this->_idRubrica, $idFactor, $orden, $direccion);
            if($idSubFactorChange == null){
                throw new Exception(ANP);
            }
            //ACTUALIZA EL SIGUIENTE O ANTERIOR SUBFACTOR
            $arrayUpdate1 = array(
                'id_rubrica'   => $this->_idRubrica,
                'id_criterio'  => $idFactor,
                'id_indicador' => $idSubFactorChange,
                'orden'        => $orden
            );
            //CAMBIA EL NUEVO ORDEN PARA EL FACTOR SELECCIONADO
            $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array(
                'id_rubrica'   => $this->_idRubrica,
                'id_criterio'  => $idFactor,
                'id_indicador' => $idSubFactor,
                'orden'        => $orden
            );
            $data = $this->m_rubrica->updateSubFactor_Orden($arrayUpdate1, $arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbSubFactores'] = $this->getSubFactoresByFactorAux($this->_idRubrica, $idFactor);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getValores_CTRL() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;  
        try {
            if(!$this->puedeEditarGlobal) {
                throw new Exception('No se puede editar esta rï¿½brica');
            }
            $cantValores = _simpleDecryptInt(_post('val'));
            if($cantValores == null) {
                throw new Exception(ANP);
            }
            $opc = null;
            $cant = null;
            $arrayGeneral = array();
            if($cantValores == null) {
                $data['error'] = EXIT_WARM;
                $data['optEvmvalo'] = null;
                throw new Exception(ANP);
            }
            $data = $this->m_rubrica->cambiarCantidadValoresRubrica($this->_idRubrica, $cantValores);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //MOSTRAR POPUP DE ASIGNAR//
    //INICIO//
    function mostrarValores() {
        try{
            $nidCriterio = _simpleDecryptInt(_post('idCrit'));
            $nidInd      = _decodeCI(_post('idInd'));
            if($nidCriterio == null || $nidInd == null) {
                throw new Exception(ANP);
            }
            $data['tablaValor'] = $this->buildTabla_Valores($nidCriterio, $this->_idRubrica, $nidInd);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTabla_Valores($nidCriterio, $idRubrica, $nidInd) {
        $listaTable =  $this->m_rubrica->getLeyendaByRubValLey($nidCriterio, $idRubrica, $nidInd);      
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" id="tbvalor">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Valor'      ,'class' => 'text-left');
        $head_1 = array('data' => ''           ,'class' => 'text-center');
        $head_2 = array('data' => 'Descripci&oacute;n','class' => 'text-left');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 1;
        foreach($listaTable as $row) {
            $row_col0   = array('data' => round($row->valor,3), 'class' => 'text-left' , 'class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center');
            $radio = '  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-b-15 '.$row->color_radio_button.'" for="radio-'.$val.'">
                            <input type="radio" id="radio-'.$val.'" class="mdl-radio__button" name="radioVals.'.$val.'" value="'.$row->valor.'" checked>
                            <span class="mdl-radio__label"></span>
                        </label>';
            $row_col1   = array('data' => $radio, 'class' => 'text-center', 'class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-right p-t-0');
            $row_col2   = array('data' => ' <div class="mdl-textfield mdl-js-textfield p-0">
                                                <input class="mdl-textfield__input p-0" id="leyenda'.$val.'" attr-cambio="false"  data-descrip="1"
                                                    data-pkidCrit="'._simple_encrypt($nidCriterio).'" 
                                                    data-pkidRub="'._encodeCI($idRubrica).'" 
                                                    data-pkidInd="'._encodeCI($nidInd).'" 
                                                    data-pkidVal="'._encodeCI($row->valor).'" value="'.$row->leyenda.'" maxlength="300">
                                                <label class="mdl-textfield__label" for="leyenda'.$val.'"></label>
                                            </div>', 'class' => 'text-left', 'class' => 'col-xs-10 col-sm-10 col-md-10 col-lg-10 text-center');
            $val++;
            $this->table->add_row($row_col0, $row_col1, $row_col2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function grabarLeyendas() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('leyendas'), TRUE);
            $arrayGeneral = array();
            foreach($myPostData['leyenda'] as $key => $leyenda) {
                $idCrit  = _simpleDecryptInt($leyenda['id_criterio']);
                $idRub   = _decodeCI($leyenda['id_rubrica']);
                $idInd   = _decodeCI($leyenda['id_indicador']);
                $idVal   = _decodeCI($leyenda['valor']);
                $descrip = utf8_decode(trim($leyenda['leyenda']));
                if(strlen($descrip) > 300) {
                    throw new Exception('La descripci&oacute;n de la leyenda debe tener como m&aacute;ximo 300 caracteres');
                }
                $arrayDatos = array("id_criterio"  => $idCrit,
                                    "id_rubrica"   => $idRub,
                                    "id_indicador" => $idInd,
                                    "valor"        => $idVal,
                                    "leyenda"      => $descrip);
                array_push($arrayGeneral, $arrayDatos);
            }
            $data = $this->m_rubrica->insertUpdateLeyenda($arrayGeneral);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
    //MOSTAR FIN POPUP ASIGNAR POPUP//
    
   //////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////CONTROLADOR DE CONS_RUBRICA///////////////////////////////////
    
   function grabarFicha() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $version = trim(_post('nombreRubrica'));
            if($version == null || strlen($version) == 0) {
               throw new Exception(ANP);
            }
            if(strlen($version) > 50) {
                throw new Exception('La descripci&oacute;n debe contener como m&aacute;ximo 50 caracteres.');
            }
            $version = utf8_decode($version);
            $data = $this->m_rubrica->insertFicha($version);
            if($data['error'] == EXIT_SUCCESS) {
                $data['tbRubricas'] = $this->buildTabla_Fichas();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
   } 
   
   function buildTabla_Fichas() {
        $listaFichas = $this->m_rubrica->getAllFichas();    
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    			                               data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-show-columns="true"
    			                               id="tbFichas" data-search="true" style="text-align:center;">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#','class' => 'text-center');
        $head_1 = array('data' => utf8_decode('Descripci&oacuten'),'class' => 'text-left');
        $head_2 = array('data' => utf8_decode('Fecha'),'class' => 'text-center','data-sortable' => 'true');
        $head_3 = array('data' => 'Estado','class' => 'text-center','data-sortable' => 'true');
        $head_4 = array('data' => 'Acciones','class' => 'text-center');
    
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val = 0;
        foreach($listaFichas as $row) {
            $idDetalleFic = _encodeCI($row->id_rubrica);
            $val++;
            $row_0 = array('data' => $val                    , 'class' => ' text-center');
            $row_1 = array('data' => $row->desc_version      , 'class' => ' text-left');
            $row_2 = array('data' => utf8_decode($row->fecha), 'class' => ' text-center');
            $estado = ($row->estado == RUBRICA_INACTIVA) ? 'label-danger' : ( ($row->estado == RUBRICA_PENDIENTE || $row->estado == RUBRICA_REACTIVAR) ? 'label-warning' : 'label-success') ;
            $iconEstado  = ($row->estado == RUBRICA_INACTIVA) ? 'close' : ( ($row->estado == RUBRICA_PENDIENTE) ? 'pause' : ($row->estado == RUBRICA_REACTIVAR ? 'autorenew' : 'check' ) );
            $row_3 = array('data' => '<span class="label '.$estado.'"><i class="mdi mdi-'.$iconEstado.'"></i>'.utf8_decode($row->estado).'</span>' , 'class' =>' text-center');
            
            $_liOpts = null;
            if($row->estado == RUBRICA_INACTIVA) {
                $_liOpts .= '<li class="mdl-menu__item" onclick="activarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-check"></i> Activar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="editarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-edit"></i> Editar</li>';
            } else if($row->estado == RUBRICA_PENDIENTE) {
                $_liOpts .= '<li class="mdl-menu__item" onclick="editarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-edit"></i> Editar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="activarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-check"></i> Activar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="inactivarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-close"></i> Desactivar</li>';
            } else if($row->estado == RUBRICA_ACTIVA) {
                $_liOpts .= '<li class="mdl-menu__item" onclick="inactivarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-close"></i> Desactivar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="editarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-edit"></i> Editar</li>';
            } else if($row->estado == RUBRICA_REACTIVAR) {
                $_liOpts .= '<li class="mdl-menu__item" onclick="reactivarRubrica(\''.$idDetalleFic.'\');"><i class="mdi mdi-autorenew"></i> Reactivar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="inactivarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-close"></i> Desactivar</li>';
                $_liOpts .= '<li class="mdl-menu__item" onclick="editarFicha(\''.$idDetalleFic.'\');"><i class="mdi mdi-edit"></i> Editar</li>';
            }
            $idMenuButton = "ficha-".$val;
            $opciones = '<div class="wrapper">
                             <button id="'.$idMenuButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                 <i class="mdi mdi-more_vert"></i>
                             </button>
                             <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect text-left" for="'.$idMenuButton.'">
                            '.$_liOpts.'
                             </ul>
	                     </div>';
            $row_4 = array('data' => $opciones, 'class' => ' text-center');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function finalizar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $data['error'] = EXIT_SUCCESS;
            $this->session->set_userdata(array('nid_ficha' => null));
            $this->_idRubrica = null;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));;        
    }
    
    function editarFicha() {
        try{
            $idFicha = _decodeCI(_post('idFicha'));
            if($idFicha == null ) {
                $data['error'] = EXIT_ERROR;
                throw new Exception(null);
            }
            $this->session->set_userdata(array('nid_ficha' => $idFicha));         
            Redirect('c_rubrica', 'refresh');
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function activarFicha() {
        try{
            $idFicha = _decodeCI(_post('idFicha'));
            if($idFicha == null ) {
                $data['error'] = EXIT_ERROR;
                throw new Exception(null);
            }
            $data = $this->m_rubrica->cambiarEstado($idFicha, ACTIVO_);
            $data['tbFichas'] = $this->buildTabla_Fichas();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function reactivarRubrica() {
        $data['error'] = EXIT_ERROR;
        try{
            $idRubrica = _decodeCI(_post('idRubrica'));
            if($idRubrica == null ) {
                throw new Exception(ANP);
            }
            $data = $this->m_rubrica->cambiarEstado($idRubrica, ACTIVO_, RUBRICA_ESTADO_POR_REACTIVAR);
            $data['tbFichas'] = $this->buildTabla_Fichas();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function inactivarFicha() {
        try{
            $idFicha = _decodeCI(_post('idFicha'));
            if($idFicha == null) {
                $data['error'] = EXIT_ERROR;
                throw new Exception(null);
            }
            $data = $this->m_rubrica->cambiarEstado($idFicha, INACTIVO_);
            $data['tbFichas'] = $this->buildTabla_Fichas();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
}