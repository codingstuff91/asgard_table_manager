<div
    class="flex flex-col items-center bg-slate-600 category text-white p-2 rounded-lg cursor-pointer lg:flex-row sm:justify-center"
    category-id="{{ $id }}"
>
    <img src="{{ $icon }}" class="w-12 h-12 lg:mr-4" alt="">
    <div>
        <h1 class="text-center text-lg font-bold sm:text-xl lg:text-2xl">{{ $table->name }}</h1>
        <p class="text-center text-lg">{{ $table->tables_count }}</p>
    </div>
</div>
