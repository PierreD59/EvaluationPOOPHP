<?php

declare(strict_types = 1);

class AccountManager
{

    private $_db;

    /**
     * constructor
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    /**
     * Get the value of _db
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * Set the value of _db
     *
     * @param PDO $db
     * @return  self
     */
    public function setDb(PDO $db)
    {
        $this->_db = $db;

        return $this;
    }




    /**
     * Add account into DB
     *
     * @param Account $account
     */
    public function add(Account $account)
    {
        $query = $this->getDb()->prepare('INSERT INTO accounts(name, balance) VALUES (:name, :balance)');
        $query->bindValue('name', $account->getName(), PDO::PARAM_STR);
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_INT);

        $query->execute();
    }

    /**
     * Get one account by id or name
     *
     * @param $info
     * @return Account 
     */
    public function getAccount($info) 
    {

        $query = $this->getDB()->prepare('SELECT * FROM account WHERE id = :id');
        $query->bindValue('id', $info, PDO::PARAM_INT);
        $query->execute();
        $account = $query->fetch(PDO::FETCH_ASSOC);

        return new Account($account);

    }


    /**
     * List all accounts
     *
     * @return array $arrayOfAccounts
     */
    public function getAccounts() 
    {

        $query = $this->getDb()->query('SELECT * FROM accounts');
        $accounts = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($accounts as $account) {
            $arrayOfAccounts[] = new Account($account);
        }
        return $arrayOfAccounts;

    }

    /**
     * Update account's data 
     *
     * @param Account $account
     */
    public function update(Account $account)
    {
        $query = $this->getDb()->prepare('UPDATE Accounts SET name = :name, balance = :balance WHERE id = :id');

        $query->bindValue('id', $account->getId(), PDO::PARAM_INT);
        $query->bindValue('name', $account->getName(), PDO::PARAM_STR);
        $query->bindValue('balance', $account->getBalance(), PDO::PARAM_INT);

        $query->execute();
    }
    
    public function deleteAccount($account)
    {

        $query = $this->getDb()->prepare('DELETE FROM accounts WHERE id = :id');
        $query->bindValue('id', $account, PDO::PARAM_INT);
        $query->execute();

    }
}
