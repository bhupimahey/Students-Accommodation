<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdminBlogCategoryRepository extends EntityRepository
{


 public function CountBlogCategory()
    {
     return $this->createQueryBuilder('blog_category')
	        ->select('count(blog_category.category_id)')
            ->orderBy('blog_category.entry_time', 'DESC')
            ->getQuery()->getSingleScalarResult();
    }

 public function BlogCategoryDropdown()
    {
       $query = $this->createQueryBuilder('blog_category')
            ->orderBy('blog_category.entry_time', 'DESC')
            ->getQuery()->getResults();
			
	 $paginator = $this->paginate($query, $currentPage);
      return $paginator;
    }
					
 public function AllBlogCategory($currentPage = 1, $limit)
    {
       $query = $this->createQueryBuilder('blog_category')
            ->orderBy('blog_category.entry_time', 'DESC')
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
