<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}
?>

<style>
  .consistent-navbar {
    width: 100%;
    height: 68px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 40px;
    box-sizing: border-box;
    font-family: 'Poppins', Arial, sans-serif;
  }

  .navbar-brand {
    font-weight: 700;
    color: #b22929;
    font-size: 22px;
    text-decoration: none;
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .nav-link-text {
    color: #000;
    font-weight: 500;
    text-decoration: none;
    padding: 6px 0;
    margin-left: 20px;
  }

  .nav-link-text:hover {
    color: #b22929;
  }

  .profile-icon-wrapper {
    display: flex;
    align-items: center;
    margin-left: 10px;
  }

  .profile-icon-wrapper img {
    width: 38px;
    height: auto;
    object-fit: cover;
  }
</style>

<header class="consistent-navbar">
  <a class="navbar-brand" href="beranda.php">AUTO CARE</a>

  <div class="nav-links">
    <a class="nav-link-text" href="beranda.php">Beranda</a>

    <a href="profil.php" class="profile-icon-wrapper">
      <img src="IMG/profil.jpg" alt="Profil">
    </a>
  </div>
</header>
