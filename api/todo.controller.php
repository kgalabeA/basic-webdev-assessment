<?php
require_once("todo.class.php");

class TodoController
{
    private const PATH = __DIR__ . "/todo.json";
    private array $todos = [];

    public function __construct()
    {
        $content = file_get_contents(self::PATH);
        if ($content === false) {
            throw new Exception(self::PATH . " does not exist");
        }
        $dataArray = json_decode($content);
        if (!json_last_error()) {
            foreach ($dataArray as $data) {
                if (isset($data->id) && isset($data->title))
                    $this->todos[] = new Todo($data->id, $data->title, $data->description, $data->done);
            }
        }
    }

    public function loadAll(): array
    {
        return $this->todos;
    }

    public function load(string $id): Todo | bool
    {
        foreach ($this->todos as $todo) {
            if ($todo->id == $id) {
                return $todo;
            }
        }
        return false;
    }

    public function create(Todo $todo): bool
    {
        $this->todos[] = $todo;
        $this->saveTodosToFile();
        return true;
    }

    public function update(string $id, Todo $todo): bool
    {
        foreach ($this->todos as $key => $existingTodo) {
            if ($existingTodo->id == $id) {
                $this->todos[$key] = $todo;
                $this->saveTodosToFile();
                return true;
            }
        }
        return false;
    }

    public function delete(string $id): bool
    {
        foreach ($this->todos as $key => $todo) {
            if ($todo->id == $id) {
                return true;
            }
        }
        return false;
    }

    // add any additional functions you need below
    private function saveTodosToFile(): void
    {
        file_put_contents(self::PATH, json_encode($this->todos, JSON_PRETTY_PRINT));
    }
}
