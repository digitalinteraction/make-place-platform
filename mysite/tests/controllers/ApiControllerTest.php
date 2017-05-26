<?php


class MockApiDataObject extends DataObject {
    
}


/** Tests ApiController */
class ApiControllerTest extends SapphireTest {
    
    
    public $usesDatabase = true;
    
    
    /* Test Lifecycle */
    function setUp() {
        parent::setUp();
        
        $this->mockObject = MockApiDataObject::create();
        $this->mockObject->write();
    }
    
    
    /* Parameter Tests */
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
    
    
    
    /* Target Tests */
    public function testFindObject() {
        
        $controller = CommentApiController::create();
        
        $errors = [];
        $target = $controller->findObject("MockApiDataObject", 1, $errors);
        
        $this->assertNotNull($target);
        $this->assertCount(0, $errors);
    }
    
    public function testFindObjectWithInvalidClass() {
        
        $controller = CommentApiController::create();
        
        $errors = [];
        $target = $controller->findObject("null", 999, $errors);
        
        $this->assertCount(1, $errors);
    }
    
    public function testFindObjectWithNonDataObject() {
        
        $controller = CommentApiController::create();
        
        $errors = [];
        $target = $controller->findObject("Object", 999, $errors);
        
        $this->assertCount(1, $errors);
    }
    
    public function testFindObjectNotExisting() {
        
        $controller = CommentApiController::create();
        
        $errors = [];
        $target = $controller->findObject("MockApiDataObject", 999, $errors);
        
        $this->assertCount(1, $errors);
    }
}
