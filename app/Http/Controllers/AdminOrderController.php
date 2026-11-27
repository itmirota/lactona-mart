<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'items',
            'statusHistories.user',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'Menunggu Konfirmasi',
                    'Diproses',
                    'Dikirim',
                    'Selesai',
                    'Dibatalkan',
                ]),
            ],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            // Hindari membuat riwayat jika status tidak berubah
            if ($oldStatus === $newStatus) {
                return;
            }

            $order->update([
                'status' => $newStatus,
            ]);

            ActivityLogger::log(
                'updated',
                'Pesanan',
                "Mengubah status pesanan {$order->order_number} dari {$oldStatus} menjadi {$newStatus}",
                $order,
                [
                    'old' => [
                        'status' => $oldStatus,
                    ],
                    'new' => [
                        'status' => $newStatus,
                    ],
                ]
            );

            // Simpan riwayat perubahan
            $order->statusHistories()->create([
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            ActivityLogger::log(
                'deleted',
                'Pesanan',
                "Menghapus pesanan {$order->order_number}",
                $order,
                [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                ]
            );

            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }
}
