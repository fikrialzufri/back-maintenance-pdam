@php
$segment1 = request()->segment(1);
$segment2 = request()->segment(2);
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-award bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#"><i class="ik ik-home"></i></a>
                        </li>
                        @can('view-' . $segment1)
                            <li class="breadcrumb-item">
                                <a href="{{ url('/' . $segment1) }}">{{ ucwords($segment1) }}</a>
                            </li>
                        @endcan
                        @if ($segment2)
                            <li class="breadcrumb-item">
                                <a href="#">{{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}</a>
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @if (session('message'))
        <div class="row" id="#success-alert">
            <div class=" container-fluid alert alert-{{ session('Class') }} alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
</div>
