
<?php if (isset($page, $pageCount) && $pageCount > 0): ?>
<nav aria-label="Page navigation" class="my-4">
  <ul class="pagination fancy-pagination justify-content-center align-items-center flex-wrap gap-2">

    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <?php if ($page <= 1): ?>
        <span class="page-link" aria-disabled="true">
          <i class="bi bi-chevron-left"></i>
          <span class="d-none d-sm-inline ms-1">Previous</span>
        </span>
      <?php else: ?>
        <a
          class="page-link"
          href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page - 1])), ENT_QUOTES, 'UTF-8') ?>"
          aria-label="Previous page"
        >
          <i class="bi bi-chevron-left"></i>
          <span class="d-none d-sm-inline ms-1">Previous</span>
        </a>
      <?php endif; ?>
    </li>

    <?php for ($i = 1; $i <= $pageCount; $i++): ?>
      <?php if (
          $i === 1 ||
          $i === (int) $pageCount ||
          ($i >= $page - 2 && $i <= $page + 2)
      ): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <?php if ($i == $page): ?>
            <span class="page-link" aria-current="page"><?= $i ?></span>
          <?php else: ?>
            <a
              class="page-link"
              href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $i])), ENT_QUOTES, 'UTF-8') ?>"
            ><?= $i ?></a>
          <?php endif; ?>
        </li>

      <?php elseif (
          ($i === 2 && $page > 3) ||
          ($i === $pageCount - 1 && $page < $pageCount - 2)
      ): ?>
        <li class="page-item disabled">
          <span class="page-link dots" aria-hidden="true">…</span>
        </li>
      <?php endif; ?>
    <?php endfor; ?>

    <!-- Next -->
    <li class="page-item <?= $page >= $pageCount ? 'disabled' : '' ?>">
      <?php if ($page >= $pageCount): ?>
        <span class="page-link" aria-disabled="true">
          <span class="d-none d-sm-inline me-1">Next</span>
          <i class="bi bi-chevron-right"></i>
        </span>
      <?php else: ?>
        <a
          class="page-link"
          href="?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['page' => $page + 1])), ENT_QUOTES, 'UTF-8') ?>"
          aria-label="Next page"
        >
          <span class="d-none d-sm-inline me-1">Next</span>
          <i class="bi bi-chevron-right"></i>
        </a>
      <?php endif; ?>
    </li>

  </ul>
</nav>
<?php endif; ?>