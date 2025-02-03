<div class="bg-gray-200 px-4 py-2 w-full rounded-lg mt-2">
    <h2 class="text-xl text-black text-center my-2 font-extrabold">{{ $association->name }} - {{ $association->city }}
        - {{ $association->department }}</h2>

    <h3 class="text-lg text-black my-2">Description</h3>
    <p class="text-sm text-justify text-black">{{ $association->description }}</p>

    <x-secondary-button class="mt-2 w-full text-center">
        <a href="{{ route('association.select', $association->slug) }}">
            Choisir
        </a>
    </x-secondary-button>
</div>

