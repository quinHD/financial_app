var app = app || {};

var routes = Backbone.Router.extend({
	routes: {
		'': 'expense',
		'expenses/:id': 'detail'
	},

	expense: function(){
		window.stade = 'expense';
	}

});

app.Route = new routes();
