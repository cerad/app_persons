(function(angular) { 'use strict';
    
var authModule = angular.module('ceradAuthModule', []);

authModule.controller('CeradOAuthTokenController',
  ['$scope', '$routeParams', 'cerapApiPrefix',
  function($scope, $routeParams, apiPrefix) 
  {
    $scope.provider = $routeParams.provider;
    $scope.providerUrl = aprPrefix + 'oauth/tokens?provider=' + $scope.provider;
  }
]);
authModule.controller('CeradLoginController', ['$scope','$window','ceradApiPrefix',
  function($scope,$window,ceradApiPrefix) 
  { 
    var authWindow;
    
    $scope.oauth = function(provider)
    {
      var url = ceradApiPrefix + '/oauth/tokens?provider=' + provider;
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


