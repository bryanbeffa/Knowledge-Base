<?php


class DocCase
{
    /**
     * @var title of the case.
     */
    private $title;

    /**
     * @var category id of the case.
     */
    private $category;

    /**
     * @var variant of the case.
     */
    private $variant;

    /**
     * @var description of the case.
     */
    private $description;

    public function __construct($title, $category, $variant, $description)
    {
        $this->title = $title;
        $this->category = $category;
        $this->variant = $variant;
        $this->description = $description;
    }

    /**
     * @return title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return variant
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @return description
     */
    public function getDescription()
    {
        return $this->description;
    }

}