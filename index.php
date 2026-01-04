<?php
$site_name = "Übersicht";
include ("./includes/header.php");
#echo "<meta http-equiv='refresh' content='0, URL=abrechnung_anzeigen.php?date=today'>";
?>

<?php
// Assume $pdo is your existing PDO connection

$sql = "
    SELECT
        p.id,
        p.last_name,
        p.first_name
    FROM persons p
    WHERE EXISTS (
        SELECT 1
        FROM transactions t
        WHERE t.person_id = p.id
          AND t.paid = 0
    )
    ORDER BY
        p.last_name ASC,
        p.first_name ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
?>




<div class="container text-center">
  <div class="row row-cols-auto">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col m-1 bg-primary text-white" onclick="location.href='person.php?id=<?= (int)$row['id'] ?>'">
            <?= htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') ?><br>
            <?= htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endwhile; ?>
        <div class="col m-1 my-auto" onclick="location.href='new_transaction.php'">
          <img src="assets/plus.svg" width=30px alt="Neuer Eintrag">
        </div>
  </div>
</div>








<?php
include ("./includes/footer.php");
?>
