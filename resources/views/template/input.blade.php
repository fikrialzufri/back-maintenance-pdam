<div class="form-group form-textinput" id="form_{{ $item['name'] }}">

    <div>
        <label for="{{ $item['name'] }}" class=" form-control-label">{{ $item['alias'] }}</label>
    </div>

    @if (!isset($item['input']))
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @else
        @if ($item['input'] == 'combo')
            {{-- @if (isset($hasValue))
                @if ($hasValue . '_id' == $item['name'])
                    TODO: WORK ON THIS TOMORROW
                    <h1>{{ $hasValue . '_id' }}</h1>
                @else --}}
            <select class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }} selected2"
                @if (isset($item['multiple'])) name="{{ $item['name'] }}[]" multiple @else name="{{ $item['name'] }}" @endif
                id="cmb{{ $item['name'] }}">
                <option value="">--Pilih {{ $item['alias'] }}--</option>
                @if (isset($item['value']))
                    @foreach ($item['value'] as $key => $val)
                        @if (isset($val['id']))
                            <option value="{{ $val['id'] }}"
                                @if ($store == 'update') @if (is_array($data[$item['name'] . 'id']))
                                {{ in_array($val['id'], $data[$item['name'] . 'id']) ? 'selected' : '' }}
                                @else {{ $data[$item['name']] == $val['id'] ? 'selected' : '' }} @endif
                            @else {{ old($item['name']) == $val['id'] ? 'selected' : '' }} @endif>
                                @if (isset($val['value']))
                                    {{ ucfirst($val['value']) }}
                                @else
                                    Array salah harus menggunakan value
                                @endif
                            </option>
                        @else
                            <option value="{{ $val }}"
                                @if ($store == 'update') {{ $data[$item['name']] == $val ? 'selected' : '' }} @else {{ old($item['name']) == $val ? 'selected' : '' }} @endif>
                                {{ ucfirst($val) }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>
            {{-- @endif
            @endif --}}

        @endif
        @if ($item['input'] == 'rupiah')
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">Rp.</div>
                </div>
                <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                    placeholder="{{ $item['alias'] }}"
                    class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                    @if ($store == 'update') value="{{ format_uang($data[$item['name']]) }}" @else value="{{ old($item['name']) }}" @endif>
            </div>


        @endif
        @if ($item['input'] == 'radio')
            <div class="form-radio">
                @foreach ($item['value'] as $key => $val)
                    <div class="radio radiofill radio-inline">
                        <label>
                            <input type="radio" name="{{ $item['name'] }}" value="{{ $val }}"
                                @if ($store == 'update') {{ $data[$item['name']] == $val ? 'checked' : '' }} @else {{ old($item['name']) == $val ? 'checked' : '' }} @endif>
                            <i class="helper"></i>{{ ucfirst($val) }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
        @if ($item['input'] == 'date')
            <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                @if ($item['input'] == 'password') autocomplete="on" @else placeholder="{{ $item['alias'] }}" @endif
                class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
        @endif
        @if ($item['input'] == 'textarea')
            <textarea class="form-control" rows="3" placeholder="{{ $item['alias'] }}" name="{{ $item['name'] }}">
@if ($store == 'update'){{ $data[$item['name']] }}
@else
@endif
</textarea>
        @endif

        @if ($item['input'] == 'text' || $item['input'] == 'number' || $item['input'] == 'email' || $item['input'] == 'password' || $item['input'] == 'time')
            <div>
                <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                    @if ($item['input'] == 'password') autocomplete="on" @else placeholder="{{ $item['alias'] }}" @endif
                    class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                    @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
            </div>
        @endif
    @endif

    @if ($errors->has($item['name']))
        <span class="text-danger text-capitalize">
            <strong id="text{{ $item['name'] }}">
                @if (isset($item['alias']))
                    {{ $item['alias'] }} {{ str_replace('_id', '', $errors->first($item['name'])) }}
                @else
                    {{ str_replace('_id', '', $errors->first($item['name'])) }}
                @endif
            </strong>
        </span>
    @endif
</div>
@push('style')
    <style type="text/css">


    </style>
@endpush
@push('scriptdinamis')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}">
    </script>
    <script type="text/javascript">
        $(function() {
            @if (isset($item['input']))
                @if ($item['input'] == 'combo')
                    $("#cmb{{ $item['name'] }}").select2({
                    placeholder: '--- Pilih ' + "{{ $item['alias'] }}" + ' ---',
                    width: '100%'
                    });
                    $("#cmb{{ $item['name'] }}").on("change", function(e) {
                    $("#{{ $item['name'] }}").removeClass("is-invalid");
                    $("#text{{ $item['name'] }}").html("");
                    });
                @endif
            @endif
            @if (isset($item['input']))
                @if ($item['input'] == 'rupiah')
                    let rupiah = document.getElementsByClassName('rupiah');
                    $('#{{ $item['name'] }}').on("input", function() {

                    let val = formatRupiah(this.value, '');
                    $('#{{ $item['name'] }}').val(val);
                    });

                    /* Fungsi formatRupiah */
                    function formatRupiah(angka, prefix){
                    var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                    if(ribuan){
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
                    }
                @endif
            @endif

            $("#{{ $item['name'] }}").keypress(function() {

                $("#{{ $item['name'] }}").removeClass("is-invalid");
                $("#text{{ $item['name'] }}").html("");
            });
            $("#{{ $item['name'] }}").change(function() {
                $("#{{ $item['name'] }}").removeClass("is-invalid");
                $("#text{{ $item['name'] }}").html("");
            });
            @if (isset($item['input']))
                @if ($item['input'] == 'radio')
                    if($("input:radio[name='{{ $item['name'] }}']").is(":checked")) {
                    $("#{{ $item['name'] }}").removeClass("is-invalid");
                    $("#text{{ $item['name'] }}").html("");
                    }
                @endif
            @endif
        });
    </script>
@endpush
