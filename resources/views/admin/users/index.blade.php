@extends('layouts.admin')

@section('content')
    @can('user_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-User">
                <thead>
                    <tr>
                        <!-- 1) Actions column header -->
                        <th>{{ trans('global.actions') }}</th>
                        <!-- 2) Selection (checkbox) column header -->
                        <th width="10"></th>
                        <!-- 3) Other columns -->
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <th>{{ trans('cruds.user.fields.mobile') }}</th>
                        <th>{{ trans('cruds.user.fields.roles') }}</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                        <td></td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
            @can('user_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.users.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).data(), function(entry) {
                            return entry.id;
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}');
                            return;
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                headers: { 'x-csrf-token': _token },
                                method: 'POST',
                                url: config.url,
                                data: { ids: ids, _method: 'DELETE' }
                            }).done(function() { location.reload(); });
                        }
                    }
                };
                dtButtons.push(deleteButton);
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.users.index') }}",
                columns: [
                    // 1) Actions column (buttons)
                    { data: 'actions', name: '{{ trans('global.actions') }}', orderable: false, searchable: false, width: '150px' },
                    // 2) Selection (checkbox) column
                    { data: null, defaultContent: '', name: 'placeholder' },
                    // 3) ID column
                    { data: 'id', name: 'id' },
                    // 4) Name column
                    { data: 'name', name: 'name' },
                    // 5) Email column
                    { data: 'email', name: 'email' },
                    // 6) Mobile column
                    { data: 'mobile', name: 'mobile' },
                    // 7) Roles column
                    { data: 'roles', name: 'roles', orderable: false, searchable: false }
                ],
                // Set up the selector (checkbox) column as the second column (index 1)
                columnDefs: [
                    {
                        targets: 1,
                        orderable: false,
                        className: 'select-checkbox'
                    }
                ],
                // Configure row selection to use the second column
                select: {
                    style: 'multi',
                    selector: 'td:nth-child(2)'
                },
                orderCellsTop: true,
                // Default ordering by the ID column (index 2)
                order: [[2, 'desc']],
                pageLength: 100,
            };

            let table = $('.datatable-User').DataTable(dtOverrideGlobals);

            $('.datatable thead').on('input change', '.search', function() {
                let strict = $(this).attr('strict') || false;
                let value = strict && this.value ? "^" + this.value + "$" : this.value;

                table.column($(this).parent().index() + ":visible")
                    .search(value, strict)
                    .draw();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        .datatable-User td {
            white-space: nowrap;
        }

        .datatable-User .btn {
            margin-right: 2px;
        }
    </style>
@endsection
