<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Easybook\Slugger;
use Faker\Factory;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $slugger = new Slugger();

        $max = 100;

        for ($i = 1; $i <= $max; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence);
            $slug = $slugger->slugify($post->getTitle());
            $post->setSlug($slug);
            $post->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
            $post->setBody($faker->realText(rand(500, 1200)));
            $post->setCategory($this->getReference('c' . (rand(1, 2))));
            $manager->persist($post);

            if (($i % 50) == 0 || $i == $max) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}