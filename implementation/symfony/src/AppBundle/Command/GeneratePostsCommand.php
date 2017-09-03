<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use Easybook\Slugger;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePostsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:generate-posts');
        $this->addArgument('number', InputArgument::REQUIRED, 'Number of posts to be generated');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '32M');
        $faker = Factory::create();
        $slugger = new Slugger();
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        $category1 = $manager
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category1']);

        $category2 = $manager
            ->getRepository(Category::class)
            ->findOneBy(['title' => 'category2']);

        $max = $input->getArgument('number');


        for ($i = 1; $i <= $max; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence);
            $slug = $slugger->slugify($post->getTitle());
            $post->setSlug($slug);
            $post->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
            $post->setBody($faker->realText(rand(500, 1200)));

            if (rand() % 2) {
                $post->setCategory($category1);
            } else {
                $post->setCategory($category2);
            }

            $manager->persist($post);

            if (($i % 500) == 0 || $i == $max) {
                $output->writeln(sprintf('%s Memory usage (currently) %dKB/ (max) %dKB',
                    $i,
                    round(memory_get_usage(true) / 1024), memory_get_peak_usage(true) / 1024));
                $manager->flush();
                $manager->clear();
                $category1 = $manager->merge($category1);
                $category2 = $manager->merge($category2);
                gc_collect_cycles();
            }
        }

        $manager->flush();
    }

}