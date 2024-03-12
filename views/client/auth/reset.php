<?php include(ROOT_PATH . "/views/client/header.php") ?>
    <?php
        $errors = getValidationErrors();
        $old_inputs = getOldInputs();
        $message = getMessage();
    ?>
    <section id="content">
        <div class="container-fluid">
            <form style="margin: 0 auto;" class="w-50 mt-3" id="login-form" method="post" action="<?php echo url("auth/resetpassword"); ?>">
                <div class="p-3">
                    <h1>ĐẶT LẠI MẬT KHẨU</h1>
                    <div class="row mx-0">
                        <div class="col-12 mb-3 p-0">
                            <label class="form-label">Mật khẩu mới<span aria-hidden="true" class="MuiInputLabel-asterisk css-sp68t1"> *</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control" placeholder="Nhập mật khẩu mới" name="password">
                                <svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye-fill eye-icon position-absolute" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                            </div>
                        </div>
                        <?php if(isset($errors) && isset($errors['password'])): ?>
                                <?php foreach($errors['password'] as $message): ?>
                                    <p class="error px-0"><?php echo $message; ?></p>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <div class="col-12 mb-3 p-0">
                            <label class="form-label">Nhập lại mật khẩu mới<span aria-hidden="true" class="MuiInputLabel-asterisk css-sp68t1"> *</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu mới" name="retype_password">
                                <svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye-fill eye-icon position-absolute" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                            </div>
                        </div>
                        <?php if(isset($errors) && isset($errors['retype_password'])): ?>
                            <?php foreach($errors['retype_password'] as $message): ?>
                                <p class="error px-0"><?php echo $message; ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="token" value="<?php echo get_query("token"); ?>">
                    <div style="font-size: 14px;" class="mb-3">
                        Đã nhớ mật khẩu? <a href="<?php echo url("auth/login"); ?>">Đăng nhập ngay</a>
                    </div>
                    <div class="row mx-0">
                        <button class="btn btn-secondary p-2" type="submit">Đặt lại mật khẩu</button>
                    </div>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>
    </section>    
<?php include(ROOT_PATH . "/views/client/footer.php") ?>