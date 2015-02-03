var app = app || {};

app.ExpenseView = Backbone.View.extend({
	el: "#app",

	events: {
		'click #create': 'createExpense'
	},

	initialize: function() {
		this.listenTo( app.expenses, 'add', this.showExpense );
		this.listenTo( app.expenses, 'remove', this.resetExpense );
		app.expenses.fetch();
	},

	showExpense: function( model ) {
		var vista = new app.ShowExpenseView({
			model: model
		});
		$('.expenses').append( vista.render().$el);
	},

	createExpense: function() {
		app.expenses.create({
			"description": $( '#input_description' ).val(),
			"amount": $( '#input_amount' ).val()
		});
	},

	resetExpense: function() {
		this.$('.expenses').html('');
		app.expenses.each(this.showExpense, this)
	}
});

app.ShowExpenseView = Backbone.View.extend({
	template: _.template( $( '#tplShowExpense').html() ),

	events: {
		'click .delete': 'deleteExpense'
	},

	deleteExpense: function() {
		this.model.destroy();
	},

	initialize: function() {
		var self = this;
		app.Route.on( 'route:expense', function(){
			self.render();
		});
	},

	render: function() {
		this.$el.html( this.template( this.model.toJSON() ));
		
		return this;
	}
});

