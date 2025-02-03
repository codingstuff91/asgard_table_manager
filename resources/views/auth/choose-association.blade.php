<x-guest-layout>
    <h1 class="text-xl text-white text-center pt-4 mb-4">Choisissez une association / club</h1>

    @foreach($associations as $asso)
        <x-association-card :association="$asso"/>
    @endforeach
</x-guest-layout>
