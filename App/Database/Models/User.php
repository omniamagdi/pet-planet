<?php 
namespace App\Database\Models;

use App\Database\Models\Contract\MakeCrud;
use App\Database\Models\Model;

class User extends Model implements MakeCrud {
    private $id,
    $first_name,
    $last_name,
    $email,
    $password,
    $gender,
    $phone,
    $image,
    $status,
    $admin_status,
    $service_provider_status,
    $verification_code,
    $email_verified_at; 
    

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
     * Get the value of first_name
     */ 
    public function getFirst_name()
    {
        return $this->first_name;
    }

    /**
     * Set the value of first_name
     *
     * @return  self
     */ 
    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get the value of last_name
     */ 
    public function getLast_name()
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     *
     * @return  self
     */ 
    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;

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
        $this->password =  password_hash($password,PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * Get the value of gender
     */ 
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @return  self
     */ 
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get the value of phone
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @return  self
     */ 
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of admin_status
     */ 
    public function getAdmin_status()
    {
        return $this->admin_status;
    }

    /**
     * Set the value of admin_status
     *
     * @return  self
     */ 
    public function setAdmin_status($admin_status)
    {
        $this->admin_status = $admin_status;

        return $this;
    }

    /**
     * Get the value of service_provider_status
     */ 
    public function getService_provider_status()
    {
        return $this->service_provider_status;
    }

    /**
     * Set the value of service_provider_status
     *
     * @return  self
     */ 
    public function setService_provider_status($service_provider_status)
    {
        $this->service_provider_status = $service_provider_status;

        return $this;
    }

    /**
     * Get the value of verification_code
     */ 
    public function getVerification_code()
    {
        return $this->verification_code;
    }

    /**
     * Set the value of verification_code
     *
     * @return  self
     */ 
    public function setVerification_code($verification_code)
    {
        $this->verification_code = $verification_code;

        return $this;
    }

    /**
     * Get the value of email_verified_at
     */ 
    public function getEmail_verified_at()
    {
        return $this->email_verified_at;
    }

    /**
     * Set the value of email_verified_at
     *
     * @return  self
     */ 
    public function setEmail_verified_at($email_verified_at)
    {
        $this->email_verified_at = $email_verified_at;

        return $this;
    }


    public function create() :bool
    { 
        $query = "INSERT INTO users (first_name,last_name,
        email,password,gender,phone,verification_code,admin_status,service_provider_status) 
        
        VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? )";
        
        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }
        
        $returned_stmt->bind_param('ssssssiii',$this->first_name,$this->last_name,$this->email,
        $this->password, $this->gender, $this->phone, $this->verification_code,
        $this->admin_status, $this->service_provider_status);

        return $returned_stmt->execute();
    }

    public function read() :\mysqli_result
    {
        # code...
    }

    public function update() :bool
    {
        # code...
    }

    public function delete() :bool
    {
        # code...
    }

    public function codeVerification() 
    {
        $query = "SELECT * FROM users WHERE email = ? AND verification_code = ?";

        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }
        
        $returned_stmt->bind_param('si',$this->email,$this->verification_code);
        $returned_stmt->execute();
        return $returned_stmt->get_result();
    }

    public function verifyUser() :bool
    {
        $query = "UPDATE users SET email_verified_at = ? WHERE email = ?";

        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }
        $returned_stmt->bind_param('ss',$this->email_verified_at,$this->email);
        return $returned_stmt->execute();
    }

    public function getUserInfo()
    {
        $query = "SELECT * FROM users WHERE email = ? ";

        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }

        $returned_stmt->bind_param('s',$this->email);
        $returned_stmt->execute();
        return $returned_stmt->get_result();
    }

    public function updateUserInfo() :bool
    {
        $query = "UPDATE users SET first_name = ?, last_name = ? , gender = ? WHERE email = ?";

        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }

        $returned_stmt->bind_param('ssss', $this->first_name, $this->last_name, $this->gender, $this->email);
        return $returned_stmt->execute();
    }

    

    public function updatePassword() :bool
    {
        $query = "UPDATE users SET password = ? WHERE email = ?";

        $returned_stmt = $this->connect->prepare($query);
        if(! $returned_stmt){
            return false;
        }

        $returned_stmt->bind_param('ss', $this->password, $this->email);
        return $returned_stmt->execute();
    }

}

    ?>