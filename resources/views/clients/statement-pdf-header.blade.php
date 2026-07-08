@php
    $fromLabel = \Carbon\Carbon::parse($from)->format('d M Y');
    $toLabel = \Carbon\Carbon::parse($to)->format('d M Y');
@endphp
<table width="100%" style="border-collapse: collapse; border-bottom: 0.35mm solid #d8e6e7; font-family: cairo, sans-serif;">
    <tr>
        <td width="10%" style="padding-bottom: 3mm; vertical-align: middle;">
            @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" style="width: 16mm; height: auto;" alt="SIGMA">
            @endif
        </td>
        <td width="64%" style="padding-bottom: 3mm; vertical-align: middle;">
            <div style="color: #2d5f6d; font-size: 13pt; font-weight: 700; line-height: 1.25;">Statement of Account</div>
            <div style="color: #25343b; font-size: 9.5pt; font-weight: 700; line-height: 1.35;">Dr. {{ $client->name }}</div>
            <div style="margin-top: 0.7mm; color: #6b7c85; direction: ltr; font-size: 8pt; line-height: 1.35; unicode-bidi: embed;">{{ $fromLabel }} to {{ $toLabel }}</div>
        </td>
        <td width="26%" style="padding-bottom: 3mm; color: #6b7c85; font-size: 8.5pt; text-align: right; vertical-align: middle;">
            Page {PAGENO} of {nbpg}
        </td>
    </tr>
</table>
