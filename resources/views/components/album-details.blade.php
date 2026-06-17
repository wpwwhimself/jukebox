@props([
    "album",
])

<img src="{{ $album->image }}"
    alt="{{ $album->name }}"
    class="album"
/>

<div class="album-meta">
    <div class="flex down middle no-gap">
        <h3>{{ $album->name }}</h3>
        <small class="ghost">{{ $album->years }}</small>
    </div>
    @if ($album->description) <p>{!! $album->description !!}</p> @endif
</div>

<x-shipyard.ui.button
    icon="play"
    label="Odtwórz album"
    class="primary"
    action="none"
    onclick="armPlayer({{ $album->songs->first()->id }});"
/>

<h4>Lista utworów:</h4>
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

