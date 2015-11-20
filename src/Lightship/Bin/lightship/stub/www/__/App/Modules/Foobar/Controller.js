"use strict";

/**
 * @namespace App.Modules.Foobar
 * @globals App.Modules.Foobar
 */
Ok.defineNamespace('App.Modules.Foobar');




/**
 * @class App.Modules.Foobar.Controller
 */
App.Modules.Foobar.Controller = Ok.extendObject(App.Controller, {
	construct: function () {
		App.Controller.apply(this, arguments);

		//TODO
	},

	hookup: function () {
		App.Controller.prototype.hookup.apply(this);

		//TODO
	}
});
