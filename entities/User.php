<?php

namespace entities;

/**
 * @Entity 
 * @Table(
 *  options={"collate":"utf8mb4_unicode_ci", "charset":"utf8mb4", "engine":"InnoDB"}, 
 *  name="users"
 * )  
 */
class User
{
  /**
   * @Id
   * @GeneratedValue
   * @Column(type="bigint")
   * 
   */
  private $id;
  /**
   * @Column(type="bigint", unique=true)
   */
  private $telegram_id;


  /**
   * @Column(type="string", nullable=true, options={"charset":"utf8mb4", "collation": "utf8mb4_unicode_ci"})
   */
  private $first_name;

  /**
   * @Column(type="string", nullable=true, options={"charset":"utf8mb4", "collation": "utf8mb4_unicode_ci"})
   */
  private $last_name;

  /**
   * @Column(type="string", options={"default": "user"})
   */
  private $type;

  /**
   * @Column(type="string", options={"default": "active"})
   */
  private $status;

  /**
   * @Column(type="string", options={"default": "uz"})
   */
  private $lang;


  /**
   * One author can write many pattern
   * @OneToMany(targetEntity="Pattern", mappedBy="author", cascade={"all"})
   * @var Doctrine\Common\Collection\ArrayCollection
   */
  private $patterns;


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
   * Set firstName.
   *
   * @param string $firstName
   *
   * @return User
   */
  public function setFirstName($firstName)
  {
    $this->first_name = $firstName;

    return $this;
  }

  /**
   * Get firstName.
   *
   * @return string
   */
  public function getFirstName()
  {
    return $this->first_name;
  }

  /**
   * Set lastName.
   *
   * @param string $lastName
   *
   * @return User
   */
  public function setLastName($lastName)
  {
    $this->last_name = $lastName;

    return $this;
  }

  /**
   * Get lastName.
   *
   * @return string
   */
  public function getLastName()
  {
    return $this->last_name;
  }

  /**
   * Set author.
   *
   * @param \entities\User $author
   *
   * @return User
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
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
  }

  /**
   * Add post.
   *
   * @param \entities\Post $post
   *
   * @return User
   */
  public function addPost(\entities\Post $post)
  {
    $this->posts[] = $post;

    return $this;
  }

  /**
   * Remove post.
   *
   * @param \entities\Post $post
   *
   * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
   */
  public function removePost(\entities\Post $post)
  {
    return $this->posts->removeElement($post);
  }

  /**
   * Get posts.
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getPosts()
  {
    return $this->posts;
  }

  /**
   * Set telegramId.
   *
   * @param string $telegramId
   *
   * @return User
   */
  public function setTelegramId($telegramId)
  {
    $this->telegram_id = $telegramId;

    return $this;
  }

  /**
   * Get telegramId.
   *
   * @return string
   */
  public function getTelegramId()
  {
    return $this->telegram_id;
  }

  /**
   * Set type.
   *
   * @param string|null $type
   *
   * @return User
   */
  public function setType($type = null)
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Get type.
   *
   * @return string|null
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set status.
   *
   * @param string $status
   *
   * @return User
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status.
   *
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }

    /**
     * Set lang.
     *
     * @param string $lang
     *
     * @return User
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
}
