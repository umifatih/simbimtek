<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #0F2A43; padding: 30px; }
        .kop { text-align: center; border-bottom: 2px solid #0F2A43; padding-bottom: 12px; margin-bottom: 20px; }
        .kop h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        .kop p { font-size: 11px; margin: 2px 0 0; color: #555; }
        .judul { text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline; margin-bottom: 4px; }
        .nomor { text-align: center; font-size: 11px; margin-bottom: 20px; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data td { padding: 5px 0; font-size: 12px; vertical-align: top; }
        table.data td.label { width: 160px; color: #555; }
        table.data td.titik { width: 15px; }
        .catatan { margin-top: 20px; padding: 10px; background: #f5f5f5; border-left: 3px solid #C99A3D; font-size: 11px; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>Sistem Informasi Manajemen Bimtek</h1>
        <p>Bukti Pendaftaran Peserta Bimbingan Teknis</p>
    </div>

    <div class="judul">BUKTI PENDAFTARAN</div>
    <div class="nomor">Nomor: {{ $pendaftaran->nomor_pendaftaran }}</div>

    <table class="data">
        <tr>
            <td class="label">Nama dan Gelar</td><td class="titik">:</td>
            <td>{{ $pendaftaran->nama_gelar }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td><td class="titik">:</td>
            <td>{{ $pendaftaran->nip }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td><td class="titik">:</td>
            <td>{{ $pendaftaran->jabatan }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td><td class="titik">:</td>
            <td>{{ strtoupper($pendaftaran->unit_kerja) }}</td>
        </tr>
        <tr>
            <td class="label">Kegiatan</td><td class="titik">:</td>
            <td>{{ $pendaftaran->kegiatan->nama }}</td>
        </tr>
        <tr>
            <td class="label">Jadwal</td><td class="titik">:</td>
            <td>{{ $pendaftaran->kegiatan->hari_tanggal }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi</td><td class="titik">:</td>
            <td>{{ $pendaftaran->kegiatan->lokasi }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Daftar</td><td class="titik">:</td>
            <td>{{ $pendaftaran->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <div class="catatan">
        Dokumen ini adalah bukti bahwa data di atas telah tercatat dalam sistem SIMBIMTEK.
        Surat Tugas dan SPPD dapat diunduh terpisah melalui halaman Cek Status.
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>
</body>
</html>