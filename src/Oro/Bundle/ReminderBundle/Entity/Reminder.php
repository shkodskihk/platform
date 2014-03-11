<?php

namespace Oro\Bundle\ReminderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\NotificationBundle\Entity\RecipientList;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * Reminder
 *
 * @ORM\Table(name="oro_reminder", indexes={
 *     @ORM\Index(name="reminder_is_sent_idx", columns={"is_sent"})
 * })
 * @ORM\Entity(repositoryClass="Oro\Bundle\ReminderBundle\Entity\Repository\ReminderRepository")
 * @Oro\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *  defaultValues={
 *      "entity"={
 *          "icon"="icon-bell-o"
 *      },
 *      "ownership"={
 *          "owner_type"="USER",
 *          "owner_field_name"="recipient",
 *          "owner_column_name"="recipient_id"
 *      },
 *      "security"={
 *          "type"="ACL"
 *      },
 *      "dataaudit"={"auditable"=true}
 *  }
 * )
 */
class Reminder
{
    const STATE_SENT = 'sent';
    const STATE_NOT_SENT = 'not_sent';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime", nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_at", type="datetime", nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $expireAt;

    /**
     * @var string $method
     *
     * @ORM\Column(name="method", type="string", length=255, nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $method;

    /**
     * @var integer $intervalNumber
     *
     * @ORM\Column(name="interval_number", type="integer", nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $intervalNumber;

    /**
     * @var integer $intervalNumber
     *
     * @ORM\Column(name="interval_number", type="string", length=1, nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $intervalUnit;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="text", nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $state;

    /**
     * @var integer $relatedEntityId
     *
     * @ORM\Column(name="related_entity_id", type="integer", nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $relatedEntityId;

    /**
     * @var integer $relatedEntityClassName
     *
     * @ORM\Column(name="related_entity_classname", type="string", length=255, nullable=false)
     * @Oro\Versioned
     * @ConfigField(
     *  defaultValues={
     *      "dataaudit"={"auditable"=true}
     *  }
     * )
     */
    protected $relatedEntityClassName;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $recipient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    protected $sentAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sent", type="boolean")
     */
    protected $isSent = false;

    /**
     * @var string
     */
    protected $uri;

    public function __construct()
    {
        $this->setState(self::STATE_NOT_SENT);
    }

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
     * Set subject
     *
     * @param string $subject
     * @return Reminder
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set start DateTime
     *
     * @param \DateTime $startAt
     * @return Reminder
     */
    public function setStartAt(\DateTime $startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get start DateTime
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set expiration DateTime
     *
     * @param \DateTime $expireAt
     * @return Reminder
     */
    public function setExpireAt(\DateTime $expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    /**
     * Get expiration DateTime
     *
     * @return \DateTime
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * Set method
     *
     * @param string $method
     * @return Reminder
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set interval number
     *
     * @param integer $number
     * @return Reminder
     */
    public function setIntervalNumber($number)
    {
        $this->intervalNumber = $number;

        return $this;
    }

    /**
     * Get interval number
     *
     * @return integer
     */
    public function getIntervalNumber()
    {
        return $this->intervalNumber;
    }

    /**
     * Set interval number
     *
     * @param string $string
     * @return Reminder
     */
    public function setIntervalUnit($string)
    {
        $this->intervalUnit = $string;

        return $this;
    }

    /**
     * Get interval unit
     *
     * @return integer
     */
    public function getIntervalUnit()
    {
        return $this->intervalUnit;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Reminder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set relatedEntityId
     *
     * @param integer $relatedEntityId
     * @return Reminder
     */
    public function setRelatedEntityId($relatedEntityId)
    {
        $this->relatedEntityId = $relatedEntityId;

        return $this;
    }

    /**
     * Get relatedEntityId
     *
     * @return integer
     */
    public function getRelatedEntityId()
    {
        return $this->relatedEntityId;
    }

    /**
     * Set relatedEntityClassName
     *
     * @param string $relatedEntityClassName
     * @return Reminder
     */
    public function setRelatedEntityClassName($relatedEntityClassName)
    {
        $this->relatedEntityClassName = $relatedEntityClassName;

        return $this;
    }

    /**
     * Get relatedEntityClassName
     *
     * @return string
     */
    public function getRelatedEntityClassName()
    {
        return $this->relatedEntityClassName;
    }

    /**
     * Set recipient
     *
     * @param User $recipient
     * @return Reminder
     */
    public function setRecipient(User $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Reminder
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Reminder
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return Reminder
     */
    public function setSentAt(\DateTime $sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->subject;
    }
}
