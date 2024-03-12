<?php
  $message = getMessage();
  if($message) :
?>
  <script src="<?php echo public_url("client_assets/js/notifIt.js") ?>"></script>
  <link rel="stylesheet" href="<?php echo public_url("client_assets/css/notifIt.css"); ?>">    
  <script>
      notif({
          msg:"<?php echo $message['message']; ?>",
          type:"warning",
          position:"center",
          height :"auto",
          top: 80,
          timeout: 5000,
          animation:'slide'
      });
  </script>
<?php endif; ?>
<div class="user-title">Sữa địa chỉ</div>
<div class="row">
  <div class="col-12 col-lg-6">
  <form id="edit-address-form" class="w-100" method="post" action="<?php echo url("user/address/update") ?>">
    <div class="mb-3">
      <label class="form-label">Họ và tên</label>
      <input value="<?php echo $data['address']['name'] ?>" name="name" type="text" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Số điện thoại</label>
      <input value="<?php echo $data['address']['phone'] ?>" name="phone" type="text" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Tỉnh/Thành phố</label>
      <select name="province" id="province" class="form-select">
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Quận/Huyện</label>
      <select name="district" id="district" class="form-select">
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Phường/Xã</label>
      <select name="ward" id="ward" class="form-select">
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Địa chỉ</label>
      <input value="<?php echo $data['address']['address'] ?>" name="address" type="text" class="form-control">
    </div>
    <div class="form-check mb-3">
      <input style="border-color:rgb(153, 153, 153)" class="form-check-input" type="checkbox" name="default" id="defaultChecked" <?php echo array_key_exists("default",$data['address']) ? "checked" : "" ?>>
      <label class="form-check-label" for="defaultChecked">
        Địa chỉ mặc định
      </label>
    </div>
    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
    <button type="submit" class="btn btn-secondary w-100 mb-2">Lưu thay đổi</button>
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
  </form>
  <form class="w-100" method="post" action="<?php echo url("user/address/delete"); ?>">
    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
    <button type="submit" class="btn btn-outline w-100">Xóa địa chỉ</button>
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
  </form>
  </div>
</div>    
<script>
    const add = <?php echo json_encode($data['address']); ?>;
</script>