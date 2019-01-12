<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('FR-fr');

        for($i=1;$i<=30;$i++){

            /**
             * Appelle la table Ad
             */
            $ad = new Ad();

            $title        = $faker->sentence();
            $coverImage   = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content      = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            /**
             * Stocke les informations manuellement
             */
            $ad->setTitle($title)
               ->setCoverImage($coverImage)
               ->setIntroduction($introduction)
               ->setContent($content)
               ->setPrice(mt_rand(40,500))
               ->setRooms(mt_rand(1,7));


            for($j = 1;$j <= mt_rand(2,5); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);
            }

            /**
             * Enregistre les données dans $ad
             */
            $manager->persist($ad);
        }

        /**
         * Sauvegarde toutes les données et est prêt à les envoyées
         */
        $manager->flush();
    }
}
