<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $staff = new User();
        $staff->setName("Administrator");
        $staff->setEmail("uadmin@gmail.com");
        $staff->setGender("male");
        $staff->setPhoneno("0713245678");
        $staff->setTitle('administrator');
        $staff->setDoB("1992-10-12");
        $staff->setDoM("2020-05-10");
        $staff->setPassword('$argon2id$v=19$m=65536,t=4,p=1$L253MHFwQXQ5WC5ObHYvSA$NTh9TvR5AW9fUnPmlxNJjDdv3pwcaGkFNW5BCRBE8uA');
        $staff->setDeptcom("administrator");
        $staff->setDepartment(null);
        $staff->setNationality("Tanzanian");
        $staff->setReligion("none");
        $staff->setMaritalstatus("single");
        $staff->setRoles(['ROLE_ADMIN']);
        $manager->persist($staff);
        $manager->flush();
    }
}
