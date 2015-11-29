//import some Solarfield stuff as globals for convenience
if (!window.Ok) window.Ok = Solarfield.Ok;
if (!window.Batten) window.Batten = Solarfield.Batten;
if (!window.Lightship) window.Lightship = Solarfield.Lightship;




/**
 * @namespace App
 */
if (!self.App) self.App = {};

App.Environment = Ok.extendObject(Lightship.Environment);
