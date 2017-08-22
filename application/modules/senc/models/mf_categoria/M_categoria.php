<?php
class M_categoria extends  CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->model('m_utils');
    }
    
    function getAllCategorias(){
        $sql = "SELECT c.id_categoria,
                       c.desc_cate,
                       null as checkcate 
                  FROM senc.categoria c
              ORDER BY c.desc_cate";
        $result = $this->db->query($sql);
        return $result->result();        
    }
    
    function getPreguntasNotInArray($arrayNotIn,$arrayIn,$arrayObli,$idEncuesta){
        $result = array();
        $sql = "SELECT p.id_pregunta,
                       pec._id_tipo_pregunta,
                       e._id_tipo_encuesta,
                       te.desc_tipo_enc,
                       p.desc_pregunta,
                       ".((count($arrayIn) != 0) ? "CASE WHEN(p.id_pregunta IN ? ) THEN 'checked' 
                                                                           ELSE ''
                                         END AS checked, " : "'' as checked, ")."
                       ".((count($arrayObli) != 0) ? "CASE WHEN(p.id_pregunta IN ? ) THEN 'checked' 
                                                                           ELSE ''
                                         END AS checkObli " : "'' as checkObli ")."
                  FROM senc.preguntas p
                       LEFT JOIN senc.pregunta_x_enc_cate pec ON (pec._id_pregunta = p.id_pregunta AND pec._id_encuesta = ".$idEncuesta.")
                       LEFT JOIN senc.tipo_encuestado te      ON (pec.tipo_encuestado = te.abvr_tipo_enc)
                       LEFT JOIN senc.encuesta e              ON (e.id_encuesta = ".$idEncuesta.")
                    ".((count($arrayNotIn) != 0) ? 'WHERE p.id_pregunta NOT IN  ? ' : ' ')."
              GROUP BY p.id_pregunta,p.desc_pregunta,checked,checkobli,pec._id_tipo_pregunta,e._id_tipo_encuesta,te.desc_tipo_enc
              ORDER BY checked DESC";
        if(count($arrayNotIn) != 0 && count($arrayIn) != 0 && count($arrayObli) != 0){
            $result = $this->db->query($sql,array($arrayIn,$arrayObli,$arrayNotIn));
        } 
        else if(count($arrayNotIn) != 0 && count($arrayIn) == 0 && count($arrayObli) != 0){
            $result = $this->db->query($sql,array($arrayObli,$arrayNotIn));
        } 
        else if(count($arrayNotIn) == 0 && count($arrayIn) != 0 && count($arrayObli) != 0){
            $result = $this->db->query($sql,array($arrayIn,$arrayObli));
        } 
        else if(count($arrayNotIn) == 0 && count($arrayIn) != 0 && count($arrayObli) == 0){
            $result = $this->db->query($sql,array($arrayIn));
        } 
        else if(count($arrayNotIn) != 0 && count($arrayIn) == 0 && count($arrayObli) == 0){
            $result = $this->db->query($sql,array($arrayNotIn));
        } 
        else if(count($arrayIn) != 0 && count($arrayNotIn) != 0 && count($arrayObli) == 0){
            $result = $this->db->query($sql,array($arrayIn,$arrayNotIn));
        }
        else{
            $result = $this->db->query($sql);
        }
        return $result->result();
    }
    
    function getAllCategoriasByEncuesta($idEncuesta){
        $sql = "SELECT c.id_categoria,
                       c.desc_cate,
                       CASE WHEN ce._id_categoria = c.id_categoria THEN 'checked' 
                                                                   ELSE '' 
                       END as checkcate,
                       (SELECT COUNT(1) FROM senc.categoria_x_encuesta WHERE (_id_encuesta = ?)) total
                  FROM senc.categoria c
                       LEFT JOIN senc.categoria_x_encuesta ce ON(ce._id_categoria = c.id_categoria)
                   AND ce._id_encuesta = ?
              ORDER BY orden ASC";
        $result = $this->db->query($sql,array($idEncuesta,$idEncuesta));
        return $result->result();
    }
    
    function insertCategoria($arrayData){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('senc.categoria',$arrayData);
            $data['idCate'] = $this->db->insert_id();
            if($this->db->affected_rows() != 1){
                throw new Exception(ANP);
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getIdByDescripcion($descCate){
        $sql = 'SELECT c.id_categoria
                  FROM senc.categoria c
                 WHERE LOWER(desc_cate) = LOWER(?)
                 LIMIT 1';
        $result = $this->db->query($sql,array($descCate));
        if($result->num_rows() > 0){
            return $result->row()->id_categoria;
        } else{
            return null;
        }
    }
    
    function verificaExisteCategoriaInEncuesta($idEncuesta,$idCategoria){
        $sql = "SELECT COUNT(1) as cuenta
                  FROM senc.categoria_x_encuesta
                 WHERE _id_encuesta  = ?
                   AND _id_categoria = ? ";
        $result = $this->db->query($sql,array($idEncuesta,$idCategoria));
        if($result->num_rows() > 0){
            return $result->row()->cuenta;
        } else{
            return null;
        }
    }
    
    function getDetalleByCategoria($idEncuesta, $idCategoria){
        $sql = "SELECT datos.todo,
                       datos.parte,
                       datos.obli
                  FROM (SELECT 
                        (SELECT COUNT(1) as todo
                    	  FROM senc.pregunta_x_enc_cate x
                    	 WHERE x._id_encuesta = ?
                    	   AND x._id_categoria = ?),
                       (SELECT COUNT(1) parte
                    	  FROM senc.pregunta_x_enc_cate x
                    	 WHERE x._id_encuesta = ?
                    	   AND x._id_categoria = ?
                    	   AND x._id_tipo_pregunta IS NOT NULL),
                       (SELECT COUNT(1) as obli
                    	  FROM senc.pregunta_x_enc_cate x
                    	 WHERE x._id_encuesta = ?
                    	   AND x._id_categoria = ?
                    	   AND x.flg_obligatorio = '".FLG_OBLIGATORIO."')) datos";
        $result = $this->db->query($sql,array($idEncuesta,$idCategoria,$idEncuesta,$idCategoria,$idEncuesta,$idCategoria));
        return $result->row_array();
    }
}