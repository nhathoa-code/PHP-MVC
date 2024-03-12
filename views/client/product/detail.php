<?php include(ROOT_PATH . "/views/client/header.php") ?>
<script>
    const add_to_cart_url = "<?php echo url("cart/add"); ?>";
    const add_to_wl_url = "<?php echo url("product/add/wl"); ?>";
    const p_id = "<?php echo $product->id ?>";
    const p_name = "<?php echo $product->p_name ?>";
    const product_detail = true;
    var must_pick_size = false;
    const remove_from_wl_url = "<?php echo url("product/remove/wl") ?>";
</script>
<script src="<?php echo public_url("client_assets/js/notifIt.js") ?>"></script>
<link rel="stylesheet" href="<?php echo public_url("client_assets/css/notifIt.css"); ?>"> 
<section id="content">
    <div class="container-fluid">
        <div class="row">
            <div id="product-detail" class="col-12">
                <div class="row">
                    <div class="col-lg-8 col-ms-12">
                        <div class="detail-product-picture photoswipe" id="picture">
                            <?php
                                if(isset($product->colors)){
                                    $is_color_selected = false; 
                                    $color_selected_index = -1;
                                    foreach($product->colors as $index => $color){
                                        if(!$is_color_selected){
                                            if(isset($color->sizes)){
                                                foreach($color->sizes as $item){
                                                    if($item->stock > 0){
                                                        $is_color_selected = true;
                                                        $color_selected_index = $index;
                                                        break;
                                                    }
                                                }
                                            }else{
                                                if(!$is_color_selected){
                                                    if($color->stock > 0){
                                                        $is_color_selected = true;
                                                        $color_selected_index = $index;
                                                    }
                                                }
                                            }   
                                        }
                                    }
                                }
                            ?>
                            <div>
                                <ul>
                                    <div class="detail-product-picture-main">
                                        <?php if(isset($product->colors)): ?>
                                        <li>
                                            <div class="photoswipeImages">
                                                <span>
                                                    <a href="<?php echo $product->colors[$color_selected_index]->gallery_images[0]; ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                        <img title="<?php $product->p_name ?>" loading="lazy" alt="<?php $product->p_name ?>" src="<?php echo $product->colors[$color_selected_index]->gallery_images[0]; ?>" id="mainImage" class="picture--mainimage mainimage0" width="400" height="400">
                                                    </a>
                                                </span>
                                            </div>
                                        </li>
                                        <?php foreach($product->images as $item): ?>
                                            
                                            <li>
                                                <div class="photoswipeImages">
                                                    <span>
                                                        <a href="<?php echo $item; ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                            <img title="<?php echo $product->p_name ?>" loading="lazy" alt="<?php echo $product->p_name ?>" src="<?php echo $item; ?>" id="mainImage" class="picture--mainimage mainimage0" width="400" height="400">
                                                        </a>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>    
                                        <?php elseif(isset($product->sizes)): ?>
                                            <li>
                                                <div class="photoswipeImages">
                                                    <span>
                                                        <a href="<?php echo $product->images[0]; ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                            <img title="<?php $product->p_name ?>" loading="lazy" alt="<?php $product->p_name ?>" src="<?php echo $product->images[0]; ?>" id="mainImage" class="picture--mainimage mainimage0" width="400" height="400">
                                                        </a>    
                                                    </span>
                                                </div>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <div class="photoswipeImages">
                                                    <span>
                                                        <a href="<?php echo $product->images[0]; ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                            <img title="<?php $product->p_name ?>" loading="lazy" alt="<?php $product->p_name ?>" src="<?php echo $product->images[0]; ?>" id="mainImage" class="picture--mainimage mainimage0" width="400" height="400">
                                                        </a>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </div>
                                </ul>
                            </div>
                            <div class="detail-product-photoswipe">
                                <ul class="pswp-gallery pswp-gallery--single-column" id="photoswipe">
                                    <?php if(isset($product->colors)): ?>
                                    <?php for($i = 1;$i < count($product->colors[$color_selected_index]->gallery_images);$i++): ?>
                                        <li>
                                            <div class="photoswipeImages" data-pswp-uid="2">
                                            <span data-src="//img.muji.net/img/item/4550583095635_01_1260.jpg" data-size="1260x1260">
                                                <a href="<?php echo $product->colors[$color_selected_index]->gallery_images[$i] ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                    <img src="<?php echo $product->colors[$color_selected_index]->gallery_images[$i] ?>" class="picture--mainimage" loading="lazy" alt="" width="400" height="400">
                                                </a>
                                            </span>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                    <?php else: ?>
                                    <?php for($i = 1;$i < count($product->images);$i++): ?>
                                        <li>
                                            <div class="photoswipeImages" data-pswp-uid="2">
                                            <span data-src="//img.muji.net/img/item/4550583095635_01_1260.jpg" data-size="1260x1260">
                                                <a href="<?php echo $product->images[$i] ?>" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                    <img id="1" src="<?php echo $product->images[$i] ?>" class="picture--mainimage" alt="" loading="lazy" width="400" height="400">
                                                </a>
                                            </span>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-ms-12 mt-4 mt-lg-0 sticky" style="top:78.42px">
                        <h1 class="detail-product-name"><?php echo $product->p_name ?>   
                            <span class="sale"><span class="icon">SALE</span></span>
                            <?php if(!isset($product->wl) || !$product->wl): ?>
                            <svg id="add-wl" style="margin-bottom: 5px;cursor:pointer" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.5332 6.93144L10.7688 6.14476C8.97322 4.29941 5.68169 4.93586 4.49325 7.25588C3.93592 8.34745 3.8097 9.92258 4.82836 11.9341C5.80969 13.8702 7.85056 16.1884 11.5332 18.7147C15.2158 16.1884 17.2558 13.8702 18.2389 11.9341C19.2567 9.92169 19.1323 8.34745 18.5732 7.25677C17.3847 4.93675 14.0932 4.29852 12.2976 6.14387L11.5332 6.93144ZM11.5332 20C-4.82224 9.19279 6.49768 0.757159 11.3465 5.21942C11.4096 5.27809 11.4728 5.33853 11.5332 5.40165C11.5936 5.33853 11.6559 5.2772 11.7208 5.22031C16.5687 0.755381 27.8886 9.19101 11.5341 20H11.5332Z" fill="#25282B"></path>
                            </svg>
                            <?php else: ?>
                            <svg class="remove-wl" data-p_id="<?php echo $product->id ?>" style="margin-bottom: 5px;cursor:pointer" width="24" height="24" viewBox="0 0 32 32" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6526 5.94266C22.5995 -1.35734 39.9656 11.416 15.6526 27.84C-8.66048 11.4173 8.70691 -1.35734 15.6526 5.94266Z" fill="#52575C"> </path>
                            </svg>
                            <?php endif; ?>
                        </h1>
                        <p class="desc"><?php echo $product->p_desc ?></p>
                        <section class="detail-price-label">
                            <div class="price">
                                <span class="num"><?php echo number_format($product->p_price,0,"",".") ?></span>Đ
                            </div>
                        </section>
                        <div class="jan">
                            MÃ SẢN PHẨM:<span class="num"><?php echo $product->id ?></span>
                        </div>
                        <?php if(isset($product->colors)): ?>
                            <div class="detail-product-color">
                                <dl class="color">
                                <dt>MÀU: <span id="color-name"></span></dt>
                                    <?php foreach($product->colors as $index => $color): ?>
                                        <dd class="available">
                                            <span class="color-item <?php echo $index === $color_selected_index ? "selected" : "" ?>">
                                                <img alt="" title="" src="<?php echo url($color->color_image) ?>" width="50" height="50">
                                            </span>
                                        </dd>
                                    <?php endforeach; ?>   
                                    <script>
                                        const selected_color = <?php echo json_encode($product->colors[$color_selected_index]); ?>;
                                        color_name = selected_color.color_name;
                                        color_image = selected_color.color_image;
                                        color_ID = selected_color.id;
                                        p_image = selected_color.gallery_images[0];
                                        if(!selected_color.hasOwnProperty("sizes")){
                                            price = selected_color.price;
                                        }else{
                                            must_pick_size = true;
                                        }
                                        $(".detail-product-color .color .color-item").eq(<?php echo $color_selected_index; ?>).data("selected",true);
                                        const product_colors = <?php echo json_encode($product->colors); ?>;
                                        $("#color-name").text(`${selected_color.color_name}`);
                                        $(".detail-product-color .color .color-item").each((index,item)=>{
                                            $(item).click(function(){                                                  
                                                if (!$(item).data("selected")) {
                                                    $("#product-detail .detail-product-color .color-item").removeData(
                                                    "selected"
                                                    );
                                                    $(item).data("selected", true);
                                                } else {
                                                    return;
                                                }
                                                $("#product-detail .detail-product-color .color span").removeClass("selected");
                                                $(item).addClass("selected");
                                                let color = product_colors[index];
                                                price = null;
                                                size = null;
                                                color_name = color.color_name;
                                                color_image = color.color_image;
                                                color_ID = color.id;
                                                p_image = color.gallery_images[0];
                                                $(`#product-detail .detail-product-picture-main li[data-slick-index=0] img`).prop(
                                                "src",
                                                color.gallery_images[0]
                                                );
                                                $(`#product-detail .detail-product-picture-main`).slick("slickGoTo",0)
                                                $(`#product-detail .detail-product-picture-main li[data-slick-index=<?php echo count($product->images) + 1 ?>] img`).prop(
                                                "src",
                                                color.gallery_images[0]
                                                );
                                                if(color.hasOwnProperty("sizes")){
                                                    $(".price span").text(new Intl.NumberFormat({style: "currency"}).format(Math.max(...color.sizes.map((item)=> item.price))));
                                                    $(".detail-product-size .size dt").nextAll().remove();
                                                    color.sizes.forEach((item) => {
                                                        let dd = $("<dd></dd>");
                                                        let span = $(`<span ${item.stock <= 0 ? "class='out-stock'" : ""}>${item.size}</span>`);
                                                        $(span).click(function(){
                                                            if(item.stock > 0){
                                                                $(".detail-product-size .size span").removeClass("selected");
                                                                $(this).addClass("selected");
                                                                size = item.size;
                                                                price = item.price;
                                                                $(".price span").text(new Intl.NumberFormat({style: "currency"}).format(price))
                                                            }
                                                        })
                                                        dd.append(span);
                                                        $(".detail-product-size .size").append(dd);
                                                    });
                                                }else{
                                                    price = color.price;
                                                    $(".price span").text(new Intl.NumberFormat({style: "currency"}).format(price))
                                                }
                                                let photo_swipe = "";
                                                for (let i = 1; i < color.gallery_images.length; i++) {
                                                photo_swipe += `<li>
                                                                    <div class="photoswipeImages" data-pswp-uid="2">
                                                                    <span data-src="//img.muji.net/img/item/4550583095635_01_1260.jpg" data-size="1260x1260">
                                                                        <a href="${color.gallery_images[i]}" data-pswp-width="600" data-pswp-height="600" target="_blank">
                                                                            <img id="${i}" src="${color.gallery_images[i]}" class="picture--mainimage" alt="" width="400" height="400">
                                                                        </a>    
                                                                    </span>
                                                                    </div>
                                                                </li>`;
                                                }
                                                $("#product-detail .detail-product-photoswipe ul").html(photo_swipe);
                                                $("#product-detail #color-name").text(`${color.color_name}`);
                                            })                                              
                                        })
                                    </script>
                                </dl>
                            </div>
                            <?php if(isset($product->colors[$color_selected_index]->sizes)):  ?>
                            <div class="detail-product-size">
                                <dl class="size">
                                    <dt>KÍCH THƯỚC</dt>
                                    <?php foreach($product->colors[$color_selected_index]->sizes as $size): ?>
                                        <dd>
                                            <span <?php echo $size->stock <= 0 ? 'class="out-stock"' : "" ?>>
                                                <?php echo $size->size ?>
                                            </span>
                                        </dd>
                                    <?php endforeach; ?>                         
                                </dl>
                            </div>
                            <script>
                                const sizes = <?php echo json_encode($product->colors[$color_selected_index]->sizes); ?>;
                                $(".detail-product-size .size span").each(function(index,item){
                                    $(item).click(function(){
                                        if(sizes[index].stock > 0){
                                            $(".detail-product-size .size span").removeClass("selected");
                                            $(item).addClass("selected");
                                            size = sizes[index].size;
                                            price = sizes[index].price;
                                        }
                                    })
                                })
                            </script>
                            <?php endif; ?>
                        <?php elseif(isset($product->sizes)): ?>
                            <div class="detail-product-size">
                                <dl class="size">
                                    <dt>KÍCH THƯỚC</dt>
                                    <?php foreach($product->sizes as $size): ?>
                                        <dd>
                                            <span <?php echo $size->stock <= 0 ? 'class="out-stock"' : "" ?>>
                                                <?php echo $size->size ?>
                                            </span>
                                        </dd>
                                    <?php endforeach; ?>                         
                                </dl>
                            </div>
                            <script>
                                p_image = "<?php echo $product->images[0]; ?>";
                                must_pick_size = true;
                                const sizes = <?php echo json_encode($product->sizes) ?>;
                                $(".detail-product-size .size dd span").each((index,item) => {
                                    $(item).click(function(){
                                        if(sizes[index].stock > 0){
                                            $(".detail-product-size .size span").removeClass("selected");
                                            $(this).addClass("selected");
                                            size = sizes[index].size;
                                            price = sizes[index].price;
                                            $(".price span").text(new Intl.NumberFormat({style: "currency"}).format(price))
                                        }
                                    })
                                });
                            </script>
                        <?php else: ?>    
                            <script>
                                p_image = "<?php echo $product->images[0]; ?>";
                                price = <?php echo $product->p_price; ?>;
                            </script>    
                        <?php endif; ?>
                        <div id="actions" class="d-flex">
                            <button id="buy-now" class="btn btn-secondary border-radius-none col">Mua ngay</button>
                            <button id="add-to-cart" class="btn btn-outline border-radius-none col">Thêm vào giỏ hàng</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($recent_viewed_products && count($recent_viewed_products) > 0): ?>
            <div id="recent-viewed-products" class="collection-content mt-5">
                <div style="font-weight: 500;font-size:24px" class="mb-4 mt-3">Đã xem gần đây</div>
                    <div class="collection recent-viewed-products">
                    <?php foreach($recent_viewed_products as $id => $p): extract($p)?>
                            <div class="collection-item">
                                <a href="<?php echo url("product/detail/{$id}") ?>">
                                    <img width="187.5" height="187.5" loading="lazy" src="<?php echo $thumbnail ?>" style="display: inline;">
                                    <div class="title"><?php echo $name ?></div>
                                    <div class="price"><span class="num"><?php echo number_format($price,0,"",".") ?></span><span class="currency">đ</span></div>
                                </a>
                                <?php if(isset($p['colors'])): ?>
                                <ul class="colors">
                                    <?php if(count($p['colors']) <= 3): ?>
                                    <?php foreach($p['colors'] as $color): ?>
                                    <li>
                                        <span>
                                            <img src="<?php echo url($color) ?>" alt="">
                                        </span>
                                    </li>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <?php 
                                            $break_index = 0;
                                        ?>
                                        <?php foreach($p['colors'] as $color): ?>
                                        <li <?php echo $break_index === 2 ? "class='d-flex flex-row align-items-center'" : "" ?>>
                                        <span>
                                            <img src="<?php echo url($color) ?>" alt="">
                                        </span>
                                        <?php if($break_index === 2): ?>
                                            <span style="color:#666">+<?php echo count($p['colors']) - 3; ?></span>
                                        <?php endif; ?>    
                                        </li>
                                        <?php if($break_index === 2){break;} $break_index++; endforeach; ?>
                                    <?php endif; ?>    
                                </ul>
                                <?php endif; ?>
                            </div>                     
                    <?php endforeach; ?>    
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<script>
    const cart_url = "<?php echo url("cart"); ?>";
    const checkout_url = "<?php echo url("checkout"); ?>";
</script>
<?php include(ROOT_PATH . "/views/client/footer.php") ?>
