(function(angular) { 'use strict';

var configModule = angular.module('appConfigModule', []);

configModule.constant('ceradApiPrefix','http://localhost:8001/app.php');

})(angular);
