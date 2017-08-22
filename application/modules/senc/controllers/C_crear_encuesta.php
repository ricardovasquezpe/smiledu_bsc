<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_crear_encuesta extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_categoria/m_categoria');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_crear_encuesta');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
        $idEncuesta = _getSesion('id_encuesta_edit');
        $flg_estado = ($idEncuesta != null) ? $this->m_utils->getById('senc.encuesta', 'flg_estado', 'id_encuesta', $idEncuesta, 'senc') : null;
        if($flg_estado != ENCUESTA_BLOQUEADA && $flg_estado != ENCUESTA_CREADA && $flg_estado != null) {
            redirect('c_consultar_encuesta','refresh');
        }
        $rpta = $this->checkIfHasPermiso_Aux($idEncuesta, $this->_idUserSess, PERMISO_EDITAR);
        if(!$rpta) {
            redirect('c_consultar_encuesta','refresh');
        }
    }
    
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']      = 'Administraci&oacute;n';
        $data['return']           = '';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']             = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['barraSec'] = '  <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                                   <a href="#" class="mdl-layout__tab is-active">Nueva Encuesta</a>
                               </div>';
        //NECESARIO
        $idEncuestaSession    = _getSesion('id_encuesta_edit');
        $data['jsonArray']    = json_encode(array());
        $data['idTipoEnc']    = "''";
        $data['titleHeader']  = "Edici&oacute;n";
        $categoriasArray      = null;
        $data['flg_tipoEnc']  = "false";
        $data['comboTipoEnc'] = "";
        $data['arrayTipos']   = json_encode(array());
        _setSesion(array('id_cate_selected' => null));
        if($idEncuestaSession == null) {
            $categoriasArray = $this->m_categoria->getAllCategorias();
            $data['vistaPrevia'] = '<a id="vistaPrevia" onclick="vistaPrevia(null)"
                            				class="mdl-button mdl-js-button mdl-button--icon"> <i
                            				class="mdi mdi-remove_red_eye"></i>
                            			</a>';
            $data['checkedAnonima'] = 'checked';
        } else {
            $data['vistaPrevia'] = '<a id="vistaPrevia" onclick="vistaPrevia(\''._simple_encrypt($idEncuestaSession).'\')"
                        				class="mdl-button mdl-js-button mdl-button--icon"> <i
                        				class="mdi mdi-remove_red_eye"></i>
                        			</a>';
            $categoriasArray = $this->m_categoria->getAllCategoriasByEncuesta($idEncuestaSession);
            $data['jsonArray']      = json_encode($this->buildArrayCatePregByEncuesta($idEncuestaSession));
            //                 $tipoEncuesta        = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuestaSession, 'senc');
            $datos                  = $this->m_utils->getCamposById('senc.encuesta', array('_id_tipo_encuesta','flg_anonima','titulo_encuesta'), 'id_encuesta', $idEncuestaSession);
            $data['idTipoEnc']      = "'"._simple_encrypt($datos['_id_tipo_encuesta'])."'";
            $data['flg_tipoEnc']    = ($datos['_id_tipo_encuesta'] == TIPO_ENCUESTA_LIBRE) ? "true" : "false";
            $data['tituloEnc']      = $datos['titulo_encuesta']/*$this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuestaSession, 'senc')*/;
            $result                 = $this->buildComboTipoEncuestado($idEncuestaSession);
            $data['comboTipoEnc']   = $result[0];
            $data['arrayTipos']     = $result[1];
            $data['displayAnonima'] = ($datos['_id_tipo_encuesta'] != TIPO_ENCUESTA_LIBRE) ? 'none' : 'block';
            $data['checkedAnonima'] = ($datos['flg_anonima'] == FLG_ANONIMA) ? 'checked' : null;
        }
        $data['menu']               = $this->load->view('v_menu', $data, true);
        $data['tbCategoria'] = $this->buildTableCategoriasHTML($categoriasArray);
        if(_validate_metodo_rol($this->_idRol)) {
            $data['tipo_encuesta'] = __buildComboTipoEncuestaSimpleEncrypt();
        } else {
            $tipo_encuestas = array(TIPO_ENCUESTA_LIBRE);
            $data['tipo_encuesta'] = __getOptionTipoEncuestaByIdSimpleDecrypt($tipo_encuestas);
        }
        ///////////
        _setSesion(array('tab_active_config' => null));
        $this->load->view('vf_encuesta/v_crear_encuesta',$data);
    }
   
    /**
     * @author Cesar Villarreal
     * @param arrayCategorias $data
     * @return tablaCategorias
     */
    function buildTableCategoriasHTML($data) {
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-save="true" id="tb_categorias" data-search="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $cont = 0;
        $contChecked = 0;
        foreach($data as $row){
            $cont++;
            $contChecked = ($row->checkcate == 'checked') ? ($contChecked+1) : $contChecked;
            $idCryptCate = _simple_encrypt($row->id_categoria,CLAVE_ENCRYPT);
            //$row_0 = array('data' => $cont);
            $row_1 = array('data' => _ucwords($row->desc_cate));
            $checkCate = $row->checkcate;
            $row_2 = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkCat'.$cont.'">
									     <input type="checkbox"  class="mdl-checkbox__input checkCate" attr-orden="'.$cont.'" id="checkCat'.$cont.'" '.$checkCate.' attr-idcategoria="'.$idCryptCate.'">
									     <span></span>
								     </label>','class' => 'text-center');
            $datos = $this->getDetalleByCategoria($row->id_categoria);
            $row_3 = array('data' => (($datos['obli'] != 0) ? 'S&iacute;' : 'No'));
            $row_4 = array('data' => $datos['parte'].'/'.$datos['todo'],'class' => 'text-right');
            $botonArriba = null;
            $botonAbajo  = null;
            if($row->checkcate == 'checked'){
                $disabledUp    = ($cont == 1) ? 'disabled' : null;
                $disabledDown  = ($cont == count($data)) ? 'disabled' : null;                
                $botonArriba = '<button class="mdl-button mdl-js-button mdl-button--icon up"   attr-orden="'.$cont.'" attr-direccion="1" attr-idcategoria="'.$idCryptCate.'" '.$disabledUp.'><i class="mdi mdi-arrow_drop_up"></i></button>';
                $botonAbajo  = '<button class="mdl-button mdl-js-button mdl-button--icon down" attr-orden="'.$cont.'" attr-direccion="0" attr-idcategoria="'.$idCryptCate.'" '.$disabledDown.'><i class="mdi mdi-arrow_drop_down"></i></button>';               
            }
            $row_5 = array('data' => $botonArriba.$botonAbajo);
            $this->table->add_row(/*$row_0,*/$row_2,$row_1,$row_3,$row_4,$row_5);
        }
        $checkAll = (count($data) == $contChecked) ? 'checked' : null;
        //$head_0 = array('data' => '#');
        $head_1 = array('data' => 'Categor&iacute;a');
        $head_2 = array('data' => /*'<div class="checkbox checkbox-inline checkbox-styled">
									     <label>
										     <input type="checkbox" '.$checkAll.' onclick="activeAllCate($(this));">
										     <span>Todos</span>
									     </label>
								     </div>'*/'Asignar');
        $head_3 = array('data' => 'Obligatoria');
        $head_4 = array('data' => 'Detalle');
        $head_5 = array('data' => 'Orden');
        $this->table->set_heading(/*$head_0,*/$head_2,$head_1,$head_3,$head_4,$head_5);
        $table = $this->table->generate();
        return $table;
    }
    
    /**
     * @author Cesar Villarreal
     * @param arrayPreguntas $data
     * @return tabla0
     */
    function buildTablePreguntas($data){
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-search="false"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-min-size-search="3"
			                                   id="tb_preguntas">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        //$head_0 = array('data' => '#');
        $head_1 = array('data' => 'Pregunta');
        $head_2 = array('data' => 'Asignar','class' => 'text-left', 'data-searchable' => 'false');
        $head_3 = array('data' => '&#191;Obligatorio?','class' => 'text-left', 'data-searchable' => 'false');
        //$head_4 = array('data' => 'Tipo de Pregunta');
        $head_5 = array('data' => 'Tipo', 'data-searchable' => 'false');
        $head_6 = array('data' => 'Orden', 'data-searchable' => 'false');
        $this->table->set_heading($head_2,$head_1,$head_5,$head_3,$head_6);
        $cont = 0;
        foreach($data as $row) {
            $cont++;
            $idCryptPreg = _simple_encrypt($row->id_pregunta,CLAVE_ENCRYPT);
            $info = ($row->_id_tipo_encuesta == TIPO_ENCUESTA_LIBRE) ? '<a id="toolTip'.$cont.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.(($row->desc_tipo_enc != null) ? $row->desc_tipo_enc : 'Todos').'" >
                         <i style="vertical-align:middle" class="mdi mdi-info"></i>
                     </a>' : null;
            $row_1 = array('data' => $info.$row->desc_pregunta, 'click' => 'alert()', 'class' => 'col-xs-6');
            $checked = ($row->checked == 'checked') ? 'checked' : null; 
            $row_2 = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkPreg'.$cont.'">
									     <input type="checkbox"  class="mdl-checkbox__input" attr-orden="'.$cont.'" id="checkPreg'.$cont.'" attr-id_select="selectTipoPregunta'.$cont.'" attr-idcheckObli="flgObli'.$cont.'" onclick="cambioCheckAsignaPregunta(\'checkPreg'.$cont.'\',0);" '.$checked.' attr-idpregunta="'.$idCryptPreg.'">
									     <span></span>
								     </label>', 'class' => 'col-xs-1 text-center');
            $checkObli = $row->checkobli;
            $disabledComboObli = ($row->checked == 'checked') ? null : 'disabled';
            $row_3 = array('data' => '<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="flgObli'.$cont.'" style="margin-left: 12.5%">
                                          <input type="checkbox" class="mdl-switch__input" '.$disabledComboObli.' id="flgObli'.$cont.'" name="" attr-idcheckPreg="checkPreg'.$cont.'" onclick="cambioCheckFlgObligatorio(this);" '.$checkObli.' attr-idpregunta="'.$idCryptPreg.'">
                                          <span class="mdl-switch__label"></span>
                                      </label>' , 'class' => 'text-center col-xs-1');
            $row_5 = array('data' => '<select id="selectTipoPregunta'.$cont.'" data-live-search="true" '.$disabledComboObli.' data-container="body" onchange="setIdTipoPreguntaCombo(this,\''.$idCryptPreg.'\',\'checkPreg'.$cont.'\')" class="form-control pickerButn">'.$this->buildComboTipoPregunta($row->_id_tipo_pregunta).'</select>',
                           'class' => 'col-xs-3');
            //CAMBIAR ORDEN
            //DIRECCION 1 ARRIBA || 0 ABAJO            
            $disabledUp    = ($cont == 1) ? 'disabled' : null;
            $disabledDown  = ($cont == count($data)) ? 'disabled' : null;
            
            $botonArriba = '<button class="mdl-button mdl-js-button mdl-button--icon up"   attr-orden="'.$cont.'" attr-direccion="1" attr-idpregunta="'.$idCryptPreg.'" '.$disabledUp.'><i class="mdi mdi-arrow_drop_up"></i></button>';
            $botonAbajo  = '<button class="mdl-button mdl-js-button mdl-button--icon down" attr-orden="'.$cont.'" attr-direccion="0" attr-idpregunta="'.$idCryptPreg.'" '.$disabledDown.'><i class="mdi mdi-arrow_drop_down"></i></button>';
//             if($cont == 1){
//                 $botonArriba = null;
//             }
//             if($cont == count($data)){
//                 $botonAbajo = null;
//             }
            $row_6 = array('data' => $botonArriba.$botonAbajo);
            $this->table->add_row($row_2,$row_1,$row_5,$row_3,$row_6);
        }
        $table = '<a class="mdl-button mdl-js-button mdl-button--icon" data-upgraded=",MaterialButton" style="float:right; z-index: 10; margin-right: 20px" onclick="openModalAllPreguntas()">
                      <i class="mdi mdi-add"></i>
                  </a>'.
                  $this->table->generate();
        return $table;
    }
    
    /**
     * @author Cesar Villarreal
     * @return Trae tabla preguntas por categoria
     */
    function getPreguntasCategoria(){
        $data['error'] = EXIT_SUCCESS;
        try{
            $idEncuesta = $this->session->userdata('id_encuesta_edit');
            $arraySelected  = (count($this->input->post('arrayCatPreg')) != 0) ? $this->input->post('arrayCatPreg') : array();
            $idCategoriaSel = _simpleDecryptInt($this->input->post('idCategoria'));
            if($idCategoriaSel == null){
                throw new Exception(ANP);
            }
            $this->session->set_userdata(array('id_cate_selected'=>$idCategoriaSel));
            $arrays = $this->getArraysPreguntasCategoria($arraySelected, $idCategoriaSel);
            if($arrays['error'] == EXIT_SUCCESS){
                $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEncuesta,$idCategoriaSel);
                $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cesar Villarreal 
     * @param array $arraySelected
     * @param array $idCategoriaSel
     * @return arrayPreguntasCategorias
     */
    function getArraysPreguntasCategoria($arraySelected,$idCategoriaSel){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $arrayDecryptPregNot = array();
            $arrayDecryptPregIn  = array();
            $arrayPregOblig      = array();
            foreach($arraySelected as $row){
                $idPregunta  = _simpleDecryptInt($row['idPregunta'],CLAVE_ENCRYPT);
                $idCategoria = _simpleDecryptInt($row['idCategoria'],CLAVE_ENCRYPT);
                if($idPregunta == null || $idCategoria == null){
                    throw new Exception(ANP);
                }
                if($idPregunta != null && $idCategoria != null && $idCategoria != $idCategoriaSel){
                    array_push($arrayDecryptPregNot, $idPregunta);
                } else if($idPregunta != null && $idCategoria != null && $idCategoria == $idCategoriaSel){
                    array_push($arrayDecryptPregIn, $idPregunta);
                }
                if($idPregunta != null && $idCategoria != null && $row['flgObli'] == FLG_OBLIGATORIO){
                    array_push($arrayPregOblig, $idPregunta);
                }
            }
            $data['arrayPregNot']  = $arrayDecryptPregNot;
            $data['arrayPregIn']   = $arrayDecryptPregIn;
            $data['arrayPregObli'] = $arrayPregOblig;
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    /**
     * 
     * @author dfloresgonz
     * @since 19.05.2016
     */
    function actualizarTitulo() {
        $data['error'] = EXIT_WARNING;
        $data['msj']   = null;
        try{
            $idEncuestaSession = _getSesion('id_encuesta_edit');
            $tituloEncuesta    = utf8_decode(trim(_post('tituloEncuesta')));
            if(strlen($tituloEncuesta) > 100 ){
                throw new Exception('Asigne un título menor de 100 caracteres');
            }
            if($idEncuestaSession != null) {
                $flgEditar = $this->m_encuesta->verificarTituloEncuesta($tituloEncuesta, $idEncuestaSession);
                if($flgEditar == 0) {//el titulo es distinto se puede editar
                    $arrayUpdate = array('titulo_encuesta' => $tituloEncuesta);
                    $data = $this->m_encuesta->updateEncuesta($arrayUpdate, $idEncuestaSession);
                }
            }
        } catch(Exception $e) {
            $data['arrayJson'] = json_encode($this->buildArrayCatePregByEncuesta($idEncuestaSession));
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cesar Villarreal
     * @return guarda cada accion que se ejecuta al realizar la encuesta
     */
    function saveEncuesta() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $flg_condicionCombo = EXIT_ERROR;
            $flgCondicionBD     = UPDATE_PREG;
            $idEncuestaSession  = _getSesion('id_encuesta_edit');
            $logeoUsario        = _getSesion('nid_persona');
            $nombreCompletoPers = _getSesion('nombre_completo');
            $idCategoria        = _simple_decrypt(_post('idCategoria'));
            $idTipoEncuesta     = _simpleDecryptInt(_post('tipoEncuesta'));
            $arrayData          = _post('arrayCatPreg');
            $check              = (_post('check') == 'true') ? FLG_ANONIMA : FLG_NO_ANONIMA;
            $idPregPaint        = null;
            // 2 Graba desde modal todas las preguntas | 1 por cada check 
            $tipoGrabado    = _post('tipoGrabado');
            $tituloEncuesta = utf8_decode(trim(_post('tituloEncuesta')));
            //VALIDA SI ES UNA NUEVA ENCUESTA O SI ESTA EDITANDO UNA EXISTENTE
            if($idEncuestaSession == null){
                $flgCondicionBD = INSERT_PREG;
                $data['tbPreguntas'] = null;
                if($idTipoEncuesta == null){
                    throw new Exception(ANP);
                }
                $abvrTipoEnc = $this->m_utils->getById('senc.tipo_encuesta', 'abvr', 'id_tipo_encuesta', $idTipoEncuesta, 'senc');
                $correlativo = ($idTipoEncuesta != TIPO_ENCUESTA_LIBRE) ? $this->m_encuesta->getCountEncuestasByTipo($idTipoEncuesta) : null;
                $desc_enc = ($idTipoEncuesta == TIPO_ENCUESTA_DOCENTE) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_ALUMNOS) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_PADREFAM) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_PERSADM) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : 'LIBRE')));
                $arrayInsertEncu = array('_id_tipo_encuesta' => $idTipoEncuesta,
                                         'desc_enc'          => utf8_encode($desc_enc),
                                         'fecha_apertura'    => NULL,
                                         'fecha_cierre'      => NULL, 
                                         'flg_estado'        => ENCUESTA_CREADA,
                                         'audi_pers_regi'    => $logeoUsario,
                                         'audi_nomb_regi'    => $nombreCompletoPers,
                                         'fecha_registro'    => date('Y-m-d'),
                                         'titulo_encuesta'   => $tituloEncuesta,
                                         'flg_anonima'       => $check
                );
                $data = $this->m_encuesta->saveEncuestaInactiva($arrayInsertEncu);
                if($data['error'] == EXIT_SUCCESS){
                    $data['firstTable'] = 1;
                    $data['msj'] = 'Encuesta creada.';
                    $data['encuesta'] = _simple_encrypt($data['idEncuesta']);
                    $categorias = $this->m_categoria->getAllCategorias();
                    $data['tbCategoria'] = $this->buildTableCategoriasHTML($categorias);
                    $result = $this->buildComboTipoEncuestado($idEncuestaSession);
                    $data['optCombos'] = $result[0];
                    $this->session->set_userdata(array('id_encuesta_edit' => $data['idEncuesta']));
                }
            } else {
                if($idTipoEncuesta == null) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('Seleccione un tipo de encuesta');
                }
                $abvrTipoEnc = $this->m_utils->getById('senc.tipo_encuesta', 'abvr', 'id_tipo_encuesta', $idTipoEncuesta, 'senc');
                $correlativo = ($idTipoEncuesta != TIPO_ENCUESTA_LIBRE) ? $this->m_encuesta->getCountEncuestasByTipo($idTipoEncuesta) : null;
                $desc_enc = ($idTipoEncuesta == TIPO_ENCUESTA_DOCENTE) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_ALUMNOS) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_PADREFAM) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : (($idTipoEncuesta == TIPO_ENCUESTA_PERSADM) ? $abvrTipoEnc.'_'.date('Y').'_'.$correlativo : 'LIBRE')));
                $arrayUpdate = array('_id_tipo_encuesta' => $idTipoEncuesta,
                                     'desc_enc'          => ($desc_enc),
                                     'titulo_encuesta'   => $tituloEncuesta,
                                     'flg_anonima'       => (($idTipoEncuesta != TIPO_ENCUESTA_LIBRE) ? FLG_ANONIMA : $check)
                );
                $data   = $this->m_encuesta->updateTipoPreguntaByEncuesta($arrayUpdate,$idEncuestaSession,$idTipoEncuesta);
                $flg_condicionCombo = $data['flg_condicionCombo'];
            }
            
            //VERIFICA SI SE LLENARA SOLO LA ENCUESTA O LLENARA PREGUNTAS TAMBIEN
            $flg_elimina_preg = null;
            $idPregPaint = null;
            if(count($arrayData) != 0) {
                $data = $this->buildArrayInsertPregCatByEncuesta($arrayData,$idEncuestaSession);
                $idPregPaint = $data['idPregUpdate'];
                $flg_elimina_preg = (count($data['arrayDelete']) > 0) ? DELETE_IN_ENC : null; 
                $idEncuesta = $data['idEncuesta'];
                if($data['error'] == EXIT_SUCCESS){
                    //INSERTA EL ARRAY CONSTRUIDO EN LA TABLA pregunta_x_enc_cate
                    $data = $this->m_encuesta->insertaPregCateByEncuesta($data['arrayInsert'],$data['arrayUpdate'],$data['arrayDelete'],$data['pregDelOpt'],$idEncuestaSession);
                    $idCateSelected = _getSesion('id_cate_selected');
                    $data['idCateCrypt']  = _simple_encrypt($idCateSelected);
                    if($tipoGrabado == 2){//GUARDA TODAS LAS PREGUNTAS DESDE EL MODAL O ELIMINA LA PREGUNTA
                        $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEncuesta,$idCateSelected);
                        $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
                        $data['msj']         = 'Se asignar&oacute;n las preguntas';
                    } else if($flg_elimina_preg == DELETE_IN_ENC){
                        $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEncuesta,$idCateSelected);
                        $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
                    }
                 }
                $data['arrayJson'] = json_encode($this->buildArrayCatePregByEncuesta($idEncuestaSession));
            } else {
                $data['tbPreguntas'] = null;
                $data['arrayJson'] = json_encode(array());
            }
            //VISTA PREVIA
            $data['encuesta'] = _simple_encrypt($idEncuestaSession);
            $data['cant_preg'] = $this->m_encuesta->countPregEncuesta($idEncuestaSession);
            $categoriasArray = $this->m_categoria->getAllCategoriasByEncuesta($idEncuestaSession);
            $data['tbCategorias'] = $this->buildTableCategoriasHTML($categoriasArray);
            //////////////
            $data['switchAnonima'] = ($check == FLG_ANONIMA) ? true : false;
            if($idTipoEncuesta != TIPO_ENCUESTA_LIBRE) {
                $data['flg_anonima'] = false;
            }
        } catch(Exception $e) {
            $data['arrayJson'] = json_encode($this->buildArrayCatePregByEncuesta($idEncuestaSession));
            $data['msj'] = $e->getMessage();
        }
        $data['comboDel'] = EXIT_ERROR;
        $data['arrayTipEnc'] = json_encode(array());
        if($flg_condicionCombo == EXIT_SUCCESS && $idTipoEncuesta != TIPO_ENCUESTA_LIBRE) {
            $result = $this->buildComboTipoEncuestado($idEncuestaSession);
            $data['options']     = $result[0];
            $data['arrayTipEnc'] = $result[1];
            $data['comboDel'] = EXIT_SUCCESS;
        }
        $data['pregSelected'] = $idPregPaint;
        $data['flg_tipoEnc'] = (($idTipoEncuesta == 5) ? "true" : "false");
        unset($data['arrayCatEnc']);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cesar Villarreal
     * @param arrayGeneral $arrayData
     * @param int $idEncuesta
     * @return Arrays para insertar, eliminar o actualizar
     */
    function buildArrayInsertPregCatByEncuesta($arrayData,$idEncuesta) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $arrayCategorias = array();
            $arrayInsert = array();
            $arrayUpdate = array();
            $arrayDelete = array();
            $arrayPregDel = array();
            $idCategoria = $this->session->userdata('id_cate_selected');
            $orden = $this->m_crear_encuesta->getLastOrdenPregByEncuestaCategoria($idEncuesta,$idCategoria);
            $pregSelected = null;
            foreach($arrayData as $row){
                $idPregunta  = _simpleDecryptInt($row['idPregunta'],CLAVE_ENCRYPT);
                $idCategoria = _simpleDecryptInt($row['idCategoria'],CLAVE_ENCRYPT);
                if($row['tipo_encuestado'] != null){
                    $idTipEncu   = (_simple_decrypt($row['tipo_encuestado']) == '') ? null : _simple_decrypt($row['tipo_encuestado']);
                } else{
                    $idTipEncu = null;
                }
                $flgObli     = $row['flgObli'];
                $idTipoPregAux = _simpleDecryptInt($row['idTipoPreg']);
                $idTipoPreg  = ($idTipoPregAux != -1 && $idTipoPregAux != '' && $idTipoPregAux != NULL) ? $idTipoPregAux : null;
                if($idPregunta == null){
                    throw new Exception('$idPregunta');
                }
                ////////VALDIAR SI ES PREGUNTA SIN CATEGORIA
                $tipoEncuestado = $this->m_encuesta->getTipoencuestadoByEncuestaPregunta($idEncuesta,$idPregunta);
                if($tipoEncuestado == null) {
                    if($idCategoria == null) {
                        throw new Exception('$idCategoria');
                    }
                }
                ////////////////////////////////////////////
                $arrayRow    = array('_id_encuesta'      => $idEncuesta,
                                    '_id_pregunta'      => $idPregunta,
                                    '_id_categoria'     => $idCategoria,
                                    '_id_tipo_pregunta' => $idTipoPreg,
                                    'orden'             => $orden,
                                    'flg_estado'        => ESTADO_ACTIVO,
                                    'flg_obligatorio'   => $flgObli,
                                    'tipo_encuestado'   => $idTipEncu
                                );
                if($row['cambio'] == '1'){
                    array_push($arrayInsert, $arrayRow);
                    $orden++;
                } else if($row['cambio'] == '2'){
                    unset($arrayRow['orden']);
                    array_push($arrayUpdate, $arrayRow);
                    $pregSelected = _simple_encrypt($arrayRow['_id_pregunta']);
                    break;
                } else if($row['cambio'] == '3'){
                    unset($arrayRow['orden']);
                    unset($arrayRow['_id_tipo_pregunta']);
                    unset($arrayRow['flg_estado']);
                    unset($arrayRow['flg_obligatorio']);
                    array_push($arrayPregDel, $idPregunta);
                    array_push($arrayDelete, $arrayRow);
                }
            }
            $data['arrayInsert'] = $arrayInsert;
            $data['error']       = EXIT_SUCCESS;
            $data['idEncuesta']  = $idEncuesta;
            $data['arrayDelete'] = $arrayDelete;
            $data['arrayUpdate'] = $arrayUpdate;
            $data['pregDelOpt']  = $arrayPregDel;
            $data['idPregUpdate'] = $pregSelected;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @param array $arrayCate
     * @param int $idEncuesta
     * @return Array para insertar a la encuesta por categoria
     */
    function buildArrayInsertCategoriaEncuesta($arrayCate,$idEncuesta){
        $arrayInsert = array();
        $cont = 0;
        foreach($arrayCate as $idCate){
            $cont++;
            array_push($arrayInsert, array('_id_categoria' => $idCate,
                                           '_id_encuesta'  => $idEncuesta,
                                           'orden'         => $cont,
                                           'flg_estado'    => ESTADO_ACTIVO                               
                                          )
                      );
        }
        return $arrayInsert;
    }
    
    /**
     * @author Cï¿½sar Villarreal 
     * @param int $idEncuesta
     * @return Array general preguntas por categoria en encuesta
     */
    function buildArrayCatePregByEncuesta($idEncuesta){
        $data = $this->m_encuesta->getPregCateByEncuesta($idEncuesta);
        $jsonArray = array();
        foreach($data as $row){
            $idPregunta  = _simple_encrypt($row->_id_pregunta,CLAVE_ENCRYPT);
            $idCategoria = _simple_encrypt($row->_id_categoria,CLAVE_ENCRYPT);
            $idTipoPreg  = ($row->_id_tipo_pregunta != null) ? _simple_encrypt($row->_id_tipo_pregunta,CLAVE_ENCRYPT) : null;
            $tipoEncuestado = _simple_encrypt($row->tipo_encuestado);
            $arrayRow = array('idPregunta'      => $idPregunta,
                              'idCategoria'     => $idCategoria,
                              'flgObli'         => $row->flg_obligatorio,
                              'idTipoPreg'      => $idTipoPreg,
                              'cambio'          => '0',
                              'tipo_encuestado' => $tipoEncuestado
                             );
            array_push($jsonArray, $arrayRow);
        }
        return $jsonArray;
    }
    
    
    function validateObliXCategoria($arrayData){
        $arrayAux = array();
        $arrayCategorias = array();
        foreach($arrayData as $row){
            $idCategoria = _simpleDecryptInt($row['idCategoria'],CLAVE_ENCRYPT);
            $flgObli     = $row['flgObli'];
            array_push($arrayAux, array('idCategoria'     => $idCategoria,
                                        'flg_olbigatorio' => $flgObli
                                       )
                      );
            $arrayCategorias[$idCategoria] = null;
        }
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Radio butons para seleccionar tipo de pregunta
     */
    function buildRadioTipoPreg(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            $idTipoPreg = null;
            $idPreg = $this->input->post('preg');
            $idPregDecry = _simpleDecryptInt($idPreg);
            $numPreg = _decodeCI($this->input->post('numPreg'));
            if($idPregDecry == null){
                throw new Exception(ANP);
            }
            if($numPreg == null){
                throw new Exception(ANP);
            }
            if($idEncuestaSession != null){
                $arrayPreg = $this->m_utils->getTipoPreguntaByEncuestaPregunta($idPregDecry,$idEncuestaSession);
                $idTipoPreg = (count($arrayPreg) != 0) ? $arrayPreg['_id_tipo_pregunta'] : $idTipoPreg;
            }
            $data['radio'] = __buildRadioAllTipoPreguntas(('setIdTipoPreguntaCombo(this,\''.$idPreg.'\')'),$idTipoPreg);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj']   = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Retorna codigo HTML para crear los inpus de opciones
     */
    function crearOpcionesPregunta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            $idPreg = _simpleDecryptInt($this->input->post('idPreg'));
            $idTipoPreg = _simpleDecryptInt($this->input->post('idTipoPreg'));
            if($idEncuestaSession == null){
                throw new Exception(ANP);
            }
            if($idPreg == null){
                throw new Exception(ANP);
            }
            if($idTipoPreg == null){
                throw new Exception(ANP);
            }
            $data = $this->buildOpcionesByTipoPregunta($idEncuestaSession, $idPreg,$idTipoPreg);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @param int $idEncuesta
     * @param int $idPreg
     * @param int $idTipoPreg
     * @return retorna codigo para las opciones de cada pregunta
     */
    function buildOpcionesByTipoPregunta($idEncuesta,$idPreg,$idTipoPreg){
        $data['divOpciones'] = '<form id="formAddOpcion">'; 
        $data['arrayOpciones'] = null;
        $arrayOpciones = array();
        $cont = 0;
        $arrayOpciones = array();
        if($idTipoPreg == TIPO_PREG_CASILLA || $idTipoPreg == TIPO_PREG_LISTA || $idTipoPreg == TIPO_PREG_OPT_MULTI){
            $opciones = $this->m_encuesta->getOpcionesByEncuestaPregunta($idEncuesta,$idPreg);
            foreach($opciones as $row){
                $idAlternativaCrypt = _encodeCI($row->id_alternativa);
                $idAlterPregCrypt   = _encodeCI($row->id_alter_x_tipo_preg_x_preg);
                $data['divOpciones'].= '<div class="col-xs-11 p-0">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="opcion'.$cont.'" attr-id_alter_preg="'.$idAlternativaCrypt.'" attr-flg_cambio="0" id="opcion'.$cont.'" value="'.$row->desc_alternativa.'">
                                                <label class="mdl-textfield__label" for="opcion'.$cont.'">Alternativa '.$cont.'</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-1 p-0">
                                            <button class="mdl-button mdl-js-button mdl-button--icon m-0" onclick="deleteOpcionByPreg(\''.$idAlterPregCrypt.'\')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>  
                                        </div>';
                $cont++;
                array_push($arrayOpciones, array('id_alter'    => $idAlternativaCrypt,
                                                 'cambio'      => SIN_CAMBIO,
                                                 'desc_alter' => $row->desc_alternativa) 
                                                );
            }
            $data['divOpciones'] .= '   <div class="col-xs-11 p-0">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" name="opcion'.$cont.'" id="opcion'.$cont.'">
                                                <label class="mdl-textfield__label" for="opcion'.$cont.'">Agregar una opci&oacute;n</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-1 p-0">
                                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--icon m-0">
                                                <i class="mdi mdi-add"></i>
                                            </button>  
                                        </div>
                                    </form>';
        }
        $data['idOptionFocus'] = 'opcion'.$cont;
        $data['arrayOpciones'] = json_encode($arrayOpciones);
        return $data;
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Edita o guarda cada opciï¿½n
     */
    function saveOpcion(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $arrayOpcionesInsert = array();
            $idPreg              = _simpleDecryptInt($this->input->post('idPreg'));
            $idTipoPreg          = _simpleDecryptInt($this->input->post('idTipoPreg'));
            $arrayOpt            = $this->input->post('arrayOpt');
            $idEncuestaSession   = $this->session->userdata('id_encuesta_edit');
            if($idPreg == null){
                throw new Exception(ANP);
            }
            if($idEncuestaSession == null){
                throw new Exception(ANP);
            }
            if($idTipoPreg == null){
                throw new Exception(ANP);
            }
            if($arrayOpt == null){
                throw new Exception("Ingresa una opci&oacute;n");
            }
            $arrayUpdate = array();
            $arrayInsertAlter = array();
            $arrayInsertAlterPreg = array();
            foreach($arrayOpt as $row){
                if(trim($row['desc_alter']) == null){
                    throw new Exception('No debe ingresar datos vacios');
                }
                if(strlen(trim($row['desc_alter'])) > 50){
                    throw new Exception('El máximo de caracteres es 50');
                }
                if($row['cambio'] == INSERT_ACCION){
                    if($row['desc_alter'] != null){
                        $arrayInsertAlter     = array('desc_alternativa' => utf8_decode(_ucfirst(trim($row['desc_alter']))));
                        $arrayInsertAlterPreg = array('_id_tipo_pregunta' => $idTipoPreg,
                                                      '_id_encuesta'      => $idEncuestaSession,
                                                      '_id_pregunta'      => $idPreg
                                                     );
                    }
                } else if($row['cambio'] == UPDATE_IN_ENC){
                    $arrayUpdate = array('id_alternativa'   => _decodeCI($row['id_alter']),
                                         'desc_alternativa' => utf8_decode(trim($row['desc_alter']))
                                        );
                }
            }
            $data = $this->m_encuesta->insertOpcionesByPregunta($arrayInsertAlter,$arrayInsertAlterPreg,$arrayUpdate);
            if($data['error'] == EXIT_SUCCESS){
                $data = $this->buildOpcionesByTipoPregunta($idEncuestaSession, $idPreg, $idTipoPreg);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage(); 
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Elimina la opcion de esa pregunta para esa encuesta
     */
    function deleteOpcionByPregunta(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            $idAlterPreg       = _decodeCI($this->input->post('idAlterPreg'));
            $idPreg            = _simpleDecryptInt($this->input->post('idPreg'));
            $idTipoPreg        = _simpleDecryptInt($this->input->post('idTipoPreg'));
            if($idAlterPreg == null){
                throw new Exception(ANP);
            }
            $data = $this->m_encuesta->deleteOpcionByPregunta($idAlterPreg);
            if($data['error'] == EXIT_SUCCESS){
                $data = $this->buildOpcionesByTipoPregunta($idEncuestaSession, $idPreg, $idTipoPreg);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Inserta o elimina la categoria en la encuesta
     */
    function insertDeleteCategoriaEncuesta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idCate = _simpleDecryptInt($this->input->post('idCat'));
            $idEnc  = $this->session->userdata('id_encuesta_edit');
            $opcion = $this->input->post('opcion');
            $tipoEncuesta = $this->input->post('tipoEncuesta');
            if($opcion != INSERT_IN_ENC && $opcion != DELETE_IN_ENC){
                throw new Exception(ANP);
            }
            if($idCate == null){
                throw new Exception(ANP);
            }
            if($tipoEncuesta == null){
                throw new Exception('Seleccione un tipo de encuesta para guardar los datos');
            }
            if($opcion == DELETE_IN_ENC){
                $this->session->set_userdata(array('id_cate_selected' => null));
            } else{
                $this->session->set_userdata(array('id_cate_selected' => $idCate));
            }
            $orden = null;
            if($opcion == INSERT_IN_ENC){
                $orden = $this->m_crear_encuesta->getLastOrdenCateByEncuesta($idEnc);
            } else{
                $orden = $this->m_crear_encuesta->getOrdenCategoriaByEncuesta($idEnc,$idCate);
            }
            $arrayData = array('_id_encuesta'  => $idEnc,
                               '_id_categoria' => $idCate,
                               'orden'         => $orden,
                               'flg_estado'    => ESTADO_ACTIVO
                              );
            $data = $this->m_encuesta->insertCategoriaEncuesta($arrayData,$opcion);
            if($data['error'] == EXIT_SUCCESS){
                $categorias = $this->m_categoria->getAllCategoriasByEncuesta($idEnc);
                $data['tbCategorias'] = $this->buildTableCategoriasHTML($categorias);
                if($opcion == INSERT_IN_ENC){
                    $data['idCateSel'] = _simple_encrypt($idCate);
                }
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Registra una categoria y la guarda en la encuesta
     */
    function agregarCategoria(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $descCate = utf8_decode(trim($this->input->post('desc_cate')));
            $idEnc    = $this->session->userdata('id_encuesta_edit');
            $arraySelected  = (count($this->input->post('arrayCatPreg')) != 0) ? $this->input->post('arrayCatPreg') : array();
            if($descCate == null){
                throw new Exception('Escribe una categorï¿½a');
            }
            if(strlen($descCate) > 50){
                throw new Exception('La descripciï¿½n no debe superar los 50 caracteres');
            }
            $idCategoria = $this->m_categoria->getIdByDescripcion($descCate);
            $orden = $this->m_crear_encuesta->getLastOrdenCateByEncuesta($idEnc);
            if($idCategoria == null){
                $arrayInsert = array('desc_cate' => _ucfirst($descCate));
                $data = $this->m_categoria->insertCategoria($arrayInsert);
                if($data['error'] == EXIT_SUCCESS){
                    $arrayInsert = array('_id_encuesta'  => $idEnc,
                                         '_id_categoria' => $data['idCate'],
                                         'orden'         => $orden,
                                         'flg_estado'    => ESTADO_ACTIVO
                                        );
                    $idCategoria = $data['idCate'];
                    $data = $this->m_encuesta->insertCategoriaEncuesta($arrayInsert, INSERT_IN_ENC);
                }
            } else{
                $flg_existeCateEnc = $this->m_categoria->verificaExisteCategoriaInEncuesta($idEnc,$idCategoria);
                if($flg_existeCateEnc == 0){
                    $arrayInsert = array('_id_encuesta'  => $idEnc,
                                         '_id_categoria' => $idCategoria,
                                         'orden'         => $orden,
                                         'flg_estado'    => ESTADO_ACTIVO
                                        );
                    $data = $this->m_encuesta->insertCategoriaEncuesta($arrayInsert, INSERT_IN_ENC);
                } else{
                    $data['error'] = EXIT_SUCCESS;
                }
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['arrayJson']    = json_encode($this->buildArrayCatePregByEncuesta($idEnc));
                $categorias = $this->m_categoria->getAllCategoriasByEncuesta($idEnc);
                $data['tbCategorias'] = $this->buildTableCategoriasHTML($categorias);
                $arrays = $this->getArraysPreguntasCategoria($arraySelected, $idEnc);
//                 $preguntas = $this->m_categoria->getPreguntasNotInArray($arrays['arrayPregNot'],$arrays['arrayPregIn'],$arrays['arrayPregObli'],$idEnc);
                $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEnc,$idCategoria);
                $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
                $this->session->set_userdata(array('id_cate_selected' => $idCategoria));
                $data['idCate']  = _simple_encrypt($idCategoria);
            }
        } catch (Exception $e){
            $data['msj']   = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Registra pregunta y la guarda en la encuesta
     */
    function agregarPregunta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $descPreg = $this->input->post('desc_preg');
            if($descPreg == null){
                throw new Exception('Escribe una pregunta');
            }
            $descPreg = utf8_decode(trim($descPreg));
            $idCategoriaSelect = _simpleDecryptInt($this->input->post('idCate'));
            $idEnc    = $this->session->userdata('id_encuesta_edit');
            $arraySelected  = (count($this->input->post('arrayCatPreg')) != 0) ? $this->input->post('arrayCatPreg') : array();
            if($idCategoriaSelect == null){
                throw new Exception(ANP);
            }
            if(strlen($descPreg) > 200){
                throw new Exception('La descripciï¿½n no debe superar los 200 caracteres');
            }
            $idPregunta = $this->m_pregunta->getIdByDescPregunta($descPreg);
            $orden = $this->m_crear_encuesta->getLastOrdenPregByEncuestaCategoria($idEnc,$idCategoriaSelect);
            if($idPregunta == null) {
                $descPreg = str_replace("\"", "'", $descPreg);
                $arrayInsert = array('desc_pregunta' => _ucfirst($descPreg),
                                     'flg_estado'    => ESTADO_ACTIVO
                                    );
                $data = $this->m_pregunta->insertPregunta($arrayInsert);
                if($data['error'] == EXIT_SUCCESS){
                    $arrayInsert = array('_id_encuesta'  => $idEnc,
                                         '_id_categoria' => $idCategoriaSelect,
                                         '_id_pregunta'  => $data['idPreg'],
                                         'orden'         => $orden,
                                         'flg_estado'    => ESTADO_ACTIVO
                                        );
                    $idPregunta = $data['idPreg'];
                    $data = $this->m_encuesta->insertPreguntaEncuesta($arrayInsert);
                }
            } else{
                $flg_existePregEnc = $this->m_pregunta->verificaExistePreguntaInEnc($idEnc,$idPregunta);
                if($flg_existePregEnc == 0){
                    $arrayInsert = array('_id_encuesta'  => $idEnc,
                                         '_id_categoria' => $idCategoriaSelect,
                                         '_id_pregunta'  => $idPregunta,
                                         'orden'         => $orden,
                                         'flg_estado'    => ESTADO_ACTIVO
                                        );
                    $data = $this->m_encuesta->insertPreguntaEncuesta($arrayInsert);
                } else{
                    $data['msj']   = "Esa pregunta ya ha sido registrada";
                    $data['error'] = EXIT_SUCCESS;
                }
            }
            if($data['error'] == EXIT_SUCCESS){
                $data['arrayJson']    = json_encode($this->buildArrayCatePregByEncuesta($idEnc));
                array_push($arraySelected, array('idCategoria' => _simple_encrypt($idCategoriaSelect),
                                                 'idPregunta'  => _simple_encrypt($idPregunta),
                                                 'idTipoPreg'  => null,
                                                 'flgObli'     => '0',
                                                 'cambio'      => '0'
                                                ));
                $arrays = $this->getArraysPreguntasCategoria($arraySelected, $idCategoriaSelect);
                $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEnc,$idCategoriaSelect);
                $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
                $categoriasArray = $this->m_categoria->getAllCategoriasByEncuesta($idEnc);
                $data['tbCategorias'] = $this->buildTableCategoriasHTML($categoriasArray);
                $data['idCateSelected'] = _simple_encrypt($idCategoriaSelect);
                $this->session->set_userdata(array('id_cate_selected' => $idCategoriaSelect));
            }
        } catch (Exception $e){
            $data['msj']   = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cï¿½sar Villarreal
     * @return Redirecciona a la vista previa de esa encuesta
     */
    function redirectVistaPrevia(){
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try{
            $idEncuesta = _simpleDecryptInt($this->input->post('encuesta'));
            if($idEncuesta == null){
                throw new Exception(ANP);
            }
            $idTipoEncuesta = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuesta, 'senc');
            if($idTipoEncuesta == TIPO_ENCUESTA_LIBRE){
                $cond = $this->m_encuesta->verificaTieneTipoEncuestadosByEncuesta($idEncuesta);
                if($cond == 0){
                    throw new Exception('Debe asignar al menos un tipo de encuestado');
                }
            }
            $this->session->set_userdata(array('idEncuestaVistaPrevia' => $idEncuesta));
            $data['url'] = base_url().'senc/c_vista_previa';
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function getTableTipoEncuestado(){
        $idEncuesta = $this->session->userdata('id_encuesta_edit');
        $idEncuestaCrypt = _encodeCI($this->session->userdata('id_encuesta_edit'));
        $listaEncuestados = $this->m_encuesta->getAllTipoEncuestados($idEncuesta);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-save="true" id="tb_tipo_enc">',
                                       'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Tipo de Encuestado','class'=> 'text-center');
        $head_2 = array('data' => 'Pregunta');
        $head_3 = array('data' => 'Asignar','class'=> 'text-center');
        $this->table->set_heading($head_3,$head_1/*,$head_2*/);
        $cont = 0;
        foreach($listaEncuestados as $row){
            $cont++;
            $idTipEncCrypt = _encodeCI($row->id_tipo_encuestado);
            $row_1 = array('data' => $row->desc_tipo_enc ,'class'=> 'text-center');            
            $row_2 = array('data' => '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                          <input class="mdl-textfield__input" type="text" id="descPregunta">
                                          <label class="mdl-textfield__label" for="descPregunta"></label>
                                      </div>');
            $row_3 = array('data' => '<div class="checkbox checkbox-inline checkbox-styled">
									     <label>
										     <input type="checkbox" '.$row->checktipoenc.' onclick="setTipoEncuestadoByEncuesta(\''.$idEncuestaCrypt.'\',\''.$idTipEncCrypt.'\',$(this))">
										     <span></span>
									     </label>
								     </div>' ,'class'=> 'text-center');
            $this->table->add_row($row_3,$row_1/*,$row_2*/);
        }
        $tabla = $this->table->generate();
        echo $tabla;
    }
    
    function saveTipoEncuestadoXPregunta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEncuesta       = _decodeCI($this->input->post('idEncuesta'));
            $idTipoEncuestado = _decodeCI($this->input->post('idTipoEncuestado'));
            $checked          = $this->input->post('checked');
            if($idEncuesta == null || $idTipoEncuestado == null){
                throw new Exception(ANP);
            }
            $arrayInsert = array('_id_encuesta'        => $idEncuesta,
                                 '_id_tipo_encuestado' => $idTipoEncuestado
                                );
            $this->m_encuesta->insertTipoEncuestadoByEncuesta($arrayInsert,$checked);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboTipoPregunta($idTipoPreg){
        $listaTipoPreg = $this->m_pregunta->getAllTipoPreguntas($idTipoPreg);
        $opt = '<optgroup>
                    <option value="'._simple_encrypt(-1).'">Seleccione</option>';
        foreach($listaTipoPreg as $row){
            $idTipoPregEncry = _simple_encrypt($row->id_tipo_pregunta);
            $opt .= '<option value="'.$idTipoPregEncry.'" '.$row->selected.'>'.$row->desc_tipo_preg.'</option>';
        }
        $opt .= '</optgroup>
                 <optgroup>
                     <option data-icon="glyphicon glyphicon-pencil" value="'._simple_encrypt(0).'">Editar</option>
                 </optgroup>';
        return $opt;
    }
    
    function buildRadioTipoEncuestado(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try{
            $idEncuesta = $this->session->userdata('id_encuesta_edit');
            $idPreguntaCrypt = $this->input->post('idPregunta');
            $idPregunta = _simpleDecryptInt($idPreguntaCrypt);
            if($idPregunta == null){
                throw new Exception(ANP);
            }
            $desc_pregunta = $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $idPregunta, 'senc');            
            $idTipEncCrypt = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuesta, 'senc');
            if($idTipEncCrypt != TIPO_ENCUESTA_LIBRE){
                //FLG PARA QUE NO HAGA NADA CUANDO ES TIPO LIBRE
                $data['error'] = 2;
                throw new Exception('');
            }
            $listaTipoEnc = $this->m_encuesta->getAllTipoEncuestadosByEncuestaPregunta($idEncuesta,$idPregunta);
            if(count($listaTipoEnc) == 0){
                throw new Exception('Asigne tipo de encuestados');
            }
            $desc_pregunta = str_replace("'", "", $desc_pregunta);
            $radio = '<div class="col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                          <label class="radio-inline radio-styled" style="margin-bottom: 10px">
						  <input type="radio" name="radioVals" checked onclick="setTipoEncuestadoByPregunta(\''.$idPreguntaCrypt.'\',\''._simple_encrypt(null).'\',\'Todos\',\''.$desc_pregunta.'\')">
                          <span>Todos</span>
					  </label></div>';
            foreach($listaTipoEnc as $row){
                $idTipEncCrypt = _simple_encrypt($row->abvr_tipo_enc);
                $radio .= '<div class="col-sm-5 col-sm-offset-1 col-xs-10 col-xs-offset-1">
                          <label class="radio-inline radio-styled" style="margin-bottom: 10px">
						  <input type="radio" '.$row->checked_tipo_enc.' name="radioVals" onclick="setTipoEncuestadoByPregunta(\''.$idPreguntaCrypt.'\',\''.$idTipEncCrypt.'\',\''.$row->desc_tipo_enc.'\',\''.$desc_pregunta.'\')">
                          <span>'.$row->desc_tipo_enc.'</span>
					  </label></div>';
            }
            $data['contTipoEnc'] = $radio;
            $data['error'] = EXIT_SUCCESS;
        }catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //flg_open_modal = 1 ABRIR | flg_open_modal = 0 CERRAR
    function getDataOptionByTipo(){
        $idEncuesta = $this->session->userdata('id_encuesta_edit');
        $data['flg_open_modal'] =  0;
        $idOption = _simpleDecryptInt($this->input->post('idOption'));
        $idPreg   = _simpleDecryptInt($this->input->post('idPregunta'));
        if($idOption == TIPO_PREG_DOS_OPT || $idOption == TIPO_PREG_OPT_MULTI || $idOption == TIPO_PREG_LISTA){
            $data['flg_open_modal'] =  1;
            $data += $this->buildOpcionesByTipoPregunta($idEncuesta, $idPreg,$idOption);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    //flg_mostrarModal = 0 NO ABRE MODAL   |   flg_mostrarModal = 1 ABRE MODAL
    //flg_saveArray = 0 NO GUARDA EN ARRAY |   flg_saveArray = 1 GUARDA EN ARRAY
    function getOptionsByPregunta(){
        $data['flg_mostrarModal'] = 0;
        $data['flg_saveArray']    = 0;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            $idOptionSelected = _simpleDecryptInt($this->input->post('idOptionSel'));
            $idPregunta       = _simpleDecryptInt($this->input->post('idPregunta'));
            if($idOptionSelected == null || $idPregunta == null){
                throw new Exception(ANP);
            }
            $datos = $this->m_pregunta->getExisteTipoPregByPregunta($idEncuestaSession, $idPregunta);
            //SELECCIONAR OPCION EDITAR
            if($idOptionSelected == 0){
                //datos['cuenta'] = 1 EXISTE
                if($datos['cuenta'] == 1 && ($datos['_id_tipo_pregunta'] == TIPO_PREG_CASILLA || $datos['_id_tipo_pregunta'] == TIPO_PREG_OPT_MULTI || $datos['_id_tipo_pregunta'] == TIPO_PREG_LISTA)){
                    $data['flg_mostrarModal'] = 1;
                    $data += $this->buildOpcionesByTipoPregunta($idEncuestaSession, $idPregunta,$datos['_id_tipo_pregunta']);
                }
                $idOptionSelected = $datos['_id_tipo_pregunta'];
            } else if($idOptionSelected == TIPO_PREG_CASILLA || $idOptionSelected == TIPO_PREG_OPT_MULTI || $idOptionSelected == TIPO_PREG_LISTA){//SELECCIONAR OTRA OPCION
                $data['flg_mostrarModal'] = 1;
                $data['flg_saveArray']    = 1;
                if($datos['cuenta'] == 1){
                    $this->m_pregunta->updateTipoPreguntaByPregunta($idEncuestaSession,$idPregunta,$idOptionSelected);
                }
                $data += $this->buildOpcionesByTipoPregunta($idEncuestaSession, $idPregunta,$idOptionSelected);
            } else{
                $data['flg_saveArray']    = 1;
                if($datos['cuenta'] == 1){
                    $this->m_pregunta->updateTipoPreguntaByPregunta($idEncuestaSession,$idPregunta,$idOptionSelected);
                }
            }
            $data['tipoPreguntaSelected'] = _simple_encrypt($idOptionSelected);
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboTipoEncuestado($idEncuestaSession){
        $data = null;
        $listaEncuestados = $this->m_encuesta->getAllTipoEncuestados($idEncuestaSession);
        $opt = null;
        $arrayTipEnc = array();
        foreach($listaEncuestados as $row){
            $idTipoEncuestadoCrypt = _simple_encrypt($row->id_tipo_encuestado);
            $opt .= '<option value="'.$idTipoEncuestadoCrypt.'" '.$row->select_tipo_enc.'>'.$row->desc_tipo_enc.'</option>';
            if($row->select_tipo_enc == 'selected'){
                array_push($arrayTipEnc, $idTipoEncuestadoCrypt);
            }
        }
        return array($opt,json_encode($arrayTipEnc));
    }
    
    function saveTipoEncuestadoXPregunta2(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            $idTipoEncuestado  = _simpleDecryptInt($this->input->post('idTipoEncuestado'));
            $selected          = $this->input->post('selected');
            if($idEncuestaSession == null || $idTipoEncuestado == null){
                throw new Exception(ANP);
            }
            $arrayInsert = array('_id_encuesta'        => $idEncuestaSession,
                                 '_id_tipo_encuestado' => $idTipoEncuestado
                                );
            $data = $this->m_encuesta->insertTipoEncuestadoByEncuesta($arrayInsert,$selected);
            if($data['error'] == EXIT_SUCCESS){
                $result = $this->buildComboTipoEncuestado($idEncuestaSession);
                $data['arrayTipEnc'] = ($result[1]);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDetalleByCategoria($idCategoria){
        $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
        $detalle = $this->m_categoria->getDetalleByCategoria($idEncuestaSession, $idCategoria);
        return $detalle;
    }
    
    function buildOptionsByPreguntaEncuesta(){
        $data = null;
        $idPregunta = _simpleDecryptInt($this->input->post('idPregunta'));
        $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
        try{
            if($idPregunta == null){
                throw new Exception(ANP);
            }
            $idTipoPregunta = $this->m_encuesta->getTipoPreguntaByPreguntaEncuesta($idEncuestaSession,$idPregunta);
            $data = $this->buildComboTipoPregunta($idTipoPregunta);
        }catch(Exception $e){
            
        }
        echo $data;
    }
    
    function getAllPreguntasSinAsignar(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            if($idEncuestaSession == null){
                throw new Exception(ANP);
            }
            $preguntas = $this->m_crear_encuesta->getAllPreguntasNoAsignadasByEncuesta($idEncuestaSession);
            $data['tbAllPreg'] = $this->buildTableAllPreguntas($preguntas);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildTableAllPreguntas($preguntas){
        $tmpl = array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" data-search="true"
			                                  data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-min-size-search="3"
			                                  id="tb_all_preguntas">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_1 = array('data' => 'Pregunta');
        $head_2 = array('data' => 'Asignar','class' => 'text-left', 'data-searchable' => 'false');
        $this->table->set_heading($head_2,$head_1);
        $cont = 0;
        foreach($preguntas as $row){
            $cont++;
            $idCryptPreg = _simple_encrypt($row->id_pregunta);
            $row_col0 = array('data' => $row->desc_pregunta);
            $row_col1 = array('data' => '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkPregAll'.$cont.'">
										     <input type="checkbox"  class="mdl-checkbox__input" attr-orden="'.$cont.'" id="checkPregAll'.$cont.'" attr-idpregunta="'.$idCryptPreg.'"
                                                   onclick="asignaRemuevePregunta($(this),\''.$idCryptPreg.'\')">
										     <span></span>
									     </label>','class' => 'text-center');
            $this->table->add_row($row_col1,$row_col0);
        }
        return $this->table->generate();
    }
    
    function changeOrdenPregunta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden        = $this->input->post('orden');
            $idPregSelect = _simpleDecryptInt($this->input->post('idPreg'));
            $direccion    = $this->input->post('direccion');
            $idEncuesta   = $this->session->userdata('id_encuesta_edit');
            $idCategoria  = $this->session->userdata('id_cate_selected');
            if($direccion != 1 && $direccion != 0){
                throw new Exception();
            }
            if($idPregSelect == null){
                throw new Exception(ANP);
            }
            $idPregChange = $this->m_encuesta->getPrevNextPregunta($idEncuesta,$idCategoria,$orden,$direccion);
            if($idPregChange == null){
                throw new Exception(ANP);
            }
            //ACTUALIZA EL SIGUIENTE O ANTERIOR PREGUNTA
            $arrayUpdate1 = array('_id_pregunta'  => $idPregChange,
                                  '_id_categoria' => $idCategoria,
                                  '_id_encuesta'  => $idEncuesta,
                                  'orden'         => $orden
                                 );
            //CAMBIA EL NUEVO ORDEN PARA LA PREGUNTA SELECCIONADA
            $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array('_id_pregunta'  => $idPregSelect,
                                  '_id_categoria' => $idCategoria,
                                  '_id_encuesta'  => $idEncuesta,
                                  'orden'         => $orden
                                 );
            $data = $this->m_encuesta->updateOrdenByPregunta($arrayUpdate1,$arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS){
                $preguntas = $this->m_crear_encuesta->getAllPreguntasByCateEnc($idEncuesta,$idCategoria);
                $data['tbPreguntas'] = $this->buildTablePreguntas($preguntas);
            }
            $data['pregSelected'] = _simple_encrypt($idPregSelect);
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function changeOrdenCategoria(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $orden        = $this->input->post('orden');
            $idCateSelect = _simpleDecryptInt($this->input->post('idCate'));
            $direccion    = $this->input->post('direccion');
            $idEncuesta   = $this->session->userdata('id_encuesta_edit');
            if($direccion != 1 && $direccion != 0){
                throw new Exception();
            }
            if($idCateSelect == null){
                throw new Exception(ANP);
            }
            $idCateChange = $this->m_encuesta->getPrevNextCategoria($idEncuesta,$orden,$direccion);
            if($idCateChange == null){
                throw new Exception(ANP);
            }
            //ACTUALIZA EL SIGUIENTE O ANTERIOR PREGUNTA
            $arrayUpdate1 = array('_id_categoria' => $idCateChange,
                                  '_id_encuesta'  => $idEncuesta,
                                  'orden'         => $orden
                                 );
            //CAMBIA EL NUEVO ORDEN PARA LA PREGUNTA SELECCIONADA
            $orden = ($direccion == 1) ? $orden - 1 : $orden + 1 ;
            $arrayUpdate2 = array('_id_categoria' => $idCateSelect,
                                  '_id_encuesta'  => $idEncuesta,
                                  'orden'         => $orden
                                 );
            $data = $this->m_encuesta->updateOrdenByCategoria($arrayUpdate1,$arrayUpdate2);
            if($data['error'] == EXIT_SUCCESS){
                $categorias = $this->m_categoria->getAllCategoriasByEncuesta($idEncuesta);
                $data['tbCategorias'] = $this->buildTableCategoriasHTML($categorias);
                $data['idCate'] = _simple_encrypt($idCateSelect);
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveAnonimaEncuesta(){
        $data['error'] = EXIT_WARNING;
        $data['msj']   = null;
        try{
            $checked           = $this->input->post('check');
            $idEncuestaSession = $this->session->userdata('id_encuesta_edit');
            if($checked != 'true' && $checked != 'false'){
                throw new Exception(ANP);
            }
            if($idEncuestaSession != null) {
                $tipoEncuesta = $this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuestaSession, 'senc');
                if($tipoEncuesta == TIPO_ENCUESTA_LIBRE){
                    $checked = ($checked == 'true') ? FLG_ANONIMA : FLG_NO_ANONIMA;
                    $arrayUpdate = array('flg_anonima' => $checked);
                    $data = $this->m_encuesta->updateEncuesta($arrayUpdate, $idEncuestaSession);
                }
            }
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}