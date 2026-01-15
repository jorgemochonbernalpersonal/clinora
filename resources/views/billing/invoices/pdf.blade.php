<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm 2cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.4;
            color: #1f2937;
            font-size: 10pt;
            position: relative;
        }
        
        /* Header */
        .pdf-header {
            position: fixed;
            top: -1.5cm;
            left: 0;
            right: 0;
            height: 1.5cm;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pdf-header .brand {
            font-size: 16pt;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
        }
        .pdf-header .invoice-number {
            font-size: 12pt;
            color: white;
            font-weight: bold;
        }
        
        /* Footer */
        .pdf-footer {
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            right: 0;
            height: 1.5cm;
            border-top: 2px solid #e5e7eb;
            padding: 8px 20px;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }
        
        /* Content */
        .content {
            margin-top: 1.5cm;
            margin-bottom: 1.5cm;
        }
        
        /* Invoice Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .invoice-info {
            flex: 1;
        }
        
        .invoice-title {
            font-size: 24pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .invoice-meta {
            font-size: 9pt;
            color: #6b7280;
            line-height: 1.8;
        }
        
        .invoice-dates {
            text-align: right;
            font-size: 9pt;
        }
        
        .invoice-dates strong {
            color: #1f2937;
        }
        
        /* Parties */
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 30px;
        }
        
        .party {
            flex: 1;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .party-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .party-info {
            font-size: 9pt;
            line-height: 1.8;
            color: #374151;
        }
        
        .party-info strong {
            color: #1f2937;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background: #1e40af;
            color: white;
        }
        
        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
        }
        
        .items-table tbody tr:hover {
            background: #f9fafb;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        /* Totals */
        .totals {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 9pt;
        }
        
        .total-row.subtotal {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .total-row.irpf {
            color: #6b7280;
        }
        
        .total-row.final {
            border-top: 2px solid #1e40af;
            padding-top: 15px;
            margin-top: 10px;
            font-size: 12pt;
            font-weight: bold;
            color: #1e40af;
        }
        
        /* Notes */
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }
        
        .notes-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .notes-content {
            font-size: 9pt;
            color: #374151;
            line-height: 1.6;
        }
        
        /* Legal */
        .legal-info {
            margin-top: 30px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            font-size: 8pt;
            color: #92400e;
            line-height: 1.6;
        }
        
        .legal-info strong {
            display: block;
            margin-bottom: 5px;
        }
        
        /* Page break */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="pdf-header">
        <div class="brand">CLINORA</div>
        <div class="invoice-number">FACTURA {{ $invoice->invoice_number }}</div>
    </div>
    
    <!-- Footer -->
    <div class="pdf-footer">
        <div>Esta factura cumple con la normativa VeriFactu (AEAT) - Generada el {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <!-- Content -->
    <div class="content">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="invoice-info">
                <div class="invoice-title">FACTURA</div>
                <div class="invoice-meta">
                    <strong>Número:</strong> {{ $invoice->invoice_number }}<br>
                    <strong>Serie:</strong> {{ $invoice->series }}<br>
                    @if($invoice->appointment)
                    <strong>Referencia Cita:</strong> #{{ $invoice->appointment->id }}
                    @endif
                </div>
            </div>
            <div class="invoice-dates">
                <div><strong>Fecha de emisión:</strong><br>{{ $invoice->issue_date->format('d/m/Y') }}</div>
                <div style="margin-top: 10px;"><strong>Fecha de vencimiento:</strong><br>{{ $invoice->due_date->format('d/m/Y') }}</div>
            </div>
        </div>
        
        <!-- Parties -->
        <div class="parties">
            <!-- Seller (Professional) -->
            <div class="party">
                <div class="party-title">Emisor</div>
                <div class="party-info">
                    <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                    @if($professional->address_street)
                    {{ $professional->address_street }}<br>
                    @endif
                    @if($professional->address_postal_code || $professional->address_city)
                    {{ $professional->address_postal_code }} {{ $professional->address_city }}<br>
                    @endif
                    @if($user->email)
                    Email: {{ $user->email }}<br>
                    @endif
                    @if($user->phone)
                    Tel: {{ $user->phone }}<br>
                    @endif
                    @if($user->dni)
                    DNI/NIF: {{ $user->dni }}<br>
                    @endif
                    @if($professional->license_number)
                    Colegiado: {{ $professional->license_number }}
                    @endif
                </div>
            </div>
            
            <!-- Buyer (Contact) -->
            <div class="party">
                <div class="party-title">Cliente</div>
                <div class="party-info">
                    <strong>{{ $contact->first_name }} {{ $contact->last_name }}</strong><br>
                    @if($contact->email)
                    Email: {{ $contact->email }}<br>
                    @endif
                    @if($contact->phone)
                    Tel: {{ $contact->phone }}<br>
                    @endif
                    @if($contact->dni)
                    DNI/NIF: {{ $contact->dni }}<br>
                    @endif
                    @if($invoice->is_b2b)
                    <strong>Tipo:</strong> Empresa (B2B)
                    @else
                    <strong>Tipo:</strong> Particular
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 50%;">Descripción</th>
                    <th style="width: 10%;" class="text-center">Cantidad</th>
                    <th style="width: 15%;" class="text-right">Precio Unit.</th>
                    <th style="width: 20%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->description }}
                        @if($item->notes)
                        <br><small style="color: #6b7280;">{{ $item->notes }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
                    <td class="text-right"><strong>{{ number_format($item->subtotal, 2, ',', '.') }} €</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals">
            <div class="total-row subtotal">
                <span>Base Imponible:</span>
                <span><strong>{{ number_format($invoice->subtotal, 2, ',', '.') }} €</strong></span>
            </div>
            
            @if($invoice->is_iva_exempt)
            <div class="total-row">
                <span>IVA:</span>
                <span>Exento (Art. 20.1 Ley 37/1992)</span>
            </div>
            @else
            <div class="total-row">
                <span>IVA ({{ number_format($invoice->tax_rate ?? 0, 2) }}%):</span>
                <span>{{ number_format($invoice->tax, 2, ',', '.') }} €</span>
            </div>
            @endif
            
            @if($invoice->irpf_retention > 0)
            <div class="total-row irpf">
                <span>Retención IRPF ({{ number_format($invoice->irpf_rate, 2) }}%):</span>
                <span>-{{ number_format($invoice->irpf_retention, 2, ',', '.') }} €</span>
            </div>
            @endif
            
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>{{ number_format($invoice->total, 2, ',', '.') }} €</span>
            </div>
        </div>
        
        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes">
            <div class="notes-title">Observaciones</div>
            <div class="notes-content">{{ $invoice->notes }}</div>
        </div>
        @endif
        
        <!-- Legal Info -->
        <div class="legal-info">
            <strong>Información Legal:</strong>
            Esta factura cumple con la normativa de facturación electrónica VeriFactu de la AEAT (Agencia Estatal de Administración Tributaria).
            Los servicios de psicología clínica están exentos de IVA según el Artículo 20.1 de la Ley 37/1992 del Impuesto sobre el Valor Añadido.
            @if($invoice->is_b2b && $invoice->irpf_retention > 0)
            Se aplica retención de IRPF del {{ number_format($invoice->irpf_rate, 2) }}% según la normativa vigente para profesionales.
            @endif
        </div>
    </div>
</body>
</html>
