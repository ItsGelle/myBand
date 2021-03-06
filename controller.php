<?php

function homepage_action()
{
    global $smarty;
    $articles = get_articles();
    $smarty->assign('articles', $articles);
    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('home.tpl');
    $smarty->display('footer.tpl');
}

function page_not_found_action($smarty)
{
    global $smarty;
    $smarty->display('notfound.tpl');
}

function contact_action() {
    global $smarty;
    //MODEL

    //VIEWS
    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('contact.tpl');
    $smarty->display('footer.tpl');

}


function news_action()
{
    global $smarty;

    //VIEWS
    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('news.tpl');
    $smarty->display('footer.tpl');
}

function events_action() {
    global $smarty;
    global $pageno;
    global $searchterm;
    //MODEL
    $articles = get_some_articles();
    $number_of_pages = get_number_of_pages();
    $smarty->assign('current_page', $pageno);
    $smarty->assign('number_of_pages', $number_of_pages);
    $smarty->assign('articles', $articles);
    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('events.tpl');
    $smarty->display('footer.tpl');
}

function succes_action() {
    global $smarty;

    $smarty->display('headerLogin.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('inlogformulier.tpl');
    $smarty->display('footer.tpl');
}

function login_action() {
    global $smarty;


    $smarty->display('headerLogin.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('inlogformulier.tpl');
    $smarty->display('footer.tpl');
}

function info_action() {
    global $smarty;


    $smarty->display('headerLogin.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('info.tpl');
    $smarty->display('footer.tpl');
}

function signup_action() {
    global $smarty;

    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('signup.tpl');
    $smarty->display('footer.tpl');
}

function logout_action() {
    global $smarty;

    $smarty->display('header.tpl');
    $smarty->display('nav.tpl');
    $smarty->display('logout.tpl');
    $smarty->display('footer.tpl');
}

function login_user_action() {
    login();
}

function register_action() {
    register();
}

function logout_user_action(){
    logout();
}
