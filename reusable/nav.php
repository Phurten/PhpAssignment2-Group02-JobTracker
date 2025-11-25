<header class="header-navbar">
  <nav class="header-nav-flex">
    <ul class="header-menu">
      <li><a class="header-link" href="jobs.php">Jobs</a></li>
      <li><a class="header-link" href="companies.php">Companies</a></li>
      <li><a class="header-link" href="addJob.php">Add Job</a></li>
      <?php if (function_exists('is_admin') && is_admin()): ?>
        <li><a class="header-link" href="users.php">Users</a></li>
      <?php endif; ?>
    </ul>
    <a class="header-link header-logout-link" href="logout.php">Logout</a>
  </nav>
</header>
