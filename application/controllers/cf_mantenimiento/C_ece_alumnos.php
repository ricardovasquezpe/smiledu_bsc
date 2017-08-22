<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_ece_alumnos extends CI_Controller {

    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_ece_alumnos');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->library('Classes/PHPExcel.php');
        _validate_usuario_controlador(ID_PERMISO_ALUMNO_ECE);
        $this->_idUserSess = _getSesion('nid_persona');
    }


    public function index(){
  	    $data['titleHeader']      = 'Alumno ECE';
  	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
  	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
  	    $data['rutaSalto']        = 'SI';

        $data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);

    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
        
        //MENU
        $menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
        $data['font_size'] = _getSesion('font_size');
        
        $data['tablaAlumnos']  = $this->buildTableEceAlumnosHtml(null, null);
        $data['tablaAulas']    = $this->buildTableEceAulasHtml(null, null);
        $data['comboSedes']    = __buildComboSedes();
        
        $this->load->view('vf_mantenimiento/v_ece_alumnos', $data);
    }
    
    function buildTableEceAlumnosHtml($idSede, $idGrado, $year = null) {
        $listaAlumnosEce = ($idSede != null || $idGrado != null || $year != null ) ? $this->m_ece_alumnos->getAllAlumnosEceExcel($idSede, $idGrado, $year) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-show-columns="true" data-search="true" id="tb_alumnos">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => 'Secci&oacute;n', 'class' => 'text-center');
		$head_1 = array('data' => 'Alumno');	
		$head_2 = array('data' => 'Nivel de Logro', 'class' => 'text-center');
		$head_3 = array('data' => 'Medida Rash', 'class' => 'text-center');
		$head_4 = array('data' => 'Nivel de Logro', 'class' => 'text-center');
		$head_5 = array('data' => 'Medida Rash', 'class' => 'text-center');
		$this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5);
		foreach($listaAlumnosEce as $row) {
		    $row_col0  = array('data' => $row->nombre_letra);
		    $row_col1  = array('data' => ucwords(strtolower($row->nom_persona)));
		    $row_col2  = array('data' => $row->nivel_logro_lectora);
		    $row_col3  = array('data' => $row->medida_rash_lectura);
		    $row_col4  = array('data' => $row->nivel_logro_matematica);
		    $row_col5  = array('data' => $row->medida_rash_matematica);

		    $this->table->add_row($row_col0, $row_col1, $row_col2, $row_col3, $row_col4, $row_col5);
		}
		return $this->table->generate();
    }
 
    function comboSedesNivelEce_CtrlEce() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $year   = _decodeCI(_post('year'));
            if($idSede == null || $year == null) {
                $data['error'] = EXIT_WARNING;
	            $data['optNivel'] = null;
	            $data['tabAlumnos'] = $this->buildTableEceAlumnosHtml(null, null);
	            throw new Exception(null);
            }
            $data['optNivel']     = __buildComboNivelesBySedeYear($idSede, $year);
            $data['tablaAlumnos'] = $this->buildTableEceAlumnosHtml(0,0);
            $data['error']        = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getComboGradoByNivel_CtrlEce(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede = _decodeCI(_post('idSede'));
            $idNivel = _decodeCI(_post('idNivel'));
            $year   = _decodeCI(_post('year'));
            if($idNivel == null || $idSede == null || $year == null) {
                $data['error']    = EXIT_WARNING;
	            $data['optGrado'] = null;
	            $data['tabAlumnos'] = $this->buildTableEceAlumnosHtml(0,0);
	            throw new Exception(null);
            }
            $data['optGrado'] = __buildComboGradosByNivelYear($idNivel, $idSede, $year);
            //$data['tablaAlumnos'] = $this->buildTableEceAlumnosHtml(0,0);
            $data['error']     = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
   
    function getComboGrado_CtrlEce() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede  = _decodeCI(_post('idSede'));
            $idGrado = _decodeCI(_post('idGrado'));
            $year   = _decodeCI(_post('year'));
            if($idSede == null || $idGrado == null || $year == null) {
                $data['error']    = EXIT_WARNING;
                $data['optGrado'] = null;
                $data['tablaAlumnos'] = $this->buildTableEceAlumnosHtml(0,0);
                throw new Exception(null);
            }
            $data['tablaAlumnos'] = $this->buildTableEceAlumnosHtml($idSede, $idGrado, $year);
            $data['error']     = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function logOut(){
        $logedUser = _getSesion('usuario');
        $this->session->sess_destroy();
        redirect('','refresh');
    }
    
    function subir_excel_CTRL() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $arrayECE    = json_decode(ESTADOS_ECE);
        try {
            $idSede  = _decodeCI(_post('idSede'));
            $idGrado = _decodeCI(_post('idGrado'));
            $year    = _decodeCI(_post('year'));
            
            if($idGrado == null || $idSede == null || $year == null) {
                throw new Exception(ANP);
            }
            $tituloImg = null;
            if(empty($_FILES['itFileXLS']['name'])) {
                throw new Exception("Seleccionar un archivo Excel para subir");
            }
            $ext = pathinfo($_FILES['itFileXLS']['name'], PATHINFO_EXTENSION);
            if($ext != 'xls' && $ext != 'xlsx') {
                throw new Exception('El archivo tiene que ser de tipo Excel (xls o xlsx)');
            }
            $file = 'excel_'.__generateRandomString(5).date("dmhis").'.'.$ext;
            $config['upload_path']   = EXCEL_PATH;
            $config['allowed_types'] = '*';
            $config['max_size']	     = EXCEL_MAX_SIZE;
            $config['file_name']     = $file;
            
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('itFileXLS')) {
                throw new Exception($this->upload->display_errors());
            }
            $upload_data = $this->upload->data();
            $tituloImg = EXCEL_PATH_BD.$upload_data['file_name'];
            $data['error'] = EXIT_SUCCESS;
            
            $inputFileName = './'.$tituloImg;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            if($inputFileType == _HTML_TYPE) {
                throw new Exception("El archivo Excel no debe ser de tipo Página Web (HTML), guárdelo como libro de Excel");
            }         
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
                      
            //var_dump($objPHPExcel);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            
            $tabla = array();
            $flg = __COD_ERROR;
             
            $tmpl = array('table_open'  => '<table id="tbError" border="1">',
                          'table_close' => '</table>');
            $this->table->set_template($tmpl);
            $head_0  = array('data' => 'SECCIÓN'                    ,'width' => '100px');
            $head_1  = array('data' => 'APELLIDO PATERNO'           ,'width' => '300px');
            $head_2  = array('data' => 'APELLIDO MATERNO'           ,'width' => '300px');
            $head_3  = array('data' => 'NOMBRES'                    ,'width' => '300px');
            $head_4  = array('data' => 'NIVEL DE LOGRO - LECTURA'   ,'width' => '100px');
            $head_5  = array('data' => 'MEDIDA RASH - LECTURA'      ,'width' => '100px');
            $head_6  = array('data' => 'NIVEL DE LOGRO - MATEMï¿½TICA','width' => '100px');
            $head_7  = array('data' => 'MEDIDA RASH - MATEMï¿½TICA'   ,'width' => '100px');
            $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7);
            $errTabla = 0;

            $todoBD = array();
            $toBD = array();
           
            $todoSlq = array();
            $nombreCompleto = null;
            foreach ($cell_collection as $cell) {                
                $columnName = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $rowNumber  = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                $A = $objPHPExcel->getActiveSheet()->getCell('A'.$rowNumber)->getValue();                
                $B = $objPHPExcel->getActiveSheet()->getCell('B'.$rowNumber)->getValue();
                $C = $objPHPExcel->getActiveSheet()->getCell('C'.$rowNumber)->getValue();
                $D = $objPHPExcel->getActiveSheet()->getCell('D'.$rowNumber)->getValue();
                $E = $objPHPExcel->getActiveSheet()->getCell('E'.$rowNumber)->getValue();
                $F = $objPHPExcel->getActiveSheet()->getCell('F'.$rowNumber)->getValue();
                $G = $objPHPExcel->getActiveSheet()->getCell('G'.$rowNumber)->getValue();
                $H = $objPHPExcel->getActiveSheet()->getCell('H'.$rowNumber)->getValue();         
                $vacioTodo = ($A == null && $B == null && $C == null && $D == null && $E == null 
                              && $F == null && $G == null && $H == null);            
                    
                if ($rowNumber > 1 && !$vacioTodo) {
                    if(substr($cell, 0, 1) == CELDA_SECCION) {
                        $msj = null;
                        if(strlen(trim($data_value)) > 0 && is_string($data_value)) {
                            $nid_Aula = $this->m_ece_alumnos->getNidAulaByLetra($data_value, $idSede, $idGrado, $year);                          
                            if($nid_Aula != null) {
                                $toBD['__id_aula'] = $nid_Aula;      
                            } else {
                                $msj = "El aula no existe en el sistema";
                                $errTabla++;
                            }
                        } else {
                            $msj = "Ingrese solo letras en Seccion";
                            $errTabla++;
                        }
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }
                        $row_celda_1 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_PATERNO) {
                        $msj = null;
                        if(strlen(trim($data_value)) > 0 && is_string($data_value)) {
                            $nombreCompleto = $data_value;
                        } else {
                            $msj = "Ingrese solo letras en apellido paterno";
                            $errTabla++;
                        }
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }
                        $row_celda_2 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_MATERNO) {
                        $msj = null;
                        if(strlen(trim($data_value)) > 0 && is_string($data_value)){
                            $nombreCompleto .= ' '.$data_value;
                        }else{
                            $msj = "Ingrese solo letras en apellido materno";
                            $errTabla++;
                        }
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }  
                        $row_celda_3 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_NOMBRES) {
                        $msj = null;
                        if(!ctype_digit((string)$data_value) || !empty($data_value) ) {
                                $primerNombre = explode(' ', $data_value)[0];
                                $nombreCompleto .= ' '.$primerNombre;
                                $nid_Alumno = $this->m_ece_alumnos->getAlumnosByAula(utf8_decode($nombreCompleto), $nid_Aula);                             
                                if($nid_Alumno ==  null || $nid_Alumno ==  ""){
                                      $msj = "nombre de alumno no figura en el aula";
                                      $errTabla++;
                                }else{
                                    $toBD['__id_persona'] = $nid_Alumno;
                                }
                        } else {
                            $msj = "Ingrese solo letras en nombres";
                            $errTabla++;
                        }
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }  
                        $row_celda_4 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_NIVEL_LOGRO_LECT) {
                        $msj = null;                  
                       if( in_array(strtoupper($data_value), $arrayECE) ) {                         
                           $toBD['ind_logro_lectura']   = $this->getNivelLogro(strtoupper($data_value));
                           $toBD['nivel_logro_lectora'] = ($data_value != null) ? strtoupper($data_value) : '---';
                       } /*else {
                           $msj = "Ingrese solo la opción Inicio o Proceso o Satisfactorio";
                           $errTabla++;
                       }*/
                       $err = null;
                       if($msj != null) {
                           $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                           $err = '#FF0000';
                       } else {
                           $msj = $data_value;
                       }     
                       $row_celda_5 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_MEDIDA_RASH_LECT) {
                        $msj = null;
                        if(ctype_digit((string) $data_value)) {
                            $toBD['medida_rash_lectura'] = ($data_value != null) ? $data_value : 0;
                        } /*else {
                            $msj = "Ingrese solo numeros en el campo medida rash";
                            $errTabla++;
                        }*/
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }                
                        $row_celda_6 = array('data' => $msj, 'bgcolor' => $err);
                    }
                    if(substr($cell, 0, 1) == CELDA_NIVEL_LOGRO_MAT) {
                        $msj = null;
                        if(in_array(strtoupper($data_value), $arrayECE) ) {
                            $toBD['ind_logro_matematica']   = $this->getNivelLogro(strtoupper($data_value));
                            $toBD['nivel_logro_matematica'] = ($data_value != null) ? strtoupper($data_value) : '---';
                        }/* else {
                            $msj = "Ingrese solo la opción Inicio o Proceso o Satisfactorio";
                            $errTabla++;
                        }*/
                        $err = null;                    
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        }else {
                            $msj = $data_value;
                        }
                        $row_celda_7 = array('data' => $msj, 'bgcolor' => $err);
                    }     
                    if(substr($cell, 0, 1) == CELDA_MEDIDA_RASH_MAT) {
                        $msj = null;
                        $toBD['medida_rash_matematica'] = ($data_value != null) ? $data_value : 0;
                        if(ctype_digit((string) $data_value)) {
                            $toBD['year_academico'] = $year;//date('Y');
                            array_push($todoBD, $toBD);
                            $toBD = array();
                        }/* else {
                            $msj = "Ingrese solo números en el campo medida rash";
                            $errTabla++;
                        }*/
                        $err = null;
                        if($msj != null) {
                            $msj = '<font color="#FFFFFF"><b>'.$data_value.' Error: '.$msj.'</b></font>';
                            $err = '#FF0000';
                        } else {
                            $msj = $data_value;
                        }
                        $row_celda_8 = array('data' => $msj, 'bgcolor' => $err);
                        $this->table->add_row($row_celda_1, $row_celda_2, $row_celda_3, $row_celda_4, $row_celda_5, $row_celda_6, $row_celda_7, $row_celda_8);
                    }
                }
            }
            /** FIN DEL FOREACH */
           $data['error_excel'] = EXIT_SUCCESS;
           if($errTabla > 0) {
               $data['error_excel'] = EXIT_ERROR;
               $data['error'] = EXIT_ERROR;
               $data['msj']   = 'Errores en el Archivo Excel';
               $tabla = $this->table->generate();
               $this->session->set_userdata(array('TABLA_ERROR' => $tabla) );
               throw new Exception(ANP);
               break;
           } else {
               $data = $this->m_ece_alumnos->updateCargaExcel($todoBD);
           }
           if($data['error'] == EXIT_SUCCESS) {
                $formu = '<div id="custom-toolbarAlumn" style="margin-left:-10px">
                             <div class="form-inline" role="form">
                                  <div style="margin-top: 19px;display:inline;margin-right:10px;">                                
                                  </div>                             
                                  <form action="setExcel" name="expexcel" id="expexcel" method="post"></form>
                             </div>
                          </div>';
                $this->table->generate();
                $data['tablaAlumnos'] = $this->buildTableEceAlumnosHtml($idSede, $idGrado, $year);
                $data['msj'] = 'Datos cargados';
            }
            //borrar archivo excel
            if (!unlink('./'.$tituloImg)) {
                throw new Exception('(CX-001)');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    //generar excel si hubo errores
    function setExcel() {   
        $tabla = _getSesion('TABLA_ERROR');
        if($tabla != null) {
            $data['tabla'] = $tabla;
            $this->session->set_userdata(array('TABLA_ERROR' => null) );
            $this->load->view('vf_mantenimiento/v_reporteexcel', $data);
        }
    }

    function getNivelLogro($nivelLogro) {
        if($nivelLogro == ECE_INICIO) {
            return 1;
        } else if($nivelLogro == ECE_PROCESO) {
            return 2;
        } else {
            return 3;
        }
        return 0;
    }
    
    function enviarFeedBack(){
        $nombre = _getSesion('nombre_completo');
        $mensaje = utf8_decode(_post('feedbackMsj'));
        $url = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
    
    //MODAL POPUP AULAS//
    function getMostrarAulas_CtrlEce(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSede  = _decodeCI(_post('idSede'));
            $idGrado = _decodeCI(_post('idGrado'));
            $year    = _decodeCI(_post('year'));
            if($idSede == null || $idGrado == null || $year == null) {
                $data['error']    = EXIT_WARM;
                $data['tablaAulas'] = $this->buildTableEceAulasHtml(0,0);
                throw new Exception(null);
            }
            $data['tablaAulas'] = $this->buildTableEceAulasHtml($idSede, $idGrado, $year);
            $data['error']     = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
       
    }
       
    function buildTableEceAulasHtml($idSede, $idGrado, $year = null) {
        $listaAlumnosEce = ($idSede != null || $idGrado != null || $year != null ) ? $this->m_ece_alumnos->getAllAulasEcE($idSede, $idGrado, $year) : array();
        $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless"  style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" id="tb_aulas">',
                      'table_close' => '</table>');
        $this->table->set_template($tmpl);
        $head_0 = array('data' => '#');
        $head_1 = array('data' => 'Aula');
        $head_2 = array('data' => 'Nombre de Letra');
        $val = 0;
        $this->table->set_heading($head_0, $head_1, $head_2);
        foreach($listaAlumnosEce as $row) {
            $val++;
            $row_col0  = array('data' => $val);
            $row_col1  = array('data' => $row->desc_aula);
            $row_col2   = array('data' => ' <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="nomLet'. $val .'" attr-cambio="false" maxlength="1" data-descrip="1" data-pkIdAul="' . _encodeCI($row->nid_aula) . '" value="' . $row->nombre_letra . '">
                                                <label class="mdl-textfield__label" for="nomLet'. $val .'"></label>
                                            </div>');
            $this->table->add_row($row_col0, $row_col1, $row_col2);
        }
        $tabla = $this->table->generate();
        return $tabla;
    }
    
    function grabarAulas(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $myPostData = json_decode(_post('letras'), TRUE);
            $arrayGeneral = array();       
            foreach($myPostData['letra'] as $key => $letra) {                                     
                    $idAula  = _decodeCI($letra['nid_aula']);
                    $descrip = strtoupper($letra['nombre_letra']);
                    $arrayDatos = array("nid_aula"     => $idAula,
                                        "nombre_letra" => $descrip); 
                    array_push($arrayGeneral, $arrayDatos);
                    $data = $this->m_ece_alumnos->UpdateLetras($arrayGeneral);                 
                }                         
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode',$data));
    }
}