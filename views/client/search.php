<?php include(ROOT_PATH . "/views/client/header.php") ?>
    <section id="content" class="collection-content">
        <div class="container-fluid">
            <div class="mb-5 border-top border-bottom py-2" id="search-title">
                Tìm thấy <span style="font-weight:500"><?php echo $count ?> sản phẩm</span> cho từ khóa <span>"<?php echo get_query("keyword"); ?>"</span>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 gy-5 collection">
            <?php foreach($products as $p): ?>
                <div class="col">
                    <div class="collection-item">
                        <a href="<?php echo url("product/detail/{$p->id}") ?>">
                            <img width="187.5" height="187.5" loading="lazy" src="<?php echo $p->thumbnail ?>" style="display: inline;">
                            <div class="title"><?php echo $p->p_name ?></div>
                            <div class="price"><span class="num">392.000</span><span class="currency">VND</span></div>
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
            <?php if($total_pages > 1): ?>
                <div class="group-pagging flex-column mt-5">
                    <div style="font-size: 13px;color:#8a8a8a;margin-bottom:5px">hiển thị <span data-displayed-products="<?php echo $displayed_products; ?>" id="displayed_products"><?php echo $displayed_products ?></span> trong <span id="total_products"><?php echo $number_of_products; ?></span> sản phẩm</div>
                    <button class="view-more view-more-search cate-view-more" data-currentpage="1">
                        Xem thêm <span class="viewmore-totalitem"><?php echo $number_of_products - $limit > $limit ? $limit : $number_of_products - $limit ?></span> sản phẩm
                    </button>
                </div>
                <?php else: ?>
                <div class="group-pagging flex-column mt-5">
                    <div style="font-size: 13px;color:#8a8a8a;margin-bottom:5px">hiển thị <span data-displayed-products="<?php echo $displayed_products; ?>" id="displayed_products"><?php echo $displayed_products ?></span> trong <span id="total_products"><?php echo $number_of_products; ?></span> sản phẩm</div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <script>
        const keyword = "<?php echo get_query("keyword") ?>";
        const search_url = "<?php echo current_url() ?>";
        const search = true;
        var total_products = <?php echo $number_of_products; ?>;
        const limit = <?php echo $limit; ?>
    </script>
<?php include(ROOT_PATH . "/views/client/footer.php") ?>