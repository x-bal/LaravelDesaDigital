<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="yoriadiatma">
    <link rel="icon" href="">
    <title>Antrian No - {{ $antrian->no_antrian }}</title>
    <style>
        @page {
            margin: 0
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12;
        }

        .table-kop tr td {
            padding: 5px;
        }

        .italic {
            font-style: italic;
        }

        .sheet {
            overflow: hidden;
            position: relative;
            display: block;
            margin: 0 auto;
            box-sizing: border-box;
            page-break-after: always;
        }

        /** Paper sizes **/
        body.A3 .sheet {
            width: 297mm;
            height: 419mm
        }

        body.A3.landscape .sheet {
            width: 420mm;
            height: 296mm
        }

        body.A4 .sheet {
            width: 210mm;
            height: 296mm
        }

        body.struk .sheet {
            width: 100mm;
        }

        body.A4.landscape .sheet {
            width: 297mm;
            height: 209mm
        }

        body.A5 .sheet {
            width: 148mm;
            height: 209mm
        }

        body.A5.landscape .sheet {
            width: 210mm;
            height: 147mm
        }

        /** Padding area **/
        .sheet.padding-2mm {
            padding-top: 2mm;
            padding-bottom: 2mm;
            padding-left: 15mm;
            padding-right: 15mm;
        }

        .sheet.padding-10mm {
            padding: 10mm
        }

        .sheet.padding-15mm {
            padding: 15mm
        }

        .sheet.padding-20mm {
            padding: 20mm
        }

        .sheet.padding-25mm {
            padding: 25mm
        }

        /** For screen preview **/
        @media screen {
            body {
                background: #e0e0e0
            }

            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
                margin: 5mm auto;
                display: block;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.struk .sheet {
                width: 100mm;
            }

            body.A3.landscape {
                width: 420mm
            }

            body.A3,
            body.A4.landscape {
                width: 297mm
            }

            body.A4,
            body.A5.landscape {
                width: 210mm
            }

            body.A5 {
                width: 148mm
            }
        }
    </style>
</head>

<body class="struk">
    <section class="sheet padding-2mm">
        <div class="header" style="text-align: center;">
            <img src="{{ asset('storage/' . $antrian->desa->logo) }}" alt="" width="70px">
            <p>
                <span style="font-size: 20px; text-transform: uppercase;">{{ $antrian->desa->nama_desa }}</span><br>
                <span style="font-size: 12px;">{{ $antrian->desa->alamat }}</span>
            </p>
            <p style="border-bottom: 1px solid black;"></p>
        </div>

        <div class="content" style="text-align: center;">
            <p>
                <span style="text-transform: uppercase; font-size: 12px;">Nomor antrian anda : </span><br><br>
                <span style="text-transform: uppercase; font-size: 40px; font-weight: bold;">{{ str_pad($antrian->no_antrian, 3, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>

        <div class="footer" style="font-size: 12px; margin-top: 40px; padding-bottom: 50px;">
            <table>
                <tr>
                    <td style="text-align: left;">{{
                        \Carbon\Carbon::parse($antrian->tanggal_antri)->setTimezone('Asia/Jakarta')->format('D, M Y')
                     }}</td>
                    <td width="100px" style="text-align: center;">Loket {{ $antrian->loket->nama }}</td>
                    <td width="70px" style=" text-align: end !important;">{{ \Carbon\Carbon::parse($antrian->created_at)->format('H:i:s') }}</td>
                </tr>
            </table>

            <span>SISA ANTRIAN : {{ $sisa }}</span>

            <div style="border-bottom: 1px solid black; margin-top: 10px;"></div>
            <p style="text-align: center; margin-top: 30px; font-size: 14px;">
                <span>Terima Kasih</span>
            </p>
        </div>
    </section>
</body>


</html>