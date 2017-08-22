<?php

class M_cons_eval extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function getEvaluacionesEjecutadas($idRol, $idPersona) {
        $sql = "SELECT t.*,
                       CASE WHEN t.css_hay_msjs IS NOT NULL OR t.css_hay_evid IS NOT NULL THEN 'css_pintar_tres_puntitos' ELSE NULL END AS css_tres_punt
                  FROM (SELECT e.id_evaluacion,
                               e.fecha_inicio,
                               e.fecha_fin,
                               e.fecha_evaluacion,
                               e.id_evaluador,
                               e.nota_vigesimal,
                               e.tipo_visita,
                               (SELECT CONCAT(UPPER(p2.ape_pate_pers), ' ', UPPER(p2.ape_mate_pers), ', ', INITCAP(SPLIT_PART(p2.nom_persona, ' ' , 1 ) ) )
                                  FROM persona p2 
                                 WHERE p2.nid_persona = e.id_evaluador) AS evaluador,
                               h.docente,
                               h.curso,
                               h.aula,
                               (SELECT COUNT(1) FROM sped.eval_mensaje em WHERE em.id_evaluacion = e.id_evaluacion AND em.notificacion = $idPersona) AS notificar,
                               (SELECT CASE WHEN COUNT(1) > 0 THEN 'css_hay_msjs' ELSE NULL END FROM sped.eval_mensaje em WHERE em.id_evaluacion = e.id_evaluacion)  AS css_hay_msjs,
                               (SELECT CASE WHEN COUNT(1) > 0 THEN 'css_hay_evid' ELSE NULL END FROM sped.evidencia    em WHERE id_evaluacion    = e.id_evaluacion ) AS css_hay_evid
                          FROM sped.evaluacion e,
                               (SELECT m.nid_main,
                                       p.nid_persona id_docente,
                            	       CONCAT(UPPER(p.ape_pate_pers), ' ',UPPER(p.ape_mate_pers), ', ', INITCAP(SPLIT_PART(p.nom_persona, ' ' , 1 ) ) ) AS docente,
                            	       c.desc_curso AS curso,
                            	       CONCAT(a.desc_aula,' / ',s.abvr,' - ',g.abvr,' ',n.abvr) aula
                            	  FROM main    m,
                            	       persona p,
                            	       aula    a,
                            	       sede    s,
                            	       nivel   n,
                            	       grado   g,
                                       (SELECT id_curso,
                                               desc_curso
                                          FROM cursos
                                        UNION ALL
                                        SELECT id_curso_equiv,
                                               desc_curso_equiv
                                          FROM curso_equivalente) AS c
                            	 WHERE m.nid_persona = p.nid_persona
                            	   AND m.nid_curso   = c.id_curso
                            	   AND m.nid_aula    = a.nid_aula
                            	   AND a.nid_sede    = s.nid_sede
                            	   AND a.nid_grado   = g.nid_grado
                            	   AND a.nid_nivel   = n.nid_nivel ) h
                         WHERE estado_evaluacion = '".EJECUTADO."'
                           AND (  ($idRol IN (".ID_ROL_EVALUADOR.", ".ID_ROL_SUBDIRECTOR.") AND e.id_evaluador = $idPersona  ) OR
                                  ($idRol = ".ID_ROL_DOCENTE."                               AND h.id_docente   = $idPersona ) OR
                                  ($idRol = ".ID_ROL_ADMINISTRADOR." AND 1 = 1)
                               )
                           AND e.id_horario = h.nid_main
                        ORDER BY fecha_evaluacion DESC ) AS t ";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getCriteriosEvaluacion($idEvaluacion) {
        $sql = "SELECT c.id_rubrica,
                       c.id_criterio,
                       c.desc_criterio,
                       (SELECT ROUND(SUM(valor_indi), 2)
                          FROM sped.rubri_crit_indi_deta d2 
                         WHERE d2.id_evaluacion = ? 
                           AND d2.id_rubrica    = c.id_rubrica 
                           AND d2.id_criterio   = c.id_criterio
                           AND d2.flg_no_aplica IS NULL) AS valor,
                       (SELECT MAX(valor)
            		      FROM sped.rubrica_valor_leyenda l
            		     WHERE l.id_rubrica  = c.id_rubrica
            		       AND l.id_criterio = c.id_criterio) AS max_valor
                  FROM sped.rubri_crit_indi c,
                       sped.rubri_crit_indi_deta d,
                       sped.rubricar_x_criterio rc
                 WHERE d.id_evaluacion = ?
                   AND c.id_rubrica    = d.id_rubrica
                   AND c.id_criterio   = d.id_criterio
                   AND c.id_rubrica    = rc.id_rubrica
                   AND c.id_criterio   = rc.id_criterio
                 GROUP BY c.id_rubrica, c.id_criterio, c.desc_criterio, rc.orden
                 ORDER BY rc.orden";
        $result = $this->db->query($sql, array($idEvaluacion, $idEvaluacion));
        return $result->result();
    }
    
    function getIndicadoresByCriterioEval($idEvaluacion, $idCriterio) {
        $sql = "SELECT d.id_indicador,
                       d.desc_indicador,
                       d.valor_indi,
                       d.desc_leyenda,
                       d.flg_no_aplica
                  FROM sped.rubri_crit_indi_deta d,
                       sped.rubri_crit_indi ci
                 WHERE d.id_evaluacion = ?
                   AND d.id_criterio   = ?
                   AND d.id_rubrica    = ci.id_rubrica
                   AND d.id_criterio   = ci.id_criterio
                   AND d.id_indicador  = ci.id_indicador
                 ORDER BY ci.orden";
        $result = $this->db->query($sql, array($idEvaluacion, $idCriterio));
        return $result->result();
    }
    
    function getPosiblesValoresCriterio($idEvaluacion, $idRubrica, $idCriterio) {
        $sql = "SELECT STRING_AGG(val.valor::text, ', ') vals
                  FROM (SELECT valor
                	  FROM sped.leyenda_x_evaluacion
                	 WHERE id_evaluacion = ?
                	   AND id_rubrica    = ?
                	   AND id_factor     = ?
                	   AND flg_acti      = '".FLG_ACTIVO."'
                	GROUP BY valor
                	ORDER BY valor ) val";
        $result = $this->db->query($sql, array($idEvaluacion, $idRubrica, $idCriterio));
        if($result->num_rows() == 1) {
            return $result->row()->vals;
        }
        return null;
    }
    
    function insertarMensaje($idEval, $msj) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idNotificar = null;
            $mensajeros = $this->getMensajeros($idEval);
            if($mensajeros['id_evaluador'] == _getSesion('nid_persona')) {
                $idNotificar = $mensajeros['nid_persona'];
            } else if($mensajeros['nid_persona'] == _getSesion('nid_persona')) {
                $idNotificar = $mensajeros['id_evaluador'];
            }
            $this->db->insert('sped.eval_mensaje', array("id_evaluacion"        => $idEval,
                                                         "notificacion"         => $idNotificar,
                                                         "id_remitente"         => _getSesion('nid_persona'),
                                                         "nombres_remitente"    => _getSesion('nombre_abvr'),
                                                         "msj"                  => utf8_decode($msj) ));
            if($this->db->affected_rows() == 1) {
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_INS;
            }   
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getMensajesEvaluacion($idEval, $idUsuario) {
        $sql = "SELECT id_remitente,
                       nombres_remitente,
                       TO_CHAR(fec_envio, 'DD/MM/YYYY hh12:mi:ss AM') fecha,
                       CASE WHEN ? = id_remitente THEN '"._getSesion('foto_usuario')."'
                            ELSE (SELECT CASE WHEN p2.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."',p2.foto_persona)
                                              WHEN p2.google_foto  IS NOT NULL THEN p2.google_foto
                                              ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END
                                    FROM persona p2 
                                   WHERE p2.nid_persona = id_remitente) END AS foto,
                       msj,
                       CASE WHEN ? = id_remitente THEN 'left'
                            ELSE 'OTRO' END AS position
                  FROM sped.eval_mensaje
                 WHERE id_evaluacion = ?
                 ORDER BY fec_envio ASC";
        $result = $this->db->query($sql, array($idUsuario, $idUsuario, $idEval));
        $this->db->where('id_evaluacion', $idEval);
        $this->db->where('notificacion', $idUsuario);
        $this->db->update('sped.eval_mensaje', array('notificacion' => null));
        return $result->result();
    }
    
    /**
     * Query para visualizar los mensajes desde un usuario que no es ni el evaluador o evaluado
     * por ejemplo administrador, director, promotor
     * @param $idEval
     */
    function getMensajesEvaluacionAlguienMas($idEval) {
        $sql = "SELECT TO_CHAR(tab.fec_envio, 'DD/MM/YYYY hh12:mi:ss AM') fecha,
                       tab.id_remitente,
                       tab.nombres_remitente,
                       tab.msj,
                       (SELECT CASE WHEN p2.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."', p2.foto_persona)
                        		    WHEN p2.google_foto  IS NOT NULL THEN p2.google_foto
                        		    ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END
                        	    FROM persona p2
                        	   WHERE p2.nid_persona = tab.id_remitente) AS foto,
                       CASE WHEN tab.id_remitente = tab.primero THEN 'left' ELSE 'OTRO' END AS position
                  FROM (SELECT em.*,
                    	       (SELECT em2.id_remitente
                        		  FROM sped.eval_mensaje em2
                        		 WHERE em2.id_evaluacion = ?
                        		 GROUP BY em2.id_remitente
                        		 LIMIT 1 OFFSET 0) AS primero
                	      FROM sped.eval_mensaje em
                	     WHERE em.id_evaluacion = ?
                	    ORDER by em.fec_envio ASC ) AS tab";
        $result = $this->db->query($sql, array($idEval, $idEval));
        return $result->result();
    }
    
    function getMensajeros($idEval) {
        $sql = "SELECT e.id_evaluador,
                       (SELECT nid_persona FROM main WHERE nid_main = e.id_horario)
                  FROM sped.evaluacion e
                 WHERE e.id_evaluacion = ?";
        $result = $this->db->query($sql, array($idEval));
        return $result->row_array();
    }
}