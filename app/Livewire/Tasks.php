<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class Tasks extends Component
{
    public Project $project;
    public string $name = '';
    public ?int $editingId = null;

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxPriority = $this->project->tasks()->max('priority') ?? 0;

        $this->project->tasks()->create([
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

        $task = $this->project->tasks()->findOrFail($this->editingId);
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
        $this->project->tasks()->orderBy('priority')->get()->values()->each(function ($t, $index) {
            $t->update(['priority' => $index + 1]);
        });
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->project->tasks()->where('id', (int) $id)->update(['priority' => $index + 1]);
        }
    }

    public function render()
    {
        return view('livewire.tasks', [
            'tasks' => $this->project->tasks()->orderBy('priority')->get(),
        ]);
    }
}
