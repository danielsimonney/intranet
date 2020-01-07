<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        $testSubject = ["JS", "PHP", "Symfony", "MEAN", "Go", "ReactJS", "Ruby", "Algo", "Swift", "Android", "Integration", "Python"];
        $userList = [];
        $listSubjects=[];


        foreach ($testSubject as $key => $value) {
            $subject = new Subject;
                $subject->setTitle($value);
                $manager->persist($subject);
                $listSubjects[]=$subject;
           }

        for ($u = 0; $u < 8; $u++) {
            $user = new User;
            if ($u == 1 || $u == 2) {
                $role = ["ROLE_ADMIN"];
            } else if ($u == 3 || $u == 4) {
                $role = ["ROLE_PROFESSOR"];
            } else {
                $role = [""];
            }
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail($faker->email)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName)
                ->setPassword($hash)
                ->setRoles($role);

            $userList[] = $user;

            $manager->persist($user);

           
            

            if ($role == [""]) {
                for ($t = 0; $t < mt_rand(1, 3); $t++) {
                    $note = new Note;
                    $note->setUser($user)
                        ->setSubject($listSubjects[array_rand($listSubjects)])
                        ->setNote(rand(0, 20))
                        ->setComment($faker->text)
                        ->setCoeff(mt_rand(1,8));
                    $manager->persist($note);
                }
            }

        }
        foreach ($listSubjects as $sub) {
            $firstUserToAssign = $userList[mt_rand(0,3)];
            $secondUserToAssign = $userList[mt_rand(3,7)];
            
            $sub->addUser($secondUserToAssign);
            $sub->addUser($firstUserToAssign);

        }

        $manager->flush();
    }
}
