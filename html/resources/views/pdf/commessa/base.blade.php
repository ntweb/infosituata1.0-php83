<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>PDF Generator</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        @page {
            margin: 0cm 0cm;
        }

        header {
            position: fixed;
            margin: 5mm;
            margin-left: 10mm;
            margin-right: 10mm;
            /*background-color: aquamarine;*/
            height: 2cm;
        }

        footer {
            position: fixed;
            bottom: 0mm;
            margin: 5mm;
            margin-top: 0mm;
            margin-left: 10mm;
            margin-right: 10mm;
            height: 1.5cm;
            /*background-color: aquamarine;*/
        }

        * {
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }
        p {
            line-height: 6mm;
        }
        ul, ol {
            margin-left: 10mm;
            margin-right: 10mm;
        }
        ul li { margin-top: 1.2mm; margin-bottom: 1.2mm;}
        ol li { margin-top: 1.2mm; margin-bottom: 1.2mm;}
        .page-break {
            page-break-after: always;
        }
        body {
            margin-top: 3cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 3cm;
        }
        table.bordered {
            border-left: 0.01em solid #000;
            border-right: 0;
            border-top: 0.01em solid #000;
            border-bottom: 0;
            border-collapse: collapse;
        }
        table.bordered td,
        table.bordered th {
            border-left: 0;
            border-right: 0.01em solid #000;
            border-top: 0.01em solid #000;
            border-bottom: 0.01em solid #000;
        }
        td { padding: 1mm; }
        th { padding: 1mm; }
        .vertical-align-top {
            vertical-align: top;
        }
        .print-friendly {
            page-break-inside: avoid;
        }
        .bordered {
            box-sizing: border-box;
            border: solid #000 0.2mm;
        }
        .box {
            position: absolute;
            margin: 0cm !important;
            padding: 0cm !important;
        }
        .absolute { position: absolute; }
        .fixed { position: absolute; }
        .text-capitalize { text-transform: capitalize; }
        .text-lowercase { text-transform: lowercase; }
        .text-uppercase { text-transform: uppercase; }
        .text-normal { font-weight: normal; }
        .text-bold { font-weight: bold; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .full-width { width: 100% }
        .font-size-4 { font-size: 4pt }
        .font-size-5 { font-size: 5pt }
        .font-size-6 { font-size: 6pt }
        .font-size-7 { font-size: 7pt }
        .font-size-8 { font-size: 8pt }
        .font-size-9 { font-size: 9pt }
        .font-size-10 { font-size: 10pt }
        .font-size-11 { font-size: 11pt }
        .font-size-12 { font-size: 12pt }
        .font-size-13 { font-size: 13pt }
        .font-size-14 { font-size: 14pt }
        .m-0 { margin: 0; }
        .m-1 { margin: 1mm; }
        .m-2 { margin: 2mm; }
        .m-3 { margin: 3mm; }
        .m-4 { margin: 4mm; }
        .m-5 { margin: 5mm; }
        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: 1mm; }
        .mt-2 { margin-top: 2mm; }
        .mt-3 { margin-top: 3mm; }
        .mt-4 { margin-top: 4mm; }
        .mt-5 { margin-top: 5mm; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 1mm; }
        .mb-2 { margin-bottom: 2mm; }
        .mb-3 { margin-bottom: 3mm; }
        .mb-4 { margin-bottom: 4mm; }
        .mb-5 { margin-bottom: 5mm; }
        .p-0 { padding: 0; }
        .p-1 { padding: 1mm; }
        .p-2 { padding: 2mm; }
        .p-3 { padding: 3mm; }
        .p-4 { padding: 4mm; }
        .p-5 { padding: 5mm; }
        .pt-0 { padding-top: 0; }
        .pt-1 { padding-top: 1mm; }
        .pt-2 { padding-top: 2mm; }
        .pt-3 { padding-top: 3mm; }
        .pt-4 { padding-top: 4mm; }
        .pt-5 { padding-top: 5mm; }
        .pb-0 { padding-bottom: 0; }
        .pb-1 { padding-bottom: 1mm; }
        .pb-2 { padding-bottom: 2mm; }
        .pb-3 { padding-bottom: 3mm; }
        .pb-4 { padding-bottom: 4mm; }
        .pb-5 { padding-bottom: 5mm; }
        small { font-size: 6pt }
        h1 { font-size: 14pt; }
        h2 { font-size: 12pt; }
        h3 { font-size: 10pt; }
        h4 { font-size: 8pt; }
        h5 { font-size: 6pt; }
        .bgLightGrey { background: #F7F7F7; }

        main {
            /*background-color: #0c525d;*/
        }

        .wireframe { background: rgba(255,10,25,0.5); }

    </style>
    @yield('style')
</head>
<body>

@yield('content')

</body>
