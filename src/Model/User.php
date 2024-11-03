<?php 

namespace App\Model;

class User {

    private ?int $id;

    public function __construct(?int $id, private string $username, private string $email, private string $password,  private array $roles) {
        $this->id = $id;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRoles()
    {
        $roles = [];

        if (!in_array("user", $this->roles)) {
            $roles[] = "user";
            for ($i = 0; $i < count($this->roles); $i++) {
                $roles[] = $this->roles[$i];
            }
        }
        return $this->roles;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public static function generateFixtures() {
        $users = [];
        $users[] = new User(null,"admin", "admin@admin.com", '$2y$10$2FTPY42EFZKsdEjsDk2EcOXdCpQG1GSTfz48PtedoOt/wAT7DpeXK', ["admin"]);
        $users[] = new User(null, "user", "user@user.com", '$2y$10$emK8Ts37VtuFOtjBEu0PBu9jnnxSmXCrDf9VvM4UO8ClAVWBg3ykG', ["user"]);
        return $users;
    }

}
