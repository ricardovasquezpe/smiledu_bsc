<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 * @property    M_utils_notas $m_utils_notas
 * @property    M_detalle_evaluacion $m_detalle_evaluacion
 * @property    M_detalle_contactos $m_detalle_contactos
 * @property    M_detalle_evento $m_detalle_evento
 * @property    M_evaluacion $m_evaluacion
 * @property    M_matricula $m_matricula
 * @property    M_contactos $m_contactos
 * @property    M_reportes $m_reportes
 * @property    M_traslado $m_traslado
 * @property    M_usuario $m_usuario
 * @property    M_alumno $m_alumno
 * @property    M_utils $m_utils
 * @property    M_aula $m_aula
 * @property    M_migracion $m_migracion
 * @property    M_migrar_scirerh $m_migrar_scirerh
 * @property    M_formulario $m_formulario
 * @property    M_crear_encuesta $m_crear_encuesta
 * @property    M_encuesta $m_encuesta
 * @property    M_compromisos $m_compromisos
 * @property    M_main $m_main
 * @property    M_indicador $m_indicador
 * @property    M_agenda $m_agenda
 * @property    M_evaluar $m_evaluar
 * @property    M_cons_eval $m_cons_eval
 * @property    M_graficos_new $m_graficos_new
 * @property    M_evento $m_evento
 * @property    M_espera $m_espera
 * @property    M_boleta $m_boleta
 * @property    M_movimientos $m_movimientos
 * @property    M_cronograma $m_cronograma
 * @property    M_pagos $m_pagos
 * @property    M_pregunta $m_pregunta
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

}
