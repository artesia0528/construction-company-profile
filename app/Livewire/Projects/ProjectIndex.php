<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectIndex extends Component
{
    use WithPagination;

    #[Url(as: 'category', except: '', history: true)]
    public string $category = '';

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    #[Computed]
    public function projects(): LengthAwarePaginator
    {
        return Project::query()
            ->published()
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->latestCompleted()
            ->paginate(9)
            ->withQueryString();
    }

    /**
     * @return Collection<int, string>
     */
    #[Computed]
    public function categories(): Collection
    {
        return Project::query()
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    public function render(): View
    {
        return view('livewire.projects.project-index');
    }
}
