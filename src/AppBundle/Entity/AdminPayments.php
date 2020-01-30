<?php
// src/AppBundle/Entity/AdminPayments.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminPayments
*
* @ORM\Table(name="users_payment")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPaymentsRepository")
*/
class AdminPayments
{
/**
    * @var int
    *
    * @ORM\Column(name="payment_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $payment_id;
  
   /**
    * @var int
    *
    * @ORM\Column(name="user_id", type="integer")
    */
   private $user_id;
   
  /**
    * @var string
    *
    * @ORM\Column(name="payment_amount", type="string")
    */
   private $payment_amount;
   
 
   /**
    * @var string
    *
    * @ORM\Column(name="ip_address", type="string", length=40)
    */	
   private $ip_address;

   /**
    * @var string
    *
    * @ORM\Column(name="entry_time", type="datetime")
    */	
    private $entry_time;

  public function getPaymentId()
    {
        return $this->payment_id;
    }

  public function getUserId()
    {
        return $this->user_id;
    }	
	
   public function getPaymentAmount()
    {
        return $this->payment_amount;
    }

		
   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		
	
   public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }	

			
 }