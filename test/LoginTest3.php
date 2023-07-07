<?php
use PHPUnit\Framework\TestCase;

class LoginTest3 extends TestCase
{
    public function testRedirectToLoginWhenNotLoggedIn()
    {
       
        $_SESSION = [];

       
        ob_start();
        include 'index.php';
        $output = ob_get_clean();

        
        $this->assertStringContainsString('Location: login.php', xdebug_get_headers()['Location']);
    }
}

?>