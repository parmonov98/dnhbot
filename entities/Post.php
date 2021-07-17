<?php

namespace entities;

/**
 * @Entity
 * @Table(name="posts")
 */
class Post
{
  /**
   * @Id
   * @GeneratedValue
   * @Column(type="smallint")
   */

  private $id;

  /**
   * @Column(type="string")
   */
  private $title;

  /**
   * @Column(type="text")
   */
  private $text;

  /**
   * @Column(type="datetime")
   */
  private $date;


  /**
   * Many posts belong to one user
   * @ManyToOne(targetEntity="User", inversedBy="posts")
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
   * Set title.
   *
   * @param string $title
   *
   * @return Post
   */
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get title.
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set text.
   *
   * @param string $text
   *
   * @return Post
   */
  public function setText($text)
  {
    $this->text = $text;

    return $this;
  }

  /**
   * Get text.
   *
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set date.
   *
   * @param \DateTime $date
   *
   * @return Post
   */
  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }

  /**
   * Get date.
   *
   * @return \DateTime
   */
  public function getDate()
  {
    return $this->date;
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
   * @return Post
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
   * Set author.
   *
   * @param \entities\User $author
   *
   * @return Post
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
