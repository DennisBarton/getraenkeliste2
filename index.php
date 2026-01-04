<?php
$site_name = "Übersicht";
include ("./includes/header.php");
?>

<?php
// Assume $pdo is your existing PDO connection

$active = isset($_GET['newEntry']) && $_GET['newEntry'] == 1 ? FALSE : TRUE;
if ($active) {
  print("<h1>Aktive Eintr&auml;ge</h1>");
  $tileColor="bg-success";
} else {
  print("<h1>Neuer Eintrag</h1>");
  $tileColor="bg-primary";
}

$rows = personQuery($pdo, $active);
?>




<div class="container-fluid text-center">
  <div class="row row-cols-auto">
    <?php foreach ($rows as $row): ?>
        <div class="col p-3 m-2 <?=$tileColor?> text-white" onclick="location.href='person.php?id=<?= (int)$row['id'] ?>'">
            <h5>
            <?= htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') ?><br>
            <?= htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') ?>
            </h5>
        </div>
    <?php endforeach; ?>
    <?php if ($active) { ?>
        <div class="col p-3 m-2 my-auto" onclick="location.href='index.php?newEntry=1'">
          <img src="assets/plus.svg" width=40px alt="Neuer Eintrag">
        </div>
    <?php } else { ?>
        <div class="col p-3 m-2 bg-primary text-dark" onclick="location.href='person.php'">
            <h5>
            Neue <br> Person
            </h5>
        </div>
    <?php } ?>
  </div>
</div>








<?php
include ("./includes/footer.php");
?>
