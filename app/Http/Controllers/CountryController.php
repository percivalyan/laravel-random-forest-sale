<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('salesRecords')
            ->orderBy('name')
            ->paginate(15);

        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'code' => ['nullable', 'string', 'size:3', 'unique:countries,code'],
        ]);

        Country::create($validated);

        return redirect()->route('countries.index')
            ->with('success', 'Negara berhasil ditambahkan.');
    }

    public function show(Country $country)
    {
        $country->load('salesRecords.product');

        return view('countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'name')->ignore($country->id),
            ],
            'code' => [
                'nullable',
                'string',
                'size:3',
                Rule::unique('countries', 'code')->ignore($country->id),
            ],
        ]);

        $country->update($validated);

        return redirect()->route('countries.index')
            ->with('success', 'Negara berhasil diperbarui.');
    }

    public function destroy(Country $country)
    {
        if ($country->salesRecords()->exists()) {
            return back()->with(
                'error',
                'Negara tidak dapat dihapus karena masih memiliki data penjualan.'
            );
        }

        $country->delete();

        return redirect()->route('countries.index')
            ->with('success', 'Negara berhasil dihapus.');
    }
}
