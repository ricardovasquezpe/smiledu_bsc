<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_consultar_encuesta extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, ID_PERMISO_ADMIN_ENC, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
   
	public function index() {
	    $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
	    ////Modal Popup Iconos///
	    $data['titleHeader']                = 'Administración';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
	    //MENU
	    $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
	    $encuestas = null;
        if(_validate_metodo_rol($this->_idRol)) {
            $encuestas = $this->m_encuesta->getAllEncuestas();
        } else {
            $encuestas = $this->m_encuesta->getAllEncuestasByPersona($this->_idUserSess);
        }
        $data += $this->createTableEncuestas($encuestas);
	    ///////////
	    $this->session->set_userdata(array('tab_active_config' => null));
	    $this->load->view('vf_encuesta/v_consultar_encuesta',$data);
	}
	
	public function createTableEncuestas($data) {
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-custom-search="$.noop"
			                                   data-pagination="true" 
			                                   data-show-columns="true" data-search="true" id="tb_encuestas">',
	                  'heading_row_start'     => '<tr class="filters">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0  = array('data' => 'Código');
	    $head_8  = array('data' => '#', 'class' => 'text-right', 'data-searchable' => 'false');
	    $head_1  = array('data' => 'Tipo','style' => 'text-align:left', 'data-sortable' => 'false', 'class' => 'text-left');
	    $head_2  = array('data' => 'Fecha Apertura', 'class' => 'text-center');
	    $head_3  = array('data' => 'Fecha Finalizó', 'class' => 'text-center');
	    $head_4  = array('data' => 'Estado', 'class' => 'text-center');
	    $head_5  = array('data' => 'Acción', 'class' => 'text-center', 'data-searchable' => 'false');
	    $head_10 = array('data' => 'Excel');
	    $head_11 = array('data' => 'Título', 'class' => 'text-left');
	    $head_12 = array('data' => 'Registrado por' ,'data-visible' => 'false');
	    $head_13 = array('data' => 'Encuestados' , 'class' => 'text-center', 'data-field' => 'cant_encuestados');
	    $val = 0;
	    $this->table->set_heading($head_8, $head_11, $head_0, $head_1, $head_12, $head_2, $head_3, $head_4, $head_13, $head_5);
	    $botones = null;
	    foreach($data as $row) {
	        $val++;
	        $idEncuesta = _simple_encrypt($row->id_encuesta);
	        $row_col8  = array('data' => $val, 'data-id_encuesta' => $idEncuesta, 'class' => 'cellID');    
	        $row_col0  = array('data' => $row->desc_enc);
	        $row_col1  = array('data' => $row->desc_tipo_encuesta);
	        $fechaApertura = ($row->fecha_apertura == null) ? "-" : $row->fecha_apertura;
	        $row_col2  = array('data' => $fechaApertura);
	        $fechaCierre = ($row->fecha_cierre == null) ? "-" : $row->fecha_cierre;
	        $row_col3  = array('data' => $fechaCierre);
	        //BUTTON Y TITULO
	        $titulo = 'Finalizar Encuesta - '.$row->desc_enc;
	        $classButon = '';
	        $condicionCambio = _simple_encrypt(ENCUESTA_FINALIZADA);
	        $idTipoEncuesta  = _encodeCI($row->_id_tipo_encuesta);
	        $estado      = "label-success-light";
	        $iconEstado  = "check";
	        //SIN FUNCIONALIDAD
	        $botonStandBy = '<li class="mdl-menu__item" onclick="openModalStandBy(\''.$idEncuesta.'\',\''.str_replace("'", ' ', str_replace('"', ' ', $row->titulo_encuesta)).'\')"><i class="mdi mdi-pause_circle_outline"></i> Stand By</li>';
	        $botonEdit    = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-edit" disabled="disabled"></i> Editar</li>';
	        ///////////////////
	        //BOTONES CON FUNCIONALIDAD
	        $botonEstado         = '<li onclick="openModalChangeEstado(\''.$idEncuesta.'\' , \''.$classButon.'\' , \''.$titulo.'\' , \''.$condicionCambio.'\' ,\''.$idTipoEncuesta.'\');" class="mdl-menu__item"><i class="mdi mdi-done_all"></i> Finalizar</li>';
	        $botonBloqueaEnc     = '<li class="mdl-menu__item" onclick="openModalBloquearEncuesta(\''.$idEncuesta.'\',\''.$idTipoEncuesta.'\');"><i class="mdi mdi-block"></i> Bloquear</li>';
	        $botonVistaPreevia   = '<li class="mdl-menu__item" onclick="vistaPrevia(\''.$idEncuesta.'\');" ><i class="mdi mdi-remove_red_eye"></i> Vista Previa</li>';
	        $botonDescargarExcel = '<li class="mdl-menu__item" onclick="openModalDescargarExcel(\''.$idEncuesta.'\')"><i class="mdi mdi-file_download"></i> Descargar plantilla (.xls)</li>';
	        $botonSubirExcel     = '<li class="mdl-menu__item" onclick="openModalSubirExcel(\''.$idEncuesta.'\', $(this))"><i class="mdi mdi-file_upload"></i> Subir Excel</li>';
	        $botonLlenadoFisico  = null;
	        if($row->_id_tipo_encuesta == TIPO_ENCUESTA_PADREFAM) {
	            $botonLlenadoFisico = '<li class="mdl-menu__item" onclick="goLlenadoFisico(\''.$idEncuesta.'\', \''.RUTA_SMILEDU.'\' )"><i class="mdi mdi-forward"></i> Llenado</li>';
	        }
	        //GENERAR URL SOLO PARA ENCUESTAS LIBRES APERTURADAS
	        $onclickURL          = ($row->_id_tipo_encuesta == TIPO_ENCUESTA_LIBRE && $row->flg_estado == ENCUESTA_APERTURADA) ? 'onclick="generaUrl(\''.(_encodeCI($row->id_encuesta)).'\')"' : null;
	        $inactiveUrl         = ($row->_id_tipo_encuesta == TIPO_ENCUESTA_LIBRE && $row->flg_estado == ENCUESTA_APERTURADA) ? null : 'disabled="disabled" ';
	        $botonGeneraUrl      = '<li class="mdl-menu__item" '.$inactiveUrl.' '.$onclickURL.' ><i class="mdi mdi-link" '.$inactiveUrl.'></i>Generar URL</li>';
	        ////////////////////////////////////////////////////
	        if($row->flg_estado == ENCUESTA_FINALIZADA){
	            $estado = 'label-info';
	            //SIN FUNCIONALIDAD
	            $botonStandBy = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-pause_circle_outline" disabled="disabled"></i> Stand By</li>';
	            $botonEstado = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-done_all" disabled="disabled"></i> Finalizar</li>';
	            $botonBloqueaEnc = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-block" disabled="disabled"></i> Bloquear</li>';
	            $botonDescargarExcel = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-file_download" disabled="disabled"></i> Descargar plantilla (.xls)</li>';
	            $botonSubirExcel = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-file_upload" disabled="disabled"></i> Subir Excel</li>';
	            $iconEstado  = "done_all";
	            ///////////////////
	        }else if($row->flg_estado == ENCUESTA_CREADA || $row->flg_estado == ENCUESTA_BLOQUEADA){
	            if($row->_id_tipo_encuesta != TIPO_ENCUESTA_LIBRE){
	                $titulo = 'Aperturar Encuesta - '.$row->desc_enc.' Se finalizaran todas las encuestas del tipo '.$row->desc_tipo_encuesta.' ¿Esta seguro de aperturarla?';
	            } else{
	                $titulo = 'Aperturar Encuesta - '.$row->desc_enc;
	            }
	            //SIN FUNCIONALIDAD
	            $botonStandBy = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-pause_circle_outline" disabled="disabled"></i> Stand By</li>';
	            $botonBloqueaEnc = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-block" disabled="disabled"></i> Bloquear</li>';
	            if ($row->flg_estado == ENCUESTA_CREADA){
	                $estado = 'label-warning';
	                $iconEstado  = "edit";
	            } else {
	                $estado = 'label-danger';
	                $iconEstado  = "block";
	            }
	            //$estado = 'label-warning';
	            $condicionCambio = _simple_encrypt(ENCUESTA_APERTURADA);
	            $botonEdit = '<li class="mdl-menu__item" onclick="redirectEditEncuestaInactiva(\''.$idEncuesta.'\');" ><i class="mdi mdi-edit"></i> Editar</li>';
	            $botonEstado = '<li class="mdl-menu__item" onclick="openModalChangeEstado(\''.$idEncuesta.'\' , \''.$classButon.'\' , \''.$titulo.'\' , \''.$condicionCambio.'\',\''.$idTipoEncuesta.'\');" ><i class="mdi mdi-check"></i> Aperturar</li>';
	            $botonVistaPreevia = '<li class="mdl-menu__item" onclick="vistaPrevia(\''.$idEncuesta.'\');" ><i class="mdi mdi-remove_red_eye"></i> Vista Previa</li>';
	            $botonDescargarExcel = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-file_download" disabled="disabled"></i> Descargar plantilla (.xls)</li>';
	            $botonSubirExcel = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-file_upload" disabled="disabled"></i> Subir Excel</li>';
	            //$iconEstado  = "block";
	        } else if($row->flg_estado == ENCUESTA_STAND_BY){
	            if($row->_id_tipo_encuesta != TIPO_ENCUESTA_LIBRE){
	                $titulo = 'Aperturar Encuesta - '.$row->desc_enc.' Se finalizaran todas las encuestas del tipo '.$row->desc_tipo_encuesta.' ¿Esta seguro de aperturarla?';
	            } else{
	                $titulo = 'Aperturar Encuesta - '.$row->desc_enc;
	            }
                $estado = 'label-default';
                $iconEstado  = "pause";
                $condicionCambio = _simple_encrypt(ENCUESTA_APERTURADA);
	            $botonEstado = '<li class="mdl-menu__item" onclick="openModalChangeEstado(\''.$idEncuesta.'\' , \''.$classButon.'\' , \''.$titulo.'\' , \''.$condicionCambio.'\',\''.$idTipoEncuesta.'\');" ><i class="mdi mdi-check"></i> Aperturar</li>';
	            $botonStandBy = '<li class="mdl-menu__item" disabled="disabled"><i class="mdi mdi-pause_circle_outline" disabled="disabled"></i> Stand By</li>';
	        }
	        $row_col4 = array('data' => '<span class="label '.$estado.'"><i class="mdi mdi-'.$iconEstado.'"></i>'.$row->flg_estado.'</span>');
	        $row_col11 = array('data' => $row->titulo_encuesta);
	        $row_col12 = array('data' => _ucwords($row->audi_nomb_regi));
	        $row_col13 = array('data' => $row->cant_encuestados, 'class' => 'text-right');
	        $estadoCrypt = _encodeCI($row->flg_estado);
	        //Id para los ul
	        $idButton = "vistaOpciones".$val;
	        /////////////
	        $botonCompartir = '<li class="mdl-menu__item" onclick="openModalCompartirEncuesta($(this));"><i class="mdi mdi-group_add"></i> Compartir</li>';
	        $botones = '<ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" id="opcionesEncuesta'.$val.'" for="'.$idButton.'">'
	                     .$botonCompartir.$botonBloqueaEnc.$botonEstado.$botonStandBy.'<hr/>'.$botonEdit.$botonVistaPreevia.'<hr/>'.$botonDescargarExcel.$botonSubirExcel.$botonLlenadoFisico.$botonGeneraUrl.
	                   '</ul>';
	        $botonGeneral = '<div class="wrapper">
	                             <button id="'.$idButton.'" class="mdl-button mdl-js-button mdl-button--icon" >
                                     <i class="mdi mdi-more_vert"></i>
                                 </button>
	                             '.$botones.'
	                         </div>
	                         ';
	        $this->table->add_row($row_col8, $row_col11, $row_col0, $row_col1, $row_col12, $row_col2, $row_col3, $row_col4, $row_col13, $botonGeneral);
	    }
	    $result['tablaEncuestas'] = $this->table->generate();
	    return $result;
	}

	function cambiarEstadoEncuesta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idEncuesta   = _simple_decrypt(_post('idEncuesta'));
	        $condicion    = _simple_decrypt(_post('condicion'));
	        $idTipoEnc    = _decodeCI(_post('idTipoEnc'));
	        $flg_apertura = _post('flg_aperturar');
	        $rpta = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_EDITAR);
	        if(!$rpta) {
	            throw new Exception('No tiene el permiso para editar la encuesta');
	        }
	        $titulo     = $this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta);
	        $data['flg_aperturar'] = 1;
	        $msj = null;
	        if($condicion != ENCUESTA_APERTURADA && $condicion != ENCUESTA_FINALIZADA){
	            throw new Exception(ANP);
	        }
	        if($idEncuesta == null){
	            throw new Exception(ANP);
	        }
	        if($idTipoEnc == null){
	            throw new Exception(ANP);
	        }
	        if($titulo == null){
	            throw new Exception('Ingrese un título a la encuesta');
	        }
	        $arrayUpdate = array(); 
	        $cantPregAlter = $this->m_pregunta->getCantidadPregConAlternativas($idEncuesta);
	        $cantCatePreg  = $this->m_pregunta->getCantidadCategoriaConPreguntas($idEncuesta);
	        if($condicion == ENCUESTA_APERTURADA && $this->m_pregunta->getCantidadPreguntasObligatoriasByEncuesta($idEncuesta) > 0 && 
	           $cantPregAlter['cant_preg_alter'] == $cantPregAlter['cant_preg_enc'] && $cantCatePreg['cant_cate_enc'] == $cantCatePreg['cant_cate_enc_preg']){
	            if($flg_apertura == 0 && $idTipoEnc != TIPO_ENCUESTA_LIBRE){
	                $data = $this->validateCantPregCategorias($idEncuesta);
	                $data['flg_aperturar'] = 0;
	            } else {
	                $data = $this->deleteCategoriasSinPreguntas($idEncuesta);
	            }
	            $arrayUpdate = array('flg_estado'     => $condicion,
                    	             'fecha_apertura' => date('Y-m-d')
                    	            );
	            $flg_estado = $this->m_utils->getById('senc.encuesta', 'flg_estado', 'id_encuesta', $idEncuesta);
	            if($flg_estado == ENCUESTA_STAND_BY){
	                unset($arrayUpdate['fecha_apertura']);
	            }
	            $msj = "Se aperturó";
	        }else if($condicion == ENCUESTA_FINALIZADA){
	            $arrayUpdate = array('flg_estado'   => $condicion,
	                                 'fecha_cierre' => date('Y-m-d')
	            );
	            $this->m_encuesta->updateFlgEncuestaRol($idTipoEnc);
	            $data['error'] = EXIT_SUCCESS;
	            $msj = "Se finalizó";
	        }
	        
	        else if($cantCatePreg['cant_cate_enc'] != $cantCatePreg['cant_cate_enc_preg']){
	            $data['error'] = 2;
	            $data['msj'] = "Todas las categorias deben tener una pregunta";
	        }else if($cantPregAlter['cant_preg_alter'] != $cantPregAlter['cant_preg_enc']){
	            $data['error'] = 2;
	            $data['msj'] = "Todas las preguntas deben tener una alternativa";
	        }else if($this->m_pregunta->getCantidadPreguntasObligatoriasByEncuesta($idEncuesta) <= 0){
	            $data['error'] = 2;
	            $data['msj'] = "Ingrese al menos una pregunta obligatoria";
	        }
	        
	        if($data['error'] == EXIT_SUCCESS){
	            if($condicion == ENCUESTA_APERTURADA && $idTipoEnc != TIPO_ENCUESTA_LIBRE){
	                $data = $this->m_encuesta->finalizaEncuestasByTipo($idTipoEnc);
	            }
	            if($data['error'] == EXIT_SUCCESS){
	                $data = $this->m_encuesta->cambiaEstadoEncuesta($idEncuesta, $arrayUpdate,$idTipoEnc);
	                $encuestas = null;
	                if(_validate_metodo_rol($this->_idRol)){
	                    $encuestas = $this->m_encuesta->getAllEncuestas();
	                }else{
	                    $encuestas = $this->m_encuesta->getAllEncuestasByPersona($this->_idUserSess);
	                }
	                $data += $this->createTableEncuestas($encuestas);
	                $data['msj'] = $msj;
	            }   
	        }
	        
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function redirectEditEncuestaInactiva() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null){
	            throw new Exception(ANP);
	        }
	        $rpta = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_EDITAR);
	        if(!$rpta) {
	            throw new Exception('No tiene el permiso para editar la encuesta');
	        }
	        $data['url'] = base_url().'senc/c_crear_encuesta';
	        $data['error'] = EXIT_SUCCESS;
	        _setSesion(array('id_encuesta_edit' => $idEncuesta));
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage(); 
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function redirectCrearEncuesta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $data['url'] = base_url().'senc/c_crear_encuesta';
	        $data['error'] = EXIT_SUCCESS;
	        _setSesion(array('id_encuesta_edit' => null));
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function bloquearEncuesta(){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        $idTipoEnc  = _decodeCI(_post('idTipoEnc'));
	        if($idEncuesta == null){
	            throw new Exception(ANP);
	        }
	        if($idTipoEnc == null){
	            throw new Exception(ANP);
	        }
	        $rpta = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_EDITAR);
	        if(!$rpta) {
	            throw new Exception('No tiene el permiso para editar la encuesta');
	        }
	        $arrayUpdate = array('flg_estado'     => ENCUESTA_BLOQUEADA,
	                             'fecha_apertura' => null);
	        //Borrar datos del mongo al cambiar a estado bloqueado
	        $returnDelete = $this->m_encuesta->deleteDataEncuestasByEstadoBloqueo($idEncuesta);
	        if($returnDelete == ERROR_MONGO){
	            throw new Exception('No se pudo cambiar de estado.....Vuelva a intentarlo');
	        }
	        $data = $this->m_encuesta->cambiaEstadoEncuesta($idEncuesta, $arrayUpdate,$idTipoEnc);
	        if($data['error'] == EXIT_SUCCESS){
	            $encuestas = null;
	            if(_validate_metodo_rol($this->_idRol)){
	                $encuestas = $this->m_encuesta->getAllEncuestas();
	            }else{
	                $encuestas = $this->m_encuesta->getAllEncuestasByPersona($this->_idUserSess);
	            }
	            $data = $this->m_encuesta->updateFlgEncuestaRol($idTipoEnc);
	            $data += $this->createTableEncuestas($encuestas);
	            $data['msj'] = "Se bloqueó";
	        }
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function redirectVistaPrevia(){
	    $data['msj'] = null;
	    $data['error'] = EXIT_ERROR;
	    try{
	        $idEncuesta = _simpleDecryptInt(_post('encuesta'));
	        if($idEncuesta == null){
	            throw new Exception(ANP);
	        }
	        $this->session->set_userdata(array('idEncuestaVistaPrevia' => $idEncuesta));
	        $data['url'] = base_url().'senc/c_vista_previa';
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function validateCantPregCategorias($idEncuesta){
	    $data['msj'] = null;
	    $data['error'] = EXIT_ERROR;
	    try{
	        $categoriasArray = $this->m_encuesta->getCategoriasByEncuesta($idEncuesta);
	        foreach($categoriasArray as $categoria){
	            $countPreguntas = count($this->m_encuesta->getPreguntasByCategoria($idEncuesta,$categoria->_id_categoria, array(null),null));
	            if($countPreguntas == 0){
	                throw new Exception('Hay una(as) categoría(as) que no tienen preguntas.
	                                     Se borrarán esas categorías si presiona aceptar.
	                                     ¿Desea aperturarla?');
	            }
	        }

	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function deleteCategoriasSinPreguntas($idEncuesta){
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    $arrayCateEncDel = array();
	    try{
	        $categoriasArray = $this->m_encuesta->getCategoriasByEncuesta($idEncuesta);
	        foreach($categoriasArray as $categoria){
	            $countPreguntas = count($this->m_encuesta->getPreguntasByCategoria($idEncuesta,$categoria->_id_categoria, array(null), null));
	            if($countPreguntas == 0){
	                array_push($arrayCateEncDel, array('_id_encuesta'  => $idEncuesta,
	                                                   '_id_categoria' => $categoria->_id_categoria));
	            }
	        }
	        $data = $this->m_encuesta->deleteCateSinPregByEncuesta($arrayCateEncDel);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage(); 
	    }
	    return $data;
	}

	function getTinyUrlEncuesta(){
	    $data['msj']   = null;
	    $data['error'] = null;
	    try{
	        $idEncuestaCrypt = $this->input->post('idEncuesta');
	        $idEncuestaDecry = _decodeCI($idEncuestaCrypt);
	        if($idEncuestaDecry == null){
	            throw new Exception(ANP);
	        }
	        $data['urlTiny'] = $this->get_tiny_url(base_url()."senc/c_encuesta_nueva/c_encuesta?encuesta=".$idEncuestaCrypt);
	    } catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function get_tiny_url($url)  {
	    $ch = curl_init();
	    $timeout = 5;
	    curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}
	
	function setIdSistemaInSession(){
	    $idSistema = _decodeCI(_post('id_sis'));
	    $idRol     = _decodeCI(_post('rol'));
	    if($idSistema == null || $idRol == null) {
	        throw new Exception(ANP);
	    }
	    $data = $this->lib_utils->setIdSistemaInSession($idSistema,$idRol);
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function changeStandByEncuesta() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        $rpta = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_EDITAR);
	        if(!$rpta) {
	            throw new Exception('No tiene el permiso para editar la encuesta');
	        }
            $arrayData = array('flg_estado' => ENCUESTA_STAND_BY); 
	        $data = $this->m_encuesta->cambiaEstadoEncuesta($idEncuesta,$arrayData,null);
	        if($data['error'] == EXIT_SUCCESS){
	            $encuestas = null;
	            if(_validate_metodo_rol($this->_idRol)){
	                $encuestas = $this->m_encuesta->getAllEncuestas();
	            }else{
	                $encuestas = $this->m_encuesta->getAllEncuestasByPersona($this->_idUserSess);
	            }
                $data += $this->createTableEncuestas($encuestas);
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function refreshTable(){
	    $data      = null;
	    $encuestas = null;
        if(_validate_metodo_rol($this->_idRol)){
            $encuestas = $this->m_encuesta->getAllEncuestas();
        }else{
            $encuestas = $this->m_encuesta->getAllEncuestasByPersona($this->_idUserSess);
        }
        $data = $this->createTableEncuestas($encuestas);
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function compartirEncuesta() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        $puedeCompartir = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_COMPARTIR);
	        if(!$puedeCompartir) {
	            throw new Exception(ANP);
	        }
	        $permisos = _post('permisos');
	        $permisos = json_decode($permisos);
	        $jsonNew = '[';
	        foreach ($permisos as $perm) {
	            $permisoDeta = null;
	            if($perm->permiso_editar == 1) {
	                $permisoDeta .= PERMISO_EDITAR;
	            }
	            if($perm->permiso_compartir == 1) {
	                $permisoDeta .= PERMISO_COMPARTIR;
	            }
	            if($perm->permiso_graficos == 1) {
	                $permisoDeta .= PERMISO_GRAFICOS;
	            }
	            $idPers = _decodeCI($perm->id_pers);
	            $jsonNew .= '{"permisos": "'.$permisoDeta.'", "id_pers_compartido": '.$idPers.', "id_pers_comparte" : '.$this->_idUserSess.'},';
	        }
	        $jsonNew = rtrim(trim($jsonNew), ",");
	        $jsonNew .= ']';
	        $data = $this->m_encuesta->actualizarCompartidos($idEncuesta, $jsonNew);
	        $compartidos = $this->m_encuesta->getListadoCompartidosByEncuesta($idEncuesta);
	        $data['tablaCompartidos'] = $this->buildHTML_listadoCompartidos($compartidos);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCompartidos() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        $puedeCompartir = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_COMPARTIR);
	        if(!$puedeCompartir) {
	            throw new Exception(ANP);
	        }
	        $compartidos = $this->m_encuesta->getListadoCompartidosByEncuesta($idEncuesta);
	        $data['tablaCompartidos'] = $this->buildHTML_listadoCompartidos($compartidos);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function buscarUsuarios() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $search = trim(_post('search'));
	        if(strlen($search) < 3) {
	            throw new Exception(ANP);
	        }
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        $puedeCompartir = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_COMPARTIR);
	        if(!$puedeCompartir) {
	            throw new Exception(ANP);
	        }
	        $resultados = $this->m_encuesta->buscarPersonalSinCompartidos($search, $idEncuesta, $this->_idUserSess);
	        $data['tabla_busqueda'] = $this->buildHTML_ResultadosBusqueda($resultados);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function modificarPermisosByPers() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $checked    = _post('checked');
	        $fieldNom   = _post('fieldNom');
	        $idPers     = _decodeCI(_post('idPers'));
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        if($idPers == null || $idPers == $this->_idUserSess) {//no puedes modificar tus propios permisos
	            throw new Exception(ANP);
	        }
	        $permiso = ($fieldNom == 'checkbox1' ? PERMISO_EDITAR : ($fieldNom == 'checkbox2' ? PERMISO_COMPARTIR : ($fieldNom == 'checkbox3' ? PERMISO_GRAFICOS : null) ) );
	        if($permiso == null) {
	            throw new Exception(ANP);
	        }
	        $puedeCompartir = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_COMPARTIR);
	        if(!$puedeCompartir) {
	            throw new Exception(ANP);
	        }
	        $hasPermiso = $this->m_encuesta->check_SitieneElPermiso($idEncuesta, $permiso, $idPers);
	        if($checked == 'true') { //PONE EL CHECK
	            if($hasPermiso) { //Ya tiene el permiso, para que quiere ponerlo otra vez.
	                throw new Exception(null);
	            }
	            $permisosNew = $this->m_encuesta->getCurrentPermisos($idEncuesta, $idPers, $permiso);
	            $jsonNew = '[{"permisos": "'.$permisosNew.'", "id_pers_compartido": '.$idPers.', "id_pers_comparte" : '.$this->_idUserSess.'}]';
	            $data = $this->m_encuesta->quitarCompartido($idEncuesta, $idPers);
	            $data = $this->m_encuesta->actualizarCompartidos($idEncuesta, $jsonNew);
	        } else if($checked == 'false') { //QUITA EL CHECK
	            if(!$hasPermiso) { //Ya NO tiene el permiso, para que quiere quitarlo.
	                throw new Exception(null);
	            }
	            $puedeQuitar = $this->m_encuesta->check_SiPuedesQuitarPermiso($idEncuesta, $idPers, $this->_idUserSess);
	            if(!$puedeQuitar) {
	                throw new Exception('No puedes quitar el permiso del usuario seleccionado, no fuiste tú quien lo asignó.');
	            }
	            $permisosNew = $this->m_encuesta->getPermisosAfterBorrar($idEncuesta, $idPers, $permiso);
	            if($permisosNew == '') {//quito todos los permisos, borrar  todo el JSONB
	                $data = $this->m_encuesta->quitarCompartido($idEncuesta, $idPers);
	            } else {
	                $data = $this->m_encuesta->quitarCompartido($idEncuesta, $idPers);
	                $jsonNew = '[{"permisos": "'.$permisosNew.'", "id_pers_compartido": '.$idPers.', "id_pers_comparte" : '.$this->_idUserSess.'}]';
	                $data = $this->m_encuesta->actualizarCompartidos($idEncuesta, $jsonNew);
	            }
	        }
	        $compartidos = $this->m_encuesta->getListadoCompartidosByEncuesta($idEncuesta);
	        $data['tablaCompartidos'] = $this->buildHTML_listadoCompartidos($compartidos);
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function checkIfHasPermisoCompartir() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = 'No tiene el permiso para compartir esta encuesta.';
	    try {
	        $idEncuesta = _simpleDecryptInt(_post('idEncuesta'));
	        if($idEncuesta == null) {
	            throw new Exception(ANP);
	        }
	        $data['is_ok'] = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_COMPARTIR);
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function checkIfHasPermiso_Aux($idEncuesta, $idPersona, $permiso) {
	    $rpta = false;
	    $audiPersRegi = $this->m_utils->getById('senc.encuesta', 'audi_pers_regi', 'id_encuesta', $idEncuesta);
	    if($idPersona == $audiPersRegi) {
	        $rpta = true;
	    } else {
	        $hasPermiso = $this->m_encuesta->check_SitieneElPermiso($idEncuesta, $permiso, $idPersona);
	        if($hasPermiso) {
	            $rpta = true;
	        }
	    }
	    return $rpta;
	}
	
	function buildHTML_ResultadosBusqueda($resultados) {
	    $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                  data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35]" data-page-size="5"
			                                  id="tbBusqPersonal">',
	                 'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#'           , 'class' => 'text-left');
	    $head_1 = array('data' => 'Nombre'      , 'class' => 'text-left');
	    $head_2 = array('data' => ''  , 'class' => 'text-center', 'data-field' => 'checkbox1');
	    $head_3 = array('data' => ''  , 'class' => 'text-center', 'data-field' => 'checkbox2');
	    $head_4 = array('data' => ''  , 'class' => 'text-center', 'data-field' => 'checkbox3');
	    $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
	    foreach ($resultados as $row) {
	        $imagePersonal = '<img alt="Personal" src="'.$row['foto_persona'].'" width=28 height=28
		                           class="img-circle m-r-10">
		                          <p class="classroom-value" style="display: inline">'.$row['nombres'].'</p>';
	        $row_0 = $row['rnum'];
	        $row_1 = array('data' => $imagePersonal , 'class' => 'text-left', 'data-id_pers' => _encodeCI($row['nid_persona']));
	        $row_2 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle1-'.$row['rnum'].'">
                                          <input type="checkbox" id="icon-toggle1-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermiso($(this));" data-tipo="1">
                                          <i class="mdl-icon-toggle__label mdi mdi-mode_edit"></i>
                                      </label>', 'class' => 'text-center');
	        $row_3 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle2-'.$row['rnum'].'">
                                          <input type="checkbox" id="icon-toggle2-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermiso($(this));" data-tipo="2">
                                          <i class="mdl-icon-toggle__label mdi mdi-group_add"></i>
                                      </label>', 'class' => 'text-center');
	        $row_4 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle3-'.$row['rnum'].'">
                                          <input type="checkbox" id="icon-toggle3-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermiso($(this));" data-tipo="3">
                                          <i class="mdl-icon-toggle__label mdi mdi-insert_chart"></i>
                                      </label>', 'class' => 'text-center');
	        $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
	    }
	    $table = $this->table->generate();
	    return $table;
	}
	
	function buildHTML_listadoCompartidos($compartidos) {
	    $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                  data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35]" data-page-size="5"
			                                  id="tbCompartidos">',
	                 'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => '#'           , 'class' => 'text-left');
	    $head_1 = array('data' => 'Nombre'      , 'class' => 'text-left');
	    $head_2 = array('data' => ''  , 'class' => 'text-center col-xs-1 col-sm-1 col-md-1 col-lg-1', 'data-field' => 'checkbox1');
	    $head_3 = array('data' => ''  , 'class' => 'text-center col-xs-1 col-sm-1 col-md-1 col-lg-1', 'data-field' => 'checkbox2');
	    $head_4 = array('data' => ''  , 'class' => 'text-center col-xs-1 col-sm-1 col-md-1 col-lg-1', 'data-field' => 'checkbox3');
	    $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
	    foreach ($compartidos as $row) {
	        $imagePersonal = '<img alt="Personal" src="'.$row['foto_persona'].'" width=28 height=28
		                           class="img-circle m-r-10">
		                          <p class="classroom-value" style="display: inline">'.$row['nombres'].'</p>';
	        $row_0 = $row['rnum'];
	        $permisos = $row['permisos'];
	        $row_1 = array('data' => $imagePersonal , 'class' => 'text-left cellId_pers', 'data-id_pers' => _encodeCI($row['nid_persona']));
	        $row_2 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle1-'.$row['rnum'].'">
                                          <input type="checkbox" '.((strlen(strstr($permisos, 'e')) > 0) ? 'checked' : null).' id="icon-toggle1-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermisoNow(event, $(this));" data-tipo="1">
                                          <i class="mdl-icon-toggle__label mdi mdi-mode_edit"></i>
                                      </label>', 'class' => 'text-center');
	        $row_3 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle2-'.$row['rnum'].'">
                                          <input type="checkbox" '.((strlen(strstr($permisos, 'c')) > 0) ? 'checked' : null).' id="icon-toggle2-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermisoNow(event, $(this));" data-tipo="2">
                                          <i class="mdl-icon-toggle__label mdi mdi-group_add"></i>
                                      </label>', 'class' => 'text-center');
	        $row_4 = array('data' => '<label class="mdl-icon-toggle mdl-js-icon-toggle mdl-js-ripple-effect" for="icon-toggle3-'.$row['rnum'].'">
                                          <input type="checkbox" '.((strlen(strstr($permisos, 'g')) > 0) ? 'checked' : null).' id="icon-toggle3-'.$row['rnum'].'" class="mdl-icon-toggle__input" onclick="updateCellPermisoNow(event, $(this));" data-tipo="3">
                                          <i class="mdl-icon-toggle__label mdi mdi-insert_chart"></i>
                                      </label>', 'class' => 'text-center');
	        $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
	    }
	    $table = $this->table->generate();
	    return $table;
	}
}