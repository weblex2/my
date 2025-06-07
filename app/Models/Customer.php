<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "customers";

    protected $guarded = ['id'];

    public function customer_address()  {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function company()  {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function primaryAddress() {
        return $this->belongsTo(CustomerAddress::class, 'primary_address');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function homeAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'HOME');
    }

    public function invoiceAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'INVC');
    }

    public function assd()
    {
        return $this->hasOne(CustomerAssd::class, 'customer_id', 'id');
    }

    public function preferredAddress()
    {
        // Priorisiert HOME, dann INVC
        return $this->hasOne(CustomerAddress::class)
            ->whereIn('type', ['HOME', 'INVC'])
            ->orderByRaw("FIELD(type, 'HOME', 'INVC')");
    }

    public static function countByStatus($status)
    {
        return static::where('status', $status)->count();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // Optional: Scopes für spezifische Stati
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Gibt ein Array zurück, das E-Mail-Adressen den Kunden-IDs zuordnet
     *
     * @return array Array im Format ['email' => 'id']
     */
    public static function getEmailToIdMap(): array
    {
        try {
            return self::select('id', 'email')
                ->get()
                ->pluck('id', 'email')
                ->toArray();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fehler beim Abrufen der E-Mail-zu-ID-Zuordnung', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Gibt die Anzahl der Datensätze pro Monat für die letzten 6 Monate als Array zurück.
     * Schlüssel sind Monatsnamen (z. B. 'January'), auch Monate ohne Datensätze sind enthalten (mit count = 0).
     *
     * @return array Format: ['January' => count, 'February' => count, ...]
     */
    public static function getRecordCountByYearMonthLastSixMonths(): array
    {
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $now = now()->startOfMonth();

        // Hole die Daten aus der Datenbank
        $results = self::selectRaw("
                MONTHNAME(created_at) AS month_name,
                COUNT(*) AS record_count
            ")
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByDesc('created_at')
            ->get()
            ->pluck('record_count', 'month_name')
            ->toArray();

        // Generiere alle Monate der letzten 6 Monate
        $allMonths = [];
        $currentMonth = Carbon::parse($now);
        for ($i = 0; $i < 6; $i++) {
            $monthName = $currentMonth->format('F'); // Vollständiger Monatsname (z. B. January)
            $allMonths[$monthName] = $results[$monthName] ?? 0;
            $currentMonth->subMonth();
        }

        // Sortiere nach Monatsreihenfolge (absteigend)
        $monthOrder = array_reverse(array_slice(array_map(fn($i) => Carbon::createFromDate(2025, $i, 1)->format('F'), range(1, 12)), -6, 6));
        $sortedMonths = array_fill_keys($monthOrder, 0);
        foreach ($allMonths as $month => $count) {
            $sortedMonths[$month] = $count;
        }

        return $sortedMonths;
    }

}
