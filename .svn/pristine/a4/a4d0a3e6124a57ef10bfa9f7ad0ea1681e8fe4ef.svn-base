<?php defined('BASEPATH') or exit('No direct script access allowed');

class C_docentes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->helper('html');
        $this->load->model('../m_utils');
        $this->load->model('m_docente');
        $this->load->library('table');
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_DOCENTES_AULAS, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);       
    }

    public function index() {
        $idUserSess = $this->_idUserSess;
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);       
        $data['titleHeader']      = 'Docentes y aulas';
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;

        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
          
        $data2 = _searchInputHTML('Busca tus Aulas');
        $data = array_merge($data, $data2);
        $data['cmbAreas']         = __buildComboAreasAcademicas();
        $data['cmbYears']         = __buildComboYearsAcademicos();        
        $data['cmbGradoNivel']    = __buildComboGradoNivel_All(); 
        //$data['tbGruposConsulta'] = $this->getTableConsultarGrupos();
        
        $this->load->view('v_docentes', $data);
    }
    
    function getAulas() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCurs_Aulas'] = $this->tablaselectAulas($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaselectAulas($idGrado, $idAnio) {
        $arrayAulas = $this->m_docente->getAulas($idGrado, $idAnio);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbAulas" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);     
        $head_0 = array('data' => 'Aulas', 'class' => 'text-left');
        $head_1 = array('data' => 'Cursos. Asig.', 'class' => 'text-center');
        $head_2 = array('data' => 'Tutor', 'class' => 'text-center');
        $head_3 = array('data' => 'Acciones', 'class' => 'text-center');        
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3);
        $val = 0;
        $fotoTut = null;
        $countCursos = null;
        foreach($arrayAulas as $row) {
            
            $countCursos = $this->m_docente->countCursos($idGrado, $idAnio, $row->nid_aula);
            $fotoTutor   = $this->m_docente->getFotoTutor($row->nid_aula);
            $val++;
            $actions = '<button class="mdl-button mdl-js-button mdl-button--icon" onclick="getCursos($(this))">
                            <i class="mdi mdi-visibility"></i>
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--icon" onclick="getModalTuCo($(this))">
                            <i class="mdi mdi-assignment_ind"></i>
                        </button>';   
            $fotoTut = '<img src="'.$fotoTutor['foto_persona'].'" width=30 height=30 class="img-circle" data-toggle="tooltip" data-placement="bottom" title="'.$fotoTutor['nombre_corto'].'">
	                      <p class="classroom-value" style="display: inline">';
            if($fotoTutor['foto_persona'] == null || $fotoTutor['foto_persona'] == '') {
                $row_2 = array('data' => '-' , 'class' => 'text-center btnFoto', 'data-foto_tutor' => $fotoTutor['foto_persona'], 'data-nombre_tutor' => $fotoTutor['nombre_corto']);              
            } else {
                $row_2 = array('data' => $fotoTut , 'class' => 'text-center btnFoto', 'data-foto_tutor' => $fotoTutor['foto_persona'], 'data-nombre_tutor' => $fotoTutor['nombre_corto']);         
            }
            $row_0 = array('data' => _ucwords($row->desc_aula).' '.$row->sede, 'class' => 'text-left btnAulaID', 'data-id_aula' => _simple_encrypt($row->nid_aula));
            $row_1 = $this->colorCantidad($countCursos['count_cursos'], $countCursos['total']);
            $row_3 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2, $row_3);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function colorCantidad($countCursos, $total) {
        if($countCursos == $total && $countCursos != 0) {    
            $row_1 = array('data' => '<span class="label label-success">'.$countCursos.'/'.$total.'</span>');
            return $row_1;     
        } else if($countCursos != $total) {
            $row_1 = array('data' => '<span class="label label-warning">'.$countCursos.'/'.$total.'</span>');
            return $row_1;
        } else {
            $row_1 = array('data' => '<span class="label label-danger">'.$countCursos.'/'.$total.'</span>');
            return $row_1;
        }
    }
    
    function getCursos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            $idAula  = _simpleDecryptInt(_post('idAula'));
            if($idGrado == null || $idAnio == null || $idAula == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaCursos'] = $this->buildHTML_CursosByAulaGrado($idGrado, $idAnio, $idAula);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_CursosByAulaGrado($idGrado, $idAnio, $idAula) {
        $arrayCursos = $this->m_docente->getCursos($idGrado, $idAnio);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbCursos" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);      
        $head_0 = array('data' => 'Cursos', 'class' => 'text-left');
        $head_1 = array('data' => 'Docente(s)', 'class' => 'text-lefet', 'data-field' => 'doc_asig');
        $head_3 = array('data' => 'Grupo(s)', 'class' => 'text-center');
        $head_2 = array('data' => 'Acciones', 'class' => 'text-center');
        
        $this->table->set_heading($head_0, $head_1, $head_3, $head_2);
        $val = 0; 
        $grupos = $this->m_docente->getGrupos($idGrado, null);
        foreach($arrayCursos as $row) {
            $docentes = $this->m_docente->getDocentesByAulaCurso($idAula, $row['id_curso']);
            $val++;           
            $actions = '<button id="asigDocente" class="mdl-button mdl-js-button mdl-button--icon" data-cont="'.$val.'" onclick = "getDocenteModal($(this))">
                            <i class="mdi mdi-assignment_ind" title="Asignar docentes"></i>
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--icon" data-cont="'.$val.'" data-curso_taller = "'.$row['curso_taller'].'" onclick = "getAsigGrupoModal($(this))">
                            <i class="mdi mdi-group" title="Asignar grupos"></i>
                        </button>';
            ////////// TRAER LOS DOCENTES (tabla main) QUE ENSENAN EN UN CURSO / AULA
            $docentesHTML = null;
            $gruposHTML   = null;
            foreach ($docentes as $doc) {
                list($style, $bg, $t) = $this->flgActivoInactivo($doc['flg_activo'], $doc['flg_titular']);
                $docentesHTML .= '  <span class="mdl-chip mdl-chip--contact mdl-chip--deletable" style="'.$bg.'" id="btnDocente-'._encodeCI($doc['nid_main']).'">
                                        <img class="mdl-chip__contact" src="'.$doc['foto_persona'].'">
                                        <span class="mdl-chip__text" style="'.$style.'">'.$doc['nombre_corto'].$t.'</span>
                                        <button class="mdl-button mdl-js-button mdl-button--icon mdl-chip__action" data-id_docente="'._simple_encrypt($doc['nid_persona']).'" data-id_main="'._simple_encrypt($doc['nid_main']).'" data-activ_desac="'.$doc['flg_activo'].'" onclick="asigDesaModal($(this))"><i class="mdi mdi-edit"></i></button>
                                    </span>';
            }
               
            $countGrupos = $this->m_docente->countGrupos($row['id_curso'], null, $idGrado, $idAnio);
            if($countGrupos == null) {
                throw new Exception(ANP);
            }
            
            if($countGrupos->count_taller > 0 || $countGrupos->count_cursos > 0) {
                $gruposHTML .= '<span onclick="getGrupoModal($(this))" style="cursor:pointer"><i class="mdi mdi-group_work"></i></span>';
            }
            
            $row_0 = array('data' => $row['desc_curso'], 'class' => 'text-left  btnDoc', 'data-id_curso' => _simple_encrypt($row['id_curso']));           
            $row_1 = array('data' => $docentesHTML,'class' => 'text-left');
            $row_2 = array('data' => $gruposHTML  ,'class' => 'text-center');
            $row_3 = array('data' => $actions     ,'class' => 'text-center');
              
            $this->table->add_row($row_0, $row_1, $row_2, $row_3);
        }   
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function flgActivoInactivo($flgActivo, $flgTitular) {
        $style = ($flgActivo == FLG_DOCENTE_ASIGNADO) ? $style = null : $style = 'text-decoration: line-through;';  
        if($flgTitular == DOCENTE_TITULAR) {
            $background = 'background-color:lightblue';
            $tit = "(T)";
            return array($style, $background, $tit);
        } else if($flgTitular == DOCENTE_SUPLENTE) {
            $background = null;
            return array($style, $background, null);
        }
    }
    
    function getDocentesAsig() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idAnio  = _decodeCI(_post('idAnio'));
            if($idGrado == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $data['error']        = EXIT_SUCCESS;
            $data['tablaDocAsig'] = $this->docentesAsigModal($idGrado, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_Docentes_ToAsignar($busqueda, $idAula, $idCurso) {
        $arrayDocentes = $this->m_docente->getDocentesParaAsignar($busqueda, $idAula, $idCurso);
        if($arrayDocentes == "") {
            $arrayDocentes = "-";
        }
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbDocAsig" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'     , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        foreach ($arrayDocentes as $row) {
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
	                         <p class="classroom-value" style="display: inline">'.$row['nombre_corto'].'</p>';
            $row_0 = $i;
            $row_1 = array('data' => $imageStudent, 'class' => 'text-left');
            $radio = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radiodoc_'.$i.'">
				          <input id="radiodoc_'.$i.'" type="radio" class="mdl-radio__button recto" name="radioVals" data-id_doc="'._simple_encrypt($row['nid_persona']).'" data-nom="'.$row['nombre_corto'].'" data-foto="'.$row['foto_persona'].'" onclick="radioCheck($(this))">
				          <span></span>
			          </label>';
            $row_2 = array('data' => $radio, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function asignarDocente() {
        try {
            $idPersona = _simple_decrypt(_post('idPersona'));
            $idCurso   = _simple_decrypt(_post('idCurso'));
            $idAula    = _simple_decrypt(_post('idAula'));
            $idGrado   = _decodeCI(_post('idGrado'));
            $idAnio    = _decodeCI(_post('idAnio'));
            
            if($idPersona == null || $idCurso == null || $idAula == null) {
                throw new Exception(ANP);
            }
            
            $arrayAsig = array( 
                "nid_curso"     => $idCurso,
                "nid_aula"      => $idAula,
                "estado"        => FLG_ACTIVO
            );
            $data     = $this->m_docente->asignarDocenteMain($arrayAsig);
            $titular  = $this->countTitular($idAula, $idCurso);
            $fechaHoy = date("Y-m-d H:i:s");
            $arrayAsigDoc = array(
            		"__id_docente"  => $idPersona,
            		"__id_main"     => $data['idmain'],
            		"fec_in"        => $fechaHoy,
                    "flg_titular"   => $titular,
            		"flg_activo"    => FLG_ACTIVO
            );
            $data = $this->m_docente->asignarDocenteGxD($arrayAsigDoc);
            if($data['error'] == EXIT_SUCCESS) {
            $data['error'] == EXIT_SUCCESS; 
                //Notificar Via Email al docente
                /*$docenteData = $this->m_usuario->getDatosPersona($idPersona);
                $datosCurso = $this->m_utils->getDatosDocenteEmailAsig($idAula, $idAnio, $idGrado, $idCurso);
                $body = $this->armarBodyCorreoNotifAsig($docenteData, $datosCurso['desc_sede'], _ucwords($datosCurso['desc_curso']), _ucwords($datosCurso['desc_aula']));
                $data = $this->lib_utils->enviarEmail($docenteData['correo'], 'Asignaci�n a un curso', $body);*/
                if($data['error'] == EXIT_SUCCESS) {
                    $data['msj'] = 'Se asign&oacute; al docente y se le envi&oacute; un correo de notificaci&oacute;n';
                }
                $data['tablaCursos']     = $this->buildHTML_CursosByAulaGrado($idGrado, $idAnio, $idAula);
                $data['tablaCurs_Aulas'] = $this->tablaselectAulas($idGrado, $idAnio);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function countTitular($idAula, $idCurso) {
        $countTitular = $this->m_docente->countTitular($idAula, $idCurso);
        if($countTitular == null) {
            return 0;
        } else {
            return DOCENTE_TITULAR;
        }
    }
    
    function armarBodyCorreoNotifAsig($docenteData, $sede, $curso, $aula) {
        $html = '<p>Docente: <strong>'.$docenteData['nombre_abvr'].'</strong>:</p>
                 <p>Ud. ha sido asignado para ense&ntilde;ar un curso</p>
                 <table border="1" cellpadding="1" cellspacing="1" style="width:500px">
                     <tbody>
                         <tr>
                             <td><strong>Sede</strong></td>
                             <td>'.$sede.'</td>
                         </tr>
                         <tr>
                             <td><strong>Curso</strong></td>
                             <td>'.$curso.'</td>
                         </tr>
                         <tr>
                             <td><strong>Aula</strong></td>
                             <td>'.$aula.'</td>
                         </tr>
                     </tbody>
                 </table>
                 <p>Puede consultar el listado completo entrando a la plataforma <a href="'.RUTA_SMILEDU.'" target="_blank">SmilEDU</a></p>
                 <p>Usa tu usuario: '.$docenteData['usuario'].' y tu respectiva clave. </p>
                 <p>Si no tienes tu usuario/clave obtenlo en el link de "�Olvidaste tu contrase�a?" en la pantalla de inicio. </p>';
        return $html;
    }
    
    function buscarDocenteAsignar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula     = _simpleDecryptInt(_post('idAula'));
            $idCurso    = _simpleDecryptInt(_post('idCurso'));
            $buscar     = _post('buscar');
            
            if($buscar == null) {
                throw new Exception(ANP);
            }
            
            if(strlen($buscar) < 3) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaDocAsig'] = $this->buildHTML_Docentes_ToAsignar($buscar, $idAula, $idCurso);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function deleteDocenteAsignado() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idPersonaDocente = _simpleDecryptInt(_post('idPersonaDocente'));
            $idAula           = _simpleDecryptInt(_post('idAula'));
            $idCurso          = _simpleDecryptInt(_post('idCurso'));
            $idGrado          = _decodeCI(_post('idGrado'));
            $idAnio           = _decodeCI(_post('idAnio')); 
            $idMain           = _simpleDecryptInt(_post('idMain'));
            $optionRadio      = _post('radiOption'); 
            $fechaActual 	  = date("Y-m-d H:i:s");
            if($idAula == null || $idCurso == null || $idGrado == null || $idAnio == null || $idMain == null || $optionRadio == null || $fechaActual == null) { 
                throw new Exception(ANP);
            }
            if($optionRadio != FLG_DOCENTE_ASIGNADO && $optionRadio != FLG_DOCENTE_DESACTIVADO && $optionRadio != FLG_DOCENTE_DESASIGNADO ) {
                throw new Exception(ANP);
            }
            $data = $this->m_docente->desasDesactDocenteAsignado($idPersonaDocente, $idAula, $idCurso, $fechaActual, $optionRadio);
            $data['tablaCurs_Aulas'] = $this->tablaselectAulas($idGrado, $idAnio);
            $data['tablaCursos']     = $this->buildHTML_CursosByAulaGrado($idGrado, $idAnio, $idAula);
            //$countIdMain = $this->m_docente->comparacionIdMain($idMain);
            // if($countIdMain <= 1) {
             //   throw new Exception(ANP.'1');
            //}
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function buscarTutorAsignar() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $buscarTutor = _post('buscarTutor');
            $idAula      = _simpleDecryptInt(_post('idAula'));
            if($buscarTutor == null) {
                throw new Exception(ANP);
            }
            if(strlen($buscarTutor) < 3) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaTutorModalAsig'] = $this->buildHTML_Tutor_ToAsignar($buscarTutor, $idAula);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildHTML_Tutor_ToAsignar($buscarTutor, $idAula) {
        $arrayTutor = $this->m_docente->getTutorParaAsignar($buscarTutor, $idAula);
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination = "true" id="tbTutorAsig" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'     , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Acci&oacute;n', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        foreach ($arrayTutor as $row) {
            $countTutorAsig = $this->m_docente->countTutorAsignado($row['nid_persona'], $idAula);
            $imageStudent = '<img alt="Student" src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">';
                if($row['desc_aula'] == null || $row['desc_aula'] == '') {
                    $nombreAula = '<p class="classroom-value" style="display: inline">'.$row['nombre_completo'].' '.$row['desc_aula'].'</p>';
                } else {
                    $nombreAula = '<p class="classroom-value" style="display: inline">'.$row['nombre_completo'].' ('.$row['desc_aula'].')</p>';                   
                }
 	        $row_0 = $i;
            $row_1 = array('data' => $imageStudent.$nombreAula, 'class' => 'text-left');
            $radio = '<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="radio_'.$i.'">
				          <input type="radio" class="mdl-radio__button recto" name="radioVals" data-count_tutor="'.$countTutorAsig.'" data-id_tutor="'._simple_encrypt($row['nid_persona']).'"  id="radio_'.$i.'" onclick="radioTutor($(this))"><span></span>
			          </label>';
            $row_2 = array('data' => $radio, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getImagesTuCoModal() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula = _simpleDecryptInt(_post('idAula'));

            if($idAula == null) {
                throw new Exception(ANP);
            }
            $data['error']        = EXIT_SUCCESS;
            $data['imgTutor']   = $this->imagesTutores($idAula);
            $data['imgCotutor'] = $this->imagesCotutores($idAula);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));     
    }    
    
    function imagesCotutores($idAula) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbTutorAsig" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'    , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Desasignar', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        $arrayCotutor = $this->m_docente->getCotutores($idAula);
        $cotutorImg = null; 
        foreach ($arrayCotutor as $row) {               
            $cotutorImg = ' <span class="mdl-list__item-primary-content">
                                <img class="mdl-list__item-avatar" src="'.$row['foto_persona'].'" data-id_cotutor="'._simple_encrypt($row['nid_persona']).'" >
                                <span id="nombreCotutor">'.$row['nombre_corto'].'</span>  
                            </span>';
            $delete    = '<button class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" data-id_cotutor_delete= "'._simple_encrypt($row['nid_persona']).'" onclick="modalDeleteCotutor($(this))"><i class="mdi mdi-delete"></i></button>';  
            
 	        $row_0 = $i;
            $row_1 = array('data' => $cotutorImg, 'class' => 'text-left');
            $row_2 = array('data' => $delete, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function imagesTutores($idAula) {
        $tmpl= array('table_open' => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbTutores" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'     , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre', 'class' => 'text-left');
        $head_2 = array('data' => 'Desasignar', 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        $arrayTutor = $this->m_docente->getTutores($idAula);
        $tutorImg = null;
        foreach ($arrayTutor as $row) {
            $tutorImg = '
                              <span class="mdl-list__item-primary-content">
                                  <img class="mdl-list__item-avatar" src="'.$row['foto_persona'].'">
                                  <span id="nombreCotutor">'.$row['nombre_corto'].'</span>
                              </span>';
            $delete  = '<button class="mdl-list__item-secondary-action mdl-button mdl-js-button mdl-button--icon" data-id_tutor_delete= "'._simple_encrypt($row['nid_persona']).'" onclick="modalDeleteTutor($(this))"><i class="mdi mdi-delete"></i></button>';
            
            $actions = '<button id="opc_asigTutor-'.$i.'" class="mdl-button mdl-js-button mdl-button--icon" >
                            <i class="mdi mdi-more_vert"></i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_asigTutor-'.$i.'">
                            <li class="mdl-menu__item"  data-id_tutor_asignado = "'._encodeCI($row['nid_persona']).'" onclick="agregarTuCo(1, $(this));"><i class="mdi mdi-assignment_ind"></i>Reasignar</li>
                            <li class="mdl-menu__item"  data-id_tutor_delete   = "'._simple_encrypt($row['nid_persona']).'" onclick="modalDeleteTutor($(this))"><i class="mdi mdi-delete"></i>Desasignar</li>
                        </ul>';  
 
         	$row_0 = $i;
            $row_1 = array('data' => $tutorImg, 'class' => 'text-left');
            $row_2 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function asigReasigTutorCoTutor() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idTutorCotutor = _simple_decrypt(_post('idTutorCotutor'));
            $idAula         = _simple_decrypt(_post('idAula'));
            $idTutorRolPer  = _decodeCI(_post('idTutorRolPer'));
            $idGrado        = _decodeCI(_post('idGrado'));
            $idAnio         = _decodeCI(_post('idAnio'));
            $option         = _post('option');
            if($idTutorCotutor == null || $idAula == null || $idGrado == null || $idAnio == null || $option == null) {
                throw new Exception(ANP);
            }
            
            if($option == ASIGNAR_TUTOR) {
                $arrayIdTutorCotutor = array(
                    "id_tutor" => $idTutorCotutor
                );
                $data = $this->m_docente->asignarTutor($idTutorCotutor, $idAula, $arrayIdTutorCotutor, $idTutorRolPer);
            } else if($option == ASIGNAR_COTUTOR) {
                //REGLA DE NECOGIO SI NO TIENE TUTOR, NO SE PUEDEN ASIGNAR COTUTORES
                $tutor = $this->m_docente->selectTutorAula($idAula);
                if($tutor == null) {
                    throw new Exception('Seleccion ar un tutor antes de un cotutor');
                }
                $cantCoTutores = $this->m_docente->getCantidadCoTutores($idAula);
                $checkExistCoTutor = $this->m_docente->checkIfCotutorExiste($idAula, $idTutorCotutor);
                if($checkExistCoTutor != null) {
                    throw new Exception('El/La Cotutor(a) ya ha sido asignado(a)');
                }
                $data  = $this->m_docente->asignarCoTutor($idTutorCotutor, $idAula, ($cantCoTutores + 1));
            }
            if($data['error'] == EXIT_SUCCESS) {
                $data['tablaCurs_Aulas'] = $this->tablaselectAulas($idGrado, $idAnio);
                $data['imgTutor']        = $this->imagesTutores($idAula);
                $data['imgCotutor']      = $this->imagesCotutores($idAula);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function deleteTutCot() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idTutor    = _simple_decrypt(_post('idTutor'));
            $idAula     = _simple_decrypt(_post('idAula'));
            $idCotutor  = _simple_decrypt(_post('idCotutor'));
            $option     = _post('optionDeleteTutCot');
            
            if($option != 1 && $option != 2) {
                throw new Exception(ANP);
            }
            if($idAula == null) {
                throw new Exception(ANP);
            }
            if($option == 1) {
                if($idTutor == null) {
                    throw new Exception(ANP);
                }
                //Check si hay cotutores, si lo hay NO dejar deasignar
                $cantCoTutores = $this->m_docente->getCantidadCoTutores($idAula);
                if($cantCoTutores == null) {
                    throw new Exception(ANP);
                }
                if($cantCoTutores > 0) {
                    throw new Exception('No puede desasignar al tutor, a&uacute;n hay Cotutores asignados');
                }
                $data = $this->m_docente->desasignarTutorFromAula($idAula, $idTutor);
            } else if($option == 2) {
                if($idCotutor == null) {
                    throw new Exception(ANP);
                }
                $data  = $this->m_docente->desasignarCoTutorFromAula($idAula, $idCotutor);
            }
            if($data['error'] == EXIT_SUCCESS) {
                $data['imgTutor']   = $this->imagesTutores($idAula);
                $data['imgCotutor'] = $this->imagesCotutores($idAula);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
    function logout() {
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }

    function cambioRol()
    {
        $idRol = _simple_decrypt(_post('id_rol'));
        $nombreRol = $this->m_utils->getById("rol", "desc_rol", "nid_rol", $idRol, 'schoowl');
        $dataUser = array(
            "id_rol" => $idRol,
            "nombre_rol" => $nombreRol
        );
        $this->session->set_userdata($dataUser);
        $result['url'] = base_url() . "c_main/";
        echo json_encode(array_map('utf8_encode', $result));
    }

    function getRolesByUsuario() {
        $idPersona = _getSesion('id_persona');
        $idRol     = _getSesion('id_rol');
        $roles     = $this->m_usuario->getRolesByUsuario($idPersona, $idRol);
        $return = null;
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
        
    function registrarGrupo() { 
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idArea        = _decodeCI(_post('idArea'));
            $idCursoTaller = _decodeCI(_post('idCursoTaller'));
            $idAula        = _decodeCI(_post('idAula'));
            $idDocente     = _simple_decrypt(_post('idDocente'));
            $descGrupo     = _post('descGrupo');
            $capacidad     = _post('capacidad');

            if($capacidad == '' || $descGrupo == '' || $idArea == '' || $idDocente == '') {
                throw new Exception(ANP);
            }
            
            $arrayGradoGrupo = _post('arrayGradoGrupo');
            if(!is_array($arrayGradoGrupo) ) {
                throw new Exception('Seleccione grados para el curso');
            }
            if(ID_AREA_TALLER_ARTISTICO == $idArea || ID_AREA_TALLER_DEPORTIVO == $idArea) {  
                if($idAula == '' || $idCursoTaller == null)  {
                    throw new Exception(ANP);
                }
                $data = $this->m_docente->registrarGrupoTaller($idCursoTaller, $descGrupo, $capacidad, $idDocente, $arrayGradoGrupo, $idAula);
            } else {
                if($idCursoTaller == '') {
                    throw new Exception(ANP);
                }
                $data = $this->m_docente->registrarGrupoCursos($idCursoTaller, $descGrupo, $capacidad, $idDocente, $arrayGradoGrupo, $idAula);      
            }
                        
            if(count($arrayGradoGrupo) == 0) {
                throw new Exception('Seleccione grados para el curso');
            }

            $data['tablaGruposConsult'] = $this->getTableConsultarGrupos();
        } catch(Exception $e) { 
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol     = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarFeedBack() {
        $nombre = _getSesion('nombre_completo');
        $mensaje = _post('feedbackMsj');
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje, $url, $nombre);
    }

    function mostrarRolesSistema() {
        $idSistema = _decodeCI(_post('sistema'));
        $roles = $this->m_usuario->getRolesOnlySistem(_getSesion('id_persona'), $idSistema);
        $result = '<ul>';
        foreach ($roles as $rol) {
            $idRol = _encodeCI($rol->nid_rol);
            $result .= '<li style="cursor:pointer" onclick="goToSistema(\'' . _post('sistema') . '\', \'' . $idRol . '\')">' . $rol->desc_rol . '</li>';
        }
        $result .= '</ul>';
        $data['roles'] = $result;
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function fechaActual() {
    	$zonahoraria = date_default_timezone_get();
    	ini_set('date.timezone','America/Lima');
    	$hoy = date("Y-m-d H:i:s");
    	return $hoy;
    }

    function getContenidoModalGrupo() {
        $idArea = _decodeCI(_post('idArea'));

        if(ID_AREA_TALLER_ARTISTICO == $idArea || ID_AREA_TALLER_DEPORTIVO == $idArea) {
            $comboTaller      = __buildComboTalleres($idArea);
            $comboAulaExterna = null;
            $htmlComboCuTa   = $this->combosHTML('cmbCursoTaller', 'Selec. Taller', 'getFormularioGrupo();', $comboTaller, 'select');
            $tipo = 1;
        } else {
            $comboCursos   = __buildComboCursosUgelEquiv($idArea);
            $htmlComboCuTa = $this->combosHTML('cmbCursoTaller', 'Selec. Curso', 'getFormularioGrupo();', $comboCursos, 'select');
            $tipo = 0;
        }
        $data['tipo']            = $tipo;
        $data['htmlTallerCurso'] = $htmlComboCuTa;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getFormularioGrupo() {
        $idCursoTaller = _decodeCI(_post('idCursoTaller'));
        $comboAulas = __buildComboSoloAulas($idCursoTaller);
        $data['htmlComboAulas']  = $this->combosHTML('cmbAula'  , 'Selec. Aula' , '', $comboAulas, 'select');
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function combosHTML($idName, $title, $onChange, $contCombo, $class) {
        $htmlCombo = '<select id="'.$idName.'" name="'.$idName.'" class="form-control pickerButn '.$class.'" data-live-search="true" title="'.$title.'" onchange="'.$onChange.'">
    		              <option value="">'.$title.'</option>'
                          .$contCombo.
                     '</select>';
        return $htmlCombo; 
    }
    
    function iconsGrados() {
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbGradosGrupos" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" data-page-size  = "5">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'          , 'class' => 'text-left');
        $head_1 = array('data' => 'Grado'      , 'class' => 'text-left');
        $head_2 = array('data' => 'Seleccionar', 'class' => 'text-center', 'data-field' => 'checkbox');
        $this->table->set_heading($head_0, $head_1, $head_2);
        $i = 1;
        $val = 0;
        $grados = $this->m_utils->getGradosNivel_All(); 
        foreach ($grados as $grad) {
            $val++;
            $checkbox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_docente" for="chk_'.$val.'" >
                                 <input type="checkbox" id="chk_'.$val.'" class="mdl-checkbox__input" onclick="handleCheckGradoGrupo($(this))"
                                        data-id_grado_grupo="'._encodeCI($grad->nid_grado).'" >
                         </label>';
        
            $row_0 = $i;
            $row_1 = array('data' => $grad->grado_nivel, 'class' => 'text-left');
            $row_2 = array('data' => $checkbox         , 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2);
            $i++;
        }
        $table = $this->table->generate();
        $data['tableGrados'] = $table;
        echo json_encode(array_map('utf8_encode', $data));    
    }
    
    function getTableConsultarGrupos() {        
        $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
		                                     data-pagination="true" id="tbConsultarGrupos" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                     'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#'                , 'class' => 'text-left');
        $head_1 = array('data' => 'Nombre del Grupo' , 'class' => 'text-left');
        $head_2 = array('data' => 'Taller'           , 'class' => 'text-center');
        $head_3 = array('data' => 'Docente'          , 'class' => 'text-center');
        $head_4 = array('data' => 'Aula'             , 'class' => 'text-center');
        $head_5 = array('data' => 'Capacidad'        , 'class' => 'text-center');
        $head_6 = array('data' => 'Grados'           , 'class' => 'text-center');
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $i = 1;
        $grupos = $this->m_docente->getGrupos(null, null);
        foreach ($grupos as $row) {
 	        $row_0 = $i;
            $row_1 = array('data' => $row['nombre_grupo']  , 'class' => 'text-left');
            $row_2 = array('data' => $row['desc_taller']   , 'class' => 'text-center');
            $row_3 = array('data' => $row['nom_persona']   , 'class' => 'text-center');
            $row_4 = array('data' => $row['desc_aula_ext'] , 'class' => 'text-center');
            $row_5 = array('data' => $row['limite_alumno'] , 'class' => 'text-center');
            $row_6 = array('data' => $row['grados']        , 'class' => 'text-center');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
            $i++;
        }
        $table = $this->table->generate();
        return $table;
    }
    
    function getTableConsultarGrup() {
        $data['tableGrupos'] = $this->getTableConsultarGrupos();
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTableAsigGrup() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    		                                     data-pagination="true" id="tbGruposAsig" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                         'table_close' => '</table>');
            $this->table->set_template($tmpl);
            $head_0 = array('data' => '#'                , 'class' => 'text-left');
            $head_1 = array('data' => 'Nombre del Grupo' , 'class' => 'text-left');
            $head_2 = array('data' => 'Taller'           , 'class' => 'text-center');
           //$head_3 = array('data' => 'Seleccionar', 'class' => 'text-center', 'data-field' => 'checkbox');
            $this->table->set_heading($head_0, $head_1, $head_2);
            $i = 1;
            $val = 0;
            $grupos = $this->m_docente->getGrupos($idGrado, null);
            foreach ($grupos as $row) {
                $val++;
                $checkbox = '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect check_docente" for="chk_'.$val.'" >
                                     <input type="checkbox" id="chk_'.$val.'" class="mdl-checkbox__input" onclick="handleCheckGradoGrupo($(this))"
                                            data-id_grado_grupo="'._encodeCI($row['nid_main']).'" >
                             </label>';
        
                $row_0 = $i;
                $row_1 = array('data' => $row['nombre_grupo'], 'class' => 'text-left');
                $row_2 = array('data' => $row['desc_taller'], 'class' => 'text-center');
                //$row_3 = array('data' => $checkbox, 'class' => 'text-center');
                $this->table->add_row($row_0, $row_1, $row_2);
                $i++;
            }
            $table = $this->table->generate();
            $data['error'] = EXIT_SUCCESS;
            $data['tableAsigGrupos'] = $table;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGrupo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idGrado = _decodeCI(_post('idGrado'));
            $idCurso = _simple_decrypt(_post('idCurso'));
            $year    = _decodeCI(_post('year'));
            if($idGrado == null || $idCurso == null || $year == null) {
                throw new Exception(ANP);
            }
            $tmpl= array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
    		                                     data-pagination="true" id="tbGrupo" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]">',
                         'table_close' => '</table>');
            $this->table->set_template($tmpl);
            $head_0 = array('data' => '#'                , 'class' => 'text-left');
            $head_1 = array('data' => 'Nombre del Grupo' , 'class' => 'text-left');
            $head_2 = array('data' => 'Taller'           , 'class' => 'text-center');
            //$head_3 = array('data' => 'Seleccionar', 'class' => 'text-center', 'data-field' => 'checkbox');
            $this->table->set_heading($head_0, $head_1, $head_2);
            $i = 1;
            $val = 0;
            $grupos = $this->m_docente->getGruposCursosAulas($idCurso, $idGrado, $year, null);
            if($grupos != null) {
                foreach ($grupos as $row) {
                    $val++;
                    $row_0 = $i;
                    $row_1 = array('data' => $row['nombre_grupo'], 'class' => 'text-left');
                    //$row_2 = array('data' => $row['desc_taller'], 'class' => 'text-left');
                    $this->table->add_row($row_0, $row_1);
                    $i++;
                }
            }

            $table = $this->table->generate();
            $data['error'] = EXIT_SUCCESS;
            $data['tableGruposByCursos'] = $table;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}