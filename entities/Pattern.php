<?php

namespace entities;

/**
 * @Entity
 * @Table(name="patterns")
 */
class Pattern
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
  private $name;

  /**
   * @Column(type="string")
   */
  private $value;

  /**
   * @Column(type="string")
   */
  private $type;


  /**
   * Many templates belong to one user
   * @ManyToOne(targetEntity="User", inversedBy="templates")
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
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return Pattern
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Pattern
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
