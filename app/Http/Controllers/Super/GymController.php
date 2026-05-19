<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymRequest;
use App\Models\Gym;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GymController extends Controller
{
    public function index(): View
    {
        return view('super.gyms.index', [
            'gyms' => Gym::withCount(['users', 'members', 'coaches'])->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('super.gyms.create', ['gym' => new Gym]);
    }

    public function store(GymRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        Gym::create($data);

        return redirect()->route('super.gyms.index')->with('status', __('messages.gym_created'));
    }

    public function edit(Gym $gym): View
    {
        return view('super.gyms.edit', compact('gym'));
    }

    public function update(GymRequest $request, Gym $gym): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $gym->update($data);

        return redirect()->route('super.gyms.index')->with('status', __('messages.gym_updated'));
    }

    public function destroy(Gym $gym): RedirectResponse
    {
        $gym->delete();

        return back()->with('status', __('messages.gym_deleted'));
    }
}
