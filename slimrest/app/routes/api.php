<?php
	$app->post( "/expenses/", function() use( $app )
	{
		$description = $app -> request -> post( "description" );
		$amount		 = $app -> request -> post( "amount" );

		if( isset( $description ) && isset( $amount )) 
		{
			$connection = getConnection();
			$db_handler = $connection -> prepare( "INSERT INTO expenses VALUES( null, ?, ?, NOW() )" );
			$db_handler -> bindParam( 1, $description );
			$db_handler -> bindParam( 2, $amount );
			$db_handler -> execute();
			$expense = $connection -> lastInsertId();
			$connection = null;

			$app -> response -> body( json_encode( array("answer" => "OK", "content" => $expense )));
		}
		else
		{
			$app -> response -> body( json_encode( array("answer" => "OK" )));
		}
	});


?>