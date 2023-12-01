<?php
namespace App\Services;
use Illuminate\Support\Collection;

interface ArticlesServiceInterface
{
    public function getArticles():Collection;
}
