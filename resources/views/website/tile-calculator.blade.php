@extends('layouts.website')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Tile Calculator</h2>

    <form id="tile-calculator" onsubmit="return false;">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="room_length" class="form-label">Room Length (meters)</label>
                <input type="number" step="0.01" id="room_length" class="form-control" placeholder="e.g. 5" required>
            </div>
            <div class="col-md-6">
                <label for="room_width" class="form-label">Room Width (meters)</label>
                <input type="number" step="0.01" id="room_width" class="form-control" placeholder="e.g. 4" required>
            </div>
            <div class="col-md-6">
                <label for="tile_length" class="form-label">Tile Length (cm)</label>
                <input type="number" step="0.1" id="tile_length" class="form-control" placeholder="e.g. 30" required>
            </div>
            <div class="col-md-6">
                <label for="tile_width" class="form-label">Tile Width (cm)</label>
                <input type="number" step="0.1" id="tile_width" class="form-control" placeholder="e.g. 30" required>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" onclick="calculateTiles()">Calculate</button>
    </form>

    <div class="mt-4" id="tile-result" style="display:none;">
        <h4>Estimated Tiles Needed: <span id="tiles-needed"></span></h4>
        <p><small>(Including 10% wastage)</small></p>
    </div>
</div>

<script>
    function calculateTiles() {
        const roomLength = parseFloat(document.getElementById('room_length').value);
        const roomWidth = parseFloat(document.getElementById('room_width').value);
        const tileLengthCm = parseFloat(document.getElementById('tile_length').value);
        const tileWidthCm = parseFloat(document.getElementById('tile_width').value);

        if (roomLength <= 0 || roomWidth <= 0 || tileLengthCm <= 0 || tileWidthCm <= 0) {
            alert('Please enter positive numbers for all fields.');
            return;
        }

        const tileLengthM = tileLengthCm / 100;
        const tileWidthM = tileWidthCm / 100;
        const roomArea = roomLength * roomWidth;
        const tileArea = tileLengthM * tileWidthM;
        const tilesNeeded = roomArea / tileArea;
        const tilesWithWastage = Math.ceil(tilesNeeded * 1.1);

        document.getElementById('tiles-needed').textContent = tilesWithWastage;
        document.getElementById('tile-result').style.display = 'block';
    }
</script>
@endsection
