<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-4 rounded-xl">
        <p class="text-neutral-500">Welcome! Go to <a href="{{ route('projects') }}" class="text-blue-500 underline" wire:navigate>Projects</a> to manage your tasks.</p>
    </div>
</x-layouts::app>
