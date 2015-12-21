<?php
namespace SilexSkeleton\Entity;

/**
 * Represents a basic message
 * 
 * @author Lennart Rosam - <hello@takuto.de>
 * 
 * @Table(name="message")
 * @Entity()
 *
 */
class Message {
    
    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(name="id", type="integer")
     */
    private $id;
    
    
    /**
     * @Column(name="message", type="string", nullable=false, length=255)
     */
    private $message;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
