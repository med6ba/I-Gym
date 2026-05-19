<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymRequest;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GymController extends Controller
{
    public function index(): View
    {
        return view('super.gyms.index', [
            'gyms' => Gym::with('primaryAdmin')->withCount(['users', 'members', 'coaches'])->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('super.gyms.create', ['gym' => new Gym]);
    }

    public function store(GymRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $admin = $this->pullAdminData($data);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        DB::transaction(function () use ($data, $admin): void {
            $gym = Gym::create($data);
            $this->syncAdminAccount($gym, $admin);
        });

        return redirect()->route('super.gyms.index')->with('status', __('messages.gym_created'));
    }

    public function edit(Gym $gym): View
    {
        $gym->load('primaryAdmin');

        return view('super.gyms.edit', compact('gym'));
    }

    public function update(GymRequest $request, Gym $gym): RedirectResponse
    {
        $data = $request->validated();
        $admin = $this->pullAdminData($data);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        DB::transaction(function () use ($gym, $data, $admin): void {
            $gym->update($data);
            $this->syncAdminAccount($gym, $admin);
        });

        return redirect()->route('super.gyms.index')->with('status', __('messages.gym_updated'));
    }

    public function destroy(Gym $gym): RedirectResponse
    {
        DB::transaction(function () use ($gym): void {
            $gym->users()->delete();
            $gym->delete();
        });

        return back()->with('status', __('messages.gym_deleted'));
    }

    private function pullAdminData(array &$data): array
    {
        $admin = [
            'name' => $data['admin_name'],
            'email' => $data['admin_email'],
            'password' => $data['admin_password'] ?? null,
        ];

        unset($data['admin_name'], $data['admin_email'], $data['admin_password']);

        return $admin;
    }

    private function syncAdminAccount(Gym $gym, array $admin): void
    {
        $user = $gym->primaryAdmin()->first();
        $data = [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'phone' => $gym->phone,
            'status' => 'active',
        ];

        if (filled($admin['password'])) {
            $data['password'] = $admin['password'];
        }

        if ($user) {
            $user->update($data);

            return;
        }

        User::create($data + [
            'gym_id' => $gym->id,
            'role' => 'gym_admin',
            'language' => app()->getLocale(),
            'theme' => 'light',
            'currency' => 'MAD',
            'bio' => 'Gym owner account created by the I-Gym super admin.',
        ]);
    }
}
