<?php

namespace RCSOAuth\Entity;

use Doctrine\ORM\Mapping as ORM,
    \Doctrine\Common\Collections\ArrayCollection;
    
/**
 * Category table entity.
 * @ORM\Entity
 * @ORM\Table(name="rcsoauth_tokens")
 * @property string $value
 * @property string $provider
 * @property date $date
 * @property int $id
 */
class Token extends \RCSBase\Doctrine\Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * @ORM\Column(type="string")
     */
    protected $provider;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    public function __set($property, $value){
        $this->$property = $value;
    }

    public function __get($property){
        return $this->$property;
    }

    public function __construct(){
        parent::__construct();
        $this->date = new \DateTime();
    }

}
