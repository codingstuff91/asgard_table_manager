<div>
    <button
        class="bg-gray-200 px-4 py-2 rounded-lg"
        wire:click.prevent="open">
        Choisir un jeu
    </button>

    @if($showModal)
        <div id="game-modal" class="fixed left-0 top-0 p-8 bg-slate-500 opacity-1 w-full border-2 border-gray-500">

            <livewire:search-game />

            <button wire:click.prevent="save">Valider</button>
            <button wire:click="close">Annuler</button>
        </div>
    @endif
</div>
