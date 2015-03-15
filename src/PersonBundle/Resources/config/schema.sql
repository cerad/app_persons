DROP TABLE IF EXISTS referees;

CREATE TABLE referees (
  id INT AUTO_INCREMENT       NOT NULL, 
  ussf_id         VARCHAR(40) NOT NULL, 
  name_first      VARCHAR(40) DEFAULT NULL, 
  name_last       VARCHAR(40) DEFAULT NULL, 
  name_middle     VARCHAR(40) DEFAULT NULL, 
  email_ussf      VARCHAR(80) DEFAULT NULL, 
  phone_home      VARCHAR(20) DEFAULT NULL, 
  phone_work      VARCHAR(20) DEFAULT NULL, 
  gender          VARCHAR(10) DEFAULT NULL, 
  dob             DATE        DEFAULT NULL, 
  address_street  VARCHAR(80) DEFAULT NULL, 
  address_city    VARCHAR(40) DEFAULT NULL, 
  address_state   VARCHAR(10) DEFAULT NULL, 
  address_zipcode VARCHAR(10) DEFAULT NULL, 

  reg_year   VARCHAR(10) DEFAULT NULL, 
  reg_state  VARCHAR(10) DEFAULT NULL, 
  reg_status VARCHAR(20) DEFAULT NULL, 
  district   VARCHAR(10) DEFAULT NULL, 

  new_grade_attained_date DATE DEFAULT NULL,
  ref_first_date          DATE DEFAULT NULL,
  state_approved_date     DATE DEFAULT NULL,
  
  UNIQUE INDEX referees_index_ussf_id (ussf_id), 
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
