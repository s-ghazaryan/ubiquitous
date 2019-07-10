<?php

namespace App\Database\Entities;

/**
 * @Entity(repositoryClass="App\Database\Repositories\ArticleRepository")
 * @Table(name="articles")
 */
class Article
{
	const ID = 'id';
	const TITLE = 'title';
	const CONTENT = 'content';

	private $relationshipsGetters = [
		"categories" => "getCategories"
	];

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

	public function getConstants(): array
	{
		$reflection = new \ReflectionClass(self::class);
		return $reflection->getConstants();
	}

	public function getTypeForJsonApi()
	{
		// TODO: Define parent and get this data by EntityManager
		return 'articles';
	}

	public function getRelationshipsGetters(): array
	{
		return $this->relationshipsGetters;
	}

	public function getSelf()
	{
		return self::class;
	}
}
