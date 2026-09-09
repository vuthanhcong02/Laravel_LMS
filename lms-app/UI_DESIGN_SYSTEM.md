# 🎨 XiaoMu LMS - General UI/UX Design System

Tài liệu quy chuẩn thiết kế UI/UX tổng quan (General Design System) áp dụng nhất quán cho toàn bộ dự án **XiaoMu - Tiếng Trung LMS**. Tất cả các trang và UI components khi được tạo mới hoặc chỉnh sửa đều BẮT BUỘC tuân thủ các quy tắc tổng quát bên dưới.

---

## 1. Brand Identity & Header Rules (Nhận diện & Header)

- **Logo thương hiệu**: Mascot Avatar tròn + Chữ `XiaoMu` (`font-bold`) + Subtitle `Tiếng Trung LMS` (`text-[#e07a5f] text-[11px]`).
- **Quy chuẩn Khung Logo**: Sạch sẽ, **KHÔNG** thêm viền outer box, **KHÔNG** thêm chấm màu bên cạnh.
- **Bộ chuyển đổi ngôn ngữ (Multi-language Dropdown)**:
  - Nút chuyển kèm cờ SVG Vector + Tên ngôn ngữ:
    - 🇻🇳 `Việt Nam`
    - 🇨🇳 `中文 (简体)` (Dùng phông Hán tự `Noto Sans SC`)
    - 🇬🇧 `English`

---

## 2. Color Palette & Design Tokens (Bảng Màu Tổng Quan)

### 🎨 Màu Chủ Đạo & Accent (Brand Colors)
- **Màu thương hiệu chính (Primary Accent)**: Terracotta Warm Coral `#e07a5f` (Hover: `#c86349`)
- **Màu nền thẻ nổi bật (Primary Light BG)**: Soft Peach `#fff2ee` (Viền: `#fcdccf`)
- **Màu phụ trợ (Supporting Accents)**:
  - **Xanh Dương (Info/Thời gian)**: `#0284c7`
  - **Xanh Lá (Thành công/Đã hoàn thành)**: `#10b981` (Thẻ nhẹ: `#a7f3d0`)
  - **Vàng Kim (Điểm thưởng/Vương miện)**: `#f59e0b`

### 🌞 Light Mode (Giao diện Sáng)
- **Nền trang chính (Page BG)**: Warm Cream `#f8f6f3`
- **Nền thẻ Card (Card BG)**: Trắng thuần `#ffffff`
- **Nền khung phụ bên trong (Inset BG)**: Soft Inset `#fcfaf7`
- **Đường viền (Borders)**: Sand Beige `#e8e2d9`
- **Chữ chính (Primary Text)**: Dark Slate `text-slate-800` (`#1e1b18`)
- **Chữ phụ (Secondary Text)**: `text-slate-500` / `text-slate-400`

### 🌙 Dark Mode (Giao diện Tối)
- **Nền trang chính (Page BG)**: Dark Charcoal `#0e0c0b`
- **Nền thẻ Card (Card BG)**: Warm Charcoal `#181615`
- **Nền khung phụ bên trong (Inset BG)**: `#23201e`
- **Đường viền (Borders)**: Dark Border `#2d2926`
- **Chữ chính (Primary Text)**: `text-slate-100` / `text-white`
- **Chữ phụ (Secondary Text)**: `text-slate-400`

---

## 3. Typography & Size Scale (Phông Chữ & Cỡ Chữ Standard)

- **Phông chữ UI chính**: Google Font `Inter`, `sans-serif`
- **Phông chữ Hán tự (Tiếng Trung)**: Google Font `Noto Sans SC` (`class="zh-text"`)

### ⚠️ Quy tắc Độ dày Chữ (Font-Weight):
- **TUYỆT ĐỐI KHÔNG** sử dụng `font-black` (gây thô cứng).
- Tiêu đề trang / Card: `font-bold`
- Nội dung / Nút bấm / Nhãn: `font-semibold` hoặc `font-medium`
- Chú thích / Metadata: `font-semibold` hoặc `font-normal`

### 📐 Quy chuẩn Cỡ chữ (Size Scale):
- **Tiêu đề lớn (Page H1)**: `text-xl` đến `text-2xl` (`font-bold`)
- **Tiêu đề Thẻ / Section (Card H2/H3)**: `text-base` (`font-bold`)
- **Văn bản nội dung (Body text)**: `text-xs` đến `text-sm` (`font-medium` / `font-normal`)
- **Chú thích phụ (Captions/Subtext)**: `text-xs` (`font-normal`)
- **Nhãn nhỏ / Metadata / Badge**: `text-[10px]` hoặc `text-[11px]` (`font-semibold`)

---

## 4. Cards & Containers (Thẻ & Khung Chứa)

- **Bo tròn (Border Radius)**:
  - Thẻ chính (Main Card): `rounded-2xl` ($20\text{px}$)
  - Khung nhỏ bên trong / Item: `rounded-xl` ($12\text{px}$)
  - Badge / Nhãn: `rounded-full` hoặc `rounded-lg`
- **Đường viền (Card Border)**: 1px viền nhã nhặn (`border border-[#e8e2d9] dark:border-[#2d2926]`).
- **Hiệu ứng Hover**: Nâng nhẹ `hover:-translate-y-0.5 hover:shadow-md transition-all duration-200`.

---

## 5. Buttons & Interactive Elements (Nút Bấm & Thành phần Tương tác)

- **Nút bấm chính (Primary Button)**:
  - Class: `bg-[#e07a5f] hover:bg-[#c86349] text-white font-bold rounded-xl text-xs sm:text-sm px-4 py-2 shadow-xs transition-all btn-tactile`
- **Nút bấm phụ (Secondary / Ghost Button)**:
  - Class: `bg-white dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-slate-300 font-bold rounded-xl text-xs px-3 py-2 transition-all btn-tactile`
- **Hiệu ứng Nút bấm (Tactile Press)**: Thêm class `btn-tactile` (`active:scale-96 transition-all duration-150`).
- **Thanh Bộ lọc / Tab Segmented Control**:
  - Luôn nằm trên **1 DÒNG DUY NHẤT** (`flex-nowrap gap-1.5 overflow-x-auto no-scrollbar`), dạng nút pill bo tròn nhỏ gọn (`text-[11px] px-2.5 py-1`).

---

## 6. Form Inputs & Controls (Ô Nhập liệu)

- **Ô Tìm kiếm / Input Text**:
  - Class: `bg-[#f8f6f3] dark:bg-slate-800 border border-[#e8e2d9] dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-[#e07a5f] focus:ring-2 focus:ring-[#e07a5f]/20 transition-all`
