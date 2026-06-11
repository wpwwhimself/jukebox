@props([
    "song",
])

<div class="song-meta">
    <div class="flex right middle nowrap">
        <img src="{{ $song->album->image }}"
            alt="{{ $song->album->name }}"
            class="album small"
        />
        <div class="flex down no-gap">
            <h3 style="color: {{ $song->album->color }};">{{ $song->name }}</h3>
            <small class="ghost">{{ $song->released_at?->format("d.m.Y") }}</small>
        </div>
    </div>

    @if ($song->description) <p>{!! $song->description !!}</p> @endif
</div>

<x-shipyard.ui.button
    icon="play"
    label="Odtwórz"
    class="primary"
    action="none"
    onclick="armPlayer({{ $song->id }});"
/>

<div class="flex right spread and-cover">
    <x-shipyard.ui.button
        icon="chevron-left"
        label="Wróć"
        class="tertiary"
        action="none"
        onclick="openAlbum({{ $song->album_id }})"
    />
    <x-shipyard.ui.button
        icon="close"
        label="Zamknij"
        class="tertiary"
        action="none"
        onclick="closeModal()"
    />
</div>

