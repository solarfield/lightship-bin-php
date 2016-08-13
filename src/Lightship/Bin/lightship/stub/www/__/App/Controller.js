define(
	'app/App/Controller',
	[
		'app/App/Environment',
		'solarfield/lightship-js/src/Solarfield/Lightship/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (Env, LightshipController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Controller
		 * @extends Solarfield.Lightship.Controller
		 * @constructor
		 */
		var Controller = ObjectUtils.extend(LightshipController, {
			constructor: function (aCode, aOptions) {
				Controller.super.apply(this, arguments);

				console.log('App controller was constructed');
				//TODO
			},

			hookup: function () {
				Controller.super.prototype.hookup.apply(this);

				console.log('App controller was hooked up');
				//TODO
			},

			doTask: function () {
				Controller.super.prototype.doTask.apply(this, arguments);

				//TODO
			}
		});

		ObjectUtils.defineNamespace('App');
		return App.Controller = Controller;
	}
);
