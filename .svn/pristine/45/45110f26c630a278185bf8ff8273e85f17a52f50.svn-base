<?php
//LAST CODE 004
class M_evento extends  CI_Model{
    function __construct(){
        parent::__construct();
    }

    function getAllEventos($tipoEvento = null, $yearEvento = null, $estadoEvento = null, $nombreEvento = null){
        $sql = "SELECT desc_evento,
                       fecha_realizar,
                       fecha_registro,
                       hora_inicio,
                       hora_fin,
                       estado,
                       id_evento,
                       ENCODE(ENCRYPT(e.id_evento::text::bytea, 'softhysac', 'aes'), 'hex') AS id_evento_crypt,
                       tipo_evento,
                       id_evento_enlazado,
                       (SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona) as creador_completo
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona, ' ', 1)) as creador
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento   = e.id_evento
                           AND i.opcion      = ".OPCION_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistira,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") talvez,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") no_asistira,
                        (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_ASISTIRA.") asistira_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ.") talvez_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA.") no_asistira_total,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.asistencia = ".ASISTENCIA_CONTACTO."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistencia,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.") ) invitados,
                        (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") prosp_grado,
                        (SELECT COUNT(1)
                           FROM admision.evento
                          WHERE id_evento_enlazado = e.id_evento) cant_eventos_enlazados,
                        (SELECT INITCAP(desc_combo) as desc
                           FROM combo_tipo
                          WHERE grupo = ".COMBO_TIPO_EVENTO."
                            AND valor = tipo_evento::CHARACTER VARYING) as tipo_evento_desc,
                        CASE WHEN fecha_realizar = now()::date THEN 1
                             ELSE 0 END AS hoy,
                        '0' as flg_toma_asistencia,
                        (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona,
                        (SELECT google_foto
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona_google,
                        (SELECT COUNT(1) 
                          FROM admision.recurso_x_evento  re,
                               admision.persona_x_recurso pr
                         WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                           AND re.id_evento = e.id_evento) cant_colab
                  FROM admision.evento e
                 WHERE CASE WHEN ? IS NOT NULL THEN e.tipo_evento = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN EXTRACT(year from e.fecha_realizar) = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN e.estado = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(e.desc_evento)) like UNACCENT(UPPER(?))
                            ELSE  1 = 1 END           
                  ORDER BY orden, CASE WHEN id_evento_enlazado IS NULL THEN fecha_realizar
                                       ELSE NULL END ASC";
        $result = $this->db->query($sql, array($tipoEvento, $tipoEvento, $yearEvento, $yearEvento, $estadoEvento, $estadoEvento, "%".$nombreEvento."%", "%".$nombreEvento."%"));
        return $result->result();
    }
      
    function getAllEventosBySede($idSede, $idPersona, $tipoEvento = null, $yearEvento = null, $estadoEvento = null, $nombreEvento = null){
        $sql = "SELECT e.desc_evento,
                       e.fecha_realizar,
                       e.fecha_registro,
                       e.hora_inicio,
                       e.hora_fin,
                       e.estado,
                       e.id_evento,
                       ENCODE(ENCRYPT(e.id_evento::text::bytea, 'softhysac', 'aes'), 'hex') AS id_evento_crypt,
                       e.tipo_evento,
                       e.id_evento_enlazado,
                       e.id_persona_encargada,
                       e.id_persona_registro,
                       (SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona) as creador_completo
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona, ' ', 1)) as creador
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistira,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") talvez,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND  i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") no_asistira,
                        (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_ASISTIRA.") asistira_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ.") talvez_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA.") no_asistira_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.asistencia = ".ASISTENCIA_CONTACTO."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistencia,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.") ) invitados,
                        (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")) prosp_grado,
                        (SELECT COUNT(1)
                           FROM admision.evento
                          WHERE id_evento_enlazado = e.id_evento) cant_eventos_enlazados,
                        (SELECT INITCAP(desc_combo) as desc
                           FROM combo_tipo
                          WHERE grupo = ".COMBO_TIPO_EVENTO."
                            AND valor = tipo_evento::CHARACTER VARYING) as tipo_evento_desc,
                        CASE WHEN fecha_realizar = now()::date THEN 1
                             ELSE 0 END AS hoy,
                        '0' as flg_toma_asistencia,
                        (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona,
                        (SELECT google_foto
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona_google,
                        (SELECT COUNT(1) 
                          FROM admision.recurso_x_evento  re,
                               admision.persona_x_recurso pr
                         WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                           AND re.id_evento = e.id_evento) cant_colab
                  FROM admision.evento e LEFT JOIN admision.recurso_x_evento re ON e.id_evento = re.id_evento
                       LEFT JOIN admision.persona_x_recurso pr ON re.id_recurso_x_evento = pr.id_recurso_x_evento
                 WHERE (e.id_sede_realizar     = ? OR
                        e.id_persona_encargada = ? OR
                        e.id_evento IN (SELECT id_evento
                                          FROM admision.recurso_x_evento
                                         WHERE id_sede = ?) OR
                        e.id_evento IN (SELECT id_evento
                                          FROM admision.ruta_tour
                                         WHERE id_sede = ?) OR
                        e.id_persona_registro = ? OR
                        pr.id_persona = ? OR 
                        re.id_responsable = ?)
                   AND CASE WHEN ? IS NOT NULL THEN e.tipo_evento = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN EXTRACT(year from e.fecha_realizar) = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN e.estado = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(e.desc_evento)) like UNACCENT(UPPER(?))
                           ELSE  1 = 1 END             
              ORDER BY orden";
        $result = $this->db->query($sql, array($idSede, $idPersona, $idSede, $idSede, $idPersona, $idPersona, $idPersona, $tipoEvento, $tipoEvento, $yearEvento, $yearEvento, $estadoEvento, $estadoEvento, "%".$nombreEvento."%", "%".$nombreEvento."%"));
        return $result->result();
    }
    
    function getAllEventosByPersona($idPersona, $tipoEvento = null, $yearEvento = null, $estadoEvento = null, $nombreEvento = null){
        $sql = "SELECT e.desc_evento,
                       e.fecha_realizar,
                       e.fecha_registro,
                       e.hora_inicio,
                       e.hora_fin,
                       e.estado,
                       e.id_evento,
                       ENCODE(ENCRYPT(e.id_evento::text::bytea, 'softhysac', 'aes'), 'hex') AS id_evento_crypt,
                       e.tipo_evento,
                       e.id_evento_enlazado,
                       re.id_recurso,
                       (SELECT CONCAT(p.ape_pate_pers, ' ', p.ape_mate_pers, ', ', p.nom_persona) as creador_completo
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT CONCAT(p.ape_pate_pers, ', ', split_part(p.nom_persona, ' ', 1)) as creador
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro),
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistira,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") talvez,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") no_asistira,
                        (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_ASISTIRA.") asistira_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_TALVEZ.") talvez_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.opcion = ".OPCION_NO_ASISTIRA.") no_asistira_total,
                       (SELECT COUNT(1)
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.asistencia = ".ASISTENCIA_CONTACTO."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE.") asistencia,
                       (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.") ) invitados,
                        (SELECT COUNT(1) 
                          FROM admision.invitados i,
                               admision.contacto c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.id_evento = e.id_evento
                           AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                           AND c.flg_estudiante = ".FLG_ESTUDIANTE."
                           AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")) prosp_grado,
                       (SELECT COUNT(1)
                           FROM admision.evento
                          WHERE id_evento_enlazado = e.id_evento) cant_eventos_enlazados,
                       (SELECT INITCAP(desc_combo) as desc
                           FROM combo_tipo
                          WHERE grupo = ".COMBO_TIPO_EVENTO."
                            AND valor = tipo_evento::CHARACTER VARYING) as tipo_evento_desc,
                        CASE WHEN fecha_realizar = now()::date THEN 1
                             ELSE 0 END AS hoy,
                        re.flg_toma_asistencia,
                        (SELECT CASE WHEN p.foto_persona IS NOT NULL THEN p.foto_persona
	                                 ELSE 'nouser.svg' END AS foto_persona
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona,
                        (SELECT google_foto
                          FROM persona p
                         WHERE p.nid_persona = e.id_persona_registro) foto_persona_google,
                        (SELECT COUNT(1) 
                          FROM admision.recurso_x_evento  re,
                               admision.persona_x_recurso pr
                         WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                           AND re.id_evento = e.id_evento) cant_colab
                  FROM admision.evento e,
                       admision.recurso_x_evento re LEFT JOIN
                       admision.persona_x_recurso pr ON re.id_recurso_x_evento = pr.id_recurso_x_evento
                 WHERE e.id_evento = re.id_evento
                   AND (pr.id_persona = ? OR re.id_responsable = ?)
                   AND CASE WHEN ? IS NOT NULL THEN e.tipo_evento = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN EXTRACT(year from e.fecha_realizar) = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN e.estado = ?
                            ELSE  1 = 1 END
                   AND CASE WHEN ? IS NOT NULL THEN UNACCENT(UPPER(e.desc_evento)) like UNACCENT(UPPER(?))                              
                            ELSE  1 = 1 END                                                    
              ORDER BY orden";
        $result = $this->db->query($sql, array($idPersona, $idPersona, $tipoEvento, $tipoEvento, $yearEvento, $yearEvento, $estadoEvento, $estadoEvento, $nombreEvento, "%".$nombreEvento."%"));
        return $result->result();
    }
    
    function getTourCampanaActual(){
        $sql = "SELECT desc_evento,
                       fecha_realizar,
                       id_evento
                  FROM admision.evento e
                 WHERE (e.tipo_evento = ".TIPO_EVENTO_TOUR." OR e.tipo_evento = ".TIPO_EVENTO_CHARLA.")  
                   AND EXTRACT(year from e.fecha_realizar) = EXTRACT(year from now()) 
                   AND estado != '".EVENTO_ANULADO."'
              ORDER BY orden";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function insertEvento($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.evento", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-001)');
            }
            $rpt['idEvento'] = $this->db->insert_id();
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function countEventosFecha($fecha, $idEvento = null){
        $sql = "SELECT COUNT(1) AS count
	              FROM admision.evento
	             WHERE fecha_realizar = ?
                   AND CASE WHEN ? IS NOT NULL THEN id_evento <> ? 
                            ELSE 1 = 1 END";
        $result = $this->db->query($sql, array($fecha, $idEvento, $idEvento));
        return $result->row()->count;
    }
    
    function countEventosEnlazados($idTour){
        $sql = "SELECT COUNT(1) AS count
	              FROM admision.evento
	             WHERE id_evento_enlazado = ?";
        $result = $this->db->query($sql, array($idTour));
        return $result->row()->count;
    }
    
    function updateEvento($arrayUpdate, $idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_evento", $idEvento);
            $this->db->update("admision.evento", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(ME-002)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function updateEventosEnlazados($arrayUpdate, $idEventoEnlazado){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_evento_enlazado", $idEventoEnlazado);
            $this->db->update("admision.evento", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(ME-002)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getDetalleEvento($idEvento){
         $sql="SELECT desc_evento,
                    fecha_realizar,
                    hora_inicio,
                    hora_fin,
                    id_persona_encargada,
                    observacion
               FROM admision.evento
              WHERE id_evento = ?";
        $result = $this->db->query($sql,array($idEvento));
        return $result->row_array();
    }
    
    function getListaAsistenciaDRA($idEvento, $busqueda = null){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                       CASE WHEN i.asistencia = '1' THEN 'checked'
                            ELSE '' END AS asistencia,
                       CASE WHEN parentesco IS NOT NULL THEN c.parentesco
                            ELSE '0' END AS parentesco,
                       CASE WHEN parentesco IS NOT NULL THEN '20px'
                            ELSE '0px' END AS margen,
                       CASE WHEN parentesco IS NULL THEN '700'
                            ELSE '0' END AS font,
                       c.cod_grupo,
                       (SELECT CONCAT('FAMILIA ',UPPER(c.ape_paterno),' ',UPPER(c.ape_materno))
                           FROM admision.contacto c1
                          WHERE c.cod_grupo    = c1.cod_grupo
                            AND flg_estudiante = 1
                          LIMIT 1) apellidofamilia,
                      CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN '(POSTULANTE)'
                           ELSE '' END AS desc_post
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento   = ?
                   AND i.id_contacto = c.id_contacto
                   AND CASE WHEN ? IS NOT NULL THEN UNACCENT(CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',UPPER(c.nombres))) LIKE UNACCENT(UPPER(?))
                            ELSE 1 = 1 END
                   AND CASE WHEN ? IS NULL THEN i.asistencia = ".INASISTENCIA_CONTACTO."
                            ELSE 1 = 1 END
              ORDER BY c.cod_grupo DESC, apellidofamilia";
        $result = $this->db->query($sql, array($idEvento, $busqueda, '%'.$busqueda.'%', $busqueda));
        
        return $result->result();
    }
    
    function getListaAsistenciaTour($idEvento, $busqueda = null){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                       CASE WHEN i.asistencia = '1' THEN 'checked'
                            ELSE '' END AS asistencia,
                       CASE WHEN parentesco IS NOT NULL THEN c.parentesco
                            ELSE '0' END AS parentesco,
                       CASE WHEN parentesco IS NOT NULL THEN '20px'
                            ELSE '0px' END AS margen,
                       CASE WHEN parentesco IS NULL THEN '700'
                            ELSE '0' END AS font,
                       c.cod_grupo,
                       (SELECT CONCAT('FAMILIA ',UPPER(c.ape_paterno),' ',UPPER(c.ape_materno))
                           FROM admision.contacto c1
                          WHERE c.cod_grupo    = c1.cod_grupo
                            AND flg_estudiante = 1
                          LIMIT 1) apellidofamilia,
                      CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN '(POSTULANTE)'
                           ELSE '' END AS desc_post
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento   = ?
                   AND i.id_contacto = c.id_contacto
                   AND CASE WHEN ? IS NOT NULL THEN UNACCENT(CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',UPPER(c.nombres))) LIKE UNACCENT(UPPER(?))
                            ELSE 1 = 1 END
                   AND CASE WHEN ? IS NULL THEN i.asistencia = ".INASISTENCIA_CONTACTO."
                            ELSE 1 = 1 END
              ORDER BY c.cod_grupo DESC, apellidofamilia";
        $result = $this->db->query($sql, array($idEvento, $busqueda, '%'.$busqueda.'%', $busqueda));
    
        return $result->result();
    }
    
    function getListaContactoBusqueda($busqueda, $idEvento){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                        c.cod_grupo,
                       (SELECT CONCAT('FAMILIA ',UPPER(c.ape_paterno),' ',UPPER(c.ape_materno))
                           FROM admision.contacto c1
                          WHERE c.cod_grupo    = c1.cod_grupo
                            AND flg_estudiante = 1
                          LIMIT 1) apellidofamilia,
                       CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN '(POSTULANTE)'
                           ELSE '' END AS desc_post,
                       flg_estudiante
                  FROM admision.contacto c
                 WHERE c.id_contacto NOT IN (SELECT i.id_contacto 
                                               FROM admision.invitados i
                                              WHERE i.id_evento = ?)
                   AND  UNACCENT(UPPER(CONCAT(c.ape_paterno,' ',c.ape_materno,', ',c.nombres))) LIKE UNACCENT(UPPER(?))
              ORDER BY c.cod_grupo DESC";
        $result = $this->db->query($sql, array($idEvento, "%".$busqueda."%"));
        return $result->result();
    }
    
    function updateAsistenciaInvitado($idInvitado, $idEvento, $arrayUpdate){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where("id_evento", $idEvento);
            $this->db->where("id_contacto", $idInvitado);
            $this->db->update("admision.invitados", $arrayUpdate);
            if($this->db->affected_rows() != 1){
                throw new Exception('(ME-003)');
            }
            $rpt['error']    = EXIT_SUCCESS;
            $rpt['msj']      = MSJ_UPT;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function countAsistentesEvento($idEvento){
        $sql = "SELECT CONCAT(COUNT(1), '/', (SELECT COUNT(1)
                                                FROM admision.invitados i2,
                                                     admision.contacto c2
                                               WHERE (i2.opcion = ".OPCION_ASISTIRA." 
                                                  OR  i2.opcion = ".OPCION_TALVEZ.")
                                                 AND c2.id_contacto = i2.id_contacto
                                                 AND i2.id_evento = ?)) AS count
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento    = ?
                   AND c.id_contacto = i.id_contacto
                   AND i.asistencia   = ".ASISTENCIA_CONTACTO;
        $result = $this->db->query($sql, array($idEvento, $idEvento));
        return $result->row()->count;
    }
    
    function insertContactoInvitado($arrayInsert){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->insert("admision.invitados", $arrayInsert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_INS;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function getListaInvitadosOpcion($idEvento, $opcion){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                       CASE WHEN flg_estudiante = ".FLG_ESTUDIANTE." THEN 'POSTULANTE'
                            ELSE (SELECT UPPER(desc_combo)
                                    FROM combo_tipo
                                   WHERE grupo = ".COMBO_PARENTEZCO."
                                     AND valor = parentesco::CHARACTER VARYING ) END AS parentesco,
                        CASE WHEN (c.telefono_celular IS NOT NULL) 
                                       THEN (c.telefono_celular)
                                       ELSE '-'
                        END AS telefono_celular,
                        CASE WHEN(c.correo IS NOT NULL)
                                       THEN(c.correo)
                                       ELSE '-'
                        END AS correo, 
                        (SELECT CONCAT('FAMILIA ',UPPER(c.ape_paterno),' ',UPPER(c.ape_materno))
                           FROM admision.contacto c1
                          WHERE c.cod_grupo    = c1.cod_grupo
                            AND flg_estudiante = 1
                          LIMIT 1) apellidofamilia,       
                        (SELECT COUNT(1)
                           FROM admision.invitados i1,
                                admision.contacto  c1
                          WHERE i1.id_contacto    = c1.id_contacto
                            AND c1.flg_estudiante = ".FLG_ESTUDIANTE."
                            AND i1.id_evento   = ?
                            AND CASE WHEN ? <> 0 THEN i1.opcion = ?
                                ELSE i1.flg_asistencia_directa  = ".ASISTENCIA_INVITACION_CONTACTO." END
                            AND c1.cod_grupo      = c.cod_grupo) cantidad_post,      
                        CASE WHEN (c.sede_interes IS NOT NULL AND c.sede_interes <> 0) THEN (SELECT desc_sede
                                                                                              FROM sede
                                                                                             WHERE nid_sede = c.sede_interes) 
                              WHEN c.flg_estudiante = ".FLG_FAMILIAR." THEN '-'
                              ELSE 'Por definir' END AS desc_sede_interes,
                        c.cod_grupo
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento   = ?
                   AND CASE WHEN ? <> 0 THEN i.opcion = ?
                            ELSE i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO." END
                   AND i.id_contacto = c.id_contacto
              ORDER BY c.cod_grupo DESC, c.parentesco DESC";
        $result = $this->db->query($sql, array($idEvento, $opcion, $opcion, $idEvento, $opcion, $opcion));
        return $result->result();
    }
    
    function getListaInvitadosAsistieron($idEvento ,$persona){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       CASE WHEN i.flg_asistencia_directa  = 1 THEN 'S&Iacute;'
                            ELSE 'NO' END AS flg_asistencia_directa,
                       i.hora_llegada,
                       c.id_contacto,
                       c.cod_grupo,
                       CASE WHEN flg_estudiante = ".FLG_ESTUDIANTE." THEN 'ESTUDIANTE'
                            ELSE (SELECT UPPER(desc_combo)
                                    FROM combo_tipo
                                   WHERE grupo = ".COMBO_PARENTEZCO."
                                     AND valor = parentesco::CHARACTER VARYING ) END AS parentesco,
                       (SELECT CONCAT('FAMILIA ',UPPER(c.ape_paterno),' ',UPPER(c.ape_materno))
                           FROM admision.contacto c1
                          WHERE c.cod_grupo    = c1.cod_grupo
                            AND flg_estudiante = 1
                          LIMIT 1) apellidofamilia
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE ((UNACCENT(UPPER(c.ape_paterno)) like UNACCENT(UPPER(?))) OR (UNACCENT(UPPER(c.ape_materno)) like UNACCENT(UPPER(?))) OR (UNACCENT(UPPER(c.nombres)) like UNACCENT(UPPER(?))))
                   AND i.id_evento = COALESCE(?, i.id_evento)
                   AND i.asistencia = ".ASISTENCIA_CONTACTO."
                   AND i.id_contacto = c.id_contacto
                 ORDER BY c.cod_grupo DESC, i.hora_llegada";
        $result = $this->db->query($sql, array('%'.$persona.'%', '%'.$persona.'%', '%'.$persona.'%', $idEvento));
        return $result->result();
    }
    
    function getHoraEvento($idEvento){
        $sql="SELECT fecha_realizar,
                    hora_inicio,
                    hora_fin
               FROM admision.evento
              WHERE id_evento = ?";
        $result = $this->db->query($sql,array($idEvento));
        return $result->row_array();
    }
    
    function getYearsFromEventos(){
        $sql="SELECT EXTRACT(year from fecha_realizar) as year
                FROM admision.evento
            GROUP BY EXTRACT(year from fecha_realizar)
            ORDER BY EXTRACT(year from fecha_realizar) ASC";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function isResponsableAsistencia($idPersona, $idEvento){
        $sql = "SELECT string_agg(re.flg_toma_asistencia::CHARACTER VARYING,',') as asist
                  FROM admision.evento e,
                       admision.recurso_x_evento re,
                       admision.persona_x_recurso pr
                 WHERE e.id_evento            = re.id_evento
                   AND re.id_recurso_x_evento = pr.id_recurso_x_evento
                   AND e.id_evento            = ?
                   AND pr.id_persona          = ?";
        $result = $this->db->query($sql, array($idEvento, $idPersona));
        return $result->row()->asist;
    }
    
    function getHijosByTipo($opcion, $idEvento){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                        c.telefono_celular,
                        c.correo,
              CASE WHEN (c.nivel_ingreso IS NOT NULL AND c.nivel_ingreso <> 0) THEN (SELECT desc_nivel
                                                                                       FROM nivel
                                                                                      WHERE nid_nivel = c.nivel_ingreso) 
                              ELSE 'Por definir' END AS desc_nivel_ingreso,
              CASE WHEN (c.grado_ingreso IS NOT NULL AND c.grado_ingreso <> 0) THEN (SELECT desc_grado
                                                                                       FROM grado
                                                                                      WHERE nid_grado = c.grado_ingreso) 
                              ELSE 'Por definir' END AS desc_grado_ingreso
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento      = ?
                   AND i.id_contacto    = c.id_contacto
                   AND c.flg_estudiante = ".FLG_ESTUDIANTE."
              ORDER BY c.nivel_ingreso,c.grado_ingreso, nombrecompleto";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function buscarEventoMain($texto = null){
        $sql = "SELECT  id_evento,
                        INITCAP(e.desc_evento) desc_evento,
                        fecha_realizar,
                        hora_inicio,
                        hora_fin,
                        tipo_evento,
                        (SELECT COUNT(1) 
                           FROM admision.recurso_x_evento  re,
                                admision.persona_x_recurso pr
                          WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                            AND re.id_evento = e.id_evento) AS cant_colab,
                        (SELECT COUNT(1) 
                           FROM admision.invitados  i,
                                admision.contacto c
                          WHERE i.id_evento = e.id_evento
			                AND	i.id_contacto = c.id_contacto) AS cant_invitados,
                        CASE WHEN fecha_realizar < now()::date THEN 1
                           ELSE 0 END AS fecha_pasada
                    FROM admision.evento e
                   WHERE (UNACCENT(UPPER(e.desc_evento)) LIKE UNACCENT(UPPER(?))
                      OR TO_CHAR(e.fecha_realizar,'dd/mm/yyyy') LIKE ?)
                     AND e.estado = 'PENDIENTE'
                ORDER BY fecha_realizar DESC";                
        $result = $this->db->query($sql, array("%".$texto."%", "%".$texto."%"));
        return $result->result();    
    }
    
    function buscarEventoBySedeMain($idPersona, $idSede, $texto = null){
        $sql = "SELECT  e.id_evento,
                        INITCAP(e.desc_evento) desc_evento,
                        e.fecha_realizar,
                        e.hora_inicio,
                        e.hora_fin,
                        e.estado,
                        e.tipo_evento,
                        (SELECT COUNT(1) 
                           FROM admision.recurso_x_evento  re,
                                admision.persona_x_recurso pr
                          WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                            AND re.id_evento = e.id_evento) AS cant_colab,
                        (SELECT COUNT(1) 
                           FROM admision.invitados  i,
                                admision.contacto c
                          WHERE i.id_evento = e.id_evento
			                AND	i.id_contacto = c.id_contacto) AS cant_invitados,
			          CASE WHEN fecha_realizar < now()::date THEN 1
                           ELSE 0 END AS fecha_pasada
                           FROM admision.evento e 
                      LEFT JOIN admision.recurso_x_evento re ON e.id_evento = re.id_evento
                      LEFT JOIN admision.persona_x_recurso pr ON re.id_recurso_x_evento = pr.id_recurso_x_evento
                          WHERE (e.id_sede_realizar     = ? OR
                                e.id_persona_encargada = ? OR
                                e.id_evento IN (SELECT id_evento
                                FROM admision.recurso_x_evento
                                WHERE id_sede = ?) OR
                                e.id_evento IN (SELECT id_evento
                                FROM admision.ruta_tour
                                WHERE id_sede = ?) OR
                                e.id_persona_registro = ? OR
                                pr.id_persona = ? OR
                                re.id_responsable = ?)
                            AND (UNACCENT(UPPER(e.desc_evento)) like UNACCENT(UPPER(?))
                             OR TO_CHAR(e.fecha_realizar,'dd/mm/yyyy') LIKE ?)
                            AND estado = '".EVENTO_PENDIENTE."'
                       ORDER BY fecha_realizar DESC ";
        $result = $this->db->query($sql, array($idSede, $idPersona, $idSede, $idSede, $idPersona, $idPersona, $idPersona, "%".$texto."%", "%".$texto."%"));
        return $result->result();                            
    }
    function getPostulantesGrados($idEvento){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       COALESCE(grado_ingreso, 0) AS grado_ingreso,
                       COALESCE((SELECT desc_grado
                                   FROM grado
                                  WHERE nid_grado = grado_ingreso), 'SIN DEFINIR') AS desc_grado,
                       COALESCE((SELECT n.abvr
                                  FROM grado g,
                                       nivel n
                                 WHERE nid_grado  = grado_ingreso
                                   AND g.id_nivel = n.nid_nivel), 'SIN DEFINIR') AS desc_nivel,
                       (SELECT COUNT(1)
                          FROM admision.invitados i1,
                               admision.contacto  c1
                         WHERE i1.id_evento     = ?
                           AND c1.grado_ingreso = c.grado_ingreso
                           AND i1.id_contacto   = c1.id_contacto) AS cant_grado,
                       (SELECT hora_cita
                          FROM admision.horario_evaluacion he
                         WHERE he.id_evento = ?
                           AND i.id_hora_cita = he.correlativo) AS horario
                  FROM admision.invitados i,
                       admision.contacto  c
                 WHERE i.id_evento              = ?
                   AND i.flg_asistencia_directa = ".ASISTENCIA_INVITACION_CONTACTO."
                   AND i.opcion IN (".OPCION_ASISTIRA.", ".OPCION_TALVEZ.")
                   AND i.id_contacto            = c.id_contacto
                   AND c.flg_estudiante         = ".FLG_ESTUDIANTE."
                ORDER BY grado_ingreso";
        $result = $this->db->query($sql, array($idEvento, $idEvento, $idEvento));
        return $result->result();
    }
  
    
    function validateconfig_eval_completo(){
        $sql = "SELECT 
                    (SELECT COUNT(1)
                       FROM admision.config_eval) AS cantidad,
                    (SELECT COUNT(1)
                       FROM admision.config_eval
                      WHERE indicadores IS NOT NULL
                        AND opciones_eval IS NOT NULL) AS cant_term";
        $result = $this->db->query($sql);
        $res = $result->row_array();
        if($res['cantidad'] == $res['cant_term']){
            return true;
        }else{
            return false;
        }
    }
    
    function getCountPostulantesAsistentesEventoByGrupo($idEvento, $codGrupo){
        $sql = "SELECT COUNT(1) AS count
	              FROM admision.invitados i,
                       admision.contacto  c
                 WHERE i.id_contacto    = c.id_contacto
                   AND i.id_evento      = ?
                   AND c.cod_grupo      = ?
                   AND c.flg_estudiante = ".FLG_ESTUDIANTE."
                   AND i.asistencia     = ".ASISTENCIA_CONTACTO;
        $result = $this->db->query($sql, array($idEvento, $codGrupo));
        return $result->row()->count;
    }
    
    function getFamiliaAsistenciaEvento($idEvento, $codGrupo){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                       CASE WHEN i.asistencia = '1' THEN 'checked'
                            ELSE '' END AS asistencia,
                       flg_estudiante,
                      CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN '(POSTULANTE)'
                           ELSE '' END AS desc_post
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_evento      = ?
                   AND i.id_contacto    = c.id_contacto
                   AND c.cod_grupo      = ?
              ORDER BY c.flg_estudiante DESC";
        $result = $this->db->query($sql, array($idEvento, $codGrupo));
        return $result->result();
    }
    
    function getEventosByPersonaFiltro($idPersona, $texto){
        $sql = "SELECT  e.id_evento,
                        INITCAP(e.desc_evento) desc_evento,
                        e.fecha_realizar,
                        e.hora_inicio,
                        e.hora_fin,
                        e.tipo_evento,
                        (SELECT COUNT(1) 
                           FROM admision.recurso_x_evento  re,
                                admision.persona_x_recurso pr
                          WHERE re.id_recurso_x_evento = pr.id_recurso_x_evento
                            AND re.id_evento = e.id_evento) AS cant_colab,
                        (SELECT COUNT(1) 
                           FROM admision.invitados  i,
                                admision.contacto c
                          WHERE i.id_evento = e.id_evento
			                AND	i.id_contacto = c.id_contacto) AS cant_invitados,
                      CASE WHEN fecha_realizar < now()::date THEN 1
                           ELSE 0 END AS fecha_pasada
                           FROM admision.evento e,
                                admision.recurso_x_evento re LEFT JOIN
                                admision.persona_x_recurso pr ON re.id_recurso_x_evento = pr.id_recurso_x_evento
                          WHERE e.id_evento = re.id_evento
                            AND (pr.id_persona = ? OR re.id_responsable = ?)
                            AND (UNACCENT(UPPER(e.desc_evento)) LIKE UNACCENT(UPPER(?))            
                             OR TO_CHAR(e.fecha_realizar,'dd/mm/yyyy') LIKE ?)
                            AND estado = '".EVENTO_PENDIENTE."'
                       GROUP BY e.id_evento
                       ORDER BY fecha_realizar DESC";
        $result = $this->db->query($sql, array($idPersona, $idPersona, "%".$texto."%", "%".$texto."%"));
        return $result->result();
    }
    function deleteEvento($idEvento){
        $rpt['error']    = EXIT_ERROR;
        $rpt['msj']      = MSJ_ERROR;
        try{
            $this->db->where('id_evento', $idEvento);
            $this->db->delete("admision.evento");
            if($this->db->affected_rows() != 1) {
                throw new Exception('(ME-004)');
            }
            $rpt['error'] = EXIT_SUCCESS;
            $rpt['msj']   = MSJ_DEL;
        }catch(Exception $e){
            $rpt['msj'] = $e->getMessage();
        }
        return $rpt;
    }
    
    function countInvitadorEvento($idEvento){
        $sql = "SELECT COUNT(1) AS count
	              FROM admision.invitados i,
                       admision.contacto  c
                 WHERE i.id_contacto    = c.id_contacto
                   AND i.id_evento      = ?";
        $result = $this->db->query($sql, array($idEvento));
        return $result->row()->count;
    }
    
    function countConfiguracion($idEvento){
        $sql = "SELECT COUNT(1)
                  FROM admision.recurso_x_evento  re
                 WHERE re.id_evento = ?";
        $result = $this->db->query($sql, array($idEvento));
        return $result->row()->count;
    }
    
    function getInfoSubidrectorBySede($idSede){
        $sql = "SELECT CONCAT(INITCAP(p.ape_pate_pers),', ',INITCAP(p.nom_persona)) nombrecompleto,
                       CASE WHEN correo_inst IS NOT NULL THEN correo_inst
                            WHEN correo_admi IS NOT NULL THEN correo_admi
                            ELSE correo_pers END AS correo
                  FROM persona p INNER JOIN rrhh.personal_detalle pd ON id_persona = p.nid_persona,
                       persona_x_rol pr,
                       sede s
                 WHERE p.nid_persona = pr.nid_persona
                   AND s.nid_sede    = ?
                   AND s.nid_sede = pd.id_sede_control
                   AND pr.nid_rol = ".ID_ROL_SUBDIRECTOR."
                 LIMIT 1";
        $result = $this->db->query($sql,array($idSede));
        return $result->row_array();
    }
    
    function getInfoDetalleEvento($idEvento){
        $sql="SELECT INITCAP(desc_evento) desc_evento,
                    id_evento,
                    orden,
                    fecha_realizar,
                    hora_inicio,
                    hora_fin,
                    estado
               FROM admision.evento
              WHERE id_evento_enlazado = ?
           ORDER BY  desc_evento";
        $result = $this->db->query($sql,array($idEvento));
        return $result->result();
    }

    function getEvaluacionesByTour($idEvento){
        $sql = "SELECT desc_evento,
                       fecha_realizar,
                       id_evento
                  FROM admision.evento e
                 WHERE (e.tipo_evento = ".TIPO_EVENTO_EVALUACION.")  
                   AND EXTRACT(year from e.fecha_realizar) = EXTRACT(year from now()) 
                   AND estado                             != '".EVENTO_ANULADO."'
                   AND id_evento_enlazado                  = ?
              ORDER BY orden";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function validateContactoInvitacionEvento($idEvento, $idContacto){
        $sql = "SELECT COUNT(1) AS count
	              FROM admision.invitados i
                 WHERE i.id_contacto    = ?
                   AND i.id_evento      = ?";
        $result = $this->db->query($sql, array($idContacto, $idEvento));
        return $result->row()->count;
    }
    
    function getHorariosByEvento($idEvento){
        $sql = "SELECT hora_cita,
                       desc_hora_cita,
                       correlativo
                  FROM admision.horario_evaluacion
                 WHERE id_evento = ?
              ORDER BY hora_cita";
        $result = $this->db->query($sql, array($idEvento));
        return $result->result();
    }
    
    function getAsistentesByEvento() {
        $sql = "";
        $result = $this->db->query($sql, array(null));
        return $result->result();
    }
    
    function nombreCompletoContacto($idContacto){
        $sql = "SELECT CONCAT(INITCAP(c.ape_paterno),', ',INITCAP(c.nombres)) nombrecompleto
                  FROM admision.contacto c 
                 WHERE c.id_contacto = ?";
        $result = $this->db->query($sql,array($idContacto));
        return $result->row()->nombrecompleto;
    }
    
    function validateCountPartEventoParEst($grupo, $evento){
        $sql = 'SELECT (SELECT COUNT(*) 
                          FROM admision.invitados i,
                               admision.contacto  c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.asistencia  = '.ASISTENCIA_CONTACTO.'
                           AND i.id_evento   = ?
                           AND c.cod_grupo   = ?
                           AND c.flg_estudiante = '.FLG_FAMILIAR.') AS countpadres,
                        (SELECT COUNT(*) 
                          FROM admision.invitados i,
                               admision.contacto  c
                         WHERE i.id_contacto = c.id_contacto
                           AND i.asistencia  = '.ASISTENCIA_CONTACTO.'
                           AND i.id_evento   = ?
                           AND c.cod_grupo   = ?
                           AND c.flg_estudiante = '.FLG_ESTUDIANTE.') AS countpost';
        $result = $this->db->query($sql,array($evento, $grupo, $evento, $grupo));
        return $result->row_array();
    }
    
    function getFamiliaEvento($idEvento, $codGrupo){
        $sql = "SELECT CONCAT(UPPER(c.ape_paterno),' ',UPPER(c.ape_materno),', ',INITCAP(c.nombres)) nombrecompleto,
                       c.id_contacto,
                       flg_estudiante,
                       '' AS asistencia,
                      CASE WHEN c.flg_estudiante = ".FLG_ESTUDIANTE." THEN '(POSTULANTE)'
                           ELSE '' END AS desc_post
                  FROM admision.invitados i,
                       admision.contacto c
                 WHERE i.id_contacto    = c.id_contacto
                   AND c.cod_grupo      = ?
                   AND c.id_contacto NOT IN (SELECT i.id_contacto 
                                               FROM admision.invitados i
                                              WHERE i.id_evento = ?)
              GROUP BY c.id_contacto
              ORDER BY c.flg_estudiante DESC";
        $result = $this->db->query($sql, array($codGrupo, $idEvento));
        return $result->result();
    }
    
    function validateCantParPostEvento($codGrupo, $idEvento){
        $sql="SELECT  (
                SELECT COUNT(*)
                FROM admision.contacto c,
                     admision.invitados i
                 WHERE c.id_contacto = i.id_contacto
                   AND c.cod_grupo = ?
                   AND i.id_evento = ?
                   AND i.asistencia = ".ASISTENCIA_CONTACTO."
                   AND flg_estudiante = ".FLG_ESTUDIANTE."
                ) AS count_post,
                (
                SELECT COUNT(*)
                FROM admision.contacto c,
                     admision.invitados i
                 WHERE c.id_contacto = i.id_contacto
                   AND c.cod_grupo = ?
                   AND i.id_evento = ?
                   AND i.asistencia = ".ASISTENCIA_CONTACTO."
                   AND flg_estudiante = ".FLG_FAMILIAR."
                ) AS count_par";
        $result = $this->db->query($sql,array($codGrupo, $idEvento, $codGrupo, $idEvento));
        return $result->row_array();
    }
    
    function getEvaluacionesPendientes(){
        $sql = "SELECT desc_evento,
                       fecha_realizar,
                       id_evento
                  FROM admision.evento e
                 WHERE (e.tipo_evento = ".TIPO_EVENTO_EVALUACION." OR e.tipo_evento = ".TIPO_EVENTO_EVALUACION_VERANO.")
                   AND e.fecha_realizar >= now()
                   AND estado           != '".EVENTO_ANULADO."'
              ORDER BY orden";
        $result = $this->db->query($sql);
        return $result->result();
    }
    function getdetalleColaborador($idEvento){
        $sql="SELECT DISTINCT CONCAT(INITCAP(p.ape_pate_pers),' ',INITCAP(p.ape_mate_pers)) apellidocompleto,
			                  INITCAP(p.nom_persona) nombre,
                              p.nid_persona,
                              p.fec_naci,
		                      p.telf_pers
                         FROM admision.recurso_x_evento  re,
                              admision.persona_x_recurso pr,
                              admision.evento e,
                              persona p
                        WHERE re.id_evento = e.id_evento
                          AND re.id_recurso_x_evento = pr.id_recurso_x_evento
                          AND p.nid_persona = pr.id_persona
                          AND e.id_evento = ? ";
        $result = $this->db->query($sql,array($idEvento));
        return $result->result();
    }
    function getdetalleInvitado($idEvento){
        $sql="SELECT DISTINCT CONCAT(INITCAP(c.ape_paterno),' ',INITCAP(c.ape_materno)) apellidocompleto,
                              INITCAP(c.nombres) nombres,
                              c.id_contacto,
		                      c.fecha_nacimiento,
		                      c.telefono_celular
                         FROM admision.invitados  i,
                              admision.contacto c,
                              admision.evento e
                        WHERE i.id_evento = e.id_evento
			              AND i.id_contacto = c.id_contacto
			              AND e.id_evento = ?";
        $result = $this->db->query($sql,array($idEvento));
        return $result->result();
    }
}