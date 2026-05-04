# TextformatterSmartQuotes

**A ProcessWire Textformatter that replaces straight quotes (`"..."`) with typographic quotes – only in visible text content.**

---

## ✨ Features

- Replaces `"..."` with typographic quotes like `„...“`, `„...”`, `“…”`, or `« ... »`
- Leaves all HTML markup and attributes untouched
- Simple and safe – no risky regular expressions
- Supports configurable quote styles:
  - **German**: „...“
  - **Polish**: „...”
  - **English**: “...”
  - **French**: « ... »

---

## 🧠 Why this exists

We needed a way to convert straight quotes into proper typographic ones in content fields – but without breaking HTML attributes like:

```html
<strong style="font-size: 18px;">Improved "well-being"</strong>
```

Most regex-based solutions failed, either:

- Replacing quotes inside HTML attributes (which breaks markup), or
- Missing valid quotes in visible content

This module solves that cleanly by operating only on the **visible text**, ignoring all tags and attributes.

---

## 📦 Installation

1. Copy this module into your ProcessWire site under:

   ```
   /site/modules/TextformatterSmartQuotes/
   ```

2. In the ProcessWire admin, go to **Modules → Refresh**, then install **Textformatter Smart Quotes**.

3. In any text/textarea/CKEditor field settings, add the formatter under **“Text formatters”**.

---

## ⚙️ Configuration

Go to **Modules → Configure → Textformatter Smart Quotes**  
Select your preferred **quote style** from the dropdown.

---

## 💬 Feedback & Contributions

Feel free to open issues or submit pull requests.  
This module is simple by design, but open for improvements (e.g. nested quotes, single quote support, etc.).

---

**Developed by [frameless Media](https://frameless.at)**
