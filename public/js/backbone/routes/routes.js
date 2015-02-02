var app = app || {};

var routes = Backbone.Router.extend({
	routes: {
		'': 'expense',
		'expenses/:id': 'detail'
	},

	detail: function( id ){
		window.expenseId = id;
		window.stade = 'detail';

	},

	expense: function(){
		window.stade = 'expense';
	}

});

app.Route = new routes();
