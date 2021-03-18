<?php
namespace App\Data;

use App\Entity\Category;

class Search {

    /**
     * @var string
     */
    public $search;

    /**
     * @var Category[]
     */
    public $categories = [];
}