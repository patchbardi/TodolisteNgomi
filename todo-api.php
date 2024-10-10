<?php

require_once("config.php");
require_once("TodoDB.php");

header("Content-Type: application/json");

// LOG function in PHP
function write_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    fwrite($log, "$timestamp - $action: " . json_encode($data) . "\n");
    fclose($log);
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage() . " in "
              . $e->getFile() . " on line " . $e->getLine());
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        // Get Todo's (READ)
        echo json_encode($todoDB->getTodos());
        // write_log("READ", $todo_items);
        break;
    case "POST":
        // Get data from the input stream.
        $input = file_get_contents('php://input');

        // Decode JSON input data into PHP array.
        $data = json_decode($input, true);

        // Prepare the insert statement.
        $insert_statement = $pdo->prepare(
            "INSERT INTO todos (text, completed) VALUES (:text, :completed)");

        // Execute the insertion.
        $result = $insert_statement->execute(['text' => $data['text'],
                                             'completed' => 0]);
        // Tell the client the success of the operation.
        echo json_encode(['status' => 'success']);

        write_log("CREATE", $data);
        break;
    case "PUT":
        // Change Todo (UPDATE)

        // Get data from the input stream.
        $input = file_get_contents('php://input');

        // Decode JSON input data into PHP array.
        $data = json_decode($input, true);

        // Prepare the insert statement.
        $complete_statement = $pdo->prepare("UPDATE todos SET completed=1 WHERE id=:myid");

        // Execute the deletion.
        $result = $complete_statement->execute(["myid" => $data["id"]]);

        // Tell the client the success of the operation.
        echo json_encode(['status' => 'success']);

        write_log("PUT", $data);
        break;
    case "DELETE":
        // Remove Todo (DELETE)

        // Get the data from the php input stream.
        $data = json_decode(file_get_contents('php://input'), true);

        // Prepare the insert statement.
        $delete_statement = $pdo->prepare("DELETE FROM todos WHERE id=:myid");

        // Execute the deletion.
        $result = $delete_statement->execute(["myid" => $data["id"]]);

        if ($result === true) {
            // Tell the client the success of the operation.
            echo json_encode(['status' => 'success']);
            write_log("DELETE", $data);
        } else {
            // or the failure
            echo json_encode(['status' => 'failure']);
            write_log("DELETE FAILED", $data);
        }
        break;
}

