<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsPortalController extends Controller
{
    private function saveToCache($key, $value)
    {
        $data = $this->get('cache.app')->getItem($key);
        $data->set($value);
        $this->get('cache.app')->save($data);
    }

    private function loadFromCache($key)
    {
        $cacheEngine = $this->get('cache.app');

        $cacheData = $cacheEngine->getItem($key);
        if (!$cacheData->isHit()) {
            return false;
        }

        return $cacheData->get();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $category1Posts = $this->loadPosts('category1');
        $category2Posts = $this->loadPosts('category2');

        return $this->render('@App/homepage.html.twig', [
            'category1Posts' => $category1Posts,
            'category2Posts' => $category2Posts,
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        phpinfo();
    }

    private function loadPostsFromDb($categoryName, $maxResults = 15)
    {
        /** @var EntityRepository $repo */
        $repo = $this->getDoctrine()->getRepository(Post::class);

        $qb = $repo
            ->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->where('c.title = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->setMaxResults($maxResults)
            ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function loadPosts($categoryName, $maxResults = 15)
    {
        $cachedData = $this->loadFromCache($categoryName);
        if (false === $cachedData) {
            $cachedData = $this->loadPostsFromDb($categoryName, $maxResults);
            $this->saveToCache($categoryName, $cachedData);
        }

        return $cachedData;

    }
}
