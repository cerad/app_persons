(function(angular) { 'use strict';
    
var refereeModule = angular.module('ceradRefereeModule', []);

refereeModule.config(['$routeProvider',function($routeProvider) {
  $routeProvider.
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
      controller:  'RefereeInsertController'});
}]);

refereeModule.controller('RefereeListController', ['$scope', 'refereeRepository',
  function($scope, refereeRepository) 
  {
    $scope.referees = refereeRepository.findAll();
  }
]);
refereeModule.controller('RefereeShowController', ['$scope', '$routeParams', 'refereeRepository',
  function($scope, $routeParams, refereeRepository) 
  {
    $scope.referee = refereeRepository.find($routeParams.id);
  }
]);
refereeModule.controller('RefereeUpdateController', ['$scope', '$routeParams', 'refereeRepository',
  function($scope, $routeParams, refereeRepository) 
  { 
    $scope.referee = refereeRepository.find($routeParams.id);
    
    $scope.update = function() {
      refereeRepository.update($scope.referee);
    };
  }
]);
refereeModule.controller('RefereeInsertController', ['$scope', 'refereeRepository',
  function($scope, refereeRepository) 
  {
    // TODO: create()
    $scope.referee = new refereeRepository({ name: 'New Name'});
    
    $scope.insert = function() {
      refereeRepository.save($scope.referee);
    };
  }
]);

var Referee = function(params)
{
  var item = 
  {
    id:         null,
    name_first: null,
    name_last:  null,
    email_ussf: null,
  
    get name_full() {
      return this.name_first + ' ' + this.name_last;
    }
  };
  if (!params) return item;

  Object.keys(item).forEach(function(key)
  {
    if (params.hasOwnProperty(key)) item[key] = params[key];
  });
  
  return item;
};
var RefereeRepository = function(resource)
{
  this.items    = [];
  this.resource = resource;
  
  this.findAll = function(reload)
  {
    if (!reload) return this.items;
    
    return this.items = this.resource.findAll();
  };
  this.findx = function(id) 
  { 
    return this.resource.find({ id: id }); 
  };
  
  this.find = function(id)
  {
    var itemx = {};
      
    this.items.every(function(item) {
      if (item.id !== id) return true;
      itemx = item; 
      return false; 
    });
    return itemx;
  };
  this.update = function(item)
  {
    return this.resource.update(item);
  };
};
refereeModule.factory('refereeRepository', ['$resource','ceradApiPrefix',
  function($resource,apiPrefix)
  {
    var resource = 
      $resource(apiPrefix + '/referees/:id', { id: '@id' }, {
        update: {method: 'PUT'},
        findAll:  { 
          method: 'GET' , 
          isArray:true,
          transformResponse: function(data)
          {
            var items = angular.fromJson(data);
            var referees = [];
            items.forEach(function(item) {
              var referee = new Referee(item);
              referees.push(referee);
            });
            return referees;
          }
        },
        find:  { 
          method: 'GET',
          transformResponse: function(data)
          {
            var item = angular.fromJson(data);
            return new Referee(item);
          }
        }
    });
    var refereeRepository = new RefereeRepository(resource);
    
    refereeRepository.findAll(true);
    
    return refereeRepository;
}]);

})(angular);