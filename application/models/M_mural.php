<?php
class M_mural extends  CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function publicar($arrayPublic){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try {
            $this->db->insert('publicacion',$arrayPublic);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception(ANP);
            }
            $rpta['id_publicacion'] = $this->db->insert_id();
            $rpta['error']     = EXIT_SUCCESS;
            $rpta['msj']       = CABE_INS;
            $rpta['cabecera']  = CABE_INS;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    function insertImagenMural($data){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try {
            $this->db->insert_batch('archivos',$data);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception(ANP);
            }
            $rpta['error']     = EXIT_SUCCESS;
            $rpta['msj']       = CABE_INS;
            $rpta['cabecera']  = CABE_INS;
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    //padre = 1 generales | padre = 0 comentarios
    function getPublicaciones($start, $tipo,$padre = null,$idPublicacion= null){
        $sql = "SELECT p.audi_usua_regi,
                       p.audi_pers_regi,
                       p.audi_fec_regi,
                       p.comentario,
                       p.tipo_mural,
                       ( SELECT CASE WHEN pers.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."',foto_persona)
                                     WHEN pers.google_foto  IS NOT NULL THEN google_foto
                                     ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END
                           FROM persona pers
                          WHERE pers.nid_persona = p.audi_usua_regi ) AS foto_persona,
                       p.id_publicacion,
                       CASE WHEN p.nro_likes IS NULL THEN 0
                            ELSE p.nro_likes END AS nro_likes,
                       CASE WHEN p.nro_comentarios IS NULL THEN 0
                            ELSE p.nro_comentarios END AS nro_comentarios,
                       (SELECT STRING_AGG(a.ruta,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as rutas,
                       (SELECT STRING_AGG(a.tipo,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as tipos,
                       (SELECT STRING_AGG(a.extension,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as extensiones,
                       (SELECT STRING_AGG(a.nombre_archivo,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as nombres
                  FROM publicacion p
                 WHERE p.tipo_mural = ?
                   AND ((1 = ? AND p.id_padre IS NULL) OR (0 = ? AND p.id_padre = ?) )
              ORDER BY audi_fec_regi DESC
                       offset ?";
        
        $result = $this->db->query($sql, array($tipo,$padre,$padre,$idPublicacion,$start));
        return $result->result();
    }
    
    function getPublicacion($id){
        $sql = "SELECT p.audi_usua_regi,
                       p.audi_pers_regi,
                       p.audi_fec_regi,
                       p.comentario,
                       ( SELECT CASE WHEN pers.foto_persona IS NOT NULL THEN CONCAT('".RUTA_SMILEDU."',foto_persona)
                                     WHEN pers.google_foto  IS NOT NULL THEN google_foto
                                     ELSE '".RUTA_SMILEDU.FOTO_DEFECTO."' END
                           FROM persona pers
                          WHERE pers.nid_persona = p.audi_usua_regi ) AS foto_persona,
                       p.id_publicacion,
                       p.tipo_mural,
                       CASE WHEN p.nro_likes IS NULL THEN 0
                            ELSE p.nro_likes END AS nro_likes,
                       CASE WHEN p.nro_comentarios IS NULL THEN 0
                            ELSE p.nro_comentarios END AS nro_comentarios,
                       (SELECT STRING_AGG(a.ruta,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as rutas,
                       (SELECT STRING_AGG(a.tipo,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as tipos,
                       (SELECT STRING_AGG(a.extension,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as extensiones,
                       (SELECT STRING_AGG(a.nombre_archivo,'|') as rutas
                          FROM archivos a
                         WHERE a._id_publicacion = p.id_publicacion) as nombres
                  FROM publicacion p
                 WHERE p.id_publicacion = ?
              ORDER BY audi_fec_regi DESC";
    
        $result = $this->db->query($sql, array($id));
        return $result->result();
    }
    
    function like($id){
        $sql = "UPDATE publicacion
                   SET nro_likes = nro_likes + 1
                 WHERE id_publicacion = ?";
        
        $result = $this->db->query($sql, array($id));
    }
}