@extends('layouts.app')


@section('content')

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          @include('layouts.includes.company_menu')
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            
            <div class="tab-content">
              <div class="box-body box-profile">
                @if (!empty($userData->picture))
                  <img alt="User profile picture" src='{{url("public/uploads/userPic/$userData->picture")}}' class="profile-user-img img-responsive img-circle asa">
                @else
                  <img alt="User profile picture" src='{{url("public/uploads/userPic/avatar.jpg")}}' class="profile-user-img img-responsive img-circle asa">
                @endif  
                  <h3 class="profile-username text-center">{{$userData->real_name}}</h3>


                  <a class="btn btn-primary btn-block" href='{{url("change-password/$userData->id")}}'><b>{{ trans('message.form.change_password') }}</b></a>
                </div>
              <div>
                <form action='{{ url("update-user/$userData->id") }}' method="post" class="form-horizontal" enctype="multipart/form-data">
                  {!! csrf_field() !!}
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.form.full_name') }}</label>

                    <div class="col-sm-10">
                      <input type="text" value="{{$userData->real_name}}" class="form-control valdation_check" id="fname" name="real_name">
                    <span id="val_fname" style="color: red"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputEmail">{{ trans('message.table.email') }}</label>

                    <div class="col-sm-10">
                      <input type="email" value="{{$userData->email}}" class="form-control valdation_check" id="em" name="email" readonly>
                    <span id="val_em" style="color: red"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputName">{{ trans('message.table.phone') }}</label>

                    <div class="col-sm-10">
                      <input type="text" value="{{$userData->phone}}" class="form-control valdation_check" id="name" name="phone">
                    <span id="val_name" style="color: red"></span>
                    </div>
                  </div>
                  @if (Auth::user()->role_id == 1)
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="inputExperience">{{ trans('message.sidebar.role') }}</label>

                    <div class="col-sm-10">
                      <select class="form-control" name="role_id" required id="kk">
                    <option value="">----Select One----</option>
                    @foreach ($roleData as $data)
                      <option value="{{$data->id}}" <?=isset($data->id) && $data->id == $userData->role_id ? 'selected':""?> >{{$data->role}}</option>
                    @endforeach
                    </select>
                    </div>
                  </div>
                  @endif

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">{{ trans('message.form.picture') }}</label>

                    <div class="col-sm-10">
                      <input type="file" name="picture" class="form-control input-file-field" onchange="previewFile()">
                      <input type="hidden" name="pic" value="{{ $userData->picture ? $userData->picture : 'NULL' }}">
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button class="btn btn-primary btn-flat" type="submit">{{ trans('message.form.update') }}</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection