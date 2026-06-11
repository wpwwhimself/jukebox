@props([
    "album",
])

<img src="{{ $album->image }}"
    alt="{{ $album->name }}"
    class="album"
/>

<div class="album-meta flex down middle">
    <h3 style="color: {{ $album->color }};">{{ $album->name }}</h3>
    <small class="ghost">{{ $album->years }}</small>
    @if ($album->description) <p>{{ $album->description }}</p> @endif
</div>

<ol class="song-list">
    @foreach ($album->songs as $song)
    <li value="{{ $song->order }}"
        class="interactive shift-right"
        onclick="openSong({{ $song->id }})"
    >
        {{ $song->name }}
    </li>
    @endforeach
</ol>

<div class="flex right spread and-cover">
    <x-shipyard.ui.button
        icon="close"
        label="Zamknij"
        class="tertiary"
        action="none"
        onclick="closeModal()"
    />
</div>

