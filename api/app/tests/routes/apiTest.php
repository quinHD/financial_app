<?php

	class apiTest extends \PHPUnit_Framework_TestCase
	{
	    public function test_Post_Expense_Adds_Expense_To_Db_And_Returns_The_Id()
	   	{
	   		$new_expense = array( "description" => "Ordenador nuevo", "amount" => 890.95 );
	   		$minimum_id_value = 1;

		    $response = $this -> execute_http_call( "POST", $new_expense );
		    
   			$this -> assertGreaterThanOrEqual( $minimum_id_value,  $response );
	    }

	   

	    private function populate_db( $expense )
	    {
	    	$response = $this -> execute_http_call( "POST", $expense );
	    	$id_returned = $response["content"];

	    	return $id_returned;
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