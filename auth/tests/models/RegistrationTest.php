<?php

/** Tests Registration */
class RegistrationTest extends SapphireTest {
    
    public function testProvidePermissions() {
        
        // Doesn't really need testing ...
        $perms = Registration::create()->providePermissions();
        $this->assertNotNull($perms);
    }
}
