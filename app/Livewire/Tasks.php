<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class Tasks extends Component
{
    public string $name = '';
    public ?int $editingId = null;

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxPriority = Task::max('priority') ?? 0;

        Task::create([
            'name' => $this->name,
            'priority' => $maxPriority + 1,
        ]);

        $this->reset('name');
    }

    public function edit(Task $task): void
    {
        $this->editingId = $task->id;
        $this->name = $task->name;
    }

    public function update(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($this->editingId);
        $task->update([
            'name' => $this->name,
        ]);

        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('name');
    }

    public function delete(Task $task): void
    {
        $task->delete();

        if ($this->editingId === $task->id) {
            $this->cancelEdit();
        }

        // Re-sequence priorities
        Task::orderBy('priority')->get()->values()->each(function ($t, $index) {
            $t->update(['priority' => $index + 1]);
        });
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Task::where('id', (int) $id)->update(['priority' => $index + 1]);
        }
    }

    public function render()
    {
        return view('livewire.tasks', [
            'tasks' => Task::orderBy('priority')->get(),
        ]);
    }
}
        // ]);
    // }
// }
