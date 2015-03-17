(function(angular) { 'use strict';
    
var refereeComponent = angular.module('zaysoApp.refereeComponent', []);

refereeComponent.controller('RefereeListController', ['$scope', 'refereeRepository',
  function($scope, refereeRepository) 
  {
    $scope.referees = refereeRepository.findAll();
  }
]);
refereeComponent.controller('RefereeShowController', ['$scope', '$routeParams', 'refereeRepository',
  function($scope, $routeParams, refereeRepository) 
  {
    $scope.referee = refereeRepository.find($routeParams.id);
  }
]);
refereeComponent.controller('RefereeUpdateController', ['$scope', '$routeParams', 'refereeRepository',
  function($scope, $routeParams, refereeRepository) 
  { 
    $scope.referee = refereeRepository.find($routeParams.id);
    
    $scope.update = function() {
      refereeRepository.update($scope.referee);
    };
  }
]);
refereeComponent.controller('RefereeInsertController', ['$scope', 'refereeRepository',
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
  this.id         = null;
  this.name_first = null;
  this.name_last  = null;
  this.email_ussf = null;
  
  this.nameFull = function()
  {
    return this.name_first + ' ' + this.name_last;
  };
  if (params)
  {
    var that = this;
    Object.keys(that).forEach(function(key)
    {
      if (params.hasOwnProperty(key)) that[key] = params[key];
    });
  }
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
refereeComponent.factory('refereeRepository', ['$resource',
  function($resource)
  {
    var resource = 
      $resource('/app_dev.php/referees/:id', { id: '@id' }, {
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