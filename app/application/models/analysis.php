<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Analysis extends CI_Model
{
 
function passPercentageReportCollege($client_id,$exam_id,$filterQry)

{

	$query = $this->db->query("select * from
	(select a.student_cnt,b.student_pass_cnt,
    ROUND((b.student_pass_cnt/a.student_cnt)*100) pass_percentage from 
    (select client_id,count(distinct student_id) student_cnt from results
    where lgcl_del_f='N' and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)a
    
    join
    (select client_id,count(distinct student_id) student_pass_cnt from results
    where marks_obtained>=pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)b
    on a.client_id=b.client_id)
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
    where marks_obtained>=pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code)b
    on a.dept_code=b.dept_code)
	SCR ".$filterQry." order by dept_code");

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
    where marks_obtained>=pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by year)b
    on a.year=b.year)
  SCR ".$filterQry." order by year");

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
    where marks_obtained>=pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year)b
    on a.dept_code=b.dept_code and a.year=b.year)
  SCR ".$filterQry." order by dept_code,year");

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
    where marks_obtained>=pass_mark and lgcl_del_f='N' 
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year,section)b
    on a.dept_code=b.dept_code and a.year=b.year)
  SCR ".$filterQry." order by dept_code,year,section");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function topperReportCollege($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN percentage=@oldPercent
THEN @curRow := @curRow + 0 
ELSE @curRow := @curRow + 1 
END
)AS rank,
@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark
group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark)b
on a.student_id=b.student_id)
rset,
(SELECT @curRow := 0, @oldPercent := 0) r
order by percentage desc
)SCR ".$filterQry);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function topperReportDept($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code = @dept and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code = @dept and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark
group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark)b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
order by dept_code,percentage desc
)SCR ".$filterQry." order by dept_code");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function topperReportYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@year := year,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark
group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark)b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by year,percentage desc
)SCR ".$filterQry." order by year");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function topperReportDeptYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark
group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark)b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function topperReportClass($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and section=@section and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and section=@section and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year, @section := section,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark
group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." and marks_obtained>=pass_mark)b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year,section");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}



function studentRankListReportCollege($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN percentage=@oldPercent
THEN @curRow := @curRow + 0 
ELSE @curRow := @curRow + 1 
END
)AS rank,
@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")b
on a.student_id=b.student_id)
rset,
(SELECT @curRow := 0, @oldPercent := 0) r
order by percentage desc
)SCR ".$filterQry." order by rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}


function studentRankListReportDept($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code = @dept and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code = @dept and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
order by dept_code,percentage desc
)SCR ".$filterQry." order by dept_code,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function studentRankListReportYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@year := year,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by year,percentage desc
)SCR ".$filterQry." order by year,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function studentRankListReportDeptYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function studentRankListReportClass($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and section=@section and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and section=@section and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year, @section := section,@oldPercent := percentage
from
(select b.*,a.percentage
from
(select student_id,AVG(marks_obtained) percentage
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by student_id)a
join
(select distinct client_id,dept_code,year,section,student_id,student_name
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")b
on a.student_id=b.student_id)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year,section,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function subjectRankListReportCollege($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN percentage=@oldPercent
THEN @curRow := @curRow + 0 
ELSE @curRow := @curRow + 1 
END
)AS rank,
@oldPercent := percentage
from
(select b.*,ROUND((b.student_pass_cnt/a.student_cnt)*100) percentage
from
(select subject_code,subject_name,count(distinct student_id) student_cnt
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by subject_code,subject_name)a
join
(select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,count(distinct student_id) student_pass_cnt
from results
where lgcl_del_f='N' and marks_obtained>pass_mark and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name)b
on a.subject_code=b.subject_code)
rset,
(SELECT @curRow := 0, @oldPercent := 0) r
order by percentage desc
)SCR ".$filterQry." order by rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}


function subjectRankListReportDept($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code = @dept and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code = @dept and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@oldPercent := percentage
from
(select b.*,ROUND((b.student_pass_cnt/a.student_cnt)*100) percentage
from
(select subject_code,subject_name,count(distinct student_id) student_cnt
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by subject_code,subject_name)a
join
(select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,count(distinct student_id) student_pass_cnt
from results
where lgcl_del_f='N' and marks_obtained>pass_mark and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name)b
on a.subject_code=b.subject_code)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
order by dept_code,percentage desc
)SCR ".$filterQry." order by dept_code,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function subjectRankListReportYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@year := year,@oldPercent := percentage
from
(select b.*,ROUND((b.student_pass_cnt/a.student_cnt)*100) percentage
from
(select subject_code,subject_name,count(distinct student_id) student_cnt
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by subject_code,subject_name)a
join
(select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,count(distinct student_id) student_pass_cnt
from results
where lgcl_del_f='N' and marks_obtained>pass_mark and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name)b
on a.subject_code=b.subject_code)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by year,percentage desc
)SCR ".$filterQry." order by year,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function subjectRankListReportDeptYear($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year,@oldPercent := percentage
from
(select b.*,ROUND((b.student_pass_cnt/a.student_cnt)*100) percentage
from
(select subject_code,subject_name,count(distinct student_id) student_cnt
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by subject_code,subject_name)a
join
(select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,count(distinct student_id) student_pass_cnt
from results
where lgcl_del_f='N' and marks_obtained>pass_mark and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name)b
on a.subject_code=b.subject_code)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function subjectRankListReportClass($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,percentage,rank from
(select rset.*,
( 
CASE 
WHEN dept_code=@dept and year = @year and section=@section and percentage=@oldPercent
THEN @curRow := @curRow + 0 
WHEN dept_code=@dept and year = @year and section=@section and percentage<>@oldPercent
THEN @curRow := @curRow + 1 
ELSE @curRow := 1 END
) AS rank,@dept := dept_code,@year := year, @section := section,@oldPercent := percentage
from
(select b.*,ROUND((b.student_pass_cnt/a.student_cnt)*100) percentage
from
(select subject_code,subject_name,count(distinct student_id) student_cnt
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by subject_code,subject_name)a
join
(select client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name,count(distinct student_id) student_pass_cnt
from results
where lgcl_del_f='N' and marks_obtained>pass_mark and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)." group by client_id,dept_code,year,section,subject_code,subject_name,staff_id,staff_name)b
on a.subject_code=b.subject_code)rset,
(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section :='') r
order by dept_code,year,percentage desc
)SCR ".$filterQry." order by dept_code,year,section,rank");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function studentMarkListReport($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from 
    (select client_id,dept_code,year,section,subject_code,subject_name,student_id,student_name,total_marks,marks_obtained,
case when marks_obtained>=pass_mark then '1' else '0' end result 
from results
where lgcl_del_f='N' and exam_id=".$this->db->escape($exam_id)." 
and client_id=".$this->db->escape($client_id)."
)SCR ".$filterQry."
order by dept_code,year,section,student_id,subject_code");

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