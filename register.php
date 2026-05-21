<?php
session_start();
$backend_url = getenv('BACKEND_URL') ?: 'http://backend-service/api.php';
$role_default = $_GET['role'] ?? 'pembeli';

if(isset($_POST['register'])){
  $ch = curl_init("$backend_url?action=register");
  curl_setopt_array($ch, [
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => http_build_query($_POST),
      CURLOPT_RETURNTRANSFER => true
  ]);
  $response = curl_exec($ch);
  $res = json_decode($response, true);
  
  if (curl_errno($ch)) {
      $error = 'CURL Error: ' . curl_error($ch);
  } elseif(($res['status'] ?? '') == 'success') {
      header("Location: login.php?msg=registered");
      exit;
  } else {
      $error = $res['message'] ?? 'Gagal mendaftar! (API Response Empty)';
  }
  curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Daftar Akun — NaumiShop</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="login-page">
  <div class="login-left">
    <div class="login-illo">✨</div>
    <h1>Gabung NaumiShop</h1>
    <p>Mulai perjalanan belanja dan jualan online Anda bersama jutaan pengguna lainnya!</p>
    <div style="display:flex;gap:16px;margin-top:28px;flex-wrap:wrap;justify-content:center">
      <div style="text-align:center"><div style="font-size:26px;font-weight:800">10K+</div><div style="font-size:12px;opacity:.8">Produk</div></div>
      <div style="width:1px;background:rgba(255,255,255,.3)"></div>
      <div style="text-align:center"><div style="font-size:26px;font-weight:800">5K+</div><div style="font-size:12px;opacity:.8">Penjual</div></div>
      <div style="width:1px;background:rgba(255,255,255,.3)"></div>
      <div style="text-align:center"><div style="font-size:26px;font-weight:800">50K+</div><div style="font-size:12px;opacity:.8">Pembeli</div></div>
    </div>
  </div>
  <div class="login-right fade-up">
    <h2>Buat Akun Baru</h2>
    <p class="sub">Daftar sekarang dan nikmati Flash Sale setiap hari! 🚀</p>
    
    <?php if(isset($error)):?>
    <div class="alert-err"><i class="fas fa-exclamation-circle"></i> <?=htmlspecialchars($error)?></div>
    <?php endif;?>
    
    <form method="POST" style="width:100%">
      <div class="form-group">
        <label class="form-label"><i class="fas fa-user" style="color:var(--primary);margin-right:5px"></i>Username</label>
        <input type="text" name="username" class="form-control" placeholder="Buat username unik" required autofocus>
      </div>
      
      <div class="form-group">
        <label class="form-label"><i class="fas fa-lock" style="color:var(--primary);margin-right:5px"></i>Password</label>
        <input type="password" name="password" id="pwd" class="form-control" placeholder="Buat password yang kuat" required>
      </div>
      
      <div style="display:flex;align-items:center;gap:7px;margin-bottom:20px">
        <input type="checkbox" id="sp" onchange="document.getElementById('pwd').type=this.checked?'text':'password'">
        <label for="sp" style="font-size:12px;color:var(--gray);cursor:pointer">Tampilkan password</label>
      </div>
      
      <div class="form-group">
        <label class="form-label"><i class="fas fa-user-tag" style="color:var(--primary);margin-right:5px"></i>Daftar Sebagai</label>
        <select name="role" class="form-control" required style="cursor:pointer">
            <option value="pembeli" <?= $role_default == 'pembeli' ? 'selected' : '' ?>>🛒 Pembeli (Belanja Barang)</option>
            <option value="penjual" <?= $role_default == 'penjual' ? 'selected' : '' ?>>🏪 Penjual (Buka Toko)</option>
        </select>
      </div>
      
      <button type="submit" name="register" class="btn-primary" style="margin-top:10px;"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
    </form>
    
    <div style="margin-top:20px;text-align:center;font-size:14px;">
      Sudah punya akun? <a href="login.php" style="color:var(--primary);font-weight:600;text-decoration:none;">Masuk di sini</a>
    </div>
    <div style="margin-top:16px;text-align:center">
      <a href="index.php" style="color:var(--gray);font-size:12px;text-decoration:none;"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
    </div>
  </div>
</div>
</body></html>
