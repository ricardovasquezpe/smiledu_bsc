<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_migracion extends CI_Controller {
    
    private $_idUserSess = null;
    
    function __construct() {
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('m_utils');
        $this->load->model('mf_mantenimiento/m_migracion');
        $this->load->model('mf_rh/m_migrar_scirerh');
        $this->load->library('table');
        $this->load->helper('html');
        if(!$this->input->is_cli_request()) {//Si NO es llamado desde la linea de comandos
            _validate_usuario_controlador(ID_PERMISO_MIGRACION);
            $this->_idUserSess = _getSesion('nid_persona');
        }
    }

	public function index() {
	    $data['titleHeader']      = 'Migraci&oacute;n';
	    $data['ruta_logo']        = MENU_LOGO_SIST_AV;
	    $data['ruta_logo_blanco'] = MENU_LOGO_SIST_AV;
	    $data['rutaSalto']        = 'SI';
        
    	$data['arbolPermisosMantenimiento'] = __buildArbolPermisosBase($this->_idUserSess);
    	
    	$rolSistemas  = $this->m_utils->getSistemasByRol(0, $this->_idUserSess);
    	$data['apps'] = __buildModulosByRol($rolSistemas, $this->_idUserSess);
    	
    	//MENU Y CABECERA
    	$menu     = $this->load->view('v_menu', $data, true);
    	$data['menu']      = $menu;
    	$data['font_size'] = _getSesion('font_size');
    	
    	$data['tablaMigracion'] = $this->buildTablaMigracion_HTML(null, null);
    	$opcion = null;
    	foreach (json_decode(TIPOS_MIGRACION) as $tipo) {
    	    $opcion .= '<option value="'.$tipo.'">'.$tipo.'</option>';
    	}
    	$data['tipoMigra'] = $opcion;
    	
        $this->load->view('vf_mantenimiento/v_migracion', $data);
	}
	
	function buildTablaMigracion_HTML($codMigracion, $tipo) {
	    $listaMigrados = $this->m_migracion->getDatosMigrados($codMigracion, $tipo);
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbMigracion" data-show-columns="true">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1 = array('data' => '#');
	    $head_2 = array('data' => 'Descripci&oacute;n');
	    $head_3 = array('data' => 'Fecha');
	    $head_4 = array('data' => 'Detalle');
	    $head_5 = array('data' => 'Usuario');
	    $val = 0;
	    $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5);
	    foreach($listaMigrados as $row) {
	        $val++;
	        $row_cell_1  = array('data' => $val);
	        $row_cell_2  = array('data' => $row->desc_migracion);
	        $row_cell_3  = array('data' => $row->fecha);
	        $row_cell_4  = array('data' => $row->detalle);
	        $row_cell_5  = array('data' => $row->audi_pers_regi);
	        $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5);
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function getHistorialByGrupo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        if(_post('tipo') == null || _post('tipo') == '') {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        if(!in_array(strtoupper(_post('tipo')), json_decode(TIPOS_MIGRACION))) {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        if(_post('grupo') == null || _post('grupo') == '') {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        $grupo = _simple_decrypt(_post('grupo'));
	        $data['tbMigrar'] = $this->buildTablaMigracion_HTML($grupo, _post('tipo'));
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getGruposByTipo() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        if(_post('tipo') == null || _post('tipo') == '') {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        if(!in_array(strtoupper(_post('tipo')), json_decode(TIPOS_MIGRACION))) {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        $data['comboGrupo'] = __buildComboGrupoMigracion(_post('tipo'));
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function migrarDatos() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        if(_post('tipo') == null || _post('tipo') == '') {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        if(!in_array(strtoupper(_post('tipo')), json_decode(TIPOS_MIGRACION))) {
	            $data['tbMigrar'] = null;
	            $data['error'] = EXIT_SUCCESS;
	            throw new Exception(null);
	        }
	        $data = $this->m_migracion->migrarDatos(_post('tipo'));
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['tbMigrar'] = $this->buildTablaMigracion_HTML($data['cod_migracion'], _post('tipo'));
	            $data['cod_migracion'] = _simple_encrypt($data['cod_migracion']);
	            $data['comboGrupo'] = __buildComboGrupoMigracion(_post('tipo'));
	            if(_post('tipo') == _PERSONAL_) {
	                $this->m_utils->vacuumTablesMigracion(array("persona", "rrhh.huella", "log_migracion", "rrhh.personal_activo"));
	            }
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**
	 * Migrar de SCIRERH a SCHOOWL este metodo solo lo llama el NodeJS
	 * @author dfloresgonz
	 * @since 04.05.2016
	 */
	function migrarPersonal() {
	    $personal = $this->m_migrar_scirerh->getPersonalScirerh(true, null);
	    $this->m_utils->vacuumTablesMigracion(array("persona", "rrhh.huella", "log_migracion", "rrhh.personal_activo"));
	    echo $personal['msj'];
	}
	
	function getPersonalSCIRERH() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $personal = $this->m_migrar_scirerh->getPersonalUpdate();
	        $data['tbPersonal'] = $this->buildTablaPersonal_HTML($personal, 'Personal de Planilla');
            $data['titlePersonal']    = 'Personal de Planilla';
	        $data['areasGenerales']   = $this->getCombo('areasGenerales');
	        $data['areasEspecificas'] = null;//$this->getCombo('areasEspecificas');
	        $data['cmbCargo']         = null;//$this->getCombo('cmbCargo');
	        $data['cmbJornLab']       = $this->getCombo('cmbJornLab');
	        $data['cmbSedesCtrl']     = $this->getCombo('cmbSedesCtrl');
	        $data['cmbNivelCtrl']     = $this->getCombo('cmbNivelCtrl');
	        
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCombo($tipo) {
	    $lista = null;
	    if($tipo == 'areasGenerales') {
	        $lista = $this->m_migrar_scirerh->getAreasGenerales();
	    } else if($tipo == 'areasEspecificas') {
	        $lista = $this->m_migrar_scirerh->getAreasEspecificas();
	    } else if($tipo == 'cmbCargo') {
	        $lista = $this->m_migrar_scirerh->getCargos();
	    } else if($tipo == 'cmbJornLab') {
	        $lista = $this->m_migrar_scirerh->getJornadasLaborales();
	    } else if($tipo == 'cmbSedesCtrl') {
	        $lista = $this->m_migrar_scirerh->getSedesControl();
	    } else if($tipo == 'cmbNivelCtrl') {
	        $lista = $this->m_migrar_scirerh->getNivelesControl();
	    }
	    if(!is_array($lista)) {
	        return null;
	    }
	    $opcion = '';
	    foreach ($lista as $obj) {
	        $opcion .= '<option value="'. _simple_encrypt($obj->id).'">'._ucwords($obj->descr).'</option>';
	    }
	    return $opcion;
	}
	
function buildTablaPersonal_HTML($personal, $titulo) {
	    $tmpl = array('table_open'  => '<table data-toggle="table" class="table borderless" data-toolbar="#custom-toolbar" style="background-color:white;border-color:white"
			                                   data-pagination="true" data-page-list="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
			                                   data-search="true" id="tbPersonal" data-show-columns="true">',
	                  'table_close' => '</table>');
	    $this->table->set_template($tmpl);
	    $head_1  = array('data' => 'ID');
	    $head_2  = array('data' => 'Nombres');
	    $head_3  = array('data' => 'Nro. Doc.', 'data-visible' => 'false');
	    $head_4  = array('data' => 'Empresa'  , 'data-visible' => 'false');
	    $head_5  = array('data' => 'A. General' , 'data-field' => 'area_general');
	    $head_6  = array('data' => 'A. Especif.', 'data-field' => 'area_especifica');
	    $head_7  = array('data' => 'Cargo'      , 'data-field' => 'cargo');
	    $head_8  = array('data' => 'Jorn. Lab.' , 'data-field' => 'jornada_laboral');
	    $head_9  = array('data' => 'Sede Ctrl'  , 'data-field' => 'sede_control');
	    $head_10 = array('data' => 'Nivel Ctrl' , 'data-field' => 'nivel_control');
	    $head_12 = array('data' => 'Correo Pers.' , 'data-visible' => 'false', 'data-field' => 'correo_pers');
	    $head_13 = array('data' => 'Correo Inst.' , 'data-visible' => 'false', 'data-field' => 'correo_inst');
	    $head_14 = array('data' => 'Correo Admin.' , 'data-visible' => 'false', 'data-field' => 'correo_admin');
	    if($titulo == 'Personal de Planilla') {
	        $head_11 = array('data' => 'Acci&oacute;n', 'data-field' => 'button');
	        $this->table->set_heading($head_11, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8, $head_9, $head_10, $head_12, $head_13, $head_14);
	    } else {
	        $this->table->set_heading($head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8, $head_9, $head_10, $head_12, $head_13, $head_14);
	    }
	    $val = 0;
	    foreach($personal as $row) {
	        $val++;
	        $row_cell_1   = array('data' => $row->personal_id);
	        $row_cell_2   = array('data' => ucwords($row->nombre_completo));
	        $row_cell_3   = array('data' => $row->nro_documento);
	        $row_cell_4   = array('data' => $row->empresa_razon_social);
	        $row_cell_5   = array('data' => $row->desc_area_general, 'class' => ($row->id_area_general == '0' ? 'danger' : null).' celda_area_general' );
	        $row_cell_6   = array('data' => $row->desc_area_especifica, 'class' => ($row->id_area_especifica == '0000000000' ? 'danger' : null).' celda_area_especifica' );
	        $row_cell_7   = array('data' => $row->desc_cargo_schoowl, 'class' => ($row->id_cargo_schoowl == '00' ? 'danger' : null).' celda_cargo' );
	        $row_cell_8   = array('data' => $row->desc_jornada_laboral, 'class' => ($row->id_jornada_laboral == '000000000000000' ? 'danger' : null).' celda_jornada' );
	        $row_cell_9   = array('data' => $row->desc_sede_control, 'class' => ($row->id_sede_control == '00000000' ? 'danger' : null).' celda_sede' );
	        $row_cell_10  = array('data' => $row->desc_nivel_control, 'class' => ($row->id_nivel_control == '00000000' ? 'danger' : null).' celda_nivel' );
	        
	        $row_cell_12  = array('data' => $row->correo_pers, 'class' => ($row->correo_pers == null ? 'danger' : null).' celda_correo_pers' );
	        $row_cell_13  = array('data' => $row->correo_inst, 'class' => ($row->correo_inst == null ? 'danger' : null).' celda_correo_inst' );
	        $row_cell_14  = array('data' => $row->correo_adm, 'class' => ($row->correo_adm == null ? 'danger' : null).' celda_correo_adm' );
	        
	        if($titulo == 'Personal de Planilla') {
	            $row_cell_11  = array('data' => $this->getButton( ( (array) $row )  ) );
	            $this->table->add_row($row_cell_11, $row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5,
	                                  $row_cell_6, $row_cell_7, $row_cell_8, $row_cell_9, $row_cell_10, $row_cell_12, $row_cell_13, $row_cell_14);
	        } else {
	            $this->table->add_row($row_cell_1, $row_cell_2, $row_cell_3, $row_cell_4, $row_cell_5,
	                                  $row_cell_6, $row_cell_7, $row_cell_8, $row_cell_9, $row_cell_10, $row_cell_12, $row_cell_13, $row_cell_14);
	        }
	    }
	    $tabla = $this->table->generate();
	    return $tabla;
	}
	
	function getButton($row) {
	    $dataTag = 'data-nombres="'.ucwords($row['nombre_completo']).'" data-id_pers="'._encodeCI($row['personal_id']).'" data-correo_pers="'.$row['correo_pers'].'"
	                    data-id_area_gene="'._simple_encrypt($row['id_area_general']).'"  data-id_area_espe="'._simple_encrypt($row['id_area_especifica']).'"
	                    data-id_cargo="'._simple_encrypt($row['id_cargo_schoowl']).'"     data-id_jorn_lab="'._simple_encrypt($row['id_jornada_laboral']).'"
	                    data-id_sede_ctrl="'._simple_encrypt($row['id_sede_control']).'"  data-id_nivel_ctrl="'._simple_encrypt($row['id_nivel_control']).'"
	                    data-id_periodo="'._encodeCI($row['periodo_id']).'"  data-correo_inst="'.$row['correo_inst'].'"  data-correo_adm="'.$row['correo_adm'].'"  ';
	    $button = '<button class="mdl-button mdl-js-button mdl-button--icon" type="button" onclick="abrirEditar( $(this) );" '.$dataTag.' data-toggle="tooltip" data-placement="top" data-original-title="Edit row"><i class="mdi mdi-edit"></i></button>';
	    return $button;
	}
	
	function editarDatosSCIRERH() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idPersGlobal = _decodeCI(_post('idPersGlobal'));
	        if($idPersGlobal == null) {
	            throw new Exception(ANP);
	        }
	        $idPeriodoGlobal = _decodeCI(_post('idPeriodoGlobal'));
	        if($idPeriodoGlobal == null) {
	            throw new Exception(ANP);
	        }
	        $arryUpdatePersonal       = array();
	        $arryUpdatePersonalActivo = array();
	        
	        $correoPers   = _post('correoPers');
	        $correo_inst  = _post('correo_inst');
	        $correo_admin = _post('correo_admin');
	        //if($correoPers != null && trim($correoPers) != "") {
	            //@PENDIENTE validar que sea correo valido
	            $arryUpdatePersonal['email']        = utf8_decode(strtolower(trim($correoPers)));
	            $arryUpdatePersonal['correo_otro']  = utf8_decode(strtolower(trim($correo_inst)));
	            $arryUpdatePersonal['correo_otro2'] = utf8_decode(strtolower(trim($correo_admin)));
	        //}
	        $idAreaGeneral = _simpleDecryptInt(_post('areaGeneral'));
	        if($idAreaGeneral != null && $idAreaGeneral != "") {
	            $arryUpdatePersonal['convenio_evita_tributacion_id'] = $idAreaGeneral;
	        }
	        $idAreaEspecif = _simpleDecryptInt(_post('areaEspecif'));
	        if($idAreaEspecif != null && $idAreaEspecif != "") {
	            $arryUpdatePersonal['proyecto_id'] = $idAreaEspecif;
	        }
	        $idCargo = _simpleDecryptInt(_post('cargo'));
	        if($idCargo != null && $idCargo != "") {
	            $arryUpdatePersonal['situacion_id'] = $idCargo;
	        }
	        $idJornLab = _simpleDecryptInt(_post('jornLabo'));
	        if($idJornLab != null && $idJornLab != "") {
	            $arryUpdatePersonalActivo['categoria_auxiliar_id'] = $idJornLab;
	        }
	        $idSedeCtrl = _simpleDecryptInt(_post('sedeCtrl'));
	        if($idSedeCtrl != null && $idSedeCtrl != "") {
	            $arryUpdatePersonalActivo['personal_anexo_id'] = $idSedeCtrl;
	        }
	        $idNivelCtrl = _simpleDecryptInt(_post('nivelCtrl'));
	        if($idNivelCtrl != null && $idNivelCtrl != "") {
	            $arryUpdatePersonalActivo['personal_anexo2_id'] = $idNivelCtrl;
	        }
	        $data = $this->m_migrar_scirerh->actualizarDatos($idPersGlobal, $idPeriodoGlobal, $arryUpdatePersonal, $arryUpdatePersonalActivo);
	        if($data['error'] == EXIT_SUCCESS) {
	            $data['button'] = $this->getButton($data);
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getPersonalRecibos() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $html_link  = "https://docs.google.com/spreadsheets/d/1pLNnt5E0Dbs0_REixlFYbFZoGkRJmcgMjKuq_Jd202E/pubhtml?gid=0&single=true";
            $local_html = "sheets.html";
            
            $file_contents = file_get_contents($html_link);
            file_put_contents($local_html, "\xEF\xBB\xBF".$file_contents);

            $dom        = new DOMDocument();  
            $html       = $dom->loadHTMLFile($local_html);
            $dom->preserveWhiteSpace = false;   

            $tables     = $dom->getElementsByTagName('table');   
            $rows       = $tables->item(0)->getElementsByTagName('tr'); 
            $personal = array();
            foreach ($rows as $i => $row) {
                $cols = $row->getElementsByTagName('td');
                $row = array();
                $nomb_columna = null;
                foreach ($cols as $j => $node) {
                    if($j == 0 ) {
                        $nomb_columna = strtolower(utf8_decode($node->textContent));
                    }
                    if($j > 0) {
                        $personal[$j][$nomb_columna] = ( $node->textContent == null || trim($node->textContent) == '' ? null : strtolower(utf8_decode($node->textContent)));
                    }
                }
            }
            $data = $this->m_migrar_scirerh->actualizarPersonalRecibosSpreadSheet($personal);
            if($data['error'] == EXIT_SUCCESS) {
                $personal = $this->m_migrar_scirerh->getPersonalRecibos();
                $data['tbPersonal'] = $this->buildTablaPersonal_HTML($personal, 'Personal por Recibos');
                $data['titlePersonal'] = 'Personal por Recibos';
            }
            if(file_exists('./'.$local_html)) {
                if (!unlink('./'.$local_html)) {
                    throw new Exception('(CM-001)');
                }
            }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function consAreasEspecifCargos() {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try {
	        $idAreaGeneral = _simple_decrypt(_post('idAreaGeneral'));
	        
	        $cmbAreaEspec = '';
	        $areasEspec = $this->m_migrar_scirerh->getAreasEspecificasByGeneral($idAreaGeneral);
	        foreach ($areasEspec as $obj) {
	            $cmbAreaEspec .= '<option value="'. _simple_encrypt($obj->id).'">'._ucwords($obj->descr).'</option>';
	        }
	        $data['areaEspOpt'] = $cmbAreaEspec;
	        //
	        $cmbCargo = '';
	        $cargos = $this->m_migrar_scirerh->getCargosByGeneral($idAreaGeneral);
	        foreach ($cargos as $obj) {
	            $cmbCargo .= '<option value="'. _simple_encrypt($obj->id).'">'._ucwords($obj->descr).'</option>';
	        }
	        $data['cargoOpt'] = $cmbCargo;
	        $data['error'] = EXIT_SUCCESS;
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function logOut() {
	    $this->session->sess_destroy();
	    redirect('','refresh');
	}
}