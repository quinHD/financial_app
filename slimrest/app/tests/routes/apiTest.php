<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	    public function test_Post_Expense_Adds_Expense_To_Db_And_Returns_The_Id()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );
	   		$expected_answer = "OK";
	   		$minimum_id_value = 1;

		    $response = $this -> execute_http_call( $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response["answer"] );
   			$this -> assertGreaterThanOrEqual( $minimum_id_value, $response["content"] );
	    }

	    public function test_Post_Expense_Returns_Minus_One_If_Parameters_Are_Insufficient()
	   	{
	   		$new_expense = array( "amount" => 890.95 );
	   		$expected_answer = -1;

		    $response = $this -> execute_http_call( $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response["answer"] );
	    }

	    public function test_Get_Expense_Returns_Ok_If_Connection_Is_Ok()
	    {
	   		$expected_answer = "OK";

		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses/1" );
	        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "GET" );
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );

   			$this->assertEquals( $expected_answer, $response ); 
	    }

	    public function test_Get_Expense_Returns_An_Expense_From_One_Given_Id()
	    {	
	    	$expected_expense = array( "description" => "Proyector", "amount" => 599 );
	    	
	    	$id_inserted = $this -> populate_db( $expected_expense );

		    $curl_handler = curl_init( "http://localhost:1000/slimrest/expenses/".$id_inserted );
	        curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, "GET" );
	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );
	        $response_decoded = json_decode( $response, true );

	        $expense_received = $response_decoded["content"];

   			$this->assertEquals( $expected_expense["description"], $expense_received["description"] ); 
	    }

	    private function populate_db( $new_expense )
	    {
	    	$response = $this -> execute_http_call( $new_expense );
	    	$id_expense = $response["content"];

	    	return $id_expense;
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