<div class="col-md-4">

    <!-- Blog Search Well -->
    <div class="well">
        <h4>Search</h4>
        <form action="">
            <div class="input-group">
                <input required name="search" type="text" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" >
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form>
    </div>

    <div class="well">
        <h4>Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">

                    <?php

                    foreach (\app\models\proxy\CategoryProxy::getAll() as $category) {
                        echo "<li><a href='\?cat_id=".$category->getCatId()."'>".$category->getCatTitle()."</a></li>";

                    }
                    ?>

                </ul>
            </div>
            <!-- /.col-lg-6 -->

        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <?php include "sideWidget.php"; ?>

</div>