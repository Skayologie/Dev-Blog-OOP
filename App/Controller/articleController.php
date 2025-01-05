<?php
namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");

use App\Config\Database;
use App\Modules\Article;
use App\Modules\CRUD;
class articleController{
    public static function AddArticle($title,$content,$meta,$categorieID,$author_id,$tags){
        $db = Database::getConnection();
       if(CRUD::Add($db,'articles', ["title","content","meta_description","category_id","author_id"],
            [$title,$content,$meta,$categorieID,$author_id])){
            $lastID = CRUD::GetLastID("articles","id");
            foreach ($tags as $value) {
                CRUD::Add($db,"article_tags",["article_id","tag_id"],[$lastID,$value]);
            }

       }

    }

    public static function GetArticles()
    {
        $Results = CRUD::GetArticles(0);
        return $Results;
    }

    public static function GetArchivedArticles()
    {
        $Results = CRUD::GetArticles(1);
        return $Results;
    }
    public static function GetPendingArticles()
    {
        $Results = Article::GetPendingArticles();
        return $Results;
    }
    
    public static function AcceptArticle(){
        
    }

}




