<?php
// $id = isset($_GET['id']) ? $_GET['id'] : '';
    $id = $_GET['id'] ?? '';
    $blogDetails = mysqli_query($koneksi, "SELECT categories.name as category_name, blogs.* FROM blogs JOIN categories ON categories.id = blogs.id_category WHERE blogs.id = '$id' ORDER BY blogs.id DESC");
    // All (Data lebih dari satu)
    $rowBlogDetail = mysqli_fetch_assoc($blogDetails);

    $recentBlogs = mysqli_query($koneksi, "SELECT categories.name as category_name, blogs.* FROM blogs JOIN categories ON categories.id = blogs.id_category ORDER BY blogs.id DESC LIMIT 5");
    // All (Data lebih dari satu)
    $rowRecentBlogs = mysqli_fetch_all($recentBlogs, MYSQLI_ASSOC);

    $tagsJson = $rowBlogDetail['tags']; // JSON string
    $tagsArray = json_decode($tagsJson, true); // Decode ke array asosiatif
    // $tagValues = array_column($tagsArray, 'value'); // Ambil hanya kolom 'value'
?>
<?php 
    function FormattedDate($date_blog){
        return date("M d, Y", strtotime($date_blog));
    }
?>

<!-- Page Title -->
<div class="page-title accent-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Blog Details</h1>
        <nav class="breadcrumbs">
            <ol>
                <li><a href="index.html">Home</a></li>
                <li class="current">Blog Details</li>
            </ol>
        </nav>
    </div>
</div><!-- End Page Title -->

<div class="container">
    <div class="row">

        <div class="col-lg-8">

            <!-- Blog Details Section -->
            <section id="blog-details" class="blog-details section">
                <div class="container">

                    <article class="article">
                        <div class="post-img">
                            <img src="admin/uploads/<?= $rowBlogDetail['image']?>" alt="" class="img-fluid"
                                width="100%">
                        </div>

                        <h2 class="title"><?= $rowBlogDetail['title']?>
                        </h2>

                        <div class="meta-top">
                            <ul>
                                <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a
                                        href="blog-details.html"><?= $rowBlogDetail['penulis']?></a></li>
                                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a
                                        href="blog-details.html"><time
                                            datetime="2020-01-01"><?= FormattedDate($rowBlogDetail['created_at'])?></time></a>
                                </li>
                                <!-- <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a
                                        href="blog-details.html">12 Comments</a></li> -->
                            </ul>
                        </div><!-- End meta top -->

                        <div class="content">
                            <?= $rowBlogDetail['content']?>
                        </div><!-- End post content -->

                        <div class="meta-bottom">
                            <i class="bi bi-folder"></i>
                            <ul class="cats">
                                <li><a href="#"><?= $rowBlogDetail['category_name']?></a></li>
                            </ul>
                            <?php
                                $tags = json_decode($rowBlogDetail['tags']);
                            ?>

                            <i class="bi bi-tags"></i>
                            <ul class="tags">
                                <!-- Array Object -->
                                <?php if (!empty($tags) && (is_array($tags) || is_object($tags))): ?>
                                <?php foreach($tags as $tag): ?>
                                <li><a href="#"><?= $tag->value?></a></li>
                                <?php endforeach ?>
                                <?php else: ?>
                                <li><em>Tag tidak tersedia</em></li>
                                <?php endif; ?>
                            </ul>
                        </div><!-- End meta bottom -->

                    </article>

                </div>
            </section><!-- /Blog Details Section -->
        </div>

        <div class="col-lg-4 sidebar">

            <div class="widgets-container">


                <!-- Search Widget -->
                <div class="search-widget widget-item">

                    <h3 class="widget-title">Search</h3>
                    <form action="">
                        <input type="text">
                        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                    </form>

                </div>
                <!--/Search Widget -->

                <!-- Recent Posts Widget -->
                <div class="recent-posts-widget widget-item">

                    <h3 class="widget-title">Recent Posts</h3>

                    <?php foreach ($rowRecentBlogs as $val): ?>
                    <div class="post-item">
                        <h4><a href="?page=blog-detail&id=<?= $val['id']?>"><?= $val['title'] ?></a></h4>
                        <time datetime="2020-01-01"><?= FormattedDate($val['created_at']) ?></time>
                    </div><!-- End recent post item-->
                    <?php endforeach ?>
                </div>
                <!--/Recent Posts Widget -->

                <!-- Tags Widget -->
                <div class="tags-widget widget-item">
                    <?php 
                    ?>

                    <h3 class="widget-title">Tags</h3>
                    <ul>
                        <!-- Assosiatif Array -->
                        <?php foreach($rowRecentBlogs as $tagsRecent):
                            $tags = json_decode($tagsRecent['tags'],true);
                            if (!is_array($tags)) continue; 
                        ?>
                        <?php foreach($tags as $tag): ?>
                        <li><a href="#"><?= $tag['value'] ?></a></li>
                        <?php endforeach ?>
                        <?php endforeach ?>
                    </ul>

                </div>
                <!--/Tags Widget -->

            </div>

        </div>

    </div>
</div>