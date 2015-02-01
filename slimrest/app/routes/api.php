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
			$app -> response -> body( json_encode( array( "answer" => -1 )));	

	});

	$app->get( "/expenses/", function() use( $app )
	{
		$app -> response -> body( json_encode( array( "answer" => "OK" )));

	});

	$app->put( "/expenses/:id", function( $id ) use( $app )
	{
		$description = $app->request->put( "description" );
		$amount 	 = $app->request->put( "amount" );
		
		if( argument_is_invalid( $description ) || argument_is_invalid( $amount ))
		{
			$app -> response -> body( json_encode( array( "answer" => -1 )));	
		}
		else
		{
			run_update( $description, $amount, $id );
			$app -> response -> body( json_encode( array( "answer" => "OK" )));
		}
	});


	$app->delete( "/expenses/:id", function( $id ) use( $app )
	{
		$row_count = run_delete( $id );

		if ($row_count > 0)
			$app -> response -> body( json_encode( array( "answer" => "OK" )));
		else
			$app -> response -> body( json_encode( array( "answer" => -1 )));

	});


	function run_delete( $id )
	{
		$connection = getConnection();
		$dbh = $connection-> prepare( "DELETE FROM expenses WHERE id = ?" );
		$dbh->bindParam( 1, $id );
		$dbh->execute();
		$connection = null;

		return $dbh->rowCount();
	}

	function run_update( $description, $amount, $id )
	{
		$connection = getConnection();
		$dbh = $connection-> prepare( "UPDATE expenses SET description = ?, amount = ?, created_at = NOW() WHERE id = ?" );
		$dbh->bindParam( 1, $description );
		$dbh->bindParam( 2, $amount );
		$dbh->bindParam( 3, $id );
		$dbh->execute();
		$connection = null;
	}

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

	function argument_is_invalid( $var )
	{
		return ( ctype_space( $var ) || $var == "" );
	}

?>