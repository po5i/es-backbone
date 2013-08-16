


var esbbSimpleAppView = Backbone.View.extend({
	query: null,
	//TODO: define the containing elements you want on the page (define the layout)
	template: '\
		<div id="{{prefix}}-header">\
			<div id="{{prefix}}-search-url"></div>\
			<div id="{{prefix}}-search-bar"></div>\
			<div id="{{prefix}}-search-filters"></div>\
			<div id="{{prefix}}-date-range"></div>\
		</div>\
		<div id="{{prefix}}-left-col">\
			<div id="{{prefix}}-context-selector"></div>\
			<div id="{{prefix}}-language-selector"></div>\
			<div id="{{prefix}}-dataset-selector"></div>\
			<div id="{{prefix}}-format-selector"></div>\
		</div>\
		<div id="{{prefix}}-center-col">\
			<div id="{{prefix}}-timeline" class="esbb-timeline" style="display:none"></div>\
			<div id="{{prefix}}-sort"></div>\
			<div id="{{prefix}}-navigation"></div>\
			<div id="{{prefix}}-search-results"></div>\
		</div>\
		<div id="{{prefix}}-right-col">\
		</div>\
	',

	//TODO: customize how the results will be rendered.
	//  this is a mustache.js template (http://mustache.github.com/)
	templateResults: '\
		<h3>{{header}} [{{number}}/{{total}}]</h3>\
		{{#hits}}\
		<p class="esbb-result"> \
			<h4>{{_source.title}}</h4>\
			<div><a href="{{_source.location}}" target="_blank">{{_source.location}}</a></div>\
			<div>Context: {{_source.context}}</div>\
			<div>Format: {{_source.format}}</div>\
			<div>Language: {{_source.language}}</div>\
			<div>Authors:</div>\
			<ul>\
			{{#_source.authors}}\
				<li>{{name}}</li>\
			{{/_source.authors}}\
			</ul>\
		</p>\
		{{/hits}}\
		',


	initialize: function() {
		this.query = this.options.query;
		_.bindAll( this, 'render' );
		this.render();
	},
	
	render: function() {
		this.$el.empty();
		this.$el.html( Mustache.render( this.template, { prefix: this.options.id_prefix } ) );

		//TODO: instantiate the desired header elements and connect to the proper element ids
		//  Also don't forget to change your facetName where appropriate
		new esbbSearchURLView( { 
			model: this.query,
			baseURL: 'http://localhost:9200/aginfra/_search',
			el: '#' + this.options.id_prefix + '-search-url',
		} );
		new esbbSearchBarView( { 
			model: this.query,
			el: '#' + this.options.id_prefix + '-search-bar',
			headerName: 'Simple Search:'
		} );
		new esbbSearchFilterSelectView( { 
			model: this.query, 
			el: '#' + this.options.id_prefix + '-search-filters',
			//TODO: fields that will appear in autocomplete (full syntax is "author:gibrown", so this is really just a hit to the user
			avail_fields: [ 'context:other', 'language:es','language:en','language:hu', 'format:', 'dataset:' ]
		} );
		/*new esbbSearchDateRangePickerView( { 
			model: this.query,
			el: '#' + this.options.id_prefix + '-date-range',
			headerName: 'Date Range',
			facetName: 'date'
		} );*/

		//TODO: instantiate the desired center column elements and connect to the proper element ids
		/*new esbbSearchFacetTimelineView( { 
			facetName: 'date',
			el: '#' + this.options.id_prefix + '-timeline',
			model: this.model,
			searchQueryModel: this.query
		} );*/
		new esbbSearchResultsView( { 
			model: this.model, 
			template: this.templateResults,
			el: '#' + this.options.id_prefix + '-search-results' ,
			highlightField: 'title' //TODO: set to whatever your highlighted field name is
		} );

		new esbbSortView( {
			model: this.query, 
			el: '#' + this.options.id_prefix + '-sort' ,
			headerName: 'Sort | ',
			sorts: [ {name: 'title', data: 'title'} ]
		} );

		new esbbNavigationView( {
			model: this.query, 
			el: '#' + this.options.id_prefix + '-navigation' ,
			headerName: 'Navigate | ',
		} );

		//TODO: instantiate the desired left column elements and connect to the proper element ids
		/*new esbbSearchFacetPieView( { 
			facetName: 'author',
			el: '#' + this.options.id_prefix + '-author-pie',
			model: this.model,
			searchQueryModel: this.query
		} );
		new esbbSearchFacetSelectView( { 
			facetName: 'tag',
			headerName: 'Tags',
			el: '#' + this.options.id_prefix + '-tag-selector',
			searchQueryModel: this.query,
			model: this.model
		} );*/
		new esbbSearchFacetSelectView( { 
			facetName: 'language',
			headerName: 'Language',
			el: '#' + this.options.id_prefix + '-language-selector',
			searchQueryModel: this.query,
			model: this.model
		} );
		new esbbSearchFacetSelectView( { 
			facetName: 'context',
			headerName: 'Context',
			el: '#' + this.options.id_prefix + '-context-selector',
			searchQueryModel: this.query,
			model: this.model
		} );
		new esbbSearchFacetSelectView( { 
			facetName: 'format',
			headerName: 'Format',
			el: '#' + this.options.id_prefix + '-format-selector',
			searchQueryModel: this.query,
			model: this.model
		} );
		new esbbSearchFacetSelectView( { 
			facetName: 'dataset',
			headerName: 'Dataset',
			el: '#' + this.options.id_prefix + '-dataset-selector',
			searchQueryModel: this.query,
			model: this.model
		} );
	}

	//TODO: instantiate the desired right column elements and connect to the proper element ids

});
