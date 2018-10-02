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
		 */
		var Controller = ObjectUtils.extend(LightshipController, {
			/**
			 * @constructor
			 */
			constructor: function (aEnvironment, aCode, aContext, aOptions) {
				Controller.super.call(this, aEnvironment, aCode, aContext, aOptions);
				console.log('App controller was constructed');
			},

			onHookup: function (aEvt) {
				// wait until the parent/lightship hookup is complete, then do some app-level hookup
				// Note that we call aEvt.waitUntil(), to extend the hookup event lifetime.
				// i.e. onDoTask() will wait until this hookup stuff is complete.
				// The returned promise represents the app's hookup process. Modules which override this
				// method, can integrate the promise into their timeline.
				return aEvt.waitUntil(Controller.super.prototype.onHookup.call(this, aEvt)
					.then(function () {
						console.log('App controller was hooked up');
					}));
			},

			onDoTask: function (aEvt) {
				Controller.super.prototype.onDoTask.call(this, aEvt);
				console.log('App did its task');
			}
		});

		return Controller;
	}
);
