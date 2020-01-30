<?php
namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPropertyRepository extends EntityRepository
{


 public function CountProperty($prop_status=NULL)
    {
	if($prop_status!=NULL){
     return $this->createQueryBuilder('property')
	        ->select('count(property.property_id)')
			->where('property.property_status = :property_status')
			->setParameters(['property_status' => $prop_status])
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult(); 
	}	
	else{ 
	return $this->createQueryBuilder('property')
	        ->select('count(property.property_id)')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();	
	}
			
    }

  public function PropertyInfo($property_id)
    {
     $result =  $this->createQueryBuilder('property')
										->Where('property.property_id = :property_id')
										->setParameters(['property_id' => $property_id])
										->orderBy('property.entry_time', 'DESC')
										->getQuery()->getResult();
	 if( $result &&  isset($result[0])) 									
	   return  $result[0];
										
    }


  public function TitleExists($property_title)
    {
     return $this->createQueryBuilder('property')->select('count(property.property_id)')
										->Where('property.property_title = :property_title')
										->setParameters(['property_title' => $property_title])
										->getQuery()->getSingleScalarResult();
    }
				
 public function AllProperty($currentPage = 1 , $limit,$prop_status=NULL)
    {
	 if($prop_status!=NULL){
	$query = $this->createQueryBuilder('property')
			->where('property.property_status = :property_status')
			->setParameters(['property_status' => $prop_status])
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery();	 
		 
	 }
	 else{
		 
	$query = $this->createQueryBuilder('property')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery();	 
	 }
       
			
	 $paginator = $this->paginate($query, $currentPage, $limit);
	 
	 
      return $paginator;
    }


 public function AllPropertyDropdown()
    {
       return $this->createQueryBuilder('property')
            ->orderBy('property.entry_time', 'DESC')
            ->getQuery()->getResult();
    }

	
 public function paginate($dql, $page = 1, $limit)
  {
    $paginator = new Paginator($dql);

    $paginator->getQuery()
        ->setFirstResult($limit * ($page - 1)) // Offset
        ->setMaxResults($limit); // Limit

    return $paginator;
  }
}
