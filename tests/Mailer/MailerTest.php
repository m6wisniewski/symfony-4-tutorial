<?php

namespace App\Tests\Mailer;

use App\Entity\User;
use App\Mailer\Mailer;

use PHPUnit\Framework\TestCase;
class MailerTest extends TestCase{
    
    public function testConfirmationEmail(){
        $user = new User();
        $user->setEmail('example@example.com');
        
        $swiftMailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $swiftMailer->expects($this->once())
                ->method('send')
                ->with($this->callback(function ($subject){
                    $messageStr = (string)$subject;
                   // dump($messageStr);
                    return strpos($messageStr, "From: test@example.com") !== false
                        && strpos($messageStr, "Content-Type: text/html; charset=utf-8") !== false
                        && strpos($messageStr, "Subject: Welcome to the micro-post app!") !== false
                        && strpos($messageStr, "To: example@example.com") !== false
                        && strpos($messageStr, "This is a message body") !== false;
                }));
        
        $twigMock = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twigMock->expects($this->once())
                ->method('render')
                ->with('email/registration.html.twig', [
                    'user' => $user
                    ]
                )
                ->willReturn('This is a message body');
        $mailer = new Mailer($swiftMailer, $twigMock, 'test@example.com');
        $mailer->sendConfirmationEmail($user);
    }   
}
