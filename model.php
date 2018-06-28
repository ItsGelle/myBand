<?php


function make_connection()
{
    $mysqli = new mysqli('localhost', 'root', '', 'myband_db');
    if ($mysqli->connect_errno) {
        die('Connection error: ' . $mysqli->connect_errno . '<br>');
    }
    return $mysqli;
}


function get_articles() {
    $mysqli = make_connection();
    $query = "SELECT title FROM articles";
    $stmt = $mysqli->prepare($query) or die ('Error preparing 1.');
    $stmt->bind_result($title) or die ('Error binding results 1.');
    $stmt->execute() or die ('Error executing 1.');
    $results = array();
    while ($stmt->fetch()) {
        $results[] = $title;
    }
    return $results;
}


function get_some_articles() {
    global $pageno, $searchterm;
    $mysqli = make_connection();
    $number_of_pages = calculate_pages() or die ('Error calculating.');
    $firstrow = ($pageno - 1) * ARTICLES_PER_PAGE;
    $per_page = ARTICLES_PER_PAGE;

    $query =    "SELECT title, content ";
    $query .=   "FROM articles ";
    $query .=   "WHERE title LIKE ?  OR ";
    $query .=   "content LIKE ? ";
    $query .=   "ORDER BY article_id ";
    $query .=   "DESC LIMIT $firstrow, $per_page";

    $stmt = $mysqli->prepare($query) or die ('Error preparing 1.');
    $stmt->bind_param('ss', $searchterm, $searchterm) or die ('Error binding searchterm');
    $stmt->bind_result($title, $content) or die ('Error binding results 1.');
    $stmt->execute() or die ('Error executing 1.');
    $results = array();
    while ($stmt->fetch()) {
        $article = array();
        $article[] = $title;
        $article[] = $content;
        $results[] = $article;
    }

    return $results;
}


function get_number_of_pages() {
    $number_of_pages = calculate_pages() or die ('Error calculating.');
    return $number_of_pages;
}


function calculate_pages() {
    $mysqli = make_connection();
    $query = "SELECT * FROM articles";
    $result = $mysqli->query($query) or die ('Error querying 2.');
    $rows = $result->num_rows;
    //echo 'Rows: ' . $rows;
    $number_of_pages = ceil($rows / ARTICLES_PER_PAGE);
    return $number_of_pages;
}

function check_login() {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == 'admin' && $password == 'admin') {
        $_SESSION['loggedin'] = 'loggedin';
    }
}

//INLOGGEN ALS ADMIN

function admin_login() {
    if(!isset($_POST['submit_admin_login'])) {
        return 'no_post';
        exit();
    }

    if(empty($_POST['username']) OR empty ($_POST['password'])) {
        return 'no_info';
        exit();
    }

    $mysqli = make_connection();
    $query = "SELECT admin_id FROM admin WHERE admin_name = ? AND admin_password = ?";
    $stmt = $mysqli->prepare($query) or die ('Error preparing 2');
    $stmt->bind_param('ss',$username, $password) or die ('Error binding results');
    $stmt->bind_result($admin_id) or die ('Error binding results 3');
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt->execute() or die ('Error executing');
    $fetch_success = $stmt->fetch();

    if(!$fetch_success) {
        return 'no_fetch';
        exit();
    }

    $_SESSION['loggedin'] = 'loggedin';
    setcookie('admin_id', $admin_id, time() + 3600);
    return 'cms';

}

function admin_cookie() {
    if (!isset($_COOKIE['admin_id'])) {
        admin_action();
        exit();
    }
}