<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	    public function testPostExpenseAddsExpenseToDbAndReturnsTheId()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );
	   		$expected_answer = "OK";
	   		$minimum_id_value = 1;

		    $response = $this -> execute_http_call( $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response["answer"] );
   			$this -> assertGreaterThanOrEqual( $minimum_id_value, $response["content"] );
	    }

	    public function testPostExpenseReturnsMinusOneIfParametersAreInsufficient()
	   	{
	   		$new_expense = array( "amount" => 890.95 );
	   		$expected_answer = -1;

		    $response = $this -> execute_http_call( $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response["answer"] );
	    }

	    public function testGetExpenseReturnsOkIfConnectionIsOk()
	    {
	   		$expected_answer = "OK";

		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses/1" );
	        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "GET" );
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );
			
   			$this->assertEquals( $expected_answer, $response ); 
	    }

	    private function execute_http_call( $new_expense=null )
	    {
		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses" );
	    	curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "POST" );
	        if( $new_expense )
	        {
	        	curl_setopt( $curl_handler, CURLOPT_POSTFIELDS, http_build_query( $new_expense ));
	        }
	        
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );

	        $response_decoded = json_decode( $response, true );


	        return $response_decoded;
	    }
	}

?>