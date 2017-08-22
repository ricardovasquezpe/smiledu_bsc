<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pensiones extends CI_Controller {

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
        $this->load->model('m_utils');
        $this->load->model('m_utils_pagos');
        $this->load->model('m_pensiones');
        $this->load->model('m_compromisos');
        $this->load->model('m_boleta');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, null, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
	

    function logout() {
       $this->session->set_userdata(array("logout" => true));
       unset($_COOKIE[__getCookieName()]);
       $cookie_name2 = __getCookieName();
       $cookie_value2 = "";
       setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
       Redirect(RUTA_SMILEDU, true);
    }
	
	function cambioRol() {
	    $idRol     = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser  = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = _getSesion('id_persona');
	    $idRol     = _getSesion('id_rol');
	    $roles     = $this->m_usuario->getRolesByUsuario($idPersona,$idRol);
	    $return    = null;
	    foreach ($roles as $var){
	        $check = null;
	        $class = null;
	        if($var->check == 1){
	            $check = '<i class="md md-check" style="margin-left: 5px;margin-bottom: 0px"></i>';
	            $class = 'active';
	        }
	        $idRol     = _simple_encrypt($var->nid_rol);
	        $return   .= "<li class='".$class."'>";
	        $return   .= '<a href="javascript:void(0)" onclick="cambioRol(\''.$idRol.'\')"><span class="title">'.$var->desc_rol.$check.'</span></a>';
	        $return   .= "</li>";
	    }
	    $dataUser = array("roles_menu" => $return);
	    $this->session->set_userdata($dataUser);
	}

    function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null){
	        throw new Exception(ANP);
	    }	    
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
    
	function enviarFeedBack(){
	    $nombre    = _getSesion('nombre_completo');
	    $mensaje   = _post('feedbackMsj');
	    $url       = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles     = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'),$idSistema);
	    $result    = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result        .= '</ul>';
	    $data['roles']  = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//Carga el metodo crearTablaNivelesHTML con sus datos
	function cargarSedesByYear(){
		$pensiones_year = trim(_post('pensiones_year'));
		$tipoCrono      = trim(_decodeCI(_post('tipoCrono')));
		try {
			$sedes              = $this->m_utils->getSedes();
			$data['tableSede']  = __buildTablaSedesHTML($sedes, $pensiones_year,$tipoCrono);
			$data['flechasNav'] = __getFlechasByYear($pensiones_year,$tipoCrono);
			$data['img']        = '<div class="img-search m-b-30" id="img_table_empty">
                                       <img src="'.base_url().'public/general/img/smiledu_faces/select_empty_state.png">
                                       <p>Seleccione una sede para</p>
                                       <p>visualizar sus niveles.</p>
                                   </div>';
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function mostrarNiveles() {
	    $id_sede        = _decodeCI(_post('idsede'));
	    $year           = _post('pensiones_year');
	    $tipoCronograma = _decodeCI(_post('tipoCrono'));
	    try {
	        $data['flg_promo'] = 0;
	    	$existe = $this->m_pensiones->existeCondicion($id_sede, $year,$tipoCronograma);
	    	if ($existe == 0){
	    		$data['tableNiveles'] = '<div class="img-search m-b-30">
                                             <img src="'.base_url().'public/general/img/smiledu_faces/select_empty_state.png">
                                             <p>Seleccione una sede para</p>
                                             <p>visualizar sus niveles.</p>
                                         </div>';
	    		$data['iconSedes'] = '';
	    	}else{	
		        $niveles               = $this->m_pensiones->getNivelesbySedes($id_sede);
		        $data['tableNiveles']  = $this->crearTablaNivelesHTML($niveles, $id_sede, $year,$tipoCronograma);
		        $condicion             = $this->m_pensiones->getFlgCerrado($id_sede, $year,$tipoCronograma);
		        $sede                  = $this->m_pensiones->getsede($id_sede);
		        $data['flgCI']         = $this->m_pensiones->getConfigCI($id_sede, $year)['check'];
		        $data['radios']        = $this->buildRadiosByCrono($id_sede,$year,$tipoCronograma);
		        if($condicion == 0){
		        	$data['iconSedes'] ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="openModalCerrarSede(\''.$sede.'\');">
								      	     <i class="mdi mdi-lock_open"></i> Definir montos
								      	 </button>';
		        }else if($condicion == 1){
		        	$data['iconSedes'] = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-cursor__default" disabled>
								      	      <i class="mdi mdi-lock"></i> Montos definidos
								      	  </button>';
		        }
		        $data['flg_promo'] = $this->m_pensiones->getFlgPromo($id_sede,$year,$tipoCronograma);
	    	}
	    	$data['readonly_mat'] = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipoCronograma,'sede_monto')['flg_cerrado_mat'];
		} catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//Crea la tabla de los niveles y grados de cada sede
	function crearTablaNivelesHTML($listaNiveles, $id_sede, $year,$tipoCronograma, $idNivel = null, $idGrado = null) {
	    $tabla    = null;
	    $flgCI    = $this->m_pensiones->getConfigCI($id_sede, $year);
	    $headerCI = '<td class="text-right" style="border-top: none;">C.Ingreso(S/)</td>';
	    $tabla   .= '<table id="tree" class="tree table">';
	    $tabla   .= '<tr >
                       <td class="text left p-l-20" style="border-top: none;">Descripci&oacute;n</td>
                       '.(($flgCI['check'] == 'checked') ? $headerCI :null).'
                       <td class="text-right" style="border-top: none;">Mat. y Rat (S/)</td>
			    	   <td class="text-right" style="border-top: none;">Pensiones(S/)</td>
	    			   <td class="text-right" style="border-top: none;">Desc. Pronto Pago(S/)</td>
			    	   <td class="text-right" style="border-top: none;">Editar</td>
                   </tr>';
	    $val  = 0;
	    $val1 = null;
	    $val2 = 1;
	    foreach ($listaNiveles as $niveles) {
	    	$parpadea = null;
	    	if($idNivel == null){
	    		$parpadea = '';
	    	}else{
	    		if($niveles->nid_nivel == $idNivel){
	    			$parpadea = 'parpadea-text';
	    		}else{
	    			$parpadea = '';
	    		}
	    	}
	        $val++;
	        $val1          = $val;
	        $encryptSede   =_encodeCI($id_sede);
	        $idCondicion   = $this->m_pensiones->getIdCondicion($id_sede, $niveles->nid_nivel, $year,$tipoCronograma);
	        ($idCondicion  == null) ? $idCondicion==0 : $idCondicion;
	        $encryptNivel  =_encodeCI($idCondicion);
	        $montos        = $this->m_pensiones->getMontosByNivelOrByGrado($idCondicion);
	        $condicion     = null;
	        $habilitado    = $this->m_pensiones->getAllMontos($id_sede,$year,$tipoCronograma);
	        $condicion     = $habilitado['flg_cerrado'];
	        ($condicion == 0 && $year >= date("Y")) ? $condicion = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="openModalEditarNivel(\''.$encryptNivel.'\', \''.$encryptSede.'\', \''.$niveles->desc_nivel.'\', \''.$val.'\')">
										                            	<i class="mdi mdi-mode_edit"></i>
										                            </button>' : $condicion = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cursor__default" disabled>
										                                            		 		<i class="mdi mdi-lock"></i>
										                                        			   </button>';
	        $columCINivel = '<td class="text-right '.$parpadea.'" data-col="2">'.$montos['monto_cuota_ingreso'].'</td>';
	        $tabla .='<tr class="treegrid-'.$val.'" data-row="'.$val.'" >
	                      <td class="text-left p-l-10" data-col="1" >'.$niveles->desc_nivel.'</td>
	                      '.(($flgCI['check'] == 'checked') ? $columCINivel : null).'
	                      <td class="text-right '.$parpadea.'" data-col="3">'.$montos['monto_matricula'].'</td>
	                      <td class="text-right '.$parpadea.'" data-col="4">'.$montos['monto_pension'].'</td>
	                      <td class="text-right '.$parpadea.'" data-col="5">'.$montos['descuento_nivel'].'</td>
	                      <td class="text-right">
	                           '.$condicion.'</td>
            	      </tr>';
	        $Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
	        foreach ($Listagrados as $grados) {
	        	$parpadep1 = null;
	        	if($idNivel == null){
	        		if($grados->nid_grado == $idGrado){
	        			$parpadep1 = 'parpadea-text';
	        		}else{
	        			$parpadep1='';
	        		}
	        	}else{
	        		$parpadep1='';
	        	}
	            $val++;
	            $idCondicion1  = $this->m_pensiones->getIdCondicionSedeNivelGrado($id_sede, $niveles->nid_nivel, $grados->nid_grado, $year,$tipoCronograma);
	            $montos1       = $this->m_pensiones->getMontosByNivelOrByGrado($idCondicion1);
	            $encryptGrado  =_encodeCI($idCondicion1);
	            $condicion1    = null;
	            $condicion     = $habilitado['flg_cerrado'];
	            ($condicion == 0 && $year >= date("Y")) ? $condicion1 = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" 
								                	                         onclick="openModalEditarGrado(\''.$encryptGrado.'\', \''.$encryptSede.'\', \''.$niveles->desc_nivel.'\', \''.$grados->desc_grado.'\', \''.$val.'\')">
									                                         <i class="mdi mdi-mode_edit"></i>
									                                     </button>' : $condicion1='<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect mdl-cursor__default" disabled>
																                                       <i class="mdi mdi-lock"></i>
																                                   </button>';
	            $columCIGrado = '<td class="text-right '.$parpadep1.'" data-col="2">'.$montos1['monto_cuota_ingreso'].'</td>';
	            $tabla        .= '<tr class="treegrid-'.($val).' treegrid-parent-'.$val1.'" class="p-l-10" data-row="'.$val.'">
                	                  <td class="text-left" data-col="1">'.$grados->desc_grado.'</td>
                	                  '.(($flgCI['check'] == 'checked') ? $columCIGrado :null).'
                	                  <td class="text-right '.$parpadep1.'" data-col="3">'.$montos1['monto_matricula'].'</td>
                	                  <td class="text-right '.$parpadep1.'" data-col="4">'.$montos1['monto_pension'].'</td>
                	                  <td class="text-right '.$parpadep1.'" data-col="4">'.$montos1['descuento_nivel'].'</td>
                	                  <td class="text-right"> '.$condicion1.' </td>
                	              </tr>';
	        }
	    }
	    $tabla .= '</table>';
	    return $tabla;
	}
	
	function cerrarSedesPagos() {
		$data['error']  = EXIT_ERROR;
		$data['msj']    = null;
		try{
		    $id_sede        = _decodeCI(_post('indexSedeGlobalCerrar'));
		    $year           = trim(_post('pensiones_year'));
		    $tipoCronograma = _decodeCI(_post('tipoCrono'));
		    $flg_cerrar     = _decodeCI(_post('cerrar'));
		    if($flg_cerrar != 1 && $flg_cerrar != 2 && $flg_cerrar != 3){
		        throw new Exception('Selecciona una opci&oacute;n');
		    }
		    $flags  = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipoCronograma,'sede_monto');
		    if($flags['flg_cerrado_mat'] != FLG_CERRADO && $flg_cerrar == 2){
		        throw new Exception(ANP);
		    }
			$arrayUpdate          = ($flg_cerrar == 1) ? array('flg_cerrado_mat' => 1) 
			                                           : array('flg_cerrado'     => 1,
			                                                   'flg_cerrado_mat' => 1
			                                                  );
			$data                 = $this->m_pensiones->actualizarPensionesBySedes($id_sede, $year,$tipoCronograma, $arrayUpdate,1);
			$sedes                = $this->m_utils->getSedes();
			$data['tableSede']    = __buildTablaSedesHTML($sedes, $year,$tipoCronograma);
			$niveles              = $this->m_pensiones->getNivelesbySedes($id_sede);
			$data['tableNiveles'] = $this->crearTablaNivelesHTML($niveles, $id_sede, $year,$tipoCronograma);
			$condicion            = $this->m_pensiones->getFlgCerrado($id_sede, $year,$tipoCronograma);
			$sede                 = $this->m_pensiones->getsede($id_sede);
			$data['radios']       = $this->buildRadiosByCrono($id_sede,$year,$tipoCronograma);
			if($condicion == 0){
				$data['iconSedes'] ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="openModalCerrarSede(\''.$sede.'\');">
                    				     <i class="mdi mdi-lock_open"></i> Definir montos
                    				 </button>';
			}else if($condicion == 1){
				$data['iconSedes'] = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-cursor__default" disabled>
								          <i class="mdi mdi-lock"></i> Montos definidos
								      </button>';
			}
			$data['readonly_mat'] = $arrayUpdate['flg_cerrado_mat'];
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	//Registra o actualiza los distintos montos en cada sede y sus respectivos niveles y grados
	function updatePensionesBySedes() {
		$data['error']   = EXIT_ERROR;
		$data['msj']     = null;
		$montoMatriculal = trim(_post('montoMatriculal'));
		$montoCuotas     = trim(_post('montoCuotas'));
		$id_sede         = _decodeCI(_post('id_sede'));
		$year            = _post('pensiones_year');
		$descuento_sede  = _post('descuento_sede');
        $id_persona      = $this->_idUserSess;
        $tipoCronograma  = _decodeCI(_post('tipoCrono'));
        $name_persona    = _getSesion('nombre_completo');
		try {
			if( $year < date("Y") ) {
				throw new Exception(ANP);
			}
			if(empty($montoMatriculal)) {
				throw new Exception('Ingrese La matr&iacute;cula');
			}
			if($montoMatriculal <= 0) {
				throw new Exception('La matr&iacute;cula Debe ser un numero positivo');
			}
			if(filter_var($montoMatriculal, FILTER_VALIDATE_FLOAT) === false) {
				throw new Exception('Solo Numeros en La matr&iacute;cula');
			}
			if($montoMatriculal >= 1000000) {
			    throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
			}
			if(empty($montoCuotas)) {
				throw new Exception('Ingrese La pensi&oacute;n');
			}
			if($montoCuotas <= 0) {
				throw new Exception('La pensi&oacute;n Debe ser un numero positivo');
			}
			if(filter_var($montoCuotas, FILTER_VALIDATE_FLOAT) === false) {
				throw new Exception('Solo Numeros en La pensi&oacute;n');
			}
			if($montoCuotas >= 1000000) {
			    throw new Exception('La pensi&oacute;n debe ser menor que 1000000');
			}
			if($descuento_sede > $montoCuotas) {
				throw new Exception('El descuento debe ser menor a la Pensi&oacute;n');
			}
			if($tipoCronograma == null) {
			    throw new Exception(ANP);
			}
			if($montoMatriculal == null || $montoCuotas == null || $descuento_sede == null) {
			    throw new Exception('Se deben llenar todos los campos');
			}
			$flags      = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipoCronograma,'sede_monto');
			$existe     = $this->m_pensiones->existeCondicion($id_sede, $year,$tipoCronograma);
			$data['monto_prom'] = null;
			if($existe == 0) {
			    $lastMontosSede     = $this->m_pensiones->getMontosLastYear($year,$id_sede);
			    $data['monto_prom'] = $lastMontosSede['monto_matricula'];
				$arrayInsert    = array('_id_sede'             => $id_sede,
								        'year'                 => $year,
									    'monto_matricula'      => $montoMatriculal,
									    'monto_pension'        => $montoCuotas,
								        'descuento_sede'       => $descuento_sede,
									    'id_pers_registro'     => intval($id_persona),
						                'nombre_pers_registro' => $name_persona,
				                        '_id_tipo_cronograma'  => $tipoCronograma,
				                        'monto_matricula_prom' => $lastMontosSede['monto_matricula']
				                       );
				if($flags['flg_cerrado_mat'] == FLG_CERRADO){
				    unset($arrayInsert['monto_matricula']);
				}
				$arrayGNR       = array();
				$arrayGGR       = array();
				$nombreSede     = $this->m_pensiones->getsede($id_sede);
				$listaNiveles   = $this->m_pensiones->getNivelesbySedes($id_sede);
				foreach ($listaNiveles as $niveles) {
				    $lastMontoNivel = $this->m_pensiones->getMontosNivelGradoLastYear($year,$id_sede,$niveles->nid_nivel,0);
					$arraySubUpdate = array('desc_condicion'       => $nombreSede." ".$niveles->desc_nivel,
											'monto_matricula'      => $montoMatriculal,
											'monto_pension'        => $montoCuotas,
											'_id_sede'       	   => $id_sede,
											'_id_nivel'      	   => $niveles->nid_nivel,
											'_id_grado'      	   => 0,
											'year_condicion'       => $year,
											'descuento_nivel'      => $descuento_sede,
											'tipo_condicion'       => 1,
											'id_pers_registro'     => intval($id_persona),
								            'nombre_pers_registro' => $name_persona,
					                        '_id_tipo_cronograma'  => $tipoCronograma,
                    					    'monto_matricula_prom' => $lastMontoNivel['monto_matricula']
					                       );
					if($flags['flg_cerrado_mat'] == FLG_CERRADO){
					    unset($arraySubUpdate['monto_matricula']);
					}  
					array_push($arrayGNR, $arraySubUpdate);
					$Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
					foreach ($Listagrados as $grados) {
					    $lastMontoGrado  = $this->m_pensiones->getMontosNivelGradoLastYear($year,$id_sede,$niveles->nid_nivel,$grados->nid_grado);
						$arraySubUpdate1 = array('desc_condicion'  => $nombreSede." ".$niveles->desc_nivel." ".$grados->desc_grado ,
											'monto_matricula'      => $montoMatriculal,
											'monto_pension'        => $montoCuotas,
											'_id_sede'       	   => $id_sede,
											'_id_nivel'      	   => $niveles->nid_nivel,
											'_id_grado'      	   => $grados->nid_grado,
											'year_condicion'       => $year,
										    'descuento_nivel'      => $descuento_sede,
											'tipo_condicion'       => 1,
											'id_pers_registro'     => intval($id_persona),
								            'nombre_pers_registro' => $name_persona,
					                        '_id_tipo_cronograma'  => $tipoCronograma,
                						    'monto_matricula_prom' => $lastMontoGrado['monto_matricula']
						                   );
						if($flags['flg_cerrado_mat'] == FLG_CERRADO){
						    unset($montoMatriculal['monto_matricula']);
						}
						array_push($arrayGGR, $arraySubUpdate1);
					}
				}
				if(count($arrayInsert) == 0 || count($arrayGNR) == 0 || count($arrayGGR) == 0){
				    throw new Exception('No hay niveles y grados configurados');   
				}
				$data = $this->m_pensiones->registrarPensionesBySedes($arrayInsert);
				$data = $this->m_pensiones->registrarCodiciones($arrayGNR);
		        $data = $this->m_pensiones->registrarCodiciones($arrayGGR,1);
		    } else if($existe == 1){
    		    $arrayUpdate = array('monto_matricula'     => $montoMatriculal,
    								 'monto_pension'       => $montoCuotas,
    							     'descuento_sede'      => $descuento_sede,
    								 'id_pers_registro'    => intval($id_persona),
    					             'nombre_pers_registro'=> $name_persona);
    		    if($flags['flg_cerrado_mat'] == FLG_CERRADO){
    		        unset($arrayUpdate['monto_matricula']);
    		    }
    			$arrayGNU     = array();
    			$arrayGGU     = array();
    			$nombreSede   = $this->m_pensiones->getsede($id_sede);
    			$listaNiveles = $this->m_pensiones->getNivelesbySedes($id_sede);
    			foreach ($listaNiveles as $niveles) {
    			    $idCondicion =$this->m_pensiones->getIdCondicion($id_sede, $niveles->nid_nivel, $year,$tipoCronograma);
    				$arraySubUpdate = array('id_condicion'         =>$idCondicion,
    										'monto_matricula'      => $montoMatriculal,
    										'monto_pension'        => $montoCuotas,
    						                'descuento_nivel'      => $descuento_sede,
    										'id_pers_registro'     => intval($id_persona),
    							            'nombre_pers_registro' => $name_persona
    				);
    				if($flags['flg_cerrado_mat'] == FLG_CERRADO){
    				    unset($arraySubUpdate['monto_matricula']);
    				}
    				array_push($arrayGNU, $arraySubUpdate);
    				$Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
    				foreach ($Listagrados as $grados) {
    				    $idCondicion1 =$this->m_pensiones->getIdCondicionSedeNivelGrado($id_sede, $niveles->nid_nivel, $grados->nid_grado, $year,$tipoCronograma);
    					$arraySubUpdate1 = array(
    					                        'id_condicion'         =>$idCondicion1,
    											'monto_matricula'      => $montoMatriculal,
    											'monto_pension'        => $montoCuotas,
    							                'descuento_nivel'      => $descuento_sede,
    											'id_pers_registro'     => intval($id_persona),
    								            'nombre_pers_registro' => $name_persona
    					);
    					if($flags['flg_cerrado_mat'] == FLG_CERRADO){
    					    unset($arraySubUpdate1['monto_matricula']);
    					}
    					array_push($arrayGGU, $arraySubUpdate1);
				    }
    			}
    			if(count($arrayGNU) == 0 || count($arrayGGU) == 0){
    			    throw new Exception('No hay niveles y grados configurados');
    			}
    			$data = $this->m_pensiones->actualizarPensionesBySedes($id_sede, $year,$tipoCronograma,$arrayUpdate);
    			$data = $this->m_pensiones->actualizarCodiciones($arrayGNU);
    			$data = $this->m_pensiones->actualizarCodiciones($arrayGGU,1);
		    }
		    if($data['error'] == EXIT_SUCCESS){
		        $sedes                = $this->m_utils->getSedes();
		        $data['tableSede']    = __buildTablaSedesHTML($sedes, $year,$tipoCronograma);
		        $niveles              = $this->m_pensiones->getNivelesbySedes($id_sede);
		        $data['tableNiveles'] = $this->crearTablaNivelesHTML($niveles, $id_sede, $year,$tipoCronograma);
		        $condicion            = $this->m_pensiones->getFlgCerrado($id_sede, $year,$tipoCronograma);
		        $sede                 = $this->m_pensiones->getsede($id_sede);
		        if($condicion == 0){
		            $data['iconSedes'] ='<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="openModalCerrarSede(\''.$sede.'\');">
                        				<i class="mdi mdi-lock_open"></i> Definir montos
                        			 </button>';
		        }else if($condicion == 1){
		            $data['iconSedes'] = '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-cursor__default" disabled>
							      	      <i class="mdi mdi-lock"></i> Montos definidos
							      	  </button>';
		        }
		        $data['radios']     = $this->buildRadiosByCrono($id_sede, $year, $tipoCronograma);
		        $data['flechasNav'] = __getFlechasByYear($year,$tipoCronograma);
		        $data['flgNulo']    = 1;
		    }
	    } catch (Exception $e) {
		    $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
    }
    
    //Trae los montos de cada sede
	function cargarMontosBySedes(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
		    $id            = _decodeCI(_post('id'));
		    $year          = _post('pensiones_year');
		    $tipoCrono     = _decodeCI(_post('tipoCrono'));
		    if($id == null || $year == null || $tipoCrono == null){
		        throw new Exception(ANP);
		    }
			$data  = $this->m_pensiones->getAllMontos1($id, $year,$tipoCrono);
			$cont  = 0;
			if($data['monto_matricula'] != null || $data['monto_pension'] != null || $data['descuento_sede'] != null) {
			    $cont = count($data);
			}
			$data += $this->m_pensiones->getConfigCI($id, $year);
			$data['combo']        = __buildComboByGrupo(COMBO_TIPO_CINGRESO, _ucwords($data['combo']));
			$data['readonly_mat'] = $this->m_utils_pagos->getFlgCerrados($id,$year,$tipoCrono,'sede_monto')['flg_cerrado_mat']; 
			$data['flgNulo'] = ($cont > 0) ? 1 : 0;
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	//Trae los montos de cada nivel
   function cargarMontosbyNivel(){
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$id            = _decodeCI(_post('id'));
		$year          = _post('pensiones_year');
		$tipoCrono     = _decodeCI(_post('tipoCrono'));
		try {
			$data = $this->m_pensiones->getMontosByNivelOrByGrado($id);
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	//Trae los montos de cada grado
	function cargarMontosbyGrado(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $id            = _decodeCI(_post('id'));
	    $year          = _post('pensiones_year');
	    try {
	        $data = $this->m_pensiones->getMontosByNivelOrByGrado($id);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//Actualiza cada nivel con sus respectivos grados
	function updatePensionesBySedesByNivel(){
	    $data['error']        = EXIT_ERROR;
	    $data['msj']          = null;
	    $montoInicialNivel    = trim(_post('montoInicialNivel'));
	    $montoMatriculalNivel = trim(_post('montoMatriculalNivel'));
	    $montoCuotasNivel     = trim(_post('montoCuotasNivel'));
	    $id_condicion         = _decodeCI(_post('id_condicion'));
	    $id_sede              = _decodeCI(_post('id_sedeNivel'));
	    $year                 = _post('pensiones_year');
		$descuento_nivel      = _post('descuento_nivel');
        $id_persona           = $this->_idUserSess;
        $name_persona         = _getSesion('nombre_completo');
        $tipoCrono            = _decodeCI(_post('tipoCrono'));
	    
	    try {
	        $flgCI    = $this->m_pensiones->getConfigCI($id_sede, $year);
	    	if( $year < date("Y") ){
	    		throw new Exception(ANP);
	    	}
	    	if($flgCI['check'] == 'checked'){
    	        if(empty($montoInicialNivel) && $montoInicialNivel != 0){
    	            throw new Exception('Ingrese la cuota de ingeso');
    	        }
    	        if($montoInicialNivel < 0){
    	            throw new Exception('La cuota de ingeso debe ser un numero positivo');
    	        }
    	        if($montoInicialNivel >= 1000000){
    	            throw new Exception('La cuota de ingeso debe ser menor que 1000000');
    	        }
    	        if(filter_var($montoInicialNivel, FILTER_VALIDATE_FLOAT) === false){
    	            throw new Exception('Solo Numeros en la cuota de ingeso');
    	        }
	    	}
	    	$montoInicialNivel = ($flgCI['check'] == 'checked') ? $montoInicialNivel : null;
	    	if(empty($montoMatriculalNivel)){
	            throw new Exception('Ingrese la matr&iacute;cula ');
	        }
	        if($montoMatriculalNivel >= 1000000){
	            throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
	        }
	        if($montoMatriculalNivel <= 0){
	            throw new Exception('La matr&iacute;cula debe ser un numero positivo');
	        }
	        if(filter_var($montoMatriculalNivel, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en la matr&iacute;cula');
	        }
	        if(empty($montoCuotasNivel)){
	            throw new Exception('Ingrese la pensi&oacute;n');
	        }
	        if($montoCuotasNivel <= 0){
	            throw new Exception('La pensi&oacute;n debe ser un numero positivo');
	        }
	        if($montoCuotasNivel >= 1000000){
	            throw new Exception('La pensi&oacute;n debe ser menor que 1000000');
	        }
	        if(filter_var($montoCuotasNivel, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en La Pensi&oacute;n');
	        }
	        if($descuento_nivel > $montoCuotasNivel){
	        	throw new Exception('El descuento debe ser menor a la Pensi&oacute;n');
	        }
	        $arrayUpdateNivel = array('monto_cuota_ingreso'  => $montoInicialNivel,
                    	              'monto_matricula'      => $montoMatriculalNivel,
                    	              'monto_pension'        => $montoCuotasNivel,
	            	                  'descuento_nivel'      => $descuento_nivel,
									  'id_pers_registro'     => intval($id_persona),
						              'nombre_pers_registro' => $name_persona,
	                                  '_id_tipo_cronograma'  => $tipoCrono
	        );
	        $idNivel     = $this->m_pensiones->getIdNivel($id_condicion);
	        $Listagrados = $this->m_utils->getGradosByNivel_sinAula($idNivel);
	        $arrayGGU    = array();
	        foreach ($Listagrados as $grados) {
	            $idCondicion1 =$this->m_pensiones->getIdCondicionSedeNivelGrado($id_sede, $idNivel, $grados->nid_grado, $year,$tipoCrono);
	            $arraySubUpdate1 = array('id_condicion'        =>$idCondicion1,
	                					 'monto_cuota_ingreso' => $montoInicialNivel,
	                					 'monto_matricula'     => $montoMatriculalNivel,
	                					 'monto_pension'       => $montoCuotasNivel,
	                                     'descuento_nivel'     => $descuento_nivel,
										 'id_pers_registro'    => intval($id_persona),
							             'nombre_pers_registro'=> $name_persona);
	            array_push($arrayGGU, $arraySubUpdate1);
	        }
	        $data                 = $this->m_pensiones->actualizarPensionesBySedesbyNivelOrGrado($id_condicion, $arrayUpdateNivel);
	        $data                 = $this->m_pensiones->actualizarCodiciones($arrayGGU,1);
	        $sedes                = $this->m_utils->getSedes();
	        $data['tableSede']    = __buildTablaSedesHTML($sedes, $year,$tipoCrono);
	        $niveles              = $this->m_pensiones->getNivelesbySedes($id_sede);
	        $data['tableNiveles'] = $this->crearTablaNivelesHTML($niveles, $id_sede, $year,$tipoCrono, $idNivel['_id_nivel']);
	    } catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	//Actualiza cada grado
	function updatePensionesBySedesByNivelByGrado(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $montoInicialGrado    = trim(_post('montoInicialGrado'));
	    $montoMatriculalGrado = trim(_post('montoMatriculalGrado'));
	    $montoCuotasGrado     = trim(_post('montoCuotasGrado'));
	    $id_condicion         = _decodeCI(_post('id_condicion'));
	    $id_sede              = _decodeCI(_post('id_sedeNivel'));
	    $year                 = _post('pensiones_year');
        $id_persona           = $this->_idUserSess;
        $name_persona         = _getSesion('nombre_completo');
        $tipoCronograma       = _decodeCI(_post('tipoCrono'));
	    try {
	    	if( $year < date("Y") ){
	    		throw new Exception(ANP);
	    	}
	    	$flgCI    = $this->m_pensiones->getConfigCI($id_sede, $year);
	    	if($flgCI['check'] == 'checked'){
    	        if(empty($montoInicialGrado) && $montoInicialGrado != 0){
    	            throw new Exception('Ingrese la cuota de ingreso');
    	        }
    	        if($montoInicialGrado < 0){
    	            throw new Exception('Debe la cuota de ingreso debe ser un numero positivo');
    	        }
    	        if($montoInicialGrado >= 1000000){
    	            throw new Exception('La cuota de ingreso debe ser menor que 1000000');
    	        }
    	        if(filter_var($montoInicialGrado, FILTER_VALIDATE_FLOAT) === false){
    	            throw new Exception('Solo numeros el la cuota de ingreso');
    	        }
	    	}
	    	$montoInicialGrado = ($flgCI['check'] == 'checked') ? $montoInicialGrado : null;
	        if(empty($montoMatriculalGrado)){
	            throw new Exception('Ingrese la matricula');
	        }
	        if($montoMatriculalGrado >= 1000000){
	            throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
	        }
	        if($montoMatriculalGrado <= 0){
	            throw new Exception('La matr&iacute;cula de ser un numero positivo');
	        }
	        if(filter_var($montoMatriculalGrado, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en la matr&iacute;cula');
	        }
	        if(empty($montoCuotasGrado)){
	            throw new Exception('Ingrese la pensi&oacute;n');
	        }
	        if($montoCuotasGrado >= 1000000){
	            throw new Exception('La Pensi&oacute;n debe ser menor que 1000000');
	        }
	        if($montoCuotasGrado <= 0){
	            throw new Exception('La Pensi&oacute;n de ser un numero positivo');
	        }
	        if(filter_var($montoCuotasGrado, FILTER_VALIDATE_FLOAT) === false){
	            throw new Exception('Solo Numeros en La Pensi&oacute;n');
	        }
	        $arrayUpdateGrado     = array('monto_cuota_ingreso' => $montoInicialGrado,
                    	                  'monto_matricula'     => $montoMatriculalGrado,
                    	                  'monto_pension'       => $montoCuotasGrado,
										  'id_pers_registro'    => intval($id_persona),
							              'nombre_pers_registro'=> $name_persona);
	        $data                 = $this->m_pensiones->actualizarPensionesBySedesbyNivelOrGrado($id_condicion, $arrayUpdateGrado,1);
	        $sedes                = $this->m_utils->getSedes();
	        $data['tableSede']    = __buildTablaSedesHTML($sedes, $year,$tipoCronograma);
	        $niveles              = $this->m_pensiones->getNivelesbySedes($id_sede);
	        $idGrado              = $this->m_pensiones->getIdGrado($id_condicion);
	        $data['tableNiveles'] = $this->crearTablaNivelesHTML($niveles, $id_sede, $year,$tipoCronograma, null, $idGrado['_id_grado']);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getSedesMontoByTipo(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $tipoCrono     = _decodeCI(_post('tipoCrono'));
	    try {
	        if($tipoCrono == null){
	            throw new Exception('Debes seleccionar un tipo de cronograma');
	        }
	        $data['flg_sport_summer'] = 0;
	        if($tipoCrono == CRONO_SPORT_SUMMER){
	            $paquetes           = $this->m_utils_pagos->getPaquetesByTipo($tipoCrono,NULL,NULL);
	            $data['tableSede']  = $this->buildTableMontosPaquete($paquetes);
	            $data['error']      = EXIT_SUCCESS;
	        } else{
	            $sedes              = $this->m_pensiones->getAllSedes(SEDES_NOT_IN);
	            $data['tableSede']  = __buildTablaSedesHTML($sedes, _getYear(), $tipoCrono);
	            $data['flechasNav'] = __getFlechasByYear(_getYear(), $tipoCrono);
	            $data['error']      = EXIT_SUCCESS;
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode',$data));
	}
	
	function getMontosByPaquete(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $sede         = (_decodeCI(_post('sede')) != NULL) ? _decodeCI(_post('sede')) : _simple_decrypt(_post('sede'));
	        $tipoCrono    = _decodeCI(_post('tipoCrono'));
	        $year         = trim(_post('pensiones_year'));
	        if($tipoCrono == null){
	            throw new Exception(ANP);
	        }
		    $niveles              = $this->m_pensiones->getNivelesbySedes($sede);
	        $data['tableNiveles'] = $this->crearTablaNivelesHTML($niveles, $sede, $year, $tipoCrono);
	        $data['error'] = EXIT_SUCCESS;
	    }catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function setConfigCI(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $montoInicial = _post('montoInicial');
	        $tipoCI       = _simpleDecryptInt(_post('tipoCI'));
	        $switchCI     = _post('switchCI');
	        $sede         = _decodeCI(_post('id_sede'));
	        $tipoCrono    = _decodeCI(_post('tipoCrono'));
	        $year         = trim(_post('pensiones_year'));
	        $flags  = $this->m_utils_pagos->getFlgCerrados($sede,$year,$tipoCrono,'sede_monto');
	        if($flags['flg_cerrado_mat'] == FLG_CERRADO){
	            throw new Exception('Ya no se puede realizar esta acci&oacute;n');
	        }
	        if($sede == null){
	            throw new Exception(ANP);
	        }
	        if($tipoCrono == null){
	            throw new Exception(ANP);
	        }
			if($switchCI != true && $switchCI != false){
			    throw new Exception(ANP);
			}
			if($switchCI == 'true'){
			    if(empty($montoInicial) && $montoInicial != 0) {
			        throw new Exception('Ingrese la cuota de ingreso');
			    }
			    if($montoInicial < 0) {
			        throw new Exception('La cuota de ingreso debe ser un numero positivo');
			    }
			    if($montoInicial >= 1000000) {
			        throw new Exception('La cuota de ingreso debe ser menor que 1000000');
			    }
			    if(filter_var($montoInicial, FILTER_VALIDATE_FLOAT) === false) {
			        throw new Exception('Solo Numeros en la cuota de ingreso');
			    }
			    if($tipoCI != FLG_CI_FAMILIA && $tipoCI != FLG_CI_ESTUDIANTE){
			        throw new Exception('Seleccione un tipo');
			    }
			}
			$tipoCI       = ($switchCI == 'false') ? null          : $tipoCI;
			$estado       = ($switchCI == 'true')  ? ESTADO_ACTIVO : ESTADO_INACTIVO;
			$montoInicial = ($switchCI == 'false') ? null          : $montoInicial;
			$countExists = $this->m_pensiones->checkIfExistsConfigCI($sede,$year);
			$arrayUpdInsert = array('_id_sede'   => $sede,
			                        'year'       => $year,
			                        'estado'     => $estado,
			                        'flg_afecta' => $tipoCI,
			                        'accion'     => (($countExists == 0) ? INSERTA : ACTUALIZA)
			                       );
			$arrayUptSede = array('_id_sede'      => $sede,
			                      'year'          => $year,
			                      'cuota_ingreso' => $montoInicial
			                     );
            $arrayNivel   = array();
			$arrayGrado   = array();
			$nombreSede   = $this->m_pensiones->getsede($sede);
			$listaNiveles = $this->m_pensiones->getNivelesbySedes($sede);
			
			foreach ($listaNiveles as $niveles) {
			    $idCondicion =$this->m_pensiones->getIdCondicion($sede, $niveles->nid_nivel, $year,$tipoCrono);
			    if($idCondicion != null){
			        $arraySubUpdate = array('id_condicion'        => $idCondicion,
                    			            'monto_cuota_ingreso' => $montoInicial
                    			           );
			        array_push($arrayNivel, $arraySubUpdate);
			    }
				$Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
				foreach ($Listagrados as $grados) {
				    $idCondicion1 = $this->m_pensiones->getIdCondicionSedeNivelGrado($sede, $niveles->nid_nivel, $grados->nid_grado, $year,$tipoCrono);
				    if($idCondicion1 != null){
				        $arraySubUpdate1 = array('id_condicion'        => $idCondicion1,
                    				             'monto_cuota_ingreso' => $montoInicial
                    				            );
				        array_push($arrayGrado, $arraySubUpdate1);
				    }
			    }
			}
			$data = $this->m_pensiones->updateInsertConfigCI($arrayUpdInsert,$arrayNivel,$arrayGrado,$arrayUptSede);
			if($data['error'] == EXIT_SUCCESS){
			    $data['flgCI']         = $switchCI;
			    $sedes                 = $this->m_utils->getSedes();
			    $niveles               = $this->m_pensiones->getNivelesbySedes($sede);
			    $data['tableSede']     = __buildTablaSedesHTML($sedes, $year,$tipoCrono);
			    $data['tableNiveles']  = $this->crearTablaNivelesHTML($niveles, $sede, $year,$tipoCrono);
			    $data['radios']        = $this->buildRadiosByCrono($sede, $year, $tipoCrono);
			}
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildRadiosByCrono($sede,$year,$tipo){
	    $radios = null;
	    $cont   = 1;
	    $flags  = $this->m_utils_pagos->getFlgCerrados($sede,$year,$tipo,'sede_monto');
	    $disableMyR   = ($flags['flg_cerrado_mat'] == FLG_CERRADO) ? 'disabled' : null;
	    $disableCuota = ($flags['flg_cerrado_mat'] == FLG_CERRADO) ?  null      : 'disabled';
	    $encrycMatri  = _encodeCI(FLG_CERRADO_MATRICULA);
	    $enccryCuota  = _encodeCI(FLG_CERRADO_CUOTA);
	    $enccryTodos  = _encodeCI(FLG_CERRADO_TODO);
	    $configCI     = $this->m_compromisos->verifyConfigCI($sede,$year);
	    $textOpt1     =  ($configCI == null || $configCI['estado'] == 'INACTIVO') ? 'Matr&iacute;cula' : 'Matr&iacute;cula y Cuota de Ingreso'; 
	    $radios = '<div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
                            <input type="radio" id="option-1" class="mdl-radio__button" name="cerrar" value="'.$encrycMatri.'" '.$disableMyR.'>
                            <span class="mdl-radio__label">'.$textOpt1.'</span>
                        </label>
    				</div>
    				<div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
                            <input type="radio" id="option-2" class="mdl-radio__button" name="cerrar" value="'.$enccryCuota.'" '.$disableCuota.'>
                            <span class="mdl-radio__label">Ratificaci&oacute;n y Cuotas</span>
                        </label>
    				</div>
                    <div class="col-sm-12">
    				    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-3">
                            <input type="radio" id="option-3" class="mdl-radio__button" name="cerrar" value="'.$enccryTodos.'">
                            <span class="mdl-radio__label">Todos</span>
                        </label>
    				</div>';
	    return $radios;
	}
	
	function saveMontosPromocion(){
	   $data['error'] = EXIT_ERROR;
	   $data['msj']   = null;
	   try{
	       $monto_matricula = _post('monto_matricula');
	       $id_sede         = _decodeCI(_post('id_sede'));
	       $year            = _post('pensiones_year');
	       $tipo_cronograma = _decodeCI(_post('tipoCrono'));
	       $name_persona    = _getSesion('nombre_completo');
	       $switchProm      = _post('switchProm');
	       $fechaFinPromo   = implode('-', array_reverse(explode('/', _post('fecFinPromo'))));
	       $flags           = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipo_cronograma,'sede_monto');
	       if($switchProm != 'true' && $switchProm != 'false'){
	           throw new Exception(ANP);
	       }
	       if($flags['flg_cerrado_mat'] == 1){
	           throw new Exception('Ya has cerrado esta configuraci&oacute;n');
	       }
	       if($switchProm == 'true' && $flags['flg_cerrado_mat'] == 0){
	           if(empty($monto_matricula)) {
	               throw new Exception('Ingrese La matr&iacute;cula');
	           }
	           if($monto_matricula <= 0) {
	               throw new Exception('La matr&iacute;cula Debe ser un numero positivo');
	           }
	           if(filter_var($monto_matricula, FILTER_VALIDATE_FLOAT) === false) {
	               throw new Exception('Solo Numeros en La matr&iacute;cula');
	           }
	           if($monto_matricula >= 1000000) {
	               throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
	           }   
	       }
	       if($switchProm == 'true' && $fechaFinPromo == null){
	           throw new Exception('Ingresa una fecha fin');
	       }
	       $fecFinAux = explode('-', $fechaFinPromo);
	       if(count($fecFinAux) != 3){
	           throw new Exception('Ingresa una fecha v&aacute;lida');
	       }
	       if($switchProm == 'true' && !(checkdate($fecFinAux[1],$fecFinAux[2],$fecFinAux[0]))){
	           throw new Exception('Ingresa una fecha v&aacute;lida');
	       }
	       if($switchProm == 'true' && $fechaFinPromo <= date('Y-m-d')){
	           throw new Exception('La fecha fin no puede ser el mismo año');
	       }
	       if($switchProm == 'false'){
	           $monto_matricula = null;
	           $fechaFinPromo   = null;
	       }
		   $arrayUpdate = array('monto_matricula_prom' => $monto_matricula,
                    		    'id_pers_registro'     => $this->_idUserSess,
                    		    'nombre_pers_registro' => $name_persona,
		                        'fecha_fin_promo'      => $fechaFinPromo,
		                        'flg_promo'            => (($switchProm == 'true') ? '1' : '0')
		                       );
		   if($flags['flg_cerrado_mat'] == FLG_CERRADO){
		       unset($arrayUpdate['monto_matricula_prom']);
		   }
		   $arrayGNU     = array();
		   $arrayGGU     = array();
		   $nombreSede   = $this->m_pensiones->getsede($id_sede);
		   $listaNiveles = $this->m_pensiones->getNivelesbySedes($id_sede);
		   foreach ($listaNiveles as $niveles) {
		       $idCondicion =$this->m_pensiones->getIdCondicion($id_sede, $niveles->nid_nivel, $year,$tipo_cronograma);
		       $arraySubUpdate = array('id_condicion'         =>$idCondicion,
                    		           'monto_matricula_prom' => $monto_matricula,
                    		           'id_pers_registro'     => $this->_idUserSess,
                    		           'nombre_pers_registro' => $name_persona
                    		          );
		       if($flags['flg_cerrado_mat'] == FLG_CERRADO){
		           unset($arraySubUpdate['monto_matricula_prom']);
		       }
		       array_push($arrayGNU, $arraySubUpdate);
		       $Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
		       foreach ($Listagrados as $grados) {
		           $idCondicion1 =$this->m_pensiones->getIdCondicionSedeNivelGrado($id_sede, $niveles->nid_nivel, $grados->nid_grado, $year,$tipo_cronograma);
		           $arraySubUpdate1 = array('id_condicion'         => $idCondicion1,
                    		                'monto_matricula_prom' => $monto_matricula,
                    		                'id_pers_registro'     => $this->_idUserSess,
                    		                'nombre_pers_registro' => $name_persona
                    		               );
		           if($flags['flg_cerrado_mat'] == FLG_CERRADO){
		               unset($arraySubUpdate1['monto_matricula_prom']);
		           }
		           array_push($arrayGGU, $arraySubUpdate1);
		       }
		   }
		   $data = $this->m_pensiones->actualizarPensionesBySedes($id_sede, $year,$tipo_cronograma,$arrayUpdate);
		   $data = $this->m_pensiones->actualizarCodiciones($arrayGNU);
		   $data = $this->m_pensiones->actualizarCodiciones($arrayGGU,1);
		   $data['flg_promo'] = ($switchProm == 'true') ? '1' : '0';
	   } catch(Exception $e){
	       $data['msj'] = $e->getMessage();
	   }
	   echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveMontosPromocionNivel(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $monto_matricula = _post('monto_matricula');
	        $id_sede         = _decodeCI(_post('id_sede'));
	        $year            = _post('year');
	        $tipo_cronograma = _decodeCI(_post('tipoCrono'));
	        $name_persona    = _getSesion('nombre_completo');
	        $id_condicion    = _decodeCI(_post('id_condicion'));
	        $flags           = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipo_cronograma,'sede_monto');
	        if($flags['flg_cerrado_mat'] == 1){
	            throw new Exception('Ya has cerrado esta configuraci&oacute;n');
	        }
	        if($flags['flg_cerrado_mat'] == 0){
	            if(empty($monto_matricula)) {
	                throw new Exception('Ingrese La matr&iacute;cula');
	            }
	            if($monto_matricula <= 0) {
	                throw new Exception('La matr&iacute;cula Debe ser un numero positivo');
	            }
	            if(filter_var($monto_matricula, FILTER_VALIDATE_FLOAT) === false) {
	                throw new Exception('Solo Numeros en La matr&iacute;cula');
	            }
	            if($monto_matricula >= 1000000) {
	                throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
	            }
	        }
	        $flg_promo = $this->m_pensiones->getFlgPromo($id_sede,$year,$tipo_cronograma);
	        if($flg_promo == 0){
	            throw new Exception('No se ha activado las promociones');
	        }
	        $arrayUpdateNivel = array('monto_matricula_prom' => $monto_matricula,
                        	          'id_pers_registro'     => $this->_idUserSess,
                        	          'nombre_pers_registro' => $name_persona,
                        	          '_id_tipo_cronograma'  => $tipo_cronograma
                        	         );
	        if($flags['flg_cerrado_mat'] == 1){
	            unset($arrayUpdateNivel['monto_matricula_prom']);
	        }
	        $idNivel     = $this->m_pensiones->getIdNivel($id_condicion);
	        $Listagrados = $this->m_utils->getGradosByNivel_sinAula($idNivel);
	        $arrayGGU    = array();
	        foreach ($Listagrados as $grados) {
	            $idCondicion1    = $this->m_pensiones->getIdCondicionSedeNivelGrado($id_sede, $idNivel, $grados->nid_grado, $year,$tipo_cronograma);
	            $arraySubUpdate1 = array('id_condicion'         => $idCondicion1,
                    	                 'monto_matricula_prom' => $monto_matricula,
                    	                 'id_pers_registro'     => $this->_idUserSess,
                    	                 'nombre_pers_registro' => $name_persona
	                                    );
	            if($flags['flg_cerrado_mat'] == 1){
	                unset($arraySubUpdate1['monto_matricula_prom']);
	            }
	            array_push($arrayGGU, $arraySubUpdate1);
	        }   
	        $data  = $this->m_pensiones->actualizarPensionesBySedesbyNivelOrGrado($id_condicion, $arrayUpdateNivel);
	        $data  = $this->m_pensiones->actualizarCodiciones($arrayGGU,1);
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function saveMontosPromocionGrado(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $monto_matricula = _post('monto_matricula');
	        $id_sede         = _decodeCI(_post('id_sede'));
	        $year            = _post('year');
	        $tipo_cronograma = _decodeCI(_post('tipoCrono'));
	        $name_persona    = _getSesion('nombre_completo');
	        $id_condicion    = _decodeCI(_post('id_condicion'));
	        $flags           = $this->m_utils_pagos->getFlgCerrados($id_sede,$year,$tipo_cronograma,'sede_monto');
	        if($flags['flg_cerrado_mat'] == 1){
	            throw new Exception('Ya has cerrado esta configuraci&oacute;n');
	        }
            if(empty($monto_matricula)) {
                throw new Exception('Ingrese La matr&iacute;cula');
            }
            if($monto_matricula <= 0) {
                throw new Exception('La matr&iacute;cula Debe ser un numero positivo');
            }
            if(filter_var($monto_matricula, FILTER_VALIDATE_FLOAT) === false) {
                throw new Exception('Solo Numeros en La matr&iacute;cula');
            }
            if($monto_matricula >= 1000000) {
                throw new Exception('La matr&iacute;cula debe ser menor que 1000000');
            }
	        $flg_promo = $this->m_pensiones->getFlgPromo($id_sede,$year,$tipo_cronograma);
	        if($flg_promo == 0){
	            throw new Exception('No se ha activado las promociones');
	        }
	        $arrayUpdateGrado     = array('monto_matricula_prom' => $monto_matricula,
										  'id_pers_registro'     => $this->_idUserSess,
							              'nombre_pers_registro' => $name_persona
	                                     );
	        if($flags['flg_cerrado_mat'] == 1){
	            unset($arrayUpdateGrado['monto_matricula_prom']);
	        }
	        $data                 = $this->m_pensiones->actualizarPensionesBySedesbyNivelOrGrado($id_condicion, $arrayUpdateGrado,1);
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buildTableMontosPaquete($paquetes){
	    $tabla = null;
	    //CABECERAS
	    $tabla    .=  '<table id="tb_paquetes" class="tree table">
	                       <tr >
                               <td class="col-xs-5 text left p-l-20" style="border-top: none;">Paquetes</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">Descuento</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">Monto</td>
                               <td class="col-sm-2 text-center" style="border-top: none;">Acci&oacute;n</td>
                           </tr>';
	    //PAQUETES
	    $valNodo = 0;
	    $valAux  = null;
	    $year    = _getYear() + 1;
	    foreach($paquetes as $emp){
	        $valNodo++;
	        $valAux         = $valNodo;
	        $idPaqueteCrypt = _encodeCI($emp->id_paquete);
	        $tabla .='<tr class="treegrid-'.$valNodo.'">
	                      <td class="text-left p-l-10 col-sm-3">'.$emp->desc_paquete.'</td>
	                      <td class="text-left col-sm-3 img-table">-</td>
	                      <td class="text-left col-sm-3 img-table">-</td>
	                      <td class="text-center col-sm-3"></td>
            	      </tr>';
	        $arraySedes = array();
	        foreach(explode(',', $emp->id_paquete) as $idSede){
	            array_push($arraySedes, _encodeCI($idSede));
	        }
	        //BANCOS POR EMPRESA
	        $sedes = $this->m_pensiones->getAllSedes(SEDES_NOT_IN);
	        foreach($sedes as $sede){
	            $valNodo++;
	            //BOTONES DE ACCION
	            $idSedeCrypt = _encodeCI($sede->nid_sede);
	            $montos     = $this->m_pensiones->getMontosPaquetesBySede($sede->nid_sede, $emp->id_paquete);
	            $buttonEdit = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-paquete=\''._encodeCI($emp->id_paquete).'\' data-sedes=\''.json_encode($arraySedes).'\' data-toggle="tooltip"
 	                                     data-placement="bottom" title="Editar" data-ref="" data-text="" onclick="openModalSportSummer(\''.$idPaqueteCrypt.'\',\''.$idSedeCrypt.'\')">
                                     <i class="mdi mdi-mode_edit"></i>
                                 </button>';
	            $tabla .='<tr onclick="openTableNivel(\''.$idSedeCrypt.'\')" class="treegrid-'.$valNodo.' treegrid-parent-'.$valAux.'">
	                          <td class="text-left p-l-5 img-table">'.$sede->desc_sede.'</td>
	                          <td class="text-left img-table">'.$montos['descuento_nivel'].'</label></td>
	                          <td class="text-left img-table">'.$montos['monto_matricula'].'</td>
	                          <td class="text-center" style="display: flex">'.$buttonEdit.'</td>
            	          </tr>';
	        }
	    }
	    $tabla .= '</table>';
	    return $tabla;
	}
	
	function setConfigSportSummer(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $sede      = (_decodeCI(_post('indexSedeGlobal')) != NULL)? _decodeCI(_post('indexSedeGlobal')) : _simple_decrypt(_post('indexSedeGlobal'));
	        $paquete   = (_decodeCI(_post('globalPaquete')) != NULL)? _decodeCI(_post('globalPaquete')) : _simple_decrypt(_post('globalPaquete'));
	        $tipoCrono = _decodeCI(_post('tipoCrono'));
	        $year      = trim(_post('year'));
	        $flags     = $this->m_utils_pagos->getFlgCerrados($sede,$year,$paquete,'sede_monto');
	        $cuotaSS   = _post('montoCuotasVerano');
	        $descuento = _post('descuentoSedeVerano');
	        $idPersona = $this->_idUserSess;
	        $nomPersona = $this->m_boleta->getPersona($idPersona);
	        if($flags['flg_cerrado_mat'] == FLG_CERRADO){_log('1');
	            throw new Exception('Ya no se puede realizar esta acci&oacute;n');
	        }
	        if($sede == null){
	            throw new Exception(ANP);
	        }
	        if($tipoCrono == null){
	            throw new Exception(ANP);
	        }
// 	        $tipoCI       = ($switchCI == 'false') ? null          : $tipoCI;
// 	        $estado       = ($switchCI == 'true')  ? ESTADO_ACTIVO : ESTADO_INACTIVO;
// 	        $montoInicial = ($switchCI == 'false') ? null          : $montoInicial;
	        $countExists = $this->m_pensiones->checkIfExistsConfigCI($sede,$year);
	        $nombreSede   = $this->m_pensiones->getsede($sede); 
	        $arrayNivel   = array();
	        $arrayGrado   = array();
	        $listaNiveles = $this->m_pensiones->getNivelesbySedes($sede);
	        foreach ($listaNiveles as $niveles) {
	            $idCondicion = $this->m_pensiones->getIdCondicion($sede, $niveles->nid_nivel, $year,$tipoCrono);
// 	            $nombreNivel = $this->m_pensiones->getNombreNiveles($sede, $niveles->nid_nivel);
	            if($idCondicion != null){
	                $arraySubUpdate = array('id_condicion'        => $idCondicion,
	                                        'monto_cuota_ingreso' => $cuotaSS
	                                       );
	                array_push($arrayNivel, $arraySubUpdate);
	            }
	            $Listagrados = $this->m_utils->getGradosByNivel_sinAula($niveles->nid_nivel);
	            foreach ($Listagrados as $grados) {
	                $idCondicion1 = $this->m_pensiones->getIdCondicionSedeNivelGrado($sede, $niveles->nid_nivel, $grados->nid_grado, $year,$tipoCrono); 
	                if($idCondicion1 != null){
	                    $arraySubUpdate1 = array('id_condicion'        => $idCondicion1,
	                                             'monto_cuota_ingreso' => $cuotaSS
	                    );
	                    array_push($arrayGrado, $arraySubUpdate1);
	                }
	            }
	        }
// 	        $desc_condicion = $nombreSede.' '.$nombreNivel->desc_nivel;
            $accion      = (($this->m_pensiones->getExisteMontosPaquetesBySede($sede,$paquete) == 0) ? INSERTA : ACTUALIZA); 
	        $arrayUpdInsert = array('_id_sede'              => $sede,
                    	            'desc_condicion'        => $nombreSede, 
                    	            'year_condicion'        => $year,
	                                'id_pers_registro'      => $idPersona,
	                                'nombre_pers_reguistro' => $nomPersona,
                    	            'monto_matricula'       => $cuotaSS,
	                                'descuento_nivel'       => $descuento,
                    	            '_id_paquete'           => $paquete,
	                               );
	        $arrayUptSede = array('_id_sede'        => $sede,
                    	          '_id_paquete'     => $paquete,
                    	          'monto_matricula' => $cuotaSS,
	                              'descuento_nivel' => $descuento,
	                             );
	        $data = $this->m_pensiones->registrarCodiciones(array($arrayUpdInsert), $accion, $arrayUptSede);
	        if($data['error'] == EXIT_SUCCESS){
// 	            $data['flgCI']         = $switchCI;
	            $paquetes              = $this->m_utils_pagos->getPaquetesByTipo($tipoCrono,NULL,NULL);
	            $sedes                 = $this->m_utils->getSedes();
	            $niveles               = $this->m_pensiones->getNivelesbySedes($sede);
	            $data['tableSede']     = $this->buildTableMontosPaquete($paquetes);
	            $data['tableNiveles']  = $this->crearTablaNivelesHTML($niveles, $sede, $year,$tipoCrono);
	            $data['radios']        = $this->buildRadiosByCrono($sede, $year, $tipoCrono);
	        }
        _log($data['error']);
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
}