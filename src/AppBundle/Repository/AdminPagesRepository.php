<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminPagesRepository extends EntityRepository
{


 public function CountPages()
    {
     return $this->createQueryBuilder('pages')
	        ->select('count(pages.id)')
            ->orderBy('pages.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();
    }

 public function AllPagesMenus()
    {
       $query = $this->createQueryBuilder('pages')
            ->orderBy('pages.entry_time', 'DESC')
            ->getQuery()->getResult();
      return $query;
    }


 public function PageInfo($page_id)
    {
       $query = $this->createQueryBuilder('pages')
	        ->Where('pages.id = :page_id')
			->setParameters(['page_id' => $page_id])				
            ->orderBy('pages.entry_time', 'DESC')
            ->getQuery()->getResult();
      if($query && isset($query[0]))
       return $query[0];
      }
 				
 public function AllPages($currentPage = 1 , $limit)
    {
       $query = $this->createQueryBuilder('pages')
            ->orderBy('pages.entry_time', 'DESC')
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
