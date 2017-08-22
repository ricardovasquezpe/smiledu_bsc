<?php

class M_persona extends CI_Model
{
    function __construct(){
		parent::__construct();
	}
	/*
	function getProfesoresCursosByAula($idAula){
		$sql = "SELECT INITCAP(CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ')) AS apellidos,
		               INITCAP(CONCAT(p.nom_persona)) AS nombres,
		               c.desc_curso,
             CASE WHEN p.dni IS NOT NULL THEN p.dni
                  ELSE '-' END AS dni,
	         CASE WHEN a.desc_aula IS NOT NULL THEN a.desc_aula
                  ELSE '-' END AS desc_aula,
             CASE WHEN s.desc_sede IS NOT NULL THEN s.desc_sede
                  ELSE '-' END AS desc_sede,
	         CASE WHEN g.abvr IS NOT NULL THEN CONCAT(g.abvr,' ',n.abvr)
                  ELSE '-' END AS desc_grado,
             CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
                  ELSE 'foto_perfil_default.png' END AS foto_persona
			      FROM main     m
		     LEFT JOIN persona p
				    ON m.nid_persona = p.nid_persona
		     LEFT JOIN cursos c
				    ON m.nid_curso   = c.id_curso
		     LEFT JOIN persona_x_aula pa
				    ON p.nid_persona = pa.__id_persona
		     LEFT JOIN aula a
				    ON a.nid_aula    = m.nid_aula
		     LEFT JOIN sede s
				    ON a.nid_sede    = s.nid_sede
		     LEFT JOIN nivel n
				    ON a.nid_nivel   = n.nid_nivel
		     LEFT JOIN grado g
				    ON a.nid_grado   = g.nid_grado
		         WHERE m.nid_aula    = ?
		      ORDER BY CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona)";
		
		$result = $this->db->query($sql, array($idAula));
		return $result->result();
	}
	
	function insertDocenteAulaCurso($data){
	    $rpt['error']    = EXIT_ERROR;
	    $rpt['msj']      = MSJ_ERROR;
	    try{
	        $this->db->insert('sima.profesor_aula_curso',$data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('(MP-001)');
	        }
	        $rpt['error']    = EXIT_SUCCESS;
	        $rpt['msj']      = MSJ_INSERT_SUCCESS;
	    }catch(Exception $e){
	        $rpt['msj'] = $e->getMessage();
	    }
	    return $rpt;
	}*/
}