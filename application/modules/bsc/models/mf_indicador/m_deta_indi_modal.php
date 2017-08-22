<?php
class M_deta_indi_modal extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getDatosIndicadorDetalleById($idIndicadorDetalle){
        $sql = "SELECT indd.id_indicador_detalle, 
                       indd.__id_indicador,
                       indd.id_sede,
                       indd.id_nivel,
                       indd.id_grado,
                       indd.id_aula,
                       indd.tipo_regi, 
                       indd.id_disciplina,
                       indd.id_area,
                       (SELECT id.id_sede_ppu FROM bsc.indicador_detalle id WHERE id.__id_indicador = indd.__id_indicador AND tipo_regi = 'INDI' AND year = (indd.year) ),
                       (SELECT id.id_ppu      FROM bsc.indicador_detalle id WHERE id.__id_indicador = indd.__id_indicador AND tipo_regi = 'INDI' AND year = (indd.year) ),
                       (SELECT id.tipo_eai    FROM bsc.indicador_detalle id WHERE id.__id_indicador = indd.__id_indicador AND tipo_regi = 'INDI' AND year = (indd.year) )
                  FROM bsc.indicador_detalle indd 
                 WHERE indd.id_indicador_detalle = ?
                   AND indd.flg_acti             = '".FLG_ACTIVO."'
                   --AND indd.year                 = (SELECT EXTRACT(YEAR FROM now()))  ";
        $result= $this->db->query($sql,array($idIndicadorDetalle));
        $data   = $result->row_array();
        return $data;
    }
    
    function getAlumnosByAula($idAula){
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', INITCAP(p.nom_persona)) AS nombrecompleto,
                       pa.promedio_final,
                       CASE WHEN pa.promedio_final IS NOT NULL THEN
                		    (CASE WHEN pa.promedio_final < (SELECT valor_numerico
                									    FROM config
                									   WHERE id_nota     = 3
                						 AND year_config = (SELECT EXTRACT(YEAR FROM now()))) 
                		    THEN 'bg-warning' 
                		    ELSE 'bg-success' END)
	    ELSE NULL END AS color
                  FROM persona 	      p,
                       persona_x_aula pa
                 WHERE pa.flg_acti   = '1'
                   AND p.flg_acti    = '1'
                   AND p.nid_persona = pa.__id_persona
                   AND pa.__id_aula  = ?
              ORDER BY pa.promedio_final DESC";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }
    
    function getAlumnosByAulaIndicador_1($idAula){
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona) AS nombrecompleto,
                       pa.promedio_final,
                       CASE WHEN pa.promedio_final < 11 THEN 'bg-warning' ELSE 'bg-success' END as color
                  FROM persona 	      p,
                       persona_x_aula pa
                 WHERE pa.flg_acti   = '1'
                   AND p.flg_acti    = '1'
                   AND p.nid_persona = pa.__id_persona
                   AND pa.__id_aula  = ?
              ORDER BY pa.promedio_final DESC";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }
    
    function getNotaPromedioFinalIndicador(){
        $sql = "SELECT valor_numerico
                  FROM config
                 WHERE id_config = ".CONFIG_3."";
        $result= $this->db->query($sql);
        $data   = $result->row_array();
        return $data;
    }
    
    function getDatosAlumnosTercio($idGrado,$idSede){
        $sql = "SELECT * 
                  FROM (SELECT CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombrecompleto,
        		       pa.promedio_final,
        		       a.desc_aula,
        		       CASE WHEN pa.promedio_final < ( SELECT valor_numerico
        							                     FROM config
        							                    WHERE id_config = ".CONFIG_2.") THEN 'bg-warning' ELSE 'bg-success' END as color
        		 FROM persona_x_aula pa,
        		      aula           a,
        		      persona        p
        		WHERE pa.flg_acti       = '1'
        		  AND a.flg_acti        = ".FLG_ACTIVO."
        		  AND pa.year_academico = (SELECT EXTRACT(YEAR FROM now()))
        		  AND a.nid_grado       = ?
        		  AND a.nid_sede        = ?
        		  AND a.nid_aula        = pa.__id_aula
        		  AND pa.__id_persona   = p.nid_persona
        	        ORDER BY promedio_final DESC LIMIT( (SELECT COUNT(1) 
                            						       FROM persona_x_aula pa2, 
                            							        aula a2 
                            						      WHERE a2.nid_aula = pa2.__id_aula 
                            						        AND a2.nid_sede = ? 
                            							    AND a2.flg_acti     = 1 
                            							    AND a2.nid_grado    = ? ) / 3 )
        		) tercio_superior
               ORDER BY tercio_superior.promedio_final DESC";
        $result = $this->db->query($sql,array($idGrado,$idSede,$idSede,$idGrado));
        return $result->result();            
    }
    
    function getDatosAlumnosOrdenMerito($idAula){
        $sql = "SELECT * 
                  FROM (SELECT CONCAT(p.ape_pate_pers ,' ',p.ape_mate_pers , ', ' , p.nom_persona) nombrecompleto,
                	           promedio_final,DENSE_RANK() OVER(PARTITION BY __id_aula ORDER BY promedio_final DESC) as rank,
                               CASE WHEN promedio_final < (SELECT valor_numerico FROM config WHERE id_config = ".CONFIG_1.") 
                	                THEN 'bg-warning' ELSE 'bg-success' END AS color
                         FROM persona_x_aula ta,
                	          persona 	     p
                        WHERE __id_aula         = ?
                          AND ta.__id_persona   = p.nid_persona
                          AND ta.flg_acti       = '1'
                          AND year_academico = (SELECT EXTRACT(YEAR FROM now()))) todo
                 WHERE todo.rank <= 3";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }

    /* Realizado por Arturo Flores
     * fecha 19/10/2015
     * indicador 5-8
     * Estudiantes que superan el EAI Matematica/Comunicacion/Ciencias/Infomartica
     */ 
    function getAlumnosSuperanEAI($idAula, $tipoEAI){
        $materia = ($tipoEAI == EAI_MATE) ? MATEMATICA : (($tipoEAI == EAI_COMU) ? COMUNICACION : (($tipoEAI == EAI_CIEN) ? CIENCIA : (INFORMATICA) ) ) ;
        $ind_materia = ($tipoEAI == EAI_MATE) ? IND_MATEMATICA : (($tipoEAI == EAI_COMU) ? IND_COMUNICACION : (($tipoEAI == EAI_CIEN) ? IND_CIENCIA : (IND_INFORMATICA) ) ) ;
        $sql = "SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona) AS nombrecompleto,
            	       pa.".$materia." as materia, 
                	   CASE WHEN COALESCE(pa.".$materia.", 0) <= (SELECT valor_numerico
                                								    FROM config
                                								   WHERE year_config = "._YEAR_."
                                								     AND tipo_ece  = '".EAI."'
                                								     AND id_rash   = ".INICIO.") THEN 'bg-warning' 
                	        WHEN pa.".$materia." BETWEEN (SELECT valor_numerico
                            							    FROM config
                            							   WHERE year_config = "._YEAR_."
                            							     AND tipo_ece  = '".EAI."'
                            							     AND id_rash   = ".INICIO.")+1
                            							     AND 
                            							 (SELECT valor_numerico
                            							    FROM config
                            							   WHERE year_config = "._YEAR_."
                            							     AND tipo_ece = '".EAI."'
                            							     AND id_rash  = '".PROCESO."') THEN 'bg-info' ELSE 'bg-success' END AS color,   
                       CASE WHEN pa.".$ind_materia." = ".INICIO." THEN 'INICIO'
            		        WHEN pa.".$ind_materia." = '".PROCESO."' THEN 'PROCESO' 
            		        WHEN pa.".$ind_materia." IS NULL THEN NULL ELSE 'SATISFACTORIO' END AS nivel_logro	 
            	 FROM persona_x_aula  pa,
            			       persona p
            	WHERE pa.__id_aula    = ?
            	  AND pa.__id_persona = p.nid_persona
             ORDER BY pa.".$materia." ASC";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }
    
    function getAlumnosEceSuperado($idAula, $flg_materia){
        $tipo = $flg_materia == ECE_MATE ? 'MATEMATICA' : 'LECTORA';
        $sql = "SELECT valor_numerico
                  FROM config
                 WHERE tipo_ece    = ?
                   AND year_config = (SELECT EXTRACT(YEAR FROM now()))";
        $result = $this->db->query($sql,array($tipo));
        $valor = $result->row()->valor_numerico;
        if($valor == null) {
            return null;
        }
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers, ' ',p.ape_mate_pers, ',' ,p.nom_persona) AS nombrecompleto,
                       CASE WHEN pa.ind_logro_".$flg_materia." = 1 THEN '".ECE_INICIO."' 
                            WHEN pa.ind_logro_".$flg_materia." = 2 THEN '".ECE_PROCESO."'
                            WHEN pa.ind_logro_".$flg_materia." = 3 THEN '".ECE_SATISF."' ELSE NULL END AS ind_logro_lectura,
                       CASE WHEN pa.ind_logro_".$flg_materia." >= ? THEN 'bg-success' ELSE 'bg-warning' END as color
                  FROM persona        p,
                       persona_x_aula pa
                 WHERE pa.flg_acti       = '1'
                   AND pa.__id_aula      = ?
                   AND pa.__id_persona   = p.nid_persona
                   AND pa.year_academico = (SELECT EXTRACT(YEAR FROM now()))
                 ORDER BY pa.ind_logro_".$flg_materia." DESC";
        $result = $this->db->query($sql,array(intval($valor), $idAula));
        return $result->result();
    }
    
    function getAlumnosPostulantesUnivConsorcio($idSede,$idGrado,$idUniversidad){
        $sql = "SELECT todo.*,
                       ua.id_admision,
                       CASE WHEN ua.id_admision <> 0 THEN 'SI' 	       ELSE 'NO'         END AS postulo,
                       CASE WHEN ua.id_admision <> 0 THEN 'bg-success' ELSE 'bg-warning' END AS color
                  FROM (SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona ) AS nombrecompleto,
                       a.nid_aula,
                       a.desc_aula,
                       pa.year_academico
                 FROM persona          p,
                      persona_x_aula  pa,
                      aula             a
                WHERE a.nid_sede              = ?
                  AND a.nid_grado             = ?
                  AND a.flg_acti              = 1
                  AND p.flg_acti              = '".FLG_ACTIVO."'
                  AND pa.year_academico       = (SELECT EXTRACT(YEAR FROM now()))
                  AND pa.flg_acti             = '".FLG_ACTIVO."'
                  AND pa.__id_persona         = p.nid_persona
                  AND pa.__id_aula            = a.nid_aula ) todo LEFT JOIN univ_admision ua ON (todo.nid_persona    = ua.id_alumno
                                                                                             AND todo.nid_aula       = ua.id_aula
                                                                                             AND ua.__id_universidad = ?
                                                                                             AND todo.year_academico = ua.year_academico)
                 ORDER BY ua.id_admision, todo.nombrecompleto";
        $result = $this->db->query($sql,array($idSede,$idGrado,$idUniversidad));
        return $result->result();
    }
    
    function getAlumnosSimulacroPUCP($idSede, $idGrado, $idUniversidad){
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) nombrecompleto,
                       a.desc_aula,
                       simu.flg_apto,
                       CONCAT('Simulacro ', simu.nro_simulacro) num_simu,
                       CASE WHEN simu.flg_apto = 'S' THEN 'bg-success' ELSE 'bg-warning' END AS color,
                       CASE WHEN simu.flg_apto = 'S' THEN 'Sí'         ELSE 'No'         END AS postulo
                  FROM univ_simulacro simu,
                       persona           p,
                       persona_x_aula   pa,
                       aula              a
                 WHERE simu.year_academico     = (SELECT EXTRACT(YEAR FROM now()))
                   AND simu.id_sede            = ?
                   AND simu.id_grado           = ?
                   AND simu.nro_simulacro      = (SELECT MAX(nro_simulacro)
            		                                FROM univ_simulacro s
            		                               WHERE s.year_academico   = (SELECT EXTRACT(YEAR FROM now()))
                            						 AND s.id_grado         = ?
                            						 AND s.id_sede          = ?
                            						 AND s.__id_universidad = ?)
                   AND simu.__id_universidad        = ?
                   AND id_alumno               = p.nid_persona
                   AND pa.__id_persona         = p.nid_persona
                   AND pa.__id_aula            = a.nid_aula
                 ORDER BY simu.flg_apto";
        $result = $this->db->query($sql,array($idSede,$idGrado, $idGrado, $idSede, $idUniversidad, $idUniversidad));
        return $result->result();
    }
    
    function getAlumnosSimuUniv($idSede,$idGrado,$idUniversidad){
        $sql ="SELECT p.nid_persona,
               CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) AS nombrecompleto,
               a.desc_aula,simu.puntaje,
               CASE WHEN simu.puntaje::numeric >= (SELECT valor_numerico
        					     FROM config
        					    WHERE year_config = (SELECT EXTRACT(YEAR FROM now()))
        					      AND id_univ     = ?) 
              THEN 'bg-success' ELSE 'bg-warning' END AS color
              FROM  univ_simulacro simu,
                    persona           p,
                    persona_x_aula   pa,
                    aula              a
              WHERE simu.year_academico   = (SELECT EXTRACT(YEAR FROM now()))
                AND simu.id_grado         = ?
                AND simu.id_sede          = ?
                AND __id_universidad      = ?
                AND id_alumno             = p.nid_persona
                AND pa.__id_persona       = p.nid_persona
                AND pa.__id_aula          = a.nid_aula
              ORDER BY simu.puntaje DESC";
        $result = $this->db->query($sql,array($idUniversidad,$idGrado,$idSede,$idUniversidad));
        return $result->result();
    }   
    function getAllFrecuenciasByIndicador($idIndicador){
        $sql = "  SELECT fm.id_frecuencia,
                         fm.desc_frecuencia,
                         fm.fecha_medicion,
                         fm.flg_medido,
                         CASE WHEN fm.flg_medido = 'S' THEN 'Sí' 	     ELSE 'No' END AS medido,
                         CASE WHEN fm.flg_medido = 'S' THEN 'bg-success'           END AS color,
                         fm.year,
                         fm.nro_medicion,
                         fm.fecha_medido
                    FROM bsc.frecuencia_medicion fm
                   WHERE fm.year =  (SELECT EXTRACT(YEAR FROM now()))
                     AND fm.__id_indicador = ?
                ORDER BY fm.nro_medicion";
        $result = $this->db->query($sql,array($idIndicador));
        return $result->result();
    }
    
    /* Realizado por Arturo Flores
     * fecha 12/10/2015
     * indicador 24 - 28 
     * Numero de ingresantes al consorcio grado 5to secundaria
     */ 
    //PUCP
    function getAlumnosIngresantesPUCP($idSede,$idGrado,$idUniversidad){
        $sql = "SELECT todo.*, 
                       ua.id_admision,
                       CASE WHEN ua.flg_ingreso = 'S' THEN 'bg-success' ELSE 'bg-warning' END AS color,
                       CASE WHEN ua.flg_ingreso = 'N' THEN 'Sí'         ELSE 'No'         END AS ingreso
                  FROM (SELECT p.nid_persona,
                CONCAT (p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona ) AS nombrecompleto,
                       a.nid_aula,
                       a.desc_aula,
                       pa.year_academico
                  FROM persona          p,
                       persona_x_aula  pa,
                       aula             a
                 WHERE a.nid_sede              = ?
                   AND a.nid_grado             = ?
                   AND a.flg_acti              = '".FLG_ACTIVO."'
                   AND p.flg_acti              = '".FLG_ACTIVO."'
                   AND pa.year_academico       = (SELECT EXTRACT(YEAR FROM now()))
                   AND pa.flg_acti             = '".FLG_ACTIVO."'
                   AND pa.__id_persona         = p.nid_persona
                   AND pa.__id_aula            = a.nid_aula ) todo 
            INNER JOIN univ_admision ua ON (todo.nid_persona = ua.id_alumno
                   AND todo.nid_aula           = ua.id_aula
                   AND ua.__id_universidad     = ?
                   AND todo.year_academico     = ua.year_academico)
             ORDER BY ua.id_admision, todo.nombrecompleto";
        $result = $this->db->query($sql,array($idSede,$idGrado,$idUniversidad));
        return $result->result();
    }
    //CAYETANO,ULIMA,PACIFICO,UNMSM
    function getAlumnosIngresantesUnivConsorcioMenosPUCP($idSede,$idGrado,$idUniversidad){
        $sql = "SELECT todo.*, 
                       ua.id_admision,
                       ua.puntaje,
                       CASE WHEN ua.flg_ingreso = 'S'
                            THEN 'bg-success' ELSE 'bg-warning' END AS color
                  FROM (SELECT p.nid_persona,
                               CONCAT (p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona ) AS nombrecompleto,
                                       a.nid_aula,
                                       a.desc_aula,
                                       pa.year_academico
                          FROM persona          p,
                               persona_x_aula  pa,
                               aula             a
                         WHERE a.nid_sede              = ?
                           AND a.nid_grado             = ?
                           AND a.flg_acti              = '".FLG_ACTIVO."'
                           AND p.flg_acti              = '".FLG_ACTIVO."'
                           AND pa.year_academico       = (SELECT EXTRACT(YEAR FROM now()))
                           AND pa.flg_acti             = '".FLG_ACTIVO."'
                           AND pa.__id_persona         = p.nid_persona
                           AND pa.__id_aula            = a.nid_aula ) todo INNER JOIN univ_admision ua ON (todo.nid_persona = ua.id_alumno
                                                                                    			       AND todo.nid_aula           = ua.id_aula
                                                                                    			       AND ua.__id_universidad     = ?
                                                                                    			       AND todo.year_academico     = ua.year_academico)
              ORDER BY ua.id_admision, todo.nombrecompleto";
        $result = $this->db->query($sql,array($idSede, $idGrado, $idUniversidad));
        return $result->result();
    }
    //fin del indicador 24-28
    
     /* Realizado por Arturo Flores
     * fecha 12/10/2015
     * Indicador del 38-40
     *  Numero de logros y galardones obtenidos en la disciplina
     */ 
    function getLogrosGalardonesDisciplinas($idNivel,$nivelComp,$tipoDiscip,$idDisciplina){
        $sql = "SELECT d.desc_disciplina,
                       d.tipo_disciplina, 
                       dd.nivel_competitivo, 
                       dd.nro_copas,
                       dd.fecha, 
                       p.nom_persona
                FROM   disciplina_detalle dd, 
                	   disciplina d, 
                		     nivel n,
                		    persona p				
                WHERE  dd.__id_disciplina	  =   d.id_disciplina
                  AND  n.nid_nivel	          =   dd.__id_nivel
                  AND  p.nid_persona	      =   dd.__id_docente
                  AND  dd.__id_nivel          =   ?
                  AND  dd.nivel_competitivo   =   ?
                  AND  d.tipo_disciplina      =   ?
                  AND  dd.__id_disciplina     =   ?
             ORDER BY fecha DESC";
        $result = $this->db->query($sql,array($idNivel,$nivelComp,$tipoDiscip,$idDisciplina));
        return $result->result();
    }
     //fin del indicador 24-28
    
     /* Realizado por Arturo Flores
     * fecha 12/10/2015
     * Indicador del 32
     * Número de alumnos participantes en las evaluaciones para la certificación  internacional del idioma inglés 
     */ 
    function getAlumnosParticipantesEvalCertificacionIngles($idAula,$idEstado){
        $sql = "SELECT ac.nid_alumno_certificacion,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona ) as nombrecompleto,
                       ac.estado, 
                       CASE WHEN ac.estado  in ('".SOLO_PARTICIPO."', '".APROBO."') THEN 'bg-success' ELSE 'bg-warning' END AS color,
                       CASE WHEN ac.estado  != ? THEN 'Sí'         ELSE 'No'               END AS participo
                  FROM persona p,
                       persona_x_aula pa LEFT JOIN  alumno_certificacion ac ON (ac.__id_alumno = pa.__id_persona AND ac.year = (Select extract (year from now())))		
                 WHERE pa.__id_aula   = ?
                   AND p.nid_persona  = pa.__id_persona
              ORDER BY estado ASC";
        $result = $this->db->query($sql,array($idEstado,$idAula));      
        return $result->result();
    }
    //fin del indicador 32
    
    /* Realizado por Arturo Flores
     * fecha 12/10/2015
     * Indicador del 33
     * Numero de alumnos que obtienen la certificacion internacional del idioma ingles
     */
    function getAlumnosObtienenCertificacionIngles($idAula){
        $sql = "  SELECT ac.nid_alumno_certificacion,
                        CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,' ',p.nom_persona ) as nombrecompleto,
                        ac.estado,
                        CASE WHEN ac.estado  ='".APROBO."' THEN 'bg-success' ELSE 'bg-warning' END AS color,
                        CASE WHEN ac.estado  ='".APROBO."' THEN 'Sí'         ELSE 'No'            END AS participo
                   FROM persona p,
                        persona_x_aula pa LEFT JOIN  alumno_certificacion ac ON (ac.__id_alumno = pa.__id_persona AND ac.year = (Select extract (year from now())))
                  WHERE pa.__id_aula   = ?
                    AND p.nid_persona  = pa.__id_persona
                    AND ac.estado     IN ('".SOLO_PARTICIPO."', '".APROBO."')
               ORDER BY estado ASC";
        $result = $this->db->query($sql,array($idAula));
        return $result->result();
    }    
    //fin del indicador 33
    
    /* Realizado por Arturo Flores
     * fecha 12/10/2015
     * Indicador del 34 - 35
     * Número de docentes con certificación  EFCE Y ingles Nativo  en el idioma inglés (sede)
     */
    function getDocentesCertificaciónEFCE_INGLESNATIVO($idSede,$flg_certificado){
        $sql = "SELECT p.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) as nombrecompleto,
                       p.".$flg_certificado.", CASE WHEN p.".$flg_certificado." = 'S' 
                    						   THEN 'Sí' ELSE 'No' END as flg,
                    					       CASE WHEN p.".$flg_certificado." = 'S' 
                    					       THEN 'bg-success' ELSE 'bg-warning' END as color
                  FROM persona p, 
                       persona_x_rol pr
                 WHERE pr.nid_rol  = '".ID_ROL_DOCENTE."'
                   AND pr.flg_acti = '".FLG_ACTIVO."'
                   AND p.id_sede_paga = ?
                   AND pr.nid_persona = p.nid_persona 
              GROUP BY p.nid_persona, nombrecompleto ,p.flg_certi_efce
              ORDER BY flg DESC";
        $result = $this->db->query($sql,array($idSede));
        return $result->result();
    }
    //fin del indicador 34-35
    
    /* Realizado por Arturo Flores
     * fecha 13/10/2015
     * Indicador 11-13
     * Puesto alcanzado por los estudiantes de la secundaria en la evaluacion PPU Aptitud Numerica/Lectura/ciencias
     * */
    function getEvaluacionesPPU($idPPu, $idSede, $idGrado){
        $sql= "SELECT ppu.puesto,
                      pu.desc_ppu,
                      g.desc_grado
                 FROM grado g,
                      grado_ppu ppu,
                      ppu pu
                WHERE ppu.__id_grado IN (?)
                  AND pu.id_ppu       = ?
                  AND ppu.__id_sede   = ?
                  AND ppu.__id_grado  = g.nid_grado
                  AND ppu.__id_ppu    = pu.id_ppu
              GROUP BY puesto,desc_ppu,desc_grado";
        $result = $this->db->query($sql,array($idGrado, $idPPu, $idSede));
        return $result->result();
    }
    //fin del indicador 11-13
    
    function getLastFechaMedicion($idIndicador){
        $sql = "  SELECT fm.fecha_medicion
                    FROM bsc.frecuencia_medicion fm
                   WHERE fm.__id_indicador = ?
                ORDER BY fm.nro_medicion DESC  LIMIT 1";
        $result = $this->db->query($sql,array($idIndicador));
        $data   = $result->row_array();
        return $data;
    }
    
    function editFrecuencia($pk, $campo, $valor){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try{
            $data = array($campo => (($valor == null) ? null : $valor));
            $this->db->where('id_frecuencia', $pk);
            $this->db->update('bsc.frecuencia_medicion', $data);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(MDI-001)');
            }
            $rpta['error'] = EXIT_SUCCESS;
            $rpta['msj']   = MSJ_UPT;
        }catch(Exception $e){
            $rpta['msj']   = $e->getMessage();
        }
        return $rpta;
    }
    
    function getMedidasBeforeAfterByIndicador($idIndicador, $idFrecuencia){
        $sql = "SELECT (SELECT fm.fecha_medicion 
                    	  FROM bsc.frecuencia_medicion fm
                         WHERE fm.__id_indicador = ?
                           AND fm.nro_medicion = (SELECT fm.nro_medicion 
                    	             			    FROM bsc.frecuencia_medicion fm
                                                   WHERE fm.id_frecuencia = ?) -1) as fechaAntes  ,
                       (SELECT fm.fecha_medicion
                          FROM bsc.frecuencia_medicion fm
                         WHERE fm.__id_indicador = ?
                           AND fm.nro_medicion = (SELECT fm.nro_medicion 
                                                    FROM bsc.frecuencia_medicion fm
                                                   WHERE fm.id_frecuencia = ?) + 1) as fechaDespues ";
        $result= $this->db->query($sql,array($idIndicador,$idFrecuencia,$idIndicador,$idFrecuencia));
        $data   = $result->row_array();
        return $data;
    }
    
    /*Realiza por Arturo Flores
     * fecha 13/10/2015
     * Indicador 36
     * Tardanza escolar: cantidad de alumnos que llegan tarde en promedio por mes (aula)
     */
    function getTardanzaEscolarAlumnos($idAula){
        $sql= "SELECT todos.mes_nomb,CONCAT ('%',' ',ROUND((tardones.cant_tard * 100)::numeric / todos.count, 2) )AS porc_tard
                  FROM (SELECT COUNT(1),
                    	       EXTRACT(MONTH FROM date (fecha_asistencia)) mes,
                    	       to_char(fecha_asistencia, 'TMMonth') mes_nomb
                    	  FROM asistencia asis
                    	 WHERE __id_aula        = ?
                    	   AND __year_academico = (SELECT EXTRACT(YEAR FROM now()))
                      GROUP BY EXTRACT(MONTH FROM date (fecha_asistencia)), to_char(fecha_asistencia, 'TMMonth')
                      ORDER BY EXTRACT(MONTH FROM date (fecha_asistencia) ) )todos
                  LEFT JOIN
                	(SELECT COUNT(1) cant_tard,
                    		EXTRACT(MONTH FROM date (fecha_asistencia)) mes,
                    		to_char(fecha_asistencia, 'TMMonth') mes_nomb
                       FROM asistencia
                      WHERE __id_aula        = ?
                        AND __year_academico = (SELECT EXTRACT(YEAR FROM now()))
                    	AND estado = '".TARDE."'
                      GROUP BY EXTRACT(MONTH FROM date (fecha_asistencia) ), to_char(fecha_asistencia, 'TMMonth')
                      ORDER BY EXTRACT(MONTH FROM date (fecha_asistencia) ) ) tardones
                   ON (todos.mes = tardones.mes)";
        $result = $this->db->query($sql,array($idAula,$idAula));
        return $result->result();
    }
    //fin del indicador 36
    
    function getNotasDocentesSD_Indicador_58($idSede, $idNivel, $idArea){
        $sql= "SELECT * FROM bsc.fun_get_docentes_sd_notas_detalle("._YEAR_."::integer, ?, ?, ?)";
        $result = $this->db->query($sql,array(intval($idSede), intval($idNivel), intval($idArea)));
        return $result->result();
    }
    /*Realiza por Arturo Flores
     * fecha 22/10/2015
     * Indicador Detalle
     * Nombre de la persona o personas responsables de un indicador
     */
    /*ARREGLAR*/
    function getPersonaResponsableIndicador($idIndicador){
        $sql= "SELECT CONCAT(p.ape_pate_pers ,' ',p.ape_mate_pers , ', ' , p.nom_persona) nombrecompleto
                 FROM indicador_responsable ir,
            	      persona p
                WHERE ir.__id_persona = p.nid_persona
            	  AND ir.__id_indicador = ?
             GROUP BY nombrecompleto
             ORDER BY nombrecompleto";
        $result = $this->db->query($sql,array($idIndicador));
        return $result->result();
    }
    
    function getVacantesBySedeAreaEstado($idSede, $idArea, $idAreaEsp, $estado){
        $filtroEstado = null;
        if($estado != null){
            $filtroEstado = "AND estado = '".$estado."'"; 
        }
        $sql= " SELECT nombres_solicitante,
                       to_char(fec_regi,'DD/MM/YYYY HH12:MI AM') fec_regi,
                       to_char(fec_fin,'DD/MM/YYYY HH12:MI AM') fec_fin,
                       estado,
                       nombres_usua_atencion,
                       CASE WHEN estado = 'CONTRATADO' then 'bg-success' ELSE NULL END AS color,
                       (SELECT ROUND(AVG(v.fec_fin::timestamp::date - v.fec_regi::timestamp::date),0) promatencion
                          FROM vacante v 
                         WHERE id_sede            = ?
                           AND id_area            = ?
                           AND id_area_especifica = ?)
                 FROM  vacante
                 WHERE id_sede            = ?
                 AND   id_area            = ?
                 AND   id_area_especifica = ?
                 AND   (SELECT EXTRACT(YEAR FROM date(fec_regi))) = "._YEAR_;
        $result = $this->db->query($sql,array($idSede,$idArea,$idAreaEsp,$idSede,$idArea,$idAreaEsp));
        return $result->result();
    }
    
    function getCapacitacionesBySedeArea($idSede,$idArea,$idAreaEsp){
        $sql = "SELECT desc_capacitacion,
                       TO_CHAR(fec_programada,'DD/MM/YYYY HH12:MI AM') fec_programada,
                       TO_CHAR(fec_realizada,'DD/MM/YYYY HH12:MI AM') fec_realizada,
                       CASE WHEN fec_realizada IS NOT NULL THEN 'bg-success' ELSE 'bg-warning' END AS color,
                       observaciones,
                       estado,
                       audi_pers_regi
                  FROM capacitacion
                 WHERE id_sede            = ?
                   AND id_area            = ?
                   AND id_area_especifica = ?";
        $result = $this->db->query($sql,array($idSede,$idArea,$idAreaEsp));
        return $result->result();
    }
    
    function getIncidenciasBySedeArea($idSede,$idArea,$idAreaEsp,$tipoInci,$idIndicador){
        $filtroCheck = null;
        /*if($idIndicador == INDICADOR_101) {
            $filtroCheck = "AND i.flg_checkbox = '".FLG_ACTIVO."'";    
        }*/
        $sql = "SELECT i.desc_incidencia,
                       ct.desc_combo,
                       to_char(i.fecha_incidencia,'DD/MM/YYYY') fecha_incidencia,
                       i.nombres_personal,
                       CASE WHEN i.flg_checkbox = '1' THEN 'bg-success' 
                				                      ELSE 'bg-warning' 
                       END AS color,
                       i.audi_pers_regi
                  FROM incidencia_laboral i,
                       combo_tipo ct
                 WHERE i.__id_sede            = ?
                   AND i.__id_area            = ?
                   AND i.__id_area_especifica = ?
                   AND ct.grupo               = ".GRUPO_INCIDENCIA."	
                   AND ct.valor               = ? 
                   $filtroCheck
                   AND i.tipo_incidencia      = ct.valor";
        $result = $this->db->query($sql,array($idSede,$idArea,$idAreaEsp,$tipoInci));
        return $result->result();
    }
    
    function getPuntualidad($idSede, $idAreaGen, $idAreaEsp, $year) {
        $sql = "SELECT nif,
                       persona,
                       CASE WHEN activo = '1' THEN 'bg-success' ELSE 'bg-warning' END AS clase_activo,
                       id_calendario,
                       id_horario,
                       temprano AS detalle,
                       todos
                  FROM rrhh.fun_get_puntualidad(?,?,?)
                 WHERE area_especifica = ?
              ORDER BY persona";
        $result = $this->db->query($sql,array($idSede, $idAreaGen, $year, $idAreaEsp));
        return $result->result();
    }
    
    function getAsistencia($idSede, $idAreaGen, $idAreaEsp, $year) {
        $sql = "SELECT nif,
                       persona,
                       CASE WHEN activo = '1' THEN 'bg-success' ELSE 'bg-warning' END AS clase_activo,
                       id_calendario,
                       id_horario,
                       (dias_labo - faltas) AS detalle,
                       dias_labo AS todos
                  FROM rrhh.fun_get_asistencia(?,?,?)
                 WHERE area_especifica = ?
              ORDER BY persona";
        $result = $this->db->query($sql,array($idSede, $idAreaGen, $year, $idAreaEsp));
        return $result->result();
    }
    
    function getDetalleAsistencia_Puntualidad($nif, $year) {
        $nif = '0'.$nif;
        $sql = "SELECT * FROM rrhh.fun_get_detalle_puntua_asist(?, ?)";
        $result = $this->db->query($sql, array($nif, $year));
        return $result->result();
    }
}