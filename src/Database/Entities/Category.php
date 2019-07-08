<?php

namespace App\Database\Entities;

/**
 * @Entity(repositoryClass="App\Database\Repositories\CategoryRepository")
 * @Table(name="categories")
 */
class Category
{
	/**
	 * @Id @Column(type="integer", unique=true) @GeneratedValue
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
     * @ManyToMany(targetEntity="Article", inversedBy="categories")
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

	public function getArticles(): array
	{
		return $this->articles;
	}
}
