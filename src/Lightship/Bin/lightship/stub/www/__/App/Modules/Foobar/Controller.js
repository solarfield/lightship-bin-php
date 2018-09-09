define(
	[
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (AppController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Modules.Foobar.Controller
		 */
		var Controller = ObjectUtils.extend(AppController, {
			hookup: function () {
				return Controller.super.prototype.hookup.call(this)
				.then(function () {
					console.log('Foobar controller was hooked up');

					//TODO
				});
			}
		});
		
		return Controller;
	}
);
