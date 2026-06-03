# NMS Admin Read-Only Access Control System

## Overview
This system ensures that NMS (Non-JMS) admin users can only **view data** without being able to create, edit, update, or delete any data. JMS admins retain full access to all operations.

## Implementation Components

### 1. Middleware: CheckNmsReadOnly
**File**: `app/Http/Middleware/CheckNmsReadOnly.php`

This middleware prevents NMS users from performing any write operations (POST, PUT, PATCH, DELETE).

**Behavior**:
- Checks if user role is 'NMS'
- If NMS user attempts a write operation (POST, PUT, PATCH, DELETE), returns an error
- JMS users can proceed normally
- GET requests are always allowed

**Usage**: Applied to all routes that modify data

### 2. Helper Class: AuthHelper
**File**: `app/Helpers/AuthHelper.php`

Provides utility functions for checking user permissions in controllers and views:

```php
AuthHelper::canManageData()      // false for NMS, true for JMS
AuthHelper::isNmsAdmin()         // true if user is NMS
AuthHelper::isJmsAdmin()         // true if user is JMS
AuthHelper::getReadOnlyMessage() // Returns read-only restriction message
```

## Protected Routes

The following routes are protected with the `CheckNmsReadOnly` middleware:

### Hospital Management
- `PUT /hospital/update` - Update hospital data
- `DELETE /hospital/delete` - Delete hospital

### Contract Management (Kendali Kontrak)
- `POST /menu/kendali-kontrak/store` - Create new contract
- `POST /menu/kendali-kontrak/update/{id}` - Update contract
- `DELETE /menu/kendali-kontrak/delete/{id}` - Delete contract

### Time Table Management
- `POST /menu/time-table/store` - Create time table entry
- `PUT /menu/time-table/update` - Update time table
- `DELETE /menu/time-table/delete` - Delete time table entry

### RAB (Rencana Anggaran Biaya) Management
- `POST /menu/rab-pra-operasional/store` - Create RAB
- `PUT /menu/rab-pra-operasional/update/{id}` - Update RAB
- `DELETE /menu/rab-pra-operasional/delete/{id}` - Delete RAB

### Payment Schedule Management
- `POST /pajak-tahap/store` - Create tax stage payment
- `PUT /menu/payment-schedule/update` - Update payment schedule
- `PUT /payment-schedule/update` - Update payment schedule (controller version)

## Read-Only Routes (Always Accessible)

These GET routes remain accessible to all users:

- `GET /dashboard` - View dashboard
- `GET /menu/kendali-kontrak` - View contracts
- `GET /menu/time-table` - View time table
- `GET /menu/rab-pra-operasional` - View RAB
- `GET /menu/payment-schedule` - View payment schedule
- `GET /menu/outstanding-payment` - View outstanding payments
- `GET /menu/payment-jurnal` - View payment journal

## User Roles

### JMS Admin (Full Access)
- **Role**: 'JMS'
- **Permissions**: Can view, create, edit, update, delete all data
- **Hospital Management**: Can add, edit, delete hospitals
- **Credentials**: 
  - Username: `jms` or Email: `admin.jms@mail.com`
  - Password: `passwordjms`

### NMS Admin (Read-Only Access)
- **Role**: 'NMS'
- **Permissions**: Can ONLY view data, NO modifications allowed
- **Hospital Management**: Cannot add new hospitals, can only select from existing list
- **Credentials**:
  - Username: `nms` or Email: `guest@gmail.com`
  - Password: `passwordnms`

## Implementation in Views

To hide edit/delete buttons for NMS users in Blade templates:

```blade
@if(AuthHelper::canManageData())
    <!-- Show edit/delete buttons only for JMS -->
    <button class="btn btn-edit">Edit</button>
    <button class="btn btn-delete">Delete</button>
@endif

<!-- Or use specific checks -->
@if(AuthHelper::isJmsAdmin())
    <!-- JMS-specific controls -->
@elseif(AuthHelper::isNmsAdmin())
    <!-- NMS-specific readonly message -->
    <p class="alert alert-info">{{ AuthHelper::getReadOnlyMessage() }}</p>
@endif
```

## Error Handling

When an NMS user tries to access a protected route:
- The middleware intercepts the request
- Returns a back redirect with error message:
  > "Admin NMS hanya memiliki akses untuk melihat data. Tidak dapat melakukan perubahan atau penghapusan data."
- User is redirected to the previous page with the error notification

## Testing

### Test NMS Read-Only Access:
1. Login as NMS (username: `nms`, password: `passwordnms`)
2. Navigate to any data view page (e.g., contracts, time table, RAB)
3. Attempt to:
   - Click edit button → Should show error message
   - Click delete button → Should show error message
   - Submit form → Request blocked, error message shown

### Test JMS Full Access:
1. Login as JMS (username: `jms`, password: `passwordjms`)
2. Navigate to any data view page
3. Edit/Delete operations should work normally

## Security Notes

- The middleware checks `session('user_role')` which is set at login
- All write operations are protected at the route level
- Database queries for sensitive operations should include role checks as defense-in-depth
- Sessions are server-side and cannot be easily tampered with
- For additional security, consider implementing database-level role-based access control

## Future Enhancements

1. Add database-backed roles and permissions system
2. Implement role-based middleware in middleware groups
3. Add audit logging for access attempts
4. Create admin panel for managing user roles
5. Implement granular permission system for partial access
