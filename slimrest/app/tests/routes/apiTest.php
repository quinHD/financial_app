<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	    public function test_Post_Expense_Adds_Expense_To_Db_And_Returns_The_Id()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );
	   		$expected_answer = "OK";
	   		$minimum_id_value = 1;

		    $response = $this -> execute_http_call( "POST", $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response[ "answer" ] );
   			$this -> assertGreaterThanOrEqual( $minimum_id_value, $response[ "content" ] );
	    }

	    public function test_Post_Expense_Returns_Minus_One_If_Parameters_Are_Insufficient()
	   	{
	   		$new_expense = array( "amount" => 890.95 );
	   		$expected_answer = -1;

		    $response = $this -> execute_http_call( "POST", $new_expense );
		    
   			$this -> assertEquals( $expected_answer, $response["answer"] );
	    }

	    public function test_Get_Expense_Returns_An_Expense_From_One_Given_Id()
	    {	
	    	$expected_expense = array( "description" => "Proyector", "amount" => 599 );
	   		$expected_answer = "OK";
	    	
	    	$id_inserted = $this -> populate_db( $expected_expense );

	    	$response = $this -> execute_http_call( "GET", null, $id_inserted );
	        $expense_received = $response[ "content" ];
	        $answer_received = $response[ "answer" ];

   			$this -> assertEquals( $expected_answer, $answer_received ); 
   			$this -> assertEquals( $expected_expense[ "description" ], $expense_received[ "description" ] );
   			$this -> assertEquals( $expected_expense[ "amount" ], $expense_received[ "amount" ] ); 

	    }

	    public function test_Get_Expense_Returns_Minus_One_If_The_Id_Given_Does_Not_Exist()
	   	{
	   		$expected_answer = -1;
			$expense_id_to_get = -99;

	    	$response = $this -> execute_http_call( "GET", null, $expense_id_to_get );
	        $answer_received = $response[ "answer" ];

   			$this -> assertEquals( $expected_answer, $answer_received ); 
	    }

		public function test_Put_Change_Fields_Of_a_Given_Expense()
	    {
	   		$original_expense = array( "description" => "Silla de escritorio", "amount" => 99 );
	   		$new_fields = array( "description" => "Silla giratoria de escritorio", "amount" => 199 );
	   		$expected_expense = $new_fields;
	   		$expected_answer = "OK";

	   		$id_inserted = $this -> populate_db( $original_expense );

		   	$response = $this -> execute_http_call( "PUT", $new_fields, $id_inserted );
			$answer_received = $response[ "answer" ];

			$response = $this -> execute_http_call( "GET", null, $id_inserted );
			$expense_received = $response[ "content" ];
	        $answer_received = $response[ "answer" ];


   			$this -> assertEquals( $expected_answer, $answer_received );  
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
			$answer_received = $response[ "answer" ];

  			$this -> assertEquals( $expected_answer, $answer_received );  
	    }


	    public function test_Delete_Expense_Removes_An_Expense_From_Givem_Id()
	    {
	    	$demo_expense = array( "description" => "Archivador", "amount" => 399 );
	   		$expected_delete_answer = "OK";
	   		$expected_get_answer = -1;

	   		$id_inserted = $this -> populate_db( $demo_expense );

	    	$response = $this -> execute_http_call( "DELETE", null, $id_inserted );
	        $received_delete_answer = $response[ "answer" ];

	        $response = $this -> execute_http_call( "GET", null, $id_inserted );
	        $received_get_answer = $response[ "answer" ];

   			$this -> assertEquals( $expected_delete_answer, $received_delete_answer );
   			$this -> assertEquals( $expected_get_answer, $received_get_answer ); 

	    }

	    public function test_Delete_Expense_Returns_Minus_One_If_The_Id_Given_Does_Not_Exist()
	    {
	   		$expected_answer = -1;

	    	$response = $this -> execute_http_call( "DELETE", null, 9999999 );
	        $received_answer = $response[ "answer" ];

   			$this -> assertEquals( $expected_answer, $received_answer );
	    }

	    public function test_Get_Expenses_Returns_Ok_If_Connection_Is_Ok()
	    {
	   		$expected_answer = "OK";

	        $response = $this -> execute_http_call( "GET", null );
	        $answer_received = $response[ "answer" ];
			
   			$this -> assertEquals( $expected_answer, $answer_received ); 
	    }

	    private function populate_db( $expense )
	    {
	    	$response = $this -> execute_http_call( "POST", $expense );
	    	$id_returned = $response["content"];

	    	return $id_returned;
	    }

	    private function execute_http_call( $http_verb, $new_expense, $expense_id=null )
	    {
	    	$resource = "http://localhost:1000/slimrest/expenses/".$expense_id;
	    	
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