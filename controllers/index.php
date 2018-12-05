<?php

// On enregistre notre autoload.
function chargerClasse($classname)
{
    if (file_exists('../models/' . $classname . '.php')) {
        require '../models/' . $classname . '.php';
    } else {
        require '../entities/' . $classname . '.php';
    }
}
spl_autoload_register('chargerClasse');

$array = array('Compte courant', 'PEL', 'Livret A', 'Compte joint');


$db = Database::DB();
$manager = new AccountManager($db);

if(isset($_POST['delete']))
{
    $accountId = $_POST['id'];
    $delete = $manager->deleteAccount($accountId);
    header('location: index.php');
}

if(isset($_POST['name'])) 
{
    $name = htmlspecialchars($_POST['name']);

    $account = new Account([
        "name" => $name,
        "balance" => 80
    ]);
    $manager->add($account);
    header('location: index.php');
}


$accounts = $manager->getAccounts();


include "../views/indexView.php";
