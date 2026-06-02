@extends('layouts.app' ,[ 'pageSlug' =>'Gallery Media'])

@section('content')
    <style>
        .media-config-page .media-page-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 0 0 24px;
        }

        .media-config-page .sigma-config-table.sunriseTable tbody tr:nth-child(odd),
        .media-config-page .sigma-config-table.sunriseTable tbody tr:nth-child(even) {
            background-color: #ffffff !important;
        }

        .media-config-page .sigma-config-table.sunriseTable tbody td {
            padding: 2px 16px !important;
        }

        .media-config-page table#datatable.sigma-config-table thead th:first-child,
        .media-config-page table#datatable.sigma-config-table thead th:last-child {
            background: #408385 !important;
            color: #ffffff !important;
        }

        .media-config-page .sigma-config-table .btn.btn-sm {
            padding: 0.1rem 0.45rem !important;
            line-height: 1.1 !important;
            font-size: 11.5px !important;
            min-height: auto !important;
        }
    </style>
    <div class="sigma-config-page sigma-list-page media-config-page">
        <div class="media-page-actions">
            <a href="{{route('create-media')}}" class="btn sigma-config-add-btn">
                <i class="fa fa-plus-circle"></i> New Media
            </a>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table id="datatable" class="table sunriseTable order-column display nowrap compact cell-border dataTable no-footer sigma-list-table sigma-config-table" role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr role="row"><th class="sorting_asc sigma-cell-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 50.93px;">ID</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 240px;">Title</th>
                                            <th class="sorting sigma-cell-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 148.32px;">Date Added</th>
                                            <th class="no-sort sigma-cell-center" rowspan="1" colspan="1" aria-label="Actions" style="width: 100px;">Actions</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($media as $mediaItem)
                                            <tr role="row" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$mediaItem->id}}">
                                                <td class="sorting_1 sigma-cell-center">{{$mediaItem->id}}</td>
                                                <td>{{substr($mediaItem->text,0,16) }}</td>
                                                <td class="sigma-cell-center">{{substr($mediaItem->created_at,0,16) }}</td>
                                                <td class="sigma-cell-center" onclick="event.stopPropagation();">
                                                    <a href="{{route('edit-media',$mediaItem->id)}}" class="btn btn-sm btn-info">Edit</a>
                                                    <form action="{{route('delete-media',$mediaItem->id)}}" method="POST" style="display:inline-block;">
                                                        @csrf

                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this media item?');">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <div class="modal sigma-modal--media-actions" tabindex="-1" role="dialog" id="actionsDialog{{$mediaItem->id}}">

                                                <input type="hidden" name="media_id" value="{{$mediaItem->id}}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Gallery Media #{{$mediaItem->id}}</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="form-group row" style="margin-bottom: 0px">
                                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                    <label for="doctor">Title: </label>
                                                                    <h5 id="doctor"><b>{{$mediaItem->text}}</b></h5>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="form-group row" style="justify-content: center">
                                                                <video width="85%"  controls>
                                                                    <source src="{{ '/gallery/'.$mediaItem->id . '/' .'video.mp4'}}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                            <div class="form-group row" style="justify-content: center">
                                                                <img width="70%" src="{{ '/gallery/'.$mediaItem->id . '/' .'thumbnail.jpg'}}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer fullBtnsWidth" >
                                                            <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%">
                                                                <div class="col-6 padding5px">
                                                                    <a href="{{route('edit-media',$mediaItem->id)}}" style="width:100%; display:block;">
                                                                        <button type="button" class="btn btn-warning" style="width:100%"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                                                    </a>
                                                                </div>
                                                                <div class="col-6 padding5px">
                                                                    <form action="{{route('delete-media',$mediaItem->id)}}" method="POST" style="width:100%;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger" style="width:100%" onclick="return confirm('Are you sure you want to delete this media item?');">
                                                                            <i class="fas fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%; margin-top: 10px;">
                                                                <div class="col-12 padding5px">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                        </tbody>
            </table>
        </div>
    </div>

@endsection

@push('js')

<!-- Responsive and datatable js -->
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable(
            {
                "pageLength": 25,
                "searching": false,
                "lengthChange": false,
                "order": [[ 2, "desc" ]],
            }
        );
    } );
    function delConfirmation(ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
  swal.fire({
            title: "You sure You want to delete this media item ? ",
            text: "You will not be able to restore media's video and image!",
            icon: "warning",
            showDenyButton: true,
            confirmButtonText: 'Delete',
            denyButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = urlToRedirect;
            } else if (result.isDenied) {
                swal.fire("Media NOT deleted.");
    }
    });

    }
</script>
@endpush
