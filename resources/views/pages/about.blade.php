@extends("layouts.shipyard.admin")
@section("title", "O mnie")
@section("subtitle", "Hydrophilia")

@section("content")

<x-shipyard::app.section>
    <img src="{{ asset("media/hydrophilia.svg") }}" alt="Logo" class="about-logo" />
    <p>
        Hydrophilia jest moim dużym projektem muzycznym, obejmujący całkiem długi okres mojego życia. Głównie tworzę w nim własne utwory i gram na wielu instrumentach, łącząc materiał inspirowany hard-rockiem, jazzem i elektroniką.
    </p>
    <p>
        Pierwsze próby pisania własnych piosenek pojawiły się u mnie w wieku około 10 lat. Moje pierwsze utwory nie były specjalnie polotne, ale i tak tworzą część mojej kariery. Niestety prawie wszystkie utwory z tamtych czasów zaginęły. To, co się zachowało, prezentuję tutaj.
    </p>
    <p>
        Dorastając, zacząłem korzystać z nowych technologii i nowego oprogramowania. Jakość rosła razem ze mną i nawet pomimo tego, że nie mogłem się zdecydować nad jedną nazwą projektu, nie powstrzymywało mnie to przed komponowaniem. Próbowałem też inkorporować więcej elementów muzyki na żywo, na przykład instrumenty dęte.
    </p>
    <p>
        Na tej stronie znajdziesz dorobek, stan obecny i plany związane z moją pracą. Niestety czas wolny znika w równie zatrważającym tempie, co przypływ lat, wobec czego liczba nowych utworów ciągle maleje. Mimo to nadal mam nadzieję, że projekt potrwa dalej...
    </p>
</x-shipyard::app.section>

<div class="flex right center middle">
    <x-shipyard::ui.button
        label="Przeglądaj utwory"
        icon="disc"
        :action="route('home')"
        class="primary"
    />
</div>

@endsection
