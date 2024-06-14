<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tasks
        </h2>
    </x-slot>

    <div class="grid grid-rows-2 grid-cols-1 px-40 pt-10">
        <div>
            <x-primary-button 
                x-data=""
                x-on:click="$dispatch('open-modal', 'new-task')">
                    New Task
            </x-primary-button>
            <x-modal name="new-task" focusable>
                <form action="{{ route('task.create') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-y-14 grid-rows-2 grid-cols-1 m-5">
                        <div class="">
                            <x-input-label for="t_title" value="Title" />
                            <x-text-input id="t_title" name="t_title" type="text" required max="100" class="w-full"></x-text-input>

                            <x-input-label for="t_content" value="Content" />
                            <x-text-input id="t_content" name="t_content" type="text"  class="w-full"></x-text-input>

                            <x-input-label for="t_status" value="Content" />
                            <select name="t_status" id="t_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="2" selected>To-do</option>
                                <option value="1">In Progress</option>
                                <option value="0">Done</option>
                            </select>

                            <x-input-label for="t_file" value="File" />
                            <x-text-input id="t_file" name="t_file" type="file"  class="w-full"></x-text-input>

                            <x-input-label class="inline-flex" for="r_draft" value="Draft" />
                            <input class="inline-flex" type="radio" value="1" name="t_is_published" id="r_draft">

                            <x-input-label class="inline-flex" for="r_published" value="Published" />
                            <input class="inline-flex" type="radio" value="0" name="t_is_published" id="r_published">
                        </div>
                        <div>
                            <button class="focus:outline-none text-white uppercase bg-green-500 hover:bg-green-600 text-sm px-4 py-2 rounded-md font-semibold text-xs" type="submit">Create</button>
                        </div>
                    </div>
                </form>
            </x-modal>
        </div>
        

        <div>
            <table id="tbl_tasks" class="table-auto">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Published</th>
                    </tr>
                </thead>
            </table>
        </div>
        
    </div>

    <script>
        $(document).ready(function() {
            if($.fn.DataTable.isDataTable('#tbl_tasks')) {
                $('#tbl_tasks').DataTable().destroy();
            }
            
            // get tasks data thru ajax and display data table
            $('#tbl_tasks').DataTable({
                ajax: {
                    url: "{{ route('task.all') }}",
                    type: 'get',
                    dataSrc: ""
                },
                columns: [
                    { data: 't_title' },
                    { data: 't_content' },
                    { data: function(result) {
                            // save status on change
                            let html = '<select name="t_status" id="t_status_'+ result.t_id +'" onchange="edit('+ result.t_id +', \'status\')" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">\
                                    <option value="2" '+ (result.t_status == 2 ? 'selected' : '') +'>To-do</option>\
                                    <option value="1" '+ (result.t_status == 1 ? 'selected' : '') +'>In Progress</option>\
                                    <option value="0" '+ (result.t_status == 0 ? 'selected' : '') +'>Done</option>\
                                </select>';

                            return html;
                        } 
                    },
                    { data: function(result) {
                            // link to file
                            let storage_path = "{{ asset('files') }}";
                            let html = '';
                            if(result.t_file != null && result.t_file != '') {
                                html = '<a href="'+ storage_path + '/' + result.t_file +'">View file</a>';
                            }

                            return html;
                        } 
                    },
                    { data: function(result) {
                            // save published status on change
                            let html = '<select name="t_is_published" id="t_is_published_'+ result.t_id +'" onchange="edit('+ result.t_id +', \'is_published\')" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">\
                                    <option value="1" '+ (result.t_is_published == 1 ? 'selected' : '') +'>Draft</option>\
                                    <option value="0" '+ (result.t_is_published == 0 ? 'selected' : '') +'>Published</option>\
                                </select>';

                            return html;
                        } 
                    },
                ]
            }); 
        });

        function edit(id, column) {
            let element_id = '#t_' + column + '_' + id;
            console.log(element_id);
            let value = $(element_id).val();
            if(value == undefined)
                value = null;
            $.ajax({
                url: "{{ route('task.update') }}",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': id,
                    'column': column,
                    'value': value
                },
                success: function() {
                    location.reload();
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
    </script>
</x-app-layout>