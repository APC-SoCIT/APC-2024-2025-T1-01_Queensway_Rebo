@extends('layouts.website')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Installation Videos</h2>

    @php
        $titles = [
            'QUEENSWAY WPC Timber Tube Installation',
            'QUEENSWAY Rebo Bamboo Decking Installation',
            'QUEENSWAY Vinyl Tiles Installation',
            'QUEENSWAY WPC Bamboo Charcoal Veneer Board - Stone series Installation',
            'QUEENSWAY WPC Flutted Wall Panel Installation',
            'QUEENSWAY REBO Parametric Wall Partition Installation',
            'QUEENSWAY PVC Laminate Ceiling Panel Installation',
            'QUEENSWAY REBO Bamboo Slats with Aluminum Brackets Installation',
            'QUEENSWAY Parametric Wall Cladding Installation',
        ];
    @endphp

    <div class="mb-4">
        <label for="videoSelector" class="form-label">Select an Installation Video:</label>
        <select id="videoSelector" class="form-select">
            <option value="" selected disabled>-- Choose a video --</option>
            @foreach ($titles as $title)
                @php
                    // Create slugified filename to match storage path
                    $file = $title . '.mp4';
                    $url = asset('storage/videos/' . $file);
                @endphp
                <option value="{{ $url }}">{{ $title }}</option>
            @endforeach
        </select>
    </div>

    <div id="videoPlayer" style="display: none;">
        <h4 id="videoTitle" class="mb-3"></h4>
        <video id="mainVideo" controls width="100%" height="auto">
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>

<script>
    const videoSelector = document.getElementById('videoSelector');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoElement = document.getElementById('mainVideo');
    const videoTitle = document.getElementById('videoTitle');

    videoSelector.addEventListener('change', function () {
        const selectedTitle = this.options[this.selectedIndex].text;
        const src = this.value;

        if (src) {
            videoElement.querySelector('source').src = src;
            videoElement.load();
            videoTitle.textContent = selectedTitle;
            videoPlayer.style.display = 'block';
        }
    });
</script>
@endsection
