<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminEnquiriesRepository extends EntityRepository
{

		
 public function CountEnquiries()
    {
     return $this->createQueryBuilder('enquiries')->select('count(enquiries.enquiry_id)')
	                                              ->where('enquiries.is_converted = :is_converted')
											      ->setParameters(['is_converted' => '0'])  
	                                              ->orderBy('enquiries.entry_time', 'DESC')
											      ->getQuery()->getSingleScalarResult();
    }
	
  public function AllEnquiries($currentPage = 1,$limit)
    {
       $query = $this->createQueryBuilder('enquiries')
	                    ->where('enquiries.is_converted = :is_converted')
						->setParameters(['is_converted' => '0'])
						->orderBy('enquiries.entry_time', 'DESC')->getQuery();			
	   $paginator = $this->paginate($query, $currentPage, $limit);
       return $paginator;
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
