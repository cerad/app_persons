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
  reg_year, reg_state, reg_status, district,
  new_grade_attained_date, ref_first_date, state_approved_date
)
VALUES 
(:ussf_id,  
 :name_first,:name_last,:name_middle,:email_ussf,:phone_home,:phone_work,:gender,:dob,
 :address_street,:address_city,:address_state,:address_zipcode,
 :reg_year,:reg_state,:reg_status,:district,
 :new_grade_attained_date,:ref_first_date,:state_approved_date
);
EOT;
    return $this->prepared[$key] = $this->db->prepare($sql);
  }
  public function insertReferee($referee)
  {
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
}