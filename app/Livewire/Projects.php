<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Projects extends Component
{
    public string $name = '';
    public string $description = '';
    public ?int $editingId = null;

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->projects()->create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset('name', 'description');
    }

    public function edit(Project $project): void
    {
        $this->authorize('update', $project);

        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
    }

    public function update(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project = Project::findOrFail($this->editingId);
        $this->authorize('update', $project);

        $project->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('name', 'description');
    }

    public function delete(Project $project): void
    {
        $this->authorize('delete', $project);

        $project->delete();

        if ($this->editingId === $project->id) {
            $this->cancelEdit();
        }
    }

    public function render()
    {
        return view('livewire.projects', [
            'projects' => Auth::user()->projects()->latest()->get(),
        ]);
    }
}
