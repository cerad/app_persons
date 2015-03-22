(function(angular) { 'use strict';
    
var authModule = angular.module('ceradAuthModule', []);

authModule.factory('ceradAuthInterceptor', ['$q', 'ceradAuthManager', function ($q, authManager) {
  return {
    request: function (config) {
      config.headers = config.headers || {};
      if (authManager.authToken) {
        // Was prefixed with 'Bearer '
        config.headers.Authorization = '' + authManager.authToken;
      }
      return config;
    },
    response: function (response) {
      if (response.status === 401) {
        // handle the case where the user is not authenticated
        alert('401 response ');
      }
      return response || $q.when(response);  //???
    }
  };
}]);
authModule.controller('CeradOAuthTokenController',
  ['$scope', '$routeParams', 'cerapApiPrefix',
  function($scope, $routeParams, apiPrefix) 
  {
    $scope.provider = $routeParams.provider;
    $scope.providerUrl = apiPrefix + 'oauth/tokens?provider=' + $scope.provider;
  }
]);
authModule.controller('CeradLoginController', 
  ['$scope','$window','$http','$sessionStorage','ceradApiPrefix','ceradAuthManager',
  function($scope,$window,$http,$sessionStorage,apiPrefix,authManager) 
  { 
    var oauthWindow;
    
    $scope.oauthConnect = function(provider)
    {
      var url = apiPrefix + '/oauth/tokens?provider=' + provider;
      oauthWindow = $window.open(url,'_blank', 'height=600, width=600, top=100, left=300, modal=yes');
      oauthWindow.focus();
    };
    $window.oauthCallback = function(oauthToken) 
    {
      oauthWindow.close();
      
      // Now use the oauthToken to get a real token
      console.log('Callback ' . oauthToken);
      authManager.oauthToken = oauthToken;
    };
    $scope.oauthSubmit = function()
    {
      var oauthToken = authManager.oauthToken;
      console.log('OAuthToken ' + oauthToken);
      
      var url = apiPrefix + '/auth/tokens';
      
      $http.post(url,{ oauthToken: oauthToken })
      .success(function(data)
      {
        var userData = angular.fromJson(data);
        console.log('User  '     + userData.username);
        console.log('Roles '     + userData.roles);
        console.log('AuthToken ' + userData.authToken);
        
        authManager.authToken = userData.authToken;
        
        delete userData.authToken;
        
        authManager.authUser = userData;
        
      });
      // Handle unauthenticated stuff
    }
  }
]);
// Want to be able to configure this for different storage
authModule.factory('ceradAuthManager',['$sessionStorage',
  function(storage)
  {
    var userLocal;
    
    var manager = 
    {
      set oauthToken(token) { storage.oauthToken = token; },
      get oauthToken() { return storage.oauthToken; },
      
      set authToken(token) { storage.authToken = token; },
      get authToken() { return storage.authToken; },
      
      // Maybe should keep a local copy?
      set authUser(user) { storage.authUser = user; },
      get authUser() { return storage.authUser; },
      
      reset: function()
      {
        delete storage.oauthToken;
        delete storage.authToken;
        delete storage.authUser;
      }
    };
    return manager;
  }]);
})(angular);


