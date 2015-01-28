<?php

	$app->post( "/expenses/", function() use( $app )
	{
		$app->response->body( "OK" );
	});

?>