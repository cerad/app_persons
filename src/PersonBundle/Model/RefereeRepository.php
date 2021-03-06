<?php

namespace Cerad\Bundle\PersonBundle\Model;

class RefereeRepository
{
  private $db;
  private $prepared = array();
  
  public function __construct($db)
  {
    $this->db = $db;
  }
  private function prepareInsertReferee()
  {
    $key = 'refereeInsert';
        
    if (isset($this->prepared[$key])) { return $this->prepared[$key]; }
        
    $sql = <<<EOT
INSERT INTO referees
( ussf_id,  
  name_first, name_last, name_middle, email_ussf, phone_home, phone_work, gender, dob,
  address_street, address_city, address_state, address_zipcode,
  badge,reg_year, reg_state, reg_status, district,
  new_grade_attained_date, ref_first_date, state_approved_date
)
VALUES 
(:ussf_id,  
 :name_first,:name_last,:name_middle,:email_ussf,:phone_home,:phone_work,:gender,:dob,
 :address_street,:address_city,:address_state,:address_zipcode,
 :badge,:reg_year,:reg_state,:reg_status,:district,
 :new_grade_attained_date,:ref_first_date,:state_approved_date
);
EOT;
    return $this->prepared[$key] = $this->db->prepare($sql);
  }
  public function insertReferee($referee)
  {
    $referee = array_merge($this->createReferee(),$referee);
    
    if (isset($referee['id'])) unset($referee['id']);
    
    $stmt = $this->prepareInsertReferee();
    $stmt->execute($referee);
    return $this->db->lastInsertId();
  }
  private function prepareFindRefereeByUssfId()
  {
    $key = 'refereeFindByUssfId';
        
    if (isset($this->prepared[$key])) { return $this->prepared[$key]; }
        
    $sql = <<<EOT
SELECT * FROM referees WHERE ussf_id = :ussf_id;
EOT;
    return $this->prepared[$key] = $this->db->prepare($sql);
  }
  public function findRefereeByUssfId($id)
  {
    $stmt = $this->prepareFindRefereeByUssfId();
    $stmt->execute(['ussf_id' => $id]);
    $rows = $stmt->fetchAll();
    return (count($rows) == 1) ? $rows[0] : null;
  }
  public function updateReferee($new,$old)
  {
    $changes = [];
    foreach($new as $key => $value)
    {
      if ($value != $old[$key])
      {
        $changes[$key] = $value;
      }
    }
    if (count($changes) < 1) { return null; }
    
    $cols = [];
    foreach(array_keys($changes) as $key)
    {
      $cols[] = $key . '=:' . $key;
    }
    $sets = implode(',',$cols);
    $prepareKey = 'updateReferee ' . $sets;
    if (isset($this->prepared[$prepareKey]))
    {
      $stmt = $this->prepared[$prepareKey];
    }
    else
    {
      $sql = sprintf("UPDATE referees SET %s WHERE id = :id;",$sets);
    
      $this->prepared[$prepareKey] = $stmt = $this->db->prepare($sql);
    }
    
    $changes['id'] = $old['id'];
    
    $stmt->execute($changes);
    
    return $changes;
  }
  public function findAll()
  {
    $sql = "SELECT * FROM referees;";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  public function find($id)
  {
    $sql = "SELECT * FROM referees WHERE id = :id;";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
        
    $referees = $stmt->fetchAll();
        
    return (count($referees) == 1) ? $referees[0] : null;
  }
  // Maybe pull from database?
  public function createReferee()
  {
    return 
    [
      'ussf_id'     => null,  
      'name_first'  => null, 
      'name_last'   => null, 
      'name_middle' => null, 
      'email_ussf'  => null, 
      'phone_home'  => null, 
      'phone_work'  => null, 
      'gender'      => null, 
      'dob'         => null,
      'address_street'  => null, 
      'address_city'    => null, 
      'address_state'   => null, 
      'address_zipcode' => null,
      'badge'      => null,
      'reg_year'   => null, 
      'reg_state'  => null, 
      'reg_status' => null, 
      'district'   => null,
      'new_grade_attained_date' => null, 
      'ref_first_date' => null, 
      'state_approved_date' => null
    ];
  }
}