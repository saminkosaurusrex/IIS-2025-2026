<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ShowType;
use Illuminate\Http\Request;

class CrudController extends Controller
{
    protected $modelClass;
    protected $viewPath;
    protected $returnMessage;

    public function index()
    {
        $items = ($this->modelClass)::all();
        return Inertia::render("admin/{$this->viewPath}/Index", [
            $this->viewPath => $items,
        ]);
    }

    public function create(){
        return Inertia::render("admin/{$this->viewPath}/Create");
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:'.$this->viewPath.',name',
        ]);

        ($this->modelClass)::create($request->only('name'));

        return redirect('/'.$this->viewPath)->with('success', "{$this->returnMessage} bol úspešne vytvorený.");
    }

    public function edit($id){
        $item = ($this->modelClass)::findOrFail($id);
        return Inertia::render("admin/{$this->viewPath}/Edit", [
            $this->viewPath => $item
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $item = ($this->modelClass)::findOrFail($id);
        $item->update($request->only('name'));

        return redirect('/'.$this->viewPath)->with('success', "{$this->returnMessage} bol úspešne upravený.");
    }

    public function destroy($id){
        $item = ($this->modelClass)::findOrFail($id);
        $item->delete();
        return redirect('/'.$this->viewPath)->with('success', "{$this->returnMessage} bol úspešne vymazaný.");
    }
}
