<?php

/**
 * Application data
 */
class My_Model
{
    /**
     * Get text for the home page
     *
     * @return string
     */
    public function getFrontPageArticle()
    {
        return file_get_contents(__DIR__ . '/../../../README.md');
    }
}