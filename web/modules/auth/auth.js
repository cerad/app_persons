(function(angular) { 'use strict';
    
var authModule = angular.module('ceradAuthModule', []);

authModule.controller('CeradOAuthTokenController', 
  ['$scope', '$routeParams',
  function($scope, $routeParams) 
  {
    $scope.provider = $routeParams.provider;
    $scope.providerUrl = '/app_dev.php/oauth/tokens?provider=' + $scope.provider;
  }
]);

})(angular);


