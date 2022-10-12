<?php

namespace App\Models;

use App\Enums\ExtensionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The public path of qr code.
     * 
     * @var string
     */
    public const QR_CODE_PATH = "images/qr_codes/";

    /**
     * The public path of receipt.
     * 
     * @var string
     */
    public const RECEIPT_PATH = "images/receipts/";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_no';

    /**
     * The "type" of the primary key ID.
     * Illuminate\Support\Str::uuid()->toString()
     * has a type of string.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Bootstrap the model and its traits.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Create the primary key UUID.
         */
        static::creating(function ($reservations) {
            $reservations->{$reservations->getKeyName()} = Str::uuid()->toString();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'accommodation_id',
        'package_id',
        'user_id',
        'status_id',
        'no_of_people',
        'amount_to_pay',
        'mode_of_payment',
        'reserved_date',
        'qr_code_path',
        'receipt_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'no_of_people' => 'integer',
        'amount_to_pay' => 'integer',
        'reserved_date' => 'date',
    ];

    /**
     * Addons that belong to the Reservation.
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class)
            ->withTimestamps()
            ->withPivot(['quantity']);
    }

    /**
     * Get the Accommodation that owns the Reservation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    /**
     * Get the Package that owns the Reservation.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the User that owns the Reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Status that owns the Reservation.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Returns the path of the qr code given its transaction_no.\
     * This path is use to get the directory and file name of the qr code.\
     * This is also the path to be saved in the database.
     * 
     * @param string $transaction_no Name of the file.
     * @return string filepath of the qr code.
     */
    public static function getQrCodeFilepathFor(string $transaction_no): string {
        return Reservation::QR_CODE_PATH . $transaction_no . '.png';
    }

    /**
     * Returns the path of the receipt given its transaction_no.\
     * This path is use to get the directory and file name of the receipt.\
     * This is also the path to be saved in the database.
     * 
     * @param string $transaction_no Name of the file.
     * @return string filepath of the receipt.
     */
    public static function getReceiptFilepathFor(string $transaction_no): string {
        return Reservation::RECEIPT_PATH . $transaction_no . '.pdf';
    }

    /**
     * Reservation cancellation must be 2 weeks prior the reservation date.
     * 
     * @return bool whether the `reserved_date` meets the policy for cancellation.
     */
    public function isCancelable(): bool {
        return $this->reserved_date > Carbon::parse('+2 weeks');
    }

    public function isExtensionOpen(): bool {
        return $this->extension_status === ExtensionStatus::open->value;
    }

    public function isExtensionRequested(): bool {
        return $this->extension_status === ExtensionStatus::confirming->value
            || $this->extension_status === ExtensionStatus::approved->value;
    }
}
