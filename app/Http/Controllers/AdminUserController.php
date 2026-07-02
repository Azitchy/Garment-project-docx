<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private const ROLES = ['admin', 'manager', 'accountant', 'hr', 'sales', 'inventory', 'staff'];

    private const PERMISSIONS = [
        'dashboard_access' => 'Dashboard access',
        'master_data_access' => 'Master Data access',
        'orders_access' => 'Orders access',
        'inventory_access' => 'Inventory access',
        'hr_payroll_access' => 'HR & Payroll access',
        'finance_access' => 'Finance access',
        'reports_access' => 'Reports access',
        'user_management_access' => 'User management access',
        'inventory_edit' => 'Inventory edit permission',
        'finance_edit' => 'Finance edit permission',
        'hr_edit' => 'HR edit permission',
        'order_edit' => 'Order edit permission',
    ];

    public function index(): View
    {
        $canManageUsers = auth()->user()?->hasPermission('user_management_access') ?? false;
        $activeTab = request()->query('tab', 'create-user');

        return view('admin.users.index', $this->formData(new User([
            'role' => 'staff',
            'permissions' => [],
        ]), $activeTab) + [
            'users' => User::query()->latest()->paginate(10)->withQueryString(),
            'formMode' => 'create',
            'canManageUsers' => $canManageUsers,
            'activeTab' => $activeTab,
        ]);
    }

    public function create(): View
    {
        return $this->index();
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeUserManagement();

        User::create($this->validateUser($request));

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $this->authorizeUserManagement();
        $activeTab = request()->query('tab', 'create-user');

        return view('admin.users.index', $this->formData($user, $activeTab) + [
            'users' => User::query()->latest()->paginate(10)->withQueryString(),
            'formMode' => 'edit',
            'canManageUsers' => true,
            'activeTab' => $activeTab,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUserManagement();

        $data = $this->validateUser($request, true);
        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeUserManagement();

        abort_if(auth()->id() === $user->id, 422, 'You cannot delete your own account.');

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User deleted successfully.');
    }

    private function authorizeUserManagement(): void
    {
        abort_unless(auth()->user()?->hasPermission('user_management_access'), 403);
    }

    private function formData(User $user, string $activeTab = 'overview'): array
    {
        return [
            'sidebarSection' => 'permissions',
            'sidebarSubsection' => $activeTab,
            'userRecord' => $user,
            'roles' => self::ROLES,
            'permissionsCatalog' => self::PERMISSIONS,
            'selectedPermissions' => $user->permissions ?? [],
        ];
    }

    private function validateUser(Request $request, bool $isUpdate = false): array
    {
        $passwordRules = $isUpdate
            ? ['nullable', 'string', 'min:6', 'confirmed']
            : ['required', 'string', 'min:6', 'confirmed'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($request->route('user')?->getKey()),
            ],
            'password' => $passwordRules,
            'role' => ['required', Rule::in(self::ROLES)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_keys(self::PERMISSIONS))],
        ]);

        $data['permissions'] = array_values($request->input('permissions', []));

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }
}
