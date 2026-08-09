<?php

declare(strict_types=1);

namespace App\Livewire\AuditLogs;

use App\Models\AuditLog;
use App\Traits\WithToast;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    use WithToast;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $eventFilter = '';

    #[Url(except: '')]
    public string $dateFrom = '';

    #[Url(except: '')]
    public string $dateTo = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', AuditLog::class);
    }

    public function updated($property): void
    {
        if (\in_array($property, ['search', 'eventFilter', 'dateFrom', 'dateTo'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'eventFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<AuditLog>
     */
    #[Computed]
    public function auditLogs(): LengthAwarePaginator
    {
        return AuditLog::with('user')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('auditable_type', 'ilike', '%' . $this->search . '%')
                        ->orWhere('ip_address', 'ilike', '%' . $this->search . '%')
                        ->orWhere('message', 'ilike', '%' . $this->search . '%')
                        ->orWhereHas('user', function (Builder $uq) {
                            $uq->where('first_name', 'ilike', '%' . $this->search . '%')
                                ->orWhere('last_name', 'ilike', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->eventFilter, fn (Builder $q) => $q->where('event', $this->eventFilter))
            ->when($this->dateFrom, fn (Builder $q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn (Builder $q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(20);
    }

    public function render()
    {
        return view('livewire.audit-logs.index');
    }
}