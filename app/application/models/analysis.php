<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Analysis extends CI_Model
{
 
function passPercentageReportCollege($client_id,$exam_id,$filterQry)

{

	$query = $this->db->query("select * from
	(select c.client_name,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from clients c
    join
    (select client_id,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)a
    on c.client_id=a.client_id
    join
    (select client_id,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)b
    on c.client_id=b.client_id
    where c.client_id=".$this->db->escape($client_id).")
	SCR ".$filterQry);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}


function passPercentageReportDept($client_id,$exam_id,$filterQry)

{

	$query = $this->db->query("select * from
	(select a.dept_code,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from 
    (select dept_code,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code)a
    join
    (select dept_code,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code)b
    on a.dept_code=b.dept_code)
	SCR ".$filterQry." order by pass_percentage desc");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function passPercentageReportYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from
  (select a.year,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from 
    (select year,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by year)a
    join
    (select year,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by year)b
    on a.year=b.year)
  SCR ".$filterQry." order by pass_percentage desc");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function passPercentageReportDeptYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from
  (select a.dept_code,a.year,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from 
    (select dept_code,year,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year)a
    join
    (select dept_code,year,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year)b
    on a.dept_code=b.dept_code and a.year=b.year)
  SCR ".$filterQry." order by pass_percentage desc");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function passPercentageReportClass($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from
  (select a.dept_code,a.year,a.section,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from 
    (select dept_code,year,section,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year,section)a
    join
    (select dept_code,year,section,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year,section)b
    on a.dept_code=b.dept_code and a.year=b.year)
  SCR ".$filterQry." order by pass_percentage desc");

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