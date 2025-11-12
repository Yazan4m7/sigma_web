@extends('layouts.app' ,[ 'pageSlug' =>'Gallery Media'])

@section('content')
    <!--suppress ALL -->
    <div class="row" style="padding-left: 10px;padding-top: 10px">
        <div class="col-lg-12 col-sm-12">
            <div class="m-b-30">
                <div class="table-responsive">
                    <div class="container"><div class="row">
                            <div class="col-8"><h5 class="header-title">Gallery Media Items</h5></div>

                        <div class="col-3   "><a href="{{route('create-media')}}" style="display:block">
                            <button type="button" class="btn btn-secondary btn-lg btn-block"><i class="fa fa-plus-circle" style=""></i>  New Media</button>
                        </a></div>
                </div>

                    <div class="table-odd">
                        <div id="datatable_wrapper" class=""><div class="row"><div class="col-sm-12">
                                    <table id="datatable" class="table sunriseTable order-column  display nowrap compact cell-border dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 50.93px;">ID</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 240px;">Title</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 148.32px;">Date Added</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($media as $mediaItem)
                                            <tr role="row" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$mediaItem->id}}">
                                                <td class="sorting_1">{{$mediaItem->id}}</td>
                                                <td>{{substr($mediaItem->text,0,16) }}</td>
                                                <td>{{substr($mediaItem->created_at,0,16) }}</td>
                                            </tr>
                                            <div class="modal" tabindex="-1" role="dialog" id="actionsDialog{{$mediaItem->id}}">

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
                                                                    <source src="{{ '/media/'.$mediaItem->id . '/' .$mediaItem->id. '.mp4'}}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                            <div class="form-group row" style="justify-content: center">
                                                                <img width="70%" src="{{ '/media/'.$mediaItem->id . '/' .$mediaItem->id. '.jpg'}}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer fullBtnsWidth" >
                                                            <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">

                                                                        <div class="col-12 padding5px" >
                                                                            <a  href="{{route('edit-media',$mediaItem->id)}}">
                                                                                <button type="button" class="btn btn-warning "><i class="fa-solid fa-pen-to-square"></i>  Update</button>
                                                                            </a></div>



                                                        </div>
                                                            <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">

                                                                <div class="col-4 padding5px" >
                                                                    <a onclick="delConfirmation(event )"
                                                                            href="{{route('delete-media',$mediaItem->id)}}">
                                                                        <button type="button" class="btn btn-danger "><i class="fas fa-trash"></i>  Delete</button>
                                                                    </a></div>
                                                                <div class="col-8 padding5px" >
                                                                    <button type="button" class="btn btn-secondary " data-dismiss="modal" style="width:100%">Cancel</button>
                                                                </div>



                                                            </div>



                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div></div>
                    </div>
                </div>
            </div>
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
                "order": [[ 4, "desc" ]],
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
