<?php

class M_compromisos extends  CI_Model {
    function __construct(){
        parent::__construct();
    }
    
    function getAulasAllSede($id_sede) {
        $sql = "SELECT a.nid_aula,
                       a.desc_aula
                  FROM public.sede s
            INNER JOIN public.aula a ON  a.flg_acti  = ".FLG_ACTIVO." and s.nid_sede = a.nid_sede 
                 WHERE s.nid_sede = ?
              ORDER BY a.desc_aula   ";
        $result = $this->db->query($sql,array($id_sede));
        $arryAulas = array();
        foreach($result->result() as $row){
            array_push($arryAulas, array("id" => $row->nid_aula, "name" => utf8_encode($row->desc_aula) ));
        }
        return $arryAulas;
    }
    
    function getEstudiantesByAula($idaulas) {
        $sql ="SELECT p.nid_persona as id_estudiantes,
                      CONCAT(UPPER(p.ape_pate_pers),' ',UPPER(p.ape_mate_pers),' ',INITCAP(p.nom_persona)) as nombre_estudiante,
                      CASE WHEN(p.foto_persona IS NULL) THEN 'nouser.svg'
                                                        ELSE p.foto_persona
                      END AS foto_persona,
                      pa.__id_aula aula
                FROM persona              p,
                     persona_x_aula      pa,
                     sima.detalle_alumno da
               WHERE pa.flg_acti   = '".FLG_ACTIVO."' 
                 AND pa.__id_aula  IN (?)
                 AND p.nid_persona = pa.__id_persona
                 AND p.nid_persona = da.nid_persona";
        $result = $this->db->query($sql,array($idaulas));
        return $result->result();
    }
    
    function getEstudiantesByGraficoTendencias($id) {
        $sql = "SELECT m._id_persona pers,
                       cast(to_char(dc.fecha_vencimiento,'mm') as int) as mes,
                       CASE WHEN (dc.fecha_vencimiento < now()) THEN 'V' ELSE 'A' END AS new_estado
                  FROM pagos.movimiento m,detalle_cronograma dc
             LEFT JOIN pagos.concepto co ON co.id_concepto = '".CONCEPTO_SERV_ESCOLAR."'
                 WHERE m._id_persona in (?)
                  AND  m.tipo_movimiento       = 'INGRESO'
                  AND cast(to_char(dc.fecha_vencimiento,'YYYY') as int) = EXTRACT (YEAR FROM now())
                  AND m._id_detalle_cronograma IS NOT NULL
                  AND m._id_detalle_cronograma = dc.id_detalle_cronograma";
        $result   = $this->db->query($sql,array($id));
        return $result->result();
    }
    
    function getCardCompromisoAll($sede,$nivel = NULL,$grado = NULL) {
        $sede = _getSesion('id_sede_trabajo');
        $sql = "SELECT a.nid_aula as n_aula, 
                       INITCAP(a.desc_aula) as nombre_aula, 
                	   s.desc_sede,
                	   nvel.desc_nivel,
                	   gr.desc_grado,
                	   a.nombre_letra as seccion,
                	   concat(pe.ape_pate_pers,' ',pe.nom_persona) as tutor,
                	   (    SELECT ar.desc_area as area_tutor
                	          FROM public.area ar
                        INNER JOIN public.persona_x_area perarea ON perarea.nid_persona = pe.nid_persona AND perarea.nid_area = ar.id_area 
                	         WHERE ar.flg_general = '".FLG_GENERAL_AREA_ESPECIFICO."'
                	   ),
                	   pe.foto_persona
                  FROM public.sede s
            INNER JOIN public.aula a     		    ON a.flg_acti  = 1 AND s.nid_sede = a.nid_sede 
            LEFT  JOIN public.nivel nvel 		    ON nvel.nid_nivel = a.nid_nivel
            LEFT  JOIN public.grado gr   		    ON gr.nid_grado = a.nid_grado
            LEFT  JOIN public.persona pe 		    ON pe.nid_persona = a.id_tutor
            LEFT  JOIN public.persona_x_area pearea ON pearea.nid_persona = pe.nid_persona	 
                 WHERE s.nid_sede = ?
                   AND CASE WHEN( ? IS NOT NULL) THEN nvel.nid_nivel  = ? ELSE 1 = 1 END
                   AND CASE WHEN( ? IS NOT NULL) THEN gr.nid_grado    = ? ELSE 1 = 1 END
              ORDER BY a.desc_aula     ";
        $result = $this->db->query($sql,array($sede,$nivel,$nivel,$grado,$grado));
        return $result->result();
    }
    
function validarAlumnosCompromisos($apellido,$codAlu,$codFamilia,$idSede,$offSet = 0) {
        $sql ="SELECT pe.nid_persona,
                      da.cod_alumno,
                      INITCAP(pe.nom_persona) as nombres,
                      UPPER(CONCAT(pe.ape_pate_pers,' ',pe.ape_mate_pers)) AS apellidos,  
                      au.nid_sede,
                      au.nid_nivel,
                      au.nid_grado,
                      (SELECT desc_sede FROM sede where nid_sede = au.nid_sede) AS desc_sede, 
                      (SELECT desc_nivel FROM nivel WHERE nid_nivel = au.nid_nivel) AS desc_nivel,
                      (SELECT desc_grado FROM grado WHERE nid_grado = au.nid_grado) AS desc_grado, 
                      au.desc_aula,  
                      da.cod_familia,
                      (SELECT COUNT(1)
                         FROM pagos.movimiento
                        WHERE estado = 'VENCIDO'
                          AND _id_persona = pe.nid_persona) moroso
                 FROM public.persona        pe,
                      public.persona_x_aula pa,
                      public.aula           au,
                      public.sede            s,
                      public.grado           g,
                      public.nivel           n,
                      sima.detalle_alumno   da
                WHERE au.year = "._YEAR_."
                  AND da.estado IN('MATRICULADO')
                  AND LOWER(CONCAT(pe.ape_pate_pers,' ',pe.ape_mate_pers,' ',pe.nom_persona)) LIKE LOWER(?)
                  AND ((da.cod_alumno  IS NOT NULL AND da.cod_alumno  LIKE ?) OR (da.cod_alumno IS NULL AND 1 = 1))
                  AND ((da.cod_familia IS NOT NULL AND da.cod_familia LIKE ?) OR (da.cod_familia IS NULL AND 1 = 1))
                  AND pa.__id_persona = pe.nid_persona
                  AND da.nid_persona  = pe.nid_persona
                  AND au.nid_aula     = pa.__id_aula
                  --AND s.nid_sede = ?
                  AND au.nid_sede     = s.nid_sede
                  AND au.nid_grado    = g.nid_grado
                  AND au.nid_nivel    = n.nid_nivel
                UNION
               SELECT pe.nid_persona,
                      da.cod_alumno,
                      INITCAP(pe.nom_persona) as nombres,
                      UPPER(CONCAT(pe.ape_pate_pers,' ',pe.ape_mate_pers)) AS apellidos,  
                      da.id_sede_ingreso,
                      da.id_nivel_ingreso,
                      da.id_grado_ingreso,
                      (SELECT desc_sede FROM sede WHERE nid_sede = da.id_sede_ingreso) AS desc_sede, 
                      (SELECT desc_nivel FROM nivel WHERE nid_nivel = da.id_nivel_ingreso) AS desc_nivel,
                      (SELECT desc_grado FROM grado WHERE nid_grado = da.id_grado_ingreso) AS desc_grado, 
                      '' as desc_aula,
                      da.cod_familia,
                      (SELECT COUNT(1)
                         FROM pagos.movimiento
                        WHERE estado = 'VENCIDO'
                          AND _id_persona = pe.nid_persona) moroso
                 FROM public.persona	   pe,
                      public.sede          s,
                      public.grado         g,
                      public.nivel         n,
                      sima.detalle_alumno da
                WHERE da.estado      	   IN('PREREGISTRO','REGISTRADO','DATOS_INCOMPLETOS','VERANO','RETIRADO')
                  AND UNACCENT(LOWER(CONCAT(pe.ape_pate_pers,' ',pe.ape_mate_pers,' ',pe.nom_persona))) LIKE UNACCENT(LOWER(?))
                  AND da.nid_persona 	  = pe.nid_persona
                  AND da.id_sede_ingreso  = s.nid_sede
                  AND da.id_nivel_ingreso = n.nid_nivel
                  --AND s.nid_sede = ? 
                  AND da.id_grado_ingreso = g.nid_grado
             ORDER BY apellidos
                LIMIT 12 OFFSET " . $offSet;
            $result = $this->db->query($sql,array("%".$apellido."%","%".$codAlu."%","%".$codFamilia."%",$idSede,"%".$apellido."%",$idSede));
            return $result->result();
    }
    
    function GetListaPersonas($id_persona) {
        $sql = "SELECT nid_persona,
                       CONCAT(UPPER(ape_pate_pers),' ',UPPER(ape_mate_pers),', ',INITCAP(nom_persona)) persona
                  FROM public.persona
                 WHERE nid_persona IN ?";
        $result = $this->db->query($sql,array($id_persona));
        return $result->result();
    }
    
    function listaGlobalCompromisos($id) {
        $sql = "SELECT id_movimiento,
                       monto,
                       monto_final,
                       to_char(fecha_registro,'DD/MM/YYYY HH24:MM:SS') fecha_registro,
                       estado,
                       _id_persona,
                       (SELECT desc_concepto FROM pagos.concepto WHERE id_concepto = _id_concepto) as concepto
                  FROM pagos.movimiento
                 WHERE _id_compromiso_global = ? and estado = ?
              ORDER BY _id_persona";
        $result   = $this->db->query($sql,array($id,ESTADO_POR_PAGAR));
        return $result->result();
    }
    
    function getListaCompromisosGlobales($idSecretaria) {
        $sql = "SELECT id_compromiso_global,
                       desc_concepto,
                       to_char(audi_fec_regi,'DD/MM/YYYY HH24:MM:SS') audi_fec_regi
                  FROM pagos.compromiso_global
                 WHERE id_audi_persona = ?
        		   AND estado = '".FLG_ESTADO_ACTIVO."'";
        $result   = $this->db->query($sql,array($idSecretaria));
        return $result->result();
    }
    
    function total_cronoBySedeArray($id_sede) {
        $sql = "SELECT count(det.desc_detalle_crono) as total,cro._id_sede 
        		  FROM pagos.cronograma cro
         	INNER JOIN pagos.detalle_cronograma det ON cast(to_char(det.fecha_vencimiento,'yyyy') as int) = EXTRACT (YEAR FROM now()) OR cast(to_char(det.fecha_vencimiento,'yyyy') as int) = EXTRACT (YEAR FROM now())+1
                 WHERE cro._id_sede IN ? AND (cro.year = EXTRACT (YEAR FROM now()) OR cro.year = EXTRACT (YEAR FROM now())+1) 
                   AND cro.id_cronograma = det._id_cronograma AND cro.estado = '".FLG_ESTADO_ACTIVO."'
              GROUP BY _id_sede";
        $result   = $this->db->query($sql,array($id_sede));
        return $result->result();
    }
    
    function ValidarAluCompromisos($idalu) {
        $sql = "SELECT count(det.desc_detalle_crono) AS total,
                       cro._id_sede,
                       mov._id_persona 
                  FROM pagos.movimiento mov
            INNER JOIN pagos.cronograma cro ON cro.estado = 'ACTIVO'
          	INNER JOIN pagos.detalle_cronograma det ON det.id_detalle_cronograma = mov._id_detalle_cronograma AND det._id_cronograma = cro.id_cronograma
                 WHERE mov._id_detalle_cronograma IS NOT NULL 
        	       AND mov._id_concepto = '".CONCEPTO_SERV_ESCOLAR."' 
        		   AND mov._id_persona  IN ?
              GROUP BY cro.id_cronograma,mov._id_persona 
              ORDER BY cro.id_cronograma";
        $result   = $this->db->query($sql,array($idalu));
        return $result->result();
    }
    
    function ValidarConceptoCompromisos($desc) {
            $sql = "SELECT desc_concepto
                      FROM pagos.concepto
                     WHERE lower(desc_concepto) = lower(?)";
            $descripc   = $this->db->query($sql,array($desc));
            if($descripc->num_rows() > 0) {
                return null;
            }
            else{
                return 1;
            }
    }
    
    function crearConceptoCompromisos($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            $this->db->insert("pagos.concepto",$datos);
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                throw new Exception('No se pudo guardar el concepto');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
            $data['insert_id_concepto'] = $this->db->insert_id();
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function crearCompromisosGlobales($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        try{
            $this->db->insert("pagos.compromiso_global",$datos);
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                throw new Exception('No se pudo guardar el compromiso global');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = MSJ_INS;
            $data['cabecera']  = CABE_INS;
            $data['insert_id'] = $this->db->insert_id();
        }
        catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getDetallesAllCronogramasByYearActual() { 
     $sql="SELECT cro._id_sede,
                  cro.id_cronograma,
                  det.id_detalle_cronograma,
                  det.desc_detalle_crono,
                  det.fecha_vencimiento,
                  det.flg_tipo,
                  CASE WHEN(cuot.flg_beca IS NULL) THEN '0' ELSE cuot.flg_beca END as flg_beca
             FROM pagos.cronograma cro
       INNER JOIN pagos.detalle_cronograma det ON det._id_cronograma = cro.id_cronograma
        LEFT JOIN pagos.cuota_x_mes cuot ON cuot.numero_mes          = CAST(to_char(det.fecha_vencimiento,'mm') as int) 
              AND cuot._id_sede = cro._id_sede AND cuot.year         = EXTRACT (YEAR FROM now())
            WHERE cro.year = EXTRACT (YEAR FROM now())
            ORDER BY cro._id_sede,det.fecha_vencimiento";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getDetallesAllCronogramas($year,$id_detalleCro) {
        $sql="SELECT cro._id_sede,
                     cro.id_cronograma,
                     det.id_detalle_cronograma,
                     det.desc_detalle_crono,
                     det.fecha_vencimiento,
                     det.flg_tipo,
                     CASE WHEN(cuot.flg_beca IS NULL) THEN '0' ELSE cuot.flg_beca END as flg_beca
                FROM pagos.cronograma cro
          INNER JOIN pagos.detalle_cronograma det ON det._id_cronograma = cro.id_cronograma AND det._id_cronograma = IN ?
           LEFT JOIN pagos.cuota_x_mes cuot ON cuot.numero_mes = CAST(to_char(det.fecha_vencimiento,'mm') as int)
                 AND cuot._id_sede = cro._id_sede AND cuot.year = IN ?
               WHERE cro.year = IN ?
            ORDER BY det.id_detalle_cronograma,cro._id_sede,det.fecha_vencimiento";
        $result = $this->db->query($sql,array($id_detalleCro,$year,$year));
        return $result->result();
    }    
    
    function becasByidAlumnosIdcondicion($id_condicion,$id_persona) {
        $sql="SELECT _id_persona,
                    _id_condicion
               FROM pagos.condicion_x_persona
              WHERE estado        = 'ACTIVO'
                AND flg_beca      = '1'
                AND _id_condicion IN ?
                AND _id_persona   IN ?";
        $result = $this->db->query($sql,array($id_condicion,$id_persona));
        return $result->result();
    }
    
    function condicionesPagoBySedeNivelGrado() {
            $sql="SELECT CASE WHEN(tipo_condicion = '1') 
                              THEN 'pension' ELSE 'beca' END as condicion,
                         _id_sede,
                         _id_nivel,
                         _id_grado,
                         id_condicion,
                         monto_cuota_ingreso,
                         monto_matricula,
                         monto_pension,
                         porcentaje_beca,
                         descuento_nivel
                    FROM pagos.condicion
                   WHERE year_condicion = EXTRACT (YEAR FROM now())
                     AND (tipo_condicion = '0' OR tipo_condicion = '1') 
                ORDER BY condicion,_id_sede,_id_nivel,_id_grado";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function condicionesBecasByAlumnos($sede,$nivel,$grado) {
        $sql="SELECT descuento_nivel,
                     monto_matricula,
                     monto_pension,
                     CASE WHEN(_id_sede <> 0 AND _id_nivel <> 0 AND _id_grado <> 0) 
                          THEN monto_cuota_ingreso END AS monto_ingreso
                FROM pagos.condicion
               WHERE _id_sede       IN (?)
                 AND _id_nivel      IN (?)
                 AND _id_grado      IN (?) 
                 AND year_condicion = cast(to_char(now(),'YYYY')as int) 
                 AND tipo_condicion = '1'
                 AND _id_sede       <> 0 
                 AND _id_nivel      <> 0 
                 AND _id_grado      <> 0 
                 AND porcentaje_beca IS NULL";
        $result = $this->db->query($sql, array($sede,$nivel,$grado));
        return $result->result();
    }
    
    function anularCompromisosExtras($id_mov,$obs,$datos_insert, $idGlobal) {
        $data['error']    = EXIT_ERROR;
	    $data['msj']      = null;
	    $data['cabecera'] = CABE_ERROR;
	    $this->db->trans_begin();
        try{
            $this->db->where_in('id_movimiento',$id_mov);
            $this->db->update('pagos.movimiento',array('estado' =>ESTADO_ANULADO,'observacion' =>$obs));
            if($this->db->trans_status() === FALSE){
                throw new Exception('No se puedo anular los compromisos');
            }
            $this->db->insert_batch("pagos.audi_movimiento",$datos_insert);
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos_insert)) {
                throw new Exception('No se pudieron eliminar los compromisos');
            }
            $sql = "SELECT COUNT(1) as cont
            		  FROM pagos.compromiso_global cg,
            		       pagos.movimiento m
            		 WHERE cg.id_compromiso_global = ?
            		   AND m._id_compromiso_global = cg.id_compromiso_global
            		   AND m.estado = '".ESTADO_POR_PAGAR."'";
            $result = $this->db->query($sql,array($idGlobal));
            $contador = $result->row_array();
            if($contador['cont'] == 0){
            	$this->db->where('id_compromiso_global',$idGlobal);
            	$this->db->update('pagos.compromiso_global',array('estado' => ESTADO_INACTIVO));
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = "Los compromisos se anularon correctamente";
            $data['cabecera']  = CABE_INS;
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function SaveCompromisosMovimientos($datos) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        $this->db->trans_begin();
        try{
            if(count($datos) > 1){
                $data['n_total_mov'] = $this->db->insert_batch("pagos.movimiento",$datos);
            } else{
                $this->db->insert("pagos.movimiento",$datos[1]);
                $data['n_total_mov'] = 1;
            }
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos)) {
                throw new Exception('No se guardaron los compromisos');
            }
            $data['error']         = EXIT_SUCCESS;
            $data['cabecera']      = CABE_INS;
            $data['id_movimiento'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function SaveCompromisosAudiMovimientos($datos,$condicion) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        try{
            $this->load->model('m_pensiones');
            $this->db->trans_begin();
            if(isset($condicion['_id_condicion'])){
                if($this->m_pensiones->getCountCondicionAsignada($condicion['_id_condicion'],$condicion['_id_persona']) == 0){
                    $this->db->insert("pagos.condicion_x_persona",$condicion);
                    if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != 1) {
                        throw new Exception('No se guardaron los datos');
                    }
                }   
            }
            if(count($datos) > 1){
                $this->db->insert_batch("pagos.audi_movimiento",$datos);
            } else{
                $this->db->insert("pagos.audi_movimiento",$datos[0]);
            }
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($datos)) {
                throw new Exception('No se guardaron los compromisos');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['cabecera']  = CABE_INS;
            $data['msj']       = 'Los compromisos se generaron correctamente';
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getId_condicionAlumno($sede,$nivel,$grado,$year,$tipoCrono,$idDeta) {
        $sql = "SELECT *
                FROM pagos.condicion           c,
                     pagos.detalle_cronograma dc
                WHERE _id_sede                 = ?
                  AND _id_nivel                = ?
                  AND _id_grado                = ?
                  AND year_condicion           = ?
                  AND _id_tipo_cronograma      = ?
                  AND dc._id_paquete           = c._id_paquete
                  AND dc.id_detalle_cronograma = ?";
        $result = $this->db->query($sql, array($sede,$nivel,$grado,$year,$tipoCrono,$idDeta));
        return $result->row_array();
    }
    
    function ValidarCronoAluCompromisosMulti($sede,$nivel,$grado,$year,$id_persona,$julio=7,$dic=12) {
        $sql = "SELECT DISTINCT(det.id_detalle_cronograma),
                       cope._id_persona AS _id_persona,
                       det.desc_detalle_crono as detalle,
                       to_char(det.fecha_vencimiento, 'DD/MM/YYYY') as fecha_v,
                       to_char(det.fecha_descuento, 'DD/MM/YYYY') as fecha_d,
                       cond.descuento_nivel, cond.monto_matricula, cond.monto_cuota_ingreso,cond.monto_pension,
                       cond.flg_beca
                       CASE WHEN(cast(to_char(det.fecha_vencimiento,'mm') as int) <> 7) AND (cast(to_char(det.fecha_vencimiento,'mm') as int) <> 12) THEN
                	       ( SELECT porcentaje_beca 
                		       FROM pagos.condicion 
                			   INNER  JOIN pagos.condicion_x_persona cope2 ON cope2.estado = 'ACTIVO' AND cope2._id_condicion = id_condicion AND flg_beca = 0 AND cope2._id_persona IN ?
                		       WHERE tipo_condicion = '0'  AND _id_sede IS NULL AND _id_nivel IS NULL AND _id_grado IS NULL
                		   )
                           ELSE 0 END AS porcentaje_beca,
                       CASE WHEN( det.flg_tipo = '1')
                            THEN 'MATRICULA' ELSE CASE WHEN( det.flg_tipo = '2')
                            THEN 'RATIFICACI�N' ELSE 'CUOTA' END END AS concepto
                  FROM pagos.cronograma cro
            INNER JOIN pagos.detalle_cronograma det ON  det._id_cronograma = cro.id_cronograma 
            INNER JOIN pagos.condicion cond ON cond._id_sede IN ? AND cond._id_nivel IN ? AND cond._id_grado IN ? AND cond.year_condicion = ? AND tipo_condicion = '1' 
             LEFT JOIN pagos.condicion_x_persona cope ON cope._id_persona IN ? AND cope.estado = 'ACTIVO' AND cope._id_condicion = cond.id_condicion
                 WHERE cro._id_sede IN ? AND year = ?
              ORDER BY det.id_detalle_cronograma,det.fecha_vencimiento,cope._id_persona";
        $result = $this->db->query($sql, array($id_persona,$sede,$nivel,$grado,$year,$id_persona,$sede,$year));
        return $result->result();
    }
    
    function ValidarCronoAluCompromisos($sede,$nivel,$grado,$year,$id_persona,$flg_tipo,$julio='7',$dic='12') {
        $id_det_cronograma = array();
        $sql_mov="SELECT _id_detalle_cronograma
                    FROM pagos.movimiento
                    WHERE _id_persona = ?
                      AND (estado = '".ESTADO_PAGADO."' OR estado = '".ESTADO_POR_PAGAR."' OR estado = '".ESTADO_VENCIDO."')";
        $result = $this->db->query($sql_mov, array($id_persona));
        $id_det_crono = $result->result();
        if($result->num_rows() == 0){ $id_det_cronograma[] = 0;}
        else{
            foreach ($id_det_crono as $item){
                $id_det_cronograma[] = $item->_id_detalle_cronograma;
            }
        }
        $sql_beca = "SELECT porcentaje_beca
                       FROM pagos.condicion c
                 INNER JOIN pagos.condicion_x_persona cp ON cp._id_persona = ?
                        AND cp.estado = 'ACTIVO'
                        AND cp._id_condicion = c.id_condicion
                      WHERE c.tipo_condicion = '0'";
        $porcentaje   = $this->db->query($sql_beca,array($id_persona));
        if($porcentaje->num_rows() > 0) {
            $descuento = $porcentaje->row()->porcentaje_beca;
        }
        else{
            $descuento = null;
        }
        $sql_prom = "SELECT pea.year_academico, da.cod_alumno
                       FROM sima.detalle_alumno da,
                            persona AS pe
                  LEFT JOIN public.persona_x_aula pea ON pea.__id_persona = ? AND year_academico <> ? 
                      WHERE pe.nid_persona = ?
                        AND da.nid_persona = pe.nid_persona";
        $promovido = $this->db->query($sql_prom,array($id_persona,$year,$id_persona));
        $tipos = '{"1","2","3","4"}';
        $sql = "SELECT * 
                 FROM pagos.fun_get_compromisos_by_estudiante(?, NULL, ?, ?, ?, ?, ?, ?, ?)";
        $result = $this->db->query($sql,array($id_persona,$sede,$nivel,$grado,$year,'0',$flg_tipo,$tipos));
//         if($promovido->row()->year_academico != NULL) {
//              $sql = "SELECT det.id_detalle_cronograma,
//                             cro._id_tipo_cronograma,
//                             INITCAP(det.desc_detalle_crono) as detalle,
//                             to_char(det.fecha_vencimiento, 'DD/MM/YYYY')as fecha_v,
//                             to_char(det.fecha_descuento, 'DD/MM/YYYY')as fecha_d,
//                             det.flg_beca,
//                             CASE WHEN(current_date > det.fecha_vencimiento::timestamp::date) 
//                                  THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora
//                                  ELSE 0
//                             END AS mora,
//                             CASE WHEN (det.flg_beca = '".FLG_BECA."') 
//                                 THEN  'BECA'
//                                  ELSE ''
//                             END AS descuento,
//                             CASE WHEN( det.flg_tipo = '".FLG_RATIFICACION."') 
//                                  THEN cond.monto_matricula
//                                  ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '".FLG_BECA."') 
//                                            THEN round(((cond.monto_pension * (to_number('100', '9999D99') - ?))/100),2) --+ round((CASE WHEN(current_date > det.fecha_vencimiento) THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora ELSE 0 END),2)
//                                            ELSE CASE WHEN(current_date < det.fecha_descuento ) 
//                                                      THEN cond.monto_pension - cond.descuento_nivel
//                                                      ELSE cond.monto_pension --+ round((CASE WHEN(current_date > det.fecha_vencimiento) THEN(current_date - det.fecha_vencimiento::timestamp::date)*det.cantidad_mora ELSE 0 END),2)
//                                                 END
//                                       END
//                             END AS monto,
//                             CASE WHEN( det.flg_tipo = '".FLG_RATIFICACION."') 
//                                 THEN 'Ratificaci�n' 
//                                 ELSE CASE WHEN( det.flg_tipo = '".FLG_CUOTA."') 
//                                           THEN 'Cuota' 
//                                           ELSE 'CUOTA' 
//                                      END 
//                             END AS concepto,
//                             CASE WHEN (Det.id_detalle_cronograma IN ?) 
//                     			THEN 'TIENE COMPROMISO'
//                     			ELSE 'NO TIENE COMPROMISO' 
//                 			END AS compromiso,
//                             det.flg_tipo
//                        FROM pagos.cronograma cro
//                  INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND 
//                                                              (det.flg_tipo        NOT IN('".FLG_BECA."')))
//                  INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND 
//                                                               cond._id_nivel      = ?                 AND 
//                                                               cond._id_grado      = ?                 AND 
//                                                               cond.year_condicion = ?                 AND 
//                                                               cond._id_tipo_cronograma = cro._id_tipo_cronograma AND 
//                                                               det._id_paquete         = cond._id_paquete) 
//                   LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                   AND 
//                                                               cope.estado         = '".ESTADO_ACTIVO."' AND 
//                                                               cope._id_condicion  = cond.id_condicion)
//                       WHERE cro._id_sede           = ? 
//                         AND cro.year               = ? 
//                         AND cro.flg_cerrado        = '".FLG_CERRADO."'
//                         AND cro._id_tipo_cronograma = ? 
//                         AND CASE WHEN cro._id_tipo_cronograma = '2' 
//                                  THEN (SELECT flg_cerrado
//                                          FROM pagos.sede_monto
//                                         WHERE year = cro.year
//                                           AND flg_cerrado = '".FLG_CERRADO."'
//                                           AND _id_sede    = cro._id_sede) = '1'
//                                  ELSE 1 = 1 
//                             END
//                    ORDER BY cro._id_tipo_cronograma,  det.fecha_vencimiento";
//         }else{
//              $matricula_ratificacion = null;
//              $sql = "SELECT det.id_detalle_cronograma,
//                             cro._id_tipo_cronograma,
//                             (INITCAP(det.desc_detalle_crono)) as detalle,
//                             det.fecha_vencimiento as fecha_v,
//                             det.fecha_descuento as fecha_d,
//                             det.flg_beca,
//                             CASE WHEN(current_date > det.fecha_descuento::timestamp::date) 
//                                  THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora
//                                  ELSE 0
//                             END AS mora,
//                             CASE WHEN (det.flg_beca = '".FLG_BECA."') 
//                                 THEN 'BECA'
//                                 ELSE ''
//                             END AS descuento,
//                             CASE WHEN( det.flg_tipo = '".FLG_MATRICULA."') 
//                                  THEN cond.monto_matricula
//                                  ELSE CASE WHEN(? IS NOT NULL) AND (det.flg_beca = '".FLG_BECA."') 
//                                            THEN round(((cond.monto_pension * (to_number('100', '9999D99') - ?))/100),2) --+ round((CASE WHEN(current_date > det.fecha_descuento) THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora ELSE 0 END),2)
//                                            ELSE CASE WHEN(current_date < det.fecha_descuento ) 
//                                                      THEN cond.monto_pension - cond.descuento_nivel
//                                                      ELSE cond.monto_pension --+ round((CASE WHEN(current_date > det.fecha_descuento) THEN(current_date - det.fecha_descuento::timestamp::date)*det.cantidad_mora ELSE 0 END),2)    
//                                                 END
//                                       END
//                             END AS monto,
//                             INITCAP(CASE WHEN( det.flg_tipo = '".FLG_MATRICULA."')
//                                 THEN 'MATRICULA' 
//                                 ELSE CASE WHEN( det.flg_tipo = '".FLG_CUOTA."')
//                                         THEN 'CUOTA' 
//                                         ELSE 'CUOTA' 
//                                     END 
//                             END) AS concepto,
//                             CASE WHEN (Det.id_detalle_cronograma IN ?) 
//                     			THEN 'TIENE COMPROMISO'
//                     			ELSE 'NO TIENE COMPROMISO' 
//                 			END AS compromiso,
//                             det.flg_tipo
//                        FROM pagos.cronograma cro
//                  INNER JOIN pagos.detalle_cronograma det   ON(det._id_cronograma  = cro.id_cronograma AND 
//                                                              (det.flg_tipo        NOT IN('".FLG_RATIFICACION."')))
//                  INNER JOIN pagos.condicion cond           ON(cond._id_sede       = ?                 AND 
//                                                               cond._id_nivel      = ?                 AND 
//                                                               cond._id_grado      = ?                 AND 
//                                                               cond.year_condicion = ?                 AND 
//                                                               cond._id_tipo_cronograma = cro._id_tipo_cronograma AND 
//                                                               det._id_paquete         = cond._id_paquete)  
//                   LEFT JOIN pagos.condicion_x_persona cope ON(cope._id_persona    = ?                 AND 
//                                                               cope.estado         = 'ACTIVO'          AND 
//                                                               cope._id_condicion  = cond.id_condicion)
//                       WHERE cro._id_sede            = ? 
//                         AND cro.year                = ? 
//                         AND cro.flg_cerrado         = '".FLG_CERRADO."'
//                         AND cro._id_tipo_cronograma = ? 
//                         AND CASE WHEN cro._id_tipo_cronograma = '2' 
//                                  THEN (SELECT flg_cerrado
//                                          FROM pagos.sede_monto
//                                         WHERE year = cro.year
//                                           AND flg_cerrado = '".FLG_CERRADO."'
//                                           AND _id_sede    = cro._id_sede) = '1'
//                                  ELSE 1 = 1 
//                             END
//                    ORDER BY cro._id_tipo_cronograma,  det.fecha_vencimiento";
//         }
//         $result = $this->db->query($sql, array($descuento,$descuento,$id_det_cronograma,$sede,$nivel,$grado,$year,$id_persona,$sede,$year,$flg_tipo));
        return array("result" => $result->result(),"descuento" => $descuento,'codigo' => $promovido->row()->cod_alumno);
    }
    
    function buscarConcepto($id) {
        $sql = "SELECT desc_concepto
	              FROM pagos.concepto
	             WHERE id_concepto = ?";
        $result = $this->db->query($sql, $id);
        return $result->row()->desc_concepto;
    }
    
    function getDetalleConogramas() {
        $sql = "SELECT det.id_detalle_cronograma,
                       det.desc_detalle_crono,
                       cro.year,
                       cro._id_sede
                  FROM pagos.cronograma cro
            INNER JOIN pagos.detalle_cronograma det ON det._id_cronograma = cro.id_cronograma
                 WHERE cro.year = now()
              ORDER BY cro.year,cro._id_sede,det.fecha_vencimiento";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function crearCompromisosMasivos($compromisos,$message = "Los compromisos se generaron con �xito") {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = CABE_ERROR;
        try{
            $this->db->insert_batch("pagos.movimiento",$compromisos);
            if ($this->db->trans_status() === FALSE  || $this->db->affected_rows() != count($compromisos)) {
                throw new Exception('Los compromisos no se guardaron');
            }
            $data['error']     = EXIT_SUCCESS;
            $data['msj']       = $message;
            $data['cabecera']  = CABE_INS;
            $data['insert_id'] = $this->db->insert_id();
            $this->db->trans_commit();
        }
        catch (Exception $e) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getMoraByDetalle($idDetalle,$porcentaje,$sede,$nivel,$grado,$year,$tipoCronograma) {
        $sql = "SELECT CASE WHEN(current_date > dc.fecha_vencimiento::timestamp::date) THEN ((current_date-dc.fecha_vencimiento::timestamp::date) * dc.cantidad_mora)
                								                                       ELSE 0 END as mora,
                       CASE WHEN(dc.fecha_vencimiento::timestamp::date < current_date) THEN 'VENCIDO'
                								                                       ELSE 'POR PAGAR'
                       END AS estado,
                       CASE WHEN(dc.flg_tipo = '3') THEN round(monto_pension* ? ,2)  
                            WHEN(dc.flg_tipo = '4') THEN round(monto_pension* 1 ,2)  
                            ELSE monto_matricula 
                       END AS monto_base
                  FROM pagos.detalle_cronograma dc,
                       pagos.condicion c
                 WHERE dc.id_detalle_cronograma = ?
                   AND c._id_sede               = ?
                   AND c._id_nivel              = ?
                   AND c._id_grado              = ?
                   AND c.year_condicion         = ?
                   AND c._id_tipo_cronograma    = ?
                   AND c._id_paquete            = dc._id_paquete";
        $result = $this->db->query($sql,array($porcentaje,$idDetalle,$sede,$nivel,$grado,$year,$tipoCronograma));
        return $result->row_array();
    }
    
    function evaluateCuotaIngresoByPersonaFamilia($idPersona,$type = FLG_CI_FAMILIA) {
        if($type == FLG_CI_FAMILIA){
            $sql = "SELECT COUNT(1) count
                      FROM persona p,
                           pagos.movimiento m,
                           sima.detalle_alumno da
                     WHERE da.cod_familia IN(SELECT cod_familia
                		                       FROM sima.detalle_alumno
                			                  WHERE nid_persona = ?)
                       AND m._id_persona   = p.nid_persona
                       AND m._id_concepto  = 3
                       AND p.nid_persona   = da.nid_persona
                  GROUP BY p.nid_persona";
        } else {
            $sql = "SELECT COUNT(1) count
                      FROM persona p,
                           pagos.movimiento m
                     WHERE p.nid_persona = 10369
                       AND m._id_persona = p.nid_persona
                       AND m._id_concepto = ".CUOTA_INGRESO;   
        }
        $result = $this->db->query($sql,array($idPersona));
        if($result->num_rows() == 0){
            return 0;
        } else{
            return $result->row()->count;
        }
    }
    
    function verifyConfigCI($sede,$year){
        $sql = "SELECT estado,
                       flg_afecta
                  FROM pagos.config_cuota_ingreso
                 WHERE _id_sede = ?
                   AND year     = ?";
        $result = $this->db->query($sql,array($sede,$year));
        if($result->num_rows() == 0){
            return null;
        } else{
            return $result->row_array();
        }
    }
    
    /**
     * 
     * @param $year
     * @param $idSede
     * @param $codFamilia
     * @param $idEstudiante
     * @author dfloresgonz 14.12.2016
     * @return flg si genera o no cuota de ingreso
     */
    function checkSi_generarCuotaIngreso($year, $idSede, $codFamilia, $idEstudiante) {
        $sql = "SELECT * FROM pagos.check_si_generar_cuota_ingreso(?, ?, ?, ?) AS flg_cuota_ingreso";
        $result = $this->db->query($sql, array($year, $idSede, $codFamilia, $idEstudiante));
        if($result->num_rows() == 0) {
            return 0;
        }
        return $result->row()->flg_cuota_ingreso;
    }
    
    function getNivelGradoSiguiente($idPersona,$year){
        $sql = "SELECT da.id_sede_ingreso,
                       g.nid_grado,
                       g.id_nivel
                  FROM grado                g,
                       nivel                n,
                       sima.detalle_alumno da
                 WHERE g.nid_grado    = da.id_grado_ingreso + 
                                        (?    - 
                                      (SELECT year_academico
                                         FROM persona_x_aula
                                        WHERE __id_persona = ?
                                     ORDER BY year_academico DESC
                                        LIMIT 1))
                   AND g.id_nivel     = n.nid_nivel
                   AND da.nid_persona = ?
                   AND da.estado      IN('MATRICULADO')
               UNION 
                  SELECT da.id_sede_ingreso,
                         g.nid_grado,
                         n.nid_nivel
                    FROM sima.detalle_alumno da,
                         nivel                n,
                         grado                g
                   WHERE da.nid_persona      = ?
                     AND da.estado           IN ('REGISTRADO','PROM_PREREGISTRO','PROM_REGISTRO','PREREGISTRO','MATRICULABLE','RETIRADO','VERANO')
                     AND da.id_nivel_ingreso = n.nid_nivel
                     AND da.id_grado_ingreso = g.nid_grado
                     AND n.nid_nivel         = g.id_nivel";
        $result = $this->db->query($sql,array($year,$idPersona,$idPersona,$idPersona));
        return $result->row_array();
    }
}