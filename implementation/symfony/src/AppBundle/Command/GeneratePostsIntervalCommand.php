<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
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

        $cacheEngine = $this->getContainer()->get('cache.app');

        while (true) {
            $post = $this->createPost();

            if (rand() % 2) {
                $post->setCategory($category1);
            } else {
                $post->setCategory($category2);
            }

            $manager->persist($post);
            $manager->flush();
            $cacheEngine->deleteItem($post->getCategory()->getTitle());
            $manager->clear();
            $category1 = $manager->merge($category1);
            $category2 = $manager->merge($category2);
            sleep(5);
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

}