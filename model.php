<?php


function make_connection()
{
    $mysqli = new mysqli('localhost', '25158_root', '25158_root', '25158_db');
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


    //Register

function register() {
    $mysqli = make_connection();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    if($password != $repeat_password) {
        header('Location: index.php?page=login');
        exit();
    }

    //MA ACCOUNT

    $posistion = strpos($email, '@ma-web.nl');
    if(!$posistion) {
        header('Location: index.php?page=login');
    }

    //bestaat deze username al?

    $query = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute() or die ('Error querying USERNAME');
    $row = $stmt->fetch();
    if ($row) {
        echo 'Sorry, maar deze gebruikersnaam is al in gebruik...';
        echo 'Klik <a href="index.php?page=register.php">hier</a> om terug te keren';

        exit();
    }

    //Bestaat dit mailadres al?

    $query = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $email);
    $result = $stmt->execute() or die ('Error querying EMAIL');
    $row = $stmt->fetch();
    if ($row) {
        echo 'Sorry, maar dit mailadres is al in gebruik...';
        echo 'Klik <a href="index.php?page=register.php">hier</a> om terug te keren';
        exit();
    }

    //Gebruiker toevoegen aan de database
    $query = "INSERT INTO users VALUE (0,?,?,?,?,0)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss', $username,$email,$hash,$password);
    $random_number = rand(0, 1000000);
    $hash = hash('sha512', $random_number);
    $password = hash('sha512', $password);
    $result = $stmt->execute() or die ('Error insterting user');

    //Gebruiker mailen

    $to = $email;
    $subject = 'Verifieer je account bij myBand!';
    $message = 'Klik op de onderstaande link om je account te activeren: ';
    $message .= 'http://25158.hosts.ma-cloud.nl/bewijzenmap/periode1.4/proj/myBand/public/index.php?email=' . $email . '&hash=' .$hash;
    $headers = 'From: jelle.buurman@live.nl';
    mail($to,$subject,$message,$headers) or die ('Error mailing.');

    $query = "SELECT user_id FROM users WHERE email = ? AND hash = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss',$email,$hash);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $row = $stmt->fetch();
    if(!$user_id) {
        echo 'Sorry, maar deze combinatie van mailadres en hash ken ik niet';
        exit();
    }
    $stmt->close();

    //Account activeren
    $query = "UPDATE users SET active = 1 WHERE user_id = ?";
    $stmt = $mysqli->prepare($query) or die ('Error updating');
    $stmt->bind_param('i',$user_id);
    $stmt->execute() or die ('Error executing');
    echo 'Je account is geactiveerd!';
    echo 'Klik <a href="index.php?page=register.php">hier</a> om in te loggen';
}

//Inloggen

function login() {
    $mysqli = make_connection();


    $query = "SELECT user_id, hash, active FROM users WHERE username = ? AND password = ?";
    $stmt = $mysqli->prepare($query) or die ('Error preparing');
    $username = $_POST['username'];
    $password = hash('sha512',$_POST['password']);
    $stmt->bind_param('ss', $username, $password) or die ('Error binding params');
    $stmt->execute() or die ('Error executing');
    $stmt->bind_result($user_id, $hash, $active)  or die ('Error binding results');
//    echo "Login execute" . var_dump($stmt);

    $fetch_succes = $stmt->fetch();
    if(!$fetch_succes) {
        echo 'Sorry, er is iets misgegaan ';
        echo 'Klik <a href="index.php?page=login">hier</a> om het nog eens te proberen.';
        exit();
    } else if($active == 0) {
        echo 'Sorry, je account is nog niet geactiveerd. Check je mailbox ';
        echo 'Klik <a href="index.php?page=login">hier</a> om het nog eens te proberen.';
        exit();
    }

    setcookie('user_id', $user_id, time() + 3600 * 24 * 7);
    setcookie('hash', $hash, time() + 3600 * 24 * 7);
    header('Location: index.php?page=home');

    if(!isset($_COOKIE['user_id'])) {
        header('Location: index.php?page=login');
    }

    $query = "SELECT user_id FROM users WHERE user_id = ? AND hash = ?";
    $stmt = $mysqli->prepare($query) or die ('Error preparing');
    $stmt->bind_param('is',$user_id, $hash) or die ('Error binding params');
    $stmt->execute() or die ('Error executing');
    $user_id = $_COOKIE['user_id'];
    $hash = $_COOKIE['hash'];

    if (!$fetch_succes) {
        header('Location: index.php?page=contact');
    }

}

function logout() {
    session_start();

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()- 3600);
    }

    $_SESSION = array();
    session_destroy();

    if(isset($_COOKIE['user_id'])) {
        setcookie('user_id', '', time() - 3600);
        setcookie('hash', '', time() - 3600);
    }
    header('Location: index.php?page=news');
}

