
<nav>
  <div class="menu-icon" id="menu-icon-top">
      <i class="bx bx-menu icon"></i>
  </div>
  <div class="sidebar">
    <div class="sidebar-content">
      <ul class="lists">
        <span class="logo-name">
          <b>AdminPanel</b>
          <b class="menu-icon" id="menu-icon-sidebar"><i class="bx bx-x icon"></i></b>
        </span>
        
        <hr>
        <li class="list <?php echo (basename($_SERVER['PHP_SELF'], ".php") == 'dashboard') ? 'active' : ''; ?>">
          <a href="dashboard.php" class="nav-link">
            <i class="bx bx-home-alt icon"></i>
            <span class="link">Dashboard</span>
          </a>
        </li>
        <li class="list  <?php echo (basename($_SERVER['PHP_SELF'], ".php") == 'products') ? 'active' : ''; ?>">
          <a href="products.php" class="nav-link">
            <i class="bx bx-bar-chart-alt-2 icon"></i>
            <span class="link">Ürünler</span>
          </a>
        </li>
        <li class="list <?php echo (basename($_SERVER['PHP_SELF'], ".php") == 'messages') ? 'active' : ''; ?>">
          <a href="messages.php" class="nav-link">
            <i class="bx bx-message-rounded icon"></i>
            <span class="link">Mesajlar</span>
          </a>
        </li>
        <li class="list <?php echo (basename($_SERVER['PHP_SELF'], ".php") == 'orders') ? 'active' : ''; ?>">
          <a href="orders.php" class="nav-link">
            <i class="bx bx-bell icon"></i>
            <span class="link">Siparişler</span>
          </a>
        </li>
      </ul>
      <div class="bottom-content">
        <li class="list <?php echo (basename($_SERVER['PHP_SELF'], ".php") == 'settings') ? 'active' : ''; ?>">
          <a href="settings.php" class="nav-link">
            <i class="bx bx-cog icon"></i>
            <span class="link">Ayarlar</span>
          </a>
        </li>
        <li class="list">
          <a href="../components/logout.php" class="nav-link">
            <i class="bx bx-log-out icon"></i>
            <span class="link">Logout</span>
          </a>
        </li>
      </div>
    </div>
  </div>
</nav>

<script>
  const navBar = document.querySelector("nav"),
        menuBtns = document.querySelectorAll(".menu-icon"),
        overlay = document.querySelector(".overlay"),
        navLinks = document.querySelectorAll('.nav-link');

  menuBtns.forEach((menuBtn) => {
      menuBtn.addEventListener("click", () => {
          navBar.classList.toggle("open");
      });
  });

  overlay.addEventListener("click", () => {
      navBar.classList.remove("open");
  });
</script>
