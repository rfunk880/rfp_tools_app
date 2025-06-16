<form method="GET">
    <div class="row">
        <div class="col-md-2 mb-2">
            <label for="keyword">Keyword</label>
            <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Search" />
        </div>

        <div class="col-md-2 mb-2">
            <label for="proejct_id">Proejct ID</label>
            <select id="proejct_id" class="form-select" name="proejct_id">
                <option value="">All</option>
                {!! OptionsView(\App\Models\Project::select('id', 'pn')->get(), 'id', 'pn', request('proejct_id')) !!}
            </select>
        </div>

        <div class="col-md-2 mb-2">
            <label for="type">Type</label>
            <select id="type" class="form-select" name="type">
                    <option value="All">Select</option>
                    {!! arrayOptions(\App\Models\Message::$typeLabel, request('type')) !!}
            </select>
        </div>

        <div class="col-md-2 mb-2">
            <label for="sender_id">Sender</label>
            <select id="sender_id" class="form-select" name="sender_id">
                <option value="">All</option>
                {!! OptionsView(\App\Models\User::select('id', 'name')->get(), 'id', 'name', request('sender_id')) !!}
            </select>
        </div>
       
        <div class="col-md-2 mb-2">
            <label for="receiver_id">Receiver</label>
            <select id="receiver_id" class="form-select" name="receiver_id">
                <option value="">All</option>
                {!! OptionsView(\App\Models\Contact::select('id', 'name')->get(), 'id', 'name', request('receiver_id')) !!}
            </select>
        </div>
         
        <div class="col-md-2 mb-2">
            <label for="date_from">Date From</label>
            <input id="date_from" type="text" class="form-control dp"  name="date_from" value="{{ request('date_from')}}">
        </div>
        
        <div class="col-md-2 mb-2">
            <label for="date_to">Date To</label>
            <input id="date_to" type="text" class="form-control dp" name="date_to" value="{{ request('date_to')}}">
        </div>
        
         <div class="col-md-2 mb-2">
            <input type="submit" class="btn btn-dark" value="Submit" style="margin-top:1rem;" />
            <a href="{{ url()->current() }}" class="btn btn-secondary" style="margin-top:1rem;">Reset</a>
        </div>
        
    </div>
</form>
