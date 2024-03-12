<?php include(ROOT_PATH . "/views/client/header.php") ?>
    <?php
        $errors = getValidationErrors();
        $old_inputs = getOldInputs();
        $message = getMessage();
    ?>
    <section id="content">
        <div class="container-fluid">
            <form style="margin: 0 auto;" class="w-50 mt-3" id="login-form" method="post" action="<?php echo url("auth/retrievepassword"); ?>">
                <div class="p-3">
                    <h1>QUÊN MẬT KHẨU</h1>
                    <div class="row mx-0">
                        <div class="col-12 mb-3 p-0">
                            <label class="form-label">Email<span aria-hidden="true" class="MuiInputLabel-asterisk css-sp68t1"> *</span></label>
                            <input value="<?php echo isset($old_inputs) ? $old_inputs['email'] : "" ?>" type="text" class="form-control" placeholder="Nhập email" name="email">
                        </div>
                        <?php if(isset($errors) && isset($errors['email'])): ?>
                            <?php foreach($errors['email'] as $message): ?>
                                <p class="error px-0"><?php echo $message; ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($message) && isset($message['message'])): ?>
                        <p class="<?php echo $message['type'] ?>"><?php echo $message['message']; ?></p>
                    <?php endif; ?>
                    <div style="font-size: 14px;" class="mb-3">
                        Đã có tài khoản? <a href="<?php echo url("auth/login"); ?>">Đăng nhập ngay</a>
                    </div>
                    <div class="row mx-0">
                        <button class="btn btn-secondary p-2" type="submit">Lấy lại mật khẩu</button>
                    </div>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>
    </section>
   
<?php include(ROOT_PATH . "/views/client/footer.php") ?>