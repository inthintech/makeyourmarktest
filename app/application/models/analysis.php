<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Analysis extends CI_Model
{
 
function passPercentageReportCollege($client_id,$exam_id)

{

  $query = $this->db->query("select c.client_name,a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from clients c
    join
    (select client_id,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id).")a
    on c.client_id=a.client_id
    join
    (select client_id,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id).")b
    on c.client_id=b.client_id
    where c.client_id=".$this->db->escape($client_id));

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