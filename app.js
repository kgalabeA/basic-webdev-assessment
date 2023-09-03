function putTodo(todo) {
  fetch(window.location.href + "api/todo/" + todo.id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(todo),
  })
    .then((response) => {
      if (response.ok) {
        console.log("TODO was updated successfully");
      } else {
        console.error("Failed to update TODO");
      }
    })
    .catch((error) => console.error("Error:", error));
  console.log("calling putTodo");
  console.log(todo);
}

function postTodo(todo) {
  fetch(window.location.href + "api/todo", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(todo),
  })
    .then((response) => {
      if (response.ok) {
        console.log("TODO was created successfully");
      } else {
        console.error("Failed to create TODO");
      }
    })
    .catch((error) => console.error("Error:", error));
  console.log("calling postTodo");
  console.log(todo);
}

function deleteTodo(todo) {
  fetch(window.location.href + "api/todo/" + todo.id, {
    method: "DELETE",
  })
    .then((response) => {
      if (response.ok) {
        console.log("TODO deleted successfully");
      } else {
        console.error("Failed to delete TODO");
      }
    })
    .catch((error) => console.error("Error:", error));
  console.log("calling deleteTodo");
  console.log(todo);
}

// example using the FETCH API to do a GET request
function getTodos() {
  fetch(window.location.href + "api/todo")
    .then((response) => response.json())
    .then((json) => drawTodos(json))
    .catch((error) => showToastMessage("Failed to retrieve todos..."));
}

getTodos();
