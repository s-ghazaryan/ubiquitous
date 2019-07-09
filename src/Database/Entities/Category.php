<?php

namespace App\Database\Entities;

/**
 * @Entity(repositoryClass="App\Database\Repositories\CategoryRepository")
 * @Table(name="categories")
 */
class Category
{
	/**
	 * @Id @Column(type="integer") @GeneratedValue(strategy="IDENTITY")
	 * @SequenceGenerator(sequenceName="id")
	 * @var int
	 */
	private $id;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $name;

	/**
     * Many Categories have Many Articles.
     * @ManyToMany(targetEntity="Article", mappedBy="categories")
     */
	private $articles;

	public function getId(): ?int
	{
		return $id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getArticles()
	{
		return $this->articles;
	}

	public function addArticle(Article $article): void
	{
		if ($this->articles->contains($article)){
	        // Do nothing if its already part of our collection
	        return;
    	}

		$this->articles->add($article);
		$article->addCategory($this);
	}
}
