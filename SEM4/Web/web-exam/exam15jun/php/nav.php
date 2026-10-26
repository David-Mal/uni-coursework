<?php // nav.php — include at top of every page (after config.php) ?>
<nav>
  <a href="index.php">Login<?= $me ? ' (' . htmlspecialchars($me) . ')' : '' ?></a>
  <a href="all_classes.php">Upcoming Classes</a>
  <?php if ($me): ?>
  <a href="my_classes.php">My Classes</a>
  <?php endif; ?>
</nav>
