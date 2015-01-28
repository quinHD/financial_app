<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	   	public function testPostExpenseReturnsOkIfConnectionIsOk()
	   	{
	   		$expected_answer = "OK";

		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses" );
	        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "POST" );
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );
			
   			$this->assertEquals( $expected_answer, $response );
	    }

	    public function testPostExpenseAddsExpenseToDbAndReturnsTheId()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );

		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses" );
	        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "POST" );
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );
			
   			$this->assertGreaterThanOrEqual( 1, $response );
	    }
	}

?>