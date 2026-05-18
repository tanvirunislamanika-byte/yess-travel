<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6 display-block']) }}>
   

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="py-5 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

            {{ $content }}
        </div>
    </div>
</div>
