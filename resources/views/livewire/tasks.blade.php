<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    {{-- Row 1: Form --}}
    <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
        <form wire:submit="{{ $editingId ? 'update' : 'save' }}" class="flex items-end gap-4">
            <div class="flex-1">
                <flux:input wire:model="name" label="Task Name" placeholder="Enter task name" />
            </div>
            @if ($editingId)
                <flux:button type="submit" variant="primary">Update</flux:button>
                <flux:button type="button" wire:click="cancelEdit" variant="ghost">Cancel</flux:button>
            @else
                <flux:button type="submit" variant="primary">Add Task</flux:button>
            @endif
        </form>
    </div>

    {{-- Row 2: Table --}}
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 dark:border-neutral-700">
                <tr>
                    <th class="px-4 py-3 font-medium"></th>
                    <th class="px-4 py-3 font-medium">#</th>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Created</th>
                    <th class="px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody
                x-data
                x-init="
                    Sortable.create($el, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd(evt) {
                            const rows = [...$el.querySelectorAll('tr[data-id]')];
                            const orderedIds = rows.map(r => r.dataset.id);
                            $wire.reorder(orderedIds);
                        }
                    })
                "
            >
                @forelse ($tasks as $task)
                    <tr data-id="{{ $task->id }}" class="border-b border-neutral-200 dark:border-neutral-700" wire:key="task-{{ $task->id }}">
                        <td class="px-4 py-3">
                            <span class="drag-handle cursor-grab active:cursor-grabbing text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-neutral-500">{{ $task->priority }}</td>
                        <td class="px-4 py-3">{{ $task->name }}</td>
                        <td class="px-4 py-3 text-neutral-500">{{ $task->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="ghost" wire:click="edit({{ $task->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $task->id }})" wire:confirm="Are you sure you want to delete this task?">Delete</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-neutral-500">No tasks yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
@endassets
