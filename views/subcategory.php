<?php
require_once __DIR__ . '/../vendor/autoload.php';

use app\models\proxy\CategoryProxy;
use app\models\proxy\SubCategoryProxy;
use app\views\components\subcategory\SubcategoryBuilder;

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            if(isset($_GET['cat_id'])){
                $categoryProxy = CategoryProxy::getById($_GET['cat_id']);

                echo '<h1 class="page-header">
                '.$categoryProxy->getCatTitle().'

                    </h1>';

                foreach (SubCategoryProxy::getAllWhere(['cat_id'=> $categoryProxy->getCatId(),'sub_category_status'=>'0']) as $subcategory){
                    $subcategoryView = SubcategoryBuilder::buildPublicVeiw($subcategory->getSubCategoryId());
                    $subcategoryView->includeTitle()->render();
                }
            }

            ?>

        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php" ?>

    </div>
    <!-- /.row -->