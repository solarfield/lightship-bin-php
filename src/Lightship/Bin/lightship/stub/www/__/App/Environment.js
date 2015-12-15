define(
  'app/App/Environment',
  [
		'solarfield/lightship-js/src/Solarfield/Lightship/Environment',
    'solarfield/ok-kit-js/src/Solarfield/Ok/ok'
  ],
  function (LightshipEnvironment, Ok) {
		"use strict";

		var Environment = Ok.extendObject(LightshipEnvironment);

		Ok.defineNamespace('App');
		return App.Environment = Environment;
	}
);
