@extends("layouts.shipyard.admin")

@section("content")

@foreach ($albumGroups as $normality => $albums)
<x-shipyard.app.section
    :title="$normality ? 'Albumy' : 'Pozostałe albumy'"
    :icon="model_icon('albums')"
    inner-class="grid but-mobile-down"
    inner-style="--col-count: 3;"
>
    @foreach ($albums as $album)
    <x-shipyard.app.card
        class="album-card interactive"
        style="--album-color: {{ $album->color }}77;"
        inner-class="flex right middle nowrap"
        onclick="openAlbum({{ $album->id }})"
    >
        <img src="{{ $album->image }}"
            alt="{{ $album->name }}"
            class="album small"
        />
        <div class="flex down no-gap">
            <h3>{{ $album->name }}</h3>
            <small class="ghost">{{ $album->years }}</small>
        </div>

    </x-shipyard.app.card>
    @endforeach
</x-shipyard.app.section>
@endforeach

@endsection

@section ("prepends")
<script>
function reuseModal() {
    loader.classList.remove("hidden");
    modal.classList.remove("hidden");
    card.classList.add("hidden");
    card.classList.add("flex", "down", "middle");
}

function openAlbum(album_id) {
    reuseModal();
    fetchComponent(
        `#modal .loader`,
        `/album-data/${album_id}`,
        {},
        [
            [`#modal-card`, `html`],
        ],
        () => {
            card.classList.remove("hidden");
        },
    );
}

function openSong(song_id) {
    reuseModal();
    fetchComponent(
        `#modal .loader`,
        `/song-data/${song_id}`,
        {},
        [
            [`#modal-card`, `html`],
        ],
        () => {
            card.classList.remove("hidden");
        },
    );
}
</script>
@endsection
