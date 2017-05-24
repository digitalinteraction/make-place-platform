<?php

/** Tests ApiController */
class ApiControllerTest extends SapphireTest {
    
    public function testPostVar() {
        
        $controller = ApiController::create();
        
        $errors = [];
        
        $value = $controller->postVar('something', $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
    
    public function testGetVar() {
        
        $controller = ApiController::create();
        
        $errors = [];
        
        $value = $controller->getVar('something', $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
    
    public function testJsonVar() {
        
        $body = json_encode(["SomeKey" => "SomeValue"]);
        $controller = ApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], [], $body));
        
        $value = $controller->jsonVar("SomeKey");
        
        $this->assertEquals("SomeValue", $value);
    }
    
    public function testJsonVarErrors() {
        
        $body = json_encode([]);
        $controller = ApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], [], $body));
        
        $errors = [];
        $value = $controller->jsonVar("something", $errors);
        
        $this->assertNull($value);
        $this->assertEquals(["Please provide 'something'"], $errors);
    }
    
    public function testBodyVarUsesPostAndJson() {
        
        $body = json_encode(["JsonKey" => "JsonValue"]);
        $post = ["PostKey" => "PostValue"];
        
        $controller = ApiController::create();
        $controller->setRequest(new SS_HTTPRequest("POST", "localhost", [], $post, $body));
        
        $jsonValue = $controller->bodyVar("JsonKey");
        $postValue = $controller->bodyVar("PostKey");
        
        $this->assertEquals("JsonValue", $jsonValue);
        $this->assertEquals("PostValue", $postValue);
    }
}
