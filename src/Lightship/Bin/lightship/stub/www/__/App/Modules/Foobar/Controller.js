define(
	'app/App/Modules/Foobar/Controller',
	[
		'app/App/Environment',
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (Env, AppController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Modules.Foobar.Controller
		 */
		var Controller = ObjectUtils.extend(AppController, {
			hookup: function () {
				Controller.super.prototype.hookup.apply(this);

				console.log('Foobar controller was hooked up');
				//TODO
			}
		});

		ObjectUtils.defineNamespace('App.Modules.Foobar');
		return App.Modules.Foobar.Controller = Controller;
	}
);
