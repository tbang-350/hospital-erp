<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create(int $patient)
    {
        return view('invoices.create', ['patientId' => $patient]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|size:3',
        ]);

        $subtotal = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $tax = round($subtotal * 0.0, 2); // placeholder tax
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'patient_id' => $data['patient_id'],
            'status' => 'issued',
            'currency' => $data['currency'] ?? 'TZS',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'issued_date' => now(),
        ]);

        foreach ($data['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $lineTotal,
            ]);
        }

        return redirect()->route('patients.show', $invoice->patient_id)->with('success', 'Invoice created');
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);
        
        $query = Invoice::with('patient');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        $invoices = $query->orderBy($sortField, $sortDirection)->paginate($perPage);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient','items']);
        return view('invoices.show', compact('invoice'));
    }
}
