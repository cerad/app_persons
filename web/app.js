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
  zaysoApp.controller('HomeController', ['$scope','$window',
  function($scope,$window) 
  { 
    var authWindow;
    
    $scope.oauth = function(provider)
    {
      var url = '/app_dev.php/oauth/tokens?provider=' + provider;
      authWindow = $window.open(url,'_blank', 'height=400, width=300, top=100, left=300, modal=yes');
      authWindow.focus();
    };
    $window.oauthCallback = function(oauthInfo) 
    {
      authWindow.close();
      
      // Now use the oauthToken to get a real token
      console.log(oauthInfo);
    };
  }
]);  
})(angular);
