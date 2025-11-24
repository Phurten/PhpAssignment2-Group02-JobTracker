<nav class="sidebar-navbar">
  <ul class="sidebar-menu">
    <li><a class="sidebar-link" href="index.php">Home</a></li>
    <li><a class="sidebar-link" href="jobs.php">Jobs</a></li>
    <li><a class="sidebar-link" href="companies.php">Companies</a></li>
    <li><a class="sidebar-link" href="addJob.php">Add Job</a></li>
    <?php if (function_exists('is_admin') && is_admin()): ?>
      <li><a class="sidebar-link" href="users.php">Users</a></li>
    <?php endif; ?>
    <li><a class="sidebar-link" href="logout.php">Logout</a></li>
  </ul>
</nav>
