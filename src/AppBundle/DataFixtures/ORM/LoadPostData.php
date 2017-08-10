<?php
/**
 * Created by PhpStorm.
 * User: gabiudrescu
 * Date: 10.08.2017
 * Time: 23:49
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++)
        {
            $post = new Post();

            $post->setTitle('lorem ipsum');
            $post->setBody('bla bla ' . random_int(1,99999999999));
            $manager->persist($post);
        }

        $manager->flush();
    }

}