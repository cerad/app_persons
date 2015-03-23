(function(angular) { 'use strict';
    
var refereeModule = angular.module('ceradRefereeModule', []);

refereeModule.config(['$routeProvider',function($routeProvider) {
  $routeProvider.
    when('/referees', { 
      templateUrl: 'modules/referee/referee-list.html',
      controller:  'RefereeListController as refereeList'}).
    when('/referees/:id/show', {
      templateUrl: 'modules/referee/referee-show.html',
      controller:  'RefereeShowController as refereeShow'}).
    when('/referees/:id/update', {
      templateUrl: 'modules/referee/referee-update.html',
      controller:  'RefereeUpdateController as refereeUpdate'}).
    when('/referees/insert', {
      templateUrl: 'modules/referee/referee-insert.html',
      controller:  'RefereeInsertController'});
}]);

refereeModule.controller('RefereeListController', ['$http','refereeRepository',
  function($http, refereeRepository) 
  {
    var vm = this;
  //vm.referees = refereeRepository.findAll();
  //vm.referees = findAll();
    
    var findAll = function()
    {
      var items = [];
      
      $http.get('/app_dev.php/referees').then(function(response)
      {
        response.data.forEach(function(item)
        {
          items.push(new Referee(item));
        });
      //return items;
      });
      return items; //items; // promise
    };
    var findAll2 = function()
    {
      var referees = [];
      
      var req = 
      {
        method: 'GET',
        url: '/app_dev.php/referees',
        transformResponse: function(data)
        {
          var items = angular.fromJson(data);
          console.log(items[0]);
          
          items.forEach(function(item) 
          {
            var referee = new Referee(item);
          //console.log('Referee ' + itemx.name_full);
            referees.push(referee);
          });
          return referees;
        }
      };
      $http(req).then(function(response)
      {
        console.log('Referee ' + response.data[4].name_full);
      //referees = response.data;
      });
      return referees; //items; // promise
    };
    vm.referees = findAll2();
    /*
    findAll().then(function(items)
    {
      vm.referees = items;
    });*/
  }
]);
refereeModule.controller('RefereeShowController', 
  ['$routeParams', 'refereeRepository',
  function($routeParams, refereeRepository) 
  {
    var vm = this;
    
    vm.referee = refereeRepository.find($routeParams.id);
  }
]);
refereeModule.controller('RefereeUpdateController', 
  ['$routeParams', 'refereeRepository',
  function($routeParams, refereeRepository) 
  { 
    var vm = this;
    
    vm.referee = refereeRepository.find($routeParams.id);
    
    vm.update = function() {
      refereeRepository.update(vm.referee);
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
  this.id         = null;
  this.name_first = null;
  this.name_last  = null;
  this.email_ussf = null;
  
Object.defineProperty(this, 'name_fullx', 
{
  enumerable: false,
  get: function() 
  {
    return this.name_first + '###' + this.name_last;
  },
  set: function(name) 
  {
    var words = name.split(' ');
    this.name_first = words[0] || '';
    this.name_last  = words[1] || '';
  }
});

  if (!params) return;
  
  var thisObj = this;
  
  Object.keys(this).forEach(function(key)
  {
    if (params.hasOwnProperty(key)) 
    {
      thisObj[key] = params[key];
    }
  });
};
// This need to here so we don't try readding the methods over and over
Object.defineProperty(Referee.prototype, 'name_full', 
{
  enumerable: false,
  get: function() 
  {
    return this.name_first + '##' + this.name_last;
  },
  set: function(name) 
  {
    var words = name.split(' ');
    this.name_first = words[0] || '';
    this.name_last  = words[1] || '';
  }
});
Object.defineProperty(Referee.prototype, 'type', { value: 'Referee'});

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
            //console.log('Referee ' + referee.name_full);
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
            var referee = new Referee(item);
          //console.log('Referee ' + referee.name_full + ' ' + referee.type);
            return referee;
          }
        }
    });
    var refereeRepository = new RefereeRepository(resource);
    
    refereeRepository.findAll(true);
    
    return refereeRepository;
}]);

})(angular);