<?php
// src/AppBundle/Service/CommonConfig.php
namespace AppBundle\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\AdminPages; 



class CommonConfig
{ 
   
  public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

  public function getPagesConfig(){
	$all_pages =  $this->entityManager->getRepository('AppBundle:AdminPages')->AllPagesMenus();
	return $all_pages;
   }

    
 public function getRequestsConfig($index_key=NULL)
    {
        $requests = [
            '1'=>'Paint',
            '2'=>'Water Supply',
            '3'=>'Whitewashed'
        ];
		if($index_key!=NULL)
		return $requests[$index_key];
		else
        return $requests;
    }

 public function GendersConfig($index_key=NULL)
    {
        $requests = [
            'Male'=>'m',
            'Female'=>'f',
            
        ];
		if($index_key!=NULL)
		return $requests[$index_key];
		else
        return $requests;
    }
	
	    
  public function ArrayReverse($arraylist){
    $return_array=array();  
    if($arraylist){
        foreach($arraylist as $array_keys => $array_values){
            $return_array[$array_values]=$array_keys;
        }
     }
     return $return_array;
  }
  

  public function getUserSession(){
      $session = new Session();
	  if(!$session->get('customer_id'))
	     return "0";
	    else
	    return "1";
  }


 public function PropertyConfig($array_name,$array_key=NULL)
    {
    $config = array();
    $type_of_property=array();       
    $type_of_property['Choose Type']='';       
    $type_of_property["2+ Bedrooms"]="1";
    $type_of_property["1 Bedroom"]="2";
    $type_of_property["Studio"]="3";
    $type_of_property["Granny Flat"]="4";
    $config['type_of_property'] = $type_of_property;
 
    $total_bedrooms=array();         
    $total_bedrooms['Choose Bedrooms']='';
    $total_bedrooms["2"]="1";
    $total_bedrooms["3"]="2";
    $total_bedrooms["4"]="3";
    $total_bedrooms["5"]="4";
    $total_bedrooms["6+"]="5";
    $config['total_bedrooms'] = $total_bedrooms;
    
    $total_bathrooms=array();          
    $total_bathrooms['Choose Bathrooms']='';
    $total_bathrooms["1"]="1";
    $total_bathrooms["2"]="2";
    $total_bathrooms["3"]="3";
    $total_bathrooms["4+"]="4";
    $config['total_bathrooms'] = $total_bathrooms;
    
    $parking_status=array();        
    $parking_status['Choose Parking']='';
    $parking_status["No parking"]="1";
    $parking_status["Off-street parking"]="2";
    $parking_status["On-street Parking"]="3";
    $config['parking_status'] = $parking_status;
    
    
    $internet_status=array();           
    $internet_status['Choose Internet']='';
    $internet_status["No internet"]="1";
    $internet_status["Available but not inc in rent"]="2";
    $internet_status["Included in rent"]="3";
    $internet_status["Unlimited included in rent"]="4";
    $config['internet_status'] = $internet_status;
    
    
    $total_flatmates=array();        
    $total_flatmates['Choose Flatmates']='';
    $total_flatmates["1"]="1";
    $total_flatmates["2"]="2";
    $total_flatmates["3"]="3";
    $total_flatmates["4"]="4";
    $total_flatmates["5"]="5";
    $total_flatmates["6"]="6";
    $total_flatmates["7+"]="7";
    $config['total_flatmates'] = $total_flatmates;
    
    
    $roomtypes_status=array();                       
    $roomtypes_status["Private"]="1";
    $roomtypes_status["Shared"]="2";
    $config['roomtypes_status'] = $roomtypes_status;
    
 
    
    
    $room_furnishing_features=array();                       
    $room_furnishing_features["Bed side table"]="1";
    $room_furnishing_features["Wardrobe"]="2";
    $room_furnishing_features["Drawers"]="3";
    $room_furnishing_features["Air conditioner"]="4";
    $room_furnishing_features["Heater"]="5";
    $room_furnishing_features["Desk"]="6";
    $room_furnishing_features["Lamp"]="7";
    $room_furnishing_features["Chair"]="8";
    $room_furnishing_features["Couch"]="9";
    $room_furnishing_features["TV"]="10";
    $room_furnishing_features["Balcony"]="11";
    $room_furnishing_features["Door lock"]="12";
    $room_furnishing_features["Kitchenette"]="13";
    $config['room_furnishing_features'] = $room_furnishing_features;
    
    $roomfurnishings_status=array();         
    $roomfurnishings_status["Any"]="1";
    $roomfurnishings_status["Furnished"]="2";
    $roomfurnishings_status["Unfurnished"]="3";
    $config['roomfurnishings_status'] = $roomfurnishings_status;
    
    $bathrooms_status=array();                       
    $bathrooms_status["Shared"]="1";
    $bathrooms_status["Own"]="2";
    $bathrooms_status["Ensuite"]="3";
    $config['bathrooms_status'] = $bathrooms_status;
    
    $bond_status=array(); 
    $bond_status['Choose Bond']=''; 
    $bond_status["None"]="1";
    $bond_status["1 Week"]="2";
    $bond_status["2 Week"]="3";
    $bond_status["3 Week"]="4";
    $bond_status["4 Week"]="5";
    $config['bond_status'] = $bond_status;
    
    
    $bills_status=array();  
    $bills_status['Choose Bills']='';  
    $bills_status["Additional to the rent"]="1";
    $bills_status["Some included in the rent"]="2";
    $bills_status["Included in rent"]="3";
    $config['bills_status'] = $bills_status;
    

    $length_of_stay=array();           
    $length_of_stay['Choose Stay Length']='';
    $length_of_stay["1 Week"]="1";
    $length_of_stay["2 Weeks"]="2";
    $length_of_stay["1 Month"]="3";
    $length_of_stay["2 Months"]="4";
    $length_of_stay["3 Months"]="5";
    $length_of_stay["4 Months"]="6";
    $length_of_stay["6 Months"]="7";
    $length_of_stay["9 Months"]="8";
    $length_of_stay["+12 Months"]="9";
    $config['length_of_stay'] = $length_of_stay;



    $flatmatespref_status=array();                       
    $flatmatespref_status["Anyone"]="1";
    $flatmatespref_status["Females only"]="2";
    $flatmatespref_status["Males only"]="3";
    $flatmatespref_status["Female or male (no couples)"]="4";
    $flatmatespref_status["Couple"]="5";
    $flatmatespref_status["No preference"]="6";
    $config['flatmatespref_status'] = $flatmatespref_status;
 
	if($array_name!=NULL)
		return $config[$array_name];
	elseif($array_name!=NULL && $array_key!=NULL)
		return $config[$array_name][$array_key];
	else
        return false;
    }


 public function SendMail($to, $subject, $htmlContent,$from,$fromName,$cc=NULL,$attachment_file=NULL)
    {
        
    	
	if($attachment_file!=''){	
		
	$tmp_name    = $attachment_file['tmp_name']; // get the temporary file name of the file on the server 
    $file_name        = $attachment_file['name'];  // get the name of the file 
    $size        = $attachment_file['size'];  // get size of the file for size validation 
    $file_type        = $attachment_file['type'];  // get type of the file 
    $error       = $attachment_file['error']; // get the error (if any) 
  $uid     = md5(uniqid(time()));
   
   $file_size = filesize($tmp_name);
$handle    = fopen($tmp_name, "r");
$content   = fread($handle, $file_size);
fclose($handle);

$content = chunk_split(base64_encode($content));


   
$header    = "From: " . $fromName . " <" . $from . ">\n";
if($cc!=NULL)
$header .= 'Cc: '.$cc.'\n';
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\n\n";
$emessage = "--" . $uid . "\n";
$emessage .= "Content-type:text/html; charset=iso-8859-1\n";
$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
$emessage .= $htmlContent . "\n\n";
$emessage .= "--" . $uid . "\n";
$emessage .= "Content-Type: ".$file_type."; name=\"" . $file_name . "\"\n"; // use different content types here
$emessage .= "Content-Transfer-Encoding: base64\n";
$emessage .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"\n\n";
$emessage .= $content . "\n\n";
$emessage .= "--" . $uid . "--";
$mail =mail($to, $subject, $emessage, $header);
	}
	else{
	    $headers = "MIME-Version: 1.0" .PHP_EOL; 
        if($cc!=NULL)
        $headers .= 'Cc: '.$cc.'' .PHP_EOL;
        $headers .= "Content-type:text/html;charset=UTF-8" .PHP_EOL; 
        // Additional headers 
        $headers .= 'From: '.$fromName.'<'.$from.'>' .PHP_EOL; 
	    // Send email 
        $mail = mail($to, $subject, $htmlContent, $headers);
	    
	    
	}	
		
        if($mail)
         return $mail;
         else
         return FALSE;
    }    
}
