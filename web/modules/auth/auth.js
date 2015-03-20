(function(angular) { 'use strict';
    
var authModule = angular.module('ceradAuthModule', []);

authModule.controller('CeradOAuthTokenController', 
  ['$scope', '$routeParams',
  function($scope, $routeParams) 
  {
    $scope.provider = $routeParams.provider;
    $scope.providerUrl = '/' + $scope.provider + '.html';
  }
]);

})(angular);


