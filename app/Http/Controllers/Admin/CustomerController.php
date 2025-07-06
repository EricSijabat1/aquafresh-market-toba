<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders')->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load('orders.orderItems');
        return view('admin.customers.show', compact('customer'));
    }
}
