<html>
<body>

<!-- TODO: i'm not a designer, you may want to update the style sheet, 
        very basic, please contribute improvements! :) -->
<link rel="stylesheet" type="text/css" href="css/simple.css?m=2012-06-18" />
<link rel="stylesheet" href="../../js/jquery-ui-1.8.21.custom.css" />
<link rel="stylesheet" href="../../js/select2.css" />

<!-- Load JS libraries -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

<script type="text/javascript" src="../../js/mustache.js"></script>
<script type="text/javascript" src="../../js/underscore-min.js"></script>
<script type="text/javascript" src="../../js/backbone-min.js"></script>
<script type="text/javascript" src="../../js/spin.min.js"></script>
<script type="text/javascript" src="../../js/spin.js"></script>
<script type="text/javascript" src="../../js/jquery.spin.js"></script>
<script type="text/javascript" src="../../js/jquery.flot.min.js"></script>
<script type="text/javascript" src="../../js/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="../../js/jquery.flot.selection.min.js"></script>
<script type="text/javascript" src="../../js/jquery.deparam.js"></script>

<script type="text/javascript" src="../../js/select2.js"></script>

<script type="text/javascript" src="../../es-backbone.js?m=2012-06-18"></script>
<script type="text/javascript" src="simple-view.js?m=2012-06-18"></script>

<script>

(function($) {
$(function(){

var esbbSimpleSearchResults = new esbbSearchResultsModel( );

//TODO: the QueryModel defines the query that will be passed to your server.
// At a minimum you should change the field names, and ensure that you define all of the facets
// that your display will depend on.
var esbbSimpleSearchQuery = new esbbSearchQueryModel( {
	size : 10,
	from : 0,
	query : {
		filtered : {
			query : { 
				query_string: {
					fields: [ "title" ],	//"content", "title", "tag"
					query: "",
					default_operator: "AND"
			} },
			filter : {
				match_all: { }
			}
	} },
	facets : {
		language : {
			terms : {
				field : "language",
				"size" : 20
			}
		},
		context : {
			terms : {
				field : "context",
				"size" : 20
			}
		},
		format : {
			terms : {
				field    : "format",
				"size" : 20
			}
		},
		dataset : {
			terms : {
				field    : "dataset",
				"size" : 20
			}
		}
	}
} );
esbbSimpleSearchQuery.resultsModel = esbbSimpleSearchResults;

//TODO: define the url for your ES endpoint, index name, and doc type name
esbbSimpleSearchQuery.ajax_url = 'http://localhost/es-backbone/examples/simple/simple_endpoint.php';
esbbSimpleSearchQuery.index = 'aginfra';
esbbSimpleSearchQuery.index_type = 'aginfra';

	var esbbSimpleApp = new esbbSimpleAppView( { 
		model: esbbSimpleSearchResults, 
		query: esbbSimpleSearchQuery, 
		el: '#esbb-simple-app',
		id_prefix: 'esbb-simple'
	} );
	
});
})(jQuery);

</script>

<div id='esbb-simple-app'></div>

</body>
</html>
