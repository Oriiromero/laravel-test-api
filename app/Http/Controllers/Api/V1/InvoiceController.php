<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use App\Filters\V1\InvoicesFilter;
use App\Http\Requests\V1\BulkStoreInvoicesRequest;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $filter= new InvoicesFilter();
        $queryItems = $filter->transform($request);

        if(count($queryItems) == 0)
        {
            return new InvoiceCollection(Invoice::paginate());
        }else
        {
            $invoices = Invoice::where($queryItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function bulkStore(BulkStoreInvoicesRequest $request)
    {
       $bulk = collect($request->all())->map(function ($arr, $key)
       {
        return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
       });

       Invoice::insert($bulk->toArray());
    }
}
