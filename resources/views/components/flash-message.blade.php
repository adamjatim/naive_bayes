@props(['colour'])
@props(['message'])
@props(['position'])

@if ($message)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show"
        class="{{ $colour }} {{ $position }} text-white py-4 px-6 rounded-xl text-md transition duration-500 ease-in-out transform hover:opacity-75">
        <p>{{ $message }}</p>
    </div>
@endif
