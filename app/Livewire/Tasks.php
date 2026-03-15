<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Tasks extends Component
{
    public string $name = '';
    public string $project_id = '';
    public ?int $editingId = null;

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $maxPriority = Task::max('priority') ?? 0;

        Task::create([
            'name' => $this->name,
            'project_id' => $this->project_id,
            'priority' => $maxPriority + 1,
        ]);

        $this->reset('name', 'project_id');
    }

    public function edit(Task $task): void
    {
        $this->editingId = $task->id;
        $this->name = $task->name;
        $this->project_id = (string) $task->project_id;
    }

    public function update(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task = Task::findOrFail($this->editingId);
        $task->update([
            'name' => $this->name,
            'project_id' => $this->project_id,
        ]);

        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('name', 'project_id');
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
            'tasks' => Task::with('project')->orderBy('priority')->get(),
            'projects' => Auth::user()->projects()->orderBy('name')->get(),
        ]);
    }
}
        // ]);
    // }
// }
