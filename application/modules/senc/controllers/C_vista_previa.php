<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_vista_previa extends CI_Controller {

    private $_idUserSess = null;
    private $_idRol      = null;
    
    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('mf_pregunta/m_pregunta');
        $this->load->model('m_utils');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_SENC, null, SENC_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(SENC_ROL_SESS);
    }
   
    public function index() {
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_SENC, SENC_FOLDER);
        ////Modal Popup Iconos///
        $data['titleHeader']                = 'Administraci�n';
        $data['ruta_logo']        = MENU_LOGO_SENC;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_SENC;
        $data['nombre_logo']      = NAME_MODULO_SENC;
        //MENU
        $rolSistemas              = $this->m_utils->getSistemasByRol(ID_SISTEMA_SENC, $this->_idUserSess);
        $data['apps']               = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu']             = $this->load->view('v_menu', $data, true);
        //NECESARIO
        $idEncuesta = $this->session->userdata('idEncuestaVistaPrevia');
        $tipoEnc = ($this->m_utils->getById('senc.encuesta', '_id_tipo_encuesta', 'id_encuesta', $idEncuesta, 'senc'));
        if($tipoEnc == TIPO_ENCUESTA_LIBRE){
            $data['arraTipoEncuestadoHTML'] = $this->getTipoEncuestadoHTML();
            $data['display'] = 'block';
        } else{
            $data['display'] = 'block';
            //$data += $this->getCategoriasHTML($idEncuesta);
        }
        $data['arraTipoEncuestadoHTML'] = $this->getTipoEncuestadoHTML();
        $data['titulo'] = _ucwords(strtolower($this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta, 'senc')));
        $data['tipoEncuesta'] = $this->m_utils->getById('senc.tipo_encuesta', 'desc_tipo_encuesta', 'id_tipo_encuesta', $tipoEnc, 'senc');
        $data['estado'] = ($this->m_utils->getById('senc.encuesta', 'flg_estado', 'id_encuesta', $idEncuesta, 'senc'));
        $data['idEnc']  = _simple_encrypt($idEncuesta);
        $tipoEnc = $this->m_utils->getById("senc.encuesta", "_id_tipo_encuesta", "id_encuesta", $idEncuesta, "senc");
        $data['tipoEnc'] = $tipoEnc;
        if($tipoEnc == TIPO_ENCUESTA_LIBRE){
            $data['msj_especial'] = "Aperturar Encuesta - LIBRE";
        }else{
            $descTipoEnc = $this->m_utils->getById("senc.tipo_encuesta", "desc_tipo_encuesta", "id_tipo_encuesta", $tipoEnc, "senc");
            $data['msj_especial'] = "Aperturar Encuesta ".$descTipoEnc." - Se finalizar�n todas las encuestas del tipo ".$descTipoEnc." &iquest;Est&aacute;s seguro de aperturarla?";
        }
        $data['idEnc']  = _simple_encrypt($idEncuesta);
        ///////////
        $this->session->set_userdata(array('tab_active_config' => null));
        $this->load->view('v_vista_previa', $data);
    }
    
    /**
     * @author Fernando Luna 15/04/16
     * @return Crea codigo HTML de cada tipo de encuestado a seleccionar para la encuesta
     */
    public function getTipoEncuestadoHTML(){
        $idEncuesta = $this->session->userdata('idEncuestaVistaPrevia');
        $tipoEncuestadoArray = $this->m_encuesta->getCantTipoEncuestadoByIdEnc($idEncuesta);
        $tipoEncuestadoHTML = null;
        $contEncuestado = 0;
        foreach($tipoEncuestadoArray as $rowTipoEncuestado){
            $contEncuestado++;
            $tipoEncuestadoHTML .= '<div id="cont'.$contEncuestado .'" class="btn-services" onclick="selectTipoEncuestado(\''.$rowTipoEncuestado->abvr_tipo_enc.'\', this);">
    					               <a class="item-select" name="tipo_encuestado" attr-flg_active="0">'.$rowTipoEncuestado->desc_tipo_enc.'</a>                                           
    						        </div>';
        }
        return $tipoEncuestadoHTML;
    }
    
    function getPreguntasCategoriasEncuesta(){
        $tipoEncuestado = $this->input->post('tipoEncuGlobal');
        $this->session->set_userdata(array('tipoEncuGlobal' => $tipoEncuestado));
        $idEncuesta = $this->session->userdata('idEncuestaVistaPrevia');
        $data = $this->getCategoriasHTML($idEncuesta);
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getCategoriasHTML($idEncuesta) {
        $data['error'] = EXIT_ERROR;
        $tipoEncGlobal= $this->session->userdata('tipoEncuGlobal');
        $idArrayServ = array(null);
        $categoriaArray = $this->m_encuesta->getCategoriasByEncuesta($idEncuesta);
        try{
               $categoriaHTML = null;
               $preguntasHTML = null;
               $cont = 0;
               $cont_con_preg = 0;
               $arrObJSON = array();
               $arrCateObliJSON = array();
               if(count($categoriaArray) == 0){
                   throw new Exception('No hay nada');
               }
               $data['idCategMainGalleryFirst'] = $categoriaArray[0]->_id_categoria;
               //RECORRE CATEGORIAS DE LA ENCUESTA
               foreach($categoriaArray as $row) {
                   array_push($arrCateObliJSON, array("id_cate"       => $row->_id_categoria,
                                                       "cntObliAct"   => 0,
                                                       "flg_pasoCate" => 0));
//                    $cantPregOblixCate = $this->m_encuesta->getCantPregOblixCate($row->_id_categoria,$idEncuesta,$idArrayServ);
//                    $activo    = ($cont == 0) ? 'active' : null;
//                    $tabActive = ($cont == 0) ? 'is-active' : null;
//                    $preguntasHTML .= '<div class="mdl-tabs__panel '.$tabActive.'" id="categoria'.$cont.'"> 
//                                         <div class="main-gallery'.$row->_id_categoria.'" >';
//                    $categoriaHTML .= '<a href="#categoria'.$cont.'" id="c_'.$cont.'" class="mdl-tabs__tab is-'.$activo.'"
//                                         onclick="setIndexCategoria('.$cont.')">'.$row->desc_cate.'</a>';
                   //SELECCIONA LAS PREGUNTAS DE ESA CATEGOR�A
                   $preguntasArray = $this->m_encuesta->getPreguntasByCategoria($idEncuesta, $row->_id_categoria, $idArrayServ, $tipoEncGlobal);
                   $val = 0;
                   $firstPreg = null;
                   $activo    = ($cont == 0) ? 'active' : null;
                   $tabActive = ($cont == 0) ? 'is-active' : null;
                   if(count($preguntasArray) != 0){
                       $preguntasHTML .= '<section class="mdl-layout__tab-panel '.$tabActive.' " id="categoria'.$cont.'">
                                            <div class="mdl-content-cards">
                                                    <div class="main-gallery'.$row->_id_categoria.'" >';
                       
                       $categoriaHTML .= '<a href="#categoria'.$cont.'" id="c_'.$cont.'" class="mdl-layout__tab is-'.$activo.'"
                                        onclick="setIndexCategoria('.$cont.')">'.$row->desc_cate.'</a>';
                       $firstPreg = $preguntasArray[0]->_id_pregunta;
                   }
                   //$firstPreg = $preguntasArray[0]->_id_pregunta;
                   //RECORRE LAS PREGUNTAS DE LA CATEGORIA
                   foreach ($preguntasArray as $rowPreg) {
                       $val++;
                       $cont_con_preg++;
                       $isLastPregunta = null;
                       if($val === count($preguntasArray)) {
                           $isLastPregunta = 1;
                       }
                       $idPreguntaEnc = _simple_encrypt($rowPreg->_id_pregunta);
                       //Guarda todas las preguntas de cada categoria con flg si es obligatoria o no
                       array_push($arrObJSON, array("id_preg"  => $idPreguntaEnc,
                                                    "id_alter" => null,
                                                    "flg_obli" => $rowPreg->flg_obligatorio));
                       
                       //ENCRIPTAR
                       $idFooter = 'cont_pregunta_'.$cont_con_preg;
                       //SELECCIONA LAS ALTERNATIVAS PARA ESA PREGUNTA EN ESA ENCUESTA
                       $alternativaArray = $this->m_encuesta->getAlternativas($rowPreg->_id_pregunta,$idEncuesta);
                       $alternativasHTML = null;
                       $alterPropuestaMejoraHTML = null;
                       $cantAlterFinal = 0;
                       $necesario['id_footer']    = $idFooter;
                       $necesario['id_categoria'] = $row->_id_categoria;
                       $necesario['id_tipo_pregunta'] = $rowPreg->_id_tipo_pregunta;
                       $necesario['val_preguntas'] = $val;
                       $necesario['cont_preguntas'] = $cont_con_preg;
                       $necesario['id_servicio'] = $rowPreg->_id_servicio;
                       $necesario['flg_obligatorio'] = $rowPreg->flg_obligatorio;
                       $flg_obli = ( ($rowPreg->flg_obligatorio != 1 ) ? 0 : 1 );
                       //CONSTRUYE EL CODIGO HTML PARA CADA PREGUNTA CON SU TIPO DE PREGUNTA
                       $opciones = $this->getOpciones($alternativaArray, $necesario,$idPreguntaEnc, $flg_obli);     
                       $activeCard =(($rowPreg->_id_pregunta ==($firstPreg) ) ? 'active' : null);
                       $preguntasHTML .=  ' <div class="mdl-card__content">
                                                <div class="gallery-cell" data-last="'.$isLastPregunta.'">
                            				   		<div class="mdl-card '.$activeCard.'" id="'.$idFooter.'">
                        				   		        <div class="mdl-card__title p-r-50 p-l-50">
                            				   		        <div class="mdl-color-text--grey-500 mdl-typography--font-light f-s-regular post-number">'.$val.'.</div>
                        				   		            <h2 class="mdl-card__title-text mdl-color-text--grey-500 mdl-typography--font-light f-s-regular" >'.$rowPreg->desc_pregunta.'</h2>
                        				   		        </div>
                                                	   '.(($rowPreg->flg_obligatorio ==  FLG_OBLIGATORIO) ? 
                                                	    '<div class="mdl-card__menu">
            							                     <a data-toggle="tooltip" data-placement="right" data-original-title="Obligatoria">
                                                                 <i class="mdi mdi-mandatory mdl-color-text--grey-500"></i>
                                                             </a>
                                                         </div>' : null).'
                                                        <div class="mdl-card__supporting-text text-center br-b"><div class="row">'.($opciones).'</div></div>
                            				   		</div>
                                                </div>
                					       </div>';
                   }
                   $preguntasHTML .= '</div>
			                  </div>
                          </section>';
                   $cont++;                           
               }
               $arrHTML = array($categoriaHTML, $preguntasHTML, $arrObJSON, $arrCateObliJSON);
               $data['cant_pregObligatorias'] = $this->m_encuesta->getcantFlagOblibyEncbyTipoEncGlo($idEncuesta,$idArrayServ, $tipoEncGlobal);              
               $data['barraAvance'] = '0 / '.$data['cant_pregObligatorias'];
               $data['categoriaHTML'] = $arrHTML[0];
               $data['preguntasHTML'] = $arrHTML[1];
               $data['jsonObj'] = json_encode($arrHTML[2]);
               $data['arrCateObliJSON'] = json_encode($arrHTML[3]);
               $data['error'] = EXIT_SUCCESS;
       } catch (Exception $e){
           $data['msj'] = $e->getMessage();
       }
       return $data;
    }
    
    public function getOpciones($opciones, $necesario,$idPregCrypt, $flg_obli){
        $res = null;
        if($necesario['id_tipo_pregunta'] == CINCO_CARITAS){
            $numCol = '2';
            $res.= '<div class="col-xs-1"></div>';
            foreach($opciones as $opc){
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol, $flg_obli);
            }
        }else if($necesario['id_tipo_pregunta'] == TRES_CARITAS){
            $numCol = '4';
            foreach($opciones as $opc){
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol, $flg_obli);
            }
        }else if($necesario['id_tipo_pregunta'] == PROPUESTA_MEJORA){
            $res = '<div id="magicsuggest"></div>';
        }else if($necesario['id_tipo_pregunta'] == OPCION_MULTIPLE){
            $res .= '<div class="form-group">';
            $cont = 0;
            foreach ($opciones as $opcRadio){
                $res .= $this->contOpcionMultiple($opcRadio, $necesario,$idPregCrypt,$cont);
                $cont++;
            }
            $res .= '</div>';
        }else if($necesario['id_tipo_pregunta'] == CASILLAS_VERIFICACION){
            $res .= '<div class="form-group">';
            $cont = 0;
            foreach ($opciones as $opc){
                $res .= $this->contCasillasVerificacion($opc, $necesario,$idPregCrypt,$cont);
                $cont++;
            }
            $res .= '</div>';
        }else if($necesario['id_tipo_pregunta'] == LISTA_DESPLEGABLE){
            $cont_preguntas   = $necesario['cont_preguntas'];
            $res .= '<div class="col-sm-12 mdl-input-group mdl-input-group__only">
                         <div class="mdl-select">   
                            <select onchange=" selectAnswer(\''."null".'\',\''.$necesario['id_footer'].'\', '.$necesario['id_categoria'].', '.$necesario['val_preguntas'].', $(this), '.$cont_preguntas.');" data-tipo-preg="desplegable" class="form-control selectBootstrap" data-container="body">                        
                            <option value="">Seleccione uno</option>';
            foreach($opciones as $opc){
                $res .= $this->contOpcionListDesplegable($opc, $necesario,$idPregCrypt);
            }
            $res .= '</select></div></div>';
        }else if($necesario['id_tipo_pregunta'] == DOS_OPCIONES){
            $res .= '<div class="btn-group" data-toggle="buttons">';
            foreach($opciones as $opc){
                $res .= $this->contOpcion2Opciones($opc, $necesario,$idPregCrypt);
            }
            $res .= '</div>';
        }else if($necesario['id_tipo_pregunta'] == CUATRO_CARITAS){
            $numCol = '3';
            foreach($opciones as $opc){
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol, $flg_obli);
            }
        }
        return $res;
    }
    
    /**
     * 
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna c�digo HTML para el tipo de pregunta 2 Opciones (SI/NO)
     */
    function contOpcion2Opciones($opcion, $necesario,$idPreguntaEnc){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $str = $opcion->desc_alternativa;
        $css = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $id_servicio      = $necesario['id_servicio'];
        $flg_oblig        = $necesario['flg_obligatorio'];
        $opcionHTML = null;
        $opcionHTML = '<label id="a-'.$id_categoria.'-'.$val.'" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'"
                           class="btn ink-reaction" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.', $(this), '.$cont_preguntas.');" data-tipo-preg="2opciones">
    					   <input type="radio" name="options" >'.$str.'
    				   </label>';
        
        return $opcionHTML;
    }
    
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @param numeroDeColumna(col-sm) $numCol
     * @return Retorna c�digo HTML para el tipo de pregunta caritas (3,4,5)
     */
    public function contOpcionCaritas($opcion, $necesario,$idPreguntaEnc,$numCol, $flg_obli){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $css              = $opcion->css_alternativa;
        $str_desc         = (strstr($opcion->desc_alternativa, ' ') ? str_replace(" ", "<br/>",$opcion->desc_alternativa ) : $opcion->desc_alternativa);
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $id_servicio      = $necesario['id_servicio'];
        $flg_oblig        = $necesario['flg_obligatorio'];  
        $opcionHTML = null; 
        if( ($id_servicio != null && $flg_oblig != 1) ||
            ($id_servicio != null && $flg_oblig == 1 && $idAlternativ != 6) ||
            ($id_servicio == null && $flg_oblig != 1 && $idAlternativ != 6) ||
            ($id_servicio == null && $flg_oblig == 1 && $idAlternativ != 6)  ) {
                $opcionHTML = '<div data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" class="col-xs-'.$numCol.' p-0 ">
                                   <a data-flg_obli="'.$flg_obli.'" id="a-'.$id_categoria.'-'.$val.'" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.', $(this), '.$cont_preguntas.');
                                       activeEffectIcon(\''.$idFooter.'\',\''.$css.'\');" data-tipo-preg="caritas" class="text-center">
                                       <i class="mdi mdi-'.$css.' f-s-regular-34 mdl-color-text--grey-500 m-l-5"></i>
                                       <br>
                                       <small class="small-'.$css.' mdl-color-text--grey-500"></small>
                                   </a>
						     </div>';
            }
        
        return $opcionHTML;
    }
    
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna c�digo HTML para el tipo de pregunta opcion multiple (RadioButton)
     */
    public function contOpcionMultiple($opcion, $necesario,$idPreguntaEnc,$cont){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $css              = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $id_servicio      = $necesario['id_servicio'];
        $flg_oblig        = $necesario['flg_obligatorio'];
        $str              = $opcion->desc_alternativa;
        $opcionHTML = null;        
        $opcionHTML ='<div class="col-md-12 col-lg-12">            
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-t-5 m-b-5" for="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'">
                            <input class="mdl-radio__button" type="radio" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" name="radioVals'.$val.'" data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="multipleOpc" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');">
                            <span class="mdl-radio__label">'.$str.'</span>
                        </label></div>';
        return $opcionHTML;
    }
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna c�digo HTML para el tipo de pregunta casillas de verificaci�n (CheckBox)
     */ 
    public function contCasillasVerificacion($opcion, $necesario,$idPreguntaEnc,$cont){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $css              = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $id_servicio      = $necesario['id_servicio'];
        $flg_oblig        = $necesario['flg_obligatorio'];
        $str              = $opcion->desc_alternativa;
        $opcionHTML = null;
        /*$opcionHTML .='	<label class="checkbox-inline checkbox-styled checkbox-warning">
    						<input type="checkbox" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-tipo-preg="casilla"  data-flg_obli="'.$flg_oblig.'" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="optionM" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');"><span class="questionTb">'.$str.'</span>
    					</label>';*/
        $opcionHTML .='<div class="col-md-12 col-lg-12">   
                          <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="a-'.$id_categoria.'-'.$val.'-'.$cont.'">
                              <input type="checkbox" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-flg_obli="'.$flg_oblig.'" data-tipo-preg="casilla" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="optionM" class="mdl-checkbox__input" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');">
                              <span class="mdl-checkbox__label">'.$str.'</span>
                           </label>
                       </div>';
        return $opcionHTML;
    }
    
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna c�digo HTML para el tipo de pregunta lista desplegable (ComboBox)
     */
    public function contOpcionListDesplegable($opcion, $necesario,$idPreguntaEnc){
        //$idPreguntaEnc    = _encodeCI($opcion->_id_pregunta);
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $str              = $opcion->desc_alternativa;
        $css              = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $opcionHTML = null;
        $opcionHTML = '<option data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" attr-id-categoria="'.$id_categoria.'">'.$str.'</option>';
        return $opcionHTML;
    }
    
    function logout() {
        $this->session->set_userdata(array("logout" => true));
        unset($_COOKIE['schoowl']);
        $cookie_name2 = "schoowl";
        $cookie_value2 = "";
        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30), "/");
        Redirect(RUTA_SMILEDU, true);
    }
    
    function cambiarEstadoEncuesta(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEnc = _simple_decrypt($this->input->post("idEncuesta"));
            $cantObli      = $this->m_pregunta->getCantidadPreguntasObligatoriasByEncuesta($idEnc);
            $cantPregAlter = $this->m_pregunta->getCantidadPregConAlternativas($idEnc);
            $cantCatePreg  = $this->m_pregunta->getCantidadCategoriaConPreguntas($idEnc);
            $titulo = $this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEnc, 'senc');
            if($cantCatePreg['cant_cate_enc'] != $cantCatePreg['cant_cate_enc_preg']){
                $data['msj'] = "Todas las categorias deben tener una pregunta";
                $data['error'] = EXIT_ERROR;
            }else if($cantPregAlter['cant_preg_alter'] != $cantPregAlter['cant_preg_enc']){
                $data['msj'] = "Todas las preguntas deben tener una alternativa";
                $data['error'] = EXIT_ERROR;
            }else if($cantObli <= 0){
                $data['msj'] = "Ingrese al menos una pregunta obligatoria";
                $data['error'] = EXIT_ERROR;
            }else if($titulo == null){
                $data['msj'] = "Ingrese un t�tulo a la encuesta";
                $data['error'] = EXIT_ERROR;
            }else{
                $data  = $this->m_encuesta->cambiarEstadoEncuesta($idEnc, ENCUESTA_APERTURADA);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author dfloresgonz
     * @since  22.05.2016
     */
    function imprimirEncuesta() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idEnc = _simpleDecryptInt(_post("idEncuesta"));
            if($idEnc == null) {
                throw new Exception(ANP);
            }
            $html = __getHTML_RubricaFisicaEFQM_Padres($idEnc); //$this->getHTMLImprimir($idEnc);// $this->generarTablaHTML($this->m_escuela->getEscuelasByIds($arrayIdsColes));
            $this->load->library('m_pdf');
            $nomFile     = __generateRandomString(8);
            $file        = "uploads/modulos/senc/pdf/".$nomFile.".pdf";
            $data['file'] = $file;
            $pdfFilePath = "./".$file;
            $pdfObj = $this->m_pdf->load('','A4-L', 7, 'Arial', 9, 9, 16, 16, 9, 9, 'L');
            
            $codigoEncuesta = $this->m_utils->getById("senc.encuesta", "desc_enc", "id_encuesta", $idEnc);
            $codigoEncuesta = utf8_encode('Encuesta '.$codigoEncuesta);
            
            $pdfObj->SetColumns(2);
            $pdfObj->KeepColumns = true;
            
            $pdfObj->SetFooter($codigoEncuesta.'|{PAGENO}|'.date('d/m/Y h:i:s a'));
            $pdfObj->WriteHTML(utf8_encode($html));
            $pdfObj->Output($pdfFilePath, 'F');
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author dfloresgonz
     * @since  22.05.2016
     */
    function borrar(){
        $pdfRuta = $this->input->post('ruta');
        if(file_exists($pdfRuta)) {
            $pdfRuta = './'.$this->input->post('ruta');
            if (!unlink($pdfRuta)){
                //echo ("No se borr� el archivo");
            }else{
                //echo ("Se borr�");
            }
        }
        echo null;
    }
}