<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index(): View
    {
        return view('pages.pageWorkspace.admin-withdrawals', [
            'pendingWithdrawals' => Withdrawal::with('user:id,name,username')
                ->where('status', Withdrawal::STATUS_PENDING)
                ->oldest()
                ->oldest('id')
                ->get(),
            'paidWithdrawals' => Withdrawal::with('user:id,name,username')
                ->where('status', Withdrawal::STATUS_PAID)
                ->latest('updated_at')
                ->latest('id')
                ->limit(20)
                ->get(),
        ]);
    }

    public function markAsPaid(Withdrawal $withdrawal): RedirectResponse
    {
        return $this->resolvePending($withdrawal, Withdrawal::STATUS_PAID, 'marked as paid');
    }

    public function reject(Withdrawal $withdrawal): RedirectResponse
    {
        return $this->resolvePending($withdrawal, Withdrawal::STATUS_REJECTED, 'rejected');
    }

    private function resolvePending(Withdrawal $withdrawal, string $newStatus, string $message): RedirectResponse
    {
        $updated = DB::transaction(function () use ($withdrawal, $newStatus) {
            $locked = Withdrawal::whereKey($withdrawal->id)->lockForUpdate()->first();

            if ($locked->status !== Withdrawal::STATUS_PENDING) {
                return false;
            }

            $locked->update(['status' => $newStatus]);

            return true;
        });

        if (! $updated) {
            return back()->withErrors(['withdrawal' => 'Only pending withdrawals can be updated.']);
        }

        return back()->with('success', 'Withdrawal #'.$withdrawal->id.' '.$message.'.');
    }
}
