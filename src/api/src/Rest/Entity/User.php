<?php

namespace Rest\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class User
{
    private $id;
    private $name;
    private $address;
    private $postal_code;
    private $city;
    private $phone;
    private $email;
    private $created_at;
    private $updated_at;

    private static $editFields = [
        'name', 'address', 'postal_code', 'city', 'phone', 'email'
    ];

    public static $searchFields = [
        'email' => 'partial',
        'postal_code' => 'exact'
    ];

    public function fillFromRequest(Request $request)
    {
        foreach (self::$editFields as $field) {
            if (property_exists($this, $field)) {
                $this->{$field} = (htmlspecialchars($request->get($field)));
            }
        }
    }

    public static function getUpdatedFieldsFromRequest(Request $request)
    {
        $user = [];
        foreach (self::$editFields as $field) {
            $value = $request->get($field, false);
            if ($value === false) {
                continue;
            }
            $user[$field] = htmlspecialchars($value);
        }

        return $user;
    }

    public static function loadValidatorMetadata(ClassMetaData $metaData) {
        $metaData->addPropertyConstraint('name', new NotBlank());
        $metaData->addPropertyConstraint('address', new NotBlank());
        $metaData->addPropertyConstraint('postal_code', new Length(array('min' => 5, 'max' => 5)));
        $metaData->addPropertyConstraint('city', new NotBlank());
        $metaData->addPropertyConstraint('email', new NotBlank());
        $metaData->addPropertyConstraint('email', new Email());
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param mixed $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

}