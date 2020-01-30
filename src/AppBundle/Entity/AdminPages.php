<?php
// src/AppBundle/Entity/AdminPages.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminPages
*
* @ORM\Table(name="pages")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminPagesRepository")
*/

class AdminPages
{
/**
    * @var int
    *
    * @ORM\Column(name="page_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $id;

   /**
    * @var string
    *
    * @ORM\Column(name="page_title", type="string", length=150)
    */
   private $page_title;
      

   /**
    * @var string
    *
    * @ORM\Column(name="page_url_title", type="string", length=150)
    */
   private $page_url_title;
   


    /**
    * @var string
    *
    * @ORM\Column(name="page_description", type="text")
    */	
   private $page_description;



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

   /**
    * @var string
    *
    * @ORM\Column(name="modified_time", type="datetime")
    */	
    private $modified_time;

 
    public function getId()
    {
        return $this->id;
    }	
	  

   public function getPageTitle()
    {
        return $this->page_title;
    }
	
   public function setPageTitle($page_title)
    {
        return $this->page_title=$page_title;
    }
    
    
   public function getPageUrlTitle()
    {
        return $this->page_url_title;
    }

  public function setPageUrlTitle($page_url_title)
    {
        return $this->page_url_title=$page_url_title;
    }

   public function getPageDescription()
    {
        return $this->page_description;
    }
	
   public function setPageDescription($page_description)
    {
        return $this->page_description=$page_description;
    }

   public function getEntryTime()
    {
        return $this->entry_time;
    }	
	
   public function setEntryTime($entry_time)
    {
        return $this->entry_time=$entry_time;
    }		


   public function getModifiedTime()
    {
        return $this->modified_time;
    }	
	
   public function setModifiedTime($modified_time)
    {
        return $this->modified_time=$modified_time;
    }		



  public function setIpAddress($ip_address)
    {
        return $this->ip_address=$ip_address;
    }		
 }