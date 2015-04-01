(function(angular) { 'use strict';
    
var authModule = angular.module('ceradAuthModule', []);

authModule.config(['$routeProvider',function($routeProvider) {
  $routeProvider.
    when('/login', { 
      templateUrl: 'modules/auth/login.html', 
      controller:  'CeradLoginController as login'});
}]);

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

/* ========================
 * How much of this can be moved to the authManager?
 */
authModule.controller('CeradLoginController', 
  ['$scope','$window','$http','$location','ceradApiPrefix','ceradAuthManager',
  function($scope,$window,$http,$location,apiPrefix,authManager) 
  { 
    var self = this;
    
    var oauthWindow;
    
    self.oauthConnect = function(provider)
    {
      var url = apiPrefix + '/oauth/tokens?provider=' + provider;
      oauthWindow = $window.open(url,'_blank', 'height=600, width=600, top=100, left=300, modal=yes');
      oauthWindow.focus();
    };
    $window.oauthCallback = function(oauthToken) 
    {
      oauthWindow.close();
      oauthWindow = null;
      authManager.oauthToken = oauthToken;
      
      self.oauthSubmit();
    };
    self.oauthSubmit = function()
    {
      var oauthToken = authManager.oauthToken;
      
      var url = apiPrefix + '/auth/tokens';
      var payload = { oauth_token: oauthToken };
      
      $http.post(url,payload).success(authTokenSuccess);

      // Handle unauthenticated stuff
    };
    // Form login
    self.userLoginInfo = authManager.userLoginInfo;
    
    self.loginSubmit = function()
    {
      var url = apiPrefix + '/auth/tokens';
      
      var payload = 
      { username: self.userLoginInfo.username, 
        password: self.userLoginInfo.password };
      
      $http.post(url,payload).success(authTokenSuccess);
    };
    var authTokenSuccess = function(data)
    {
      var userData = angular.fromJson(data);
      console.log('User  ' + userData.username);
      console.log('Roles ' + userData.roles);
      console.log('Token ' + userData.auth_token);
        
      authManager.authToken = userData.auth_token;
        
      delete userData.auth_token;
        
      authManager.authUser = userData;
      
      if (self.userLoginInfo.rememberMe) authManager.userLoginInfo = self.userLoginInfo;
      else                               authManager.userLoginInfo = null;
      
      $location.url('/home');
    };
  }
]);
authModule.controller('CeradUserInfoController', 
['$scope','$location','ceradAuthManager',
function($scope,$location,authManager) 
{ 
  var self = this;
  
  self.user = authManager.authUser;
  
  self.logout = function()
  {
    authManager.logout(); // Tell server?
  };
  self.login = function()
  {
    $location.url('/login');
  };
  $scope.$on('userChanged',function() // Destroy?
  {
    self.user = authManager.authUser;
  });
}]);

// Want to be able to configure this for different storage
/* So far there is nothing to actually manage
 * This is actualy an authStorage service
 */
authModule.factory('ceradAuthManager',['$rootScope','$sessionStorage',
function($rootScope,storage)
{
  var manager = 
  {
    set oauthToken(token) { storage.oauthToken = token; },
    get oauthToken() { return storage.oauthToken; },
      
    set authToken(token) { storage.authToken = token; },
    get authToken() { return storage.authToken; },
      
    set authUser(user) 
    { 
      storage.authUser = user;
      $rootScope.$broadcast('userChanged');
    },
    get authUser() { return storage.authUser; },
    
    set userLoginInfo(info) 
    { 
      storage.userLoginInfo = info;
    },
    get userLoginInfo() 
    {
      var info = storage.userLoginInfo;
      if (angular.isObject(info)) return info;
      return { username: null, password: null, rememberMe : false };
    },

    logout: function()
    {
      delete storage.oauthToken;
      delete storage.authToken;
      delete storage.authUser;
      $rootScope.$broadcast('userChanged');
    },
  };
  return manager;
}]);
})(angular);


