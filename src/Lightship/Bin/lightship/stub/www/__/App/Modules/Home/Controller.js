define(
	'app/App/Modules/Home/Controller',
	[
		'app/App/Environment',
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (Env, AppController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Modules.Home.Controller
		 */
		var Controller = ObjectUtils.extend(AppController, {
			construct: function () {
				Controller.super.apply(this, arguments);

				//TODO
			},

			hookup: function () {
				Controller.super.prototype.hookup.apply(this);

				console.log('Home controller was hooked up');
				//TODO
			}
		});

		ObjectUtils.defineNamespace('App.Modules.Home');
		return App.Modules.Home.Controller = Controller;
	}
);
