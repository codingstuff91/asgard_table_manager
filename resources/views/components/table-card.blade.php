<div class="bg-gray-200 game-table w-full rounded-lg mt-2" data-category="{{ $table->category_id }}">
    <x-table-card-header
        :table="$table"
        :color="$color"
    />

    <x-table-card-body :table="$table"/>
</div>

