<?php
/**
 * Tests response construction through the Zenith\SOAP\Response class
 * Author: Emmanuel Antico
 */
use Zenith\SOAP\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase {
	protected $result;
	
	public function setUp() {
		$this->result = '<test><testname>RequestTest</testname><package>Zenith\SOAP</package></test>';
	}
	
	public function testBuild() {
		$response = new Response();
		$response->setService('TestClass', 'TestMethod');
		$response->setStatus(1, 'Unexpected error');
		$response->setResult($this->result);
		
		$this->assertEquals('TestClass', $response->getClass());
		$this->assertEquals('TestMethod', $response->getMethod());
		$this->assertEquals(1, $response->getStatusCode());
		$this->assertEquals('Unexpected error', $response->getStatusMessage());
		$this->assertEquals($this->result, $response->getResult());
		
		$response->setClass('DummyService');
		$this->assertEquals('DummyService', $response->getClass());
		$response->setMethod('run');
		$this->assertEquals('run', $response->getMethod());
		
		$response->setStatusCode(3);
		$this->assertEquals(3, $response->getStatusCode());
		$response->setStatusMessage('Oops!!!');
		$this->assertEquals('Oops!!!', $response->getStatusMessage());
		
		$xml = $response->getResult(Response::AS_SIMPLEXML);
		$this->assertEquals('SimpleXMLElement', get_class($xml));
		$this->assertObjectHasAttribute('testname', $xml);
		$this->assertEquals('RequestTest', (string) $xml->testname);
		$this->assertObjectHasAttribute('package', $xml);
		$this->assertEquals('Zenith\SOAP', (string) $xml->package);
		
		$dom = $response->getResult(Response::AS_DOM);
		$this->assertEquals('DOMDocument', get_class($dom));
		$test = $dom->getElementsByTagName('testname')->item(0);
		$this->assertEquals('RequestTest', $test->nodeValue);
		$test = $dom->getElementsByTagName('package')->item(0);
		$this->assertEquals('Zenith\SOAP', $test->nodeValue);
	}
	
	/**
	 * @expectedException \RuntimeException
	 */
	public function testResultXMLFormat() {
		$response = new Response();
		$response->setResult('Hello');
		libxml_use_internal_errors(true);
		$result = $response->getResult(Response::AS_SIMPLEXML);
	}
	
	/**
	 * @expectedException \RuntimeException
	 */
	public function testResultXMLFormatDOM() {
		$response = new Response();
		$response->setResult('Hello');
		libxml_use_internal_errors(true);
		$result = $response->getResult(Response::AS_DOM);
	}
}