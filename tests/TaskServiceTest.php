<?php

use PHPUnit\Framework\TestCase;
use App\Models\Task;

class TaskServiceTest extends TestCase {
    
    protected static $db;
    protected static $taskService = null;

    public static function setUpBeforeClass() {        
        self::$taskService = new App\Services\TaskService('ut');
        self::$db = new PDO("mysql:dbname=test_qst;host=127.0.0.1", "root", "123456");
    }
    
    public static function tearDownAfterClass() {
        self::$db = null;
    }
    
    protected function tearDown() {
        $this->createMethod(); 
        $this->updateMethod();
        $this->deleteMethod();
    }
    
    private function createMethod() {
        $query = self::$db->prepare("SELECT COUNT(*) FROM tasks");
        $query->execute();
        
        $rows = $query->fetchColumn();
        
        if($rows > 2) {
            self::$db->query("DELETE FROM tasks WHERE id_task NOT IN (1, 2)");
        }
    }
    
    private function updateMethod() {
        $query = self::$db->prepare("SELECT subject, description FROM tasks WHERE id_task = 1");
        $query->execute();
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        
        if(($data['subject'] != 'Unit Test') || ($data['description'] != 'Test for Unit Test')) {
            $query = self::$db->prepare("UPDATE tasks SET subject = 'Unit Test', "
                    . "description = 'Test for Unit Test' WHERE id_task = 1");
            $query->execute();
        }
    }
    
    private function deleteMethod() {
        $sql = "INSERT INTO tasks VALUES (2, 'Task Delete', 'Task for Delete', '2017-09-07 00:00:00', 3, 2)";
        $query = self::$db->prepare($sql);
        $query->execute();
    }
    
    /**
     * @dataProvider getTaskProvider
     */
    public function testGetTask($expectException, $id, $subject = null, $description = null) {
        $task = self::$taskService->getTask($id);
        
        if (!$expectException) {
            $this->assertEquals($subject, $task->subject);
            $this->assertEquals($description, $task->description);
        } else {
            $this->assertEmpty($task);
        }
    }

    public function getTaskProvider() {
        return [
            //expectExc,    id,     subject,    description
            [false,         1,      'Test',     'Test description'],
            [true,          null],
            [true,          'invalid'],
            [true,          9999999]
        ];
    }
    
    /**
     * @dataProvider createTaskProvider
    */
    public function testCreateTask($expectException, $subject, $description) {
        $taskData = new Task([
            "subject" => "Test",
            "description" => "Test description",
            "created_at" => '2017-09-07 00:00:00',
            "department" => 'HR',
            "tasktype" => 'High',
            "assign" => 1
        ]);
        
        if ($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $task = self::$taskService->createTask($taskData);
            
            $this->assertEquals($subject, $task->subject);
            $this->assertEquals($description, $task->description);
        } catch (PDOException $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        } 

    }
    
    public function createTaskProvider() {
        return [
            //expectExc,                      subject,      description
            [false,                           'Test',       'Test description'],
            ["'description' cannot be null",  'Test',       null],
            ["'subject' cannot be null",       null,        'Test description'],
            ["'subject' cannot be null",       null,        null]
        ];
    }
    
    /**
     * @dataProvider updateTaskProvider
    */
    public function testUpdateTask($expectException, $subject, $description) {
        $taskData = new Task([
            "subject" => "Unit Test",
            "description" => "Test for Unit Test",
            "created_at" => '2017-09-07 00:00:00',
            "department" => 'Testing',
            "tasktype" => 'Minor',
            "id_task" => 1
        ]);
        
        if($expectException) {
            $this->expectException(PDOException::class);
        }
        
        try {
            $task = self::$taskService->updateTask($taskData);
            $query = self::$db->prepare("SELECT subject, description FROM tasks WHERE subject = ? AND description = ?");
            $query->bindParam(1, $task->subject, \PDO::PARAM_STR);
            $query->bindParam(2, $task->description, \PDO::PARAM_STR);
            $query->execute();
            
            $result = $query->fetch(\PDO::FETCH_ASSOC);
            
            $this->assertEquals($subject, $result["subject"]);
            $this->assertEquals($description, $result["description"]);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
        
    }
    
    public function updateTaskProvider() {
        return [
            //expectExc,                      subject,             description
            [false,                           'Unit Test',         'Test for Unit Test'],
            ["'description' cannot be null",  'Unit Test Test',     null],
            ["'subject' cannot be null",       null,               'Not Existed Test for Unit Test'],
            ["'subject' cannot be null",       null,                null]
        ];
    }
    
    /**
     * @dataProvider deleteTaskProvider
    */
    public function testDeleteTask($expectException, $data) {
        if($expectException) {
            $this->expectException(TypeError::class);
        }
        
        try {
            $task = self::$taskService->deleteTask($data);
            $this->assertEquals($data, $task);
        } catch (Exception $ex) {
            $this->assertContains($expectException, $ex->getMessage());
            throw $ex;
        }
    }
    
    public function deleteTaskProvider() {
        $taskData = new Task([
            "subject" => "Unit Test",
            "description" => "Test for Unit Test",
            "created_at" => '2017-09-07 00:00:00',
            "department" => 'Testing',
            "tasktype" => 'Minor',
            "id_task" => 1
        ]);
        
        return [
            //expectExc,                                       data
            [false,                                            $taskData],
            ['does not match expected type "integer"',         2],
            ['does not match expected type "string"',          'hello'],
            ['does not match expected type "NULL"',            null]
        ];
    }
    
}
