<?php
    require realpath(__DIR__."/../../../vendor/autoload.php");

use App\Controller\articleController;
use App\Modules\article;
    function nicetime($date){
        if(empty($date)) {
            return "No date provided";
        }
        
        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");
        
        $now             = time();
        $unix_date       = strtotime($date);
        
        // check validity of date
        if(empty($unix_date)) {    
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {    
            $difference     = $now - $unix_date;
            $tense         = "ago";
            
        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }
        
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
        
        if($difference != 1) {
            $periods[$j].= "s";
        }
        
        return "$difference $periods[$j] {$tense}";
    }
    
    $keyword = $_POST["SearchInput"];
    if (!empty($keyword)) {
        $results = article::articleSearch($keyword);
    }elseif(empty($keyword)){
        $results = articleController::GetPublishedArticle();
    }
?>

<?php foreach ($results as $key => $ArticleRow): ?>
    <div class="card" style="width: 18rem;">
        <div style="position :absolute; background-color:white;color:black;padding-inline:15px;padding-block:2px;border-radius:50px;margin:10px;">
            <i class="fa-solid fa-eye"></i>
            <?=$ArticleRow["views"]?>
        </div>
        <div style="width:100%;height:200px;">
            <img style="height: 100%; width:100%;object-fit:cover;" class="card-img-top " src="./../../../public/img/covers/referenceCover.jpg" alt="Card image cap">
            <!-- <img style="height: 100%; width:100%;object-fit:cover;" class="card-img-top " src="./../../../public/img/covers/reference<?=$ArticleRow["featured_image"]?>" alt="Card image cap"> -->
        </div>
        <div class="card-body">
            <p class="card-title " style="font-size: 13px;">Posted By <strong><?= $ArticleRow["username"] ?> <?= nicetime( $ArticleRow["created_at"])?></strong> </p>
            <strong>
                <a href="./view.php?id=<?=$ArticleRow["ArticleId"]?>" >    
                    <h5 class="card-title"><?= $ArticleRow["title"] ?></h5>
                </a>
            </strong>    
            <p style='text-overflow: ellipsis;overflow: hidden; white-space: nowrap;' class="card-text"><?= $ArticleRow["content"] ?></p>
        </div>
    </div>
<?php endforeach; ?>
