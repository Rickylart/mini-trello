<x-layouts::app :title="__('Tasks - ' . $project->name)">
    <livewire:tasks :project="$project" />
</x-layouts::app>
