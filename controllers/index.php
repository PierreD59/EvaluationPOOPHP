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
// groups account names in a table
$array = array('Compte courant', 'PEL', 'Livret A', 'Compte joint');

// call database
$db = Database::DB();
$manager = new AccountManager($db);

// Delete account
if(isset($_POST['delete']))
{
    $accountId = $_POST['id'];
    $delete = $manager->deleteAccount($accountId);
    header('location: index.php');
}
// Add new account 

if(isset($_POST['name'])) 
{
    $name = htmlspecialchars($_POST['name']);
    // if the name of the account is different from current account, then it has 0 euros
    if($name !== "Compte courant") 
    {
        $account = new Account([
            "name" => $name,
            "balance" => 0
        ]);
    }
    // else the account wins 80 euros
    else {
        $account = new Account([
            "name" => $name,
            "balance" => 80
        ]);
    }
    $manager->add($account);
    header('location: index.php');
}

// add money to the account

if(isset($_POST['payment']))
{
    if(isset($_POST['id']))
    {
        if(isset($_POST['balance'])) {
            $getPayment = htmlspecialchars($_POST['payment']);
            $accountId = htmlspecialchars($_POST['id']);
            $balance = htmlspecialchars($_POST['balance']);
            
            $payment = $manager->getAccount($accountId);

            $money = $payment->addMoney($balance);
            $manager->update($payment);
            header('location: index.php');
        }    
    }
}

// allows you to withdraw money from the account

if (isset($_POST['debit'])) {
    if (isset($_POST['id'])) {
        if (isset($_POST['balance'])) {
            $getPayment = htmlspecialchars($_POST['debit']);
            $accountId = htmlspecialchars($_POST['id']);
            $balance = htmlspecialchars($_POST['balance']);

            $payment = $manager->getAccount($accountId);

            $money = $payment->removeMoney($balance);
            $manager->update($payment);
            header('location: index.php');

        }
    }
}

$accounts = $manager->getAccounts();


include "../views/indexView.php";
