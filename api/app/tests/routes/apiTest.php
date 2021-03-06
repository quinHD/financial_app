<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	    public function test_Post_Expense_Adds_Expense_To_Db_And_Returns_The_Id()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );
	   		$minimum_id_value = 1;

		    $response = $this -> execute_http_call( "POST", $new_expense );
		    $this -> remove_test_expense( $response );

   			$this -> assertGreaterThanOrEqual( $minimum_id_value,  $response );
	    }

	    public function test_Post_Expense_Returns_Minus_One_If_Parameters_Are_Insufficient()
	   	{
	   		$new_expense = array( "amount" => 890.95 );
	   		$expected_answer = -1;

		    $response = $this -> execute_http_call( "POST", $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response);
	    }

	    public function test_Get_Expense_Returns_An_Expense_From_One_Given_Id()
	    {	
	    	$expected_expense = array( "description" => "Proyector", "amount" => 599 );
	    	
	    	$id_inserted = $this -> populate_db( $expected_expense );

	    	$response = $this -> execute_http_call( "GET", null, $id_inserted );

	        $this -> remove_test_expense( $id_inserted );

   			$this -> assertEquals( $expected_expense[ "description" ], $response[ "description" ] );
   			$this -> assertEquals( $expected_expense[ "amount" ], $response[ "amount" ] ); 

	    }

	    public function test_Get_Expense_Returns_Minus_One_If_The_Id_Given_Does_Not_Exist()
	   	{
	   		$expected_answer = -1;
			$expense_id_to_get = -99;

	    	$response = $this -> execute_http_call( "GET", null, $expense_id_to_get );

   			$this -> assertEquals( $expected_answer, $response ); 
	    }

		public function test_Put_Change_Fields_Of_a_Given_Expense()
	    {
	   		$original_expense = array( "description" => "Silla de escritorio", "amount" => 99 );
	   		$new_fields = array( "description" => "Silla giratoria de escritorio", "amount" => 199 );
	   		$expected_expense = $new_fields;
	   		$expected_answer = 1;

	   		$id_inserted = $this -> populate_db( $original_expense );

		   	$response = $this -> execute_http_call( "PUT", $new_fields, $id_inserted );
			$answer_received = $response;

			$response = $this -> execute_http_call( "GET", null, $id_inserted );
			$expense_received = $response;

			$this -> remove_test_expense( $id_inserted );

			$this -> assertEquals( $expected_answer, $answer_received);
   			$this -> assertEquals( $expected_expense[ "description" ], $expense_received[ "description" ] );
   			$this -> assertEquals( $expected_expense[ "amount" ], $expense_received[ "amount" ] ); 
	    }

	    public function test_Put_Expense_Returns_Minus_One_If_One_Parameter_Is_Incorrect()
	   	{
	   		$original_expense = array( "description" => "Silla de escritorio", "amount" => 99 );
	   		$new_fields = array( "description" => "", "amount" => 199 );
	   		$expected_answer = -1;

	   		$id_inserted = $this -> populate_db( $original_expense );

		   	$response = $this -> execute_http_call( "PUT", $new_fields, $id_inserted );

			$this -> remove_test_expense( $id_inserted );
  			$this -> assertEquals( $expected_answer, $response );  
	    }


	    public function test_Delete_Expense_Removes_An_Expense_From_Givem_Id()
	    {
	    	$demo_expense = array( "description" => "Archivador", "amount" => 399 );
	   		$expected_delete_answer = 1;
	   		$expected_get_answer = -1;

	   		$id_inserted = $this -> populate_db( $demo_expense );

	    	$response = $this -> execute_http_call( "DELETE", null, $id_inserted );
	        $received_delete_answer = $response;

	        $response = $this -> execute_http_call( "GET", null, $id_inserted );
	        $received_get_answer = $response;

   			$this -> assertEquals( $expected_delete_answer, $received_delete_answer );
   			$this -> assertEquals( $expected_get_answer, $received_get_answer ); 

	    }

	    public function test_Delete_Expense_Returns_Minus_One_If_The_Id_Given_Does_Not_Exist()
	    {
	   		$expected_answer = -1;

	    	$response = $this -> execute_http_call( "DELETE", null, 9999999 );

   			$this -> assertEquals( $expected_answer, $response );
	    }

 		public function test_Get_Expenses_Returns_All_The_Expenses()
	    {
	    	$expected_number_rows = 3;
	    	$id_inserted_1 = $this -> populate_db( array( "description" => "Proyector", "amount" => 599 ) );
	    	$id_inserted_2 = $this -> populate_db( array( "description" => "Escritorio", "amount" => 300 ) );
	    	$id_inserted_3 = $this -> populate_db( array( "description" => "Lámpara", "amount" => 50 ) );

	        $response = $this -> execute_http_call( "GET", null );

	        $this -> remove_test_expense( $id_inserted_1 );
	        $this -> remove_test_expense( $id_inserted_2 );
	        $this -> remove_test_expense( $id_inserted_3 );
			
			$this -> assertGreaterThanOrEqual( $expected_number_rows, count( $response ));
	    }

	    private function populate_db( $expense )
	    {
	    	$response = $this -> execute_http_call( "POST", $expense );
	    	$id_returned = $response;

	    	return $id_returned;
	    }

	    private function remove_test_expense( $id )
	    {
	    	$response = $this -> execute_http_call( "DELETE", null, $id );
	    }

	    private function execute_http_call( $http_verb, $new_expense, $expense_id=null )
	    {
	    	$resource = "http://localhost/api/expenses/".$expense_id;
	    	
		    $curl_handler = curl_init( $resource );
	    	curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $curl_handler, CURLOPT_CUSTOMREQUEST, $http_verb );
	       
	        if( $new_expense )
	        	curl_setopt( $curl_handler, CURLOPT_POSTFIELDS, http_build_query( $new_expense ));

	        $response = curl_exec( $curl_handler );
	        curl_close( $curl_handler );

	        $response_decoded = json_decode( $response, true );

	        return $response_decoded;
	    }
	}

?>