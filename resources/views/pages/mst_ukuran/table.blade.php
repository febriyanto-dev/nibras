@if(count($datatable) > 0)

<div class="row">
    <div class="col-log-12">
        <div class="table-responsive">
            <table class="table table-bordered table-xs table-striped table-hover table-data">
                <thead class="text-uppercase">
                    <tr>
                        <th>No</th>
                        <th>Ukuran</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @php $no = (($datatable->currentpage()-1) * $datatable->perpage()) + 1; @endphp

                    @foreach($datatable as $data)

                        <tr>
                            <td class="text-center" width="8%">{{ $no }}</td>
                            <td class="">{{ $data->ukuran }}</td>
                            <td class="text-center" width="20%">
                                <a class="btn btn-link" href="javascript:void(0);" data-action="edit" data-id="{{Common::hashids()->encode($data->kode_ukuran)}}" onclick="_page._event.form(this);" role="button">Edit</a>|
                                <a class="btn btn-link text-danger" href="javascript:void(0);" data-action="delete" data-name="{{$data->ukuran}}" data-id="{{Common::hashids()->encode($data->kode_ukuran)}}" onclick="_page._event.form(this);" role="button">Delete</a>
                            </td>
                        </tr>

                    @php $no++ @endphp

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xs-12 text-center">
        <nav aria-label="Page navigation">
            {{ $datatable->links() }}
        </nav>
    </div>
</div>

@else

<div class="row">
    <div class="col-md-12 ">
        <div class="alert bg-danger table-data alert-dismissible text-center" role="alert">
            <strong>DATA NOT FOUND</strong>
        </div>
    </div>
</div>

@endif
