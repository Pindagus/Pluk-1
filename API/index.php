<?php
require_once "global.php";

header('Content-type: application/json');

echo json_encode(new User($Database, 1));