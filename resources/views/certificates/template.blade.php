<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }
        .container {
            width: 270mm;
            height: 185mm;
            border: 5px solid {{ $config->primary_color ?? '#1e3a8a' }};
            box-sizing: border-box;
            padding: 3mm;
            position: absolute;
            top: 12mm;
            left: 13mm;
        }
        .inner-border {
            width: 100%;
            height: 100%;
            border: 2px solid {{ $config->secondary_color ?? '#d4af37' }};
            box-sizing: border-box;
        }
        
        .content-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .content-cell {
            vertical-align: middle;
            text-align: center;
        }

        .title {
            font-size: 34pt;
            font-weight: bold;
            color: {{ $config->primary_color ?? '#1e3a8a' }};
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }
        .cert-number {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5mm;
        }
        .gold-line {
            height: 2px;
            background-color: {{ $config->secondary_color ?? '#d4af37' }};
            width: 80px;
            margin: 0 auto 5mm auto;
        }
        
        .issuer {
            font-size: 11pt;
            color: {{ $config->primary_color ?? '#1e3a8a' }};
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2mm;
        }
        .desc {
            font-size: 10pt;
            color: #444;
            margin-bottom: 5mm;
        }
        
        .student-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1a1a1a;
            font-style: italic;
            border-bottom: 2px solid {{ $config->secondary_color ?? '#d4af37' }};
            padding: 0 40px 5px 40px;
            display: inline-block;
            margin-bottom: 5mm;
        }
        
        .course-label { font-size: 10pt; color: #555; margin-bottom: 2mm; }
        .course-title {
            font-size: 16pt;
            font-weight: bold;
            color: {{ $config->primary_color ?? '#1e3a8a' }};
            margin-bottom: 3mm;
        }
        .course-desc {
            font-size: 9pt;
            color: #555;
            max-width: 80%;
            margin: 0 auto 10mm auto;
        }
        
        .date {
            font-size: 10pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 12mm;
        }

        .sig-table {
            width: 100%;
            margin-bottom: 5mm;
            border-collapse: collapse;
        }
        
        /* Revised Footer Layout: 22% | 28% | 28% | 22% */
        .col-spacer { width: 22%; }
        .col-sig { width: 28%; vertical-align: bottom; text-align: center; }
        .col-qr { width: 22%; vertical-align: bottom; text-align: center; }

        .sig-title {
            font-size: 9pt;
            color: #666;
            margin-bottom: 30px; 
        }
        .sig-name {
            font-size: 10pt;
            font-weight: bold;
            color: #000;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 160px;
        }
        
        .qr-img { width: 60px; height: 60px; }
        .qr-text { font-size: 7pt; color: #666; margin-top: 5px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-border">
            <table class="content-table">
                <tr>
                    <td class="content-cell">
                        <div class="title">{{ $config->certificate_title ?? 'SERTIFIKAT' }}</div>
                        <div class="cert-number">No. {{ $certificate->certificate_number }}</div>
                        <div class="gold-line"></div>
                        
                        <div class="issuer" style="text-transform: capitalize; font-style: italic;">Bismillahirrahmanirrahim</div>
                        <div class="desc">{{ $config->certificate_text ?? 'Dengan bangga memberikan sertifikat ini kepada:' }}</div>
                        
                        <div class="student-name">{{ $user->name }}</div>
                        
                        <div class="course-label">Atas kelulusannya dalam kursus:</div>
                        <div class="course-title">{{ $course->title }}</div>
                        <div class="course-desc">
                            Telah menyelesaikan seluruh materi pembelajaran dan tugas yang diberikan dengan hasil yang memuaskan.
                        </div>
                        
                        <div class="date">
                            {{ $certificate->issued_at->locale('id')->translatedFormat('d F Y') }}
                        </div>
                        
                        <table class="sig-table">
                            <tr>
                                <!-- Left Spacer -->
                                <td class="col-spacer"></td>
                                
                                <!-- Signature 1 -->
                                <td class="col-sig">
                                    @if($signatures && $signatures->count() > 0)
                                        <div class="sig-title">{{ $signatures[0]->signatory_title }}</div>
                                        <div class="sig-name">{{ $signatures[0]->signatory_name }}</div>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                                
                                <!-- Signature 2 -->
                                <td class="col-sig">
                                    @if($signatures && $signatures->count() > 1)
                                        <div class="sig-title">{{ $signatures[1]->signatory_title }}</div>
                                        <div class="sig-name">{{ $signatures[1]->signatory_name }}</div>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                                
                                <!-- QR Code -->
                                <td class="col-qr">
                                    @if($config && $config->show_qr_code && isset($qrCode))
                                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-img" alt="QR">
                                        <span class="qr-text">Scan Validasi</span>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
