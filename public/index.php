<?php
require '../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

function getDB() {
	// Create connect to SQLite database in file
	$dbConnection = new PDO('sqlite:../test.db');
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;	
}

// Get all student
$app->get('/listStudent', function($request, $response) {
	try {
		$db = getDB();
		$query = "SELECT * FROM student";
		$sth = $db->query($query);
		$data = $sth->fetchAll(PDO::FETCH_CLASS);

		$response->withStatus(200);
		$response->withHeader('Content-type', 'application/json');

		//echo json_encode($data);
		return $response->withJson($data); 
	} catch(PDOException $e) {
        $response->withStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
	}

	//echo json_encode($data);
});

// Get single student
$app->get('/listStudent/{id}', function($request, $response) {
	try {
		$db = getDB();
		$id = $request->getAttribute('id');
		$query = "SELECT * FROM student WHERE id=$id";
		$sth = $db->query($query);
		$data = $sth->fetchObject();

		$response->withStatus(200);
		$response->withHeader('Content-type', 'application/json');

		//echo json_encode($data);
		return $response->withJson($data);
	} catch(PDOException $e) {
        $response->withStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
});

// Add student
$app->post('/listStudent', function($request, $response) {
	try {
		$db = getDB();
		$id = $request->getParam('id');
		$name = $request->getParam('name');
		$avg_score = $request->getParam('avg_score');

		$query = "INSERT INTO  student VALUES(:id, :name, :avg_score)";

		$stmt = $db->prepare($query);
		
		//Bind parameters to statement variables
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':avg_score', $avg_score);

		// $id = '99';
		// $name = 'John bae';
		// $avg_score = 7;

		$stmt->execute();

		$response->withStatus(200);

		echo json_encode(array("status" => "success", "code" => 1));
		//return $response->withJson($request->getParsedBody());
	} catch(PDOException $e) {
        $response->withStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
});

// Update student
$app->put('/listStudent/{id}', function($request, $response) {
	try {
		$db = getDB();
		$id = $request->getAttribute('id');
		$name = $request->getParam('name');
		$avg_score = $request->getParam('avg_score');

		$query = "UPDATE student SET name = :name, avg_score = :avg_score WHERE id = $id";

		$stmt = $db->prepare($query);
		
		// Bind parameters to statement variables
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':avg_score', $avg_score);

		// $name = 'Thai Nam';
		// $avg_score = 4;

		$stmt->execute();

		$response->withStatus(200);
		echo json_encode(array("status" => "success", "code" => 1));
		//return $response->withJson($request->getParsedBody());
	} catch(PDOException $e) {
        $response->withStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
});

// Delete student
$app->delete('/listStudent/{id}', function($request, $response) {
	try {
		$db = getDB();
		$id = $request->getAttribute('id');

		$query = "DELETE FROM student WHERE id = $id";
		$stmt = $db->prepare($query);
		$stmt->execute();
		
		$data = $stmt->fetchAll();

		$response->withStatus(200);
		echo json_encode(array("status" => "success", "code" => 1));
		return $response->withJson($data); 
	} catch(PDOException $e) {
        $response->withStatus(404);
        echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
});

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();