<?php
class M_mantenimiento extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function mostrarConcepto($id){
		$sql = "SELECT c.id_concepto,
					   c.tipo_movimiento,
					   c.monto_referencia,
					   INITCAP(c.desc_concepto) AS desc_concepto,
		               c.flg_padre,
		               c.id_padre
	              FROM pagos.concepto c
	             WHERE c.id_concepto = ?";
		$result = $this->db->query($sql, $id);
		return $result->row_array();
	}
	
	function descConcepto($id) {
		$sql = "SELECT INITCAP(c.desc_concepto) AS desc_concepto,
	              FROM pagos.concepto c
	             WHERE c.id_concepto = ?";
		$result = $this->db->query($sql, $id);
		return $result->row()->desc_concepto;
	}
	
	function getAllConceptos() {
	    $sql = "SELECT c.id_concepto,
					   c.tipo_movimiento,
					   c.monto_referencia,
					   INITCAP(c.desc_concepto) AS desc_concepto,
	    			   to_char(c.fecha_registro,'DD/MM/YYYY') as fecha_registro,
	    			   c.estado
	              FROM pagos.concepto c
	             WHERE c.id_concepto NOT IN ?
	    		 ORDER BY c.estado, c.desc_concepto";
	    $result = $this->db->query($sql,array(json_decode(ARRAY_CONCEPTOS)));
	    return $result->result();
	}
	
	function allConcepto($desc) {
	    $sql = "SELECT COUNT(1) as count
	              FROM pagos.concepto c
	             WHERE LOWER(desc_concepto) = LOWER(?)";
	    $result = $this->db->query($sql, $desc);
	    return $result->row()->count;
	}
	
	function estadoConcepto($id) {
		$sql = "SELECT c.estado
	              FROM pagos.concepto c
	             WHERE c.id_concepto = ?";
		$result = $this->db->query($sql, $id);
		return $result->row()->estado;
	}
	
	function buscarConcepto($id) {
		$sql = "SELECT COUNT(1) as count
	              FROM pagos.movimiento m
	             WHERE m._id_concepto = ?";
		$result = $this->db->query($sql, $id);
		return $result->row()->count;
	}
	
	function conceptoMovimiento($id) {
		$sql = "SELECT COUNT(1) AS count
	              FROM pagos.movimiento m
	             WHERE m._id_concepto = ?
	               AND estado <> 'ANULADO'";
		$result = $this->db->query($sql, $id);
		return $result->row()->count;
	}
	
	function eliminarConcepto($id) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $this->db->trans_begin();
	    try{
	        $this->db->where('id_concepto', $id);
	        $this->db->delete('pagos.concepto');
	        if($this->db->affected_rows()!=1 || $this->db->trans_status() == FALSE){
	           throw new Exception("Vuelva a Intentar");
	        }
	        $data['error'] = EXIT_SUCCESS;
	        $data['msj']   = "Concepto Eliminado";
	        $this->db->trans_commit();
	    }catch (Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function actualizarConcepto($id, $arrayUpdate) {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$this->db->trans_begin();
		try{
			$this->db->where('id_concepto', $id);
			$this->db->update('pagos.concepto', $arrayUpdate);
			if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
				throw new Exception("Vuelva a Intentar");
			}
			$data['error'] = EXIT_SUCCESS;
			$data['msj']   = "Concepto Actualizado";
			$this->db->trans_commit();
		}catch (Exception $e){
			$data['msj']   = $e->getMessage();
			$this->db->trans_rollback();
		}
		return $data;
	}
	
	function modificarConcepto($id, $arrayUpdate) {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		$this->db->trans_begin();
		try{
			$this->db->where('id_concepto', $id);
			$this->db->update('pagos.concepto', $arrayUpdate);
			if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
				throw new Exception("vuelva a intentar");
			}
			$data['error'] = EXIT_SUCCESS;
			$data['msj']   = "Estado Actualizado";
			$this->db->trans_commit();
		}catch (Exception $e){
			$data['msj']   = $e->getMessage();
			$this->db->trans_rollback();
		}
		return $data;
	}
	
	function registrarConcepto($arrayInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    $this->db->trans_begin();
	    try{
	        $this->db->insert('pagos.concepto', $arrayInsert);
	        if($this->db->affected_rows() != 1 || $this->db->trans_status() == FALSE){
	            throw new Exception("Vuelva a Intentar");
	        }
	        $data['error'] = EXIT_SUCCESS;
	        $data['msj']   = "Registro exitoso";
	        $this->db->trans_commit();
	    }catch (Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getAllConceptosByTipo($tipoConcepto) {
	    $sql = "SELECT c.id_concepto,
	                   INITCAP(c.desc_concepto) AS desc_concepto,
	                   c.monto_referencia
	              FROM pagos.concepto c
	             WHERE c.tipo_movimiento = ?
	               AND c.id_concepto NOT IN ('".CONCEPTO_SERV_ESCOLAR."','".CUOTA_INGRESO."','".DEVOLUCIONES."')
	               AND c.estado          = '".FLG_ESTADO_ACTIVO."'";
	    $result = $this->db->query($sql,array($tipoConcepto));
	    return $result->result();
	}
	
	function getCountConceptosUso($idConcepto) {
	    $sql = "SELECT COUNT(1) cuenta
	              FROM pagos.movimiento
	             WHERE _id_concepto = ?";
	    $result = $this->db->query($sql,array($idConcepto));
	    return $result->row()->cuenta;
	}
	
	function getComboPadres($id) {
		$sql = "SELECT id_concepto, 
		               INITCAP(c.desc_concepto) AS desc_concepto
				  FROM pagos.concepto c
				 WHERE flg_padre = '0'
				   AND id_concepto <> ?";
		$result = $this->db->query($sql, array($id));
		return $result->result();
	}
	
	function getCountConceptoHijo($idConcepto) {
		$sql = "SELECT COUNT(1) cuenta
	              FROM pagos.concepto
	             WHERE id_padre = ?";
		$result = $this->db->query($sql,array($idConcepto));
		return $result->row()->cuenta;
	}
	
}