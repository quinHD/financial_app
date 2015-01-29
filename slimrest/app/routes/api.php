<?php
	$app->post( "/expenses/", function() use( $app )
	{
		$description = $app -> request -> post( "description" );
		$amount		 = $app -> request -> post( "amount" );

		try 
		{
			$connection = getConnection();
			$db_handler = $connection -> prepare( "INSERT INTO expenses VALUES( null, ?, ?, NOW() )" );
			$db_handler -> bindParam( 1, $description );
			$db_handler -> bindParam( 2, $amount );
			$db_handler -> execute();
			$expense_id = $connection -> lastInsertId();
			$connection = null;

			$app -> response -> body( json_encode( array( "answer" => "OK", "content" => $expense_id )));
		}
		catch( PDOException $e )
		{
			$app -> response -> body( json_encode( array( "answer" => -1)));	
		}		
	});


	$app->get( "/expenses/:id", function( $id ) use( $app )
	{
		$connection = getConnection();
		$dbh = $connection-> prepare("SELECT * FROM expenses WHERE id = ?");
		$dbh->bindParam(1, $id);
		$dbh->execute();
		$expense = $dbh->fetch();
		$connection = null;

		$app -> response -> body( json_encode( array( "answer" => "OK", "content" => $expense)));
	});

?>