<?php include_once ROOT_PATH . "/views/admin/header.php" ?>
    <?php
        $message = getMessage();
        if($message) :
    ?>
        <script src="<?php echo public_url("client_assets/js/notifIt.js") ?>"></script>
        <link rel="stylesheet" href="<?php echo public_url("client_assets/css/notifIt.css"); ?>">    
        <script>
            notif({
                msg:"<?php echo $message['message']; ?>",
                type:"success",
                position:"center",
                height :"auto",
                top: 80,
                timeout: 5000,
                animation:'slide'
            });
        </script>
    <?php endif; ?>
    <script>
        const variation_of_products = [];
    </script>
    <?php //array_print($products); ?>
    <form class="mb-3">
        <input type="text" name="keyword" value="<?php echo get_query("keyword") ?? "" ?>" class="form-control" placeholder="Tìm sản phẩm theo tên hoặc mã">
    </form>    
    <table id="product-table" class="table bg-white">
        <thead>
            <tr>
                <th style="width:30%" scope="col">Tên sản phẩm</th>
                <th style="width:20%" scope="col">Phân loại hàng</th>
                <th style="width:15%" scope="col">Giá</th>
                <th style="width:10%" scope="col">Kho</th>
                <th style="width:15%" scope="col">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $p): ?>
            <?php if(isset($p->colors_sizes)): ?>
                <?php
                    $groupedColors = [];
                    foreach($p->colors_sizes as $item){
                        $groupedColors[$item->color_name][] = $item;
                    }
                ?>
                <script>
                    variation_of_products.push({p_id:"<?php echo $p->id; ?>", variation:<?php echo json_encode($groupedColors); ?>});
                </script>
            <?php endif; ?>      
            <tr id="<?php echo $p->id; ?>">
                <td class="product-name-wrap">
                    <div class="d-flex">
                        <img style="width: 100px;margin-right:10px" src="<?php echo $p->product_images[0]; ?>" alt="">
                        <span><?php echo $p->p_name ?></span>
                    </div>
                </td>
                <?php
                    if($p->colors_sizes){
                        $color = $p->colors_sizes[0]->color_name;
                    }
                ?>
                <td class="product-variation-name">
                    <?php if(isset($p->colors_sizes)): ?>
                        <?php foreach($p->colors_sizes as $item):?>
                            <?php if($color === $item->color_name): ?>
                                <div><?php echo "{$item->color_name},{$item->size}"; ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if(count($groupedColors) > 1): ?>
                            <div class="toggle-variation more" data-p_id="<?php echo $p->id ?>">Xem thêm</div>
                        <?php endif; ?>
                    <?php elseif(isset($p->colors)): ?>
                        <?php foreach($p->colors as $item):?>
                           <div><?php echo "{$item->color_name}"; ?></div>
                        <?php endforeach; ?>
                    <?php elseif(isset($p->sizes)): ?>
                        <?php foreach($p->sizes as $item):?>
                           <div><?php echo "{$item->size}"; ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="product-variation-price">
                    <?php if(isset($p->colors_sizes)): ?>
                        <?php foreach($p->colors_sizes as $item): ?>
                            <?php if($color === $item->color_name): ?>
                                <div><?php echo number_format($item->price,0,"",".") . "₫"; ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php elseif(isset($p->colors)): ?>
                        <?php foreach($p->colors as $item): ?>
                            <div><?php echo number_format($item->price,0,"",".") . "₫"; ?></div>
                        <?php endforeach; ?>
                    <?php elseif(isset($p->sizes)): ?>
                        <?php foreach($p->sizes as $item): ?>
                            <div><?php echo number_format($item->price,0,"",".") . "₫"; ?></div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div><?php echo number_format($p->p_price,0,"",".") . "₫"; ?></div>
                    <?php endif; ?>
                </td>
                <td class="product-variation-stock">
                    <?php if(isset($p->colors_sizes)): ?>
                        <?php foreach($p->colors_sizes as $item): ?>
                            <?php if($color === $item->color_name): ?>
                                <div><?php echo $item->stock; ?></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php elseif($p->colors): ?>
                        <?php foreach($p->colors as $item): ?>
                            <div><?php echo $item->stock; ?></div>
                        <?php endforeach; ?>
                    <?php elseif($p->sizes): ?>
                        <?php foreach($p->sizes as $item): ?>
                            <div><?php echo $item->stock; ?></div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div><?php echo $p->p_stock; ?></div>
                    <?php endif; ?>
                </td>
                <td class="product-actions">
                    <a href="<?php echo url("admin/product/edit/{$p->id}") ?>">Cập nhật</a>
                    <a href="<?php echo url("product/detail/{$p->id}") ?>">Xem sản phẩm</a>
                </td>
            </tr>
            <?php endforeach; ?>    
        </tbody>
    </table>
    <?php echo pagination($totalPages,$currentPage); ?>
               
<?php include_once ROOT_PATH . "/views/admin/footer.php" ?>