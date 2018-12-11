<?php

declare(strict_types = 1);

class Account
{
    protected $id;
    protected $name;
    protected $balance;



    /**
     * constructor
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->hydrate($array);
    }

    /**
     * Hydratation
     *
     * @param array $donnees
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut.
            $method = 'set' . ucfirst($key);
                
            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }


    /* GETTER */

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

                
    /**
     * Get the value of balance
     */
    public function getBalance()
    {
        return $this->balance;
    }
        
    
    /* SETTER */

    /**
     * Set the value of id
     *
     * @param int $id
     * @return  self
     */
    public function setId($id)
    {
        $id = (int)$id;

        if ($id > 0) {
            $this->id = $id;
        }

        return $this;
    }


    /**
     * Set the value of name
     *
     * @param string $name
     * @return  self
     */
    public function setName(string $name)
    {
        if (is_string($name)) {
            $this->name = $name;
        }
        return $this;
    }


    /**
     * Set the value of balance
     *
     * @param int $balance
     * @return  self
     */
    public function setBalance($balance)
    {
        $balance = (int)$balance;
        $this->balance = $balance;
        return $this;
    }

    /**
     * function that credits
     *
     * @param int $balance
     * @return  self
     */
    public function addMoney($balance)
    {
        $balance = (int)$balance;
        $balance = $this->getBalance() + $balance;

        return $this->setBalance($balance);
    }

    /**
     * function that debits
     *
     * @param int $balance
     * @return  self
     */
    public function removeMoney($balance)
    {
        $balance = (int)$balance;
        $balance = $this->getBalance() - $balance;

        return $this->setBalance($balance);
    }
}
