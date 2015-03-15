<?php

namespace Cerad\Bundle\PersonBundle\Action\Saver;

class RefereeSaverDBAL
{
  private $refereeRepository;
  
  private $results;
  
  public function __construct($refereeRepository)
  {
    $this->refereeRepository = $refereeRepository;
    
    $this->results = 
    [
      'total'    => 0,
      'inserted' => 0,
      'updated'  => 0,
    ];
  }
  public function save($referees)
  {
    foreach($referees as $referee)
    {
      $this->results['total']++;
      $this->processReferee($referee);
    }
    return $this->results;
  }
  private function processReferee($referee)
  {
    $refereeRepository = $this->refereeRepository;
    
    $refereex = $refereeRepository->findRefereeByUssfId($referee['ussf_id']);
    if (!$refereex)
    {
      $this->results['inserted']++;
      $refereeRepository->insertReferee($referee);
    }
  //$referee['name_first'] = 'Art';
  //$referee['name_last' ] = 'Hundiak';
    $updated = $refereeRepository->updateReferee($referee,$refereex);
    
    if ($updated) { $this->results['updated']++; }
    
    return;
  }
}