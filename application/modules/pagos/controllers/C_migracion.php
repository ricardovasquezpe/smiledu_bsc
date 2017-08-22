<?php
defined('BASEPATH') or exit('No direct script access allowed');
//@PENDIENTE cambiar por la columna cod_alumno
class C_migracion extends CI_Controller {
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $GLOBALS['er'] = 'error';
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_migracion');
        $this->load->model('m_caja');
        $this->load->model('m_movimientos');
        $this->load->library('table');
        $this->load->helper('download');
        $this->load->helper('file');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_MIGRACION_PAGOS, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
    public function index() {
        $data['titleHeader']                = 'Migraci&oacute;n';
        $tabSess   = _getSesion('tab_active_config');
        $tabSess   = (($tabSess == null && $this->_idRol == ID_ROL_RESP_COBRANZAS) || $tabSess == 'tab-1' && $this->_idRol == ID_ROL_RESP_COBRANZAS) ? 'tab-1' : ((($tabSess == null && $this->_idRol == ID_ROL_CONTABILIDAD) ? 'tab-2' : $tabSess));
        $data['barraSec']    = $this->buildTabsByRol($tabSess,$this->_idRol);
        $data['tabActivo']  = $tabSess;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_PAGOS, PAGOS_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Migraci&oacute;n';
        $data['ruta_logo']        = MENU_LOGO_PAGOS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_PAGOS;
        $data['nombre_logo']      = NAME_MODULO_PAGOS;
        //MENU
        $rolSistemas        = $this->m_utils->getSistemasByRol(ID_SISTEMA_PAGOS, $this->_idUserSess);
        $data['apps']       = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']       = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $data['optYears']   = $this->buildComboYear();
        $data['optMeses']   = $this->buildComboMeses();
        $data['tbEmpresas'] = $this->buildTableEmpresasHTML();
        $data['tbBancos']   = $this->buildTableMigracionBancosHTML();
        ///////////
        $this->session->set_userdata(array('tab_active_movi' => null));
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_migracion', $data);
    }
    
    function buildTabsByRol($activo,$rol){
	    $tabs = null;
	    if($rol == ID_ROL_RESP_COBRANZAS){
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                         <a href="#tab-1" class="mdl-layout__tab '.(($activo == 'tab-1') ? 'is-active' : null).'">Bancos</a>
                     </div>';
	    } else if($rol == ID_ROL_CONTABILIDAD){
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                         <a href="#tab-2" class="mdl-layout__tab '.(($activo == 'tab-2') ? 'is-active' : null).'">SISCONT</a>
                    </div>';
	    } else{
	        $tabs = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                         <a href="#tab-1" class="mdl-layout__tab '.(($activo == 'tab-1') ? 'is-active' : null).'">Bancos</a>
                         <a href="#tab-2" class="mdl-layout__tab '.(($activo == 'tab-2') ? 'is-active' : null).'">SISCONT</a>
                     </div>';
	    }
	    return $tabs;
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
	    $idRol = _simple_decrypt(_post('id_rol'));
	    $nombreRol = $this->m_utils->getById("public.rol", "desc_rol", "nid_rol", $idRol, 'smiledu');
	    $dataUser = array("id_rol"     => $idRol,
	                      "nombre_rol" => $nombreRol);
	    $this->session->set_userdata($dataUser);
	    $result['url'] = base_url()."c_main/";
	    echo json_encode(array_map('utf8_encode', $result));
	}
	
	function getRolesByUsuario() {
	    $idPersona = $this->_idUserSess;
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
	    $nombre = _getSesion('nombre_completo');
	    $mensaje = _post('feedbackMsj');
	    $url = _post('url');
	    __enviarFeedBack($mensaje,$url,$nombre);
	}
	
	function mostrarRolesSistema(){
	    $idSistema = _decodeCI(_post('sistema'));
	    $roles = $this->m_usuario->getRolesOnlySistem($this->_idUserSess,$idSistema);
	    $result = '<ul>';
	    foreach($roles as $rol){
	        $idRol   = _encodeCI($rol->nid_rol);
	        $result .= '<li style="cursor:pointer" onclick="goToSistema(\''._post('sistema').'\', \''.$idRol.'\')">'.$rol->desc_rol.'</li>';
	    }
	    $result .= '</ul>';
	    $data['roles'] = $result;
	    
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function generateTxtFile($datos,$tipoTxt){
	    $data['error'] = EXIT_ERROR;
	    $filename = "msiscont".$tipoTxt."_".date('Ymdhis').".txt";
	    $f = fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	    $result = $this->buildTxtByTipo($tipoTxt, $datos, $f);
	    fclose($result[0]);
	    return array($filename,$result[1]);
	}
	
	function buildTxtByTipo($tipoTxt,$datos,$f){
	    $arrayDocumentos = array();
	    $correlativoOpe  = '1';
	    if($tipoTxt == 'v'){
	        $arrayColumn1        = array(1 , 25 , 71 , 81 , 96 , 114 , 185 , 235);
	        $arrayColumn2        = array(1 , 25 , 71 , 81 , 96 , 114 , 135 , 171 , 185 , 235);
	        $arrayColumn3        = array(1 , 25 , 71 , 81 , 96 , 114 , 185 , 235);
	        foreach($datos as $row){
// 	            $row->estudiante = ($row->estado != 'ANULADO') ? _getSesion('nombre_completo') : $row->flg_anulado;
// 	            $row->cod_alumno_temp = $this->m_utils->getById('public.persona', 'cod_alumno_temp', 'nid_persona', $row->_id_persona, 'smiledu');
	            $fila            = null;
	            $nro_ope         = $this->getCorrelativoOperacion($correlativoOpe,4);
	            if($row->estado == 'ANULADO'){
	                $arrayColumn1[3] = ($arrayColumn1[3]-2);
	                $arrayColumn2[3] = ($arrayColumn2[3]-2);
	                $arrayColumn3[3] = ($arrayColumn3[3]-2);
	            }
	            for($i = 1; $i <= 3; $i++){
	                if($i == 1){
	                    $arrayDatos = array((OP_VENTA.$nro_ope.$row->fecha_registro.CTA_POR_COBRAR),
	                                        ($row->monto.DEBE.SOLES.TIPO_CAMBIO.$row->aux_monto.$row->correlativo),
                	                        ($row->fecha_registro),
                	                        (($row->estado == ESTADO_ANULADO) ? $row->flg_cod_alumn : $row->cod_alumno_temp),
                	                        ('1002'),//CENTRO DE COSTO DE CADA SEDE
                	                        ('V'.$row->fecha_registro),
                	                        ($row->cod_alumno_temp.'2'.$row->estudiante),
                	                        ($row->desc_detalle_crono.PHP_EOL)
                	                       );
	                    $fila = $this->buildStringByArray($arrayDatos, $arrayColumn1);
	                } else if($i == 2){
	                    $arrayDatos = array((OP_VENTA.$nro_ope.$row->fecha_registro.CTA_PROPIA),
                	                        ($row->monto.HABER.SOLES.TIPO_CAMBIO.$row->aux_monto.$row->correlativo),
                	                        ($row->fecha_registro),
                	                        (($row->estado == ESTADO_ANULADO) ? $row->flg_cod_alumn : $row->cod_alumno_temp),
                	                        ('1002'),//CENTRO DE COSTO DE CADA SEDE
                	                        ('V'.$row->fecha_registro),
                	                        ($row->monto),
                	                        ('000000000.00'),
                	                        ($row->cod_alumno_temp.'2'.$row->estudiante),
                	                        ($row->desc_detalle_crono.PHP_EOL)
                	                    );
	                    $fila = $this->buildStringByArray($arrayDatos, $arrayColumn2);
	                } else{
	                    $arrayDatos = array((OP_VENTA.$nro_ope.$row->fecha_registro.CTA_SERV_PRESTADOS),
                	                        ($row->monto.HABER.SOLES.TIPO_CAMBIO.$row->aux_monto.$row->correlativo),
                	                        ($row->fecha_registro),
                	                        (($row->estado == ESTADO_ANULADO) ? $row->flg_cod_alumn : $row->cod_alumno_temp),
                	                        ('1002'),//CENTRO DE COSTO DE CADA SEDE
                	                        ('V'.$row->fecha_registro.$row->monto),
                	                        ($row->cod_alumno_temp.'2'.$row->estudiante),
                	                        ($row->desc_detalle_crono.PHP_EOL)
                	                    );
	                    $fila = $this->buildStringByArray($arrayDatos, $arrayColumn3);
	                }
	                fwrite($f, $fila);
	            }
	            $correlativoOpe++;
	        }
	    } else if($tipoTxt == 'a'){
	        $arrayColumn1        = array(1 , 25 , 71 , 81 , 96 , 115 , 185 , 235);
	        $arrayColumn2        = array(1 , 25 , 71 , 81 , 96 , 115 , 185 , 235);
	        foreach($datos as $row){
	            $nro_ope = $this->getCorrelativoOperacion($correlativoOpe,4);
	            $row->cod_alumno_temp = $this->m_utils->getById('public.persona', 'cod_alumno_temp', 'nid_persona', $row->_id_persona, 'smiledu');
	            $fila = null;
	            if($row->estado == 'ANULADO'){
	                $arrayColumn1[3] = ($arrayColumn1[3]-2);
	                $arrayColumn2[3] = ($arrayColumn2[3]-2);
	            }
	            for($i = 1; $i <= 2; $i++){
	                if($i == 1){
	                    $arrayDatos = array((OP_ASIENTO.$nro_ope.$row->fecha_registro.CTA_10111),
                	                        ($row->monto.DEBE.SOLES.TIPO_CAMBIO_NONE.$row->aux_monto.$row->correlativo),
                	                        ($row->fecha_registro),
                	                        (($row->estado == ESTADO_ANULADO) ? $row->flg_cod_alumn : $row->cod_alumno_temp),
                	                        ('1002'),//CENTRO DE COSTO DE CADA SEDE
                	                        ($row->fecha_registro),
                	                        ($row->cod_alumno_temp.'2'.$row->estudiante),
                	                        ($row->desc_detalle_crono.PHP_EOL)
                	                    );
	                    $fila = $this->buildStringByArray($arrayDatos, $arrayColumn1);
	                } else if($i == 2){
	                    $arrayDatos = array((OP_ASIENTO.$nro_ope.$row->fecha_registro.CTA_POR_COBRAR),
                	                        ($row->monto.HABER.SOLES.TIPO_CAMBIO_NONE.$row->aux_monto.$row->correlativo),
                	                        ($row->fecha_registro),
                	                        (($row->estado == ESTADO_ANULADO) ? $row->flg_cod_alumn : $row->cod_alumno_temp),
                	                        ('1002'),//CENTRO DE COSTO DE CADA SEDE
                	                        ($row->fecha_registro),
                	                        ($row->cod_alumno_temp.'2'.$row->estudiante),
                	                        ($row->desc_detalle_crono.PHP_EOL)
                	                    );
	                    $fila = $this->buildStringByArray($arrayDatos, $arrayColumn2);
	                }
	                fwrite($f, $fila);
	            }
	            $correlativoOpe++;
	        }
	    }
	    
	    return array($f,$arrayDocumentos);
	}
	
	function buildStringByArray($arrayData, $arrayLimites){
	    $fila = null;
	    for($i = 0 ; $i < count($arrayLimites) ; $i++){
	        while($arrayLimites[$i] > (strlen($fila)+1)){
	            $fila .= ' ';
	        }
	        $fila = substr($fila, 0,$arrayLimites[$i]-1);
	        $fila .= $arrayData[$i];
	    }
	    return $fila;
	}
	
	function deleteTxtFile(){
	    $ruta = 'uploads/modulos/pagos/txt/file.txt';
	    if(file_exists($ruta)) {
	        $ruta = './'.$ruta;
	        if (!unlink($ruta)){
	            echo ("No se borrï¿½ el archivo $ruta");
	        }else{
	            echo ("Se borrï¿½ $ruta");
	        }
	    }
	}
	
	function buildTableEmpresasHTML(){
	    $empresas = $this->m_migracion->getAllEmpresas();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_empresas">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#');
	    $head_1 = array('data' => 'Empresa');
	    $head_2 = array('data' => 'Sedes');
	    $head_3 = array('data' => 'Exportar', 'class' => 'text-center');
	    $head_5 = array('data' => '&Uacute;ltima exportaci&oacute;n');

	    $this->table->set_heading($head_0,$head_1,$head_2,$head_5,$head_3);
	    $val = 1;
	    foreach($empresas as $row){
	        $sedes = array();
	        foreach(explode(',', $row->ids) as $id){
	            array_push($sedes, _encodeCI($id));
	        }
	        $sedes = json_encode($sedes);
	        $row_0 = array('data' => $val);
	        $row_1 = array('data' => $row->desc_empresa);
	        $row_2 = array('data' => $row->sedes);
	        $row_3 = array('data' => '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-sedes=\''.$sedes.'\' data-empresa="'._encodeCI($row->id_empresa).'" onclick="abrirModalExportarCont($(this))">
	                                      <i class="mdi mdi-file_upload"></i>
	                                  </button', 'class' => 'text-center');
	        $exportacion = $this->m_migracion->getLastExportacionByEmpresa($row->id_empresa);
	        $imgPersonaExport = null;
	        if($exportacion['fecha'] != null){
	        	$imgPersonaExport = '<img style="cursor:pointer" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$exportacion['persona'].'"></img>';
	        }
	        $row_5 = array('data' => ' '.$imgPersonaExport.$exportacion['fecha'], 'class' => 'img-table');
	        $this->table->add_row($row_0,$row_1,$row_2,$row_5,$row_3);
	        $val++;
	    }
	    return $this->table->generate();
	}
	
	function exportarDatosSiscont(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $logeoUsario = $this->_idUserSess;
	        $nombres     = _getSesion('nombre_usuario');
	        $tipoTxt     = _post('tipoTxt');
	        $mes         = _decodeCI(_post('mes'));
	        $year        = _post('year');
	        $empresa     = _decodeCI(_post('empresa'));
	        if(in_array($tipoTxt, array(1,0)) != 1){
	            throw new Exception('Seleccione una opci&oacute;n');
	        }
	        $arraySedes = __getArrayObjectFromArray(_post('arraySedes'));
	        if(count($arraySedes) == 0){
	            throw new Exception(ANP);
	        }
	        if($year == null){
	            throw new Exception('Seleccione un A&ntilde;o');
	        }
	        if($mes == null){
	            throw new Exception('Seleccione un mes');
	        }
	        if($empresa == null){
	            throw new Exception(ANP);
	        }
	        $datos = $this->m_migracion->getDataSiscont($arraySedes,$mes,$year);
	        if(count($datos) == 0){
	            throw new Exception('No hay datos a exportar');
	        }
	        $tipoTxt = ($tipoTxt == 0) ? 'v' : 'a';
	        //INSERT AUDITORIA
	        $newCorrelativo = $this->m_migracion->getCorrelativoByEmpresa($empresa);
	        $newCorrelativo = $this->getCorrelativoOperacion($newCorrelativo, 10);
	        $arrayInsertAudi = array('_id_empresa'    => $empresa,
                    	             'id_pers_regi'   => $logeoUsario,
                    	             'audi_pers_regi' => $nombres,
                    	             'correlativo'    => $newCorrelativo
                    	       );
	        $data = $this->m_migracion->insertAudiMigracion('audi_contabilidad',$arrayInsertAudi);
	        if($data['error'] == EXIT_SUCCESS){
	            $result           = $this->generateTxtFile($datos,$tipoTxt);
	            $data['filename'] = $result[0];
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se exportaron los datos';
	            $data['table']    = $this->buildTableEmpresasHTML();
	        }
	        //END INSERT
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deleteFile(){
	    $filename = 'uploads/modulos/pagos/txt/'.utf8_decode(_post('filename'));
	    if(file_exists($filename)) {
	        $filename = './'.$filename;
	        if (!unlink($filename)){
	            echo ("No se borr&oacute; el archivo $filename");
	        }else{
	            echo ("Se borrï¿½ $filename");
	        }
	    }
	    echo null;
	}
	
	function buildComboMeses(){
	    $meses = array(1 => 'Enero' , 2 => 'Febrero' , 3 => 'Marzo'    , 4  => 'Abril'    , 5  => 'Mayo'     , 6  => 'Junio',
	                   7 => 'Julio' , 8 => 'Agosto'  , 9 => 'Setiembre' , 10 => 'Octubre' , 11 => 'Noviembre', 12 => 'Diciembre');
	    $opt = null;
	    foreach($meses as $mes){
	        $mesNumber  = _encodeCI(array_search($mes,$meses));
	        $opt       .= '<option value="'.$mesNumber.'">'.$mes.'</option>';
	    }
	    return $opt;
	}
	
	function buildComboYear(){
	    $opt = null;
	    for($i = date('Y') ; $i > (date('Y')-6) ; $i--){
	        $opt .= '<option value="'.$i.'" '.(($i == date('Y')) ? 'selected' : null).'>'.$i.'</option>';
	    }
	    return $opt;
	}
	
// 	function buildTableMigracionBancosHTML(){
// 	    $tabla = null;
// 	    $tabla    .=  '<table id="" class="tree table">
// 	                       <tr >
//                                <td class="col-xs-5 text left p-l-20" style="border-top: none;">Descripci&oacute;n</td>
// 	                           <td class="col-sm-2 text-left" style="border-top: none;">Ultima importaci&oacute;n</td>
// 	                           <td class="col-sm-2 text-left" style="border-top: none;">Ultima exportaci&oacute;n</td>
//                                <td class="col-sm-2 text-center" style="border-top: none;">Acci&oacute;n</td>
//                            </tr>';
// 	    $empresas = $this->m_migracion->getAllEmpresas();
// 	    $sedes    = $this->m_utils->getSedes();
	    
// 	    $valNodo = 0;
// 	    $valAux  = null;
// 	    $exportar = 'exportar';
// 	    $importar = 'importar';
// 	    foreach($sedes as $sede){
// 	    	if($sede->nid_sede == 7){
// 	    		continue;
// 	    	}
// 	    	$lastBancoImportar = $this->m_migracion->getLastBanco(IMPORTAR, $sede->nid_sede);
// 	    	$lastBancoExportar = $this->m_migracion->getLastBanco(EXPORTAR, $sede->nid_sede);
// 	        $valNodo++;
// 	        $valAux = $valNodo;
// 	        $tabla .='<tr class="treegrid-'.$valNodo.'">
// 	                      <td class="text-left p-l-10 col-sm-3">'.$sede->desc_sede.'</td>
// 	                      <td class="text-left col-sm-3">'.$lastBancoImportar['desc_banco'].'</td>
// 	                      <td class="text-left col-sm-3">'.$lastBancoExportar['desc_banco'].'</td>
// 	                      <td class="text-center col-sm-3"></td>
//             	      </tr>';
// 	        $idSedeCrypt = _encodeCI($sede->nid_sede);
// 	        $bancos   = $this->m_migracion->getAllBancosActivosBySede($sede->nid_sede);
// 	        foreach($bancos as $banco){
// 	            $audiMigracionImport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$sede->nid_sede,IMPORTAR);
// 	            $audiMigracionExport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$sede->nid_sede,EXPORTAR);
// 	            $valNodo++;
// 	            //TEXTOS REFERENCIA
// 	            $textImport = '&#191;Desea importar los datos del banco '.$banco->abvr.' &#63;';
// 	            $textExport = '&#191;Desea exportar los datos del banco '.$banco->abvr.'  &#63;';
// 	            $textRefeEx = 'Se exportar&aacute;n los datos del banco '.$banco->abvr.' para la sede ' .$sede->desc_sede.'.'; 
// 	            $textRefeIm = 'Se importar&aacute;n los datos del banco '.$banco->abvr.' para la sede ' .$sede->desc_sede.'.';
// 	            //END
// 	            $idBancoCrypt = _encodeCI($banco->_id_banco);
// 	            $buttonImport = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-sedes=\''.$idSedeCrypt.'\' data-toggle="tooltip" 
//  	                                     data-placement="bottom" title="Importar" data-ref="'.$textRefeIm.'" data-text="'.$textImport.'" onclick="openModalImportar(\''.$idBancoCrypt.'\',\''.IMPORTAR.'\',$(this))">
//                                      <i class="mdi mdi-file_download"></i>
//                                  </button>';
// 	            $buttonExport = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-sedes=\''.$idSedeCrypt.'\' data-toggle="tooltip" 
// 	                                     data-placement="bottom" title="Exportar" data-ref="'.$textRefeEx.'" data-text="'.$textExport.'" onclick="openModalMigracion(\''.$idBancoCrypt.'\',\''.EXPORTAR.'\',$(this))">
//                                      <i class="mdi mdi-file_upload"></i>
//             	                 </button>';
// 	            $imgPersonaImport = null;
// 	            $imgPersonaExport = null;
// 	            if($audiMigracionImport['fecha'] != null){
// 	                $imgPersonaImport = '<img style="cursor:pointer" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionImport['persona'].'"></img>';
// 	            }
// 	            if($audiMigracionExport['fecha'] != null){
// 	                $imgPersonaExport = '<img style="cursor:pointer" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionExport['persona'].'"></img>';
// 	            }
// 	            $tabla .='<tr class="treegrid-'.$valNodo.' treegrid-parent-'.$valAux.'">
// 	                          <td class="text-left p-l-5">'.$banco->desc_banco.'</td>
// 	                          <td class="text-left img-table">'.$imgPersonaImport.$audiMigracionImport['fecha'].'</label></td>
// 	                          <td class="text-left img-table">'.$imgPersonaExport.$audiMigracionExport['fecha'].'</td>
// 	                          <td class="text-center" style="display: flex">'.$buttonImport.$buttonExport.'</td>
//             	          </tr>';
// 	        }
// 	    }
// 	    $tabla .= '</table>';
// 	    return $tabla;
// 	}

	function buildTableMigracionBancosHTML(){
	    $tabla = null;
	    //CABECERAS
	    $tabla    .=  '<table id="" class="tree table">
	                       <tr >
                               <td class="col-xs-5 text left p-l-20" style="border-top: none;">Descripci&oacute;n</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">&Uacute;ltima importaci&oacute;n</td>
	                           <td class="col-sm-2 text-left" style="border-top: none;">&Uacute;ltima exportaci&oacute;n</td>
                               <td class="col-sm-2 text-center" style="border-top: none;">Acci&oacute;n</td>
                           </tr>';
	    //EMPRESAS
	    $empresas = $this->m_migracion->getAllEmpresas();
	    
	    $valNodo = 0;
	    $valAux  = null;
	    $exportar = 'exportar';
	    $importar = 'importar';
	    foreach($empresas as $emp){
	        $lastBancoImportar = explode('|', $emp->last_import);
	        $lastBancoExportar = explode('|', $emp->last_export);
	        $valNodo++;
	        $valAux = $valNodo;
	        $imgBancoImport = null;
	        $imgBancoExport = null;
	        if(count($lastBancoImportar) > 1){
	            $imgBancoImport = '<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$lastBancoImportar[1]).'" data-toggle="tooltip" data-placement="bottom">
	                               </img>';
	        }
	        if(count($lastBancoExportar) > 1){
	            $imgBancoExport = '<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$lastBancoExportar[1]).'" data-toggle="tooltip" data-placement="bottom">
	                               </img>';
	        }
	        $tabla .='<tr class="treegrid-'.$valNodo.'">
	                      <td class="text-left p-l-10 col-sm-3">'.$emp->desc_empresa.' ('.$emp->sedes.')</td>
	                      <td class="text-left col-sm-3 img-table">'.$imgBancoImport./*$lastBancoImportar[0].*/'</td>
	                      <td class="text-left col-sm-3 img-table">'.$imgBancoExport./*$lastBancoExportar[0].*/'</td>
	                      <td class="text-center col-sm-3"></td>
            	      </tr>';
            $arraySedes = array();
	        foreach(explode(',', $emp->ids) as $idSede){
	            array_push($arraySedes, _encodeCI($idSede));
	        }
	        //BANCOS POR EMPRESA
	        $bancos   = $this->m_migracion->getAllBancosActivosBySede(explode(',', $emp->ids));
	        foreach($bancos as $banco){
	            $audiMigracionImport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$emp->id_empresa,IMPORTAR, null, null);
	            $audiMigracionExport = $this->m_migracion->getLastMigracionByBancoSede($banco->_id_banco,$emp->id_empresa,EXPORTAR, null, null);
	            $valNodo++;
	            //TEXTOS REFERENCIA
	            $textImport = '&#191;Desea importar los datos del banco '.$banco->abvr.' &#63;';
	            $textExport = '&#191;Desea exportar los datos del banco '.$banco->abvr.'  &#63;';
	            $textRefeEx = 'Se exportar&aacute;n los datos del banco '.$banco->abvr.' para la empresa ' .$emp->desc_empresa.'.';
	            $textRefeIm = 'Se importar&aacute;n los datos del banco '.$banco->abvr.' para la empresa ' .$emp->desc_empresa.'.';
	            //END
	            //BOTONES DE ACCION
	            $idBancoCrypt = _encodeCI($banco->_id_banco);
	            $buttonImport = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-empresa=\''._encodeCI($emp->id_empresa).'\' data-sedes=\''.json_encode($arraySedes).'\' data-toggle="tooltip"
 	                                     data-placement="bottom" title="Importar" data-ref="'.$textRefeIm.'" data-text="'.$textImport.'" onclick="openModalImportar(\''.$idBancoCrypt.'\',\''.IMPORTAR.'\',$(this))">
                                     <i class="mdi mdi-file_download"></i>
                                 </button>';
	            $buttonExport = '<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-empresa=\''._encodeCI($emp->id_empresa).'\' data-sedes=\''.json_encode($arraySedes).'\' data-toggle="tooltip"
	                                     data-placement="bottom" title="Exportar" data-ref="'.$textRefeEx.'" data-text="'.$textExport.'" onclick="openModalMigracion(\''.$idBancoCrypt.'\',\''.EXPORTAR.'\',$(this))">
                                     <i class="mdi mdi-file_upload"></i>
            	                 </button>';
	            //-----------------
	            $imgPersonaImport = null;
	            $imgPersonaExport = null;
	            if($audiMigracionImport['fecha'] != null){
	                $imgPersonaImport = '<img style="cursor:pointer" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionImport['persona'].'"></img>';
	            }
	            if($audiMigracionExport['fecha'] != null){
	                $imgPersonaExport = '<img style="cursor:pointer" src="'.RUTA_SMILEDU.'public/general/img/profile/nouser.svg" data-toggle="tooltip" data-placement="bottom" title="'.$audiMigracionExport['persona'].'"></img>';
	            }
	            $idBanco = $banco->_id_banco;
	            $imgBanco ='<img style="cursor:pointer" class="img-banco" src="'.RUTA_IMG.'bancos/'.(json_decode(IMAGENES_BANCO_ID)->$idBanco).'" data-toggle="tooltip" data-placement="bottom"> 
	                         </img>';
	            $tabla .='<tr class="treegrid-'.$valNodo.' treegrid-parent-'.$valAux.'">
	                          <td class="text-left p-l-5 img-table">'.$imgBanco./*$banco->abvr.*/'</td>
	                          <td class="text-left img-table">'.$imgPersonaImport.$audiMigracionImport['fecha'].'</label></td>
	                          <td class="text-left img-table">'.$imgPersonaExport.$audiMigracionExport['fecha'].'</td>
	                          <td class="text-center" style="display: flex">'.$buttonImport.$buttonExport.'</td>
            	          </tr>';
	        }
	    }
	    $tabla .= '</table>';
	    return $tabla;
	}
	//////////EXPORTACION-DE-BANCOS///////////////////////
	function migrarDatosBanco(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	//se recepciona los datos mandados desde el js por metodo post
	        $id_banco     = _decodeCI(_post('id_banco'));
	        $accion       = _post('accionM');
	        $sede         = _post('sedes');
	        $id_empresa   = _decodeCI(_post('id_empresa'));
	        //se realizan las validaciones respectivas
	        if($accion != IMPORTAR && $accion != EXPORTAR){
	            throw new Exception(ANP);
	        }
	        $sede         = __getArrayObjectFromArray($sede);
	        //se extrae el id de la empresa al que pertenece la sede
// 	        $id_empresa   = $this->m_utils->getById('empresa_x_sede', '_id_empresa', '_id_sede', $sede);
	        $arrayInsert  = array();
	        $fecha_actual = date("Ymd");
	        //se extrae el nombre de la sede
			$nameEmpresa  = $this->stripAccents($this->m_utils->getById('empresa','desc_empresa','id_empresa',$id_empresa));
	        $filename     = null;
	        $file         = null;
	        //se toma el id_persona del usuario en session
	        $logeoUsario  = $this->_idUserSess;
	        //se toma el nombre_completo del usuario en session
	        $nameUsario   = _getSesion('nombre_completo');
	        //aqui se divide segun el banco seleccionado para realizar la exportacion
	        if($id_banco == BANCO_BANBIF){
	        	//se crea el nombre del archivo a exportar
	            $filename = "BANBIF-".$nameEmpresa."-".$fecha_actual.".txt";
	            //se crea el archivo txt
	            $file     = fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	            //se procede a llenar el txt con los datos necesarios segun la parametrica de cada banco
	        	$data = $this->buildTxtBANBIF($id_empresa,$sede,$id_banco,$accion,$file);
	        	//se extrae el correlativo de la cantidad de veces que se haya realizado la exportacion transformandolo en una cadena de 10 caracteres
	        	$num_correlativo  = $this->getCorrelativoOperacion(($this->m_migracion->getCorrelativoByBanco($id_banco,$id_empresa) + 1), 10);
	        	//se arma el array para insetar en la auditoria documento
	        	$arrayInsert 		= array('correlativo'   => $num_correlativo,
                        	        	   '_id_banco'      => $id_banco,
                        	        	   'id_pers_regi'   => $logeoUsario,
	        	                           '_id_empresa'     => $id_empresa,
                        	        	   'audi_pers_regi' => $nameUsario,
                        	        	   'accion'         => $accion);
	        	//si esque no se encontraron datos al realizar buildTxtBANBIF se notificara lo ocurrido
	        	if($data['msj'] == 'No hay datos para exportar'){
	        		throw new Exception("No hay datos para exportar");
	        	}
	        } else if($id_banco == BANCO_BBVA){
	        	//se extrae el ultimo correlativo de exportacion del BANCO_BBVA  en el dia
	        	$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa);
	        	//se valida si ya supero el limite de exportacion luego de exceder el limite el $num_corre siempre sera igual a 8
	        	$num_corre  = ($num_correlativo >= LIMITE_CORRELATIVO_X_DIA) ? LIMITE_CORRELATIVO_X_DIA : $num_correlativo;
	        	//se transforma en una cadena de 3 digitos para añadirlos en el nombre del archivo
	        	$correlativo 	= $this->getCorrelativoOperacion($num_correlativo, 3);
	        	//se transforma en una cadena de 3 digitos para usarlos en la generacion del archivo txt
	        	$num_corre      = $this->getCorrelativoOperacion($num_corre, 3);
	        	//se crea el nombre del archivo txt
	        	$filename 		= "BBVA-".$nameEmpresa."-".$fecha_actual."-".$correlativo.".txt";
	        	//se crea el archivo txt
	        	$file 			= fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	        	//se registra los datos segun la parametrica del BANCO_BBVA en el txt
	        	$data = $this->buildTxtBBVA($id_empresa,$sede,$id_banco,$accion, $file, $num_corre);
	        	//si esque no se encontraron datos al realizar buildTxtBBVA se notificara lo ocurrido
	        	if($data['msj'] == 'No hay datos para exportar'){
	        		throw new Exception("No hay datos para exportar");
	        	}
	        	// se transforma $num_correlativo en una cadena de 10 caracteres pero aumentado en 1 ya que en audi banco se empieza en 1 y no en 0
	        	$corre_audi_banco 	= $this->getCorrelativoOperacion($num_correlativo + 1, 10);
	        	//se crear el array insert para audi banco
	        	$arrayInsert 		= array('correlativo'     => $corre_audi_banco,
	        								'_id_banco'       => $id_banco,
	        								'id_pers_regi'    => $logeoUsario,
	        	                            '_id_empresa'     => $id_empresa,
	        								'audi_pers_regi'  => $nameUsario,
	        								'accion'          => $accion);
	        } else if($id_banco == BANCO_BCP){
	        	//se crea el nombre del archivo txt
	            $filename = "BCP-".$nameEmpresa."-".$fecha_actual.".txt";
	        	//se crea el archivo txt
	            $file     = fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	        	//se registra los datos segun la parametrica del BANCO_BCP en el txt
	            $data     = $this->buildTxtBCP($id_empresa,$sede,$id_banco,$accion,$file);
	        	//si esque no se encontraron datos al realizar buildTxtBBVA se notificara lo ocurrido
	            if($data['msj'] == 'No hay datos para exportar'){
	            	throw new Exception("No hay datos para exportar");
	            }
	            // se transforma $num_correlativo en una cadena de 10 caracteres pero aumentado en 1 ya que en audi banco se empieza en 1 y no en 0
	        	$num_correlativo  = $this->getCorrelativoOperacion(($this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa) + 1), 10);
	            //se crear el array insert para audi banco
	        	$arrayInsert      = array('correlativo'     => $num_correlativo,
                        	              '_id_banco'       => $id_banco,
                        	              'id_pers_regi'    => $logeoUsario,
	        	                           '_id_empresa'     => $id_empresa,
                        	              'audi_pers_regi'  => $nameUsario,
                        	              'accion'          => $accion);
	        }else if($id_banco == BANCO_SCOTIA){
	        	//se crea el nombre del archivo txt
	        	$filename 		= "SCOTIABANK-".$nameEmpresa."-".$fecha_actual.".txt";
	        	//se crea el archivo txt
	        	$file     = fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	        	//se registra los datos segun la parametrica del BANCO_SCOTIA en el txt
	        	$data =$this->buildTxtSCOTIA($id_empresa,$sede,$id_banco,$accion, $file);
	        	//si esque no se encontraron datos al realizar buildTxtSCOTIA se notificara lo ocurrido
	        	if($data['msj'] == 'No hay datos para exportar'){
	        		throw new Exception("No hay datos para exportar");
	        	}
	        	// se transforma $num_correlativo en una cadena de 10 caracteres pero aumentado en 1 ya que en audi banco se empieza en 1 y no en 0
	        	$num_correlativo  = $this->getCorrelativoOperacion(($this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa) + 1), 10);
	            //se crear el array insert para audi banco
	        	$arrayInsert 		= array('correlativo'     => $num_correlativo,
	        								'_id_banco'       => $id_banco,
	        								'id_pers_regi'    => $logeoUsario,
	        	                            '_id_empresa'     => $id_empresa,
	        								'audi_pers_regi'  => $nameUsario,
	        								'accion'          => $accion);
	        } else if($id_banco == BANCO_COMERCIO){
	        	//se crea el nombre del archivo txt
	            $filename 		= "COMERCIO-".$nameEmpresa."-".$fecha_actual.".txt";
	        	//se crea el archivo txt
	            $file           = fopen("./uploads/modulos/pagos/txt/".$filename, "w");
	        	//se registra los datos segun la parametrica del BANCO_COMERCIO en el txt
	            $data =$this->buildTxtComercio($id_empresa,$sede,$id_banco,$accion, $file);
	        	//si esque no se encontraron datos al realizar buildTxtSCOTIA se notificara lo ocurrido
	            if($data['msj'] == 'No hay datos para exportar'){
	            	throw new Exception("No hay datos para exportar");
	            }
	            // se transforma $num_correlativo en una cadena de 10 caracteres pero aumentado en 1 ya que en audi banco se empieza en 1 y no en 0
	        	$num_correlativo  = $this->getCorrelativoOperacion(($this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa) + 1), 10);
	            //se crear el array insert para audi banco
	        	$arrayInsert 		= array('correlativo'    => $num_correlativo,
                        	                '_id_banco'      => $id_banco,
                        	                'id_pers_regi'   => $logeoUsario,
	        	                            '_id_empresa'    => $id_empresa,
                        	                'audi_pers_regi' => $nameUsario,
                        	                'accion'         => $accion);
	        }
	        //se inserta en la tabla audi movimiento
	        $data = $this->m_migracion->insertAudiMigracion('audi_banco', $arrayInsert);
	        //se guarda el nombre del archivo en data para mandarlo al js
	        $data['nombre'] = utf8_encode($filename);
	        //se reconstruye la tabla
	        $data['tabla'] = $this->buildTableMigracionBancosHTML();
	    } catch(Exception $e){
	        //se guarda el nombre del archivo en data para mandarlo al js
	    	$data['nombre'] = $filename;
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//////////EXPORTACION-BANBIF///////////////////////
	function buildTxtBANBIF($idEmpresa,$sedes,$idBanco,$accion,$file){
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;
		try{
			//se extrae el formato de exportacion de la bd
			$formato = $this->m_utils->getById('pagos.banco', (($accion == EXPORTAR)  ? 'formato_exportar' : 'formato_importar'), 'id_banco', $idBanco, 'pagos');
			//se retira las comas y se convierte a un array separado por cada coma encontrada dentro de $formato
			$arrayLimites = explode(',', $formato);
			//se trae los datos necesarios para llenar el txt
		    $datos   = $this->m_migracion->getDataMigracionBanco($sedes);
		    $dataNom = array();
		    $nro_operacion = 1;
		    $nombres = array();
		    if($datos != null){
			    foreach($datos as $row){
			        $codAlu = $this->getCodAluExport($row->cod_alumno_temp);
// 			        $codAlu = (substr($row->cod_alumno_temp,0,1) == 2) ? substr_replace($row->cod_alumno_temp,"5",0,1) : $row->cod_alumno_temp; 
			        $correIni       = $codAlu.$row->fecha_vencimiento;
			        $correlativoMov = $this->getCorrelativoOperacion($correIni, 20);
			        $arrayData = array($correlativoMov,
			                           $codAlu,
			                           $row->ape_pate_pers,
			                           $row->ape_mate_pers,
			                           $row->nom_persona,
			                           CODIGO_GRUPO_BANBIF,
			                           '00000000',//FECHA EMISION NO OBLIGATORIO
			                           $row->fecha_vencimiento,
			                           $row->moneda,
			                           '',//NO OBLIGATORIO
			                           $row->desc_detalle_crono,
			                           '',//NO OBLIGATORIO
			                           $row->flg_mora,
			                           CODIGO_BANBIF,//CODIGO CONCEPTO 1
			                           $row->monto,
			                           CONCEPTO_BANBIF,
			                           IMPORTE_BANBIF,
		                	           CONCEPTO_BANBIF,
		                	           IMPORTE_BANBIF,
		                	           CONCEPTO_BANBIF,
		                	           IMPORTE_BANBIF,
		                	           CONCEPTO_BANBIF,
		                	           IMPORTE_BANBIF,
		                	           CONCEPTO_BANBIF,
		                	           IMPORTE_BANBIF.PHP_EOL);
			        $fila = $this->buildStringByArray($arrayData, $arrayLimites);
			        fwrite($file, $fila);
			        $nro_operacion++;
			    }
			}else{
				throw new Exception("No hay datos para exportar");
			}
		}catch(Exception $e){
			$data['msj']   = $e->getMessage();
		}
		return $data;
	}

	//////////EXPORTACION-BBVA///////////////////////
	function buildTxtBBVA($idEmpresa,$sedes,$idBanco,$accion, $file, $correlativo){
		$data['msj'] = null;
		try{
			//se extrae el formato de exportacion de la bd
			$formato 		 = $this->m_utils->getById('pagos.banco', (($accion == EXPORTAR)  ? 'formato_exportar' : 'formato_importar'), 'id_banco', $idBanco, 'pagos');
			//se retira las comas y se convierte a un array separado por cada coma encontrada dentro de $formato
			$arrayLimites 	 = explode(',', $formato);
			//se trae los datos necesarios para llenar el txt
		    $array 			 = $this->m_migracion->getDatosBBVA($sedes);
		    //aqui se encuentra todos los datos extraidos de la bd
			$arrayData 		 = $array[0];
			//aquie se encuentra la cantidad de registros que muestra la bd
			$num_row 		 = $array[1];
			//fecha actual segun el formato solicitado
			$fecha_actual	 = date("Ymd");
			//retorna el codigo de clase segun la empresa a la que pertenece la sede
			$codigoClase     = $this->m_migracion->getCodClase($idEmpresa);
			//se pasa $codigoClase que es un array a solo retornar el dato necesario
			$codigo = $codigoClase['0']['cod_clase'];
			//se valida que se haya encontrado el codigo de clase ya que si no se encuentra el metodo getCodClase retornara -1
			if($codigo < 0){
				throw new Exception("Empresa no encontrada");
			}
			//se extrae el ruc de la empresa
			$ruc 			 = $this->m_utils->getCamposById('public.empresa',array('ruc'),'id_empresa',$idEmpresa,'smiledu');
			//se construye el un array el encabezado respetando el orden de la parametrica
			$encabezado 	 = array('01',$ruc['ruc'],$codigo, TIPO_MONEDA_SOLES,$fecha_actual,$correlativo,' ',''.PHP_EOL);
			//se crea un array con las ubicaciones de cada elemento dentro una determina columna del txt segun la parametrica
			$arrayEncabezado = array(1,3,14,17,20,28,31,360);
			// se crea la cadena a insertar en el txt
			$fila 			 = $this->buildStringByArray($encabezado, $arrayEncabezado);
			//se escribe en el txt
			fwrite($file, $fila);
		    //en la varable $totalmonto se guardara la cantidad total 
			$totalmonto=null;
			$montoAux = 0;
			if($arrayData != null){
				foreach ($arrayData as $row){
					//se retira la coma del nombre completo del usuario en session
					//se trae informacion necesaria del alumno
					$detalles 	= $this->m_utils->getCamposById('public.persona',array('nro_documento','nom_persona','ape_pate_pers','ape_mate_pers'),'nid_persona',$row->_id_persona,'smiledu');
					$codAlumno  = $this->getCodAluExport($this->m_utils->getById('sima.detalle_alumno', 'cod_alumno_temp', 'nid_persona', $row->_id_persona));
					$nro_dni    = $detalles['nro_documento'];
// 					if(substr($codAlumno,0,1) == 2){
// 					    $cod_alumno = substr_replace($codAlumno,"5",0,1);
// 					} else if(substr($codAlumno,0,1) == 1){
// 					    $cod_alumno = substr_replace($codAlumno,"1",0,1);
// 				    } else if(substr($codAlumno,0,1) == 2){
// 				        $cod_alumno = substr_replace($codAlumno,"1",0,1);
// 			        } else if(substr($codAlumno,0,1) == 2){
				            
// 		            } else if(substr($codAlumno,0,1) == 2){
		                
// 	                } else if(substr($codAlumno,0,1) == 2){
	                    
// 	                } 
					
					$cod_alumno = (substr($codAlumno,0,1) == 2) ? substr_replace($codAlumno,"5",0,1) : $codAlumno;
					$nombre 	= $detalles['ape_pate_pers'].' '.$detalles['ape_mate_pers'].' '.$detalles['nom_persona'];
					$array = array(1,15);
					$array1 = array(1,26);
					//se transforma el $cod_alumno en una cadena de 15 caracteres
					$cod_alumno = $this->buildStringByArray(array($cod_alumno,''), $array);
					//se transforma el $concepto en una cadena de 26 caracteres
					$concepto   = $this->buildStringByArray(array($row->desc_detalle_crono,''), $array1);
					//se construye el array del cuerpo del txt respetando el orden de la parametrica
					$cuerpo     = array('02',$row->nombres,$cod_alumno.$row->fecha_vencimiento.$concepto,$row->fecha_vencimiento,$row->fecha_bloqueo,
										$row->periodo,$row->monto,$row->monto,SECCION_BANCO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										COD_SUB_CONCEPTO,VAL_SUB_CONCEPTO,
										CUENTA_CLIENTE,
										'','',' ',''.PHP_EOL);
					$totalmonto = $row->total;
					// se crea la cadena a insertar en el txt
					$fila 		= $this->buildStringByArray($cuerpo, $arrayLimites);
					//se escribe en el txt
					fwrite($file, $fila);
					$montoAux = $montoAux + floatval($row->monto_aux);
				}
			}else {
				throw new Exception("No hay datos para exportar");
			}
			$totalmonto = str_replace('.', '', number_format($montoAux, 2, '.', ''));
			$num_row = __generateFormatString($num_row,9);
			//si el $totalmonto ah retornado null se remplaza por una cadena de 0 tantas veces como la parametrica lo indica
			$totalmonto  = __generateFormatString($totalmonto,18);
			//se construye el array del cuerpo del txt respetando el orden de la parametrica
			$total      = array('03',$num_row,$totalmonto,$totalmonto,ADICIONALES,' ','');
			//se crea un array con las ubicaciones de cada elemento dentro una determina columna del txt segun la parametrica
			$arrayTotal = array(1,3,12,30,48,66,360);
			// se crea la cadena a insertar en el txt
			$fila = $this->buildStringByArray($total, $arrayTotal);
			//se escribe en el txt
			fwrite($file, $fila);
		}catch(Exception $e){
			$data['msj']   = $e->getMessage();
		}
		return $data;
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
	
	function upload_txt(){
// 		$config['upload_path'] = './uploads/modulos/pagos/txt/';
// 		$config['allowed_types'] = 'txt';
// 		$config['max_size']     = '100';
// 		$config['max_width'] = '1024';
// 		$CONFIG['MAX_HEIGHT'] = '768';
// 		$this->load->library('upload', $config);
// 		$field_name = "pgadmin.txt";
// 		$this->upload->do_upload($field_name);
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        if(empty($_FILES['userfile']['name'])){
	            throw new Exception('Seleccione una foto');
	        }
// 	        $ext = pathinfo('nameprueba');
	        $config['upload_path'] = './uploads/modulos/pagos/txt';
	        $config['allowed_types'] = 'txt';	        
	        $config['max_size']   = '100';	        
	        $config['max_width']  = '1024';	        
	        $config['max_height'] = '768';
	        $file_name            = __generateRandomString();
	        $config['file_name']  = $file_name;
	        $this->load->library('upload', $config);
	        if (!$this->upload->do_upload('userfile',$config)){
	            $this->session->unset_userdata('importacion');
	            throw new Exception(utf8_decode($this->upload->display_errors())  );
	        }
	        $data['namefile'] = $file_name.'.txt';
	        $data['error'] = EXIT_SUCCESS;
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	     echo json_encode(array_map('utf8_encode', $data));
	}
	////Este metodo permite insertar cualquier valor a un string en una determinada posicion////
	function insertar($valor,$posicion,$array){
		// Si la posición indicada es superior al los valores del array o inferior a 0
		if($posicion>count($array) || $posicion<0){
			return false;
		}
	
		// si la posicion es la misma que la cantidad de valores, lo añadiremos al
		// final del array que hemos recibido
		if($posicion==count($array)){
			$array[]=$valor;
			return $array;
		}
	
		$nuevoArray=array();
	
		// Recorremos todo el array de valores y añadimos el valor en la posicion
		// indicada
		for($i=0;$i<count($array);$i++){
	
			// En el momento que coincide, se añade el valor
			if($i==$posicion)
				$nuevoArray[]=$valor;
	
			$nuevoArray[]=$array[$i];
		}
		// Devolvemos el array modificado
		return $nuevoArray;
	}
	//////////IMPORTACION-DE-BANCOS///////////////////////
	function update_migracion_banco(){
		//se obtiene las variables enviadas desde el js por metodo post
		$id_banco   = _decodeCI(_post('id_banco'));
		$accion     = _post('accionM');
		$id_empresa = _decodeCI(_post('id_empresa'));
		$arraySedes = _post('arraySedes');
		$name_file  = _post('namefile');
		$arraySedes = __getArrayObjectFromArray($arraySedes);
		$name_file  = str_replace(' ', '_', $name_file);
		$file       = read_file("./uploads/modulos/pagos/txt/".$name_file);
		$data = null;
		if($id_banco == BANCO_BBVA){
			$data = $this->importacionBBVA($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file);
		}else if($id_banco == BANCO_SCOTIA){
			$data = $this->importacionSCOTIA($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file);
		}else if($id_banco == BANCO_COMERCIO){
			$data = $this->importacionCOMERCIO($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file);
		}else if($id_banco == BANCO_BCP){
			$data = $this->importacionBCP($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file);
		}else if($id_banco == BANCO_BANBIF){
			$data = $this->importacionBANBIF($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file);
		}
		$movimientos      = isset($data['movimientos']) ? $data['movimientos'] : array();
		if(count($movimientos ) > 0){
		  $result           = $this->m_migracion->getMovimientosUpdMigracion($movimientos);
		  $data['tableUpd'] = $this->buildTablePreviewMigrar($result);
		}
		$data['namefile'] = $name_file;
	    $data['tabla']    = $this->buildTableMigracionBancosHTML();
		unset($data['movimientos']);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	//////////IMPORTACION-BBVA///////////////////////
	function importacionBBVA($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file){
	    $archivo = fopen("./uploads/modulos/pagos/txt/".$name_file,'r');
		$array_file = array();
		$i = 0;
		while(!feof($archivo))
		{
			$array_file[$i] = fgets($archivo);
			$i++;
		}
		if(trim($array_file[count($array_file)-1]) == ''){
			unset($array_file[count($array_file)-1]);
		}
		$encabezado            = str_split($array_file[0]);
		$total                 = str_split($array_file[count($array_file)-1]);
		$eli                   = array_shift($array_file);
		$eli1                  = array_pop($array_file);
		$arrayEncabezado       = array(1,3,14,17,20,28,46,153);
		$arrayTotal            = array(1,3,12,27,42,57,153);
		$encabezadoGeneral     = $this->extracString($arrayEncabezado, $encabezado);
		$totalGeneral          = $this->extracString($arrayEncabezado, $total);
		$data = null;
		$data['error']         = EXIT_ERROR;
		$data['msj']           = null;
		try{
			$formato 		    = $this->m_utils->getById('pagos.banco', (($accion == IMPORTAR)  ? 'formato_importar' : 'formato_exportar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	    = explode(',', $formato);
			$arrayMovUpdate     = array();
			$arrayAudiMovInsert = array();
			$arrayDocumentos    = array();
			$arrayGeneralCorre  = array();
			$duplicadosGeneral  = array();
			$arrayUpdDetaAlu    = array();
			$arrayIdMovsImport  = array(); 
			foreach ($array_file as $row){
				$subarray      = str_split($row);
				$cuerpoGeneral = $this->extracString($arrayLimites, $subarray);
				$codigoAlumno  = $cuerpoGeneral[2];
				$codigoAlumno  = $this->getCodAlu($codigoAlumno);
				$detalleAlumno = $this->m_migracion->getSedeByAlumno($codigoAlumno, $cuerpoGeneral[1]);
				$sedes = explode(',', $detalleAlumno['nid_sede']);
				if(!in_array(2, $sedes)){
				    continue;
				}
				$fecha         = substr($cuerpoGeneral[3],0,4).'-'.substr($cuerpoGeneral[3],4,2).'-'.substr($cuerpoGeneral[3],6,2);
				$year          = substr($cuerpoGeneral[3],0,4);
				if(!is_numeric($year)){
				    throw new Exception('Archivo no reconocido');
				}
				$movmiento = $this->m_migracion->getMovCuotaIngresoByAlumno($fecha,$codigoAlumno);
				if(!isset($movmiento['id_movimiento'])){
				    $cuota = explode(',', $this->m_migracion->getCuotaByFecha($fecha, $sedes, $year));
				    if($cuota == null){
				        throw new Exception('No se encontro la cuota');
				    }
				    $movmiento        = $this->m_migracion->getMovimientoByAlumno($detalleAlumno['nid_persona'], $sedes, $cuota, $year);
				    if(!isset($movmiento['id_movimiento'])){
				        throw new Exception('Hubo un problema con el alumno '.$cuerpoGeneral[1] );
				    }   
				}
				$flgUpt = $this->m_migracion->flgUpdateDetalleAlumno($detalleAlumno['nid_persona'],$movmiento['id_movimiento']);
				if($flgUpt['count'] != 0){
				    $subArrDetaAlu  = array('nid_persona'   => $detalleAlumno['nid_persona'],
                    				        'estado'        => (($flgUpt['estado'] == ALUMNO_PREREGISTRO) ? ALUMNO_REGISTRADO : ALUMNO_PROM_REGISTRO)
                    				    );
				    array_push($arrayUpdDetaAlu, $subArrDetaAlu);
				}
				$monto_pago = array();
				$monto_pago = str_split(trim($cuerpoGeneral[6]));
				for ($i=0; $i < count($monto_pago);){
					if($monto_pago[$i]== 0){
						array_shift($monto_pago);
					}else{
						break;
					}
				}
				$monto_pago = $this->insertar('.', count($monto_pago)-2, $monto_pago);
				$monto_pago = implode('', $monto_pago);
				array_push($arrayIdMovsImport, $movmiento['id_movimiento']);
				if($movmiento['estado'] != ESTADO_PAGADO){
					$correDocumento = null;
// 					if($arrayGeneralCorre == null){
// 						$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 					}else{
// 						$flg_else = true;
// 						for ($i=0; $i < count($arrayGeneralCorre); $i++){
// 							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
// 								$correDocumento = $arrayGeneralCorre[$i]['numero_correlativo'];
// 								$flg_else = false;
// 								break;
// 							}
// 						}
// 						if($flg_else == true){
// 							$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 						}
// 					}
					$fechaPago        = substr($encabezadoGeneral[4],0,4).'-'.substr($encabezadoGeneral[4],4,2).'-'.substr($encabezadoGeneral[4],6,2);
					$correlativo      = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
					$correlativoByMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
					$caja             = $this->m_caja->getLastCajaBySede($detalleAlumno['sede_actual'],null);
					$subArrayUpdate   = array('id_movimiento'   => $movmiento['id_movimiento'],
											  'estado'          => ESTADO_PAGADO,
											  'fecha_pago'      => $fechaPago,
											  'monto_final'     => 0,
											  'monto_adelanto'  => $monto_pago,
					                          'desc_lugar_pago' => LUGAR_PAGO_BANCO,
					                          'flg_lugar_pago'  => FLG_BANCO,
					                          '_id_banco_pago'  => BANCO_BBVA,
					                          'desc_banco_pago' => BBVA
					);
					$subArrayInsert   = array('_id_movimiento' => $movmiento['id_movimiento'],
											  'correlativo'    => $correlativoByMov,
											  'accion'         => PAGAR,
											  'monto_pagado'   => $monto_pago,
											  '_id_sede'       => $detalleAlumno['sede_actual'],
					                          '_id_caja'       => $caja['id_caja']
					                         );
					/*$arrayRecibo      = array('_id_movimiento' => $movmiento['id_movimiento'],
											  'tipo_documento' => DOC_RECIBO,
											  'nro_serie'      => SERIE_DEFAULT,
											  'nro_documento'  => $correlativo,
											  '_id_sede'       => $detalleAlumno['nid_sede'],
											  'flg_impreso'    => 0,
											  'estado'         => ESTADO_CREADO,
											  'num_corre'      => $correlativoByMov);*/
// 					if($arrayGeneralCorre == null){
// 						$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
// 								'tipo_documento' => DOC_RECIBO,
// 								'tipo_movimiento' => MOV_INGRESO,
// 								'nro_serie'      => SERIE_DEFAULT,
// 								'numero_correlativo' => $correDocumento + 1,
// 												'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
// 						array_push($arrayGeneralCorre, $arrayCorre);
// 					}else{
// 						$flg_else = true;
// 						for ($i=0; $i < count($arrayGeneralCorre); $i++){
// 							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
// 								$arrayGeneralCorre[$i]['numero_correlativo'] = $correDocumento+1;
// 								$flg_else = false;
// 								break;
// 							}
// 						}
// 						if($flg_else == true){
// 							$arrayCorre = array('_id_sede'       => $detalleAlumno['nid_sede'],
// 												'tipo_documento' => DOC_RECIBO,
// 												'tipo_movimiento' => MOV_INGRESO,
// 												'nro_serie'      => SERIE_DEFAULT,
// 												'numero_correlativo' => $correDocumento + 1,
// 												'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
// 							array_push($arrayGeneralCorre, $arrayCorre);
// 						}
// 					}
					array_push($arrayMovUpdate, $subArrayUpdate);
					array_push($arrayAudiMovInsert, $subArrayInsert);
// 					array_push($arrayDocumentos, $arrayRecibo);
					$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa);
					$corre_audi_banco = $this->getCorrelativoOperacion($num_correlativo + 1, 10);
					$logeoUsario  = $this->_idUserSess;
					$nameUsario   = _getSesion('nombre_completo');
					$arrayAudiBanco  = array('correlativo'    => $corre_audi_banco,
											 '_id_banco'      => $id_banco,
											 'id_pers_regi'   => $logeoUsario,
											 '_id_empresa'    => $id_empresa,
											 'audi_pers_regi' => $nameUsario,
											 'accion'         => $accion);
				}else if($movmiento['flg_lugar_pago'] == FLG_COLEGIO){
					$logeoUsario = $this->_idUserSess;
					$nombre_registra = _getSesion('nombre_completo');
					$arrayDuplicados = array('_id_banco'      => $id_banco,
											 '_id_persona'    => $detalleAlumno['nid_persona'],
											 '_id_movimiento' => $movmiento['id_movimiento'],
											 'monto_pagado'   => $monto_pago,
											 'audi_pers_regi' => $nombre_registra);
					array_push($duplicadosGeneral, $arrayDuplicados);
				}
			}
			if($arrayMovUpdate != null && $arrayAudiMovInsert != null && $arrayAudiBanco != null){
			    $arrayTransactions = array('importacion' => 
			                                               array('updateMov'      => $arrayMovUpdate,
			                                                     'insertAudi'     => $arrayAudiMovInsert,
			                                                     'arrayAudiBanco' => $arrayAudiBanco,
			                                                     'duplicados'     => $duplicadosGeneral,
			                                                     'updateDetaAlu'  => $arrayUpdDetaAlu
			                                                    )
			                               );
			    $this->session->set_userdata($arrayTransactions);
			    $data['error'] = EXIT_SUCCESS;
// 				$data = $this->m_migracion->updateMigracion($arrayMovUpdate, $arrayAudiMovInsert/*, $arrayDocumentos, $arrayGeneralCorre*/, $arrayAudiBanco, $duplicadosGeneral,$arrayUpdDetaAlu);
			}else{
				$data['msj'] = 'La actualizaci&oacute;n no fue exitosa';
			}
			$arrNecesario = $this->session->userdata('importacion');
			$data['movimientos'] = $arrayIdMovsImport;
			$data['namefile']    = $name_file.'.txt';
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return $data;
	}
	function extracString($arrayLimit, $array){
		$arrayGeneral = array();
		for ($j=0; $j < count($arrayLimit); $j++){
			$stringPalabra = null;
			if(isset($arrayLimit[$j+1])){
				for ($i=($arrayLimit[$j]-1); $i < $arrayLimit[$j+1]-1; $i++){
					$stringPalabra .= $array[$i];
				}
				array_push($arrayGeneral, trim($stringPalabra));
			}
		}
		return $arrayGeneral;
	}
	function getCorrelativoReciboByMovimiento($correlativo){
		$lengthCorre = strlen($correlativo);
		$correlativoNew = null;
		for($i = $lengthCorre; $i < 8 ; $i++){
			$correlativoNew .= '0';
		}
		$correlativoNew .= $correlativo;
		return $correlativoNew;
	}
	//////////EXPORTACION-SCOTIABANK///////////////////////
	function buildTxtSCOTIA ($id_empresa,$arraySedes,$id_banco,$accion, $file){
		$data['msj'] = null;
		try{
			$formato 		 = $this->m_utils->getById('pagos.banco', (($accion == EXPORTAR)  ? 'formato_exportar' : 'formato_importar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	 = explode(',', $formato);
			$array 			 = $this->m_migracion->getDatosSCOTIA($arraySedes);
			$arrayData 		 = (($array[0] != null) ? $array[0] : '000000000000000');
			$num_row 		 = $array[1];
			$num_row         = $this->getCorrelativoOperacion($num_row, 7);
			$fecha_actual	 = date("Ymd");
			$ruc 			 = $this->m_utils->getCamposById('public.empresa',array('ruc'),'id_empresa',$id_empresa,'smiledu');
			$encabezado 	 = array('H', '111111100014', CODIGO_SCOTIA, $num_row, trim($arrayData['0']->total), '00000000000000000', $ruc['ruc'], $fecha_actual, $fecha_actual, '000', '900', TIPO_MORA, '00000000000', '00000000', '00000000000', TIPO_DESCUENTO, '00000000000', '00000000', '000', '', '*'.PHP_EOL);
			$arrayEncabezado = array(1,2,16,19,26,43,60,71,79,87,90,93,95,106,114,125,127,138,146,149,266);
			$fila 			 = $this->buildStringByArray($encabezado, $arrayEncabezado);
			fwrite($file, $fila);
			if($arrayData != '000000000000000'){
				foreach ($arrayData as $row){
					$nombre 	= str_replace(',', '', strtoupper(_getSesion('nombre_completo')));
					$detalles 	= $this->m_utils->getCamposById('public.persona',array('cod_alumno_temp'),'nid_persona',$row->_id_persona,'smiledu');
					$cod_alumno	= $detalles['cod_alumno_temp'];
					$array      = array(1,15);
					$array1     = array(1,30);
					$cod_alumno = $this->buildStringByArray(array($cod_alumno,''), $array);
					$concepto   = $this->buildStringByArray(array($row->desc_detalle_crono,''), $array1);
					$cuerpo     = array('D','111111100014','001',$cod_alumno,
										'',$ruc['ruc'],'0','0000',$nombre,$concepto,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										COD_SUB_CONCEPTO,IMPORTE_COBRAR,
										trim($row->monto),trim($row->monto),'00000000',
										'0',$fecha_actual,$row->fecha_vencimiento,
										'000','','*'.PHP_EOL);
					$fila 		= $this->buildStringByArray($cuerpo, $arrayLimites);
					fwrite($file, $fila);
				}
			}else{
				throw new Exception("No hay datos para exportar");
			}
			$total      = array('C','111111100014','001','01','TRANSFERENCIADEUDALDI','0','111111100014 ',' ','*');
			$arrayTotal = array(1,2,16,19,21,51,52,66,266);
			$fila = $this->buildStringByArray($total, $arrayTotal);
			fwrite($file, $fila);
		}catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
		return $data;
	}
	//////////EXPORTACION-BCP///////////////////////
	function buildTxtBCP($id_empresa,$id_sede,$id_banco,$accion, $file){
	    $data['msj'] = null;
		try{
			$formato 		 = $this->m_utils->getById('pagos.banco', (($accion == EXPORTAR)  ? 'formato_exportar' : 'formato_importar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	 = explode(',', $formato);
			$array 			 = $this->m_migracion->getDatosBCP($id_sede);
			$arrayData 		 = (($array[0] != null) ? $array[0] : '000000000000000');

			if($arrayData == '000000000000000'){
				throw new Exception("No hay datos para exportar");
			}
			$num_row 		 = $array[1];
			$num_row         = $this->getCorrelativoOperacion($num_row, 9);
			$datoEmpresa 	 = $this->m_utils->getCamposById('public.empresa',array('ruc', 'desc_empresa'),'id_empresa',$id_empresa,'smiledu');
			$encabezado 	 = array('CC', CODIGO_BCP, MONEDA_SOLES_BCP, 
									CUENTA_EN_BCP, 'C', $datoEmpresa['desc_empresa'], 
									trim($arrayData['0']->fecha_actual), $num_row, 
									trim($arrayData['0']->total), 'R', COD_SERVICIO_BCP, '', ''.PHP_EOL);
			$arrayEncabezado = array(1,3,6,7,14,15,55,63,72,87,88,94,250);
			$fila 			 = $this->buildStringByArray($encabezado, $arrayEncabezado);
			fwrite($file, $fila);
			$val = 0;
				foreach ($arrayData as $row){
				    $val++;
				    $caracteres_prohibidos = array("'","/","<",">",";");
				    $nombreCompleto = str_replace($caracteres_prohibidos,"",$row->nombre_completo);
				    $cod_alumno = $this->getCodAluExport($row->cod_alumno_temp);// (substr($row->cod_alumno_temp,0,1) == 2) ? substr_replace($row->cod_alumno_temp,"5",0,1) : $row->cod_alumno_temp;
					$cuerpo     = array('DD',CODIGO_BCP,MONEDA_SOLES_BCP,CUENTA_EN_BCP,
										$cod_alumno, $row->nombre_completo, 
										$nombreCompleto, $row->fecha_actual, 
										$row->fecha_vencimiento, $row->monto, $row->mora, 
										MONTO_MINIMO, 'R', DCT_PAGO_NUMERICO, 
										DCT_ID_NUMERICO,'',''.PHP_EOL);
					$fila 		= $this->buildStringByArray($cuerpo, $arrayLimites);
					fwrite($file, $fila);
				}
			
		}catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
		return $data;
	}
	//////////EXPORTACION-COMERCIO///////////////////////
	function buildTxtComercio($idEmpresa,$sedes,$idBanco,$accion,$file){
		$data['msj']   = null;
		try{
			$formato      = $this->m_utils->getById('pagos.banco', (($accion == EXPORTAR)  ? 'formato_exportar' : 'formato_importar'), 'id_banco', $idBanco, 'pagos');
		    $arrayLimites = explode(',', $formato);
		    $datos        = $this->m_migracion->getDataMigracionBanco($sedes);
		    $totalEnvio   = (isset($datos[0])) ? $datos[0]->total : '00000000000000';
		    $nombres = array();
		    $arrayData = array('T',
		                       $this->getCorrelativoOperacion(count($datos), 6),
		                       $totalEnvio,
		                       $this->getCorrelativoOperacion('', 6),
		                       $this->getCorrelativoOperacion('', 14),
		                       date('Ymd'),
		                       ''.PHP_EOL
		                      );
		    $arrayLimites2 = array(1,2,8,22,28,42,50);
		    $fila = $this->buildStringByArray($arrayData, $arrayLimites2);
		    fwrite($file, $fila);
		    $val = 0;
		    if($datos != null){
			    foreach($datos as $row){
			        $val++;
// 			        if(!array_key_exists($row->_id_persona, $nombres)){
// 			            $datos = $this->m_utils->getCamposById('public.persona', array('nom_persona','ape_pate_pers','ape_mate_pers','cod_alumno_temp'), 'nid_persona', $row->_id_persona, 'smiledu');
// 			            $nombres[$row->_id_persona] = $datos;
// 			        } else{
// 			            $datos = $nombres[$row->_id_persona];
// 			        }
// 			        if(substr($codigoAlumno,0,1) != 5){
// 			            continue;
// 			        } else{
// 			            $codigoAlumno = substr_replace($cuerpoGeneral[1],"",0,1);
// 			        }
                    $codAlu = substr_replace($row->cod_alumno_temp,"",0,1);
			        $arrayData = array('D',
			                           $row->cod_servicio,//CODIGO DEL SERVICIO
			                           '000',//CODIGO DEL SUCURSAL
			                           $codAlu,
			                           strtoupper($row->ape_pate_pers.' '.$row->ape_mate_pers).' '._ucwords($row->nom_persona),
			                           '00000000000000000000',//NUMERO DE RECIBO
			                           '-20000000000000000000000000000',
			                           //$row->desc_detalle_crono,
			                           //$row->fecha_emision,
			                           $row->fecha_vencimiento,
			                           $row->moneda_comercio,
			                           $row->monto_comercio,//IMPORTE
			                           '00000000000000000000000000000000000000000000000000',//DATOS ENTIDAD
			                           '00000000000000',//INTERES MORATORIO
			                           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
		                	           '0000','00000000000000',
			                           '00000000000000',
		                	           '00000000000000','00000000000000'.PHP_EOL);
			        $fila = $this->buildStringByArray($arrayData, $arrayLimites);
			        fwrite($file, $fila);
			    }
		    }else{
		    	throw new Exception("No hay datos para exportar");
		    }
	    }catch(Exception $e){
	    	$data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function stripAccents($string){
	    return strtr($string,'ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½',
	        'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
	//////////IMPORTACION-SCOTIABANK///////////////////////
	function importacionSCOTIA($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file){
		$array_file = str_split($file, 154);
		if(trim($array_file[count($array_file)-1]) == ''){
			unset($array_file[count($array_file)-1]);
		}
		$encabezado            = str_split($array_file[0]);
		$total                 = str_split($array_file[count($array_file)-1]);
		$eli                   = array_shift($array_file);
		$eli1                  = array_pop($array_file);
		$arrayEncabezado       = array(1,3,14,17,20,28,46,153);
		$arrayTotal            = array(1,3,12,27,42,57,153);
		$encabezadoGeneral     = $this->extracString($arrayEncabezado, $encabezado);
		$totalGeneral          = $this->extracString($arrayEncabezado, $total);
		$data = null;
		$data['error']         = EXIT_ERROR;
		$data['msj']           = null;
		try{
			$formato 		    = $this->m_utils->getById('pagos.banco', (($accion == IMPORTAR)  ? 'formato_importar' : 'formato_exportar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	    = explode(',', $formato);
			$arrayMovUpdate     = array();
			$arrayAudiMovInsert = array();
			$arrayDocumentos    = array();
			$arrayGeneralCorre  = array();
			$duplicadosGeneral  = array();
			foreach ($array_file as $row){
				$subarray      = str_split($row);
				$cuerpoGeneral = $this->extracString($arrayLimites, $subarray);
				$codigoAlumno  = trim($cuerpoGeneral[4]);
				$codigoAlumno  = $this->getCodAlu($codigoAlumno);
				$detalleAlumno    = $this->m_migracion->getSedeByAlumno($codigoAlumno);
				$sedes = explode(',', $detalleAlumno['nid_sede']);
				if(!in_array(2, $sedes)){
				    continue;
				}
				$movmiento        = $this->m_migracion->getMovimientoByAlumno($detalleAlumno['nid_persona'], $sedes, $cuerpoGeneral[4]);
				if(!isset($movmiento['id_movimiento'])){
				    throw new Exception('Hubo un problema con el alumno '.$cuerpoGeneral[4] );
				}
				$monto_pago = array();
				$monto_pago = str_split(trim($cuerpoGeneral[6]));
				for ($i=0; $i < count($monto_pago);){
					if($monto_pago[$i]== 0){
						array_shift($monto_pago);
					}else{
						break;
					}
				}
				$monto_pago = $this->insertar('.', count($monto_pago)-2, $monto_pago);
				$monto_pago = implode('', $monto_pago);
				if($movmiento['estado'] != ESTADO_PAGADO){
					$correDocumento = null;
					if($arrayGeneralCorre == null){
						$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
					}else{
						$flg_else = true;
						for ($i=0; $i < count($arrayGeneralCorre); $i++){
							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
								$correDocumento = $arrayGeneralCorre[$i]['numero_correlativo'];
								$flg_else = false;
								break;
							}
						}
						if($flg_else == true){
							$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
						}
					}
					$correlativo      = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
					$correlativoByMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
					$subArrayUpdate   = array('id_movimiento'  => $movmiento['id_movimiento'],
							'estado'         => ESTADO_PAGADO,
							'fecha_pago'     => date('Y-m-d h:m:s'),
							'monto_final'    => 0,
							'monto_adelanto' => $monto_pago);
					$subArrayInsert   = array('_id_movimiento' => $movmiento['id_movimiento'],
                            				  'correlativo'    => 2,
                            				  'accion'         => PAGAR,
                            				  'monto_pagado'   => $monto_pago,
                            				  '_id_sede'       => $detalleAlumno['nid_sede']);
					$arrayRecibo      = array('_id_movimiento' => $movmiento['id_movimiento'],
							'tipo_documento' => DOC_RECIBO,
							'nro_serie'      => SERIE_DEFAULT,
							'nro_documento'  => $correlativo,
							'_id_sede'       => $detalleAlumno['nid_sede'],
							'flg_impreso'    => 0,
							'estado'         => ESTADO_CREADO,
							'num_corre'      => $correlativoByMov);
					if($arrayGeneralCorre == null){
						$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
								'numero_correlativo' => $correDocumento + 1,
								'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
						array_push($arrayGeneralCorre, $arrayCorre);
					}else{
						$flg_else = true;
						for ($i=0; $i < count($arrayGeneralCorre); $i++){
							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
								$arrayGeneralCorre[$i]['numero_correlativo'] = $correDocumento+1;
								$flg_else = false;
								break;
							}
						}
						if($flg_else == true){
							$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
									'numero_correlativo' => $correDocumento + 1,
									'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
							array_push($arrayGeneralCorre, $arrayCorre);
						}
					}
					array_push($arrayMovUpdate, $subArrayUpdate);
					array_push($arrayAudiMovInsert, $subArrayInsert);
					array_push($arrayDocumentos, $arrayRecibo);
					$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $arraySedes);
					$corre_audi_banco 	= $this->getCorrelativoOperacion($num_correlativo + 1, 10);
					$logeoUsario  = $this->_idUserSess;
					$nameUsario   = _getSesion('nombre_completo');
					$arrayAudiBanco  = array('correlativo'     => $corre_audi_banco,
							'_id_banco'       => $id_banco,
							'id_pers_regi'    => $logeoUsario,
							'_id_empresa'     => $id_empresa,
							'audi_pers_regi'  => $nameUsario,
							'accion'          => $accion);
				}else if($movmiento['flg_lugar_pago'] == FLG_COLEGIO){
					$logeoUsario = $this->_idUserSess;
					$nombre_registra = _getSesion('nombre_completo');
					$arrayDuplicados = array('_id_banco'      => $id_banco,
							'_id_persona'    => $detalleAlumno['nid_persona'],
							'_id_movimiento' => $movmiento['id_movimiento'],
							'monto_pagado'   => $monto_pago,
							'audi_pers_regi' => $nombre_registra);
					array_push($duplicadosGeneral, $arrayDuplicados);
				}
			}
			if($arrayMovUpdate != null && $arrayAudiMovInsert != null && $arrayDocumentos != null && $arrayGeneralCorre != null && $arrayAudiBanco != null){
				$data = $this->m_migracion->updateMigracion($arrayMovUpdate, $arrayAudiMovInsert, $arrayDocumentos, $arrayGeneralCorre, $arrayAudiBanco, $duplicadosGeneral);
			}else{
				$data['msj'] = 'No se encontraron datos para actualizar';
			}
			$data['namefile'] = $name_file.'.txt';
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return $data;
	}
	//////////IMPORTACION-BANBIF///////////////////////
	function importacionBANBIF($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file){
	    $archivo = fopen("./uploads/modulos/pagos/txt/".$name_file,'r');
	    $array_file = array();
	    $i = 0;
	    while(!feof($archivo))
	    {
	        $array_file[$i] = fgets($archivo);
	        $i++;
	    }
		$data = null;
		$data['error']         = EXIT_ERROR;
		$data['msj']           = null;
		try{
			$formato 		    = $this->m_utils->getById('pagos.banco', (($accion == IMPORTAR)  ? 'formato_importar' : 'formato_exportar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	    = explode(',', $formato);
			$arrayMovUpdate     = array();
			$arrayAudiMovInsert = array();
			$arrayDocumentos    = array();
			$arrayGeneralCorre  = array();
			$duplicadosGeneral  = array();
			$arrayUpdDetaAlu    = array();
			$arrayIdMovsImport  = array();
			foreach ($array_file as $row){
			    if($row == null){
			        break;
			    }
				$subarray      = str_split($row);
				$cuerpoGeneral = $this->extracString($arrayLimites, $subarray);
				$codigoAlumno  = $cuerpoGeneral[1];
				$codigoAlumno  = $this->getCodAlu($codigoAlumno);
				$detalleAlumno = $this->m_migracion->getSedeByAlumno($codigoAlumno, $cuerpoGeneral[2]);
				$sedes = explode(',', $detalleAlumno['nid_sede']);
				if(!in_array(2, $sedes)){
				    continue;
				}
				$arrayFecha    = explode('/', trim($cuerpoGeneral[11]));
				$arrayFecha    = array_reverse($arrayFecha);
				$fecha         = implode('-', $arrayFecha);
				$year          = date("Y", strtotime($fecha));
				$movmiento = $this->m_migracion->getMovCuotaIngresoByAlumno($fecha,$codigoAlumno);
				if(!isset($movmiento['id_movimiento'])){
				    $cuota = explode(',', $this->m_migracion->getCuotaByFecha($fecha, $sedes, $year));
    				if($cuota == null){
    				    throw new Exception('No se encontro la cuota');
    				}
    				$movmiento        = $this->m_migracion->getMovimientoByAlumno($detalleAlumno['nid_persona'], $sedes, $cuota, $year);
    				if(!isset($movmiento['id_movimiento'])){
    				    throw new Exception('Hubo un problema con el alumno '.$cuerpoGeneral[4] );
    				}
			    }
			    $flgUpt = $this->m_migracion->flgUpdateDetalleAlumno($detalleAlumno['nid_persona'],$movmiento['id_movimiento']);
			    if($flgUpt['count'] != 0){
			        $subArrDetaAlu  = array('nid_persona'   => $detalleAlumno['nid_persona'],
                    			            'estado'        => (($flgUpt['estado'] == ALUMNO_PREREGISTRO) ? ALUMNO_REGISTRADO : ALUMNO_PROM_REGISTRO)
                    			        );
			        array_push($arrayUpdDetaAlu, $subArrDetaAlu);
			    }
				$monto_pago = array();
				$monto_pago = str_split(trim($cuerpoGeneral[14]));
				
				for ($i=0; $i < count($monto_pago);){
					if($monto_pago[$i]== 0){
						array_shift($monto_pago);
					}else{
						break;
					}
				}
				$monto_pago = implode('', $monto_pago);
				array_push($arrayIdMovsImport, $movmiento['id_movimiento']);
				if($movmiento['estado'] != ESTADO_PAGADO){
// 					$correDocumento = null;
// 					if($arrayGeneralCorre == null){
// 						$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 					}else{
// 						$flg_else = true;
// 						for ($i=0; $i < count($arrayGeneralCorre); $i++){
// 							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
// 								$correDocumento = $arrayGeneralCorre[$i]['numero_correlativo'];
// 								$flg_else = false;
// 								break;
// 							}
// 						}
// 						if($flg_else == true){
// 							$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 						}
// 					}
// 					$correlativo      = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
// 					$correlativoByMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
				    $fechaPago = implode('-', array_reverse(explode('/', trim($cuerpoGeneral[15]))));
				    
					$subArrayUpdate   = array('id_movimiento'   => $movmiento['id_movimiento'],
                							  'estado'          => ESTADO_PAGADO,
                							  'fecha_pago'      => $fechaPago,
                							  'monto_final'     => 0,
                							  'monto_adelanto'  => $monto_pago,
					                          'desc_lugar_pago' => LUGAR_PAGO_BANCO,
					                          'flg_lugar_pago'  => FLG_BANCO,
					                          '_id_banco_pago'  => BANCO_BCP,
					                          'desc_banco_pago' => BCP);
					$subArrayInsert   = array('_id_movimiento' => $movmiento['id_movimiento'],
                							  'correlativo'    => 2,
                							  'accion'         => PAGAR,
                							  'monto_pagado'   => $monto_pago,
                							  '_id_sede'       => $detalleAlumno['sede_actual']);
// 					$arrayRecibo      = array('_id_movimiento' => $movmiento['id_movimiento'],
// 							'tipo_documento' => DOC_RECIBO,
// 							'nro_serie'      => SERIE_DEFAULT,
// 							'nro_documento'  => $correlativo,
// 							'_id_sede'       => $detalleAlumno['nid_sede'],
// 							'flg_impreso'    => 0,
// 							'estado'         => ESTADO_CREADO,
// 							'num_corre'      => $correlativoByMov);
					/*if($arrayGeneralCorre == null){
						$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
								'tipo_documento' => DOC_RECIBO,
								'tipo_movimiento' => MOV_INGRESO,
								'nro_serie'      => SERIE_DEFAULT,
								'numero_correlativo' => $correDocumento + 1,
								'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
						array_push($arrayGeneralCorre, $arrayCorre);
					}else{
						$flg_else = true;
						for ($i=0; $i < count($arrayGeneralCorre); $i++){
							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
								$arrayGeneralCorre[$i]['numero_correlativo'] = $correDocumento+1;
								$flg_else = false;
								break;
							}
						}
						if($flg_else == true){
							$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
									'tipo_documento' => DOC_RECIBO,
									'tipo_movimiento' => MOV_INGRESO,
									'nro_serie'      => SERIE_DEFAULT,
									'numero_correlativo' => $correDocumento + 1,
									'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
							array_push($arrayGeneralCorre, $arrayCorre);
						}
					}*/
					array_push($arrayMovUpdate, $subArrayUpdate);
					array_push($arrayAudiMovInsert, $subArrayInsert);
// 					array_push($arrayDocumentos, $arrayRecibo);
					$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa);
					$corre_audi_banco 	= $this->getCorrelativoOperacion($num_correlativo + 1, 10);
					$logeoUsario  = $this->_idUserSess;
					$nameUsario   = _getSesion('nombre_completo');
					$arrayAudiBanco  = array('correlativo'     => $corre_audi_banco,
							'_id_banco'       => $id_banco,
							'id_pers_regi'    => $logeoUsario,
							'_id_empresa'     => $id_empresa,
							'audi_pers_regi'  => $nameUsario,
							'accion'          => $accion);
				} else if($movmiento['flg_lugar_pago'] == FLG_COLEGIO){
					$logeoUsario = $this->_idUserSess;
					$nombre_registra = _getSesion('nombre_completo');
					$arrayDuplicados = array('_id_banco'      => $id_banco,
							'_id_persona'    => $detalleAlumno['nid_persona'],
							'_id_movimiento' => $movmiento['id_movimiento'],
							'monto_pagado'   => $monto_pago,
							'audi_pers_regi' => $nombre_registra);
					array_push($duplicadosGeneral, $arrayDuplicados);
				}
			}
			if($arrayMovUpdate != null && $arrayAudiMovInsert != null /*&& $arrayDocumentos != null && $arrayGeneralCorre != null*/ && $arrayAudiBanco != null){
			    $arrayTransactions = array('importacion' =>
                                        			        array('updateMov'      => $arrayMovUpdate,
                                        			              'insertAudi'     => $arrayAudiMovInsert,
                                        			              'arrayAudiBanco' => $arrayAudiBanco,
                                        			              'duplicados'     => $duplicadosGeneral,
                                        			              'updateDetaAlu'  => $arrayUpdDetaAlu
                                        			             )
                                          );
			    $this->session->set_userdata($arrayTransactions);
			    $data['error'] = EXIT_SUCCESS;
// 				$data = $this->m_migracion->updateMigracion($arrayMovUpdate, $arrayAudiMovInsert/*, $arrayDocumentos, $arrayGeneralCorre*/, $arrayAudiBanco, $duplicadosGeneral,$arrayUpdDetaAlu);
			}else{
			    $data['msj'] = (count($duplicadosGeneral) > 0) ? 'Se encontraron compromisos ya pagados' : 'No se realizo la actualizacion';
			}
			$data['namefile']    = $name_file.'.txt';
			$data['movimientos'] = $arrayIdMovsImport;
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return $data;
	}
	//////////IMPORTACION-COMERCIO///////////////////////
	function importacionCOMERCIO($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file){
// 		$array_file = str_split($file, 154);
// 		if(trim($array_file[count($array_file)-1]) == ''){
// 			unset($array_file[count($array_file)-1]);
// 		}
		$archivo = fopen("./uploads/modulos/pagos/txt/".$name_file,'r');
		$array_file = array();
		$i = 0;
		while(!feof($archivo))
		{
			$array_file[$i] = fgets($archivo);
			$i++;
		}
		if(trim($array_file[count($array_file)-1]) == ''){
			unset($array_file[count($array_file)-1]);
		}
		$encabezado            = str_split($array_file[0]);
		$eli                   = array_shift($array_file);
		$arrayEncabezado       = array(1,2,8,22,28,42,50);
		$encabezadoGeneral     = $this->extracString($arrayEncabezado, $encabezado);
		$data = array();
		$data['error']         = EXIT_ERROR;
		$data['msj']           = null;
		try{
			$formato 		    = $this->m_utils->getById('pagos.banco', (($accion == IMPORTAR)  ? 'formato_importar' : 'formato_exportar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	    = explode(',', $formato);
			$arrayMovUpdate     = array();
			$arrayAudiMovInsert = array();
			$arrayDocumentos    = array();
			$arrayGeneralCorre  = array();
			$duplicadosGeneral  = array();
			$arrayUpdDetaAlu    = array();
			$arrayIdMovsImport  = array();
			foreach ($array_file as $row){
				$subarray      = str_split($row);
				$cuerpoGeneral = $this->extracString($arrayLimites, $subarray);
				$codigoAlumno  = substr(trim($cuerpoGeneral[1]), -1).$cuerpoGeneral[3];
				$codigoAlumno  = $this->getCodAlu($codigoAlumno);
				$detalleAlumno = $this->m_migracion->getSedeByAlumno($codigoAlumno, $cuerpoGeneral[4]);
				$sedes = explode(',', $detalleAlumno['nid_sede']);
				if(!in_array(2, $sedes)){
				    continue;
				}
				$fechaArray    = str_split(trim($cuerpoGeneral[8]));
				$fechaArray    = $this->insertar('-', count($fechaArray)-4, $fechaArray);
				$fechaArray    = $this->insertar('-', count($fechaArray)-2, $fechaArray);
				$fecha         = implode('', $fechaArray);
				$year          = date("Y", strtotime($fecha));
				$movmiento = $this->m_migracion->getMovCuotaIngresoByAlumno($fecha,$codigoAlumno);
				if(!isset($movmiento['id_movimiento'])){
				    $cuota = explode(',', $this->m_migracion->getCuotaByFecha($fecha, $sedes, $year));
//     				$cuota         = $this->m_migracion->getCuotaByFecha($fecha, $detalleAlumno['nid_sede'], $year);
    				if($cuota == null){
    				    throw new Exception('No se encontro la cuota');
    				}
    				$movmiento     = $this->m_migracion->getMovimientoByAlumno($detalleAlumno['nid_persona'], $sedes, $cuota, $year);
    				if(!isset($movmiento['id_movimiento'])){
    				    throw new Exception('Hubo un problema con el alumno '.$cuerpoGeneral[4] );
    				}
				}
				$flgUpt = $this->m_migracion->flgUpdateDetalleAlumno($detalleAlumno['nid_persona'],$movmiento['id_movimiento']);
				if($flgUpt['count'] != 0){
				    $subArrDetaAlu  = array('nid_persona'   => $detalleAlumno['nid_persona'],
                    				        'estado'        => (($flgUpt['estado'] == ALUMNO_PREREGISTRO) ? ALUMNO_REGISTRADO : ALUMNO_PROM_REGISTRO)
                    				    );
				    array_push($arrayUpdDetaAlu, $subArrDetaAlu);
				}
				$monto_pago    = array();
				$monto_pago    = str_split(trim($cuerpoGeneral[10]));
				for ($i=0; $i < count($monto_pago);){
					if($monto_pago[$i]== 0){
						array_shift($monto_pago);
					}else{
						break;
					}
				}
				$monto_pago = $this->insertar('.', count($monto_pago)-2, $monto_pago);
				$monto_pago = implode('', $monto_pago);
				array_push($arrayIdMovsImport, $movmiento['id_movimiento']);
				if($movmiento['estado'] != ESTADO_PAGADO){
					$correDocumento = null;
// 					if($arrayGeneralCorre == null){
// 						$correDocumento   = $this->m_movimientos->getCurrentCorrelativoALumno($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 					}else{
// 						$flg_else = true;
// 						for ($i=0; $i < count($arrayGeneralCorre); $i++){
// 							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
// 								$correDocumento = $arrayGeneralCorre[$i]['numero_correlativo'];
// 								$flg_else = false;
// 								break;
// 							}
// 						}
// 						if($flg_else == true){
// 							$correDocumento   = $this->m_movimientos->getCurrentCorrelativoALumno($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 						}
// 					}
// 					$correlativo      = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
// 					$correlativoByMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
					$fechaPago = str_split(trim($cuerpoGeneral[13]));
					$fechaPago = $this->insertar('-', count($fechaArray)-4, $fechaArray);
					$fechaPago = $this->insertar('-', count($fechaArray)-2, $fechaArray);
					$fechaPago = implode('', $fechaArray);
					$subArrayUpdate   = array('id_movimiento'   => $movmiento['id_movimiento'],
                    						  'estado'          => ESTADO_PAGADO,
                    						  'fecha_pago'      => $fechaPago,
                    						  'monto_final'     => 0,
                    						  'monto_adelanto'  => $monto_pago,
					                          'desc_lugar_pago' => LUGAR_PAGO_BANCO,
					                          'flg_lugar_pago'  => FLG_BANCO,
					                          '_id_banco_pago'  => BANCO_COMERCIO,
					                          'desc_banco_pago' => COMERCIO);
					$subArrayInsert   = array('_id_movimiento' => $movmiento['id_movimiento'],
                							  'correlativo'    => 2,
                							  'accion'         => PAGAR,
                							  'monto_pagado'   => $monto_pago,
                							  '_id_sede'       => $detalleAlumno['sede_actual']);
// 					$arrayRecibo      = array('_id_movimiento' => $movmiento['id_movimiento'],
// 							'tipo_documento' => DOC_RECIBO,
// 							'nro_serie'      => SERIE_DEFAULT,
// 							'nro_documento'  => $correlativo,
// 							'_id_sede'       => $detalleAlumno['nid_sede'],
// 							'flg_impreso'    => 0,
// 							'estado'         => ESTADO_CREADO,
// 							'num_corre'      => $correlativoByMov);
					/*if($arrayGeneralCorre == null){
						$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
								'tipo_documento' => DOC_RECIBO,
								'tipo_movimiento' => MOV_INGRESO,
								'nro_serie'      => SERIE_DEFAULT,
								'numero_correlativo' => ($correlativo),
								'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
						array_push($arrayGeneralCorre, $arrayCorre);
					}else{
						$flg_else = true;
						for ($i=0; $i < count($arrayGeneralCorre); $i++){
							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
								$arrayGeneralCorre[$i]['numero_correlativo'] = $correDocumento+1;
								$flg_else = false;
								break;
							}
						}
						if($flg_else == true){
							$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
									'tipo_documento' => DOC_RECIBO,
									'tipo_movimiento' => MOV_INGRESO,
									'nro_serie'      => SERIE_DEFAULT,
									'numero_correlativo' => ($correlativo),
									'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
							array_push($arrayGeneralCorre, $arrayCorre);
						}
					}*/
					array_push($arrayMovUpdate, $subArrayUpdate);
					array_push($arrayAudiMovInsert, $subArrayInsert);
// 					array_push($arrayDocumentos, $arrayRecibo);
					$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa);
					$corre_audi_banco 	= $this->getCorrelativoOperacion($num_correlativo + 1, 10);
					$logeoUsario  = $this->_idUserSess;
					$nameUsario   = _getSesion('nombre_completo');
					$arrayAudiBanco  = array('correlativo'    => $corre_audi_banco,
                							 '_id_banco'      => $id_banco,
                							 'id_pers_regi'   => $logeoUsario,
                							 '_id_empresa'    => $id_empresa,
                							 'audi_pers_regi' => $nameUsario,
                							 'accion'         => $accion);
				} else if($movmiento['flg_lugar_pago'] == FLG_COLEGIO){
					$logeoUsario = $this->_idUserSess;
					$nombre_registra = _getSesion('nombre_completo');
					$arrayDuplicados = array('_id_banco'      => $id_banco,
							'_id_persona'    => $detalleAlumno['nid_persona'],
							'_id_movimiento' => $movmiento['id_movimiento'],
							'monto_pagado'   => $monto_pago,
							'audi_pers_regi' => $nombre_registra);
					array_push($duplicadosGeneral, $arrayDuplicados);
				}
			}
			if($arrayMovUpdate != null && $arrayAudiMovInsert != null /*&& $arrayDocumentos != null && $arrayGeneralCorre != null*/ && $arrayAudiBanco != null){
			    $arrayTransactions = array('importacion' =>
                                        			        array('updateMov'      => $arrayMovUpdate,
                                        			              'insertAudi'     => $arrayAudiMovInsert,
                                        			              'arrayAudiBanco' => $arrayAudiBanco,
                                        			              'duplicados'     => $duplicadosGeneral,
                                        			              'updateDetaAlu'  => $arrayUpdDetaAlu
                                                                  )
			                              );
			    $this->session->set_userdata($arrayTransactions);
			    $data['error'] = EXIT_SUCCESS;
// 			    $data = $this->m_migracion->updateMigracion($arrayMovUpdate, $arrayAudiMovInsert/*, $arrayDocumentos, $arrayGeneralCorre*/, $arrayAudiBanco, $duplicadosGeneral,$arrayUpdDetaAlu);
			}else{
				$data['msj'] = 'Archivo Peteneciente a otra Empresa';
			}
			$data['namefile'] = $name_file.'.txt';
			$data['movimientos'] = $arrayIdMovsImport;
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return $data;
	}
	//////////IMPORTACION-BCP///////////////////////
	function importacionBCP($file, $id_banco, $accion, $id_empresa, $arraySedes, $name_file){
		///////////////////////////
		$array_file = str_split($file, 201);
		if(trim($array_file[count($array_file)-1]) == ''){
			unset($array_file[count($array_file)-1]);
		}
		$archivoAux = fopen("./uploads/modulos/pagos/txt/".$name_file,'r');
		$array_file = array();
		$i = 0;
		while(!feof($archivoAux))
		{
		    $array_file[$i] = fgets($archivoAux);
		    if($i == 100){
		        break;
		    }
		    $i++;
		}
		$encabezado            = str_split($array_file[0]);
		$total                 = str_split($array_file[count($array_file)-1]);
		$eli                   = array_shift($array_file);
		$arrayEncabezado       = array(1,3,14,15,23,33,46,57);
		$encabezadoGeneral     = $this->extracString($arrayEncabezado, $encabezado);
		$fechaArray = str_split(trim($encabezadoGeneral[3]));
		$fechaArray = $this->insertar('-', count($fechaArray)-4, $fechaArray);
		$fechaArray = $this->insertar('-', count($fechaArray)-2, $fechaArray);
		$fechaPago  = implode('', $fechaArray);
		$data = null;
		$data['error']         = EXIT_ERROR;
		$data['msj']           = null;
		try{
			$formato 		    = $this->m_utils->getById('pagos.banco', (($accion == IMPORTAR)  ? 'formato_importar' : 'formato_exportar'), 'id_banco', $id_banco, 'pagos');
			$arrayLimites 	    = explode(',', $formato);
			$arrayMovUpdate     = array();
			$arrayAudiMovInsert = array();
			$arrayDocumentos    = array();
			$arrayGeneralCorre  = array();
			$arrayAudiBanco     = array();
			$duplicadosGeneral  = array();
			$arrayUpdDetaAlu    = array();
			$arrayIdMovsImport  = array();
			$cont = 0;
			foreach ($array_file as $row){
			    if($row == null){
			        break;
			    }
			    $cont++;
				$subarray      = str_split($row);
				$cuerpoGeneral = $this->extracString($arrayLimites, $subarray);
				$codAluAux = trim($cuerpoGeneral[1]);
				//@PENDIENTE solo recibe a los que son de la empresa privados y sede ecologica
// 				if(substr($codAluAux, 0,1) != 5){
// 				    continue;
// 				}
				$codigoAlumno = $this->getCodAlu($codAluAux);
				//@PENDIENTE cambio del primer caracter del codigo temp de alumno para ecologica de 5(txt) a 2(BD)
// 				$codigoAlumno  = substr_replace($codAluAux, 2, 0, 1);
				$detalleAlumno    = $this->m_migracion->getSedeByAlumno($codigoAlumno, $cuerpoGeneral[2]);
				$sedes = explode(',', $detalleAlumno['nid_sede']);
				if(!in_array(2, $sedes)){
				    continue;
				}
	           	if(!isset($detalleAlumno['nid_persona'])){
				    throw new Exception('No se encontro un estudiante');
				}
				$fechaArray = str_split(trim($cuerpoGeneral[4]));
				$fechaArray = $this->insertar('-', count($fechaArray)-4, $fechaArray);
				$fechaArray = $this->insertar('-', count($fechaArray)-2, $fechaArray);
				$fecha = implode('', $fechaArray);
				$year = date("Y", strtotime($fecha));
				$movmiento = $this->m_migracion->getMovCuotaIngresoByAlumno($fecha,$codigoAlumno);
				if(!isset($movmiento['id_movimiento'])){
				    $cuota = explode(',', $this->m_migracion->getCuotaByFecha($fecha, $sedes, $year));
    				if($cuota == null){
    				    throw new Exception('No se encontro la cuota');
    				}
    				$movmiento        = $this->m_migracion->getMovimientoByAlumno($detalleAlumno['nid_persona'], $sedes, $cuota, $year);
    				if(!isset($movmiento['id_movimiento'])){
    				    throw new Exception('Hubo un problema con el alumno '.$cuerpoGeneral[2] );
    				}
				}
				$flgUpt = $this->m_migracion->flgUpdateDetalleAlumno($detalleAlumno['nid_persona'],$movmiento['id_movimiento']);
				if($flgUpt['count'] != 0){
				    $subArrDetaAlu  = array('nid_persona'   => $detalleAlumno['nid_persona'],
                    				        'estado'        => (($flgUpt['estado'] == ALUMNO_PREREGISTRO) ? ALUMNO_REGISTRADO : ALUMNO_PROM_REGISTRO)
                    				       );
				    array_push($arrayUpdDetaAlu, $subArrDetaAlu);
				}
				$monto_pago = array();
				$monto_pago = str_split(trim($cuerpoGeneral[7]));
				for ($i=0; $i < count($monto_pago);){
					if($monto_pago[$i]== 0){
						array_shift($monto_pago);
					}else{
						break;
					}
				}
				$monto_pago = $this->insertar('.', count($monto_pago)-2, $monto_pago);
				$monto_pago = implode('', $monto_pago);
				array_push($arrayIdMovsImport, $movmiento['id_movimiento']);
				if($movmiento['estado'] != ESTADO_PAGADO){
// 					$correDocumento = null;
// 					if($arrayGeneralCorre == null){
// 						$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 					}else{
// 						$flg_else = true;
// 						for ($i=0; $i < count($arrayGeneralCorre); $i++){
// 							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
// 								$correDocumento = $arrayGeneralCorre[$i]['numero_correlativo'];
// 								$flg_else = false;
// 								break;
// 							}
// 						}
// 						if($flg_else == true){
// 							$correDocumento   = $this->m_movimientos->getCurrentCorrelativo($detalleAlumno['nid_sede'],DOC_RECIBO,MOV_INGRESO);
// 						}
// 					}
// 					$correlativo      = $this->getCorrelativoReciboByMovimiento($correDocumento+1);
// 					$correlativoByMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
					$subArrayUpdate   = array('id_movimiento'   => $movmiento['id_movimiento'],
                    						  'estado'          => ESTADO_PAGADO,
                    						  'fecha_pago'      => $fechaPago,
                    						  'monto_final'     => 0,
                    						  'monto_adelanto'  => $monto_pago,
					                          'desc_lugar_pago' => LUGAR_PAGO_BANCO,
					                          'flg_lugar_pago'  => FLG_BANCO,
					                          '_id_banco_pago'  => BANCO_BCP,
					                          'desc_banco_pago' => BCP);
					$correlativoMov = $this->m_movimientos->getNextCorrelativo($movmiento['id_movimiento']);
					$subArrayInsert   = array('_id_movimiento' => $movmiento['id_movimiento'],
                							  'correlativo'    => $correlativoMov,
                							  'accion'         => PAGAR,
                  							  'monto_pagado'   => $monto_pago,
                							  '_id_sede'       => $detalleAlumno['sede_actual']);
// 					$arrayRecibo      = array('_id_movimiento' => $movmiento['id_movimiento'],
// 							'tipo_documento' => DOC_RECIBO,
// 							'nro_serie'      => SERIE_DEFAULT,
// 							'nro_documento'  => $correlativo,
// 							'_id_sede'       => $detalleAlumno['nid_sede'],
// 							'flg_impreso'    => 0,
// 							'estado'         => ESTADO_CREADO,
// 							'num_corre'      => $correlativoByMov);
					/*if($arrayGeneralCorre == null){
						$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
								'numero_correlativo' => $correDocumento + 1,
						        'tipo_movimiento'    => 'INGRESO',
						        'tipo_documento'    => 'RECIBO',
						        'nro_serie'          => SERIE_DEFAULT,
								'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
						array_push($arrayGeneralCorre, $arrayCorre);
					}else{
						$flg_else = true;
						for ($i=0; $i < count($arrayGeneralCorre); $i++){
							if($arrayGeneralCorre[$i]['_id_sede'] == $detalleAlumno['nid_sede']){
								$arrayGeneralCorre[$i]['numero_correlativo'] = $correDocumento+1;
								$flg_else = false;
								break;
							}
						}
						if($flg_else == true){
							$arrayCorre       = array('_id_sede'       => $detalleAlumno['nid_sede'],
									'numero_correlativo' => $correDocumento + 1,
									'accion'         => (($correDocumento == null) ? 'INSERT' : 'UPDATE'));
							array_push($arrayGeneralCorre, $arrayCorre);
						}
					}*/
					array_push($arrayMovUpdate, $subArrayUpdate);
					array_push($arrayAudiMovInsert, $subArrayInsert);
// 					array_push($arrayDocumentos, $arrayRecibo);
					$num_correlativo  = $this->m_migracion->getCorrelativoByBanco($id_banco, $id_empresa);
					$corre_audi_banco 	= $this->getCorrelativoOperacion($num_correlativo + 1, 10);
					$logeoUsario  = $this->_idUserSess;
					$nameUsario   = _getSesion('nombre_completo');
					$arrayAudiBanco  = array('correlativo'     => $corre_audi_banco,
							'_id_banco'       => $id_banco,
							'id_pers_regi'    => $logeoUsario,
							'_id_empresa'     => $id_empresa,
							'audi_pers_regi'  => $nameUsario,
							'accion'          => $accion);
			    } else if($movmiento['flg_lugar_pago'] == FLG_COLEGIO){
					$logeoUsario = $this->_idUserSess;
					$nombre_registra = _getSesion('nombre_completo');
					$arrayDuplicados = array('_id_banco'      => $id_banco,
							'_id_persona'    => $detalleAlumno['nid_persona'],
							'_id_movimiento' => $movmiento['id_movimiento'],
							'monto_pagado'   => $monto_pago,
							'audi_pers_regi' => $nombre_registra);
					array_push($duplicadosGeneral, $arrayDuplicados);
				}
			}
			if($arrayMovUpdate != null && $arrayAudiMovInsert != null /*&& $arrayDocumentos != null && $arrayGeneralCorre != null*/ && $arrayAudiBanco != null){
			    $arrayTransactions = array('importacion' =>
                                        			        array('updateMov'      => $arrayMovUpdate,
                                        			              'insertAudi'     => $arrayAudiMovInsert,
                                        			              'arrayAudiBanco' => $arrayAudiBanco,
                                        			              'duplicados'     => $duplicadosGeneral,
                                        			              'updateDetaAlu'  => $arrayUpdDetaAlu
                                        			             )
                                          );
			    $this->session->set_userdata($arrayTransactions);
			    $data['error'] = EXIT_SUCCESS;
// 				$data = $this->m_migracion->updateMigracion($arrayMovUpdate, $arrayAudiMovInsert, /*$arrayDocumentos, $arrayGeneralCorre,*/ $arrayAudiBanco, $duplicadosGeneral,$arrayUpdDetaAlu);
			}else{
				$data['msj'] = 'No se actualizaron los datos';
			}
			$data['movimientos'] = $arrayIdMovsImport;
			$data['namefile']    = $name_file.'.txt';
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		return $data;
	}
	
	function buildTablePreviewMigrar($result){
	    $empresas = $this->m_migracion->getAllEmpresas();
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="false" data-search="false" id="tb_preview">',
	        'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#');
	    $head_1 = array('data' => 'Sede');
	    $head_2 = array('data' => 'Estudiante');
	    $head_3 = array('data' => 'Cuota');
	    $head_4 = array('data' => 'Fec. Vencimiento');
	    $head_5 = array('data' => 'Monto');
	    $head_6 = array('data' => 'Mora');
	    $head_7 = array('data' => 'Total');
	    $head_8 = array('data' => 'Obs.');
	    $this->table->set_heading($head_0,$head_1,$head_2,$head_3,$head_4,$head_5,$head_6,$head_7,$head_8);
	    foreach($result as $row){
	        $row_col0 = array('data' => $row->row_num);
	        $row_col1 = array('data' => $row->desc_sede);
	        $row_col2 = array('data' => $row->nombre_completo);
	        $row_col3 = array('data' => $row->desc_cuota);
	        $row_col4 = array('data' => $row->fecha_vencimiento);
	        $row_col5 = array('data' => $row->monto);
	        $row_col6 = array('data' => $row->mora_acumulada);
	        $row_col7 = array('data' => $row->monto_final);
	        $row_col8 = array('data' => $row->info);
	        $this->table->add_row($row_col0,$row_col1,$row_col2,$row_col3,$row_col4,$row_col5,$row_col6,$row_col7,$row_col8);
	    }
	    return $this->table->generate();
	}
	
	function executeImportData(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = 'Hubo un error';
	    $arrImport     =  $this->session->userdata('importacion');
	    $data['error'] = EXIT_SUCCESS;
	    if($arrImport != null){
	        $data = $this->m_migracion->updateMigracion($arrImport['updateMov'], $arrImport['insertAudi']/*, $arrayDocumentos, $arrayGeneralCorre*/, $arrImport['arrayAudiBanco'], $arrImport['duplicados'], $arrImport['updateDetaAlu']);
	        $this->session->unset_userdata('importacion');
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCodAlu($codAlumno){
	    $cod_alumno = null;
	    if(substr($codAlumno,0,1) == 5){//ECOLOGICA
	        $cod_alumno = substr_replace($codAlumno,"2",0,1);
	    } else if(substr($codAlumno,0,1) == 1){//CENTRAL
	        $cod_alumno = substr_replace($codAlumno,"1",0,1);
	    } else if(substr($codAlumno,0,1) == 3){//INDUSTRIAL
	        $cod_alumno = substr_replace($codAlumno,"4",0,1);
	    } else if(substr($codAlumno,0,1) == 4){//SUPERIOR
	        $cod_alumno = substr_replace($codAlumno,"5",0,1);
	    } else if(substr($codAlumno,0,1) == 2){
	        $cod_alumno = substr_replace($codAlumno,"3",0,1);
	    } else if(substr($codAlumno,0,1) == 6){
	        $cod_alumno = substr_replace($codAlumno,"6",0,1);
	    } else {
	        $cod_alumno = $codAlumno;
	    }
	    return $cod_alumno;
	}
	
	function getCodAluExport($codAlumno){
	    $cod_alumno = null;
	    if(substr($codAlumno,0,1) == 2){//ECOLOGICA
	        $cod_alumno = substr_replace($codAlumno,"5",0,1);
	    } else if(substr($codAlumno,0,1) == 1){//CENTRAL
	        $cod_alumno = substr_replace($codAlumno,"1",0,1);
	    } else if(substr($codAlumno,0,1) == 4){//INDUSTRIAL
	        $cod_alumno = substr_replace($codAlumno,"3",0,1);
	    } else if(substr($codAlumno,0,1) == 5){//SUPERIOR
	        $cod_alumno = substr_replace($codAlumno,"4",0,1);
	    } else if(substr($codAlumno,0,1) == 3){
	        $cod_alumno = substr_replace($codAlumno,"2",0,1);
	    } else if(substr($codAlumno,0,1) == 6){
	        $cod_alumno = substr_replace($codAlumno,"6",0,1);
	    } else{
	        $cod_alumno = $codAlumno;
	    }
	    return $cod_alumno;
	}
}