# 🧪 Form Builder - Complete Testing Guide

## ✅ Pre-Test Checklist
1. Clear browser cache
2. Hard refresh page (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows)
3. Open browser console (F12)
4. Check for JavaScript errors

## 🎯 Test Scenarios

### Test 1: Basic Form Save (Empty)
**URL**: http://127.0.0.1:8002/admin/forms/1/edit

**Steps**:
1. Open the form editor
2. Change only the form name to "Test Form 1"
3. Click "💾 Spara formulär"

**Expected**:
- ✅ Form saves successfully
- ✅ Redirects to forms index
- ✅ Success message appears

**If Error**: Check browser console for JavaScript errors

---

### Test 2: Add Text Field
**Steps**:
1. Click "📝 Textfält" button
2. Click on the newly added field
3. In right panel, set:
   - Etikett: "Ditt namn"
   - Fältnamn: "name"
   - Platshållare: "Ange ditt namn"
   - ✅ Check "Obligatoriskt fält"
4. Click "💾 Spara formulär"

**Expected**:
- ✅ Field appears in preview
- ✅ Form saves with field
- ✅ Field properties are saved

**Check Console**: Look for the saved data structure

---

### Test 3: Add Validation Rules
**Steps**:
1. Add a text field
2. Click on the field
3. Scroll down to "✓ Valideringsregler"
4. Set:
   - Min längd: 3
   - Max längd: 50
5. Click "💾 Spara formulär"

**Expected**:
- ✅ Validation rules saved
- ✅ No validation errors

**Debug**: Check if validationRules object is in the JSON

---

### Test 4: Add Number Field with Pricing
**Steps**:
1. Click "🔢 Nummer"
2. Click on the field
3. Set label: "Antal rum"
4. Set name: "rooms"
5. Scroll to "💰 Prissättning"
6. Set "Pris per enhet": 100
7. Click "💾 Spara formulär"

**Expected**:
- ✅ Number field saved
- ✅ Pricing rule saved

---

### Test 5: Add Select Field with Options
**Steps**:
1. Click "📋 Rullgardinsmeny"
2. Set label: "Välj storlek"
3. Set name: "size"
4. Click "+ Lägg till alternativ" 3 times
5. Set options:
   - Liten (price: 50)
   - Medium (price: 100)
   - Stor (price: 150)
6. Click "💾 Spara formulär"

**Expected**:
- ✅ Select field saved
- ✅ All 3 options saved with prices

---

### Test 6: Add Conditional Logic
**Steps**:
1. Add two text fields:
   - Field 1: label="Typ", name="type"
   - Field 2: label="Detaljer", name="details"
2. Click on Field 2
3. Scroll to "🔀 Villkorsstyrd logik"
4. Click "+ Lägg till villkor"
5. Set:
   - Fält: "type" (Typ)
   - Villkor: "Är lika med"
   - Värde: "premium"
6. Click "💾 Spara formulär"

**Expected**:
- ✅ Conditional logic saved
- ✅ Field 2 has condition tied to Field 1

---

### Test 7: Field Actions
**Steps**:
1. Add 3 text fields
2. Test each action:
   - ⬆️ Move field up
   - ⬇️ Move field down
   - 📋 Duplicate field
   - 🗑️ Delete field
3. Click "💾 Spara formulär"

**Expected**:
- ✅ All actions work
- ✅ Order is saved correctly

---

### Test 8: Form Duplication
**Steps**:
1. Go to http://127.0.0.1:8002/admin/forms
2. Find a form with fields
3. Click "📑 Duplicera"

**Expected**:
- ✅ Form duplicates successfully
- ✅ All fields are copied
- ✅ Status is set to "draft"
- ✅ Name has "(Kopia)" suffix

---

## 🐛 Common Issues & Solutions

### Issue 1: "The form schema field must be an array"
**Solution**: 
- Clear browser cache
- Hard refresh (Cmd/Ctrl + Shift + R)
- Check if form_schema_input has a value in console

### Issue 2: "Fälttyp är obligatoriskt"
**Solution**:
- Ensure fields have all required properties
- Check browser console for the fields array structure
- Verify prepareForValidation is mapping fields correctly

### Issue 3: Fields not showing after save
**Solution**:
- Check database: `select * from form_fields where form_id = 1;`
- Verify field_width is a valid value (25, 33, 50, 100)
- Check for JSON encoding errors

### Issue 4: Validation rules not saving
**Solution**:
- Ensure validationRules is an object, not null
- Check the structure: {minLength: 3, maxLength: 50}
- Verify the form_fields table has validation_rules column

### Issue 5: Conditional logic not working
**Solution**:
- Ensure conditionalLogic has proper structure
- Check: {operator: 'and', rules: [{field, condition, value}]}
- Verify field names match exactly

---

## 🔍 Debug Commands

### Check Form in Database
```sql
SELECT * FROM forms WHERE id = 1;
```

### Check Fields in Database
```sql
SELECT id, field_type, field_label, field_name, field_width, pricing_rules, validation_rules, conditional_logic 
FROM form_fields 
WHERE form_id = 1;
```

### Check Form Schema
```sql
SELECT form_schema FROM forms WHERE id = 1;
```

### Clear All Caches
```bash
php artisan optimize:clear
php artisan view:clear
```

### Check JavaScript Console
```javascript
// In browser console on edit page:
console.log('Form Schema:', window.formSchema);
console.log('Alpine Data:', Alpine.store());
```

---

## ✅ Success Criteria

All tests pass if:
- [x] Form saves without errors
- [x] Fields are created and saved
- [x] Validation rules work
- [x] Pricing rules work
- [x] Conditional logic works
- [x] Field actions (move, duplicate, delete) work
- [x] Form duplication works
- [x] All field types work
- [x] Field options with prices work
- [x] Preview shows correct fields

---

## 📊 Expected Data Structure

### Field Object (JavaScript)
```javascript
{
    id: "field_1234567890",
    type: "text",
    label: "Ditt namn",
    name: "name",
    placeholder: "Ange ditt namn",
    helpText: "",
    width: "100",
    required: true,
    options: null,
    pricingRules: {
        pricePerUnit: 0
    },
    validationRules: {
        minLength: 3,
        maxLength: 50,
        min: null,
        max: null,
        pattern: null
    },
    conditionalLogic: {
        operator: "and",
        rules: []
    }
}
```

### Mapped Field (After Validation)
```php
[
    'field_type' => 'text',
    'field_label' => 'Ditt namn',
    'field_name' => 'name',
    'placeholder_text' => 'Ange ditt namn',
    'help_text' => '',
    'field_width' => '100',
    'required' => true,
    'pricing_rules' => ['pricePerUnit' => 0],
    'validation_rules' => ['minLength' => 3, 'maxLength' => 50],
    'conditional_logic' => ['operator' => 'and', 'rules' => []],
    'field_options' => null
]
```

---

**Testing Date**: 2024-10-10
**Status**: Ready for testing
**Documentation**: Complete
