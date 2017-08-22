<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_reporte_verano extends CI_Controller{
    
    private $_idUserSess = null;
    private $_idRol      = null;
    
	public function __construct(){
        parent::__construct();
        $this->output->set_header(CHARSET_ISO_8859_1);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->model('../mf_usuario/m_usuario');
        $this->load->model('m_utils');
        $this->load->model('m_reportes');
        $this->load->library('table');
        _validate_uso_controladorModulos(ID_SISTEMA_PAGOS, ID_PERMISO_REPORTES, PAGOS_ROL_SESS);
        $this->_idUserSess = _getSesion('nid_persona');
        $this->_idRol      = _getSesion(PAGOS_ROL_SESS);
    }
    
    function getTiposReporteVerano(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $year = _post('yearSelected');
            $sede = _decodeCI(_post('sedeSelected'));
            $data['optCrono'] = null;
            if($sede == null || $year == null){
                throw new Exception('Debes seleccionar la sede y año');
            }
            $cronogramas      = $this->m_reportes->getTiposBySedeYear($sede,$year);
            $data['optCrono'] = $this->buildComboTipoCronoByFiltro($cronogramas);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function buildComboTipoCronoByFiltro($cronogramas){
        $opt = null;
        foreach($cronogramas as $row){
            $idCronoCrypt = _encodeCI($row->id_cronograma);
            $opt .= '<option value="'.$idCronoCrypt.'">'.$row->desc_tipo_cronograma.'</option>';
        }
        return $opt;
    }
    
    function getTalleresByCrono(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $year  = _post('yearSelected');
            $sede  = _decodeCI(_post('sedeSelected'));
            $crono = _decodeCI(_post('tipoSelected'));
            $data['optTalleres'] = null;
            if($sede == null || $year == null || $crono == null){
                throw new Exception('Debes seleccionar un tipo');
            }
            $data['optTalleres'] = __buildComboCuotasByCronograma($crono);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    } 
    
    function getEstudiantesByTaller(){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $taller = _decodeCI(_post('tallerSelected'));
            if($taller == null){
                throw new Exception('Debes seleccionar un taller');
            }
            $estudiantes = $this->m_reportes->getEstudiantesByTaller($taller);
            $data['tbEstudiantes'] = __buildTableHTMLEstudiantesTaller($estudiantes);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e){
            $data['msj']   = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function downloadPDFByFiltroVerano(){
        $this->load->library('m_pdf');
        $idTaller    = _decodeCI($_POST['tallerVeranoPDF']);
        $estudiantes = $this->m_reportes->getEstudiantesByTaller($idTaller);
        $html        = __buildTableHTMLEstudiantesTallerPDF($estudiantes);
        $pdf              = $this->m_pdf->load('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
        $pdf->SetFooter('|{PAGENO}|'.date('d/m/Y h:i:s a'));
        $data['cabecera'] = 'Fecha: '.date('d/m/Y').'<p style="margin-left:400px;margin-top:-50px;text-decoration: underline;font-size:15px"></p>
	                         <img src="../smiledu/public/general/img/logos_colegio/avantgardLogo.png" width="80" height="80" style="margin-bottom:20px;margin-left:600px;margin-top:-30px" /><br/><br/>';
        $data['html']     = $html;
        $data['pdfObj']   = $pdf;
        $data['name']     = 'reporte.pdf';
        $this->load->view('v_pdf_download',$data);
    }
}

