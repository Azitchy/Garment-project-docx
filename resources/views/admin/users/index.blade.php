@extends('layouts.admin')

@section('content')
    @php($activeTab = $activeTab ?? request()->query('tab', 'create-user'))
    @php($canManage = $canManageUsers ?? false)
    @php($locked = ! $canManage)
    @php($permissionValues = old('permissions', $selectedPermissions ?? []))

    <div class="page-head">
        <div>
            <h1>Permission Management</h1>
            <p>Create users, assign roles, and pick permissions with check marks.</p>
        </div>
        <div class="updated">Admin / Permissions</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <section class="card" id="create-user">
            <div class="section-title">
                <h2>Create User</h2>
                <span class="pill">{{ $canManage ? ($formMode === 'edit' ? 'Update access' : 'New user') : 'Read only' }}</span>
            </div>

            <form action="{{ $canManage ? ($formMode === 'edit' ? route('admin.users.update', $userRecord) : route('admin.users.store')) : '#' }}" method="POST">
                @csrf
                @if ($formMode === 'edit' && $canManage)
                    @method('PUT')
                @endif

                <div class="content-grid">
                    <div class="field">
                        <label for="name">Full Name</label>
                        <input id="name" name="name" value="{{ old('name', $userRecord->name) }}" @disabled($locked) required>
                        @error('name')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $userRecord->email) }}" @disabled($locked) required>
                        @error('email')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="field-input" style="width:100%;padding:14px 16px;border:1px solid var(--line);background:#f8fafc;border-radius:14px;" @disabled($locked)>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', $userRecord->role ?? 'staff') === $role)>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="password">{{ $formMode === 'edit' ? 'New Password' : 'Password' }}</label>
                        <input id="password" name="password" type="password" @disabled($locked) {{ $formMode === 'create' ? 'required' : '' }}>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" @disabled($locked) {{ $formMode === 'create' ? 'required' : '' }}>
                    </div>
                </div>

                <section class="card" style="margin:18px 0;box-shadow:none;background:#f8fafc;">
                    <div class="section-title">
                        <h2>Permissions</h2>
                        <span class="pill">Assign access rights</span>
                    </div>

                    <div class="content-grid">
                        @foreach ($permissionsCatalog as $permission => $label)
                            <label class="card" style="box-shadow:none;padding:14px;display:flex;gap:10px;align-items:flex-start;">
                                <input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked(in_array($permission, $permissionValues, true)) style="margin-top:4px;" @disabled($locked)>
                                <span>
                                    <strong>{{ $label }}</strong>
                                    <div class="subtle">{{ $permission }}</div>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('permissions')<div class="error">{{ $message }}</div>@enderror
                </section>

                <div class="form-actions">
                    @if ($canManage)
                        <button class="button primary" type="submit">{{ $formMode === 'edit' ? 'Update User' : 'Create User' }}</button>
                        @if ($formMode === 'edit')
                            <a class="button secondary" href="{{ route('admin.users.index', ['tab' => 'users']) }}#users">Cancel</a>
                        @endif
                    @else
                        <span class="subtle">Form is locked until `user_management_access` is granted.</span>
                    @endif
                </div>
            </form>
        </section>

        <section class="card" id="roles">
            <div class="section-title">
                <h2>Role Guide</h2>
                <span class="pill">Quick reference</span>
            </div>
            <div class="list">
                <div class="activity"><div><strong>Admin</strong><div class="subtle">Full access to everything, including user management.</div></div></div>
                <div class="activity"><div><strong>Manager</strong><div class="subtle">Operational access across modules and reports.</div></div></div>
                <div class="activity"><div><strong>Accountant</strong><div class="subtle">Finance-focused access for income, expenses, AP, AR, and reports.</div></div></div>
                <div class="activity"><div><strong>HR</strong><div class="subtle">Employee, attendance, leave, and payroll access.</div></div></div>
                <div class="activity"><div><strong>Sales / Inventory / Staff</strong><div class="subtle">Restricted access based on daily job scope.</div></div></div>
            </div>
        </section>
    </section>

    <section class="content-grid" style="margin-bottom:18px;">
        <section class="card" id="catalog">
            <div class="section-title">
                <h2>Permission Catalog</h2>
                <span class="pill">{{ count($permissionsCatalog) }} permissions</span>
            </div>
            <div class="content-grid">
                @foreach ($permissionsCatalog as $permission => $label)
                    <div class="card" style="box-shadow:none;padding:14px;background:#f8fafc;">
                        <strong>{{ $label }}</strong>
                        <div class="subtle">{{ $permission }}</div>
                    </div>
                @endforeach
            </div>
        </section>
    </section>

    <section class="card" id="users">
        <div class="section-title">
            <h2>Existing Users</h2>
            <span class="pill">Current access list</span>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $existingUser)
                    <tr>
                        <td><strong>{{ $existingUser->name }}</strong></td>
                        <td>{{ $existingUser->email }}</td>
                        <td>{{ ucfirst($existingUser->role ?? 'staff') }}</td>
                        <td>
                            @forelse (($existingUser->permissions ?? []) as $permission)
                                <span class="pill" style="display:inline-block;margin:2px 4px 2px 0;">{{ str_replace('_', ' ', $permission) }}</span>
                            @empty
                                <span class="subtle">No extra permissions</span>
                            @endforelse
                        </td>
                        <td>
                            @if ($canManage)
                                <a class="button secondary" href="{{ route('admin.users.edit', ['user' => $existingUser, 'tab' => 'create-user']) }}">Edit</a>
                                <form action="{{ route('admin.users.destroy', $existingUser) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button secondary" type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            @else
                                <span class="subtle">View only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:18px;">
            {{ $users->links() }}
        </div>
    </section>
@endsection
