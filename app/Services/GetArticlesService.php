<?php

namespace App\Services;

use Exception;

class GetArticlesService implements ArticlesServiceInterface
{
public function getArticles():array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    try {
        $json='';
        for($i=1;$i<env('max_page');$i++) {
            curl_setopt($ch, CURLOPT_URL, env('URL_articles').'?per_page=10&page='.$i);
            $res  = curl_exec($ch);
            $json = json_decode($res);
            $arr  = json_decode(json_encode($json), true);

            foreach ($arr as $article) {
                $array_articles[] = [$article['title']['rendered'], $article['content']['rendered'], $article['date']];
            }
        }

    } catch (Exception $e){
        return [];
    }

    return $array_articles;
}
}
