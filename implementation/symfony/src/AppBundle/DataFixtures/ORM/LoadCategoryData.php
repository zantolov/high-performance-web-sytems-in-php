<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Easybook\Slugger;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $slugger = new Slugger();

        for ($i = 1; $i < 3; $i++) {
            $category = new Category();
            $category->setTitle('category' . $i);
            $slug = $slugger->slugify($category->getTitle());
            $category->setSlug($slug);
            $category->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));
            $manager->persist($category);

            $this->setReference('c' . $i, $category);
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
        return 10;
    }
}