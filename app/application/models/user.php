<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User extends CI_Model
{
 
 function login($username, $password)
 {
   
    $query = $this->db->query("select clients.client_id,clients.is_active,users.user_id,users.user_type from users join clients 
    on users.client_id=clients.client_id where users.lgcl_del_f='N'
    and username=".$this->db->escape($username)." and passwd=".$this->db->escape($password));

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

 function getExamName($examid)
 {
   
    $query = $this->db->query("select exam_name from exams where exam_id=".$this->db->escape($examid));

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
    where
    exam_id in (select distinct exam_id from class c where c.client_id=".$this->db->escape($client_id)." and c.is_ready='Y' and c.lgcl_del_f='N')
    and client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function getStudentsList($client_id)

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

  $query = $this->db->query("select exam_id from exams 
    where status=1 and client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

  $eid = '-1';

  foreach($query->result() as $row)
    {
      $eid= $eid.','.$row->exam_id;
    } 

  $query = $this->db->query("select distinct student_id,student_name from results 
    where client_id=".$this->db->escape($client_id)." and exam_id in (".$eid.") order by student_id");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function getSubjectsList($client_id)

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

  $query = $this->db->query("select exam_id from exams 
    where status=1 and client_id=".$this->db->escape($client_id)." order by crte_ts desc LIMIT ".$no);

  $eid = '-1';

  foreach($query->result() as $row)
    {
      $eid= $eid.','.$row->exam_id;
    } 

  $query = $this->db->query("select distinct subject_code,subject_name from results 
    where client_id=".$this->db->escape($client_id)." and exam_id in (".$eid.") order by subject_code");

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
    case
    when (select count(*) from class c where c.exam_id=exam_id and c.is_ready='Y' and c.lgcl_del_f='N')>=1
    then 'Results Available' else 'Results Not Uploaded' end status_msg,status from exams 
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

      $query = $this->db->query("select IFNULL(max(batch_id),0)+1 batch_id from results");
      
      foreach($query->result() as $row)
        {
          $batchid= $row->batch_id;
        }
        
      $query = $this->db->query('LOAD DATA LOCAL INFILE "'.$target_path.'"
      INTO TABLE results
      FIELDS TERMINATED BY "," ENCLOSED BY "\""
      LINES TERMINATED BY "\n"               
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
      batch_id='.$this->db->escape($batchid).',
      student_id=REPLACE(student_id, \' \', \'\'),
      student_name=TRIM(student_name),
      marks_obtained=TRIM(marks_obtained),
      student_id= CASE WHEN CHAR_LENGTH(student_id)<>0 then student_id else NULL END,
      student_name= CASE WHEN CHAR_LENGTH(student_name)<>0 then student_name else NULL END,
      marks_obtained= CASE WHEN CHAR_LENGTH(marks_obtained)<>0 then marks_obtained else NULL END,
      lgcl_del_f = CASE
      when student_id is NULL then "Y"
      when student_name is NULL then "Y"
      when marks_obtained is NULL then "Y"
      when CAST(marks_obtained AS UNSIGNED)=0 then "Y"
      when marks_obtained>total_marks then "Y"
      ELSE "N"
      END

      ');
      
      $query = $this->db->query("select count(*) cnt from results where batch_id=".$batchid);
      
      foreach($query->result() as $row)
        {
          $cnt= $row->cnt;
        }
      
     if($cnt>0)
     {
       $query = $this->db->query("update exams set status=1 where exam_id=".$this->db->escape($exam_id)); 
       return true;
     }
     else
     {
       return false;
     }

  }
  
  
  function newBatch($client_id,$exam_id,$staffname,$staffid,$subname,$subcode,$maxmark,$minmark,$deptcode,$year,$section)

  {

      $query = $this->db->query("select IFNULL(max(batch_id),0)+1 batch_id from class");
      
      foreach($query->result() as $row)
        {
          $batchid= $row->batch_id;
        }
        
      $query = $this->db->query("insert into class(batch_id,exam_id,client_id,dept_code,year,section,subject_code,subject_name,
                                staff_id,staff_name,total_marks,pass_mark,crte_ts,updt_ts,
                                is_ready,lgcl_del_f) values(".$batchid.",".$this->db->escape($exam_id).",".$this->db->escape($client_id).","
                                .$this->db->escape($deptcode).",".$this->db->escape($year).","
                                .$this->db->escape($section).",".$this->db->escape($subcode).","
                                .$this->db->escape($subname).",".$this->db->escape($staffid).","
                                .$this->db->escape($staffname).",".$this->db->escape($maxmark).","
                                .$this->db->escape($minmark).",CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'N','N')");
                               
      if($query)
      {
        return $batchid;
      }
      
      else
      {
       return false;
      }

  }

 function newMarks($batchid,$target_path)

  {
       
      $query = $this->db->query('LOAD DATA LOCAL INFILE "'.$target_path.'"
      INTO TABLE marks
      FIELDS TERMINATED BY "," ENCLOSED BY "\""
      LINES TERMINATED BY "\n"               
      IGNORE 1 LINES
      (student_id,student_name,marks_obtained)   
      set
      batch_id='.$this->db->escape($batchid).',
      crte_ts=CURRENT_TIMESTAMP,
      updt_ts=CURRENT_TIMESTAMP');
      
      if($query)
      {
        $querysub = $this->db->query("update class set is_ready='Y' where batch_id=".$batchid);
        if($querysub)
        {
         return TRUE;
        }
        else
        {
         return FALSE;
        }
      }
      else
      {
        return FALSE;
      }

  }
  
function getResultInfo($client_id,$exam_id)

  {

    $query = $this->db->query("select exam_id,batch_id,dept_code,year,section,staff_id,staff_name,subject_name
    from class
    where is_ready='Y' and lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)."
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
    from marks m join class c on m.batch_id=c.batch_id
    where c.client_id=".$this->db->escape($client_id)." and c.exam_id=".$this->db->escape($exam_id)." and c.batch_id=".$this->db->escape($batch_id));

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

    $query = $this->db->query("update class set lgcl_del_f='Y'
    where client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." and batch_id=".$this->db->escape($batch_id));
    
    if($query)
    {
       /*
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
       }*/
       return TRUE;
    }
    else
    {
      return FALSE;
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

  $query = $this->db->query("insert into users(client_id,username,passwd,user_type,crte_ts,updt_ts,lgcl_del_f) 
  values(".$this->db->escape($client_id).",".$this->db->escape($uname).",".$this->db->escape($pass).",2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'N')");

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
   
    $query = $this->db->query("select user_id,username from users where lgcl_del_f='N' and user_type=2 and client_id=".$this->db->escape($client_id));

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }

 function removeUser($client_id,$userid)
 {
   
    $query = $this->db->query("update users set lgcl_del_f='Y' where user_type=2 and client_id=".$this->db->escape($client_id)." and user_id=".$this->db->escape($userid));

   if($query)
   {
     return true;
   }
   else
   {
     return false;
   }
 }


function checkPassword($uid,$pass)

  {

    $query = $this->db->query("select * from users where user_id=".$this->db->escape($uid)." and passwd=".$this->db->escape($pass));

    if($query -> num_rows() == 1)
   {
     return true;
   }
   else
   {
     return false;
   }

  }


function changePassword($uid,$pass)

  {

    $query = $this->db->query("update users set passwd=".$this->db->escape($pass)." where user_id=".$this->db->escape($uid));

    if($query)
   {
     return true;
   }
   else
   {
     return false;
   }

  }

}
?>