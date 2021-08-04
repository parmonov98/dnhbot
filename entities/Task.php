<?php

namespace entities;

/**
 * @Entity
 * @Table(name="tasks")
 */
class Task
{
  /**
   * @Id
   * @GeneratedValue
   * @Column(type="integer")
   */

  private $id;

  /**
   * @Column(type="string")
   */
  private $expiration_date;

  /**
   * @Column(type="string")
   */
  private $domain;


  /**
   * Many tasks belong to one user
   * @ManyToOne(targetEntity="User", inversedBy="tasks")
   * @JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
   * @var \entities\User
   */
  private $author;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Pattern
     */
    public function setExpirationDate($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getExpirationDate()
    {
        return $this->name;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return string
     */
    public function setDomain($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->value;
    }

    /**
     * Set author.
     *
     * @param \entities\User $author
     *
     * @return Pattern
     */
    public function setAuthor(\entities\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \entities\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
