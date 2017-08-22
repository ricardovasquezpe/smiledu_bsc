<?php

class M_agenda extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->model('m_evaluar');
        $this->load->model('m_utils');
    }
    
    function updateDatosEvent($arrayUpdate,$id) {
        try{
            $this->db->where('id_evaluacion', $id);
            $this->db->update('sped.evaluacion', $arrayUpdate);
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = MSJ_UPT;
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function getHorariosByDocenteCurso($condicion, $texto, $idSedeSubDirector) {
        $sql = "SELECT *
                  FROM (SELECT m.nid_main,
                    	       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ', INITCAP(SPLIT_PART( p.nom_persona, ' ' , 1 )) ) AS docente,
                    	       INITCAP(c.desc_curso) AS curso,
                    	       CONCAT(INITCAP(a.desc_aula),' / ',g.abvr,' ',n.abvr,' / ',s.abvr) aula
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
                	 WHERE m.estado      = '".FLG_ACTIVO."'
                	   AND s.nid_sede    = ?
                	   AND p.flg_acti    = '".FLG_ACTIVO."'
                	   AND m.nid_persona = p.nid_persona
                	   AND m.nid_curso   = c.id_curso
                	   AND m.nid_aula    = a.nid_aula
                	   AND a.nid_sede    = s.nid_sede
                	   AND a.nid_grado   = g.nid_grado
                	   AND a.nid_nivel   = n.nid_nivel ) AS newhorario
                  WHERE ( ( ? = 'D' AND UPPER(newhorario.docente) LIKE UPPER(?)) OR ( ? = 'C' AND UPPER(newhorario.curso) LIKE UPPER(?)) OR ( ? = 'A' AND UPPER(newhorario.aula) LIKE UPPER(?)) )";
        $result = $this->db->query($sql, array($idSedeSubDirector, $condicion, '%'.$texto.'%', $condicion, '%'.$texto.'%', $condicion, '%'.$texto.'%'));
        return $result->result();
    }
    
    function getEvaluacionesCalendario($idEvaluador) {
        $sql = "SELECT e.id_evaluacion     AS id,
            	       CONCAT((CAST(EXTRACT(epoch FROM e.fecha_inicio at time zone 'edt') AS INTEGER)), '000') AS start,
                       CASE WHEN e.estado_evaluacion = '".PENDIENTE."'      THEN '".EVT_COLR_PEND_INFO_CELESTE."'
                            WHEN e.estado_evaluacion = '".EJECUTADO."'      THEN '".EVT_COLR_EJEC_SUCC_VERDE."'
                            WHEN e.estado_evaluacion = '".NO_EJECUTADO."'   THEN '".EVT_COLR_NOEJ_WARN_AMBAR."'
                            WHEN e.estado_evaluacion = '".POR_JUSTIFICAR."' THEN '".EVT_COLR_POJU_INVE_NEGRO."'
                            WHEN e.estado_evaluacion = '".JUSTIFICADO."'    THEN '".EVT_COLR_JUST_SPEC_MORADO."'
                            WHEN e.estado_evaluacion = '".INJUSTIFICADO."'  THEN '".EVT_COLR_PEND_INJU_ROJO."'
                            ELSE NULL END AS class,
                       (SELECT CONCAT(e.estado_evaluacion,' - ',
                                      INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1),'.' ,
                                      ' - ', TO_CHAR(e.fecha_inicio, 'hh12:mi AM'))
                          FROM persona p
                         WHERE p.nid_persona = (SELECT m.nid_persona
                                                  FROM main m
                                                 WHERE m.nid_main = e.id_horario) ) AS title
                  FROM sped.evaluacion e
                 WHERE e.id_evaluador = ?" ;
        $result = $this->db->query($sql, array($idEvaluador));
        return $result->result_array();
    }
    
    function grabarNuevaEvaluacion($insert, $idPersona, $idRol, $fecha) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('sped.evaluacion' , $insert);
            if($this->db->affected_rows() == 1) {
                $data['id'] = $this->db->insert_id();
                //Leyendas_x_evaluacion
                $sql = "INSERT INTO sped.leyenda_x_evaluacion (id_evaluacion, id_rubrica, id_factor  , id_subfactor, valor, orden, leyenda, color_radio_button, flg_acti)
                           SELECT ?            , id_rubrica, id_criterio, id_indicador, valor, orden, leyenda, color_radio_button, '".FLG_ACTIVO."'
                             FROM sped.rubrica_valor_leyenda
                            WHERE id_rubrica = ?";
                $result = $this->db->query($sql, array($data['id'], $insert['id_rubrica']));
                if($result != 1) {
                    throw new Exception('(MA-002)');
                }
                //
                //$this->insertMongoEvaluadorSedeArea($data['id'], $idPersona, $idRol);
                if($insert['tipo_visita'] == VISITA_OPINADA) {
                    //Enviar correo de notificacion del docente que sera evaluado
                    $datosCorreo = $this->m_evaluar->getDatosAlFinalizar($data['id']);
                    $body = $this->armarBodyCorreoNotificacionOpinada($datosCorreo);
                    //__enviarEmail($datosCorreo['destino'], 'Te van a visitar :) (No responder)', $body);
                    $datosInsert = array(
                        'correos_destino' => $datosCorreo['destino'],
                        'asunto'          => 'Te van a visitar :) (No responder)',
                        'body'            => $body,
                        'sistema'         => 'SD');
                    $this->m_utils->insertarEnviarCorreo($datosInsert);
                } else if($insert['tipo_visita'] == VISITA_SEMI_OPINADA) {
                    $datosCorreo = $this->m_evaluar->getDatosAlFinalizar($data['id']);
                    $datosInsert = array('correos_destino'         => $datosCorreo['destino'],
                                         'asunto'                  => 'Te van a visitar :) (No responder)',
                                         'body'                    => $this->armarBodyCorreoNotificacionOpinada($datosCorreo),
                                         'fecha_envio_semiopinada' => $fecha,
                                         'sistema'                 => 'SD');
                    $this->m_utils->insertarEnviarCorreo($datosInsert);
                }
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = MSJ_INS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function insertEnviarCorreo($datosInsert) {
        $this->db->insert('envio_correo', $datosInsert);
    }
    
    function evaluaCruceHoras($fechaInicio,$fechaFin) {
        $sql = "SELECT COUNT(1) AS campo
                  FROM sped.evaluacion
                 WHERE (fecha_inicio, fecha_fin)
              OVERLAPS (TIMESTAMP ?, TIMESTAMP ?)";
        $result = $this->db->query($sql,array($fechaInicio,$fechaFin));
        return ($result->row()->campo);
    }
    
    function validarEvaluacion($idEvaluacion, $idPersona) {
        $sql = "SELECT COUNT(1) cnt
                  FROM sped.evaluacion
                 WHERE id_evaluacion     = ?
                   AND estado_evaluacion IN ('".PENDIENTE."', '".NO_EJECUTADO."')
                   AND id_evaluador      = ? ";
        $result = $this->db->query($sql, array($idEvaluacion, $idPersona));
        if($result->row()->cnt == 1) {
            return true;
        }
        return false;
    }
    
    function borrarEvaluacion($idEvaluacion, $idUserSess) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        $this->db->trans_begin();
        try {
            $estadoEvaluacion = $this->m_utils->getById('sped.evaluacion', 'estado_evaluacion', 'id_evaluacion', $idEvaluacion);
            if($estadoEvaluacion != PENDIENTE) {
                throw new Exception('(MA-003)');
            }
            $idEvaluador = $this->m_utils->getById('sped.evaluacion', 'id_evaluador', 'id_evaluacion', $idEvaluacion);
            if($idEvaluador != $idUserSess) {//NO SE PUEDE BORRAR LA EVALUACION DE ALGUIEN MAS
                throw new Exception(ANP);
            }
            //BORRAR sped.leyenda_x_evaluacion
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->delete('sped.leyenda_x_evaluacion');
            //BORRAR sped.rubri_crit_indi_deta
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->delete('sped.rubri_crit_indi_deta');
            //BORRAR sped.evaluacion
            $this->db->where('id_evaluacion', $idEvaluacion);
            $this->db->delete('sped.evaluacion');
            
            $this->resetSerialEvaluacion();
            
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
            $data['msj']   = 'Se borr&oacute; la visita :O';
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function resetSerialEvaluacion() {
        $resetearSequenceSQL = "SELECT SETVAL('sped.evaluacion_id_evaluacion_seq', COALESCE(MAX(id_evaluacion), 1) ) FROM sped.evaluacion";
        $this->db->query($resetearSequenceSQL);
        return true;
    }
    
    function getIdRubricaEvaluar() {
        $sql = "SELECT nid_ficha id_rubrica
                  FROM sped.rubrica
                 WHERE flg_acti = '".ACTIVO_."'
                  -- AND (CASE WHEN tipo_rubrica = 'E' THEN ".ID_ROL_EVALUADOR." ELSE ".ID_ROL_SUBDIRECTOR." END) = ?
                 LIMIT 1";
        $result = $this->db->query($sql,array($this->session->userdata('id_rol')));
        if($result->num_rows() == 1) {
            return $result->row()->id_rubrica;
        }
        return null;
    }
    
    function getRubricaFromEvaluacion($idEvaluacion) {
        $sql = "SELECT e.id_rubrica
                  FROM sped.evaluacion e
                 WHERE e.id_evaluacion = ?
                   AND e.estado_evaluacion IN ('".PENDIENTE."', '".NO_EJECUTADO."')
                   AND (SELECT 1
                          FROM sped.rubrica r
                         WHERE r.nid_ficha = e.id_rubrica
                           /*AND r.flg_acti  = '".ACTIVO_."'*/ LIMIT 1) = 1";
        $result = $this->db->query($sql,array($idEvaluacion));
        if($result->num_rows() == 1) {
            return $result->row()->id_rubrica;
        }
        return null;
    }
    
    function armarBodyCorreoNotificacionOpinada($datosCorreo) {
        $nombreRol = $this->m_utils->getById('rol', 'desc_rol', 'nid_rol', _getSesion(SPED_ROL_SESS));
        $html = '<table style="border-collapse:collapse;width:500px" width="500" cellpadding="0" cellspacing="0">
                     <tbody>
                         <tr>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:middle;background-color:#000000">
                                 <table style="width:100%;border-collapse:collapse">
                                     <tbody>
                                         <tr>
                                             <td width="140px" style="width:140px;padding: 5px" align="left">
                                                 <img alt="Smiledu" src="'.RUTA_SMILEDU.'public/general/img/menu/logo-smiledu.png" border="0" style="border:0px;max-height:40px; float:left;" class="CToWUd">
                                                 <label style="color:#ffffff;font-size:24px; padding-top: 5px; padding-left: 5px; float:left;">Smiledu</label>
                                             </td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </td>
                             <td width="20" style="width:20px;background-color:#000000;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                         </tr>
                         <tr>
                             <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 0px 5px">&nbsp;</td>
                             <td align="center" valign="middle" style="text-align:center;vertical-align:top;background-color:#fafafa;font-size:14px;color:#333333">
                                 <br>
                                 <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;font-size:19px">
                                     <p>Hola <b>'.$datosCorreo['docente'].'</b></p>
                                 </div>
                                 <br>
                                     El día '.$datosCorreo['fec_visita'].' será visitad@ por <u>'._getSesion('nombre_abvr').'</u>: ('.$nombreRol.')
                                 <br><br>
                                 <div>
                                     <table style="border-collapse:collapse;width:460px">
                                         <tbody>
                                             <tr>
                                                 <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                     <b>Curso</b>
                                                     <br>
                                                     <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['curso'].'</div>
                                                 </td>
                                                 <td style="text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:14px;background-color:#eeeeee;vertical-align:middle;padding:15px 0;width:50%" valign="middle">
                                                     <b>Aula</b>
                                                     <br>
                                                     <div style="margin:0px;padding:0px;font-family:inherit;font-size:inherit;text-align:center;width:100%">'.$datosCorreo['aula'].'<br>'.$datosCorreo['grado_nivel_sede'].'</div>
                                                 </td>
                                             </tr>
                                         </tbody>
                                     </table>
                                     <br>
                                 </div>
                             <div style="font-size:11px">
                                 <p>Ingresa a la plataforma <a href="'.RUTA_SMILEDU.'" target="_blank">SmilEDU</a>, con tu usuario: '.$datosCorreo['usuario'].' y tu respectiva clave. </p>
                             </div>
                         </td>
                         <td width="20" style="width:20px;background-color:#fafafa;line-height:10px;font-size:10px;border-radius:0px 0px 5px 0px">&nbsp;</td>
                     </tr>
                     <tr>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:5px 0px 0px 0px">&nbsp;</td>
                         <td align="center" valign="middle" style="text-align:center;vertical-align:middle;font-size:10px;color:#888888"><br>
                             Si no tienes tu usuario/clave obtenlo en el link de "Recuperar contraseña" en la pantalla de inicio o comunícate con el Administrador de la plataforma
                         </td>
                         <td width="20" style="width:20px;line-height:10px;font-size:10px;border-radius:0px 5px 0px 0px">&nbsp;</td>
                     </tr>
                 </tbody>
             </table>';
        return '<div align="center" style="text-align:center;"><center>'.$html.'</center></div>';
    }
    
    function insertMongoEvaluadorSedeArea($idEvaluacion, $idEvaluador, $idRol) {
        $m = new MongoClient(MONGO_CONEXION);
        $db = $m->selectDB(SMILEDU_MONGO);
        $data['msj']    = null;
        //EVALUA SI INSERTA UN NUEVO DOCUMENTO O AGREGA UN ARRAY DENTRO DE UN DOCUMENTO
        $sqlCount = 'db.rubrica_evaluadores.find({
                    	id_evaluador : '.$idEvaluador.',
                    	mes 	     : '.date("m").',
                    	year		 : '.date("Y").'
                    }).count()';
        $resultCount = $db->execute('return '.$sqlCount);
        $cond = $resultCount['retval'];
        $evaluacionData = $this->getDataEvaluacion($idEvaluacion);
        if(count($evaluacionData) == 0) {
            throw new Exception('No se pudo registrar la evaluación');
        }
        /* id_area   : '.$evaluacionData['id_area'].',
           desc_area : "'.utf8_encode($evaluacionData['desc_area']).'",  */
        if($idRol == ID_ROL_EVALUADOR || $idRol == ID_ROL_SUBDIRECTOR) {
            $array = '{
                          id_evaluacion  : '.$idEvaluacion.',
                          fec_eval       : "'.$evaluacionData['fecha_inicio'].'",
                          id_docente     : '.$evaluacionData['nid_persona'].',
                          nombre_docente : "'.utf8_encode($evaluacionData['docente']).'",
                          id_sede        : '.$evaluacionData['nid_sede'].',
                          abvr_sede      : "'.$evaluacionData['sede'].'",
                          id_nivel       : '.$evaluacionData['nid_nivel'].',
                          abvr_nivel     : "'.$evaluacionData['nivel'].'",
                          id_aula        : '.$evaluacionData['nid_aula'].',
                          desc_aula      : "'.utf8_encode($evaluacionData['desc_aula']).'",
                          estado         : "'.PENDIENTE.'"
                      }';
        }
        if($cond == INSERTA) {
            $sql = 'db.rubrica_evaluadores.insert({
                    id_evaluador     : '.$idEvaluador.',
                    rol				 : '.$idRol.',
                	nombre_evaluador : "'.utf8_encode(_getSesion('nombre_abvr')).'",
                	mes              : '.date('m').',
                	year             : '.date('Y').',
                	"array_evas"     :
                	         [ '.$array.' ]
            })';
        } else if($cond == ACTUALIZA) {
            $sql = 'db.rubrica_evaluadores.update(
                        { id_evaluador : '.$idEvaluador.' , mes : '.date("m").' , year : '.date("Y").'},
                        { $push : {
                                      array_evas : '.$array.'
                                  }
                        }
                    )';
        }
        $val = 'return '.$sql;
        $result = $db->execute($val);
        return $result;
    }
    
    function getDataEvaluacion($idEvaluacion){
        $sql = "SELECT m.nid_persona,
                       CONCAT(p.ape_pate_pers,' ',p.ape_mate_pers,', ',p.nom_persona) as docente,
                       s.nid_sede,
                       s.desc_sede,
                       s.abvr sede,
                       n.nid_nivel,
                       n.abvr nivel,
                       a.nid_aula,
                       a.desc_aula,
                       e.estado_evaluacion,
                       e.fecha_inicio::date fecha_inicio
                  FROM main       m,
                       persona    p,
                       aula       a,
                       sede       s,
                       nivel      n,
                       grado      g,
                       sped.evaluacion e,
                       (SELECT id_curso,
                    	       desc_curso
                    	  FROM cursos
                    	UNION ALL
                    	SELECT id_curso_equiv,
                    	       desc_curso_equiv
                    	  FROM curso_equivalente) AS c
                 WHERE m.estado             = '".FLG_ACTIVO."'
                   AND e.id_evaluacion      = ?
                   AND m.nid_main           = e.id_horario
                   AND m.nid_persona        = p.nid_persona
                   AND m.nid_curso          = c.id_curso
                   AND m.nid_aula           = a.nid_aula
                   AND a.nid_sede           = s.nid_sede
                   AND a.nid_grado          = g.nid_grado
                   AND a.nid_nivel          = n.nid_nivel LIMIT 1";
        $result = $this->db->query($sql, array($idEvaluacion));
        return $result->row_array();
    }
    
    function getDataDocente_A_Evaluar($idMain) {
        $sql = "SELECT pd.id_persona,
                       pd.id_sede_control,
                       pd.id_nivel_control,
                       pd.id_area_especifica,
                       CASE WHEN p.correo_inst IS NOT NULL THEN p.correo_inst
                            WHEN p.correo_admi IS NOT NULL THEN p.correo_admi
                            ELSE NULL END AS destino,
                       CONCAT(INITCAP(SPLIT_PART(p.nom_persona, ' ', 1)),' ',p.ape_pate_pers,' ',SUBSTRING(p.ape_mate_pers,1, 1)) AS docente
                  FROM rrhh.personal_detalle pd,
                       persona               p
                 WHERE pd.id_persona = (SELECT nid_persona FROM main WHERE nid_main = ? )
                   AND pd.id_persona = p.nid_persona";
        $result = $this->db->query($sql, array($idMain));
        return $result->row_array();
    }
}