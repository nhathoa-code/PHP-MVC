<?php include(ROOT_PATH . "/views/client/header.php") ?>
    <section id="banner">
        <div class="container-fluid">
            <div class="d-none d-md-block">
                <a href="">
                    <img src="<?php echo public_url("client_assets/images/WebBannerPC_NVSM2024.jpg") ?>" alt="">
                </a>       
            </div>
            <div class="d-block d-md-none">
                <a href="">
                    <img src="<?php echo public_url("client_assets/images/WebBannerMobile_NVSM2024.jpg") ?>" alt="">
                </a>       
            </div>            
        </div>
    </section>         
    <section id="product-categories">
        <div class="container-fluid">
            <h1 class="my-4">Danh mục</h1>
            <div class="row row-cols-2 gy-5 row-cols-md-5">
                <div class="col category">
                    <img style="width:100%;height:100%" src="<?php echo public_url("client_assets/images/trang-phuc-nam.png") ?>" alt="">
                    <a href="<?php echo url("collection/Trang-phuc-nam") ?>">Trang phục nam</a>
                </div>
                <div class="col category">
                    <img style="width:100%;height:100%" src="<?php echo public_url("client_assets/images/trang-phuc-nu.png") ?>" alt="">
                    <a href="<?php echo url("collection/Trang-phuc-nu") ?>">Trang phục nữ</a>
                </div>
                <div class="col category">
                    <img style="width:100%;height:100%" src="<?php echo public_url("client_assets/images/trang-phuc-tre-em.png") ?>" alt="">
                    <a href="<?php echo url("collection/Trang-phuc-tre-em") ?>">Trang phục trẻ em</a>
                </div>
                <div class="col category">
                    <img style="width:100%;height:100%" src="<?php echo public_url("client_assets/images/giay.png") ?>" alt="">
                    <a href="<?php echo url("collection/Giay") ?>">Giày</a>
                </div>
                <div class="col category">
                    <img style="width:100%;height:100%" src="<?php echo public_url("client_assets/images/mu.png") ?>" alt="">
                    <a href="<?php echo url("collection/Phu-kien") ?>">Phụ kiện</a>
                </div>
            </div>
        </div>
    </section>

    <section id="latest-products" class="collection-content mt-5">
        <div class="container-fluid">
            <h1 class="mb-4">Sản phẩm mới nhất</h1>
            <div class="row row-cols-2 row-cols-md-4 gy-5 collection">
                <?php foreach($latest_products as $p): ?>
                    <div class="col">
                        <div class="collection-item">
                            <a href="<?php echo url("product/detail/{$p->id}") ?>">
                                <img width="187.5" height="187.5" loading="lazy" src="<?php echo $p->thumbnail ?>" style="display: inline;">
                                <div class="title"><?php echo $p->p_name ?></div>
                                <div class="price"><span class="num"><?php echo number_format($p->p_price,0,"",".") ?></span><span class="currency">đ</span></div>
                            </a>
                            <ul class="colors">
                                <?php if(count($p->colors) <= 3): ?>
                                <?php foreach($p->colors as $color): ?>
                                <li>
                                    <span>
                                        <img src="<?php echo url($color->color_image) ?>" alt="">
                                    </span>
                                </li>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <?php 
                                        $break_index = 0;
                                    ?>
                                    <?php foreach($p->colors as $color): ?>
                                    <li>
                                    <span>
                                        <img src="<?php echo url($color->color_image) ?>" alt="">
                                    </span>
                                    <?php if($break_index === 2): ?>
                                        <span style="color:#666">+<?php echo count($p->colors) - 3; ?></span>
                                    <?php endif; ?>    
                                    </li>
                                    <?php if($break_index === 2){break;} $break_index++; endforeach; ?>
                                <?php endif; ?>    
                            </ul>
                        </div>
                    </div>                           
                <?php endforeach; ?>    
            </div>
        </div>
    </section>
<?php include(ROOT_PATH . "/views/client/footer.php") ?>