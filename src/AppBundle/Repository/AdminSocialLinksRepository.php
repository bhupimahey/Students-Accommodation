<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminSocialLinksRepository extends EntityRepository
{


 public function CountLinks()
    {
     return $this->createQueryBuilder('social_links')
	        ->select('count(social_links.id)')
            ->orderBy('social_links.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();
    }

 public function GetSocialLinks()
    {
       $query = $this->createQueryBuilder('social_links')
            ->orderBy('social_links.entry_time', 'DESC')
            ->getQuery()->getResult();
	
      return $query;
    }


				
 public function AllLinks($currentPage = 1 , $limit)
    {
       $query = $this->createQueryBuilder('social_links')
            ->orderBy('social_links.entry_time', 'DESC')
            ->getQuery();
			
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
