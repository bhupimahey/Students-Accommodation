<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminBlogRepository extends EntityRepository
{


 public function CountBlog()
    {
     return $this->createQueryBuilder('blog')
	        ->select('count(blog.id)')
            ->orderBy('blog.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();
    }

 public function blog_category()
    {
       return $this->createQueryBuilder('blog_category')
            ->orderBy('blog_category.entry_time', 'DESC')
            ->getQuery()->getResult();
	
    }
				
 public function AllBlog($currentPage = 1 , $limit)
    {
       $query = $this->createQueryBuilder('blog')
            ->orderBy('blog.entry_time', 'DESC')
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
