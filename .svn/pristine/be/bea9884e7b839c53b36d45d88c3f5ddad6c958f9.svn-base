<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_grafico extends MX_Controller {

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
        $this->load->model('mf_lineaEstrat/m_lineaEstrat');
        $this->load->model('mf_indicador/m_indicador');
        $this->load->model('mf_grafico/m_grafico');
        $this->load->model('mf_indicador/m_responsable_indicador');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_BSC, ID_PERMISO_GRAFICOS_BSC, BSC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(BSC_ROL_SESS);
    }
   
	public function index(){
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_BSC, BSC_FOLDER);
	    ////Modal Popup Iconos///
	    $rolSistemas = $this->m_utils->getSistemasByRol(ID_SISTEMA_BSC, $this->_idUserSess);
	    $data['tb']  = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    //MENU
	    $data['main'] = true;
	    $data['ruta_logo']        = MENU_LOGO_BSC;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_BSC;
	    $data['nombre_logo']      = NAME_MODULO_BSC;
	    $data['titleHeader']      = 'Gr&aacute;ficos';
	    $data['rutaSalto']        = 'SI';
	    $menu         = $this->load->view('v_menu', $data, true);
	    $data['menu'] = $menu;
	    
	    $data['lineaEstrat'] = _buildLineaEstrategica();
	    
	    $this->load->view('vf_grafico/v_grafico',$data);
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
	
	public function getGraficoByIndicador(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idIndicador = $this->encrypt->decode(_post('idIndicador'));
	        if($idIndicador == null) {
	            throw new Exception(null);
	        }
	        
	        $nombreIndicador = $this->m_utils->getById('bsc.indicador','desc_indicador','_id_indicador',$idIndicador);
	        $codIndicador = $this->m_utils->getById('bsc.indicador','cod_indi','_id_indicador',$idIndicador);
	        $dataUser = array("nombre_indicador_grafico" => $nombreIndicador,
	                           "id_indicador_grafico"    => $idIndicador,
	                           "cod_indicador"           => $codIndicador
	        );
	        $this->session->set_userdata($dataUser);
	         
	        $nombreIndicador = "(".$codIndicador.") ".$nombreIndicador;
	        
	        $tipoEst = $this->m_utils->getById('bsc.indicador','_id_tipo_estructura','_id_indicador', $idIndicador);
	        $result = $this->m_grafico->getDataGraficoByIndicadorPostgres($idIndicador);
	        //$result = $result['retval'];
	        //$data = $this->getGraficoSubNiveles($result, $nombreIndicador);
	        $data = $this->getGraficoSubNivelesPostgres($result, $nombreIndicador);
	        $c = $this->getCombosFiltro($tipoEst);
	        $data['combosFiltro'] = $c['combos'];
	        $data['tipo']         = $c['tipo'];
	        
	        $tipoGraf = $this->m_utils->getById('bsc.indicador','tipo_gauge','_id_indicador',$idIndicador);
	        
	        $data['maxVal'] = null;
	        if($tipoGraf == GAUGE_NORMAL){
	            $data['maxVal'] = 100;
	        }else if($tipoGraf == GAUGE_RATIO){
	            $data['maxVal'] = 1;
	        }
	        $data['ppu'] = 0;
	        if($tipoGraf == GAUGE_PUESTO){
	            $data['ppu'] = 1;
	        }
            
	        $data['error']     = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getIndicadoresByCodigo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $codigo = trim(_post('codigo'));
	        if($codigo == null) {
	            throw new Exception(null);
	        }
	        $data['indi_combo'] = __buildComboIndicadorByCodigo($codigo); 
	        $data['error']     = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGrafico($result, $nombreIndicador){
	    $arraynombreSedes = array();
	    $arrayMetas = array();
	    $arrayActuales = array();
	    $arrayComparativas = array();
	    $h = 0;
	    foreach ($result as $var){
	        array_push($arraynombreSedes, $var['desc_registro']);
	        array_push($arrayMetas, $var['valor_meta']);
	        array_push($arrayActuales, $var['valor_actual_porcentaje']);
	         
	        $arrayC = array();
	        if(isset($var['comparativas'])) {/** dfloresgonz 24.10.2015 Le puse este IF porq al no traer comparativas se caia */
	            foreach ($var['comparativas'] as $res){
	                foreach ($res as $res1){
	                    $arrayJ = array();
	                    for($i=0;$i<$h;$i++){
	                        array_push($arrayJ, null);
	                    }
	                    array_push($arrayJ, $res1['valor_comparativa']);
	                    array_push($arrayJ, $res1['desc_comparativa']);
	                    array_push($arrayC, $arrayJ);
	                } 
	            }
	            array_push($arrayComparativas, $arrayC);
	        }
	        
	        $h++;
	    }
	     
	    $data['years'] = json_encode($arraynombreSedes);
	    $data['metas'] = json_encode($arrayMetas);
	    $data['actuales'] = json_encode($arrayActuales);
	    $data['comparativas'] = json_encode($arrayComparativas);
	    $data['titulo']   = $nombreIndicador;
	    
	    return $data;
	}
	
	function getCombosFiltro($tipoEst){
	    $data = null;
	    $tipo = null;
	    $result = null;
	    $cabecera = '<div class="col-sm-6">
                         <div class="class="form-group">';
	    $pie      = '    </div>
	                 </div>';
	    if($tipoEst == ESTRUCTURA_SNGA){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("N", "getGraficoByNivel", null);
	        $result .= $this->getComboByTipo("G", "getGraficoByGrado", null);
	        $result .= $this->getComboByTipo("AU", "getGraficoByAula", null);
	        $tipo = 1;
	    }else if($tipoEst == ESTRUCTURA_SNG){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("N", "getGraficoByNivel", null);
	        $result .= $this->getComboByTipo("G", "getGraficoByGrado", null);
	        $tipo = 1;
	    }else if($tipoEst == ESTRUCTURA_SN){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("N", "getGraficoByNivel", null);
	        $tipo = 1;
	    }else if($tipoEst == ESTRUCTURA_S){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $tipo = 2;
	    }else if($tipoEst == ESTRUCTURA_DN){
	        $result .= $this->getComboByTipo("D", "getGraficoByDisciplinaMaster", __buildComboDisciplina());
	        $result .= $this->getComboByTipo("N", "getGraficoByNivelDN", null);
	        $tipo = 3;
	    }else if($tipoEst == ESTRUCTURA_SNA){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("N", "getGraficoByNivelSNA", null);
	        $result .= $this->getComboByTipo("AR", "getGraficoByAreaSNA", null);
	        $tipo = 4;
	    }else if($tipoEst == ESTRUCTURA_SA){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("AR", "getGraficoByAreaSA", null);
	        $tipo = 5;
	    }else if($tipoEst == ESTRUCTURA_SG){
	        $result .= $this->getComboByTipo("S", "getGraficoBySedeMaster", __buildComboSedes());
	        $result .= $this->getComboByTipo("G", "getGraficoByGradoSG", null);
	        $tipo = 6;
	    }
	    
	    $data['combos'] = $result;
	    $data['tipo'] = $tipo;
	    
	    return $data;
	}
	
	function getGraficoByNivelAcademico() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
    	    $tipo            = _post('tipo');
    	    $nombreIndicador = _getSesion('nombre_indicador_grafico');
    	    $idIndicador     = _getSesion('id_indicador_grafico');
    	    $codIndicador    = _getSesion('cod_indicador');
    	    
    	    $nombreIndicador = "(".$codIndicador.") ".$nombreIndicador;

    	    $result = null;
    	    if($tipo == 0){//POR SEDE(SNGA-SNG-SN-S)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico')[0]);
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $result = $this->m_grafico->getDataGraficoBySedePostgre($idIndicador, $idNivelAcademico);
    	        $data['comboNivel'] = __buildComboNivelesBySede($idNivelAcademico);
    	    }else if($tipo == 1){//POR NIVEL(SNGA-SNG-SN)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede = $this->encrypt->decode(_post('idSede')[0]);
    	        $result = $this->m_grafico->getDataGraficoByNivelPostgre($idIndicador, $idSede, $idNivelAcademico);
    	        $data['comboGrado'] = __buildComboGradosByNivel($idNivelAcademico, $idSede);
    	    }else if($tipo == 2){//POR GRADO(SNGA-SNG)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede  = $this->encrypt->decode(_post('idSede')[0]);
    	        $idNivel = $this->encrypt->decode(_post('idNivel'));
    	        $result = $this->m_grafico->getDataGraficoByGradoPostgre($idIndicador, $idSede, $idNivel, $idNivelAcademico);
    	        $data['comboAula'] = __buildComboAulas($idNivelAcademico, $idSede);
    	    }else if($tipo == 3){//POR AULA(SNGA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede  = $this->encrypt->decode(_post('idSede')[0]);
    	        $idNivel = $this->encrypt->decode(_post('idNivel'));
    	        $idGrado  = $this->encrypt->decode(_post('idGrado'));
    	        $result = $this->m_grafico->getDataGraficoByAulaPostgre($idIndicador, $idNivelAcademico);
    	    }
    	    
    	    else if($tipo == 4){//POR DISCIPLINA(DN)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico')[0]);
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $result  = $this->m_grafico->getDataGraficoByDisciplinaPostgre($idIndicador, $idNivelAcademico);
    	        $data['comboNivel'] = __buildComboNiveles();
    	    }else if($tipo == 5){//POR NIVEL(DN)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idDisciplina = $this->encrypt->decode(_post('idDisciplina'));
    	        $result  = $this->m_grafico->getDataGraficoByDisciplinaNivelPostgre($idIndicador, $idDisciplina, $idNivelAcademico);
    	    }
    	    
    	    else if($tipo == 6){//POR SEDE(SNA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico')[0]);
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $result = $this->m_grafico->getDataGraficoBySedePostgre($idIndicador, $idNivelAcademico);
    	        $data['comboNivel'] = __buildComboNivelesBySede($idNivelAcademico);
    	    }else if($tipo == 7){//POR NIVEL(SNA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede = $this->encrypt->decode(_post('idSede')[0]);
    	        $result = $this->m_grafico->getDataGraficoByNivelPostgre($idIndicador, $idSede, $idNivelAcademico);
    	        $data['comboArea'] = __buildComboAreasAcad();
    	    }else if($tipo == 8){//POR AREA(SNA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede  = $this->encrypt->decode(_post('idSede')[0]);
    	        $idNivel = $this->encrypt->decode(_post('idNivel'));
    	        $result = $this->m_grafico->getDataGraficoBySedeNivelAreaPostgre($idIndicador, $idSede, $idNivel, $idNivelAcademico);
    	    }
    	    
    	    else if($tipo == 9){//POR SEDE(SA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico')[0]);
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $result = $this->m_grafico->getDataGraficoBySedePostgre($idIndicador, $idNivelAcademico);
    	        $data['comboArea'] = __buildComboAreasAcad();
    	    }else if($tipo == 10){//POR AREA(SA)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede  = $this->encrypt->decode(_post('idSede')[0]);
    	        $result = $this->m_grafico->getDataGraficoBySedeAreaPostgre($idIndicador, $idSede, $idNivelAcademico);
    	    }
    	    
    	    else if($tipo == 11){//POR SEDE(SG)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico')[0]);
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $result = $this->m_grafico->getDataGraficoBySedePostgre($idIndicador, $idNivelAcademico);
    	        $data['comboGrado'] = __buildComboGradosBySedeAll($idNivelAcademico);
    	    }else if($tipo == 12){//POR GRADO(SG)
    	        $idNivelAcademico = $this->encrypt->decode(_post('idNivelAcademico'));
    	        if($idNivelAcademico == null) {
    	            throw new Exception(ANP);
    	        }
    	        $idSede = $this->encrypt->decode(_post('idSede')[0]);
    	        $result = $this->m_grafico->getDataGraficoBySedeGradoPostgre($idIndicador, $idSede, $idNivelAcademico);
    	    }
    	    
    	    $data += $this->getGraficoSubNivelesPostgres($result, $nombreIndicador);
    	    
    	    $data['error']     = EXIT_SUCCESS;
    	    
    	    $tipoGraf = $this->m_utils->getById('bsc.indicador','tipo_gauge','_id_indicador',$idIndicador);
    	    $data['maxVal'] = null;
    	    if($tipoGraf == GAUGE_NORMAL){
    	        $data['maxVal'] = 100;
    	    }else if($tipoGraf == GAUGE_RATIO){
    	        $data['maxVal'] = 1;
    	    }
    	    $data['ppu'] = 0;
    	    if($tipoGraf == GAUGE_PUESTO){
    	        $data['ppu'] = 1;
    	    }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoComparando() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $tipo = _post('tipo');
	        $year = _post('year');
	        $idIndicador = _getSesion('id_indicador_grafico');
	        $nombreIndicador = _getSesion('nombre_indicador_grafico').'('.$year.')';
	        
	        if($tipo == 1 || $tipo == 2 || $tipo == 4 || $tipo == 5 || $tipo == 6){//POR SEDES
	            $sedes  = _post('sedes');
	            $result = $this->m_grafico->getDataGraficoBySedeMultiPostgres($idIndicador, $sedes, $year);
	        }else if($tipo == 3){ //POR DISCIPLINAS
	            $disciplinas  = _post('disciplinas');
	            $result = $this->m_grafico->getDataGraficoByDisciplinaMultiPostgres($idIndicador, $disciplinas, $year);
	        }
	        
	        $data += $this->getGraficoSubNivelesPostgresMulti($result, $nombreIndicador);
	        
	        $tipoGraf = $this->m_utils->getById('bsc.indicador','tipo_gauge','_id_indicador',$idIndicador);
	        $data['maxVal'] = null;
	        if($tipoGraf == GAUGE_NORMAL){
	            $data['maxVal'] = 100;
	        }else if($tipoGraf == GAUGE_RATIO){
	            $data['maxVal'] = 1;
	        }
	        $data['ppu'] = 0;
	        if($tipoGraf == GAUGE_PUESTO){
	            $data['ppu'] = 1;
	        }
	        
	        $data['error']     = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGraficoSubNiveles($result, $nombreIndicador){
	    $arrayYears = array();
	    $arrayMetas = array();
	    $arrayActuales = array();
	    $arrayComparativas = array();
	    $h = 0;
	    foreach ($result as $var){
	        array_push($arrayYears, $var['_id']);
	        array_push($arrayMetas, $var['valor_meta']);
	        array_push($arrayActuales, $var['valor_actual_porcentaje']);
	        
	        $arrayC = array();
	        foreach ((array)$var['comparativas'] as $res){
	            $arrayJ = array();
	            for($i=0;$i<$h;$i++){
	                array_push($arrayJ, null);
	            }
	            array_push($arrayJ, $res['valor_comparativa']);
	            array_push($arrayJ, $res['desc_comparativa']);
	            array_push($arrayC, $arrayJ);
	        }
	        
	        $h++;
	        array_push($arrayComparativas, $arrayC);
	    }
	     
	    $data['years'] = json_encode($arrayYears);
	    $data['metas'] = json_encode($arrayMetas);
	    $data['actuales'] = json_encode($arrayActuales);
	    $data['comparativas'] = json_encode($arrayComparativas);
	    $data['titulo']   = $nombreIndicador;
	    return $data;
	}
	
	function getGraficoSubNivelesPostgres($result, $nombreIndicador){
	    $arrayYears = array();
	    $arrayMetas = array();
	    $arrayActuales = array();
	    $arrayComparativas = array();
	    $h = 0;
	    foreach ($result as $var){
	        array_push($arrayYears, $var->year);
	        array_push($arrayMetas, (float)$var->valor_meta);
	        array_push($arrayActuales, (float)$var->valor_actual_porcentaje);
	        if(isset($var->comparativas)){
	            $comparativas = json_decode(utf8_encode($var->comparativas));
	            $arrayC = array();
	            foreach($comparativas as $comp){
	                $arrayJ = array();
	                for($i=0;$i<$h;$i++){
	                    array_push($arrayJ, null);
	                }
	                array_push($arrayJ, (float)$comp->valor_comparativa);
	                array_push($arrayJ, utf8_encode($comp->desc_comparativa));
	                array_push($arrayC, $arrayJ);
	            }
	            $h++;
	            array_push($arrayComparativas, $arrayC);
	        }
	    }
	    $data['years'] = json_encode($arrayYears);
	    $data['metas'] = json_encode($arrayMetas);
	    $data['actuales'] = json_encode($arrayActuales);
	    $data['comparativas'] = json_encode($arrayComparativas);
	    $data['titulo']   = $nombreIndicador;
	    return $data;
	}
	
	function getGraficoSubNivelesPostgresMulti($result, $nombreIndicador){
	    $arrayYears = array();
	    $arrayMetas = array();
	    $arrayActuales = array();
	    $arrayComparativas = array();
	    $h = 0;
	    foreach ($result as $var){
	        array_push($arrayYears, utf8_encode($var->descripcion));
	        array_push($arrayMetas, (float)$var->valor_meta);
	        array_push($arrayActuales, (float)$var->valor_actual_porcentaje);
	        if(isset($var->comparativas)){
	            $comparativas = json_decode(utf8_encode($var->comparativas));
	            $arrayC = array();
	            foreach($comparativas as $comp){
	                $arrayJ = array();
	                for($i=0;$i<$h;$i++){
	                    array_push($arrayJ, null);
	                }
	                array_push($arrayJ, (float)$comp->valor_comparativa);
	                array_push($arrayJ, utf8_encode($comp->desc_comparativa));
	                array_push($arrayC, $arrayJ);
	            }
	            $h++;
	            array_push($arrayComparativas, $arrayC);
	        }
	    }
	    $data['years'] = json_encode($arrayYears);
	    $data['metas'] = json_encode($arrayMetas);
	    $data['actuales'] = json_encode($arrayActuales);
	    $data['comparativas'] = json_encode($arrayComparativas);
	    $data['titulo']   = $nombreIndicador;
	    return $data;
	}
	
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['smiledu']);
        $cookie_name2 = "smiledu";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
	
	function getComboByTipo($tipo, $onClick, $data){
	    $result = null;
	    $result .= '<div class="col-sm-6 mdl-input-group mdl-input-group__only">
                         <div class="mdl-select">';
	    if($tipo == 'S'){
	        $result .= '<select id="multiSedes" name="multiSedes" multiple="multiple" data-live-search="true" data-noneSelectedText="Seleccione Sede" data-title="Seleccione Sede" class="form-control pickerButn" onchange="'.$onClick.'(this)">
	                       '.$data.' 
	                    </select>';
	        
	    }else if($tipo == 'N'){
	        $result .= '<select id="selectNivel" name="selectNivel" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
                            <option>Seleccione Nivel</option>
	                        '.$data.'
                        </select>';
	    }else if($tipo == 'G'){
	        $result .= '<select id="selectGrado" name="selectGrado" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
                            <option>Seleccione Grado</option>
	                        '.$data.'
                        </select>';
	    }else if($tipo == 'AU'){
	        $result .= '<select id="selectAula" name="selectAula" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
	                        <option>Seleccione Aula</option>
	                        '.$data.' 
	                    </select>';
	    }else if($tipo == 'D'){
	        $result .= '<select id="multiDisciplinas" name="multiDisciplinas" multiple="multiple" multiple="multiple" data-live-search="true" data-noneSelectedText="Seleccione Disciplina" data-title="Seleccione Disciplina" class="form-control pickerButn" onchange="'.$onClick.'(this)">
	                        '.$data.'
                        </select>';
	    }else if($tipo == 'AR'){
	        $result .= '<select id="selectArea" name="selectArea" data-live-search="true" class="form-control pickerButn" onchange="'.$onClick.'()">
                            <option>Seleccione Area</option>
	                        '.$data.'
                        </select>';
	    }
	    
	    $result     .= '    </div>
	                 </div>';
	    
	    return $result;
	}
	
	function cambioRol(){
	    $idRolEnc = _post('id_rol');
	    $idRol = _simpleDecryptInt($idRolEnc);
	    $nombreRol = $this->m_utils->getById("schoowl_rol", "desc_rol", "nid_rol", $idRol);
	
	    $dataUser = array("id_rol"     => $idRol,
	        "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	
	    $idRol     = _getSesion('nombre_rol');
	
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function setIdSistemaInSession(){
	    $idSistema = $this->encrypt->decode(_post('id_sis'));
	    $idRol     = $this->encrypt->decode(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function enviarFeedBack(){
	    $nombre = _getSesion('nombre_usuario');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
	}
	
}