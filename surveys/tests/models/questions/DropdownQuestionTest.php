<?php

/** ... */
class DropdownQuestionTest extends SapphireTest {
    
    protected $question = null;
    
    
    public function setUp() {
        parent::setUp();
        
        $this->question = DropdownQuestion::create([
            'Options' => 'A, B, C'
        ]);
    }
    
    public function testExtraFields() {
        
        $this->assertEquals(1, count($this->question->extraFields()));
    }
    
    public function testGetOptionsArray() {
        
        $options = $this->question->getOptionsArray();
        
        $this->assertEquals(3, count($options));
    }
    
    public function testGetRawOptions() {
        
        $this->assertEquals(['A', 'B', 'C'], $this->question->getRawOptions());
    }
    
    public function testRenderResponse() {
        
        $this->question->Options = "Some Long Value, Another Value";
        
        $value = "some-long-value";
        $expected = "Some Long Value";
        
        $result = $this->question->renderResponse($value);
        
        $this->assertEquals($expected, $result);
    }
}
