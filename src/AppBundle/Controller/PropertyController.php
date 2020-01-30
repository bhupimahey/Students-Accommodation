<?php
// src/AppBundle/Controller/PropertyController.php
namespace AppBundle\Controller;
use AppBundle\Entity\AdminProperty; 
use AppBundle\Entity\AdminPropertyImages;
use AppBundle\Entity\AdminPropertyRooms;
use AppBundle\Service\CommonConfig;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PropertyController extends Controller
{
    /**
    
     * @Route("/property/{id}" ,name="property_info")
    */
    public function propertyinfoindexAction($id,CommonConfig $CommonConfig)
    {  
       $type_of_property          = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('type_of_property'));
       $property_bedrooms         = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('total_bedrooms'));
       $property_bathrooms        = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('total_bathrooms'));
       $property_parking          = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('parking_status'));
       $property_internet         = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('internet_status'));
       $total_flatmates           = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('total_flatmates'));
       $bond_status               = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('bond_status'));
       $bills_status              = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('bills_status'));
       $room_type                 = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('roomtypes_status'));
       $room_furnishings          = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('roomfurnishings_status'));
       $room_bathroom             = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('bathrooms_status'));
       $room_furnishings_feature  = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('room_furnishing_features'));
       $flatmatespref_status      = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('flatmatespref_status'));
       $length_of_stay            = $CommonConfig->ArrayReverse($CommonConfig->PropertyConfig('length_of_stay')); 
       $property_info             = $this->getDoctrine()->getRepository(AdminProperty::class)->PropertyInfo($id);
       $property_images           = $this->getDoctrine()->getRepository('AppBundle:AdminPropertyImages')->AllPropertyImages($id);
       $property_rooms            = $this->getDoctrine()->getRepository('AppBundle:AdminPropertyRooms')->AllPropertyRooms($id);
       if($property_info){
       if($property_info->getRoomFurnishingsFeatures())
        $room_furnishings_feature_val = explode(',',$property_info->getRoomFurnishingsFeatures());
       else
        $room_furnishings_feature_val='';
        return $this->render('default/property_info.html.twig',['property_info'=>$property_info,'type_of_property'=>$type_of_property,
                             'total_bedrooms'=>$property_bedrooms,'total_bathrooms'=>$property_bathrooms,'parking_status'=>$property_parking,
                             'internet_status'=>$property_internet,'total_flatmates'=>$total_flatmates,'bond_status'=>$bond_status,'bills_status'=>$bills_status,
                             'roomtypes_status'=>$room_type,'roomfurnishings_status'=>$room_furnishings,'bathrooms_status'=> $room_bathroom,
                             'room_furnishing_features'=>$room_furnishings_feature,'flatmatespref_status'=>$room_furnishings_feature,
                             'flatmatespref_status'=>$flatmatespref_status,'length_of_stay'=>$length_of_stay,
                             'room_furnishings_feature_val'=>$room_furnishings_feature_val,'property_images'=>$property_images,
                             'property_rooms'=>$property_rooms]);
	}
	else
	return $this->redirectToRoute('home_page');	
    }
    
	
}