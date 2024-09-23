<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>PDF Generator</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        @page {
            margin: 0cm 0cm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        body {
            margin-top: 5cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 3cm;
        }

        table.bordered {
            border-left: 0.01em solid #ccc;
            border-right: 0;
            border-top: 0.01em solid #ccc;
            border-bottom: 0;
            border-collapse: collapse;
        }
        table.bordered td,
        table.bordered th {
            border-left: 0;
            border-right: 0.01em solid #ccc;
            border-top: 0.01em solid #ccc;
            border-bottom: 0.01em solid #ccc;
        }
        td { padding: 1mm; }
        table.bordered-left td { border-left: 0.01em solid #ccc !important; }
        th { padding: 1mm; }
        .bordered {
            box-sizing: border-box;
            border: solid #000 0.1mm;
        }
        .no-border {
            border: none;
        }
        .box {
            position: absolute;
            margin: 0cm !important;
            padding: 0cm !important;
        }
        .absolute { position: absolute; }
        .text-capitalize { text-transform: capitalize; }
        .text-lowercase { text-transform: lowercase; }
        .text-uppercase { text-transform: uppercase; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .full-width { width: 100% }
        .font-size-6 { font-size: 6pt }
        .font-size-7 { font-size: 7pt }
        .font-size-8 { font-size: 8pt }
        .font-size-9 { font-size: 9pt }
        .font-size-10 { font-size: 10pt }
        .font-size-11 { font-size: 11pt }
        .font-size-12 { font-size: 12pt }
        .font-size-13 { font-size: 13pt }
        .font-size-14 { font-size: 14pt }
        small { font-size: 6pt }
        h1 { font-size: 14pt; }
        h2 { font-size: 12pt; }
        h3 { font-size: 10pt; }
        h4 { font-size: 8pt; }
        h5 { font-size: 6pt; }

        header {
            position: fixed;
            padding: 5mm;
            background-color: #0a66b7 !important;
        }

        footer {
            position: fixed;
            bottom: 18mm;
            padding: 5mm;
            min-height: 2cm;
        }

    </style>
    @yield('style')
</head>
<body>
@yield('content')
</body>
