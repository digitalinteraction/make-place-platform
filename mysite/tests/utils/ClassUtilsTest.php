<?php



class MockClass extends Object { }

class FirstMockClass extends MockClass { }

class SecondMockClass extends MockClass { }

class AReallyComplexMockClass extends MockClass { }




/** ... */
class ClassUtilsTest extends SapphireTest {
    
    protected $object = null;
    
    public function setUp() {
        parent::setUp();
        
        $this->object = MockClass::create();
    }
    
    public function testSubclasses() {
        
        $classes = ClassUtils::getSubclasses($this->object, 'MockClass');
        
        $this->assertEquals(4, count($classes));
    }
    
    public function testSubclassesWithoutBase() {
        
        $classes = ClassUtils::getSubclasses($this->object, 'MockClass', true);
        
        $this->assertEquals(3, count($classes));
    }
    
    public function testFriendlyNames() {
        
        $classes = ClassUtils::getSubclasses($this->object, 'MockClass');
        
        $this->assertContains("First", $classes);
    }
    
    public function testFriendlyNamesHasSpaces() {
        
        $classes = ClassUtils::getSubclasses($this->object, 'MockClass');
        
        $this->assertContains("A Really Complex", $classes);
    }
}
