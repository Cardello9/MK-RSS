<?php

namespace App\Class;

class NewsCategory
{
    private $title;

    private $shortDescription;

    private $fullDescription;

    private $urls;

    public function setTitle(string $title): NewsCategory 
    {
        $this->title = $title;

        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setShortDescription(string $shortDescription): NewsCategory 
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
    
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setFullDescription(string $fullDescription): NewsCategory 
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }
    
    public function getFullDescription(): ?string
    {
        return $this->fullDescription;
    }

    public function addUrl(string $url): NewsCategory
    {
        $this->urls[] = $url;

        return $this;
    }

    public function getUrls(): array 
    {
        return $this->urls;
    }
}