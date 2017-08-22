<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_correos extends CI_Controller{

    private $_idUserSess = null;
    private $_idRol      = null;
    
	public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_correos');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CORREOS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }

	public function index(){
	    $data['titleHeader']                = 'Correos';
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['ruta_logo']                  = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco']           = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']                = NAME_MODULO_PAGOS;
	    //MENU
	    $rolSistemas                        = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']                         = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']                       = $this->load->view('v_menu', $data, true);
	    //NECESARIO
	    $data['events']                     = $this->getAllEnviosCorreoActivos();
	    $this->m_correos->getEstudiantesPensionesVencidas();
	    $this->m_correos->getEstudiantesPagoPuntual();
	    $this->m_correos->getEstudiantesRecVencimiento();
	    ///////////
	    $this->load->view('v_correos', $data);
    }
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE[__getCookieName()]);
        $cookie_name2 = __getCookieName();
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function cambioRol(){
        $idRol     = _simple_decrypt(_post('id_rol'));
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
        $dataUser  = array("id_rol"     => $idRol,
            "nombre_rol" => $nombreRol);
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }
    
    function getRolesByUsuario(){
        $idPersona = _getSesion('id_persona');
        $idRol     = _getSesion('id_rol');
        $roles     = $this->m_usuario->getRolesByUsuario($idPersona, $idRol);
        $return    = null;
        foreach ($roles as $var) {
            $check = null;
            $class = null;
            if ($var->check == 1) {
                $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
                $class = 'active';
            }
            $idRol = _simple_encrypt($var->nid_rol);
            $return .= "<li class='" . $class . "'>";
            $return .= '<a href="javascript:void(0)" onclick="cambioRol(\'' . $idRol . '\')"><span class="title">' . $var->desc_rol . $check . '</span></a>';
            $return .= "</li>";
        }
        $dataUser = array(
            "roles_menu" => $return
        );
        $this->session->set_userdata($dataUser);
    }
    
    function setIdSistemaInSession(){
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url     = _post('url');
        __enviarFeedBack($mensaje, $url, $nombre);
    }
    
    function mostrarRolesSistema(){
        $idSistema = _decodeCI(_post('sistema'));
        $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'), $idSistema);
        $result    = '<ul>';
        foreach ($roles as $rol) {
            $idRol   = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result        .= '</ul>';
        $data['roles']  = $result;
    
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveFechaEnvio(){
        $meses = array(1 =>'Enero',2 =>'Febrero',3 =>'Marzo',4 =>'Abril',5 =>'Mayo',6 =>'Junio',7 =>'Julio',8 =>'Agosto',9 =>'Setiembre',10 =>'Octubre',11=>'Noviembre',12=>'Diciembre');
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $fecha_envio     = DateTime::createFromFormat('d/m/Y',_post('fecha_envio'))->format('Y-m-d');
            $flg_tipo_correo = _post('selected');
            $check           = _post('checked');
            $logeoUsario     = _getSesion('id_persona');
            $nombres         = _getSesion('nombre_usuario');
            if(!in_array($flg_tipo_correo, array(CUOTA_VENCIDA,PRONTO_PAGO,REC_VENCIMIENTO))){
                throw new Exception('Selecciona una opci&oacute;n v&aacute;lida');
            }
            if($fecha_envio == null){
                throw new Exception('Ingresa una fecha v&aacute;lida');
            }
            if ($check != 'true' && $check != 'false') {
                throw new Exception(ANP);
            }
            $currentDate = date('Y-m-d');
            if($fecha_envio <= $currentDate){
                throw new Exception('La fecha debe ser mayor a la actual');
            }
            $existe = $this->m_correos->getCountByDate($fecha_envio,$flg_tipo_correo);
            if($existe > 0){
                throw new Exception('Ya se registr&oacute; el tipo de cuota en esa fecha, intenta con otro');
            }
            $arrayInsert = array();
            $correlativoNew = $this->m_correos->getNextCorrelativoByYear();
            if($check == 'true'){
                $keys = array_keys($meses);
                foreach($keys as $mes){
                    $day = date("d", strtotime($fecha_envio));
                    $correlativoNew = $this->getCorrelativoOperacion($correlativoNew,10);
                    $currentMonth = date("m", strtotime($fecha_envio));
                    if($currentMonth <= $mes){
                        array_push($arrayInsert, array('year'            => date('Y'),
                                                       'fecha_envio'     => (_getYear().'-'.$this->getCorrelativoOperacion($mes, 2).'-'.$day),
                                                       'id_pers_regi'    => $logeoUsario,
                                                       'audi_pers_regi'  => $nombres,
                                                       'correlativo'     => $correlativoNew,
                                                       'flg_tipo_correo' => $flg_tipo_correo
                                                    ));
                                                    $correlativoNew = $correlativoNew + 1;
                    }
                }
            } else{
            	$correlativoNew = $this->getCorrelativoOperacion($correlativoNew,10);
                array_push($arrayInsert, array('year'            => date('Y'),
                                               'fecha_envio'     => $fecha_envio,
                                               'id_pers_regi'    => $logeoUsario,
                                               'audi_pers_regi'  => $nombres,
                                               'correlativo'     => $correlativoNew,
                                               'flg_tipo_correo' => $flg_tipo_correo
                                            ));
            }
            $data = $this->m_correos->insertFechasEnvioCorreo($arrayInsert);
            if($data['error'] == EXIT_SUCCESS){
                $data['events'] = $this->getAllEnviosCorreoActivos();
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getCorrelativoOperacion($correlativo,$cantNumbers){
        $lengthCorre = strlen($correlativo);
        $correlativoNew = null;
        for($i = $lengthCorre; $i < $cantNumbers ; $i++){
            $correlativoNew .= '0';
        }
        $correlativoNew .= $correlativo;
        return $correlativoNew;
    }
    
    function getAllEnviosCorreoActivos(){
        $eventos = $this->m_correos->getAllEventosActivos();
        $array = array();
        $val   = 0;
        foreach($eventos as $row){
            $arraySubUpdate = array('id'         => $val,
                                    'title'      => utf8_encode($row->title),
									'start'      => $row->start,
					                'end'        => $row->end,
			                        'class'      => $row->class);
            array_push($array, $arraySubUpdate);
            $val++;
        }
        return json_encode($array);
    }
    
    function getInfoCorreo(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $fecha = _post('fecha');
            $fecha = date("d-m-Y", ($fecha / 1000) );
            $datos = $this->m_utils->getCamposById('pagos.correo_x_calendario', array('flg_tipo_correo','fecha_envio'), 'fecha_envio', $fecha, 'pagos');
            $data['optTipoCorreo'] = $this->buildComboCorreo($datos['flg_tipo_correo']);
            $data['fecha'] = $fecha;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboCorreo($val){
    	$opcion = '';
    	$opcion .= '<option '.(($val == CUOTA_VENCIDA)   ? 'selected' : null).' value="'.CUOTA_VENCIDA.'"   > Cuota Vencida    </option>';
    	$opcion .= '<option '.(($val == PRONTO_PAGO)     ? 'selected' : null).' value="'.PRONTO_PAGO.'"     > Pronto Pago      </option>';
    	$opcion .= '<option '.(($val == REC_VENCIMIENTO) ? 'selected' : null).' value="'.REC_VENCIMIENTO.'" > Rec. Vencimiento </option>';
    	return $opcion;
    }
    
    function editFechaEnvio(){
        $data['error']   = EXIT_ERROR;
        $data['msj']     = null;
    	$fecha_envio     = DateTime::createFromFormat('d/m/Y',_post('fecha_envio'))->format('Y-m-d');
    	$fechaGlobal     = _post('fechaGlobal');
    	$flg_tipo_correo = _post('selected');
    	$logeoUsario     = _getSesion('id_persona');
    	$nombres         = _getSesion('nombre_usuario');
    	try{
    		if(!in_array($flg_tipo_correo, array(CUOTA_VENCIDA,PRONTO_PAGO,REC_VENCIMIENTO))){
    			throw new Exception('Selecciona una opci&oacute;n v&aacute;lida');
    		}
    		if($fecha_envio == null){
    			throw new Exception('Ingresa una fecha v&aacute;lida');
    		}
    		$currentDate = date('Y-m-d');
    		if($fecha_envio <= $currentDate){
    			throw new Exception('La fecha debe ser mayor a la actual');
    		}
    		$existe = $this->m_correos->getCountByDate($fecha_envio,$flg_tipo_correo);
    		if($existe > 0){
    			throw new Exception('Ya se registr&oacute; en esa fecha');
    		}
    		$datos = $this->m_utils->getCamposById('pagos.correo_x_calendario', array('correlativo','year'), 'fecha_envio', $fechaGlobal, 'pagos');
    		$arrayUpdate = array('fecha_envio' => $fecha_envio,
    							 'id_pers_regi' => $logeoUsario,
    							 'audi_pers_regi' => $nombres,
    							 'flg_tipo_correo' => $flg_tipo_correo);
    		$data = $this->m_correos->updateCorreo($arrayUpdate, $datos['correlativo'], $datos['year']);
    		$data['events'] = $this->getAllEnviosCorreoActivos();
    	}catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    function deleteFechaEnvio(){
    	try{
	    	$fechaGlobal = _post('fechaGlobal');
	    	$fechaGlobal = date("Y-m-d", strtotime($fechaGlobal));
	    	
	    	$check       = _post('checked');
	    	$mes         = date("n", strtotime($fechaGlobal));
	    	$day         = date("d", strtotime($fechaGlobal));
			if ($check != 'true' && $check != 'false') {
	    		throw new Exception(ANP);
	    	}
			$logeoUsario     = _getSesion('id_persona');
	    	$nombres         = _getSesion('nombre_usuario');
	    	$arrayFechas = array();
	    	if($check == 'true'){
	    	for ($i = $mes; $i <= 12; $i++){
	    		$nextMes = (_getYear().'-'.$this->getCorrelativoOperacion($i, 2).'-'.$day) ;
	    		array_push($arrayFechas, $nextMes);
	    	}
	    	}else{
	    		array_push($arrayFechas, $fechaGlobal);
	    	}
	    	$data = $this->m_correos->deleteCorreo($arrayFechas, $day);
	    	$data['events'] = $this->getAllEnviosCorreoActivos();
    	}catch (Exception $e){
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function sendCorreosVencidos(){
        $personas = $this->m_correos->getEstudiantesPensionesVencidas();
        $arrayGeneral = array();
        foreach($personas as $row){
            $cuotas = explode(',', $row->cuotas);
            $total = 0;
            $table = '<table style=" width:100%;border-collapse:collapse;">
                      <thead>
                          <tr style="background-color: #EEEEEE;">
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Cuota</th>
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto</th>
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Fec. Vencimiento</th>
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Mora</th>
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto Final</th>
                          </tr>
                      </thead>
					  <tbody style="color:#666;">';
            foreach($cuotas as $cuota){
                $cuota = explode('|', $cuota);
                $table .= '<tr>
						       <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[0].'</td>
							   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[1].'</td>
							   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[4].'</td>
							   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[2].'</td>
						       <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[3].'</td>
						   </tr>';
                $total += $cuota[3];
            }
            $table .= '<tr style="background-color: #EEEEEE;color:#000;">
					       <td colspan="4" style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;text-align:right;">TOTAL</td>
						   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;" >'.$total.'</td>
                       </tr>';
            $table .= '</tbody>
                       </table>';
            if(isset($arrayGeneral[$row->cod_familia])){
                array_push($arrayGeneral[$row->cod_familia]['table'], $table);
            } else{
                $arrayGeneral[$row->cod_familia]              = array();
                $arrayGeneral[$row->cod_familia]              = array();
                if($row->apoderado != null){
                    $apoderado                                = explode('|', $row->apoderado);
                }
                $arrayGeneral[$row->cod_familia]['apoderado'] = (isset($apoderado[1]) ? $apoderado[1] : null);
                $arrayGeneral[$row->cod_familia]['correo']    = (isset($apoderado[0]) ? $apoderado[0] : null);
                $arrayGeneral[$row->cod_familia]['table']     = array();
                $arrayGeneral[$row->cod_familia]['table'][0]  = $table;
            }
        }
        $this->buildCorreoHTML($arrayGeneral);
    }
    
    function buildCorreoHTML($arrayGeneral = array()){
        foreach($arrayGeneral as $msj){
            $tablesHijos = null;
            foreach($msj['table'] as $table){
                $tablesHijos .= $table;
            }
            $date = explode('-', date('Y-m-d'));
            $html ='<div style="border:1px solid #EEEEEE;width:800px;margin:auto;text-align:center;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);">
                        <div class="parte1" style="height: 80px;padding:20px 30px; border-bottom:1px solid #EEEEEE;">
                            <div class="header" style="width:40%; float:left;">
                                <div style="width:45%;float:left;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/logos_colegio/avantgard.png" style="height: 100%;width:100%;max-width:90px;"> 
                                </div>
                                <div style="width:55%;float:right;text-align:left;">
                                    <h1 style="font-size:16px;margin:0;padding:0;">Maria Fernanda Castro Suarez</h1>
                                    <p style="margin:0;padding:0;color:#666;">cobranzas@nslm.edu.pe</p>
                                </div>
                            </div>
                            <div class="titulo" style="width:50%;float:right;">
                                <div style="text-align:right;">
                                    <h1 style="font-size:16px;margin:0;padding:0;">Recordatorio del mes de '.__mesesTexto($date[1]).'</h1>
                                    <p style="margin:0;padding:0;color:#666;">'.$date[2].' de '.__mesesTexto($date[1]).' del '.$date[0].'</p>
                                </div>
                            </div>
                        </div>
                        <div class="parte2" style="padding:20px 30px;border-bottom:1px solid #EEEEEE;height:80px;">
                            <div style="width:40%; float:left;">
                                <div style="width:45%;float:left;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/foto_perfil_default.png" style="height: 100%;width:100%;max-width:80px;"> 
                                </div>
                                <div style="width:55%;float:right;text-align:left;">
                                    <h1 style="font-size:16px;margin:0;padding:0;">'.$msj['apoderado'].'</h1>
                                    <p style="margin:0;padding:0;color:#666;">'.$msj['correo'].'</p>
                                </div>
                            </div>
                            <div style="width:50%; float:right;">                            
                                <div style="text-align:left;">
                                    <h1 style="font-size:16px;margin:0;padding:0;">Deuda de pagos de marzo-diciembre</h1>
                                    <p style="margin:0;padding:0;color:#666;">Nos dirigimos a usted con la finalidad de recordarle que tiene las siguientes cuotas vencidas seg&uacute;n el cronograma de pagos 2016.</p>
                                </div>
                            </div>
                        </div>
                        <div class="parte3" style="padding:30px; height:100%;">
                        	<div style="text-align:center;">
                                '.($tablesHijos).'
                        	</div>
    
                        	<div style="height:100px;padding:30px 0;">
                    		<div style="width:68%;float:left; text-align: left;">
                    			<p>Usted podr&aacute; realizar los pagos de servicio de la eduaci&oacute;n de su menor hijo(a) acerc&aacute;ndose a los siguientes bancos.</p>
                    			<p>Ante cualquier duda o consulta envie un correo a cobranzas@nslm.edu.pe o ac&eacute;rquese a cualquiera de nuestras sedes.</p>	
                    		</div>
                    		<div style="width: 30%; float:right;height:100%;max-height:200px;">
                    			<div style="text-align:center;height:200px;">
                    				<img src="http://181.224.241.203/smiledu/public/general/img/bancos/bbva.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/bancos/bcp.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/bancos/banbif.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/bancos/scotiabank.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                                    <img src="http://181.224.241.203/smiledu/public/general/img/bancos/comercio.png" style="width:100%;max-width:140px;height:100%;max-height:25px;">
                    			</div>
                    		</div>

                    	</div>                     	
                        </div>         
                    </div>';
            __enviarEmail('cesar.villarrreal@gmail.com', null, $html);
        }
    }
    
    function sendCorreosRecVencimiento(){
        $personas = $this->m_correos->getEstudiantesRecVencimiento();
        $arrayGeneral = array();
        foreach($personas as $row){
            $cuotas = explode(',', $row->cuotas);
            $total = 0;
            $table = '<table style=" width:100%;border-collapse:collapse;">
                      <thead>
                          <tr style="background-color: #EEEEEE;">
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Cuota</th>
                          <!--<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto</th>-->
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Fec. Vencimiento</th>
                          <!--<th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Mora</th>-->
                          <th colspan="1" style="font-weight:bold;padding:5px 0 5px 15px;border:1px solid #EEE;">Monto Final</th>
                          </tr>
                      </thead>
					  <tbody style="color:#666;">';
            foreach($cuotas as $cuota){
                $cuota = explode('|', $cuota);
                $table .= '<tr>
						       <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[0].'</td>
							   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[1].'</td>
							   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[4].'</td>
							   <!--<td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[2].'</td>-->
						       <td style="padding:5px 0 5px 15px;border:1px solid #EEE;">'.$cuota[3].'</td>
						   </tr>';
                $total += $cuota[3];
            }
            $table .= '<tr style="background-color: #EEEEEE;color:#000;">
					       <td colspan="4" style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;text-align:right;">TOTAL</td>
						   <td style="padding:5px 0 5px 15px;border:1px solid #EEE;font-weight:bold;" >'.$total.'</td>
                       </tr>';
            $table .= '</tbody>
                       </table>';
            if(isset($arrayGeneral[$row->cod_familia])){
                array_push($arrayGeneral[$row->cod_familia]['table'], $table);
            } else{
                $arrayGeneral[$row->cod_familia]              = array();
                $arrayGeneral[$row->cod_familia]              = array();
                if($row->apoderado != null){
                    $apoderado                                = explode('|', $row->apoderado);
                }
                $arrayGeneral[$row->cod_familia]['apoderado'] = (isset($apoderado[1]) ? $apoderado[1] : null);
                $arrayGeneral[$row->cod_familia]['correo']    = (isset($apoderado[0]) ? $apoderado[0] : null);
                $arrayGeneral[$row->cod_familia]['table']     = array();
                $arrayGeneral[$row->cod_familia]['table'][0]  = $table;
            }
        }
        $this->buildCorreoHTML($arrayGeneral);
    }
    
    function sendFelicitacionesMsj(){
        $personas = $this->m_correos->getEstudiantesPagoPuntual();
        $arrayGeneral = array();
        $this->buildCorreoHTML($arrayGeneral);
    }
}