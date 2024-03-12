<?php include_once ROOT_PATH . "/views/admin/header.php" ?>
<div class="heading">Danh mục</div>
<div class="row">
    <div class="col-4">
        <form id="add-cat-form" action="<?php echo url("admin/category/add") ?>">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" name="cat_name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Slug</label>
                <input type="text" name="cat_slug" class="form-control">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Parent</label>
                <select name="parent_id" class="form-select form-control" aria-label="Default select example">
                <option selected value="">none</option>
                <?php $fetchParentOptions(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Thêm hình ảnh</label>
                <input style="display:none" id="file-input" type="file" name="cat_image">
                <div style="display: flex;">
                    <div class="image-upload-container image-upload">
                        <div class="image-upload-icon" style="width: 30px;height:30px">
                            <svg viewBox="0 0 23 21" xmlns="http://www.w3.org/2000/svg"><path d="M18.5 0A1.5 1.5 0 0120 1.5V12c-.49-.07-1.01-.07-1.5 0V1.5H2v12.65l3.395-3.408a.75.75 0 01.958-.087l.104.087L7.89 12.18l3.687-5.21a.75.75 0 01.96-.086l.103.087 3.391 3.405c.81.813.433 2.28-.398 3.07A5.235 5.235 0 0014.053 18H2a1.5 1.5 0 01-1.5-1.5v-15A1.5 1.5 0 012 0h16.5z"></path><path d="M6.5 4.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM18.5 14.25a.75.75 0 011.5 0v2.25h2.25a.75.75 0 010 1.5H20v2.25a.75.75 0 01-1.5 0V18h-2.25a.75.75 0 010-1.5h2.25v-2.25z"></path></svg>
                        </div>
                    </div>
                    <img src="https://img.muji.net/img/item/4550583281465_400.jpg" class="preview-image" style="display:none;max-width: 80px; max-height: 80px;float:right" />
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-primary mr-2">Add New Category</button>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">                              
        </form>
    </div>
    <div class="col-8">
        <table id="cat-table" class="table table-borderless position-relative">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Count</th>
                </tr>
            </thead>
            <tbody>
                <?php $fetchCategories(); ?>
            </tbody>
        </table>
    </div>
</div>       
<?php include_once ROOT_PATH . "/views/admin/footer.php" ?>