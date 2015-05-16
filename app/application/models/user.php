<?php
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
}
?>