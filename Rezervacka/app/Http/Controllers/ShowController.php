<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Show;
use Inertia\Inertia;
use App\Models\ShowType;
use App\Models\Performer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowController extends Controller
{
    public function show($show_name){
        $shows = Show::with(['show_type', 'events.hall'])
            ->whereHas('show_type', function ($query) use ($show_name) {
                $query->where('name', $show_name);
            })
            ->get();

        if ($shows->isEmpty()) {
            abort(404, 'Žiadne predstavenia podľa typu neboli nájdené');
        }

        return Inertia::render('ShowType',['shows' => $shows]);
    }

    public function index(){
        $shows = Show::with('show_type')->oldest()->get();
        $shows->map(function ($show) {
            $show->type = $show->show_type->name;
            $show->tags = $show->tags()->pluck('name')->join(', ');
            $show->performers = $show->performers()->pluck('name')->toArray();
            return $show;
        });
        return Inertia::render('admin/shows/Index', compact('shows'));
    }

    public function create(){
        $show_types = ShowType::all();
        $tags = Tag::all();
        $performers = Performer::all();
        return Inertia::render('admin/shows/Create', compact(['show_types', 'tags', 'performers']));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show_types' => 'required|exists:show_types,id',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'performers' => 'required|array',
            'performers.*' => 'exists:performers,id',
            'image' => 'nullable|image|max:10000',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = Storage::url($path);;
        }

        $show = Show::create([
            'name' => $validated['name'],
            'show_type_id' => $validated['show_types'],
            'image' => $validated['image'] ?? null,
        ]);
        $show->show_type()->associate($validated['show_types']);
        $show->tags()->attach($validated['tags']);


        $show->performers()->attach($validated['performers']);

        return redirect('/shows')->with('success', 'Predstavenie úspešne vytvorené.');
    }

    public function edit(Show $show){
        $show->load('show_type', 'tags', 'performers');
        $show_types = ShowType::all();
        $tags = Tag::all();
        $performers = Performer::all();
        return Inertia::render('admin/shows/Edit', compact(['show', 'show_types', 'tags', 'performers']));
    }

    public function update(Request $request, Show $show){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show_types' => 'required|exists:show_types,id',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'performers' => 'required|array',
            'performers.*' => 'exists:performers,id',
            'image' => 'nullable|image|max:10000',
        ]);

        if ($request->hasFile('image')) {
            if ($show->image) {
                $path = str_replace('/storage/', '', $show->image);
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = Storage::url($path);
        } else {
            $validated['image'] = $show->image;
        }
        if ($request->input('delete_image') == 1) {
            if ($show->image) {
                $path = str_replace('/storage/', '', $show->image);
                Storage::disk('public')->delete($path);
            }
            $validated['image'] = null;
        }

        $show->update([
            'name' => $validated['name'],
            'show_type_id' => $validated['show_types'],
            'image' => $validated['image'] ?? null,
        ]);
        $show->show_type()->associate($validated['show_types']);
        $show->tags()->sync($validated['tags']);
        $show->performers()->sync($validated['performers']);

        return redirect('/shows')->with('success', 'Predstavenie úspešne upravené.');
    }

    public function destroy(Show $show){
        if ($show->image) {
            $path = str_replace('/storage/', '', $show->image);
            Storage::disk('public')->delete($path);
        }
        $show->delete();
        return redirect('/shows')->with('success', 'Predstavenie úspešne zmazané.');
    }
}
