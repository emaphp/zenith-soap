<?php
/**
 * Tests request processing through the Zenith\SOAP\Request class
 * Author: Emmanuel Antico
 */
use Zenith\SOAP\Request;

class RequestTest extends \PHPUnit_Framework_TestCase {
	protected $parameter;
	
	public function setUp() {		
		$this->parameter = '<test><testname>RequestTest</testname><package>Zenith\SOAP</package></test>';
	}
	
	public function testConstructor() {
		$request = new Request();
		$request->setService('DummyService', 'run');
		$request->setOption('option1', 'value1');
		$request->setOption('option2', 'value2');
		$request->setParameter($this->parameter);
		
		$class = $request->getClass();
		$this->assertEquals('DummyService', $class);
		$method = $request->getMethod();
		$this->assertEquals('run', $method);
		$option = $request->getOption('option1');
		$this->assertEquals('value1', $option);
		
		$request->setClass('TestClass');
		$this->assertEquals('TestClass', $request->getClass());
		$request->setMethod('execute');
		$this->assertEquals('execute', $request->getMethod());
		
		$request->clearOption('option1');
		$option = $request->getOption('option1');
		$this->assertNull($option);
		
		$parameter = $request->getParameter();
		$this->assertEquals($this->parameter, $parameter);
				
		$parameter = $request->getParameter(Request::AS_SIMPLEXML);
		$this->assertEquals('SimpleXMLElement', get_class($parameter));
		$this->assertObjectHasAttribute('testname', $parameter);
		$this->assertEquals('RequestTest', (string) $parameter->testname);
		$this->assertObjectHasAttribute('package', $parameter);
		$this->assertEquals('Zenith\SOAP', (string) $parameter->package);
		
		$parameter = $request->getParameter(Request::AS_DOM);
		$this->assertEquals('DOMDocument', get_class($parameter));
		$test = $parameter->getElementsByTagName('testname')->item(0);
		$this->assertEquals('RequestTest', $test->nodeValue);
		$test = $parameter->getElementsByTagName('package')->item(0);
		$this->assertEquals('Zenith\SOAP', $test->nodeValue);
	}
	
	/**
	 * @expectedException \RuntimeException
	 */
	public function testParameterXMLFormat() {
		$request = new Request();
		$request->setParameter('Hello');
		libxml_use_internal_errors(true);
		$parameter = $request->getParameter(Request::AS_SIMPLEXML);
	}
	
	/**
	 * @expectedException \RuntimeException
	 */
	public function testParameterXMLFormatDOM() {
		$request = new Request();
		$request->setParameter('Hello');
		libxml_use_internal_errors(true);
		$parameter = $request->getParameter(Request::AS_DOM);
	}
}