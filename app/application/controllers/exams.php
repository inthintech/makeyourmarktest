<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exams extends CI_Controller {


	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        $this->load->model('user','',TRUE);
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
    }

/*----------------------------------------------  Subscription Details  ----------------------------------------------*/

	public function index()
	{

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Welcome to Make Your Mark','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$result = $this->user->getSubscriptionDetails($this->session->userdata('client_id'));
		$subscriptiondata = '';
		foreach($result as $row)
			{

			$subscriptiondata = array('subscription_info' => '<tr><td class="home_header">Name</td><td>'.$row->client_name.'</td></tr><tr><td class="home_header">Active From</td>
					<td>'.$row->subscription_start_date.'</td></tr>
				<tr><td class="home_header">Subscription Ends On</td><td>'.$row->subscription_end_date.'</td></tr><tr><td class="home_header">Package Name</td>
				<td>'.$row->package_name.'</td></tr>
				<tr><td class="home_header">Package Description</td><td>'.$row->package_desc.'</td></tr>');
			} 
		$this->load->view('vsubscription',$subscriptiondata);
		$this->load->view('footer');
		
	}


/*----------------------------------------------  Add a new Exam ----------------------------------------------*/

	public function addexam()
	{

	
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add new exam','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));
	    $this->load->view('vnewexam');
		$this->load->view('footer');		
					
						
	}

	public function addexamstatus()
	{

		if(!isset($_POST['submit']))
		{
			redirect('exams/addexam');
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('ename', 'Exam Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Exam Name]');
		$this->form_validation->set_error_delimiters('<br><br><p class="errorMsg">', '</p>');
		
		if($this->form_validation->run() == FALSE)
		   	{
			     
				$this->addexam();		 

		   }
	   else
		   {

				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
				{
					$client_name= $row->client_name;
				} 
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add new exam','container_height' => 130 );
				$this->load->view('header',$headerdata);
				if($this->user->newExamEntry($this->session->userdata('client_id'),$this->input->post('ename')))
				{
					$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : New exam record has been created.</div>');
				}
				else
				{
					$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
				}
			   	
			    $this->load->view('vmessage',$statusdata);
				$this->load->view('footer');			 

		   }


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


/*----------------------------------------------  Exam Status Details  ----------------------------------------------*/

	public function status()
	{
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
			$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Exam Status','container_height' => 130 );
			$this->load->view('header',$headerdata);
		
		
		$result = $this->user->getExamStatus($this->session->userdata('client_id'));

		if($result)
			{
				$examsts = '';
				$sno = 0;
				foreach($result as $row)
					{
					$sno++;
					$examsts = $examsts."<tr><td>".$sno."</td><td>".$row->exam_name."</td><td>".$row->cdate."</td><td>".$row->status_msg."</td></tr>";
					} 
				
				$statusdata = array('examstatus' => $examsts);	
				$this->load->view('vexamstatus',$statusdata);
			}
		else
			{
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Exam Status','container_height' => 130 );
				$this->load->view('header',$headerdata);
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : No exam has been created. Please create an exam.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		$this->load->view('footer');			 
		
	}



/*----------------------------------------------  Upload Results ----------------------------------------------*/

	
	

	public function upload()
	{
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Upload Exam Results','container_height' => 300 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));
		$result = $this->user->getExamList($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
 			{
   			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
   			
  			} 
  			$examdata = array('examlist' => $examlist);
			$this->load->view('vuploadresults',$examdata);
		}
		else
		{
			$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : No exam has been created. Please create an exam.</div>');
			$this->load->view('vmessage',$statusdata);
		}

		$this->load->view('footer');	
	}





	public function uploadstatus()

	{

		if(!isset($_POST['submit']))
		{
			redirect('exams/upload');
		}

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
			$this->upload();		
		}
		else
		{
			$result = $this->user->getClientName($this->session->userdata('client_id'));
			foreach($result as $row)
				{

				$client_name= $row->client_name;
				} 
			$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Upload Exam Results','container_height' => 130 );
			$this->load->view('header',$headerdata);
			$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : Results have been uploaded.</div>');
			$this->load->view('vmessage',$statusdata);
			$this->load->view('footer');
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

	public function fileValidation()
	{
			
			/* Check if file is selected */

			if (!is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) 
			{
			$this->form_validation->set_message('fileValidation', 'Please upload a valid CSV file');
     		return FALSE;
			}
			
			/* Check if file selected is csv*/

			$examFileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
			if($examFileType != "csv") 
			{
			$this->form_validation->set_message('fileValidation', 'File Uploaded is not a CSV file');
     		return FALSE;
			}

			/* Check if file selected has data */

			if ($_FILES["fileToUpload"]["size"] == 0)
			{
			$this->form_validation->set_message('fileValidation', 'File Uploaded is empty');
     		return FALSE;
			}

			/* Check file size */

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
			
			/* Check no of columns */

			$file = fopen($target_path, "r"); 
			while ($line = fgetcsv($file))
			{
			  // count($line) is the number of columns
			  $numcols = count(array_filter(($line),'strlen'));

			  // Bail out of the loop if columns are incorrect
			  if ($numcols != 3) 
			  {
			    $this->form_validation->set_message('fileValidation', 'Uploaded file has less/more columns or Missing Values.');
     			return FALSE;
			  }

			}

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


			fclose($file);
			//fclose($fp);

			$result = $this->user->newResult($this->session->userdata('client_id'),
				$this->input->post('examid'),$target_path,$this->input->post('staffname'),$this->input->post('staffid'),
				$this->input->post('subname'),$this->input->post('subcode'),$this->input->post('maxmark'),
				$this->input->post('minmark'),$this->input->post('dept_code'),$this->input->post('year'),
				$this->input->post('section')
				);

			if($result)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}

	}


/*----------------------------------------------  Verify Results ----------------------------------------------*/


	public function verify()
		{

			$result = $this->user->getClientName($this->session->userdata('client_id'));
			foreach($result as $row)
 			{
   
   			$client_name= $row->client_name;
  			} 
			$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Verify your results','container_height' => 130 );
			$this->load->view('header',$headerdata);
			$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
			if($result)
			{
				$examlist = '';
				foreach($result as $row)
	 			{
	   			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
	   			
	  			} 
	  			$examdata = array('examlist' => $examlist);
				$this->load->view('vverify',$examdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
				$this->load->view('vmessage',$statusdata);
			}
			$this->load->view('footer');
			
		}

	public function verifystatus()
	{

		if(!isset($_POST['submit']))
		{
			redirect('exams/verify');
		}

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Check or Delete Results','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$result = $this->user->getResultInfo($this->session->userdata('client_id'),$this->input->post('examid'));

		if($result)
			{
				$html = '';
				$sno = 0;
				foreach($result as $row)
					{
					$sno++;
					$html= $html."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->staff_name."</td>
					<td>".$row->subject_name."</td>
					<td>
					<form target=\"_blank\" action=\"".site_url('exams/viewresults')."\" method=\"POST\">
					<input type=\"hidden\" name=\"batchid\" value=".$row->batch_id.">
					<input type=\"hidden\" name=\"examid\" value=".$row->exam_id.">  
					<button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">View</button>
					</form>
					</td>
					<td>
					<form action=\"".site_url('exams/deleteresults')."\" method=\"POST\">
					<input type=\"hidden\" name=\"batchid\" value=".$row->batch_id.">
					<input type=\"hidden\" name=\"examid\" value=".$row->exam_id.">  
					<button type=\"submit\" name=\"submit\" class=\"btn btn-danger\">Delete</button>
					</form>
					</td></tr>";
					} 
			}
		$data = array('resultsInfo' => $html);

		$this->load->view('vverifyoption',$data);
		$this->load->view('footer');
		
	}

	public function viewresults()

	{

		if(!isset($_POST['submit']))
		{
			redirect('exams/verify');
		}

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'View Results');
		$this->load->view('rptheader',$headerdata);
		$result = $this->user->getResultDetails($this->session->userdata('client_id'),$this->input->post('examid'),$this->input->post('batchid'));
		if($result)
			{
				$html = '';
				$sno = 0;
				foreach($result as $row)
					{
					$sno++;
					$html= $html."<tr><td>".$sno."</td><td>".$row->student_id."</td><td>".$row->student_name."</td>
					<td>".$row->total_marks."</td><td>".$row->pass_mark."</td><td>".$row->marks_obtained."</td>";
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
			redirect('exams/verify');
		}
		$res = $this->user->removeResults($this->session->userdata('client_id'),$this->input->post('examid'),$this->input->post('batchid'));
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Welcome to Make Your Mark','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : Results are deleted from system.</div>');
		$this->load->view('vmessage',$statusdata);
		$this->load->view('footer');
	}


/*----------------------------------------------  Add User ----------------------------------------------*/

	public function adduser()

	{

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add New User','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));
		$this->load->view('vadduser');
		$this->load->view('footer');
		
	}

	public function adduserstatus()

	{

		if(!isset($_POST['submit']))
		{
			redirect('exams/adduser');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('uname', 'User Name', 'trim|required|xss_clean|max_length[15]|callback_alphanumericValnoSpc[User Name]|callback_checkUname');
		$this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean|max_length[15]');
		$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');

		if($this->form_validation->run() == FALSE)
	   	{
		    $this->adduser();		 
		}
		else

		{
			if($this->user->newUserEntry($this->session->userdata('client_id'),$this->input->post('uname'),$this->input->post('pass')))
			{

				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
					{

					$client_name= $row->client_name;
					} 
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add new user','container_height' => 130 );
				$this->load->view('header',$headerdata);
				$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : New user has been created.</div>');
				$this->load->view('vmessage',$statusdata);
				$this->load->view('footer');


			}
			else
			{
				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
					{

					$client_name= $row->client_name;
					} 
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add new user','container_height' => 130 );
				$this->load->view('header',$headerdata);
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
				$this->load->view('vmessage',$statusdata);
				$this->load->view('footer');

			}

		}

	
		
	}


	public function checkUname($inp)
	{
		
		if($this->user->checkUsername($inp))
		{
			
			$this->form_validation->set_message('checkUname', 'Username already exists');
     		return FALSE;			
		}
		else
		{	
			return TRUE;	
		}		
	}

	public function alphanumericValnoSpc($inp,$name)
	{
		
		if(preg_match('/^[a-zA-Z0-9]+$/', $inp))
		//check if only alphanumeric,numbers are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('alphanumericValnoSpc', 'Please enter only alphabets and numbers for '.$name.' field');
     		return FALSE;
		}		
	}

/*----------------------------------------------  Delete User ----------------------------------------------*/

	public function deleteuser()
	{
		
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Delete User','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));

		$result = $this->user->getUserList($this->session->userdata('client_id'));
			if($result)
			{
				$userlist = '';
				foreach($result as $row)
	 			{
	   			$userlist = "<option selected value=".$row->user_id.">".$row->username."</option>".$userlist;
	   			
	  			} 
	  			$data = array('userlist' => $userlist);
				$this->load->view('vdeleteuser',$data);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : No user has been created. Please create a user.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		$this->load->view('footer');

	}
	
	public function deleteuserstatus()
	{
		if(!isset($_POST['submit']))
		{
			redirect('exams/deleteuser');
		}
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Delete User','container_height' => 130 );
		$this->load->view('header',$headerdata);
	
		$result = $this->user->removeUser($this->session->userdata('client_id'),$this->input->post('userid'));
		if($result)
		{
			$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : User is deleted from system.</div>');
			$this->load->view('vmessage',$statusdata);			
		}
		else
		{
			$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
			$this->load->view('vmessage',$statusdata);			
		}
		$this->load->view('footer');
	}

/*----------------------------------------------  Change Password ----------------------------------------------*/
	
	public function changepassword()
	{
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Change Password','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));
		$this->load->view('vchangepass');
		$this->load->view('footer');
	}
	
	public function changepasswordstatus()
	{
		if(!isset($_POST['submit']))
		{
			redirect('exams/changepassword');
		}
		

		$this->load->library('form_validation');
		$this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required|xss_clean|max_length[15]|callback_checkOldPass');
		$this->form_validation->set_rules('newpass', 'Password', 'trim|required|xss_clean|max_length[15]');
		$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');

		if($this->form_validation->run() == FALSE)
	   	{
		    $this->changepassword();		 
		}
		else

		{

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Change Password','container_height' => 130 );
		$this->load->view('header',$headerdata);

		if($this->user->changePassword($this->session->userdata('user_id'),$this->input->post('newpass')))
		{
			$statusdata = array('message' => '<div class="alert alert-success" role="alert">Success : Password is changed.</div>');
			$this->load->view('vmessage',$statusdata);
		}
		else
		{
			$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
			$this->load->view('vmessage',$statusdata);
		}

		$this->load->view('footer');


		}

		
	}

	public function checkOldPass($inp)
	{

		if($this->user->checkPassword($this->session->userdata('user_id'),$inp))
		{
			
			return TRUE;		
		}
		else
		{	
				
			$this->form_validation->set_message('checkOldPass', 'Current Password entered is wrong.'.$this->session->userdata('user_id'));
     		return FALSE;	
		}		

	}

/*----------------------------------------------  Create CSV ----------------------------------------------*/

	public function createcsv()

	{

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'How to Create CSV file','container_height' => 180 );
		$this->load->view('header',$headerdata);
		$this->load->view('vcreatecsv');
		$this->load->view('footer');

	}



/*----------------------------------------------  Create CSV ----------------------------------------------*/

	public function help()

	{

		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{

		$client_name= $row->client_name;
		} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'How to Create CSV file','container_height' => 130 );
		$this->load->view('header',$headerdata);
		$statusdata = array('message' => '<p class="help">If you need any technical support or if you have any feedback about our product, please contact us at 
<span style="color:blue;">keyrelations@gmail.com</span> <br><br>Note : Please include your Institution name and contact number in the mail.</p>');
		$this->load->view('vmessage',$statusdata);
		$this->load->view('footer');

	}


}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */