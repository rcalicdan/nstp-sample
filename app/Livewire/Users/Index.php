<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\Role;
use App\Forms\Users\CreateForm;
use App\Forms\Users\UpdateForm;
use App\Models\User;
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

    public CreateForm $createForm;

    public UpdateForm $updateForm;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $selectedRole = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', User::class);
    }

    public function updated($property): void
    {
        if (\in_array($property, ['search', 'selectedRole'], true)) {
            $this->resetPage();
        }
    }

    public function store(): void
    {
        Gate::authorize('create', User::class);

        $this->createForm->store();
        $this->toast('success', 'New system user created successfully.');
        $this->dispatch('close-modal', 'create-user-modal');
    }

    public function editUser(User $user): void
    {
        Gate::authorize('update', $user);

        $this->updateForm->setUser($user);
        $this->resetValidation();
        $this->dispatch('open-modal', 'edit-user-modal');
    }

    public function update(): void
    {
        Gate::authorize('update', $this->updateForm->targetUser);

        $this->updateForm->update();
        $this->toast('success', 'User account updated successfully.');
        $this->dispatch('close-modal', 'edit-user-modal');
    }

    public function toggleActive(User $user): void
    {
        Gate::authorize('toggleActive', $user);

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        $this->toast('success', "User account {$status}.");
    }

    public function deleteUser(User $user): void
    {
        Gate::authorize('delete', $user);

        $user->delete();
        $this->toast('success', 'User account permanently removed.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'selectedRole']);
        $this->resetPage();
    }

    /**
     * @return list<object{value: string, label: string}>
     */
    #[Computed]
    public function assignableRoleOptions(): array
    {
        return array_map(function (Role $role) {
            return (object) [
                'value' => $role->value,
                'label' => $role->label(),
            ];
        }, auth()->user()->assignableRoles());
    }

    /**
     * @return LengthAwarePaginator<User>
     */
    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('first_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                        ->orWhere('email', 'ilike', '%' . $this->search . '%')
                    ;
                });
            })
            ->when($this->selectedRole, fn (Builder $q) => $q->where('role', $this->selectedRole))
            ->orderBy('last_name')
            ->paginate(15)
        ;
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
