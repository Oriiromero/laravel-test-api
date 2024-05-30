<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use App\Filters\V1\InvoicesFilter;

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
}
