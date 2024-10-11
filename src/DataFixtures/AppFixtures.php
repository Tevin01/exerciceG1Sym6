<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
# on va récupérer notre entité user
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        # Instanciation d'un User
        $user = new User();
        $user->setUserName('admin');
        $user->setUserMail('admin@gmail.com');
        $user->setRoles(['ROLE_ADMIN','ROLE_REDAC','ROLE_MODERATOR']);
        $user->setPassword(password_hash('admin', PASSWORD_DEFAULT));
        $user->setUserActive(true);
        $user->setUserRealName('The Admin!');

        # Utilisation du $manager pour mettre le 
        # user en mémoire
        $manager->persist($user);

        # envoie à la base de données (commit)
        $manager->flush();
    }
}
