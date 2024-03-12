<?php include_once ROOT_PATH . "/views/admin/header.php" ?>
<style>
    .error {
        margin-top: 10px;
        margin-bottom: 0; 
    }
</style>
<?php
    $errors = getValidationErrors();
    $old_inputs = getOldInputs();
?>
<?php
    $message = getMessage();
    if($message) :
?>
<script>
    notif({
        msg:"<?php echo $message['message']; ?>",
        type:"warning",
        position:"center",
        height :"auto",
        top: 80,
        timeout: 10000,
        animation:'slide'
    });
</script>
<?php endif; ?>    
<h1 class="heading">Tạo mới người dùng</h1>
<form id="add-user-form" action="<?php echo url("admin/user/add") ?>" method="post">
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Tên</label>
        </div>
        <div class="col-5">
            <input value="<?php echo isset($old_inputs) ? $old_inputs['name'] : "" ?>" type="text" name="name" class="form-control">
            <?php if(isset($errors) && isset($errors['name'])): ?>
                <?php foreach($errors['name'] as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Mã đăng nhập</label>
        </div>
        <div class="col-5">
            <input value="<?php echo isset($old_inputs) ? $old_inputs['login_key'] : "" ?>" type="text" name="login_key" class="form-control">
            <?php if(isset($errors) && isset($errors['login_key'])): ?>
                <?php foreach($errors['login_key'] as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Email</label>
        </div>
        <div class="col-5">
            <input value="<?php echo isset($old_inputs) ? $old_inputs['email'] : "" ?>" type="text" name="email" class="form-control">
            <?php if(isset($errors) && isset($errors['email'])): ?>
                <?php foreach($errors['email'] as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label for="">Mật khẩu</label>
        </div>
        <div class="col-5">
            <div class="position-relative">
                <input type="password" name="password" class="form-control">
                <svg style="right:10px" width="20" height="20" fill="currentColor" class="bi bi-eye-fill eye-icon position-absolute" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>
            </div>
            <?php if(isset($errors) && isset($errors['password'])): ?>
                <?php foreach($errors['password'] as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div> 
    <div class="row mb-5">
        <div class="col-2">
            <label>Vai trò</label>
        </div>
        <div class="col-5">
            <select name="role" class="form-select form-control" aria-label="Default select example">
                <option selected value="user">User</option>
                <option value="admin">Admin</option>
                <option value="guest">Guest</option>
            </select>
            <?php if(isset($errors) && isset($errors['role'])): ?>
                <?php foreach($errors['role'] as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
    <div class="row">
        <button class="btn btn-primary mr-2">Tạo người dùng</button>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
</form>
<?php include_once ROOT_PATH . "/views/admin/footer.php" ?>