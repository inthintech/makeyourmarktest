<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify extends CI_Controller {

	private $containerHeight;
	
	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
		$this->load->model('user','',TRUE);
		$this->containerHeight = 100;
    }
	
	public function headerSetup($title,$height)
	{
		$client_name = '';
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{
			$client_name= $row->client_name;
		}
		$headerdata = array(
		'client_name' => $client_name ,
		'title' => $title,
		'container_height' => $height,
		'user_name' => $this->session->userdata('user_name'));
		$this->load->view('header',$headerdata);
	}
	
	public function index()
	{
		redirect('adminstatic');	
	}
	
	public function exam()
	{
		$this->headerSetup('Verify result',$this->containerHeight);
		
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			
			} 
			$data = array('formaction' => 'verify/result','examlist' => $examlist);
			$this->load->view('vverify',$data);
		}
		else
		{
			$data = array('message' => '<div class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
			$this->load->view('vmessage',$data);
		}
		
		$this->load->view('footer');
	}
	
	public function result()
	{
		if(!isset($_POST['submit']))
		{
			redirect('verify/exam');
		}
		
		$this->headerSetup('Verify result',$this->containerHeight+30);
		
		$result = $this->user->getResultInfo($this->session->userdata('client_id'),$this->input->post('examid'));
		$html = '';
		if($result)
		{
			
			$sno = 0;
			foreach($result as $row)
			{
				$sno++;
				$class_code = '';
				if($this->session->userdata('client_type')==1)
				{
					$class_code = $row->dept_code." ".$row->year." ".$row->section;
				}
				else
				{
					$class_code = $row->dept_code." ".$row->section;
				}
				$html= $html."<tr><td>".$sno."</td><td>".$class_code."</td>
				<td>".$row->staff_name."</td>
				<td>".$row->subject_name."</td>
				<td>".$row->count."</td>
				<td>
				<form target=\"_blank\" action=\"".site_url('verify/viewresults')."\" method=\"POST\">
				<input type=\"hidden\" name=\"batchid\" value=".$row->batch_id.">
				<input type=\"hidden\" name=\"examid\" value=".$row->exam_id.">  
				<button type=\"submit\" name=\"submit\" class=\"btn btn-success\">View</button>
				</form>
				</td>
				<td>
				<form action=\"".site_url('verify/deleteresults')."\" method=\"POST\">
				<input type=\"hidden\" name=\"batchid\" value=".$row->batch_id.">
				<input type=\"hidden\" name=\"examid\" value=".$row->exam_id.">";
				
				if(($row->user_id==$this->session->userdata('user_id'))||($this->session->userdata('user_type')==1))
				{
					$html = $html."<button type=\"submit\" name=\"submit\" class=\"btn btn-danger\">Delete</button>";
				}
				else
				{
					$html = $html."<button disabled name=\"submit\" class=\"btn btn-default\">Delete</button>";
				}
				
				$html = $html."</form></td></tr>";
			}
			$data = array('resultsInfo' => $html);
			$this->load->view('vverifyoption',$data);
		}
		else
		{
			$data = array('message' => '<div class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
			$this->load->view('vmessage',$data);
		}
				
		$this->load->view('footer');
	}
	
	public function viewresults()
	{
		if(!isset($_POST['submit']))
		{
			redirect('verify/exam');
		}
		
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{
			$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'View Results');
		$this->load->view('rptheader',$headerdata);
		
		$result = $this->user->getResultDetails($this->session->userdata('client_id'),$this->input->post('examid'),$this->input->post('batchid'));
		$html = '';
		if($result)
		{
			$sno = 0;
			foreach($result as $row)
			{
				if($row->marks_obtained>=$row->pass_mark)
					{
						$stat = "<td style='background:green;color:white;'>".$row->marks_obtained."</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>".$row->marks_obtained."</td>";
					}
				$sno++;
				$html= $html."<tr><td>".$sno."</td><td>".$row->student_id."</td><td>".$row->student_name."</td>
				<td>".$row->total_marks."</td><td>".$row->pass_mark."</td>".$stat;
				
			} 
		}
		$data = array('resultsInfo' => $html);
		$this->load->view('vverifydetails',$data);
		
		$this->load->view('footer');

	}
	
	public function deleteresults()
	{
		if(!isset($_POST['submit']))
		{
			redirect('verify/exam');
		}
		
		$this->headerSetup('Delete result',$this->containerHeight);
		$result = $this->user->removeResults($this->session->userdata('client_id'),$this->input->post('examid'),$this->input->post('batchid'));
		if($result)
		{
			$data = array('message' => '<div class="alert alert-success" role="alert">Success : Results are deleted from system.</div>');
			
		}
		else
		{
			$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Unable to delete the results.</div>');
			
		}
		$this->load->view('vmessage',$data);
		$this->load->view('footer');

	}
}