define(
  'app/App/Environment',
  [
		'solarfield/lightship-js/src/Solarfield/Lightship/Environment',
    'solarfield/ok-kit-js/src/Solarfield/Ok/ObjectUtils'
  ],
  function (LightshipEnvironment, ObjectUtils) {
		"use strict";

		var Environment = ObjectUtils.extend(LightshipEnvironment);

	  ObjectUtils.defineNamespace('App');
		return App.Environment = Environment;
	}
);
