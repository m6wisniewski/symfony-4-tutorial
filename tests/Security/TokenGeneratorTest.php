<?php

namespace App\Tests\Security;

use PHPUnit\Framework\TestCase;
use App\Security\TokenGenerator;

class TokenGeneratorTest extends TestCase{
    
    public function testTokenGeneration(){
        $tokenGen = new TokenGenerator();
        $token = $tokenGen->getRandomSecureToken(30);
        //[15] = '*';
        echo $token;
        
        $this->assertEquals(30, strlen($token));
        //$this->assertEquals(1, preg_match("/[A-Za-z0-9]/"), $token);
        $this->assertTrue(ctype_alnum($token), 'Token contains incorrect characters');
    }
}
