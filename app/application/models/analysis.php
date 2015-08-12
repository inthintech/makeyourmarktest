<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Analysis extends CI_Model
{
 
function passPercentageReportCollege($client_id,$exam_id,$filterQry,$level_id)

{
	//Level
	// 1 - College
	// 2 - Dept
	// 3 - Year
	// 4 - Class
	// 5 - Dept Year
	
	$queryStr = "Select * from (select ";
	
	//select columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr."a.client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr."a.dept_code,";
	  break;
	  case 3:
		$queryStr = $queryStr."a.year,";
	  break;
	  case 4:
		$queryStr = $queryStr."a.dept_code,a.year,a.section,";
	  break;
	  case 5:
		$queryStr = $queryStr."a.dept_code,a.year,";
	  break;
	}
	
  	$queryStr = $queryStr."
	count(distinct a.student_id) as student_cnt,
	count(distinct(case when b.student_id is null then a.student_id end)) as student_pass_cnt,
	count(distinct(case when b.student_id is null then a.student_id end))
	/count(distinct a.student_id) * 100 as pass_percentage 
	from
	(select distinct c.*,student_id
	from marks m
	join class c
	on m.batch_id=c.batch_id
	where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
	)a left join
	(select distinct student_id
	from marks m
	join class c
	on m.batch_id=c.batch_id
	where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
	and c.client_id=".$this->db->escape($client_id)." and marks_obtained<pass_mark
	)b
	on a.student_id=b.student_id";
		
	//group by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." group by a.client_id)SCR";
	  break;
	  case 2:
		$queryStr = $queryStr." group by a.dept_code)SCR";
	  break;
	  case 3:
		$queryStr = $queryStr." group by a.year)SCR";
	  break;
	  case 4:
		$queryStr = $queryStr." group by a.dept_code,a.year,a.section)SCR";
	  break;
	  case 5:
		$queryStr = $queryStr." group by a.dept_code,a.year)SCR";
	  break;
	}
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code";
	  break;
	  case 3:
		$queryStr = $queryStr." order by year";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,year,section";
	  break;
	  case 5:
		$queryStr = $queryStr." order by dept_code,year";
	  break;
	}
	
	$query = $this->db->query($queryStr);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function passPercentageReportSchool($client_id,$exam_id,$filterQry,$level_id)

{
	//Level
	// 1 - School
	// 2 - Standard
	// 4 - Class
	
	$queryStr = "Select * from (select ";
	
	//select columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr."a.client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr."a.dept_code,";
	  break;
	  case 4:
		$queryStr = $queryStr."a.dept_code,a.section,";
	  break;
	}
	
  	$queryStr = $queryStr."
	count(distinct a.student_id) as student_cnt,
	count(distinct(case when b.student_id is null then a.student_id end)) as student_pass_cnt,
	count(distinct(case when b.student_id is null then a.student_id end))
	/count(distinct a.student_id) * 100 as pass_percentage 
	from
	(select distinct c.*,student_id
	from marks m
	join class c
	on m.batch_id=c.batch_id
	where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
	)a left join
	(select distinct student_id
	from marks m
	join class c
	on m.batch_id=c.batch_id
	where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
	and c.client_id=".$this->db->escape($client_id)." and marks_obtained<pass_mark
	)b
	on a.student_id=b.student_id";
		
	//group by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." group by a.client_id)SCR";
	  break;
	  case 2:
		$queryStr = $queryStr." group by a.dept_code)SCR";
	  break;
	  case 4:
		$queryStr = $queryStr." group by a.dept_code,a.section)SCR";
	  break;
	}
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id";
	  break;
	  case 2:
		$queryStr = $queryStr." order by cast(dept_code as unsigned)";
	  break;
	  case 4:
		$queryStr = $queryStr." order by cast(dept_code as unsigned),section";
	  break;
	}
	
	//print $queryStr;
	$query = $this->db->query($queryStr);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function overallTopperReportCollege($client_id,$exam_id,$filterQry,$level_id,$allpass)
{
  
  //Level
	// 1 - College
	// 2 - Dept
	// 3 - Year
	// 4 - Class
	// 5 - Dept Year
	
	$rstFilter = '';
	
	switch($allpass)
	{
	  case 99:
	  break;
	  case 1:
		$rstFilter = " and b.student_id is null ";
	  break;
	  case 0:
		$rstFilter = " and b.student_id is not null ";
	  break;
	}
	
  	$queryStr =
	"Select *
	from
	(
	select 
	RSET.*,
	( 
	CASE 
	WHEN ";//strcmp(dept_code,@dept_code)<>0
	
	//rank case columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." client_id<>@client_id";
	  break;
	  case 2:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0";
	  break;
	  case 3:
		$queryStr = $queryStr."year<>@year";
	  break;
	  case 4:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0 or year<>@year or strcmp(section,@section)<>0 ";
	  break;
	  case 5:
		$queryStr = $queryStr." strcmp(dept_code,@dept_code)<>0 or year<>@year";
	  break;
	}
	
	$queryStr = $queryStr.
	" THEN @curRow := 1
	WHEN percentage=@oldPercent THEN @curRow := @curRow + 0 
	WHEN percentage<>@oldPercent THEN @curRow := @curRow + 1
	END
	)AS rank,
	@oldPercent := percentage,@client_id := client_id,@dept_code := dept_code,@year := year,@section := section
	from
	(
		select
		a.client_id,a.dept_code,a.year,a.section,a.student_id,a.student_name,
		case when b.student_id is null then '1' else '0' end allpass,
		avg(marks_obtained) percentage
		from
		(select c.*,m.student_id,m.student_name,m.marks_obtained
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
		)a left join
		(select distinct student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
		and c.client_id=".$this->db->escape($client_id)." 
		and marks_obtained<pass_mark
		)b
		on a.student_id=b.student_id
		where 1=1".$rstFilter."
		group by a.client_id,a.dept_code,a.year,a.section,a.student_id,a.student_name
		order by a.client_id,a.dept_code,a.year,a.section
	)
	RSET,(SELECT @curRow := 0, @oldPercent := 0, @dept_code := '', @year := 0, @section := '') r";
	
//order by columns for rank
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,";
	  break;
	  case 3:
		$queryStr = $queryStr." order by year,";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,year,section,";
	  break;
	  case 5:
		$queryStr = $queryStr." order by dept_code,year,";
	  break;
	}
	
	$queryStr = $queryStr.
	"percentage desc
	)SCR";
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,rank";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,rank";
	  break;
	  case 3:
		$queryStr = $queryStr." order by year,rank";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,year,section,rank";
	  break;
	  case 5:
		$queryStr = $queryStr." order by dept_code,year,rank";
	  break;
	}
	
	$query = $this->db->query($queryStr);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
  
}

function overallTopperReportSchool($client_id,$exam_id,$filterQry,$level_id,$allpass)
{
  
  //Level
	// 1 - College
	// 2 - Dept
	// 4 - Class
	
	$rstFilter = '';
	
	switch($allpass)
	{
	  case 99:
	  break;
	  case 1:
		$rstFilter = " and b.student_id is null ";
	  break;
	  case 0:
		$rstFilter = " and b.student_id is not null ";
	  break;
	}
	
  	$queryStr =
	"Select *
	from
	(
	select 
	RSET.*,
	( 
	CASE 
	WHEN ";
	
	//rank case columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." client_id<>@client_id";
	  break;
	  case 2:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0";
	  break;
	  case 4:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0 or strcmp(section,@section)<>0 ";
	  break;
	}
	
	$queryStr = $queryStr.
	" THEN @curRow := 1
	WHEN percentage=@oldPercent THEN @curRow := @curRow + 0 
	WHEN percentage<>@oldPercent THEN @curRow := @curRow + 1
	END
	)AS rank,
	@oldPercent := percentage,@client_id := client_id,@dept_code := dept_code,@section := section
	from
	(
		select
		a.client_id,a.dept_code,a.section,
		case when 12-cast(dept_code as unsigned)<=2 then substring(a.student_id,4)
		else substring(a.student_id,3) end AS student_id,
		a.student_name,
		case when b.student_id is null then '1' else '0' end allpass,
		avg(marks_obtained) percentage
		from
		(select c.*,m.student_id,m.student_name,m.marks_obtained
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
		)a left join
		(select distinct student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
		and c.client_id=".$this->db->escape($client_id)." 
		and marks_obtained<pass_mark
		)b
		on a.student_id=b.student_id
		where 1=1".$rstFilter." 
		group by a.client_id,a.dept_code,a.section,a.student_id,a.student_name
		order by a.client_id,a.dept_code,a.section
	)
	RSET,(SELECT @curRow := 0, @oldPercent := 0, @dept_code := '', @section := '') r";
	
//order by columns for rank
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,section,";
	  break;
	}
	
	$queryStr = $queryStr.
	"percentage desc
	)SCR";
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,rank";
	  break;
	  case 2:
		$queryStr = $queryStr." order by cast(dept_code as unsigned),rank";
	  break;
	  case 4:
		$queryStr = $queryStr." order by cast(dept_code as unsigned),section,rank";
	  break;
	}
	
	$query = $this->db->query($queryStr);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
  
}

function subjectRankListReportCollege($client_id,$exam_id,$filterQry,$level_id)
{
  
  //Level
	// 1 - College
	// 2 - Dept
	// 3 - Year
	// 4 - Class
	// 5 - Dept Year
	
  	$queryStr =
	"Select *
	from
	(
	select 
	RSET.*,
	( 
	CASE 
	WHEN ";//strcmp(dept_code,@dept_code)<>0
	
	//rank case columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." client_id<>@client_id";
	  break;
	  case 2:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0";
	  break;
	  case 3:
		$queryStr = $queryStr."year<>@year";
	  break;
	  case 4:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0 or year<>@year or strcmp(section,@section)<>0 ";
	  break;
	  case 5:
		$queryStr = $queryStr." strcmp(dept_code,@dept_code)<>0 or year<>@year";
	  break;
	}
	
	$queryStr = $queryStr.
	" THEN @curRow := 1
	WHEN percentage=@oldPercent THEN @curRow := @curRow + 0 
	WHEN percentage<>@oldPercent THEN @curRow := @curRow + 1
	END
	)AS rank,
	@oldPercent := percentage,@client_id := client_id,@dept_code := dept_code,@year := year,@section := section
	from
	(
		select a.client_id,a.dept_code,a.year,a.section,a.subject_name,
		count(distinct a.student_id) as student_cnt,
		count(distinct(case when b.student_id is null then a.student_id end)) as student_pass_cnt,
		count(distinct(case when b.student_id is null then a.student_id end))
		/count(distinct a.student_id) * 100 as percentage 
		from
		(select distinct c.*,student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
		)a left join
		(select distinct m.batch_id,student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
		and c.client_id=".$this->db->escape($client_id)." and marks_obtained<pass_mark
		)b
		on a.student_id=b.student_id and a.batch_id=b.batch_id
		group by a.client_id,a.dept_code,a.year,a.section,a.subject_name
		order by a.client_id,a.dept_code,a.year,a.section
	)
	RSET,(SELECT @curRow := 0, @oldPercent := 0, @dept_code := '', @year := 0, @section := '') r";
	
//order by columns for rank
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,";
	  break;
	  case 3:
		$queryStr = $queryStr." order by year,";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,year,section,";
	  break;
	  case 5:
		$queryStr = $queryStr." order by dept_code,year,";
	  break;
	}
	
	$queryStr = $queryStr.
	"percentage desc
	)SCR";
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,rank";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,rank";
	  break;
	  case 3:
		$queryStr = $queryStr." order by year,rank";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,year,section,rank";
	  break;
	  case 5:
		$queryStr = $queryStr." order by dept_code,year,rank";
	  break;
	}
	
	$query = $this->db->query($queryStr);

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
  
}

function subjectRankListReportSchool($client_id,$exam_id,$filterQry,$level_id)
{
  
  //Level
	// 1 - College
	// 2 - Dept
	// 4 - Class
	
  	$queryStr =
	"Select *
	from
	(
	select 
	RSET.*,
	( 
	CASE 
	WHEN ";
	
	//rank case columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." client_id<>@client_id";
	  break;
	  case 2:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0";
	  break;
	  case 4:
		$queryStr = $queryStr."strcmp(dept_code,@dept_code)<>0 or strcmp(section,@section)<>0 ";
	  break;
	}
	
	$queryStr = $queryStr.
	" THEN @curRow := 1
	WHEN percentage=@oldPercent THEN @curRow := @curRow + 0 
	WHEN percentage<>@oldPercent THEN @curRow := @curRow + 1
	END
	)AS rank,
	@oldPercent := percentage,@client_id := client_id,@dept_code := dept_code,@section := section
	from
	(
		select a.client_id,a.dept_code,a.section,a.subject_name,
		count(distinct a.student_id) as student_cnt,
		count(distinct(case when b.student_id is null then a.student_id end)) as student_pass_cnt,
		count(distinct(case when b.student_id is null then a.student_id end))
		/count(distinct a.student_id) * 100 as percentage 
		from
		(select distinct c.*,student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)." and c.client_id=".$this->db->escape($client_id)."
		)a left join
		(select distinct m.batch_id,student_id
		from marks m
		join class c
		on m.batch_id=c.batch_id
		where c.lgcl_del_f='N' and c.exam_id=".$this->db->escape($exam_id)."
		and c.client_id=".$this->db->escape($client_id)." and marks_obtained<pass_mark
		)b
		on a.student_id=b.student_id and a.batch_id=b.batch_id
		group by a.client_id,a.dept_code,a.section,a.subject_name
		order by a.client_id,a.dept_code,a.section
	)
	RSET,(SELECT @curRow := 0, @oldPercent := 0, @dept_code := '', @section := '') r";
	
//order by columns for rank
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,";
	  break;
	  case 2:
		$queryStr = $queryStr." order by dept_code,";
	  break;
	  case 4:
		$queryStr = $queryStr." order by dept_code,section,";
	  break;
	}
	
	$queryStr = $queryStr.
	"percentage desc
	)SCR";
	
	$queryStr = $queryStr." ".$filterQry;
	
	//order by columns
	
	switch($level_id)
	{
	  case 1:
		$queryStr = $queryStr." order by client_id,rank";
	  break;
	  case 2:
		$queryStr = $queryStr." order by cast(dept_code as unsigned),rank";
	  break;
	  case 4:
		$queryStr = $queryStr." order by cast(dept_code as unsigned),section,rank";
	  break;
	}
	
	$query = $this->db->query($queryStr);

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
	(select client_id,dept_code,year,section,subject_name,student_id,student_name,marks_obtained from class c
	join  
	(
	select b.batch_id,b.student_id,b.student_name,b.marks_obtained from
	(select batch_id,max(marks_obtained) max from marks
	group by batch_id)a
	join marks b
	on a.batch_id=b.batch_id and a.max=b.marks_obtained
	)m
	on c.batch_id=m.batch_id where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)."
	)SCR ".$filterQry." order by client_id,dept_code,year,section");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function subjectTopperReportSchool($client_id,$exam_id,$filterQry)

{
  $query = $this->db->query("select * from
	(select client_id,dept_code,section,subject_name,
	case when 12-cast(dept_code as unsigned)<=2 then substring(student_id,4)
	else substring(student_id,3) end AS student_id,
	student_name,
	marks_obtained from class c
	join  
	(
	select b.batch_id,b.student_id,b.student_name,b.marks_obtained from
	(select batch_id,max(marks_obtained) max from marks
	group by batch_id)a
	join marks b
	on a.batch_id=b.batch_id and a.max=b.marks_obtained
	)m
	on c.batch_id=m.batch_id where lgcl_del_f='N'
	and exam_id=".$this->db->escape($exam_id)." and client_id=".$this->db->escape($client_id)."
	)SCR ".$filterQry." order by client_id,cast(dept_code as unsigned),section");

   if($query -> num_rows() >= 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }

}

function studentMarkListReportCollege($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from 
    (select client_id,dept_code,year,section,subject_code,subject_name,student_id,
	student_name,total_marks,marks_obtained,
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

function studentMarkListReportSchool($client_id,$exam_id,$filterQry)

{

  $query = $this->db->query("select * from 
    (select client_id,cast(dept_code as unsigned)dept_code,section,subject_name,
	case when 12-cast(dept_code as unsigned)<=2 then substring(student_id,4)
	else substring(student_id,3) end AS student_id,
	student_name,total_marks,marks_obtained,
	case when marks_obtained>=pass_mark then '1' else '0' end result 
	from class cs join marks ms on cs.batch_id=ms.batch_id 
	where lgcl_del_f='N'  and exam_id=".$this->db->escape($exam_id)." 
	and client_id=".$this->db->escape($client_id)."
	)SCR ".$filterQry."
	order by dept_code,section,student_id,subject_name");

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