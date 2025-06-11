<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $account_number
 * @property \Illuminate\Support\Carbon $booking_date
 * @property \Illuminate\Support\Carbon $value_date
 * @property string $booking_text
 * @property string $purpose
 * @property string $counterparty
 * @property string|null $counterparty_iban
 * @property string|null $counterparty_bic
 * @property numeric $amount
 * @property string $currency
 * @property string $info
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereBookingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereBookingText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCounterparty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCounterpartyBic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCounterpartyIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankTransaction whereValueDate($value)
 */
	class BankTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BlogComments> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Blog whereUserId($value)
 */
	class Blog extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $blog_id
 * @property int|null $user_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $new_field_1
 * @property string $new_field_2
 * @property int $new_field_3
 * @property-read \App\Models\Blog|null $blog
 * @property-read \App\Models\User|null $comment_user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereBlogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereNewField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereNewField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereNewField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlogComments whereUserId($value)
 */
	class BlogComments extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $url
 * @property string|null $primary_hex
 * @property int $is_visible
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand wherePrimaryHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Brand withoutTrashed()
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property string $event
 * @property string $event_type
 * @property string $event_time_start
 * @property string $event_time_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereEventTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereEventTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Calendar whereUpdatedAt($value)
 */
	class Calendar extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property int $is_visible
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $child
 * @property-read int|null $child_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $address
 * @property string|null $zip
 * @property string|null $city
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $external_id
 * @property int|null $customer_id
 * @property string $type
 * @property string|null $from
 * @property string|null $to
 * @property string|null $subject
 * @property string|null $details
 * @property string|null $contacted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereContactedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id Die ID halt
 * @property int $is_active
 * @property string $name
 * @property string $first_name
 * @property string|null $status
 * @property int|null $company_id
 * @property string $email
 * @property string $phone
 * @property string $website
 * @property string $comments
 * @property string $date_birth
 * @property string $language
 * @property string $external_id
 * @property int $primary_address
 * @property \Illuminate\Support\Carbon $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property string|null $spread
 * @property string|null $arr
 * @property string|null $cm
 * @property string|null $bi
 * @property string|null $solution
 * @property string|null $houses
 * @property string|null $rooms
 * @property string|null $sales_volume
 * @property int|null $user_id
 * @property string $user01
 * @property string $user02
 * @property string $user03
 * @property string $test4
 * @property string $test6
 * @property string $test_7
 * @property string $test_9
 * @property string $test10
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\CustomerAssd|null $assd
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerAddress> $customer_address
 * @property-read int|null $customer_address_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \App\Models\CustomerAddress|null $homeAddress
 * @property-read \App\Models\CustomerAddress|null $invoiceAddress
 * @property-read \App\Models\CustomerAddress|null $preferredAddress
 * @property-read \App\Models\CustomerAddress|null $primaryAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quote> $quotes
 * @property-read int|null $quotes_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer new()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereArr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDateBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereHouses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePrimaryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSalesVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSpread($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTest10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTest4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTest6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTest7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTest9($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUser01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUser02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUser03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer withoutTrashed()
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $customer_id
 * @property string $type
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $zip
 * @property string|null $state
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CustomerAddressFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereZip($value)
 */
	class CustomerAddress extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $type
 * @property string|null $spread
 * @property string|null $arr
 * @property string|null $cm
 * @property string|null $bi
 * @property string|null $solution
 * @property string|null $houses
 * @property string|null $rooms
 * @property string|null $sales_valume
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereArr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereBi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereHouses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereSalesValume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereSolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereSpread($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAssd whereUpdatedAt($value)
 */
	class CustomerAssd extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $resource
 * @property string $field
 * @property string $type
 * @property string $display_as
 * @property string $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereDisplayAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerField whereVisible($value)
 */
	class CustomerField extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $value
 * @property string|null $header
 * @property string $date_from
 * @property string $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cv whereValue($value)
 */
	class Cv extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $contact_id
 * @property int|null $customer_id
 * @property string|null $external_id
 * @property string $filename
 * @property int $size
 * @property string $mime_type
 * @property string|null $content
 * @property int $user
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Contact|null $contact
 * @property-read \App\Models\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUser($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $xname
 * @property string $xunicode
 * @property string $xdec
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereXdec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereXname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Emoticons whereXunicode($value)
 */
	class Emoticons extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $section
 * @property int $form
 * @property int $user_id
 * @property string $label
 * @property string $table
 * @property string $field
 * @property string $type
 * @property int|null $is_badge
 * @property string|null $badge_color
 * @property string|null $align
 * @property string|null $select_options
 * @property string|null $visible
 * @property string|null $format
 * @property string|null $relation_table
 * @property string|null $relation_show_field
 * @property string $options
 * @property string|null $extra_attributes
 * @property string|null $color
 * @property int $searchable
 * @property int $sortable
 * @property int|null $is_toggable
 * @property int $disabled
 * @property int $required
 * @property int $dehydrated
 * @property int $collapsible
 * @property int $colspan
 * @property string|null $icon
 * @property string|null $icon_color
 * @property string|null $link
 * @property string|null $link_target
 * @property string $bagdecolor
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereAlign($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereBagdecolor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereCollapsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereColspan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereDehydrated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereIconColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereIsBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereIsToggable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereLinkTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereRelationShowField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereRelationTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereSearchable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereSelectOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereSortable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilTableFields whereVisible($value)
 */
	class FilTableFields extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property string $resource
 * @property string|null $field
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FilamentConfig whereValue($value)
 */
	class FilamentConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $street
 * @property string $plz
 * @property string $city
 * @property string $tel
 * @property string $email
 * @property string $openhours
 * @property string $pic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Friesenpics> $friesenpics
 * @property-read int|null $friesenpics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereOpenhours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen wherePlz($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesen whereUpdatedAt($value)
 */
	class Friesen extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $friesenid
 * @property string $pic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Friesen|null $friese
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics whereFriesenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friesenpics whereUpdatedAt($value)
 */
	class Friesenpics extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $img
 * @property array<array-key, mixed> $ingredients
 * @property string $how_to_make
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereHowToMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereIngredients($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Futter whereUpdatedAt($value)
 */
	class Futter extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $day
 * @property int $futter_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Futter|null $Futter
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay whereFutterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FutterPerDay whereUpdatedAt($value)
 */
	class FutterPerDay extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $country_map_name
 * @property string|null $color
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryMappoint> $GalleryMappoint
 * @property-read int|null $gallery_mappoint_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryPics> $GalleryPics
 * @property-read int|null $gallery_pics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereCountryMapName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gallery whereUpdatedAt($value)
 */
	class Gallery extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $option
 * @property string|null $value
 * @property string|null $value2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryConfig whereValue2($value)
 */
	class GalleryConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $gallery_id
 * @property string $country_id
 * @property string $mappoint_name
 * @property string $lat
 * @property string $lon
 * @property int $ord
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryPics> $GalleryPics
 * @property-read int|null $gallery_pics_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryPicContent> $Thumbnail
 * @property-read int|null $thumbnail_count
 * @property-read \App\Models\Gallery|null $gallery
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereGalleryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereMappointName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereOrd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryMappoint whereUpdatedAt($value)
 */
	class GalleryMappoint extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $size
 * @property int $pic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $filecontent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent whereFilecontent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent wherePicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPicContent whereUpdatedAt($value)
 */
	class GalleryPicContent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $gallery_id
 * @property string $pic
 * @property array<array-key, mixed>|null $meta
 * @property string|null $lat
 * @property string|null $lon
 * @property string|null $text
 * @property int $mappoint_id
 * @property int $ord
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GalleryMappoint|null $GalleryMappoint
 * @property-read \App\Models\GalleryPicContent|null $GalleryPicContent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryText> $GalleryText
 * @property-read int|null $gallery_text_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GalleryText> $GalleryTextAll
 * @property-read int|null $gallery_text_all_count
 * @property-read \App\Models\GalleryMappoint|null $Mappoint
 * @property-read \App\Models\GalleryPicContent|null $PicXl
 * @property-read \App\Models\GalleryPicContent|null $Thumbnail
 * @property-read \App\Models\Gallery|null $gallery
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereGalleryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereMappointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereOrd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryPics whereUpdatedAt($value)
 */
	class GalleryPics extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $pic_id
 * @property int $gallery_id
 * @property int|null $mappoint_id
 * @property string $language
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GalleryPics|null $galleryPics
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereGalleryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereMappointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText wherePicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryText whereUpdatedAt($value)
 */
	class GalleryText extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $field
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GeneralSetting whereValue($value)
 */
	class GeneralSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereAvailableAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jobs whereReservedAt($value)
 */
	class Jobs extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $topic
 * @property string $description
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\KnowledgeBaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KnowledgeBase whereUpdatedAt($value)
 */
	class KnowledgeBase extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $level
 * @property string|null $type
 * @property string|null $context
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logs whereUpdatedAt($value)
 */
	class Logs extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUserId($value)
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $topic
 * @property string|null $description
 * @property string $priority
 * @property string|null $tags
 * @property string $date
 * @property string $reminder
 * @property int $recurring
 * @property string|null $recurring_interval
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereRecurringInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MyDates whereUpdatedAt($value)
 */
	class MyDates extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $customer_id
 * @property string $number
 * @property string $total_price
 * @property string $status
 * @property string|null $shipping_price
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $quantity
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $show_name
 * @property string $series
 * @property string $lead_actor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereLeadActor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereShowName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PdfData whereUpdatedAt($value)
 */
	class PdfData extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $brand_id
 * @property string $slug
 * @property string $sku
 * @property string|null $image
 * @property string|null $description
 * @property int $quantity
 * @property string $price
 * @property int $is_visible
 * @property int $is_featured
 * @property string $type
 * @property string $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuoteProduct> $quoteProducts
 * @property-read int|null $quote_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Quote> $quotes
 * @property-read int|null $quotes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $reoccurance
 * @property int $customer_id
 * @property string $quote_number
 * @property string $total_amount
 * @property string $status
 * @property string $valid_until
 * @property string|null $notes
 * @property string|null $agb
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuoteProduct> $quoteProducts
 * @property-read int|null $quote_products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereAgb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereQuoteNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereReoccurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote whereValidUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Quote withoutTrashed()
 */
	class Quote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $quote_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $reoccurance
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Quote $quote
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereQuoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereReoccurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuoteProduct whereUpdatedAt($value)
 */
	class QuoteProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $resource
 * @property string $navigation_group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources whereNavigationGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources whereResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resources whereUpdatedAt($value)
 */
	class Resources extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $page_id
 * @property string $type
 * @property string $path
 * @property int $recursive
 * @property string|null $start_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereRecursive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereStartFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ssc whereUpdatedAt($value)
 */
	class Ssc extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property bool $personal_team
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TeamInvitation> $teamInvitations
 * @property-read int|null $team_invitations_count
 * @property-read \App\Models\Membership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $team_id
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeamInvitation whereUpdatedAt($value)
 */
	class TeamInvitation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $facebook_id
 * @property string|null $user01
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUser01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models\sk{
/**
 * 
 *
 * @property int $id
 * @property int $game_id
 * @property string $player_id
 * @property int $card_id
 * @property int $played
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card wherePlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Card whereUpdatedAt($value)
 */
	class Card extends \Eloquent {}
}

