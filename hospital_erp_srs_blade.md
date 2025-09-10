You are tasked with designing and generating a **Hospital ERP platform** tailored for general hospitals (public & private) with a focus on **global use cases and Tanzania**. The backend stack is **Laravel (PHP 8.x) with MySQL/MariaDB**, and the frontend stack is **Blade templates, Alpine.js (for simple reactivity), and optional Laravel Livewire**. The system must support multi‑user roles, modular expansion, regulatory compliance, and localization (English & Swahili).

### 1. System Goals
- Provide a **comprehensive ERP system for hospitals**, managing patients, finances, inventory, HR, payroll, and assets.
- Support **regulatory reporting** (e.g., MTUHA in Tanzania).
- Deliver a **secure, scalable, and user‑friendly platform** that works in resource‑constrained environments.
- Enable **future integration** with telehealth, IoT devices, and AI analytics (keep placeholders for later).

### 2. Core Modules (MVP)
1. **Patient Management**
   - Patient registration with MRN generation.
   - Appointment scheduling with reminders (SMS/WhatsApp).
   - EMR: vitals, progress notes, discharge summaries.
   - Patient portal for viewing appointments, bills, and results.

2. **Financial Management**
   - Billing & invoicing (multi‑currency).
   - Insurance claims workflows.
   - General ledger and journal entries.

3. **Inventory & Procurement**
   - Procurement requests → approvals → purchase orders.
   - Stock management (FIFO, expiry, reorder alerts).
   - Pharmacy dispensing linked to billing.

4. **HR & Payroll**
   - Employee profiles, qualifications, and shifts.
   - Shift scheduling with conflict checks.
   - Payroll calculations and payslip generation.

5. **Asset Management**
   - Equipment registry with warranties.
   - Preventive maintenance scheduling.
   - Utilization reports.

6. **Analytics & Reporting**
   - Dashboards (bed occupancy, revenue trends).
   - Regulatory reports (MTUHA, DHIS2 exports).
   - Custom report builder with filters and export (CSV, PDF).

7. **Interoperability**
   - RESTful JSON APIs for all modules.
   - HL7/FHIR compliance for patient, observations, and medication data.

### 3. Technical Requirements
- **Auth & Security**: Laravel Sanctum/OAuth2, role‑based access, AES‑256 encryption at rest, TLS in transit, full audit trails.
- **Performance**: handle ≥1,000 concurrent users, response <300ms.
- **Deployment**: Dockerized, scalable on‑prem or cloud.
- **Usability**: Blade server‑rendered UI, responsive for desktop/tablet, multilingual (EN/SWA).
- **Reliability**: 99.9% uptime, daily backups, PIT recovery.

### 4. Data Model Schemas (Simplified)
Key entities:
- **users** (linked to **roles**) for authentication & access.
- **patients** (linked to **appointments** and **invoices**).
- **employees** (linked to **users**, **appointments**, **payrolls**).
- **inventory_items** (linked to **inventory_transactions**).
- **assets** (linked to **maintenance_orders**).

### 5. Design & Frontend Expectations
- **Blade Components**: `<x-input>`, `<x-modal>`, `<x-table>`.
- **Layouts**: `layouts/app.blade.php` with `@extends` and `@section` usage.
- **AJAX Enhancements**: Axios/jQuery for dynamic slot loading, inventory refreshes.
- **Livewire Support**: for richer interactivity (optional).
- **Alpine.js**: for modals, simple form toggles, and inline validations.

### 6. Deliverables for Lovable & V0
- **UI mockups** for each module (dashboard, patient profile, billing, inventory, HR, assets).
- **Entity Relationship Diagram (ERD)** for the data models.
- **Blade component library** with examples for tables, forms, modals.
- **REST API documentation** (patients, appointments, billing, inventory).
- **Configurable report templates** for analytics and compliance.

### 7. Future Expansion (Footnotes)
- Telehealth workflows (virtual consults, e‑prescriptions).
- IoT integrations (real‑time vitals from devices).
- AI‑driven predictive analytics (bed demand forecasting, patient risk scores).

---

**Instruction:** Use the above requirements to generate:
1. A **clickable prototype** showing core workflows (registration → appointment → billing).
2. **Code scaffolding** in Laravel with Blade components for UI.
3. Suggested **UI/UX flows** optimized for hospital staff and administrators.

---

This prompt should serve as a **blueprint** for Lovable and V0 to generate prototypes, designs, and scaffolding aligned with the Hospital ERP system vision.

