<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
                <div class="text-sm font-medium text-neutral-500">Total Projects</div>
                <div class="mt-2 text-3xl font-bold">{{ auth()->user()->projects()->count() }}</div>
                <div class="mt-3">
                    <flux:button variant="ghost" size="sm" href="{{ route('projects') }}" wire:navigate>View Projects →</flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
