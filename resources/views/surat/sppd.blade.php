<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color:#000; }
        table { border-collapse: collapse; width:100%; }
        .center { text-align:center; }
        .judul { text-decoration:underline; font-weight:bold; font-size:13px; }
    </style>
</head>
<body>
    @include('surat.partials.kop', ['pendaftaran' => $pendaftaran])

    <div class="center">
        <div class="judul">SURAT PERINTAH PERJALANAN DINAS</div>
        <div>Nomor : ......./{{ $kegiatan->tanggal_mulai->format('Y') }}</div>
    </div>

    <table style="margin-top:14px;">
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
        Untuk melakukan perjalanan dinas ke lokasi kegiatan {{ $kegiatan->nama }} dengan ketentuan sebagai berikut:
    </p>

    <table style="margin-left:30px;">
        <tr><td style="width:110px;">Hari, tanggal</td><td style="width:15px;">:</td><td>{{ $kegiatan->hari_tanggal }}</td></tr>
        <tr><td>Waktu</td><td>:</td><td>Pukul {{ substr($kegiatan->waktu, 0, 5) }} WIB s.d. selesai</td></tr>
        <tr><td>Tempat</td><td>:</td><td>{{ $kegiatan->lokasi }}</td></tr>
    </table>

    <table style="margin-top:30px;">
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

    <div style="page-break-before: always;"></div>

    {{-- ===== RINCIAN PERJALANAN (dinamis, ikut jumlah hari kegiatan) ===== --}}
    @php
        if (!function_exists('angkaRomawi')) {
            function angkaRomawi(int $angka): string
            {
                $map = ['M'=>1000,'CM'=>900,'D'=>500,'CD'=>400,'C'=>100,'XC'=>90,'L'=>50,'XL'=>40,'X'=>10,'IX'=>9,'V'=>5,'IV'=>4,'I'=>1];
                $hasil = '';
                foreach ($map as $roman => $nilai) {
                    while ($angka >= $nilai) {
                        $hasil .= $roman;
                        $angka -= $nilai;
                    }
                }
                return $hasil;
            }
        }

        $mulai = $kegiatan->tanggal_mulai;
        $selesai = $kegiatan->tanggal_selesai;
        $periode = \Carbon\CarbonPeriod::create($mulai, $selesai);
        $jumlahHari = $mulai->diffInDays($selesai) + 1;
    @endphp

    <table style="width:100%; border-collapse:collapse; font-size:12px;">
        <tr>
            <td style="border:1px solid #000; width:50%; padding:8px;"></td>
            <td style="border:1px solid #000; width:50%; padding:8px; vertical-align:top;">
                I. Berangkat dari&nbsp;: {{ $pendaftaran->unit_kerja }}<br>
                Ke&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $kegiatan->lokasi }}<br>
                Pada Tanggal&nbsp;: {{ $mulai->translatedFormat('d F Y') }}
                <br><br><br>
                Kepala
                <br><br><br><br>
                <strong>{{ $pendaftaran->nama_gelar_kepsek }}</strong><br>
                NIP. {{ $pendaftaran->nip_kepsek ?? '-' }}
            </td>
        </tr>

        @foreach ($periode as $index => $tanggal)
            <tr>
                <td style="border:1px solid #000; padding:8px; vertical-align:top;">
                    {{ angkaRomawi($index + 2) }}. Tiba di&nbsp;: {{ $kegiatan->lokasi }}<br>
                    Pada Tanggal&nbsp;: {{ $tanggal->translatedFormat('d F Y') }}<br>
                    Ketua Panitia
                    <br><br><br><br>
                    <strong>{{ $kegiatan->nama_panitia }}</strong><br>
                    NIP {{ $kegiatan->nip_panitia }}
                </td>
                <td style="border:1px solid #000; padding:8px; vertical-align:top;">
                    Berangkat dari&nbsp;: {{ $kegiatan->lokasi }}<br>
                    Ke&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $pendaftaran->unit_kerja }}<br>
                    Pada Tanggal&nbsp;: {{ $tanggal->translatedFormat('d F Y') }}<br>
                    Ketua Panitia
                    <br><br><br><br>
                    <strong>{{ $kegiatan->nama_panitia }}</strong><br>
                    NIP {{ $kegiatan->nip_panitia }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td style="border:1px solid #000; padding:8px; vertical-align:top;">
                {{ angkaRomawi($jumlahHari + 2) }}. Tiba di&nbsp;: {{ $pendaftaran->unit_kerja }}<br>
                Pada Tanggal&nbsp;: {{ $selesai->translatedFormat('d F Y') }}
                <br><br>
                Kepala {{ $pendaftaran->unit_kerja }}
                <br><br><br><br>
                <strong>{{ $pendaftaran->nama_gelar_kepsek }}</strong><br>
                NIP. {{ $pendaftaran->nip_kepsek ?? '-' }}
            </td>
            <td style="border:1px solid #000; padding:8px; vertical-align:top; font-size:11px;">
                Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas
                perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu
                yang sesingkat-singkatnya
                <br><br>
                Kepala {{ $pendaftaran->unit_kerja }}
                <br><br><br><br>
                <strong>{{ $pendaftaran->nama_gelar_kepsek }}</strong><br>
                NIP. {{ $pendaftaran->nip_kepsek ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>