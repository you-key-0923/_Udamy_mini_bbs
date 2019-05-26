<?php
error_reporting(E_ALL & ~E_NOTICE);

require('dbconnect.php');

$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id = p.member_id ORDER BY p.created DESC');
print_r($posts);

/*
$test = $db->query('SELECT * FROM posts');
print_r($test);
var_dump($test);
*/