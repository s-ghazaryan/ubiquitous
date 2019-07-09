<?php

namespace App\Database\Entities;

/**
 * @Entity
 * @Table(name="articles")
 */
class Article
{
	/**
	 * @Id @Column(type="integer", unique=true) @GeneratedValue(strategy="IDENTITY")
	 * @SequenceGenerator(sequenceName="id")
	 * @var int
	 */
	private $id;

	/**
	 * @Column(type="string")
	 * @var string|null
	 */
	private $title;

	/**
	 * @Column(type="string")
	 * @var string|null
	 */
	private $content;

	/**
     * Many Articles have Many Categories.
     * @ManyToMany(targetEntity="Category", inversedBy="articles")
     * @JoinTable(name="articles_categories")
     */
	private $categories;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(?string $title): void
	{
		$this->title = $title;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(?string $content): void
	{
		$this->content = $content;
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function addCategory(Category $category): void
	{
		if ($this->categories->contains($category)){
	        // Do nothing if its already part of our collection
	        return;
    	}

		$this->categories->add($category);
		$category->addArticle($this);
	}
}
