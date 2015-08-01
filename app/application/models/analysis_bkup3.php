<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Analysis extends CI_Model
{
 
function passPercentageReportCollege($client_id,$exam_id,$filterQry)

{
	/*
	$query = $this->db->query("select * from
	(select a.student_cnt,ifnull(b.student_pass_cnt,0) student_pass_cnt,
    ROUND((ifnull(b.student_pass_cnt,0)/a.student_cnt)*100) pass_percentage from 
    (select client_id,count(distinct student_id) student_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where is_ready='Y' and lgcl_del_f='N'  and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)a
    
    left join
    (select client_id,count(distinct student_id) student_pass_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where marks_obtained>=pass_mark and is_ready='Y' and lgcl_del_f='N'  
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by client_id)b
    on a.client_id=b.client_id)
	SCR ".$filterQry);
	*/
	
	$query = $this->db->query("select * from
	(select count(distinct student_id) as student_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end)) as student_pass_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as pass_percentage
	from class c
	join marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id).")
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

	/*$query = $this->db->query("select * from
	(select a.dept_code,a.student_cnt,ifnull(b.student_pass_cnt,0) student_pass_cnt,
    ROUND((ifnull(b.student_pass_cnt,0)/a.student_cnt)*100) pass_percentage from 
    (select dept_code,count(distinct student_id) student_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where is_ready='Y' and lgcl_del_f='N'  and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code)a
    left join
    (select dept_code,count(distinct student_id) student_pass_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where marks_obtained>=pass_mark and is_ready='Y' and lgcl_del_f='N'  
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code)b
    on a.dept_code=b.dept_code)
	SCR ".$filterQry." order by dept_code");*/
	
	$query = $this->db->query("select * from
	(select dept_code,count(distinct student_id) as student_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end)) as student_pass_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as pass_percentage
	from class c
	join marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by dept_code)
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

function passPercentageReportYear($client_id,$exam_id,$filterQry)

{
	
  /*			
  $query = $this->db->query("select * from
  (select a.year,a.student_cnt,ifnull(b.student_pass_cnt,0) student_pass_cnt,
    ROUND((ifnull(b.student_pass_cnt,0)/a.student_cnt)*100) pass_percentage from 
    (select year,count(distinct student_id) student_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where is_ready='Y' and lgcl_del_f='N'  and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by year)a
    left join
    (select year,count(distinct student_id) student_pass_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where marks_obtained>=pass_mark and is_ready='Y' and lgcl_del_f='N'  
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by year)b
    on a.year=b.year)
  SCR ".$filterQry." order by year");
  */
  
  	$query = $this->db->query("select * from
	(select year,count(distinct student_id) as student_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end)) as student_pass_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as pass_percentage
	from class c
	join marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by year)
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

function passPercentageReportDeptYear($client_id,$exam_id,$filterQry)

{

  /*
  $query = $this->db->query("select * from
  (select a.dept_code,a.year,a.student_cnt,ifnull(b.student_pass_cnt,0) student_pass_cnt,
    ROUND((ifnull(b.student_pass_cnt,0)/a.student_cnt)*100) pass_percentage from 
    (select dept_code,year,count(distinct student_id) student_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where is_ready='Y' and lgcl_del_f='N'  and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year)a
    left join
    (select dept_code,year,count(distinct student_id) student_pass_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where marks_obtained>=pass_mark and is_ready='Y' and lgcl_del_f='N'  
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year)b
    on a.dept_code=b.dept_code and a.year=b.year)
  SCR ".$filterQry." order by dept_code,year");
  */
  
  $query = $this->db->query("select * from
	(select dept_code,year,count(distinct student_id) as student_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end)) as student_pass_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as pass_percentage
	from class c
	join marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by dept_code,year)
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

function passPercentageReportClass($client_id,$exam_id,$filterQry)

{

  /*
  $query = $this->db->query("select * from
  (select a.dept_code,a.year,a.section,a.student_cnt,ifnull(b.student_pass_cnt,0) student_pass_cnt,
    ROUND((ifnull(b.student_pass_cnt,0)/a.student_cnt)*100) pass_percentage from 
    (select dept_code,year,section,count(distinct student_id) student_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where is_ready='Y' and lgcl_del_f='N'  and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year,section)a
    left join
    (select dept_code,year,section,count(distinct student_id) student_pass_cnt from class cs join marks ms on cs.batch_id=ms.batch_id 
    where marks_obtained>=pass_mark and is_ready='Y' and lgcl_del_f='N'  
    and client_id=".$this->db->escape($client_id)." and exam_id=".$this->db->escape($exam_id)." group by dept_code,year,section)b
    on a.dept_code=b.dept_code and a.year=b.year and a.section=b.section)
  SCR ".$filterQry." order by dept_code,year,section");
  */
  
  $query = $this->db->query("select * from
	(select dept_code,year,section,count(distinct student_id) as student_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end)) as student_pass_cnt,
	count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as pass_percentage
	from class c
	join marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)." group by dept_code,year,section)
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


function subjectTopperReportCollege($client_id,$exam_id,$filterQry)

{
  $query = $this->db->query("select * from
(select dept_code,year,section,student_id,student_name,subject_code,subject_name,marks_obtained,rank
from class c
join 
(select *,
( 
CASE 
WHEN batch_id = @batch_id and marks_obtained=@oldMark
THEN @curRow := @curRow + 0 
WHEN batch_id = @batch_id and marks_obtained<>@oldMark
THEN @curRow := @curRow + 1 
ELSE @curRow := 1
END
)AS rank,@batch_id := batch_id,@oldMark := marks_obtained
from marks,
(SELECT @curRow := 0, @oldMark := 0,@batch_id=0) r
order by batch_id,marks_obtained desc) m
on c.batch_id=m.batch_id 
where lgcl_del_f='N' and marks_obtained>=pass_mark
and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)."
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

function topperReportCollege($client_id,$exam_id,$filterQry)

{ 
  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
	(select dept_code,year,section,student_id,student_name,percentage,
	( 
	CASE 
	WHEN percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	ELSE @curRow := @curRow + 1 
	END
	)AS rank,
	@oldPercent := percentage
	from
	(select dept_code,year,section,student_id,student_name,avg(marks_obtained) percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	and marks_obtained>=pass_mark
	group by dept_code,year,section,student_id,student_name
	)a,
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
	(select dept_code,year,section,student_id,student_name,percentage,
	( 
	CASE 
	WHEN dept_code = @dept and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code = @dept and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@oldPercent := percentage
	from
	(select dept_code,year,section,student_id,student_name,avg(marks_obtained) percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	and marks_obtained>=pass_mark
	group by dept_code,year,section,student_id,student_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,percentage desc
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

function topperReportYear($client_id,$exam_id,$filterQry)

{
  
  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
	(select dept_code,year,section,student_id,student_name,percentage,
	( 
	CASE 
	WHEN year = @year and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN year = @year and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@year := year,@oldPercent := percentage
	from
	(select dept_code,year,section,student_id,student_name,avg(marks_obtained) percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	and marks_obtained>=pass_mark
	group by dept_code,year,section,student_id,student_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by year,percentage desc
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

function topperReportDeptYear($client_id,$exam_id,$filterQry)

{  
  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
	(select dept_code,year,section,student_id,student_name,percentage,
	( 
	CASE 
	WHEN dept_code=@dept and year = @year and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code=@dept and year = @year and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@year := year,@oldPercent := percentage
	from
	(select dept_code,year,section,student_id,student_name,avg(marks_obtained) percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	and marks_obtained>=pass_mark
	group by dept_code,year,section,student_id,student_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,year,percentage desc
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

function topperReportClass($client_id,$exam_id,$filterQry)

{  
  $query = $this->db->query("select dept_code,year,section,student_id,student_name,percentage,rank from
	(select dept_code,year,section,student_id,student_name,percentage,
	( 
	CASE 
	WHEN dept_code=@dept and year = @year and section=@section and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code=@dept and year = @year and section=@section and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@year := year, @section := section,@oldPercent := percentage
	from
	(select dept_code,year,section,student_id,student_name,avg(marks_obtained) percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	and marks_obtained>=pass_mark
	group by dept_code,year,section,student_id,student_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,year,section,percentage desc
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

function studentMarkListReport($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from 
    (select client_id,dept_code,year,section,subject_code,subject_name,student_id,student_name,total_marks,marks_obtained,
	case when marks_obtained>=pass_mark then '1' else '0' end result 
	from class cs join marks ms on cs.batch_id=ms.batch_id 
	where lgcl_del_f='N'  and exam_id=".$this->db->escape($exam_id)." 
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

function subjectRankListReportCollege($client_id,$exam_id,$filterQry)

{ 
  $query = $this->db->query("select dept_code,year,section,subject_code,subject_name,percentage,rank from
	(select dept_code,year,section,subject_code,subject_name,percentage,
	( 
	CASE 
	WHEN percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	ELSE @curRow := @curRow + 1 
	END
	)AS rank,
	@oldPercent := percentage
	from
	(select dept_code,year,section,subject_code,subject_name,
    count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	group by dept_code,year,section,subject_code,subject_name
	)a,
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


function subjectRankListReportDept($client_id,$exam_id,$filterQry)

{
  
  $query = $this->db->query("select dept_code,year,section,subject_code,subject_name,percentage,rank from
	(select dept_code,year,section,subject_code,subject_name,percentage,
	( 
	CASE 
	WHEN dept_code = @dept and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code = @dept and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@oldPercent := percentage
	from
	(select dept_code,year,section,subject_code,subject_name,
    count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	group by dept_code,year,section,subject_code,subject_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,percentage desc
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

function subjectRankListReportYear($client_id,$exam_id,$filterQry)

{
  
  $query = $this->db->query("select dept_code,year,section,subject_code,subject_name,percentage,rank from
	(select dept_code,year,section,subject_code,subject_name,percentage,
	( 
	CASE 
	WHEN year = @year and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN year = @year and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@year := year,@oldPercent := percentage
	from
	(select dept_code,year,section,subject_code,subject_name,
    count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	group by dept_code,year,section,subject_code,subject_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by year,percentage desc
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

function subjectRankListReportDeptYear($client_id,$exam_id,$filterQry)

{  
  $query = $this->db->query("select dept_code,year,section,subject_code,subject_name,percentage,rank from
	(select dept_code,year,section,subject_code,subject_name,percentage,
	( 
	CASE 
	WHEN dept_code=@dept and year = @year and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code=@dept and year = @year and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@year := year,@oldPercent := percentage
	from
	(select dept_code,year,section,subject_code,subject_name,
    count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	group by dept_code,year,section,subject_code,subject_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,year,percentage desc
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

function subjectRankListReportClass($client_id,$exam_id,$filterQry)

{  
  $query = $this->db->query("select dept_code,year,section,subject_code,subject_name,percentage,rank from
	(select dept_code,year,section,subject_code,subject_name,percentage,
	( 
	CASE 
	WHEN dept_code=@dept and year = @year and section=@section and percentage=@oldPercent
	THEN @curRow := @curRow + 0 
	WHEN dept_code=@dept and year = @year and section=@section and percentage<>@oldPercent
	THEN @curRow := @curRow + 1 
	ELSE @curRow := 1 END
	) AS rank,@dept := dept_code,@year := year, @section := section,@oldPercent := percentage
	from
	(select dept_code,year,section,subject_code,subject_name,
    count(distinct(case when marks_obtained>=pass_mark then student_id end))/count(distinct student_id) * 100 as percentage
	from class c
	join 
	marks m
	on c.batch_id=m.batch_id
	where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)."
	and client_id=".$this->db->escape($client_id)."
	group by dept_code,year,section,subject_code,subject_name
	)a,
	(SELECT @curRow := 0, @oldPercent := 0, @client_id := 0, @dept := '', @year := 0, @section='') r
	order by dept_code,year,section,percentage desc
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



}
?>