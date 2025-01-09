<?php
namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");

use App\Config\Database;
use App\Modules\Article;
use App\Modules\CRUD;
use App\Modules\Tag;

class articleController{
    public static function AddArticle($title,$content,$meta,$slug,$cover,$categorieID,$tags)
    {
        $author_id = $_SESSION["UserID"];
        $db = Database::getConnection();
       if(CRUD::Add($db,'articles', ["title","content","slug","featured_image","meta_description","category_id","author_id"],
            [$title,$content,$slug,$cover,$meta,$categorieID,$author_id])){
            $lastID = CRUD::GetLastID("articles","id");
            foreach ($tags as $value) {
                CRUD::Add($db,"article_tags",["article_id","tag_id"],[$lastID,$value]);
            }
       }
    }
    // public static function UpdateArticle($id,$title,$content,$meta,$slug,$cover,$categorieID,$author_id,$tags)
    public static function UpdateArticle($id,$title,$content,$slug,$meta,$categorieID,$author_id,$cover,$tags)
    {
        $countTags = count($tags);
        $data = [
            "title"=>$title,
            "content"=> $content,
            "slug"=> $slug,
            "featured_image"=> $cover,
            "meta_description"=> $meta,
            "category_id"=> $categorieID,
            "author_id"=> $author_id,
        ];
        
        $result = CRUD::Edit($id,"articles",$data);
        $execTags = 0 ;
        if($result){
            foreach ($tags as $value) {
                Tag::HardDeleteTag($id,intval($value));
                Tag::addArticle_Tag($id,intval($value));
                $execTags++;
            }
            if ($execTags === $countTags) {
                header("Location:./myArticles.php");
                exit;
            }
        }
        
    }

    public static function GetArticles()
    {
        $Results = CRUD::GetArticles(0,0);
        return $Results;
    }
    public static function GetArticleByID($id)
    {
        $Results = CRUD::GetById("articles","id",$id);
        return $Results;
    }
    public static function GetArchivedArticles()
    {
        $Results = CRUD::GetArticles(1,0);
        return $Results;
    }

    public static function GetPendingArticles()
    {
        $Results = Article::GetArticleStatus('pending');
        return $Results;
    }
    
    public static function GetPublishedArticle(){
        $Results = Article::GetArticleStatus('published');
        return $Results;
    }

    public static function GetRejectedArticle(){
        $Results = Article::GetArticleStatus('rejected');
        return $Results;
    }
    
    public static function create_slug($string) {
        // Replace non letter or digits by -
        $string = preg_replace('~[^\pL\d]+~u', '-', $string);
    
        // Transliterate
        if (function_exists('iconv')) {
            $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
        }
    
        // Remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);
    
        // Trim
        $string = trim($string, '-');
    
        // Remove duplicate -
        $string = preg_replace('~-+~', '-', $string);
    
        // Lowercase
        $string = strtolower($string);
    
        // If string is empty, return 'n-a'
        if (empty($string)) {
            return 'n-a';
        }
    
        return $string;
    }

    
}


