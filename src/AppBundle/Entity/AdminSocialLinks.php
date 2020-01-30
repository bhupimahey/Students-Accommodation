<?php
// src/AppBundle/Entity/AdminSocialLinks.php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM; 
/**
* AdminSocialLinks
*
* @ORM\Table(name="social_links")
* @ORM\Entity(repositoryClass="AppBundle\Repository\AdminSocialLinksRepository")
*/

class AdminSocialLinks
{
/**
    * @var int
    *
    * @ORM\Column(name="slinks_id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
   private $id;

   /**
    * @var string
    *
    * @ORM\Column(name="link_title", type="string", length=150)
    */
   private $link_title;
      

   /**
    * @var string
    *
    * @ORM\Column(name="link_text", type="string", length=255)
    */
   private $link_text;
   


    /**
    * @var string
    *
    * @ORM\Column(name="link_icon", type="string", length=255)
    */	
   private $link_icon;



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
        return $this->id;
    }	

   public function getLinkTitle()
    {
        return $this->link_title;
    }
	
   public function setLinkTitle($link_title)
    {
        return $this->link_title=$link_title;
    }
  
   public function getLinkText()
    {
        return $this->link_text;
    }
	
   public function setLinkText($link_text)
    {
        return $this->link_text=$link_text;
    }

   public function getLinkIcon()
    {
        return $this->link_icon;
    }
	
   public function setLinkIcon($link_icon)
    {
        return $this->link_icon=$link_icon;
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