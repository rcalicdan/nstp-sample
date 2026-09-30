<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\AuditLog;
use App\Models\CsvUpload;
use App\Models\SchoolYear;
use App\Services\DashboardAnalyticsService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Async;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    protected DashboardAnalyticsService $analyticsService;

    #[Url(except: '')]
    public string $schoolYear = '';

    public function boot(DashboardAnalyticsService $analyticsService): void
    {
        $this->analyticsService = $analyticsService;
    }

    public function updatedSchoolYear(): void
    {
        $this->dispatchChartsUpdate();
    }

    public function clearFilters(): void
    {
        $this->reset('schoolYear');
        $this->dispatchChartsUpdate();
    }

    #[Async]
    public function refreshStatsCache(): void
    {
        cache()->forget('nstp_stats_cwts');
        cache()->forget('nstp_stats_rotc');
        cache()->forget('nstp_stats_lts');
    }

    /**
     * @return array<string, int>
     */
    #[Computed]
    public function kpis(): array
    {
        return $this->analyticsService->getAnalytics($this->selectedSchoolYearId)['kpis'];
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function chartAnalytics(): array
    {
        return $this->analyticsService->getAnalytics($this->selectedSchoolYearId);
    }

    /**
     * @return Collection<int, CsvUpload>
     */
    #[Computed]
    public function recentUploads(): Collection
    {
        return CsvUpload::with(['user', 'schoolYear'])
            ->latest()
            ->take(5)
            ->get()
        ;
    }

    /**
     * @return Collection<int, AuditLog>
     */
    #[Computed]
    public function recentAuditLogs(): Collection
    {
        return AuditLog::with('user')
            ->latest()
            ->take(6)
            ->get()
        ;
    }

    /**
     * @return Collection<int, SchoolYear>
     */
    #[Computed]
    public function availableSchoolYears(): Collection
    {
        return SchoolYear::orderByDesc('start_year')->get();
    }

    #[Computed]
    public function selectedSchoolYearId(): ?int
    {
        if (! $this->schoolYear || ! str_contains($this->schoolYear, '-')) {
            return null;
        }

        [$start, $end] = explode('-', $this->schoolYear);

        return SchoolYear::where('start_year', (int) $start)
            ->where('end_year', (int) $end)
            ->value('id')
        ;
    }

    private function dispatchChartsUpdate(): void
    {
        $analytics = $this->chartAnalytics;

        $this->dispatch('update-chart-component-donut', [
            'series' => $analytics['componentDistribution']['series'],
        ]);

        $this->dispatch('update-chart-gender-bar', [
            'series' => $analytics['genderByComponent']['series'],
        ]);

        $this->dispatch('update-chart-college-bar', [
            'options' => ['xaxis' => ['categories' => $analytics['collegeDistribution']['categories']]],
            'series' => [['name' => 'Students', 'data' => $analytics['collegeDistribution']['series']]],
        ]);

        $this->dispatch('update-chart-enrollment-trend', [
            'options' => ['xaxis' => ['categories' => $analytics['enrollmentTrends']['categories']]],
            'series' => $analytics['enrollmentTrends']['series'],
        ]);

        $this->dispatch('update-chart-courses-bar', [
            'options' => ['xaxis' => ['categories' => $analytics['topCourses']['categories']]],
            'series' => [['name' => 'Trainees', 'data' => $analytics['topCourses']['series']]],
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
