<?php class C_desempeno_evaluadores extends MX_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('m_desempeno_evaluadores');
        $this->load->model('m_utils');
        $this->load->helper('cookie');
        
        _validate_uso_controladorModulos(ID_SISTEMA_SPED, ID_PERMISO_GRAFICOS, SPED_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
    }
    public function index(){
    }
    
    function cambioRol() {
        $idRolEnc = _post('id_rol');
        $idRol = _simpleDecryptInt($idRolEnc);
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol);
        $dataUser = array("id_rol"     => $idRol,
                          "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $idRol     = _getSesion('nombre_rol');
        $result['url'] = base_url()."c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function logOut() {
        $this->session->sess_destroy();
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        redirect(RUTA_SMILEDU, 'refresh');
    }
    
    function getEvaluacionesPorEstado() {
        $data = null;
        $fecInicio    = _post('fecInicio');
        $fecFin       = _post('fecFin');
        $selectEval   = _post('selectedEv');
        $selectRoles  = _post('selectedRol');
        $fecInicio = implode("-", array_reverse(explode("/", $fecInicio)));
        if($fecInicio == null) {
            $fecInicio = date('Y-m-d');
        }
        $fecFin    = implode("-", array_reverse(explode("/", $fecFin)));
        if($fecFin == null) {
            $fecFin = date('Y-m-d');
        }
        $listaEstados = $this->m_desempeno_evaluadores->getEvaluacionesPorRol($fecInicio, $fecFin);
        $evaluadorSede = 'Evaluador de Sede';
        $evaluadorArea = 'Evaluador de &Aacute;rea';
        $arrayRoles  = array();
        $arrayEvSede = array();
        $arrayEvArea = array();
        $arrayEstado = array();
        $arrayGeneral = array();
        $arrayColores = array();
        $arrayDataEstado = array();
        foreach($listaEstados['retval'] AS $row) {
            $arrayEvSede = array();
            $arrayEvArea = array();
            $cambioRoles = null;
            $arrayDataEstado = array();
            $estado = $row['_id'];
            array_push($arrayEstado, $estado);
            array_push($arrayColores, json_decode(COLORES_EVALUACIONES)->$estado);
            foreach($row['lista'] AS $lista) {
                if($lista['roles'] == ID_ROL_EVALUADOR){
                    array_push($arrayEvArea, $lista['contador']);
                }
                if($lista['roles'] == ID_ROL_SUBDIRECTOR){
                    array_push($arrayEvSede, $lista['contador']);
                }
            }
            if(count($arrayEvArea) == 0){
                array_push($arrayEvArea, null);
            } else if(count($arrayEvSede) == 0){
                array_push($arrayEvSede, null);
            }
            array_push($arrayGeneral, array($arrayEvArea,$arrayEvSede));
        }
        array_push($arrayRoles, utf8_encode($evaluadorArea));
        array_push($arrayRoles, utf8_encode($evaluadorSede));
        $arrayRoles = (array_values(array_unique($arrayRoles))); 
        $data['general'] = json_encode($arrayGeneral);
        $data['roles']   = json_encode(($arrayRoles));
        $data['estados'] = json_encode($arrayEstado);
        $data['colores'] = json_encode($arrayColores);
        $data += $this->dataEvaluadores($fecInicio, $fecFin, $selectEval, $selectRoles);
        $data += ($this->getDataEvaluadoresLineaTiempo($fecInicio, $fecFin, $selectEval, $selectRoles));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function dataEvaluadores($fecInicio, $fecFin, $selectEval, $selectRoles) {
        $selectEvalDecry = array();
        //Array estado evaluaciones
        $arrayBajo   = array();
        $arrayNormal = array();
        $arrayOptimo = array();
        $arrayFaltan = array();
        //Array to JS
        $arrayGeneral = array();
        $arrayNombres = array();
        $arrayEstados = array('Bajo','Normal',utf8_encode('�ptimo'),'Faltantes');
        $arrayColores = array('red','green','blue','black');
        $result = null;
        try {
            if($fecInicio == null) {
                throw new Exception('No se especificó la fecha de inicio');
            }
            if($fecFin == null) {
                throw new Exception('No se especificó la fecha de fin');
            }
            $listaData = $this->m_desempeno_evaluadores->getAllEvaluacionesEjecutadas($fecInicio, $fecFin, $selectEval, $selectRoles);
            $diasLaborables = $this->m_desempeno_evaluadores->getAllDiasLaborablesByRango($fecInicio, $fecFin);
            foreach($listaData['retval'] AS $row){
                $arrayBajo   = array();
                $arrayNormal = array();
                $arrayOptimo = array();
                $arrayFaltan = array();
                $contador = 0;
                foreach($row['lista'] AS $data) {
                    $limitEvaluaciones = $this->m_desempeno_evaluadores->getEvaluacionesMaxAndMin($data['rol']);
                    $min = $limitEvaluaciones['valor_num_1']*$diasLaborables;
                    $max = $limitEvaluaciones['valor_num_2']*$diasLaborables;
                    if($data['count'] < $min){
                        array_push($arrayBajo, $data['count']);
                    } else if($data['count'] > $min && $data['count'] < $max){
                        array_push($arrayNormal, $data['count']);
                    } else if($data['count'] > $max){
                        array_push($arrayOptimo, $data['count']);
                    }
                    if(($max - $data['count']) > 0){
                        array_push($arrayFaltan, ($max-$data['count']));
                    }
                    array_push($arrayNombres, utf8_encode($data['nombre_evaluador']));
                    if(count($arrayBajo) == $contador){
                        array_push($arrayBajo, null);
                    }
                    if(count($arrayNormal) == $contador){
                        array_push($arrayNormal, null);
                    }
                    if(count($arrayOptimo) == $contador){
                        array_push($arrayOptimo, null);
                    }
                    if(count($arrayFaltan) == $contador){
                        array_push($arrayFaltan, null);
                    }
                    $contador++;
                }
                array_push($arrayGeneral, array($arrayBajo,$arrayNormal,$arrayOptimo,$arrayFaltan));
            }
        } catch(Exception $e){
        
        }
        $result['generalEv'] = json_encode(array_reverse((count($arrayGeneral)>0) ? $arrayGeneral[0] : $arrayGeneral));
        $result['nombresEv'] = json_encode($arrayNombres);
        $result['estadosEv'] = json_encode(array_reverse($arrayEstados));
        $result['coloresEv'] = json_encode(array_reverse($arrayColores));
        return $result;
    }
    
    function buildComboEvaluadores(){
        $listaEvaluadores = $this->m_utils->getAllEvaluadoresSedeArea();
        $result = null;
        $option = null;
        foreach($listaEvaluadores as $row) {
            $option.='<option value="'._encodeCI($row->nid_persona).'">'.$row->nombrecompleto.'</option>';
        }
        $result['optEvaluadores'] = $option;
        $option = '<option value="'._encodeCI(ID_ROL_EVALUADOR).'">Evaluador de &Aacute;rea</option>
                   <option value="'._encodeCI(ID_ROL_SUBDIRECTOR).'">Evaluador de Sede</option>';
        $result['optRoles'] = $option;
        return $result;
    }
    
    function getDataEvaluadoresLineaTiempo($fecInicio,$fecFin,$selectEval,$selectRoles){
        $result = $this->m_desempeno_evaluadores->getEvaluacionesLineaTiempo($fecInicio,$fecFin,$selectEval,$selectRoles);
        $arrayFec   = array();
        $arrayColores = array();
        $arrayGener = array();
        $arrayEstado = array();
        $arrayPendi = array();
        $arrayJusti = array();
        $arrayEjecu = array();
        $arrayInjus = array();
        $arrayNoEje = array();
        $arrayPJust = array();
        $sum = null;
        $count = null;
        foreach($result['retval'] AS $row){
            array_push($arrayFec, $row['_id']);
            foreach($row['eval'] AS $data){
                if($data['estado'] == PENDIENTE){
                    array_push($arrayPendi , $data['count']);
                } else if($data['estado'] == EJECUTADO){
                    array_push($arrayEjecu , $data['count']);
                } else if($data['estado'] == 'JUSTIFICADO'){
                    array_push($arrayJusti , $data['count']);
                } else if($data['estado'] == 'INJUSTIFICADO'){
                    array_push($arrayInjus , $data['count']);
                } else if($data['estado'] == 'POR JUSTIFICAR'){
                    array_push($arrayPJust , $data['count']);
                } else if($data['estado'] == NO_EJECUTADO){
                    array_push($arrayNoEje , $data['count']);
                }
                
                $sum = $sum + $data['count'];
                array_push($arrayEstado, $data['estado']);
                array_push($arrayColores, json_decode(COLORES_EVALUACIONES)->$data['estado']);
            }
            if(count($arrayPendi) == $count)array_push($arrayPendi, null);
            if(count($arrayEjecu) == $count)array_push($arrayEjecu, null);
            if(count($arrayInjus) == $count)array_push($arrayInjus, null);
            if(count($arrayJusti) == $count)array_push($arrayJusti, null);
            if(count($arrayPJust) == $count)array_push($arrayPJust, null);
            if(count($arrayNoEje) == $count)array_push($arrayNoEje, null);
            $count++;
        }
        $arrayAux = array('PENDIENTE' => $arrayPendi, 'EJECUTADO' => $arrayEjecu, 'JUSTIFICADO' => $arrayJusti,
                          'INJUSTIFICADO' => $arrayInjus, 'POR JUSTIFICAR' => $arrayPJust, 'NO EJECUTADO' => $arrayNoEje);
        $arrayEstado = array_values(array_reverse(array_unique($arrayEstado)));
        foreach ($arrayEstado as $est){
            array_push($arrayGener, $arrayAux[$est]);
        }
        $data['coloresLin'] = json_encode(array_values(array_reverse(array_unique($arrayColores))));
        $data['estadoLin']  = json_encode(($arrayEstado));
        $data['generalLin'] = json_encode(($arrayGener));
        $data['fechasLin']  = json_encode($arrayFec);
        return $data;
    }
    
    function getDetalleEvaluadores() {
        $cat        = _post('cat');
        $serie      = _post('serie');
        $fecInicioF = _post('fecInicio');
        $fecFinF    = _post('fecFin');
        $fecInicio  = implode("-", array_reverse(explode("/", _post('fecInicio'))));
        $fecFin     = implode("-", array_reverse(explode("/", _post('fecFin'))));
        $valoresMongo = $this->m_desempeno_evaluadores->getDetalleEvaluadores($cat, $fecInicio, $fecFin);
        $dataPersona = $this->m_desempeno_evaluadores->getDataPersona($valoresMongo[0]['_id'], $valoresMongo[0]['rol']);
        $diasLaborables = $this->m_desempeno_evaluadores->getAllDiasLaborablesByRango($fecInicio, $fecFin);
        $evaluacionesNormal = $diasLaborables * $dataPersona['valor_num_1'];
        $evaluacionesOptima = $diasLaborables * $dataPersona['valor_num_2'];
        $idCrypt = _encodeCI($valoresMongo[0]['_id']);
        $rol = ($valoresMongo[0]['rol'] == ID_ROL_EVALUADOR) ? 'Evaluador de &aacute;rea' : 'Evaluador de Sede';
        $datos = '<div class="row-fluid">   
                    <div class="col-sm-12 text-center" >
                        <div id ="img">
                            <img src="'.$dataPersona['foto_persona'].'" class="img-evaluador" alt="Foto evaluador" >
                        </div>
                    </div>                            
                    <div class="col-sm-12 col-md-4">
                         <div class="form-group">
                             <label>Rol</label>
                             <p>'.$rol.'</p>
                         </div>
                     </div>
                    <div class="col-sm-12 col-md-8">
                         <div class="form-group">
                             <label>Dias laborables desde '.$fecInicioF.' a '.$fecFinF.'</label>
                             <p>'.$diasLaborables.'</p>
                         </div>
                     </div>
                    <div class="col-xs-6 col-md-4">
                         <div class="form-group">
                             <label>Evaluaciones m&iacute;nimas</label>
                             <p>'.$dataPersona['valor_num_1'].'</p>
                         </div>
                    </div>
                    <div class="col-xs-6 col-md-4">
                         <div class="form-group">
                             <label>Evaluaciones m&aacute;ximas</label>
                             <p>'.$dataPersona['valor_num_2'].'</p>
                         </div>
                     </div> 
                    <div class="col-xs-6 col-md-4">
                         <div class="form-group">
                             <label>Evaluaciones realizadas</label>
                             <p>'.$valoresMongo[0]['count'].'</p>
                         </div>
                     </div>
                    <div class="col-xs-6 col-md-4">
                         <div class="form-group">
                             <label>Evaluaciones normales</label>
                             <p>'.$evaluacionesNormal.'</p>
                         </div>
                     </div>
                    <div class="col-xs-6 col-md-4">
                         <div class="form-group">
                             <label>Evaluaciones &Oacute;ptimas</label>
                             <p>'.$evaluacionesOptima.'</p>
                         </div>
                     </div>
                 </div>
                 <div class = "col-sm-12 text-right m-b-15">
                     <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="verDetalleEvaluaciones(\''.$idCrypt.'\')">Ver Evaluaciones</button>
                 </div>';
        $data['datos']  = $datos;
        $data['tittle'] = _ucwords($dataPersona['nombrecompleto']);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleRoles() {
        $estado = _post('estado');
        $cat    = _post('cat');
        $rol    = ($cat == 'Evaluador de Sede') ? ID_ROL_SUBDIRECTOR : ID_ROL_EVALUADOR;
        $fecInicio = implode("-", array_reverse(explode("/", _post('fecInicio'))));
        $fecFin    = implode("-", array_reverse(explode("/", _post('fecFin'))));
        $dataRoles = $this->m_desempeno_evaluadores->getDetalleRoles($rol,$estado,$fecInicio,$fecFin);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_detalleRol" data-toolbar="#custom-toolbar">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Evaluador');
        $head_2 = array('data' => 'Sede');
        $head_3 = array('data' => 'Nivel');
        $head_4 = array('data' => 'Area');
        $head_5 = array('data' => 'Aula');
        $head_6 = array('data' => 'Docente');
        $head_7 = array('data' => 'Fecha');
        $this->table->set_heading($head_0,$head_7,$head_1,$head_2,$head_3,$head_4,$head_5,$head_6);
        $count = 0;
        foreach($dataRoles AS $row) {
            $count++;
            $row_0 = array('data' => $count);
            $row_1 = array('data' => utf8_decode(_ucwords($row['nombre_evaluador'])));
            $row_2 = array('data' => utf8_decode($row['sede']));
            $row_3 = array('data' => utf8_decode($row['nivel']));
            $row_4 = array('data' => utf8_decode($row['area']));
            $row_5 = array('data' => _ucwords(utf8_decode($row['aula'])));
            $row_6 = array('data' => utf8_decode(_ucwords($row['nombre_docente'])));
            $row_7 = array('data' => implode("/", array_reverse(explode("-", $row['fec_eval']))));
            $this->table->add_row($row_0,$row_7,$row_1,$row_2,$row_3,$row_4,$row_5,$row_6);
        }
        $table = $this->table->generate();
        $titleTable = utf8_decode($cat).' - '.$estado;
        $data['datos'] = $table;
        $data['titleTable'] = $titleTable; 
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleLinea() {
        $estado = _post('estado');
        $fecha  = _post('fecha');
        $dataLinea = $this->m_desempeno_evaluadores->getDetalleLinea($fecha, $estado);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false"  id="tb_detalleRol" data-toolbar="#custom-toolbar">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Evaluador');
        $head_2 = array('data' => 'Sede');
        $head_3 = array('data' => 'Nivel');
        $head_4 = array('data' => 'Area');
        $head_5 = array('data' => 'Aula');
        $head_6 = array('data' => 'Docente');
        $head_7 = array('data' => 'Fecha');
        $this->table->set_heading($head_0, $head_7, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $count = 0;
        foreach($dataLinea AS $row){
            $count++;
            $row_0 = array('data' => $count);
            $row_1 = array('data' => utf8_decode(_ucwords($row['nombre_evaluador'])));
            $row_2 = array('data' => utf8_decode($row['sede']));
            $row_3 = array('data' => utf8_decode($row['nivel']));
            $row_4 = array('data' => utf8_decode($row['area']));
            $row_5 = array('data' => utf8_decode(_ucwords($row['aula'])));
            $row_6 = array('data' => utf8_decode(_ucwords($row['nombre_docente'])));
            $row_7 = array('data' => implode("/", array_reverse(explode("-", $row['fec_eval']))));
            $this->table->add_row($row_0, $row_7, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
        }
        $table = $this->table->generate();
        $titleTable = utf8_decode($estado).' - '.$estado;
        $data['datos'] = $table;
        $data['titleTable'] = $titleTable; 
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleByEvaluador(){
        $idEvaluador = _decodeCI(_post('idEvaluador'));
        $fecInicio   = implode("-", array_reverse(explode("/", _post('fecInicio'))));
        $fecFin      = implode("-", array_reverse(explode("/", _post('fecFin'))));
        $listaData = $this->m_desempeno_evaluadores->getDetalleEvaluadorEval($idEvaluador, $fecInicio, $fecFin);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tbEvaluaciones" data-toolbar="#custom-toolbar">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Evaluador');
        $head_2 = array('data' => 'Sede');
        $head_3 = array('data' => 'Nivel');
        $head_4 = array('data' => 'Area');
        $head_5 = array('data' => 'Aula');
        $head_6 = array('data' => 'Docente');
        $head_7 = array('data' => 'Fecha');
        $this->table->set_heading($head_0, $head_7, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $count = 0;
        foreach($listaData AS $row){
            $count++;
            $row_0 = array('data' => $count);
            $row_1 = array('data' => utf8_decode(_ucwords($row['nombre_evaluador'])));
            $row_2 = array('data' => utf8_decode($row['sede']));
            $row_3 = array('data' => utf8_decode($row['nivel']));
            $row_4 = array('data' => utf8_decode($row['area']));
            $row_5 = array('data' => utf8_decode(_ucwords($row['aula'])));
            $row_6 = array('data' => utf8_decode(_ucwords($row['nombre_docente'])));
            $row_7 = array('data' => ($row['fec_eval']));
            $this->table->add_row($row_0, $row_7, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
        }
        $table = $this->table->generate();
        $titleTable = 'Evaluaciones ejecutadas';
        $data['datos'] = $table;
        $data['titleTable'] = $titleTable; 
        echo json_encode(array_map('utf8_encode', $data));
    }
}