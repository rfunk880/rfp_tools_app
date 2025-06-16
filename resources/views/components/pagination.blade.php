<div class="d-flex justify-content-between align-items-center w-full">
    {!! $data->appends(array_merge(request()->except('page'), ['_' => '_']))->links() !!}

    <div class="ml-auto ms-auto d-flex align-items-center">
        @if(!isset($hideInfo))
        <span>Showing <strong>{{ ($data->currentPage() - 1) * $data->perPage() + 1 }} - {{ ($data->currentPage()-1) * $data->perPage() + $data->count() }}</strong> of total <strong>{{ $data->total() }}</strong> records.</span> 
        @endif
        @if(isset($perPage))
        <div class="d-flex align-items-center ms-2 ml-2">
            <label>Per Page:</label>
            <select class="form-select perPageSelector" style="width:80px;" data-form="{{ @$perPageForm ? $perPageForm : '.per-page-form'}}">
                @foreach([10, 20, 50] as $option)
                <option value="{{ $option }}" {!! isSelected($option, $perPage) !!}>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>
</div>
