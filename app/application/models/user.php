<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User extends CI_Model
{
 
 function login($username, $password)
 {
   
    $query = $this->db->query("select clients.client_id,clients.is_active from users join clients 
    on users.client_id=clients.client_id where username=".$this->db->escape($username)." and passwd=".$this->db->escape($password));

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }

function getClientName($client_id)
 {
   
    $query = $this->db->query("select client_name from clients where client_id=".$this->db->escape($client_id));

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 

 function getSubscriptionDetails($client_id)
 {
   
    $query = $this->db->query("select a.client_name,DATE_FORMAT(subscription_start_date,'%b %D %Y')subscription_start_date,
    DATE_FORMAT(subscription_end_date,'%b %D %Y')subscription_end_date,c.package_name,c.package_desc from clients a 
    join clientpackage b
    on a.client_id=b.client_id
    join package c
    on b.package_id=c.package_id where a.client_id=".$this->db->escape($client_id));

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }


function newExamEntry($client_id,$ename)

{

  $query = $this->db->query("insert into exams(client_id,exam_name,crte_ts,updt_ts,status,lgcl_del_f) 
  values(".$this->db->escape($client_id).",".$this->db->escape($ename).",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,0,'N')");

   if($query)
   {
     return true;
   }
   else
   {
     return false;
   }

}

function getExamList($client_id)

{

  $query = $this->db->query("select 
case when package_id=1 then 1 
when package_id=2 then 5
when package_id=3 then 10
when package_id=4 then 25
end no
from clientpackage where client_id=".$this->db->escape($client_id));
  foreach($query->result() as $row)
          {
       
            $no= $row->no;
            } 

  $query = $this->db->query("select exam_id,exam_name from exams 
    where client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function getExamStatus($client_id)

{

  $query = $this->db->query("select 
case when package_id=1 then 1 
when package_id=2 then 5
when package_id=3 then 10
when package_id=4 then 25
end no
from clientpackage where client_id=".$this->db->escape($client_id));
  foreach($query->result() as $row)
          {
       
            $no= $row->no;
            } 

  $query = $this->db->query("select exam_name,DATE_FORMAT(crte_ts,'%b %D %Y')cdate,
    case when status=1 then 'Results Available' else 'Results Not Uploaded' end status_msg,status from exams 
    where client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}


}
?>