import streamlit as st
import pandas as pd
import numpy as np
import pickle

# ── Konfigurasi halaman ──────────────────────────────────────────────
st.set_page_config(
    page_title="Smart Spend — Prediksi Pengeluaran",
    page_icon="💰",
    layout="wide"
)

# ── Load model ───────────────────────────────────────────────────────
@st.cache_resource
def load_model():
    with open('models.pkl', 'rb') as f:
        return pickle.load(f)

data = load_model()
models    = data['models']
le_dict   = data['le_dict']
feature_cols = data['feature_cols']
target_cols  = data['target_cols']

# ── Label kolom yang lebih ramah ─────────────────────────────────────
label_map = {
    'pengeluaran_makanan_pokok':       '🍚 Makanan Pokok',
    'pengeluaran_lauk_pauk':           '🍖 Lauk Pauk',
    'pengeluaran_sayur_buah':          '🥦 Sayur & Buah',
    'pengeluaran_jajan_makan_luar':    '🍜 Jajan/Makan Luar',
    'pengeluaran_perumahan_listrik':   '🏠 Perumahan & Listrik',
    'pengeluaran_transportasi':        '🚗 Transportasi',
    'pengeluaran_komunikasi':          '📱 Komunikasi',
    'pengeluaran_kesehatan':           '🏥 Kesehatan',
    'pengeluaran_pendidikan':          '📚 Pendidikan',
    'pengeluaran_hiburan_rekreasi':    '🎮 Hiburan & Rekreasi',
    'pengeluaran_pakaian':             '👕 Pakaian',
    'total_pengeluaran':               '💳 Total Pengeluaran',
}

warna_kategori = {
    'pengeluaran_makanan_pokok':       '#378ADD',
    'pengeluaran_lauk_pauk':           '#1D9E75',
    'pengeluaran_sayur_buah':          '#639922',
    'pengeluaran_jajan_makan_luar':    '#BA7517',
    'pengeluaran_perumahan_listrik':   '#D85A30',
    'pengeluaran_transportasi':        '#7F77DD',
    'pengeluaran_komunikasi':          '#D4537E',
    'pengeluaran_kesehatan':           '#E24B4A',
    'pengeluaran_pendidikan':          '#0F6E56',
    'pengeluaran_hiburan_rekreasi':    '#534AB7',
    'pengeluaran_pakaian':             '#888780',
}

# ── CSS kustom ───────────────────────────────────────────────────────
st.markdown("""
<style>
.main-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0.2rem;
}
.sub-title {
    font-size: 1rem;
    color: #666;
    margin-bottom: 2rem;
}
.metric-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem 1.2rem;
    border: 1px solid #e9ecef;
    margin-bottom: 0.8rem;
}
.metric-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.2rem;
}
.metric-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1a2e;
}
.total-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    text-align: center;
    margin: 1rem 0;
}
.total-label {
    font-size: 1rem;
    opacity: 0.85;
}
.total-value {
    font-size: 2.2rem;
    font-weight: 800;
    margin-top: 0.3rem;
}
.sisa-card {
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    margin: 0.5rem 0;
    color: white;
}
.bar-container {
    background: #f0f0f0;
    border-radius: 6px;
    height: 10px;
    margin-top: 6px;
    overflow: hidden;
}
.bar-fill {
    height: 10px;
    border-radius: 6px;
}
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
""", unsafe_allow_html=True)

# ── Header ───────────────────────────────────────────────────────────
st.markdown('<div class="main-title">💰 Smart Spend</div>', unsafe_allow_html=True)
st.markdown('<div class="sub-title">Prediksi pengeluaran bulan depan berdasarkan profil keuanganmu</div>', unsafe_allow_html=True)

# ── Layout: form kiri, hasil kanan ───────────────────────────────────
col_form, col_hasil = st.columns([1, 1.4], gap="large")

with col_form:
    st.subheader("📋 Profil Keuangan")

    pendapatan = st.number_input(
        "Pendapatan bulanan (Rp)",
        min_value=500_000,
        max_value=50_000_000,
        value=3_000_000,
        step=100_000,
        format="%d"
    )

    col_a, col_b = st.columns(2)
    with col_a:
        usia = st.number_input("Usia", min_value=17, max_value=70, value=28)
    with col_b:
        tanggungan = st.number_input("Jumlah tanggungan", min_value=0, max_value=10, value=0)

    provinsi = st.selectbox("Provinsi", le_dict['provinsi'].classes_)
    wilayah  = st.selectbox("Klasifikasi wilayah", le_dict['klasifikasi_wilayah'].classes_)

    col_c, col_d = st.columns(2)
    with col_c:
        pekerjaan = st.selectbox("Status pekerjaan", le_dict['status_pekerjaan'].classes_)
    with col_d:
        pendidikan = st.selectbox("Pendidikan", le_dict['pendidikan_terakhir'].classes_)

    col_e, col_f = st.columns(2)
    with col_e:
        pernikahan = st.selectbox("Status pernikahan", le_dict['status_pernikahan'].classes_)
    with col_f:
        jenis_kel = st.selectbox("Jenis kelamin", le_dict['jenis_kelamin'].classes_)

    skor_literasi = st.slider("Skor literasi keuangan (0–100)", 0, 100, 50)
    cicilan = st.number_input("Cicilan hutang/bulan (Rp)", min_value=0, max_value=10_000_000, value=0, step=100_000, format="%d")

    prediksi_btn = st.button("🔮 Prediksi Sekarang", use_container_width=True, type="primary")

# ── Prediksi ─────────────────────────────────────────────────────────
with col_hasil:
    st.subheader("📊 Hasil Prediksi Bulan Depan")

    if prediksi_btn:
        input_data = {
            'pendapatan_bulanan':   pendapatan,
            'usia':                 usia,
            'jumlah_tanggungan':    tanggungan,
            'provinsi':             le_dict['provinsi'].transform([provinsi])[0],
            'klasifikasi_wilayah':  le_dict['klasifikasi_wilayah'].transform([wilayah])[0],
            'status_pekerjaan':     le_dict['status_pekerjaan'].transform([pekerjaan])[0],
            'pendidikan_terakhir':  le_dict['pendidikan_terakhir'].transform([pendidikan])[0],
            'status_pernikahan':    le_dict['status_pernikahan'].transform([pernikahan])[0],
            'jenis_kelamin':        le_dict['jenis_kelamin'].transform([jenis_kel])[0],
            'skor_literasi_keuangan': skor_literasi,
            'cicilan_hutang':       cicilan,
        }
        X_input = pd.DataFrame([input_data])[feature_cols]

        hasil = {}
        for target in target_cols:
            pred = models[target].predict(X_input)[0]
            hasil[target] = max(0, round(pred / 1000) * 1000)

        total_pred  = hasil['total_pengeluaran']
        sisa_uang   = pendapatan - total_pred
        rasio_tabungan = (sisa_uang / pendapatan * 100) if pendapatan > 0 else 0

        # Kartu total pengeluaran
        st.markdown(f"""
        <div class="total-card">
            <div class="total-label">Prediksi Total Pengeluaran</div>
            <div class="total-value">Rp {total_pred:,.0f}</div>
        </div>
        """, unsafe_allow_html=True)

        # Kartu sisa uang
        warna_sisa = '#1D9E75' if sisa_uang > 0 else '#E24B4A'
        label_sisa = 'Estimasi Tabungan' if sisa_uang > 0 else 'Estimasi Defisit'
        st.markdown(f"""
        <div class="sisa-card" style="background:{warna_sisa}">
            <div class="total-label">{label_sisa}</div>
            <div class="total-value">Rp {abs(sisa_uang):,.0f}</div>
            <div style="font-size:0.85rem;opacity:0.85;margin-top:4px">{rasio_tabungan:.1f}% dari pendapatan</div>
        </div>
        """, unsafe_allow_html=True)

        # Status keuangan
        if rasio_tabungan >= 20:
            status = ("✅ Keuangan Sehat", "#1D9E75")
        elif rasio_tabungan >= 10:
            status = ("🟡 Cukup Baik", "#BA7517")
        elif rasio_tabungan >= 0:
            status = ("⚠️ Perlu Perbaikan", "#D85A30")
        else:
            status = ("🔴 Keuangan Kritis", "#E24B4A")

        st.markdown(f"""
        <div style="text-align:center;margin-bottom:1rem;">
            <span class="status-badge" style="background:{status[1]}22;color:{status[1]};border:1px solid {status[1]}">
                {status[0]}
            </span>
        </div>
        """, unsafe_allow_html=True)

        st.markdown("**Breakdown per Kategori:**")

        # Tampilkan breakdown (tanpa total)
        for target in target_cols:
            if target == 'total_pengeluaran':
                continue
            nilai  = hasil[target]
            persen = (nilai / total_pred * 100) if total_pred > 0 else 0
            warna  = warna_kategori.get(target, '#888780')
            label  = label_map.get(target, target)

            st.markdown(f"""
            <div class="metric-card">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="metric-label">{label}</span>
                    <span style="font-size:0.8rem;color:#999">{persen:.1f}%</span>
                </div>
                <div class="metric-value" style="color:{warna}">Rp {nilai:,.0f}</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width:{min(persen,100):.1f}%;background:{warna}"></div>
                </div>
            </div>
            """, unsafe_allow_html=True)

    else:
        st.info("👈 Isi profil keuangan di sebelah kiri, lalu klik **Prediksi Sekarang**")
        st.markdown("""
        **Cara penggunaan:**
        1. Isi pendapatan bulanan
        2. Lengkapi profil demografis
        3. Klik tombol Prediksi
        4. Lihat estimasi pengeluaran per kategori
        """)