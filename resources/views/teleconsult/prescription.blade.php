<div class="table-responsive">
    <table class="table table-striped table-hover">
        <tr class="bg-black">
            <th>Prescription Code</th>
            <th>Medicine Type</th>
            <th>Drug Code</th>
            <th>Frequency</th>
            <th>Dose Regimen</th>
            <th>Quantity</th>
        </tr>
        @foreach($prescription as $row)
            <tr>
                <td style="white-space: nowrap;">
                    <b class="text-warning" style="cursor: pointer;">
                        <a>
                            {{ $row->presc_code }}
                        </a>
                    </b>
                </td>
                <td>
                    <b>{{ $row->type_med() }}</b>
                </td>
                <td>
                    <b>{{ $row->drugmed->drugcode }}</b>
                </td>
                <td>
                    <b>{{ $row->freq() }}</b>
                </td>
                <td>
                    <b>{{ $row->dose_reg() }}</b>
                </td>
                 <td>
                    <b>{{ $row->total_qty }}</b>
                </td>
            </tr>
        @endforeach
    </table>
</div>