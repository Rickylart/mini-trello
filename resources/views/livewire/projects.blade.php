<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    {{-- Form --}}
    <div class="rounded-xl border border-neutral-200 p-6 dark:border-neutral-700">
        <form wire:submit="{{ $editingId ? 'update' : 'save' }}" class="flex flex-col gap-4">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <flux:input wire:model="name" label="Project Name" placeholder="Enter project name" />
                </div>
            </div>
            <div>
                <flux:textarea wire:model="description" label="Description" placeholder="Enter project description" rows="3" />
            </div>
            <div class="flex gap-2">
                @if ($editingId)
                    <flux:button type="submit" variant="primary">Update</flux:button>
                    <flux:button type="button" wire:click="cancelEdit" variant="ghost">Cancel</flux:button>
                @else
                    <flux:button type="submit" variant="primary">Add Project</flux:button>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="relative flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-neutral-200 dark:border-neutral-700">
                <tr>
                    <th class="px-4 py-3 font-medium">#</th>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Description</th>
                    <th class="px-4 py-3 font-medium">Created</th>
                    <th class="px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr class="border-b border-neutral-200 dark:border-neutral-700" wire:key="project-{{ $project->id }}">
                        <td class="px-4 py-3 text-neutral-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $project->name }}</td>
                        <td class="px-4 py-3 text-neutral-500">{{ Str::limit($project->description, 50) }}</td>
                        <td class="px-4 py-3 text-neutral-500">{{ $project->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="primary" href="{{ route('projects.tasks', $project) }}" wire:navigate>Tasks</flux:button>
                                <flux:button size="sm" variant="ghost" wire:click="edit({{ $project->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $project->id }})" wire:confirm="Are you sure you want to delete this project?">Delete</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-neutral-500">No projects yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
