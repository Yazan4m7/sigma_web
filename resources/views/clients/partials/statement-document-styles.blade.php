.summary-layout,
.summary-table,
.transactions-table,
.statement-total {
    width: 100%;
    border-collapse: collapse;
}

.summary-layout {
    margin-bottom: 7mm;
}

.summary-layout td {
    padding: 0;
    vertical-align: top;
}

.summary-title {
    padding: 2.5mm 3mm;
    background: #e8f2f2;
    color: #337374;
    font-size: 10pt;
    font-weight: 700;
}

.summary-table td {
    padding: 1.4mm 3mm;
    border-bottom: 0.2mm solid #d9e3e5;
}

.summary-table td:first-child {
    font-weight: 600;
}

.summary-table td:last-child {
    font-weight: 700;
    text-align: left;
}

.summary-table .summary-total td {
    border-top: 0.5mm solid #408385;
    border-bottom: 0;
    color: #2d5f6d;
    font-size: 11pt;
}

.balance-due-value {
    font-weight: 800;
}

.transactions-table {
    page-break-inside: auto;
}

.transactions-table thead {
    display: table-header-group;
}

.transactions-table tr {
    page-break-inside: avoid;
}

.transactions-table th {
    padding: 2.2mm 2mm;
    border: 0.2mm solid #347779;
    background: #408385;
    color: #ffffff;
    font-size: 8.5pt;
    font-weight: 700;
    text-align: left;
}

.transactions-table th:nth-child(4),
.transactions-table th:nth-child(5),
.transactions-table th:nth-child(6) {
    font-weight: 800;
    text-align: left;
}

.transactions-table td {
    padding: 2mm;
    border: 0.2mm solid #d7e2e5;
    vertical-align: top;
}

.transactions-table tbody tr:nth-child(even) {
    background: #f5f9f9;
}

.transactions-table .number {
    font-weight: 700;
    white-space: nowrap;
    text-align: left;
}

.transactions-table .empty-row {
    padding: 8mm;
    color: #6b7c85;
    text-align: center;
}

.statement-total {
    margin-top: 4mm;
}

.statement-total td {
    padding: 2mm;
    border-top: 0.6mm solid #408385;
    font-size: 11pt;
    font-weight: 700;
}

.statement-total td:last-child {
    color: #2d5f6d;
    font-weight: 800;
    text-align: left;
}

.statement-total-label {
    text-align: left;
}

.statement-total-spacer {
    color: transparent;
}

.discount-note {
    margin-top: 3mm;
    color: #657780;
    font-size: 8pt;
}
