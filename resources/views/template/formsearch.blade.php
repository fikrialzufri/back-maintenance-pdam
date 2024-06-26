@if (!isset($item['input']))
    <input type="text" name="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
        class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
        value="{{ $hasilSearch[$item['name']] }}">
@else
    @if ($item['input'] == 'combo')
        <select name="{{ $item['name'] }}"
            class="selected2 form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            id="cmb{{ $item['name'] }}">
            <option value="">--Pilih {{ $item['alias'] }}--</option>

            @if (isset($item['value']))
                @foreach ($item['value'] as $key => $val)
                    @if (isset($val['id']))
                        <option value="{{ $val['id'] }}"
                            {{ $hasilSearch[$item['name']] == $val['id'] ? 'selected' : '' }}>
                            @if (isset($val['value']))
                                {{ ucfirst($val['value']) }}
                            @else
                                Array salah harus menggunakan value
                            @endif
                        </option>
                    @else
                        <option value="{{ $val }}"
                            {{ $hasilSearch[$item['name']] == $val ? 'selected' : '' }}>
                            {{ ucfirst($val) }}
                        </option>
                    @endif
                @endforeach
            @endif
        </select>
    @endif
    @if ($item['input'] == 'daterange')
        <input type="text" id="daterange" name="{{ $item['name'] }}" value="{{ $hasilSearch[$item['name']] }}"
            class="form-control">
    @endif
    @if (
        $item['input'] == 'text' ||
            $item['input'] == 'number' ||
            $item['input'] == 'date' ||
            $item['input'] == 'email' ||
            $item['input'] == 'password')
        <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            value="{{ $hasilSearch[$item['name']] }}">
    @endif
@endif

@push('head')
    @if ($item['input'] == 'daterange')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endif
@endpush

@push('script')
    @if ($item['input'] == 'combo')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    @endif
    <script>
        @if (isset($item['input']))
            @if ($item['input'] == 'combo')
                $(function() {
                    var cmbName = `#cmb{{ $item['name'] }}`;
                    var aliasName = `{{ $item['alias'] }}`;
                    $(cmbName).select2({
                        // placeholder: '--- Pilih ' + aliasName + ' ---',
                        width: '100%'
                    });

                });
            @endif
        @endif
    </script>
    @if ($item['input'] == 'daterange')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        @if (!$hasilSearch[$item['name']])
            <script type="text/javascript">
                $(document).ready(function() {

                    //   set date rangepicker default value null


                    $('#daterange').daterangepicker();



                    //  if daterange cancel then set default value null
                    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                    });
                })
            </script>
        @else
            <script type="text/javascript">
                $('#daterange').daterangepicker();



                //  if daterange cancel then set default value null
                $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            </script>
        @endif
    @endif
@endpush
