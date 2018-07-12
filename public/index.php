<?php
session_start();

require ('../private/Smarty/Smarty.class.php');
require ('../private/model.php');
require ('../private/controller.php');

$smarty = new Smarty();
$smarty->setCompileDir('../private/tmp');
$smarty->setTemplateDir('../private/views');

define('ARTICLES_PER_PAGE', 3);

//TERNARY OPERATOR
$page = isset($_GET['page']) ? $page = $_GET['page'] : $page = 'home';
$pageno = isset($_GET['pageno']) ? $_GET['pageno'] : '1';
$searchterm = isset($_GET['searchterm']) ?  '%' . $_GET['searchterm'] . '%' : '%';

//REGISTER
if (isset($_POST['submit_register'])) {
    register_action();
}

//LOGIN
if (isset($_POST['submit_login'])) {
    login_user_action();
}

//uitloggen
if (isset($_POST['submit_logout'])) {
    logout_user_action();
}

if (isset($_COOKIE['hash'])) {
    $smarty->assign('Login', 'true');
} else {
    $smarty->assign('Login', 'false');
}

if (isset($_GET['logout'])){
    logout_user_action();
}

switch ($page)
{
    case 'info': info_action(); break;
    case 'succes': succes_action(); break;
    case 'login': login_action(); break;
    case 'signup': signup_action(); break;
    case 'logout': logout_action(); break;
    case 'home': homepage_action(); break;
    case 'news': news_action(); break;
    case 'contact': contact_action(); break;
    case 'events': events_action(); break;
    default: page_not_found_action($smarty); break;
}

