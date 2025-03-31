<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document PDF </title>
    <style>
        @page {
            size: 710pt 950pt;
        }
    </style>
</head>
<body>
    @if(!empty( $pdf_document->msg)) {!! $pdf_document->msg !!} @endif
</body>
</html>
