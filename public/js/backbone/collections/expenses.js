var app = app || {};

var ExpensesCollection = Backbone.Collection.extend({
	model: app.Expense,
	url: '/api/expenses/'
});

app.expenses = new ExpensesCollection();