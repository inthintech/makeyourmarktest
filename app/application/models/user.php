<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User extends CI_Model
{
 
 function login($username, $password)
 {
   
    $query = $this->db->query("select clients.client_id,clients.is_active,users.user_type from users join clients 
    on users.client_id=clients.client_id where users.lgcl_del_f='N' and username=".$this->db->escape($username)." and passwd=".$this->db->escape($password));

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

function getExamListWithData($client_id)

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
    where status=1 and client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

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


function newResult($client_id,$exam_id,$target_path,$staffname,$staffid,$subname,$subcode,$maxmark,$minmark,$deptcode,$year,$section)

  {

      $query = $this->db->query("select max(batch_id)+1 batch_id from results");
      foreach($query->result() as $row)
        {
          $batchid= $row->batch_id;
        } 


      $query = $this->db->query('LOAD DATA LOCAL INFILE "'.$target_path.'"
      INTO TABLE results
      FIELDS TERMINATED BY "," ENCLOSED BY "\""
      LINES TERMINATED BY "\r\n"               
      IGNORE 1 LINES
      (student_id,student_name,marks_obtained)
      set 
      pass_mark='.$this->db->escape($minmark).',
      total_marks='.$this->db->escape($maxmark).',
      staff_id='.$this->db->escape($staffid).',
      staff_name='.$this->db->escape($staffname).',
      subject_name='.$this->db->escape($subname).',
      subject_code='.$this->db->escape($subcode).',
      dept_code='.$this->db->escape($deptcode).',
      year='.$this->db->escape($year).',
      section='.$this->db->escape($section).',
      exam_id='.$this->db->escape($exam_id).',
      client_id='.$this->db->escape($client_id).',
      crte_ts=CURRENT_TIMESTAMP,
      updt_ts=CURRENT_TIMESTAMP,
      batch_id='.$this->db->escape($batchid).'
      lgcl_del_f="N"');

     if($query)
     {
       
       unlink($target_path);
       $query = $this->db->query("update exams set status=1 where exam_id=".$this->db->escape($exam_id)); 
       return true;
     }
     else
     {
       return false;
     }

  }

function getResultInfo($client_id,$exam_id)

  {

    $query = $this->db->query("select exam_id,batch_id,dept_code,year,section,staff_id,staff_name,subject_name
    from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)."
    group by exam_id,batch_id,dept_code,year,section,staff_id,staff_name,subject_name
    order by dept_code,year,section,subject_name");

    if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }


  }


function getResultDetails($client_id,$exam_id,$batch_id)

  {

    $query = $this->db->query("select student_id,student_name,total_marks,pass_mark,marks_obtained
    from results
    where client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." and batch_id=".$this->db->escape($batch_id));

    if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }


  }


function removeResults($client_id,$exam_id,$batch_id)

  {

    $query = $this->db->query("update results set lgcl_del_f='Y'
    where client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." and batch_id=".$this->db->escape($batch_id));

   $query = $this->db->query("select count(*) cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id));

   foreach($query->result() as $row)
        {
          $count= $row->cnt;
        } 

    if($count==0)
    {
      $query = $this->db->query("update exams set status=0
      where exam_id=".$this->db->escape($exam_id));

    }


  }

  function checkUsername($uname)

  {

    $query = $this->db->query("select * from users where username=".$this->db->escape($uname));

    if($query -> num_rows() >= 1)
   {
     return true;
   }
   else
   {
     return false;
   }

  }

function newUserEntry($client_id,$uname,$pass)

{

  $query = $this->db->query("insert into users(client_id,username,passwd,user_type,crte_ts,updt_ts) 
  values(".$this->db->escape($client_id).",".$this->db->escape($uname).",".$this->db->escape($pass).",2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)");

   if($query)
   {
     return true;
   }
   else
   {
     return false;
   }

}

function getUserList($client_id)
 {
   
    $query = $this->db->query("select user_id,username from users where client_id=".$this->db->escape($client_id));

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