<?php
class M_curso_grado extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
  
    function traerId() {
        $sql = "SELECT COALESCE(MAX(id_curso), 0) + 1 new_id
                  FROM cursos";
        $result = $this->db->query($sql);
        return $result->row()->new_id;
    }

    function registrarCurso($tabla, $arryInsert) {
        $this->db->insert($tabla, $arryInsert);
        if($this->db->affected_rows() != 1) {
            throw new Exception('(MC-001)');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_INS);
    }
    
    function validarCursoUgel($descCurso) {
       $sql = "SELECT COUNT(*) AS count 
                 FROM cursos 
                WHERE UNACCENT(UPPER(desc_curso)) = UNACCENT(UPPER(?)) ";
       $result = $this->db->query($sql, array($descCurso));
       return $result->row()->count; 
    }
    
    function validarCursoEquivalente($descCurso) {
        $sql = "SELECT COUNT(*) AS count
                 FROM curso_equivalente
                WHERE UNACCENT(UPPER(desc_curso_equiv)) = UNACCENT(UPPER(?))";
        $result = $this->db->query($sql, array($descCurso));
        return $result->row()->count;
    }

    function getCursosUgel($idGrado, $yearAcad) {
        $sql = "SELECT c.id_curso,
                       INITCAP(c.desc_curso) AS desc_curso,
                       c.abvr,
                       a.desc_area
                  FROM cursos c,
                       area   a
                 WHERE c._id_area_especifica = a.id_area
                   AND c.id_curso NOT IN (SELECT c.id_curso
                                            FROM curso_ugel_x_grado a,
                                        	     cursos             c 
                            			   WHERE a._id_curso_ugel = c.id_curso
                            			     AND a._id_grado      = ?
                            			     AND a.year_acad      = ?
                            			 ORDER BY a.orden)";
        
        $result = $this->db->query($sql, array($idGrado, $yearAcad)); 
        return $result->result_array();         
    }

    function getCursosEquivalentes($idCurso, $idGrado, $idyear){
        $sql = "SELECT id_curso_equiv,
                       INITCAP(desc_curso_equiv) AS desc_curso_equiv,
                       abvr_curso_equiv 
                  FROM curso_equivalente 
                 WHERE id_curso_equiv NOT IN (SELECT a._id_curso_equiv
                                                FROM curso_equivalencia  a,
                            		                 curso_ugel_x_grado  b,
                            		                 curso_equivalente   c
                                               WHERE a._id_curso_ugel    = b._id_curso_ugel
                                                 AND a._id_curso_ugel	 = ?
                                                 AND a._id_grado         = ?
                                                 AND a._id_curso_equiv   = c.id_curso_equiv
                                                 AND a._year_acad        = ?)
              ORDER BY id_curso_equiv DESC";
        $result = $this->db->query($sql, array($idCurso,$idGrado, $idyear));
        return $result->result_array();
    }
  
    function getCursosxGrado($idGrado, $yearAcad){
        $sql = "SELECT INITCAP(c.desc_curso) AS desc_curso,
                       a.peso,
                       a._id_curso_ugel, 
                       a.year_acad,
                       a._id_grado,
                       (SELECT COUNT(1)
                          FROM curso_equivalencia ce
                         WHERE ce._id_curso_ugel = a._id_curso_ugel
                           AND ce._id_grado      = a._id_grado
                           AND ce._year_acad     = a.year_acad) AS cant_cursos_equiv
                  FROM curso_ugel_x_grado a,
                       cursos             c 
                 WHERE a._id_curso_ugel = c.id_curso
                   AND a._id_grado      = ?
                   AND a.year_acad      = ?
                ORDER BY a.orden";     
        $result = $this->db->query($sql, array($idGrado, $yearAcad));
        return $result->result();
    }

    function deleteCursosxGrado($idCurso, $idGrado, $idAnio) {            
        $arrayCurso = $this->getCursosToUpdateOrden($idCurso, $idGrado, $idAnio);
        foreach ($arrayCurso as $row) {
            $nOrden = $row['orden'] - 1;
            $this->db->where ('_id_grado'          , $idGrado);
            $this->db->where ('year_acad'          , $idAnio);
            $this->db->where ('_id_curso_ugel'     , $row['_id_curso_ugel']);
            $this->db->update('curso_ugel_x_grado' , array('orden' => $nOrden));
        }

        $this->db->where('_id_curso_ugel', $idCurso);
        $this->db->where('_id_grado'     , $idGrado);
        $this->db->where('_year_acad'    , $idAnio);           
        $this->db->delete('curso_equivalencia');
        
        $this->db->where('_id_curso_ugel', $idCurso);
        $this->db->where('_id_grado'     , $idGrado);
        $this->db->where('year_acad'     , $idAnio);
        $this->db->delete('curso_ugel_x_grado');
            
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al eliminar el curso');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_DEL);       
    }

    function deleteCursoEquivalencia($idCursoEquivalencia, $idCurso, $idGrado, $idAnio ) {        
        $arrayCursoEquiv = $this->getCursosEquivToUpdateOrden($idCursoEquivalencia, $idCurso, $idGrado, $idAnio);
        foreach ($arrayCursoEquiv as $row) {
            $nOrden = $row['orden'] - 1;
            $this->db->where ('_id_grado'         , $idGrado);
            $this->db->where ('_year_acad'        , $idAnio);
            $this->db->where ('_id_curso_ugel'    , $idCurso);
            $this->db->where ('_id_curso_equiv'   , $row['_id_curso_equiv']);
            $this->db->update('curso_equivalencia', array('orden' => $nOrden));
        }
        $this->db->where('_id_curso_equiv', $idCursoEquivalencia);
        $this->db->where('_id_curso_ugel' , $idCurso);
        $this->db->where('_id_grado'      , $idGrado);
        $this->db->where('_year_acad'     , $idAnio);
        $this->db->delete('curso_equivalencia');

        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);                 
    }

    function getEquivalencia($idCursoUgel, $idGrado, $idyear){
        $sql = "SELECT INITCAP(c.desc_curso_equiv) as desc_curso_equiv,
                       a.peso,
                       a._id_curso_equiv,
                       a.orden
                  FROM curso_equivalencia  a,
		               curso_ugel_x_grado  b,
		               curso_equivalente   c
                 WHERE a._id_curso_ugel  = b._id_curso_ugel
                   AND a._id_grado       = b._id_grado
                   AND a._year_acad        = b.year_acad
                   AND a._id_curso_ugel	 = ?
                   AND a._id_grado       = ?
                   AND a._id_curso_equiv = c.id_curso_equiv
                   AND a._year_acad      = ?
              ORDER BY a.orden";
        $result = $this->db->query($sql, array($idCursoUgel,$idGrado, $idyear));
        if($result->num_rows() > 0) {
            return $result->result();
        } else {
            return null;
        }
    }

    function insertar_cursos_ugel($arrayGeneral) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            
            $this->db->trans_begin();
            foreach ($arrayGeneral as $dat) {
                $this->db->insert('curso_ugel_x_grado', $dat);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function insertar_cursosEquiv($arrayGeneral) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            
            $this->db->trans_begin();
            foreach ($arrayGeneral as $dat) {
                $this->db->insert('curso_equivalencia', $dat);
            }
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_INS;
            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getCursoACambiarOrden($idGrado, $idAnio, $orden, $idCurso, $direccion) {
        $orden = ($direccion == ORDEN_SUBIR) ? $orden - 1 : $orden + 1;
        $sql = "SELECT _id_curso_ugel
                  FROM curso_ugel_x_grado
                 WHERE _id_grado = ?
                   AND year_acad = ?
                   AND orden     = ?";
        $result = $this->db->query($sql, array($idGrado, $idAnio, $orden));
        if($result->num_rows() == 1) {
            return $result->row()->_id_curso_ugel;
        }
        return null;
    }
     
    function updateCurso_Orden($array1, $array2) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $this->db->where('_id_grado'      , $array1['_id_grado']);
            $this->db->where('year_acad'      , $array1['year_acad']);
            $this->db->where('_id_curso_ugel' , $array1['idCursoChange']);
            $this->db->update('curso_ugel_x_grado',array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCG-001)');
            }
            $this->db->where('_id_grado'      , $array2['_id_grado']);
            $this->db->where('year_acad'      , $array2['year_acad']);
            $this->db->where('_id_curso_ugel' , $array2['idCursoChange']);
            $this->db->update('curso_ugel_x_grado',array('orden' => $array2['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCG-002)');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getCursoEquivCambiarOrden($idGrado, $idAnio, $orden, $idCurso, $direccion) {
        $orden = ($direccion == ORDEN_SUBIR) ? $orden - 1 : $orden + 1 ;
        $sql = "SELECT _id_curso_equiv 
                  FROM curso_equivalencia
                 WHERE _id_grado      = ?
                   AND _year_acad     = ?
                   AND orden          = ?                  
                   AND _id_curso_ugel = ? ";
        $result = $this->db->query($sql, array($idGrado, $idAnio, $orden, $idCurso));
        if($result->num_rows() == 1) {
            return $result->row()->_id_curso_equiv;
        }
        return null;
    }

    function updateCursoEquiv_Orden($array1, $array2) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        
        $this->db->trans_begin();
        try {
            $this->db->where ('_id_grado'         , $array1['_id_grado']);
            $this->db->where ('_year_acad'        , $array1['_year_acad']);
            $this->db->where ('_id_curso_ugel'    , $array1['_id_curso_ugel']);
            $this->db->where ('_id_curso_equiv'   , $array1['idCursoEquivChange']);
            $this->db->update('curso_equivalencia', array('orden' => $array1['orden']));
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCG-003)');
            }
            $this->db->where ('_id_grado'         , $array2['_id_grado']);
            $this->db->where ('_year_acad'        , $array2['_year_acad']);
            $this->db->where ('_id_curso_ugel'    , $array2['_id_curso_ugel']);
            $this->db->where ('_id_curso_equiv'   , $array2['idCursoEquivChange']);
            $this->db->update('curso_equivalencia', array('orden' => $array2['orden'])); 
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MCG-004)');
            }
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getCursosEquivToUpdateOrden($idCursoEquivalencia, $idCurso , $idGrado, $idAnio) {
        $sql = "SELECT orden,
                       _id_curso_equiv 
                  FROM curso_equivalencia 
                 WHERE _id_curso_ugel  =".$idCurso." 
                   AND _id_grado       = ".$idGrado." 
                   AND _year_acad      = ".$idAnio." 
                   AND  orden > ( SELECT orden
                                    FROM curso_equivalencia
                                   WHERE _id_curso_equiv    = ?
                                     AND _id_curso_ugel     = ?
                                     AND _id_grado          = ?
                                     AND _year_acad         = ? ) 
                ORDER BY orden"; 
        $result = $this->db->query($sql, array($idCursoEquivalencia, $idCurso, $idGrado, $idAnio));
        return $result->result_array(); 
    }

    function getOrdenEquivMax($idCurso, $idGrado, $idAnio) {
        $sql = "SELECT COALESCE(MAX(orden), 0)  new_orden
                  FROM curso_equivalencia 
                 WHERE _id_curso_ugel = ".$idCurso."
                   AND _id_grado      = ".$idGrado."
                   AND _year_acad     = ".$idAnio;     
        $result = $this->db->query($sql);
        return $result->row()->new_orden;
    }

    function getCursosToUpdateOrden($idCurso, $idGrado, $idAnio) {
        $sql = "SELECT orden,
                       _id_curso_ugel
                  FROM curso_ugel_x_grado
                 WHERE  _id_grado      = ".$idGrado."
                   AND year_acad       = ".$idAnio."
                   AND  orden > ( SELECT orden
                                    FROM curso_ugel_x_grado
                                   WHERE _id_curso_ugel     = ".$idCurso."
                                     AND _id_grado          = ".$idGrado."
                                     AND year_acad          = ".$idAnio.")
                ORDER BY orden  ";
        $result = $this->db->query($sql, array($idGrado, $idAnio));
        return $result->result_array();
    }

    function getOrdenCursoByGradoMax($idGrado, $idAnio) {
        $sql = "SELECT COALESCE(MAX(orden), 0)  new_orden
                  FROM curso_ugel_x_grado 
                 WHERE _id_grado            = ".$idGrado."
                   AND year_acad            = ".$idAnio;
        $result = $this->db->query($sql);
        return $result->row()->new_orden;
    }

    function actualizarCursoxGrado($idCurso, $idGrado, $idAnio, $data) {
        
  
            $this->db->where ('_id_grado'         , $idGrado);
            $this->db->where ('year_acad'         , $idAnio);
            $this->db->where ('_id_curso_ugel'    , $idCurso);
            $this->db->update('curso_ugel_x_grado', $data);
    
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al actualizar el curso');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
    }

    function actualizarCursoEquiv($idCursoEquivalencia, $idCurso, $idGrado, $idAnio, $data) {
        $this->db->where ('_id_grado'         , $idGrado);
        $this->db->where ('_year_acad'        , $idAnio);
        $this->db->where ('_id_curso_ugel'    , $idCurso);
        $this->db->where ('_id_curso_equiv'   , $idCursoEquivalencia);
        $this->db->update('curso_equivalencia', $data);
    
        if($this->db->affected_rows() != 1) {
            throw new Exception('Error al actualizar el curso');
        }
        return array("error" => EXIT_SUCCESS, "msj" => MSJ_UPT);
    } 
}