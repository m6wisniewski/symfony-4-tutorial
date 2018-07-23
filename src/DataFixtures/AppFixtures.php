<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture{
    
    /**
     *
     * @var UserPAsswordEncoderInterface
     */
    private $passwordEncoder;
    
    const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@example.com',
            'password' => 'john12345',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@example.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'marry_jane',
            'email' => 'mary_jane@example.com',
            'password' => 'mary12345',
            'fullName' => 'Mary Jane',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@example.com',
            'password' => 'admin12345',
            'fullName' => 'SUPER ADMIN',
            'roles' => [User::ROLE_ADMIN],
        ],
    ];
    
    const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today',
        'Did you watch the game yesterday?',
        'How was your day?',
    ];
    
    const LANGUAGES = [
        'en', 'fr'
    ];
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager){
        
        $this->loadUsers($manager);  
        $this->loadMicroPosts($manager);
    }
    
    private function loadMicroPosts(ObjectManager $manager){
        for($i = 0; $i < 30; $i++){      
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(self::USERS[rand(0, count(self::USERS) - 1)]['username']));
            $manager->persist($microPost);
        }  
        $manager->flush();
    }
    
    private function loadUsers(ObjectManager $manager){
        foreach (self::USERS as $userData){
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);
            $this->addReference($userData['username'], $user);
            
            $preferences = new UserPreferences();
            $preferences->setLocale(self::LANGUAGES[rand(0,1)]);

            $user->setPreferences($preferences);
            $manager->persist($user);
            $manager->flush();
        }  
    }
}
