<?php
function get_today(){
  return date('Y-m-d');
}


function conv_date ($db_date) {
  $tmp = explode('-', $db_date);
  return $tmp[2].'.'.$tmp[1].'.'.$tmp[0];
}

function run_latex_env ($texfile) {
#  docker run -v /mnt/Abrechnung:/data dockerd_latex_latex-env:latest pdflatex
  return 1;
}

function personQuery($pdo, $isActive) {
  $active = $isActive ? "" : "NOT";
  $sql = "
      SELECT
          p.id,
          p.last_name,
          p.first_name
      FROM persons p
      WHERE ".$active." EXISTS (
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
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPersonQuery($pdo, $id) {
  $sql = "
  SELECT
      p.first_name,
      p.last_name
      FROM persons p
      WHERE p.id = :person_id
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['person_id' => $id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function personEntryQuery($pdo, $id, $group) {
  $sql = "
  SELECT
      y.transaction_date,

      JSON_ARRAYAGG(
          JSON_OBJECT(
              'product_id',     y.product_id,
              'product_name',   y.product_name,
              'total_quantity', y.total_quantity,
              'price',          y.price,
              'total_price',    y.total_price
          )
          ORDER BY y.product_name
      ) AS products,

      SUM(y.total_price) AS date_total

  FROM (
      SELECT
          /* 🔁 switchable date dimension */
          CASE
              WHEN :group_by_date = 1 THEN t.transaction_date
              ELSE NULL
          END AS transaction_date,

          p.id   AS product_id,
          p.name AS product_name,
          p.price,

          SUM(ti.quantity) AS total_quantity,
          SUM(ti.quantity * p.price) AS total_price

      FROM transactions t
      JOIN transaction_items ti ON ti.transaction_id = t.id
      JOIN products p ON p.id = ti.product_id

      WHERE t.paid = 0
        AND t.person_id = :person_id

      GROUP BY
          CASE
              WHEN :group_by_date = 1 THEN t.transaction_date
              ELSE NULL
          END,
          p.id,
          p.name,
          p.price
  ) y

  GROUP BY y.transaction_date
  ORDER BY y.transaction_date;
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'person_id' => $id,
    'group_by_date' => $group
  ]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as &$row) {
    $row['products'] = json_decode($row['products'], true);
  }
  unset($row);
  return $rows;
}


function personTotalQuery($pdo, $id) {
  $sql = "
  SELECT
      COALESCE(SUM(ti.quantity * p.price), 0) AS grand_total
  FROM transactions t
  JOIN transaction_items ti ON ti.transaction_id = t.id
  JOIN products p ON p.id = ti.product_id
  WHERE t.paid = 0
    AND t.person_id = :person_id
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['person_id' => $id]);
  return $stmt->fetchColumn();
}




