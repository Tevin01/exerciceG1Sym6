<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
# on va hasher les mots de passe
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
# Chargement de Faker et création d'un alias nommé Faker
use Faker\Factory;
# on va récupérer notre entité user
use App\Entity\User;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        # Instanciation d'un User
        $user = new User();
        $user->setUserName('admin');
        $user->setUserMail('admin@gmail.com');
        $user->setRoles(['ROLE_ADMIN','ROLE_REDAC','ROLE_MODERATOR']);
        # hachage du mot de passe
        $pwdHash = $this->passwordHasher->hashPassword($user, 'admin');
        # insertion du mot de passe
        $user->setPassword($pwdHash);
        $user->setUserActive(true);
        $user->setUserRealName('The Admin!');

        # Utilisation du $manager pour mettre le 
        # user en mémoire
        $manager->persist($user);

        ###
        # Instanciation de 5 Rédacteurs
        #
        for($i =1; $i <= 5; $i++){
            $user = new User();
            $user->setUserName('redac'.$i);
            $user->setUserMail('redac'.$i.'@gmail.com');
            $user->setRoles(['ROLE_REDAC']);
            $pwdHash = $this->passwordHasher->hashPassword($user, 'redac'.$i);
            $user->setPassword($pwdHash);
            $user->setUserActive(true);
            $user->setUserRealName('The Redac'.$i.'!');
            # Utilisation du $manager pour mettre le 
            # user en mémoire
            $manager->persist($user);
        }        

        // instanciation de Faker en français
        $faker = Faker::create('fr_FR');

        ###
        # Instanciation entre 20 et 40 User sans rôles
        # en utilisant Faker
        #
        $hasard = mt_rand(20,40);
        for($i = 1; $i <= $hasard; $i++){
            $user = new User();
            # nom d'utilisateur au hasard commençant par user-1234
            $username = $faker->numerify('user-###');
            $user->setUsername($username);
            #création d'un mail au hasard
            $mail = $faker->email();
            $user->setUserMail($mail);
            $user->setURoles([ROLE_USER]);
            #transformation du mot de passe 
            #(pour tester)
            $pwdHash = $this->passwordHasher->hashPassword($user, $username);
            $user->setPassword($pwdHash);
              # on va activer 1 user sur 3
              $randActive = mt_rand(0,2);
              $user->setUserActive($randActive);
              $realname = $faker->name();
              $user->setUserRealName($realname);
              $manager->persist($user);
          }

        # envoie à la base de données (commit)
        $manager->flush();
    }
}
