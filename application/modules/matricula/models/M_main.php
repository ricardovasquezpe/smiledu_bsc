<?php

class M_main extends CI_Model
{

    
    function __construct(){
		parent::__construct();
	}
    /*
	function validateUsuario($usuario, $clave){
	    $sql = "SELECT * 
	              FROM persona p
	             WHERE LOWER(p.usuario) = LOWER(?)
	               AND p.clave          = (SELECT encrypt(?,?,'aes'))";
	    $result = $this->db->query($sql, array($usuario, $clave, $clave));
	    
	    return $result->row_array();
	}	
	
    function getUsuarios($id_usuario)
    {
        $sql = "SELECT *
	              FROM usuario
	             WHERE idUsuario = ?";
        
        $result = $this->db->query($sql, array(
            $id_usuario
        ));
        return $result->result();
    }

    function insertUsuario($array)
    {
        $this->db->insert('usuario', $array);
    }

    function updateUsuario($array, $idUsuario)
    {
        $this->db->where('idUsuario', $idUsuario);
        $this->db->update('usuario', $array);
    }

    function deleteUsuario($array)
    {
        $this->db->delete('usuario', $array);
    }

    function validarUsuario($user, $pass)
    {
        $query = "SELECT *
	                FROM persona
	               WHERE usuario = ? ";
        $result = $this->db->query($query, array(
            $user
        ));
        $res = $result->row_array();
        
        return $res;
    }
    */
}
