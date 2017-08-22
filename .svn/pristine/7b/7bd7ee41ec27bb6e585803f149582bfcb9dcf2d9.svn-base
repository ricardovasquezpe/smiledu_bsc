<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_becas extends CI_Controller{
    
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
        $this->load->model('../m_utils');
        $this->load->model('m_mantenimiento');
        $this->load->model('m_becas');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_CONFIGURACION, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SPED_ROL_SESS);
    }

	public function index(){
	    $data = _searchInputHTML('Busca tus alumnos o aulas');
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']      = 'Becas';
	    $data['ruta_logo']        = MENU_LOGO_PAGOS;
	    $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
	    $data['nombre_logo']      = NAME_MODULO_PAGOS;
        $data['return']           = '';
	    //MENU
	    $rolSistemas         = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
	    $data['apps']        = __buildModulosByRol($rolSistemas, $this->_idUserSess);
	    $data['menu']        = $this->load->view('v_menu', $data, true);
	    $data['optTipoBeca'] = $this->createComboByBecas();
	    $data['optSede']     = __buildComboSedes();
	    $this->load->view('v_alumnos_becas', $data);
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
    function createComboByBecas(){
    	$combo  = $this->m_becas->getComboBecas();
    	$opcion = '';
    	foreach ($combo as $row){
    		$selected = 'selected';
    		$opcion  .= '<option '.$selected.' value="'._simple_encrypt($row->id_condicion).'">'.$row->desc_condicion.'</option>';
    	}
    	return $opcion;
    }
	function comboSedesNivel() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty(_post('idSede')) ? null : _decodeCI(_post('idSede'));
	        $nombre        = _post('nombre');
    	    $apellidos     = _post('apellidos');
    	    $codigoAlumno  = _post('codigoAlumno');
    	    $codigoFamilia = _post('codigoFamilia');
    	    $searchMagic   = trim(_post('searchMagic'));
    	    $offset        = (12*_post('count'));
    	    $data['optNivel'] = null;
    	    $data['cards']    = null;
	        if($idSede == null) {
	            $data['error']    = EXIT_ERROR;
	            $data['optNivel'] = null;
	            throw new Exception(ANP);
	        }
	        $personas = $this->m_becas->getAlumnosByFiltro($idSede,null,null,null,$searchMagic);
	        $data['cards']    = $this->buildCardsAlumnosHTML($personas);
	        $data['optNivel'] = __buildComboNivelesBySede($idSede);
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getComboGradoByNivel_Ctrl() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty(_post('idSede'))  ? null : _decodeCI(_post('idSede'));
	        $idNivel       = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
	        $nombre        = _post('nombre');
    	    $apellidos     = _post('apellidos');
    	    $codigoAlumno  = _post('codigoAlumno');
    	    $codigoFamilia = _post('codigoFamilia');
    	    $searchMagic   = trim(_post('searchMagic'));
    	    $offset        = (12*_post('count'));
	        if($idNivel == null || $idSede == null) {
	            $data['error']    = EXIT_ERROR;
	            $data['optGrado'] = null;
	        }
	        $personas = $this->m_becas->getAlumnosByFiltro($idSede,$idNivel,null,null,$searchMagic);
	        $data['cards']    = $this->buildCardsAlumnosHTML($personas);
	        $data['optGrado'] = __buildComboGradosByNivel($idNivel, $idSede); 
	        $data['error']    = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function comboAulasByGradoUtils() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty(_post('idSede'))  ? null : _decodeCI(_post('idSede'));
	        $idNivel       = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
	        $idGrado       = empty(_post('idGrado')) ? null : _decodeCI(_post('idGrado'));
	        $nombre        = _post('nombre');
    	    $apellidos     = _post('apellidos');
    	    $codigoAlumno  = _post('codigoAlumno');
    	    $codigoFamilia = _post('codigoFamilia');
    	    $searchMagic   = trim(_post('searchMagic'));
    	    $offset        = (12*_post('count'));
	        if($idGrado == null || $idSede == null) {
	            $data['error']    = EXIT_ERROR;
	            $data['optAula']  = null;
	        }
	        $personas = $this->m_becas->getAlumnosByFiltro($idSede,$idNivel,$idGrado,null,$searchMagic);
	        $data['cards']   = $this->buildCardsAlumnosHTML($personas);
	        $data['optAula'] = __buildComboAulas($idGrado, $idSede);
	        $data['error']   = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosFromAula() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idSede        = empty(_post('idSede'))  ? null : _decodeCI(_post('idSede'));
	        $idNivel       = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
	        $idGrado       = empty(_post('idGrado')) ? null : _decodeCI(_post('idGrado'));
	        $idAula        = empty(_post('idAula'))  ? null : _decodeCI(_post('idAula'));
	        $searchMagic   = trim(_post('searchMagic'));
	        $offset        = (12*_post('count'));
	        if($idAula == null) {
	            $data['error']    = EXIT_ERROR;
	            $data['optAula']  = null;
	        }
	        $personas      = $this->m_becas->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic);
	        $data['cards'] = $this->buildCardsAlumnosHTML($personas);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getAlumnosByFiltro() {
	    $data          = null;
    	$idSede        = empty(_post('idSede'))  		? null : _decodeCI(_post('idSede'));
    	$idNivel       = empty(_post('idNivel')) 		? null : _decodeCI(_post('idNivel'));
    	$idAula        = empty(_post('idAula'))  		? null : _decodeCI(_post('idAula'));
    	$nombre        = empty(_post('nombre'))  		? null : _post('nombre');
    	$apellidos     = empty(_post('apellidos'))  	? null : _post('apellidos');
    	$codigoAlumno  = empty(_post('codigoAlumno'))  ? null : _post('codigoAlumno');
    	$codigoFamilia = empty(_post('codigoFamilia')) ? null : _post('codigoFamilia');
    	$searchMagic   = empty(trim(_post('searchMagic')))          ? null : trim(_post('searchMagic'));
	    $offset        = (12*_post('count'));
	    $personas = $this->m_becas->getAlumnosByFiltro($idSede,$idNivel,null,null,$searchMagic);
	    $data['cards'] = $this->buildCardsAlumnosHTML($personas);
	    if($data['cards'] == null || $data['cards'] == ""){
	        $data['cards'] = '<img src="'.base_url().'public/img/smiledu_faces/magic_empty_state.png">
	                          <p>Ups!
                                 No se encontro nada</p>';
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
    function onScrollGetAlumnos(){
    	$data['error'] = EXIT_SUCCESS;
    	$data['msj']   = null;
    	try {
    		$idSede        = (empty(_post('idSede')))  	? null : _decodeCI(_post('idSede'));
    		$idNivel       = (empty(_post('idNivel'))) ? null : _decodeCI(_post('idNivel'));
    		$idGrado       = (empty(_post('idGrado'))) ? null : _decodeCI(_post('idGrado'));
    		$idAula        = (empty(_post('idAula'))) 	? null : _decodeCI(_post('idAula'));
    		
    		$nombre        = empty(_post('nombre'))  		? null : _post('nombre');
    		$apellidos     = empty(_post('apellidos'))  	? null : _post('apellidos');
    		$codigoAlumno  = empty(_post('codigoAlumno'))  ? null : _post('codigoAlumno');
    		$codigoFamilia = empty(_post('codigoFamilia')) ? null : _post('codigoFamilia');
    		$searchMagic   = empty(trim(_post('searchMagic')))          ? null : trim(_post('searchMagic'));
    		$offset        = (12*_post('count'));
    		if($idAula == null) {
    			$data['error']    = EXIT_SUCCESS;
    			$data['optAula']  = null;
    		}
    		if($idSede == null) {
    			$data['error']    = EXIT_ERROR;
    		}
    		$personas      = $this->m_becas->getAlumnosByFiltro($idSede,$idNivel,$idGrado,$idAula,$searchMagic,$offset);
    		$data['cards'] = $this->buildCardsAlumnosHTML($personas);
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
	function buildCardsAlumnosHTML($personas){
	    $card = null;
	    $val = 0;
	    foreach($personas as $row){
	        $val++;
	        $cuotasDeuda = $this->m_becas->verificaDeudaByAlumno($row->nid_persona);
	        $beca        =  $this->m_becas->verificaBeca($row->nid_persona);
	        $class       = ($beca != null) ? 'moroso' : 'puntual';
	        $boton       = null;
	        ($beca['flg_beca'] != null) ? $boton = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect 
	        		                                 mdl-button--raised mdl-button--colored" onclick="openModalQuitarBeca(\''._encodeCI($row->nid_persona).'\');">Quitar</button>' 
                                        : $boton = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect 
	        		                                 mdl-button--raised mdl-button--colored" onclick="openModalAsignarBeca(\''._encodeCI($row->nid_persona).'\');">Aplicar</button>';
	        $divBeca = null;
	        ($beca['flg_beca'] != null) ? $divBeca = '<div class="col-xs-3  student-item">Beca</div>
	                                                  <div class="col-xs-9  student-value">'.$beca['desc_condicion'].'</div>' : $divBeca = '';
	        $contBeca = (($beca['desc_condicion'] != null) ? ' - '.$beca['desc_condicion'] : null);
	        $card .= '<div class="mdl-card mdl-student mdl-shadow--2dp">
                            <div class="mdl-card__title">
                                <img alt="Student" src='.RUTA_IMG.'profile/nouser.svg>
                            </div>
                            <div class="mdl-card__supporting-text pago '.$class.'">
                                <div class="row p-0 m-0">
                                    <div class="col-xs-12 student-name">'.$row->apellidos.'</div>
                                    <div class="col-xs-12 student-name">'.$row->nombres.'</div>
                                    <div class="col-xs-12 student-state">'.(($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al dia').$contBeca.'</div>
                                    <div class="col-xs-12 student-head"><strong>Detalles del Estudiante</strong></div>
                                    <div class="col-xs-7  student-item">Cod. de Alumno</div>
                                    <div class="col-xs-5  student-value">'.$row->cod_alumno.'</div>
                                    <div class="col-xs-7  student-item">Cod. de Familia</div>
                                    <div class="col-xs-5  student-value">'.$row->cod_familia.'</div>
                                    <div class="col-xs-3  student-item">Sede</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_sede.'</div>
                                    <div class="col-xs-3  student-item">Nivel</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_nivel.'</div>
                                    <div class="col-xs-3  student-item">Grado</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_grado.'</div>
                                    <div class="col-xs-3  student-item">Aula</div>
                                    <div class="col-xs-9  student-value">'.$row->desc_aula.'</div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                '.$boton.'
                            </div>
                            <div class="mdl-card__menu">
                                <button id="pago'.$val.'" class="mdl-button mdl-js-button mdl-button--icon">
                                    <i class="mdi mdi-more_vert"></i>
                                </button>
                            </div>
                        </div>';
	    }
	    return $card;
	}
    
	function quitarBeca(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$id            = _decodeCI(_post('idAlumnoQuitar'));
		$searchMagic   = _post('searchMagic');
		$updateGeneral = array();
		try {
			$arrayUpdate = array('estado'=> FLG_ESTADO_INACTIVO);
			$fecha       = date('Y-m-d');
			$tipoBeca    = $this->m_becas->getBeca($id);
			$existeCondicionesByPersona = $this->m_becas->getCondicionesExistentes($id);
			if($existeCondicionesByPersona == NUM_ROWS_CERO){
				$data          = $this->m_becas->modificarBecaDeAlumnoInactivo($id, $arrayUpdate, $fecha, null);
			}else{
				$porcentajeBeca = ($this->m_becas->porcentajeByBecas($tipoBeca)/100);
				$porcentajeBeca = 1 - $porcentajeBeca;
				$detallePagos   = $this->m_becas->detallePagosByPersona($id);
				foreach ($detallePagos as $row){
					if($porcentajeBeca == beca_100){
						$hoy            = date('Y-m-d');
						$descuento      = 0;
						$mora_acumulada = 0;
						if($hoy > $row->fecha_vencimiento){
							$segundos       = strtotime('now') - strtotime($row->fecha_vencimiento);
							$cantDias       = intval($segundos/60/60/24);
							$mora_acumulada = ($cantDias*$row->cantidad_mora);
						} else if ($hoy < $row->fecha_vencimiento && $hoy > $row->fecha_descuento){
							$mora_acumulada = 0;
						} else if ($hoy < $row->fecha_descuento){
						    $mora_acumulada = 0;
							$descuento      = $row->descuento_acumulado;
						}
						$arrayUpdatePagos =   array('mora_acumulada' => $mora_acumulada,
													'monto_final'    => (($row->monto) - $descuento + $mora_acumulada),
													'id_movimiento'  => $row->id_movimiento);
					}else if ($porcentajeBeca != beca_100){
					    $arrayUpdatePagos =   array('mora_acumulada' => ($row->mora_acumulada)/$porcentajeBeca,
												    'monto_final'    => ($row->monto_final)/$porcentajeBeca,
												    'id_movimiento'  => $row->id_movimiento);
					}
					array_push($updateGeneral, $arrayUpdatePagos);
				}
				$data = $this->m_becas->modificarBecaDeAlumnoInactivo($id, $arrayUpdate, $fecha, $updateGeneral);
			}
			$personas                 = $this->m_becas->getAlumnosByFiltro(null, null, null, null,$searchMagic);
    		$data['tableEstudiantes'] = $this->buildCardsEstudiantesHTML($personas);
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
// 	function asignarBeca(){
// 		$data['error'] = EXIT_ERROR;
// 		$data['msj']   = null;
// 		$id            = _decodeCI(_post('idAlumnoAsignar'));
// 		$searchMagic   = _post('searchMagic');
// 		$tipoBeca      = _simple_decrypt(_post('tipoBeca'));
// 		$idSede        = empty(_post('idSede'))  ? null : _decodeCI(_post('idSede'));
// 		$idNivel       = empty(_post('idNivel')) ? null : _decodeCI(_post('idNivel'));
// 		$idGrado       = empty(_post('idGrado')) ? null : _decodeCI(_post('idGrado'));
// 		$idAula        = empty(_post('idAula'))  ? null : _decodeCI(_post('idAula'));
// 		if(empty($tipoBeca)){
// 		    throw new Exception('Seleccione Tipo de Beca');
// 		}
// 		try {
// 			$existeBecaByPersona = $this->m_becas->getBecaAsignada($tipoBeca, $id);
// 			$updateGeneral       = array();
// 			if($existeBecaByPersona == NUM_ROWS_CERO){
// 				$existeCondicionesByPersona = $this->m_becas->getCondicionesExistentes($id);
// 				$arrayInsert = array('estado'        => ESTADO_ACTIVO,
// 									 '_id_persona'   => $id,
// 									 '_id_condicion' => $tipoBeca,
// 									 'flg_beca'      => FLG_BECA);
// 				if($existeCondicionesByPersona == NUM_ROWS_CERO){
// 					$data = $this->m_becas->asignarBecaDeAlumno($arrayInsert);
// 				}else{
// 					$porcentajeBeca = ($this->m_becas->porcentajeByBecas($tipoBeca)/100);
// 					$porcentajeBeca = 1-$porcentajeBeca;
// 					$detallePagos   = $this->m_becas->detallePagosByPersona($id);
// 					foreach ($detallePagos as $row){
// 						$arrayUpdatePagos = array('mora_acumulada' => ($row->mora_acumulada)*$porcentajeBeca,
// 												  'monto_final'    => ($row->monto_final)*$porcentajeBeca,
// 												  'id_movimiento'  => $row->id_movimiento);
// 					array_push($updateGeneral, $arrayUpdatePagos);
// 					}
// 					$data = $this->m_becas->asignarBecaDeAlumno($arrayInsert, $updateGeneral);
// 				}	
// 			}else{
// 				$arrayUpdate  = array('estado'=> ESTADO_ACTIVO);
// 				$fecha        = date('Y-m-d');
// 				$existeCondicionesByPersona = $this->m_becas->getCondicionesExistentes($id);
// 				if($existeCondicionesByPersona == NUM_ROWS_CERO){
// 					$data = $this->m_becas->modificarBecaDeAlumno($id, $arrayUpdate, $fecha, $tipoBeca, null);
// 				}else{
// 					$porcentajeBeca = ($this->m_becas->porcentajeByBecas($tipoBeca)/100);
// 					$porcentajeBeca = 1-$porcentajeBeca;
// 					$detallePagos   = $this->m_becas->detallePagosByPersona($id);
// 					foreach ($detallePagos as $row){
// 						$arrayUpdatePagos =   array('mora_acumulada' => ($row->mora_acumulada)*$porcentajeBeca,
// 													'monto_final'    => ($row->monto_final)*$porcentajeBeca,
// 													'id_movimiento'  => $row->id_movimiento);
// 						array_push($updateGeneral, $arrayUpdatePagos);
// 					}
// 					$data = $this->m_becas->modificarBecaDeAlumno($id, $arrayUpdate, $fecha, $tipoBeca, $updateGeneral);
// 				}
// 			}
// 			$personas      = $this->m_becas->getAlumnosByFiltro($idSede, $idNivel, $idGrado, $idAula,$searchMagic);
// 			$data['cards'] = $this->buildCardsEstudiantesHTML($personas);
// 		} catch (Exception $e) {
// 			$data['msj'] = $e->getMessage();
// 		}
// 		echo json_encode(array_map('utf8_encode', $data));
// 	}
	
	function asignarBeca(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $id            = _decodeCI(_post('idAlumnoAsignar'));
	        $searchMagic   = _post('searchMagic');
	        $tipoBeca      = _simple_decrypt(_post('tipoBeca'));
	        $yearBeca      = _post('yearBeca');
	        if(empty($tipoBeca)){
	            throw new Exception('Seleccione Tipo de Beca');
	        }
	        if(empty($yearBeca)){
	            throw new Exception('Seleccione A&ntilde;o');
	        }
	        $existeBecaByPersona = $this->m_becas->getBecaAsignada($tipoBeca, $id);
	        $updateGeneral       = array();
	        if($existeBecaByPersona == NUM_ROWS_CERO){
	            $existeCondicionesByPersona = $this->m_becas->getCondicionesExistentes($id);
	            $arrayInsert = array('estado'        => ESTADO_ACTIVO,
                	                 '_id_persona'   => $id,
                	                 '_id_condicion' => $tipoBeca,
                	                 'flg_beca'      => FLG_BECA,
	                                 'year_uso'      => $yearBeca
	            );
	            if($existeCondicionesByPersona == NUM_ROWS_CERO){
	                $data = $this->m_becas->asignarBecaDeAlumno($arrayInsert);
	            }else{
	                $porcentajeBeca = ($this->m_becas->porcentajeByBecas($tipoBeca)/100);
	                $porcentajeBeca = 1-$porcentajeBeca;
	                $detallePagos   = $this->m_becas->detallePagosByPersona($id);
	                foreach ($detallePagos as $row){
	                    $arrayUpdatePagos = array('mora_acumulada' => ($row->mora_acumulada)*$porcentajeBeca,
                        	                      'monto_final'    => ($row->monto_final)*$porcentajeBeca,
                        	                      'id_movimiento'  => $row->id_movimiento);
	                    array_push($updateGeneral, $arrayUpdatePagos);
	                }
	                $data = $this->m_becas->asignarBecaDeAlumno($arrayInsert, $updateGeneral);
	            }
	        }else{
	            $arrayUpdate  = array('estado'=> ESTADO_ACTIVO);
	            $fecha        = date('Y-m-d');
	            $existeCondicionesByPersona = $this->m_becas->getCondicionesExistentes($id);
	            if($existeCondicionesByPersona == NUM_ROWS_CERO){
	                $data = $this->m_becas->modificarBecaDeAlumno($id, $arrayUpdate, $fecha, $tipoBeca, null);
	            }else{
	                $porcentajeBeca = ($this->m_becas->porcentajeByBecas($tipoBeca)/100);
	                $porcentajeBeca = 1-$porcentajeBeca;
	                $detallePagos   = $this->m_becas->detallePagosByPersona($id);
	                foreach ($detallePagos as $row){
	                    $arrayUpdatePagos =   array('mora_acumulada' => ($row->mora_acumulada)*$porcentajeBeca,
                        	                        'monto_final'    => ($row->monto_final)*$porcentajeBeca,
                        	                        'id_movimiento'  => $row->id_movimiento);
	                    array_push($updateGeneral, $arrayUpdatePagos);
	                }
	                $data = $this->m_becas->modificarBecaDeAlumno($id, $arrayUpdate, $fecha, $tipoBeca, $updateGeneral);
	            }
	        }
	        $personas      = $this->m_becas->getAlumnosByFiltro(null, null, null, null,$searchMagic);
	        $data['tableEstudiantes'] = $this->buildCardsEstudiantesHTML($personas);
            $arrayBecas               = $this->m_becas->getBecas();
            $data['tableBecas']       = __buildTablaBecasHTML($arrayBecas);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
    function mostrarDetalle(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $id            = _decodeCI(_post('id'));
        try {
            $data = $this->m_becas->getDetalleBeca($id);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
       echo json_encode(array_map('utf8_encode', $data));
    }
    function promocionDetalle(){
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$id            = _decodeCI(_post('idCondicionPromo'));
    	try {
    		$detalle = $this->m_becas->getDetallePromocion($id);
    		$data['desc_promo'] = utf8_decode($detalle['desc_promo']);
    		$data['cant_cuotas'] = ($detalle['cant_cuotas']);
    		$data['porcentaje_descuento'] = ($detalle['porcentaje_descuento']);
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    function updateBeca(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $desc          = trim(_post('desc'));
        $procentaje    = _post('procentaje');
        $id            = _decodeCI(_post('idCondicion'));
        try {
            if(empty($desc)){
                throw new Exception('Ingrese una Descripcion');
            }
            if(strlen($desc) >= 50){
                throw new Exception('Capacidad Maxima 50 carcteres');
            }
            $original=$this->m_becas->descBeca($id);
            if(strtoupper($desc) != strtoupper($original)){
                 $descripccion = $this->m_becas->allBecas($desc);
                if($descripccion == NUM_ROWS_UNO){
                    throw new Exception('Beca ya registrada');
                }
            }
            if(empty($procentaje)){
                throw new Exception('Ingrese el procentaje');
            }
            if($procentaje <= 0){
                throw new Exception('Debe ser un numero positivo le procentaje');
            }
            if($procentaje > 100){
                throw new Exception('El procentaje no debe ser mayor a 100%');
            }
            if(filter_var($procentaje, FILTER_VALIDATE_FLOAT) === false){
                throw new Exception('Solo Numeros en procentaje');
            }
    
            $arrayUpdate = array('desc_condicion'  => $desc,
                                 'porcentaje_beca' => $procentaje,
                                 'year_condicion'  => date ("Y"),
                                 'tipo_condicion'  => '0');
            $data               = $this->m_becas->actualizarBeca($id, $arrayUpdate);
            $arrayBecas         = $this->m_becas->getBecas();
            $data['tableBecas'] = __buildTablaBecasHTML($arrayBecas);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarBeca(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $desc          = trim(_post('desc'));
        $procentaje    = _post('procentaje');
        try {
            if(empty($desc)){
                throw new Exception('Ingrese una Descripcion');
            }
            if(strlen($desc) >= 50){
                throw new Exception('Capacidad Maxima 50 carcteres');
            }
            $descripccion = $this->m_becas->allBecas($desc);
            if($descripccion == NUM_ROWS_UNO){
                throw new Exception('Beca ya registrada');
            }
            
            if(empty($procentaje)){
                throw new Exception('Ingrese el procentaje');
            }
            if($procentaje <= 0){
            	throw new Exception('Debe ser un numero positivo le procentaje');
            }
            if($procentaje > 100){
            	throw new Exception('El procentaje no debe ser mayor a 100%');
            }
            if(filter_var($procentaje, FILTER_VALIDATE_FLOAT) === false){
                throw new Exception('Solo Numeros en procentaje');
            }
            
            $arrayInsert = array('desc_condicion'   => $desc,
                                 'porcentaje_beca'  => $procentaje,
                                 'year_condicion'   => date ("Y"),
                                 'tipo_condicion'   => '0');
            $data                = $this->m_becas->registrarBeca($arrayInsert);
            $arrayBecas          = $this->m_becas->getBecas();
            $data['tableBecas']  = __buildTablaBecasHTML($arrayBecas);
            $data['optTipoBeca'] = $this->createComboByBecas();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function guardarPromocion(){
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$desc          = trim(_post('desc'));
    	$cantidad      = trim(_post('cantidad'));
    	$procentaje    = _post('procentaje');
    	try {
    		if(empty($desc)){
    			throw new Exception('Ingrese una Descripcion');
    		}
    		if(strlen($desc) >= 50){
    			throw new Exception('Capacidad Maxima 50 carcteres');
    		}
    		$descripccion = $this->m_becas->allPromocion($desc);
    		if($descripccion == NUM_ROWS_UNO){
    			throw new Exception('Promocion ya registrada');
    		}
    		if(empty($cantidad)){
    			throw new Exception('Ingrese la cantidad de cuotas');
    		}
    		if(filter_var($cantidad, FILTER_VALIDATE_INT) === false){
    			throw new Exception('Solo Numeros en la cantidad de cuotas');
    		}
    		if(empty($procentaje)){
    			throw new Exception('Ingrese el procentaje');
    		}
    		if($procentaje <= 0){
    			throw new Exception('Debe ser un numero positivo le procentaje');
    		}
    		if($procentaje > 100){
    			throw new Exception('El procentaje no debe ser mayor a 100%');
    		}
    		if(filter_var($procentaje, FILTER_VALIDATE_FLOAT) === false){
    			throw new Exception('Solo Numeros en procentaje');
    		}
    
    		$arrayInsert = array('desc_promo'   => $desc,
				    				'porcentaje_descuento'  => $procentaje,
				    				'cant_cuotas'   => $cantidad);
    		$data               = $this->m_becas->registrarPromocion($arrayInsert);
    		$arrayBecas         = $this->m_becas->getBecas();
    		$data['tableBecas'] = __buildTablaBecasHTML($arrayBecas);
    		$arrayPromociones   = $this->m_becas->getPromociones();
    		$data['tablePromociones'] = __buildTablaPromocionesHTML($arrayPromociones);
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
    }
    
    function updatePromocion(){
    	$data['error'] = EXIT_ERROR;
    	$data['msj']   = null;
    	$desc          = trim(_post('desc'));
    	$cantidad      = _post('cantidad');
    	$procentaje    = _post('procentaje');
    	$id            = _decodeCI(_post('idCondicionPromo'));
    	try {
    		if(empty($desc)){
    			throw new Exception('Ingrese una Descripcion');
    		}
    		if(strlen($desc) >= 50){
    			throw new Exception('Capacidad Maxima 50 carcteres');
    		}
    		$original=$this->m_becas->descPromocion($id);
    		if(strtoupper($desc) != strtoupper($original)){
    			$descripccion = $this->m_becas->allPromocion($desc);
    			if($descripccion == NUM_ROWS_UNO){
    				throw new Exception('Promocion ya registrada1');
    			}
    		}
    		if(empty($cantidad)){
    			throw new Exception('Ingrese la cantidad de cuotas');
    		}
    		if(filter_var($cantidad, FILTER_VALIDATE_INT) === false){
    			throw new Exception('Solo Numeros enteros en cantidad1');
    		}
    		if(empty($procentaje)){
    			throw new Exception('Ingrese el procentaje');
    		}
    		if($procentaje <= 0){
    			throw new Exception('Debe ser un numero positivo le procentaje');
    		}
    		if($procentaje > 100){
    			throw new Exception('El procentaje no debe ser mayor a 100%');
    		}
    		if(filter_var($procentaje, FILTER_VALIDATE_FLOAT) === false){
    			throw new Exception('Solo Numeros en procentaje');
    		}
    	
    		$arrayUpdate = array('desc_promo'   => $desc,
				    				'porcentaje_descuento'  => $procentaje,
				    				'cant_cuotas'   => $cantidad);
    		$data               = $this->m_becas->actualizarPromocion($id, $arrayUpdate);
    		$arrayBecas         = $this->m_becas->getBecas();
    		$data['tableBecas'] = __buildTablaBecasHTML($arrayBecas);
    		$arrayPromociones   = $this->m_becas->getPromociones();
    		$data['tablePromociones'] = __buildTablaPromocionesHTML($arrayPromociones);
    	} catch (Exception $e) {
    		$data['msj'] = $e->getMessage();
    	}
    	echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getEstudiantesByFiltro(){
        $data          = null;
        $searchMagic   = empty(trim(_post('nameEstudiante'))) ? null : trim(_post('nameEstudiante'));
        $offset        = (12*_post('count'));
        $personas = $this->m_becas->getAlumnosByFiltro(null,null,null,null,$searchMagic);
        $data['tableEstudiantes'] = $this->buildCardsEstudiantesHTML($personas);
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildCardsEstudiantesHTML($personas){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_estudiantes">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Estudiante');
	    $head_1 = array('data' => 'Estado'     , 'class' => 'text-left');
	    $head_2 = array('data' => 'Beca'       , 'class' => 'text-right');
	    $head_3 = array('data' => 'Acci&oacute;n'          , 'class' => 'text-center');
	    $this->table->set_heading($head_0,$head_1,$head_2,$head_3);
	    $cont = 1;
	    foreach($personas as $row){
	        $cuotasDeuda = $this->m_becas->verificaDeudaByAlumno($row->nid_persona);
	        $beca        =  $this->m_becas->verificaBeca($row->nid_persona);
	        $checkedBeca = ($beca['desc_condicion'] != null) ? 'checked' : null;
	        $idEstuCrypt = _encodeCI($row->nid_persona);
	        $switchHTML  = '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch'.$cont.'">
                                <input type="checkbox" id="switch'.$cont.'" onclick="asignarQuitarBeca($(this));" class="mdl-switch__input" '.$checkedBeca.' attr-persona="'.$idEstuCrypt.'">
                                <span class="mdl-switch__label"></span>
                            </label>';
	        $datoPersona = '<img class="img-circle" style="cursor:pointer;width:35px;" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg">
	                            </img>'.$row->nombrecompleto;
	        $contBeca    = (($beca['desc_condicion'] != null) ? $beca['desc_condicion'] : ' - ');
	        $row_col0    = array('data' => $datoPersona);
	        $row_col1    = array('data' => '<div class="col-xs-12 student-state">'.(($cuotasDeuda > 0) ? $cuotasDeuda.' cuota(s) vencida(s)' : 'Al dia').'</div>', 'class' => 'text-left');
	        $row_col2   = array('data' => $contBeca   , 'class' => 'text-right');
	        $row_col3   = array('data' => $switchHTML , 'class' => 'text-center');
	        $cont++;
	        $this->table->add_row($row_col0,$row_col1,$row_col2,$row_col3);
	    }
	    return $this->table->generate();
	}
}