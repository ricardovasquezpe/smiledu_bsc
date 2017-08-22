<?php
class M_migracion extends  CI_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
        $this->load->model('mf_rh/m_migrar_scirerh');
    }
    
    function getDatosMigrados($codMigracion, $tipo) {
        $sql = "SELECT INITCAP(desc_migracion) desc_migracion,
                       TO_CHAR(fec_regi, 'DD/MM/YYYY') fecha,
                       detalle,
                       INITCAP(audi_pers_regi) audi_pers_regi
                  FROM log_migracion
                 WHERE grupo_migracion = ?
                   AND tipo_migracion  = ?";
        $result = $this->db->query($sql, array($codMigracion, $tipo));
        return $result->result();
    }
    
    function migrarDatos($tipo) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $codMigracion = null;
            $sql = "SELECT COALESCE(MAX(grupo_migracion), 1) + 1 cod_migra
                      FROM log_migracion
                     WHERE tipo_migracion = ?";
            $result = $this->db->query($sql, array($tipo));
            $codMigracion = $result->row()->cod_migra;
    
            $sql = "SELECT COALESCE(MAX(codigo_carga), 1) + 1 codigo_carga
                      FROM log_errores;";
            $result = $this->db->query($sql);
            $codCarga = $result->row()->codigo_carga;
            if($tipo == AULA_ALUMNO) {
                $sql = "SELECT * FROM fun_migrar_aulas_alumno_alum_x_aula_edusys(?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($codCarga, $codMigracion, _getSesion('nid_persona'),
                                                                                 _getSesion('nombre_completo')) );
                if($result == null) {
                    throw new Exception('(MH-002)');
                }
                if($result->num_rows() != 1) {
                    throw new Exception('(MH-003)');
                }
                if($result->row()->resultado != 'OK') {
                    throw new Exception($result->row()->resultado);
                }
                $data['error'] = EXIT_SUCCESS;
            } else if($tipo == _PERSONAL_) {
                /*$sql = "SELECT * FROM fun_migrar_personal(?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($codMigracion, $codCarga, $this->session->userdata('nid_persona'),
                                           $this->session->userdata('nombre_completo')) );*/
                $data = $this->m_migrar_scirerh->getPersonalScirerh(false, $codMigracion);
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }
            } else if($tipo == MARCAJE) {
                $salida = array();
                exec(RUTA_MIGRAR_MARCAJE_WINDOWS, $salida);
                $string = implode("\n", $salida);
                $dataInsert = array("desc_migracion"  => substr($string, -500),
                                    "tipo_migracion"  => MARCAJE,
                                    "detalle"         => 'Resultado migración de marcaje MSSQL -> PostgreSQL',
                                    "grupo_migracion" => $codMigracion,
                                    "audi_usua_regi"  => _getSesion('nid_persona'),
                                    "audi_pers_regi"  => _getSesion('nombre_completo'));
                $this->db->insert('log_migracion', $dataInsert);
                $data['error'] = EXIT_SUCCESS;
            } else if($tipo == _DOCENTES_CURSOS) {
                $sql = "SELECT * FROM fun_migrar_cursos_docentes_x_curso_aula(?, ?, ?, ?) resultado";
                $result = $this->db->query($sql, array($codCarga, $codMigracion, _getSesion('nid_persona'),
                                                                                 _getSesion('nombre_completo')) );
                if($result == null) {
                    throw new Exception('(MH-004)');
                }
                if($result->num_rows() != 1) {
                    throw new Exception('(MH-005)');
                }
                if($result->row()->resultado != 'OK') {
                    throw new Exception($result->row()->resultado);
                }
                $data['error'] = EXIT_SUCCESS;
            }
            if($data['error'] == EXIT_SUCCESS) {
                $data['msj']   = 'Se realizó la migración';
            }
            $data['cod_migracion'] = $codMigracion;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
}