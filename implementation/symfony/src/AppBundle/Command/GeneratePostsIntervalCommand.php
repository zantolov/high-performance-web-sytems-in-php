<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Easybook\Slugger;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePostsIntervalCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:generate-posts-interval');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $manager = $this->getContainer()->get('doctrine')->getManager();
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $category1 = $manager
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category1']);

        $category2 = $manager
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category2']);

        while (true) {
            $post = $this->createPost();

            if (rand() % 2) {
                $post->setCategory($category1);
            } else {
                $post->setCategory($category2);
            }

            $manager->persist($post);
            $manager->flush();
            $this->refreshCache($post->getCategory()->getTitle());
            $manager->clear();
            $category1 = $manager->merge($category1);
            $category2 = $manager->merge($category2);
            sleep(1);
            gc_collect_cycles();
        }
    }

    private function createPost()
    {
        $faker = Factory::create();
        $slugger = new Slugger();

        $post = new Post();
        $post->setTitle($faker->sentence);
        $slug = $slugger->slugify($post->getTitle());
        $post->setSlug($slug);
        $post->setBody($faker->realText(rand(500, 1200)));

        return $post;
    }

    private function refreshCache($categoryName)
    {
        $data = $this->loadPostsFromDb($categoryName);
        $this->saveToCache($categoryName, $data);
    }

    private function loadPostsFromDb($categoryName, $maxResults = 15)
    {
        /** @var EntityRepository $repo */
        $repo = $this->getContainer()->get('doctrine')->getRepository(Post::class);

        $qb = $repo
            ->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->where('c.title = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->setMaxResults($maxResults)
            ->orderBy('p.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    private function saveToCache($key, $value)
    {
        $data = $this->getContainer()->get('cache.app')->getItem($key);
        $data->set($value);
        $this->getContainer()->get('cache.app')->save($data);
    }


}