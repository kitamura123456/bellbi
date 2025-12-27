<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard')->with('status', '会社情報が登録されていません。');
        }

        $shopId = $request->input('shop_id');
        $status = $request->input('status');
        $shops = $company->shops()->where('delete_flg', 0)->get();

        $query = Order::where('delete_flg', 0)
            ->whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)
                  ->where('delete_flg', 0);
            })
            ->with(['shop', 'user', 'orderItems.product']);

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('company.orders.index', compact('company', 'orders', 'shops', 'shopId', 'status'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // 注文が自社のショップのものか確認
        $shop = $company->shops()->find($order->shop_id);
        if (!$shop) {
            abort(403);
        }

        $order->load(['shop', 'user', 'orderItems.product']);

        return view('company.orders.show', compact('company', 'order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // 注文が自社のショップのものか確認
        $shop = $company->shops()->find($order->shop_id);
        if (!$shop) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:1,2,3,4,9'],
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('company.orders.show', $order)->with('status', '注文ステータスを更新しました。');
    }

    public function markAsViewed(Request $request, Order $order)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 注文が自社のショップのものか確認
        $shop = $company->shops()->find($order->shop_id);
        if (!$shop) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $order->viewed_at = now();
        $order->save();

        return response()->json(['success' => true]);
    }

    public function markMultipleAsViewed(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'order_ids' => ['required', 'array'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
        ]);

        $orders = Order::whereIn('id', $validated['order_ids'])
            ->whereHas('shop', function($q) use ($company) {
                $q->where('company_id', $company->id)->where('delete_flg', 0);
            })
            ->get();

        foreach ($orders as $order) {
            $order->viewed_at = now();
            $order->save();
        }

        return response()->json(['success' => true, 'count' => $orders->count()]);
    }
}

