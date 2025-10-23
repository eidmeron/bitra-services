# 📦 Bitra Booking Forms - WordPress Plugin Installation Guide

## 🎯 Quick Start (5 Minutes)

### Step 1: Download Plugin
The plugin is ready to install! File location:
```
/Users/mero/Desktop/bitra-services/wordpress-plugin/bitra-booking-forms.zip
```

### Step 2: Install on WordPress

#### Method A: Via WordPress Admin (Recommended)
1. Log in to your WordPress site
2. Go to **Plugins → Add New**
3. Click **Upload Plugin** button
4. Click **Choose File** and select `bitra-booking-forms.zip`
5. Click **Install Now**
6. Click **Activate Plugin**

#### Method B: Via FTP
1. Extract `bitra-booking-forms.zip`
2. Upload the `bitra-booking-forms` folder to `/wp-content/plugins/`
3. Go to WordPress **Plugins** page
4. Find "Bitra Booking Forms" and click **Activate**

### Step 3: Configure Plugin

1. Go to **Settings → Bitra Forms**
2. Enter your **Bitra API URL**:
   - For local development: `http://127.0.0.1:8003`
   - For production: `https://your-domain.com`
3. Click **Save Changes**

### Step 4: Get Form Token

1. Log in to your Bitra Services admin panel
2. Go to **Forms** section
3. Find the form you want to embed
4. Copy the **Public Token** (e.g., `abc123xyz`)

### Step 5: Embed Form

#### In WordPress Post/Page Editor:

**Classic Editor:**
```
[bitra_form token="YOUR-TOKEN-HERE"]
```

**Gutenberg (Block Editor):**
1. Add a **Shortcode** block
2. Paste: `[bitra_form token="YOUR-TOKEN-HERE"]`
3. Replace `YOUR-TOKEN-HERE` with your actual token
4. Publish or Update the page

**Example:**
```
[bitra_form token="k3n9p2m5q8r7"]
```

---

## 🎨 Advanced Configuration

### Custom Height

```
[bitra_form token="YOUR-TOKEN" height="800"]
```

### AJAX Embedding (No iframe)

```
[bitra_form token="YOUR-TOKEN" type="ajax"]
```

### Multiple Forms on One Page

```
<h2>Book Hemstädning</h2>
[bitra_form token="token-hemsadning"]

<h2>Book Flyttstädning</h2>
[bitra_form token="token-flyttstadning"]
```

---

## 📱 Responsive Design

The plugin automatically adapts to all screen sizes:
- **Desktop**: Full width, optimal height
- **Tablet**: Adjusted width, readable text
- **Mobile**: Single column, touch-friendly

---

## 🎯 Use Cases

### 1. Service Pages

Create a page for each service with its booking form:

**Page: Hemstädning**
```
[bitra_form token="hemsadning-token"]
```

**Page: Flyttstädning**
```
[bitra_form token="flyttstadning-token"]
```

### 2. Sidebar Widget

Use in a text widget:
```
[bitra_form token="quick-booking-token" height="600"]
```

### 3. Contact Page

Add multiple booking options:
```
<h2>Boka Våra Tjänster</h2>

<h3>Hemstädning</h3>
[bitra_form token="hemsadning" height="700"]

<h3>Kontorsstädning</h3>
[bitra_form token="kontorsstadning" height="700"]
```

---

## 🔧 Troubleshooting

### Problem: Form Not Showing

**Solution:**
1. Check plugin is activated
2. Verify API URL in settings (no trailing slash!)
3. Ensure token is correct
4. Check browser console for errors

### Problem: iframe Too Small/Large

**Solution:**
Adjust height parameter:
```
[bitra_form token="YOUR-TOKEN" height="1000"]
```

### Problem: Styling Conflicts

**Solution:**
Try AJAX embedding:
```
[bitra_form token="YOUR-TOKEN" type="ajax"]
```

### Problem: Form Redirects Away from Page

**Solution:**
This is normal behavior. The form is designed to redirect to a success page after submission.

---

## 🚀 Performance Tips

1. **Use iframe** (default) for best isolation
2. **Set appropriate height** to avoid scrolling
3. **Cache plugin settings** (automatically done)
4. **Use CDN** for your WordPress site

---

## 🔒 Security

✅ All form data processed securely by Bitra  
✅ No sensitive data stored on WordPress  
✅ HTTPS enforced for all communications  
✅ Follows WordPress security best practices  

---

## 📊 Testing Checklist

Before going live, test:

- [ ] Plugin activates without errors
- [ ] Settings page accessible
- [ ] API URL saves correctly
- [ ] Shortcode displays form
- [ ] Form is responsive on mobile
- [ ] Form submits successfully
- [ ] Success redirect works
- [ ] Multiple forms work on different pages

---

## 💡 Pro Tips

### Tip 1: Preview Before Publishing
Use WordPress **Preview** to see how the form looks before publishing.

### Tip 2: Keep Tokens Organized
Create a document mapping form tokens to services:
```
Hemstädning: abc123
Flyttstädning: def456
Kontorsstädning: ghi789
```

### Tip 3: Use Descriptive Page URLs
```
example.com/boka-hemsadning
example.com/boka-flyttstadning
```

### Tip 4: Add Context Text
```
<div class="booking-intro">
    <h2>Boka Hemstädning Online</h2>
    <p>Fyll i formuläret nedan för att få ett pris och boka direkt.</p>
</div>

[bitra_form token="hemsadning-token"]
```

---

## 📞 Support

- **Email:** support@bitraservices.se
- **Website:** https://bitraservices.se/support
- **Documentation:** https://bitraservices.se/docs

---

## ✅ Success!

Your WordPress site is now connected to Bitra Booking Forms! 🎉

**What's Next?**
1. Create dedicated booking pages for each service
2. Add booking forms to your most popular pages
3. Monitor bookings through Bitra admin dashboard
4. Collect customer feedback and optimize

---

**Made with ❤️ by Bitra Services**

