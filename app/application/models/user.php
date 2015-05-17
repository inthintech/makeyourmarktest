<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User extends CI_Model
{
 
 function login($username, $password)
 {
   
    $query = $this->db->query("select clients.client_id,clients.is_active from users join clients 
    on users.client_id=clients.client_id where username=".$this->db->escape($username)." and passwd=".$this->db->escape($password));

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }

function getClientName($client_id)
 {
   
    $query = $this->db->query("select client_name from clients where client_id=".$this->db->escape($client_id));

   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 function getSubscriptionDetails($client_id)
 {
   
    $query = $this->db->query("select a.client_name,DATE_FORMAT(subscription_start_date,'%b %D %Y')subscription_start_date,
    DATE_FORMAT(subscription_end_date,'%b %D %Y')subscription_end_date,c.package_name,c.package_desc from clients a 
    join clientpackage b
    on a.client_id=b.client_id
    join package c
    on b.package_id=c.package_id where a.client_id=".$this->db->escape($client_id));

   if($query -> num_rows() == 1)
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