"use strict";

/**
 * @namespace App
 */
if (!self.App) self.App = {};




/**
 * @class App.Controller
 * @extends Solarfield.Lightship.Controller
 * @constructor
 */
App.Controller = Ok.extendObject(Lightship.Controller, {
	constructor: function (aCode, aOptions) {
		Lightship.Controller.apply(this, arguments);

		//TODO
	},

	hookup: function () {
		Lightship.Controller.prototype.hookup.apply(this);

		//TODO
	},

	go: function () {
		Lightship.Controller.prototype.go.apply(this, arguments);

		//TODO
	}
});
