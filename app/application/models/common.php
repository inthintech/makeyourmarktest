<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Common extends CI_Model
{
 
 function getDeptNames()
 {
    $deptHtml = '';
    
    if($this->session->userdata('client_type')==1)
    {
        $deptHtml=$deptHtml.'
        <option selected value="CSE">Computer Science (CSE)</option>
        <option value="IT">Information Technology (IT)</option>
        <option value="EEE">Electrical and Electronics (EEE)</option>
        <option value="EIE">Electronics and Instrumentation (EIE)</option>
        <option value="ECE">Electronics and Communication (ECE)</option>
        <option value="MECH">Mechanical (MECH)</option>
        <option value="ICE">Instrumentaion and Control (ICE)</option>
        ';
    }
    
    if($this->session->userdata('client_type')==2)
    {
        $deptHtml=$deptHtml.'
        <option selected value="PHY">B.Sc Physics (PHY)</option>
        <option selected value="MAT">B.Sc Maths (MAT)</option>
        ';
    }
    
   if($this->session->userdata('client_type')==3)
   {
       $deptHtml=$deptHtml.'
       <option selected value="6">6th Standard</option>
       <option value="7">7th Standard</option>
       <option value="8">8th Standard</option>
       <option value="9">9th Standard</option>
       <option value="10">10th Standard</option>
       <option value="11">11th Standard</option>
       <option value="12">12th Standard</option>
       ';
   }
   
   return $deptHtml;
 
 
 }

}
?>