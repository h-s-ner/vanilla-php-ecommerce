
<?php if(isset($page,$pageCount)): ?>
<nav aria-label="Page navigation">
  <ul class="pagination my-3 justify-content-center flex-wrap">
    <li class="page-item <?= $page == 1?"disabled":"" ?>"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page-1])) ?>">Previous</a></li>
    <li class="page-item <?= $page == 1?"active":"" ?>"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a></li>
    <?php for($i=2;$i < $pageCount;$i++):?>
    <li class="page-item <?= $page==$i?'active':'' ?>"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a></li>
    <?php endfor;?>
    <li class="page-item <?= $page == $pageCount?"active":"" ?>"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pageCount])) ?>"><?= $pageCount ?></a></li>
    <li class="page-item <?= $page == $pageCount?"disabled":"" ?>"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page+1])) ?>">Next</a></li>
  </ul>
</nav>
<?php endif; ?>