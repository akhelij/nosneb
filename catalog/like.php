<?php
$data = print_r(file_get_contents('Likes.txt'), true) . "\r\n" .print_r(file_get_contents('php://input'), true);
file_put_contents("Likes.txt", $data);