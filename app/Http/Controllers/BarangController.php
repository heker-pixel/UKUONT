<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        $columns = ['name', 'description', 'quantity', 'price']; 
        return view('barang.index', compact('barang', 'columns'));
    }
    

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:barang,name',            
            'description' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);
    
        Barang::create($request->all());
    
        return redirect()->route('barang.index')
                        ->with('success', 'Barang "<b>' . $request->name . '</b>" created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
    
        $request->validate([
            'name' => 'required|unique:barang,name,' . $barang->id,
            'description' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);
    
        $oldValues = $barang->getAttributes();
    
        $barang->update($request->all());
    
        $changedFieldsDetails = [];
    
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $oldValues) && $oldValues[$key] != $value) {
                if ($key == 'name') {
                    $changedFieldsDetails[] = "Name changed from '<b>{$oldValues[$key]}</b>' to '<b>{$value}</b>'";
                } elseif ($key == 'description') {
                    $changedFieldsDetails[] = "Description changed";
                } elseif ($key == 'quantity') {
                    $changedFieldsDetails[] = "Quantity changed from '<b>{$oldValues[$key]}</b>' to '<b>{$value}</b>'";
                } elseif ($key == 'price') {
                    $changedFieldsDetails[] = "Price changed from '<b>{$oldValues[$key]}</b>' to '<b>{$value}</b>'";
                } else {
                    $changedFieldsDetails[] = ucfirst($key) . " changed";
                }
            }
        }
    
        $changedFieldsMessage = implode(', ', $changedFieldsDetails);
    
        return redirect()->route('barang.index')
                         ->with('success', 'Barang "<b>' . $request->name . '</b>" updated successfully. Changes: ' . $changedFieldsMessage);
    }
    
    
    public function destroy(Barang $barang)
    {
        $itemName = $barang->name;
        $barang->delete();
        return redirect()->route('barang.index')
                         ->with('success', 'Barang "<b>' . $itemName . '</b>" deleted successfully.');
    }
}
