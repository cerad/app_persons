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
    when('/login', { 
      templateUrl: 'modules/auth/login.html', 
      controller:  'CeradLoginController'}).
    when('/referees', { 
      templateUrl: 'modules/referee/referee-list.html',
      controller:  'RefereeListController'}).
    when('/referees/:id/show', {
      templateUrl: 'modules/referee/referee-show.html',
      controller:  'RefereeShowController'}).
    when('/referees/:id/update', {
      templateUrl: 'modules/referee/referee-update.html',
      controller:  'RefereeUpdateController'}).
    when('/referees/insert', {
      templateUrl: 'modules/referee/referee-insert.html',
      controller:  'RefereeInsertController'}).
    when('/oauth/tokens', {
      templateUrl: 'modules/auth/oauth-token.html',
      controller:  'CeradOAuthTokenController'}).
    otherwise({ redirectTo: '/home'});
  }]);
appModule.controller('HomeController', ['$scope',
  function($scope) 
  { 
  }
]);  
})(angular);
