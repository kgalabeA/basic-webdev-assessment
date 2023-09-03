<?php
try {
    require_once("todo.controller.php");
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = explode( '/', $uri);
    $requestType = $_SERVER['REQUEST_METHOD'];
    $body = file_get_contents('php://input');
    $pathCount = count($path);

    $controller = new TodoController();
    
    switch($requestType) {
        case 'GET':
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];
                $todo = $controller->load($id);
                if ($todo) {
                    http_response_code(200);
                    die(json_encode($todo));
                }
                http_response_code(404);
                die();
            } else {
                http_response_code(200);
                die(json_encode($controller->loadAll()));
            }
            break;
        case 'POST':
            $requestData = json_decode($body);
            if ($requestData && isset($requestData->title)) {
                $newTodo = new Todo(
                    uniqid(),
                    $requestData->title,
                    isset($requestData->description) ? $requestData->description : '',
                    isset($requestData->done) ? $requestData->done : false
                );
                if ($controller->create($newTodo)) {
                    http_response_code(201);
                    die(json_encode(['message' => 'Todo was created successfully']));
                } else {
                    http_response_code(500);
                    die(json_encode(['message' => 'Failed to create todo']));
                }
            } else {
                http_response_code(400); 
                die(json_encode(['message' => 'Invalid data request']));
            }
            break;
        case 'PUT':
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];
                $todoToUpdate = $controller->load($id);
                if ($todoToUpdate) {
                    $requestData = json_decode($body);
                    if ($requestData && isset($requestData->title)) {
                        $todoToUpdate->setTitle($requestData->title);
                        $todoToUpdate->setDescription(isset($requestData->description) ? $requestData->description : '');
                        $todoToUpdate->setDone(isset($requestData->done) ? $requestData->done : false);

                        if ($controller->update($id, $todoToUpdate)) {
                            http_response_code(200); 
                            die(json_encode(['message' => 'Todo was updated successfully']));
                        } else {
                            http_response_code(500);
                            die(json_encode(['message' => 'Failed to update todo']));
                        }
                    } else {
                        http_response_code(400); 
                        die(json_encode(['message' => 'Invalid data request']));
                    }
                } else {
                    http_response_code(404);
                    die(json_encode(['message' => 'Todo was not found']));
                }
            } else {
                http_response_code(400); 
                die(json_encode(['message' => 'Invalid data request']));
            }
            break;
        case 'DELETE':
            if ($path[$pathCount - 2] == 'todo' && isset($path[$pathCount - 1]) && strlen($path[$pathCount - 1])) {
                $id = $path[$pathCount - 1];
                $todoToDelete = $controller->load($id);
                if ($todoToDelete) {
                    if ($controller->delete($id)) {
                        http_response_code(204); 
                        die();
                    } else {
                        http_response_code(500);
                        die(json_encode(['message' => 'Failed to delete todo']));
                    }
                } else {
                    http_response_code(404); 
                    die(json_encode(['message' => 'Todo was not found']));
                }
            } else {
                http_response_code(400);
                die(json_encode(['message' => 'Invalid data request']));
            }
            break;
        default:
            http_response_code(501);
            die();
            break;
    }
} catch(Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die();
}
