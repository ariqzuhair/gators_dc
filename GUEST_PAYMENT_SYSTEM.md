# Guest Payment System - Implementation Summary

## Overview
Implemented Option 4: **Hybrid Membership + Pay-per-Session** system that allows non-members to pay RM 3 for drop-in sessions while maintaining the RM 15 semester membership value proposition.

## Key Features

### 1. **Pricing Logic**
- **Members** (with active semester membership): Free registration
- **Non-Members**: RM 3.00 per drop-in session with payment receipt upload

### 2. **Registration Flow**

#### For Members:
1. Click "Register for Session"
2. System checks active membership
3. Immediate registration (free)
4. Confirmation message displayed

#### For Non-Members:
1. Click "Register for Session"
2. System detects no active membership
3. Payment modal appears showing RM 3 requirement
4. Upload payment receipt (JPEG/PNG/PDF, max 5MB)
5. Submit registration
6. Status: "Pending admin verification"

### 3. **Admin Verification**

Admins can now verify two types of payments in the Admin Dashboard:

#### Membership Payments Tab (RM 15):
- Semester membership payments
- Verify & Activate or Reject
- Updates user's semester_memberships array

#### Guest Drop-in Payments Tab (RM 3):
- Non-member session registrations
- Approve or Reject with reasons
- Approved: payment_status = 'paid', registration active
- Rejected: registration cancelled, participant count decreased

### 4. **Membership Encouragement**

The system tracks guest spending to encourage membership upgrades:
- API endpoint: `/api/players/{id}/guest-payment-stats`
- Tracks total sessions as guest and total spent
- Calculates potential savings
- Shows "sessions until membership value" (5 sessions = RM 15)
- Recommends membership when user has spent ≥ RM 15 or attended ≥ 5 sessions

## Technical Implementation

### Backend Changes

#### Models
**Registration.php** - Added fields:
- `registration_type` - 'member' or 'non-member'
- `guest_payment_receipt` - path to uploaded receipt
- Helper methods: `isNonMemberRegistration()`, `isPaymentPending()`

#### Controllers
**RegistrationController.php** - Enhanced methods:
- `store()` - Checks membership, sets pricing, handles receipt upload
- `getPendingGuestPayments()` - Admin view pending guest payments
- `verifyGuestPayment()` - Approve/reject guest payments
- `getGuestPaymentStats()` - Track guest spending for membership encouragement

#### Routes
**api.php** - New endpoints:
```php
Route::get('/registrations/guest-payments/pending', [RegistrationController::class, 'getPendingGuestPayments']);
Route::post('/registrations/{id}/verify-guest-payment', [RegistrationController::class, 'verifyGuestPayment']);
Route::get('/players/{id}/guest-payment-stats', [RegistrationController::class, 'getGuestPaymentStats']);
```

### Frontend Changes

#### SessionDetailPage.vue
- Added membership status checking
- Payment modal for non-members
- File upload validation
- Success/error messaging with pending status indication
- Visual indicators: Free for members, RM 3 for non-members

#### AdminDashboard.vue
- Added payment type tabs (Membership vs Guest)
- Guest payments section with session details
- Approve/Reject workflow
- Live count badge for pending guest payments
- Session date and registration info display

## File Storage

Guest payment receipts stored at:
```
storage/app/public/receipts/guest_payments/
```

Files named: `{timestamp}_guest_{player_id}.{extension}`

## Database Structure

### Registration Document
```javascript
{
  player_id: ObjectId,
  session_id: ObjectId,
  status: 'registered' | 'cancelled' | 'completed' | 'no-show',
  payment_status: 'pending' | 'paid' | 'refunded',
  payment_amount: 3.00, // for non-members
  registration_type: 'member' | 'non-member',
  guest_payment_receipt: 'receipts/guest_payments/1234_guest_xyz.jpg',
  registration_date: Date,
  cancellation_date: Date,
  notes: String
}
```

## User Experience

### Visual Feedback
1. **Before Registration**: Shows membership status with helpful info
   - Green badge: "You have active membership - Free!"
   - Yellow badge: "No membership - RM 3 drop-in fee required"

2. **During Registration**: 
   - Member: Instant registration
   - Non-member: Payment modal with clear instructions

3. **After Registration**:
   - Member: "Successfully registered!"
   - Non-member: "Registration submitted. Pending payment verification."

### Admin Experience
- Separate tabs keep membership and guest payments organized
- Badge shows count of pending guest payments
- All relevant info displayed: player, session, date, amount
- One-click approve/reject
- View receipt in new tab

## Business Logic Benefits

1. **Revenue Stream**: RM 3 per drop-in from non-members
2. **Membership Value**: After 5 drop-ins (RM 15), membership makes financial sense
3. **Flexibility**: Allows casual players to try before committing
4. **Clear Pricing**: Transparent cost structure
5. **Admin Control**: Manual verification maintains payment accuracy

## Future Enhancements (Suggested)

1. **Automated Membership Prompts**: Show banner after 3-4 guest sessions
2. **Payment History**: Track all guest payments for analytics
3. **Automated Payment**: Integrate payment gateway to reduce admin burden
4. **Guest Statistics Dashboard**: Admin view of guest conversion rates
5. **Referral Discounts**: Encourage membership sign-ups

## Testing Checklist

- [ ] Member registration (should be free and instant)
- [ ] Non-member registration (should require payment upload)
- [ ] File upload validation (size, type)
- [ ] Admin approval workflow
- [ ] Admin rejection workflow (registration cancelled)
- [ ] Session participant count updates correctly
- [ ] Payment stats API returns correct data
- [ ] Receipt file access via storage link
- [ ] Mobile responsive payment modal

## Notes

- Payment verification is manual to ensure accuracy
- Storage symbolic link must be created: `php artisan storage:link`
- Receipts are stored permanently for audit purposes
- Rejected payments decrease session participant count
- System gracefully handles missing player profiles (auto-creates)
