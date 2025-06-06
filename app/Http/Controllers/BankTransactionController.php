<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;



class BankTransactionController extends Controller
{
    public function index()
    {
        $transactions = BankTransaction::orderBy('booking_date', 'DESC')->get();
        $headers = Schema::getColumnListing('bank_transactions');
        return view('bank.index', compact('transactions','headers'));
    }

    public function uploadForm()
    {
        return view('bank.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $errors = [];

        foreach ($records as $index => $record) {
            try {
                // Validate and format the dates
                $bookingDate = $this->formatDate($record['Buchungstag'], $index);
                $valueDate = $this->formatDate($record['Valutadatum'], $index);

                if (!$bookingDate || !$valueDate) {
                    throw new \Exception("Invalid date format in record {$index}");
                }

                BankTransaction::create([
                    'id' => hash('sha256', $record['Buchungstag'] . $record['Betrag'] . $record['Valutadatum'] . $record['Verwendungszweck'] . $record['Kontonummer/IBAN'] . $record['BIC (SWIFT-Code)'] . $record['Buchungstext']),
                    'account_number' => $record['Auftragskonto'],
                    'booking_date' => $bookingDate,
                    'value_date' => $valueDate,
                    'booking_text' => $record['Buchungstext'],
                    'purpose' => mb_convert_encoding($record['Verwendungszweck'], 'UTF-8', 'Windows-1252'),
                    'counterparty' => mb_convert_encoding($record['Beguenstigter/Zahlungspflichtiger'], 'UTF-8', 'Windows-1252'),
                    'counterparty_iban' => $record['Kontonummer/IBAN'] ?: null,
                    'counterparty_bic' => $record['BIC (SWIFT-Code)'] ?: null,
                    'amount' => str_replace(',', '.', $record['Betrag']),
                    'currency' => $record['Waehrung'],
                    'info' => $record['Info'],
                    'category' => mb_convert_encoding($record['Kategorie'], 'UTF-8', 'Windows-1252'),
                ]);
            } catch (\Exception $e) {
                // Log error and continue with next record
                $errors[] = "Error in record {$index}: " . $e->getMessage();
                \Log::error("Error importing transaction at record {$index}: " . $e->getMessage());
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', 'Some records could not be imported: ' . implode(', ', $errors));
        }

        return redirect()->back()->with('success', 'CSV file successfully imported');
    }

    private function formatDate(string $date, int $index): ?string
    {
        $date = trim($date);

        // Convert date using strtotime
        $timestamp = strtotime($date);
        if ($timestamp === false) {
            // Log the invalid date
            \Log::warning("Invalid date format at record {$index}: '{$date}'");
            return null;
        }

        // Ensure two-digit years are interpreted as 20xx (not 19xx)
        $year = date('Y', $timestamp);
        if ($year < 2000) {
            // Adjust year to 20xx if it's interpreted as 19xx
            $adjustedDate = date('d.m.Y', strtotime("+100 years", $timestamp));
            $timestamp = strtotime($adjustedDate);
        }

        return date('Y-m-d', $timestamp);
    }
}
