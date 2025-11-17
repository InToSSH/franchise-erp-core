<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Branch;
use App\Domain\Admin\Resources\BranchOptionResource;
use App\Domain\Admin\Resources\BranchResource;
use App\Domain\Sales\Actions\CreateOrder;
use App\Domain\Sales\Actions\UpdateOrder;
use App\Domain\Sales\Models\Order;
use App\Domain\Sales\Requests\OrderRequest;
use App\Domain\Sales\Resources\OrderResource;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (
            $user->cannot('view-any', Order::class)
            && $user->cannot('view-all', Order::class)
        ) {
            abort(403);
        }


        $query = Order::query();
        if ($user->can('sales.orders.view-all')) {
            $usersBranches = Branch::orderBy('name')->get();
        } else {
            $usersBranches = $user->branches()->orderBy('name')->get();
            $query->whereIn('branch_id', $usersBranches->pluck('id'));
        }

        $orders = $this->getDatatableResults(
            $query->with(['branch', 'createdBy', 'approvedBy', 'items', 'items.product']),
            'increment_number:DESC',
            ['increment_number', 'custom_number', 'status', 'branch.name', 'createdBy.name'],
            ['increment_number', 'custom_number', 'note', 'status', 'branch.name', 'createdBy.name', 'approvedBy.name', 'created_at']
        );

        return Inertia::render('Sales/Orders/Index', [
            'orders' => OrderResource::collection($orders),
            'branches' => BranchOptionResource::collection($usersBranches)->toArray(request()),
        ]);
    }

    public function store(OrderRequest $request, CreateOrder $createOrder)
    {
        $this->authorize('create', Order::class);

        $createOrder->execute($request->validated());
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
    }

    public function update(OrderRequest $request, Order $order, UpdateOrder $updateOrder)
    {
        $this->authorize('update', $order);

        $updateOrder->execute($order, $request->validated());
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

//        $order->delete();
//
//        return response()->json();
    }
}
