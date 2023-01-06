<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $validated = $request->validate([
            'name' => 'required|min:3|max:255|unique:labels',
            'display' => 'nullable',
            'color' => 'required|regex:/^#([0-9a-f]{6})$/i',
        ],[
            'name.required' => 'A név kitöltése kötelező!',
            'name.min' => 'A név legalább 3 karakter hosszú legyen!',
            'name.max' => 'A név legfeljebb 255 karakter hosszú legyen!',
            'name.unique' => 'A név már foglalt!',
            'color.required' => 'A szín kitöltése kötelező!',
            'color.regex' => 'A szín hexadecimális színkód legyen!',
        ]);

        if(!isset($request->display))
        {
            $validated['display'] = 0;
        }

        Label::create($validated);

        Session::flash('message', 'A címke sikeresen létrehozva!');

        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return view('labels.show', [
            'label' => $label,
            'items' => $label->items()->orderBy("obtained", "desc")->paginate(9),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }
        return view('labels.edit', ['label' => $label]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $validated = $request->validate([
            'name' => ['required', 'min:3', 'max:255', Rule::unique('labels', 'name')->ignore($label)],
            'display' => 'nullable',
            'color' => 'required|regex:/^#([0-9a-f]{6})$/i',
        ],[
            'name.required' => 'A név kitöltése kötelező!',
            'name.min' => 'A név legalább 3 karakter hosszú legyen!',
            'name.max' => 'A név legfeljebb 255 karakter hosszú legyen!',
            'name.unique' => 'A név már foglalt!',
            'color.required' => 'A szín kitöltése kötelező!',
            'color.regex' => 'A szín hexadecimális színkód legyen!',
        ]);

        if(!isset($request->display))
        {
            $validated['display'] = 0;
        }

        $label->update($validated);

        Session::flash('message', 'A címke sikeresen szerkesztve!');

        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        if(!Auth::user()){
            return abort(403, 'Hozzáférés megtadagva: jelentkezz be!');
        }elseif(!Auth::user()->is_admin){
            return abort(403, 'Hozzáférés megtagadva: nincs admin jogosultságod.');
        }

        $label->delete();

        Session::flash('message', 'A címke sikeresen törölve!');
        return redirect()->route('items.index');
    }
}
