<?php
// src/AppBundle/Entity/AdminPropertyCleanDates.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminPropertyCleanDates
*
* @ORM\Table(name="property_cleaning_dates")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPropertyCleanDatesRepository")
*/

class AdminPropertyCleanDates
{
/**
    * @var int
    *
    * @ORM\Column(name="clning_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $cleaning_id;
 
	 
   /**
    * @var int
    *
    * @ORM\Column(name="property_id", type="integer", length=10)
    */
   private $property_id;
   

   /**
    * @var string
    *
    * @ORM\Column(name="cleaning_date")
    */
   private $cleaning_date;


  /**
    * @var string
    *
    * @ORM\Column(name="notes", type="string", length=150)
    */
   private $notes;

 
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
		
 
  public function getId()
    {
        return $this->cleaning_id;
    }	
  public function setNotes($notes)
    {
        return $this->notes=$notes;
    }
  public function setPropertyId($property_id)
    {
        return $this->property_id=$property_id;
    }


  public function setCleaningDate($cleaning_date)
    {
        return $this->cleaning_date=$cleaning_date;
    }
	
  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }

  public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		
		
 }