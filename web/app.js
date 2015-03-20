(function(angular) { 'use strict';

var zaysoApp = angular.module('zaysoApp', ['ngResource','ngRoute',
  'zaysoApp.refereeComponent',
  'ceradAuthModule'
]);

zaysoApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/home', {
        templateUrl: 'home.html',
        controller:  'HomeController'
      }).
      when('/referees', {
        templateUrl: 'modules/referee/referee-list.html',
        controller:  'RefereeListController'
      }).
      when('/referees/:id/show', {
        templateUrl: 'modules/referee/referee-show.html',
        controller:  'RefereeShowController'
      }).
      when('/referees/:id/update', {
        templateUrl: 'modules/referee/referee-update.html',
        controller:  'RefereeUpdateController'
      }).
      when('/referees/insert', {
        templateUrl: 'modules/referee/referee-insert.html',
        controller:  'RefereeInsertController'
      }).
      when('/oauth/tokens', {
        templateUrl: 'modules/auth/oauth-token.html',
        controller:  'CeradOAuthTokenController'
      }).
      otherwise({
        redirectTo: '/home'
      });
  }]);
  zaysoApp.controller('HomeController', ['$scope',
  function($scope) 
  { 
  }
]);  
})(angular);
