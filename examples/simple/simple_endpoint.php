<?php
//TODO: Need includes for Elastica (php library: https://github.com/ruflin/Elastica/)
include "/var/www/Elastica/Exception/ExceptionInterface.php";
include "/var/www/Elastica/Exception/ClientException.php";
include "/var/www/Elastica/Client.php";
include "/var/www/Elastica/Exception/ResponseException.php";
include "/var/www/Elastica/Exception/InvalidException.php";
include "/var/www/Elastica/Exception/ConnectionException.php";
include "/var/www/Elastica/Exception/Connection/HttpException.php";
include "/var/www/Elastica/Util.php";
include "/var/www/Elastica/Response.php";
include "/var/www/Elastica/Result.php";
include "/var/www/Elastica/ResultSet.php";
include "/var/www/Elastica/Param.php";
include "/var/www/Elastica/Query.php";
include "/var/www/Elastica/Connection.php";
include "/var/www/Elastica/SearchableInterface.php";
include "/var/www/Elastica/Type.php";
include "/var/www/Elastica/Request.php";
include "/var/www/Elastica/Search.php";
include "/var/www/Elastica/Index.php";
include "/var/www/Elastica/Transport/AbstractTransport.php";
include "/var/www/Elastica/Transport/Http.php";

//TODO: define array of ES servers
global $es_servers;
$es_servers = array(
					    'servers' => array(
					        //array('host' => '200.126.23.173', 'port' => 9200)
					        array('host' => 'localhost', 'port' => 9200)
					    )
					);

//OPTIONAL: only allow a whitelist of particular indices and particular fields within those indices
//  also defines highlighting and which fields to return.
/*$whitelist_idx = array( 
	'index/type' => array( 
							'highlight' => array( 'title' => array( 'fragment_size' => '50', 'number_of_fragments' => 3 ) ), 
							'fields' => array( 'title', 'language', 'context', 'format' ) 
						),
);*/

if ( empty( $_REQUEST['query'] ) || empty( $_REQUEST['idx'] ) || empty( $_REQUEST['type'] ) ) {
	header("HTTP/1.0 400 Bad Request"); //improper request
	die;
}

$idx = $_REQUEST['idx'];
$type = $_REQUEST['type'];
$query = str_replace( '\\', '', $_REQUEST['query'] );

$idx_type = $idx;
if ( '' !== $type ) 
	$idx_type .= '/' . $type;

//OPTIONAL: uncomment to enable whitelisting
//if ( ! in_array( $idx_type, array_keys( $whitelist_idx ) ) ) {
//	header("HTTP/1.0 403 Forbidden"); //forbidden
//	die;
//}

try{
	$esclient = new \Elastica\Client( array( 'servers' => $es_servers ) );
	$esQ = new \Elastica\Query();
	$esQ->setRawQuery( get_object_vars( json_decode( $query ) ) );
	if ( isset( $whitelist_idx[ $idx_type ] ) ) {
		$esQ->setHighlight( array( 'fields' => $whitelist_idx[ $idx_type ][ 'highlight' ], 
			'pre_tags' => array( '<b>' ),
			'post_tags' => array( '</b>' ) ) );
		$esQ->setFields( $whitelist_idx[ $idx_type ][ 'fields' ] );
	}
	if ( '' != $type )
		$estype = $esclient->getIndex( $idx )->getType( $type );
	else
		$estype = $esclient->getIndex( $idx );

	$results = $estype->search( $esQ );
	echo json_encode( $results->getResponse()->getData() );
}
catch ( Exception $e ){
	error_log( $e->getMessage() );
	header("HTTP/1.0 500 Server Error"); //server error
	echo json_encode( array( 'error' => 'query_error: ' . $e->getMessage() ) );
}
