@if ($src)
    <audio controls src="{{ $src }}" @style(['width: 100%'])></audio>
@else
    <span>No audio available</span>
@endif
