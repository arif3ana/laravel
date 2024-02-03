<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function login(Request $request) 
    {
        $request->validate([
            'username_or_email' => "required",
            'password' => "required"
        ]);
        
        $loginField = filter_var($request->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username'; // validasi menentukan apakah email atau username

        $credentials = [
            $loginField => $request->username_or_email,
            'password' => $request->password,
        ];

        // $user = User::where($loginField, $request->username_or_email)->first();
        

        if(Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken(Str::random(10))->plainTextToken;
            $profile = $user->profile;
            // $role = $user->getRoleNames();

            $response =  response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'data' => [
                    'username' => $user->username,
                    'company_name' => $user->company_name,
                    'email' => $user->email,
                    'img_path' => $profile->img_path ?? null,
                    'reset_password' => $user->reset_password,
                    // 'roles' => $role[0],
                ],
            ], 200);

            //aithenticasi kirim cookie langsung dari sini 
            $response->cookie('tokenId', $token, 0, '/', 'localhost', false, false);
            return $response;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter a valid  ' . $loginField . ' and password !!',
            ], 401);
        }
    }

    public function InvoiceTable(Request $request)
    {
        // $user = $request->user();
        $userInvoices = Invoice::with('invoice_item');

        $filterInvoiceDate = $request->input('date');
        if ($filterInvoiceDate) {
            $userInvoices->whereDate('created_at', $filterInvoiceDate);
        }

        $filterStatus = $request->input('status');
        if ($filterStatus) {
            $userInvoices->filterStatus($filterStatus);
        }

        // search
        $searchInvoiceCode = $request->input('search');
        if ($searchInvoiceCode) {
            $userInvoices->where('invoice_code', 'like', '%' . $searchInvoiceCode . '%');
        }

        // pagination 
        $userInvoice = $userInvoices->limit(5)->latest();
        $perPage =  5;
        $userInvoicePaginate = $userInvoice->paginate($perPage);

        $response = [
            'perPage' => $userInvoicePaginate->perPage(),
            'total_data' => $userInvoicePaginate->total(),
            'total_pages' => $userInvoicePaginate->lastPage(),
            'current_page' => $userInvoicePaginate->currentPage(),
            'from' => $userInvoicePaginate->firstItem(),
            'to' => $userInvoicePaginate->lastItem(),
            'data' => [],
        ];

        foreach ($userInvoicePaginate as $item) {
            $response['data'][] = [
                'id' => $item->id,
                'user_service_id' => $item->user_service_id,
                'invoice_code' => $item->invoice_code,
                'due_date' => $item->due_date,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'invoice_item' => $item->invoice_item->map(function ($invoiceItem) {
                    return [
                        'id' => $invoiceItem->id,
                        'invoice_id' => $invoiceItem->invoice_id,
                        'item_name' => $invoiceItem->item_name,
                        'price' => $invoiceItem->price,
                    ];
                })->toArray(),
            ];
        }

        return response()->json([
            'httpStatus' => 200,
            'status' => 'success',
            'message' => 'succes di tampilkan',
            'data' => $response
        ], 200);

    }

    public function InvoiceList(Request $request)
    {
        $user = $request->user();
        $userInvoices = Invoice::with('invoice_item')->where('user_id', $user->id);

        $filterInvoiceDate = $request->input('date');
        if ($filterInvoiceDate) {
            $userInvoices->whereDate('created_at', $filterInvoiceDate);
        }

        $filterStatus = $request->input('status');
        if ($filterStatus) {
            $userInvoices->filterStatus($filterStatus);
        }

        // search
        $searchInvoiceCode = $request->input('search');
        if ($searchInvoiceCode) {
            $userInvoices->where('invoice_code', 'like', '%' . $searchInvoiceCode . '%');
        }

        // pagination 
        $userInvoice = $userInvoices->limit(5)->latest();
        $perPage =  5;
        $userInvoicePaginate = $userInvoice->paginate($perPage);

        $response = [
            'perPage' => $userInvoicePaginate->perPage(),
            'total_data' => $userInvoicePaginate->total(),
            'total_pages' => $userInvoicePaginate->lastPage(),
            'current_page' => $userInvoicePaginate->currentPage(),
            'from' => $userInvoicePaginate->firstItem(),
            'to' => $userInvoicePaginate->lastItem(),
            'data' => [],
        ];

        foreach ($userInvoicePaginate as $item) {
            $response['data'][] = [
                'id' => $item->id,
                'user_service_id' => $item->user_service_id,
                'invoice_code' => $item->invoice_code,
                'due_date' => $item->due_date,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'invoice_item' => $item->invoice_item->map(function ($invoiceItem) {
                    return [
                        'id' => $invoiceItem->id,
                        'invoice_id' => $invoiceItem->invoice_id,
                        'item_name' => $invoiceItem->item_name,
                        'price' => $invoiceItem->price,
                    ];
                })->toArray(),
            ];
        }

        return response()->json([
            'httpStatus' => 200,
            'status' => 'success',
            'message' => 'succes di tampilkan',
            'data' => $response
        ], 200);
    }
}
