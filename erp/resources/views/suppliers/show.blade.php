@extends('layouts.app')

@section('title', 'Supplier Details - ' . $supplier->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Supplier Details</h3>
                    <div>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Suppliers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold" width="30%">Name:</td>
                                    <td>{{ $supplier->name }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Person:</td>
                                    <td>{{ $supplier->contact_person ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Email:</td>
                                    <td>
                                        @if($supplier->email)
                                            <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Phone:</td>
                                    <td>
                                        @if($supplier->phone)
                                            <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Tax ID:</td>
                                    <td>{{ $supplier->tax_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Status:</td>
                                    <td>
                                        @if($supplier->status === 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Created:</td>
                                    <td>{{ $supplier->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Updated:</td>
                                    <td>{{ $supplier->updated_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Additional Information</h5>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Address:</label>
                                <div class="border p-3 bg-light">
                                    @if($supplier->address)
                                        {!! nl2br(e($supplier->address)) !!}
                                    @else
                                        <em class="text-muted">No address provided</em>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Notes:</label>
                                <div class="border p-3 bg-light">
                                    @if($supplier->notes)
                                        {!! nl2br(e($supplier->notes)) !!}
                                    @else
                                        <em class="text-muted">No notes available</em>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Inventory Items -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">
                                Inventory Items 
                                <span class="badge badge-info">{{ $supplier->inventoryItems->count() }}</span>
                            </h5>
                            
                            @if($supplier->inventoryItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Current Stock</th>
                                                <th>Unit Price</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supplier->inventoryItems as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->name }}</strong>
                                                        @if($item->sku)
                                                            <br><small class="text-muted">SKU: {{ $item->sku }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->category ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge {{ $item->quantity <= $item->minimum_stock ? 'badge-danger' : 'badge-success' }}">
                                                            {{ $item->quantity }} {{ $item->unit }}
                                                        </span>
                                                        @if($item->quantity <= $item->minimum_stock)
                                                            <br><small class="text-danger">Low Stock!</small>
                                                        @endif
                                                    </td>
                                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                                    <td>
                                                        @if($item->status === 'active')
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('inventory.show', $item) }}" 
                                                           class="btn btn-sm btn-info" title="View Item">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('inventory.edit', $item) }}" 
                                                           class="btn btn-sm btn-warning" title="Edit Item">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p>No inventory items associated with this supplier.</p>
                                    <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Inventory Item
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection