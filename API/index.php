<?php
require_once "global.php";

$data = null;
$token = $_GET["Token"]; //TODO: Implement tokens/security
$id = $_GET["Id"];

if(empty($id) || $id == "" || !is_numeric($id))
{
    echo "Invalid id";
    exit();
}

switch($_GET["Object"])
{
    case "User":
        $data = new User($Database, $id);
        break;
    case "Field":
        $data = new Field($Database, $id);
        break;
    case "Seed":
        $data = new Seed($Database, $id);
        break;
    case "Product":
        $data = new Product($Database, $id);
        break;
}

header('Content-type: application/json');

echo json_encode(
    array(
        "token" => $token,
        "data" => $data
    )
);