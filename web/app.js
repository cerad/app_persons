(function(angular) { 'use strict';

var appModule = angular.module('zaysoApp', ['ngResource','ngRoute','ngStorage',
  'appConfigModule',
  'ceradAuthModule',
  'ceradRefereeModule'
]);

appModule.config(['$routeProvider',function($routeProvider) {
  $routeProvider.
    when('/home',  { 
      templateUrl: 'home.html',
      controller:  'HomeController'}).
    otherwise({ redirectTo: '/home'});
}]);
appModule.config(['$httpProvider', function ($httpProvider) {
  $httpProvider.interceptors.push('ceradAuthInterceptor');
}]);
appModule.controller('HomeController', ['$scope',
  function($scope) 
  { 
  }
]);  
})(angular);
