<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CheckRentalBillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:check-bills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa status sewa aktif, penerbitan tagihan berkala H-7 dan perhitungan denda keterlambatan H+1.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $noticeDays = (int) setting('rent_bill_notice_days', 7);
        $lateFeeEnabled = (bool) setting('late_fee_enabled', true);
        $graceDays = (int) setting('late_fee_grace_days', 1);
        $dailyLateFee = (float) setting('late_fee_daily_amount', 20000);

        $this->info('=== AUDIT TAGIHAN SEWA & DENDA BERKALA ===');
        $this->line("Notifikasi Tagihan: H-{$noticeDays} Hari");
        $this->line('Aturan Denda: '.($lateFeeEnabled ? "Aktif (Toleransi H+{$graceDays}, Rp ".number_format($dailyLateFee, 0, ',', '.').'/hari)' : 'Nonaktif'));
        $this->newLine();

        $activeBookings = Booking::with(['tenant', 'kost', 'room'])
            ->where('status', 'active')
            ->whereNotNull('end_date')
            ->get();

        if ($activeBookings->isEmpty()) {
            $this->info('Tidak ada data sewa aktif.');

            return self::SUCCESS;
        }

        $rows = [];
        $dueCount = 0;
        $overdueCount = 0;

        foreach ($activeBookings as $booking) {
            $isDue = $booking->isDueForRenewal();
            $isOverdue = $booking->isOverdue();
            $overdueDays = $booking->getOverdueDays();
            $lateFeeDays = $booking->getLateFeeDays();
            $lateFee = $booking->getLateFee();
            $calc = $booking->calculateRenewalTotal(1);

            if ($isOverdue) {
                $statusText = "Terlambat ({$overdueDays} hari)";
                $overdueCount++;
            } elseif ($isDue) {
                $daysRemaining = max(0, (int) now()->startOfDay()->diffInDays($booking->end_date->startOfDay(), false));
                $statusText = "Tagihan Aktif (Sisa {$daysRemaining} hari)";
                $dueCount++;
            } else {
                $statusText = 'Aman (Aktif)';
            }

            if ($isDue) {
                $rows[] = [
                    '#BK-'.str_pad($booking->id, 5, '0', STR_PAD_LEFT),
                    $booking->tenant_name ?: ($booking->tenant?->name ?? 'Penyewa'),
                    $booking->kost->name.' - '.($booking->room?->name ?? 'Standard'),
                    $booking->end_date->format('d M Y'),
                    $statusText,
                    $lateFeeDays > 0 ? 'Rp '.number_format($lateFee, 0, ',', '.')." ({$lateFeeDays} hr)" : '-',
                    'Rp '.number_format($calc['grand_total'], 0, ',', '.'),
                ];
            }
        }

        if (count($rows) > 0) {
            $this->table(
                ['Booking ID', 'Penghuni', 'Kamar Kos', 'Jatuh Tempo', 'Status Tagihan', 'Denda Terhitung', 'Total Tagihan (1 Bln)'],
                $rows
            );
            $this->newLine();
            $this->info("Total tagihan aktif: {$dueCount} kamar | Total sewa terlambat/denda: {$overdueCount} kamar.");
        } else {
            $this->info('Semua penghuni saat ini belum memasuki masa jatuh tempo tagihan.');
        }

        return self::SUCCESS;
    }
}
