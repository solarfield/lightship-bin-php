define(
	[
		'solarfield/lightship-js/src/Solarfield/Lightship/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (LightshipController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Controller
		 * @extends Solarfield.Lightship.Controller
		 * @constructor
		 */
		var Controller = ObjectUtils.extend(LightshipController, {
			constructor: function (aEnvironment, aCode, aContext, aOptions) {
				Controller.super.call(this, aEnvironment, aCode, aContext, aOptions);

				console.log('App controller was constructed');
				//TODO
			},

			hookup: function () {
				return Controller.super.prototype.hookup.call(this)
				.then(function () {
					console.log('App controller was hooked up');
					//TODO
				});
			},

			doTask: function () {
				Controller.super.prototype.doTask.apply(this, arguments);

				//TODO
			}
		});
		
		return Controller;
	}
);
