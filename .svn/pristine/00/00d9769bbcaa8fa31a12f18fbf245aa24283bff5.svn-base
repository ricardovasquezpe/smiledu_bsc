<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_disciplina extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cach$tablaCompetenciase-Control: post-check=0, pre-check=0',false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('mf_mantenimiento/m_disciplina');
        $this->load->model('m_utils');
        $this->load->library('table');
        $this->load->helper('html');
        _validate_usuario_controlador(ID_PERMISO_COMPETENCIA);
        $this->_idUserSess = _getSesion('nid_persona');
    }
    
	public function index(){    
	    $data['titleHeader']      = 'Competencias';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
    	$data['tiposDisciplina']	=	json_decode(TIPOS_DISCIPLINA);
    	$data['nivelCompetitivo']	=	json_decode(NIVEL_COMPETITIVO);
    	$data['nivelesAcademicos']  = 	__buildComboNiveles();
    	$data['docentesChoice'] 	= 	__buildComboByRol(ID_ROL_DOCENTE);
    	$data['tablaCompetencias']  =   $this->buildTablaCompetenciasHTML($this->m_disciplina->getDisciplinas());
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
    	
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU
    	$menu = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
        $this->load->view('vf_mantenimiento/v_disciplina', $data);
	}	
	
	function buildTablaCompetenciasHTML($listaCompetencias){
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]" 
			                                   data-show-columns="true" data-search="true" id="tb_disciplinas">',
					  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
		$head_8 = array('data' => '#'                     , 'class' => 'text-left');
		$head_0 = array('data' => 'Tipo Disciplina'       , 'class' => 'text-left');			
		$head_1 = array('data' => 'Disciplina'            , 'class' => 'text-left');
		$head_2 = array('data' => 'Nivel Acad&eacute;mico', 'class' => 'text-left');
		$head_3 = array('data' => 'Nivel Competitivo'     , 'class' => 'text-left');
		$head_4 = array('data' => 'Organizador'           , 'class' => 'text-left');
		$head_5 = array('data' => 'Fecha'                 , 'class' => 'text-center');
		$head_6 = array('data' => 'N* Galardones'         , 'class' => 'text-right');
		$head_7 = array('data' => 'Docente'               , 'class' => 'text-left');
		$head_9 = array('data' => 'Acci&oacute;n'         , 'class' => 'text-center');
		$val = 0;
		$this->table->set_heading($head_8, $head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7,$head_9);
		foreach($listaCompetencias as $row){
		    $val++;
		    $idDetalleDis = _simple_encrypt($row->id_detalle_disciplina);
			$audi_fec_regi = ($row->fecha == null) ? '-' : date('d/m/Y',strtotime($row->fecha));
			$deletDisciplina='<button class="mdl-button mdl-js-button mdl-button--icon" onclick="deleteCompetencia(\''.$idDetalleDis.'\');"><i class="mdi mdi-delete"></i></button>';
				
			$row_col8  = array('data' => $val                    , 'class' => 'text-left');
			$row_col0  = array('data' => $row->tipo_disciplina   , 'class' => 'text-left');
			$row_col1  = array('data' => $row->desc_disciplina   , 'class' => 'text-left');
			$row_col2  = array('data' => $row->desc_nivel        , 'class' => 'text-left');
			$row_col3  = array('data' => $row->nivel_competitivo , 'class' => 'text-left');
			$row_col4  = array('data' => $row->organizador       , 'class' => 'text-left');
			$row_col5  = array('data' => $audi_fec_regi          , 'class' => 'text-center');	
			$row_col6  = array('data' => $row->nro_copas         , 'class' => 'text-right');
			$row_col7  = array('data' => '<p style="overflow-y:hidden;word-wrap:break-word;width:100%">'.ucwords(strtolower($row->ape_pate_pers)).' '.ucwords(strtolower($row->ape_mate_pers)).' '.ucwords(strtolower($row->nom_persona)).'</p>');
			$row_col9  = array('data' => $deletDisciplina        , 'class' => 'text-center');
			$this->table->add_row($row_col8, $row_col0, $row_col1, $row_col2, $row_col3, $row_col4, $row_col5, $row_col6, $row_col7,$row_col9);
		
			
		}
		$tabla = $this->table->generate();
		return $tabla;
	}
	
	function getDisciplinasByTipo(){			
		$disciplinas = $this->m_disciplina->getDisciplinasByTipoDisciplina(_post('val'));
		$result	     = '<select id="selectDisciplina" name="selectDisciplina" data-live-search="true" class="form-control pickerButn">
						<option value="">Selec. Disciplina</option>';
		foreach ($disciplinas as $row){
			$result .= '<option value="'._encodeCI($row->id_disciplina).'">'.$row->desc_disciplina.'</option>';
		}
		$result 	.= '</select>';
		echo $result;
	}
	
	function insCompetencia(){
		$data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
		try{
			$tipo_disciplina	=	_post('selectTipoDisciplina');
			$disciplina			=	_post('selectDisciplina');
			$nivel_competitivo	=	_post('selectNivelCompetitivo');
			$nivel_academico	=	_post('selectNivelAcademico');
			$docente			=	_post('selectDocentes');
			$fecha				=	_post('fecCompe');
			$organizador		=	utf8_decode(_post('organizador'));
			$nro_copa	        =	_post('nroCopas');
			$year               = date('Y',strtotime($fecha));
			if($tipo_disciplina	==	'' || $disciplina == '' || $nro_copa    == '' ||
			   $nivel_academico	==	'' || $docente	  == '' || $fecha	          == '' || $organizador == ''){
				$data['cabecera'] = CABE_WARN;
				$data['error']    = EXIT_WARM;
				throw new Exception('Por favor, complete los campos');
			}
			if($year < date('Y')){
			    throw new Exception('El a�o debe ser el actual');
			}
			$arrayTipoDisciplinas =	json_decode(TIPOS_DISCIPLINA);
			if(!in_array($tipo_disciplina, $arrayTipoDisciplinas)){
			    throw new Exception(ANP);	
			}
			
			if($tipo_disciplina == TIPO_DISCI_ARTISTICO) {
			    $nivel_competitivo = null;
			} else {
			    $arraynivelCompeti = json_decode(NIVEL_COMPETITIVO);
			    if(!in_array($nivel_competitivo, $arraynivelCompeti)){
			        throw new Exception(ANP);
			    }
			}
			$idNivel = _decodeCI($nivel_academico);
			if($idNivel	== null){
				throw new Exception(ANP);
			}
			$countNivel	=	$this->m_disciplina->existeNivel($idNivel);
		    if($countNivel	==	0){
		    	throw new Exception(ANP);		    	
		    }
		    
		    $idDocente	=	_decodeCI($docente);
		    if($idDocente == null){
		    	throw new Exception(ANP);
		    }
		    $countDocente = $this->m_disciplina->existeDocente($idDocente);
		    if($countDocente	==	0){
		    	throw new Exception(ANP);
		    }

		    $idDisciplina	=	_decodeCI($disciplina);
		    if($idDisciplina	==	null){
		    	throw new Exception(ANP);
		    }
		    $countDisciplina = $this->m_disciplina->existeDisciplina($idDisciplina);
		    if($countDisciplina	==	0){
		    	throw new Exception(ANP);
		    }
		    if(_validateDate($fecha, 'd/m/Y') == false){
		    	throw new Exception('Formato de Fecha Incorrecta');
		    }
		   
		    if(!ctype_digit((string) $nro_copa)){
		    	throw new Exception('N� de Copas : Solo Numeros');
		    }
		    
		    $data = array('__id_disciplina'   => $idDisciplina,
		    			  'nivel_competitivo' => $nivel_competitivo,
		    			  '__id_nivel'        => $idNivel,
		    			  'fecha' 			  => $fecha,
		    			  '__id_docente'      => $idDocente,
		    			  'nro_copas'         => $nro_copa,
		    			  'organizador'       => $organizador
		    );
		    
			$data = $this->m_disciplina->insertDisciplinaDetalle($data);
			if($data['error'] == EXIT_SUCCESS){
				$data['tabCompetencias'] = $this->buildTablaCompetenciasHTML($this->m_disciplina->getDisciplinas());
			}
		}catch(Exception $e){			
			$data['msj'] = $e->getMessage();
      	}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function deletCompetencia(){		
		$data['error']    = EXIT_ERROR;
		$data['msj']      = null;
		$data['cabecera'] = CABE_ERROR;
		try{
		$id_disciplina_detalle	=	_simple_decrypt(_post('id_delete'));	
		$data = $this->m_disciplina->deleteTablaByCampo('disciplina_detalle','id_detalle_disciplina',$id_disciplina_detalle);
		if($data['error'] == EXIT_SUCCESS){
			$data['tabCompetencias'] = $this->buildTablaCompetenciasHTML($this->m_disciplina->getDisciplinas());
		}
		}catch(Exception $e){
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut(){
	    $logedUser = _getSesion('usuario');
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
	
    function enviarFeedBack(){
        $nombre  = _getSesion('nombre_completo');
        $mensaje = utf8_decode(_post('feedbackMsj'));
        $url     = _post('url');
        $this->lib_utils->enviarFeedBack($mensaje,$url,$nombre);
    }
}