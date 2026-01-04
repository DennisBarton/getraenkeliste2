<?php
$site_name = "Übersicht";
include ("./includes/header.php");
?>

<?php
// Assume $pdo is your existing PDO connection

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $personEntry = personEntryQuery($pdo, $id, 0);
  $personEntryPerDate = personEntryQuery($pdo, $id, 1);
  $person = getPersonQuery($pdo, $id);
  $personTotal = personTotalQuery($pdo, $id);
}

print_r($personEntry);

print("<br>");
print("<br>");

print_r($personEntryPerDate);

print("<br>");
print("<br>");

print_r($person);

print("<br>");
print("<br>");

print_r($personTotal);
?>












<?php
include ("./includes/footer.php");
?>
