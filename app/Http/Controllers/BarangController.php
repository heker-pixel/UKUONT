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
    
        // Validate the request
        $request->validate([
            'name' => 'required|unique:barang,name,' . $barang->id,
            'description' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);
    
        // Store the old values
        $oldValues = $barang->getAttributes();
    
        // Update the model with the new values
        $barang->update($request->all());
    
        // Determine which fields were changed
        $changedFieldsDetails = [];
        $hasChanges = false;
    
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $oldValues) && $oldValues[$key] != $value) {
                $hasChanges = true;
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
    
        // Create the message based on whether there were changes
        if ($hasChanges) {
            $changedFieldsMessage = implode(', ', $changedFieldsDetails);
            $successMessage = 'Barang "<b>' . $request->name . '</b>" updated successfully. Changes: ' . $changedFieldsMessage;
        } else {
            $successMessage = 'No changes were made to Barang "<b>' . $request->name . '</b>".';
        }
    
        return redirect()->route('barang.index')
                         ->with('success', $successMessage);
    }
    
    
    
    public function destroy(Barang $barang)
    {
        $itemName = $barang->name;
        $barang->delete();
        return redirect()->route('barang.index')
                         ->with('success', 'Barang "<b>' . $itemName . '</b>" deleted successfully.');
    }
}
