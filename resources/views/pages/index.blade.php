@extends("layouts.shipyard.admin")

@section("content")

@foreach ($albumGroups as $normality => $albums)
<x-shipyard::app.section
    :title="$normality ? 'Albumy' : 'Pozostałe albumy'"
    :icon="model_icon('albums')"
    inner-class="grid but-mobile-down stagger-contents"
    inner-style="--col-count: 3;"
>
    @foreach ($albums as $album)
    <x-shipyard::app.card
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
        <x-shipyard::stats.counter
            :rank="$album->songs->count()"
            style="lines"
            label="Liczba utworów"
        />

    </x-shipyard::app.card>
    @endforeach
</x-shipyard::app.section>
@endforeach

@endsection

@section ("prepends")
<script>
// 🪟 modal with data 🪟 //
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
// 🪟 modal with data 🪟 //

// ▶️ player ▶️ //
window.player_data = {
    now_playing: null,
    next_in_queue: null,
};
function findPlayer() {
    const player = document.querySelector("#player");
    const audio = player.querySelector("audio");
    return { player, audio };
}

function armPlayer(song_id) {
    const { player, audio } = findPlayer();
    const loader = player.querySelector(".loader");
    const progress = player.querySelector(".progress-bar");

    loader.classList.remove("hidden");
    progress.classList.add("hidden");
    fetch(`/play/${song_id}`)
        .then(res => res.json())
        .then(data => {
            window.player_data.now_playing = data.id;
            window.player_data.next_in_queue = data.next_id;
            player.querySelector(`.played-song-title`).textContent = data.title;
            player.querySelector(`.played-song-album`).textContent = data.album;
            player.querySelector(`.album`).src = data.album_cover;
            player.querySelector(`.album`).onclick = () => openSong(data.id);
            player.style.color = data.color;
            audio.src = data.file;

            play();
        })
        .catch(err => {
            console.error(err);
            player.querySelector(`.played-song-title`).textContent = "Błąd";
            player.querySelector(`.played-song-album`).textContent = "Nie udało się pobrać utworu.";
            player.style.color = "red";
        })
        .finally(() => {
            loader.classList.add("hidden");
            progress.classList.remove("hidden");
        });

}

function showPlayer(show = true) {
    const { player } = findPlayer();
    player.classList.toggle("visible", show);
}

function updateProgress() {
    const { player, audio } = findPlayer();
    const bar = player.querySelector(`.progress-bar`);

    let currentTimeMinutes = 0;
    let currentTimeSeconds = audio.currentTime.toFixed(0);
    while (currentTimeSeconds > 60) {
        currentTimeMinutes++;
        currentTimeSeconds -= 60;
    }

    bar.style.setProperty(`--progress`, (audio.currentTime / (audio.duration || 1) * 100) + "%");
    bar.querySelector(`.time`).textContent = `${currentTimeMinutes}:${currentTimeSeconds.toString().padStart(2, "0")}`;
}

function play() {
    const { player, audio } = findPlayer();
    showPlayer(true);
    audio.play();
}

function pause() {
    const { audio } = findPlayer();
    audio.pause();
}

function playOrPause() {
    const { audio } = findPlayer();
    if (audio.paused) {
        play();
    } else {
        pause();
    }
}

function stop() {
    const { audio } = findPlayer();
    pause();
    audio.currentTime = 0;
    showPlayer(false);
}

function next() {
    if (!window.player_data.next_in_queue) {
        stop();
        return;
    }
    armPlayer(window.player_data.next_in_queue);
}
// ▶️ player ▶️ //
</script>
@endsection

@section ("appends")
<div id="player" class="card bordered animatable">
    <div class="flex right middle nowrap">
        <x-shipyard::ui.button
            icon="stop"
            class="stop-btn primary"
            action="none"
            onclick="stop()"
        />
        <x-shipyard::ui.button
            icon="play-pause"
            class="play-btn primary"
            action="none"
            onclick="playOrPause()"
        />
        <img src="" alt="Odtwarzany utwór"
            class="album small interactive halo"
        >
        <div class="song-meta flex down no-gap">
            <strong class="played-song-title">—</strong>
            <span class="played-song-album ghost">—</span>
            <x-shipyard::app.loader horizontal />
            <x-shipyard::app.progress-bar progress="0">
                <span class="time">0:00</span>
            </x-shipyard::app.progress-bar>
        </div>
        <x-shipyard::ui.button
            icon="skip-next"
            class="next-btn primary"
            action="none"
            onclick="next()"
        />
    </div>
    <audio
        onended="next();"
        ontimeupdate="updateProgress()"
    >
        <source src="" type="audio/mpeg">
    </audio>
</div>
@endsection
