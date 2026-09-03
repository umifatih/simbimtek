<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color:#000; }
        table { border-collapse: collapse; }
        .center { text-align:center; }
        .judul { text-decoration:underline; font-weight:bold; font-size:13px; }
    </style>
</head>
<body>
    @include('surat.partials.kop', ['pendaftaran' => $pendaftaran])

    <div class="center">
        <div class="judul">SURAT TUGAS</div>
        <div>Nomor : ......./{{ $kegiatan->tanggal_mulai->format('Y') }}</div>
    </div>

    <table style="width:100%; margin-top:14px;">
        <tr>
            <td style="width:70px; vertical-align:top;">Dasar</td>
            <td style="width:15px; vertical-align:top;">:</td>
            <td style="text-align:justify;">
                Surat pemberitahuan dan undangan dari panitia kegiatan Nomor
                {{ $kegiatan->nomor_surat_dasar }} tanggal
                {{ optional($kegiatan->tanggal_surat_dasar)->translatedFormat('d F Y') }},
                Perihal {{ $kegiatan->nama }}.
            </td>
        </tr>
    </table>

    <p style="margin-top:14px;">Dengan ini menugaskan kepada :</p>

    <table style="margin-left:30px; margin-top:6px;">
        <tr><td style="width:100px;">Nama</td><td style="width:15px;">:</td><td>{{ $pendaftaran->nama_gelar }}</td></tr>
        <tr><td>NIP</td><td>:</td><td>{{ $pendaftaran->nip }}</td></tr>
        <tr><td>Jabatan</td><td>:</td><td>{{ $pendaftaran->jabatan }}</td></tr>
    </table>

    <p style="margin-top:14px; text-align:justify;">
        Untuk mengikuti kegiatan {{ $kegiatan->nama }} dengan ketentuan sebagai berikut:
    </p>

    <table style="margin-left:30px;">
        <tr><td style="width:110px;">Hari, tanggal</td><td style="width:15px;">:</td><td>{{ $kegiatan->hari_tanggal }}</td></tr>
        <tr><td>Waktu</td><td>:</td><td>Pukul {{ substr($kegiatan->waktu, 0, 5) }} WIB s.d. selesai</td></tr>
        <tr><td>Tempat</td><td>:</td><td>{{ $kegiatan->lokasi }}</td></tr>
    </table>

    <p style="margin-top:20px;">Demikian untuk menjadikan perhatian dan dilaksanakan dengan penuh tanggung jawab.</p>

    <table style="width:100%; margin-top:30px;">
        <tr>
            <td style="width:60%;"></td>
            <td style="text-align:center;">
                Mandiraja, {{ $kegiatan->tanggal_mulai->translatedFormat('d F Y') }}<br>
                Kepala Sekolah
                <br><br><br><br>
                <strong>{{ $pendaftaran->nama_gelar_kepsek }}</strong><br>
                NIP. {{ $pendaftaran->nip_kepsek ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>