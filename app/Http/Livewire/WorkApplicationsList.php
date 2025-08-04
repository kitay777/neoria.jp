<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Application;
use App\Models\Work;

class WorkApplicationsList extends Component
{
    use WithPagination;

    public Work $work;

    protected $paginationTheme = 'tailwind';

    public function mount(Work $work)
    {
        $this->work = $work;
    }

    public function render()
    {
        $applications = Application::with('user')
            ->where('work_id', $this->work->id)
            ->latest()
            ->get()
            ->unique('user_id')
            ->values();

        $perPage = 4;
        $page = request()->get('page', 1);
        $sliced = $applications->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $applications->count(),
            $perPage,
            $page,
            ['pageName' => 'page', 'path' => request()->url()]
        );

        return view('livewire.work-applications-list', [
            'latestApplications' => $paginated,
        ]);
    }
}
