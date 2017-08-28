<?php

namespace AppBundle\Controller;

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

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        // replace this example code with whatever you need
        return $this->render('@App/homepage.html.twig', [
            'posts' => $posts,
        ]);
    }
}
