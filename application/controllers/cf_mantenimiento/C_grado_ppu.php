<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_grado_ppu extends CI_Controller {

    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_grado_ppu');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_GRADOS_PPU);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    
    function index() {       
	    $data['titleHeader']      = 'Grado PPU';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
        $data['tabPPU'] = $this->buildTablaGradoPPUHTML(null,null,null);
        $data['AulaByAlumnoTable'] = $this->buildTableAulaAlumnoAllHTML(null,null);
        $data['optSede'] = __buildComboSedes();
        $data['optPPu']  = __buildComboPPU();
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
        
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        //MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
        $data['font_size'] = _getSesion('font_size');
        
        $this->load->view('vf_mantenimiento/v_grado_ppu', $data);
    }
    
    function buildTablaGradoPPUHTML($idNivel,$idSede,$idPPu){
        $listaGradosPPU = ($idNivel != null && $idSede != null && $idPPu != null) ? $this->m_grado_ppu->getAllGrados_PPU($idNivel,$idSede,$idPPu) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_grado_ppu">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => '#'      , 'class' => 'col-sm-2');
        $head_2 = array('data' => 'Grado'  , 'class' => 'col-sm-7');
        $head_3 = array('data' => 'Puesto' , 'class' => 'col-sm-3');
       // $head_4 = array('data' => 'Opci�n');
        $val = 0;
        $this->table->set_heading($head_1, $head_2, $head_3/*,$head_4*/);
        foreach($listaGradosPPU as $row){
            $btn = null;
            $idCryptGrado = _encodeCI($row->nid_grado);
            $val++;
            $row_cell_1  = array('data' => $val);
            $row_cell_2  = array('data' => $row->desc_grado);             
            $puesto = $row->puesto;
            $row_cell_3  = array('data' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input type="text" onchange="onChangePuesto(this);" class="mdl-textfield__input" value="'.$puesto.'" name="puesto" id="'.$val.'" 
                                                       attr-cambio="false" attr-bd="'.$puesto.'"  attr-idgrado="'.$idCryptGrado.'" readonly>
                                                <label class="mdl-textfield__label" for="'.$val.'"></label>
                                            </div>
            ');
            /*if(!empty($puesto)){
                $btn = '<button class="btn ink-reaction btn-flat btn-primary borde btn-icon-toggle" style="float:right;width: 100px" onclick="abrirTablaAula(this)"  id="aula'.$val.'" attr-idgrado="'.$idCryptGrado.'">Asignar</button>';
            }          
            $row_cell_4 = array('data' => $btn);*/
            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3 /*,$row_cell_4*/);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function comboGradosAula() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            if($idGrado == null) {
                throw new Exception(ANP);
            }
            $data['optAula'] = __buildComboAulasGrados($idGrado);
            $data['error']   = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));      
    }
     
    function getAllAulaByAlumno(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula = _decodeCI(_post('idAula'));
            $idPPu  = _decodeCI(_post('idPPu'));
            if($idAula == null && $idPPu == null) {
                throw new Exception(ANP);
            }
            $data['AulaByAlumnoTable'] = $this->buildTableAulaAlumnoAllHTML($idAula,$idPPu);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }     
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function buildTableAulaAlumnoAllHTML($idAula, $idPPu){      
        $listaAulaAlumno = ($idAula != null) ? $this->m_grado_ppu->getAulaAlumnoAll($idAula, $idPPu) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_aula_alumno">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);    
        $head_1 = array('data' => '#');
        $head_2 = array('data' => 'Alumno');
        $head_3 = array('data' => 'Puesto');   
        $this->table->set_heading($head_1,$head_2,$head_3);
        $val = 0;
       
        foreach($listaAulaAlumno as $row){
            $val++;
            $idpersonaCryp = _encodeCI($row->__id_persona);
            $row_cell_1 = array('data' => $val);
            $row_cell_2 = array('data' => $row->nombrecompleto);
            $puesto_alum = $row->puesto_alumno;         
            $row_cell_3  = array('data' => '<input type="text" onchange="onChangePuestoAlumno(this);" class="form-control" 
                                            value="'.$puesto_alum.'" name="puesto" id="'.$val.'" attr-cambio="false"
                                            attr-bd="'.$puesto_alum.'" attr-persona="'.$idpersonaCryp.'" attr-alumn="'.$puesto_alum.'">');           
            $this->table->add_row($row_cell_1,$row_cell_2,$row_cell_3);
        }     
        $tabla = $this->table->generate();       
        return $tabla;
    }
    
    function comboGradosNivel() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            if($idSede == null) {
                $data['error']    = EXIT_WARM;
                $data['optNivel'] = null;
                $data['puestoSede'] = null;
                $data['tabPPU']   = $this->buildTablaGradoPPUHTML(null, null, null);
                throw new Exception(null);
            }
            $data['optNivel']   = __buildComboNivelesSecundariaBySede($idSede);
            $data['tabPPU']     = $this->buildTablaGradoPPUHTML(null, null, null);
            $data['error']      = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function getPPUfromGrado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idNivel = _decodeCI(_post('idNivel'));
            $idSede  = _decodeCI(_post('idSede'));         
            $idPPu   = _decodeCI(_post('idPPu'));
            if($idSede == null || $idNivel == null || $idPPu == null) {
                $data['error']      = EXIT_WARNING;
                $data['puestoSede'] = null;
                $data['tabPPU']     = $this->buildTablaGradoPPUHTML(null,null,null);
                throw new Exception(null);
            }
            $data['tabPPU']     = $this->buildTablaGradoPPUHTML($idNivel,$idSede,$idPPu);
            $data['puestoSede'] = $this->m_grado_ppu->getPuestoSedeAreaGrado($idSede, 0, $idPPu);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function grabarGradoPuestos(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('puesto'), TRUE);
            $idNivel    = _decodeCI(_post('idNivel'));
            $idPPu      = _decodeCI(_post('idPPu'));
            $idSede     = _decodeCI(_post('idSede'));
            $puestoSede = _post('puestoSede');
            if($idNivel == null || $idPPu == null || $idSede == null) {
                throw new Exception(ANP);
            }
            if($puestoSede == null || trim($puestoSede) == '') {
                throw new Exception('Debe ingresar el puesto de la Sede');
            }
            if(!ctype_digit((string) $puestoSede)) {
                throw new Exception('El puesto de la sede debe ser num�rico');
            }
            $arrayGeneral = array();
            foreach($myPostData['puesto'] as $key =>$puesto) {
                $idGrado   = _decodeCI($puesto['idGrado']);           
                $puestoobt = isset($puesto['puesto']) ? $puesto['puesto'] : null;         
                $ppu       = $this->m_grado_ppu->getCantPuestoSedeAreaGrado($idSede, $idGrado, $idPPu);    
                $puestoobt = ($puestoobt == 0 || trim($puestoobt) == "" || $puestoobt < 0) ? null: $puestoobt;
                
                if($ppu == 0 && $puestoobt != null){
                    $arrayDatos = array("__id_grado"     => $idGrado,
                                        "__id_sede"      => $idSede,
                                        "__id_ppu"       => $idPPu,
                                        "puesto"         => $puestoobt,
                                        "year_academico" => _getYear(),
                                        "ACCION"         => 'I');//INSERT
                }else if($ppu == 1 && $puestoobt != null){
                    $arrayDatos = array("__id_grado"     => $idGrado,
                                        "__id_sede"      => $idSede,
                                        "__id_ppu"       => $idPPu,
                                        "puesto"         => $puestoobt,
                                        "ACCION"         => 'U');//UPDATE
                }else if ($ppu == 1 && $puestoobt == null){
                    $arrayDatos = array("__id_grado"     => $idGrado,
                                        "__id_sede"      => $idSede,
                                        "__id_ppu"       => $idPPu,
                                        "ACCION"         => 'D');//DELETE
                }
                array_push($arrayGeneral, $arrayDatos);
             }
             $data = $this->m_grado_ppu->InsertUpdateGradoPPU($arrayGeneral, $idSede, $idPPu, $puestoSede);
             if($data['error'] == EXIT_SUCCESS){
                 $data['tabPPU'] = $this->buildTablaGradoPPUHTML($idNivel,$idSede,$idPPu);
             }      
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));            
    }
    
    function grabarAlumnosPuestos(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $msj   = null;
        $error = 0;       
        try {
            $myPostData = json_decode(_post('puesto_alumno'), TRUE);
            $idAula = _decodeCI(_post('idAula'));
            $idPPu= _decodeCI(_post('idPPu'));
           
            if($idAula == null && $idPPu == null){
                throw new Exception(ANP);
            }
            $arrayGeneral = array();
            foreach($myPostData['puesto'] as $key =>$puesto){
                $idPersona  = _decodeCI($puesto['idPersona']);
                $puestoobt = $puesto['puesto_alumno'];           
                $puestoobt=($puestoobt==null || trim($puestoobt)=="" || $puestoobt < 0)?null:$puestoobt;
              if($idPPu==1){                
                      $arrayDatos = array("__id_persona"        => $idPersona,
                                          "__id_aula"           => $idAula,                    
                                          "ppu_puesto_numerico" => $puestoobt,
                                          "ACCION"              => 'U');    
                  array_push($arrayGeneral, $arrayDatos);                 
              }
              if($idPPu==2){                
                      $arrayDatos = array("__id_persona"       => $idPersona,
                                          "__id_aula"          => $idAula,
                                          "ppu_puesto_ciencia" => $puestoobt,
                                          "ACCION"             => 'U');
                      array_push($arrayGeneral, $arrayDatos);
              }               
              if($idPPu==3){                 
                      $arrayDatos = array("__id_persona"       => $idPersona,
                                          "__id_aula"          => $idAula,
                                          "ppu_puesto_lectura" => $puestoobt,
                                          "ACCION"             => 'U');               
                      array_push($arrayGeneral, $arrayDatos);
              }             
            }
                $data = $this->m_grado_ppu->updatedeleteaulaalum($arrayGeneral);
                $data['error'] = $error;
                $data['msj']   = $msj;
                if($data['error'] == EXIT_SUCCESS){
                   $data['AulaByAlumnoTable'] = $this->buildTableAulaAlumnoAllHTML($idAula,$idPPu);               
                }   
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));   
    }   
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
    
    function grabarPpuSedeGrado(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idNivel = _decodeCI(_post('idNivel'));
            $idSede  = _decodeCI(_post('idSede'));
            $idPPu   = _decodeCI(_post('idPPu'));
            $puesto  = _post('puestoSede');
            if($idSede == null || $idNivel == null || $idPPu == null) {
                $data['error']      = EXIT_ERROR;
                $data['puestoSede'] = null;
                $data['msj']        = 'Los datos ingresados son incorrectos';
                $data['tabPPU']     = $this->buildTablaGradoPPUHTML(null,null,null);
                throw new Exception(null);
            } else if($puesto == null){
                $data['msj']        = 'Debe ingresar un puesto';
                $data['error']      = EXIT_ERROR;
                throw new Exception(null);
            } else if($puesto < 0 ){
                $data['error'] = EXIT_ERROR;
                $data['msj']   = 'El puesto debe ser mayor a 0';
                $data['tabPPU']     = $this->buildTablaGradoPPUHTML(null,null,null);
                throw new Exception(null);
            } else{
                $data['tabPPU']     = $this->buildTablaGradoPPUHTML($idNivel,$idSede,$idPPu);
                $data['puestoSede'] = $this->m_grado_ppu->updateInsertPuestoPpuSede($idSede, $idPPu,$puesto);
                $data['error']      = EXIT_SUCCESS;
                $data['msj']        = "Se Edit�";
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}