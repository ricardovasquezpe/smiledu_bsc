<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_encuesta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->library('user_agent');
        $this->load->model('m_utils');
        $this->load->library('table');
    }
    
    public function index() {
        //NECESARIO
        $idEncuesta             = _decodeCI(str_replace(" ","+",$_GET['encuesta']));
        $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
        $data['isAperturada']   = $this->m_encuesta->validarEncuestaAperturada($idEncuesta);
        $data['arraTipoEncuestadoHTML'] = $this->getTipoEncuestadoHTML();
        $data['arraServiHTML']  = $this->getServicioHTML();
        $propuestaMrray         = $this->m_encuesta->getCantPropM($idEncuesta);
        $dataPropuestas         = $this->getPropuestasMejoraHTML($propuestaMrray, array());
        $data['arraPropMHTML']  = $dataPropuestas[0];
        $data['arrayFavProp']   = $dataPropuestas[1];
        $data['tituloEncuesta'] = $this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta);
        $flg_anonima = $this->m_utils->getById('senc.encuesta', 'flg_anonima', 'id_encuesta', $idEncuesta);
        $data['flg_anonima']    = ($flg_anonima == null || $flg_anonima == FLG_NO_ANONIMA) ? FLG_NO_ANONIMA : FLG_ANONIMA;
        if(isset($_COOKIE['persona'])) {
            $idPersona = _simpleDecryptInt($_COOKIE['persona']);
            if($idPersona == null){
                $idPersona = _getSesion('nid_persona');
            }
        } else {
            $idPersona = _getSesion('nid_persona');
        }
        $this->session->set_userdata(array('nid_persona_encuesta' => $idPersona));
        if($data['flg_anonima'] == FLG_NO_ANONIMA){
            if(!isset($_COOKIE['smiledu'])) {
                $data['flg_need_login'] = true;
            }
        }
        $data += $this->getCategoriasHTML();
        unset($_COOKIE['persona']);
        ///////////
        $this->load->view('vf_encuesta/v_encuesta', $data);
    }
    
    public function getTipoEncuestadoHTML() {
        $idEncuesta = _getSesion('idEncuestaActiva');
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
       
    public function getServicioHTML() {
       $servicioArray = $this->m_encuesta->getCantServicio();
       $servicioHTML = null;
       $contServ = 0;
       foreach($servicioArray as $rowServ) {
           $contServ++;
           $idencrypt = _encodeCI($rowServ->id_servicio);
           $servicioHTML .= '<div class="col-sm-4 col-xs-6">
                                 <a class="btn-service" id="idServ'.$contServ.'" attr-flg_active="0"
                                    onclick="pintarActive($(this), \''.$idencrypt.'\')">'.$rowServ->desc_servicio.'
                                 </a>
                             </div><br>';        
       }
       return $servicioHTML;     
    }
    
    function getPropuestasMejoraHTML($propuestaMrray, $cantNewPropM) {
        $propuestaMHTML = null;
        $contPropM = 0;
        $arrayEncrypt = array();
        foreach($propuestaMrray as $rowPropM){
            $contPropM++;
            $idEncrypt = _simple_encrypt($rowPropM->id_propuesta);
            foreach($cantNewPropM as $rowMarcar){
                if(($rowMarcar) == ($idEncrypt)){
                    $propuestaMHTML .= '<option value = "'.$rowMarcar.'" selected>'.$rowPropM->desc_propuesta.'</option>';
                    $idEncrypt= null;
                    $rowPropM->desc_propuesta = null;
                }
            }
            array_push($arrayEncrypt, $idEncrypt);
            if($idEncrypt != null && $rowPropM->desc_propuesta != null){
                $propuestaMHTML .= '<option value = "'.$idEncrypt.'">'.$rowPropM->desc_propuesta.'</option>';
            }
        }
        return array($propuestaMHTML, json_encode($arrayEncrypt));
    }
    

    function getPreguntasCategoriasEncuesta(){
        $data = $this->getCategoriasHTML();
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getCategoriasHTML() {
        $data['error'] = EXIT_ERROR;
        $idEncuesta = _getSesion('idEncuestaActiva');
        $tipoEncGlobal= _getSesion('tipoEncuGlobal');
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
                                                       "flg_pasoCate" => 0) );
                   //SELECCIONA LAS PREGUNTAS DE ESA CATEGORÍA
                   $preguntasArray = $this->m_encuesta->getPreguntasByCategoria($idEncuesta, $row->_id_categoria, $idArrayServ, $tipoEncGlobal);
                   $val = 0;
                   $firstPreg = null;
                   if(count($preguntasArray) != 0){
                       $activo    = ($cont == 0) ? 'active' : null;
                       $tabActive = ($cont == 0) ? 'is-active' : null;
                       $preguntasHTML .= '<section class="mdl-layout__tab-panel '.$tabActive.' p-0 " id="categoria'.$cont.'">
                                            <div class="page-content">
                                                    <div class="main-gallery'.$row->_id_categoria.'" >';
                       
                       $categoriaHTML .= '<a href="#categoria'.$cont.'" id="c_'.$cont.'" class="mdl-layout__tab is-'.$activo.'"
                                        onclick="setIndexCategoria('.$cont.')">'.$row->desc_cate.'</a>';
                       $firstPreg = $preguntasArray[0]->_id_pregunta;
                       $cont++;
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
                        				   		        <div class="mdl-card__title p-20 p-r-50 p-l-50">
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
               }
               $arrHTML = array($categoriaHTML, $preguntasHTML, $arrObJSON, $arrCateObliJSON);
               $data['cant_pregObligatorias'] = $this->m_encuesta->getcantFlagOblibyEncbyTipoEncGlo($idEncuesta,$idArrayServ, $tipoEncGlobal);
               $data['barraAvance'] = '0 /  '.$data['cant_pregObligatorias'];
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
    
    /**
     * 
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna código HTML de cada pregunta en la encuesta
     */
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
            $res .= '<div class="col-sm-12"><select onchange=" selectAnswer(\''."null".'\',\''.$necesario['id_footer'].'\', '.$necesario['id_categoria'].', '.$necesario['val_preguntas'].', $(this), '.$cont_preguntas.');" data-tipo-preg="desplegable" class="form-control selectBootstrap" data-container="body">
                        <option value="">Seleccione uno</option>';
            foreach($opciones as $opc){
                $res .= $this->contOpcionListDesplegable($opc, $necesario,$idPregCrypt);
            }
            $res .= '</select></div>';
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
     * @return Retorna código HTML para el tipo de pregunta 2 Opciones (SI/NO)
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
     * @return Retorna código HTML para el tipo de pregunta caritas (3,4,5)
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
                $opcionHTML = '<div data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" class="col-xs-'.$numCol.' p-0">
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
     * @return Retorna código HTML para el tipo de pregunta opcion multiple (RadioButton)
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
        $opcionHTML ='  <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect m-t-5 m-b-5" for="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'">
                            <input type="radio" class="mdl-radio__button" value="1" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" name="radioVals'.$val.'" data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="multipleOpc" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');">
                            <span class="mdl-radio__label">'.$str.'</span>
                        </label>';
        return $opcionHTML;
    }
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna código HTML para el tipo de pregunta casillas de verificación (CheckBox)
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
        $opcionHTML .='<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="a-'.$id_categoria.'-'.$val.'-'.$cont.'">
                          <input type="checkbox" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-flg_obli="'.$flg_oblig.'" data-tipo-preg="casilla" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="optionM" class="mdl-checkbox__input" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');">
                          <span class="mdl-checkbox__label">'.$str.'</span>
                       </label>';
        return $opcionHTML;
    }
    
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna código HTML para el tipo de pregunta lista desplegable (ComboBox)
     */
    public function contOpcionListDesplegable($opcion, $necesario,$idPreguntaEnc){
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
    
    /**
     * @author Fernando Luna
     * @return Inserta los datos en las colecciones 
     */
    public function enviarEncuesta() {
        $start = microtime(true);
        $this->db->trans_begin();
        $idEncuesta     = _getSesion('idEncuestaActiva');
        $idSede         = _post('idSedeGlobal');
        $idAula         = _post('idAulaPadre');
        $idAreaDoc      = _post('idAreaEspGlobal');
        $idAreaAdmin    = _post('idAreaGeneral');
        $idNivelDoc     = _post('idNivelGlobDoc');
        $tipoEncuGlobal = _getSesion('tipoEncuGlobal');
        $objJson        = _post('objJson');
        $contObli       = _post('contador');
        $objJSONProp    = _post('objJSONProp');
        $idPersona      = _getSesion('nid_persona_encuesta');
        $comenPropM     = trim(_post('comenPropM'));
        $arrayCheck     = _post('arrayCheck');
        $metaDatos      = _post('client_info')[0];
        $arryInfo       = array();
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if(_getSesion("flg_envio_enc") == 1) {
                throw new Exception('Procesando...');
            }
            if($idEncuesta == null || $idEncuesta == false) {
                throw new Exception('Ocurri&oacute; un error, intente nuevamente');
            }
            $flg_anonima = $this->m_utils->getById('senc.encuesta', 'flg_anonima', 'id_encuesta', $idEncuesta);
            $this->session->set_userdata(array("flg_envio_enc" => 1));
            $isAperturada = $this->m_encuesta->validarEncuestaAperturada($idEncuesta);
            if($isAperturada != 1){
                throw new Exception('Esta encuesta ya caducó, Muchas Gracias.');
            }
            $idAula      = _decodeCI($idAula);
            $idAreaDoc   = _decodeCI($idAreaDoc);
            $idAreaAdmin = _decodeCI($idAreaAdmin);
            $idSede      = _decodeCI($idSede);
            $idNivelDoc  = _decodeCI($idNivelDoc);
            if($idAula == null && ($tipoEncuGlobal == 'P' || $tipoEncuGlobal == 'E') ) {
                throw new Exception('Seleccione el aula porfavor :)');
            }
            /////////////////////////////////////
            $data = $this->validarEncuesta($objJson, $objJSONProp, $idAula, $arryInfo, $arrayCheck);
            $this->session->set_userdata(array('tipoEncuestadoLibre' => $tipoEncuGlobal));
            if($flg_anonima == FLG_NO_ANONIMA) {
                $pdf = $this->generatePDFEncuesta($data['arrayGeneral']);
            }
            /////////////////////////////////////
            if($data['error'] == SUCCESS_MONGO) {
                if($tipoEncuGlobal == 'P' || $tipoEncuGlobal == 'E') {
                    if($idSede == null || $idAula == null) {
                        throw new Exception('Debes seleccionar el padre');
                    }
                    $SNGAula  = $this->m_encuesta->getSedeNivelGradoByAula($idAula);
                    if($SNGAula['nid_sede'] == null || $SNGAula['nid_nivel'] == null || $SNGAula['nid_grado'] == null || $SNGAula['nid_aula'] == null) {
                        throw new Exception('Par&aacute;metros no configurados correctamente');
                    }
                } else if($tipoEncuGlobal == 'A') {
                    $SNGAula = array("nid_sede" => $idSede, "nid_grado" => 0, "nid_nivel" => 0,"nid_area" => $idAreaAdmin, "nid_aula" => 0);
                    if($idSede == null || $idAreaAdmin == null) {
                        throw new Exception('Comuníquese con el administrador para poder configurar su cuenta');
                    }
                } else if($tipoEncuGlobal == 'D') {
                    $SNGAula = array("nid_sede" => $idSede, "nid_grado" => 0,"nid_nivel" => $idNivelDoc, "nid_area" => $idAreaDoc, "nid_aula" => 0);
                    if($idSede == null || $idNivelDoc == null || $idAreaDoc == null) {
                        throw new Exception('Comuníquese con el administrador para poder configurar su cuenta');
                    }
                } else if($tipoEncuGlobal == 'I') {
                    $SNGAula = array("nid_sede" => 0, "nid_grado" => 0,"nid_nivel" => 0, "nid_area" => 0, "nid_aula" => 0);
                }
                $arrayGeneral   = $data['arrayGeneral'];
                $arrayPropuesta = $data['arrayPropuesta'];
                /////////////////////////////////////////////////
                $respuestas       = null;
                $propuestasMejora = null;
                foreach($arrayGeneral as $row) {
                    if($row['respuesta'] != null) {
                        $respuesta = $this->m_utils->getById('senc.alternativa','desc_alternativa', 'id_alternativa', $row['respuesta']);
                        $pregunta  = str_replace("\"", "'", $this->m_utils->getById('senc.preguntas', 'desc_pregunta', 'id_pregunta', $row['id_pregunta']));
                        $respuestas .= '{
                                            "id_pregunta"    : '.$row['id_pregunta'].',
                                            "desc_pregunta"  : "'.$pregunta.'",
                                            "id_respuesta"   : '.$row['respuesta'].',
                                            "respuesta"      : "'.strtoupper($respuesta).'",
                                            "count"          : 1
                                        },';
                    }
                }
                foreach($arrayPropuesta as $row) {
                    $desc_propuesta = $this->m_utils->getById('senc.propuesta_mejora','desc_propuesta', 'id_propuesta', $row['id_propuesta']);
                    $propuestasMejora .= '{
                                              "id_propuesta"   : '.$row['id_propuesta'].',
                                              "desc_propuesta" : "'.$desc_propuesta.'",
                                              "count"          : 1
                                          },';
                }
                $respuestas       = rtrim(trim($respuestas), ",");
                $propuestasMejora = rtrim(trim($propuestasMejora), ",");
                $metaDatosJson = '{
                                      "sist_oper"         : "'.($this->agent->platform() != null ? $this->agent->platform() : $metaDatos['sist_oper'] ).'",
                                      "id_address"        : "'.($this->input->ip_address()).'",
                                      "browser"           : "'.(($this->agent->is_browser()) ? $this->agent->browser().' '.$this->agent->version() : $metaDatos['browser']).'",
                                      "device"            : "'.(isset($metaDatos['device'])        ? $metaDatos['device'] : null).'",
                                      "device_tipo"       : "'.(isset($metaDatos['device_tipo'])   ? $metaDatos['device_tipo'] : null).'",
                                      "device_vendor"     : "'.(isset($metaDatos['device_vendor']) ? $metaDatos['device_vendor'] : null).'",
                                      "resolution_device" : "'.$metaDatos['resolution_device'].'",
                                      "is_touch"          : "'.$metaDatos['touch'].'",
                                      "is_mobile"         : "'.($this->agent->is_mobile() ? 'SI' : 'NO').'"
                                  }';
                $arryInfo['metadata_jsonb']   = '{ "metadatos" : [ '.$metaDatosJson.' ] }';
                $arryInfo['respuestas_jsonb'] = '{ "preguntas" : [ '.$respuestas.' ] }';
                $arryInfo['propuestas_jsonb'] = ($propuestasMejora != null) ? '{ "propuestas" : [ '.$propuestasMejora.' ] }' : null;
                $arryInfo['comentario']       = utf8_decode(trim($comenPropM));
                ////////////////////////////////////////////
                $arryInfo['id_encuesta'] = $idEncuesta;
                $arryInfo['tipo_encuestado'] = _getSesion('tipoEncuestadoLibre');
                $arryInfo['nid_sede']  = isset($SNGAula['nid_sede'])  ? $SNGAula['nid_sede']  : null;
                $arryInfo['nid_nivel'] = isset($SNGAula['nid_nivel']) ? $SNGAula['nid_nivel'] : null;
                $arryInfo['nid_grado'] = isset($SNGAula['nid_grado']) ? $SNGAula['nid_grado'] : null;
                $arryInfo['nid_aula']  = isset($SNGAula['nid_aula'])  ? $SNGAula['nid_aula']  : null;
                $arryInfo['nid_area']  = isset($SNGAula['nid_area'])  ? $SNGAula['nid_area']  : null;
                $data = $this->m_encuesta->insertDeviceInfoEncuestadoAux($arryInfo);
                $idDeviceInfo = $data['id_dispositivo'];
                unset($data['id_dispositivo']);
                if($idDeviceInfo == null) {
                    $data['error'] = ERROR_MONGO;
                    throw new Exception($idDeviceInfo['msj']);
                }
                $arrayRollBack = array();
                $id_tipo_Enc = $this->m_encuesta->getIdTipoEncbyIdEnc($idEncuesta);
                //usar el $idDeviceInfo para grabar en el MongoDB
                $data = $this->m_encuesta->insertRptaMongoDB($arrayGeneral, TIPO_ENCUESTA_LIBRE,$SNGAula,$idEncuesta,$idDeviceInfo,$arrayRollBack);_log('1: '.$data['error']. ' msj: '.$data['msj']);
                if($data['error'] == SUCCESS_MONGO){
                    $data = $this->m_encuesta->llenaEncSatistaccion($arrayGeneral,TIPO_ENCUESTA_LIBRE,$SNGAula,$idEncuesta,$data['arrayRollBack'], $id_tipo_Enc);_log('2: '.$data['error']);
                    if($data['error'] == SUCCESS_MONGO){
                        $data = $this->m_encuesta->llenaEncInsatistaccion($arrayGeneral, TIPO_ENCUESTA_LIBRE,$SNGAula,$idEncuesta,$data['arrayRollBack'], $id_tipo_Enc);_log('3: '.$data['error']);
                        if($data['error'] == SUCCESS_MONGO){
                            $data = $this->m_encuesta->insertPropuMejora($arrayPropuesta,TIPO_ENCUESTA_LIBRE,$SNGAula,$idEncuesta,$data['arrayRollBack']);_log('4: '.$data['error']);
                            if($data['error'] == SUCCESS_MONGO && count($objJSONProp) > 0){
                                $comenPropM = str_replace("\"", "'", $comenPropM);
                                if($comenPropM != null){
                                    $data = $this->m_encuesta->insertPropuestaMejoraComentario($arrayPropuesta, TIPO_ENCUESTA_LIBRE,$SNGAula,$idEncuesta,$idDeviceInfo,$data['arrayRollBack'],utf8_encode($comenPropM));_log('5: '.$data['error'].'  msj: '.$data['msj']);
                                }
                            }
                        }
                    }
                }
            }
            if($data['error'] == ERROR_MONGO) {
                $data = $this->m_encuesta->executeRollBack($data['arrayRollBack']);
                if($idDeviceInfo != null) {
                    $this->m_encuesta->borrarDeviceInfoEncuestado($idDeviceInfo);
                }
                throw new Exception("Hubo un error");
            }
            unset($data['arrayRollBack']);
            if($flg_anonima == FLG_NO_ANONIMA) {
                $data['ruta'] = 'uploads/pdf/'.$pdf.'.pdf';
                if($idPersona == null){
                    $idPersona = _getSesion("id_persona");
                }
                $correoPersona = $this->m_encuesta->getCorreoByPersAdmin($idPersona, $tipoEncuGlobal);
                if($correoPersona != null && $correoPersona != '') {
                    $this->enviarDocEmail($correoPersona,$_SERVER['DOCUMENT_ROOT'].'/smiledu/uploads/modulos/senc/pdf/'.$pdf.'.pdf');
                }
            }
            $this->m_encuesta->aumentaCantEnc($idEncuesta);
            $this->db->trans_commit();
            $this->session->set_userdata(array("flg_envio_enc" => null));
        } catch(Exception $e) {
            /*unset($data['arrayGeneral']);
            unset($data['arrayPropuesta']);
            $data['error'] = ERROR_MONGO;*/
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        
        $time_elapsed_secs = microtime(true) - $start;
        $unidMedida = 'segundo(s)';
        if($time_elapsed_secs >= 60) {
            $time_elapsed_secs = $time_elapsed_secs / 60;
            $unidMedida = 'minuto(s)';
        }
        _log('LIBRE FINALIZO OK en '.(round($time_elapsed_secs, 2)).' '.$unidMedida.'   ::: '.print_r($data, true));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRespuestas $objJson
     * @param arrayPropuestas $objJSONProp
     * @param idAula $idAula
     * @param arrayInformacionUsuario $arryInfo
     * @throws Exception
     * @return Retorna 0 en caso de success o 1 en caso de error y tambien los arrays para insertar
     */
    public function validarEncuesta($objJson, $objJSONProp, $idAula,$arrayCheck) {
        $idEncuesta = _getSesion('idEncuestaActiva');
        $arrayIdBdPregs = $this->m_encuesta->getIdsPreguntasByEncuesta($idEncuesta);
        $arrayGeneral = array();
        foreach ($objJson as $row) {
            $idPreg = _simple_decrypt($row['id_preg']);
            $idAltr = $this->encrypt->decode($row['id_alter']);
            //1. id_preg sea diferente de nulo
            if($idPreg == null) {
                throw new Exception("Pregunta nula");
            }
            //2. borrar el id_preg del array de BD para verificar que no vengan repetidos
            if(in_array($idPreg, $arrayIdBdPregs)) {
                $key = array_search($idPreg, $arrayIdBdPregs);
                if (false !== $key) {
                    unset($arrayIdBdPregs[$key]);
                }
            }
            //3. validar q la pregunta este en la encuesta
            $pregEncu = $this->m_encuesta->getExistePreguntaInEncuesta($idPreg,$idEncuesta);
            if($pregEncu['count'] != PREGUNTA_PERTENECE_ENCUESTA) {
                throw new Exception("Pregunta no pertenece a la encuesta");
            }
            //4.que la pregunta obligatoria tenga alternativa
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr == null) {
                throw new Exception('Le faltó marcar al menos una pregunta obligatoria');
            }
            //5.que la alternativa pertenezca a la pregunta
            $idTipoPreg = $this->m_encuesta->getIdTipoPreguntaByPreguntaEncuesta($idPreg,$idEncuesta);
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr != null) {
                $cntAlterEnPreg = ($idTipoPreg == CINCO_CARITAS || $idTipoPreg == CUATRO_CARITAS || $idTipoPreg == TRES_CARITAS ) ? $this->m_encuesta->getExisteAlterInPregunta($idPreg,$idEncuesta,$idAltr) : $this->m_encuesta->getExisteAlterInPreguntaMulti($idPreg,$idEncuesta,$idAltr);
            }
            if(isset($cntAlterEnPreg) && $cntAlterEnPreg != ALTERNATIVA_PERTENECE) {
                throw new Exception("Alternativa no pertenece a la encuesta");
            }
            if($idTipoPreg != CASILLAS_VERIFICACION){
                if($idAltr != null) {
                    array_push($arrayGeneral, array('id_pregunta' => $idPreg,'respuesta' => $idAltr));
                }
            } else {
                if(count($arrayCheck) != 0){
                    $arrayGeneral = $this->addToArrayGeneral($arrayGeneral,$arrayCheck);
                }
            }
        }
        $arrayPropMejora = array();
        //Evalua las propuestas de mejora
        if(is_array($objJSONProp)) {
            foreach($objJSONProp as $rowBtn) {
                $idPropMDecrypt =_simpleDecryptInt($rowBtn);
                if($idPropMDecrypt == null ) {
                    Throw new Exception(ANP);
                }
                $this->m_encuesta->aumentaContPropM($idPropMDecrypt);
                $descPropM =$this->m_encuesta->getCantidadPropM($idPropMDecrypt);
                    array_push($arrayPropMejora, array('id_propuesta'   => $idPropMDecrypt,
                                                       'desc_propuesta' => $descPropM));
            }
        }
        $data['arrayGeneral'] = $arrayGeneral;
        $data['arrayPropuesta'] = $arrayPropMejora;
        $data['error'] = SUCCESS_MONGO;
        $data['msj'] = null;
        return $data;
    }
    
    function addToArrayGeneral($arrayGeneral,$arrayCheck){
        $idEncuesta = _getSesion('idEncuestaActiva');
        $arrayIdBdPregs = $this->m_encuesta->getIdsPreguntasByEncuesta($idEncuesta);
        foreach ($arrayCheck as $row) {
            $idPreg = _simpleDecryptInt($row['id_preg']);
            $idAltr = $this->encrypt->decode($row['id_alter']);
            //1. id_preg sea diferente de nulo
            if($idPreg == null) {
                throw new Exception("Pregunta nula");
            }
            //2. borrar el id_preg del array de BD para verificar que no vengan repetidos
            if(in_array($idPreg, $arrayIdBdPregs)) {
                $key = array_search($idPreg, $arrayIdBdPregs);
                if (false !== $key) {
                    unset($arrayIdBdPregs[$key]);
                }
            }
            //3. validar q la pregunta este en la encuesta
            $pregEncu = $this->m_encuesta->getExistePreguntaInEncuesta($idPreg,$idEncuesta);
            if($pregEncu['count'] != PREGUNTA_PERTENECE_ENCUESTA) {
                throw new Exception("Pregunta no peretenece a la encuesta");
            }
            //4.que la pregunta obligatoria tenga alternativa
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr == null) {
                throw new Exception('Le faltó marcar al menos una pregunta obligatoria');
            }
            //5.que la alternativa pertenezca a la pregunta
            $idTipoPreg = $this->m_encuesta->getIdTipoPreguntaByPreguntaEncuesta($idPreg,$idEncuesta);
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr != null) {
                $cntAlterEnPreg = ($idTipoPreg == CINCO_CARITAS || $idTipoPreg == CUATRO_CARITAS || $idTipoPreg == TRES_CARITAS ) ? $this->m_encuesta->getExisteAlterInPregunta($idPreg,$idEncuesta,$idAltr) : $this->m_encuesta->getExisteAlterInPreguntaMulti($idPreg,$idEncuesta,$idAltr);
            }
            if(isset($cntAlterEnPreg) && $cntAlterEnPreg != ALTERNATIVA_PERTENECE) {
                throw new Exception("Alternativa no pertenece a la encuesta");
            }
            if($idAltr != null) {
                array_push($arrayGeneral, array('id_pregunta' => $idPreg,'respuesta' => $idAltr));
            }
        }
        return $arrayGeneral;
    }
    
    function getListaPropuestas() {
        $arrayPropuestas = $this->m_encuesta->getPropuestas();
        $json = json_encode($arrayPropuestas);
        echo $json;
    }
    
    function getListaAulas() {
        $arryAulas = $this->m_utils->getAulasAll();
        $json = json_encode($arryAulas);
        echo $json;
    }
    
    function saveClientDeviceInfo($arryInfo) {
        $this->load->library('user_agent');
        $arryInfo['fecha']        = date('d/m/Y H:i:s');
        $arryInfo['id_address']   = $this->input->ip_address();
        $arryInfo['_browser']     = ($this->agent->is_browser()) ? $this->agent->browser().' '.$this->agent->version() : 'Otro';
        $arryInfo['sist_oper_ci'] = $this->agent->platform();
        $arryInfo['es_mobile']    = $this->agent->is_mobile() ? 'SI' : 'NO';
        $arryInfo['redirect']     = $this->agent->is_referral() ? 'SI' : 'NO';
        $idEncuesta     = _getSesion('idEncuestaActiva');
        $flg_anonima    = $this->m_utils->getById('senc.encuesta', 'flg_anonima', 'id_encuesta', $idEncuesta);
        if($flg_anonima == FLG_NO_ANONIMA){
            $arryInfo['_id_persona'] = _getSesion('nid_persona_encuesta');
        }
        return $this->m_encuesta->insertDeviceInfoEncuestado($arryInfo);
    }
    
    function getSedes() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $tipoEncuGlobal = _post('tipoEncuGlobal');
            $data['flg_anonima'] = $this->m_utils->getById('senc.encuesta', 'flg_anonima', 'id_encuesta', _getSesion('idEncuestaActiva'));
            $this->session->set_userdata(array("tipoEncuGlobal" => $tipoEncuGlobal));
            $idPersona = _getSesion('nid_persona_encuesta');
            if($tipoEncuGlobal != 'P' && $tipoEncuGlobal != 'A' && $tipoEncuGlobal != 'I' && $tipoEncuGlobal != 'D' && $tipoEncuGlobal != 'E') {
                throw new Exception(ANP);
            }
            if($data['flg_anonima'] == FLG_NO_ANONIMA && ($tipoEncuGlobal == 'D' || $tipoEncuGlobal == 'A' || $tipoEncuGlobal == 'E')){
                $data    = $this->getCategoriasHTML();
                $data['error'] = EXIT_SUCCESS;
                $SNGAula = $this->m_encuesta->getNivelesByTipoEncuestado($tipoEncuGlobal,$idPersona);
                if( ($tipoEncuGlobal == 'D' && ($SNGAula['sede'] == null || $SNGAula['nivel'] == null || $SNGAula['area'] == null)) || ($tipoEncuGlobal == 'A' && ($SNGAula['sede'] == null || $SNGAula['area'] == null) || ($tipoEncuGlobal == 'E' && ($SNGAula['sede'] == null || $SNGAula['nivel'] == null || $SNGAula['grado'] == null || $SNGAula['aula'] == null)))){
                    $data['error'] = EXIT_ERROR;
                } else if($tipoEncuGlobal != 'I'){
                    $SNGAula['sede']  = _encodeCI((isset($SNGAula['sede'])  /*? $SNGAula['sede']  : null*/));
                    $SNGAula['nivel'] = _encodeCI((isset($SNGAula['nivel']) /*? $SNGAula['nivel'] : null*/));
                    $SNGAula['grado'] = _encodeCI((isset($SNGAula['grado']) /*? $SNGAula['grado'] : null*/));
                    $SNGAula['aula']  = _encodeCI((isset($SNGAula['aula'])  /*? $SNGAula['aula']  : null*/));
                    $SNGAula['area']  = _encodeCI((isset($SNGAula['area'])  /*? $SNGAula['area']  : null*/));
                }
                $data['niveles'] = json_encode($SNGAula);
                $data['flg_anonima'] = FLG_NO_ANONIMA;
            } else{
                if($tipoEncuGlobal == 'P' || $tipoEncuGlobal == 'E'){//PADRE Y ESTUDIANTE
                    $data['optSedes'] = __buildComboSedes();
                    ///////////////////////
                    $data['tipoEncuGlo'] = $tipoEncuGlobal;
                    $data['error'] = EXIT_SUCCESS;
                }else if($tipoEncuGlobal == 'A'){//ADMINISTRATIVO
                    $data['optSedes']    = __buildComboSedes();
                    $data['tipoEncuGlo'] = $tipoEncuGlobal;
                    $data['error']       = EXIT_SUCCESS;
                }else if($tipoEncuGlobal == 'D'){//DOCENTE
                    $data['optSedes'] = __buildComboSedes();
                    $data['tipoEncuGlo'] = $tipoEncuGlobal;
                    $data['error'] = EXIT_SUCCESS;
                } else if($tipoEncuGlobal == 'I'){//INVITADO
                    $data['tipoEncuGlo'] = $tipoEncuGlobal;
                    $data['error'] = EXIT_SUCCESS;
                }   
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * 
     * @author dfloresgonz
     * @since 09.04.2016
     * @param array $data
     * @param $returnMode 1 = si es P o A / null si es Invitado
     * @throws Exception
     * @return unknown
     */
    function getPreguntaInicial($data = null, $returnMode = null) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idEncuesta = _getSesion('idEncuestaActiva');
            $tipoEncuGlobal = _post('tipoEncuGlobal');
            if($tipoEncuGlobal != 'P' && $tipoEncuGlobal != 'E' && $tipoEncuGlobal != 'D' && $tipoEncuGlobal != 'A') {
                throw new Exception(ANP);
            }
            $this->session->set_userdata(array("tipoEncuGlobal" => $tipoEncuGlobal));
            $val = 1;
            $id_categoria = 0;
            ////////////////////////////CAAMBIAR POR VARIABLES
            $preguntaInicial = $this->m_encuesta->getPregInicial($idEncuesta, $tipoEncuGlobal);          
            $idPregCrypt = _simple_encrypt($preguntaInicial['_id_pregunta']);
            //-----------------------------------alternativas-----------------------------------------------------
            $res = null;
            $alternativaArray = $this->m_encuesta->getAlternativas($preguntaInicial['_id_pregunta'], $idEncuesta);
            $idFooter = 'pregunta'.$preguntaInicial['_id_pregunta'].'_'.$val.'_'.$preguntaInicial['flg_obligatorio'];
            $necesario['id_footer']    = $idFooter;
            $necesario['id_categoria'] = $id_categoria;//en la BD tiene categoria NULL
            $necesario['id_tipo_pregunta'] = $preguntaInicial['_id_tipo_pregunta'];
            $necesario['val_preguntas'] = $val;
            $necesario['id_servicio'] = 0;
            $necesario['cont_preguntas'] = 0;
            $necesario['flg_obligatorio'] = $preguntaInicial['flg_obligatorio'];
            
            $res .= '<br><div class="btn-group" data-toggle="buttons">';
            foreach($alternativaArray as $opc){
                $res .= $this->contOpcion2Opciones($opc, $necesario, $idPregCrypt);
            }
            $res .= '</div>';
            //FIN alternativas ------------------------------------------------------------------------------------
            $data['arraPregIni'] = $preguntaInicial['desc_pregunta'].$res;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        if($returnMode == null) {
            echo json_encode(array_map('utf8_encode', $data));
        } else {
            return $data;
        }
    }
    
    function registraNuevaPropM(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $nuevaPropM = utf8_decode(trim(_post('newPropM')));
            $newPropM = is_array(_post('selePropM')) ? _post('selePropM') : array();
            $idEncuesta = _getSesion('idEncuestaActiva');
            if($nuevaPropM == null){
                throw new Exception('Escribe una nueva propuesta');
            }
            $porciones = explode(" ", $nuevaPropM);
            if(count($porciones) > 5 || $nuevaPropM > 100){
                throw new Exception('Debe ingresar un máximo de cinco palabras');
            }
            $IdPropM = $this->m_encuesta->getIdPropMbyDesc(strtolower($nuevaPropM),$idEncuesta);
            if($IdPropM != null) {
                array_push($newPropM, _simple_encrypt($IdPropM));
                $idEncryptN = _simple_encrypt($IdPropM);
                
                $data['idNewPropMejora'] = $idEncryptN;
                $arraNewPropM = $this->m_encuesta->getCantPropM($idEncuesta);
                $propuestaMHTML = null;
                $data['propuestaMHTML'] = $this->getPropuestasMejoraHTML($arraNewPropM,$newPropM)[0];
                $data['error'] = EXIT_SUCCESS;
            }else{
                $arrayInsert = array("desc_propuesta" => strtoupper($nuevaPropM),
                                     "flg_estado"     => ESTADO_ACTIVO ,
                                     "count"          => 0,
                                     "_id_encuesta"   => $idEncuesta
                               );
                $data = $this->m_encuesta->insertDescProp($arrayInsert);//inserto en PG
                if($data['error'] == EXIT_SUCCESS){
                    $idEncrypt = _simple_encrypt($data['id_propInsert']);
                    $arraNewPropM = $this->m_encuesta->getCantPropM($idEncuesta);
                    $data['idNewPropMejora']   = $idEncrypt;
                    array_push( $newPropM, $idEncrypt);
                    $data['propuestaMHTML'] = $this->getPropuestasMejoraHTML($arraNewPropM,$newPropM)[0];
                }
           }       
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author César Villarreal 09/03/2016
     * @param arrayIds $arrayEncrypt
     * @return retorna array con los ids desencriptados
     */
    function decryptArrayIdsInArray($arrayEncrypt){
        $arrayDecrypt = array();
        foreach($arrayEncrypt AS $idEncry){
            array_push($arrayDecrypt, _simple_decrypt($idEncry));
        }
        return $arrayDecrypt;
    }
    
    function mostrarPropMrestantes(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $arrayEncrypt = _post('arraEncPropM');
            if($arrayEncrypt == null){
                throw new Exception(ANP);
            }
            $arrayDec = $this->decryptArrayIdsInArray($arrayEncrypt);
            $arrayPropRestantes = $this->m_encuesta->getCantRestantePropM($arrayDec);          
            $data['arraPropMRest'] = $this->getPropuestasMejoraHTML($arrayPropRestantes)[0];
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
             $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getUrlLogin(){
        $idEncuesta = _encodeCI(_getSesion('idEncuestaActiva'));
        setcookie('encuesta', $idEncuesta, time() + (86400 * 30), "/");
        $url        = RUTA_SMILEDU.'c_login';
        echo $url;        
    }
    
    function generatePDFEncuesta($respuestas){
        $idEncuesta      = _getSesion('idEncuestaActiva');
        $idPersona       = _getSesion('nid_persona_encuesta');
        if($idPersona == null){
            $idPersona = _getSesion("id_persona");
        }
        $this->load->library('m_pdf');
        $pdf             = $this->m_pdf->load('','A4', 0, '', 15, 15, 16, 16, 9, 9, 'P');
        $nomFile         = __generateRandomString(8);
        $file            = 'uploads/modulos/senc/pdf/'.$nomFile.'.pdf';
        $pdfFilePath     = "./".$file;
        $data['pdfObj']  = $pdf;
        $data           += $this->getCategoriasHTML();
        $data['titulo']  = $this->m_utils->getById('senc.encuesta', 'titulo_encuesta', 'id_encuesta', $idEncuesta);
        $data['table']   = $this->buildTableEncuestaPreguntas($this->m_encuesta->getAllPreguntasCategoriaTabla($idEncuesta),$respuestas);
        $data['nombres'] = $this->m_usuario->getNombrePersona($idPersona);
        $this->load->view('v_pdf_encuesta',$data);
        $pdf->Output($pdfFilePath, 'F');
        return $nomFile;
    }
    
    function buildTableEncuestaPreguntas($result,$respuestas){
        $tmpl     = array('table_open'  => '<table border="1" style="width:100%;border: 1px solid black;border-collapse: collapse;">',
                          'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_0 = array('data' => 'Categorias');
	    $head_1 = array('data' => 'Preguntas' );
	    $head_2 = array('data' => 'Respuestas');
	    $this->table->set_heading($head_0,$head_1,$head_2);
        foreach($result as $row){
            $cont = 0;
            $preguntas = explode(',', $row->preguntas);
            foreach($preguntas as $pregunta){
                $preg = explode('|', $pregunta);
                $arrayIdRpta = array();
                foreach($respuestas as $rpta){
                    if($rpta['id_pregunta'] == $preg[1]){
                        array_push($arrayIdRpta, $rpta['respuesta']);
                    }
                }
                $row_0 = array('data' => $row->desc_cate , 'rowspan' => count($preguntas));
                $row_1 = array('data' => $preg[0]);
                $row_2 = array('data' => ((count($arrayIdRpta) > 0 ) ? $this->m_encuesta->getRptasByPregunta($arrayIdRpta) : null));
                if($cont == 0){
                    $this->table->add_row($row_0,$row_1,$row_2);
                } else{
                    $this->table->add_row($row_1,$row_2);
                }
                $cont++;
            }
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function enviarDocEmail($correo_destino, $doc){
        $date    = date('d/m/Y h:i:s A');
        $persona = _getSesion('nombre_usuario');
        $correo  = _getSesion('correo_persona');
        $data = __enviarEmail($correo_destino,"Respuestas de Encuesta(no-reply)","<strong>Fecha:</strong> ".$date."<br>", $doc);
        return $data;
    }
    
    function borrar(){
        $pdfRuta = _post('ruta');
        if(file_exists($pdfRuta)) {
            $pdfRuta = './'._post('ruta');
            if (!unlink($pdfRuta)){
                //echo ("No se borró el archivo");
            }else{
                //echo ("Se borró");
            }
        }
        echo null;
    }
}

