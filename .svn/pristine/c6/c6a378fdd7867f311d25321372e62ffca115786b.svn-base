<?php
class M_responsable_indicador extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllPersonasByIndicador($idIndicador){
        $sql = "SELECT p.nid_persona,
                       p.foto_persona,
                       p.telf_pers,
                       p.correo_pers,
                       CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona) AS nombrecompleto,
                       (SELECT string_agg(r2.desc_rol,', ') 
                       FROM rol r2, 
                            persona_x_rol pr2 
                       WHERE r2.nid_rol = pr2.nid_rol 
                         AND pr2.nid_persona = p.nid_persona 
                         AND pr2.flg_acti = '1') roles,
                       (SELECT CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona)
                          FROM persona p1
                         WHERE p1.nid_persona = ir.audi_id_usua) nombre_asignador,
                       audi_fec_regi
                  FROM persona 	      p,
                       bsc.indicador_responsable ir
                 WHERE ir.flg_acti       = '1'
                   AND p.flg_acti        = '1'
                   AND ir.__id_persona   = p.nid_persona
                   AND ir.__id_indicador = ? ";
        $result = $this->db->query($sql,array($idIndicador));
        return $result->result();
    }
    
    function getInfoResponsableByIndicador($idIndicador){
        $sql = "SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
                            ELSE 'no_user.svg' END AS foto_persona,
                       p.telf_pers,
                       p.correo_pers,
                       p.nid_persona,
                       p.google_foto,
                       CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona) AS nombrecompleto
                  FROM persona 	      p,
                       bsc.indicador_responsable ir
                 WHERE ir.flg_acti       = '1'
                   AND p.flg_acti        = '1'
                   AND ir.__id_persona   = p.nid_persona
                   AND ir.__id_indicador = ? 
                   ORDER BY ir.audi_fec_regi ASC";
        $result = $this->db->query($sql,array($idIndicador));
        return $result->result();
    }
    
    function getObjetivosByLinea($idLinea){
        $sql = "SELECT o._id_objetivo,
                       o.desc_objetivo,
                       o.cod_obje
                  FROM bsc.objetivo o 
                 WHERE o.__id_linea_estrategica = ? ";
        $result = $this->db->query($sql, array($idLinea));
        return $result->result();
    }
    
    function getIndicadoresByObjetivo($idObjetivo){
        $sql = "SELECT i._id_indicador, 
                       i.desc_indicador ,
                       i.cod_indi
                  FROM bsc.indicador i 
                 WHERE i.__id_objetivo = ? 
                 ORDER BY i._id_indicador";
        $result = $this->db->query($sql, array($idObjetivo));
        return $result->result();
    }
    
    function deleteResposableByIndicador($dato){
        $rpt['error'] = 1;
        $rpt['msj']   = null;
        $rpt['cabecera'] = CABE_ERROR;
        try {
            $this->db->where('__id_persona'   , $dato['__id_persona']);
            $this->db->where('__id_indicador' , $dato['__id_indicador']);
            unset($dato['__id_persona']);
            unset($dato['__id_indicador']);
            $this->db->update('bsc.indicador_responsable', $dato);
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_DEL;
            $rpt['cabecera'] = CABE_DEL;
        }catch (Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }

    function getAllPersonasByNombre($idIndicador,$nombrePersona){
        $sql = "SELECT * 
                  FROM (
                SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona) AS nombrecompleto,
                       ir.flg_acti,
                      (SELECT COUNT(1)
                         FROM bsc.indicador_responsable ir1
                        WHERE ir1.__id_persona = p.nid_persona) AS count_ind
                 FROM persona p,
                 	  persona_x_rol pr LEFT JOIN bsc.indicador_responsable ir ON(ir.__id_indicador = ? AND pr.nid_persona = ir.__id_persona AND ir.flg_acti = '1')
                WHERE pr.nid_persona = p.nid_persona
                  AND pr.nid_rol     <> ".ID_ROL_ESTUDIANTE."
                  AND pr.flg_acti    = '".FLG_ACTIVO."'
                  AND p.flg_acti     = '".FLG_ACTIVO."'
             GROUP BY p.nid_persona, CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ', ' ,p.nom_persona), ir.flg_acti
             ORDER BY ir.flg_acti , nombrecompleto) new_pers
           WHERE UPPER(new_pers.nombrecompleto) LIKE UPPER(?)";
        
        $result = $this->db->query($sql,array($idIndicador,'%'.$nombrePersona.'%'));
        return $result->result();
    }
    
    function updateInsertIndicadorPersona($arrayDatos){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try {
            $cont = 0;
            foreach ($arrayDatos as $dato) {
                if($dato['condicion'] == 0 ) {//insert
                    unset($dato['condicion']);
                    unset($dato['audi_id_modi']);
                    unset($dato['audi_nomb_modi']);
                    $this->db->insert('bsc.indicador_responsable', $dato);
                    $cont = $cont + $this->db->affected_rows();
                } else if($dato['condicion'] == 1){
                    unset($dato['condicion']);
                    $this->db->where('__id_persona', $dato['__id_persona']);
                    $this->db->where('__id_indicador', $dato['__id_indicador']);
                    unset($dato['__id_persona']);
                    unset($dato['__id_indicador']);
                    unset($dato['audi_id_usua']);
                    unset($dato['audi_nomb_usua']);
                    $this->db->update('bsc.indicador_responsable', $dato);
                    $cont = $cont + $this->db->affected_rows();
                }
            }
            if($cont != count($arrayDatos) ) {
                $this->db->trans_rollback();
                throw new Exception('(MA-002)');
            }
            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = '(MA-001)';
                $this->db->trans_rollback();
            } else {
                $data['error']     = EXIT_SUCCESS;
                $data['msj']       = MSJ_INS;
                $data['cabecera']  = CABE_INS;
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function evaluaInsertUpdate($idPersona, $idIndicador){
        $sql = "SELECT COUNT(__id_persona) as cuenta
                  FROM bsc.indicador_responsable
                 WHERE __id_persona   = ?
                   AND __id_indicador = ?";
        $result = $this->db->query($sql, array($idPersona, $idIndicador));
        return $result->row()->cuenta;
    }
    
    function getCategoriaByObjetivo($idObjetivo){
        $sql = "SELECT c.id_categoria,
                        c.desc_categoria
                 FROM bsc.categoria c
                 WHERE c.__id_objetivo = ?";
         
        $result = $this->db->query($sql, array($idObjetivo));
        return $result->result();
    }
    
    function getIndicadoresByCategoria($idCategoria){
        $sql = "SELECT i._id_indicador,
                       i.desc_indicador,
                       i.cod_indi 
                 FROM bsc.indicador i,
                      bsc.categoria_x_indicador ci
                 WHERE ci.__id_indicador = i._id_indicador
                  AND ci.__id_categoria  = ?
                  AND i.cod_indi IS NOT NULL";
    
        $result = $this->db->query($sql, array($idCategoria));
        return $result->result();
    }
    
    function getIndicadoresByNombreCod($nombreCod){
        $sql = "SELECT i._id_indicador,
                       i.desc_indicador,
                       i.cod_indi
                 FROM bsc.indicador i
                 WHERE (UPPER(i.desc_indicador) LIKE UPPER(?) OR 
                        UPPER(i.cod_indi) LIKE UPPER(?))
                  AND i.cod_indi IS NOT NULL
              GROUP BY i._id_indicador";
        $result = $this->db->query($sql, array("%".$nombreCod."%", "%".$nombreCod."%"));
        return $result->result();
    }
}