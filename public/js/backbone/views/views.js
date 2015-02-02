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
		'click h3': 'detail',
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

		app.Route.on( 'route:detail', function(){
			self.render();
		});
	},

	render: function() {
		if( window.stade === "expense")
		{
			$('.expenses').show();
			$('.detail').hide();
			this.$el.html( this.template( this.model.toJSON() ));
		}else if( window.stade === 'detail' )
		{
			$('.detail').show();
			$('.expenses').hide();
			if(this.model.get('id') === window.expenseId){

				new app.DetailExpenseView({model: this.model});
			}
		}
		return this;
	},

	detail: function() {
		Backbone.history.navigate('expenses/' + this.model.get('id'), {trigger: true} );
	}
});

app.DetailExpenseView = Backbone.View.extend({
	el: ".detail",
	template: _.template( $( '#tplShowDetailExpense').html() ),

	events: {
		'click .backToExpenses': 'backToExpenses'
	},

	initialize: function() {
		this.render();
	},

	render: function() {
		this.$el.html( this.template( this.model.toJSON() ));
	},

	backToExpenses: function() {
		Backbone.history.navigate('', {trigger:true})
	}


});
