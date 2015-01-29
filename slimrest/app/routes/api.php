<?php

	$app->post( "/expenses/", function() use( $app )
	{
		$description = $app -> request -> post( "description" );
		$amount		 = $app -> request -> post( "amount" );

		try 
		{
			$expense_inserted_id = run_insert( $description, $amount );
			$app -> response -> body( json_encode( array( "answer" => "OK", "content" => $expense_inserted_id )));
		}
		catch( PDOException $e )
		{
			$app -> response -> body( json_encode( array( "answer" => -1)));	
		}		
	});

	$app->get( "/expenses/:id", function( $id ) use( $app )
	{
		$expense = run_select( $id );
		
		if( $expense )
			$app -> response -> body( json_encode( array( "answer" => "OK", "content" => $expense )));
		else
			$app -> response -> body( json_encode( array( "answer" => -1)));	

	});

	function run_select( $id )
	{
		$connection = getConnection();
		$dbh = $connection -> prepare( "SELECT * FROM expenses WHERE id = ?" );
		$dbh -> bindParam( 1, $id );
		$dbh -> execute();
		$expense = $dbh -> fetch();
		$connection = null;

		return $expense;
	}

	function run_insert( $description, $amount )
	{
		$connection = getConnection();
		$db_handler = $connection -> prepare( "INSERT INTO expenses VALUES( null, ?, ?, NOW() )" );
		$db_handler -> bindParam( 1, $description );
		$db_handler -> bindParam( 2, $amount );
		$db_handler -> execute();
		$expense_id = $connection -> lastInsertId();
		$connection = null;

		return $expense_id;
	}

?>