@if (!isset($item['input']))
    <input type="text" name="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
        class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}" value="{{ old($item['name']) }}">
@else
    @if ($item['input'] == 'combo')
        <select name="{{ $item['name'] }}"
            class="selected2 form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            id="cmb{{ $item['name'] }}">
            <option value="">--Pilih {{ $item['alias'] }}--</option>
            @if (isset($item['value']))
                @foreach ($item['value'] as $key => $val)
                    @if (isset($val['id']))
                        <option value="">--Pilih {{ $item['alias'] }}--</option>
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
    @if ($item['input'] == 'text' || $item['input'] == 'number' || $item['input'] == 'date' || $item['input'] == 'email' || $item['input'] == 'password')
        <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}" value="">
    @endif
@endif

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        @if (isset($item['input']))
            @if ($item['input'] == 'combo')
                $(function() {
                var cmbName = `#cmb{{ $item['name'] }}`;
                var aliasName = `{{ $item['alias'] }}`;
                $(cmbName).select2({
                placeholder: '--- Pilih ' + aliasName + ' ---',
                width: '100%'
                });

                });
            @endif
        @endif
    </script>
@endpush
