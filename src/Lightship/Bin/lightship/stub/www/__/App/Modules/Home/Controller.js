define(
	[
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (AppController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Modules.Home.Controller
		 * @extends App.Controller
		 */
		var Controller = ObjectUtils.extend(AppController, {
			/**
			 * @constructor
			 */
			constructor: function (aEnvironment, aCode, aContext, aOptions) {
				Controller.super.call(this, aEnvironment, aCode, aContext, aOptions);
				console.log('Home controller was constructed');
			},

			onHookup: function (aEvt) {
				// wait until the parent/app's hookup is complete, then do some module-level hookup
				// Note that we call aEvt.waitUntil(), to extend the hookup event lifetime.
				// i.e. onDoTask() will wait until this hookup stuff is complete.
				return aEvt.waitUntil(Controller.super.prototype.onHookup.call(this, aEvt)
					.then(function () {
						console.log('Home controller was hooked up');
					}));
			}
		});

		return Controller;
	}
);
