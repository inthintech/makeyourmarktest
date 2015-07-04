<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

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
		$this->containerHeight = 130;
    }
	
	public function headerSetup($title,$height)
	{
		$client_name = '';
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{
			$client_name= $row->client_name;
		}
		$headerdata = array('usertype' => $this->session->userdata('user_type'),
		'client_name' => $client_name ,
		'title' => $title,
		'container_height' => $height );
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
	
	/*
	public function result()
	{

		$this->headerSetup('Upload Results',$this->containerHeight+170);
		
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
			
				$data = array('formaction' => 'upload/result','examlist' => $examlist);
				$this->load->view('vuploadresults',$data);
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
			$this->form_validation->set_rules('staffid', 'Staff Id', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Staff Id]');
			$this->form_validation->set_rules('subname', 'Subject Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Name]');
			$this->form_validation->set_rules('subcode', 'Subject Code', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Code]');
			$this->form_validation->set_rules('maxmark', 'Maximum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Maximum Mark]');
			$this->form_validation->set_rules('minmark', 'Minimum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Minimum Mark]');
			$this->form_validation->set_rules('fileToUpload', 'File', 'callback_fileValidation');
	
			$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');
			if($this->form_validation->run() == FALSE)
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
				
					$data = array('formaction' => 'upload/result','examlist' => $examlist);
					$this->load->view('vuploadresults',$data);
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
				$target_path = "files/";
				$target_path = $target_path . basename( $_FILES['fileToUpload']['name']);
				
				$result = $this->user->newResult($this->session->userdata('client_id'),
				$this->input->post('examid'),$target_path,$this->input->post('staffname'),$this->input->post('staffid'),
				$this->input->post('subname'),$this->input->post('subcode'),$this->input->post('maxmark'),
				$this->input->post('minmark'),$this->input->post('dept_code'),$this->input->post('year'),
				$this->input->post('section')
				);
				
				unlink($target_path);
				
				if($result)
				{
					
					$data = array('message' => '<div class="alert alert-success" role="alert">Success : Results have been uploaded.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				$this->load->view('vmessage',$data);
			}
		}
		
		$this->load->view('footer');
		
	}*/
	
	public function step1()
	{

		$this->headerSetup('Upload Results',$this->containerHeight+170);
		
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
			
				$data = array('formaction' => 'upload/step1','examlist' => $examlist);
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
			$this->form_validation->set_rules('staffid', 'Staff Id', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Staff Id]');
			$this->form_validation->set_rules('subname', 'Subject Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Name]');
			$this->form_validation->set_rules('subcode', 'Subject Code', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Subject Code]');
			$this->form_validation->set_rules('maxmark', 'Maximum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Maximum Mark]');
			$this->form_validation->set_rules('minmark', 'Minimum Mark', 'trim|required|xss_clean|max_length[250]|callback_numericVal[Minimum Mark]');
			/*$this->form_validation->set_rules('fileToUpload', 'File', 'callback_fileValidation');*/
	
			$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');
			if($this->form_validation->run() == FALSE)
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
				
					$data = array('formaction' => 'upload/step1','examlist' => $examlist);
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
				$batch = $this->user->newBatch($this->session->userdata('client_id'),
				$this->input->post('examid'),$this->input->post('staffname'),$this->input->post('staffid'),
				$this->input->post('subname'),$this->input->post('subcode'),$this->input->post('maxmark'),
				$this->input->post('minmark'),$this->input->post('dept_code'),$this->input->post('year'),
				$this->input->post('section'));
				
				if($batch)
				{
					$this->session->set_flashdata('fdata', 'en09876mmmm');
					redirect('upload/step2/'.$batch);
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
							
			}
		}
		
		$this->load->view('footer');
		
	}
	
	public function step2($batch)
	{
		
		
		
		$this->headerSetup('Upload Results',$this->containerHeight);
		
		if(!isset($_POST['submit']))
		{
			//PREVENT DIRECT ACCESS OF STEP2
			/*
			if(!$this->session->flashdata('fdata'))
			{
				redirect('upload/step1');
			}
			*/
			
			$this->load->helper(array('form'));
			$data = array('formaction' => 'upload/step2/'.$batch ,'batch' => $batch);
			$this->load->view('vuploadresults2',$data);
		}
		
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('fileToUpload', 'File', 'callback_csvFileValidation');
	
			$this->form_validation->set_error_delimiters('<br><p class="errorMsg">', '</p>');
			if($this->form_validation->run() == FALSE)
			{
				$this->load->helper(array('form'));
				$data = array('formaction' => 'upload/step2/'.$batch ,'batch' => $batch);
				$this->load->view('vuploadresults2',$data);
			}
			else
			{
				/*
				$target_path = "files/";
				$target_path = $target_path . basename( $_FILES['fileToUpload']['name']);
				
				$result = $this->user->newResult($this->session->userdata('client_id'),
				$this->input->post('examid'),$target_path,$this->input->post('staffname'),$this->input->post('staffid'),
				$this->input->post('subname'),$this->input->post('subcode'),$this->input->post('maxmark'),
				$this->input->post('minmark'),$this->input->post('dept_code'),$this->input->post('year'),
				$this->input->post('section')
				);
				
				unlink($target_path);
				
				if($result)
				{
					
					$data = array('message' => '<div class="alert alert-success" role="alert">Success : Results have been uploaded.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>
					<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
				}
				$this->load->view('vmessage',$data);
				*/
				
				
				$data = array('message' => '<div class="alert alert-success" role="alert">Success : Results have been uploaded.</div>');
				$this->load->view('vmessage',$data);
				
			}
		}
		
		$this->load->view('footer');
		
	}

	
	
	public function fileValidation()
	{
			
			/*
			
			// Check if file is selected 

			if (!is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) 
			{
			$this->form_validation->set_message('fileValidation', 'Please upload a valid CSV file');
     		return FALSE;
			}
			
			// Check if file selected is csv

			$examFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
			if($examFileType != "csv") 
			{
			$this->form_validation->set_message('fileValidation', 'File Uploaded is not a CSV file');
     		return FALSE;
			}

			// Check if file selected has data 

			if ($_FILES["fileToUpload"]["size"] == 0)
			{
			$this->form_validation->set_message('fileValidation', 'File Uploaded is empty');
     		return FALSE;
			}

			// Check file size 

			if ($_FILES["fileToUpload"]["size"] > (5*1048576))
			{
			$this->form_validation->set_message('fileValidation', 'File Uploaded is too big. Allowed is upto 5MB.');
     		return FALSE;
			}
			
			$target_path = "files/";

			$target_path = $target_path . basename( $_FILES['fileToUpload']['name']); 

			if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) 
			{
			$this->form_validation->set_message('fileValidation', 'Unable to Upload file to server');
     		return FALSE;
			} 
			
			// Check no of columns 

			$file = fopen($target_path, "r"); 
			while ($line = fgetcsv($file))
			{
			  // count($line) is the number of columns
			  $numcols = count(array_filter(($line),'strlen'));

			  // Bail out of the loop if columns are incorrect
			  if ($numcols != 3) 
			  {
			    $this->form_validation->set_message('fileValidation', 'Uploaded file has less/more columns or Missing Values.');
				fclose($file);
     			return FALSE;
			  }

			}
			
			fclose($file);

			  // Bail out of the loop if one or less rows present

			$fp = file($target_path);
			  if (count($fp) <= 1) 
			  {
			    $this->form_validation->set_message('fileValidation', 'Uploaded file has one or no rows');
				
     			return FALSE;
			  }

			  if (count($fp) > 100) 
			  {
			    $this->form_validation->set_message('fileValidation', 'Maximum limit exceeded. You can only upload for a max of 100 students.');
				
     			return FALSE;
			  }

			return TRUE;
			
			*/
			
			
			
			

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
			
			if(!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) 
			{
			$this->form_validation->set_message('csvFileValidation', 'Unable to Upload file to server');
     		return FALSE;
			} 
		
		return TRUE;
		
	}
	


}


