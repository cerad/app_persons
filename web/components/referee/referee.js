'use strict';
(function() {
    
var refereeComponent = angular.module('zaysoApp.refereeComponent', []);

refereeComponent.controller('RefereeListController', ['$scope', 'RefereeResource',
  function($scope, RefereeResource) 
  {
  //$scope.referees = [];
    
    RefereeResource.query(function(response)
    {
        $scope.referees = response;
    });
  }
]);
refereeComponent.controller('RefereeShowController', ['$scope', '$routeParams', 'RefereeResource',
  function($scope, $routeParams, RefereeResource) 
  {
    $scope.referee = RefereeResource.get({ id: $routeParams.id }, function(item)
    {
        $scope.referee = item;
        console.log('Referee Show Success' + $scope.referee);
    });
    
    console.log('Referee Show' + $scope.referee);
  }
]);
refereeComponent.controller('RefereeUpdateController', ['$scope', '$routeParams', 'RefereeResource',
  function($scope, $routeParams, RefereeResource) 
  {
    $scope.referee = RefereeResource.get({ id: $routeParams.id });
    
    $scope.update = function() {
      console.log($scope.referee);
      $scope.referee.$update();
    };
  }
]);
refereeComponent.controller('RefereeInsertController', ['$scope', '$routeParams', 'RefereeResource',
  function($scope, $routeParams, RefereeResource) 
  {
    $scope.referee = new RefereeResource({ name: 'New Name'});
    
    $scope.insert = function() {
      $scope.referee = $scope.referee.$save();  // Not quite right
    };
  }
]);

/* =============================================
 * This needs more research
 */
refereeComponent.factory('RefereeResource', ['$resource',
  function($resource){
    var referee = 
      $resource('app_dev.php/referees/:id', {
        id:   '@id'
      }, {
      update: {method: 'PUT'}
    });
    referee.namex = 'New Referee Name';
    
    return referee;
}]);

})();