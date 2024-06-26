<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Filters\V1\CustomersFilter;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filter= new CustomersFilter();
        $filterItems = $filter->transform($request);

        $includeInvoices = $request->query('includeInvoices');

        $customers = Customer::where($filterItems);
        
        if( $includeInvoices)
        {
            $customers = $customers->with('invoices');
        }
        
        return new CustomerCollection($customers->paginate()->appends($request->query()));

    }

    public function show(Customer $customer)
    {

        $includeInvoices = request()->query('includeInvoices');

        if($includeInvoices) 
        {
            return new CustomerResource($customer->loadMissing('invoices'));
        }

        return new CustomerResource($customer);
    }

    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }


    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }
}
