define(
	[
		'app/App/Controller',
		'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
	],
	function (AppController, ObjectUtils) {
		"use strict";

		/**
		 * @class App.Modules.Foobar.Controller
		 * @extends App.Controller
		 */
		var Controller = ObjectUtils.extend(AppController, {
			onHookup: function (aEvt) {
				// do the parent/app's hookup, AND some other stuff in PARALLEL,
				// then do some finalization once everything else is complete.
				// Note that we do NOT call aEvt.waitUntil(), so none of the hookup code here will delay
				// the hook-up event timeline (i.e. onDoTask() will NOT wait)

				return Promise.all([
					// app's hookup
					Controller.super.prototype.onHookup.call(this, aEvt),

					// other stuff
					new Promise(function (resolve) {
						// do some async stuff that is independent of the app's hookup
						// e.g. load stylesheets, or other assets, etc.
						setTimeout(function () {
							resolve();
						}, 2000);
					}),
				])
					.then(function () {
						// finalization
						console.log('Foobar is ready');
					});
			}
		});

		return Controller;
	}
);
