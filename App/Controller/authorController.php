<?php
namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");

use App\Config\Database;
use App\Modules\Article;
use App\Modules\Author;
use App\Modules\CRUD;
class authorController{
    // public static function AddArticle($title,$content,$meta,$categorieID,$author_id,$tags)
    // {
    //     $db = Database::getConnection();
    //    if(CRUD::Add($db,'articles', ["title","content","meta_description","category_id","author_id"],
    //         [$title,$content,$meta,$categorieID,$author_id])){
    //         $lastID = CRUD::GetLastID("articles","id");
    //         foreach ($tags as $value) {
    //             CRUD::Add($db,"article_tags",["article_id","tag_id"],[$lastID,$value]);
    //         }

    //    }

    // }

    public static function GetOwnArticles($id)
    {
        $Results = Author::GetOwnArticles($id,0,'published');
        return $Results;
    }

    public static function GetOwnArchivedArticles()
    {
        $Results = CRUD::GetArticles(1,0);
        return $Results;
    }

    public static function GetOwnPendingArticles()
    {
        $Results = Article::GetArticleStatus('pending');
        return $Results;
    }
    
    public static function GetOwnPublishedArticle(){
        $Results = Article::GetArticleStatus('published');
        return $Results;
    }

    public static function GetOwnRejectedArticle(){
        $Results = Article::GetArticleStatus('rejected');
        return $Results;
    }
    
}




