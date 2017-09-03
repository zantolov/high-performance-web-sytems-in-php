<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsPortalController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $category1 = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category1']);

        $category2 = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category2']);

        $category1Posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['category' => $category1], ['createdAt' => 'DESC'], 15);

        $category2Posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['category' => $category2], ['createdAt' => 'DESC'], 15);

        return $this->render('@App/homepage.html.twig', [
            'category1Posts' => $category1Posts,
            'category2Posts' => $category2Posts,
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        phpinfo();
    }
}
