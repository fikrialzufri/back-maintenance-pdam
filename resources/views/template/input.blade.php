<div class="form-group form-textinput" id="form_{{ $item['name'] }}">

    @if (isset($item['input']) && $item['input'] !== 'hidden')
        <div>
            <label for="{{ $item['name'] }}" class=" form-control-label">{{ $item['alias'] }}</label>
        </div>
    @endif
    @if (!isset($item['input']))
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @else
        @if ($item['input'] == 'hidden')
            <input type="hidden" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
                @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ isset($item['value']) ? $item['value'] : '' }}" @endif>
        @endif
        @if ($item['input'] == 'combo')
            <select class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }} selected2"
                @if (isset($item['multiple'])) name="{{ $item['name'] }}[]" multiple @else name="{{ $item['name'] }}" @endif
                id="cmb{{ $item['name'] }}">
                <option value="">--Pilih {{ $item['alias'] }}--</option>
                @if (isset($item['value']))

                    @foreach ($item['value'] as $key => $val)
                        @if (isset($val['id']))
                            <option value="{{ $val['id'] }}"
                                @if ($store == 'update') @if (gettype($data[$item['name']]) == 'array')
                                         {{ in_array($val['id'], $data[$item['name']]) ? 'selected' : '' }}
                                        @else
                                        @if (is_array($data[$item['name'] . 'id']))
                                        {{ in_array($val['id'], $data[$item['name'] . 'id']) ? 'selected' : '' }}
                                        @else
                                        {{ $data[$item['name']] == $val['id'] ? 'selected' : '' }} @endif
                                @endif
                            @else
                                {{ old($item['name']) == $val['id'] ? 'selected' : '' }}
                        @endif>
                        @if (isset($val['value']))
                            {{ ucfirst($val['value']) }}
                        @else
                            Array salah harus menggunakan value
                        @endif
                        </option>
                    @else
                        <option value="{{ $val }}"
                            @if ($store == 'update') @if (isset($item['array']))
                                    {{ in_array($val, $data[$item['name']]) ? 'selected' : '' }}
                                    @else
                                    {{ $data[$item['name']] == $val ? 'selected' : '' }} @endif
                        @else {{ old($item['name']) == $val ? 'selected' : '' }} @endif>
                            {{ ucfirst($val) }}
                        </option>
                    @endif
                    @if (isset($item['array']))
                        @if ($store == 'update')
                            <option value="{{ $val }}">
                                {{ $item['name'] }}
                                {{ implode(' ', $data[$item['name']]) == $val ? 'jhabuk' : 'kada' }}

                            </option>

                        @endif
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

    @if ($item['input'] == 'warna')
        {{ old($item['name']) }}
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            id="text-field" class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }} warna"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) === old($item['name']) ? $item['default'] : old($item['name']) }}" @endif>


    @endif
    @if ($item['input'] == 'radio')
        <div class="form-radio">
            @foreach ($item['value'] as $key => $val)
                <div class="radio radiofill radio-inline">
                    <label>
                        <input type="radio" name="{{ $item['name'] }}" value="{{ $val }}"
                            @if ($store == 'update') {{ $data[$item['name']] == $val ? 'checked' : '' }} @else {{ old($item['name']) == $val ? 'checked' : '' }} {{ $item['default'] == $val ? 'checked' : '' }} @endif>
                        <i class="helper"></i>{{ ucfirst($val) }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif
    @if ($item['input'] == 'persen')
        <div class="form-group row">
            <div class="col-sm-3">
                <div class="input-group ">
                    <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" class="form-control"
                        @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
                        placeholder="Isi {{ $item['name'] }}">
                    <span class="input-group-append">
                        <label class="input-group-text">%</label>
                    </span>
                </div>
            </div>
        </div>
    @endif
    @if ($item['input'] == 'nominal')
        <input type="text" name="{{ $item['name'] }}" id="{{ $item['name'] }}" placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @endif
    @if ($item['input'] == 'datetimepicker')
        <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}">
    @endif
    @if ($item['input'] == 'year')
        <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}">
    @endif
    @if ($item['input'] == 'date')
        <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
            placeholder="{{ $item['alias'] }}"
            class="form-control {{ $errors->has($item['name']) ? 'is-invalid' : '' }}"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>
    @endif
    @if ($item['input'] == 'image')
        <input type="file" value="{{ old($item['name']) }}" name="{{ $item['name'] }}"
            placeholder="{{ $item['alias'] }}" id="{{ $item['name'] }}" class="form-control"
            @if ($store == 'update') value="{{ $data[$item['name']] }}" @else value="{{ old($item['name']) }}" @endif>

        <br>
        @if ($store == 'update')
            <img class="img-profile img-responsive" id="img_{{ $item['name'] }}" width="20%"
                @if ($data[$item['name']] == null) src=""
                        @else
                        src="{{ asset('storage/' . $route . '/thumbnail/' . $data[$item['name']]) }}" @endif>

        @endif
        <div id="preview{{ $item['name'] }}"></div>

    @endif
    @if ($item['input'] == 'textarea')
        <textarea class="form-control" rows="3" placeholder="{{ $item['alias'] }}" name="{{ $item['name'] }}">
@if ($store == 'update'){{ $data[$item['name']] }}@else{{ old($item['name']) }}@endif
</textarea>
    @endif

    @if (
        $item['input'] == 'text' ||
            $item['input'] == 'number' ||
            $item['input'] == 'email' ||
            $item['input'] == 'password' ||
            $item['input'] == 'time')
        <div>
            <input type="{{ $item['input'] }}" name="{{ $item['name'] }}" id="{{ $item['name'] }}"
                @if (isset($item['required'])) {{ $item['required'] === true ? 'required' : '' }} @endif
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


@push('head')
    @if (isset($item['input']))
        @if ($item['input'] == 'datetimepicker' || $item['input'] == 'year')
            <link rel="stylesheet" type="text/css"
                href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        @endif
    @endif
    @if (isset($item['input']))
        @if ($item['input'] == 'warna')
            <link rel="stylesheet"
                href="{{ asset('plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
            <link rel="stylesheet" href="{{ asset('plugins/jquery-minicolors/jquery.minicolors.css') }}">
            <link rel="stylesheet" href="{{ asset('plugins/datedropper/datedropper.min.css') }}">
        @endif
    @endif
@endpush

@push('scriptdinamis')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>

    @if (isset($item['input']))
        @if ($item['input'] == 'warna')
            <script src="{{ asset('plugins/moment/moment.js') }}"></script>
            <script src="{{ asset('plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}"></script>
            <script src="{{ asset('plugins/jquery-minicolors/jquery.minicolors.min.js') }}"></script>

            <script>
                $('.warna').each(function() {
                    //
                    // Dear reader, it's actually very easy to initialize MiniColors. For example:
                    //
                    //  $(selector).minicolors();
                    //
                    // The way I've done it below is just for the demo, so don't get confused
                    // by it. Also, data- attributes aren't supported at this time...they're
                    // only used for this demo.
                    //
                    $(this).minicolors({
                        control: $(this).attr('data-control') || 'hue',
                        defaultValue: $(this).attr('data-defaultValue') || '',
                        format: $(this).attr('data-format') || 'hex',
                        keywords: $(this).attr('data-keywords') || '',
                        inline: $(this).attr('data-inline') === 'true',
                        letterCase: $(this).attr('data-letterCase') || 'lowercase',
                        opacity: $(this).attr('data-opacity'),
                        position: $(this).attr('data-position') || 'bottom left',
                        swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                        change: function(value, opacity) {
                            if (!value) return;
                            if (opacity) value += ', ' + opacity;
                        },
                        theme: 'bootstrap'
                    });

                });
            </script>
        @endif
    @endif
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
                    function formatRupiah(angka, prefix) {
                        var number_string = angka.replace(/[^,\d]/g, '').toString(),
                            split = number_string.split(','),
                            sisa = split[0].length % 3,
                            rupiah = split[0].substr(0, sisa),
                            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                        // tambahkan titik jika yang di input sudah menjadi angka ribuan
                        if (ribuan) {
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
                    if ($("input:radio[name='{{ $item['name'] }}']").is(":checked")) {
                        $("#{{ $item['name'] }}").removeClass("is-invalid");
                        $("#text{{ $item['name'] }}").html("");
                    }
                @endif
                @if ($item['input'] == 'nominal')
                    $("#{{ $item['name'] }}").keypress(function(e) {
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                            return false;
                        }
                    });
                @endif
            @endif
        });
    </script>
    @if (isset($item['input']))
        @if ($item['input'] == 'datetimepicker')
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            @if ($store == 'update')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#{{ $item['name'] }}").daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerIncrement: 1,
                            timePickerSeconds: true,
                            locale: {
                                format: 'DD/M/Y HH:mm:ss'
                            }
                        })
                    })
                </script>
            @else
                <script type="text/javascript">
                    $(document).ready(function() {
                        let start = moment().startOf('month')
                        let end = moment().endOf('month')
                        $("#{{ $item['name'] }}").daterangepicker({
                            startDate: start,
                            endDate: end,
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerIncrement: 1,
                            timePickerSeconds: true,
                            minDate: start,
                            locale: {
                                format: 'DD/M/Y HH:mm:ss'
                            }
                        })
                    })
                </script>
            @endif
        @endif
        @if ($item['input'] == 'year')
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css"
                rel="stylesheet" />

            @if ($store == 'update')
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#{{ $item['name'] }}").datepicker({
                            format: "yyyy",
                            viewMode: "years",
                            minViewMode: "years"
                        })
                    })
                </script>
            @else
                <script type="text/javascript">
                    $(document).ready(function() {

                        $("#{{ $item['name'] }}").datepicker({
                            format: " yyyy", // Notice the Extra space at the beginning
                            viewMode: "years",
                            minViewMode: "years"

                        })
                    })
                </script>
            @endif
        @endif
        @if ($item['input'] == 'image')
            <script>
                function readURL(input, id) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        // console.log("{{ $item['name'] }}");

                        reader.onload = function(e) {
                            $("#preview" + id).html("<img src='" + e.target.result +
                                "' width='310' id='image_{{ $item['name'] }}'>");
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#{{ $item['name'] }}").change(function() {
                    readURL(this, "{{ $item['name'] }}");
                    console.log("{{ $item['name'] }}");
                    $('#img_{{ $item['name'] }}').remove();
                });

                $('.close').on('click', function() {
                    // console.log("{{ $item['name'] }}");
                    $('#image_{{ $item['name'] }}').remove();
                });
            </script>
        @endif
    @endif
@endpush
