<?php
header('Content-Type: application/json');

// Przykładowe dane dla wykresów
$data = [
    "week" => [120, 200, 150, 80, 170, 90, 110],
    "month" => array_map(fn() => rand(50, 200), range(1, 30)),
    "year" => array_map(fn() => rand(500, 5000), range(1, 12)),
];

echo json_encode($data);
?>
