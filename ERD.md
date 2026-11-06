# Entity Relationship Diagram (ERD) - Seven Kost (MVP)

Dokumen ini mendefinisikan rancangan struktur database dan relasi antar entitas untuk platform Seven Kost (tahap MVP 1 Bulan).

## Diagram Mermaid

```mermaid
erDiagram
    USERS ||--o{ MODEL_HAS_ROLES : has
    ROLES ||--o{ MODEL_HAS_ROLES : assigned_to

    PROVINCES ||--o{ REGENCIES : contains
    REGENCIES ||--o{ KOSTS : located_in

    USERS ||--o{ KOSTS : owns
    KOSTS ||--o{ KOST_IMAGES : has
    KOSTS ||--o{ KOST_FACILITIES : has
    FACILITIES ||--o{ KOST_FACILITIES : categorizes
    KOSTS ||--o{ ROOMS : has

    USERS ||--o{ BOOKINGS : rents
    ROOMS ||--o{ BOOKINGS : booked_for

    USERS ||--o{ CHAT_MESSAGES : sends
    USERS ||--o{ CHAT_MESSAGES : receives
    KOSTS ||--o{ CHAT_MESSAGES : regarding

    KOSTS ||--o{ ADS_PROMOTIONS : boosted_by
    ADS_PACKAGES ||--o{ ADS_PROMOTIONS : defines

    USERS ||--o{ REPORTS : reports
    KOSTS ||--o{ REPORTS : reported_target

    USERS ||--o{ BANNERS : created_by

    USERS {
        bigint id PK
        string name
        string email UK
        string phone
        string avatar
        timestamp email_verified_at
        string password
        remember_token remember_token
        timestamps timestamps
    }

    PROVINCES {
        char id PK "Kode BPS (2 digit, misal: 31)"
        string name
        decimal latitude
        decimal longitude
    }

    REGENCIES {
        char id PK "Kode BPS (4 digit, misal: 3171)"
        char province_id FK
        string name
        string type "Kabupaten / Kota"
        decimal latitude
        decimal longitude
    }

    KOSTS {
        bigint id PK
        bigint user_id FK "Pemilik"
        char province_id FK
        char regency_id FK
        string name
        string slug UK
        text description
        enum type "putra, putri, campur"
        decimal price_start "Harga termurah"
        enum rent_type "bulanan, tahunan, harian"
        text address
        decimal latitude
        decimal longitude
        enum verification_status "draft, pending, verified, rejected"
        text rejection_reason
        boolean is_available
        boolean is_featured "Penanda sedang aktif iklan unggulan"
        timestamps timestamps
    }

    ROOMS {
        bigint id PK
        bigint kost_id FK
        string name "Misal: Tipe A, Kamar 101"
        decimal price
        integer total_rooms
        integer available_rooms
        string room_size "Misal: 3x4 m"
        timestamps timestamps
    }

    FACILITIES {
        bigint id PK
        string name "AC, WiFi, Kamar Mandi Dalam"
        string icon
        enum category "kamar, umum, sekitar"
    }

    KOST_FACILITIES {
        bigint kost_id FK
        bigint facility_id FK
    }

    KOST_IMAGES {
        bigint id PK
        bigint kost_id FK
        string file_path
        boolean is_primary
        integer sort_order
    }

    BOOKINGS {
        bigint id PK
        bigint user_id FK "Penghuni / Penyewa"
        bigint room_id FK
        date start_date
        date end_date
        enum status "pending, approved, rejected, cancelled"
        text notes
        timestamps timestamps
    }

    ADS_PACKAGES {
        bigint id PK
        string name "Misal: Top Banner 7 Hari, Highlight 30 Hari"
        integer duration_days
        decimal price
        boolean is_active
        timestamps timestamps
    }

    ADS_PROMOTIONS {
        bigint id PK
        bigint kost_id FK
        bigint ads_package_id FK
        datetime starts_at
        datetime ends_at
        enum status "pending, active, expired"
        text proof_of_payment "Bukti transfer manual"
        timestamps timestamps
    }

    BANNERS {
        bigint id PK
        string title
        string image_path
        string target_url
        char regency_id FK "Bisa null jika berlaku nasional"
        datetime starts_at
        datetime ends_at
        integer click_count
        boolean is_active
        timestamps timestamps
    }

    REPORTS {
        bigint id PK
        bigint user_id FK "Pelapor"
        bigint kost_id FK "Kos terlapor"
        string reason
        text details
        enum status "pending, reviewed, resolved, rejected"
        timestamps timestamps
    }
```

---

## Ringkasan 5 Peran (RBAC Spatie)

1. **`superadmin`**: Mengelola admin, mengatur tarif iklan unggulan, mengelola slot banner, jejak audit, dan seluruh akses sistem.
2. **`admin`**: Memverifikasi listing kos baru, mengelola moderasi dan laporan pelanggaran, serta mencatat status pembayaran paket unggulan.
3. **`pemilik`**: Membuat dan mengelola iklan kos, mengelola kamar, melihat dan merespons pengajuan sewa, membeli paket iklan unggulan.
4. **`penghuni`**: Menjelajahi kos, mengajukan sewa kos (booking), mengirim chat/kontak pemilik kos, mengelola profil penyewa.
5. **`pengunjung`**: Menjelajahi pencarian kos, filter berdasarkan wilayah & fasilitas, melihat detail kos tanpa login.

