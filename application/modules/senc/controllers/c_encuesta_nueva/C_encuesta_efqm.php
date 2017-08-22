<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_encuesta_efqm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_encuesta/m_encuesta');
        $this->load->model('m_crear_encuesta');
        $this->load->model('m_utils');
        $this->load->model('m_utils_senc');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->library('user_agent');
        $this->load->library('table');
        $idUsuario = _getSesion('nid_persona');
        $idRol     = _getSesion(SENC_ROL_SESS);
//         $flg_encuestado = /*$this->m_encuesta->getFlgEncuestadoByPersonaRol($idUsuario,$idRol)*/REALIZO_ENCUESTA;
        //@dfloresgonz 06.04.2016 Comentado solo para el evento de JSMercedario
        /*if(!isset($_COOKIE['smiledu'])){
            $this->session->sess_destroy();
            redirect(RUTA_SMILEDU, 'refresh');
        }*/
    }
     
    public function index() {
        $data = null;
        $idTipoEnc = null;
        $idUsuario = _getSesion('nid_persona');
        $idRol     = _getSesion(SENC_ROL_SESS);
        if($idUsuario != null) {
            $realizo   = $this->m_usuario->realizoEncuesta($idUsuario,null);
            $dataUser  = array("realizo_encuesta" => $realizo);
            $this->session->set_userdata($dataUser);
        }
        if(_get('codfam') != null) { //PADRE VA A ELEGIR A UN HIJO
            $codFam           = _decodeCI(str_replace(" ","+", _get('codfam')));
            $user_data        = array("cod_fam" => _get('codfam'));
            $this->session->set_userdata($user_data);
            $hijos            = $this->m_encuesta->getHijosByCodFamilia($codFam);
            $data['hijosFam'] = $this->createTableHijos($hijos);
            $idEncuesta       = $this->m_utils_senc->getEncuestaIdActivaByTipo(TIPO_ENCUESTA_PADREFAM);
            $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
            $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
        } else if(_get('aula') != null && _get('tipo') != null && _get('aux') != null) {//PADRE VA  A LLENAR ENCUESTA DEL HIJO
            $idPersona        = _decodeCI(str_replace(" ","+", _get('aux')));
            $realizo          = $this->m_usuario->realizoEncuesta($idPersona, TIPO_ENCUESTA_PADREFAM);
            $this->session->set_userdata(array('realizo_encuesta' => $realizo));
            $data['realizoLocal']  = 1;
            if($realizo == NO_REALIZO_ENCUESTA) {//SI EL PADRE NO HA LLENADO LA ENCUESTA DEL HIJO SELECCIONADO
                $idAula    = _decodeCI(str_replace(" ","+", _get('aula')));
                $idTipo    = _decodeCI(str_replace(" ","+", _get('tipo')));
                $user_data = array(
                    "id_aula_enc" => $idAula,
                    "id_pers_enc" => $idPersona,
                    "id_tipo_enc" => $idTipo
                );
                $this->session->set_userdata($user_data);
                $data += $this->getCategoriasHTML(null);
                $data['arraServiHTML'] = $this->getServicioHTML();
                $data['realizoLocal']  = 0;
            }
            $idTipoEnc = _getSesion('id_tipo_enc');
            $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipoEnc);
            
            //TRAE PROPUESTAS POR ENCUESTA
            $propuestaMrray = $this->m_encuesta->getCantPropM($idEncuesta);
            $dataPropuestas = $this->getPropuestasMejoraHTML($propuestaMrray, array());
            $data['arraPropMHTML'] = $dataPropuestas[0];
            $data['arrayFavProp']  = $dataPropuestas[1];
            //////////////////////////////
            $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
            $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
        } else if(_get('encu_fisica') != null) {
            $data['encu_fisicaHTML'] = $this->load->view('vf_encuesta/V_previo_fisica', $data, true);
            $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
        } else if(_get('encu_fisica_empezar') != null) {
            $idTipoEnc = _decodeCI(_get('tipo'));
            $idAula    = _decodeCI(_get('aula'));
            $idEstu    = _decodeCI(_get('idEstu'));
            ///////////
            $data['realizoLocal'] = 0;//nose porque cero
            $data['arraServiHTML'] = $this->getServicioHTML();
            $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipoEnc);
            $this->session->set_userdata(array(
                "id_tipo_enc"     => $idTipoEnc, 
                "id_aula_enc"     => $idAula, 
                "flg_encu_fisica" => "1", 
                "idEstu"          => $idEstu));
            //TRAE PROPUESTAS POR ENCUESTA
            $propuestaMrray = $this->m_encuesta->getCantPropM($idEncuesta);
            $dataPropuestas = $this->getPropuestasMejoraHTML($propuestaMrray, array());
            $data['arraPropMHTML'] = $dataPropuestas[0];
            $data['arrayFavProp']  = $dataPropuestas[1];
            //////////////////////////////
            $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
            $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
        } else {
            if(_get('aula') != null && _get('tipo') != null) { //ALUMNO VA A LLENAR
                $idAula    = _decodeCI(str_replace(" ", "+", _get('aula')));
                $idTipo    = _decodeCI(str_replace(" ", "+", _get('tipo')));
                $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipo);
                $cantAlum  = $this->m_encuesta->getCantAlumnosAula($idAula);
                $encRealiz = $this->m_encuesta->checkIf_sePuedeEncuestarEnAula($idAula, $idEncuesta);
                $locked = $this->m_utils->getById('aula', 'flg_encuesta_efqm_estu', 'nid_aula', $idAula);
                $data['realizoLocal']  = 1;
                if($encRealiz && $locked == null) {//SI AUN NO SE LLENAN LAS ENCUESTAS DE ESA AULA
                    //$idTipo    = _decodeCI(str_replace(" ","+",$this->input->get('tipo')));
                    $user_data = array("id_aula_enc" => $idAula,
                                       "id_pers_enc" => null,
                                       "id_tipo_enc" => $idTipo);
                    $this->session->set_userdata($user_data);
                    $data += $this->getCategoriasHTML(null);
                    $data['arraServiHTML'] = $this->getServicioHTML();
                    $data['realizoLocal']  = 0;

                    $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo(_getSesion('id_tipo_enc'));
                    //TRAE PROPUESTAS POR ENCUESTA
                    $propuestaMrray = $this->m_encuesta->getCantPropM($idEncuesta);
                    $dataPropuestas = $this->getPropuestasMejoraHTML($propuestaMrray, array());
                    $data['arraPropMHTML'] = $dataPropuestas[0];
                    $data['arrayFavProp']  = $dataPropuestas[1];
                    //////////////////////////////
                    $data['tipoEncuestaDesc'] = $this->m_utils->getById('senc.tipo_encuesta', 'desc_tipo_encuesta', 'id_tipo_encuesta', $idTipoEnc);
                    $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
                    $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
                }
            } else if($idUsuario != null && isset($_COOKIE[__getCookieName()])) {//ENCUESTA PERS ADMINISTRATIVO | DOCENTES
                $tipoEnc = null;
                $area = $this->m_utils->getById('rrhh.personal_detalle', 'id_area_general', 'id_persona', $idUsuario);
                if($area == ID_AREA_ACADEMICA) {
                    $tipoEnc = TIPO_ENCUESTA_DOCENTE;
                } else {
                    $tipoEnc = TIPO_ENCUESTA_PERSADM;
                }
                $user_data = array("id_aula_enc" => null,
                                   "id_pers_enc" => null,
                                   "id_tipo_enc" => $tipoEnc);
                $this->session->set_userdata($user_data);
                $data['arraServiHTML'] = $this->getServicioHTML();
                $data += $this->getCategoriasHTML(null);
                $idArea = _getSesion('id_area');
                $SNGAula = $this->m_encuesta->getNivelesByTipoEncuesta($tipoEnc,$idArea,$idUsuario);
                if($tipoEnc == TIPO_ENCUESTA_PERSADM) {
                    if($SNGAula['nid_area'] == null || $SNGAula ['nid_sede'] == null) {
                        $data['msj'] = 'Su cuenta a&uacute;n no ha sido configurada, comun&iacute;quese con el administrador';
                    }
                } else {
                    if($SNGAula['nid_area'] == null || $SNGAula ['nid_sede'] == null || $SNGAula ['nid_nivel'] == null) {
                        _log('Id docente a configurar:::'.$idUsuario);
                        $data['msj'] = 'Su cuenta a&uacute;n no ha sido configurada, comun&iacute;quese con el administrador';
                    }
                }
                $idTipoEnc = _getSesion('id_tipo_enc');
                $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipoEnc);
                //TRAE PROPUESTAS POR ENCUESTA
                $propuestaMrray = $this->m_encuesta->getCantPropM($idEncuesta);
                $dataPropuestas = $this->getPropuestasMejoraHTML($propuestaMrray, array());
                $data['arraPropMHTML'] = $dataPropuestas[0];
                $data['arrayFavProp']  = $dataPropuestas[1];
                //////////////////////////////
                $data['tipoEncuestaDesc'] = $this->m_utils->getById('senc.tipo_encuesta', 'desc_tipo_encuesta', 'id_tipo_encuesta', $idTipoEnc);
                $this->session->set_userdata(array('idEncuestaActiva' => $idEncuesta));
                $this->load->view('vf_encuesta/v_encuesta_efqm', $data);
            } else {
                redirect(RUTA_SMILEDU, 'refresh');
            }
        }
    }
    
    /**
     * @author Fernando Luna 08/04/16
     * @return Crea codigo HTML de cada servicio a seleccionar para la encuesta
     */
    public function getServicioHTML() {
       $servicioArray = $this->m_encuesta->getCantServicio();
       $servicioHTML = null;
       $contServ = 0;
       foreach($servicioArray as $rowServ) {
           $contServ++;
           $idencrypt=_encodeCI($rowServ->id_servicio);
           $servicioHTML .= '<a class="item-select" id="idServ'.$contServ.'" attr-flg_active="0" onclick="pintarActive($(this), \''.$idencrypt.'\')">'.$rowServ->desc_servicio.'</a>';        
       }
       return $servicioHTML;     
    }
    
    /**
     * @author Cesar Villarreal 06/04/16
     * @return retorna 0 si no hay servicios | 1 si es que tiene uno o mis servicios
     */   
    function getPropuestasMejoraHTML($propuestaMrray, $cantNewPropM) {
        $propuestaMHTML = null;
        $contPropM = 0;
        $arrayEncrypt = array();
        foreach($propuestaMrray as $rowPropM) {
            $contPropM++;
            $idEncrypt = _simple_encrypt($rowPropM->id_propuesta);
            foreach($cantNewPropM as $rowMarcar) {
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
    
    function evaluaServiciosInEncuesta(){
        $data['encuestaActiva'] = 0;
        $data['serviciosCount'] = 0;
        $data['error'] = EXIT_ERROR;
        try{
            $flg_encuestado = _getSesion('realizo_encuesta');
            if($flg_encuestado == REALIZO_ENCUESTA){
                throw new Exception('Ya realizaste esta encuesta, gracias');//entra en este throw
            }
            $idTipoEnc = _getSesion('id_tipo_enc');
            $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipoEnc);
            if($idEncuesta != null){
                $countServicios = $this->m_encuesta->getCountServiciosInPregByEncuesta($idEncuesta);
                if($countServicios == 0){
                    $data += $this->getCategoriasHTML(array());
                } else{
                    $data['serviciosCount'] = 1;
                }
                $data['encuestaActiva'] = 1;
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Cesar Villarreal 06/04/16
     * @return Preguntas por categoria de la encuesta
     */
    function getPreguntasCategoriasEncuesta(){
        $arrayServ = $this->input->post('arrayServ');
        $data['error'] = EXIT_ERROR;
        try{
            $flg_encuestado = _getSesion('realizo_encuesta');
            if($flg_encuestado == REALIZO_ENCUESTA){
                throw new Exception('Ya realiazaste esta encuesta, gracias');
            }
            $data = $this->getCategoriasHTML($arrayServ);
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**
     * @author Fernando Luna
     * @param array() $arrayServ
     * @return Construye el codigo HTML de cada pregunta en cada categoria de toda la encuesta
     */
    public function getCategoriasHTML($arrayServ) {
        $data['error'] = EXIT_ERROR;
        $idTipoEnc = _getSesion('id_tipo_enc');
        $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo($idTipoEnc);
        $categoriaArray = $this->m_encuesta->getCategoriasByEncuesta($idEncuesta);
        $idArrayServ = array();
        try{
            if($idEncuesta == null){
                throw new Exception('No hay una encuesta activa para ti, comun&iacute;quese con alguien a cargo');
            }
            if(count($categoriaArray) == 0){
                throw new Exception('No hay preguntas para esta encuesta');
            }
           ////////////////////////////////////////////////////////////////////////////////////////////////////
           if(count($arrayServ) == 0) {//USUARIO NO SELECCIONO NINGUN SERVICIO
//                $arrayServicio = $this->m_encuesta->getCantServicio();//CARGAR TODOS LOS SERVICIOS DE LA BD
//                foreach ($arrayServicio as $rowServ) {
//                    array_push($idArrayServ, $rowServ->id_servicio);
//                }
               $categoriaHTML = null;
               $preguntasHTML = null;
               $cont = 0;
               $cont_con_preg = 0;
               $arrObJSON = array();
               $arrCateObliJSON = array();
               $idArrayServ = null;
//                $idArrayServ = explode(';', SERVICIOS_COMPLEMENTARIOS);
               $data['idCategMainGalleryFirst'] = $categoriaArray[0]->_id_categoria;
               //RECORRE CATEGORIAS DE LA ENCUESTA
               foreach($categoriaArray as $row) {
                   array_push($arrCateObliJSON, array("id_cate"       => $row->_id_categoria,
                                                       "cntObliAct"   => 0,
                                                       "flg_pasoCate" => 0) );
                   //SELECCIONA LAS PREGUNTAS DE ESA CATEGOR�A
                   $preguntasArray = $this->m_encuesta->getPreguntasByCategoria($idEncuesta, $row->_id_categoria, $idArrayServ,null);         
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
            							                     <a data-toggle="tooltip" data-placement="left" data-original-title="Obligatoria">
                                                                 <i class="mdi mdi-mandatory mdl-color-text--grey-500"></i>
                                                             </a>
                                                         </div>' : null).'
                                                        <div class="mdl-card__supporting-text br-b text-center"><div class="row-fluid">'.($opciones).'</div></div>
                            				   		</div>
                                                </div>
                					       </div>';
                   }
                   $preguntasHTML .= '</div>
			                  </div>
                          </section>';                                  
               }
               $arrHTML = array($categoriaHTML, $preguntasHTML, $arrObJSON, $arrCateObliJSON);
               $data['cant_pregObligatorias'] = $this->m_encuesta->getcantFlagOblibyEncuesta($idEncuesta, $idArrayServ);
               $data['barraAvance'] = '0 /  '.$data['cant_pregObligatorias'];
               $data['categoriaHTML'] = $arrHTML[0];
               $data['preguntasHTML'] = $arrHTML[1];
               $data['jsonObj'] = json_encode($arrHTML[2]);
               $data['arrCateObliJSON'] = json_encode($arrHTML[3]);
               $data['error'] = EXIT_SUCCESS;
           
           } else { //El USUARIO SELECCIONO AL MENOS 1 SERVICIO
               foreach ($arrayServ as $servArray) {//LO AGREGAS Y DESENCRIPTAS
                   $idServ = _decodeCI($servArray['serv']);
                   array_push($idArrayServ, $idServ);
               }
               $categoriaHTML = null;
               $preguntasHTML = null;
               $cont = 0;
               $cont_con_preg = 0;
               $arrObJSON = array();
               $arrCateObliJSON = array();
               $data['idCategMainGalleryFirst'] = $categoriaArray[0]->_id_categoria;
               //RECORRE CATEGORIAS DE LA ENCUESTA
               foreach($categoriaArray as $row) {
                   array_push($arrCateObliJSON, array("id_cate"       => $row->_id_categoria,
                       "cntObliAct"   => 0,
                       "flg_pasoCate" => 0) );
                   //SELECCIONA LAS PREGUNTAS DE ESA CATEGOR�A
                   $preguntasArray = $this->m_encuesta->getPreguntasByCategoria($idEncuesta, $row->_id_categoria, $idArrayServ,null);
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
                       $preguntasHTML .=  ' <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 p-0">
                                                <div class="gallery-cell" data-last="'.$isLastPregunta.'">
                            				   		<div class="mdl-card '.$activeCard.'" id="'.$idFooter.'">
                        				   		        <div class="mdl-card__title p-r-50 p-l-40">
                            				   		        <div class="mdl-color-text--grey-500 mdl-typography--font-light f-s-regular post-number">'.$val.'.</div>
                        				   		            <h2 class="mdl-card__title-text mdl-color-text--grey-500 mdl-typography--font-light f-s-regular" >'.$rowPreg->desc_pregunta.'</h2>
                        				   		        </div>
                                                	   '.(($rowPreg->flg_obligatorio ==  FLG_OBLIGATORIO) ?
                                               	       '<div class="mdl-card__menu">
            							                     <a data-toggle="tooltip" data-placement="left" data-original-title="Obligatoria">
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
               $data['cant_pregObligatorias'] = $this->m_encuesta->getcantFlagOblibyEncuesta($idEncuesta,$idArrayServ);
               $data['barraAvance'] = '0 /  '.$data['cant_pregObligatorias'];
               $data['categoriaHTML'] = $arrHTML[0];
               $data['preguntasHTML'] = $arrHTML[1];
               $data['jsonObj'] = json_encode($arrHTML[2]);
               $data['arrCateObliJSON'] = json_encode($arrHTML[3]);
               $data['error'] = EXIT_SUCCESS;
           }
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
     * @return Retorna codigo HTML de cada pregunta en la encuesta
     */
    public function getOpciones($opciones, $necesario,$idPregCrypt,$flg_obli){
        $res = null;
        if($necesario['id_tipo_pregunta'] == CINCO_CARITAS){
            $numCol = '2';
            $res.= '<div class="col-xs-1"></div>';
            foreach($opciones as $opc){
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol,$flg_obli);
            }
        }else if($necesario['id_tipo_pregunta'] == TRES_CARITAS){
            $numCol = '4';
            foreach($opciones as $opc){
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol,$flg_obli);
            }
        }else if($necesario['id_tipo_pregunta'] == PROPUESTA_MEJORA){
            $res = '<div id="magicsuggest"></div>';
        }else if($necesario['id_tipo_pregunta'] == OPCION_MULTIPLE){
            $cont = 0;
            $res .= '<div class="form-group">';
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
                $res .= $this->contOpcionCaritas($opc, $necesario,$idPregCrypt,$numCol,$flg_obli);
            }
        }
        return $res;
    }
    
    /**
     * 
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna codigo HTML para el tipo de pregunta 2 Opciones (SI/NO)
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
        $opcionHTML .= '<label id="a-'.$id_categoria.'-'.$val.'" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'"
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
     * @return Retorna codigo HTML para el tipo de pregunta caritas (3,4,5)
     */
    public function contOpcionCaritas($opcion, $necesario,$idPreguntaEnc,$numCol,$flg_obli){
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
     * @return Retorna codigo HTML para el tipo de pregunta opcion multiple (RadioButton)
     */
    public function contOpcionMultiple($opcion, $necesario,$idPreguntaEnc){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $css              = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $id_servicio      = $necesario['id_servicio'];
        $flg_oblig        = $necesario['flg_obligatorio'];
        $str = $opcion->desc_alternativa;
        $opcionHTML = null;
        $opcionHTML .='<label data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" class="radio-inline radio-styled radio-success m-t-5 m-b-5">
						      <input type="radio" id="a-'.$id_categoria.'-'.$val.'" name="radioVals'.$val.'" data-tipo-preg="multiple" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="multipleOpc" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');"><span>'.$str.'</span>
					      </label>';
        
        return $opcionHTML;
    }
    
    /**
     *
     * @param alternativas $opciones
     * @param datosNecesarios $necesario
     * @param idPreguntaEncriptado $idPregCrypt
     * @return Retorna codigo HTML para el tipo de pregunta casillas de verificaci�n (CheckBox)
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
        $str = $opcion->desc_alternativa;
        $opcionHTML = null;
        /*$opcionHTML .='	<label class="checkbox-inline checkbox-styled checkbox-warning">
    						<input type="checkbox" id="a-'.$id_categoria.'-'.$val.'-'.$cont.'" data-flg_obli="'.$flg_oblig.'" data-tipo-preg="casilla" data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" value="optionM" onclick="selectAnswer(\''.$css.'\',\''.$idFooter.'\', '.$id_categoria.', '.$val.',$(this), '.$cont_preguntas.');">
    						<span class="questionTb">'.$str.'</span>
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
     * @return Retorna codigo HTML para el tipo de pregunta lista desplegable (ComboBox)
     */
    public function contOpcionListDesplegable($opcion, $necesario,$idPreguntaEnc){
        $idAlternativ     = $opcion->_id_alternativa;
        $idAlternativaEnc = _encodeCI($idAlternativ);
        $str = $opcion->desc_alternativa;
        $css              = $opcion->css_alternativa;
        $id_categoria     = $necesario['id_categoria'];
        $idFooter         = $necesario['id_footer'];
        $val              = $necesario['val_preguntas'];
        $cont_preguntas   = $necesario['cont_preguntas'];
        $opcionHTML = null;
        $opcionHTML .= '<option data-id_pregunta = "'.$idPreguntaEnc.'" data-id_alternativa = "'.$idAlternativaEnc.'" attr-id-categoria="'.$id_categoria.'">'.$str.'</option>';
        
        return $opcionHTML;
    }
    
    /**
     * @author Cesar / Diego
     * @return Inserta los datos en las colecciones 
     */
    public function enviarEncuesta() {
        $start = microtime(true);
        $this->db->trans_begin();
        try {
            if(_getSesion("flg_envio_enc") == 1) {
                throw new Exception('Procesando...');
            }
            $this->session->set_userdata(array("flg_envio_enc" => 1));
            $idUsuario    = _getSesion('nid_persona');
            $tipoEncuesta = _getSesion('id_tipo_enc');
            $idEncuesta   = $this->m_utils_senc->getEncuestaIdActivaByTipo($tipoEncuesta);

            $idAula      = _getSesion('id_aula_enc');
            $idArea      = _getSesion('id_area');
            $objJson     = _post('objJson');
            $contObli    = _post('contador');
            $objJSONProp = _post('objJSONProp');
            $comenPropM  = _post('comenPropM');
            $arrayCheck  = _post('arrayCheck');
            $metaDatos   = _post('client_info')[0];
            $arryInfo    = array();
            $data['error'] = EXIT_ERROR;
            $data['msj']   = null;
            $SNGAula   = array();
            $isAperturada = $this->m_encuesta->validarEncuestaAperturada($idEncuesta);
            if($isAperturada != 1) {_log('$idEncuesta caduca: '.$idEncuesta);
                throw new Exception('Esta encuesta ya caduc&oacute;, Muchas Gracias.');
            }
            $flg_encuestado = _getSesion('realizo_encuesta');
            if($flg_encuestado == REALIZO_ENCUESTA) {
                throw new Exception('Ya realizaste esta encuesta, gracias.');
            }
            $this->session->set_userdata(array('tipoEncuestadoLibre' => null));
            //@PENDIENTE Hasta implementar la tabla detalle personal @Diego
            //             if($idArea == null ){
            //                 throw new Exception('Ocurrio un error... comuniquese con alguien a cargo por favor');
            //             }
            $data = $this->validarEncuesta($objJson, $objJSONProp, $idAula,$arrayCheck,$idEncuesta); _log('000');
            if($data['error'] == SUCCESS_MONGO) {
                $SNGAula = array('nid_sede' => '0','nid_nivel' => '0','nid_grado' => '0','nid_aula' => '0','nid_area' => '0');
                if($tipoEncuesta == TIPO_ENCUESTA_DOCENTE) {
                    $SNGAula = $this->m_encuesta->getNivelesByTipoEncuesta($tipoEncuesta,$idArea,$idUsuario);
                    if($SNGAula['nid_area'] == null || $SNGAula['nid_sede'] == null || $SNGAula['nid_nivel'] == null) {
                        _log('Id Docente a configurar:::'.$idUsuario);
                        throw new Exception('Tu cuenta no ha sido configurada');
                    }
                } else if($tipoEncuesta == TIPO_ENCUESTA_PERSADM) {
                    $SNGAula = $this->m_encuesta->getNivelesByTipoEncuesta($tipoEncuesta,$idArea,$idUsuario);
                    if($SNGAula['nid_area'] == null || $SNGAula['nid_sede'] == null) {
                        throw new Exception('Tu cuenta no ha sido configurada');
                    }
                } else if($tipoEncuesta == TIPO_ENCUESTA_PADREFAM || $tipoEncuesta == TIPO_ENCUESTA_ALUMNOS ) {
                    $SNGAula = $this->m_encuesta->getNivelesByTipoEncuesta($tipoEncuesta, $idAula, null);
                    if($SNGAula['nid_grado'] == null || $SNGAula['nid_sede'] == null || $SNGAula['nid_nivel'] == null || $SNGAula['nid_aula'] == null) {
                        throw new Exception('Tu cuenta no ha sido configurada');
                    }
                    $sePuedeEncuestar = $this->m_encuesta->checkIf_sePuedeEncuestarEnAula($idAula, $idEncuesta);
                    log_message('error', '$sePuedeEncuestar: '.$sePuedeEncuestar);
                    /*if($cantReali == null && $cantReali != 0) {
                        throw new Exception('Error interno');
                    }*/
                    if(!$sePuedeEncuestar) {
                        throw new Exception('Ups! parece que esta encuesta ya ha terminado');
                    }
                    if(_getSesion('flg_encu_fisica') != null) {
                        $rows = $this->m_crear_encuesta->actualizarEstudianteFlgEncuestaFisica($SNGAula['nid_aula'], _getSesion('idEstu'));
                        if($rows != 1) {
                            throw new Exception('Hubo un error al actualizar al encuestado.');
                        }
                    }
                }
                $arrayGeneral   = $data['arrayGeneral'];
                $arrayPropuesta = $data['arrayPropuesta'];
                $arryInfo['id_encuesta']     = $idEncuesta;
                $arryInfo['tipo_encuestado'] = _getSesion('tipoEncuestadoLibre');
                $arryInfo['nid_sede']  = isset($SNGAula['nid_sede'])  ? $SNGAula['nid_sede']  : null;
                $arryInfo['nid_nivel'] = isset($SNGAula['nid_nivel']) ? $SNGAula['nid_nivel'] : null;
                $arryInfo['nid_grado'] = isset($SNGAula['nid_grado']) ? $SNGAula['nid_grado'] : null;
                $arryInfo['nid_aula']  = isset($SNGAula['nid_aula'])  ? $SNGAula['nid_aula']  : null;
                $arryInfo['nid_area']  = isset($SNGAula['nid_area'])  ? $SNGAula['nid_area']  : null;

                _log('111');
                $respuestas       = null;
                $propuestasMejora = null;
                foreach($data['arrayGeneral'] as $row) {
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
                } _log('222');
                foreach($data['arrayPropuesta'] as $row) {
                    $desc_propuesta = $this->m_utils->getById('senc.propuesta_mejora','desc_propuesta', 'id_propuesta', $row['id_propuesta']);
                    $propuestasMejora .= '{
                                              "id_propuesta"   : '.$row['id_propuesta'].',
                                              "desc_propuesta" : "'.$desc_propuesta.'",
                                              "count"          : 1
                                          },';
                }
                $respuestas       = rtrim(trim($respuestas), ",");
                $propuestasMejora = ($propuestasMejora != null) ? rtrim(trim($propuestasMejora), ",") : null;
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
                $arryInfo['flg_encu_fisica'] = _getSesion('flg_encu_fisica');
//                 $idDeviceInfo = $this->saveClientDeviceInfo($arryInfo);//Id encuestado
                $idDeviceInfo = $this->m_encuesta->insertDeviceInfoEncuestadoAux($arryInfo)['id_dispositivo'];
                if($idDeviceInfo == null) {
                    $data['error'] = ERROR_MONGO;
                    throw new Exception($idDeviceInfo['msj']);
                }
                //$data = $this->m_encuesta->insertDeviceInfoEncuestado($arryInfo);
                $arrayRollBack = array();
                $id_tipo_Enc = $this->m_encuesta->getIdTipoEncbyIdEnc($idEncuesta);
                $data = $this->m_encuesta->insertRptaMongoDB($arrayGeneral, $tipoEncuesta, $SNGAula, $idEncuesta, $idDeviceInfo, $arrayRollBack);_log('aaa');
                if($data['error'] == SUCCESS_MONGO) {_log('bbb');
                    $data = $this->m_encuesta->llenaEncSatistaccion($arrayGeneral, $tipoEncuesta,$SNGAula,$idEncuesta, $data['arrayRollBack'], $id_tipo_Enc);
                    if($data['error'] == SUCCESS_MONGO) {_log('ccc');
                        $data = $this->m_encuesta->llenaEncInsatistaccion($arrayGeneral, $tipoEncuesta,$SNGAula,$idEncuesta, $data['arrayRollBack'], $id_tipo_Enc);
                        if($data['error'] == SUCCESS_MONGO) {_log('ddd');
                            $data = $this->m_encuesta->insertPropuMejora($arrayPropuesta, $tipoEncuesta, $SNGAula,$idEncuesta, $data['arrayRollBack']);
                            if($data['error'] == SUCCESS_MONGO && count($objJSONProp) > 0) {_log('eee');
                                $comenPropM = str_replace("\"", "'", $comenPropM);
                                $data = $this->m_encuesta->insertPropuestaMejoraComentario($arrayPropuesta, $tipoEncuesta, $SNGAula, $idEncuesta, $idDeviceInfo, $data['arrayRollBack'], utf8_decode(trim($comenPropM)));
                            }
                        }
                    }
                }
            } _log('444');
            if($data['error'] == ERROR_MONGO) {
                $data = $this->m_encuesta->executeRollBack($data['arrayRollBack']);
                if($idDeviceInfo != null) {_log('fff');
                    $this->m_encuesta->borrarDeviceInfoEncuestado($idDeviceInfo);
                }
            }
            unset($data['arrayRollBack']);
            if($data['error'] == SUCCESS_MONGO) {
                $this->m_encuesta->aumentaCantEnc($idEncuesta);
                if(_getSesion('id_tipo_enc') == TIPO_ENCUESTA_PADREFAM && _getSesion('flg_encu_fisica') == null) { //llenado no fisico (regular)
                    $id_persona = _getSesion('id_pers_enc');
                    $array_update = array("flg_encuesta" => 1); _log('555');
                    $dataUser = array("realizo_encuesta" => REALIZO_ENCUESTA);
                    $this->session->set_userdata($dataUser);
                    $this->m_encuesta->updateFlgEncuestaPersona($id_persona, $array_update, TIPO_ENCUESTA_PADREFAM);
                } else if(_getSesion('nid_persona') != null && _getSesion('id_tipo_enc') != TIPO_ENCUESTA_ALUMNOS) {
                    $this->m_encuesta->updateFlgEncuestaPersona(_getSesion('nid_persona'), array("flg_encuesta" => 1), null);
                    $this->session->set_userdata(array("realizo_encuesta" => REALIZO_ENCUESTA));
                }
            }
            $this->db->trans_commit(); _log('666');
            $this->session->set_userdata(array("flg_envio_enc" => null));
        } catch(Exception $e) {
            if(isset($data['arrayGeneral'])) {
                unset($data['arrayGeneral']);
            }
            if(isset($data['arrayPropuesta'])) {
                unset($data['arrayPropuesta']);
            }
            $data['error'] = ERROR_MONGO;
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
            _log('MESSAGE:::::::::::::::::: '.$data['msj']);
        }
        $time_elapsed_secs = microtime(true) - $start;
        $unidMedida = 'segundo(s)';
        if($time_elapsed_secs >= 60) {
            $time_elapsed_secs = $time_elapsed_secs / 60;
            $unidMedida = 'minuto(s)';
        }
        _log('FINALIZO OK en '.(round($time_elapsed_secs, 2)).' '.$unidMedida.'   ::: '.print_r($data, true));
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveClientDeviceInfo($arryInfo) {
        $this->load->library('user_agent');
    
        //$arryInfo['fecha']     = date('d/m/Y H:i:s'); comentado dfloresgonz 15.10.16 now() en BD
        $arryInfo['id_address'] = $this->input->ip_address();
        $arryInfo['_browser']   = ($this->agent->is_browser()) ? $this->agent->browser().' '.$this->agent->version() : 'Otro';
        $arryInfo['sist_oper_ci'] = $this->agent->platform();
        $arryInfo['es_mobile']  = $this->agent->is_mobile() ? 'SI' : 'NO';
        $arryInfo['redirect']  = $this->agent->is_referral() ? 'SI' : 'NO';
    
        return $this->m_encuesta->insertDeviceInfoEncuestado($arryInfo);
    }
    
    /**
     * @author Fernando Luna
     * @param arrayPreguntasRespuestas $objJson
     * @param arrayPropuestas $objJSONProp
     * @param idAula $idAula
     * @throws Exception
     * @return Retorna 0 en caso de success o 1 en caso de error y tambien los arrays para insertar
     */
    public function validarEncuesta($objJson, $objJSONProp, $idAula, $arrayCheck, $idEncuesta) {
        $arrayIdBdPregs = $this->m_encuesta->getIdsPreguntasByEncuesta($idEncuesta);
        $arrayGeneral = array();
        foreach ($objJson as $row) {
            $idPreg = _simple_decrypt($row['id_preg']);
            $idAltr = $this->encrypt->decode($row['id_alter']);
            //1. id_preg sea diferente de nulo
            if($idPreg == null) {
                throw new Exception("PREGUNTA NULA");
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
                throw new Exception("PREGUNTA NO PERTENECE A LA ENCUESTA");
            }
            //4.que la pregunta obligatoria tenga alternativa
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr == null) {
                throw new Exception('Le falt&oacute; marcar al menos una pregunta obligatoria');
            }
            //5.que la alternativa pertenezca a la pregunta
            $idTipoPreg = $this->m_encuesta->getIdTipoPreguntaByPreguntaEncuesta($idPreg,$idEncuesta);
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr != null) {
                $cntAlterEnPreg = ($idTipoPreg == CINCO_CARITAS || $idTipoPreg == CUATRO_CARITAS || $idTipoPreg == TRES_CARITAS ) ? $this->m_encuesta->getExisteAlterInPregunta($idPreg,$idEncuesta,$idAltr) : $this->m_encuesta->getExisteAlterInPreguntaMulti($idPreg,$idEncuesta,$idAltr);
            }
            if(isset($cntAlterEnPreg) && $cntAlterEnPreg != ALTERNATIVA_PERTENECE) {
                throw new Exception("ALTERNATIVA NO PERTENECE A LA ENCUESTA");
            }
            if($idTipoPreg != CASILLAS_VERIFICACION){
                if($idAltr != null) {
                    array_push($arrayGeneral, array('id_pregunta' => $idPreg,'respuesta' => $idAltr));
                }
            } else {
                if(count($arrayCheck) != 0){
                    $arrayGeneral = $this->addToArrayGeneral($arrayGeneral,$arrayCheck,$idEncuesta);
                }
            }
        }
        $arrayPropMejora = array();
        //Evalua las propuestas de mejora
        if(is_array($objJSONProp)) {
            foreach($objJSONProp as $rowBtn) {
                $idPropMDecrypt =_simpleDecryptInt($rowBtn);
                if($idPropMDecrypt == null ) {
                    throw new Exception(ANP);
                }
                //AUMENTAR EL COUNT UPDATE PROPUESTA_MEJORA SET COUNT  = COUNT + 1 WHERE ID_PROP = $idPropMDecrypt
                $this->m_encuesta->aumentaContPropM($idPropMDecrypt);
                $descPropM = $this->m_encuesta->getCantidadPropM($idPropMDecrypt);
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
    
    function addToArrayGeneral($arrayGeneral,$arrayCheck,$idEncuesta){
        $arrayIdBdPregs = $this->m_encuesta->getIdsPreguntasByEncuesta($idEncuesta);
        foreach ($arrayCheck as $row) {
            $idPreg = _simple_decrypt($row['id_preg']);
            $idAltr = $this->encrypt->decode($row['id_alter']);
            //1. id_preg sea diferente de nulo
            if($idPreg == null) {
                throw new Exception("PREGUNTA NULA");
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
                throw new Exception("PREGUNTA NO PERTENECE A LA ENCUESTA");
            }
            //4.que la pregunta obligatoria tenga alternativa
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr == null) {
                throw new Exception('Le falt&oacute; marcar al menos una pregunta obligatoria');
            }
            //5.que la alternativa pertenezca a la pregunta
            $idTipoPreg = $this->m_encuesta->getIdTipoPreguntaByPreguntaEncuesta($idPreg,$idEncuesta);
            if($pregEncu['flg_obligatorio'] == FLG_OBLIGATORIO && $idAltr != null) {
                $cntAlterEnPreg = ($idTipoPreg == CINCO_CARITAS || $idTipoPreg == CUATRO_CARITAS || $idTipoPreg == TRES_CARITAS ) ? $this->m_encuesta->getExisteAlterInPregunta($idPreg,$idEncuesta,$idAltr) : $this->m_encuesta->getExisteAlterInPreguntaMulti($idPreg,$idEncuesta,$idAltr);
            }
            if(isset($cntAlterEnPreg) && $cntAlterEnPreg != ALTERNATIVA_PERTENECE) {
                throw new Exception("ALTERNATIVA NO PERTENECE A LA ENCUESTA");
            }
            if($idAltr != null) {
                array_push($arrayGeneral, array('id_pregunta' => $idPreg,'respuesta' => $idAltr));
            }
        }
        return $arrayGeneral;
    }
    
    function getListaPropuestas() {
        $arryPropuestas = array();
        $arrayPropuestas = $this->m_encuesta->getPropuestas();
        $json = json_encode($arrayPropuestas);
        echo $json;
    }
    
    function getListaAulas() {
        $arryAulas = $this->m_utils->getAulasAll();
        $json = json_encode($arryAulas);
        echo $json;
    }
    
    function registraNuevaPropM(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $flg_encuestado = _getSesion('realizo_encuesta');
            $idEncuesta = _getSesion('idEncuestaActiva');
            if($idEncuesta == null){
                throw new Exception();
            }
            if($flg_encuestado == REALIZO_ENCUESTA){
                throw new Exception('Ya realizaste esta encuesta, gracias');
            }
            $nuevaPropM = utf8_decode(trim($this->input->post('newPropM')));
            $newPropM = is_array($this->input->post('selePropM')) ? $this->input->post('selePropM') : array();
            if($nuevaPropM == null){
                throw new Exception('Escribe una nueva propuesta');
            }
            $porciones = explode(" ", $nuevaPropM);
            if(count($porciones) > CANT_MAX_PALABRAS){
                throw new Exception('Debe ingresar un m&aacute;ximo de 5 palabras');
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
            $arrayEncrypt = $this->input->post('arraEncPropM');
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
    
    
    function getSedes() {
        $data['optSedes'] = __buildComboSedes();
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getInfoAulaElegida() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAulaEnc = _post('idAula');
            if($idAulaEnc == null) {
                throw new Exception(ANP);
            }
            $idEncuesta = $this->m_utils_senc->getEncuestaIdActivaByTipo(TIPO_ENCUESTA_ALUMNOS);
            $idAula    = _decodeCI($idAulaEnc);
            $tipo_enc  = _encodeCI(TIPO_ENCUESTA_ALUMNOS);
            $cantAlumn = $this->m_encuesta->getCantAlumnosAula($idAula);
            $cantReali = $this->m_encuesta->getCantEncuestasRealizadasByAula($idAula, $idEncuesta);_log('>>>$cantReali::::>>>>> '.$cantReali);
            $data['perm']      = 0;
            $data['cantAlum']  = $cantAlumn;
            $data['encRealiz'] = $cantReali;
            $locked = $this->m_utils->getById('aula', 'flg_encuesta_efqm_estu', 'nid_aula', $idAula);
            $data['candado'] = ($locked == null ) ? 'mdi mdi-lock_open' : 'mdi mdi-lock';
            if($cantAlumn > $cantReali) {
                $data['perm'] = 1;
                $tiny   = $this->get_tiny_url(base_url()."senc/c_encuesta_nueva/c_encuesta_efqm?aula=".$idAulaEnc."&tipo=".$tipo_enc);
                $data['urlTiny'] = str_replace("http://", "", $tiny);
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function bloquearAulaEncuEFMQEstu() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $idAula = _decodeCI(_post('idAula'));
            if($idAula == null) {
                throw new Exception(ANP);
            }
            $locked = $this->m_utils->getById('aula', 'flg_encuesta_efqm_estu', 'nid_aula', $idAula);
            $valorNuevo = ($locked == null) ? '1' : null;
            $data = $this->m_utils->updateTabla('aula', 'nid_aula', $idAula, 'flg_encuesta_efqm_estu', $valorNuevo);
            $data['candado'] = ($valorNuevo == null ) ? 'mdi mdi-lock_open' : 'mdi mdi-lock';
            $data['msj'] = ($valorNuevo == 1) ? 'Se bloque&oacute;' : 'Se desbloque&oacute;';
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function get_tiny_url($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    function createTableHijos($hijos) {
        $res = '<ul class="mdl-list">';
        foreach ($hijos as $h){
            $style   = "";
            $onclick = "";
            $iconBTN = "lock";
            if($h->estado == 'person'){
                $style     = "cursor:pointer";
                $idAula    = _encodeCI($h->nid_aula);
                $idPersona = _encodeCI($h->nid_persona);
                $tipo_enc  = _encodeCI(TIPO_ENCUESTA_PADREFAM);
                $onclick   = "goToEncuestaAlumno('".$idAula."', '".$idPersona."', '".$tipo_enc."')";
                $iconBTN   = "input";
            }
            $res .= '<li class="mdl-list__item mdl-list__item--three-line" >
                            <span class="mdl-list__item-primary-content">
                              <i class="mdl-list__item-avatar mdi mdi-'.$h->estado.'"></i>
                              <span class="mdl-list__item-text-body">'.$h->nombre_completo.'</span>
                              <span>'.$h->ubic.'</span>
                            </span>
                            <span class="mdl-list__item-secondary-content">
                              <button class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" style="'.$style.'" onclick="'.$onclick.'" ><i class="mdi mdi-'.$iconBTN.'"></i></button>
                            </span>
                      </li>';
        }
        $res .= '</ul>';
        return $res;
    }

    function redirect() {
        $codFam = _getSesion('cod_fam');
        if(isset($codFam)) {
            redirect(base_url().'senc/c_encuesta_nueva/c_encuesta_efqm?codfam='._getSesion('cod_fam'), 'refresh');
        } else {
            redirect(RUTA_SMILEDU, 'refresh');
        }
    }
    
    function redirectLoginPadres(){
        $this->session->sess_destroy();
        echo RUTA_SMILEDU.'Padres';
    }
    
    function getEstudiantesEncuFisica() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula = _decodeCI(_post('idAula'));
            if ($idAula == null) {
                throw new Exception(ANP);
            }
            $data['comboEstus'] = __buildComboEstuEncuestaFisica($idAula);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }
    
    function logoutAux(){
        $this->session->sess_destroy();
        echo base_url();
    }
}