<?php include(ROOT_PATH . "/views/client/header.php") ?>
        <section id="content">
            <div class="container-fluid collection-content">
                <div class="row">
                    <div style="top:78.42px" class="col-12 col-lg-3 sticky d-none d-lg-block">
                        <h1 class="collection-left-title filter-title">Danh mục</h1>
                        <ul style="display: flex;flex-direction:column;gap:10px;font-size:14px;padding-left:12px">
                            <?php foreach($siblings as $c): ?>
                                <li>
                                    <a href="<?php echo url("collection/{$parent->cat_slug}/{$c->cat_slug}") ?>"><?php echo $c->cat_name ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form id="filter-form">
                            <button type="submit" style="padding:0.5rem 0.75rem !important" class="btn btn-secondary w-100 mb-2 mt-3">Lọc</button>
                            <section class="search-detail-color">
                                <h1 data-bs-toggle="collapse" data-bs-target="#collapseColorsFilter" class="collection-left-title toggle-collapse filter-title">Màu sắc <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path>
                                        </svg></h1>

                                <ul class="common-color-palette collapse" id="collapseColorsFilter">
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="TRẮNG">
                                            <span class="mark white">TRẮNG</span>
                                        </label>
                                        <span>TRẮNG</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="ĐEN">
                                            <span class="mark black">ĐEN</span>
                                        </label>
                                        <span>ĐEN</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="XÁM">
                                            <span class="mark grey">XÁM</span>
                                        </label>
                                        <span>XÁM</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="NÂU">
                                            <span class="mark brown">NÂU</span>
                                        </label>
                                        <span>NÂU</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="BE">
                                            <span class="mark beige">BE</span>
                                        </label>
                                        <span>BE</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="XANH LÁ">
                                            <span class="mark green">XANH LÁ</span>
                                        </label>
                                        <span>XANH LÁ</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="XANH DƯƠNG">
                                            <span class="mark blue">XANH DƯƠNG</span>
                                        </label>
                                        <span>XANH DƯƠNG</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="TÍM">
                                            <span class="mark purple">TÍM</span>
                                        </label>
                                        <span>TÍM</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="VÀNG">
                                            <span class="mark yellow">VÀNG</span>
                                        </label>
                                        <span>VÀNG</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="HỒNG">
                                            <span class="mark pink">HỒNG</span>
                                        </label>
                                        <span>HỒNG</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="ĐỎ">
                                            <span class="mark red">ĐỎ</span>
                                        </label>
                                        <span>ĐỎ</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="CAM">
                                            <span class="mark orange">CAM</span>
                                        </label>
                                        <span>CAM</span>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="colors[]" class="rbtn" value="">
                                            <span class="mark other">KHÁC</span>
                                        </label>
                                        <span>KHÁC</span>
                                    </li>
                                </ul>
                            </section>
                            <section class="collection-size-filter mb-4">
                                <h1 data-bs-toggle="collapse" data-bs-target="#collapseSizesFilter" class="collection-left-title toggle-collapse filter-title">Kích cỡ <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path>
                                        </svg></h1>
                                <div class="fr-list mt-s mb-s">
                                    <ul class="fr-filter-tiles fr-filter-tiles-size collapse" id="collapseSizesFilter">
                                        <?php foreach($sizes_filter as $item): ?>
                                            <li class="fr-filter-tile" data-test="filter-XS"><input id="check-size-<?php echo $item->size ?>" type="checkbox" value="<?php echo $item->size ?>" name="sizes[]"><label for="check-size-<?php echo $item->size ?>" class="fr-filter-label"><?php echo $item->size ?></label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </section>
                            <section class="collection-price-filter">
                                <h1  data-bs-toggle="collapse" data-bs-target="#collapsePricesFilter" class="collection-left-title toggle-collapse filter-title">Giá <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path>
                                        </svg></h1>
                                <ul class="collapse" id="collapsePricesFilter">
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-0" name="prices[]" value="0-199000"><label for="spa-checkbox-group-0" style="cursor: pointer;"><span style="font-size: 14px;">< 199.000 VND</span></label></span>
                                    </li>
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-1" name="prices[]" value="199000-299000"><label for="spa-checkbox-group-1" style="cursor: pointer;"><span style="font-size: 14px;">199.000 VND - 299.000 VND</span></label></span>
                                    </li>
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-2" name="prices[]" value="299000-399000"><label for="spa-checkbox-group-2" style="cursor: pointer;"><span style="font-size: 14px;">299.000 VND - 399.000 VND</span></label></span>
                                    </li>
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-3" name="prices[]" value="399000-499000"><label for="spa-checkbox-group-3" style="cursor: pointer;"><span style="font-size: 14px;">399.000 VND - 499.000 VND</span></label></span>
                                    </li>
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-4" name="prices[]" value="499000-799000"><label for="spa-checkbox-group-4" style="cursor: pointer;"><span style="font-size: 14px;">499.000 VND - 799.000 VND</span></label></span>
                                    </li>
                                    <li class="item">
                                        <span class="price-check"><input type="checkbox" id="spa-checkbox-group-5" name="prices[]" value="799000-999000"><label for="spa-checkbox-group-5" style="cursor: pointer;"><span style="font-size: 14px;">799.000 VND - 999.000 VND</span></label></span>
                                    </li>
                                </ul>
                            </section>
                            <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                        </form>
                    </div>
                    <div style="top:57.84px" class="d-flex justify-content-between align-items-center d-lg-none mb-3 bg-white sticky-top py-3 filter-mobile">
                        <div id="filter-mobile" style="display: inline-block;cursor:pointer">
                            <svg style="height:20px" fill="none" viewBox="0 0 24 24"><g clip-path="url(#a)">
                                <path fill="black" fill-rule="evenodd" d="M3 19c.042 0 .083-.003.124-.008a4.002 4.002 0 0 0 7.75.008H23a1 1 0 1 0 0-2H10.874a4.002 4.002 0 0 0-7.75.008A1.016 1.016 0 0 0 3 17H1a1 1 0 1 0 0 2h2Zm6-1a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm6-12a2 2 0 1 0 4 0 2 2 0 0 0-4 0Zm2 4a4.002 4.002 0 0 1-3.874-3H1a1 1 0 1 1 0-2h12.126a4.002 4.002 0 0 1 7.75.008c.04-.005.082-.008.124-.008h2a1 1 0 1 1 0 2h-2c-.042 0-.083-.003-.124-.008A4.002 4.002 0 0 1 17 10Z" clip-rule="evenodd"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h24v24H0z"/></clipPath></defs>
                            </svg>
                            <span class="ms-2">Bộ lọc</span>
                        </div>
                        <div style="font-size: 14px;">
                            <span id="number-check">0</span> tiêu chí
                        </div>
                        <!-- for mobile -->
                        <div id="slideDiv" class="slide-out px-2">
                            <form id="filter-form-mobile">
                                <section class="search-detail-color">
                                    <h1 data-bs-toggle="collapse" data-bs-target="#collapseColorsFilter" class="collection-left-title toggle-collapse filter-title">Màu sắc <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path></svg>
                                    </h1>
                                    <ul class="common-color-palette collapse ms-3" id="collapseColorsFilter">
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="TRẮNG">
                                                <span class="mark white">TRẮNG</span>
                                            </label>
                                            <span>TRẮNG</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="ĐEN">
                                                <span class="mark black">ĐEN</span>
                                            </label>
                                            <span>ĐEN</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="XÁM">
                                                <span class="mark grey">XÁM</span>
                                            </label>
                                            <span>XÁM</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="NÂU">
                                                <span class="mark brown">NÂU</span>
                                            </label>
                                            <span>NÂU</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="BE">
                                                <span class="mark beige">BE</span>
                                            </label>
                                            <span>BE</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="XANH LÁ">
                                                <span class="mark green">XANH LÁ</span>
                                            </label>
                                            <span>XANH LÁ</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="XANH DƯƠNG">
                                                <span class="mark blue">XANH DƯƠNG</span>
                                            </label>
                                            <span>XANH DƯƠNG</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="TÍM">
                                                <span class="mark purple">TÍM</span>
                                            </label>
                                            <span>TÍM</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="VÀNG">
                                                <span class="mark yellow">VÀNG</span>
                                            </label>
                                            <span>VÀNG</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="HỒNG">
                                                <span class="mark pink">HỒNG</span>
                                            </label>
                                            <span>HỒNG</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="ĐỎ">
                                                <span class="mark red">ĐỎ</span>
                                            </label>
                                            <span>ĐỎ</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="CAM">
                                                <span class="mark orange">CAM</span>
                                            </label>
                                            <span>CAM</span>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" name="colors[]" class="rbtn" value="">
                                                <span class="mark other">KHÁC</span>
                                            </label>
                                            <span>KHÁC</span>
                                        </li>
                                    </ul>
                                </section>
                                <section class="collection-size-filter mb-4">
                                    <h1 data-bs-toggle="collapse" data-bs-target="#collapseSizesFilter" class="collection-left-title toggle-collapse filter-title">Kích cỡ 
                                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path>
                                        </svg>
                                    </h1>
                                    <div class="fr-list mt-s mb-s">
                                        <ul class="fr-filter-tiles fr-filter-tiles-size collapse ms-3" id="collapseSizesFilter">
                                            <?php foreach($sizes_filter as $item): ?>
                                                <li class="fr-filter-tile" data-test="filter-XS"><input id="check-size-<?php echo $item->size ?>-mobile" type="checkbox" value="<?php echo $item->size ?>" name="sizes[]"><label for="check-size-<?php echo $item->size ?>-mobile" class="fr-filter-label"><?php echo $item->size ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </section>
                                <section class="collection-price-filter">
                                    <h1 data-bs-toggle="collapse" data-bs-target="#collapsePricesFilter" class="collection-left-title toggle-collapse filter-title">Giá <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M12.056 7.496a.6.6 0 0 1 .85 0l7.2 7.2a.6.6 0 1 1-.85.85L12.48 8.768l-6.775 6.776a.6.6 0 1 1-.85-.85l7.2-7.2Z" clip-rule="evenodd"></path>
                                            </svg></h1>
                                    <ul class="collapse ms-3" id="collapsePricesFilter">
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-0-mobile" name="prices[]" value="0-199000"><label for="spa-checkbox-group-0-mobile" style="cursor: pointer;"><span style="font-size: 14px;">< 199.000 VND</span></label></span>
                                        </li>
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-1-mobile" name="prices[]" value="199000-299000"><label for="spa-checkbox-group-1-mobile" style="cursor: pointer;"><span style="font-size: 14px;">199.000 VND - 299.000 VND</span></label></span>
                                        </li>
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-2-mobile" name="prices[]" value="299000-399000"><label for="spa-checkbox-group-2-mobile" style="cursor: pointer;"><span style="font-size: 14px;">299.000 VND - 399.000 VND</span></label></span>
                                        </li>
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-3-mobile" name="prices[]" value="399000-499000"><label for="spa-checkbox-group-3-mobile" style="cursor: pointer;"><span style="font-size: 14px;">399.000 VND - 499.000 VND</span></label></span>
                                        </li>
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-4-mobile" name="prices[]" value="499000-799000"><label for="spa-checkbox-group-4-mobile" style="cursor: pointer;"><span style="font-size: 14px;">499.000 VND - 799.000 VND</span></label></span>
                                        </li>
                                        <li class="item">
                                            <span class="price-check"><input type="checkbox" id="spa-checkbox-group-5-mobile" name="prices[]" value="799000-999000"><label for="spa-checkbox-group-5-mobile" style="cursor: pointer;"><span style="font-size: 14px;">799.000 VND - 999.000 VND</span></label></span>
                                        </li>
                                    </ul>
                                </section>
                                <button type="submit" style="padding:0.5rem 0.75rem !important" class="btn btn-secondary w-100 mb-2 mt-3">Lọc</button>
                                <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                            </form>
                            <span id="close-filter-mobile">
                                <svg data-index="65dc901b9c520" style="align-self:end;cursor:pointer" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9">
                        <div class="row row-cols-2 row-cols-sm-3 gy-5 collection">
                            <?php foreach($collection as $p): ?>
                                <div class="col">
                                    <div class="collection-item">
                                        <a href="<?php echo url("product/detail/{$p->id}") ?>">
                                            <img width="187.5" height="187.5" loading="lazy" src="<?php echo $p->thumbnail ?>" style="display: inline;">
                                            <div class="title"><?php echo $p->p_name ?></div>
                                            <div class="price"><span class="num"><?php echo number_format($p->p_price,0,"",".") ?></span><span class="currency">Đ</span></div>
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
                            <button class="view-more cate-view-more" data-currentpage="1">
                                Xem thêm <span class="viewmore-totalitem"><?php echo $number_of_products - $limit > $limit ? $limit : $number_of_products - $limit ?></span> sản phẩm
                            </button>
                        </div>
                        <?php else: ?>
                        <div class="group-pagging flex-column mt-5">
                            <div style="font-size: 13px;color:#8a8a8a;margin-bottom:5px">hiển thị <span data-displayed-products="<?php echo $displayed_products; ?>" id="displayed_products"><?php echo $displayed_products ?></span> trong <span id="total_products"><?php echo $number_of_products; ?></span> sản phẩm</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
       
        <script>
            const collection_url = "<?php echo $collection_url; ?>";
            const collection = true;
            const filter_url = "<?php echo url("filter") ?>";
            const category_id = <?php echo $category_id; ?>;
            var total_products = <?php echo $number_of_products; ?>;
            const limit = <?php echo $limit; ?>
        </script>
<?php include(ROOT_PATH . "/views/client/footer.php") ?>
