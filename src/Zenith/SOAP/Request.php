<?php
namespace Zenith\SOAP;

class Request {
	/**
	 * Conversion constants
	 */
	const AS_STRING = 0;
	const AS_SIMPLEXML = 1;
	const AS_DOM = 2;
	
	/**
	 * Service section
	 * @var array
	 */
	protected $service = array();
	
	/**
	 * Configuration section
	 * @var array
	 */
	protected $configuration = array();
	
	/**
	 * Request parameter
	 * @var string
	 */
	protected $parameter;
	
	/**
	 * Request raw parameter
	 * @var \stdClass
	 */
	protected $rawParameter;
	
	/**
	 * Sets values for service section
	 * @param string $class
	 * @param string $method
	 */
	public function setService($class, $method) {
		$this->service = array('class' => $class, 'method' => $method);
	}
	
	/**
	 * Obtains service section values
	 * @return array
	 */
	public function getService() {
		return $this->service;
	}
	
	/**
	 * Sets service class
	 * @param string $class
	 */
	public function setClass($class) {
		$this->service['class'] = $class;
	}
	
	/**
	 * Obtains service class
	 * @return string|NULL
	 */
	public function getClass() {
		if (array_key_exists('class', $this->service)) {
			return $this->service['class'];
		}
		
		return null;
	}
	
	/**
	 * Sets service method
	 * @param string $method
	 */
	public function setMethod($method) {
		$this->service['method'] = $method;
	}
	
	/**
	 * Obtains service method
	 * @return string|NULL
	 */
	public function getMethod() {
		if (array_key_exists('method', $this->service)) {
			return $this->service['method'];
		}
		
		return null;
	}
	
	/**
	 * Sets configuration section
	 * @param array $configuration
	 */
	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}
	
	/**
	 * Obtains configuration section
	 * @return array
	 */
	public function getConfiguration() {
		return $this->configuration;
	}
	
	/**
	 * Sets a new configuration value
	 * @param string $name
	 * @param string $value
	 */
	public function setOption($name, $value) {
		$this->configuration[$name] = $value;
	}
	
	/**
	 * Obtains a configuration value
	 * @param string $name
	 * @return string|NULL
	 */
	public function getOption($name) {
		if (array_key_exists($name, $this->configuration)) {
			return $this->configuration[$name];
		}
		
		return null;
	}
	
	/**
	 * Clears a configuration value
	 * @param string $name
	 */
	public function clearOption($name) {
		if (array_key_exists($name, $this->configuration)) {
			unset($this->configuration[$name]);
		}
	}
	
	/**
	 * Sets request parameter
	 * @param string $parameter
	 */
	public function setParameter($parameter) {
		$this->parameter = $parameter;
	}
	
	/**
	 * Obtains parameter in the specified format
	 * @param int $as
	 * @return SimpleXMLElement|\DOMDocument|string
	 */
	public function getParameter($as = self::AS_STRING) {
		if ($as == self::AS_SIMPLEXML) {
			//convert to SimpleXMLElement
			$success = simplexml_load_string($this->parameter);
				
			if ($success === false) {
				$error = libxml_get_last_error();
				throw new \RuntimeException("XML Syntax error: " . $error->message);
			}
				
			return $success;
		}
		elseif ($as == self::AS_DOM) {
			//convert to DOMDocument
			$dom = new \DOMDocument();
				
			if (!$dom->loadXML($this->parameter)) {
				$error = libxml_get_last_error();
				throw new \RuntimeException("XML Syntax error: " . $error->message);
			}
				
			return $dom;
		}
		
		return $this->parameter;
	}
	
	/**
	 * Sets raw parameter
	 * @param \stdClass $rawParameter
	 */
	public function setRawParameter($rawParameter) {
		$this->rawParameter = $rawParameter;
	}
	
	/**
	 * Obtains raw parameter
	 * @return \stdClass
	 */
	public function getRawParameter() {
		return $this->rawParameter;
	}
}