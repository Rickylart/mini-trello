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

    //**Save method to add a new task
    //* Validates the input data for name
    //* Creates a new task associated with the current project and assigns it the next priority
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

    //**Edit method to load task data for editing
    //* Sets the editingId and loads the task name into the component state
    public function edit(Task $task): void
    {
        $this->editingId = $task->id;
        $this->name = $task->name;
    }

    //**Update method to save changes to an existing task
    //* Validates the input data for name
    //* Updates the task with the new data
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

    //**CancelEdit method to reset the component state
    //* Resets the editingId and clears the name field
    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('name');
    }

    //**Delete method to remove a task
    //* Deletes the task and resets the editing state if the deleted task was being edited
    //* Re-sequences the priorities of the remaining tasks

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

    //**Reorder method to update task priorities based on the new order
    //* Accepts an array of ordered task IDs and updates their priorities accordingly
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->project->tasks()->where('id', (int) $id)->update(['priority' => $index + 1]);
        }
    }

    //**Render method to display the tasks
    //* Retrieves the tasks for the current project and passes them to the view
    public function render()
    {
        return view('livewire.tasks', [
            'tasks' => $this->project->tasks()->orderBy('priority')->get(),
        ]);
    }
}
