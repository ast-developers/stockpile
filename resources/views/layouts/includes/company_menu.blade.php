<!-- Profile Image -->
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{{trans('message.header.company_setting')}}</h3>
  </div>
  <div class="box-body no-padding" style="display: block;">
    <ul class="nav nav-pills nav-stacked">
      @if(!empty(Session::get('companysetting')))
      <li {{ isset($list_menu) &&  $list_menu == 'sys_company' ? 'class=active' : ''}}><a href="{{ URL::to("company/setting")}}">{{ trans('message.table.company_setting') }}</a></li>
      @endif
      
      <li {{ isset($list_menu) &&  $list_menu == 'users' ? 'class=active' : ''}}><a href="{{ URL::to("users")}}">{{ trans('message.extra_text.team_member') }}</a></li>
      
      @if(!empty(Session::get('user_role')))
        <li {{ isset($list_menu) &&  $list_menu == 'role' ? 'class=active' : ''}}><a href="{{ URL::to("user-role")}}">{{ trans('message.extra_text.user_role') }}</a></li>
      @endif
      <li {{ isset($list_menu) &&  $list_menu == 'location' ? 'class=active' : ''}}><a href="{{ URL::to("location")}}">{{ trans('message.extra_text.locations') }}</a></li>
      
    </ul>
  </div>
  <!-- /.box-body -->
</div>