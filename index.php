<?php
require 'db.php';

$lang = $_GET['lang'] ?? 'rus';
$columns = [
    'rus' => ['c_name_rus', 'c_descr_rus'],
    'eng' => ['c_name_eng', 'c_descr_eng'],
    'ger' => ['c_name_ger', 'c_descr_ger']
];
list($name_col, $descr_col) = $columns[$lang];

$query = "
    SELECT gr.$name_col AS glob_region, c.$name_col AS country, c.$descr_col AS country_descr,
           r.$name_col AS region, r.$descr_col AS region_descr, ct.$name_col AS city, ct.$descr_col AS city_descr
    FROM glob_region gr
    JOIN country c ON gr.id = c.glob_region_id
    LEFT JOIN region r ON c.id = r.r_country_id
    LEFT JOIN city ct ON c.id = ct.c_country_id AND (ct.c_region_id = r.id OR ct.c_region_id = 0)
    WHERE gr.gr_name_rus = 'Европа'
    ORDER BY gr.id, c.id, r.id, ct.id;
";

$stmt = $pdo->query($query);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$current_country = $current_region = null;

echo '<ul>';
foreach ($data as $row) {
    if ($current_country !== $row['country']) {
        $current_country = $row['country'];
        echo "<li data-descr=\"{$row['country_descr']}\">{$current_country}<ul>";
    }
    if ($current_region !== $row['region'] && $row['region']) {
        $current_region = $row['region'];
        echo "<li data-descr=\"{$row['region_descr']}\">{$current_region}<ul>";
    }
    if ($row['city']) {
        echo "<li data-descr=\"{$row['city_descr']}\">{$row['city']}</li>";
    }
    if ($current_region && !$row['region']) {
        echo '</ul></li>';
        $current_region = null;
    }
}
echo '</ul></li></ul>';
?>
<script src="scripts.js"></script>
