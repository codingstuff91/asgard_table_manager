<x-guest-layout>
    <h1 class="text-xl text-white text-center my-4">Veuillez choisir une association / club</h1>

    @foreach($associations as $asso)
        <x-association-card :association="$asso"/>
    @endforeach
</x-guest-layout>
