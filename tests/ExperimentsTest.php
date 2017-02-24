<?php


namespace GD\Tests;


class ExperimentsTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 */
	public function testScenarioExampleDepends() {
		$this->assertTrue(true);
	}
	
    /**
     * @depends testScenarioExampleDepends
     */
     public function testShouldGetToStepOne() {
     	$this->assertTrue(true);
     }
     
     /**
      * @depends testShouldGetToStepOne
      */
      public function testShouldGetToStepTwo() {
      	$this->assertTrue(true);
      }
      
      /**
       * @depends testShouldGetToStepTwo
       */
       public function testShouldFailSinceNotComplete() {
       	 $this->markTestIncomplete("This should fail the tests");
       }
     
	
	
}