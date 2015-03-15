<?php
namespace Cerad\Bundle\PersonBundle\Action\Reader;

use Cerad\Bundle\PersonBundle\Excel\ExcelReader;

class BillWalkerReader extends ExcelReader
{
  protected $record = 
  [
    'ussf_id'        => ['cols' => 'USSF ID'],
      
    'reg_year'       => ['cols' => 'Registration Year'],
    'reg_state'      => ['cols' => 'Registered State'],
    'reg_status'     => ['cols' => 'Registration Status'],
    'district'      => ['cols' => 'District'],
         
    'name_last'      => ['cols' => 'LNAME'],
    'name_middle'    => ['cols' => 'MNAME'],
    'name_first'     => ['cols' => 'FNAME'],
      
    'address_street' => ['cols' => 'Street Address'],
    'address_city'   => ['cols' => 'City'],
    'address_state'  => ['cols' => 'State'],
    'address_zipcode'=> ['cols' => 'Zip'],
      
    'phone_home'     => ['cols' => 'Home Phone'],
    'phone_work'     => ['cols' => 'Work Phone'],
    'email_ussf'     => ['cols' => 'Email'],
      
    'gender'        => ['cols' => 'Gender'],
    'dob'           => ['cols' => 'Date of Birth'],
      
    'new_grade_attained_date'  => ['cols' => 'NewGrade Attained Date'],
    'ref_first_date'          => ['cols' => 'Ref 1st Date'],
    'state_approved_date'     => ['cols' => 'State Approved Date'],
  ];
  protected function processItem($item)
  {
    // ussf id has weird char at end, just grab digits
    $item['ussf_id'] = preg_replace('/\D/','',$item['ussf_id']);
    
    foreach(['dob','new_grade_attained_date','ref_first_date','state_approved_date'] as $key)
    {
      $item[$key] = $this->processDate($item[$key]);
    }
    
    $this->items[] = $item;
  }
}