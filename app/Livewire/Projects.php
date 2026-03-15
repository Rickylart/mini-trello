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

    //******Save method to add a new project
    //* Validates the input data for name and description
    //* Creates a new project associated with the authenticated user
    // */
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

    //**Edit method to load project data for editing
    //* Checks if the user is authorized to update the project
    //* Sets the editingId and loads the project name and description into the component state
    public function edit(Project $project): void
    {
        $this->authorize('update', $project);

        $this->editingId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
    }

    //**Update method to save changes to an existing project
    //* Validates the input data for name and description
    //* Checks if the user is authorized to update the project
    //* Updates the project with the new data
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

    //**CancelEdit method to reset the component state
    //* Resets the editingId and clears the name and description fields
    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset('name', 'description');
    }

    //**Delete method to remove a project
    //* Checks if the user is authorized to delete the project
    //* Deletes the project and resets the editing state if the deleted project was being edited
    public function delete(Project $project): void
    {
        $this->authorize('delete', $project);

        $project->delete();

        if ($this->editingId === $project->id) {
            $this->cancelEdit();
        }
    }

    //**Render method to display the projects
    //* Retrieves the authenticated user's projects and passes them to the view
    public function render()
    {
        return view('livewire.projects', [
            'projects' => Auth::user()->projects()->latest()->get(),
        ]);
    }
}
