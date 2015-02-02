var app = app || {};

app.Expense = Backbone.Model.extend({
	urlRoot: '/api/expenses/'
});