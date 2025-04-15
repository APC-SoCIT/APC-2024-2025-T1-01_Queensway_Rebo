<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_length = floatval($_POST["room_length"]);
    $room_width = floatval($_POST["room_width"]);
    $tile_length = floatval($_POST["tile_length"]);
    $tile_width = floatval($_POST["tile_width"]);
    
    if ($room_length > 0 && $room_width > 0 && $tile_length > 0 && $tile_width > 0) {
        // Calculate total room area
        $room_area = $room_length * $room_width;
        
        // Calculate area of one tile
        $tile_area = $tile_length * $tile_width;
        
        // Calculate required tiles without wastage
        $tiles_needed = ceil($room_area / $tile_area);
        
        // Add 10% extra for wastage
        $tiles_with_wastage = ceil($tiles_needed * 1.1);
        
        $result = "You need approximately $tiles_with_wastage tiles.";
    } else {
        $result = "Please enter valid dimensions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tile Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Tile Calculator</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Tile Calculator</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Room Length (meters):</label>
                            <input type="number" step="0.01" name="room_length" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room Width (meters):</label>
                            <input type="number" step="0.01" name="room_width" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tile Length (meters):</label>
                            <input type="number" step="0.01" name="tile_length" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tile Width (meters):</label>
                            <input type="number" step="0.01" name="tile_width" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Calculate</button>
                    </form>
                    <?php if (isset($result)) { echo "<p class='mt-3 text-center'><strong>$result</strong></p>"; } ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p>&copy; 2025 Tile Calculator. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>