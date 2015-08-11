<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	private $containerHeight;
	private $targetPathValue;
	
	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
		$this->load->model('user','',TRUE);
		$this->load->model('common','',TRUE);
		$this->containerHeight = 140;
		$this->targetPathValue = '';
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

	public function alphanumericVal($inp,$name)
	{
		
		if(preg_match('/^[a-zA-Z0-9 ]+$/', $inp))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('alphanumericVal', 'Please enter only alphabets, numbers and spaces for '.$name.' field');
     		return FALSE;
		}		
	}
	
	public function alphanumericNoSpcVal($inp,$name)
	{
		
		if(preg_match('/^[a-zA-Z0-9]+$/', $inp))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('alphanumericNoSpcVal', 'Please enter only alphabets and numbers for '.$name.' field');
     		return FALSE;
		}		
	}
	
	public function numericVal($inp,$name)
	{
		
		if(preg_match('/^[0-9 ]+$/', $inp))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('numericVal', 'Please enter only numbers for '.$name.' field');
     		return FALSE;
		}		
	
	}
	
	public function index()
	{
		redirect('adminstatic');	
	}
	

	
	public function form()
	{
		
		if($this->session->userdata('client_type')==1)
		{
			$this->headerSetup('Upload Results',$this->containerHeight+130);
		}
		else
		{
			$this->headerSetup('Upload Results',$this->containerHeight+100);
		}
		
		if(!isset($_POST['submit']))
		{
			
			$this->load->helper(array('form'));
			$examlist = '';
			$result = $this->user->getExamList($this->session->userdata('client_id'));
			if($result)
			{
				foreach($result as $row)
				{
					$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
				}
			
				$data = array('formaction' => 'upload/form','examlist' => $examlist,'deptHtml' => $this->common->getDeptNames());
				$this->load->view('vuploadresults1',$data);
			}
			else
			{
				$data = array('message' =>
				'<div class="alert alert-danger" role="alert">No exams have been created to upload results.</div>
				<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				$this->load->view('vmessage',$data);
			}
			
					
		}
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('staffname', 'Staff Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Staff Name]');
			if($this->session->userdata('client_type')==1)
			{
				/*$this->form_validation->set_rules('staffid', 'Staff Id', 'trim|required|xss_clean|max_length[250]|callback_alphanumericNoSpcVal[Staff Id]');*/
			}
			$this->form_validation->set_rules('subname', 'Subject Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Name]');
			if($this->session->userdata('client_type')==1)
			{
				$this->form_validation->set_rules('subcode', 'Subject Code', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Code]');
			}
			$this->form_validation->set_rules('maxmark', 'Maximum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Maximum Mark]');
			$this->form_validation->set_rules('minmark', 'Minimum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Minimum Mark]');
			$this->form_validation->set_rules('fileToUpload', 'File', 'callback_csvFileValidation');
	
			$this->form_validation->set_error_delimiters('<p class="uploadErrMsg">* ', '</p>');
			if($this->form_validation->run() == FALSE)
			{
				if($this->targetPathValue)
				{
					unlink($this->targetPathValue);
				}
				$this->load->helper(array('form'));
				$examlist = '';
				$result = $this->user->getExamList($this->session->userdata('client_id'));
				if($result)
				{
					foreach($result as $row)
					{
						$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
					}
				
					$data = array('formaction' => 'upload/form','examlist' => $examlist,'deptHtml' => $this->common->getDeptNames());
					$this->load->view('vuploadresults1',$data);
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">No exams have been created to upload results.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
					$this->load->view('vmessage',$data);
				}		
			}
			else
			{
				if($this->session->userdata('client_type')==1)
				{
					$batch = $this->user->newBatch($this->session->userdata('client_id'),
					$this->input->post('examid'),$this->input->post('staffname'),'99',
					$this->input->post('subname'),$this->input->post('subcode'),$this->input->post('maxmark'),
					$this->input->post('minmark'),$this->input->post('dept_code'),$this->input->post('year'),
					$this->input->post('section'),$this->targetPathValue);
				}
				else
				{
					//for school subname and staff name are same, year is not required
					$batch = $this->user->newBatch($this->session->userdata('client_id'),
					$this->input->post('examid'),$this->input->post('staffname'),'99',
					$this->input->post('subname'),$this->input->post('subname'),$this->input->post('maxmark'),
					$this->input->post('minmark'),$this->input->post('dept_code'),'99',
					$this->input->post('section'),$this->targetPathValue);
				}
				
				if($batch)
				{
					$data = array('message' => '<div class="alert alert-warning" role="alert">Success : Exam Results are uploaded successfully.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				unlink($this->targetPathValue);
				$this->load->view('vmessage',$data);
			}
		}
		
		$this->load->view('footer');
		
	}
	
	
	function csvFileValidation ()
	
	{
		
		// Check if file is selected 

			if (!is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) 
			{
			$this->form_validation->set_message('csvFileValidation', 'Please upload a valid CSV file');
     		return FALSE;
			}
		
		// Move file to folder
		
			$ext = explode('.',$_FILES['fileToUpload']['name']);
			$extension = $ext[1];
			$newname = $this->session->userdata('user_id').rand(1000, 1999).'.'.$extension;
			$target_path = "files/";
			$target_path = $target_path .$newname;
			$this->targetPathValue = $target_path;
			
			if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) 
			{
			$this->form_validation->set_message('csvFileValidation', 'Unable to Upload file to server');
     		return FALSE;
			} 
		
		// Check if file selected is csv

			$examFileType = pathinfo($target_path,PATHINFO_EXTENSION);
			if($examFileType != "csv") 
			{
			$this->form_validation->set_message('csvFileValidation', 'File Uploaded is not a CSV file');
     		return FALSE;
			}
		
		// Check if file selected has data 

			if (filesize($target_path) == 0)
			{
			$this->form_validation->set_message('csvFileValidation', 'File Uploaded is empty');
     		return FALSE;
			}
		
		// Check maximum file size 

			if (filesize($target_path) > (2*1048576))
			{
			$this->form_validation->set_message('csvFileValidation', 'File Uploaded is too big. Allowed is upto 2 MB.');
     		return FALSE;
			}
			
			$file = fopen($target_path, "r");
			$numcols = 0;
			$numrows = 1;
		
		// Column and File Validation

			while ($line = fgetcsv($file, 1000))
			{
			  // count the number of columns
			  $numcols = count(array_filter(($line),'strlen'));
			  //$numcols = count($line);
			  
			  /*for ($j = 0; $j < count($line); $j++) {
                if(trim($line[$j]))
				   {
						$numcols++;
				   }
            }*/
            

			  // Bail out of the loop if columns are incorrect
			  if ($numcols != 3) 
			  {
					$this->form_validation->set_message('csvFileValidation', 'Uploaded file has less/more columns at Row '.$numrows.' Column Count : '.$numcols.' '.$line[0].' '.$line[1].' '.$line[2]);
					return FALSE;
			  }
			  
			  
			  if($numrows>=2)
			  {
					//Data validation of student_id column
					if(preg_match('/^[a-zA-Z0-9]+$/', trim($line[0])))
					{
						
					}
					else
					{
						$this->form_validation->set_message('csvFileValidation', 'Student_Id column has invalid data in uploaded file at Row '.$numrows.' Column 1.');
						return FALSE;
					}
					
					//Data validation of student_name column
					if(preg_match('/^[a-zA-Z ]+$/', trim($line[1])))
					{
						
					}
					else
					{
						$this->form_validation->set_message('csvFileValidation', 'Student_name column has invalid data in uploaded file at Row '.$numrows.' Column 2.');
						return FALSE;
					}
					
					//Data validation of marks_obtained column
					if(preg_match('/^[0-9]+$/', trim($line[2])))
					{
						
					}
					else
					{
						$this->form_validation->set_message('csvFileValidation', 'Marks_Obtained column has invalid data in uploaded file at Row '.$numrows.' Column 3.');
						return FALSE;
					}
			  }
			  
			  $numrows++;

			}
			
		// Check no of rows

			
			if ($numrows <= 1) 
			{
			  $this->form_validation->set_message('csvFileValidation', 'Uploaded file has only one or no rows');
			  return FALSE;
			}
			
		// Check max no of rows

			
			if ($numrows > 102) 
			{
			  $this->form_validation->set_message('csvFileValidation', 'Maximum row limit exceeded. You can only upload for a max of 100 students.');
			  return FALSE;
			}

		
		
		return TRUE;
		
	}
	


}


