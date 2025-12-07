<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header
  style="
    width:100%;
    height:64px;
    position:fixed;
    top:0;
    left:0;
    right:0;
    z-index:100;
    background:#ffffff;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 32px;
    box-sizing:border-box;
    font-family:'Poppins', Arial, sans-serif;
  "
>
  <!-- KIRI: AUTO CARE -->
  <div
    style="
      font-size:22px;
      font-weight:700;
      color:#b22929;
      user-select:none;
    "
  >
    AUTO CARE
  </div>

  <!-- KANAN: Beranda + Profil -->
  <div
    style="
      display:flex;
      align-items:center;
      gap:20px;
    "
  >
    <!-- Beranda: bisa diklik tapi bukan link biru -->
    <span
      style="
        font-size:16px;
        font-weight:500;
        color:#333;
        cursor:pointer;
        padding:6px 10px;
        border-radius:6px;
        user-select:none;
        transition:background .2s;
      "
      onclick="window.location.href='beranda.php'"
      onmouseover="this.style.background='#f0f0f0'"
      onmouseout="this.style.background='transparent'"
    >
      Beranda
    </span>

    <!-- Foto Profil -->
    <a
      href="profil.php"
      style="
        width:38px;
        height:38px;
        border-radius:50%;
        overflow:hidden;
        border:2px solid #b22929;
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        transition:transform .2s;
      "
      onmouseover="this.style.transform='scale(1.05)'"
      onmouseout="this.style.transform='scale(1)'"
    >
      <img
        src="IMG/profil.jpg"
        alt="Profil"
        style="
          width:100%;
          height:100%;
          object-fit:cover;
        "
      >
    </a>
  </div>
</header>
