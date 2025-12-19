<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Technical Submittal - Fire Door Assemblies</title>
  <style>
    /* Use a PDF-safe font family; DejaVu Sans is commonly available to PDF renderers */
    :root{--base-font: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif; --base-size:12px}
    html,body{font-family:var(--base-font);font-size:var(--base-size);color:#111;margin:0;padding:0}
    .page{padding:28px 32px;box-sizing:border-box}
    header{margin-bottom:18px}
    h1{font-size:18px;margin:0 0 6px}
    h2{font-size:14px;margin:12px 0}
    p{margin:8px 0;line-height:1.5}
    .lead{font-size:13px}

    /* project summary table */
    .summary{width:100%;border-collapse:collapse;margin:8px 0 14px}
    .summary td{border:1px solid #ddd;padding:8px;vertical-align:top}
    .summary td:first-child{width:30%;font-weight:700;background:#f8f8f8}

    ul{margin:6px 0 12px 20px}
    li{margin:6px 0}

    .section{margin:12px 0}
    .standards-list{display:block;margin-left:0;padding-left:18px}

    /* small footer */
    footer{margin-top:24px;font-size:11px;color:#444}

    /* responsive for on-screen preview */
    @media screen and (max-width:600px){.page{padding:18px}}

    /* utility */
    .muted{color:#666;font-size:11px}
    .boxed{border:1px solid #eee;padding:10px;background:#fff}
  </style>
</head>
<body>
  <div class="page">
    <header>
      <h1>Technical Submittal — Fire Door Assemblies</h1>
      <p class="muted">Generated: {{ now()->toDateString() }}</p>
    </header>

    <section>
      <p class="lead">This technical submittal outlines the specification, certification, installation methodology, and ongoing compliance requirements for the fire door assemblies proposed for the <strong>{{ $data['project_name'] ?? '' }}</strong> project.</p>

      <p>All doorsets included in this package have been selected in accordance with the project fire strategy and are designed, manufactured, and to be installed in compliance with:</p>

      <ul class="standards-list">
        @php
          $defaultStandards = ['BS 476 Part 22','EN 1634-1','BS 8214:2016 (Installation of Timber-based Fire Doors)'];
          $standardsList = $standards ?? $defaultStandards;
        @endphp
        @foreach($standardsList as $std)
          <li><strong>{{ $std }}</strong></li>
        @endforeach
        <li>The applicable <strong>Field of Application (FoA)</strong> documents as listed in Section 7</li>
        @php $certs = $certifications ?? ['Certifire','Q-Mark','IFC']; @endphp
        <li>Certification schemes such as: <strong>{{ implode(', ', $certs) }}</strong></li>
      </ul>
    </section>

    <section class="section">
      <h2>Project Summary</h2>
      <table class="summary">
        <tr>
          <td>Client / Contractor</td>
          <td>{{ $data['client_contractor'] ?? '' }}</td>
        </tr>
        <tr>
          <td>Site Address</td>
          <td>{{ $data['site_address'] ?? '' }}</td>
        </tr>
        <tr>
          <td>Number of Doorsets</td>
          <td>{{ $data['NumberOfDoorSets'] ?? '' }}</td>
        </tr>
        <tr>
          <td>Fire Rating</td>
          <td>FD30 / FD60</td>
        </tr>
        <tr>
          <td>Core Type(s)</td>
          <td>{{ $data['configurationItemName'] ?? ' ' }}</td>
        </tr>
        <tr>
          <td>Frame Type</td>
          <td>{{ '[Softwood / Hardwood / MDF / Steel / Composite]' }}</td>
        </tr>
      </table>
    </section>

    <section class="section">
      <h2>Verification &amp; Field of Application</h2>
      <p>Each doorset type has been verified against valid and current Field of Application documentation to ensure:</p>
      <ul>
        <li>Correct leaf and frame configuration</li>
        <li>Approved hardware sets</li>
        <li>Suitable glazing systems and glass types (if applicable)</li>
        <li>Approved intumescent sealing systems</li>
      </ul>

      <p>All supporting documentation—test evidence, Declarations of Performance (DoPs), maintenance instructions, and photographic records—has been included within this submission and referenced in the document index below.</p>
    </section>

    <section class="section">
      <h2>Additional Documentation Included</h2>
      <ul>
        <li>U-value calculations: {!! $uValueHtml ?? '[U-value calculations inserted here]' !!}</li>
        <li>BREEAM supporting documents: {{ $breeamDocs ?? '[List of BREEAM docs]' }}</li>
        <li>BCAR ancillary certification: {{ $bcarCert ?? '[BCAR if required]' }}</li>
      </ul>
    </section>

    <section class="section boxed">
      <p>The intent of this package is to demonstrate full traceability and compliance in line with building regulations and project fire safety requirements. All installations will be carried out by competent personnel following manufacturer and industry best practice, and will be subject to inspection and certification under the project's QA procedure.</p>
    </section>
  </div>
</body>
</html>
