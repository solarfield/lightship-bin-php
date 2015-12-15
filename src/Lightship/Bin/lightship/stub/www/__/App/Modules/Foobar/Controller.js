define(
	'app/App/Controller',
	[
		'app/App/Environment',
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ok'
	],
	function (Env, AppController, Ok) {
		"use strict";

		/**
		 * @class App.Modules.Foobar.Controller
		 */
		var Controller = Ok.extendObject(AppController, {
			hookup: function () {
				Controller.super.prototype.hookup.apply(this);

				console.log('Foobar controller was hooked up');
				//TODO
			}
		});

		Ok.defineNamespace('App.Modules.Foobar');
		return App.Modules.Foobar.Controller = Controller;
	}
);
