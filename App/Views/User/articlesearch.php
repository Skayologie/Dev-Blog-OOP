<?php
require realpath(__DIR__."/../../../vendor/autoload.php");

use App\Modules\article;

$keyword = $_GET["keyword"];
$results = article::articleSearch($keyword);
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
