<?php
include 'functions.php';
secure();
require 'reusable/conn.php';

$companiesQuery = "SELECT companies.*, COUNT(jobs.id) AS job_count
                   FROM companies
                   LEFT JOIN jobs ON jobs.company_id = companies.id
                   GROUP BY companies.id
                   ORDER BY companies.name";

$companies = mysqli_query($conn, $companiesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Companies</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'reusable/nav.php'; ?>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <h1 class="display-4 mt-5 mb-3">Companies</h1>
        <p class="text-muted">Review and maintain the list of employers in the tracker.</p>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <?php get_message(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th scope="col">Company</th>
                <th scope="col">Industry</th>
                <th scope="col">Website</th>
                <th scope="col">Jobs</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($companies && mysqli_num_rows($companies) > 0): ?>
                <?php while ($company = mysqli_fetch_assoc($companies)): ?>
                  <tr>
                    <td><?= htmlspecialchars($company['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($company['industry'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                      <?php if (!empty($company['website'])): ?>
                        <a href="<?= htmlspecialchars($company['website'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                          <?= htmlspecialchars($company['website'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                      <?php else: ?>
                        <span class="text-muted">N/A</span>
                      <?php endif; ?>
                    </td>
                    <td><?= (int) $company['job_count']; ?></td>
                    <td class="text-end">
                      <form action="deleteCompany.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this company?');">
                        <input type="hidden" name="id" value="<?= (int) $company['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">No companies found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
