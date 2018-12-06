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

    // if the account exists, you post an error
    if($manager->CheckIfExist($name)) 
    {
        echo "Le compte " . $name . " existe déjà";
    }
    else 
    {
        
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
        // add new account
        $manager->add($account);
        header('location: index.php');

    }
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
            
            // call one account
            $payment = $manager->getAccount($accountId);

            // call the function that gives money
            $money = $payment->addMoney($balance);
            // update
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

            // call one account
            $payment = $manager->getAccount($accountId);
            // call the function that withdraws the money
            $money = $payment->removeMoney($balance);
            // update
            $manager->update($payment);
            header('location: index.php');

        }
    }
}

if(isset($_POST['balance']))
{
    if(isset($_POST['idDebit']))
    {
        if(isset($_POST['idPayment']))
        {
            if(isset($_POST['transfert']))
            {
                $balance = htmlspecialchars($_POST['balance']);
                $accountId = htmlspecialchars($_POST['idDebit']);
                $accountPayment = htmlspecialchars($_POST['idPayment']);
                $transfert = htmlspecialchars($_POST['transfert']);
    
                $actualyAccount = $manager->getAccount($accountId);
                $otherAccount = $manager->getAccount($accountPayment);

                $addMoney = $otherAccount->addMoney($balance);
                $removeMoney = $actualyAccount->removeMoney($balance);

                $manager->update($actualyAccount);
                $manager->update($otherAccount);
                header('location: index.php');
            }
        }
    }
}




$accounts = $manager->getAccounts();


include "../views/indexView.php";
