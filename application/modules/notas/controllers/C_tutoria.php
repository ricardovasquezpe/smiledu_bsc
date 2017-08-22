<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_tutoria extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_tutoria');
        $this->load->model('../m_utils');
        $this->load->library('table');
        
        _validate_uso_controladorModulos(ID_SISTEMA_NOTAS, ID_PERMISO_TUTORIA, NOTAS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(NOTAS_ROL_SESS);
    }

    public function index() {
        $data['titleHeader'] = 'Tutor&iacute;a';
        $data['tablaAulas'] = $this->tablaselectAulas();               
        $data['arbolPermisosMantenimiento'] = __buildArbolPermisos($this->_idRol, ID_SISTEMA_NOTAS, NOTAS_FOLDER);
        $data['ruta_logo']        = MENU_LOGO_NOTAS;
        $data['ruta_logo_blanco'] = MENU_LOGO_BLANCO_NOTAS;
        $data['nombre_logo']      = NAME_MODULO_NOTAS;

        $rolSistemas  = $this->m_utils->getSistemasByRol(ID_SISTEMA_NOTAS, $this->_idUserSess);
        $data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        $data['menu'] = $this->load->view('v_menu', $data, true);
        $this->load->view('v_tutoria', $data);
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
    
    function tablaselectAulas() {
        $capacidad = $this->m_tutoria->getCapacidad(_getSesion('nid_persona'));

        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="false" id="tbAulas" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Aulas'    , 'class' => 'text-left');       
        $head_1 = array('data' => 'Capacidad', 'class' => 'text-center');
        $head_2 = array('data' => 'Acciones' , 'class' => 'text-center');     
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 0;
        $fotoTut = null;
        foreach($capacidad as $row) {
            $val++;
            $actions = '<button id="opc_aula-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                            <i class="mdi mdi-more_vert"></i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_aula-'.$val.'">
                            <li class="mdl-menu__item"  onclick="getAlumnos($(this))"><i class="mdi mdi-school"></i> Alumnos</li>
                            <li class="mdl-menu__item"  onclick="getDocentes($(this))"><i class="mdi mdi-business_center"></i> Docentes</li>
                            <li class="mdl-menu__item"  onclick="getPadresFamilia($(this))"><i class="mdi mdi-wc"></i> Padres de Familia</li>
                        </ul>';
            
                $row_1 = array('data' => $row['cant_estu'].'/'.$row['capa_max'], 'class' => 'text-center btnIdMain','data-year' => $row['year']);

            $row_0 = array('data' => _ucwords($row['desc_aula']).' '.$row['sede'], 'class' => 'text-left btnAulaID', 'data-id_aula' => _simple_encrypt($row['nid_aula']));         
            $row_2 = array('data' => $actions, 'class' => 'text-center');
            $this->table->add_row($row_0,$row_1,$row_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getAlumnos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = MSJ_ERROR;
        try {
            $idAula = _simple_decrypt(_post('idAula'));
            $idAnio = _post('year');
            if($idAula == null || $idAnio == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tabla_Alumnos'] = $this->tablaselectAlumnos($idAula, $idAnio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaselectAlumnos($idAula, $idAnio) {
        $arrayAlumno = $this->m_tutoria->getEstudiantes($idAula, $idAnio, _getSesion('nid_persona'));
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="true" id="tbAlumnos" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Nombre'        , 'class' => 'text-left');
        $head_1 = array('data' => 'I Bimestre'    , 'class' => 'text-right');
        $head_2 = array('data' => 'II Bimestre'   , 'class' => 'text-right');
        $head_3 = array('data' => 'III Bimestre'  , 'class' => 'text-right');
        $head_4 = array('data' => 'IV Bimestre'   , 'class' => 'text-right');
        $head_5 = array('data' => 'Promedio Final', 'class' => 'text-right');
        $head_6 = array('data' => 'Acciones'      , 'class' => 'text-center');
    
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
        $val = 0;
        $cont = 0;
        $indiNotaTbEstudi = 1;
        foreach($arrayAlumno as $row) {
        	$promedioBimestre = $this->m_tutoria->getPromByEstudiante($row['nid_persona'], $idAula, $idAnio, $indiNotaTbEstudi, null, null);
        	$notas = array($promedioBimestre[0]['promedio'], $promedioBimestre[1]['promedio'], $promedioBimestre[2]['promedio'], $promedioBimestre[3]['promedio']);
            $promedioF   = $this->promedioFinal($notas);

            $fotoAlum    = '<img src="'.$row['foto_persona'].'" width=30 height=30 class="img-circle m-r-10">
	                        <p class="classroom-value" style="display: inline">';
            $bimestreI   ='<label class="link-dotted" onclick = "getModalBimestre($(this),'.$promedioBimestre[0]['id_ciclo'].')" style="cursor: pointer">'.$promedioBimestre[0]['promedio'].'</label>';
            $bimestreII  ='<label class="link-dotted" onclick = "getModalBimestre($(this),'.$promedioBimestre[1]['id_ciclo'].')" style="cursor: pointer">'.$promedioBimestre[1]['promedio'].'</label>';
            $bimestreIII ='<label class="link-dotted" onclick = "getModalBimestre($(this),'.$promedioBimestre[2]['id_ciclo'].')" style="cursor: pointer">'.$promedioBimestre[2]['promedio'].'</label>';
            $bimestreIV  ='<label class="link-dotted" onclick = "getModalBimestre($(this),'.$promedioBimestre[3]['id_ciclo'].')" style="cursor: pointer">'.$promedioBimestre[3]['promedio'].'</label>';
            $promedioFinal = $this->colorPromedioFinal($promedioF);

            $val++;          
            $row_0 = array('data' => $fotoAlum.$row['nombre_corto'], 'class' => 'text-left btnAlumnoID', 'data-id_alumno' => _simple_encrypt($row['nid_persona']));
            $row_1 = array('data' => $bimestreI     , 'class' => 'text-right');
            $row_2 = array('data' => $bimestreII    , 'class' => 'text-right');
            $row_3 = array('data' => $bimestreIII   , 'class' => 'text-right');
            $row_4 = array('data' => $bimestreIV    , 'class' => 'text-right');
            $row_5 = array('data' => $promedioFinal , 'class' => 'text-right');
    
            $actions = '<button id="opc_alumno-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                            <i class="mdi mdi-more_vert"></i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_alumno-'.$val.'">
                            <li class="mdl-menu__item"  onclick="getModalLibreta($(this))"><i class="mdi mdi-library_books"></i>Ver Libreta</li>                       
                            <li class="mdl-menu__item"  onclick="ver_graficosAlumno($(this))"><i class="mdi mdi-library_books"></i>Ver Graficos</li>                        
                        </ul>';
    
            $row_6 = array('data' => $actions,'class' => 'text-center');    
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getModalTableBimestre() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula   = _simple_decrypt(_post('idAula'));
            $Anio     = _post('year');
            $opcBim   = _post('opcBimestre');
            $idAlumno = _simple_decrypt(_post('idAlumno'));
            if($idAula == null || $Anio == null || $opcBim == null || $idAlumno == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tabla_CursosBimestrales'] = $this->tablaNotasBimestre($idAula, $Anio, $opcBim, $idAlumno);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaNotasBimestre($idAula, $year, $opcBim, $idAlumno) {   
        $arrayCursos = $this->m_tutoria->getCursos($year, $idAula);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="10"
			                                   data-search="false" id="tbCursos" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Curso', 'class' => 'text-left');
        $head_1 = array('data' => 'Promedio', 'class' => 'text-right');
        $head_2 = array('data' => 'Opciones', 'class' => 'text-right');
    
        $this->table->set_heading($head_0, $head_1, $head_2);
        $val = 0;
        $fotoTut = null;
        foreach($arrayCursos as $row) {
            $val++;
            $promedio = $this->m_tutoria->getPromByEstudiante($idAlumno, $idAula, $year, null, $row['id_curso'], $opcBim);
            if($promedio['promedio'] == null) {
                $promedio['promedio'] = '-';
            } 
            $row_1 = $promedio['promedio'];
            $actions = '<button id="opc_docente-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                            <i class="mdi mdi-more_vert"></i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_docente-'.$val.'">
                            <li class="mdl-menu__item" data-id_grado="'._simple_encrypt($row['id_grado']).'" data-id_curso ="'._simple_encrypt($row['id_curso']).'" onclick="getDetalleNotas($(this))"><i class="mdi mdi-library_books"></i>Detalle de Notas</li>
                        </ul>';
            $row_0 = array('data' => $row['desc_curso'], 'class' => 'text-left');           
            $row_2 = array('data' => $actions, 'class' => 'text-right');
            $this->table->add_row($row_0, $row_1, $row_2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getDocentes() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $Anio   = _post('year');
            $idAula = _simple_decrypt(_post('idAula'));
            if($idAula == null || $Anio == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tabla_Docentes'] = $this->selectTablaDocentes($idAula, $Anio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function selectTablaDocentes($idAula, $Anio) {
        $idGrado = $this->selectGradodeAula($idAula);
        $arrayDocentes = $this->m_tutoria->getDocentesxCurso($Anio, $idGrado, $idAula);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true"
			                                   data-search="true" id="tbDocentes" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Nombre'     , 'class' => 'text-left');       
        $head_1 = array('data' => 'Curso'      , 'class' => 'text-center');
        $head_2 = array('data' => 'Telefono(s)', 'class' => 'text-center');
        $head_3 = array('data' => 'Correo'     , 'class' => 'text-center');     
        $head_4 = array('data' => 'Opciones'   , 'class' => 'text-center');
               
        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4);
        $val = 0;
        $fotoTut = null;
        foreach($arrayDocentes as $row) {
            $val++;
            $actions = '<button id="opc_docente-'.$val.'" class="mdl-button mdl-js-button mdl-button--icon" >
                            <i class="mdi mdi-more_vert"></i>
                        </button>
                        <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="opc_docente-'.$val.'">
                            <li class="mdl-menu__item" data-correo_doc= "'.$row['correo_pers'].'" onclick="getCorreo($(this))"><i class="mdi mdi-email"></i>Enviar Correo</li>
                        </ul>';            
            $row_0 = array('data' => $row['nom_persona'] , 'class' => 'text-left');
            $row_1 = array('data' => $row['desc_curso']  , 'class' => 'text-right');
            $row_2 = array('data' => $row['telf_pers']   , 'class' => 'text-right');
            $row_3 = array('data' => $row['correo_pers'] , 'class' => 'text-right');
            $row_4 = array('data' => $actions, 'class' => 'text-right');
            $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function getDetalleNotas() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAnio   = _post('year');
            $idGrado  = _simple_decrypt(_post('idGrado'));
            $idAlumno = _simple_decrypt(_post('idAlumno'));
            $idCurso  = _simple_decrypt(_post('idCurso'));
            $idAula   = _simple_decrypt(_post('idAula'));
            $opcBim   = _post('opcBim');
            if($idAnio == null || $idGrado == null || $idAlumno == null || $idCurso == null || $idAula == null || $opcBim == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tabla_DetalleNotas'] = $this->selectTablaDetalleNotas($idAlumno, $idCurso, $idGrado, $idAnio, $opcBim, $idAula);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function selectTablaDetalleNotas($idAlumno, $idCurso, $idGrado, $idAnio, $opcBim, $idAula) {
        $arrayDocentes = $this->m_tutoria->getDetalleNotas($idAlumno, $idCurso, $idGrado, $idAnio, $opcBim, $idAula);
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                                               data-page-size="10"
			                                   data-search="false" id="tbDetalleNotas" data-show-columns="false">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Concepto a Evaluar' , 'class' => 'text-left');
        $head_1 = array('data' => 'Nota'               , 'class' => 'text-center');   
            
        $this->table->set_heading($head_0, $head_1);
        
        if($arrayDocentes != null) {
            foreach($arrayDocentes as $row) {
                $row_0 = array('data' => $row['concepto_evaluar'] , 'class' => 'text-left');
                $row_1 = array('data' => $row['nota_numerica']    , 'class' => 'text-left');
                $this->table->add_row($row_0, $row_1);
            } 
        } else if($arrayDocentes == 0) {
            $row_0 = array('data' => 'Ninguno', 'class' => 'text-left');
            $row_1 = array('data' =>    '-'   , 'class' => 'text-left  ');      
            $this->table->add_row($row_0, $row_1);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function selectGradodeAula($idAula) {
        $idGrado = $this->m_tutoria->getIdGrado($idAula);
        return $idGrado;
    }
    
    function colorPromedioFinal($promedio) {
        if($promedio < 10.5) {
            $promedio = '<label style="color : red">'.$promedio.'</label>';
            return $promedio;
        } else {
            $promedio = '<label style="color : blue">'.$promedio.'</label>';
            return $promedio;
        }
    }
                
    function agregarComentario() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $comentario_bim = _post('comentario_bim');
            $idAlumno       = _simple_decrypt(_post('idAlumno'));
            $idAula         = _simple_decrypt(_post('idAula'));
            $Anio           = _post('Anio'); 
            $bimestre       = _post('bimestre');  
            
            if($comentario_bim == '' || $idAlumno == '' || $idAula == '' || $Anio == '' || $bimestre == '') {
                throw new Exception(ANP);
            }

            $bimestres     = $this->bimestresDelAnio();
            $comentarioBim = array('comentario_bim_i', 'comentario_bim_ii', 'comentario_bim_iii', 'comentario_bim_iv'); 
            foreach($bimestres as $bim) {
                $i    = 0;
                $cont = 1;
                if($bim == $bimestre) {
                    $j = $bim - $cont;
                    $comen = array(
                        $comentarioBim[$j] => $comentario_bim,
                    );                   
                } else {}
                $i++;
                $cont++;
            }         
             $data = $this->m_tutoria->agregarComentario($comen, $idAula, $idAlumno, $Anio);
             $data['arraysPromedioBim']    = $this->buildSeriesByPromedioCicloEstudiante($idAlumno, $idAula, $Anio);
             $data['arraysPromedioCursos'] = $this->buildSeriesByPromedioCursosEstudiante($Anio, $this->m_tutoria->getIdGrado($idAula), $idAlumno, $bimestre, $idAula);
             $data['asistencia']           = $this->buildAsistencia($idAlumno, $idAula, $Anio, $bimestre);    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function generar_libreta() {        
        $idAlumno      = _simple_decrypt(_post('idAlumno')); 
        $idAula        = _simple_decrypt(_post('idAula'));
        $img1          = _post('imagenGrafico1');
        $img2          = _post('imagenGrafico2');
        $img3          = _post('imagenGrafico3');
        $idAnio        = _post('idAnio');
        //Primera Imagen
       // $imgOutput = $this->base64_to_jpeg($img1, 'imagen_nota1.jpg');
        $data1 = explode(',', $img1);
        $data2 = explode(',', $img2);
        $data3 = explode(',', $img3);
        if($idAlumno == null || $idAula == null) {
            return;
        }
        ////////////////
        $bimestre = _post('bimestre');
        $this->load->library('m_pdf');
        $i = 0;
        $asistenPdf     = $this->getCountAsistencia($idAlumno, $idAula);
        $notaAsistencia = $this->m_tutoria->notaAsistencia($idAlumno, $idAula);
        $comentario = $this->m_tutoria->getComentario($idAlumno, $idAula, $idAnio, $bimestre);
        //$pdf = new mPDF('en-GB-x','A3-L','','',5,5,5,5,0,0);
        $pdf = $this->m_pdf->load('en-GB-x','A3-L','','',5,5,5,5,0,0);
        $idGrado       = $this->selectGradodeAula($idAula);       
        $abrvGradoAula = $this->m_tutoria->getAbrGradoAula($idGrado, $idAula);      
        $nombrePdf     = $this->m_tutoria->getPdfDatos($idAula, $idAlumno);
        list($htmlTbla, $promMerito) = $this->buildLibretaLlenar($idAula, $idAlumno, $idGrado, $idAnio);
        $logo       = '<img src="'.base_url().'public/img/menu/logo_smiledu.png" title="logo" width="50" height="50">';
        $titulo     = '<h2>Libreta de Notas</h2>';
        $aula_grado = $abrvGradoAula['abvr'].' '.$abrvGradoAula['desc_aula']; 
        $alumno     = $nombrePdf['nombre_completo'];
        $fecha      = $this->fechaIniFin($bimestre);
        $awardsPositivo = $this->m_tutoria->getStudentAwardsByMain($idAula, $idAlumno, $fecha['fec_inicio'], $fecha['fec_fin'], AWARD_POSITIVO);
        $awardsNegativo = $this->m_tutoria->getStudentAwardsByMain($idAula, $idAlumno, $fecha['fec_inicio'], $fecha['fec_fin'], AWARD_NEGATIVO);
        $awardsEstuPosit = $this->buildHTML_StudentAwards($awardsPositivo);
        $awardsEstuNegat = $this->buildHTML_StudentAwards($awardsNegativo);
        //$b1 = ($bimestre == ORDEN_SUBIR) ? $orden - 1 : $orden + 1 ;
        $data['libreta_html']    = $htmlTbla;
        $data['pdfObj']          = $pdf;
        $data['alumno']          = $alumno;
        $data['logo']            = $logo;
        $data['promMerito']      = $promMerito;
        $data['comentario']      = $comentario['comentario'];
        $data['img1']            = 'imagen_nota1.jpg';
        $data['img2']            = 'imagen_nota2.jpg';
        $data['img3']            = 'imagen_nota3.jpg';
        $data['aula_grado']      = $aula_grado;
        $data['awardsEstuPosi']  = $awardsEstuPosit;
        $data['awardsEstuNegat'] = $awardsEstuNegat;
        $data['asistencia']      = $asistenPdf;
        $data['notaAsistencia']  = $notaAsistencia['nota_numerica'];

        $data['data1'] = $data1;
        $data['data2'] = $data2;
        $data['data3'] = $data3;
        
        $this->load->view('v_pdf_libreta', $data);

        $pdf->Output("mi_libreta.pdf", 'D');
    }
    
    function buildSeriesByPromedioCicloEstudiante($idEstudiante, $idAula, $idAnio) {
        $indic = 1;
        $promedio  = $this->m_tutoria->getPromByEstudiante($idEstudiante, $idAula, $idAnio, $indic, null, null);
        $data      = null;
        $arrayCate = array();
        $arrayProm = array();
        foreach($promedio as $prom){
            array_push($arrayCate, $prom['desc_ciclo_acad']);
            array_push($arrayProm, floatval($prom['promedio']));
        }
        $data['arrayCate'] = json_encode($arrayCate);
        $data['arrayProm'] = json_encode($arrayProm);
        return json_encode($data);
    }
    
    function ver_graficosAlumno() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        
        try {
            $idAlumno = _simple_decrypt(_post('idAlumno'));
            $Anio     = _post('Anio');
            $idAula   = _simple_decrypt(_post('idAula'));
            $idAula   = _simple_decrypt(_post('idAula'));
            if($Anio == '' || $idAula == '' || $idAlumno == '') {
                throw new Exception(ANP);
            } 
            $data['error']  = EXIT_SUCCESS;
            $data['arraysPromedioBim'] = $this->buildSeriesByPromedioCicloEstudiante($idAlumno, $idAula, $Anio);            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
             
    function buildLibretaLlenar($idAula, $idAlumno, $idGrado, $idAnio) {
        $arrayPromBim = array();
        $indiNotaTbEstudi = 1;
        $arrayCursosPdf = $this->m_tutoria->getCursosLibreta($idGrado, $idAnio);
        $tmpl     = array('table_open'  => '<table>',
                          'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $left = 'text-align:left';$right = 'text-align:right';$center = 'text-align:center';
       
        foreach($arrayCursosPdf as $row) {
            $promedioCurso = $this->serlectPromedioxBimestreLibreta($idAlumno, $idAnio, $idAula, $row['id_curso'], $idGrado);
            $notas       = array($promedioCurso[0]['promedio'], $promedioCurso[1]['promedio'], $promedioCurso[2]['promedio'], $promedioCurso[3]['promedio']);
            $promedioF1  = $this->promedioFinal($notas);
            array_push($arrayPromBim, $promedioF1);
            $img_prom    = $this->getFlechaArribaAbajo($promedioF1);
            $row_colprom = array('data' => '<FONT>'.$promedioF1.'&nbsp;'.$img_prom.'</FONT>', 'style'=>'width:10%;text-align:right;padding-right:10px;');
            $cursosEquiv = explode(',', $row['string_agg']);
            $competencias = explode('|', $row['competencia_curso']);
            
            $row_col10  = array('data' => '<FONT>'.$row['desc_curso'] , 'style'=>'width:20%; border-left-color: white;text-align:left;padding-left:10px;');

            $row_colI   = array('data' => '<FONT>'.$promedioCurso[0]['promedio'].'</FONT>' , 'style'=>'width:10%; border-center-color: white;text-align:center;');
            $row_colII  = array('data' => '<FONT>'.$promedioCurso[1]['promedio'].'</FONT>' , 'style'=>'width:10%; border-center-color: white;text-align:center;');
            $row_colIII = array('data' => '<FONT>'.$promedioCurso[2]['promedio'].'</FONT>' , 'style'=>'width:10%; border-center-color: white;text-align:center;');
            $row_colIV  = array('data' => '<FONT>'.$promedioCurso[3]['promedio'].'</FONT>' , 'style'=>'width:10%; border-center-color: white;text-align:center;');
                       
            $this->table->add_row($row_col10, $row_colI, $row_colII,$row_colIII, $row_colIV, $row_colprom);
            foreach($cursosEquiv as $cursoEquiv) {          
                $cursoEquiv    = explode('|', $cursoEquiv);      
                $j = 0;
                if($cursoEquiv[0] != '') {
                    $row_col10   = array('data' => '<FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;'.strtolower($cursoEquiv[0]).'</FONT>' , 'style'=>'width:10%; border-left-color: white;text-align:left;padding-left:10px;');
                    $this->table->add_row($row_col10, $row_colI, $row_colII,$row_colIII, $row_colIV, '');
                } 
                foreach($competencias as $comp) {
                    if($comp != '') {
                        $row_col12   = array('data' => '<FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.strtolower($comp).'</FONT>', 'style'=>'width:20%;text-align:left;padding-left:10px;', 'style' => 'width:50%;text-align:left;padding-left:10px;', 'rowspan' => 1);
                                              
                        $this->table->add_row($row_col12 ,'', '','', '', '');      
                    }
                }
            }     
        }
        
        $promedioFinal = array_sum($arrayPromBim)/count($arrayPromBim);
        $promedioBimestre = $this->serlectPromedioxBimestreLibreta($idAlumno, $idAnio, $idAula, null, $idGrado);
        
        $row_promedio_bim    = array('data' => '<FONT>P R O M E D I O</FONT>'    , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $row_promedio_bimI   = array('data' => $promedioBimestre[0]['promedio']  , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $row_promedio_bimII  = array('data' => $promedioBimestre[1]['promedio']  , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $row_promedio_bimIII = array('data' => $promedioBimestre[2]['promedio']  , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $row_promedio_bimVI  = array('data' => $promedioBimestre[3]['promedio']  , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $row_promedio_FINAL  = array('data' => round($promedioFinal, 0) , 'class' => 'text-left painRow', 'style' =>'border-bottom: 1px solid #ccc;color:white;'.$center);
        $this->table->add_row($row_promedio_bim, $row_promedio_bimI, $row_promedio_bimII, $row_promedio_bimIII, $row_promedio_bimVI, $row_promedio_FINAL);
        $promOrdenMerito = $this->promediOrdenMerito($promedioF1, '');      
               
        $head_1 = array('data' => '<FONT>Asignaturas</FONT>' ,'style' =>'border-bottom: 1px solid #ccc;padding-left:10px;color:#757575;'.$left);
        $head_2 = array('data' => '<FONT>I</FONT><br>'       ,'style' =>'border-bottom: 1px solid #ccc;color:#757575;'.$center);
        $head_3 = array('data' => '<FONT>II</FONT><br>'      ,'style' =>'border-bottom: 1px solid #ccc;color:#757575;'.$center);
        $head_4 = array('data' => '<FONT>III</FONT><br>'     ,'style' =>'border-bottom: 1px solid #ccc;color:#757575;'.$center);
        $head_5 = array('data' => '<FONT>IV</FONT><br>'      ,'style' =>'border-bottom: 1px solid #ccc;color:#757575;'.$center);
        $head_6 = array('data' => '<FONT>P. por curso</FONT>'         ,'style' =>'border-bottom: 1px solid #ccc;color:#757575;padding-right:10px;'.$right);        
   
       $this->table->set_heading( $head_1, $head_2, $head_3, $head_4, $head_5, $head_6);
                
        $tabla = $this->table->generate();
        return array($tabla, $promOrdenMerito);
    }
    
    function getFlechaArribaAbajo($promMerito) {
        if($promMerito > 11.5) {
            $img_prom ='<img src="'.RUTA_PUBLIC_NOTAS.'img/flecha_arriba.png" style="height: 12px;width:9px;">';
            return $img_prom;
        } else {
            $img_prom ='<img src="'.RUTA_PUBLIC_NOTAS.'img/flecha_abajo.png" style="height: 12px;width:9px;">';
            return $img_prom;
        }
    }
            
    function serlectPromedioxBimestreLibreta($idAlumno, $idAnio, $idAula, $idCurso, $idGrado) {
        $ind = 1;
        $promedioxBimestre = $this->m_tutoria->getPromByEstudiante($idAlumno, $idAula, $idAnio, $ind, $idCurso, null);
        return $promedioxBimestre;
    }
            
    function promedioFinalLibreta($promedioBI, $promedioBII, $promedioBIII, $promedioBIV) {
        $promedioFin = ($promedioBI + $promedioBII + $promedioBIII + $promedioBIV)/4;
        return $promedioFin;
    }
    
    function promedioFinal($arrayPromedioFinal) {
        $cont = 0;
        $suma = 0;
        
        foreach($arrayPromedioFinal as $promeF) {
            if($promeF != '-' ) { 
                $suma = floatval($promeF) + $suma; 
                $cont++;
            } 
        }
        $promedioF = ($cont != 0) ? $suma/$cont : 0;
        return $promedioF;
    }
    
    function promediOrdenMerito($promedioF1, $promedioF2) {
        $promOrMerito = array($promedioF1, $promedioF2);
        $cont = 0;
        $suma = 0;
        foreach($promOrMerito as $promMerit) {
            if($promMerit != null) {
                $cont++;
                $suma = $promMerit + $suma;
                $promF = $suma/$cont;
                return round($promF, 2);
            } else { $cont = 0; }
        }     
    }
       
    function printInputCompentario() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAlumno = _simple_decrypt(_post('idAlumno'));
            $idAnio   = _post('Anio');
            $bimestre = _post('bimestre');
            $idAula   = _simple_decrypt(_post('idAula'));
      
            if($idAlumno == '' || $idAnio == '' || $idAula == '' || $bimestre == '') {
                throw new Exception(ANP);
            }
            
            $comentarios = $this->getGroupComentarios($idAlumno, $idAula, $idAnio, $bimestre);
            $data['comen_Bim'] = $comentarios['comentario']; 
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            }
            echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getGroupComentarios($idAlumno, $idAula, $Anio, $bimestre) {
        $Comen = $this->m_tutoria->getComentario($idAlumno, $idAula, $Anio, $bimestre);
        return $Comen;
    }
    
    function bimestresDelAnio() {
        $bimestres = array(BIMESTRE_I, BIMESTRE_II, BIMESTRE_III, BIMESTRE_IV);
        return $bimestres;
    }
            
    function buildHTML_StudentAwards($awards) {
        $tmpl= array('table_open'  => '<table>',
                     'table_close' => '</table>');

        $head_1 = array('data' => '', 'class' => 'text-left');
        $head_2 = array('data' => '', 'class' => 'text-left');
        $this->table->set_heading($head_1, $head_2);
        $cont = 0;
        foreach ($awards as $row) {
            if($cont < 2) {
                 $html.= '<div class="mdl-awards" style="float:left;width:49%; text-align:center;">
                            <div class="awards-point" style="background-color:#F3F3F3;color:#757575;width: 28px;height:29px;border-radius:50%;border:2px solid #757575; text-align:center;margin-left:60px;margin-bottom:-30px;z-index:100;padding-top:4px;">'.$row['cantidad'].'</div>
                            <img style="width:80px; margin-bottom:5px;" alt="'.$row['ruta_icono'].'" src="'.RUTA_IMG.'meritos/'.$row['ruta_icono'].'.png">   
                            <label>'.$row['desc_award'].'</label>                           
                          </div>';  
                 $cont++;
            } else {
                  return $html;
            }         
        }
    }
    
    function fechaIniFin($bimestre) {
        $arrayFecha = $this->m_tutoria->getFechaIniFin($bimestre);
        return $arrayFecha;
    }
    
    function getCountAsistencia($idAlumno, $idAula) {
        $asisten       = $this->m_tutoria->getCountAsistencia($idAlumno, $idAula);
        return $asisten;
    }
    
    function getGraficosAula() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idAula = _simple_decrypt(_post('idAula'));
            $anio   = _post('year');
            if($idAula == null || $anio == null) {
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['arrayNotasAlumnosAula'] = $this->buildSeriesByPromedioGetGraficosAula($anio, $idAula);
            $data['asistenciaAula']            = $this->buildAsistenciaAula($idAula, $anio);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
    
    function cambioRol() {
        $idRol     = _simple_decrypt(_post('id_rol'));
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
    
    function setIdSistemaInSession() {
        $idSistema = _decodeCI(_post('id_sis'));
        $idRol = _decodeCI(_post('rol'));
        if ($idSistema == null || $idRol == null) {
            throw new Exception(ANP);
        }
        $data = $this->lib_utils->setIdSistemaInSession($idSistema, $idRol);
        echo json_encode(array_map('utf8_encode', $data));
    }   
    
    function buildSeriesByPromedioCursosEstudiante($idAnio, $idGrado, $idEstudiante, $idCicloAcad, $idAula){
        $cursos = $this->m_tutoria->promedioCursos($idAnio, $idGrado, $idEstudiante, $idCicloAcad, $idAula);
        $arrayPromedio = array();
        $arrayCursos   = array();
        foreach($cursos as $row){
            array_push($arrayPromedio , floatval($row->promedio));
            array_push($arrayCursos   , utf8_encode($row->desc_curso));
        }
        $data['arrayCate'] = json_encode($arrayCursos);
        $data['arrayProm'] = json_encode($arrayPromedio);
        return json_encode($data);
    }
    
    function buildAsistencia($idAlumno, $idAula, $Anio, $bimestre) {
        $asistencia = $this->m_tutoria->getCountAsistenciaGrafic($idAlumno, $idAula, $bimestre);
        if($asistencia == null) {
            return ;
        }
        $tarde_justif = floatval($asistencia['tarde_justif']);
        $tarde        = floatval($asistencia['tarde']);
        $falta        = floatval($asistencia['falta']);
        $falta_justi  = floatval($asistencia['falta_justif']);
        
        $data['tarde_justif'] = json_encode($tarde_justif);
        $data['tarde']        = json_encode($tarde);
        $data['falta']        = json_encode($falta);
        $data['falta_justi']  = json_encode($falta_justi);
        return json_encode($data);
    }
    
    function buildAsistenciaAula($idAula, $year) {
        $asistenciaAula = $this->m_tutoria->getCountAsistenciaAula($idAula, $year);
        if($asistenciaAula == null) {
            return ;
        }
        $tarde_justif = floatval($asistenciaAula['tarde_justif']);
        $tarde        = floatval($asistenciaAula['tarde']);
        $falta        = floatval($asistenciaAula['falta']);
        $falta_justif  = floatval($asistenciaAula['falta_justif']);
    
        $data['tarde_justif'] = json_encode($tarde_justif);
        $data['tarde']        = json_encode($tarde);
        $data['falta']        = json_encode($falta);
        $data['falta_justif']  = json_encode($falta_justif);
        return json_encode($data);
    }
    
    function buildSeriesByPromedioGetGraficosAula($anio, $idAula){
        $alumNotas = $this->m_tutoria->getNotasAula($anio, $idAula);
        $arrayGeneral   = array();
        foreach($alumNotas as $row){
            array_push($arrayGeneral, array(utf8_encode($row->nombre_corto), floatval($row->promedio_final)));
        }
        $data['arrayGeneral'] = json_encode($arrayGeneral);
        return json_encode($data);
    }
}
