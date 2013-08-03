<?php
namespace Zenith\SOAP;

class Response {
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
	 * Status section
	 * @var array
	 */
	protected $status  = array();
	
	/**
	 * Response result
	 * @var string
	 */
	protected $result;
	
	/**
	 * Raw result
	 * @var \stdClass
	 */
	protected $rawResult;
	
	/**
	 * Sets values for service section
	 * @param string $class
	 * @param string $method
	 */
	public function setService($class, $method) {
		$this->service = array('class' => $class, 'method' => $method);
	}
	
	/**
	 * Obtains service section
	 * @return array
	 */
	public function getService() {
		return $this->service;
	}
	
	/**
	 * Sets values for status section
	 * @param int $code
	 * @param string $message
	 */
	public function setStatus($code, $message) {
		$this->status = array('code' => $code, 'message' => $message);
	}
	
	/**
	 * Obtains status section
	 * @return array
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Sets response result
	 * @param string $result
	 */
	public function setResult($result) {
		$this->result = $result;
	}
	
	/**
	 * Obtains the result in the specified format
	 * @param int $as
	 * @return SimpleXMLElement|\DOMDocument|string
	 */
	public function getResult($as = self::AS_STRING) {
		if ($as == self::AS_SIMPLEXML) {
			//convert to SimpleXMLElement
			$success = simplexml_load_string($this->result);
				
			if ($success === false) {
				$error = libxml_get_last_error();
				throw new \RuntimeException("XML Syntax error: " . $error->message);
			}
				
			return $success;
		}
		elseif ($as == self::AS_DOM) {
			//convert to DOMDocument
			$dom = new \DOMDocument();
				
			if (!$dom->loadXML($this->result)) {
				$error = libxml_get_last_error();
				throw new \RuntimeException("XML Syntax error: " . $error->message);
			}
				
			return $dom;
		}
	
		return $this->result;
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
	 * @return string|null
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
	 * @return string|null
	 */
	public function getMethod() {
		if (array_key_exists('method', $this->service)) {
			return $this->service['method'];
		}
		
		return null;
	}
	
	/**
	 * Sets status code
	 * @param int $code
	 */
	public function setStatusCode($code) {
		$this->status['code'] = $code;
	}
	
	/**
	 * Obtains status code
	 * @return int
	 */
	public function getStatusCode() {
		return $this->status['code'];
	}
	
	/**
	 * Sets status message
	 * @param string $message
	 */
	public function setStatusMessage($message) {
		$this->status['message'] = $message;
	}
	
	/**
	 * Obtains status message
	 * @return string
	 */
	public function getStatusMessage() {
		if (array_key_exists('message', $this->status)) {
			return $this->status['message'];
		}
		
		return null;
	}
	
	/**
	 * Sets raw result
	 * @param \stdClass $rawResult
	 */
	public function setRawResult($rawResult) {
		$this->rawResult = $rawResult;
	}
	
	/**
	 * Obtains raw result
	 * @return stdClass
	 */
	public function getRawResult() {
		return $this->rawResult;
	}
}